<?php


class ParserTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Cme\Parser
     */
    protected $parser;

    public function setUp()
    {
        $this->parser = new Cme\Parser;
    }

    public function tearDown()
    {
        $this->parser = null;
    }

    public function testGetMarketDataRowTypeOption()
    {
        $row = "XA AUG14 EUROPEAN AUSTRALIAN DOLLAR OPTIONS CALL\n";
        $this->assertEquals('option', $this->parser->getMarketDataRowType($row));
    }

    public function testGetMarketDataRowTypeStrike()
    {
        $row = "10000      ----      ----      ----      ----       CAB    UNCH                    CAB\n";
        $this->assertEquals('strike', $this->parser->getMarketDataRowType($row));
    }

    public function testGetMarketDataRowTypeOther()
    {
        $row = "ACD AUSTRALIAN DOLLAR/CANADIAN DOLLAR CROSSRATE FUT\n";
        $this->assertEquals('other', $this->parser->getMarketDataRowType($row));
    }

    public function testHasFoundOption()
    {
        $this->parser->setMonth('AUG14')->setCode('ZC');
        $row = "ZC AUG14 CME EURO FX OPTIONS CALL\n";
        $marketDataRow = $this->parser->getMarketDataRow($row);
        $this->assertTrue($this->parser->hasOptionFound($marketDataRow));
    }

    public function testHasNotFoundOption()
    {
        $this->parser->setMonth('AUG14')->setCode('ZC');
        $rows = array(
            'ZC JUN14 CME EURO FX OPTIONS CALL',
            'CC AUG14 CME EURO FX OPTIONS CALL',
            'CC JUN14 CME EURO FX OPTIONS CALL',
        );

        foreach ($rows as $row) {
            $row .= "\n";
            $marketDataRow = $this->parser->getMarketDataRow($row);
            $this->assertFalse($this->parser->hasOptionFound($marketDataRow));
        }

    }

}
