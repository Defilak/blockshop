<? if (count($result) == 0) { ?>
    В данной категории не найдено товара!
<? } else { ?>
    <? foreach ($result as $product) { ?>
        <div id="m<?= $product->getId() ?>" class="presale">
            <div class="button" title="<?= $product->info ?>" style="margin:2px;height:152px;font-size:10px;">
                <? if ($group == 15) { ?>
                    <input type="button" onclick="bedit('admin=<?= $product->getId() ?>');" class="ud um" title="Редактировать">
                    <input type="button" onclick="del('<?= $product->getId() ?>')" class="ud uk" title="Удалить">
                <? } ?>

                <div>
                    <b><?= $product->name ?></b>
                    <br />
                    <img src="/<?= blockshop_public("assets/img/icons/{$product->image}") ?>"><br />
                    <b><?= pages\get_display_price($product) ?> за <?= $product->amount ?> шт.</b>
                    <br>Кол-во:
                    <input type="number" style="width:50px;" id="p<?= $product->getId() ?>" value="1"> шт.
                    <br />
                    <br>
                    <input type="button" class="button" value="Купить" onclick="buy('<?= $product->getId() ?>');" style="width:50%">
                </div>
            </div>
        </div>
    <? } ?>
<? } ?>