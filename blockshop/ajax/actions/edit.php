<?php

namespace ajax\actions;

use responses;


return function($s1)
{
    global $blocks, $server_names, $cat, $db;

    /*if(count(explode("::", $s1)) < 11) {
        warning("В полях ввода обнаружены запрещенные символы!1");
    }*/

    print_r(explode("::", $s1));
    list($image, $blockid, $name, $amount, $price, $realprice, $server, $action, $mid, $category, $ench) = explode("::", $s1);
    if (
        !ctype_digit($price) or !ctype_digit($realprice) or !ctype_digit($mid) or !ctype_digit($action) or $action < 0 or $action > 99 or !ctype_digit($amount) or $amount < 1 or !isset($server_names[$server]) or !isset($cat[$category]) or
        !preg_match("|^([0-9\.\:\-\a-z]{1,8})$|is", $blockid) or !file_exists('images/' . $image) or empty($image)
    ) {
        responses\warning("В полях ввода обнаружены запрещенные символы!");
    }
    if ($mid != 0) {
        $db->update("UPDATE {$blocks} SET image='{$image}', block_id='{$blockid}', amount='{$amount}', price='{$price}', realprice='{$realprice}', name='{$name}', action='{$action}', server='{$server_names[$server]}', category='{$cat[$category]}', info='{$ench}' WHERE id='{$mid}';");
        inlog('admin.txt', "Отредактирован блок под id: {$blockid}");
        responses\success("Вы успешно отредактировали блок под ID: " . $blockid);
    } else {
        $db->insert("INSERT INTO {$blocks}(id,image,block_id,amount,price,realprice,name,action,server,category,info) VALUES (NULL, '{$image}', '{$blockid}', '{$amount}', '{$price}', '{$realprice}', '{$name}', '{$action}', '{$server_names[$server]}', '{$cat[$category]}','{$ench}');");
        inlog('admin.txt', "Добавлен блок под id: {$blockid}");
        responses\success("Вы успешно добавили блок под ID: " . $blockid);
    }
};