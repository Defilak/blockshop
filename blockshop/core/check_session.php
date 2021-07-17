<?php
if (!defined('BLOCKSHOP')) {
    die;
}

// Показываю страницу логина если нет сессии.
if (empty($_SESSION['shopname'])) {
    _include_page('auth.page.php');
    die;
}

$username = isset($_SESSION['shopname']) ? $_SESSION['shopname'] : null;
if ($username) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM `{$eco['table']}` WHERE `{$eco['name']}`= :name;");
        $stmt->bindValue(':name', $username);
        $stmt->execute();


        //create row if no exists
        $user = $stmt->fetch();
        if(empty($user)) {
            $stmt_insert = $pdo->prepare("INSERT INTO `{$eco['table']}` (id,`{$eco['name']}`,`{$eco['balance']}`) VALUES (NULL, :name,:nominal);");
            $stmt_insert->bindValue(':name', $username);
            $stmt_insert->bindValue(':nominal', $nominal);
            $stmt_insert->execute();
            
            //repeat this query
            $stmt->execute();
            $user = $stmt->fetch();
        }

        //init vars
        $money = $user[$eco['money']];
        $iconomy = $user[$eco['balance']];
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
        if($user_banlist) {
            $ban = 1;
        }

    } catch (PDOException $ex) {
        print_r($ex);
    }
} else {
    $username = 'Не игрок';
    $group = -1;
}