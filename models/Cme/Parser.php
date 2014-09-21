<?php


namespace Cme;


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

    public function fclose()
    {
        fclose($this->handle);
    }

    public function fopen($file)
    {
        $this->marketData = $file;
        $this->handle = fopen($this->marketData, 'r');
    }

    public function getMarketDataRow($row)
    {
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
        } else {
            $row = false;
        }

        return $row;
    }

    public function getMarketDataRowType($row)
    {
        $type = false;
        if ($row) {
            $type = 'other';
            if (false !== strpos($row, 'OPTIONS')) {
                $type = 'option';
            } elseif (is_numeric(trim(substr($row, 0, 7)))) {
                $type = 'strike';
            }
        }

        return $type;
    }

    public function hasOptionFound($marketDataRow)
    {
        $hasFound = false;
        if ($marketDataRow->isOption() && $this->month == $marketDataRow->getMonth() && $this->code == $marketDataRow->getCode()) {
            $hasFound = true;
        }
        return $hasFound;
    }

    public function parse()
    {
        $this->handle = fopen($this->marketData, 'r');
        if ($this->handle) {
            while ($s = fgets($this->handle)) {
                $token = $this->getMarketDataRow($s);
                if ($this->hasOptionFound($token)) {
                    $this->addOptionStrikesToReport($token->getOptionType());
                }
            }
            fclose($this->handle);
        }
    }

    public function addOptionStrikesToReport($type)
    {
        while ($s = fgets($this->handle)) {
            $token = $this->getMarketDataRow($s);
            if (!$token->isStrike()) break;
            $this->report->addStrike($type, $token);
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