<?php
include('config.php');
 echo $id=$_POST['id'];

//
//$remove = mysqli_query($conn,"UPDATE subscription SET status=1 WHERE id='$id'");
  $remove = mysqli_query($conn,"DELETE FROM users WHERE id ='$id'");

?>
