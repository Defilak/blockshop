<div class="card mt-4">
    <div class="card-header">
        Перевод из реальной валюты в игровую (1 рубль = <?= $exchangeFactor ?>)
    </div>
    <div class="card-body">
        <div class="input-group">
            <input type="number" class="form-control" onkeyup="this.value=this.value.replace(/[^\d\.]+/g,'');" id="sumz" placeholder="Сумма в рублях">
            <button type="button" class="btn btn-success" onclick="pereval();">Перевести</button>
        </div>
    </div>
</div>