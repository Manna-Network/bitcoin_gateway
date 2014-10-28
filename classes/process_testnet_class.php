<?php
/**
* Bitcoin and Testcoin Payment gateway to shared hosting account.
* @author Robert Lefebure <robert.r.lefebure@gmail.com>
* @version 1.0
* @package Bitcoin/Testcoin Monitor
* Copy the process_btc_deposit.php file to your remote shared server then:
* 
* IMPORTANT: change the path in the lines 
* $file="http://your_website_address_here/process_btc_deposit.php"; 
* in the first two functions below
* accordingly to point to that location
*/


class process_testnet
{
function credit_BB_account($user_id, $current_amount, $isTestCoin, $txn ){

$file="http://your_website_address_here/process_btc_deposit.php";
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $file);
 		curl_setopt($ch, CURLOPT_POSTFIELDS, array('user_id' => $user_id,'deposit_amount' => $current_amount,'isTestCoin' => $isTestCoin , 'txn' => $txn));
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$data = curl_exec($ch);
				curl_close($ch);
				echo($data);


}

function record_cold_transfer($user_id, $current_amount, $isTestCoin, $txn_old, $txn_new, $address_old, $address_new_cold ){

$file="http://your_website_address_here.com/process_deposit_to_cold_testnet.php";
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $file);
 		curl_setopt($ch, CURLOPT_POSTFIELDS, array('user_id' => $user_id,'amount' => $current_amount,'isTestCoin' => $isTestCoin , 'txid_old' => $txn_old, 'txid_new' => $txn_new, 'address_old' => $address_old, 'address_new_cold' => $address_new_cold));
curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$data = curl_exec($ch);
				curl_close($ch);
				echo($data);


}


public function recordColdStorage ($account,
$address,
$amount,
$category,
$confirmations,
$txid,
$sendto_address,
$walletconflicts,
$time,
$timereceived){
include($_SERVER['DOCUMENT_ROOT']."/db_cfg/db2bbconfiga.php");
include($_SERVER['DOCUMENT_ROOT']."/db_cfg/connectloginmysqli.php");

$query = "INSERT INTO `move_out_to_cold` (
`account`  ,
`address` ,
`amount` ,
`category` ,
`confirmations` ,
`txid` ,
`sendto_address` ,
`walletconflicts` ,
`time`  ,
`timereceived`) VALUES (

'$account',
'$address',
'$amount',
'$category',
'$confirmations',
'$txid',
'$sendto_address',
'$walletconflicts',
'$time',
'$timereceived'

) ";


//echo $query;
$result = mysqli_query($connect, $query);
}


public function moveToArchive ($txid){
include($_SERVER['DOCUMENT_ROOT']."/db_cfg/db2bbconfiga.php");
include($_SERVER['DOCUMENT_ROOT']."/db_cfg/connectloginmysqli.php");
//copy to deposits_in_archive
$query = "INSERT into `deposits_in_archive` Select  * from `watch_incoming` where `txid`='$txid' ";
$result = mysqli_query($connect, $query);
//copy to out_to_coldstorage_archive
$query = "DELETE FROM `watch_incoming` WHERE `txid`='$txid'";
$result = mysqli_query($connect, $query);
}

public function getWatchIncoming($txid){
include($_SERVER['DOCUMENT_ROOT']."/db_cfg/db2bbconfiga.php");
include($_SERVER['DOCUMENT_ROOT']."/db_cfg/connectloginmysqli.php");
if(empty($id)) $id = "";
if(empty($address)) $address = "";
if(empty($receive)) $receive = "";
$query = "Select  `id`, `address` from `watch_incoming` where `txid`='$txid'";
$result = mysqli_query($connect, $query);
if (mysqli_num_rows($result)>0) { 
 while ($row = mysqli_fetch_array($result))
	 {
	 $address = $row['address'];
	$id = $row['id'];
	}
 $has_been_credited = array($id, $address);
 }
else
{
$has_been_credited = "";
}
  return $has_been_credited;
}




public function updateFlag($txid){
include($_SERVER['DOCUMENT_ROOT']."/db_cfg/db2bbconfiga.php");
include($_SERVER['DOCUMENT_ROOT']."/db_cfg/connectloginmysqli.php");
$query = "UPDATE  `watch_incoming` set `flag`='1' where `txid`='".$txid."'";
//echo $query;
$result = mysqli_query($connect, $query) or die("Couldn't execute '47' query");
return true;
}

public function getFlag($txid){
include($_SERVER['DOCUMENT_ROOT']."/db_cfg/db2bbconfiga.php");
include($_SERVER['DOCUMENT_ROOT']."/db_cfg/connectloginmysqli.php");
$flag = "";
$query = "Select  `flag` from `watch_incoming` where `txid`='".$txid."'";
//echo $query;
$result = mysqli_query($connect, $query);
while ($row = mysqli_fetch_array($result)) {
$flag = $row['flag'];
}
return  $flag;
}

public function checkDepositsInArchive($txid){
include($_SERVER['DOCUMENT_ROOT']."/db_cfg/db2bbconfiga.php");
include($_SERVER['DOCUMENT_ROOT']."/db_cfg/connectloginmysqli.php");
$query = "Select  * from `deposits_in_archive` where `txid`='$txid' ";
//echo $query;
$result = mysqli_query($connect, $query);
 $row_cnt = mysqli_num_rows($result);
return  $row_cnt;
}

public function checkMoveOutToCold($txid){
include($_SERVER['DOCUMENT_ROOT']."/db_cfg/db2bbconfiga.php");
include($_SERVER['DOCUMENT_ROOT']."/db_cfg/connectloginmysqli.php");
$query = "Select  * from `move_out_to_cold` where `txid`='$txid' ";
//echo $query;
$result = mysqli_query($connect, $query);
 $row_cnt = mysqli_num_rows($result);
return  $row_cnt;
}




public function getHistoryUserTxnRows($user_id){
include($_SERVER['DOCUMENT_ROOT']."/db_cfg/db2bbconfiga.php");
include($_SERVER['DOCUMENT_ROOT']."/db_cfg/connectloginmysqli.php");
if(empty($id)) $id = "";
if(empty($address)) $address = "";
if(empty($receive)) $receive = "";
$query = "Select  * from `deposits_in_archive` where `account`='$user_id'";
$result = mysqli_query($connect, $query);
if (mysqli_num_rows($result)>0) { 
 while ($row = mysqli_fetch_array($result))
	 {
$id[] = $row['id'];
$account[]   = $row['account'];
$address[] = $row['address'];
$category[] = $row['category'];
$amount[] = $row['amount'];
$confirmations[]   = $row['confirmations'];
$txid[]   = $row['txid'];
$walletconflicts[]   = $row['walletconflicts'];
$blockhash[] = $row['blockhash'];
$blockindex[] = $row['blockindex'];
$blocktime[]   = $row['blocktime'];
$time[]   = $row['time']; 
$timereceived[]   = $row['timereceived'];
$flag[]   = $row['flag'];
	}
 $allTxn = array($id,$account,$address,$amount,$category,$confirmations,$blockhash,$blockindex,$blocktime,$txid,$walletconflicts,$time,$timereceived, $flag);
return $allTxn;
 }
else
{
return FALSE;
}
  
}




public function getWatchingUserTxnRows($user_id){
include($_SERVER['DOCUMENT_ROOT']."/db_cfg/db2bbconfiga.php");
include($_SERVER['DOCUMENT_ROOT']."/db_cfg/connectloginmysqli.php");
if(empty($id)) $id = "";
if(empty($address)) $address = "";
if(empty($receive)) $receive = "";
$query = "Select  * from `watch_incoming` where `account`='$user_id'";
$result = mysqli_query($connect, $query);
if (mysqli_num_rows($result)>0) { 
 while ($row = mysqli_fetch_array($result))
	 {
$id[] = $row['id'];
$account[]   = $row['account'];
$address[] = $row['address'];
$category[] = $row['category'];
$amount[] = $row['amount'];
$confirmations[]   = $row['confirmations'];
$txid[]   = $row['txid'];
$walletconflicts[]   = $row['walletconflicts'];
$blockhash[] = $row['blockhash'];
$blockindex[] = $row['blockindex'];
$blocktime[]   = $row['blocktime'];
$time[]   = $row['time']; 
$timereceived[]   = $row['timereceived'];
$flag[]   = $row['flag'];
	}
 $allTxn = array($id,$account,$address,$amount,$category,$confirmations,$blockhash,$blockindex,$blocktime,$txid,$walletconflicts,$time,$timereceived, $flag);
return $allTxn;
 }
else
{
return FALSE;
}
  
}

public function getAllTxnRows(){
include($_SERVER['DOCUMENT_ROOT']."/db_cfg/db2bbconfiga.php");
include($_SERVER['DOCUMENT_ROOT']."/db_cfg/connectloginmysqli.php");
if(empty($id)) $id = "";
if(empty($address)) $address = "";
if(empty($receive)) $receive = "";
$query = "Select  * from `watch_incoming`";
$result = mysqli_query($connect, $query);
if (mysqli_num_rows($result)>0) { 
 while ($row = mysqli_fetch_array($result))
	 {
$id[] = $row['id'];
$account[]   = $row['account'];
$address[] = $row['address'];
$category[] = $row['category'];
$amount[] = $row['amount'];
$confirmations[]   = $row['confirmations'];
$txid[]   = $row['txid'];
$walletconflicts[]   = $row['walletconflicts'];
$blockhash[] = $row['blockhash'];
$blockindex[] = $row['blockindex'];
$blocktime[]   = $row['blocktime'];
$time[]   = $row['time']; 
$timereceived[]   = $row['timereceived'];
$flag[]   = $row['flag'];
	}
 $allTxn = array($id,$account,$address,$amount,$category,$confirmations,$blockhash,$blockindex,$blocktime,$txid,$walletconflicts,$time,$timereceived, $flag);
return $allTxn;
 }
else
{
return FALSE;
}
  
}

public function getDepositRow($user_id, $txn_id){
include($_SERVER['DOCUMENT_ROOT']."/db_cfg/db2bbconfiga.php");
include($_SERVER['DOCUMENT_ROOT']."/db_cfg/connectloginmysqli.php");
if(empty($id)) $id = "";
if(empty($address)) $address = "";
if(empty($receive)) $receive = "";
$query = "Select  * from `watch_incoming` where `account`='$user_id' AND `txid` = '$txn_id' ORDER BY ID DESC LIMIT 1";
//echo 'line 48   ...  ', $query;
$result = mysqli_query($connect, $query);
if (mysqli_num_rows($result)>0) { 
 while ($row = mysqli_fetch_array($result))
	 {
$id = $row['id'];
$account   = $row['account'];
$address = $row['address'];
$category = $row['category'];
$amount = $row['amount'];
$confirmations   = $row['confirmations'];
$txid   = $row['txid'];
$walletconflicts   = $row['walletconflicts'];
$blockhash = $row['blockhash'];
$blockindex = $row['blockindex'];
$blocktime   = $row['blocktime'];
$time   = $row['time']; 
$timereceived   = $row['timereceived'];
$flag   = $row['flag'];
	}
 $has_been_credited = array($id,$account,$address,$category,$amount,$confirmations,$txid,$walletconflicts,$blockhash,$blockindex,$blocktime,$time,$timereceived, $flag);
 }
else
{
$has_been_credited = "";
}
  return $has_been_credited;
}

public function insertWatchIncoming($account, $address, $category,$amount, $confirmations,  $txid, $walletconflicts, $time, $timereceived){
include($_SERVER['DOCUMENT_ROOT']."/db_cfg/db2bbconfiga.php");
include($_SERVER['DOCUMENT_ROOT']."/db_cfg/connectloginmysqli.php");
date_default_timezone_set('America/New_York');
$now = date('Y-m-d H:m:s');
$query = "INSERT into  `watch_incoming` (`account`, `address`, `category`,`amount`, `confirmations`, `txid`, `walletconflicts`, `time`, `timereceived`, `flag`)values ('$account', '$address', '$category','$amount', '$confirmations', '$txid', '$walletconflicts', '$time', '$timereceived', '0')";
//echo '<br>in func line 90 ... ', $query;
//`account`, `address`, `category`,`amount`, `confirmations`, `txid`, `walletconflicts`, `time`, `timereceived`

$result = mysqli_query($connect, $query) or die("Couldn't execute '47' query");

}

public function updateWatchIncoming($account, $address, $category,$amount, $confirmations, $blockhash, $blockindex, $blocktime, $txid, $walletconflicts, $time, $timereceived){
include($_SERVER['DOCUMENT_ROOT']."/db_cfg/db2bbconfiga.php");
include($_SERVER['DOCUMENT_ROOT']."/db_cfg/connectloginmysqli.php");
date_default_timezone_set('America/New_York');
$now = date('Y-m-d H:m:s');
$query = "UPDATE  `watch_incoming` set `address`='$address', `category`='$category',`amount`='$amount', `confirmations`='$confirmations',`blockhash`='$blockhash', `blockindex`='$blockindex', `blocktime`='$blocktime', `txid`='$txid', `walletconflicts`='$walletconflicts', `time`='$time', `timereceived`='$timereceived' WHERE `account` ='$account' AND `txid`='$txid'";
//echo $query;
//`account`, `address`, `category`,`amount`, `confirmations`, `txid`, `walletconflicts`, `time`, `timereceived`

$result = mysqli_query($connect, $query) or die("Couldn't execute '47' query");

}

public function updateWatchIncomingshort($account, $address, $category,$amount, $confirmations, $txid, $walletconflicts, $time, $timereceived){
include($_SERVER['DOCUMENT_ROOT']."/db_cfg/db2bbconfiga.php");
include($_SERVER['DOCUMENT_ROOT']."/db_cfg/connectloginmysqli.php");
date_default_timezone_set('America/New_York');
$now = date('Y-m-d H:m:s');
$query = "UPDATE  `watch_incoming` set `address`='$address', `category`='$category',`amount`='$amount', `confirmations`='$confirmations', `txid`='$txid', `walletconflicts`='$walletconflicts', `time`='$time', `timereceived`='$timereceived' WHERE `account` ='$account' AND `txid`='$txid'";
//echo $query;
//`account`, `address`, `category`,`amount`, `confirmations`, `txid`, `walletconflicts`, `time`, `timereceived`

$result = mysqli_query($connect, $query) or die("Couldn't execute '47' query");

}

public function getUserHistorybyuser($user_id){
include($_SERVER['DOCUMENT_ROOT']."/db_cfg/db2bbconfiga.php");
include($_SERVER['DOCUMENT_ROOT']."/db_cfg/connectloginmysqli.php");
$query = "Select  `id`, `address` from `watch_incoming` where `account`='$user_id' LIMIT 1";
//echo $query;

$result = mysqli_query($connect, $query) or die(mysqli_error($connect));
$row_cnt = mysqli_num_rows($result);
if ($row_cnt > 0) { 
//echo 'in return = TRUE';
 return $row_cnt;
}
else {
//echo 'in return = 0';
  return "0";
}
}

public function  getUserHistorybytxid($txid){
include($_SERVER['DOCUMENT_ROOT']."/db_cfg/db2bbconfiga.php");
include($_SERVER['DOCUMENT_ROOT']."/db_cfg/connectloginmysqli.php");
$query = "Select  `id`, `address` from `watch_incoming` where `txid`='$txid' LIMIT 1";
//echo $query;

$result = mysqli_query($connect, $query) or die(mysqli_error($connect));
$row_cnt = mysqli_num_rows($result);
if ($row_cnt > 0) { 
//echo 'in return = TRUE';
 return "1";
}
else {
//echo 'in return = 0';
  return "0";
}
}



}
