<?php

global $ban;

// Условие использования действия
if($ban == 1) {
    return false;
}

//склад
return function($s1)
{
    global $table_cart, $dir, $cartdesign, $server_names, $db, $goodly, $icons;
    $c = '';
    $m = '';
    $m .= responses\infly('Здесь отображается список вещей, которые вы можете забрать в игре.<br> Для получения вещей в игре используйте команду: <b>/cart</b>');
    $siz = count($server_names);
    for ($i = 0, $size = $siz; $i < $size; ++$i) {
        $q = $db->select("SELECT * FROM `{$server_names[$i]}` WHERE `{$table_cart['name']}`='{$s1}'");
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

return [
    'group' => 15,
    'action' => function($value) {

    }
];