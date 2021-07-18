<?php

//define autoloader
spl_autoload_register(function ($className) {
    $className = str_replace("\\", DIRECTORY_SEPARATOR, $className);
    include_once $_SERVER['DOCUMENT_ROOT'] . '/blockshop/class/' . $className . '.php';
});

define('BLOCKSHOP', true);
include_once 'config.php';
include_once 'design.php';
require_once 'router.php';

// Проверка логинится-ли пользователь
require_once 'core/check_login.php';

// Проверка сессии
require_once 'core/check_session.php';

// это чтоб ажаксом страницы норм подгружались, шапку не грузим если ажакс
if (is_not_routes('shop', 'lk', 'banlist')) {
    require_once 'core/navbar_template.php';
}

if (is_route('lk')) {
    if ($_POST['lk'] == 0) {
        require_once 'core/controller_lk.php';
    } else {
        require_once 'core/navbar_template.php';

        echo '<div id="result" style="float:left;width:100%;margin-top:6px;"></div>
        <div id="cont" style="width:100%;line-height: 18px;">';
        require_once 'core/controller_lk.php';
        echo '</div>';
    }
}

///Банлист///
if (isset($_POST['banlist'])) {
    $server_id = $_POST['banlist']; //sql inject
    include 'core/banlist_template.php';
}

////магазин блоков///
if (isset($_POST['shop'])) {
    //shop($_POST['shop']);
    $s1 = $_POST['shop'];
    include 'core/shop_template.php';
    die;
}

if (isset($_POST['shop']) & isset($_POST['banlist'])) {
    $server_id = $_POST['shop']; //sql inject
    include 'core/banlist_template.php';
}

///Примечание: все запросы хорошо фильтруются, дыр и инъекций, как может многим показаться, здесь нет)))))))))
