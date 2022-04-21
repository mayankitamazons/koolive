<?php
include('config.php');
 echo $id=$_POST['userid'];

$fetchData = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * from products WHERE id='$id'"));
$old_image = $fetchData['image'];


require_once('sirv.api.class.php');
$sirvClient = new SirvClient(
  // S3 bucket
  'koofamilies',
  // S3 Access Key
  'click4mayank@gmail.com',
  // S3 Secret Key
  'iFOyO1LVMp7EOYIW3IP9VOn76UBFFWdxGaDzuJGj2tHlHMP0'
);

if ($sirvClient->testConnection()) {  
	if($old_image)
	{
		$old_image_path="product/".$old_image;  
		$sirvClient->deleteFile($old_image_path);  
	}
}
$remove = mysqli_query($conn,"UPDATE products SET status=1 WHERE id='$id'");
 //$remove = mysqli_query($conn,"DELETE FROM products WHERE id ='$id'");

?>