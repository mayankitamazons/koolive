<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include("config.php");
function gw_send_sms($user,$pass,$sms_from,$sms_to,$sms_msg){           
    $query_string = "api.aspx?apiusername=".$user."&apipassword=".$pass;
    $query_string .= "&senderid=".rawurlencode($sms_from)."&mobileno=".rawurlencode($sms_to);
    $query_string .= "&message=".rawurlencode(stripslashes($sms_msg)) . "&languagetype=1";        
     $url = "http://gateway.onewaysms.com.au:10001/".$query_string;  
    
	// Initialize a CURL session. 
	$ch = curl_init();  
	  
	// Return Page contents. 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	  
	//grab URL and pass it to the variable. 
	curl_setopt($ch, CURLOPT_URL, $url); 
	  
	$result = curl_exec($ch); 
	 $ok = "success"; 
	      
    return $ok;  
} 
// date_default_timezone_set("Asia/Kuala_Lumpur");
//echo $sql= "SELECT order_list.status,order_list.merchant_id, order_list.created_on,users.id,users.handphone_number,users.pending_time FROM order_list inner join users on order_list.merchant_id = users.id WHERE users.pending_time!=0 AND status =0 AND DATE(`created_on`) = CURDATE() order by order_list.id DESC";
// echo "SELECT order_list.status,order_list.merchant_id, order_list.created_on,users.id,users.name,users.handphone_number,users.pending_time FROM order_list inner join users on order_list.merchant_id = users.id WHERE users.pending_time!=0 AND status =0 AND DATE(`created_on`) = CURDATE() order by order_list.id DESC";
// die;
 $cur_date=date('Y-m-d');
 $cur_utc=strtotime(date('Y-m-d h:i:s'));  
  $query="SELECT order_list.invoice_no,order_list.order_alert_done,order_list.newuser,order_list.id as order_id,order_list.status,order_list.merchant_id, order_list.created_on,users.id,users.name,users.handphone_number,users.pending_time,users.whatapp_group_name  FROM order_list inner join users on order_list.merchant_id = users.id WHERE order_list.merchant_id not in('5401','5326') and users.pending_time!=0 AND status =0 AND DATE(`created_on`) ='$cur_date' and order_alert_done='n'  order by order_list.created_on  DESC ";
// die;  
$total_rows = mysqli_query($conn,$query);
while ($row=mysqli_fetch_assoc($total_rows)){
	// print_R($row);
	// die;
    $m_id = $row['merchant_id'];
    $status = $row['status'];
    $date = $row['created_on']; 
    $client = $row['name'];
    
    $createdate = strtotime($date);
    $diffrence = time() - $createdate;
     echo $min = $diffrence/60;
     echo "</br>";
    $pending_time = $row['pending_time'];

     $pending_time1 = $pending_time+2;  
	
    if($min > $pending_time && $min < $pending_time1){
    // if($pending_time){
		$order_id=$row['order_id'];
		$invoice_no=$row['invoice_no'];
		$length = 4;    
		 $query2="UPDATE `order_list` SET `order_alert_done` = 'y' WHERE `order_list`.`id` ='$order_id'";
		// die;
		$update=mysqli_query($conn,$query2);
			$rand= substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1,$length);
			$url="https://www.koofamilies.com/orderview.php?did=".$m_id."&vs=".$rand."&oid=".$order_id;    
			 // echo  $_POST['message'] = $client.', Koofamilies alert! Your order has exceeded timeframe, please investigate. Kitchen Jam? If Internet problem, please contact 012-3115670 for assistance';
			if($row['newuser']=="y")
			 $message= $_POST['message'] = $url." ".$client.",1st time user,Koofamilies alert ! Your order (".$invoice_no.") has exceeded timeframe,please ACCEPT the order";
			else
			$message=$_POST['message'] = $url." ".$client.",Koofamilies alert! Your order (".$invoice_no.") has exceeded timeframe,please ACCEPT the order,";   	       
			   
		$order_id=$row['order_id'];
	// die;
    	$sms_to = '+60123115670,'.$row['handphone_number'];
    	// $sms_to = '+60123115670';
    	// $sms_to = '+919001025477';
    	$sms_msg = $_POST['message'];
		
        $smsend=gw_send_sms("APIHKXVL33N5E", "APIHKXVL33N5EHKXVL", "9787136232", $sms_to,$sms_msg);   
		if($row['whatapp_group_name'])
		{
			$whatapp_group_name=$row['whatapp_group_name'];
			whatappgroupmsg($whatapp_group_name,$sms_msg);
		} 
		echo $smsend;
		echo "</br>";          
	} 
   else
   {
	   echo "time expire";
	   echo "</br>";
   }	   


}
   $queryv="SELECT order_list.order_alert_done,order_list.id as order_id,order_list.status,order_list.merchant_id, order_list.created_on,users.id as user_id,users.name,users.handphone_number,users.voice_pending_time FROM order_voice_list as order_list inner join users on order_list.merchant_id = users.id WHERE order_list.merchant_id!='5401' and users.voice_pending_time!=0 AND status =0 AND DATE(`created_on`) ='$cur_date' and order_alert_done='n' order by order_list.id DESC";
// die;
$total_rows = mysqli_query($conn,$queryv);
while ($row=mysqli_fetch_assoc($total_rows)){
	// print_R($row);
	// die;
    $m_id = $row['merchant_id'];
    $status = $row['status'];
    $date = $row['created_on']; 
    $client = $row['name'];
    $createdate = strtotime($date);
    $diffrence = time() - $createdate;
     echo $min = $diffrence/60;
     echo "</br>";
    $pending_time = $row['voice_pending_time'];

 $pending_time1 = $pending_time+2;  
	
    if($min > $pending_time && $min < $pending_time1){
		if($row['order_alert_done']=="n")
		{
			// echo "dd";
			 $order_id=$row['order_id'];
			 $invoice_no=$row['id'];
			 
			 $query2="UPDATE `order_voice_list` SET `order_alert_done` = 'y' WHERE `order_voice_list`.`id` ='$order_id'";   
			 $update=mysqli_query($conn,$query2);
		// if($pending_time){
			$length = 4;    
			$rand= substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1,$length);
			 $url="https://www.koofamilies.com/voiceorderview.php?did=".$m_id."&vs=".$rand."&oid=".$order_id;    
			 // echo  $_POST['message'] = $client.', Koofamilies alert! Your order has exceeded timeframe, please investigate. Kitchen Jam? If Internet problem, please contact 012-3115670 for assistance';
			if($row['newuser']=="y")
			  $_POST['message'] = $url.",".$client.",You have voice order";
			else
			$_POST['message'] = $url.",".$client.",You have voice order";   	       
			   
		// die;  0123945670
			// $sms_to = '+60123945670,'.$row['handphone_number'];
			$sms_to = '+60123115670,'.$row['handphone_number'];  
			// $sms_to = '+60123115670';
			// $sms_to = '+919001025477';
			$sms_msg = $_POST['message'];
			
			$smsend=gw_send_sms("APIHKXVL33N5E", "APIHKXVL33N5EHKXVL", "9787136232", $sms_to,$sms_msg);   
			
			// die;     
			
			echo $smsend;
			echo "</br>";  
		}		
	} 
   else
   {
	   echo "Voice time expire";
	   
	   echo "</br>";
   }	     


}     

			
?>
