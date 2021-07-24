<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$fiximgqueryroute = explode('?', $_SERVER["REQUEST_URI"]);
if (preg_match('/\.(?:png|jpg|jpeg|gif|css|ico|js)$/', $fiximgqueryroute[0])) {
    return false;    // сервер возвращает файлы напрямую.
}

if($_SERVER['REQUEST_URI'] === '/') {
    include_once 'index.php';
    exit;
}

if (file_exists(dirname(__FILE__).$_SERVER['REQUEST_URI'])) {
    include_once dirname(__FILE__).$_SERVER['REQUEST_URI'];
} else {
    include_once 'index.php';
    exit;
}
