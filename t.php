<?php
include("config.php");
function ceiling($number, $significance = 1)
	{
		return ( is_numeric($number) && is_numeric($significance) ) ? (ceil(round($number/$significance))*$significance) : false;
	}
$cur_date=date('Y-m-d');
 $cur_utc=strtotime(date('Y-m-d h:i:s'));  
  $query="SELECT order_list.*,users.id,users.name,users.handphone_number,
users.pending_time,users.whatapp_group_name,users.name as merchant_name,users.sst_rate,users.merchant_remark,users.google_map  FROM order_list 
inner join users on order_list.merchant_id = users.id
 WHERE order_list.merchant_id not in('5401') and users.pending_time!=0 AND status =0 AND DATE(`created_on`) ='$cur_date' 
  and order_list.id='23292'  order by order_list.created_on  DESC";

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
			$msg_str.="\r\n"."Order Remark:".$row['remark_extra'];
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
		if($row['special_delivery_amount']==0)
		$msg_str.="\r\n"."Collect:{".$total."+".$incsst."(SST)+".$row['order_extra_charge']."+".$row['deliver_tax_amount'].")-(".$row['wallet_paid_amount']."(WALLET)-".$row['membership_discount']."-".$row['coupon_discount']."}=".number_format($total_bill,2)."\r\n".$inv_str."\r\nPickup Type:".$row['pickup_type']."\r\nOrder from:\r\n".$row['merchant_name'].",\r\n".$row['google_map']." ,\r\n Mobile - ".$row['mobile_number']."\r\n  To: ".$row['user_name']." \r\n,".$row['user_mobile'].$otp_str."".$user_location."\r\n Order Detail:\r\n";
		else
		$msg_str.="\r\n"."Collect:{".$total."+".$incsst."(SST)+".$row['order_extra_charge']."+".$row['deliver_tax_amount']."+".$row['special_delivery_amount'].")-(".$row['wallet_paid_amount']."(WALLET)-".$row['membership_discount']."-".$row['coupon_discount']."}=".number_format($total_bill,2)."\r\n".$inv_str."\r\nPickup Type:".$row['pickup_type']."\r\n Order from:*".$row['merchant_name'].",\r\n".$row['google_map']." ,\r\n Mobile - ".$row['mobile_number']."\r\n  To: ".$row['user_name']." \r\n,".$row['user_mobile'].$otp_str."".$user_location."\r\n Order Detail:\r\n";
	    foreach ($product_ids as $key )
        {  
			if(is_numeric($key))
            {
                $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id ='".$key."'"));
				// $msg_str.="<b>".$product['product_name'].',qty-'.$quantity_ids[$i].',Unit Price '.$product['product_price'].'</b><br>';
				$msg_str.="\r\n".$product['product_name']."(".$product['category'].')('.$product['product_type'].'),qty-'.$quantity_ids[$i].',Unit Price:'.$product['product_price']."\r\n";    
			}  
			else
			{
               $msg_str.=$key.'\r\n';
			}
			if($remark_ids[$i])
			{
				$msg_str.= "Remark :".$remark_ids[$i].'\r\n';
			}  
			if($v_array[$i])
			{
				$v_match=$v_array[$i];
				$v_match = ltrim($v_match, ',');
				$sub_rows = mysqli_query($conn, "SELECT * FROM sub_products WHERE id  in ($v_match)");
				while ($srow=mysqli_fetch_assoc($sub_rows)){
					$msg_str.=  "&nbsp;&nbsp;&nbsp;&nbsp;"."-".$srow['name'].",Price:".number_format($srow['product_price'],2);
					$msg_str.=  "\r\n";
					}  
			}  
		   
			$i++;
		}        
		$msg_str.="\r\nTotal qty : ".$total_qun."\r\n";        
		// echo $msg_str;  
		sendpush($msg_str);
		die;     
    function sendpush2($sms_msg)
	{
		 $INSTANCE_ID = "17";  // TODO: Replace it with your gateway instance ID here
		$CLIENT_ID = "mayank.mangalgroup@gmail.com";  // TODO: Replace it with your Forever Green client ID here
		$CLIENT_SECRET = "a16236d83f1b43f38310e3cd393293af";   // TODO: Replace it with your Forever Green client secret here
	   // $whatapp_group_name="chao shan Delivery"; 
		// $INSTANCE_ID = "25";  // TODO: Replace it with your gateway instance ID here
		// $CLIENT_ID = "woijoonchong@gmail.com";  // TODO: Replace it with your Forever Green client ID here
		// $CLIENT_SECRET = "1df7f3075a7e490689e9bf1c469960a0";   // TODO: Replace it with your Forever Green client secret here
	   $whatapp_group_name="New order for rider group";    
	   $whatapp_group_name="error group";
	   // $sms_msg="Welcome";   
		$postData = array(
		  'group_admin' => '60123115670',  // TODO: Specify the WhatsApp number of the group creator, including the country code
		  'group_name' => $whatapp_group_name,    // TODO: Specify the name of the group
		  'message' =>"test"  // TODO: Specify the content of your message
		);

		$headers = array(
		  'Content-Type: application/json',
		  'X-WM-CLIENT-ID: '.$CLIENT_ID,
		  'X-WM-CLIENT-SECRET: '.$CLIENT_SECRET
		);

		$url = 'http://api.whatsmate.net/v3/whatsapp/group/text/message/' . $INSTANCE_ID;
		$ch = curl_init($url);   

		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));

		$response = curl_exec($ch);
		$json_array=json_decode($response);
		if($json_array->error_message)
		{
			echo "failed to first setting";
		  sendpush($sms_msg);	
		}
		// if($json_array[''])
	   print_R($response);
	   
	}
    function sendpush($sms_msg)
	{


		 $INSTANCE_ID = "25";  // TODO: Replace it with your gateway instance ID here
	$CLIENT_ID = "woijoonchong@gmail.com";  // TODO: Replace it with your Forever Green client ID here
	$CLIENT_SECRET = "1df7f3075a7e490689e9bf1c469960a0";   // TODO: Replace it with your Forever Green client secret here
	   $whatapp_group_name="New order for rider group";    
	   $whatapp_group_name="error group";
	   // $sms_msg="Welcome";   
		$postData = array(
		  'group_admin' => '60123115670',  // TODO: Specify the WhatsApp number of the group creator, including the country code
		  'group_name' => $whatapp_group_name,    // TODO: Specify the name of the group
		  'message' =>"test"  // TODO: Specify the content of your message
		);

		$headers = array(
		  'Content-Type: application/json',
		  'X-WM-CLIENT-ID: '.$CLIENT_ID,
		  'X-WM-CLIENT-SECRET: '.$CLIENT_SECRET
		);

		$url = 'http://api.whatsmate.net/v3/whatsapp/group/text/message/' . $INSTANCE_ID;
		$ch = curl_init($url);   

		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));

		$response = curl_exec($ch);
		print_R($response);
		die;   
		$json_array=json_decode($response);
		if($json_array->error_message)
		{
			sendpush2($sms_msg);
		}
		// if($json_array[''])
	   print_R($response);
	   
	}		   
	
?>