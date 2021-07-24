<?php

define('BLOCKSHOP', true);

require_once 'config.php';
require_once 'design.php';
require_once 'router.php';


//типа контроллер такой 😎
if(is_route('/auth')) {
    //require_once 'core/check_login.php';
    require_once 'pages/login.page.php';
}

//типа миддлвар еее 😎😎
require_once 'core/security.php';

// это чтоб ажаксом страницы норм подгружались, шапку не грузим если ажакс
if (is_not_routes('shop', 'lk', 'banlist', 'cart', 'history')) {
    require_once 'core/navbar_component.php';
}

if (is_route('lk')) {
    if ($_POST['lk'] == 0) {
        //require_once 'core/navbar_component.php';
        
        echo '<div id="result" class="my-4"></div><div id="cont">';
        require_once 'pages/cabinet.page.php';
        echo '</div>';
    } else {
        require_once 'core/navbar_component.php';

        echo '<div id="result" class="my-4"></div><div id="cont">';
        require_once 'pages/cabinet.page.php';
        echo '</div>';
    }
}


if (is_route('cart')) {
    $page = require_once 'pages/cart.page.php';
    if($page) {
        $page($_POST['cart']);
    }
}

if(is_route('history')) {
    exit('здесь будет история');
}

///Банлист///
if (isset($_POST['banlist'])) {
    $server_id = $_POST['banlist']; //sql inject
    include 'pages/banlist.page.php';
}

////магазин блоков///
if (isset($_POST['shop'])) {
    $s1 = $_POST['shop'];
    include 'pages/shop.page.php';
    die;
}

if (isset($_POST['shop']) & isset($_POST['banlist'])) {
    $server_id = $_POST['shop']; //sql inject
    include 'pages/banlist.page.php';
}