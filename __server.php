<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$asd = explode('?', $_SERVER["REQUEST_URI"]);
if (preg_match('/\.(?:png|jpg|jpeg|gif|css|ico|js)$/', $asd[0])) {
    return false;    // сервер возвращает файлы напрямую.
}

include_once 'index.php';