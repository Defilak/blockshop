<div class="card mt-4">
    <div class="card-header">
        Покупка статусов
    </div>
    <div class="card-body buy-account-block">
        <? if ($ban) { ?>

            <div class="account-types">
                <? if ($bancount != count($bans)) { ?>
                    <button type="button" class="btn btn-success" onclick="ajaxfunc('unban=0');tolc();">
                        Разбан № <?= $bancount + 1 ?> (<?= $bans[$bancount] ?>р)
                    </button>
                <? } else { ?>
                    <button type="button" class="btn btn-success" onclick="ajaxfunc('unban=0');tolc();">
                        Разбан невозможен
                    </button>
                <? } ?>
            </div>

            <div class="account-description">
                <p>1.Разбан переводит вас в группу игрок, аннулируя премиум статус (если был)</p>
                <p>2.После разбана у вас аннулируется количество покупок</p>
            </div>

        <? } else if ($group == 0) { ?>

            <div class="account-types">
                <? foreach ($player_groups as $key => $pgroup) { ?>
                    <? if ($key == 0) continue; ?>
                    <button type="button" class="btn btn-success" onclick="buygroup(<?= $key ?>);tolc();">
                        <?= $pgroup['name'] ?> (<?= $pgroup['price'] ?>р)
                    </button>
                <? } ?>
            </div>

            <div class="account-description">
                <p>1.Покупка осуществляется на один месяц</p>
                <p>2.Стоимость считывается только с реального счета</p>
            </div>

        <? } else if ($group != 15) { ?>
            <div class="account-types">
                <button type="button" class="btn btn-success" onclick="buygroup('<?= $group ?>');tolc();">
                    Продлить <?= $user_group['name'] ?> (<?= $user_group['price'] / 100 * 70 ?>)р)
                </button>
                <button type="button" class="btn btn-outline-secondary" onclick="buygroup('0');tolc();">
                    Отказаться от <?= $user_group['name'] ?>
                </button>
            </div>

            <div class="account-description">
                <p>1.Продление осуществляется один 1 месяц</p>
                <p>2.Продление услуги стоит на 30% дешевле.</p>
                <p>3.При отказе от услуги на ваш счет вернется 50% суммы (пропорционально оставшимся дням)</p>
            </div>

        <? } ?>
    </div>
</div>