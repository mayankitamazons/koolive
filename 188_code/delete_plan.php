<?php
include("config.php");
if($_GET['plan_id'])
{
	$merchant_id=$_SESSION['login'];
	$plan_id=$_GET['plan_id'];
	mysqli_query($conn, "update delivery_plan set status='n' where id='$plan_id'");
	// mysqli_query($conn, "delete  FROM delivery_plan WHERE id ='$plan_id'");
	header('Location: deliveryplan.php');
}
?>