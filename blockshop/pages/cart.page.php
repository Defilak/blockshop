<?php

namespace pages;

include_once 'ajax/responses.php';

use DB;
use responses;

global $ban;

// Условие использования действия
if ($ban == 1) {
    return false;
}

/*return function ($username) {
    global $server_names, $table_cart;

    $result = [];
    foreach($server_names as $server_name) {
        $stmt = DB::prepare("SELECT * FROM `{$server_name}` WHERE `{$table_cart['name']}`= :username");
        $stmt->bindValue(':username', $username);
        $stmt->execute();

        //$result = $stmt->fetchAll();

        while($row = $stmt->fetch()) {


        }
    }

};*/


//склад
return function ($s1) {
    global $table_cart, $dir, $cartdesign, $server_names, $db, $goodly, $icons;

    $c = '';
    $m = '';
    $m .= responses\infly('Здесь отображается список вещей, которые вы можете забрать в игре.<br> Для получения вещей в игре используйте команду: <b>/cart</b>');
    $siz = count($server_names);
    for ($i = 0, $size = $siz; $i < $size; ++$i) {
        $q = DB::prepare("SELECT * FROM `{$server_names[$i]}` WHERE `{$table_cart['name']}`= :username");
        $q->bindValue(':username', $s1);
        $q->execute();

        $q = $q->fetchAll();
        $search = array('{id}', '{name}', '{dir}', '{img}', '{amount}', '{srv}', '{icons}');

        for ($u = 0; $u < count($q); $u++) {
            $replace = array($q[$u]['id'], $q[$u]['name'], $dir, $q[$u]['img'], $q[$u][$table_cart['amount']], $server_names[$i], $icons);
            $c .= str_replace($search, $replace, $cartdesign);
        }
    }
    if (empty($c)) {
        responses\badly('Корзина пуста!');
    }
    die($m . $c);
};
