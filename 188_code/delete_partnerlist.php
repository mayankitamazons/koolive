<?php
include('config.php');
$plan_id=$_GET['plan_id'];
$q=mysqli_query($conn,"update unrecoginize_coin set status=0 where id='$plan_id'");
// $q=mysqli_query($conn,"delete from unrecoginize_coin  where id='$plan_id'");
header('Location:partner_list.php');

?>