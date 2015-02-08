<?php
$connect=mysqli_connect($host,$username,$password) or die("Error connecting to Database! " . mysqli_error($connect));
mysqli_select_db($connect,$database) or die("Cannot select database! " . mysqli_error($connect));
?>
