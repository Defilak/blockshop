<div class="card mt-4">
    <div class="card-header">
        Перевод валюты другому игроку
    </div>
    <div class="card-body d-flex">
        <div class="input-group">

            <input type="number" class="form-control" id="pt1" placeholder="Ник игрока">
            <input type="number" class="form-control" onkeyup="this.value=this.value.replace(/[^\d\.]+/g,'');" id="pt2" placeholder="Сумма">

            <select class="form-control form-select" id="pt3">
                <option value="1">Реальная валюта</option>
                <option value="0">Игровая валюта</option>
            </select>
            <button type="button" class="btn btn-success" onclick="perevod();">Перевести</button>
        </div>
    </div>
</div>