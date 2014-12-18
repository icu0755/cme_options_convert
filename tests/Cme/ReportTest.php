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

    public function strikesProvider()
    {
        $asset = array(
            1300 => array('call' => 100, 'put' => 150),
            1310 => array('call' => 200, 'put' => 250),
            1320 => array('call' => 300, 'put' => 350),
        );
        return array(
            array($asset)
        );
    }

    public function getStrikeMock($strike, $interest)
    {
        $mock = $this->getMockBuilder('\Cme\MarketData\Strike')
            ->disableOriginalConstructor()
            ->setMethods(array('getStrike', 'getInterest'))
            ->getMock();

        $mock->expects($this->any())->method('getStrike')->will($this->returnValue($strike));
        $mock->expects($this->any())->method('getInterest')->will($this->returnValue($interest));

        return $mock;
    }

    /**
     * @dataProvider strikesProvider
     */
    public function testAddStrike($assert)
    {
        foreach (array('call', 'put') as $type) {
            foreach (array(1300, 1310, 1320) as $strike) {
                $interest = $assert[$strike][$type];
                $strikeMock = $this->getStrikeMock($strike, $interest);
                $this->report->add($type, $strikeMock);
            }
        }

        $this->assertEquals($assert, $this->report->getData());
    }

    /**
     * @dataProvider strikesProvider
     */
    public function testGetAverage($assert)
    {
        foreach (array('call', 'put') as $type) {
            foreach (array(1300, 1310, 1320) as $strike) {
                $interest = $assert[$strike][$type];
                $strikeMock = $this->getStrikeMock($strike, $interest);
                $this->report->add($type, $strikeMock);
            }
        }

        $this->assertEquals(250, $this->report->getAverage());
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
