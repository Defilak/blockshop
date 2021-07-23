<div class="card">
    <div class="card-header">
        Пополнение счета
    </div>
    <div class="card-body d-flex">
        <form name="payment" action="https://interkassa.com/lib/payment.php" method="post" enctype="application/x-www-form-urlencoded" accept-charset="utf8">
            <input type="text" class="svlist" name="ik_payment_amount" placeholder="Интеркасса">
            <input type="submit" name="process" value="Перейти к оплате" id="pay_btn" class="button">
            <input type="hidden" name="ik_payment_id" value="<?= $username ?>">
            <input type="hidden" name="ik_baggage_fields" value="Пополнение счета игрока">
            <input type="hidden" name="ik_payment_desc" value="payment_450">
            <input type="hidden" name="ik_shop_id" value="<?= $shop_id ?>">
        </form>
    </div>
</div>