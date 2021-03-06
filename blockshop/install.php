<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('BLOCKSHOP', true);
include('config.php');
include 'db_connection.php';

//$banlist = 'banlist'; //Стандартная таблица банлиста
//$blocks = 'sale'; //таблица с блоками
//$logs = 'salelog'; ///таблица логов
//$cart = array('GetItem','nickname','item_id','item_amount');///таблица плагина выдачи вещей(таблица, колонка имени, колонка id-блока, колонка кол-во)
///$cart = array('ShopCart','player','item','amount');///ShoppingCart
//$eco = array('iConomy','username','balance');///игровая валюта(таблица, колонка имени, колонка баланса)
//$real = array('money','name','money');///реальная валюта(таблица, колонка имени, колонка баланса)
$prefix = '';


DB::insert("CREATE TABLE IF NOT EXISTS `permissions` (
  `segment` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `permission` smallint(6) NOT NULL,
  UNIQUE KEY `perm_segment_k` (`segment`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

// Банлист
DB::insert("CREATE TABLE IF NOT EXISTS `{$banlist}` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `reason` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `admin` varchar(32) NOT NULL,
  `time` datetime DEFAULT NULL,
  UNIQUE KEY `username` (`name`),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");

// Таблица с блоками
DB::insert("CREATE TABLE IF NOT EXISTS `{$prefix}{$blocks}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image` varchar(100) COLLATE utf8_bin NOT NULL DEFAULT '1',
  `block_id` varchar(11) COLLATE utf8_bin NOT NULL,
  `amount` int(11) NOT NULL DEFAULT '0',
  `price` int(11) NOT NULL DEFAULT '0',
  `realprice` int(11) NOT NULL,
  `name` varchar(35) CHARACTER SET utf8 NOT NULL,
  `info` varchar(500) COLLATE utf8_bin NOT NULL,
  `action` int(1) NOT NULL DEFAULT '0',
  `server` varchar(40) COLLATE utf8_bin NOT NULL,
  `category` varchar(80) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;");

DB::insert("CREATE TABLE IF NOT EXISTS `{$prefix}{$logs}` (
  `name` varchar(20) NOT NULL,
  `info` varchar(255) NOT NULL,
  `date` int(20) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET=utf8;");

//iconomy
DB::insert("CREATE TABLE IF NOT EXISTS `{$table_economy['table']}` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL,
  `balance` double(64,1) NOT NULL DEFAULT '0',
  `money` int NOT NULL DEFAULT '0',
  `group` int NOT NULL DEFAULT '0',
  `bancount` int NOT NULL DEFAULT '0',
  `buys` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8_general_ci AUTO_INCREMENT=0 ;");

//$s = array('HiTech','SandBox','Golden','McMMO');///массив серверов(первое по умолчанию)
for ($i = 0, $size = count($server_names); $i < $size; ++$i) {
  DB::insert("CREATE TABLE IF NOT EXISTS `{$prefix}{$server_names[$i]}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `{$table_cart['name']}` varchar(45) NOT NULL,
  `{$table_cart['item']}` varchar(100) NOT NULL,
  `{$table_cart['amount']}` varchar(11) NOT NULL,
  `type` varchar(255) DEFAULT 'item',
  `extra` text,
  `img` varchar(20) NOT NULL,
  `name` varchar(40) NOT NULL,
  `price` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;");
}
echo 'OK!';
