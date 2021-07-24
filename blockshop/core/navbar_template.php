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
                <select id="server" class="form-select form-select-sm me-2" onchange="toserver();" title="Выберите сервер"><?= servlist() ?></select>
                <select id="category" class="form-select form-select-sm me-2" onchange="toserver();" title="Выберите категорию"><?= $cats ?></select>
                <input id="usercheck" class="form-control form-select-sm me-2" type="text" placeholder="Кому берем?">
            </div>
        <? } ?>

        <? if (isset($group)) { ?>
            <div id="navbar_links" class="d-flex">
                <? foreach ($actions as $action) { ?>
                    <input src="/<?= $dir . 'assets/img/' . $action['img'] ?>.png" onclick="<?= $action['onclick'] ?>" title="<?= $action['title'] ?>" type="image" class="imgbtn m-1" <?= $action['img'] === '0' ? 'id="changeval" value="0"' : '' ?> />
                <? } ?>
            </div>
        <? } ?>

    </div>
</div>