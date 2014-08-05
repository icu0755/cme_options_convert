<?php


namespace Cme;


class Report
{
    protected $fileext = '.csv';
    protected $filename;
    protected $handle;
    protected $strikes;
    protected $symbol;
    protected $minInterest;

    function __construct($symbol)
    {
        $this->symbol = trim($symbol);
        $filename = $this->symbol;
        if ($this->fileext !== substr($this->symbol, -4)) $filename .= $this->fileext;
        $this->filename = $filename;
        $this->strikes = array();
    }

    public function addStrike($type, \Cme\MarketData\Strike $strikeObj){
        $strike = $strikeObj->getStrike();
        $interest = $strikeObj->getInterest();
        if (!isset($this->strikes[$strike])) {
            $this->strikes[$strike] = array('call' => 0, 'put' => 0);
        }

        $this->strikes[$strike][$type] = $interest;
    }

    public function getAverage()
    {
        $sum = $count = 0;
        foreach ($this->strikes as $interest) {
            if ($interest['call'] > 100) {
                $sum += $interest['call'];
                $count++;
            }
            if ($interest['put'] > 100) {
                $sum += $interest['put'];
                $count++;
            }
        }
        return 0 == $count ? 0 : round($sum/$count);
    }

    public function save()
    {
        if ($this->handle = fopen($this->filename, 'w')) {
            $this->putHeader();
            foreach ($this->strikes as $strike => $interest) {
                $this->putStrike($strike, $interest);
            }
            fclose($this->handle);
        }
    }

    protected function putHeader()
    {
        fputs($this->handle, $this->format('strike', 'put', 'call'));
    }

    protected function putStrike($strike, $interest)
    {
        $call = isset($interest['call']) ? $interest['call'] : 0;
        $put = isset($interest['put']) ? $interest['put'] : 0;
        if (!isset($this->minInterest) || ($call + $put) > $this->minInterest) {
            fputs($this->handle, $this->format($strike, $put, $call));
        }
    }

    public function format($strike, $put, $call)
    {
        return $strike . ';' . $put . ';' . $call . PHP_EOL;
    }

    /**
     * @param string $filename
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    /**
     * @param mixed $minInterest
     */
    public function setMinInterest($minInterest)
    {
        $this->minInterest = $minInterest;
    }
}