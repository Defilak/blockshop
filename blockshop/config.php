<?php

if (!defined('BLOCKSHOP')) {
    die("HACKING");
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//define autoloader
require_once 'core/autoloader.php';
require_once 'core/exception_handler.php';
require_once 'config/config.php';

session_start();

// БД магазина
$db_config = config('database');
$mysql_host = $db_config['host'];
$mysql_user = $db_config['username'];
$mysql_pass = $db_config['password'];
$mysql_db = $db_config['db_name'];
$mysql_port = $db_config['port'];
$charset = $db_config['charset'];

$blocks = 'sale'; //таблица с блоками
$logs = 'salelog'; ///таблица логов

$dir = 'blockshop/'; ///папка с данным скриптом (слэш в конце обязательно)

$icons = 'assets/img/icons/';


function blockshop_root($path = '') {
    return $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'blockshop' . DIRECTORY_SEPARATOR . $path;
}

function blockshop_public($path = '') {
    return 'blockshop/' . $path;
}


//таблицы плагинов
$banlist = 'banlist'; //Стандартная таблица банлиста

$table_cart = [ //таблица плагина выдачи вещей(таблица, колонка имени, колонка id-блока, колонка кол-во)
    'table' => 'ShopCart',
    'name' => 'player',
    'item' => 'item',
    'amount' => 'amount'
];

$table_economy = [ //игровая валюта(таблица, колонка имени, колонка баланса,колонка реальной валюты)
    'table' => 'iconomy',
    'name' => 'username',
    'balance' => 'balance',
    'money' => 'money'
];

// Донатские группы
$player_groups = config('player_groups');

// Названия серверов (первое по умолчанию)
$server_names = config('servers');


$cat = array('Все', 'Блоки', 'Инструменты', 'Еда', 'Оружие', 'Одежда'); ///массив категорий (первое значение выводит все блоки)
$bans = array('30', '50', '100');

$path_skin = blockshop_public('mc/skins/');
$path_skin_abs = blockshop_public('mc/skins/');
$path_cloak = blockshop_public('mc/cloaks/');
$path_cloak_abs = blockshop_public('mc/cloaks/');

$shop_id = '6A440C5A-EFA7-DE3D-E250-6DC0B784B6BA'; ///id магазина интеркассы
$key = 'SaJDLLbhtCTznxLS'; ///ключ интеркассы
$mrh_login = ''; ///логин робокассы
$mrh_pass1 = ''; ///пароль робокассы

$exchangeFactor = '100'; ///отношение iConomy к Рублям (30:1)
$nominal = 0; ///начальный баланс iConomy
$sklrub = ['рубль', 'рубля', 'рублей', 'руб']; ///склонение реальной валюты
$skleco = ['пиксель', 'пикселя', 'пикселей', 'пикс']; ///склонение игровой валюты

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


function skl($number, $wordCases)
{
    $number = round($number);
    $m = $number % 10;
    $j = $number % 100;
    $s = '';
    if ($m == 1) {
        $s = $wordCases[0];
    }
    if ($m >= 2 && $m <= 4) {
        $s = $wordCases[1];
    }
    if ($m == 0 || $m >= 5 || ($j >= 10 && $j <= 20)) {
        $s = $wordCases[2];
    }
    return $number . ' ' . $s;
}
