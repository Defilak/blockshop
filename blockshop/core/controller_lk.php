<?php

//получаем статус игрока
$user_group;
if (count($player_groups) > $group) {
    $user_group = $player_groups[$group];
} else {
    $user_group = $player_groups[0];
}

if ($group == 15) {
    $user_group['name'] = 'Admin';
} else if ($group == '-1' || $group == -1) {
    $user_group['name'] = 'Несуществующий игрок';
}

// проверяем время окончания статуса
$stmt = $pdo->prepare("SELECT value FROM permissions WHERE name = :username AND permission = :permission");
$stmt->bindValue(':username', $username);
$stmt->bindValue(':permission', "group-{$user_group['name']}-until");
$stmt->execute();

$until = 'Навсегда';
$res = $stmt->fetch();
if(!empty($res)) {
    $until = 'До ' . date('j.m.Y H:i:s', $res['value']);
}


if ($ban) {
    $user_group['name'] = 'Забанен';
}


// превью скинов
$rand = mt_rand(1, 9999);
$skin_preview_front = "/{$path_skin}1/char.png";
$skin_preview_back = "/{$path_skin}2/char.png";
if (file_exists("{$_SERVER['DOCUMENT_ROOT']}/{$path_skin}{$username}.png")) {
    $skin_preview_front = "/{$path_skin}1/$username.png?$rand";
    $skin_preview_back = "/{$path_skin}2/$username.png?$rand";
}

//cveta/
//Массив цветов///
/*$clrs = array(
    '0:black:Черный', '1:#0000bf:Темно-синий', '2:#00bf00:Зеленый', '3:#00bfbf:Темно-голубой', '4:#bf0000:Кровавый',
    '5:#bf00bf:Темно-розовый', '6:#bfbf00:Цвет поноса', '7:#bfbfbf:Серый', '8:#404040:Темно-серый', '9:#4040ff:Синий', 'a:#40ff40:Светло-зеленый',
    'b:#40ffff:Голубой', 'c:#ff4040:Красный', 'd:#ff40ff:Розовый', 'e:#ffff40:Желтый', 'f:#ffffff:',
);*/

$siz3 = count($clrs);
$color = '';
for ($i = 0, $size = $siz3; $i < $size; ++$i) {
    list($a, $b, $c) = explode(":", $clrs[$i]);
    $color .= '<option value="' . $a . '"  style="background:' . $b . ';">' . $c . '</option>';
}


_exit_with_template('cabinet', [
    'skin_preview_front' => $skin_preview_front,
    'skin_preview_back' => $skin_preview_back,
    'money' => $money,
    'exchangeFactor ' => skl($exchangeFactor, $skleco),
    'iconomy' => $iconomy,
    'sklrub' => $sklrub,
    'skleco' => $skleco,
    'user_group' => $user_group,
    'until' => $until,
    'buys' => $buys,
    'group' => $group,
    'shop_id' => $shop_id,
    'username' => $username,
    'ban' => $ban,
    'bancount' => $bancount,
    'bans' => $bans,
    'player_groups' => $player_groups

]);