<?php
define ('BLOCKSHOP', true );
require('config.php');
extract($_POST);
$sing_hash_str = $shop_id.':'.$ik_payment_amount.':'.$ik_payment_id.':'.$ik_paysystem_alias.':'.$ik_baggage_fields.':'.$ik_payment_state.':'.$ik_trans_id.':'.$ik_currency_exch.':'.$ik_fees_payer.':'.$key;
$sign_hash = strtoupper(md5($sing_hash_str));
if($ik_sign_hash === $sign_hash) {
	DB::update("UPDATE `{$table_economy['table']}` SET `{$table_economy['money']}`=`{$table_economy['money']}`+? WHERE `{$table_economy['name']}`='{$ik_payment_id}'"); 
	DB::update("INSERT INTO `transactions` (`name`, `date`, `money`) VALUES (`{$ik_payment_id}`,`".date('Y-m-d H:i:s')."`,`{$ik_payment_amount}`)");
} else {
exit;
}
