<div class="block personal_data">
    <div class="jaw personal_data_jaw">Личные данные</div>
    <div class="content personal_data_content">
        <div class="leftpanel" style="float: left;">
            <center>
                <iframe name="skinframe" id="skinframe" style="display:none"></iframe>
                <div class="pics" id="giveskin">
                    <img src="<?= $skin_preview_front ?>" alt="skin_front" />
                    <img src="<?= $skin_preview_back ?>" alt="skin_back" />
                </div>

                <button class="button">Выбрать скин
                    <form enctype="multipart/form-data" method="post" target="skinframe" action="<?= blockshop_public('ajaxbuy.php') ?>" id="skinform" onsubmit="return false;">
                        <input type="file" accept="image/png" name="skin" id="file" onchange="upskin();" style="margin-top: -20px;margin-left: -5px;font-size: 18px;width: 150px; height: 30px;opacity: 0;">
                    </form>
                </button>
                <br>
                <button class="button">Выбрать плащ
                    <form enctype="multipart/form-data" method="post" target="skinframe" action="<?= blockshop_public('ajaxbuy.php') ?>" id="cloackform" onsubmit="return false;">
                        <input type="file" accept="image/png" name="cloak" id="file" onchange="upcloack();" style="margin-top: -20px;margin-left: -5px;font-size: 18px;width: 150px; height: 30px;opacity: 0;">
                    </form>
                </button>
                <br>
                <button class="button" onclick="ajaxfunc('remove=skin');tolc();">
                    Удалить скин
                </button>
                <br>
                <button class="button" onclick="ajaxfunc('remove=cloak');tolc();" style="margin-right: 3px;">
                    Удалить плащ
                </button>
            </center>
        </div>

        <div class="rightpanel">
            <br>
            <center>
                <table>
                    <tr>
                        <td>Логин:</td>
                        <td><b><?= $username ?></b></td>
                    </tr>
                    <tr>
                        <td>Реальная валюта:</td>
                        <td><b><?= skl($money, $sklrub) ?></b></td>
                    </tr>
                    <tr>
                        <td>Игровая валюта:</td>
                        <td><b><?= skl($iconomy, $skleco) ?></b></td>
                    </tr>
                    <tr>
                        <td>Группа: </td>
                        <td><b><?= $user_group['name'] ?></b></td>
                    </tr>
                    <tr>
                        <td>Срок:</td>
                        <td><b><?= $until ?></b></td>
                    </tr>
                    <tr>
                        <td>Кол-во покупок:</td>
                        <td><b><?= $buys ?></b></td>
                    </tr>
                </table>
            </center>
        </div>
    </div>
</div>
<? if ($group != -1) { ?>
    <br>
    <div class="block donate_block">
        <div class="jaw donate_jaw">
            Пополнение счета
        </div>
        <div class="content donate_content">
            <form name="payment" action="https://interkassa.com/lib/payment.php" method="post" enctype="application/x-www-form-urlencoded" accept-charset="utf8">
                <input type="hidden" name="ik_shop_id" value="<?= $shop_id ?>">
                <input type="text" class="svlist" name="ik_payment_amount" placeholder="Интеркасса">
                <input type="hidden" name="ik_payment_id" value="<?= $username ?>">
                <input type="hidden" name="ik_baggage_fields" value="Пополнение счета игрока">
                <input type="hidden" name="ik_payment_desc" value="payment_450">
                <input type="submit" name="process" value="Перейти к оплате" id="pay_btn" class="button">
            </form>
        </div>
    </div>

    <? if ($group != 15) { ?>

        <br>
        <div class="block buy_status_block">
            <div class="jaw buy_status_jaw">
                Покупка статусов
            </div>
            <div class="content buy_status_content">
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

    <? } ?>

    <? if ($ban != 1) { ?>

        <br>
        <div class="block transfer_donat_block">
            <div class="jaw transfer_donat_jaw">
                Перевод из реальной валюты в игровую (1 рубль = <?= skl(100/**$koff */, $skleco) ?>)
            </div>
            <div class="content transfer_donat_content">
                <input type="number" class="svlist" onkeyup="this.value=this.value.replace(/[^\d\.]+/g,'');" id="sumz" placeholder="Сумма в рублях">
                <input type="button" class="button" value="Перевести" onclick="pereval();">
            </div>
        </div>

        <br>
        <div class="block transfer_player_block">
            <div class="jaw transfer_player_jaw">
                Перевод валюты другому игроку
            </div>
            <div class="content transfer_player_content">
                <input type="text" class="svlist" id="pt1" placeholder="Ник игрока">
                <input type="number" class="svlist" onkeyup="this.value=this.value.replace(/[^\d\.]+/g,'');" id="pt2" placeholder="Сумма">
                <select class="svlist" id="pt3">
                    <option value="1">Реальная валюта</option>
                    <option value="0">Игровая валюта</option>
                </select>
                <input type="button" class="button" value="Перевести" onclick="perevod();">
            </div>
        </div>

    <? } ?>

    <? if ($group != 0 and $ban != 1) { ?>

        <div class="block prefix_block">
            <div class="jaw prefix_jaw">
                Смена префикса
            </div>
            <div class="content prefix_content">
                <select id="prefcol" onchange="prefixview();" class="button">
                    <option value="0">Цвет префикса</option>
                    <?= $color ?>
                </select>
                <input id="pref" class="button" placeholder="Префикс">
                <select id="nickcol" class="button">
                    <option value="0">Цвет ника</option>
                    <?= $color ?>
                </select>
                <select id="suffcol" class="button">
                    <option value="0">Цвет текста</option>
                    <?= $color ?>
                </select>
                <input type="button" class="button" value="Сменить" onclick="changeprefix();">
                <input type="hidden" id="prefixview">
            </div>
        </div>

    <? } ?>
<? } ?>