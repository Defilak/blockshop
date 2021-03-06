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

    _exit_with_template('cart', [
        'result' => $result,
        'icons' => $icons
    ]);
};