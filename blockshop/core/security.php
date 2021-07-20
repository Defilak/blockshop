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
    _exit_with_template('auth1');
}

$user = user_from_session();
$username = $user->username;

$economy = $user->getEconomy();
$money = $economy->money;
$iconomy = $economy->balance;
$group = $economy->group;
$bancount = $economy->bancount;
$buys = $economy->buys;
$ban = $user->getBanEntry() ? 1 : 0;