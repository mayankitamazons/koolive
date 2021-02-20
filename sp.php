<?php 
include('config.php');
$query = mysqli_query($conn,"SELECT * FROM `users` WHERE user_roles=2 and isLocked=0 order by id desc");
$t=0;
while ($row=mysqli_fetch_assoc($query)){
	$m_id=$row['id'];
	$in="INSERT INTO `unrecoginize_coin` (`user_id`, `merchant_id`, `coin_max_limit`, `coin_class`, `coin_limit`) 
	VALUES ($m_id, '6419', '500', 'B', '500')";
	mysqli_query($conn,$in);
	$t++;
}
echo "Total Record updated ".$t;
?>