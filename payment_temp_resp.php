<?php
include("config.php");
session_start(); 
include_once ('IPay88.class.php');


function ceiling($number, $significance = 1)
{
	return ( is_numeric($number) && is_numeric($significance) ) ? (ceil(round($number/$significance))*$significance) : false;
}
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

 
$ipay88 = new IPay88('M31571');

$response = $ipay88->getResponse();


//echo $_SESSION['tmp_login'];exit;
$datetime = date('Y-m-d H:i:s');

$temp_order_id = str_replace("temp_orderid","",$response['data']['Remark']);

$fetch_data = "SELECT * FROM `tbl_temp_saveorder` where t_id = ".$temp_order_id;
$user_query = mysqli_query($conn,$fetch_data);
$srow = mysqli_fetch_assoc($user_query);	


//save response in tbl_ipay88_payment
$query_temp="INSERT INTO `tbl_ipay88_payment` (`p_order_id`, `p_user_id`, `p_trans_id`,`p_temp_order_id`, `p_status`, `p_amount`, `p_response`, `p_message`, `p_createddate`) VALUES ('', '".$srow['t_user_id']."', '".$response['data']['TransId']."', '".$temp_order_id."', '".$response['status']."', '".$response['data']['Amount']."', '".serialize($response['data'])."', '".$response['message']."', '".$datetime."')";
mysqli_query($conn,$query_temp);
$last_id = mysqli_insert_id($conn);
	
$update_query = "UPDATE `tbl_temp_saveorder` SET `t_transid` = '".$response['data']['TransId']."', `t_payment_status` = '".$response['status']."' WHERE `tbl_temp_saveorder`.`t_id` = ".$temp_order_id;	
mysqli_query($conn,$update_query);
	
$order_data = array();
$order_data = unserialize ($srow['t_order_response']);

$ipay_payment_status = $response['status'];
$ipay_message = $response['message'];
$ipay_p_id = $last_id;
$wallet = 'fpx';// payment type
$pay_transid = $response['data']['TransId'];

//end data

//order_cash.php file content

// error_reporting(-1);
if($response['status'] != ''){
	$order_data = unserialize ($srow['t_order_response']);;
}
if(isset($order_data))
{
	
	extract($order_data);
	$merchant_id = $srow['t_m_id'];	 
	$tmp_login = $srow['t_user_id'];
	
	if($tmp_login != 0){
		$_SESSION['login'] = $tmp_login;
	}
	
	//exit;
	$merchant_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SQL_NO_CACHE * FROM users WHERE id='".$merchant_id."'"));
	 
	$guest_permission=$merchant_data['guest_permission'];
	$referral_by=$merchant_data['referral_id'];
	$price_hike=$merchant_data['price_hike'];
	$write_up_share=$merchant_data['write_up_share'];
	$auto_accept_order=$merchant_data['auto_accept_order'];
	$merchant_mobile_no=$merchant_data['mobile_number'];
	 $online_pay=0;
	 if($merchant_data['credit_check'] || $merchant_data['wallet_check'] || $merchant_data['boost_check'] || $merchant_data['grab_check']
	 || $merchant_data['wechat_check'] || $merchant_data['touch_check'] || $merchant_data['fpx_check'])
	 {
		  $online_pay=1;
		  $payment_alert="y";
	 }		 
	$show_alert="y";
	$otp_verified="n";
	$password_created="n";
	$rebate_applicable="n";
	$rebate_amount=0;
	$total_amount=0;
	$agent_code = $order_data['agent_code'];
	$vendor_comission=$merchant_data['vendor_comission'];
	if($mobile_number)
	{
		 $f_letter=$mobile_number[0];
		 if($f_letter==0)
			{
				$mobile_number = substr($mobile_number, 1);
			}
		if($guest_permission==1)
		{
			
			
			// check mobile start with 1 and  greater than 9 
			
			$f_letter=$mobile_number[0];
			if (($f_letter=="1") ) {
				 // Yes
				 $show_alert="y";
				 $password_created="n";
				 $otp_verified="n";
			}
			else
			{
				$show_alert="n";
				 $password_created="y";
				  $otp_verified="y";
			}
		}
		else
		{
			$show_alert="y";
			$otp_verified="n";
		}  
		// echo $show_alert;
		// die;
	$mobile_check="60".$mobile_number;
	// echo $mobile_check;
	// die;
	if($_SESSION['login']=='')
	  {
	     $loginmatch = mysqli_query($conn, "SELECT * FROM users WHERE  mobile_number ='".$mobile_check."'");	
	  }
	  else
	  {
		  $loginmatch = mysqli_query($conn, "SELECT * FROM users WHERE  id ='".$_SESSION['login']."'");	
	  }
	  $logincount=mysqli_num_rows($loginmatch);
	}
	else
	{
		$show_alert="n";
		$logincount=0;
	}
	$mobile_block = array("60123456789", "601234567890", "Glenn", "Cleveland",'60179605935');

	if(in_array($mobile_check,$mobile_block))
	{
		echo "Your number has been barred from placing order, please contact 012-3115670 for clarification.";
		die;
	}
	
	
	//echo "logincount==".$logincount;
	if($logincount>0)
	{
		$userdata=mysqli_fetch_assoc($loginmatch);
		$user_id=$userdata['id'];
		//echo "1==".$user_id;exit;
		$user_mobile=$userdata['mobile_number'];
		if($mobile_check==$user_mobile)
		{
		}
		else
		{
			$user_mobile=$mobile_check;
		}
		
		$myr_bal=$userdata['balance_myr'];
	    $usb_bal=$userdata['balance_usd'];
	    $inr_bal=$userdata['balance_inr'];
		$user_name=$userdata['name'];
		$otp_verified=$userdata['otp_verified'];
		if($otp_verified=="y")
		$newuser="n";
		else
		$newuser="y";
	
		if($newuser=="y")
		{
		  // get all the past order made by that user id
		  
		  $q=mysqli_query($conn,"select count(id) as total_order from order_list where user_id='$user_id'");
		   $totalcount=mysqli_fetch_assoc($q);
			$totalcount=$totalcount['total_order'];
		}
		// get all order place on today 
			$todaydate=date('Y-m-d');   
			 if($m_id=='1567')   
			 {
				$q=mysqli_query($conn,"select count(id) as total_order from order_list where date(created_on)='$todaydate' and user_id='$user_id' and merchant_id='$m_id'");
				$totalcount=mysqli_fetch_assoc($q);
				$totalcount=$totalcount['total_order'];
				if($totalcount>2)
				{   
					$_SESSION['today_limit']='expire';
					header("Location: ".$site_url."/view_merchant.php"); 
					die;
				}  
			}
		
		// $_SESSION['tmp_login']=$user_id;     
		$_SESSION['user_id']=$user_id;
	}
	else
	{
		if($mobile_number)
		{
			// create new user account with respect to merchant 
			$code = uniqid();
			$ref = $mobile_number." ".$code;
			$user_role=1;
			$name = '';
			$referral_by = str_replace("'","",$referral_by);
			$reocrd=mysqli_query($conn, "INSERT INTO users SET isLocked='0',referral_id='$ref',referred_by='".$referral_by."',name='".$name."',user_roles='$user_role',mobile_number='$mobile_check',guest_user='y',login_status='1',password_created='$password_created',otp_verified='$otp_verified'");
			$user_id=mysqli_insert_id($conn);
			
			if($tmp_user_id == 0){
				$_SESSION['tmp_login'] = $user_id;
			}
			
			
			
			$user_mobile="60".$mobile_number;   
			$newuser="y";
			$date = date('Y-m-d H:i:s');
			 $loginmatch = mysqli_query($conn, "SELECT * FROM users WHERE  mobile_number ='".$mobile_check."'");	
			$userdata=mysqli_fetch_assoc($loginmatch);
			if($direct_sub=="y")
			{
				// assign defalut membership to user 
				$d_query=mysqli_query($conn,"select id from membership_plan where user_id='$m_id' and defalut_plan='y' and assign_user='y'");
				$dplan=mysqli_fetch_assoc($d_query);
				$dplan_id=$dplan['id'];
				$token_membership=gen();
				$is_upgrade="y";
				$user_member_plan = "INSERT INTO `user_membership_plan`(`user_id`,`user_mobile`,`merchant_id`, `plan_id`, `paid_via`, `paid_date`, `plan_active`, `created`, `is_upgrade`,`token_membership`) 
							VALUES ('$user_id','$user_mobile','$m_id','".$dplan['id']."','Cash','$date','y','$date','$is_upgrade','$token_membership')";
				$assign_d_plan=mysqli_query($conn,$user_member_plan);
			}
		}
		else
		{
			
			if($guest_permission == "1"){
				$user_id=3366;
				$user_mobile='';
				$$neuser="n";
				$guest_user_order="y";
			}
			else
			{
				// not permission to place order as guest 
				echo "Not permssion to place order as a guest";
				die;
			}
			// create guest user id 
			
		}
         
		
	}
	/*
	  if($_SESSION['login']=='')
	  {
		$_SESSION['tmp_login']=$user_id;
	  }	*/	
		if($tmp_user_id == 0){
			$_SESSION['tmp_login'] = $user_id;
		}
		#echo $_SESSION['tmp_login'];exit;
			
	// insert data into order table 
		$stl_key = isset($order_data['stl_key']) ? $order_data['stl_key'] : '';
		$date = date('Y-m-d H:i:s');
		$location =$order_data['location'];
		$table_type =$order_data['table_type'];
		 $section_type =$order_data['section_type'];
		$p_code = implode(',', $order_data['p_code']);
		$pro_id = implode(',', $order_data['p_id']);
		$qty_list = implode(',', $order_data['qty']);
		$prices = $order_data['p_price'];
		$p_extra = explode('|', $order_data['price_extra']);
		$p_price = [];
		foreach ($prices as $i => $item) {
			if(sizeof(explode(",",$p_extra[$i])) > 1){
				$totalExtra = explode(",",$p_extra[$i]);
				$p_extra_ind = 0;
				foreach ($totalExtra as $xtr) {
					$p_extra_ind += $xtr;
				}
			}else{
				$p_extra_ind = $p_extra[$i];
			}
			array_push($p_price, $p_extra_ind + $item);
		}
		$p_price = implode(",", $p_price);
		$option = $order_data['options'];
		$product_name =isset($order_data['product_name']) ? $order_data['product_name'] : '';
		$product_code =isset($order_data['product_code']) ? $order_data['product_code'] : '';
		// fields for rebate process 
		$w_type =$order_data['selected_wallet'];
		$arrival_time =$order_data['arrival_time'];
		$no_person =$order_data['no_person'];
		$remark_extra =$order_data['remark_extra'];
		 $order_extra_charge =$order_data['order_extra_charge'];
		
		if($pickup_type=="divein")
		{
			$order_extra_charge=0;
			$location='';
			$show_pick_up="Dine in";
		} 
		else
		{
			$show_pick_up="Delivery";
			/*if($order_extra_charge=='' || $order_extra_charge==0)
			{
				$order_extra_charge=2.99;
			}*/
		}
		if($merchant_id==7634)
		{
			$order_extra_charge=0;  
		}
		if($w_type=='')
		{
			$w_type="cash";
			$prepaid='n';
		}
		else
		{
			$prepaid='y';
		}
		
		$total_rebate_amount =$order_data['total_rebate_amount'];
		if($order_data['rem_amount']>0)
		$rem_amount =$order_data['rem_amount'];
		else
		$rem_amount=$order_data['total_cart_amount'];	
		$total_cart_amount =$order_data['total_cart_amount'];
		$coupon_code = $order_data['coupon_code'];
		$wallet_paid_amount =$order_data['payable_amount'];
		$rebate_amount = implode(',',$order_data['rebate_amount']);
		if($varient_type)
		{ 
			$vcount=0;
			// print_R($varient_type);
			// die;
			foreach($varient_type as $v)
			{
				// print_R($v);
				if($vcount==0)
				{
					$v_str=$v;
				}
				else
				{
				  $v_str=$v_str."|".$v;
				}
				$vcount++;
			}
			
		}
		// $order_process=true;
		
		if($wallet_paid_amount>0)
		{
		   if($w_type=="myr_bal")
		   {
			  $myr_bal=$userdata['balance_myr']-$wallet_paid_amount;
		   }
			else if($w_type=="usd_bal")
			{
				$usb_bal=$userdata['balance_usd']-$wallet_paid_amount;
			}
			else if($w_type=="inr_bal")
			{
				$inr_bal=$userdata['balance_inr']-$wallet_paid_amount;
			} else if($merchant_data['special_coin_name'] && $w_type!='koo cashback')
			{
				$merchantwallet = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE  id='$m_id'"));
				$walletcheck = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM special_coin_wallet WHERE  user_id='$user_id' and merchant_id ='".$m_id."'"));
				if($walletcheck)
				{
					$created_on=strtotime(date('d-m-Y H:i:s'));
					$balance_special=$walletcheck['coin_balance'];
					$mer_bal=$merchantwallet['balance_usd'];
					$new_mer_bal=$mer_bal+$wallet_paid_amount;
					$new_special=$balance_special-$wallet_paid_amount;
					$q2="update special_coin_wallet set coin_balance='$new_special' where user_id='$user_id' and merchant_id='$m_id'";
					$qmerchant="update users set balance_usd='$new_mer_bal' where id='$m_id'";
					$update2=mysqli_query($conn,$q2);
					$update3=mysqli_query($conn,$qmerchant);
					// $sql = 'INSERT INTO tranfer (sender_id, amount, receiver_id, wallet, created_on,coin_merchant_id) VALUES ("'.$user_id.'", "'.$wallet_paid_amount.'", "'.$m_id.'", "'.$w_type.'", "'.$created_on.'","'.$m_id.'")';
					// $transfer = mysqli_query($conn, $sql);  

					$sql_for_transaction = 'INSERT INTO transaction (sender_id, amount, receiver_id, wallet, created_on) VALUES ("'.$user_id.'", "'.$wallet_paid_amount.'", "'.$m_id.'", "'.$w_type.'", "'.$created_on.'")';
					$transaction = mysqli_query($conn, $sql_for_transaction);

					$noti_string = 'You Successfully Received '.$wallet_paid_amount.' '.$w_type.' from '.$merchant_data['name'];
					if($userdata['name'])
					$noti_string2 = 'You Successfully Send '.$wallet_paid_amount.' '.$w_type.' to '.$userdata['name'];
					else
						$noti_string2 = 'You Successfully Send '.$wallet_paid_amount.' '.$wallet_type.' to '.$userdata['mobile_number'];      
					$noti = 'INSERT into notifications (user_id, notification , type, created_on, readStatus) VALUES ("'.$m_id.'", "'.$noti_string.'", "receive", "'.$created_on.'", "0")';
					$noti2 = 'INSERT into notifications (user_id, notification , type, created_on, readStatus) VALUES ("'.$user_id.'", "'.$noti_string2.'", "send", "'.$created_on.'", "0")';
					$notification = mysqli_query($conn, $noti);
					$notification = mysqli_query($conn, $noti2);   
				}
				
			} else  if($w_type=='koo cashback')
			{
				
				
				$walletcheck = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM special_coin_wallet WHERE  user_id='$user_id' and merchant_id ='6419'"));
				
				 $koo_balance=$walletcheck['coin_balance'];   
				
				if($koo_balance>0)  
				{  
					$merchantkoocash = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM special_coin_wallet WHERE  user_id='$m_id' and merchant_id ='6419'"));
					$new_koo_bal=$koo_balance-$p_price;
					 $m_q="SELECT * FROM unrecoginize_coin WHERE status=1 and merchant_id='6419' and user_id='".$m_id."'";
					
					$merchantaccept = mysqli_fetch_assoc(mysqli_query($conn,$m_q));
					if($merchantaccept)
					{
						 $totalcoin="SELECT sum(amount) as totalamount FROM tranfer WHERE MONTH(created_date) = MONTH(CURRENT_DATE()) AND YEAR(created_date) = YEAR(CURRENT_DATE()) 
						and receiver_id='$m_id' and coin_merchant_id='6419'";
						
						$acceptedcoin = mysqli_fetch_assoc(mysqli_query($conn,$totalcoin));
						$totalamount=$acceptedcoin['totalamount']+$wallet_paid_amount;
						$coin_max_limit=$merchantaccept['coin_max_limit'];
						$coin_limit=$merchantaccept['coin_limit'];  
						 $pending_limit=$coin_max_limit-$acceptedcoin['totalamount']; 
						
						if($wallet_paid_amount<=$koo_balance)
						{
							if($merchantkoocash)
							{
								$new_bal=$merchantkoocash['coin_balance']+$wallet_paid_amount;
								 $q2="update special_coin_wallet set coin_balance='$new_bal' where user_id='$m_id' and merchant_id='6419'";
							}
							else
							{
								echo $q2="INSERT INTO special_coin_wallet (user_id,merchant_id,coin_balance,added_balance,user_mobile,coin_active,ref_coin) 
								VALUES ('$m_id', '6419','$wallet_paid_amount','$wallet_paid_amount','$merchant_mobile_no','y','n')";
							}
							
							$user_koo_bal=$koo_balance-$wallet_paid_amount;
							$q3="update special_coin_wallet set coin_balance='$user_koo_bal' where user_id='$user_id' and merchant_id='6419'";
							$update2=mysqli_query($conn,$q2);
							$update3=mysqli_query($conn,$q3);
							if($update2 && $update3)
							{
								// $sql = 'INSERT INTO tranfer (sender_id, amount, receiver_id, wallet, created_on,coin_merchant_id) 
								// VALUES ("'.$user_id.'", "'.$wallet_paid_amount.'", "'.$m_id.'", "'.$w_type.'", "'.$created_on.'","'.$m_id.'")';  
								// $transfer = mysqli_query($conn, $sql);
								$sql_for_transaction = 'INSERT INTO transaction (sender_id, amount, receiver_id, wallet, created_on) 
								VALUES ("'.$user_id.'", "'.$wallet_paid_amount.'", "'.$m_id.'", "'.$w_type.'", "'.$created_on.'")';
								$transaction = mysqli_query($conn, $sql_for_transaction);
								$noti_string = 'You Successfully Received '.$wallet_paid_amount.' '.$w_type.' from '.$userdata['name'];
								if($userdata['name'])
								$noti_string2 = 'You Successfully Send '.$wallet_paid_amount.' '.$w_type.' to '.$merchant_data['name'];
								else
									$noti_string2 = 'You Successfully Send '.$wallet_paid_amount.' '.$wallet_type.' to '.$merchant_data['mobile_number'];      
								$noti = 'INSERT into notifications (user_id, notification , type, created_on, readStatus) VALUES ("'.$m_id.'", "'.$noti_string.'", "receive", "'.$created_on.'", "0")';
								$noti2 = 'INSERT into notifications (user_id, notification , type, created_on, readStatus) VALUES ("'.$user_id.'", "'.$noti_string2.'", "send", "'.$created_on.'", "0")';
								$notification = mysqli_query($conn, $noti);
								$notification = mysqli_query($conn, $noti2);   
							}  
							
						}   
						else
						{
							echo "Dont have sufficient balance to pay";
							die;
						}
						
					}
					else
					{
						echo "This Merchant is not accepting Koocash Back";
						die;
					} 
					
					
				}
				else
				{
				}
				
			}
            $wallet=$w_type;
						if($wallet=="myr_bal")
						$wal_label="MYR WALLET";
						else if($wallet=="inr_bal")
						$wal_label="Koo CashBack";
						 else if($wallet=="usd_bal")
						$wal_label="CF WALLET";
						 else if($wallet=="cash")
						$wal_label="CASH";
						else $wal_label=$w_type;		
		}
		  $doublein="select id from order_list where created_timestamp > date_sub(now(), interval 5 minute) and user_name='$user_name' and user_mobile='$user_mobile' and wallet='cash' and varient_type='$v_str' and product_id='$pro_id' and
		user_id='$user_id' and merchant_id='$m_id' and  quantity='$qty_list' and  amount='$p_price' and product_code='$p_code' and  remark='$option' and table_type='".$table_type."' and section_type='$section_type'";
	 // die
		  $double_invoice_count = mysqli_num_rows(mysqli_query($conn, $doublein));
		
		// $double_invoice_count=0;
		if($double_invoice_count==0)
		{
			$sql = "SELECT MAX(invoice_no) invoice_no
			FROM order_list
			WHERE merchant_id = '$m_id'";
			$invoice_seq = mysqli_fetch_assoc(mysqli_query($conn, $sql))['invoice_no'];
			// $inv=explode('_',$invoice_no);
			// $invoice_no=$inv[0];
			if($invoice_seq == NULL) $invoice_seq = 1;
			else $invoice_seq += 1;
			 $invoice_no=$invoice_seq;
			 $invoice_seq=$invoice_seq;
			
			$vsql = "SELECT MAX(id) v_id FROM order_varient";
			$v_count = mysqli_fetch_assoc(mysqli_query($conn, $vsql))['v_id'];
			if($v_count == NULL) $v_count = 1;
			else $v_count += 1;
			$v_no=$v_count;
			if($section_type && $table_type)
			$section_saved='y';
		else
			$section_saved='n';
			$date = date('Y-m-d H:i:s');
			
			
			// coupon apply logic
			 
			// end coupon logic
			
			     $defalut_plan="select count(plan.id) as total_count,u.created from membership_plan as plan inner join user_membership_plan as u on u.plan_id=plan.id where plan.user_id='$m_id' and plan.default_plan='y'
				and u.user_id='$user_id'";
				
				$defalutarray = mysqli_fetch_assoc(mysqli_query($conn,$defalut_plan));
				$defalutplan=$defalutarray['total_count'];
				if($defalutplan>0)
				{
					$created_date=$defalutarray['created'];
					$total_shop=mysqli_fetch_assoc(mysqli_query($conn,"select sum(total_cart_amount) as total_order_amount from order_list where user_id='$user_id' and merchant_id='$m_id' and created_on>='$created_date'"));
					if($total_shop['total_order_amount'])
					{
					$total_shop_amount=number_format($total_shop['total_order_amount'],2);
					$total_shop=$total_shop['total_order_amount'];
					}
					else
					{
					$total_shop_amount=0;
					$total_shop=0;
					}
					$local_coin = mysqli_fetch_assoc(mysqli_query($conn,"select sum(local_coin) as total_coin from local_coin_sync where merchant_id='$m_id' and user_mobile='$mobile_check' and order_date>='$created_date'"));
					if($local_coin['total_coin']>0)
					{
						$total_shop=$local_coin['total_coin']+$total_shop;
					} 
					   $query="SELECT user_membership_plan.*, membership_plan.user_id as memberplan_user, membership_plan.* FROM user_membership_plan INNER JOIN membership_plan ON membership_plan.id = user_membership_plan.plan_id WHERE user_membership_plan.plan_active='y' and user_membership_plan.user_id='$user_id' and user_membership_plan.merchant_id = '$m_id' and membership_plan.total_max_order_amount>='$total_shop' and  membership_plan.total_min_order_amount<='$total_shop'";
					// die;
					// check local order synch coin detail 
					 
					$user_plan = mysqli_fetch_assoc(mysqli_query($conn,$query));
					// print_R($user_plan);
					// die;
					$membership_plan_id=$user_plan['plan_id'];
					if($user_plan['plan_type'] == 'per'){
						$discount = $total_cart_amount*($user_plan['plan_benefit']/100);
						// $total_cart_amount = $total_cart_amount - $discount;
						
					}else{
						$discount = $user_plan['plan_benefit'];
						// $total_cart_amount = $total_cart_amount - $discount;
					}
					if(is_null($user_plan)){
						
						$plan_detail = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM membership_plan WHERE user_id='$m_id' and $total_shop BETWEEN total_min_order_amount AND total_max_order_amount;"));
						
						// print_r($plan_detail);
						// die;
						if(!is_null($plan_detail)){
							// disactive all old membership 
							$past_plan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM user_membership_plan WHERE user_id='$user_id' and merchant_id='$m_id'"));
							if($past_plan)
							{
								$is_upgrade="y";
							}
							else
							{
								$is_upgrade="n";
							}
							$token_membership=gen();
							mysqli_query($conn,"update user_membership_plan set plan_active='n' where user_id='$user_id' and merchant_id='$m_id'");
							$user_member_plan = "INSERT INTO `user_membership_plan`(`user_id`,`user_mobile`,`merchant_id`, `plan_id`, `paid_via`, `paid_date`, `plan_active`, `created`, `is_upgrade`,`token_membership`) 
							VALUES ('$user_id','$mobile_check','$m_id','".$plan_detail['id']."','Cash','$date','y','$date','$is_upgrade','$token_membership')";
							$user_plan_list = mysqli_query($conn, $user_member_plan);
							$membership_plan_id = mysqli_insert_id($conn);    
						}
						if($plan_detail['plan_type'] == 'per'){
							
							$plan_la=$plan_detail['plan_benefit']." %";
							$discount = $total_cart_amount*($plan_detail['plan_benefit']/100);
							// $total_cart_amount = $total_cart_amount;
							
						}else{
							$plan_la="Rm ".$plan_detail['plan_benefit']." off";
							$discount = $plan_detail['plan_benefit'];
							// $total_cart_amount = $total_cart_amount - $discount;
						}
						$membership_discount_input=$plan_la;
					}
					// $total_cart_amount = $total_cart_amount - $coupon_discount;
					   
					if($total_cart_amount < 0){
						$total_cart_amount = 0;
					}
					if($discount>$total_cart_amount)
						$discount=$total_cart_amount;
				}  
			 
// 			var_dump($total_cart_amount);
// 			exit;
			if($_SESSION['login'])
			{
				if($total_rebate_amount>0)
					$rebate_applicable="y";
			}
			else
			{
				$rebate_applicable="n";
			}
			 // $total_hike_amount=($price_hike / 100) * $total_cart_amount;
			 
			
			 
			$vendor_total = ($vendor_comission / 100) * $total_cart_amount;  
			if($pickup_type=="divein")
				$special_delivery_amount=0;
			if($login_for_wallet_id=='')
				$membership_applicable="n";  
			if($auto_accept_order=='1')
				$status=2;
			else
				$status=0;  
				
				if($r_code)
				{
					$q="SELECT comission_list.*, users.id as ref_user_id,users.balance_usd
					FROM comission_list  INNER JOIN users ON users.id = comission_list.user_id where comission_list.refferal_code='$r_code' and comission_list.merchant_id='$m_id'; ";
					$reff_query=mysqli_query($conn,$q);
					$ref_count=mysqli_num_rows($reff_query);
					if($ref_count>0)
					{
						$ref_data=mysqli_fetch_array($reff_query);
						$order_refer_id=$ref_data['ref_user_id'];
					}
					else
					{
						$reff_query=mysqli_query($conn,"select * from users where user_refferal_code='$r_code' limit 0,1");
						$ref_data=mysqli_fetch_array($reff_query);
						$order_refer_id=$ref_data['id'];
					}
					$per=2;
					$total_refferal_amount = ($per / 100) * $total_cart_amount;
				}
				else
				{
					$order_refer_id=0;
					$total_refferal_amount=0;
				}
				if($order_refer_id==$user_id)
				{
					$order_refer_id=0;
					$total_refferal_amount=0;
				}  
				
								$territory_price = $territory_hidden_id."|".$postcode_delivery_charge_hidden_price;

			 	     
			 	     $sqlFinalIns = "INSERT INTO order_list SET free_delivery_prompt='$free_delivery_status',territory_price='$territory_price', speed_delivery_amount = '$speed_delivery_amount',r_code='$r_code',ipay_p_id='$ipay_p_id',ipay_payment_status	='$ipay_payment_status',pay_transid = '$pay_transid',ipay_message='$ipay_message',order_refer_id='$order_refer_id',total_refferal_amount='$total_refferal_amount',order_time_shop_on='$order_time_shop_on',order_lat='$latitude',order_lng='$longitude',status='$status',special_delivery_amount='$special_delivery_amount',price_hike='$price_hike',pickup_type='$show_pick_up',vendor_comission=$vendor_total,plastic_box='$plastic_box',deliver_tax_amount='$deliver_tax_amount',rebate_applicable='$rebate_applicable',membership_discount_input='$membership_discount_input',membership_applicable='$membership_applicable',order_extra_charge='$order_extra_charge',remark_extra='$remark_extra',rebate_amount='$rebate_amount',prepaid='$prepaid',membership_discount='".$discount."',coupon_id='$coupon_id',coupon_discount='".$coupon_discount."',coupon_code='".$coupon_code."',membership_plan_id='$membership_plan_id',total_cart_amount='$total_cart_amount',total_rebate_amount='$total_rebate_amount',wallet_paid_amount='$wallet_paid_amount',online_pay='$online_pay',payment_alert='$payment_alert',user_name='$user_name',user_mobile='$user_mobile',wallet='fpx',varient_type='$v_str',product_id='$pro_id',  user_id='$user_id', merchant_id='$m_id', quantity='$qty_list', amount='$p_price',product_code='$p_code', remark='$option', location='".$location."', table_type='".$table_type."',section_type='$section_type',created_on='$date', invoice_no='$invoice_no',newuser='$newuser',show_alert='$show_alert',section_saved='$section_saved',agent_code='$agent_code'";

				//echo $sqlFinalIns;exit;
				$test_method = mysqli_query($conn, $sqlFinalIns);
				$order_id = mysqli_insert_id($conn); 
				#echo 'teste'.$order_id.$test_method ;				
				#die;  
				// if order saved then go ahead 
				if($test_method)
				{
					$merchant_id = $_SESSION['AjaxCartResponse']['merchant_id'];
					unset($_SESSION['AjaxCartResponse'][$m_id]);  
					$sms_to=$user_mobile;
					$rand= substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1,4);
					$url="https://www.koofamilies.com/orderlist.php?did=".$user_id."&vs=".$rand."&oid=".$order_id;    
					$sms_msg="Your order is complete, you can check your order here. ".$url;
					if($merchant_data['order_sms'])
					{
						if($sms_to!="601001025477")
						$smsend=gw_send_sms("APIHKXVL33N5E", "APIHKXVL33N5EHKXVL", "9787136232", $sms_to,$sms_msg); 
					}				
					  $_SESSION['new_order']='y';
					 $whatapp_group_name=$merchant_data['whatapp_group_name'];
					 $merchant_onesignal_id=$merchant_data['onesignal_player_id'];
					 $user_onesignal_id=$userdata['onesignal_player_id'];
					$push_id='';
					 $rand= substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1,4);
					 $push_send="n";
					 if($push_send=="y")
					 {
					 include 'onetest.php';
					 $onesignal = new Onetest();
					if($merchant_onesignal_id)
					{
						// include 'onetest.php';
						
						$data['push_id']=$merchant_onesignal_id;
						$data['message']='Congratulation! You have a new order. Please check your order list.';
						$data['redirectURL']=$site_url .'/orderview.php?vs='.$rand."&did=".$m_id;
						$resultpush = $onesignal->sendMessage($data);
					}
					if($user_onesignal_id)
					{
						$data['push_id']=$user_onesignal_id;
						$data['message']=$sms_msg;
						$data['redirectURL']=$url;
						$resultpush = $onesignal->sendMessage($data);
					}
					
					// send onesingal push to client 
					// $admin_one_signal_id="f9720947-9c4b-4440-ba1b-e9e8132dc25a";
					$admin_one_signal_id="0a97b787-578f-4c26-98f1-8bcc04087a7f";
					$admin_one_signal_id="b5753265-4e15-44f2-8635-343cd07ac233";   
					if($admin_one_signal_id)
					{
						include 'adminpush.php';
						$adminpush = new Adminpush();
						$client_msg="New Order on ".$merchant_data['name'];
						$data['push_id']=$admin_one_signal_id;
						$data['message']=$client_msg;
						
						$rand= substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1,4);
						
						$m_url= $site_url .'/orderview.php?vs='.$rand."&did=".$m_id;
						$data['redirectURL']=$m_url;
						$resultpush = $adminpush->sendMessage($data);
					}
					$rider_order_send="y";
					if($rider_order_send)
					{
						include 'riderpush.php';
						$adminpush = new Riderpush();
						$client_msg="New Order on ".$merchant_data['name'];
						$data['push_id']=$admin_one_signal_id;
						$data['message']=$client_msg;
						
						$rand= substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1,4);
						
						$m_url= $site_url .'/norder.php?vs='.$rand."&s_id=".$order_id;
						$data['redirectURL']=$m_url;
						$r_msg=$client_msg."\r\n".$m_url;
						$r_msg.="\r\n"."Invoice No:".$invoice_no; 
						if($remark_extra)
						$r_msg.="\r\n"."Order remark: ".$remark_extra;
						if($merchant_data['merchant_remark'])
						$r_msg.="\r\n"."Merchant Remark:".$merchant_data['merchant_remark'];  
						$location_m=rawurlencode($merchant_data['google_map']);
						$r_msg.="\r\n"."Merchant Address: "."http://maps.google.com/maps?q=".$location_m;
						if($special_delivery_amount)
						$r_msg.="\r\n"."Chinese man delivery ".number_format($special_delivery_amount,2);
						whatappgroupmsg("Kulai-New order rider",$r_msg);  
						$resultpush = $adminpush->sendMessage($data);

					}
					 }
					// update coupon status 
					if($coupon_id)
					{
						// uppdate coupon count status 
						  $coupon_query = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM coupon WHERE id='$coupon_id'"));
						  $current_rem=$coupon_query['remain_user'];
						  $new_rem=$current_rem-1;
						  $updatecoupon=mysqli_query($conn,"update coupon set remain_user='$new_rem' where id='$coupon_id'");
					}
				}  
				if($test_method)
				{
					// upgrade past plan 
					if($defalutplan>0)
					{
						$created_date=$defalutarray['created'];
						$total_shop=mysqli_fetch_assoc(mysqli_query($conn,"select sum(total_cart_amount) as total_order_amount from order_list where user_id='$user_id' and merchant_id='$m_id' and created_on>='$created_date'"));
						if($total_shop['total_order_amount'])
						{
						$total_shop_amount=number_format($total_shop['total_order_amount'],2);
						$total_shop=$total_shop['total_order_amount'];
						}
						else
						{
						$total_shop_amount=0;
						$total_shop=0;
						}
						  $query="SELECT user_membership_plan.*, membership_plan.user_id as memberplan_user, membership_plan.* FROM user_membership_plan INNER JOIN membership_plan ON membership_plan.id = user_membership_plan.plan_id WHERE user_membership_plan.plan_active='y' and user_membership_plan.user_id='$user_id' and user_membership_plan.merchant_id = '$m_id' and membership_plan.total_max_order_amount>='$total_shop' and  membership_plan.total_min_order_amount<='$total_shop'";
						// check local order synch coin detail 
						$local_coin = mysqli_fetch_assoc(mysqli_query($conn,"select sum(local_coin) as total_coin from local_coin_sync where merchant_id='$m_id' and user_mobile='$mobile_check' and order_date>='$created_date'"));
						if($local_coin['total_coin']>0)
						{
							$total_shop=$local_coin['total_coin']+$total_shop;
						}  
						$user_plan = mysqli_fetch_assoc(mysqli_query($conn,$query));
						// print_R($user_plan);
						// die;
						$membership_plan_id=$user_plan['plan_id'];
						
						if(is_null($user_plan)){
							
							$plan_detail = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM membership_plan WHERE user_id='$m_id' and $total_shop BETWEEN total_min_order_amount AND total_max_order_amount;"));
							
							// print_r($plan_detail);
							// die;
							if(!is_null($plan_detail)){
								// disactive all old membership 
								$past_plan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM user_membership_plan WHERE user_id='$user_id' and merchant_id='$m_id'"));
								if($past_plan)
								{
									$is_upgrade="y";
								}
								else
								{
									$is_upgrade="n";
								}
								mysqli_query($conn,"update user_membership_plan set plan_active='n' where user_id='$user_id' and merchant_id='$m_id'");
								$token_membership=gen();
								$user_member_plan = "INSERT INTO `user_membership_plan`(`user_id`,`user_mobile`,`merchant_id`, `plan_id`, `paid_via`, `paid_date`, `plan_active`, `created`, `is_upgrade`,`token_membership`)
								VALUES ('$user_id','$mobile_check','$m_id','".$plan_detail['id']."','Cash','$date','y','$date','$is_upgrade','$token_membership')";
								$user_plan_list = mysqli_query($conn, $user_member_plan);
								$membership_plan_id = mysqli_insert_id($conn); 
							}
							
							
						}  
						
					}
					
					if($agent_code)
					{
						$agent_id = mysqli_fetch_assoc(mysqli_query($conn,"SELECT agent_id FROM agent_code WHERE code = '$agent_code' ORDER BY date DESC LIMIT 1"))['agent_id'];
						$agent_type = mysqli_fetch_assoc(mysqli_query($conn,"SELECT type FROM agents WHERE id='$agent_id'"))['type'];
						$products = explode(",", $pro_id);
						$totalPrice = 0;
						$qty_arr = explode(',', $qty_list);
						if(sizeof($products) > 1){
							foreach($products as $index => $product){
								$isCommissionable = (mysqli_fetch_assoc(mysqli_query($conn, "SELECT product_discount FROM products WHERE id='$product'"))['product_discount'] > 0) ? true : false;
								if($isCommissionable){
									$price = mysqli_fetch_assoc(mysqli_query($conn, "SELECT product_price FROM products WHERE id='$product'"))['product_price'];
									$totalPrice += floatval($price) * $qty_arr[$index];
								}
							}   
						}else{
							$isCommissionable = (mysqli_fetch_assoc(mysqli_query($conn, "SELECT product_discount FROM products WHERE id='$products[0]'"))['product_discount'] > 0) ? true : false;
							if($isCommissionable){
								$price = mysqli_fetch_assoc(mysqli_query($conn, "SELECT product_price FROM products WHERE id='$products[0]'"))['product_price'];
								$totalPrice = floatval($price) * $qty_arr[0];
							}
						}
						$firstCommission = (mysqli_num_rows(mysqli_query($conn, "SELECT id FROM agent_commissions WHERE agent_id = '$agent_id'")) > 0) ? false : true;
						if($agent_type == 0)
							$commission = ($firstCommission) ? $totalPrice * .2 : $totalPrice * .1;
						else
							$commission = ($firstCommission) ? $totalPrice * .1 : $totalPrice * .05;

						$commission = ($commission > 2) ? 2 : $commission;
					   mysqli_query($conn, "INSERT INTO agent_commissions SET agent_id='$agent_id', qty='$commission', order_id='$order_id', status='$status'");   
					}   
					// deduct rebate balance 
					$query="UPDATE users SET balance_myr= '$myr_bal',balance_usd='$usb_bal',balance_inr='$inr_bal' WHERE `users`.`id`='$user_id'";
					$insert=mysqli_query($conn,$query);
					$creaed_on=strtotime(date('Y-m-d H:i:s'));
					if($w_type=="koo_balance")
					$w_m_id=6419;
					else
					$w_m_id=$m_id;
					if($wallet_paid_amount>0)
					{
						 $q="INSERT INTO tranfer (`sender_id`, `amount`, `receiver_id`, `wallet`, `created_on`, `status`, `details`,`invoice_no`,`type_method`,`coin_merchant_id`) 
					VALUES ('$user_id', '$wallet_paid_amount', '$m_id', '$wal_label', '$creaed_on', '1', 'ewallet used for order','$invoice_no','ewallet','$m_id')";
					
						mysqli_query($conn,$q);
						
					}
					
					
				  if ($whatapp_group_name && $write_up_share=='1') {
					    $query="SELECT order_list.*,users.id,users.name,users.handphone_number,
					users.pending_time,users.whatapp_group_name,users.name as merchant_name,users.mobile_number,users.sst_rate,users.merchant_remark,users.google_map 
						FROM order_list 
					inner join users on order_list.merchant_id = users.id
					 WHERE order_list.id='$order_id'";
						$q=mysqli_query($conn,$query);
						$row=mysqli_fetch_array($q);
						$product_ids = explode(",",$row['product_id']);
						$quantity_ids = explode(",",$row['quantity']);
						$amount_val = explode(",",$row['amount']);
						$product_code = explode(",",$row['product_code']);
						$amount_data = array_combine($product_ids, $amount_val);
						$total_data = array_combine($quantity_ids, $amount_val);
						$remark_ids = explode("|",str_replace("_", " ", $row['remark']));
						$sstper=$row['sst_rate'];
						$i=0;
							 $varient_type=$row['varient_type'];
								if($varient_type)
								{
									$v_str=$row['varient_type'];
									$v_array=explode("|",$v_str);
								}
							$total_qun=0;
							foreach ($amount_val as $key => $value){
												if( $quantity_ids[$key] && $value ) {
													$total =  $total + ($quantity_ids[$key] *$value );
													$total_qun+=$quantity_ids[$key];
												} 
											   }
											 if($sstper>0){
												$incsst = ($sstper / 100) * $total;
													$incsst=@number_format($incsst, 2);
													$incsst=ceiling($incsst,0.05);
													  $incsst=@number_format($incsst, 2);
													 $g_total=@number_format($total+$incsst, 2);
													} else { $g_total=$total;} 
							$total_bill=($g_total+$row['order_extra_charge']+$row['deliver_tax_amount']+$row['special_delivery_amount'])-($row['wallet_paid_amount']+$row['membership_discount']+$row['coupon_discount']);
							$r_str='';
							$show_remark='';
							$o_remark='';
							$c_remark='';
							$msg_str='';
							$date=$row['created_on'];
							$msg_str.="Date :".date("d/m/Y h:i A",strtotime($date));
							$special_delivery_amount=$row['special_delivery_amount'];
							if($row['remark_extra'])
							{
								$msg_str.="\r\n *"."Order Remark:".$row['remark_extra']."*";
							}
							if($row['merchant_remark'])
							{
								$msg_str.="\r\n"."Merchant Remark:".$row['merchant_remark'];
							}
							if($row['plastic_box'])
							{
								$msg_str.="\r\n"."Container:".$row['plastic_box'];  
							}
							$v_comisssion=$row['vendor_comission'];
							$v_comisssion=number_format($v_comisssion,2);
							if($v_comisssion)
							{
								if($special_delivery_amount)
								$msg_str.="\r\n"."Cash term Pay:(".$total."+(chinese man delivery))-".$v_comisssion."(com)=".number_format($total-$v_comisssion,2);
								else
								$msg_str.="\r\n"."Cash term Pay:".$total."-".$v_comisssion."(com)=".number_format($total-$v_comisssion,2);
							}
							else
							{
								if($special_delivery_amount)
								$msg_str.="\r\n"."Cash term Pay:".number_format($total,2)."+".number_format($special_delivery_amount,2)."(chinese man delivery)";
								else
								$msg_str.="\r\n"."Cash term Pay:".number_format($total,2);	  
							}
							 $otp_str='';
							if($row['otp_verified']=="n")
							$otp_str="(Unverified)";
							else if($row['isLocked']==1)
							{
								$otp_str="(Locked)";
							}
							if($row['invoice_no'])
							{
								$inv_str="(".$row['wallet'].")"." Invoice No: ".$row['invoice_no'];
							}
							if($row['location'])
							{
								$user_location="\r\n".$row['location'];
							}  
							$wallet=$row['wallet'];
							if($row['coupon_discount']=='')
								$row['coupon_discount']=0;
							$m_map='';
							$u_map='';
							if($merchant_data['google_map'])
								{
									$location_m=rawurlencode($merchant_data['google_map']);
									$m_map.="\r\n"."Merchant Address: "."http://maps.google.com/maps?q=".$location_m;
								}
							if($row['location'])
							{
								$location_u=rawurlencode($row['location']);
								$u_map.="\r\n"."User Address: "."http://maps.google.com/maps?q=".$location_u;
							}
							if($row['special_delivery_amount']==0)
							{
								
								
							  $msg_str.="\r\n"."Collect:{".$total."+".$incsst."(SST)+".$row['order_extra_charge']."+".$row['deliver_tax_amount'].")-(".$row['wallet_paid_amount']."(WALLET)-".$row['membership_discount']."-".$row['coupon_discount']."}=".number_format($total_bill,2)."\r\n".$inv_str."\r\nPickup Type:".$row['pickup_type']."\r\nOrder from:\r\n";
							  $msg_str.=$row['merchant_name'].",\r\n".$row['google_map'].$m_map." ,\r\n Mobile - ".$row['mobile_number']."\r\n  To: ".$row['user_name']." \r\n,".$row['user_mobile'].$otp_str."".$user_location.$u_map."\r\n Order Detail:";
							} 
							else
							{
							  $msg_str.="\r\n"."Collect:{".$total."+".$incsst."(SST)+".$row['order_extra_charge']."+".$row['deliver_tax_amount']."+".$row['special_delivery_amount'].")-(".$row['wallet_paid_amount']."(WALLET)-".$row['membership_discount']."-".$row['coupon_discount']."}=".number_format($total_bill,2)."\r\n".$inv_str."\r\nPickup Type:".$row['pickup_type']."\r\n Order from:\r\n";
							  $msg_str="*".$row['merchant_name'].",\r\n".$row['google_map'].$m_map.",\r\n Mobile - ".$row['mobile_number']."\r\n  To: ".$row['user_name']." \r\n,".$row['user_mobile'].$otp_str."".$user_location.$u_map."\r\n Order Detail:";
							}
							$p=1;
							 foreach ($product_ids as $key )
							{  
								if(is_numeric($key))
								{
									$product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id ='".$key."'"));
									// $msg_str.="<b>".$product['product_name'].',qty-'.$quantity_ids[$i].',Unit Price '.$product['product_price'].'</b><br>';
									$msg_str.="\r\n *".$p.") ".$product['product_name']."\r\n(".$product['category'].')('.$product['product_type'].'),qty-'.$quantity_ids[$i].',Unit Price:'.$product['product_price']."*\r\n";    
								}  
								else
								{
								   $msg_str.=$key.'\r\n';
								}
								if($product['remark'])
								{
									$msg_str.= "Product Remark :".$product['remark']."\r\n";
								}   
								if($remark_ids[$i])
								{
									$msg_str.= "Remark :".$remark_ids[$i]."\r\n";
								}  
								if($v_array[$i])
								{
									$v_match=$v_array[$i];
									$v_match = ltrim($v_match, ',');
									$sub_rows = mysqli_query($conn, "SELECT * FROM sub_products WHERE id  in ($v_match)");
									while ($srow=mysqli_fetch_assoc($sub_rows)){
										$msg_str.=  "     "."-".$srow['name'].",Price:".number_format($srow['product_price'],2);
										$msg_str.=  "\r\n";
										}  
								}  
							   
								$p++;
								$i++;
							}        
							$msg_str.="\r\nTotal qty : ".$total_qun." --End-- \r\n";
							whatappgroupmsg($whatapp_group_name,$msg_str);
						// send write up to merchant   
					   
					 
					}  
				}  
				
			 
		   
		   if($guest_user_order=="y")
		   {
			   $_SESSION['guest_order_id']=$order_id;
		   }
			// $v_array=explode("|",$varient_type);
		  $vs=0;
		  $v_order=1;
		  $parray=explode(",",$pro_id);
		// print_R($varient_type);
		  if($varient_type)
		  {
			foreach($varient_type as $vr)
			{
				
				$product_id=$parray[$vs];
				if($vr)
				{
					$v_match=$vr;
					$v_match = ltrim($v_match, ',');
					$sub_rows = mysqli_query($conn, "SELECT * FROM sub_products WHERE id  in ($v_match)");
					while ($srow=mysqli_fetch_assoc($sub_rows)){
						$v_id=$srow['id'];
						 $query="INSERT INTO order_varient SET product_id='$product_id', varient_id='$v_id', invoice_no='$invoice_no',order_id='$order_id',merchant_id='$m_id',v_order='$v_order',v_code='$v_no'";
						
						 mysqli_query($conn,$query);
						$v_count++;	
						$v_no++;
						}  
				}
				$vs++;	
				$v_order++;
			}
		  }
		  $send_url=$site_url."/orderlist.php"."?payid=".$user_id."&vs=".md5(rand());
		  header("Location:$send_url");
		}
	   else
	   {  
		   $_SESSION['same_order']='y';
		    header("Location: ".$site_url."/view_merchant.php");
	   }
	  
}

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