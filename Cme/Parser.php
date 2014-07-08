<?php


namespace Cme;


require_once 'MarketData\Other.php';
require_once 'MarketData\Strike.php';
require_once 'MarketData\Option.php';

class Parser
{
    /**
     * @var \Cme\Report
     */
    protected $report;
    protected $code;
    protected $month;
    protected $marketData;
    protected $handle;

    public function getMarketDataRow()
    {
        $row = fgets($this->handle);
        if ($row) {
            switch ($this->getMarketDataRowType($row)) {
                case 'strike':
                    $row = new \Cme\MarketData\Strike($row);
                    break;
                case 'option':
                    $row = new \Cme\MarketData\Option($row);
                    break;
                default:
                    $row = new \Cme\MarketData\Other($row);
            }
        }

        return $row;
    }

    public function getMarketDataRowType($row)
    {
        $type = 'other';
        if (false !== strpos($row, 'OPTIONS')) {
            $type = 'option';
        } elseif (is_numeric(trim(substr($row, 0, 7)))) {
            $type = 'strike';
        }

        return $type;
    }

    public function parse()
    {
        $this->handle = fopen($this->marketData, 'r');
        if ($this->handle) {
            while ($r = $this->getMarketDataRow()) {
                if ($r->isOption() && $this->month == $r->getMonth() && $this->code == $r->getCode()) {
                    $optionType = $r->getOptionType();
                    for ($r = $this->getMarketDataRow(); !is_null($r) && $r->isStrike(); $r = $this->getMarketDataRow()) {
                        $this->report->addStrike($optionType, $r);
                    }
                }
            }
            fclose($this->handle);
            $this->report->save();
        }
    }

    /**
     * @param mixed $code
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @param mixed $marketData
     * @return $this
     */
    public function setMarketData($marketData)
    {
        $this->marketData = $marketData;
        return $this;
    }

    /**
     * @param mixed $month
     * @return $this
     */
    public function setMonth($month)
    {
        $this->month = $month;
        return $this;
    }

    /**
     * @param mixed $report
     * @return $this
     */
    public function setReport($report)
    {
        $this->report = $report;
        return $this;
    }
}