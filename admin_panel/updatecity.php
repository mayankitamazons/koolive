<?php
include('config.php');

if($_POST['cityval'] && $_POST['updatedid'])
{
	echo $id=$_POST['updatedid'];
	echo $CIty=$_POST['cityval'];
	$update=mysqli_query($conn,"UPDATE users SET `city`='$CIty' WHERE id='$id' ");
}
?>