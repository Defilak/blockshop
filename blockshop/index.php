<?php

//define autoloader
spl_autoload_register(function ($className) {
    $className = str_replace("\\", DIRECTORY_SEPARATOR, $className);
    include_once $_SERVER['DOCUMENT_ROOT'] . '/blockshop/class/' . $className . '.php';
});

define('BLOCKSHOP', true);
require_once 'config.php';
require_once 'views/design.php';
require_once 'router.php';


//типа контроллер такой 😎
if(is_route('/auth')) {
    require_once 'core/check_login.php';
}

//типа миддлвар еее 😎😎
require_once 'core/security.php';

// это чтоб ажаксом страницы норм подгружались, шапку не грузим если ажакс
if (is_not_routes('shop', 'lk', 'banlist')) {
    require_once 'core/navbar_template.php';
}

if (is_route('lk')) {
    if ($_POST['lk'] == 0) {
        //require_once 'core/controller_lk.php';
        
        echo '<div id="result"></div><div id="cont">';
        require_once 'core/controller_lk.php';
        echo '</div>';
    } else {
        require_once 'core/navbar_template.php';

        echo '<div id="result"></div><div id="cont">';
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
