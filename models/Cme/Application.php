<?php


namespace Cme;

class Application
{
    const MARKET_DATA_URL = 'ftp://ftp.cmegroup.com/pub/settle/stlcur';
    /**
     * @var \Cme\Parser
     */
    protected $parser;

    protected $symbols;

    protected $filename = 'stlcur.txt';

    protected $month;

    protected function debug($message)
    {
        echo $message . PHP_EOL;
    }

    public function getMarketData()
    {
        if (!file_exists($this->filename)) {
            $data = file_get_contents(self::MARKET_DATA_URL);
            file_put_contents($this->filename, $data);
        }
    }

    public function run()
    {
        $this->parser->setMarketData($this->filename)
            ->setMonth($this->month);
        foreach ($this->symbols as $code => $symbol) {
            $this->debug('Process symbol: ' . $symbol);
            $time = time();

            $report = new ReportHtml($symbol);
            $this->parser->setCode($code)->setReport($report);
            $this->parser->parse();

            // save report
            if ($avg = $report->getAverage()) {
                $report->setMinInterest($avg);
            }
            $report->save();

            // output statistics
            $time = time() - $time;
            $this->debug('Time: ' . $time . ' sec');
            $this->debug('Average: ' . $avg);
        }
    }

    public function setDirectory($directory)
    {
        if (!file_exists($directory)) {
            mkdir($directory);
        }
        chdir($directory);
        return $this;
    }

    public function setParser($parser)
    {
        $this->parser = $parser;
        return $this;
    }

    public function setSymbols($symbols)
    {
        $this->symbols = $symbols;
        return $this;
    }
    
    public function setTimezone($timezone)
    {
        date_default_timezone_set($timezone);
        return $this;
    }

    public function setMonth($month)
    {
        $this->month = $month;
        return $this;
    }
}