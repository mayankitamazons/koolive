<?php
include("config.php");
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
function ceiling($number, $significance = 1)
								{
									return ( is_numeric($number) && is_numeric($significance) ) ? (ceil(round($number/$significance))*$significance) : false;
								}
$s_id=$_POST['s_id'];
// $s_id=22657;
if($s_id)
{
    $q="SELECT  SQL_NO_CACHE order_list.*,users.name,users.isLocked,users.otp_verified,users.mobile_number 
	FROM order_list inner join users on order_list.user_id=users.id WHERE order_list.id='".$s_id."'";
	$query=mysqli_query($conn,$q);
	$row = mysqli_fetch_assoc($query);
	
	$m_id=$row['merchant_id'];
	$merchant_name=mysqli_fetch_assoc(mysqli_query($conn, "SELECT SQL_NO_CACHE * FROM users WHERE id='".$m_id."'"));
	$sstper=$merchant_name['sst_rate'];
	$merchant_latitude = $merchant_name['latitude'];
	$merchant_longitude = $merchant_name['longitude'];
	$merchant_location = $merchant_name['google_map'];
	if($row)
	{
		$product_ids = explode(",",$row['product_id']);
		$quantity_ids = explode(",",$row['quantity']);
		$amount_val = explode(",",$row['amount']);
		$product_code = explode(",",$row['product_code']);
		$amount_data = array_combine($product_ids, $amount_val);
		$total_data = array_combine($quantity_ids, $amount_val);
		 $remark_ids = explode("|",str_replace("_", " ", $row['remark']));
		// echo "Product Name </br>";
		$i=0;
		 $varient_type=$row['varient_type'];
			if($varient_type)
			{
				$v_str=$row['varient_type'];
				$v_array=explode("|",$v_str);
			}
		$total_qun=0;
		$territory_price_array = explode("|",$row['territory_price']);
		$terr_id = $territory_price_array[0];
		$territory_label = '';
		$territory_price = $territory_price_array[1];
		if($terr_id){
			//echo $terr_id;
			$qu_terr = mysqli_fetch_assoc(mysqli_query($conn,"select t_postcode,t_price,t_location_name from territory where t_id = ".$terr_id));
			if($qu_terr['t_location_name'] != ''){
				$territory_label = $qu_terr['t_location_name']." : RM ".$qu_terr['t_price'];
			}else{
				$territory_label = "";
			}
		}		

      	
		
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
								  $territory_price_array = explode("|",$row['territory_price']);
								$terr_id = $territory_price_array[0];
								$territory_price = $territory_price_array[1];//$qu_terr['t_price'];
								if($territory_price != ''){
									$territory_price = " +".$territory_price;
								}
								
							     $g_total=@number_format($total+$territory_price+$incsst+$row['speed_delivery_amount'], 2);
								} else { $g_total=$total +$territory_price + $row['speed_delivery_amount'];} 
		$total_bill=($g_total+$row['order_extra_charge']+$row['deliver_tax_amount']+$row['special_delivery_amount'] )-($row['wallet_paid_amount']+$row['membership_discount']+$row['coupon_discount']);
		$r_str='';
		$show_remark='';
		$o_remark='';
		$c_remark='';
		$msg_str='';
		$date=$row['created_on'];
		
		$msg_str.="Date :".date("d/m/Y h:i A",strtotime($date));
		$special_delivery_amount=$row['special_delivery_amount'];
		
		$speed_delivery_label = '';
		$speed_delivery_price = '';
			
		if($row['speed_delivery_amount']>0){
			$speed_delivery_label = '+ (Speed Delivery)';
			$speed_delivery_price = "+".$row['speed_delivery_amount'];
		}
		
		if($row['free_delivery_prompt'] == 1){
			$msg_str.="</br><b>*Free Delivery, 相同顾客*</b>";
		} 	
		
			
		if($row['order_time_shop_on']=="n")  
		{
			$msg_str.="</br><b>*Shop closed*</b>";
		}   
		if($row['remark_extra'])
		{
			$msg_str.="</br><b><u>(Order Remark:)".$row['remark_extra']."</u></b>";
		}
		if($merchant_name['merchant_remark'])
		{
			$msg_str.="</br>Merchant Remark:".$merchant_name['merchant_remark'];
		}
		if($row['plastic_box'])
		{
			$msg_str.="</br>Container:".$row['plastic_box'];  
		}
		$v_comisssion=$row['vendor_comission'];
		$v_comisssion=number_format($v_comisssion,2);
		$terr_label = '';
		/*if($territory_price){
			$terr_label = " +(Territory Price) ";
		}*/
		if($v_comisssion)
		{
			
			if($special_delivery_amount)
			$msg_str.="<br/>Cash term Pay:(".$total."+(chinese man delivery) ".$terr_label.$speed_delivery_label.")-".$v_comisssion."(com)=".number_format($total-$v_comisssion,2);
			else
			$msg_str.="<br/>Cash term Pay:".$total.$speed_delivery_label.$terr_label."-".$v_comisssion."(com)=".number_format($total-$v_comisssion,2);
		}
		else
		{
			if($special_delivery_amount)
			$msg_str.="<br/>Cash term Pay:".number_format($total,2)."+".number_format($special_delivery_amount,2)."(chinese man delivery)".$speed_delivery_label.$terr_label;
			else
			$msg_str.="<br/>Cash term Pay:".number_format($total,2).$speed_delivery_label.$terr_label;	  
		}
		
		
		if($territory_label != ''){
			$msg_str.="<br/>Territory: ".$territory_label;
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
			$user_location="</br>".$row['location'];
		}
		$wallet=$row['wallet'];
		
		$phone_number = $row['mobile_number'];
		$str_length = strlen($phone_number);
		if(substr($phone_number, 0,1) == 6){
			$user_phone = substr($phone_number, 1, $str_length);
		}else{
			$user_phone = $phone_number;
		}

//To: ".$row['name']." </br>

		if($row['coupon_discount']=='')
			$row['coupon_discount']=0;
		if($row['special_delivery_amount']==0)
		$msg_str.="</br>Collect:{".$total."+".$incsst."(SST)+".$row['order_extra_charge']."+".$row['deliver_tax_amount']." ".$speed_delivery_price.$territory_price.")-(".$row['wallet_paid_amount']."(WALLET)-".$row['membership_discount']."-".$row['coupon_discount']."}=".number_format($total_bill,2)."</br>".$inv_str."</br>Pickup Type:".$row['pickup_type']."</br>Order from:</br>".$merchant_name['name'].",</br>".$merchant_name['google_map']." ,</br> Mobile - ".$merchant_name['mobile_number']." <br/>  ".$user_phone.$otp_str."".$user_location."</br> <p id='od_copy_details_".$row['id']."'>Order Detail:</br>";
		else
		$msg_str.="</br>Collect:{".$total."+".$incsst."(SST)+".$row['order_extra_charge']."+".$row['deliver_tax_amount']."+".$row['special_delivery_amount']." ".$speed_delivery_price.$territory_price.")-(".$row['wallet_paid_amount']."(WALLET)-".$row['membership_discount']."-".$row['coupon_discount']."}=".number_format($total_bill,2)."</br>".$inv_str."</br>Pickup Type:".$row['pickup_type']."</br> Order from:</b>".$merchant_name['name'].",</br>".$merchant_name['google_map']." ,</br> Mobile - ".$merchant_name['mobile_number']." <br/> ".$user_phone.$otp_str."".$user_location."</br> <p id='od_copy_details_".$row['id']."'>Order Detail:</br>";
		$p=1;
		$msg_str.="Invoice No: ".$row['invoice_no']."<br>";
		foreach ($product_ids as $key )
        {  
			if(is_numeric($key))
            {
                $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SQL_NO_CACHE* FROM products WHERE id ='".$key."'"));
				// $msg_str.="<b>".$product['product_name'].',qty-'.$quantity_ids[$i].',Unit Price '.$product['product_price'].'</b><br>';
				$msg_str.="<b>".$p.")  ".$product['product_name']."</br>(".$product['category'].')('.$product['product_type'].'),qty-'.$quantity_ids[$i].',Unit Price:'.$product['product_price'].'</b></br>';    
			}
			else
			{
               $msg_str.=$key.'<br>';
			}
			
			if($product['remark'])
			{
				$msg_str.= "Product Remark :".$product['remark'].'<br>';   
			}
			if($remark_ids[$i])
			{
				$msg_str.= "Remark :".$remark_ids[$i].'<br>';
			}
			if($v_array[$i])
			{
				$v_match=$v_array[$i];
				$v_match = ltrim($v_match, ',');
				$sub_rows = mysqli_query($conn, "SELECT * FROM sub_products WHERE id  in ($v_match)");
				while ($srow=mysqli_fetch_assoc($sub_rows)){
					$msg_str.=  "&nbsp;&nbsp;&nbsp;&nbsp;"."-".$srow['name'].",Price:".number_format($srow['product_price'],2);
					$msg_str.=  "</br>";
					}
			}
			$msg_str.=  "</br>";
			$i++;
			$p++;
		}
		$msg_str .= "Total qty : ".$total_qun."<br/>";
		$msg_str .="</p>";
		if($row['location'])
		{
			$latlng=$row['order_lat'].",".$row['order_lng'];
		    // $msg_str.="Map Link : http://maps.google.com/maps?q=".urlencode($row['location'])."</br>";    
			if($row['order_lat'] && $row['order_lng'])
			{
			$msg_str.="Customer Link :https://www.google.com/maps/place/".urlencode($row['location']).",/@".$latlng.",17z/data=!3m1!4b1!4m5!3m4!1s0x396dc85b42c5e27f:0x22577f7c977bcb36!8m2!3d".$latlng."</br>"; ;     
			}
			 else
			 {
			 $msg_str.="Customer Link : http://maps.google.com/maps?q=".urlencode($row['location'])."</br>";    
			 }			 
		}
		
		$merchant_latitude = $merchant_name['latitude'];
		$merchant_longitude = $merchant_name['longitude'];
		$merchant_location = $merchant_name['google_map'];
		if($merchant_location)
		{
			$latlng=$merchant_latitude.",".$merchant_longitude;
		    // $msg_str.="Map Link : http://maps.google.com/maps?q=".urlencode($row['location'])."</br>";    
			if($merchant_latitude && $merchant_longitude)
			{
			$msg_str.="Merchant Link :https://www.google.com/maps/place/".urlencode($merchant_location).",/@".$latlng.",17z/data=!3m1!4b1!4m5!3m4!1s0x396dc85b42c5e27f:0x22577f7c977bcb36!8m2!3d".$latlng."</br>"; ;     
			}
			 else
			 {
			 $msg_str.="Merchant Link : http://maps.google.com/maps?q=".urlencode($merchant_location)."</br>";    
			 }			 
		}
		
		
		
		
		
		$msg_str.=" --End--</br>";
		
		echo $msg_str;   
	}
}
?>