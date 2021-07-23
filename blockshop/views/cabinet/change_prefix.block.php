<div class="card mt-4">
    <div class="block prefix_block">
        <div class="card-header">
            Смена префикса
        </div>
        <div class="card-body">
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
</div>