<?php


class ReportTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Cme\Report
     */
    protected $report;

    protected $symbol = 'eurusd';

    public function setUp()
    {
        $this->report = new Cme\Report($this->symbol);
    }

    public function tearDown()
    {
        unset($this->report);
    }

    public function testIsFormatCorrect()
    {
        $strike = 1000;
        $put    = 1000;
        $call   = 10;
        $format = $strike . ';' . $put . ';' . $call . PHP_EOL;

        $this->assertEquals($format, $this->report->format($strike, $put, $call));
    }


}
