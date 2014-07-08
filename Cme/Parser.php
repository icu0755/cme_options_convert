<?php


namespace Cme;


class Parser
{
    protected $directory;

    protected $filename;

    protected $handle;

    protected $symbols = array (
        'ZA' => 'audusd',
        'OB' => 'gbpusd',
        'OV' => 'cadusd',
        'ZC' => 'eurusd',
        'OJ' => 'jpyusd',
        'ZN' => 'nzdusd',
        'OF' => 'chfusd',
    );

    protected $timezone;

    function __construct()
    {
        $this->directory = dirname(__FILE__) . DIRECTORY_SEPARATOR . date('Ymd');
        $this->filename = 'stlcur.txt';
        $this->timezone = 'Europe/Moscow';
        $this->setDirectory($this->directory);
        $this->setTimezone($this->timezone);
    }

    public function getData()
    {
        if (!file_exists($this->filename)) {
            $data = file_get_contents(DATA);
            file_put_contents($this->filename, $data);
        }
    }

    public function setDirectory($directory)
    {
        if (!file_exists($directory)) {
            mkdir($directory);
        }
        chdir($directory);
    }

    public function setTimezone($timezone)
    {
        date_default_timezone_set($timezone);
    }

    public function start()
    {
        $this->getData();
        foreach ($this->symbols as $code => $symbol) {
            $report = new
        }
    }
}