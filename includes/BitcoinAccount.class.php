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
class BitcoinAccount
{

 /**
 * jsonRPCClient instance
 * @var jsonRPCClient
 */
 protected $rpc=NULL;

 /**
 * Account ID
 * @var integer|string
 */
 protected $account='';

 /**
 * Class constructor
 * @param string|jsonRPCClient $rpc_url JSON-RPC URL or class instance for connection to bitcoind.
 * @param string|integer $account Account ID. Empty string is system default account.
 */
 public function __construct($rpc,$account='')
 {
  if($rpc instanceof jsonRPCClient)
  {
   $this->rpc=$rpc;
  }
  else
  {
   $this->rpc=new jsonRPCClient($rpc);
  }
  if($this->rpc)
  {
   if($account)
   {
    $this->account=$account;
   }
  }
  else
  {
   throw new Exception('BitcoinAccount: cannot connect to bitcoin JSON-RPC.');
  }
 }

 /**
 * Get recently created bitcoin address for user. Create it if there is none.
 * @param boolean $new Force create
 * @return string|NULL
 */




 public function getAddress($new=false)
 {
  if($new)
  {
   return $this->rpc->getnewaddress($this->account);
  }
  else
  {
   $addrs=$this->getAddressList();
   if(count($addrs))
   {
    return $addrs[count($addrs)-1];
   }
   else
   {
    return $this->getAddress(true);
   }
  }
 }

 /**
 * Get all bitcoin addresses for account.
 * @return array|NULL
 */
 public function getAddressList()
 {
  return $this->rpc->getaddressesbyaccount($this->account);
 }
public function listTransactions($account)
{//,$count=1, $from=0
 return $this->rpc->listtransactions($account,1, 0);
}

public function getReceivedByAccount($account)
{//,$count=1, $from=0
 return $this->rpc->getreceivedbyaccount($account,0, 0 );
}
 

public function listAccounts()
 {
  return $this->rpc->listaccounts();
 }

public function listReceivedByAccount()
 {
  return $this->rpc->listreceivedbyaccount();
 }
public function createAccountAndAddressbyUserID($user_id)
 {
return $this->rpc->getaccountaddress($user_id);
}
public function createNewAddress($user_id){
return $this->rpc->getnewaddress($user_id); 
}
/**
 * Takes all accounts with a positive balance and transfers the address and blance out of the users account to the cold storage account staging
The ataging account will be moved when the next person requests a balance update..
 * @param integer $N Defaults to 10
 * @return array|NULL
 */
public function moveAddressToColdStorage($bitcoinaddress){
return $this->rpc->setaccount($bitcoinaddress, 'cold_storage_staging_address');
 }
public function moveCreditsToColdStorage($frombitcoinaccount, $tobitcoinaddress, $balance){
//<fromaccount> <tobitcoinaddress> <amount> [minconf=1] [comment] [comment-to] 
//sends every balance in staging area with six or more confirmations to cold storage
$balance = $balance - .0001;//subtract the transaction fee set in bitcoin.conf before sending - will result in 0 balance in address afterwards
echo '<br>in func $frombitcoinaccount = ', $frombitcoinaccount;
//getaccount 	<bitcoinaddress> 
echo '<br>in func $tobitcoinaddress = ', $tobitcoinaddress;

echo '<br>in func  balance =  ', $balance;
     $message = "Moved credits from $frombitcoinaccount to cold storage $tobitcoinaddress before mothballing ";                                                   
//changed to just 1 confirm for testing
return $this->rpc->sendfrom($frombitcoinaccount, $tobitcoinaddress, $balance, 3, $message);
}
public function moveColdstorageAddressesToMothballed($bitcoinaddress){
return $this->rpc->setaccount($bitcoinaddress, 'mothballed');
 }	
 /**
 * Get last N transactions for account.
 * @param integer $N Defaults to 10
 * @return array|NULL
 */
public function getAddressesByAccount($account){



 return $this->rpc->getaddressesbyaccount($account); 

}



 public function getTransactionList($N=10)
 {
  return $this->rpc->listtransactions($this->account,$N);
 }

 /**
 * Get account balance.
 * @return float
 */
 public function getBalance()
 {
  return $this->rpc->getbalance($this->account);
 }
public function getBalanceAfterSixConfirms($value){

echo '<br><br>in getBalanceAfterSixConfirms func - value (is it the sending address? = ', $value;

echo '<br><br>the total sendable balance (subtract trans fee from this)  should be here ----->>>>>>', $this->rpc->getreceivedbyaddress ($value,1);
return $this->rpc->getreceivedbyaddress ($value,1);
}

 /**
 * Transfer specified amount out of user's account
 * @param string $target_addr Target bitcoin address
 * @param float|integer $amount Amount to transfer.
 * @param string $comment Transfer comment
 * @return boolean
 */
 public function transfer($user_id,$target_addr,$amount,$unknown, $comment, $comment_to)
 {
$unknown=3;//
//transfer($user_id, $sendto_address,$amount, "3", $comment, $comment_to);

echo '<br> i n BicoinAccount class transfer func sending this address to sendfrom func after validating';
  $valid=$this->rpc->validateaddress($target_addr);
echo '<br>target address being validates ', $target_addr;
  if($valid['isvalid'])
  {
echo '<br> i n BicoinAccount class is valid so  sending this address to sendfrom func after validating';

   return $this->rpc->sendfrom($user_id,$target_addr,$amount);
  }
 }


 /**
 * Transfer specified amount to another user in same system
 * @param string $target_addr Target account
 * @param float|integer $amount Amount to transfer.
 * @return boolean
 */
 public function transferInternal($target_account,$amount)
 {
  return $this->rpc->move($this->account,$target_account,$amount);
 }

}
