<?php
include("config.php");
   extract($_POST);
	$current_date= date("Y/m/d"); 
	$id=$_POST['id'];
	$categoryname = $_POST['categoryname'];
	

	echo $old_cn = $_POST['category_name'];
	echo $catparent = $_POST['catparent'];
	
	
	echo $p_n_category = str_replace(' ', '-', $categoryname);
	
	echo $p_o_category = str_replace(' ', '-', $old_cn);
	
	$u_id =  $_SESSION['login'];
	 // echo "UPDATE `category` SET `category_name` = '$categoryname',catparent='$catparent',created_date='$current_date' WHERE `id` =$id";
	 // die;
 $tt = mysqli_query($conn,"UPDATE `category` SET `rider1` ='$rider1',`rider2` ='$rider2',`rider3` ='$rider3',`rider4` ='$rider4',`rider5` ='$rider5',`category_name` = '$categoryname',catparent='$catparent',created_date='$current_date' WHERE `id` =$id");
 echo $ttw = mysqli_query($conn,"UPD `products` SET `category` = '$p_n_category',created_date='$current_date' WHERE `category` = '$p_o_category' AND user_id = $u_id");
?>

