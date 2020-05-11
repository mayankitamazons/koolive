<?php
include('config.php');
if($_POST['m_id'])
{
	echo $id=$_POST['m_id'];
	echo $status=$_POST['upadtedstatus'];
	$update=mysqli_query($conn,"UPDATE users SET `show_business`='$status' WHERE id='$id' ");
}
else
{
	echo $id=$_POST['updatedid'];
	echo $status=$_POST['upadtedstatus'];
	$update=mysqli_query($conn,"UPDATE users SET `isLocked`='$status' WHERE id='$id' ");
}

?>