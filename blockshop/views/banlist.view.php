<table class="banlist_table">
    <tr>
        <th>Ник</th>
        <th>Причина</th>
        <th>Кто забанил?</th>
        <th>Дата разбана</th>
    </tr>

    <? foreach ($result as $row) { ?>
        <tr>
            <td><?= $row['name'] ?></td>
            <td><?= $row['reason'] ?></td>
            <td><?= $row['admin'] ?></td>
            <td><?= $row['time'] ?></td>
        </tr>
    <? } ?>
</table>