<?php
   include("config.php");
   function ceiling($number, $significance = 1)
   {
   	return ( is_numeric($number) && is_numeric($significance) ) ? (ceil(round($number/$significance))*$significance) : false;
   }
   $s_id=$_POST['s_id'];
   $row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT order_list.*,users.name,users.isLocked,users.otp_verified,users.mobile_number 
   	FROM order_list inner join users on order_list.user_id=users.id WHERE order_list.id='".$s_id."'"));
   $m_id=$row['merchant_id'];
   $merchant_name=mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$m_id."'"));
   $sstper=$merchant_name['sst_rate'];
   if($row)
   {
   	$product_ids = explode(",",$row['product_id']);
   	$quantity_ids = explode(",",$row['quantity']);
   	$amount_val = explode(",",$row['amount']);
   	$product_code = explode(",",$row['product_code']);
   	$amount_data = array_combine($product_ids, $amount_val);
   	$total_data = array_combine($quantity_ids, $amount_val);
   	$remark_ids = explode("|",str_replace("_", " ", $row['remark']));
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
	
		$r_str='';
   		$show_remark='';
   		$o_remark='';
   		$c_remark='';
   		$msg_str='';
		
   		$date=$row['created_on'];
   }
   $v_comisssion=$row['vendor_comission'];
   $special_delivery_amount=$row['special_delivery_amount'];
   $v_comisssion=number_format($v_comisssion,2);
   $msg_str.="New Order :</br></br>";   
   $msg_str.="Date :".date("d/m/Y h:i A",strtotime($date))."</br>";
   if($row['remark_extra'])
		{
			$msg_str.="Order Remark:".$row['remark_extra']."</br>";
		}
   $msg_str.="(".$row['wallet'].") Invoice No:".$row['invoice_no']."</br>";
   $msg_str.="Pickup Type:".$row['pickup_type']."</br></br>";
   $msg_str.="Order from:</br>";
   $msg_str.=$merchant_name['name'].",";
   if($merchant_name['google_map'])
   {
	   $msg_str.="</br>".$merchant_name['google_map'].",";
   }
   $msg_str.="</br>"."Mobile - ".$merchant_name['mobile_number']."</br></br>";
   $msg_str.="Order Detail:</br>";
   foreach ($product_ids as $key )
        {
            $sep_total=0;			
			if(is_numeric($key))
            {
                $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id ='".$key."'"));
				// $msg_str.="<b>".$product['product_name'].',qty-'.$quantity_ids[$i].',Unit Price '.$product['product_price'].'</b><br>';
				//$msg_str.="<b>".$product['product_name']."(".$product['category'].')('.$product['product_type'].'),qty-'.$quantity_ids[$i].',Unit Price:'.$product['product_price'].'</b></br>';    
			   $msg_str.="(".$product['category'].")(".$product['product_name'].")X(".$quantity_ids[$i].")</br>";
			   $msg_str.= "&nbsp;&nbsp;&nbsp;"."* Unit Price: Rm ".number_format($product['product_price'],2)."</br>";
			   $sep_total=$sep_total+($product['product_price']*$quantity_ids[$i]);
			}
			else
			{
               $msg_str.=$key.'<br>';
			}
			
			if($v_array[$i])
			{
				$v_match=$v_array[$i];
				$v_match = ltrim($v_match, ',');
				$sub_rows = mysqli_query($conn, "SELECT * FROM sub_products WHERE id  in ($v_match)");
				while ($srow=mysqli_fetch_assoc($sub_rows)){
					if($srow['product_price'])
					$msg_str.=  "&nbsp;&nbsp;&nbsp;"."-".$srow['name'].",RM ".number_format($srow['product_price'],2);
					else
					$msg_str.=  "&nbsp;&nbsp;&nbsp;"."-".$srow['name'];	
					$msg_str.=  "</br>";
					// $sep_total+=$srow['product_price']*$quantity_ids[$i];
					}
			}
			if($remark_ids[$i])
			{
				$msg_str.=  "&nbsp;&nbsp;&nbsp;"."-Remark :".$remark_ids[$i].'<br>';
			}
			// $msg_str.="&nbsp;&nbsp;&nbsp;"."=RM ".number_format($sep_total,2)."</br>";   
		     
			$i++;   
		}
		$msg_str.="</br>";
		// $i=0;
    // foreach ($product_ids as $key )
        // {  
			// if(is_numeric($key))
            // {
                // $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id ='".$key."'"));
				   
				// $msg_str.="<b>".$product['product_name'].",qty-".$quantity_ids[$i].',Unit Price:'.$product['product_price'].'</b></br>';    
			// }
			// else
			// {
               // $msg_str.=$key.'<br>';
			// }
			// if($remark_ids[$i])
			// {
				// $msg_str.= "Remark :".$remark_ids[$i].'<br>';
			// }
			// if($v_array[$i])
			// {
				// $v_match=$v_array[$i];
				// $v_match = ltrim($v_match, ',');
				// $sub_rows = mysqli_query($conn, "SELECT * FROM sub_products WHERE id  in ($v_match)");
				// while ($srow=mysqli_fetch_assoc($sub_rows)){
					// $msg_str.=  "&nbsp;&nbsp;&nbsp;&nbsp;"."-".$srow['name'];
					// $msg_str.=  "</br>";
					// }
			// }
		   
			// $i++;
		// } 
   $msg_str.="Total qty :".$total_qun;
   // $total=$sep_total;
   	if($sstper>0){
   			$incsst = ($sstper / 100) * $total;
   			$incsst=@number_format($incsst, 2);
   			$incsst=ceiling($incsst,0.05);
			$incsst=@number_format($incsst, 2);
			$g_total=@number_format($total+$incsst, 2);
   		} else { $g_total=$total;} 
   		$total_bill=($g_total+$row['order_extra_charge']+$row['deliver_tax_amount']+$row['special_delivery_amount'])-($row['wallet_paid_amount']+$row['membership_discount']+$row['coupon_discount']);
   		
   if($v_comisssion)
	{
		if($special_delivery_amount)
		$msg_str.="<br/>Total Cash Pay: (".$total."+(chinese man delivery))-".$v_comisssion."(com)="."RM ".number_format($total-$v_comisssion,2);
		else
		$msg_str.="<br/>Total Cash Pay: ".$total."-".$v_comisssion."(com)="."RM ".number_format($total-$v_comisssion,2);
	}
	else
	{
		if($special_delivery_amount)
		$msg_str.="<br/>Total Cash Pay: RM ".number_format($total,2)."+".number_format($special_delivery_amount,2)."(chinese man delivery)";
		else
		$msg_str.="<br/>Total Cash Pay: RM ".number_format($total,2);	  
	}
	$msg_str.="</br></br>"."Send to:";
	if($row['user_name'])
	$msg_str.="</br>".$row['user_name'].",";
    if($row['user_mobile'])
		$msg_str.="</br>".$row['user_mobile'];
	if($row['location'])
		$msg_str.="</br>".$row['location']."</br>";
	if($row['coupon_discount']=='')
			$row['coupon_discount']=0;
		if($row['special_delivery_amount']==0)
		$msg_str.="</br>Collect:{".$total."+".$incsst."(SST)+".$row['order_extra_charge']."+".$row['deliver_tax_amount'].")-(".$row['wallet_paid_amount']."(WALLET)-".$row['membership_discount']."-".$row['coupon_discount']."}= RM ".number_format($total_bill,2)."</br>";
		else
		$msg_str.="</br>Collect:{".$total."+".$incsst."(SST)+".$row['order_extra_charge']."+".$row['deliver_tax_amount']."+".$row['special_delivery_amount'].")-(".$row['wallet_paid_amount']."(WALLET)-".$row['membership_discount']."-".$row['coupon_discount']."}= RM ".number_format($total_bill,2)."</br>";
	
	// $msg_str.="Please accept order by typing Ok  in this chatbox or click this link to accept the order. Thank you";
   echo $msg_str;
   ?>