<div class="card mt-4">
    <div class="card-header">
        Перевод из реальной валюты в игровую (1 рубль = <?= $exchangeFactor ?>)
    </div>
    <div class="card-body">
        <input type="number" class="svlist" onkeyup="this.value=this.value.replace(/[^\d\.]+/g,'');" id="sumz" placeholder="Сумма в рублях">
        <input type="button" class="button" value="Перевести" onclick="pereval();">
    </div>
</div>