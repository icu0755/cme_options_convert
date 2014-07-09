<?php

namespace Cme\MarketData;


class Strike extends RowAbstract
{
    protected $type = 'strike';

    protected $strike;

    protected $interest;

    function __construct($row)
    {
        $this->strike = (int) trim(substr($row, 0, 7));
        $this->interest = (int) trim(substr($row, 98, 12));
    }

    /**
     * @return mixed
     */
    public function getInterest()
    {
        return $this->interest;
    }

    /**
     * @return mixed
     */
    public function getStrike()
    {
        return $this->strike;
    }
}