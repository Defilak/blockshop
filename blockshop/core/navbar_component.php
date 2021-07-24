<?php

$actions = [
    ['title' => 'Перейти в Магазин блоков', 'img' => 'shop', 'onclick' => "navbar.page('shop', server.value + ':' + category.value)"],
    ['title' => 'Перейти в Личный Кабинет', 'img' => 'lk', 'onclick' => "navbar.page('lk')"],
    ['title' => 'Сменить валюту',           'img' => '0', 'onclick' => 'navbar.currency(this); '],
    ['title' => 'Посмотреть корзину',       'img' => 'cart', 'onclick' => "navbar.page('cart');"],
    ['title' => 'Посмотреть историю',       'img' => 'history', 'onclick' => "navbar.page('history');"],
    ['title' => 'Банлист',                  'img' => 'banlist', 'onclick' => "navbar.page('banlist', server.value)"]
];

//add buttons if admin
if ($group == 15) {
    array_unshift(
        $actions,
        ['title' => 'Добавить блок',             'img' => 'add', 'onclick' => "navbar.pageAjax('admin');"],
        ['title' => 'Редактировать игрока(-ов)', 'img' => 'user', 'onclick' => "navbar.pageAjax('edituser', usercheck.value);"]
    );
}

$cats = '';
$siz1 = count($cat);
for ($i = 0, $size = $siz1; $i < $size; ++$i) {
    $cats .= '<option value="' . $i . '">' . $cat[$i] . '</option>';
}

$_POST['lk'] = 1;

$ajax_path = blockshop_public('ajaxbuy.php');
$url_index = "/{$dir}ajaxbuy.php";;


include_once 'navbar_template.php';
