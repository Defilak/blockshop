<?php

namespace pages;

include_once 'ajax/responses.php';

use DB;
use responses;

/**
 * Добавление\редактирование блока
 */
return function ($arg) {
    global $icons, $blocks;

    if (!is_numeric($arg)) {
        responses\warning("Неверно заполнено одно из полей!");
    }

    //Получаем все изображения блоков
    $block_icons = scandir(blockshop_root($icons));
    array_shift($block_icons);
    array_shift($block_icons);// криво убираю . и ..
    $data = [
        'block_icons' => $block_icons
    ];

    //если редактирование
    if($arg != 0) {
        $stmt = DB::prepare("SELECT * FROM {$blocks} WHERE id = :id");
        $stmt->bindValue(':id', $arg);
        $stmt->execute();

        $row = $stmt->fetch();
        $data['product_data'] = $row[0];
    } else {
        $data['product_data'] = [
            'id' => 0,
            'block_id' => 0,
            'name' => '',
            'amount' => 1,
            'price' => 1,
            'action' => 0,
            'realprice' => 0,
            'image' => ''
        ];
    }
    _exit_with_template('admin', $data);
};