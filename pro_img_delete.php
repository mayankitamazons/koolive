<?php
include('config.php');
 $id=$_POST['id'];
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//
//echo "UPDATE products SET image='' WHERE id='$id'";
					
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
		//$str = "Screenshot%202021-09-26%20133809.jpg";
		$pattern = "/screenshot/i";
		
		if (preg_match($pattern, $old_image)) {
			$imageName = str_replace(" ","%20",$old_image);
		}else{
			$imageName = $old_image;
		}

		$old_image_path="product/".$imageName;
		//$old_image_path = "product/XLSX2/Screenshot%202021-09-26%20133809.jpg";
		//product/XLSX2/Screenshot 2021-09-26 133809.jpg
		$sirvClient->deleteFile($old_image_path);  
	}
}

					
$remove = mysqli_query($conn,"UPDATE products SET image='' WHERE id='$id'");
 //$remove = mysqli_query($conn,"DELETE FROM products WHERE id ='$id'");

?>