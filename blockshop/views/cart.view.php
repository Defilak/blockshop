<div class="cart-page">
    <?= $message ?>
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