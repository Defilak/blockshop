<?php

//$s = array('Medieval', 'Imphar'); ///массив серверов(первое по умолчанию)


$stmt = DB::prepare("SELECT * FROM `{$banlist}_{$server_names[$server_id]}` LIMIT 25");
$stmt->execute();

$result = $stmt->fetchAll();

?>
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