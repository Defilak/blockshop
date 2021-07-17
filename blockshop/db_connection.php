<?php

if (!defined('BLOCKSHOP')) {
    die;
}

// Подключение к mysqli
require_once 'lib/class.simpleDB.php';
require_once 'lib/class.simpleMysqli.php';

$mysqli_settings = array(
    'server' => $mysql_host,
    'username' => $mysql_user,
    'password' => $mysql_pass,
    'db' => $mysql_db,
    'port' => '3306',
    'charset' => $charset,
);

$db = new simpleMysqli($mysqli_settings);


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