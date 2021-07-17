<?
define ('BLOCKSHOP', true );
require('config.php');
$mrh_pass2 = "password_2";

$tm=getdate(time()+9*3600);
$date="$tm[year]-$tm[mon]-$tm[mday] $tm[hours]:$tm[minutes]:$tm[seconds]";

$out_summ = $_REQUEST["OutSum"];
$inv_id = $_REQUEST["InvId"];
$shp_item = $_REQUEST["Shp_item"];
$crc = $_REQUEST["SignatureValue"];
$crc = strtoupper($crc);
$my_crc = strtoupper(md5("$out_summ:$inv_id:$mrh_pass2:Shp_item=$shp_item"));

if ($my_crc !=$crc)
{
  echo "bad sign\n";
  exit();
}

// признак успешно проведенной операции
// success
echo "OK$inv_id\n";

DB::insert("INSET into transactions (id,money,name,date) VALUES (NULL,'{$out_summ}','{$username}','{$date}')");
DB::update("UPDATE `{$eco[0]}` set `{$eco[3]}`='{$q[0]['money']}' where name='{$username}'");
