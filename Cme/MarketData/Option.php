<?php

namespace Cme\MarketData;

require_once 'RowAbstract.php';

class Option extends RowAbstract
{
    protected $type = 'option';

    protected $code;

    protected $month;

    protected $optionType;

    function __construct($row)
    {
        $row = explode(' ', trim($row));
        $this->code = array_shift($row);
        $this->month = array_shift($row);
        while ($this->optionType = array_shift($row)) {
            if ('CALL' == $this->optionType || 'PUT' == $this->optionType) {
                $this->optionType = strtolower($this->optionType);
                break;
            }
        }
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return mixed
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * @return mixed
     */
    public function getOptionType()
    {
        return $this->optionType;
    }
}