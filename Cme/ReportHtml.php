<?php

namespace Cme;

require_once 'Report.php';

class ReportHtml extends Report
{
    protected $fileext = '.htm';
    protected $template = 'template.html';
    protected $reportCalls = array();
    protected $reportPuts = array();
    protected $reportStrikes = array();
    protected $path;

    function __construct($symbol)
    {
        parent::__construct($symbol);
        $this->path = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Report';
    }


    public function save()
    {
        if ($this->handle = fopen($this->filename, 'w')) {
            foreach ($this->strikes as $strike => $interest) {
                $this->putStrike($strike, $interest);
            }
            $content = $this->getContent();
            fwrite($this->handle, $content);
            fclose($this->handle);
        }
    }

    /**
     * @param string $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    public function getContent()
    {
        $template = $this->getTemplate();
        $cssPath = $this->path . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR;
        $content = str_replace('<%symbol%>', $this->symbol, $template);
        $content = str_replace('<%path%>', $cssPath, $content);
        $content = str_replace('<%strikes%>', json_encode($this->reportStrikes), $content);
        $content = str_replace('<%call%>', json_encode($this->reportCalls), $content);
        $content = str_replace('<%put%>', json_encode($this->reportPuts), $content);

        return $content;
    }

    public function getTemplate()
    {
        $template = $this->path . DIRECTORY_SEPARATOR
            . $this->template;
        return file_get_contents($template);
    }

    protected function putStrike($strike, $interest)
    {
        $call = isset($interest['call']) ? $interest['call'] : 0;
        $put = isset($interest['put']) ? $interest['put'] : 0;
        if (!isset($this->minInterest) || ($call + $put) > $this->minInterest) {
            $this->reportStrikes[] = $strike;
            $this->reportCalls[] = $call;
            $this->reportPuts[] = $put;
        }
    }
}