<?php

namespace Cme\MarketData;

class Option extends RowAbstract
{
    /**
     * @var string
     */
    protected $type = 'option';

    /**
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $month;

    /**
     * @var string
     */
    protected $optionType;

    /**
     * @var string
     */
    protected $bulletinDate;

    public function __construct($row)
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

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @param string $month
     */
    public function setMonth($month)
    {
        $this->month = $month;
    }

    /**
     * @param string $optionType
     */
    public function setOptionType($optionType)
    {
        $this->optionType = $optionType;
    }

    /**
     * @return string
     */
    public function getBulletinDate()
    {
        return $this->bulletinDate;
    }

    /**
     * @param string $bulletinDate
     */
    public function setBulletinDate($bulletinDate)
    {
        $this->bulletinDate = $bulletinDate;
    }
}
