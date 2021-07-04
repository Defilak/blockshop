<?php

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
