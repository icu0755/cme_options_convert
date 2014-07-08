<?php


namespace Cme;


class Strike
{
    protected $strike;
    protected $open;
    protected $close;
    protected $high;
    protected $low;
    protected $last;
    protected $settlementPrice;
    protected $settlementPriceChange;
    protected $estimatedVolume;
    protected $priorSettlementPrice;
    protected $priorVolume;
    protected $openInterest;


    function __construct($s)
    {
        $s = trim($s);
        $this->strike = (int) substr($s, 0, 7);
        $this->priorVolume = (int) substr($s, 86, 12);
        $this->openInterest = (int) substr($s, 98, 12);
    }

    function __toString()
    {
        $s = "STRIKE: " . $this->strike . PHP_EOL
            . 'VOLUME: ' . $this->priorVolume . PHP_EOL
            . 'OPEN INTEREST: ' . $this->openInterest . PHP_EOL;

        return $s;
    }

    /**
     * @return int
     */
    public function getStrike()
    {
        return $this->strike;
    }

    /**
     * @return int
     */
    public function getOpenInterest()
    {
        return $this->openInterest;
    }


}