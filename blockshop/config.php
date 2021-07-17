<?php

if (!defined('BLOCKSHOP')) {
    die("HACKING");
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'exception_handler.php';

session_start();
global $eco;

////БД магазина///
$mysql_host = 'localhost';
$mysql_user = 'root';
$mysql_pass = 'root';
$mysql_db = 'blockshop';
$mysql_port = 3306;
$charset = 'UTF8';

$banlist = 'banlist'; //Стандартная таблица банлиста
$blocks = 'sale'; //таблица с блоками
$logs = 'salelog'; ///таблица логов

$icons = 'img/icons/';

$docRoot = getenv("DOCUMENT_ROOT");
$dir = 'blockshop/'; ///папка с данным скриптом (слэш в конце обязательно)

function blockshop_root($path) {
    return $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'blockshop'. DIRECTORY_SEPARATOR . $path;
}

//таблицы плагинов
$cart = array('ShopCart', 'player', 'item', 'amount'); ///таблица плагина выдачи вещей(таблица, колонка имени, колонка id-блока, колонка кол-во)
$eco = [
    0 => 'iconomy',
    1 => 'username',
    2 => 'balance',
    3 => 'money',
    'table' => 'iconomy',
    'name' => 'username',
    'balance' => 'balance',
    'money' => 'money'
]; ///игровая валюта(таблица, колонка имени, колонка баланса,колонка реальной валюты)
$real = array('money', 'name', 'money'); ///реальная валюта(таблица, колонка имени, колонка баланса)

$donate = array('Игрок:0:0:0', 'Silver:50:730:0', 'Gold:150:30:1'); ///статусы (название,цена,кол-во дней,возможность загрузки HD)
$s = array('Medieval', 'Imphar'); ///массив серверов(первое по умолчанию)
$cat = array('Все', 'Блоки', 'Инструменты', 'Еда', 'Оружие', 'Одежда'); ///массив категорий (первое значение выводит все блоки)
$bans = array('30', '50', '100');

$path_skin = $dir . 'mc/skins/';
$path_skin_abs = 'mc/skins/';
$path_cloak = $dir . 'mc/cloaks/';
$path_cloak_abs = 'mc/cloaks/';

$shop_id = '6A440C5A-EFA7-DE3D-E250-6DC0B784B6BA'; ///id магазина интеркассы
$key = 'SaJDLLbhtCTznxLS'; ///ключ интеркассы
$mrh_login = ''; ///логин робокассы
$mrh_pass1 = ''; ///пароль робокассы

$koff = '100'; ///отношение iConomy к Рублям (30:1)
$nominal = 0; ///начальный баланс iConomy
$sklrub = array('рубль', 'рубля', 'рублей', 'руб'); ///склонение реальной валюты
$skleco = array('пиксель', 'пикселя', 'пикселей', 'пикс'); ///склонение игровой валюты

//
///Массив зачарований///
$enchs = array(
    'q:>>>Для брони<<<', '0:Защита', '1:Огнестойкость', '2:Легкость', '3:Взрывоустойчивость', '4:Снарядостойкость', '5:Дыхание', '6:Родство с водой', '7:Шипы',
    'q:>>>Для меча<<<', '16:Острота', '17:Небесная кара', '18:Бич членистоногих', '19:Отбрасывание', '20:Аспект огня', '21:Мародерство',
    'q:>>>Для инструментов<<<', '32:Эффективность', '33:Шелковое касание', '34:Неразрушимость', '35:Удача',
    'q:>>>Для лука<<<', '48:Сила', '49:Ударная волна', '50:Воспламенение', '51:Бесконечность',
);

///Массив цветов///
$clrs = array(
    '0:black:Черный', '1:#0000bf:Темно-синий', '2:#00bf00:Зеленый', '3:#00bfbf:Темно-голубой', '4:#bf0000:Кровавый',
    '5:#bf00bf:Темно-розовый', '6:#bfbf00:Цвет поноса', '7:#bfbfbf:Серый', '8:#404040:Темно-серый', '9:#4040ff:Синий', 'a:#40ff40:Светло-зеленый',
    'b:#40ffff:Голубой', 'c:#ff4040:Красный', 'd:#ff40ff:Розовый', 'e:#ffff40:Желтый', 'f:#ffffff:',
);
$siz = count($s);
$siz2 = count($enchs);

global $asd, $serv, $cats, $q1;

for ($i = 0, $size = $siz2; $i < $size; ++$i) {
    list($a, $b) = explode(":", $enchs[$i]);
    $asd .= '<option value="' . $a . '">' . $b . '</option>';
}

for ($i = 0, $size = $siz; $i < $size; ++$i) {
    $serv .= '<option value="' . $i . '">' . $s[$i] . '</option>';
}

require_once 'db_connection.php';
require_once 'common.php';