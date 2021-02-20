<?php
include('config.php');
$margin=$_POST['updateddata'];

$update=mysqli_query($conn,"UPDATE ex_margin SET `margin`='$margin' WHERE id='1'");
?>