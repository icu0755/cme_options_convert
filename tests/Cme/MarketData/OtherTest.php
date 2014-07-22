<?php


class OtherTest extends PHPUnit_Framework_TestCase
{
    protected $row = "SEP14    1.0014    1.0049B    .9955A   1.0049B   1.0048  +.0070           1      .9978                      13\n";
    /**
     * @var Cme\MarketData\Other
     */
    protected $other;

    public function setUp()
    {
        $this->other = new Cme\MarketData\Other($this->row);
    }

    public function testIsTypeOther()
    {
        $this->assertTrue($this->other->isType('other'));
        $this->assertTrue($this->other->isOther());
    }
}
