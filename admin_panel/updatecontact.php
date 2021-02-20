<?php
include("config.php");

$id=$_POST['recordid'];
echo $id;
$status=$_POST['updated_stauts'];
$date=time();
$update=mysqli_query($conn,"UPDATE contacts SET action='$status', action_date='$date' WHERE id='$id'");
?>