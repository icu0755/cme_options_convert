<?php

require_once 'vendor/autoload.php';

const TIMES_TO_RUN = 3;

$symbols = array(
    'ZA' => 'audusd',
    'OB' => 'gbpusd',
    'OV' => 'cadusd',
    'ZC' => 'eurusd',
    'OJ' => 'jpyusd',
    'ZN' => 'nzdusd',
    'OF' => 'chfusd',
);
$app = new \Cme\Application();
$app->setTimezone('Europe/Moscow')
    ->setDirectory('data' . DIRECTORY_SEPARATOR . date('Ymd'))
    ->setParser(new \Cme\Parser())
    ->setSymbols($symbols)
    ->getMarketData();

$monthInterval = new DateInterval('P30D');
$date          = new DateTime();
for ($i=0; $i<TIMES_TO_RUN; $i++) {
    $strDate = $date->add($monthInterval)->format('My');
    $app->setMonth(strtoupper($strDate))->run();
}
