<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('BLOCKSHOP', true);
include('config.php');

//$banlist = 'banlist'; //Стандартная таблица банлиста
//$blocks = 'sale'; //таблица с блоками
//$logs = 'salelog'; ///таблица логов
//$cart = array('GetItem','nickname','item_id','item_amount');///таблица плагина выдачи вещей(таблица, колонка имени, колонка id-блока, колонка кол-во)
///$cart = array('ShopCart','player','item','amount');///ShoppingCart
//$eco = array('iConomy','username','balance');///игровая валюта(таблица, колонка имени, колонка баланса)
//$real = array('money','name','money');///реальная валюта(таблица, колонка имени, колонка баланса)
$prefix = '';

$db->insert("CREATE TABLE IF NOT EXISTS `permissions` (
  `segment` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `permission` smallint(6) NOT NULL,
  UNIQUE KEY `perm_segment_k` (`segment`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

// Банлист
$db->insert("CREATE TABLE IF NOT EXISTS `{$banlist}` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `reason` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `admin` varchar(32) NOT NULL,
  `time` datetime DEFAULT NULL,
  UNIQUE KEY `username` (`name`),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");
echo $db->error();

// Таблица с блоками
$db->insert("CREATE TABLE IF NOT EXISTS `{$prefix}{$blocks}` (
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
) ENGINE = InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1;");

$db->insert("CREATE TABLE IF NOT EXISTS `{$prefix}{$blocks}` (
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

$db->insert("CREATE TABLE IF NOT EXISTS `{$prefix}{$logs}` (
  `name` varchar(20) NOT NULL,
  `info` varchar(255) NOT NULL,
  `date` int(20) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET=utf8;");

$db->insert("CREATE TABLE IF NOT EXISTS `{$eco[0]}` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `{$eco[1]}`  varchar(32) NOT NULL,
  `{$eco[2]}` double(64,1) NOT NULL,
  `{$eco[3]}` int(6) NOT NULL,
  `group` int(2) NOT NULL DEFAULT '0',
  `bancount` int(11) NOT NULL DEFAULT '0',
  `buys` int(5) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `{$eco['1']}`  (`{$eco['1']}` ),
  UNIQUE KEY `id_2` (`id`),
  KEY `id` (`id`),
  KEY `id_3` (`id`)
) ENGINE = InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=645 ;");

$db->insert("CREATE TABLE IF NOT EXISTS `{$prefix}{$real['0']}` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `{$real['1']}` varchar(32) NOT NULL,
  `{$real['2']}` double(64,2) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `id_2` (`id`),
  KEY `id` (`id`),
  KEY `id_3` (`id`)
) ENGINE = InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");


//$s = array('HiTech','SandBox','Golden','McMMO');///массив серверов(первое по умолчанию)
for ($i = 0, $size = count($s); $i < $size; ++$i) {
  $db->insert("CREATE TABLE IF NOT EXISTS `{$prefix}{$s[$i]}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `{$cart['1']}` varchar(45) NOT NULL,
  `{$cart['2']}` varchar(100) NOT NULL,
  `{$cart['3']}` varchar(11) NOT NULL,
  `type` varchar(255) DEFAULT 'item',
  `extra` text,
  `img` varchar(20) NOT NULL,
  `name` varchar(40) NOT NULL,
  `price` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;");
}
echo 'OK!';
