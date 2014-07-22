<?php


class StrikeTest extends PHPUnit_Framework_TestCase
{
    protected $rows = array(
        "1200       ----      ----      ----      ----    .16120 +.00050                 .16070\n",
        "1360     .00740    .00840B   .00680      ----    .00740 -.00010          57     .00750         335        2674\n",
    );
    /**
     * @var Cme\MarketData\Strike
     */
    protected $strike;

    protected function getRow($number)
    {
        $row = null;
        if (isset($this->rows[$number])) {
            $row = $this->rows[$number];
        }
        return $row;
    }

    public function testGetInterest()
    {
        $row = $this->getRow(0);
        $this->strike = new Cme\MarketData\Strike($row);
        $this->assertEquals(0, $this->strike->getInterest());

        $row = $this->getRow(1);
        $this->strike = new Cme\MarketData\Strike($row);
        $this->assertEquals(2674, $this->strike->getInterest());
    }

    public function testGetStrike()
    {
        $row = $this->getRow(0);
        $this->strike = new Cme\MarketData\Strike($row);
        $this->assertEquals(1200, $this->strike->getStrike());

        $row = $this->getRow(1);
        $this->strike = new Cme\MarketData\Strike($row);
        $this->assertEquals(1360, $this->strike->getStrike());
    }

    public function testIsStrike()
    {
        $row = $this->getRow(0);
        $this->strike = new Cme\MarketData\Strike($row);
        $this->assertTrue($this->strike->isType('strike'));
        $this->assertTrue($this->strike->isStrike());
    }
}
