<?php


namespace Cme\Strike;


class Row
{
    protected $call;
    protected $put;

    /**
     * @param mixed $call
     */
    public function setCall($call)
    {
        $this->call = $call;
    }

    /**
     * @return mixed
     */
    public function getCall()
    {
        return $this->call;
    }

    /**
     * @param mixed $put
     */
    public function setPut($put)
    {
        $this->put = $put;
    }

    /**
     * @return mixed
     */
    public function getPut()
    {
        return $this->put;
    }
}