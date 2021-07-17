<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (preg_match('/\.(?:png|jpg|jpeg|gif|css|ico|js)$/', $_SERVER["REQUEST_URI"])) {
    return false;    // сервер возвращает файлы напрямую.
}

require_once 'core/bootstrap.php';