<?php

/* A unique identifier of some sort such as a user id can be used as the account name. I normally will have the authentication script provide the user id here and use it.
To switch from Testnet to real bitcoin change value of isTestCoin from 1 to 0 in the curl POSTFIELDS array list
*/
//$account = $user_id;
$account = 1001;// this is a temporary HARDCODED testuser only - delete or comment out this line and replace with something
//similar to the line above it to use your own unique user IDs
$file="http://your_VPS_address_here/process_testnet.php";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $file);
curl_setopt($ch, CURLOPT_POSTFIELDS, array('user_id' => $account, 'isTestCoin'=> 1  ));
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$data = curl_exec($ch);
curl_close($ch);

  ?>
		

 <?echo $data;

if(empty($data)){
echo "There is a problem connecting with the server. Please try again later or contact the Bungeebones administrator for assistance. Thank you.";

}
?>
