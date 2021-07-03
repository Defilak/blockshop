<?php

if (!defined('BLOCKSHOP')) {
    die("HACKING");
}
session_start();
//session_start();

include 'design.php';
////БД магазина///
$mysql_host = 'localhost';
$mysql_user = 'root';
$mysql_pass = 'root';
$mysql_db = 'blockshop';
$charset = 'UTF8';

//$db_table = 'xf_user';
//$db_tableOther = 'xf_user_authenticate';

$banlist = 'banlist'; //Стандартная таблица банлиста
$blocks = 'sale'; //таблица с блоками
$logs = 'salelog'; ///таблица логов

$icons = 'img/icons/';

$docRoot = getenv("DOCUMENT_ROOT");
$dir = 'blockshop/'; ///папка с данным скриптом (слэш в конце обязательно)
//$cart = array('ShopCart','nickname','item_id','item_amount');///таблица плагина выдачи вещей(таблица, колонка имени, колонка id-блока, колонка кол-во)
$cart = array('ShopCart', 'player', 'item', 'amount'); ///ShoppingCart
$eco = array('iconomy', 'username', 'balance', 'money'); ///игровая валюта(таблица, колонка имени, колонка баланса,колонка реальной валюты)
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
$siz1 = count($cat);
$siz2 = count($enchs);
$siz3 = count($clrs);

global $color, $asd, $serv, $cats;

for ($i = 0, $size = $siz3; $i < $size; ++$i) {
    list($a, $b, $c) = explode(":", $clrs[$i]);
    $color .= '<option value="' . $a . '"  style="background:' . $b . ';">' . $c . '</option>';
}
for ($i = 0, $size = $siz2; $i < $size; ++$i) {
    list($a, $b) = explode(":", $enchs[$i]);
    $asd .= '<option value="' . $a . '">' . $b . '</option>';
}
for ($i = 0, $size = $siz; $i < $size; ++$i) {
    $serv .= '<option value="' . $i . '">' . $s[$i] . '</option>';
}
for ($i = 0, $size = $siz1; $i < $size; ++$i) {
    $cats .= '<option value="' . $i . '">' . $cat[$i] . '</option>';
}
///подключение к mysql///
require_once "lib/class.simpleDB.php";
require_once "lib/class.simpleMysqli.php";

$conn = array(
    'server' => $mysql_host,
    'username' => $mysql_user,
    'password' => $mysql_pass,
    'db' => $mysql_db,
    'port' => '3306',
    'charset' => $charset,
);
$ex = new simpleMysqli($conn);

///определяем переменные пользователя///
$username = isset($_SESSION['shopname']) ? $_SESSION['shopname'] : null;

if ($username) {
    //SELECT * FROM `iConomy` WHERE `username`='defi';1
    $q1 = $ex->select("SELECT * FROM `{$eco[0]}` WHERE `{$eco[1]}`='{$username}';");

    if (count($q1) == 0) {
        //INSERT INTO `iConomy` (id,`username`,`balance`) VALUES (NULL, 'defi','0');
        $ex->insert("INSERT INTO `{$eco[0]}` (id,`{$eco[1]}`,`{$eco[2]}`) VALUES (NULL, '{$username}','{$nominal}');");
        //SELECT * FROM `iconomy` WHERE `username`='defi';
        $q1 = $ex->select("SELECT * FROM `{$eco[0]}` WHERE `{$eco[1]}`='{$username}';");
    }

    $money = $q1[0][$eco[3]];
    $iconomy = $q1[0][$eco[2]];
    $group = $q1[0]['group'];
    $bancount = $q1[0]['bancount'];
    $buys = $q1[0]['buys'];
    $q2 = $ex->select("select * from {$banlist} where name='{$username}'");

    if (count($q2) == 1) {
        $ban = 1;
    } else {
        $ban = 0;
    }
} else {
    $username = 'Не игрок';
    $group = '-1';
}
///вводим глобальную защиту от sql-инъекций)))))
foreach ($_POST as $name => $value) {
    $_POST[$name] = str_replace(array("'", '"', ',', '\\', '<', '>', '$', '%'), '', $value);
}
$a = array_map('trim', $_POST);
$count = count($a);

////функции///
function servlist()
{
    global $s;
    $siz = count($s);
    $l = '';
    for ($i = 0, $size = $siz; $i < $size; ++$i) {
        $l .= '<option value="' . $i . '">' . $s[$i] . '</option>';
    }
    return $l;
}

function catlist()
{
    global $cat;
    $siz = count($cat);
    $l = '';
    for ($i = 0, $size = $siz; $i < $size; ++$i) {
        $l .= '<option value="' . $i . '">' . $cat[$i] . '</option>';
    }
    return $l;
}

function bal($s1, $s2)
{
    global $q1;
    $q = mysqli_fetch_assoc($q1);
    return $q[$s2];
}

function skl($n, $s1)
{
    $n = round($n);
    $m = $n % 10;
    $j = $n % 100;
    if ($m == 1) {
        $s = $s1[0];
    }
    if ($m >= 2 && $m <= 4) {
        $s = $s1[1];
    }
    if ($m == 0 || $m >= 5 || ($j >= 10 && $j <= 20)) {
        $s = $s1[2];
    }
    return $n . ' ' . $s;
}
