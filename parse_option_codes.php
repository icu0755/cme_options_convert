<?php


$codes = array();
if ($handle = fopen('20140626/stlcur.txt', 'r')) {
    while ($s = fgets($handle)) {
        if (false !== strpos($s, 'OPTIONS')) {
            $parts = explode(' ', $s);
            $code = array_shift($parts);
            if (!array_key_exists($code, $codes)) {
                $name = array();
                $date = array_shift($parts);
                $part = array_shift($parts);
                while ('OPTIONS' !== $part && !is_null($part)) {
                    $name[] = $part;
                    $part = array_shift($parts);
                }
                $name = implode(' ', $name);
                $codes[$code] = $name;
            }
        }
    }
    fclose($handle);
}
file_put_contents('codes.txt', var_export($codes, true));