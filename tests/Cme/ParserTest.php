<?php

use \Cme\Parser;

class ParserTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Cme\Parser
     */
    protected $parser;

    public function setUp()
    {
        $this->parser = new Parser;
    }

    public function tearDown()
    {
        $this->parser = null;
    }

    public function tokenTypeProvider()
    {
        return array(
            array('XA AUG14 EUROPEAN AUSTRALIAN DOLLAR OPTIONS CALL', 'option', '\Cme\MarketData\Option'),
            array('10000      ----      ----      ----      ----       CAB    UNCH                    CAB\n', 'strike', '\Cme\MarketData\Strike'),
            array('Foo Bar', 'other', '\Cme\MarketData\Other'),
        );
    }

    public function getOptionMock($isOption, $getMonth, $getCode)
    {
        $stub = $this->getMockBuilder('\Cme\MarketData\Option')
            ->disableOriginalConstructor()
            ->setMethods(array('isOption', 'getMonth', 'getCode'))
            ->getMock();

        $stub->expects($this->any())->method('isOption')->will($this->returnValue($isOption));
        $stub->expects($this->any())->method('getMonth')->will($this->returnValue($getMonth));
        $stub->expects($this->any())->method('getCode')->will($this->returnValue($getCode));

        return $stub;
    }

    /**
     * @dataProvider tokenTypeProvider
     */
    public function testGetMarketDataRowType($token, $type, $class)
    {
        $this->assertEquals($type, $this->parser->getMarketDataRowType($token));
    }

    /**
     * @dataProvider tokenTypeProvider
     */
    public function testGetMarketDataRow($token, $type, $class)
    {
        $object = $this->parser->getMarketDataRow($token);
        $this->assertTrue(is_a($object, $class));
    }


    public function testHasFoundOption()
    {
        $this->parser->setMonth('AUG14')->setCode('ZC');

        // Return true if it is an option of month AUG14 and code ZC ...
        $stub = $this->getOptionMock(true, 'AUG14', 'ZC');
        $this->assertTrue($this->parser->hasOptionFound($stub));

        // ... and return false if one of the conditions were not met
        $stub = $this->getOptionMock(false, 'AUG14', 'ZC');
        $this->assertFalse($this->parser->hasOptionFound($stub));

        $stub = $this->getOptionMock(true, 'SEP14', 'ZC');
        $this->assertFalse($this->parser->hasOptionFound($stub));

        $stub = $this->getOptionMock(true, 'AUG14', 'CD');
        $this->assertFalse($this->parser->hasOptionFound($stub));
    }

    public function testParse()
    {
        $data = dirname(__DIR__) . '/data/stlcur.txt';

        $reportStub = $this->getMock('\Cme\Report', array('addStrike'), array('eurusd'));
        $reportStub->expects($this->exactly(130))->method('addStrike');

        $this->parser->setMarketData($data);
        $this->parser->setReport($reportStub);
        $this->parser->setMonth('AUG14');
        $this->parser->setCode('ZC');
        $this->parser->parse();
    }
}
