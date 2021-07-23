<div class="card mt-4">
    <div class="block prefix_block">
        <div class="card-header">
            Смена префикса
        </div>
        <div class="card-body">
            <div class="input-group">
                <select id="prefcol" onchange="prefixview();" class="form-control form-select">
                    <option value="0">Цвет префикса</option>
                    <?= $color ?>
                </select>
                <input id="pref" class="form-control" placeholder="Префикс">

                <select id="nickcol" class="form-control form-select">
                    <option value="0">Цвет ника</option>
                    <?= $color ?>
                </select>
                <select id="suffcol" class="form-control form-select">
                    <option value="0">Цвет текста</option>
                    <?= $color ?>
                </select>
                <input type="hidden" id="prefixview">
                <button type="button" class="btn btn-success" onclick="changeprefix();">Сменить</button>
            </div>
        </div>
    </div>
</div>