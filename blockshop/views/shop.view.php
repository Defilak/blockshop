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
                    <img src="/<?= "{$icons}{$product['image']}" ?>"><br />
                    <b><?= get_display_price($product) ?> за <?= $product['amount'] ?> шт.</b>
                    <br>Кол-во: <input type="number" style="width:50px;" id="p<?= $product['id'] ?>" onkeyup="this.value=this.value.replace(/[^\d\.]+/g,'');" value="1"> шт.<br />
                    <br>
                    <input type="button" class="button" value="Купить" onclick="buy('<?= $product['id'] ?>');" style="width:50%">
                </center>
            </div>
        </div>
    <? } ?>
<? } ?>