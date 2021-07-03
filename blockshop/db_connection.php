<?php

///подключение к mysql///
require_once "../lib/class.simpleDB.php";
require_once "../lib/class.simpleMysqli.php";

$conn = array(
    'server' => $mysql_host,
    'username' => $mysql_user,
    'password' => $mysql_pass,
    'db' => $mysql_db,
    'port' => '3306',
    'charset' => $charset,
);

$db = new simpleMysqli($conn);
