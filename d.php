<?php
include('config.php');
// copy delivery plan and update
 $d_q="select * from delivery_plan where merchant_id='7703' order by id asc";
$d_query=mysqli_query($conn,$d_q);

$total_row=mysqli_num_rows($d_query);
$all_record=mysqli_fetch_all($d_query);

if($total_row>0)
{
	$location_range=5000;  
	// extract($d_data);
	$all_merchant_q=mysqli_query($conn,"select id,delivery_plan_change from users where id not in(5062,7703) and user_roles='2' and isLocked='0' and delivery_plan_change='n'");
	while($r=mysqli_fetch_array($all_merchant_q))
	{
		$m_id=$r['id'];
		foreach($all_record as $s)
		{
			echo $q="INSERT INTO delivery_plan (`merchant_id`,`min_distance`,`max_distance`,`charge`,`status`) VALUES ('$m_id','$s[2]','$s[3]','$s[4]','y')";
			// die;
			$insert-mysqli_query($conn,$q);
		}
		echo "</br>";
		// $insert-mysqli_query($conn,$q);
		
			echo $q2="UPDATE `users` SET `delivery_plan_change` = 'y',location_range='$location_range',location_order='1' WHERE `users`.`id` ='$m_id'";
			$update=mysqli_query($conn,$q2);
		
		echo "</br>";
		// die;
	}  
}   
?>