<?php
function __autoload($class)
{
 include_once('includes/'.$class.'.class.php');
}



include($_SERVER['DOCUMENT_ROOT']."/classes/process_testnet_class.php");
$bbbridge=new process_testnet();
$account = $_POST['user_id'];// will be able to change this to POST when curl sends the request in from BB
$isTestCoin = 1;// true
$user_id = $_POST['user_id'];// will be able to change this to POST when curl sends the request in from BB

//first we perform group maintenance on all the data in the system
// we look at everything account/user in Bitcoin qt 
$bc=new BitcoinAccount('http://rpcuser:password@localhost:18332');

$usertrans = $bc->listTransactions($user_id); 
$countusertrans= count($usertrans);
print_r($usertrans);
if($countusertrans > 0){
	for($i=0;$i<=$countusertrans-1;$i++){
		unset($address);
		unset($amount);
		unset($category);
		unset($confirmations);
		unset($walletconflicts);
		unset($time);
		unset($timereceived);
		unset($txid);
unset($UserHistorybytxid);
unset($UserHistorybyuser);
unset($checkDepositsInArchive);
unset($gotflag);

		$address = $usertrans[$i]['address'];
		$amount = $usertrans[$i]['amount'];
		$category = $usertrans[$i]['category'];
		$confirmations = $usertrans[$i]['confirmations'];
		$walletconflicts = $usertrans[$i]['walletconflicts'];
		$time = $usertrans[$i]['time'];
		$timereceived = $usertrans[$i]['timereceived'];
		$txid = $usertrans[$i]['txid'];
				if($txid ==""){
				//show the existing address
				echo "<h1>Please make your Bitcoin deposit to this unique and custom Bitcoin address - made for you  ";
				$users_unique_send_to_address = $bc->createAccountAndAddressbyUserID($user_id);
				echo $users_unique_send_to_address;
				echo "<br>Then Refresh This Page Or The User Control Panel Page (Watch The Balance) To Monitor Status</h1>";
				exit();
				}
				else{
				//echo '<br>$txid = ', $txid;
				$checkDepositsInArchive = $bbbridge->checkDepositsInArchive($txid);
				if($checkDepositsInArchive < 1){
				$UserHistorybytxid = $bbbridge->getUserHistorybytxid($txid);
				//echo '<br>$UserHistorybytxid = ', $UserHistorybytxid;
				$UserHistorybyuser = $bbbridge->getUserHistorybyuser($user_id);

				$gotflag = $bbbridge->getFlag($txid);

				//echo 'check waht happens if the selector "if not" is working ', $checkDepositsInArchive;
						//thi section has important logic. We need to make sure before we insert that the transaction isn't so old that it has been moved to archive
						if($txid!=""){
							if($UserHistorybytxid>0){
				//means this transaction with this txid is already here
							$bbbridge->updateWatchIncomingshort($account, $address, $category,$amount, $confirmations, $txid, $walletconflicts, $time, $timereceived);
							}
							else
							{
							$bbbridge->insertWatchIncoming($account, $address, $category,$amount, $confirmations,  $txid, $walletconflicts, $time, $timereceived);
							}
						}//close if
				//now that watching is updated and we know it doesn't have archived items in it 
				//maybe need to retrieve watching and use what is in it as the index from here forward?


if($confirmations>0 and $gotflag == 0){
							$bbbridge->updateFlag($txid);
							$bbbridge->credit_BB_account($user_id, $amount, $isTestCoin, $txid);
							}
						if($confirmations>2){
$sendto_address = "mqSp5Bnjf6QJLjb9HMq7uhWfJKjuT5MZc7";
							$bbbridge->recordColdStorage ($account,$address,$amount,$category,$confirmations,$txid,$sendto_address,$walletconflicts,$time,$timereceived);
							$bbbridge->moveToArchive ($txid);
							//need to make another function to actual spend the money from those address to cold storage now
//mqSp5Bnjf6QJLjb9HMq7uhWfJKjuT5MZc7 mock testnet cold storage address which will be used to fund user 2 and fund the faucet the exchange
$comment = "txid = $txid";

//this is an Armory watch only address
$amount = $amount -0.0001;
$txid_new = $bc->transfer($user_id, $sendto_address,$amount, "3", $comment, $comment_to);
//located in includes/BitcoinAccount.class.php

//echo '<br>txid new', $txid_new;
//echo '<br>address', $address;
//echo '<br>$user_id = ', $user_id;
//now send to bungeebones.com 
$bbbridge->record_cold_transfer($user_id, $amount, $isTestCoin, $txid, $txid_new, $address, $sendto_address );
//located in classes/process_testnet_class.php
//talks to bungeebones.com via curl
//need to actually spend the money from those address to cold storage now
//And make one more record of the new transaction ID of it going into cold storage
echo 'The new txid of it going to cold storage is :';
echo $txid_new;
echo "<h1>The transaction process is completed. If you wish to make another deposit refresh the page to get a new address (please do not reuse addresses nor make multiple deposits to the same address</h1>  ";
				exit();
						      }
		//end the iteration through open trans
//make the rest of this be iterated by what is in watching

							
				 }//close check if in arcihive
				elseif($txid=="" AND $checkDepositsInArchive < 1)//this is duplicate code of next section but this runs if opentrans has old transactions while the other only runs for new users
				{
				echo "<h1>Please make your Bitcoin deposit to this unique and custom Bitcoin address - made for you  ";
				$users_unique_send_to_address = $bc->createAccountAndAddressbyUserID($user_id);
				echo $users_unique_send_to_address;
				echo "<br>Then Refresh This Page Or The User Control Panel Page (Watch The Balance) To Monitor Status</h1>";
				}

                      }//close check if txid >0 
                    }//close for
}//close if


$process_these_rows = $bbbridge->getWatchingUserTxnRows($user_id);
//will return a multidim array of everything in watching . Multidim because it covers for if/when user sends more than one deposit
if(!is_array($process_these_rows)){
echo "<h1>Please make your Bitcoin deposit to this unique and custom Bitcoin address - made for you  ";
$users_unique_send_to_address = $bc->createAccountAndAddressbyUserID($user_id);
echo $users_unique_send_to_address;
echo "<br>Then Refresh This Page Or The User Control Panel Page (Watch The Balance) To Monitor Status</h1>

<table ><tr><td>
<h2>Copy the address and go to any of the following Testnet faucets (or any other one you choose) and paste the address there. After one confirmation from the network the funds will appear as available to spend for better placement of your link. </h2>
<p><a target='_blank' href='http://tpfaucet.appspot.com'>TPs Testnet Faucet</a>
<p><a target='_blank' href='http://faucet.xeno-genesis.com/'>Mojocoin\'s Testnet3 Faucet</a>
<p><a target='_blank' href='https://en.bitcoin.it/wiki/Bitcoin_faucet'> A list of more of them</a>

";
exit();
}
else
{
echo '<h1> Need to process these rows, gotten from watching,  now that watching is up to date.<br>Might want to reorder the array by date in reverse?</h1>';
//print_r($process_these_rows);

	
								
									if($confirmations >0 AND $confirmations <3){
									echo '<h1>Your account has been credited but is still being processed. Please do not make any additional deposits while the transaction is being processed. Thank you for your patience. You can now return to your user control panel and use the credits to purchase better placement.';

									}
									if($confirmations >2){
							echo '<h1>The next time you refresh this page you will be presented with a completely new address to deposit to. Please do not reuse the old one.';
									}

								}//they don't need an address displayed
								


