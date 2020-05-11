<?php
include("config.php"); 

 $id=$_POST['id'];
 //~ $productname=$_POST['productname'];
 //~ $category=$_POST['category'];
 //~ $product_type=$_POST['product_type'];
 //~ $amount_com=$_POST['amount_com'];
 
		$productname = $_POST['productname'];
		//~ $category = addslashes($_POST['category']);
		$product_type = $_POST['product_type'];
		$period = $_POST['period'];
		$package_fee = $_POST['package_fee'];
		$catgeories = $_POST['catgeories'];
		$product_type = $_POST['product_type'];
		$website = $_POST['website'];
		$video = $_POST['video'];
		$sms = $_POST['sms'];
		$about = $_POST['about'];
		$referral = $_POST['referral'];
		$welcome =$_POST['welcome'];
		$restaurant = $_POST['restaurant'];
		$renewal = $_POST['renewal'];
 
 
 
 
 //print_r($_POST);


 $tt = mysqli_query($conn,"UPDATE `subscription` SET `subscription_name`='$productname',subscription_name='$productname',subscription_rate='$package_fee', subscription_qyt='$product_type',about='$about',subscription_period='$period',categories='$catgeories',company_website='$website',company_video='$video',advertisement_sms='$sms',referral_commision='$referral',welcome_note='$welcome',search_resataraunt='$restaurant',renewal_fee='$renewal' WHERE `id`=$id");
?>
