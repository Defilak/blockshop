<?php

namespace security;

use DB;
use PDOException;

class User
{
    public $username;
    public $group;
    public $balance;
    public $money;
    public $bancount;
    public $buys;
    public $ban;

    private function __construct()
    {

    }

    public static function get($username)
    {
        global $table_economy, $nominal;
        $stmt = DB::prepare("SELECT * FROM `{$table_economy['table']}` WHERE `{$table_economy['name']}`= :name;");
        $stmt->bindValue(':name', $username);
        $stmt->execute();

        //create row if no exists
        $user = $stmt->fetch();
        if (empty($user)) {
            $stmt_insert = DB::prepare("INSERT INTO `{$table_economy['table']}` (id,`{$table_economy['name']}`,`{$table_economy['balance']}`) VALUES (NULL, :name,:nominal);");
            $stmt_insert->bindValue(':name', $username);
            $stmt_insert->bindValue(':nominal', $nominal);
            $stmt_insert->execute();

            //repeat this query
            $stmt->execute();
            $user = $stmt->fetch();
        }
    }
}


session_start();

$UserData = [];

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
