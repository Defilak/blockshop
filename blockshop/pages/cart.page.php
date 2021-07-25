<?php

namespace pages;

include_once 'ajax/responses.php';

use DB;
use responses;

global $ban;

// Условие использования действия
if ($ban == 1) {
    return false;
}

//склад
return function ($username) {
    global $server_names, $table_cart, $icons;

    $result = [];
    foreach($server_names as $server_name) {
        $stmt = DB::prepare("SELECT * FROM `{$server_name}` WHERE `{$table_cart['name']}`= :username");
        $stmt->bindValue(':username', $username);
        $stmt->execute();

        //$result = $stmt->fetchAll();

        while($row = $stmt->fetch()) {
            $result[] = [
                'id' => $row['id'],
                'name' => $row['name'],
                'img' => $row['img'],
                'amount' => $row['amount'],
                'srv' => $server_name
            ];
        }
    }

    $message = '';
    if(empty($result)) {
        $message = responses\badly('Корзина пуста!');
    } else {
        $message = responses\infly('Здесь отображается список вещей, которые вы можете забрать в игре.<br> Для получения вещей в игре используйте команду: <b>/cart</b>');
    }

    _exit_with_template('cart', [
        'result' => $result,
        'message' => $message,
        'icons' => $icons
    ]);

};