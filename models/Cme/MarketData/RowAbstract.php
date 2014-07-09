<?php

namespace Cme\MarketData;


class RowAbstract
{
    protected $type;

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    public function isType($type)
    {
        return $this->type === $type;
    }

    function __call($name, $arguments)
    {
        $result = null;
        if ('is' === substr($name, 0, 2)) {
            $type = strtolower(substr($name, 2));
            $result = $this->isType($type);
        } else {
            throw new \Exception('Call to undefined method: ' . __CLASS__ . '::' . $name);
        }

        return $result;
    }
}