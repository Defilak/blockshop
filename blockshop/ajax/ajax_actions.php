<?php

namespace ajax\actions;

use responses;

/////////ФУНКЦИИ///////////
///покупка предмета
function buyblock($s1)
{
    global $blocks, $money, $iconomy, $table_cart, $logs, $username, $sklrub, $skleco, $db, $buys, $cooldown;
    $add = '';
    $skidka = '';
    list($s1, $s2, $ss, $s3) = explode("::", $s1);
    $s3 = ifuser($s3);
    if (!ctype_digit($s2) or $s2 == '0' or $s2 > 128 or !preg_match("|^[\d]+$|", $s1)) {
        responses\warning("Неверно введено количество предметов!<br>Допустимые значения от 1 до 128 шт!");
    }
    $r = $db->select("SELECT * FROM {$blocks} WHERE id='{$s1}';"); ///не волнуйтесь, запрос безопасен///
    if (count($r) == 0) {
        responses\warning("Данный блок отсутствует в продаже!");
    }
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
    if ($price == 0) {
        responses\warning("Данный тип валюты недоступен для покупки этого предмета!<br/>");
    }
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
    if ($q < $price) {
        responses\warning("У Вас не хватает денег на счету для выполнения данной операции!");
    }
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
    $q1 = $db->select("SELECT * from `{$r[0]['server']}` where `{$table_cart['name']}`='{$s3}' and `{$table_cart['item']}`='{$r[0]['block_id']}'");
    if (count($q1) == 1) {
        $db->update("UPDATE `{$r[0]['server']}` set `{$table_cart['amount']}`= `{$table_cart['amount']}` + {$amount}, price=price+{$backprice} where `{$table_cart['name']}`='{$s3}' and name='{$r[0]['name']}'");
    } else {
        $db->insert("INSERT INTO `{$r[0]['server']}`(id,`{$table_cart['name']}`,`{$table_cart['item']}`,`{$table_cart['amount']}`, img, name, price) VALUES (NULL, '{$s3}','{$r[0]['block_id']}','{$amount}', '{$r[0]['image']}', '{$r[0]['name']}','{$backprice}');");
    }
    $cooldown->update(10);
    inlog('log.txt', " Куплен предмет <b>\"{$r[0]['name']}\"</b>{$add}. Количество: <b>{$skl3}</b>. Цена: <b>{$skl1}</b>");
    responses\success("Вы купили предмет <b>\"{$r[0]['name']}\"</b>{$add}. Количество: <b>{$skl3}</b>. Цена: <b>{$skl1}</b>.<br>{$skidka}Ваш текущий баланс <b>{$skl2}</b>.<br> Для получения вещей в игре введите команду: <b>/cart</b>");
}

///покупка/продление статусов///
function donate($s1)
{
    global $username, $group, $money, $sklrub, $player_groups, $logs, $time, $db, $cooldown;
    if (!isset($player_groups[$s1])) {
        responses\warning("Данный статус не существует!");
    }

    $name = $player_groups[$s1]['name'];
    $price = $player_groups[$s1]['price'];
    $days = $player_groups[$s1]['days'];
    if ($money < $price) {
        responses\warning("Недостаточно средств для покупки статуса!");
    }
    ///отключение///
    if ($price == 0) {
        $n = $player_groups[$group]['name'];
        $p = $player_groups[$group]['price'];
        $a = $player_groups[$group]['days'];
        $q = $db->select("SELECT value from permissions where name='{$username}'");
        $tm = ($q[0]['value'] - $time) / 86400;
        $db->delete("DELETE from permissions where name='{$username}'");
        $db->delete("DELETE from permissions_inheritance where child='{$username}'");
        $db->delete("DELETE from permissions_entity where name='{$username}'");
        $price = $p / 30 * $tm / 2;
        $rub = skl($price, $sklrub);
        upgroup($username, $s1);
        upbalance($username, "+" . $price, 1);
        $cooldown->update(30);
        $info = "Отказ от {$n}:n:+{$rub}:n:0:n:264.png";
        inbdlog($info);
        responses\success("Вы отказались от статуса, вам на счет вернулось <b>{$rub}</b>!");
    }

    $s2 = skl($price, $sklrub);
    $time1 = $days * 86400;
    $date = $time1 + $time;
    $info = "Статус {$name}:n:-{$price} р:n:0:n:264.png";
    inbdlog($info);
    upgroup($username, $s1);
    ///продление///
    if ($group == $s1) {
        $db->update("UPDATE permissions set value=value+{$time1}, permission='group-{$name}-until' where name='{$username}'");
        $s2 = skl(($price / 100 * 70), $sklrub);
        inlog('log.txt', "Продлен статус {$name}");
        upbalance($username, "-" . round($price / 100 * 70), 1);
        upprop($username, 'buys=buys+10');
        $cooldown->update(30);
        responses\success("Вы продлили статус <b>{$name}</b> на <b>{$days}</b> дней за <b>{$s2}</b>!");
    }
    ///покупка///
    pex($username, $name, $date);
    inlog('log.txt', "Установлен статус {$name}");
    upbalance($username, "-" . $price, 1);
    upprop($username, 'buys=buys+10');
    $cooldown->update(30);
    responses\success("Вы купили статус <b>{$name}</b> на <b>{$days}</b> дней за <b>{$s2}</b>!");
}



///получаем превью скинов
function giveskin()
{
    global $path_skin, $path_skin_abs, $username;
    $rand = rand(1, 9999);

    if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $path_skin . $username . '.png')) {
        $d = '<img src="/' . $path_skin . '1/' . $username . '.png?' . $rand . '" alt=""/> <img src="/' . $path_skin . '2/' . $username . '.png?' . $rand . '" alt=""/>';
    } else {
        $d = '<img src="/' . $path_skin . '1/char.png" alt=""/> <img src="/' . $path_skin . '2/char.png" alt=""/>';
    }
    echo $d;
}

///перевод другому игроку///
function perevod($s1)
{
    global $username, $iconomy, $money, $skleco, $sklrub, $cooldown;
    list($name, $summ, $type) = explode("::", $s1);
    $name = ifuser($name);
    if ($type == 0) {
        $bal = $iconomy;
        $skl = $skleco;
    } else {
        $bal = $money;
        $skl = $sklrub;
    }
    if ($summ < 1) {
        responses\warning("Очень щедро с вашей стороны!");
    }
    if (!ctype_digit($summ) or empty($name)) {
        responses\warning("Некорректные данные!");
    }
    if ($bal < $summ) {
        responses\warning("Недостаточно средств для перевода!");
    }
    if ($summ > 1000) {
        responses\warning("Превышен лимит!");
    }
    $skl = skl($summ, $skl);
    upbalance($username, "-" . $summ, $type);
    upbalance($name, "+" . $summ, $type);
    $info = "{$name}:n:{$skl}:n:0:n:264.png";
    inbdlog($info);
    inlog('log.txt', "Перевел {$skl} игроку {$name}");
    $cooldown->update(20);
    responses\success("Вы перевели <b>{$skl}</b> игроку <b>{$name}</b>!!!");
}

///перевод реальной валюты в игровую///
function toeco($s1)
{
    global $username, $money, $skleco, $sklrub, $exchangeFactor, $cooldown;
    if ($s1 > 200) {
        responses\warning("Слишком большая сумма для перевода!");
    }
    if (!preg_match("|^[\d]+$|", $s1) or $s1 < 1) {
        responses\warning("Некорректные данные!");
    }
    if ($money < $s1) {
        responses\warning("Недостаточно средств для перевода!");
    }
    $s2 = $s1 * $exchangeFactor;
    upbalance($username, "-" . $s1, '1');
    upbalance($username, "+" . $s2, '0');
    $skl1 = skl($s1, $sklrub);
    $skl2 = skl($s2, $skleco);
    $info = "Перевод:n:{$s1}{$sklrub['3']} в {$s2}{$skleco['3']}:n:0:n:264.png";
    inbdlog($info);
    inlog('log.txt', "Превратил {$skl1} в {$skl2}");
    $cooldown->update(20);
    responses\success("Вы превратили <b>{$skl1}</b> в <b>{$skl2}</b>!");
}

///разбан///
function unban()
{
    global $money, $group, $bans, $banlist, $username, $bancount, $sklrub, $db, $cooldown;
    if ($bancount == count($bans)) {
        responses\warning("Кол-во разбанов исчерпано!");
    }
    if ($money < $bans[$bancount]) {
        responses\warning("Недостаточно средств для покупки разбана!");
    }
    $price = $bans[$bancount];
    $count = $bancount + 1;
    $skl = skl($price, $sklrub);
    upbalance($username, "-" . $price, 1);
    $info = "Разбан №{$count}:n:-{$skl}:n:0:n:264.png";
    inbdlog($info);
    $db->delete("DELETE from {$banlist} where name='{$username}'");
    upprop($username, 'bancount=' . $count . ',buys=0');
    inlog('admin.txt', "Купил {$count} разбан");
    $cooldown->update(20);
    responses\success("Вы купили <b>{$count}</b> разбан за <b>{$skl}</b>!");
}

///баланс///
function balance($s1)
{
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
function back($ss)
{
    global $username, $group, $table_cart, $skleco, $server_names, $db;
    list($s1, $s2) = explode(":", $ss);
    if (!ctype_digit($s1) or !in_array($s2, $server_names)) {
        responses\warning("Некорректные данные!");
    }
    $q = $db->select("SELECT price from `{$s2}` where id='{$s1}' and `{$table_cart['name']}`='{$username}'");
    if (count($q) != 1) {
        responses\warning("Указанный блок не найден!");
    }
    $backprice = $q[0]['price'];
    $skl = skl($backprice, $skleco);
    $db->delete("DELETE from `{$s2}` where id='{$s1}' and `{$table_cart['name']}`='{$username}'");
    $info = "Возврат с корзины:n:+{$skl}:n:0:n:264.png";
    inbdlog($info);
    upbalance($username, "+" . $backprice, 0);
    inlog('log.txt', "Возвращено {$skl} из корзины!");
    responses\success("Блок успешно удален! Возвращено на игровой счет: <b>{$skl}<b>");
}

///добавление префикса и суффикса///
function prefix($s1)
{
    global $username, $db;
    list($color, $prefix, $nick, $suffix) = explode(":", $s1);
    if (!preg_match("|^([0-9\a-f]{1,1})$|is", $color) or !preg_match("|^([0-9\a-z]{1,10})$|is", $prefix) or !preg_match("|^([0-9\a-f]{1,1})$|is", $nick) or !preg_match("|^([0-9\a-f]{1,1})$|is", $suffix)) {
        responses\warning("Некорректный префикс или суффикс!");
    }
    $info = '&' . $color . '[' . $prefix . ']&' . $nick;
    $db->update("UPDATE permissions_entity set prefix='{$info}',suffix='&{$suffix}' where name='{$username}'");
    responses\success("Ваш префикс и суффикс успешно изменен!");
}

///удаление скина/плаща///
function removeskin($s1)
{
    global $path_skin, $path_cloak, $username;

    if ($s1 == 'skin') {
        unlink($_SERVER['DOCUMENT_ROOT'] . '/' . $path_skin . $username . '.png');
        unlink($_SERVER['DOCUMENT_ROOT'] . '/' . $path_skin . '1/' . $username . '.png');
        unlink($_SERVER['DOCUMENT_ROOT'] . '/' . $path_skin . '2/' . $username . '.png');
        responses\success("Скин удален!");
    } else {
        unlink($_SERVER['DOCUMENT_ROOT'] . '/' . $path_cloak . $username . '.png');
        responses\success("Плащ удален!");
    }
}

if (isset($_FILES['cloak'])) {
    global $path_skin, $path_cloak, $username;

    $imageinfo = getimagesize($_FILES['cloak']['tmp_name']);
    if ($imageinfo == null) {
        echo 'IMAGENULL';
        exit();
    }
    /*if ($imageinfo[2] != 3) {
    exit();
    }*/
    if (($image_info[0] == 64 and $image_info[1] == 32) ||
        ($image_info[0] == 128 and $image_info[1] == 64) ||
        ($image_info[0] == 256 and $image_info[1] == 128) ||
        ($image_info[0] == 512 and $image_info[1] == 256) ||
        ($image_info[0] == 1024 and $image_info[1] == 512)
    ) {
        if ($group > 1) {
            $em = 200000;
        } else {
            $em = 5000;
        }
        if ($_FILES['cloak']['size'] > $em) {
            exit();
        }
        if (move_uploaded_file($_FILES['cloak']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . '/' . $path_cloak . $username . '.png')) {
            exit();
        }
    } else {
        exit();
    }
}

///заливка скина и его рендер///
if (isset($_FILES['skin'])) {
    $image_info = getimagesize($_FILES['skin']['tmp_name']);

    if ($image_info == null) {
        die('Нет картинки');
    }

    if ($image_info[2] != 3) {
        die('Можно загружать только .png');
    }

    if (($image_info[0] == 64 && $image_info[1] == 32) ||
        ($image_info[0] == 128 && $image_info[1] == 64) ||
        ($image_info[0] == 256 && $image_info[1] == 128) ||
        ($image_info[0] == 512 && $image_info[1] == 256) ||
        ($image_info[0] == 1024 && $image_info[1] == 512)
    ) {
        if ($group > 1) {
            $em = 200000;
        } else {
            $em = 10000;
        }

        if ($_FILES['skin']['size'] > $em) {
            die('Неверный размер');
        }
        if (!move_uploaded_file($_FILES['skin']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . '/' . $path_skin . $username . '.png')) {
            die('Ошибка записи');
        }

        //Создание превью скина (обязательно создайте папки 1 и 2 в вашай папке со скинами, в папку со скинами положите char.png)
        if (!empty($username)) {
            $user_name = $username;
        }

        $way_skif = $path_skin . $user_name . '.png';

        if (!file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $dir . $way_skif)) {
            $way_skif = $path_skin_abs . 'char.png';
        }

        $skif = getimagesize($way_skif);
        $h = $skif['0'];
        $w = $skif['1'];
        $ratio = $h / 64;

        $way_skin = $path_skin_abs . $user_name . '.png';
        $way_cloak = $path_cloak . $user_name . '.png';

        if (!file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $dir . $way_skin)) {
            $way_skin = $path_skin_abs . 'char.png';
        }

        if (!file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $way_cloak)) {
            $way_cloak = false;
        } else {
            $cloak = imagecreatefrompng($way_cloak);
        }

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
        imagepng($fullsize, $path_skin_abs . '1/' . $username . '.png');
        imagecopy($preview, $skin, 4 * $ratio, 8 * $ratio, 32 * $ratio, 20 * $ratio, 8 * $ratio, 12 * $ratio);
        imagecopy($preview, $skin, 4 * $ratio, 0 * $ratio, 24 * $ratio, 8 * $ratio, 8 * $ratio, 8 * $ratio);
        imageflip($preview, $skin, 0 * $ratio, 8 * $ratio, 52 * $ratio, 20 * $ratio, 4 * $ratio, 12 * $ratio);
        imagecopy($preview, $skin, 12 * $ratio, 8 * $ratio, 52 * $ratio, 20 * $ratio, 4 * $ratio, 12 * $ratio);
        imageflip($preview, $skin, 4 * $ratio, 20 * $ratio, 12 * $ratio, 20 * $ratio, 4 * $ratio, 12 * $ratio);
        imagecopy($preview, $skin, 8 * $ratio, 20 * $ratio, 12 * $ratio, 20 * $ratio, 4 * $ratio, 12 * $ratio);
        imagecopy($preview, $skin, 4 * $ratio, 0 * $ratio, 56 * $ratio, 8 * $ratio, 8 * $ratio, 8 * $ratio);
        if ($way_cloak) {
            imagecopy($preview, $cloak, 3 * $ratio, 8 * $ratio, 1 * $ratio, 1 * $ratio, 10 * $ratio, 16 * $ratio);
        }
        $fullsize = imagecreatetruecolor(80, 160);
        imagesavealpha($fullsize, true);
        $transparent = imagecolorallocatealpha($fullsize, 255, 255, 255, 127);
        imagefill($fullsize, 0, 0, $transparent);
        imagecopyresized($fullsize, $preview, 0, 0, 0, 0, imagesx($fullsize), imagesy($fullsize), imagesx($preview), imagesy($preview));
        imagepng($fullsize, $path_skin_abs . '2/' . $username . '.png');
        imagedestroy($fullsize);
        imagedestroy($preview);
        imagedestroy($skin);

        if ($way_cloak) {
            imagedestroy($cloak);
        }
        $cooldown->update(30);
        exit();
    } else {
        die('Неверное разрешение');
    }
}

////Администраторские функции////
function setstatus($s1)
{
    global $player_groups, $sklrub, $group, $username, $db;
    list($id, $name) = explode(":", $s1);
    if ($id == 'ban') {
        $time = time();
        $db->insert("INSERT into banlist (name,reason,admin,time,temptime,id) values ('{$name}','Забанен админом','{$username}','{$time}','0',NULL)");
        responses\success("Игрок <b>{$name}</b> забанен!");
    }
    $name = ifuser($name);
    if (!isset($player_groups[$id])) {
        responses\warning("Данная группа не существует!");
    }
    $n = $player_groups[$id]['name'];
    $p = $player_groups[$id]['price'];
    $d = $player_groups[$id]['days'];

    $db->delete("DELETE from banlist where name='{$name}'");
    upgroup($name, $id);
    if ($id == 0) {
        $db->delete("DELETE from permissions where name='{$name}'");
        $db->delete("DELETE from permissions_inheritance where child='{$name}'");
        $db->delete("DELETE from permissions_entity where name='{$name}'");
    } else {
        pex($name, $n, (time() + 1000000));
    }
    inlog('admin.txt', "Игроку {$name} установлена группа {$id}");
    responses\success("Статус <b>{$n}</b> установлен игроку <b>{$name}</b>!");
}

function addmoney($s1)
{
    global $table_economy, $db;
    list($type, $summ, $name) = explode(":", $s1);
    $name = ifuser($name);
    if (!ctype_digit($summ)) {
        responses\warning("Некорректные данные");
    }
    if ($type == 1) {
        $type = 'money';
    } else {
        $type = 'balance';
    }
    $db->update("UPDATE `{$table_economy['table']}` set `{$table_economy[$type]}`='{$summ}' where `{$table_economy['name']}`='{$name}'");
    inlog('admin.txt', "Игроку {$name} дано {$summ} {$table_economy[$type]}");
    responses\success("Валюта установлена игроку <b>{$name}</b>!");
}

function edituser($s1)
{
    global $dir, $table_economy, $player_groups, $edituserhead, $edituserbody, $db;
    ///список статусов///
    $arr = [];
    $s = '';
    $c = '';
    for ($i = 0, $size = count($player_groups); $i < $size; ++$i) {
        $s2 = $player_groups[$i]['name'];
        $s3 = $player_groups[$i]['price'];
        $s3 = $player_groups[$i]['days'];
        $s .= '<option value="' . $i . '">' . $s2 . '</option>';
    }
    $q = $db->select("SELECT * from `{$table_economy['table']}` where `{$table_economy['name']}` LIKE '{$s1}%%%%' LIMIT 0,50");
    ///создаем массив забаненных///
    $w = $db->select("SELECT * from banlist");

    if (!empty($w)) {
        for ($i = 0; $i < count($q); $i++) {
            $arr[] .= $w[0]['name'];
        }
    }

    $search = array('{name}', '{money}', '{icon}', '{opt}', '{bans}', '{dir}', '{buys}');
    for ($i = 0; $i < count($q); $i++) {
        if (in_array($q[$i][$table_economy['name']], $arr)) {
            $grp = '<option value="ban">Забанен</option>' . $s;
        } else {
            $grp = '<option value="ban">Забанен</option>' . str_replace('value="' . $q[$i]['group'] . '"', 'value="' . $q[$i]['group'] . '" selected', $s);
        }
        if ($q[$i]['group'] == 15) {
            $grp = '<option value="15">Админ</option>' . $s;
        }
        if ($q[$i]['group'] != '-1') {
            $replace = array($q[$i][$table_economy['name']], round($q[$i][$table_economy['money']]), round($q[$i][$table_economy['balance']]), $grp, $q[$i]['bancount'], $dir, $q[$i]['buys']);
            $c .= str_replace($search, $replace, $edituserbody);
        }
    }
    die(str_replace('{content}', $c, $edituserhead));
}

function edit($s1)
{
    global $blocks, $server_names, $cat, $db;

    /*if(count(explode("::", $s1)) < 11) {
        warning("В полях ввода обнаружены запрещенные символы!1");
    }*/

    list($image, $blockid, $name, $amount, $price, $realprice, $server, $action, $mid, $category, $ench) = explode("::", $s1);
    if (
        !ctype_digit($price) or !ctype_digit($realprice) or !ctype_digit($mid) or !ctype_digit($action) or $action < 0 or $action > 99 or !ctype_digit($amount) or $amount < 1 or !isset($server_names[$server]) or !isset($cat[$category]) or
        !preg_match("|^([0-9\.\:\-\a-z]{1,8})$|is", $blockid) or !file_exists('images/' . $image) or empty($image)
    ) {
        responses\warning("В полях ввода обнаружены запрещенные символы!");
    }
    if ($mid != 0) {
        $db->update("UPDATE {$blocks} SET image='{$image}', block_id='{$blockid}', amount='{$amount}', price='{$price}', realprice='{$realprice}', name='{$name}', action='{$action}', server='{$server_names[$server]}', category='{$cat[$category]}', info='{$ench}' WHERE id='{$mid}';");
        inlog('admin.txt', "Отредактирован блок под id: {$blockid}");
        responses\success("Вы успешно отредактировали блок под ID: " . $blockid);
    } else {
        $db->insert("INSERT INTO {$blocks}(id,image,block_id,amount,price,realprice,name,action,server,category,info) VALUES (NULL, '{$image}', '{$blockid}', '{$amount}', '{$price}', '{$realprice}', '{$name}', '{$action}', '{$server_names[$server]}', '{$cat[$category]}','{$ench}');");
        inlog('admin.txt', "Добавлен блок под id: {$blockid}");
        responses\success("Вы успешно добавили блок под ID: " . $blockid);
    }
}

function admin($s1)
{
    global $dir, $blocks, $admlist, $admbox, $admcont, $db, $icons;
    if (!ctype_digit($s1)) {
        responses\warning("Неверно заполнено одно из полей!");
    }
    ///массив изображений блоков
    
    $files = scandir(blockshop_root($icons));
    $l = sizeof($files);
    $imglist = '';
    $c = '';
    for ($i = 2; $i < $l; $i++) {
        $imglist .= str_replace(array('{img}', '{dir}', '{icons}'), array($files[$i], $dir, blockshop_public($icons)), $admlist);
    }
    ///определение значений добавления/редактирования блоков
    $serv = servlist();
    $cats = catlist();
    $f2 = '';
    $f3 = '';
    if ($s1 != 0) {
        $q = $db->select("SELECT * FROM {$blocks} where id='{$s1}'");
        $f1 = str_replace(array('{dir}', '{img}', '{icons}'), array($dir, $q[0]['image'], $icons), $admbox);
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
        $f1 = str_replace(array('{dir}', '{img}', '{icons}'), array($dir, '1.png', $icons), $admbox);
        $f4 = "1";
        $f5 = "1";
        $f6 = "0";
        $f7 = "0";
        $f8 = "0";
    }
    $search = array('{f1}', '{f2}', '{f3}', '{f4}', '{f5}', '{f6}', '{f7}', '{f8}', '{imglist}', '{serv}', '{cats}', '{ench}');
    $replace = array($f1, $f2, $f3, $f4, $f5, $f6, $f7, $f8, $imglist, $serv, $cats, enchments());
    $c .= str_replace($search, $replace, $admcont);
    die($c);
}

function delblock($s1)
{
    global $blocks, $db;
    if (!ctype_digit($s1)) {
        responses\warning("Обнаружены некорректные символы!");
    }
    $db->delete("DELETE FROM {$blocks} WHERE id='{$s1}';");
    inlog('admin.txt', "Удален блок под id: {$s1}");
    responses\success("Блок успешно удален!");
}

///побочные функции///
function ifuser($s1)
{
    global $table_economy, $username, $db;
    if (empty($s1)) {
        return $username;
    }
    $q = $db->select("SELECT `{$table_economy['name']}` from `{$table_economy['table']}` where `{$table_economy['name']}`='{$s1}'");
    if (count($q) == 1) {
        return $s1;
    } else {
        responses\warning("Пользователь с данным именем не найден!");
    }
}

function upbalance($s1, $s2, $s3)
{
    global $table_economy, $db;
    if ($s3 == 1) {
        $s3 = $table_economy['money'];
    } else {
        $s3 = $table_economy['balance'];
    }
    $db->update("UPDATE `{$table_economy['table']}` set {$s3}={$s3}{$s2} where `{$table_economy['name']}`='{$s1}'");
}

function upgroup($s1, $s2)
{
    global $table_economy, $db;
    $db->update("UPDATE `{$table_economy['table']}` set `group`='{$s2}' where `{$table_economy['name']}`='{$s1}'");
}

function upprop($s1, $s2)
{
    global $table_economy, $db;
    $db->update("UPDATE `{$table_economy['table']}` set {$s2} where `{$table_economy['name']}`='{$s1}'");
}

function pex($s1, $s2, $s3)
{
    global $db;
    $db->insert("INSERT INTO permissions (id, name, type, permission, world, value) VALUES (NULL, '{$s1}', '1', 'group-{$s2}-until', '', '{$s3}')");
    $db->insert("INSERT INTO permissions_inheritance (id, child, parent, type, world) VALUES (NULL, '{$s1}', '{$s2}', '1', NULL)");
    $db->insert("INSERT INTO permissions_entity (id, name, type, prefix, suffix) VALUES (NULL, '{$s1}','1', '&2[{$s2}]', '&f')");
}

function inlog($s1, $s2)
{
    global $username;

    if (!is_dir(blockshop_root('logs'))) {
        mkdir(blockshop_root('logs'));
    }

    file_put_contents(blockshop_root('logs/') . $s1, '[' . date("d/m/Y H:i:s", time()) . '][' . $username . '][' . $_SERVER['REMOTE_ADDR'] . ']=>' . $s2 . "\n", FILE_APPEND);
}

function inbdlog($s1)
{
    global $logs, $username, $db;
    $time = time();
    $db->insert("INSERT into {$logs} (name,info,date) VALUES ('{$username}','{$s1}','{$time}')");
}

function imageflip(&$result, &$img, $rx = 0, $ry = 0, $x = 0, $y = 0, $size_x = null, $size_y = null)
{
    if ($size_x < 1) {
        $size_x = imagesx($img);
    }
    if ($size_y < 1) {
        $size_y = imagesy($img);
    }
    imagecopyresampled($result, $img, $rx, $ry, ($x + $size_x - 1), $y, $size_x, $size_y, 0 - $size_x, $size_y);
}
