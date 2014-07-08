<?php


namespace Cme;


class Report
{
    protected $filename;
    protected $handle;

    function __construct($filename)
    {
        $this->filename = $filename;
    }

    public function start()
    {
        $this->handle = fopen($this->filename, 'w');
    }

    public function finish()
    {
        if (is_resource($this->handle)) {
            fclose($this->handle);
        }
    }
}