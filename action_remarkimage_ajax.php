<?php
//echo $_POST['orderid'];
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include('config.php');
$file =	$_FILES['file_remark']['name'];
$file_image	=	'';

if($_FILES['file_remark']['name']!=""){
    extract($_REQUEST);
	$infoExt        =   getimagesize($_FILES['file_remark']['tmp_name']);
	if(strtolower($infoExt['mime']) == 'image/gif' || strtolower($infoExt['mime']) == 'image/jpeg' || strtolower($infoExt['mime']) == 'image/jpg' || strtolower($infoExt['mime']) == 'image/png'){
		$file	=	preg_replace('/\\s+/', '-', time().$file);
		$path   =   $_SERVER['DOCUMENT_ROOT'].'/upload/merchant_remark/';
		  $name_file=date('Ymdhis');//this part is for creating random name for image
		  $ext=end(explode(".", $_FILES["file_remark"]["name"]));//gets extension	
		  $image_file = $name_file.".".$ext;
		  if (move_uploaded_file($_FILES["file_remark"]["tmp_name"], $path.$image_file)) {
			//$sql = "UPDATE `order_list` SET `payment_proof` = '".$image_file ."' WHERE `order_list`.`id` = ".$_POST['orderid'];
			$sql = "UPDATE `users` SET `merchant_remark_image` = '".$image_file ."' WHERE `id` = ".$_POST['selected_user_id'];
			$result = mysqli_query($conn,$sql);             
		  } else {
		  }
		$res = array('image'=>$image_file,'status'=>true);
	}else{
		$res = array('image'=>$image_file,'status'=>false);
	}
	echo json_encode($res);
	die;
}
?>
