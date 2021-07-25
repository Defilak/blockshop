<?php

namespace pages;

include_once 'ajax/responses.php';

use DB;
use responses;

if ($group == -1) {
    return false;
}

return function ($username) {
    global $logs;

    $stmt = DB::prepare("SELECT * FROM {$logs} WHERE name= :username AND date > :date ORDER BY date DESC");
    $stmt->bindValue(':username', $username);
    $stmt->bindValue(':date', time() - 864000);
    $stmt->execute();

    $result = [];
    while ($row = $stmt->fetch()) {

        [$name, $amount, $price, $img] = explode(':n:', $row['info']);
        
        if($price != 0) {
            $info = skl($amount, array('штука', 'штуки', 'штук')) . ' за ' . $price;
        } else {
            $info = $amount;
        }

        $result[] = [
            'name' => $name,
            'img' => $img,
            'info' => $info,
            'date' => date('j.m.Y H:i:s', $row['date'])
        ];
    }

    _exit_with_template('history', [
        'result' => $result
    ]);
};