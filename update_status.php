<?php
include("config.php");

if( isset( $_POST['method']) && ( $_POST['method'] == "updatePrinted" )  ) {
    $id= $_POST['id'];
    $printed = $_POST['printed'];
    mysqli_query($conn, "UPDATE order_list SET printed='$printed' WHERE id='$id'");
    echo('update printed.');
} else {
    $id= $_POST['id'];
    $oid= $_POST['oid'];
    $orid= $_POST['orid'];
    $status= $_POST['status'];
    $_SESSION['mm_id'] = $id;
    $_SESSION['o_id'] = $oid;
    $_SESSION['orid'] = $orid;
    $merchant_id = $_SESSION['login'];
    $invoice = mysqli_fetch_assoc(mysqli_query($conn, "SELECT max(invoice_no) no FROM order_list WHERE merchant_id='$merchant_id'"));
    $invoice_no += $invoice['no'] + 1;
    mysqli_query($conn, "UPDATE order_list SET status='$status', status_change_date = CURDATE() WHERE id='$id'");
    // echo $status;
    //die;
    if($status==1)
    {
        $order_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id,user_id,user_mobile  FROM order_list WHERE id='".$id."'"));
        $user_id=$order_data['user_id'];
        $order_id=$order_data['id'];
        $user_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT moengage_unique_id,onesignal_player_id FROM users WHERE id='".$user_id."'"));
        $push_id=$user_data['moengage_unique_id'];
        $user_onesignal_player_id=$user_data['onesignal_player_id'];
		$rand= substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1,4);
			$url="https://www.koofamilies.com/orderlist.php?did=".$user_id."&vs=".$rand."&oid=".$order_id;
		
		$sms_msg=$url." Your order is already completed. You can check the status of your order by clicking on the link.";   
		// if($order_data['user_mobile'])
		// {
			// $whatappno=$order_data['user_mobile'];
			// whatappmsg($whatappno,$sms_msg);
		// } 
		$sms_msg= $data['message']='Your food is ready, we are delivering foods to you now';
        if ($push_id) {
            $result=exec("/usr/bin/python myscript.py");
            $resultarray=explode(",",$result);
            if (count($resultarray)>0) {
                // code...
                $data['camp_name']=$camp_name=$resultarray[0];
                $data['sign']=$sign=$resultarray[1];
                $data['push_email']=$push_id;
                $data['title']='Order Done';
                $data['message']='Your food is ready, we are delivering foods to you now';
                $data['redirectURL']= $url;
                include 'push.php';   
                $user = new Push();
                $resultpush = $user->send_push($data);
                // print_R($resultpush);
                // die;
            }
        }
		if($user_onesignal_player_id)
					{
						
						 include 'onetest.php';
						 $onesignal = new Onetest();
						$data['push_id']=$user_onesignal_player_id;
						$data['message']=$sms_msg;
						$data['redirectURL']=$url;
						$resultpush = $onesignal->sendMessage($data);
					}
    }
}
?>
