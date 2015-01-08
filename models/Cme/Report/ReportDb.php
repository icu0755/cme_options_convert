<?php
namespace Cme\Report;

use Cme\MarketData\Strike;

class ReportDb extends ReportAbstract
{
    public function add(Strike $strike)
    {
        $model = new \Cme\Eloquent\Strike($strike->toArray());
        $model->save();
    }
}
