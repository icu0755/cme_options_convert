<?php
const STRIKE_POS = 0;
const INTEREST_POS = 10;
const EURO_OPTION_CODE = 'ZC';
const OUTPUT = 'output.csv';
const DATA = 'ftp://ftp.cmegroup.com/pub/settle/stlcur';

function change_working_dir()
{
    $dir = date('Ymd');
    if (!file_exists($dir)) {
        mkdir($dir);
    }
    chdir($dir);
}

function dump_strikes($strikes)
{
    if (file_exists(OUTPUT)) unlink(OUTPUT);
    file_put_contents(OUTPUT, 'strike;put;call' . PHP_EOL, FILE_APPEND);
    foreach ($strikes as $strike => $interests) {
        $call = isset($interests['call']) ? $interests['call'] : 0;
        $put = isset($interests['put']) ? $interests['put'] : 0;
        file_put_contents(OUTPUT, $strike . ';' . $put . ';' . $call . PHP_EOL, FILE_APPEND);
    }
}

function is_call($s)
{
    return false !== strpos($s, 'CALL');
}

function is_put($s)
{
    return false !== strpos($s, 'PUT');
}

function is_option($s, $code, $month)
{
    $code .= ' ';
    return 0 === strpos($s, $code) && false !== strpos($s, $month);
}

function is_total($s)
{
    return 0 === strpos($s, 'TOTAL');
}

function get_period($s)
{
    $arr = preg_split('/\s+/', $s);
    return $arr[STRIKE_POS];
}

function process_file($name)
{
    $strikes = array();
    $handle = fopen($name, 'r');
    if ($handle) {
        while ($s = fgets($handle)) {
            $isJulyCall = is_option($s, EURO_OPTION_CODE, 'JLY') && is_call($s);
            $isJulyPut = is_option($s, EURO_OPTION_CODE, 'JLY') && is_put($s);
            if ($isJulyPut) process_month($handle, $strikes, 'put');
            if ($isJulyCall) process_month($handle, $strikes, 'call');
        }
    }
    dump_strikes($strikes);
    fclose($handle);
}

function process_month($handle, &$strikes, $type)
{
    $curPos = 0;
    while ($s = fgets($handle)) {
        if (!is_total($s) && !is_call($s) && !is_put($s)) {
            process_strike($strikes, $s, $type);
            $curPos = ftell($handle);
        } else {
            fseek($handle, $curPos);
            return;
        }
    }
}

function process_strike(&$strikes, $s, $type)
{
    $s = trim($s);
    $curStrike = (int) substr($s, 0, 9);
    $curInterest = (int) substr($s, 98, 12);

    $newStrike = isset($strikes[$curStrike]) ? $strikes[$curStrike] : array();
    $newStrike[$type] = $curInterest;
    $strikes[$curStrike] = $newStrike;
}

function get_data($to)
{
    if (!file_exists($to)) {
        $data = file_get_contents(DATA);
        file_put_contents($to, $data);
    }
}

$fname = 'stlcur.txt';
date_default_timezone_set('Europe/Moscow');
change_working_dir();
get_data($fname);
process_file($fname);