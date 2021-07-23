<div class="card">
    <div class="card-header">
        Пополнение счета
    </div>
    <div class="card-body d-flex">
        <form class="input-group" action="https://interkassa.com/lib/payment.php" method="post">
            <input class="form-control" type="text" name="ik_payment_amount" placeholder="Сумма">

            <input type="hidden" name="ik_shop_id" value="<?= $shop_id ?>">
            <input type="hidden" name="ik_payment_id" value="<?= $username ?>">
            <input type="hidden" name="ik_baggage_fields" value="Пополнение счета игрока">
            <input type="hidden" name="ik_payment_desc" value="payment_450">
            
            <button class="btn btn-success" type="button" name="process" id="pay_btn">Перейти к оплате</button>
        </form>
    </div>
</div>