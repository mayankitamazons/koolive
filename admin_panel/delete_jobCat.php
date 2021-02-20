<?php 
if(isset($_GET['c_id'])){
if(!isset($_SESSION['admin']))
{
	header("location:login.php");
}
include("config.php");
    $id= $_GET['c_id'];
    
	 $query = mysqli_query($conn, "UPDATE `job_category` SET `status`='n' WHERE id = '$id'");
	header('Location: job_category.php');
}

