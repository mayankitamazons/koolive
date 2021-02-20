<?php
include("config.php");
$order_id = $_POST['order_id'];
$q1=$_POST["q1"];
$q2=$_POST["q2"];
$q3=$_POST["q3"];
$q4=$_POST["q4"];
$q5=$_POST["q5"];
$q6=$_POST["q6"];
$q7=$_POST["q7"];
$q8=$_POST["q8"];
$q9=$_POST["q9"];
$remark=$_POST["remark"];

$sql = "INSERT INTO `feedback`(`order_id`, `q1`, `q2`,`remark`) VALUES ($order_id,$q1,$q2,'$remark');";

if(mysqli_query($conn, $sql)){
	$sql1 = "UPDATE `order_list` SET `reviewed`=1 where `id`='$order_id'";
	mysqli_query($conn,$sql1);
    echo "Records inserted successfully.";
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}   




?>