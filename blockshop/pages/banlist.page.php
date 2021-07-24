<?php

//$s = array('Medieval', 'Imphar'); ///массив серверов(первое по умолчанию)

$stmt = DB::prepare("SELECT * FROM `{$banlist}_{$server_names[$server_id]}` LIMIT 25");
$stmt->execute();

$result = $stmt->fetchAll();

_exit_with_template('banlist', ['result' => $result]);