<div class="card mt-4">
    <div class="card-header">
        Покупка статусов
    </div>
    <div class="card-body d-flex">
        <? if ($ban) { ?>

            <? if ($bancount != count($bans)) { ?>
                <input type="button" value="Разбан № <?= $bancount + 1 ?> (<?= $bans[$bancount] ?>р)" onclick="ajaxfunc('unban=0');tolc();" class="button">
            <? } else { ?>
                <input type="button" value="Разбан невозможен" onclick="ajaxfunc('unban=0');tolc();" class="button" disabled>
            <? } ?>
            <input type="button" onclick="ajaxfunc('unban=0');tolc();" class="button">
            <div style="font-size:10px;"><b><br>1.Разбан переводит вас в группу игрок, аннулируя премиум статус (если был)<br>2.После разбана у вас аннулируется количество покупок</b></div>

        <? } else if ($group == 0) { ?>

            <? for ($i = 1, $size = count($player_groups); $i < $size; ++$i) { ?>
                <input type="button" value="<?= $player_groups[$i]['name'] ?> (<?= $player_groups[$i]['price'] ?>р)" onclick="buygroup('<?= $i ?>');tolc();" class="button">
            <? } ?>
            <div style="font-size:10px;"><b><br>1.Покупка осуществляется на один месяц<br>2.Стоимость считывается только с реального счета<br></b></div>

        <? } else if ($group != 15) { ?>
            <input type="button" value="Продлить <?= $user_group['name'] ?> (<?= $user_group['price'] / 100 * 70 ?>)р)" onclick="buygroup('<?= $group ?>');tolc();" class="pvb">
            <input type="button" value="Отказаться от <?= $user_group['name'] ?>" onclick="buygroup('0');tolc();" class="pvb">
            <div style="font-size:10px;"><b><br>1.Продление осуществляется один 1 месяц<br>2.Продление услуги стоит на 30% дешевле.<br>3.При отказе от услуги на ваш счет вернется 50% суммы (пропорционально оставшимся дням)</b></div>

        <? } ?>
    </div>
</div>