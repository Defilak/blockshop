<script type="text/javascript">
    var url1 = '/<?= $dir ?>ajaxbuy.php';
    var url2 = '/<?= $dir ?>index.php';
    var url3 = '/<?= $dir ?>';
</script>
<script type="text/javascript" src="/<?= $dir ?>shop.js"></script>
<link rel="stylesheet" type="text/css" href="/<?= $dir ?>shop.css" />
<?
$actions = [
    ['title' => 'Перейти в Магазин блоков', 'img' => 'shop', 'onclick' => 'setbanlistF();toserver(); '],
    ['title' => 'Перейти в Личный Кабинет', 'img' => 'lk', 'onclick' => 'setbanlistF();tolc();'],
    ['title' => 'Сменить валюту',           'img' => '0', 'onclick' => 'setbanlistF();valuta(); '],
    ['title' => 'Посмотреть корзину',       'img' => 'cart', 'onclick' => "setbanlistF();props('cart');"],
    ['title' => 'Посмотреть историю',       'img' => 'history', 'onclick' => "setbanlistF();props('history');"],
    ['title' => 'Банлист',                  'img' => 'banlist', 'onclick' => 'tobanlist(); setbanlistT();']
];

//add buttons if admin
if ($group == 15) {
    array_unshift(
        $actions,
        ['title' => 'Добавить блок',             'img' => 'add', 'onclick' => "bedit('admin=0');"],
        ['title' => 'Редактировать игрока(-ов)', 'img' => 'user', 'onclick' => "props('edituser');"]
    );
}

$_POST['lk'] = 1;
?>
<div class="headdesign" style="width:100%;float:left;">

    <? if ($group != '-1' && $group != -1) { ?>
        <div id="serverlist" style="float:left;">
            <select id="server" class="svlist" onchange="toserver();" title="Выберите сервер"><?= $serv ?></select>
            <select id="category" class="svlist" onchange="toserver();" title="Выберите категорию"><?= $cats ?></select>
            <input type="text" class="svlist" style="padding:6px;" id="usercheck" value placeholder="Кому берем?">
        </div>
    <? } ?>

    <? if (isset($group)) { ?>
        <div class="userv" style="width:auto;float:right">
            <? foreach ($actions as $action) { ?>
                <input src="/<?= $dir . 'img/' . $action['img'] ?>.png" onclick="<?= $action['onclick'] ?>" title="<?= $action['title'] ?>" type="image" class="imgbtn" <?=$action['img'] === '0' ? 'id="changeval" value="0"' : ''?>/>
            <? } ?>
        </div>
    <? } ?>

</div>