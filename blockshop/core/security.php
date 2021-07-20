<?php

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



/*$UserData = [];

$username = isset($_SESSION['shopname']) ? $_SESSION['shopname'] : null;
if ($username != null) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM `{$table_economy['table']}` WHERE `{$table_economy['name']}`= :name;");
        $stmt->bindValue(':name', $username);
        $stmt->execute();


        //create row if no exists
        $user = $stmt->fetch();
        if (empty($user)) {
            $stmt_insert = $pdo->prepare("INSERT INTO `{$table_economy['table']}` (id,`{$table_economy['name']}`,`{$table_economy['balance']}`) VALUES (NULL, :name,:nominal);");
            $stmt_insert->bindValue(':name', $username);
            $stmt_insert->bindValue(':nominal', $nominal);
            $stmt_insert->execute();

            //repeat this query
            $stmt->execute();
            $user = $stmt->fetch();
        }

        //init vars

        $money = $user[$table_economy['money']];
        $iconomy = $user[$table_economy['balance']];
        $group = $user['group'];
        $bancount = $user['bancount'];
        $buys = $user['buys'];
        $ban = 0;

        //todo: join
        //check if banned 
        $stmt_banned = $pdo->prepare("SELECT * FROM {$banlist} WHERE name= :name;");
        $stmt_banned->bindValue(':name', $username);
        $stmt_banned->execute();

        $user_banlist = $stmt_banned->fetch();
        if ($user_banlist) {
            $ban = 1;
        }
    } catch (PDOException $ex) {
        print_r($ex);
    }
} else {
    $username = 'Не игрок';
    $group = -1;
}
*/