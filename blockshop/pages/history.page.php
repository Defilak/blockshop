<?php

namespace pages;

include_once 'ajax/responses.php';

use DB;
use responses;

if ($group == -1) {
    return false;
}

//История покупок
return function ($s1) {
    global $dir, $logs, $historydesign, $db, $success, $icons;
    $m = '';
    $c = '';
    $m .= responses\infly('Здесь отображаются все совершенные вами действия в магазине за ближайшие 10 суток.');
    $stmt = DB::prepare("SELECT * FROM {$logs} WHERE name='{$s1}' ORDER BY date DESC");
    $stmt->execute();
    $q = $stmt->fetchAll();


    $time = time() - 864000;
    if (count($q) == 0) {
        responses\warning('История пуста!');
    }
    $search = array('{name}', '{dir}', '{img}', '{info}', '{date}', '{icons}');
    for ($i = 0; $i < count($q); $i++) {
        $d = date('j.m.Y H:i:s', $q[$i]['date']);
        list($n, $a, $p, $l) = explode(":n:", $q[$i]['info']);
        if ($p != 0) {
            $g = '<b>' . skl($a, array('штука', 'штуки', 'штук')) . ' за ' . $p . '</b>';
        } else {
            $g = '<b>' . $a . '</b>';
        }
        $replace = array($n, $dir, $l, $g, $d, blockshop_public($icons));
        $c .= str_replace($search, $replace, $historydesign);
    }
    DB::prepare("DELETE from {$logs} where date<{$time}")->execute();
    die($m . $c);
};
