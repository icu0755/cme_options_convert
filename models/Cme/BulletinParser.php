<?php
namespace Cme;

use Cme\MarketData\Option;
use Cme\MarketData\Strike;

class BulletinParser
{
    /**
     * @var \Cme\Report\ReportAbstract
     */
    protected $report;
    protected $file;
    protected $handle;
    protected $ignoreDate = [];

    /**
     * @return array
     */
    public function getIgnoreDate()
    {
        return $this->ignoreDate;
    }

    /**
     * @param array $ignoreDate
     */
    public function setIgnoreDate($ignoreDate)
    {
        $this->ignoreDate = $ignoreDate;
    }

    public function parse($file)
    {
        $this->file = $file;
        $this->handle = fopen($this->file, 'r');
        if ($this->handle) {
            $bulletinDate = $this->getReportBulletin();

            if (!$this->isIgnored($bulletinDate)) {
                while ($data = fgets($this->handle)) {
                    $token = MarketData::make($data);
                    if ($token && $token->isOption()) {
                        $token->setBulletinDate($bulletinDate);
                        $this->addOptionStrikesToReport($token);
                    }
                }
            } else {
                echo sprintf('Date %s ignored', $bulletinDate) . PHP_EOL;
            }

            fclose($this->handle);
        }
    }

    public function addOptionStrikesToReport(Option $option)
    {
        while ($data = fgets($this->handle)) {
            $token = MarketData::make($data);

            if (!$token || !$token->isStrike()) {
                break;
            }

            /**
             * @var $token Strike
             */
            $token->loadOption($option);

            $this->report->add($token);
        }
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

    public function isIgnored($date)
    {
        return in_array($date, $this->getIgnoreDate());
    }

    public function getReportBulletin()
    {
        $date = null;
        if ($this->handle) {
            $data = fgets($this->handle);
            if (preg_match('#\d{2}/\d{2}/\d{2}#', $data, $date)) {
                $date = date_create($date[0])->format('Y-m-d');
            }
        }

        return $date;
    }
}
