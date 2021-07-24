<?php

$actions = [
    ['title' => 'Перейти в Магазин блоков', 'img' => 'shop', 'onclick' => 'navbar.toServer()'/*'setbanlistF();toserver(); '*/],
['title' => 'Перейти в Личный Кабинет', 'img' => 'lk', 'onclick' => 'navbar.page(\'lk\')'/*'setbanlistF();tolc();'*/],
    ['title' => 'Сменить валюту',           'img' => '0', 'onclick' => 'setbanlistF();valuta(); '],
    ['title' => 'Посмотреть корзину',       'img' => 'cart', 'onclick' => "setbanlistF();props('cart');"],
    ['title' => 'Посмотреть историю',       'img' => 'history', 'onclick' => "setbanlistF();props('history');"],
    ['title' => 'Банлист',                  'img' => 'banlist', 'onclick' => 'tobanlist(); setbanlistT();']
];

//add buttons if admin
if ($group == 15) {
    array_unshift(
        $actions,
        ['title' => 'Добавить блок',             'img' => 'add', 'onclick' => "bedit('admin=0');"],
        ['title' => 'Редактировать игрока(-ов)', 'img' => 'user', 'onclick' => "props('edituser');"]
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
