<?php
namespace Cme\Report;

use Cme\MarketData\Strike;

abstract class ReportAbstract
{
    abstract public function add(Strike $strike);
}
