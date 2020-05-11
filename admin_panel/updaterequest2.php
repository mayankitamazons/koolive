<?php
include('config.php');
$id=$_POST['mid'];
$status_note=$_POST['mstatus'];

$update=mysqli_query($conn,"UPDATE requests SET `status_note`='$status_note'  WHERE id='$id' ");
?>