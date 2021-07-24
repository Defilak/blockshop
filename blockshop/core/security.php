<?php

/**
 * Проверка сессии и установка переменных игрока.
 */

namespace security;

use User;

function has_session(): bool
{
    return isset($_SESSION['shopname']);
}

function user_from_session(): User
{
    return User::where('username', $_SESSION['shopname']);
}

if (!has_session()) {
    _exit_with_template('auth');
}

$user = null;
$username = 'Не игрок';
$economy = null;
$money = 0;
$iconomy = 0;
$group = -1;
$bancount = 0;
$buys = 0;
$ban = 0;
//$session_cooldown = 0;

if (has_session()) {
    $user = user_from_session();
    $username = $user->username;

    $economy = $user->getEconomy();
    $money = $economy->money;
    $iconomy = $economy->balance;
    $group = $economy->group;
    $bancount = $economy->bancount;
    $buys = $economy->buys;
    $ban = $user->getBanEntry() ? 1 : 0;
    //$session_cooldown ?? $_SESSION['action_time'];
}

return [
    'user' => $user,
    'username' => $username,
    'economy' => $economy,
    'money' => $money,
    'iconomy' => $iconomy,
    'group' => $group,
    'bancount' => $bancount,
    'buys' => $buys,
    'ban' => $ban,
    //'session_cooldown' => $session_cooldown
];
