<?php
include('config.php');
$query="SELECT * FROM `users` WHERE user_roles='2' and isLocked='0'";
$tq="select * from timings where merchant_id='5326'";
$tsql=mysqli_query($conn,$tq);
$all_record=mysqli_fetch_all($tsql);

$query=mysqli_query($conn,$query);
while($m = mysqli_fetch_array($query))
{
	$m_id=$m['id'];
	foreach($all_record as $s)
	{
		echo $insert="INSERT INTO `timings` (`merchant_id`, `day`, `start_time`, `end_time`) VALUES ('$m_id', '$s[2]', '$s[3]', '$s[4]')";
		echo "</br>";
		mysqli_query($conn,$insert);
	}
	
}
?>