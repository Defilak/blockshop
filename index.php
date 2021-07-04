<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'blockshop/pages/page_container.php';
//has auth
/*if(isset($_SESSION['shopname'])) {
    require_once 'blockshop/pages/page_container.php';
    die;
} else {
    define('BLOCKSHOP', true);
    require_once 'blockshop/pages/auth.page.php';
    die;
}*/
/*if(empty($_SERVER['REQUEST_URI'])) {
    include_once 'index.php';
}


/*if (preg_match('/\.(?:png|jpg|jpeg|gif|css|ico|js)$/', $_SERVER["REQUEST_URI"])) {
    return false;    // сервер возвращает файлы напрямую.
} else {
    include_once 'index.php';
}*/

/*function is_route($route) {
    return $route === $_SERVER["REQUEST_URI"];
}
*/