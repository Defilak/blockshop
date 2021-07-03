<?php

if(empty($_SERVER['REQUEST_URI'])) {
    include_once 'index.php';
}


if (preg_match('/\.(?:png|jpg|jpeg|gif|css|ico|js)$/', $_SERVER["REQUEST_URI"])) {
    return false;    // сервер возвращает файлы напрямую.
} else {
    include_once 'index.php';
}

function is_route($route) {
    return $route === $_SERVER["REQUEST_URI"];
}
