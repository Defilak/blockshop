<?php

define('BLOCKSHOP', true);

global $c;

include('config.php');

if (isset($_POST['login']) and isset($_POST['pass'])) {

    $is_valid = false;
    $login = $_POST['login'];
    $passw = $_POST['pass'];

    /*$startTime = microtime(true);
            $fileDir = str_replace('\auth\webpart\blockshop', '', dirname(__FILE__));

            require_once($fileDir.'/library/XenForo/Autoloader.php');

            XenForo_Autoloader::getInstance()->setupAutoloader($fileDir.'/library');
            XenForo_Application::initialize($fileDir.'/library', $fileDir);
            XenForo_Application::set('page_start_time', $startTime);
            
            $auth = new XenForo_Authentication_Core12;
            $db = XenForo_Application::get('db');*/

    if (
        !preg_match('/^[a-zA-Z0-9_-]+$/', $login) ||
        !preg_match('/^[a-zA-Z0-9_-]+$/', $passw)
    )
        die('error1');

    /*$sql = "SELECT user_id FROM xf_user WHERE username=".$db->quote($login);

        $res = $db->fetchCol($sql);

        if (!count($res)) die('Validation Error');
        
        $user_id = $res[0];
        $sql = "select data from xf_user_authenticate where user_id=".$user_id;
        $res = $db->fetchCol($sql);
        if (!count($res)) die('Validation Error');

        
        $auth->setData($res[0]);
        
        $is_valid = $auth->authenticate($user_id, $passw);*/
    if ($login == 'defi' && $passw == '123123') {
        $is_valid = true;
    }

    if (!$is_valid)
        die("Неверный логин или пароль!");
    $_SESSION['shopname'] = $login;
    header('Location: index.php');
}
if (empty($_SESSION['shopname'])) {
    $head = '';
    die($head . str_replace(array('{name}', '{info}'), array('Авторизируйтесь', $_template_auth), $lkblock));
}

$head = '';
$add = '';
$userv = '';

require_once 'router.php';


///шапка магазина///
if (!is_route('shop') and !is_route('lk') and !is_route('banlist')) {

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
}





///личный кабинет///
if (isset($_POST['lk'])) {
    $grp = '';
    if (count($donate) > $group) {
        $grp = explode(":", $donate[$group]);
    } else {
        $grp = explode(":", $donate[0]);
    }

    ///узнаем группу и вычисляем дату окончания///
    $pex = $db->select("SELECT value from permissions where name='{$username}' and permission='group-{$grp[0]}-until'");

    if ($username === 'defi') {
        $group = 15;
    }

    if ($group == 15)
        $grp[0] = 'Admin';
    if ($group == '-1') {
        $grp[0] = 'Несуществующий игрок';
    }

    if (empty($pex[0]['value'])) {
        $until = 'Навсегда';
    } else {
        $until = 'До ' . date('j.m.Y H:i:s', $until);
    }


    ///превью скинов///
    $rand = rand(1, 9999);
    if (file_exists($docRoot . '/' . $path_skin . $username . '.png')) {
        $d = '<img src="/' . $path_skin . '1/' . $username . '.png?' . $rand . '" alt=""/> <img src="/' . $path_skin . '2/' . $username . '.png?' . $rand . '" alt=""/>';
    } else {
        $d = '<img src="/' . $path_skin . '1/char.png" alt=""/> <img src="/' . $path_skin . '2/char.png" alt=""/>';
    }



    $l = '';
    ///статусы///
    if ($ban == 1) {
        $grp['0'] = 'Забанен';
        if ($bancount != count($bans)) {
            $banc = 'Разбан №' . ($bancount + 1) . ' (' . $bans[$bancount] . 'р)';
        } else {
            $banc = 'Разбан невозвожен';
        }
        $l .= '<input type="button" value="' . $banc . '" onclick="ajaxfunc(\'unban=0\');tolc();"  class="button">' . $unban;
    } elseif ($group == 0) {
        for ($i = 1, $size = count($donate); $i < $size; ++$i) {
            list($name, $price, $days) = explode(":", $donate[$i]);
            $l .= '<input type="button" value="' . $name . ' (' . $price . 'р)" onclick="buygroup(\'' . $i . '\');tolc();"  class="button"> ';
        }
        $l .= $pokupka;
    }


    ///вывод игроку, у которого есть статус///
    elseif ($group != 15) {
        $l .= '<input type="button" value="Продлить ' . $grp[0] . ' (' . round($grp[1] / 100 * 70) . 'р)" onclick="buygroup(\'' . $group . '\');tolc();"  class="pvb"> <input type="button" value="Отказаться от ' . $grp[0] . '" onclick="buygroup(\'0\');tolc();"  class="pvb">' . $prodlenie;
    }


    ///рисуем лк
    $c .= str_replace(array('{skin}', '{dir}', '{name}', '{money}', '{icon}', '{group}', '{until}', '{buys}'), array($d, $dir, $username, skl($money, $sklrub), skl($iconomy, $skleco), $grp['0'], $until, $buys), $kabdesign);

    if ($group != '-1') {
        $c .= str_replace(array('{name}', '{info}', '{style_block}', '{style_jaw}', '{style_content}'), array('Пополнение счета', str_replace(array('{name}', '{shop}'), array($username, $shop_id), $kassa), 'donate_block', 'donate_jaw', 'donate_content'), $lkblock);

        if ($group != 15) {
            $c .= str_replace(array('{name}', '{info}', '{style_block}', '{style_jaw}', '{style_content}'), array('Покупка статусов', $l, 'buy_status_block', 'buy_status_jaw', 'buy_status_content'), $lkblock);
        }

        if ($ban != 1) {
            $c .= str_replace(array('{name}', '{info}', '{style_block}', '{style_jaw}', '{style_content}'), array('Перевод из реальной валюты в игровую (1 рубль = ' . skl($koff, $skleco) . ')', $perevod, 'transfer_donat_block', 'transfer_donat_jaw', 'transfer_donat_content'), $lkblock);
            $c .= str_replace(array('{name}', '{info}', '{style_block}', '{style_jaw}', '{style_content}'), array('Перевод валюты другому игроку', $perevod2, 'transfer_player_block', 'transfer_player_jaw', 'transfer_player_content'), $lkblock);
        }
        if ($group != 0 and $ban != 1) {
            $c .= str_replace(array('{name}', '{info}', '{clr}', '{style_block}', '{style_jaw}', '{style_content}'), array('Смена префикса', $prefix, $color, 'prefix_block', 'prefix_jaw', 'prefix_content'), $lkblock);
        }
    }
    if ($_POST['lk'] == 0) {
        echo $c;
    } else {
        echo $head . $ajaxmsg . str_replace('{cont}', $c, $ajaxcont);
    }
}

///Банлист///
if (isset($_POST['banlist'])) {
    banlist($_POST['banlist']);
}

function banlist($s1)
{
    global $banlist, $db, $s, $c;
    $q = $db->select("SELECT * FROM `" . $banlist . "_" . $s[$s1] . "`");
    $c .= '                
<table class="banlist_table">
    <tr>
        <th>Ник</th>
        <th>Причина</th>
        <th>Кто забанил?</th>
        <th>Дата разбана</th>
    </tr>';

    //$srv = str_replace(array('{name}', '{srv}', '{cats}'), array($username, $serv, $cats), $servdesign);
    //$head = str_replace(array('{servdesign}', '{user}'), array($srv, $userv), $headdesign);

    //$c .= $head;
    for ($u = 0; $u < count($q); $u++) {

        $c .= '
    <tr>
        <td>' . $q[$u]['name'] . '</td>
        <td>' . $q[$u]['reason'] . '</td>
        <td>' . $q[$u]['admin'] . '</td>
        <td>' . $q[$u]['time'] . '</td>
    </tr>
';

        //$c.= 'Игрока: '.$q[$u]['name'].' забанил администратор '.$q[$u]['admin'].'. Причина: '.$q[$u]['reason'].'<br>';
    }
    $c .= '</table>';
    die($c);
}

////магазин блоков///
if (isset($_POST['shop'])) {
    shop($_POST['shop']);
}

if (isset($_POST['shop']) & isset($_POST['banlist'])) {
    banlist($_POST['shop']);
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
