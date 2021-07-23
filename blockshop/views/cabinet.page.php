<div class="row my-4">
    <div class="col-6">
        <? include 'cabinet/user_info.block.php' ?>
    </div>

    <? if ($group != -1) { ?>
        <div class="col-6">
            <? include 'cabinet/pay_money.block.php' ?>
            <? if ($group != 15) { ?>
                <? include 'cabinet/buy_account.block.php' ?>
            <? } ?>
        </div>

        <? if ($ban != 1) { ?>
            <div class="col-6">
                <? include 'cabinet/exchange_money.block.php' ?>
                <? include 'cabinet/give_money_to_player.block.php' ?>
            </div>
        <? } ?>
        <? if ($group != 0 and $ban != 1) { ?>
            <div class="col-6">
                <? include 'cabinet/change_prefix.block.php' ?>
            </div>
        <? } ?>
    <? } ?>
</div>
<div class="row">

</div>