<?php
//echo $_POST['orderid'];
include('config.php');

$file =	$_FILES['file']['name'];
$file_image	=	'';
if($_FILES['file']['name']!=""){
    extract($_REQUEST);
	$infoExt        =   getimagesize($_FILES['file']['tmp_name']);
	if(strtolower($infoExt['mime']) == 'image/gif' || strtolower($infoExt['mime']) == 'image/jpeg' || strtolower($infoExt['mime']) == 'image/jpg' || strtolower($infoExt['mime']) == 'image/png'){
		$file	=	preg_replace('/\\s+/', '-', time().$file);
		$path   =   $_SERVER['DOCUMENT_ROOT'].'/upload/';
		//move_uploaded_file($_FILES['file']['tmp_name'],$path);
		  $name_file=date('Ymdhis');//this part is for creating random name for image
		  $ext=end(explode(".", $_FILES["file"]["name"]));//gets extension	
		  $image_file = $name_file.".".$ext;
		  if (move_uploaded_file($_FILES["file"]["tmp_name"], $path.$image_file)) {
			//echo "The file ". htmlspecialchars( basename( $_FILES["cake_image"]["name"])). " has been uploaded.";
			$sql = "UPDATE `order_list` SET `payment_proof` = '".$image_file ."' WHERE `order_list`.`id` = ".$_POST['orderid'];
			//echo $sql;
			$result = mysqli_query($conn,$sql);             
		  } else {
			//echo "Sorry, there was an error uploading your file.";
		  }
		//if($insert){ echo 1; } else { echo 0; }
		echo $path.$image_file;exit;
	}else{
		echo 2;
	}
}
?>
