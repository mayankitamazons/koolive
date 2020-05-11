<?php
include("config.php");
 $del_id=$_GET['c_id'];
mysqli_query($conn,"DELETE FROM `classfication_service` WHERE `classfication_service`.`id` ='$del_id'");
$_SESSION['show_msg']="Record Deleted Successfully";
header('Location:classficationmerchant.php');
?>