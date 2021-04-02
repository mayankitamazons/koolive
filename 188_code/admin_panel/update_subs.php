<?php
include("config.php"); 

	$user_id=$_POST['user_id'];
	$product=$_POST['product'];
	$stl_key=$_POST['stl_key'];

$total_rows = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM merchant_subscription WHERE `user_id`=$user_id"));

if($total_rows >= 1)
{
	
	
 $tt = mysqli_query($conn,"UPDATE `merchant_subscription` SET current_status='2' WHERE `user_id`=$user_id");
 $insert_value = mysqli_query($conn, "INSERT INTO `merchant_subscription` SET user_id='$user_id',type='$product',current_status='1'");

}
else
{
	
	 $tt = mysqli_query($conn, "INSERT INTO merchant_subscription SET user_id='$user_id',type='$product',current_status='1'");

}

?>
