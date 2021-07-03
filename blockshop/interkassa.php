<?php
/*	BlockShop v1.0 - магазин блоков и ЛК (мультиCMS) от 25.07.2013
	Скрипт: kassa.php (отвечает за прием платежей с интеркассы)
	Сделано @Konstantin773.  Продажа оригинального и модифицированного кода запрещена!!!
*/
define ('BLOCKSHOP', true );
require('config.php');
extract($_POST);
$sing_hash_str = $shop_id.':'.$ik_payment_amount.':'.$ik_payment_id.':'.$ik_paysystem_alias.':'.$ik_baggage_fields.':'.$ik_payment_state.':'.$ik_trans_id.':'.$ik_currency_exch.':'.$ik_fees_payer.':'.$key;
$sign_hash = strtoupper(md5($sing_hash_str));
if($ik_sign_hash === $sign_hash) {
	$db->update("UPDATE `{$eco['0']}` SET `{$eco['3']}`=`{$eco['3']}`+? WHERE `{$eco['1']}`='{$ik_payment_id}'"); 
	$db->update("INSERT INTO `transactions` (`name`, `date`, `money`) VALUES (`{$ik_payment_id}`,`".date('Y-m-d H:i:s')."`,`{$ik_payment_amount}`)");
} else {
exit;
}
?>