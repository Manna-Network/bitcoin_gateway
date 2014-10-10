<?php
/**
* Bitcoin daemon control with accounts.
* @author m0Ray <m0ray@nm.ru>
* @version 1.0
* @package BitcoinAccount
*/

/**
* System information class
* @package BitcoinAccount
*/
class BitcoinSystem
{

 /**
 * jsonRPCClient instance
 * @var jsonRPCClient
 */
 protected $rpc=NULL;

 /**
 * Class constructor
 * @param string|jsonRPCClient $rpc_url JSON-RPC URL or class instance for connection to bitcoind.
 */
 public function __construct($rpc)
 {
  if($rpc instanceof jsonRPCClient)
  {
   $this->rpc=$rpc;
  }
  else
  {
   $this->rpc=new jsonRPCClient($rpc);
  }
  if(!$this->rpc)
  {
   throw new Exception('BitcoinAccount: cannot connect to bitcoin JSON-RPC.');
  }
 }

 /**
 * Get overall system balance.
 * @return float
 */
 public function getBalance()
 {
  return $this->rpc->getbalance();
 }

 /**
 * Get some system information.
 * @return array
 */
 public function getInfo()
 {
  return $this->rpc->getinfo();
 }
 
 /**
 * Validate Bitcoin address.
 * Result contains "isvalid" (self-explanatory) and "ismine" (this address is on this node) boolean keys.
 * @param string $addr Address to validate
 * @return array
 */
 public function validateAddress($addr)
 {
  return $this->rpc->validateaddress($addr);
 }

 /**
 * Get account ID by address
 * @param string $addr Address to validate
 * @return array
 */
 public function validateAddress($addr)
 {
  return $this->rpc->getaccount($addr);
 }

}
