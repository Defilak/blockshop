<?php

if (!defined('BLOCKSHOP')) {
    die("HACKING");
}

global $q1;




function enchments()
{
    global $server_names, $enchs;
    $asd = '';
    //select for enchantments
    for ($i = 0, $size = count($enchs); $i < $size; ++$i) {
        [$a, $b] = explode(":", $enchs[$i]);
        $asd .= '<option value="' . $a . '">' . $b . '</option>';
    }

    return $asd;
}


////функции///
function servlist()
{
    global $server_names;
    $siz = count($server_names);
    $l = '';
    for ($i = 0, $size = $siz; $i < $size; ++$i) {
        $l .= '<option value="' . $i . '">' . $server_names[$i] . '</option>';
    }
    return $l;
}

function catlist()
{
    global $cat;
    $siz = count($cat);
    $l = '';
    for ($i = 0, $size = $siz; $i < $size; ++$i) {
        $l .= '<option value="' . $i . '">' . $cat[$i] . '</option>';
    }
    return $l;
}

function bal($s1, $s2)
{
    global $q1;
    $q = mysqli_fetch_assoc($q1);
    return $q[$s2];
}

function skl($n, $s1)
{
    $n = round($n);
    $m = $n % 10;
    $j = $n % 100;
    if ($m == 1) {
        $s = $s1[0];
    }
    if ($m >= 2 && $m <= 4) {
        $s = $s1[1];
    }
    if ($m == 0 || $m >= 5 || ($j >= 10 && $j <= 20)) {
        $s = $s1[2];
    }
    return $n . ' ' . $s;
}
