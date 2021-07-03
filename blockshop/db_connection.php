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


// Подключение PDO
$pdo = new PDO("mysql:host={$mysql_host};dbname={$mysql_db}", $mysql_user, $mysql_pass);
$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); //спавнить предупреждения