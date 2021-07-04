<?php
define('BLOCKSHOP', true);
include_once 'config.php';


$head = '';
$add = '';
$userv = '';

require_once 'router.php';
require_once 'core/check_login.php';
require_once 'core/check_session.php';

// это чтоб ажаксом страницы норм подгружались, шапку не грузим если ажаксом
if (is_not_routes('shop', 'lk', 'banlist')) {
    require_once 'core/navbar_template.php';
}

///шапка магазина///
/*if (!is_route('shop') and !is_route('lk') and !is_route('banlist')) {

    echo '<script>var url1 = \'/' . $dir . 'ajaxbuy.php\';var url2 = \'/' . $dir . 'index.php\'; var url3 = \'/' . $dir . '\';</script><link rel="stylesheet" type="text/css" href="/' . $dir . 'shop.css" /><script type="text/javascript" src="/' . $dir . 'shop.js"></script>';

    if ($group == 15)
        $add = '<input type="image" class="imgbtn" src="/' . $dir . 'img/user.png" onclick="props(\'edituser\');" title="Редактировать игрока(-ов)"> <input type="image" class="imgbtn" src="/' . $dir . 'img/add.png" onclick="bedit(\'admin=0\');" title="Добавить блок">';
    if (isset($group))
        $userv .= str_replace(array('{dir}', '{add}'), array($dir, $add), $userdesign);
    if ($group != '-1')
        //$head = str_replace(array('{name}', '{srv}', '{cats}', '{user}'), array($username, $serv, $cats, $userv), $headdesign);
        $srv123 = str_replace(array('{name}', '{srv}', '{cats}'), array($username, $serv, $cats), $servdesign);
    $head = str_replace(array('{servdesign}', '{user}'), array($srv123, $userv), $headdesign);
    $_POST['lk'] = 1;
}*/

if (is_route('lk')) {
    if ($_POST['lk'] == 0) {
        require_once 'core/controller_lk.php';
    } else {
        require_once 'core/navbar_template.php';
?>
        <div id="result" style="float:left;width:100%;margin-top:6px;"></div>
        <div id="cont" style="width:100%;line-height: 18px;">
            <? require_once 'core/controller_lk.php'; ?>
        </div>
<?
    }
}

///Банлист///
if (isset($_POST['banlist'])) {
    $server_id = $_POST['banlist']; //sql inject
    include 'core/banlist_template.php';
}

////магазин блоков///
if (isset($_POST['shop'])) {
    shop($_POST['shop']);
}

if (isset($_POST['shop']) & isset($_POST['banlist'])) {
    $server_id = $_POST['shop']; //sql inject
    include 'core/banlist_template.php';
}

function shop($s1)
{
    global $s, $cat, $db, $dir, $blocks, $group, $shopdesign, $sklrub, $skleco, $badly, $icons, $c, $username, $cats, $servdesign, $userv, $headdesign;
    list($srv, $ct) = explode(":", $s1);

    if (ctype_digit($srv) and isset($s[$srv])) {
        $serv = $s[$srv];
    } else {
        $serv = $s[0];
    }

    if (ctype_digit($ct) and isset($cat[$ct]) and $cat[$ct] != $cat['0']) {
        $category = $cat[$ct];
    } else {
        $category = '%%%%';
    }

    $q = $db->select("SELECT * FROM {$blocks} WHERE server='{$serv}' and category LIKE '{$category}' ORDER BY id");

    if (count($q) == 0) {
        $c .= str_replace('{msg}', 'В данной категории не найдено товара!', $badly);
    }

    $search = array('{id}', '{info}', '{admin}', '{name}', '{dir}', '{img}', '{money}', '{amount}', '{icons}');
    for ($i = 0; $i < count($q); $i++) {
        $s2 = "";
        if ($group == 15) {
            $s2 = '<input type="button" onclick="bedit(\'admin=' . $q[$i]['id'] . '\');" class="ud um" title="Редактировать"><input type="button" onclick="del(\'' . $q[$i]['id'] . '\')" class="ud uk" title="Удалить">';
        }
        if ($q[$i]['price'] != 0 and $q[$i]['realprice'] != 0) {
            $s3 = $q[$i]['realprice'] . ' ' . $sklrub['3'] . '/' . $q[$i]['price'] . ' ' . $skleco['3'];
        } elseif ($q[$i]['price'] == 0) {
            $s3 = $q[$i]['realprice'] . ' ' . $sklrub['3'];
        } else {
            $s3 = $q[$i]['price'] . ' ' . $skleco['3'];
        }
        $replace = array($q[$i]['id'], $q[$i]['info'], $s2, $q[$i]['name'], $dir, $q[$i]['image'], $s3, $q[$i]['amount'], $icons);
        $c .= str_replace($search, $replace, $shopdesign);
    }

    $srv = str_replace(array('{name}', '{srv}', '{cats}'), array($username, $serv, $cats), $servdesign);
    $head = str_replace(array('{servdesign}', '{user}'), array($srv, $userv), $headdesign);

    $c .= $head;
    die($c);
}

///Примечание: все запросы хорошо фильтруются, дыр и инъекций, как может многим показаться, здесь нет.
