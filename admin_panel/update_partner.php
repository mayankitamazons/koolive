<?php
include('config.php');
if(isset($_POST['submit']))
{
	// var_dump($_POST);
	//  die;
	// print_r($_POST);
	// die;
	extract($_POST);
	
	$totalcount=count($p_list);
	if(count($p_list)>0)
	{
		$i=0;
		$user_array = array();
		$s_p_list=explode(',',$all_p_list);
		// print_r($p_list);
		// print_r($s_p_list);
		// die;
		// find all merchant and delete which not exit in list
		$t_list=array();
		foreach($s_p_list as $k=>$i)
		{
			$t_list[]=$m_list[$i];
		}
		$t_ids = implode(',',$t_list);
		//
		// $qupdate="update unrecoginize_coin set status=0 where merchant_id not in($t_ids) and user_id='$merchant_id'";
		if($t_ids)
		 $qupdate="update unrecoginize_coin set status=0 where merchant_id not in($t_ids) and user_id='$merchant_id'";
		else
			$qupdate="update unrecoginize_coin set status=0 where user_id='$merchant_id'";    
		 
		mysqli_query($conn,$qupdate);
		// die;
		foreach($s_p_list as $k=>$i)
		{
			// echo "For loop </br>";
				// print_r($p_list[$i]);
				// print_r($i);
				// die;  
				 $s_p_id=$p_list[$i];
				 $s_class=$limit_class[$i];
				$s_coin_max_limit=$coin_max_limit[$i];
				$l_coin=limitclass($s_class);
				
				if($s_p_id)
				{
					// $s_p_id=$p_list[$i];
					// update record 
					$q="update unrecoginize_coin set coin_max_limit='$s_coin_max_limit',coin_class='$s_class',coin_limit='$l_coin' where id='$s_p_id'";
				}
				else if($m_list[$i])
				{
					$s_m_id=$m_list[$i];
					// new insert
					$q="INSERT INTO unrecoginize_coin(user_id,merchant_id,coin_max_limit,status,coin_class,coin_limit) VALUES ('$merchant_id', '$s_m_id', '$s_coin_max_limit', '1','$s_class','$l_coin')";
			
				}
				// echo $q;
				
				// echo "</br>";
				mysqli_query($conn,$q);
			// $s_p_id='';
		}
		// $i++;
	}
}
// die;    
$m_detail=mysqli_fetch_assoc(mysqli_query($conn,"select name from users where id ='$merchant_id'"));
$_SESSION['s']="Partner List updated  for ".$m_detail['name'];
header('Location:partnerlist.php'); 
// die;
function limitclass($l_class)
{
	if($l_class=="A")
	$l_coin=200;
	else if($l_class=="B")
	$l_coin =500;
	else if($l_class=="C")
	$l_coin=5000;
	else if($l_class=="D")
	$l_coin=10000;
	return $l_coin;
}
?>