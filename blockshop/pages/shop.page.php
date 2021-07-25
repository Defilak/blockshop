<?php

namespace pages;

include_once 'ajax/responses.php';

use DB;
use responses;


function get_display_price($product)
{
    global $sklrub, $skleco;

    if ($product['price'] != 0 && $product['realprice'] != 0) {
        return "{$product['realprice']}{$sklrub[3]}/{$product['price']}{$skleco[3]}";
    } elseif ($product['price'] == 0) {
        return "{$product['realprice']}{$sklrub[3]}";
    } else {
        return "{$product['price']}{$skleco[3]}";
    }
}

$server_list = array('Medieval', 'Imphar'); ///массив серверов(первое по умолчанию) 
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
]);