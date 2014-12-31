<?php
namespace Cme\MarketData;

class Strike extends Option
{
    /**
     * @var string row type
     */
    protected $type = 'strike';

    /**
     * @var int strike
     */
    protected $strike;

    /**
     * @var int strike interest rate
     */
    protected $interest;

    /**
     * @var int strike volume
     */
    protected $volume;

    public function __construct($row)
    {
        $strike   = trim(substr($row, 0, 7));
        $interest = trim(substr($row, 98, 12));
        $volume   = trim(substr($row, 86, 12));

        $this->setStrike($strike);
        $this->setInterest($interest);
        $this->setVolume($volume);
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

    /**
     * @return int
     */
    public function getVolume()
    {
        return $this->volume;
    }

    /**
     * @param int $volume
     */
    public function setVolume($volume)
    {
        $this->volume = (int)$volume;
    }

    /**
     * @param int $interest
     */
    public function setInterest($interest)
    {
        $this->interest = (int)$interest;
    }

    /**
     * @param int $strike
     */
    public function setStrike($strike)
    {
        $this->strike = (int)$strike;
    }

    public function loadOption(Option $option)
    {
        $this->setCode($option->getCode());
        $this->setMonth($option->getMonth());
        $this->setOptionType($option->getOptionType());
        $this->setBulletinDate($option->getBulletinDate());
    }

    public function toArray()
    {
        return [
            'bulletin_date' => $this->getBulletinDate(),
            'code'          => $this->getCode(),
            'month'         => $this->getMonth(),
            'type'          => $this->getOptionType(),
            'strike'        => $this->getStrike(),
            'volume'        => $this->getVolume(),
            'open_interest' => $this->getInterest(),
        ];
    }
}