<?php
include("config.php");
$l_login_id=$_SESSION['login'];
$sql = "SELECT COUNT(*) AS `total_count` FROM `order_voice_list` where merchant_id='$l_login_id'";
$total_rows = mysqli_query($conn,$sql);
$result 	= mysqli_fetch_assoc($total_rows);
echo $result['total_count'];
?>