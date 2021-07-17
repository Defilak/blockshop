<?php

$server_list = array('Medieval', 'Imphar'); ///массив серверов(первое по умолчанию) $s
$shop_categories = array('Все', 'Блоки', 'Инструменты', 'Еда', 'Оружие', 'Одежда'); ///массив категорий (первое значение выводит все блоки) $cat

$server_id = '';
$category_id = '';
list($server_id, $category_id) = explode(':', $s1);

if (is_numeric($server_id) && isset($server_list[$server_id])) {
    $current_server = $server_list[$server_id];
} else {
    $current_server = $server_list[0];
}

if (is_numeric($category_id) && isset($shop_categories[$category_id]) && $shop_categories[$category_id] != $shop_categories[0]) {
    $category = $shop_categories[$category_id];
} else {
    $category = '%%%%';
}

$stmt = $pdo->prepare("SELECT * FROM {$blocks} WHERE server = :server and category LIKE :category ORDER BY id");
$stmt->bindValue(':server', $current_server);
$stmt->bindValue(':category', $category);
$stmt->execute();

$result = $stmt->fetchAll();

function get_display_price($product)
{
    global $sklrub, $skleco;

    if ($product['price'] != 0 && $product['realprice'] != 0) {
        return "{$product['realprice']}{$sklrub[3]}/{$product['price']}{$skleco[3]}";
    } elseif ($product['price'] == 0) {
        return "{$product['realprice']}{$sklrub[3]}";
    } else {
        return "{$product['price']}{$skleco[3]}";
    }
}

?>
<div id="cont" style="width:100%;line-height: 18px;">
    <? if (count($result) == 0) { ?>
        В данной категории не найдено товара!
    <? } else { ?>
        <? foreach ($result as $product) { ?>
            <div id="m<?= $product['id'] ?>" class="presale">
                <div class="button" title="<?= $product['info'] ?>" style="margin:2px;height:152px;font-size:10px;">
                    <? if ($group == 15) { ?>
                        <input type="button" onclick="bedit('admin=<?= $product['id'] ?>');" class="ud um" title="Редактировать">
                        <input type="button" onclick="del('<?= $product['id'] ?>')" class="ud uk" title="Удалить">
                    <? } ?>

                    <center>
                        <b><?= $product['name'] ?></b>
                        <br />
                        <img src="/<?= "{$dir}{$icons}{$product['image']}" ?>"><br />
                        <b><?= get_display_price($product) ?> за <?= $product['amount'] ?> шт.</b>
                        <br>Кол-во: <input type="number" style="width:50px;" id="p<?= $product['id'] ?>" onkeyup="this.value=this.value.replace(/[^\d\.]+/g,'');" value="1"> шт.<br />
                        <br>
                        <input type="button" class="button" value="Купить" onclick="buy('<?= $product['id'] ?>');" style="width:50%">
                    </center>
                </div>
            </div>
        <? } ?>
    <? } ?>
</div>