<?php

if (!defined('BLOCKSHOP')) {
    die("HACKING");
}


////функции///
function servlist()
{
    global $s;
    $siz = count($s);
    $l = '';
    for ($i = 0, $size = $siz; $i < $size; ++$i) {
        $l .= '<option value="' . $i . '">' . $s[$i] . '</option>';
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
