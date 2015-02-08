<?php
/**
* Bitcoin daemon control for e-shop.
* @author m0Ray <m0ray@nm.ru>
* @version 1.0
* @package BitcoinShop
*/

/**
* Generic account class
* @package BitcoinShop
*/
class BungeeBonesBridge
{

public function getWatchIncoming($user_address){
include($_SERVER['DOCUMENT_ROOT']."/db_cfg/db2bbconfiga.php");
include($_SERVER['DOCUMENT_ROOT']."/db_cfg/connectloginmysqli.php");
if(empty($bb_day_ledger_id)) $bb_day_ledger_id = "";
if(empty($id)) $id = "";
if(empty($address)) $address = "";
$query = "Select `bb_day_ledger_id`, `id`, `address` from `watch_incoming` where `address`='$user_address'";
echo 'in func ', $query;
$result = mysqli_query($connect, $query);
 while ($row = mysqli_fetch_array($result))
 {
 $bb_day_ledger_id = $row['bb_day_ledger_id'];
$address = $row['address'];
$id = $row['id'];
 }
$has_been_credited = array($id, $bb_day_ledger, $address);
  return $has_been_credited;
 }

}
