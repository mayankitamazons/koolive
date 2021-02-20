<?php
 include('config.php');
   $q="select user_membership_plan.id, users.mobile_number from user_membership_plan inner join users on user_membership_plan.user_id=users.id  where user_membership_plan.token_membership='' limit 0,100";

 $m_data=mysqli_query($conn,$q);
 $i=0;
 while($row=mysqli_fetch_assoc($m_data))
 {
	 $s_id=$row['id'];
	 $mobile_number=$row['mobile_number'];
	$token=gen();
	 $q=mysqli_query($conn,"UPDATE `user_membership_plan` SET `token_membership` = '$token',`user_mobile`='$mobile_number' WHERE `user_membership_plan`.`id` ='$s_id'");
	 if($q)
		$i++;
 }
 echo "Total Record".$i;
 function gen(){
        $num = rand(100000,999999);
$query_idgetrs = "SELECT * FROM user_membership_plan where token_membership = $num";
$idgetrs = mysqli_query($conn, $query_idgetrs);
$row = mysqli_num_rows($idgetrs);

        if($row >= 1){
        gen();
        }
        return $num;
        }
?>