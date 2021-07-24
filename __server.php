<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function str_starts_with(string $haystack, string $needle): bool
{
    $length = strlen($needle);
    return substr($haystack, 0, $length) === $needle;
}

$fiximgqueryroute = explode('?', $_SERVER["REQUEST_URI"]);
if (preg_match('/\.(?:png|jpg|jpeg|gif|css|ico|js)$/', $fiximgqueryroute[0])) {
    return false;    // сервер возвращает файлы напрямую.
}


if (in_array($_SERVER['REQUEST_URI'], ['/', 'index.php'])) {
    include_once 'index.php';
    exit;
}

//load files
if (file_exists(dirname(__FILE__) . $_SERVER['REQUEST_URI'])) {
    include_once dirname(__FILE__) . $_SERVER['REQUEST_URI'];
}
