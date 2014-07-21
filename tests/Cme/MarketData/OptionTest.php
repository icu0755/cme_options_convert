<?php


class OptionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Cme\MarketData\Option
     */
    protected $option;

    public function setUp()
    {
        $row = "ZC AUG14 CME EURO FX OPTIONS CALL\n";
        $this->option = new Cme\MarketData\Option($row);
    }

    public function tearDown()
    {
        $this->option = null;
    }

    public function testGetCode()
    {
        $this->assertEquals('ZC', $this->option->getCode());
    }

    public function testGetMonth()
    {
        $this->assertEquals('AUG14', $this->option->getMonth());
    }

    public function testGetOptionTypeCall()
    {
        $this->assertEquals('call', $this->option->getOptionType());
    }

    public function testGetOptionTypePut()
    {
        $row = "ZC AUG14 CME EURO FX OPTIONS PUT\n";
        $this->option = new Cme\MarketData\Option($row);
        $this->assertEquals('put', $this->option->getOptionType());
    }
}
