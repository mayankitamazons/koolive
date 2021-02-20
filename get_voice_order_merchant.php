<?php 
	include("config.php");
	$l_login_id=$_SESSION['login'];
    if(isset($_GET['countStatus']) && $_GET['countStatus'] != ""){
        $res= array();
        $query ="SELECT COUNT(*) AS `total_count` FROM `order_voice_list` WHERE status ='0' and merchant_id='$l_login_id'";
        $total_rows = mysqli_query($conn,$query);
      	$result 	= mysqli_fetch_assoc($total_rows);
        if(!empty($result)){
            $res['status']          = true;
            $res['message']         = "success";
            $res['total_count_new'] =  $result['total_count'];
        }
    }
    die(json_encode($res));
?>