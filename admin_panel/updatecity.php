<?php
include('config.php');

if($_POST['cityval'] && $_POST['updatedid'])
{
	echo $id=$_POST['updatedid'];
	echo $CIty=$_POST['cityval'];
	$update=mysqli_query($conn,"UPDATE users SET `city`='$CIty' WHERE id='$id' ");
}
if($_POST['langval'] && $_POST['updatedid'])
{
	 $id=$_POST['updatedid'];
	 $langval=$_POST['langval'];
	// echo "UPDATE users SET `default_lang`='$langval' WHERE id='$id' ";
	$update=mysqli_query($conn,"UPDATE users SET `default_lang`='$langval' WHERE id='$id' ");
}

if($_POST['m_state'] && $_POST['updatedid'])
{
	echo $id=$_POST['updatedid'];
	echo $m_state=$_POST['m_state'];
	$city_name=$_POST['city_name'];
	$update=mysqli_query($conn,"UPDATE users SET `m_state`='$m_state' WHERE id='$id' ");
	
	$u_city = mysqli_query($conn,"UPDATE users SET `city`='' WHERE id='$id'");
	
	
									
									
}

?>