<?php


class ApplicationTest extends PHPUnit_Framework_TestCase
{
    public $application;

    protected function setUp()
    {
        parent::setUp();
        $this->application = new \Cme\Application();
    }

    public function testRun()
    {
        $month = 'AUG14';
        $symbols = array(
            'ZC' => 'eurusd',
        );
        $parser = $this->getMock('Cme\Parser');

        $parser->expects($this->once())
            ->method('setMarketData')
            ->will($this->returnSelf());

        $parser->expects($this->once())
            ->method('setMonth')
            ->with($this->equalTo($month))
            ->will($this->returnSelf());

        $parser->expects($this->once())
            ->method('setCode')
            ->with($this->equalTo('ZC'))
            ->will($this->returnSelf());

        $parser->expects($this->once())
            ->method('setReport')
            ->will($this->returnSelf());

        $parser->expects($this->once())
            ->method('parse');

        $app = new \Cme\Application();
        $app->setTimezone('Europe/Moscow')
            ->setDirectory('data' . DIRECTORY_SEPARATOR . date('Ymd'))
            ->setParser($parser)
            ->setSymbols($symbols)
            ->setMonth($month);
        $app->run();
    }

    public function testGetReportFileName()
    {
        $symbol = 'audusd';
        $month = 'aug14';
        $filename = $this->application->getReportFileName($month, $symbol);
        $this->assertEquals('AUG14_audusd', $filename);
    }
}
