<?php
include('config.php');
 $query="SELECT id,user_id,varient_exit FROM `products` WHERE id not in(select product_id 
FROM sub_products where status='1' GROUP by product_id) and varient_exit='y' and status='0'";
$q=mysqli_query($conn,$query);

while($r=mysqli_fetch_array($q))
{
	$pro_id=$r['id'];
  echo   $q1="update products set varient_exit='n' where id='$pro_id'";
  echo "</br>";
    mysqli_query($conn,$q1);
}
?>