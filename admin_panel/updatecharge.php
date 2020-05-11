<?php
include("config.php");

echo $id=$_POST['recordid'];
echo $status=$_POST['updatedpercent'];
$date=time();
echo "UPDATE charges SET percent = '$status', last_change='$date' WHERE id='$id'";
$update=mysqli_query($conn,"UPDATE charges SET percent = '$status', last_change='$date' WHERE id='$id'");
?>