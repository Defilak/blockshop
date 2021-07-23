<div class="card mt-4">
    <div class="card-header">
        Перевод валюты другому игроку
    </div>
    <div class="card-body d-flex">
        <input type="text" class="svlist" id="pt1" placeholder="Ник игрока">
        <input type="number" class="svlist" onkeyup="this.value=this.value.replace(/[^\d\.]+/g,'');" id="pt2" placeholder="Сумма">
        <select class="svlist" id="pt3">
            <option value="1">Реальная валюта</option>
            <option value="0">Игровая валюта</option>
        </select>
        <input type="button" class="button" value="Перевести" onclick="perevod();">
    </div>
</div>