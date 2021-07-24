<div>
    <table class="table">
        <? foreach ($result as $row) { ?>
            <tr>
                <td></td>
            </tr>
        <? } ?>
    </table>

</div>

<div class="presale" style="width:20%;">
    <div class="button" style="height:100px;margin:2px;font-size:10px;">
        <input type="button" onclick="delblock('{id}:{srv}');" class="ud uk" title="Отказаться от покупки">
        <div>
            <b>{name}<br />
                <img src="/{dir}{icons}{img}">
                <br>{amount} шт.<br>Сервер: {srv}</b>
        </div>
    </div>
</div>