<?php

if (!defined('BLOCKSHOP')) {
    die;
}

require_once 'class/DB.php';

// Подключение базы 
DB::initParams([
    'host' => $mysql_host,
    'port' => $mysql_port,
    'db_name' => $mysql_db,
    'user' => $mysql_user,
    'password' => $mysql_pass
]);

//удалировать
$pdo = DB::getConnection();