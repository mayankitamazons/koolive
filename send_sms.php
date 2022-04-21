<?php

include("config.php");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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

//$sms_to = "60123115670";
//$sms_msg = "test cron msg".date('Y-m-d H:i:s');
//$smsend = gw_send_sms("APIHKXVL33N5E", "APIHKXVL33N5EHKXVL", "9787136232", $sms_to, $sms_msg); 
	
	
	
// date_default_timezone_set("Asia/Kuala_Lumpur");
//echo $sql= "SELECT order_list.status,order_list.merchant_id, order_list.created_on,users.id,users.handphone_number,users.pending_time FROM order_list inner join users on order_list.merchant_id = users.id WHERE users.pending_time!=0 AND status =0 AND DATE(`created_on`) = CURDATE() order by order_list.id DESC";
// echo "SELECT order_list.status,order_list.merchant_id, order_list.created_on,users.id,users.name,users.handphone_number,users.pending_time FROM order_list inner join users on order_list.merchant_id = users.id WHERE users.pending_time!=0 AND status =0 AND DATE(`created_on`) = CURDATE() order by order_list.id DESC";
// die;


 $cur_date=date('Y-m-d');
 $cur_utc=strtotime(date('Y-m-d h:i:s'));  
    $query="SELECT order_list.invoice_no,order_list.order_alert_done,order_list.newuser,order_list.id as order_id,order_list.status,order_list.merchant_id, 
	order_list.created_on,users.id,users.name,users.handphone_number,users.write_up_link_share,users.pending_time,users.whatapp_group_name,users.order_sms  FROM order_list inner join users on 
	order_list.merchant_id = users.id WHERE order_list.merchant_id not in('5401') and users.pending_time!=0 and users.write_up_link_share='1'
	AND status =0 AND DATE(`created_on`) ='$cur_date' and order_alert_done='n'  order by order_list.created_on  DESC ";
// die;  
echo  $query;
$admindata = mysqli_query($conn, "SELECT * FROM admins");
$smsdata=mysqli_fetch_array($admindata);
$no_list='';
if($smsdata['order_mobile_no'])
	$no_list.=$smsdata['order_mobile_no'];
if($smsdata['order_mobile_no_2'])
	$no_list.=",".$smsdata['order_mobile_no_2'];
if($smsdata['order_mobile_no_3'])
	$no_list.=",".$smsdata['order_mobile_no_3'];
if($smsdata['order_mobile_no_4'])
	$no_list.=",".$smsdata['order_mobile_no_4'];

$total_rows = mysqli_query($conn,$query);
while ($row=mysqli_fetch_assoc($total_rows)){
	 print_R($row);
	  
    $m_id = $row['merchant_id'];
    $write_up_link_share = $row['write_up_link_share'];
    $status = $row['status'];
    $date = $row['created_on']; 
    $client = $row['name'];
    	$order_id=$row['order_id'];
    $createdate = strtotime($date);
    $diffrence = time() - $createdate;
     echo $min = $diffrence/60;
     echo "</br>";
    $pending_time = $row['pending_time'];
//echo "</br>";
    $pending_time1 = $pending_time+6;  
	// die;
	
	 
	 
	
    if($min > $pending_time && $min < $pending_time1){
    // if($pending_time){
		echo "</br>----------";
		$invoice_no=$row['invoice_no'];
		$length = 4;    
		echo  $query2="UPDATE `order_list` SET `order_alert_done` = 'y' WHERE `order_list`.`id` ='$order_id'";
		// die;
		$update=mysqli_query($conn,$query2);
			$rand= substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1,$length);
			$url="https://www.koofamilies.com/orderview.php?did=".$m_id."&vs=".$rand."&oid=".$order_id;    
			 // echo  $_POST['message'] = $client.', Koofamilies alert! Your order has exceeded timeframe, please investigate. Kitchen Jam? If Internet problem, please contact 012-3115670 for assistance';
			if($row['newuser']=="y")
			 // $message= $_POST['message'] = $url." ".$client.",1st time user,Koofamilies alert ! Your order (".$invoice_no.") has exceeded timeframe,please ACCEPT the order";
			 $message= $_POST['message'] = $url." ".$client.",1st time user, KooFamilies alert. Please Accept New order (".$invoice_no."). After finishing, please Done the order";
			else
			// $message=$_POST['message'] = $url." ".$client.",Koofamilies alert! Your order (".$invoice_no.") has exceeded timeframe,please ACCEPT the order,";   	       
			$message= $_POST['message'] = $url." ".$client.",KooFamilies alert. Please Accept New order (".$invoice_no."). After finishing, please Done the order";
			   
		$order_id=$row['order_id'];
	// die;
	if($row['order_sms'])
	{
    	// $sms_to = '+60127088661'.',+60123115670,'.$row['handphone_number'];
    	$sms_to =$no_list.",".$row['handphone_number'];
    	// $sms_to = '+60123115670';
    	// $sms_to = '+919001025477';
    	$sms_msg = $_POST['message'];
		
        $smsend=gw_send_sms("APIHKXVL33N5E", "APIHKXVL33N5EHKXVL", "9787136232", $sms_to,$sms_msg); 
	}		
		if($row['whatapp_group_name'])
		{
			$whatapp_group_name=$row['whatapp_group_name'];
			// whatappgroupmsg("Urgent Group",$sms_msg);
			// whatappgroupmsg($whatapp_group_name,$sms_msg);
		} 
		echo $smsend;
		echo "</br>";          
	} 
   else
   {
	    // $query2="UPDATE `order_list` SET `order_alert_done` = 'expire' WHERE `order_list`.`id` ='$order_id'";
		// die;
		$update=mysqli_query($conn,$query2);
	   echo "time expire for order id: ".$order_id;
	   echo "</br>";
   }	   


}
    $queryv="SELECT order_list.order_alert_done,order_list.id as order_id,order_list.status,order_list.merchant_id, order_list.created_on,users.id as user_id,users.name,users.handphone_number,users.voice_pending_time FROM order_voice_list as order_list inner join users on order_list.merchant_id = users.id WHERE order_list.merchant_id!='5401' and users.voice_pending_time!=0 AND status =0 AND DATE(`created_on`) ='$cur_date' and order_alert_done='n' order by order_list.id DESC";

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
	 $order_id=$row['order_id'];
    if($min > $pending_time && $min < $pending_time1){
		if($row['order_alert_done']=="n")
		{
			// echo "dd";
			
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
			// $sms_to = '+60123115670,'.$row['handphone_number'];  
			$sms_to = '+60123115670,'.$row['handphone_number'].","."+60127500913";
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
	   // $query2="UPDATE `order_voice_list` SET `order_alert_done` = 'expire' WHERE `order_voice_list`.`id` ='$order_id'";  
        // mysqli_query($conn,$query2);	   
	   echo "Voice time expire";
	   
	   echo "</br>";
   }	     


}     

$query="SELECT order_list.rider_info,order_list.invoice_no,order_list.rider_alert,order_list.newuser,order_list.id as order_id,order_list.status,order_list.merchant_id, order_list.created_on,users.id,users.name,users.handphone_number,users.whatapp_group_name  FROM order_list inner join users on order_list.merchant_id = users.id WHERE order_list.merchant_id not in('5401')  AND DATE(`created_on`) ='$cur_date' and rider_alert='n' and rider_info=''  order by order_list.created_on  DESC ";
  
$total_rows = mysqli_query($conn,$query);
while ($row=mysqli_fetch_assoc($total_rows)){
	$m_id = $row['merchant_id'];
	$date = $row['created_on'];
    $client = $row['name'];	
	$createdate = strtotime($date);
 $diffrence = time() - $createdate;
     echo "<br/>Rider Time ".$min = $diffrence/60;
	  $order_id=$row['order_id'];
	 if($min >20){
		if($row['rider_alert']=="n" && $row['rider_info']=='0')
		{
			
			
			 $invoice_no=$row['invoice_no'];
			 
			 $query2="UPDATE `order_list` SET `rider_alert` = 'y' WHERE `order_list`.`id` ='$order_id'";   
			 $update=mysqli_query($conn,$query2);
			 $rand= substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1,4);
			$url="https://www.koofamilies.com/orderview.php?did=".$m_id."&vs=".$rand."&oid=".$order_id;    
		  	       
			$message= $_POST['message'] = "Rider has not been assigned for Invoice no: (".$invoice_no."). ".$url." ".$client.",KooFamilies alert.";
			
			
	
			$sms_to = '+60123115670'.',+60127500913,'.$row['handphone_number'];
				$sms_to = '+60127500913'.',+60123115670,'.$row['handphone_number'];
			
			$sms_msg = $_POST['message'];   
			$smsend=gw_send_sms("APIHKXVL33N5E", "APIHKXVL33N5EHKXVL", "9787136232", $sms_to,$sms_msg);  
			
				$whatapp_group_name="Koo Support Team";
				$whatapp_group_name="Urgent Group";
				whatappgroupmsg($whatapp_group_name,$sms_msg);
			
		}
	 }
	 else
	 {
		  // $query2="UPDATE `order_list` SET `rider_alert` = 'y' WHERE `order_list`.`id` ='$order_id'"; 
		  // mysqli_query($conn,$query2);
	 }   
}



// SEND SMS BEFORE 2 HRS TO COMPLETE OFFER IN 48 HOURS(-1 discount)
/* Test Code send SMS*/

$cDate = date('Y-m-d H:i:s');
//$chkt_query = mysqli_query($conn, "SELECT od.sent_message_customer ,u.id as userid, m.city,c.offer_two, od.id, u.mobile_number,od.created_on, time_to_sec(timediff('".$cDate."', od.created_on)) / 3600 as totalhrs FROM `order_list` as od INNER JOIN users as u ON u.id = od.user_id INNER JOIN users as m on m.id = od.merchant_id INNER JOIN city as c on c.CityName = m.city where od.sent_message_customer = 0 and od.offer2_applied = 1 and created_on >'2021-06-05 12:00:00' ORDER BY `od`.`id` DESC");

$chkt_query = mysqli_query($conn,"SELECT od.sent_message_customer ,u.id as userid, m.city,c.offer_two, od.id, u.mobile_number,od.created_on, time_to_sec(timediff('".$cDate."', od.created_on)) / 3600 as totalhrs FROM `order_list` as od INNER JOIN users as u ON u.id = od.user_id INNER JOIN users as m on m.id = od.merchant_id INNER JOIN city as c on c.CityName = m.city where od.sent_message_customer = 0 and c.offer_two = 1  and  created_on >'2021-06-05 12:00:00' and time_to_sec(timediff('".$cDate."', od.created_on)) / 3600  >=45
and time_to_sec(timediff('".$cDate."', od.created_on)) / 3600  < 48 ORDER BY `od`.`id` DESC");


while($u_chkData = mysqli_fetch_assoc($chkt_query)){
	$us_mobileNumber = "+".$u_chkData['mobile_number'];
	$od_totalhrs =round($u_chkData['totalhrs']);
	$user_id =  $u_chkData['userid'];
	//echo "od_totalhrs".$od_totalhrs;
	//echo '<br/>';
	if($u_chkData['offer_two'] == 1){
		if($od_totalhrs >= 45 && $od_totalhrs < 48){
			$chk_pending_hrs = 48 - $od_totalhrs;
			//echo "chk_pending_hrs".$chk_pending_hrs;echo '<br/>';
			if($chk_pending_hrs <= 1){
				//echo 'sent_sms';
				$update_od_q = mysqli_query($conn,"UPDATE `order_list` SET `sent_message_customer` = '1',sent_message_time = '".date('Y-m-d H:i:s')."' WHERE `order_list`.`id` =".$u_chkData['id']);
				$sms_content = 'Koo Family food delivery voucher, Hurry up ! Enjoy RM 1 discount on your order now ( 1 hours are left ). https://www.koofamilies.com/index.php?cid='.$user_id;
								
				
				$smsend=gw_send_sms("APIHKXVL33N5E", "APIHKXVL33N5EHKXVL", "9787136232", $us_mobileNumber,$sms_content);  
			}	
		}
	}
}

/*test code sms*/


		
?>
