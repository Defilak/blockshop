-- phpMyAdmin SQL Dump
-- version 4.0.10.10
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Июл 03 2021 г., 20:31
-- Версия сервера: 5.6.26
-- Версия PHP: 5.6.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `royalworld`
--

-- --------------------------------------------------------

--
-- Структура таблицы `banlist`
--

CREATE TABLE IF NOT EXISTS `banlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(36) NOT NULL,
  `reason` text NOT NULL,
  `admin` varchar(32) NOT NULL,
  `time` bigint(20) NOT NULL,
  `temptime` bigint(20) NOT NULL,
  `type` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `cab_user`
--

CREATE TABLE IF NOT EXISTS `cab_user` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `game_money` double(64,1) NOT NULL DEFAULT '0.0',
  `real_money` int(6) NOT NULL DEFAULT '0',
  `purchases_count` int(4) NOT NULL DEFAULT '0',
  `ban_count` int(3) NOT NULL DEFAULT '0',
  `group_until` timestamp(6) NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `cab_user`
--

INSERT INTO `cab_user` (`id`, `game_money`, `real_money`, `purchases_count`, `ban_count`, `group_until`) VALUES
(1, 777.0, 228, 0, 0, NULL),
(2, 123.5, 55, 0, 0, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `iConomy`
--

CREATE TABLE IF NOT EXISTS `iconomy` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL,
  `balance` double(64,1) NOT NULL DEFAULT '0',
  `money` int NOT NULL DEFAULT '0',
  `group` int NOT NULL DEFAULT '0',
  `bancount` int NOT NULL DEFAULT '0',
  `buys` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8_general_ci AUTO_INCREMENT=0 ;

--
-- Дамп данных таблицы `iConomy`
--

INSERT INTO `iConomy` (`id`, `username`, `balance`, `money`, `group`, `bancount`, `buys`) VALUES
(645, 'Defi', 12523.0, 104, 15, 0, 0),
(646, 'asdasd', 0.0, 0, 1, 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `Medieval`
--

CREATE TABLE IF NOT EXISTS `Medieval` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player` varchar(45) NOT NULL,
  `item` varchar(100) NOT NULL,
  `amount` varchar(11) NOT NULL,
  `type` varchar(255) DEFAULT 'item',
  `extra` text,
  `img` varchar(20) NOT NULL,
  `name` varchar(40) NOT NULL,
  `price` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `permissions`
--

CREATE TABLE IF NOT EXISTS `permissions` (
  `segment` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `permission` smallint(6) NOT NULL,
  UNIQUE KEY `perm_segment_k` (`segment`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `Rise`
--

CREATE TABLE IF NOT EXISTS `Rise` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player` varchar(45) NOT NULL,
  `item` varchar(100) NOT NULL,
  `amount` varchar(11) NOT NULL,
  `type` varchar(255) DEFAULT 'item',
  `extra` text,
  `img` varchar(20) NOT NULL,
  `name` varchar(40) NOT NULL,
  `price` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sale`
--

CREATE TABLE IF NOT EXISTS `sale` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `sale`
--

INSERT INTO `sale` (`id`, `image`, `block_id`, `amount`, `price`, `realprice`, `name`, `info`, `action`, `server`, `category`) VALUES
(1, '2264.png', '2264', 1, 1, 0, 'asd', '', 0, 'Medieval', 'Все'),
(2, '279.png', '279', 1, 1, 0, 'dsasd', '', 0, 'Medieval', 'Все'),
(3, '35_003.png', '35', 1, 1, 0, 'asdfasd', '', 0, 'Rise', 'Все');

-- --------------------------------------------------------

--
-- Структура таблицы `salelog`
--

CREATE TABLE IF NOT EXISTS `salelog` (
  `name` varchar(20) NOT NULL,
  `info` varchar(255) NOT NULL,
  `date` int(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `salelog`
--

INSERT INTO `salelog` (`name`, `info`, `date`) VALUES
('Defi', 'Перевод:n:123руб. в 12300бит.:n:0:n:264.png', 1513627592),
('Defi', 'Перевод:n:1руб. в 100бит.:n:0:n:264.png', 1513627620);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
