<?php


namespace Cme;


class Report
{
    protected $fileext = '.csv';
    protected $filename;
    protected $handle;
    protected $strikes;

    function __construct($filename)
    {
        if ($this->fileext !== substr($filename, -4)) $filename .= $this->fileext;
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
        fputs($this->handle, $this->format($strike, $put, $call));
    }

    protected function format($strike, $put, $call)
    {
        return $strike . ';' . $put . ';' . $call . PHP_EOL;
    }
}