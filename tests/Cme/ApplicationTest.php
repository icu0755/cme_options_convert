<?php


class ApplicationTest extends PHPUnit_Framework_TestCase
{
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
}
