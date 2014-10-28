<?php
function __autoload($class)
{
 include_once('includes/'.$class.'.class.php');
}
?>

<html>
<head>
<title>BitcoinShop suite test page</title>
</head>
<body>
<?php
$sys=new BitcoinSystem('http://rpcuser:password@localhost:18332');
?>

<h3>System information - BitcoinSystem class</h3>
<table border>
<tr><td>Overall balance</td><td><?php echo $sys->getBalance(); ?></td></tr>
<tr><td>SysInfo</td><td><pre><?php print_r($sys->getInfo()); ?></pre></td></tr>
</table>


<?php
$bc=new BitcoinAccount('http://rpcuser:password@localhost:18332');
?>

<h3>System account - BitcoinAccount class</h3>
<table border>
<tr><td>Default system address</td><td><?php echo $bc->getAddress(); ?></td></tr>
<tr><td>All system addresses</td><td><pre><?php print_r($bc->getAddressList());?></pre></td></tr>
<tr><td>System balance</td><td><?php echo $bc->getBalance(); ?></td></tr>
</table>

<?php
$tt=new BitcoinUser('http://rpcuser:password@localhost:18332','TEST');
?>

<h3>"TEST" user account - BitcoinUser class</h3>
<table border>
<tr><td>Default address</td><td><?php echo $tt->getAddress(); ?></td></tr>
<tr><td>All account addresses</td><td><pre><?php print_r($tt->getAddressList());?></pre></td></tr>
<tr><td>Balance</td><td><?php echo $tt->getBalance(); ?></td></tr>
</table>

<?php
 $tt->take(0.01);
?>
<h3>Take coins from "TEST": 0.01</h3>
<table border>
<tr><td>System balance</td><td><?php echo $bc->getBalance(); ?></td></tr>
<tr><td>"TEST" balance</td><td><?php echo $tt->getBalance(); ?></td></tr>
</table>

<?php
 $tt->give(0.01);
?>
<h3>Give coins to "TEST": 0.01</h3>
<table border>
<tr><td>System balance</td><td><?php echo $bc->getBalance(); ?></td></tr>
<tr><td>"TEST" balance</td><td><?php echo $tt->getBalance(); ?></td></tr>
</table>
