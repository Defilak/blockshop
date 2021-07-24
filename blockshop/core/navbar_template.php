<script type="text/javascript">
    var urlAjax = '<?= blockshop_public('ajaxbuy.php') ?>';
    var urlIndex = '<?= blockshop_public('index.php') ?>';
    var urlRoot = '<?= blockshop_public('') ?>';
</script>
<script type="text/javascript" src="<?= blockshop_public('assets/navbar.js') ?>"></script>

<div id="navbar" class="navbar navbar-light bg-light">
    <div class="container-fluid">

        <? if ($group != -1) { ?>
            <div class="d-flex">
                <select id="server" class="form-select form-select-sm me-2" onchange="navbar.page('shop', server.value + ':' + category.value)" title="Выберите сервер">
                    <?= servlist() ?>
                </select>
                <select id="category" class="form-select form-select-sm me-2" onchange="navbar.page('shop', server.value + ':' + category.value)" title="Выберите категорию">
                    <?= $cats ?>
                </select>
                <input id="usercheck" class="form-control form-select-sm me-2" type="text" placeholder="Кому берем?">
            </div>
        <? } ?>

        <? if (isset($group)) { ?>
            <div class="d-flex">

                <? if ($group == 15) { ?>
                    <span class="navbar-button m-1" title="Добавить блок" onclick="navbar.page('admin', 0, urlAjax)">
                        <img src="<?= blockshop_public("assets/img/navbar/add.png") ?>" />
                    </span>
                    <span class="navbar-button m-1" title="Редактировать игрока(-ов)" onclick="navbar.page('edituser', usercheck.value, urlAjax)">
                        <img src="<?= blockshop_public("assets/img/navbar/user.png") ?>" />
                    </span>
                <? } ?>

                <span class="navbar-button m-1" title="Перейти в Магазин блоков" onclick="navbar.page('shop', server.value + ':' + category.value)">
                    <img src="<?= blockshop_public("assets/img/navbar/shop.png") ?>" />
                </span>
                <span class="navbar-button m-1" title="Перейти в Личный Кабинет" onclick="navbar.page('lk')">
                    <img src="<?= blockshop_public("assets/img/navbar/lk.png") ?>" />
                </span>
                <span class="navbar-button m-1" title="Сменить валюту" onclick="navbar.currency(this)" data-value="0">
                    <img src="<?= blockshop_public("assets/img/navbar/0.png") ?>" />
                </span>
                <span class="navbar-button m-1" title="Посмотреть корзину" onclick="navbar.page('cart')">
                    <img src="<?= blockshop_public("assets/img/navbar/cart.png") ?>" />
                </span>
                <span class="navbar-button m-1" title="Посмотреть историю" onclick="navbar.page('history')">
                    <img src="<?= blockshop_public("assets/img/navbar/history.png") ?>" />
                </span>
                <span class="navbar-button m-1" title="Банлист" onclick="navbar.page('banlist', server.value)">
                    <img src="<?= blockshop_public("assets/img/navbar/banlist.png") ?>" />
                </span>

            </div>
        <? } ?>

    </div>
</div>