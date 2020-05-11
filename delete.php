<?php
include('config.php');
if($_GET['aId'])
{
	$d_id=$_GET['aId'];
	mysqli_query($conn,"DELETE FROM set_working_hr WHERE id='$d_id'");
}
?>