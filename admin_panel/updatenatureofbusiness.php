<?php
include('config.php');
extract($_POST);
if($_POST['service_id'] && $_POST['updatedid'])
{
echo $id=$_POST['updatedid'];
echo $service_id=$_POST['service_id'];
$update=mysqli_query($conn,"UPDATE users SET `service_id`='$service_id' WHERE id='$id' ");
}
if($_POST['updatedid'] && $_POST['whatapp_group_name'])
{
	// echo "UPDATE users SET `whatapp_group_name`='$whatapp_group_name' WHERE id='$updatedid' ";
	$update=mysqli_query($conn,"UPDATE users SET `whatapp_group_name`='$whatapp_group_name' WHERE id='$updatedid' ");
	if($update)
	{
		$res=array('status'=>true,'msg'=>'Whatapp group name updated'); 
	}
	else
	{
		$res=array('status'=>false,'msg'=>'Failed to update Whatapp group name'); 
	}
	echo json_encode($res);
	die; 
}
?>