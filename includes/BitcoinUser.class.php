<?php
/**
* Bitcoin daemon control for e-shop.
* @author m0Ray <m0ray@nm.ru>
* @version 1.0
* @package BitcoinShop
*/


/**
* User account class with some security restrictions.
* @package BitcoinShop
*/
class BitcoinUser extends BitcoinAccount
{

 /**
 * Class constructor
 * @param string|jsonRPCClient $rpc JSON-RPC URL or jsonRPCClient instance
 * @param string|integer $user_id User account ID. Cannot be zero or empty string - for system security.
 */
 public function __construct($rpc,$user_id)
 {
  if($user_id)
  {
   parent::__construct($rpc,$user_id);
  }
  else
  {
   throw new Exception('BitcoinUser: invalid user ID.');
  }
 }

 /**
 * Transfer specified amount to another user in same system
 * @param string $target_addr Target account. Cannot be zero or empty string - for system security.
 * @param float|integer $amount Amount to transfer.
 * @return boolean
 */
 public function transferInternal($target_account,$amount)
 {
  if($target_account)
  {
   return $this->rpc->move($this->account,$target_account,$amount);
  }
 }

 /**
 * Takes specified amount from user's account to system default account.
 * @param float|integer $amount Amount to take.
 * @return boolean
 */
 public function take($amount)
 {
  return parent::transferInternal('',$amount);
 }

 /**
 * Gives specified amount to user's account from system default account.
 * @param float|integer $amount Amount to give.
 * @return boolean
 */
 public function give($amount)
 {
  return $this->rpc->move('',$this->account,floatval($amount));
 }

}

