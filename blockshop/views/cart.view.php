<? if (count($result) == 0) { ?>
    <?= responses\infly('Корзина пуста!'); ?>
<? } else { ?>
    <div class="cart-page">
        <?= responses\infly('Здесь отображается список вещей, которые вы можете забрать в игре.<br> Для получения вещей в игре используйте команду: <b>/cart</b>'); ?>
        <div class="d-inline-flex cart-container">
            <? foreach ($result as $item) { ?>
                <div class="img-thumbnail m-1 cart-item">
                    <input type="button" class="cart-item-delete" title="Отказатся от покупки" onclick="delblock('<?= $item['id'] ?>:<?= $item['srv'] ?>');">
                    <div>
                        <p><?= $item['name'] ?></p>
                        <img src="<?= blockshop_public("assets/img/icons/{$item['img']}") ?>">

                        <p><?= $item['amount'] ?> шт.</p>
                        <p>Сервер: <?= $item['srv'] ?></p>
                    </div>
                </div>
            <? } ?>
        </div>
    </div>
<? } ?>