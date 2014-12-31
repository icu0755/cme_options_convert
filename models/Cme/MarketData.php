<?php
namespace Cme;

use Cme\MarketData\Option;
use Cme\MarketData\Strike;

class MarketData
{
    const OPTION = 'option';

    const STRIKE = 'strike';

    public static function make($data)
    {
        $marketData = null;
        switch (static::getType($data)) {
            case static::STRIKE:
                $marketData = new Strike($data);
                break;
            case static::OPTION:
                $marketData = new Option($data);
                break;
        }

        return $marketData;
    }

    public static function getType($data)
    {
        $type = false;
        if (is_string($data)) {
            if (false !== strpos($data, 'OPTION')) {
                $type = static::OPTION;
            } elseif (is_numeric(trim(substr($data, 0, 7)))) {
                $type = static::STRIKE;
            }
        }

        return $type;
    }
}
