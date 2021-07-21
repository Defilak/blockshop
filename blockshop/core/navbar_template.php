<script type="text/javascript">
var url1 = '/<?= $dir ?>ajaxbuy.php'; var url2 = '/<?= $dir ?>index.php'; var url3 = '/<?= $dir ?>';
</script>
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


$siz1 = count($cat);
for ($i = 0, $size = $siz1; $i < $size; ++$i) {
    $cats .= '<option value="' . $i . '">' . $cat[$i] . '</option>';
}

$_POST['lk'] = 1;

?>
<div class="navbar navbar-light bg-light">
    <div class="container-fluid">

        <? if ($group != -1) { ?>
            <div class="d-flex">
                <select id="server" class="form-select form-select-sm me-2" onchange="toserver();" title="Выберите сервер"><?= $serv ?></select>
                <select id="category" class="form-select form-select-sm me-2" onchange="toserver();" title="Выберите категорию"><?= $cats ?></select>
                <input id="usercheck" class="form-control form-select-sm me-2" type="text" value placeholder="Кому берем?">
            </div>
        <? } ?>

        <? if (isset($group)) { ?>
            <div class="d-flex">
                <? foreach ($actions as $action) { ?>
                    <input src="/<?= $dir . 'assets/img/' . $action['img'] ?>.png" onclick="<?= $action['onclick'] ?>" title="<?= $action['title'] ?>" type="image" class="imgbtn m-1" <?= $action['img'] === '0' ? 'id="changeval" value="0"' : '' ?> />
                <? } ?>
            </div>
        <? } ?>

    </div>
</div>