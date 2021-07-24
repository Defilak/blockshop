<?php

if (!defined('BLOCKSHOP')) {
    die("HACKING");
}

function enchments()
{
    global $enchs;
    $template = '';
    for ($i = 0; $i < count($enchs); $i++) {
        [$code, $name] = explode(':', $enchs[$i]);
        $template = "<option value=\"$code\">$name</option>";
    }
    return $template;
}

function servlist()
{
    global $server_names;
    $l = '';
    for ($i = 0, $size = count($server_names); $i < $size; ++$i) {
        $l .= '<option value="' . $i . '">' . $server_names[$i] . '</option>';
    }
    return $l;
}

function catlist()
{
    global $cat;
    $l = '';
    for ($i = 0, $size = count($cat); $i < $size; ++$i) {
        $l .= '<option value="' . $i . '">' . $cat[$i] . '</option>';
    }
    return $l;
}

/*function bal($s1, $s2)
{
    global $q1;
    $q = mysqli_fetch_assoc($q1);
    return $q[$s2];
}*/

function skl($number, $wordCases)
{
    $number = round($number);
    $m = $number % 10;
    $j = $number % 100;
    $s = '';
    if ($m == 1) {
        $s = $wordCases[0];
    }
    if ($m >= 2 && $m <= 4) {
        $s = $wordCases[1];
    }
    if ($m == 0 || $m >= 5 || ($j >= 10 && $j <= 20)) {
        $s = $wordCases[2];
    }
    return $number . ' ' . $s;
}
