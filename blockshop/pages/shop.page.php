<?php

namespace pages;

include_once 'ajax/responses.php';

use DB;
use responses;
use ShopProduct;


function get_display_price(ShopProduct $product): string
{
    global $sklrub, $skleco;

    $display_price = "";

    if ($product->realprice != 0) {
        $display_price = "{$product->realprice}{$sklrub[3]}";
    }

    if ($product->price != 0) {
        $display_price .= strlen($display_price) != 0 ? '/' : '';
        $display_price .= "{$product->price}{$skleco[3]}";
    }

    return $display_price;
    /*if ($product['price'] != 0 && $product['realprice'] != 0) {
        return "{$product['realprice']}{$sklrub[3]}/{$product['price']}{$skleco[3]}";
    } elseif ($product['price'] == 0) {
        return "{$product['realprice']}{$sklrub[3]}";
    } else {
        return "{$product['price']}{$skleco[3]}";
    }*/
}

/*$server_list = array('Medieval', 'Imphar'); ///массив серверов(первое по умолчанию) 
$shop_categories = array('Все', 'Блоки', 'Инструменты', 'Еда', 'Оружие', 'Одежда'); ///массив категорий (первое значение выводит все блоки)

$server_id = '';
$category_id = '';
list($server_id, $category_id) = explode(':', $s1);

if (is_numeric($server_id) && isset($server_list[$server_id])) {
    $current_server = $server_list[$server_id];
} else {
    $current_server = $server_list[0];
}

if (is_numeric($category_id) && isset($shop_categories[$category_id]) && $shop_categories[$category_id] != $shop_categories[0]) {
    $category = $shop_categories[$category_id];
} else {
    $category = '%%%%';
}

$stmt = DB::prepare("SELECT * FROM {$blocks} WHERE server = :server and category LIKE :category ORDER BY id");
$stmt->bindValue(':server', $current_server);
$stmt->bindValue(':category', $category);
$stmt->execute();

$result = $stmt->fetchAll();

_exit_with_template('shop', [
    'result' => $result,
    'group' => $group,
    'icons' => $icons
]);*/


return function ($args) {
    global $group, $icons;

    $server_names = config('servers');
    $categories = config('shop.categories');
    [$server_id, $category_id] = explode(':', $args);

    $current_server = $server_names[0];
    if (is_numeric($server_id) && isset($server_names[$server_id])) {
        $current_server = $server_names[$server_id];
    }

    $current_category = '%%%%';
    if (is_numeric($category_id) && isset($categories[$category_id])) {
        $current_category = $categories[$category_id];
    }

    $production = ShopProduct::getServerCategory($current_server, $current_category);

    _exit_with_template('shop', [
        'result' => $production,
        'group' => $group,
        'icons' => $icons
    ]);

    /*$stmt = DB::prepare("SELECT * FROM sale WHERE server = :server and category LIKE :category ORDER BY id");
    $stmt->bindValue(':server', $current_server);
    $stmt->bindValue(':category', $current_category);
    $stmt->execute();*/
};
