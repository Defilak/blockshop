<div>
    <p>Выберите картинку блока:</p>
    <br>
    <div class="button" style="height:auto">
        <div class="slcon">
            <div class="item">
                <? foreach ($block_icons as $id => $icon) { ?>
                    <div id="ach">
                        <input type="radio" name="image" id="<?= $icon ?>" value="<?= $icon ?>" onChange="doIcon(this.value);">
                        <label for="<?= $icon ?>"><img src="<?= blockshop_public("assets/img/icons/$icon") ?>"></label>
                    </div>
                <? } ?>
            </div>
        </div>
    </div><br>Текущая картинка блока:<br>

    <div id="img_box" style="background-image: url(<?= blockshop_public("assets/img/icons/{$product_data['image']}") ?>);"></div>
    <input type=hidden id="b1" value="{img}" style="display:none">

    <div class="field">
        ID блока:<br /><input type="text" class="form" pattern="[0-9\.\:]*" id="b2" value="<?= $product_data['block_id'] ?>" required><br>
        Название:<input type="text" class="form" id="b3" value="<?= $product_data['name'] ?>" required><br />
    </div>
    <div class="field">
        Цена(IC)/Цена(Р)/кол-во(шт):<br />
        <input type="number" class="form" style="width:30%" pattern="[0-9]*" id="b5" value="<?= $product_data['price'] ?>" required>
        <input type="number" class="form" style="width:30%" pattern="[0-9]*" id="b10" value="<?= $product_data['realprice'] ?>" required>
        <input type="number" class="form" style="width:30%" pattern="[0-9]*" id="b4" onkeyup="this.value=this.value.replace(/[^\d\.]+/g,\'\');" value="<?= $product_data['amount'] ?>" required>
        <input type="hidden" class="form" style="width:30%" id="b7" value="<?= $product_data['action'] ?>">
        <br />
        Сервер / категория:<br />
        <select id="b6" class="form" style="width:45%"><?= servlist($product_data['server']) ?></select>
        <select id="b9" class="form" style="width:45%"><?= catlist($product_data['category']) ?></select>
        <br>
        <br>
        <button type="button" id="b8" value="<?= $product_data['id'] ?>" onclick="zedit();" class="btn-shop">Сохранить</button>
    </div>
    <div class="field">
        Генератор enchant\'ов:<br />
        <input type="number" style="width:30%" class="form" id="ench2" value="1">
        <select id="ench1" onchange="addench();" class="form" style="width:60%"><?=enchments()?></select>
        <br>
        Id всех чар:
        <br />
        <input type="text" class="form" style="width:60%" id="ench3" name="0" value="0">
        <input type="button" class="form" style="width:30%" value="Наложить" onclick="addench2()">
        <br />
    </div>
</div>