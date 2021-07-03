<?php

define('BLOCKSHOP', true);
include('config.php');
if ($group == '-1') {
    badly("Сударь, вам не мешало бы авторизироваться!");
}
$time = time();
if ($count > 1) {
    $_SESSION['buytime'] = $time + 60;
    badly("Фриз тебе на одну минуту за такие дела!");
}
//////Условия использования функций///////
if ($count == 1 and $group != '-1') 
{
    if ($ban == 1) 
    {
        if (isset($a['unban']))
            unban();
            badly("Забаненные игроки не могут этого делать!");
    }
     elseif ($group == 15) 
    {
        if (isset($a['history']) and $s1 = ifuser($a['history'])) 
        {
            history($s1);
        } 
         elseif (isset($a['cart']) and $s1 = ifuser($a['cart'])) 
        {
            cart($s1);
        } 
         elseif (isset($a['admin'])) 
        {
            admin($a['admin']);
        } 
         elseif (isset($a['edit'])) 
        {
            edit($a['edit']);
        } 
         elseif (isset($a['del'])) 
        {
            delblock($a['del']);
        } 
         elseif (isset($a['edituser'])) 
        {
            edituser($a['edituser']);
        } 
         elseif (isset($a['addmoney'])) 
        {
            addmoney($a['addmoney']);
        } 
         elseif (isset($a['setstatus'])) 
        {
            setstatus($a['setstatus']);
        }
    }
    if (isset($a['giveskin'])) 
    {
        sleep(10);
        giveskin();
    } 
    elseif (isset($a['balance'])) 
    {
        balance($a['balance']);
    } 
    elseif (isset($a['banlist'])) 
    {
        banlist();
    } 
    elseif (isset($a['cart'])) 
    {
        cart($username);
    } 
    elseif (isset($a['history'])) 
    {
        history($username);
    } 
    elseif (isset($a['delb'])) 
    {
        back($a['delb']);
    } 
    elseif ($_SESSION['buytime'] > $time) 
    {
        $tm = skl($_SESSION['buytime'] - $time, array('секунду', 'секунды', 'секунд'));
        badly("До следующей операции подождите <b>{$tm}</b>!");
    } 
    elseif (isset($a['buy'])) 
    {
        buyblock($a['buy']);
    } 
    elseif (isset($a['status'])) 
    {
        donate($a['status']);
    } 
    elseif (isset($a['perevod'])) 
    {
        perevod($a['perevod']);
    } 
    elseif (isset($a['toeco'])) 
    {
        toeco($a['toeco']);
    } 
    elseif (isset($a['prefix'])) 
    {
        prefix($a['prefix']);
    } 
    elseif (isset($a['remove'])) 
    {
        removeskin($a['remove']);
    }
}

/////////ФУНКЦИИ///////////
///покупка предмета
function buyblock($s1) {
    global $blocks, $money, $iconomy, $cart, $logs, $username, $sklrub, $skleco, $ex, $buys;
    list($s1, $s2, $ss, $s3) = explode("::", $s1);
    $s3 = ifuser($s3);
    if (!ctype_digit($s2) OR $s2 == '0' OR $s2 > 128 OR !preg_match("|^[\d]+$|", $s1))
        badly("Неверно введено количество предметов!<br>Допустимые значения от 1 до 128 шт!");
    $r = $ex->select("SELECT * FROM {$blocks} WHERE id='{$s1}';"); ///не волнуйтесь, запрос безопасен///
    if (count($r) == 0)
        badly("Данный блок отсутствует в продаже!");
    ///валюты//
    if ($ss == 0) {
        $q = $iconomy;
        $pl = '0';
        $price = $r[0]['price'] * $s2;
        $val = $skleco;
    } else {
        $q = $money;
        $pl = '1';
        $price = $r[0]['realprice'] * $s2;
        $val = $sklrub;
    }
    if ($price == 0)
        badly("Данный тип валюты недоступен для покупки этого предмета!<br/>");
    $amount = $r[0]['amount'] * $s2;
    ///скидки (удалите если не нужно)///
    if ($amount > 63) {
        $price = round($price * 0.95);
        $skidka = 'Скидка <b>5%</b>.';
    }
    if ($r[0]['action'] != '0') {
        $action = 1 - $r[0]['action'] / 100;
        $price = round($price * $action);
        $skidka = 'Скидка <b>' . $r[0]['action'] . '%</b>. ';
    }
    if ($buys > 199) {
        $price = round($price * 0.95);
        $skidka = 'Скидка <b>5%</b> за активность. ';
    }
    ///сопоставление баланса с ценой///
    if ($q < $price)
        badly("У Вас не хватает денег на счету для выполнения данной операции!");
    $now = $q - $price;
    $backprice = round($r[0]['price'] * $s2 / 2);
    $skl1 = skl($price, $val);
    $skl2 = skl($now, $val);
    $skl3 = skl($amount, array('штука', 'штуки', 'штук'));
    $inf = $r[0]['name'];
    if ($s3 != $username) {
        $inf = "Для " . $s3;
        $add = " игроку <b>\"{$s3}\"</b> ";
    }
    $info = $inf . ":n:" . $amount . ":n:" . $price . " " . $val['3'] . ":n:" . $r[0]['image'];
    inbdlog($info);
    upbalance($username, "-" . $price, $pl);
    upprop($username, 'buys=buys+1');
    $q1 = $ex->select("SELECT * from `{$r[0]['server']}` where `{$cart['1']}`='{$s3}' and `{$cart['2']}`='{$r[0]['block_id']}'");
    if (count($q1) == 1) {
        $ex->update("UPDATE `{$r[0]['server']}` set `{$cart['3']}`= `{$cart['3']}` + {$amount}, price=price+{$backprice} where `{$cart['1']}`='{$s3}' and name='{$r[0]['name']}'");
    } else {
        $ex->insert("INSERT INTO `{$r[0]['server']}`(id,`{$cart['1']}`,`{$cart['2']}`,`{$cart['3']}`, img, name, price) VALUES (NULL, '{$s3}','{$r[0]['block_id']}','{$amount}', '{$r[0]['image']}', '{$r[0]['name']}','{$backprice}');");
    }
    uptime(10);
    inlog('log.txt', " Куплен предмет <b>\"{$r[0]['name']}\"</b>{$add}. Количество: <b>{$skl3}</b>. Цена: <b>{$skl1}</b>");
    goodly("Вы купили предмет <b>\"{$r[0]['name']}\"</b>{$add}. Количество: <b>{$skl3}</b>. Цена: <b>{$skl1}</b>.<br>{$skidka}Ваш текущий баланс <b>{$skl2}</b>.<br> Для получения вещей в игре введите команду: <b>/cart</b>");
}

///покупка/продление статусов///
function donate($s1) {
    global $username, $group, $money, $sklrub, $donate, $logs, $time, $ex;
    if (!isset($donate[$s1])) {
        badly("Данный статус не существует!");
    }
    list($name, $price, $days) = explode(":", $donate[$s1]);
    if ($money < $price)
        badly("Недостаточно средств для покупки статуса!");
    ///отключение///
    if ($price == 0) {
        list($n, $p, $a) = explode(":", $donate[$group]);
        $q = $ex->select("SELECT value from permissions where name='{$username}'");
        $tm = ($q[0]['value'] - $time) / 86400;
        $ex->delete("DELETE from permissions where name='{$username}'");
        $ex->delete("DELETE from permissions_inheritance where child='{$username}'");
        $ex->delete("DELETE from permissions_entity where name='{$username}'");
        $price = $p / 30 * $tm / 2;
        $rub = skl($price, $sklrub);
        upgroup($username, $s1);
        upbalance($username, "+" . $price, 1);
        uptime(30);
        $info = "Отказ от {$n}:n:+{$rub}:n:0:n:264.png";
        inbdlog($info);
        goodly("Вы отказались от статуса, вам на счет вернулось <b>{$rub}</b>!");
    }

    $s2 = skl($price, $sklrub);
    $time1 = $days * 86400;
    $date = $time1 + $time;
    $info = "Статус {$name}:n:-{$price} р:n:0:n:264.png";
    inbdlog($info);
    upgroup($username, $s1);
    ///продление///
    if ($group == $s1) {
        $ex->update("UPDATE permissions set value=value+{$time1}, permission='group-{$name}-until' where name='{$username}'");
        $s2 = skl(($price / 100 * 70), $sklrub);
        inlog('log.txt', "Продлен статус {$name}");
        upbalance($username, "-" . round($price / 100 * 70), 1);
        upprop($username, 'buys=buys+10');
        uptime(30);
        goodly("Вы продлили статус <b>{$name}</b> на <b>{$days}</b> дней за <b>{$s2}</b>!");
    }
    ///покупка///
    pex($username, $name, $date);
    inlog('log.txt', "Установлен статус {$name}");
    upbalance($username, "-" . $price, 1);
    upprop($username, 'buys=buys+10');
    uptime(30);
    goodly("Вы купили статус <b>{$name}</b> на <b>{$days}</b> дней за <b>{$s2}</b>!");
}

///банлист///
function banlist() {
    global $banlist, $ex;
    $q = $ex->select("SELECT * FROM `{$banlist}`");
    for ($u = 0; $u < count($q); $u++) {
        $c.= 'Игрока: ' . $q[$u]['name'] . ' забанил администратор ' . $q[$u]['admin'] . '. Причина: ' . $q[$u]['reason'] . '<br>';
    }
    die($c);
}

///склад////
function cart($s1) {
    global $cart, $dir, $cartdesign, $s, $ex, $goodly;
    $m.= infly('Здесь отображается список вещей, которые вы можете забрать в игре.<br> Для получения вещей в игре используйте команду: <b>/cart</b>');
    $siz = count($s);
    for ($i = 0, $size = $siz; $i < $size; ++$i) {
        $q = $ex->select("SELECT * FROM `{$s[$i]}` WHERE `{$cart['1']}`='{$s1}'");
        $search = array('{id}', '{name}', '{dir}', '{img}', '{amount}', '{srv}');
        for ($u = 0; $u < count($q); $u++) {
            $replace = array($q[$u]['id'], $q[$u]['name'], $dir, $q[$u]['img'], $q[$u][$cart['3']], $s[$i]);
            $c.= str_replace($search, $replace, $cartdesign);
        }
    }
    if (empty($c))
        badly('Корзина пуста!');
    die($m . $c);
}

///история///
function history($s1) {
    global $dir, $logs, $historydesign, $ex, $goodly;
    $m.= infly('Здесь отображаются все совершенные вами действия в магазине за ближайшие 10 суток.');
    $q = $ex->select("SELECT * FROM {$logs} WHERE name='{$s1}' ORDER BY date DESC");
    $time = time() - 864000;
    if (count($q) == 0)
        badly('История пуста!');
    $search = array('{name}', '{dir}', '{img}', '{info}', '{date}');
    for ($i = 0; $i < count($q); $i++) {
        $d = date('j.m.Y H:i:s', $q[$i]['date']);
        list($n, $a, $p, $l, $r) = explode(":n:", $q[$i]['info']);
        if ($p != 0) {
            $g = '<b>' . skl($a, array('штука', 'штуки', 'штук')) . ' за ' . $p . '</b>';
        } else {
            $g = '<b>' . $a . '</b>';
        }
        $replace = array($n, $dir, $l, $g, $d);
        $c.= str_replace($search, $replace, $historydesign);
    }
    $ex->delete("DELETE from {$logs} where date<{$time}");
    die($m . $c);
}


///получаем превью скинов
function giveskin() 
{
    global $path_skin, $path_skin_abs, $username, $docRoot;
    $rand = rand(1, 9999);
    
    if (file_exists($docRoot.'/'.$path_skin.'/'.$username.'.png')) 
    {
        $d = '<img src="/'.$path_skin_abs.'1/'.$username.'.png?'.$rand.'" alt=""/> <img src="/'.$path_skin_abs.'2/'.$username.'.png?'.$rand.'" alt=""/>';
    } 
     else 
    {
        $d = '<img src="/'.$path_skin_abs.'1/char.png" alt=""/> <img src="/'.$path_skin_abs.'2/char.png" alt=""/>';
    }
    echo $d;
}

///перевод другому игроку///
function perevod($s1) {
    global $username, $eco, $iconomy, $money, $skleco, $sklrub;
    list($name, $summ, $type) = explode("::", $s1);
    $name = ifuser($name);
    if ($type == 0) {
        $bal = $iconomy;
        $skl = $skleco;
    } else {
        $bal = $money;
        $skl = $sklrub;
    }
    if ($summ < 1)
        badly("Очень щедро с вашей стороны!");
    if (!$ctype_digit($summ) or empty($name))
        badly("Некорректные данные!");
    if ($bal < $summ)
        badly("Недостаточно средств для перевода!");
    if ($summ > 1000)
        badly("Превышен лимит!");
    $skl = skl($summ, $skl);
    upbalance($username, "-" . $summ, $type);
    upbalance($name, "+" . $summ, $type);
    $info = "{$name}:n:{$skl}:n:0:n:264.png";
    inbdlog($info);
    inlog('log.txt', "Перевел {$skl} игроку {$name}");
    uptime(20);
    goodly("Вы перевели <b>{$skl}</b> игроку <b>{$name}</b>!!!");
}

///перевод реальной валюты в игровую///
function toeco($s1) {
    global $username, $money, $skleco, $sklrub, $koff;
    if ($s1 > 200)
        badly("Слишком большая сумма для перевода!");
    if (!preg_match("|^[\d]+$|", $s1) or $s1 < 1)
        badly("Некорректные данные!");
    if ($money < $s1)
        badly("Недостаточно средств для перевода!");
    $s2 = $s1 * $koff;
    upbalance($username, "-" . $s1, '1');
    upbalance($username, "+" . $s2, '0');
    $skl1 = skl($s1, $sklrub);
    $skl2 = skl($s2, $skleco);
    $info = "Перевод:n:{$s1}{$sklrub['3']} в {$s2}{$skleco['3']}:n:0:n:264.png";
    inbdlog($info);
    inlog('log.txt', "Превратил {$skl1} в {$skl2}");
    uptime(20);
    goodly("Вы превратили <b>{$skl1}</b> в <b>{$skl2}</b>!");
}

///разбан///
function unban() {
    global $money, $group, $bans, $banlist, $username, $bancount, $sklrub, $ex;
    if ($bancount == count($bans))
        badly("Кол-во разбанов исчерпано!");
    if ($money < $bans[$bancount])
        badly("Недостаточно средств для покупки разбана!");
    $price = $bans[$bancount];
    $count = $bancount + 1;
    $skl = skl($price, $sklrub);
    upbalance($username, "-" . $price, 1);
    $info = "Разбан №{$count}:n:-{$skl}:n:0:n:264.png";
    inbdlog($info);
    $ex->delete("DELETE from {$banlist} where name='{$username}'");
    upprop($username, 'bancount=' . $count . ',buys=0');
    inlog('admin.txt', "Купил {$count} разбан");
    uptime(20);
    goodly("Вы купили <b>{$count}</b> разбан за <b>{$skl}</b>!");
}

///баланс///
function balance($s1) {
    global $username, $iconomy, $money, $sklrub, $skleco;
    if ($s1 == 0) {
        $s2 = skl(round($iconomy), $skleco);
        $val = 'iConomy';
    } else {
        $s2 = skl(round($money), $sklrub);
        $val = 'Рубли';
    }
    die("Текущая валюта: " . $val . ". Баланс: " . $s2);
}

///возврат покупки///
function back($ss) {
    global $username, $group, $cart, $skleco, $s, $ex;
    list($s1, $s2) = explode(":", $ss);
    if (!ctype_digit($s1) or !in_array($s2, $s))
        badly("Некорректные данные!");
    $q = $ex->select("SELECT price from `{$s2}` where id='{$s1}' and `{$cart['1']}`='{$username}'");
    if (count($q) != 1)
        badly("Указанный блок не найден!");
    $backprice = $q[0]['price'];
    $skl = skl($backprice, $skleco);
    $ex->delete("DELETE from `{$s2}` where id='{$s1}' and `{$cart['1']}`='{$username}'");
    $info = "Возврат с корзины:n:+{$skl}:n:0:n:264.png";
    inbdlog($info);
    upbalance($username, "+" . $backprice, 0);
    inlog('log.txt', "Возвращено {$skl} из корзины!");
    goodly("Блок успешно удален! Возвращено на игровой счет: <b>{$skl}<b>");
}

///добавление префикса и суффикса///
function prefix($s1) {
    global $username, $ex;
    list($color, $prefix, $nick, $suffix) = explode(":", $s1);
    if (!preg_match("|^([0-9\a-f]{1,1})$|is", $color) or !preg_match("|^([0-9\a-z]{1,10})$|is", $prefix) or !preg_match("|^([0-9\a-f]{1,1})$|is", $nick) or !preg_match("|^([0-9\a-f]{1,1})$|is", $suffix))
        badly("Некорректный префикс или суффикс!");
    $info = '&' . $color . '[' . $prefix . ']&' . $nick;
    $ex->update("UPDATE permissions_entity set prefix='{$info}',suffix='&{$suffix}' where name='{$username}'");
    goodly("Ваш префикс и суффикс успешно изменен!");
}

///удаление скина/плаща///
function removeskin($s1) {
    
    global $path_skin, $path_cloak, $username, $docRoot;
    
    if ($s1 == 'skin') {
        unlink($docRoot.'/'.$path_skin.$username.'.png');
        unlink($docRoot.'/'.$path_skin.'1/'.$username.'.png');
        unlink($docRoot.'/'.$path_skin.'2/'.$username.'.png');
        goodly("Скин удален!");
    } else {
        unlink($docRoot.'/'.$path_cloak.$username.'.png');
        goodly("Плащ удален!");
    }
}

if (isset($_FILES['cloak'])) {
    
     global $path_skin, $path_cloak, $username, $docRoot;
     
    $imageinfo = getimagesize($_FILES['cloak']['tmp_name']);
    if ($imageinfo == NULL) {
        echo 'IMAGENULL';
        exit();
    }
    /*if ($imageinfo[2] != 3) {
        exit();
    }*/
    if (    ($image_info[0] == 64 AND $image_info[1] == 32) || 
            ($image_info[0] == 128 AND $image_info[1] == 64) || 
            ($image_info[0] == 256 AND $image_info[1] == 128) || 
            ($image_info[0] == 512 AND $image_info[1] == 256) || 
            ($image_info[0] == 1024 AND $image_info[1] == 512)) 
    {
        
        if ($group > 1)
            $em = 200000;
        else
            $em = 5000;
        if ($_FILES['cloak']['size'] > $em) {
            exit();
        }
        if (move_uploaded_file($_FILES['cloak']['tmp_name'], $docRoot.'/'.$path_cloak.$username.'.png')) {
            exit();
        }
    } else {
        exit();
    }
}

///заливка скина и его рендер///
if (isset($_FILES['skin'])) {
    
        global $path_skin, $path_cloak, $username, $docRoot;
    
    $image_info = getimagesize($_FILES['skin']['tmp_name']);
    
    if ($image_info == NULL) 
    {
        die('Нет картинки');
    }
    
    if ($image_info[2] != 3) 
    {
        die('Неверная инфа');
    }
    if (($image_info[0] == 64 AND $image_info[1] == 32) || ($image_info[0] == 128 AND $image_info[1] == 64) || ($image_info[0] == 256 AND $image_info[1] == 128) || ($image_info[0] == 512 AND $image_info[1] == 256) || ($image_info[0] == 1024 AND $image_info[1] == 512)) {
        
        if ($group > 1)
            $em = 200000;
        else
            $em = 10000;
        
        if ($_FILES['skin']['size'] > $em) {
            die('Неверный размер');
        }
        if (!move_uploaded_file($_FILES['skin']['tmp_name'], $docRoot.'/'.$path_skin.$username.'.png')) {
            die('Ошибка записи');
        }
        
        //Создание превью скина (обязательно создайте папки 1 и 2 в вашай папке со скинами, в папку со скинами положите char.png)
        if (!empty($username))
        {
            $user_name = $username;
        }
        
        $way_skif = $path_skin.$username.'.png';
        
        if (!file_exists($docRoot.'/'.$way_skif))
        {
            $way_skif = $path_skin.'char.png';
        }
        
        $skif = getimagesize($way_skif);
        $h = $skif['0'];
        $w = $skif['1'];
        $ratio = $h / 64;
        
        $way_skin = $path_skin.$username.'.png';
        $way_cloak = $path_cloak.$username.'.png';
        
        if (!file_exists($docRoot.'/'.$way_skin))
            $way_skin = $path_skin.'char.png';
        if (!file_exists($docRoot.'/'.$way_cloak))
            $way_cloak = false;
        else
            $cloak = imagecreatefrompng($way_cloak);
        $skin = imagecreatefrompng($way_skin);
        $preview = imagecreatetruecolor(16 * $ratio, 32 * $ratio);
        $transparent = imagecolorallocatealpha($preview, 255, 255, 255, 127);
        imagefill($preview, 0, 0, $transparent);
        imagecopy($preview, $skin, 4 * $ratio, 0 * $ratio, 8 * $ratio, 8 * $ratio, 8 * $ratio, 8 * $ratio);
        imagecopy($preview, $skin, 0 * $ratio, 8 * $ratio, 44 * $ratio, 20 * $ratio, 4 * $ratio, 12 * $ratio);
        imageflip($preview, $skin, 12 * $ratio, 8 * $ratio, 44 * $ratio, 20 * $ratio, 4 * $ratio, 12 * $ratio);
        imagecopy($preview, $skin, 4 * $ratio, 8 * $ratio, 20 * $ratio, 20 * $ratio, 8 * $ratio, 12 * $ratio);
        imagecopy($preview, $skin, 4 * $ratio, 20 * $ratio, 4 * $ratio, 20 * $ratio, 4 * $ratio, 12 * $ratio);
        imageflip($preview, $skin, 8 * $ratio, 20 * $ratio, 4 * $ratio, 20 * $ratio, 4 * $ratio, 12 * $ratio);
        imagecopy($preview, $skin, 4 * $ratio, 0 * $ratio, 40 * $ratio, 8 * $ratio, 8 * $ratio, 8 * $ratio);
        $fullsize = imagecreatetruecolor(80, 160);
        imagesavealpha($fullsize, true);
        $transparent = imagecolorallocatealpha($fullsize, 255, 255, 255, 127);
        imagefill($fullsize, 0, 0, $transparent);
        imagecopyresized($fullsize, $preview, 0, 0, 0, 0, imagesx($fullsize), imagesy($fullsize), imagesx($preview), imagesy($preview));
        imagepng($fullsize, $path_skin.'1/'.$username.'.png');
        imagecopy($preview, $skin, 4 * $ratio, 8 * $ratio, 32 * $ratio, 20 * $ratio, 8 * $ratio, 12 * $ratio);
        imagecopy($preview, $skin, 4 * $ratio, 0 * $ratio, 24 * $ratio, 8 * $ratio, 8 * $ratio, 8 * $ratio);
        imageflip($preview, $skin, 0 * $ratio, 8 * $ratio, 52 * $ratio, 20 * $ratio, 4 * $ratio, 12 * $ratio);
        imagecopy($preview, $skin, 12 * $ratio, 8 * $ratio, 52 * $ratio, 20 * $ratio, 4 * $ratio, 12 * $ratio);
        imageflip($preview, $skin, 4 * $ratio, 20 * $ratio, 12 * $ratio, 20 * $ratio, 4 * $ratio, 12 * $ratio);
        imagecopy($preview, $skin, 8 * $ratio, 20 * $ratio, 12 * $ratio, 20 * $ratio, 4 * $ratio, 12 * $ratio);
        imagecopy($preview, $skin, 4 * $ratio, 0 * $ratio, 56 * $ratio, 8 * $ratio, 8 * $ratio, 8 * $ratio);
        if ($way_cloak)
            imagecopy($preview, $cloak, 3 * $ratio, 8 * $ratio, 1 * $ratio, 1 * $ratio, 10 * $ratio, 16 * $ratio);
        $fullsize = imagecreatetruecolor(80, 160);
        imagesavealpha($fullsize, true);
        $transparent = imagecolorallocatealpha($fullsize, 255, 255, 255, 127);
        imagefill($fullsize, 0, 0, $transparent);
        imagecopyresized($fullsize, $preview, 0, 0, 0, 0, imagesx($fullsize), imagesy($fullsize), imagesx($preview), imagesy($preview));
        imagepng($fullsize, $path_skin.'2/'.$username.'.png');
        imagedestroy($fullsize);
        imagedestroy($preview);
        imagedestroy($skin);
        if ($way_cloak)
            imagedestroy($cloak);
        uptime(30);
        exit();
    } else {
        die('Неверное разрешение');
    }
}

////Администраторские функции////
function setstatus($s1) {
    global $donate, $sklrub, $group, $username, $ex;
    list($id, $name) = explode(":", $s1);
    if ($id == 'ban') {
        $time = time();
        $ex->insert("INSERT into banlist (name,reason,admin,time,temptime,id) values ('{$name}','Забанен админом','{$username}','{$time}','0',NULL)");
        goodly("Игрок <b>{$name}</b> забанен!");
    }
    $name = ifuser($name);
    if (!isset($donate[$id])) {
        badly("Данная группа не существует!");
    }
    list($n, $p, $d) = explode(':', $donate[$id]);
    $ex->delete("DELETE from banlist where name='{$name}'");
    upgroup($name, $id);
    if ($id == 0) {
        $ex->delete("DELETE from permissions where name='{$name}'");
        $ex->delete("DELETE from permissions_inheritance where child='{$name}'");
        $ex->delete("DELETE from permissions_entity where name='{$name}'");
    } else {
        pex($name, $n, (time() + 1000000));
    }
    inlog('admin.txt', "Игроку {$name} установлена группа {$id}");
    goodly("Статус <b>{$n}</b> установлен игроку <b>{$name}</b>!");
}

function addmoney($s1) {
    global $real, $eco, $ex;
    list($type, $summ, $name) = explode(":", $s1);
    $name = ifuser($name);
    if (!ctype_digit($summ))
        badly("Некорректные данные");
    if ($type == 1)
        $type = 3;
    else
        $type = 2;
    $ex->update("UPDATE `{$eco['0']}` set `{$eco[$type]}`='{$summ}' where `{$eco['1']}`='{$name}'");
    inlog('admin.txt', "Игроку {$name} дано {$summ} {$eco[$type]}");
    goodly("Валюта установлена игроку <b>{$name}</b>!");
}

function edituser($s1) {
    global $dir, $real, $eco, $donate, $edituserhead, $edituserbody, $ex;
    ///список статусов///
    for ($i = 0, $size = count($donate); $i < $size; ++$i) {
        list($s2, $s3, $s3) = explode(":", $donate[$i]);
        $s.='<option value="' . $i . '">' . $s2 . '</option>';
    }
    $q = $ex->select("SELECT * from `{$eco['0']}` where `{$eco['1']}` LIKE '{$s1}%%%%' LIMIT 0,50");
    ///создаем массив забаненных///
    $w = $ex->select("SELECT * from banlist");
    for ($i = 0; $i < count($q); $i++) {
        $arr[] .= $w[0]['name'];
    }
    $search = array('{name}', '{money}', '{icon}', '{opt}', '{bans}', '{dir}', '{buys}');
    for ($i = 0; $i < count($q); $i++) {
        if (in_array($q[$i][$eco['1']], $arr)) {
            $grp = '<option value="ban">Забанен</option>' . $s;
        } else {
            $grp = '<option value="ban">Забанен</option>' . str_replace('value="' . $q[$i]['group'] . '"', 'value="' . $q[$i]['group'] . '" selected', $s);
        }
        if ($q[$i]['group'] == 15) {
            $grp = '<option value="15">Админ</option>' . $s;
        }
        if ($q[$i]['group'] != '-1') {
            $replace = array($q[$i][$eco['1']], round($q[$i][$eco['3']]), round($q[$i][$eco['2']]), $grp, $q[$i]['bancount'], $dir, $q[$i]['buys']);
            $c.= str_replace($search, $replace, $edituserbody);
        }
    }
    die(str_replace('{content}', $c, $edituserhead));
}

function edit($s1) {
    global $blocks, $s, $cat, $ex;
    list($image, $blockid, $name, $amount, $price, $realprice, $server, $action, $mid, $category, $ench) = explode("::", $s1);
    if (!ctype_digit($price) or !ctype_digit($realprice) or !ctype_digit($mid) or !ctype_digit($action) or $action < 0 or $action > 99 or !ctype_digit($amount) or $amount < 1 or !isset($s[$server]) or !isset($cat[$category]) or
            !preg_match("|^([0-9\.\:\-\a-z]{1,8})$|is", $blockid) or !file_exists('images/' . $image) or empty($image))
        badly("В полях ввода обнаружены запрещенные символы!");
    if ($mid != 0) {
        $ex->update("UPDATE {$blocks} SET image='{$image}', block_id='{$blockid}', amount='{$amount}', price='{$price}', realprice='{$realprice}', name='{$name}', action='{$action}', server='{$s[$server]}', category='{$cat[$category]}', info='{$ench}' WHERE id='{$mid}';");
        inlog('admin.txt', "Отредактирован блок под id: {$blockid}");
        goodly("Вы успешно отредактировали блок под ID: " . $blockid);
    } else {
        $ex->insert("INSERT INTO {$blocks}(id,image,block_id,amount,price,realprice,name,action,server,category,info) VALUES (NULL, '{$image}', '{$blockid}', '{$amount}', '{$price}', '{$realprice}', '{$name}', '{$action}', '{$s[$server]}', '{$cat[$category]}','{$ench}');");
        inlog('admin.txt', "Добавлен блок под id: {$blockid}");
        goodly("Вы успешно добавили блок под ID: " . $blockid);
    }
}

function admin($s1) {
    global $dir, $blocks, $admlist, $admbox, $admcont, $ex, $asd;
    if (!ctype_digit($s1))
        badly("Неверно заполнено одно из полей!");
    ///массив изображений блоков
    $files = scandir('images/');
    $l = sizeof($files);
    for ($i = 2; $i < $l; $i++) {
        $imglist .= str_replace(array('{img}', '{dir}'), array($files[$i], $dir), $admlist);
    }
    ///определение значений добавления/редактирования блоков
    $serv = servlist();
    $cats = catlist();
    if ($s1 != 0) {
        $q = $ex->select("SELECT * FROM {$blocks} where id='{$s1}'");
        $f1 = str_replace(array('{dir}', '{img}'), array($dir, $q[0]['image']), $admbox);
        $f2 = $q[0]['block_id'];
        $f3 = $q[0]['name'];
        $f4 = $q[0]['amount'];
        $f5 = $q[0]['price'];
        $f6 = $q[0]['id'];
        $f7 = $q[0]['action'];
        $f8 = $q[0]['realprice'];
        $serv = str_replace('>' . $q[0]['server'], ' selected>' . $q[0]['server'], $serv);
        $cats = str_replace('>' . $q[0]['category'], ' selected>' . $q[0]['category'], $cats);
    } else {
        $f1 = str_replace(array('{dir}', '{img}'), array($dir, '1.png'), $admbox);
        $f4 = "1";
        $f5 = "1";
        $f6 = "0";
        $f7 = "0";
        $f8 = "0";
    }
    $search = array('{f1}', '{f2}', '{f3}', '{f4}', '{f5}', '{f6}', '{f7}', '{f8}', '{imglist}', '{serv}', '{cats}', '{ench}');
    $replace = array($f1, $f2, $f3, $f4, $f5, $f6, $f7, $f8, $imglist, $serv, $cats, $asd);
    $c.=str_replace($search, $replace, $admcont);
    die($c);
}

function delblock($s1) {
    global $blocks, $ex;
    if (!ctype_digit($s1))
        badly("Обнаружены некорректные символы!");
    $ex->delete("DELETE FROM {$blocks} WHERE id='{$s1}';");
    inlog('admin.txt', "Удален блок под id: {$s1}");
    goodly("Блок успешно удален!");
}

///побочные функции///
function ifuser($s1) {
    global $eco, $username, $ex;
    if (empty($s1)) {
        return $username;
    }
    $q = $ex->select("SELECT `{$eco['1']}` from `{$eco['0']}` where `{$eco['1']}`='{$s1}'");
    if (count($q) == 1)
        return $s1;
    else
        badly("Пользователь с данным именем не найден!");
}

function upbalance($s1, $s2, $s3) {
    global $eco, $ex;
    if ($s3 == 1) {
        $s3 = $eco['3'];
    } else {
        $s3 = $eco['2'];
    }
    $ex->update("UPDATE `{$eco['0']}` set {$s3}={$s3}{$s2} where `{$eco['1']}`='{$s1}'");
}

function upgroup($s1, $s2) {
    global $eco, $ex;
    $ex->update("UPDATE `{$eco['0']}` set `group`='{$s2}' where `{$eco['1']}`='{$s1}'");
}

function upprop($s1, $s2) {
    global $eco, $ex;
    $ex->update("UPDATE `{$eco['0']}` set {$s2} where `{$eco['1']}`='{$s1}'");
}

function pex($s1, $s2, $s3) {
    global $ex;
    $ex->insert("INSERT INTO permissions (id, name, type, permission, world, value) VALUES (NULL, '{$s1}', '1', 'group-{$s2}-until', '', '{$s3}')");
    $ex->insert("INSERT INTO permissions_inheritance (id, child, parent, type, world) VALUES (NULL, '{$s1}', '{$s2}', '1', NULL)");
    $ex->insert("INSERT INTO permissions_entity (id, name, type, prefix, suffix) VALUES (NULL, '{$s1}','1', '&2[{$s2}]', '&f')");
}

function goodly($s1) {
    global $mess;
    die(str_replace(array('{type}', '{msg}'), array('goodly', $s1), $mess));
}

function badly($s1) {
    global $mess;
    die(str_replace(array('{type}', '{msg}'), array('badly', $s1), $mess));
}

function infly($s1) {
    global $mess;
    return (str_replace(array('{type}', '{msg}'), array('infly', $s1), $mess));
}

function inlog($s1, $s2) {
    global $username;
    file_put_contents('logs/' . $s1, '[' . date("d/m/Y H:i:s", time()) . '][' . $username . '][' . $_SERVER['REMOTE_ADDR'] . ']=>' . $s2 . "\n", FILE_APPEND);
}

function inbdlog($s1) {
    global $logs, $username, $ex;
    $time = time();
    $ex->insert("INSERT into {$logs} (name,info,date) VALUES ('{$username}','{$s1}','{$time}')");
}

function uptime($s1) {
    global $_SESSION;
    $_SESSION['buytime'] = time() + $s1;
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /* Допустимые пропорции образа */

    const SKIN_BASE = 64;
    const SKIN_PROP = 2; // 64 / 32 

    /*
     * Массив допустимых пропорций плаща (для плаща в MC нет четкой привязки к размеру) 
     * Некоторые плащи используют соотношение 22x17, тогда как обычно используется 
     * соотношение 64x32 с незаполненным пространством
     */

    $cloakProps = array(
        0 => array('base' => 64, 'ratio' => 2),
        1 => array('base' => 22, 'ratio' => 1.29),
    );

    /**
     * Создает изображение головы; вид спереди
     * @param string $way_skin полный путь до файла изображения скина
     * @param int $size размер возвращаемого изображения в пикселях
     * @return resource Возвращает идентификатор GD image при успешном результате и <b>false</b> при ошибке 
     */
    
    function createHead($way_skin, $size = 151)
    {
        if (!$info = isValidSkin($way_skin))
            return false;

        $im = @imagecreatefrompng($way_skin);
        if (!$im)
            return false;

        $av = imagecreatetruecolor($size, $size);
        $mp = $info['scale'];

        imagecopyresized($av, $im, 0, 0, 8 * $mp, 8 * $mp, $size, $size, 8 * $mp, 8 * $mp);
        imagecopyresized($av, $im, 0, 0, 40 * $mp, 8 * $mp, $size, $size, 8 * $mp, 8 * $mp);
        imagedestroy($im);

        return $av;
    }

    /**
     * Создать видовое изображение из скина; фронтальный \ задний вид  
     * @param string $way_skin полный путь до файла изображения скина
     * @param string $way_cloak полный путь до файла изображения плаща ( при необходимости )
     * @param string $side вид спереди - front \ вид сзади - back \ по умолчанию оба вида на одном изображении последовательно
     * @param int $size высота возвращаемого изображения в пикселях (ширина пропорцианальна задаваемой высоте и завист так же от параметра $side)
     * @return resource Возвращает идентификатор GD image при успешном результате и <b>false</b> при ошибке
     */
    
    function createPreview($way_skin, $way_cloak = false, $side = false, $size = 224)
    {
        if (!$info = isValidSkin($way_skin))
            return false;

        $skin = @imagecreatefrompng($way_skin);
        if (!$skin)
            return false;

        $mp = $info['scale'];
        $size_x = (($side) ? 16 : 32);
        $preview = imagecreatetruecolor($size_x * $mp, 32 * $mp);
        $mp_x_h = ($side) ? 0 : imagesx($preview) / 2;

        $transparent = imagecolorallocatealpha($preview, 255, 255, 255, 127);
        imagefill($preview, 0, 0, $transparent);

        if (!$side or $side === 'front') {

            imagecopy($preview, $skin, 4 * $mp, 0 * $mp, 8 * $mp, 8 * $mp, 8 * $mp, 8 * $mp);
            imagecopy($preview, $skin, 0 * $mp, 8 * $mp, 44 * $mp, 20 * $mp, 4 * $mp, 12 * $mp);
            imageflip($preview, $skin, 12 * $mp, 8 * $mp, 44 * $mp, 20 * $mp, 4 * $mp, 12 * $mp);
            imagecopy($preview, $skin, 4 * $mp, 8 * $mp, 20 * $mp, 20 * $mp, 8 * $mp, 12 * $mp);
            imagecopy($preview, $skin, 4 * $mp, 20 * $mp, 4 * $mp, 20 * $mp, 4 * $mp, 12 * $mp);
            imageflip($preview, $skin, 8 * $mp, 20 * $mp, 4 * $mp, 20 * $mp, 4 * $mp, 12 * $mp);
            imagecopy($preview, $skin, 4 * $mp, 0 * $mp, 40 * $mp, 8 * $mp, 8 * $mp, 8 * $mp);
        }
        if (!$side or $side === 'back') {

            imagecopy($preview, $skin, $mp_x_h + 4 * $mp, 8 * $mp, 32 * $mp, 20 * $mp, 8 * $mp, 12 * $mp);
            imagecopy($preview, $skin, $mp_x_h + 4 * $mp, 0 * $mp, 24 * $mp, 8 * $mp, 8 * $mp, 8 * $mp);
            imageflip($preview, $skin, $mp_x_h + 0 * $mp, 8 * $mp, 52 * $mp, 20 * $mp, 4 * $mp, 12 * $mp);
            imagecopy($preview, $skin, $mp_x_h + 12 * $mp, 8 * $mp, 52 * $mp, 20 * $mp, 4 * $mp, 12 * $mp);
            imageflip($preview, $skin, $mp_x_h + 4 * $mp, 20 * $mp, 12 * $mp, 20 * $mp, 4 * $mp, 12 * $mp);
            imagecopy($preview, $skin, $mp_x_h + 8 * $mp, 20 * $mp, 12 * $mp, 20 * $mp, 4 * $mp, 12 * $mp);
            imagecopy($preview, $skin, $mp_x_h + 4 * $mp, 0 * $mp, 56 * $mp, 8 * $mp, 8 * $mp, 8 * $mp);
        }

        if ($way_cloak and !$info = isValidCloak($way_cloak)) {
            $way_cloak = null;
        } else {
            $mp_cloak = $info['scale'];
        }

        $cloak = @imagecreatefrompng($way_cloak);
        if (!$cloak)
            $way_cloak = null;

        if ($way_cloak) {

            if ($mp_cloak > $mp) { // cloak bigger              
                $mp_x_h = ($side) ? 0 : ($size_x * $mp_cloak) / 2;
                $mp_result = $mp_cloak;
            } else {
                $mp_x_h = ($side) ? 0 : ($size_x * $mp) / 2;
                $mp_result = $mp;
            }

            $preview_cloak = imagecreatetruecolor($size_x * $mp_result, 32 * $mp_result);
            $transparent = imagecolorallocatealpha($preview_cloak, 255, 255, 255, 127);
            imagefill($preview_cloak, 0, 0, $transparent);

            // ex. copy front side of cloak to new image

            if (!$side or $side === 'front')
                imagecopyresized(
                    $preview_cloak, // result image
                    $cloak, // source image
                    round(3 * $mp_result), // start x point of result
                    round(8 * $mp_result), // start y point of result
                    round(12 * $mp_cloak), // start x point of source img
                    round(1 * $mp_cloak), // start y point of source img
                    round(10 * $mp_result), // result <- width ->
                    round(16 * $mp_result), // result /|\ height \|/
                    round(10 * $mp_cloak), // width of cloak img (from start x \ y) 
                    round(16 * $mp_cloak) // height of cloak img (from start x \ y) 
                );

            imagecopyresized($preview_cloak, $preview, 0, 0, 0, 0, imagesx($preview_cloak), imagesy($preview_cloak), imagesx($preview), imagesy($preview));

            if (!$side or $side === 'back')
                imagecopyresized(
                    $preview_cloak, 
                    $cloak, 
                    $mp_x_h + 3 * $mp_result, 
                    round(8 * $mp_result), 
                    round(1 * $mp_cloak), 
                    round(1 * $mp_cloak), 
                    round(10 * $mp_result), 
                    round(16 * $mp_result), 
                    round(10 * $mp_cloak), 
                    round(16 * $mp_cloak)
                );

            $preview = $preview_cloak;
        }

        $size_x = ($side) ? $size / 2 : $size;
        $fullsize = imagecreatetruecolor($size_x, $size);

        imagesavealpha($fullsize, true);
        $transparent = imagecolorallocatealpha($fullsize, 255, 255, 255, 127);
        imagefill($fullsize, 0, 0, $transparent);

        imagecopyresized($fullsize, $preview, 0, 0, 0, 0, imagesx($fullsize), imagesy($fullsize), imagesx($preview), imagesy($preview));

        imagedestroy($preview);
        imagedestroy($skin);
        if ($way_cloak)
            imagedestroy($cloak);

        return $fullsize;
    }

    /**
     * Сохранить изображение в формате png; отрисованое по правилам createPreview
     * @param string $way_save путь до сохраняемого файла
     * @param string $way_skin полный путь до файла изображения скина
     * @param string $way_cloak полный путь до файла изображения плаща ( при необходимости )
     * @param string $side вид спереди - front \ вид сзади - back \ по умолчанию обе стороны
     * @param int $size высота возвращаемого изображения в пикселях (ширина пропорцианальна задаваемой высоте и завист так же от параметра $side)
     * @return resource Возвращает идентификатор GD image при успешном результате и <b>false</b> при ошибке
     */
    
    function savePreview($way_save, $way_skin, $way_cloak = false, $side = false, $size = 224)
    {
        if (file_exists($way_save))
            unlink($way_save);

        $new_skin = createPreview($way_skin, $way_cloak, $side, $size);
        if (!$new_skin)
            return false;

        imagepng($new_skin, $way_save);
        return $new_skin;
    }

    /**
     * Сохранить изображение в формате png; отрисованое по правилам createHead
     * @param int $size размер возвращаемого изображения в пикселях для одной стороны
     * @param string $way_save путь до сохраняемого файла
     * @param string $way_skin полный путь до файла изображения скина
     * @return resource Возвращает идентификатор GD image при успешном результате и <b>false</b> при ошибке
     */
    
    function saveHead($way_save, $way_skin, $size = 151)
    {
        if (file_exists($way_save))
            unlink($way_save);

        $new_head = createHead($way_skin, $size);
        if (!$new_head)
            return false;

        imagepng($new_head, $way_save);
        return $new_head;
    }

    /**
     * Проверить, является ли файл изображением, с соответствующими для скина пропорциями
     * @param string $way_skin полный путь до файла изображения скина
     * @return array Если файл не проходит проверку возвращает <b>false</b>, иначе возвращает массив пропорций изображения 
     */
    
    function isValidSkin($way_skin)
    {
        if (!file_exists($way_skin))
            return false;

        if (!$imageSize = getImageSize($way_skin))
            return false;
        if (round(self::SKIN_PROP, 2) != self::getRatio($imageSize))
            return false;

        return array(
            'ratio' => getRatio($imageSize),
            'scale' => getScale($imageSize, self::SKIN_BASE),
        );
    }

    /**
     * Проверить, является ли файл изображением, с соответствующими для плащя пропорциями
     * @param string $way_cloak полный путь до файла изображения плаща
     * @return array Если файл не проходит проверку возвращает <b>false</b>, иначе возвращает массив пропорций изображения
     */
    
    function isValidCloak($way_cloak)
    {
        if (!file_exists($way_cloak))
            return false;
        if (!$imageSize = getImageSize($way_cloak))
            return false;

        for ($i = 0; $i < sizeof($cloakProps); $i++) {
            if (round($cloakProps[$i]['ratio'], 2) != getRatio($imageSize))
                continue;

            return array(
                'ratio' => $cloakProps[$i]['ratio'],
                'scale' => getScale($imageSize, $cloakProps[$i]['base']),
            );
        }
        return false;
    }

    function getScale($inputImg, $size)
    {
        if (!is_array($inputImg) and !$inputImg = getImageSize($inputImg))
            return false;
        return $inputImg[0] / $size;
    }

    function getRatio($inputImg)
    {
        if (!is_array($inputImg) and !$inputImg = getImageSize($inputImg))
            return false;
        return round($inputImg[0] / $inputImg[1], 2);
    }

    function getImageSize($file)
    {
        $imageSize = @getimagesize($file);

        if (empty($imageSize))
            return false;
        return $imageSize;
    }

    function imageflip(&$result, &$img, $rx = 0, $ry = 0, $x = 0, $y = 0, $size_x = null, $size_y = null)
    {
        if ($size_x < 1)
            $size_x = imagesx($img);
        if ($size_y < 1)
            $size_y = imagesy($img);

        imagecopyresampled($result, $img, $rx, $ry, ($x + $size_x - 1), $y, $size_x, $size_y, 0 - $size_x, $size_y);
    }
?>