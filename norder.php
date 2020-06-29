<?php include('config.php'); ?>
<html lang="en">
<head>
  <title>Koofamilies</title>

<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>


     
<div class="container">
<?php
   $s_id=$_GET['s_id'];
		$row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT  SQL_NO_CACHE order_list.*,users.name,users.isLocked,users.otp_verified,users.mobile_number 
					FROM order_list inner join users on order_list.user_id=users.id WHERE order_list.id='".$s_id."'"));
	   $rider_info=$row['rider_info'];
	  $job_assign='';
	   if($rider_info)
	   {
		   $job_assign="<h4 style='color:red;font-weight:bold;'> Job is Alredy assign,want to access</h4>";
		    
	   }
?>
  <h2 style="margin-left: 37%;font-weight: bold;"> Order Detail  
  <!--span style="float:right:"><i  onclick="myFunction()" class="fa fa-copy" style="font-size:25px;margin-left: 10%;"></i></span!--></h2>
  <div class="row">
   <div class="col-sm-3">
    
    </div>
    <div class="col-sm-6" style="text-align:center;border: 1px solid black;text-overflow: initial;">
	<?php
	    
	   if($_POST['access'] && $_POST['r_code'])
	   {
		   $r_code=$_POST['r_code'];
		    $q="select * from riders where rider_code='$r_code'";
		   $query=mysqli_query($conn,$q);
		   $riderdata=mysqli_fetch_array($query);

		   if($riderdata)
		   {
			    $rider_id=$riderdata['id'];

         		function ceiling($number, $significance = 1)
				{
					return ( is_numeric($number) && is_numeric($significance) ) ? (ceil(round($number/$significance))*$significance) : false;
				}
				
				// $s_id=22657;
				if($s_id)
				{
					
					
					$m_id=$row['merchant_id'];
					$o_id=$row['id'];
					$system_rider_id=$row['rider_id'];
					
					// if($system_rider_id=='' || $system_rider_id==$rider_id)
					if($rider_info=='')
					{
						if($system_rider_id=='' || $system_rider_id==0)
						{
								$post_date =Date("Y-m-d h:i");  
								$rider_mobile_no=$riderdata['rider_mobile_no'];
									// $expire_date = strtotime($exDate);
							 $up="UPDATE `order_list` SET `rider_id` = '$rider_id',`rider_alert`='y',`rider_info`='$rider_mobile_no',`rider_alot_time`='$post_date' WHERE `order_list`.`id` ='$o_id'";
							mysqli_query($conn,$up);
							$system_rider_id=$rider_id;
						}
					}
					if($system_rider_id==$rider_id)
					{
					
					$merchant_name=mysqli_fetch_assoc(mysqli_query($conn, "SELECT SQL_NO_CACHE * FROM users WHERE id='".$m_id."'"));
					
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
						// echo "Product Name </br>";
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
							$msg_str.="</br>Order Remark:".$row['remark_extra'];
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
						
						if($v_comisssion)
						{
							if($special_delivery_amount)
							$msg_str.="<br/>Cash term Pay:(".$total."+(chinese man delivery))-".$v_comisssion."(com)=".number_format($total-$v_comisssion,2);
							else
							$msg_str.="<br/>Cash term Pay:".$total."-".$v_comisssion."(com)=".number_format($total-$v_comisssion,2);
						}
						else
						{
							if($special_delivery_amount)
							$msg_str.="<br/>Cash term Pay:".number_format($total,2)."+".number_format($special_delivery_amount,2)."(chinese man delivery)";
							else
							$msg_str.="<br/>Cash term Pay:".number_format($total,2);	  
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
						if($row['coupon_discount']=='')
							$row['coupon_discount']=0;
							$m_map='';
							$u_map='';
							if($merchant_name['google_map'])
								{
									$location_m=rawurlencode($merchant_name['google_map']);
									$m_map="http://maps.google.com/maps?q=".$location_m;
								}
							if($row['location'])
							{
								$location_u=rawurlencode($row['location']);
								$u_map="http://maps.google.com/maps?q=".$location_u;
							}
							if($row['special_delivery_amount']==0)
							{
								
								
							  $msg_str.="</br>"."Collect:{".$total."+".$incsst."(SST)+".$row['order_extra_charge']."+".$row['deliver_tax_amount'].")-(".$row['wallet_paid_amount']."(WALLET)-".$row['membership_discount']."-".$row['coupon_discount']."}=".number_format($total_bill,2)."</br>".$inv_str."</br>Pickup Type:".$row['pickup_type']."</br>Order from:</br>";
							  $msg_str.=$merchant_name['name'].",</br><a target='_blank' href='$m_map'>".$merchant_name['google_map']."</a> ,</br> Mobile - ".$row['mobile_number']."</br>  To: ".$row['user_name']."</br>,".$row['user_mobile'].$otp_str."<a href='$u_map' target='_blank'>".$user_location."</a></br> Order Detail:";
							}
							else
							{
							  $msg_str.="</br>"."Collect:{".$total."+".$incsst."(SST)+".$row['order_extra_charge']."+".$row['deliver_tax_amount']."+".$row['special_delivery_amount'].")-(".$row['wallet_paid_amount']."(WALLET)-".$row['membership_discount']."-".$row['coupon_discount']."}=".number_format($total_bill,2)."</br>".$inv_str."</br>Pickup Type:".$row['pickup_type']."</br> Order from:</br>";
							  $msg_str.="*".$merchant_name['name'].",</br><a target='_blank' href='$m_map'>".$merchant_name['google_map']."</a>,</br> Mobile - ".$row['mobile_number']."</br>  To: ".$row['user_name']." </br>,".$row['user_mobile'].$otp_str."<a target='_blank' href='$u_map'>".$user_location."</a></br> Order Detail:";
			  				}  
						foreach ($product_ids as $key )
						{  
							if(is_numeric($key))
							{
								$product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id ='".$key."'"));
								// $msg_str.="<b>".$product['product_name'].',qty-'.$quantity_ids[$i].',Unit Price '.$product['product_price'].'</b><br>';
								$msg_str.="<b>".$product['product_name']."(".$product['category'].')('.$product['product_type'].'),qty-'.$quantity_ids[$i].',Unit Price:'.$product['product_price'].'</b></br>';    
							}
							else
							{
							   $msg_str.=$key.'<br>';
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
						   
							$i++;
						}
						$msg_str.="Total qty : ".$total_qun." --End--</br>";
						
		   
					}
					
				
				
				?>
				<h2 style="font-weight:bold;font-size:16px;">Order assign to you</h2>
			  <p><?php echo $msg_str; ?></p>
			 
			 <input type="hidden" value="<?php echo $msg_str; ?>" id="myInput">
				<?php } else { echo "<h3 style='font-size:16px;font-weight:bold;'>Order Already Assign to other rider</h3>";}}}}  ?>

    </div>
	<div class="modal fade" id="orderdetailmodel" role="dialog" style="margin-top:12%;"> 
							<div class="modal-dialog">
							<!-- Modal content-->		
							<div class="modal-content">	
							<div class="modal-header">	
							<button type="button" class="close" data-dismiss="modal">&times;</button>						
							<h4 class="modal-title">Order Detail <?php echo $job_assign; ?></h4>	
							</div>					
							<form id ="orderdetailform" method="POST">		
							<div class="modal-body" style="padding-bottom:0px;">
                             <div class="form-group">
								  <label for="usr">Access Key:</label>
								  <input type="password" name="r_code" class="form-control" id="usr">
								</div>
								<div class="form-group">
								  
								  <input type="submit" class="form-control btn btn-primary" name="access" value="Access">
								</div>

												
							</form>						
							</div>						
							</div>						
							</div>
	</div>   

    
  </div>
</div>
<script>
$(document).ready(function(){
  // alert(3);
  var rider_id="<?php echo $rider_id; ?>";
  if(rider_id=='')
 $("#orderdetailmodel").modal("show"); 
});  
function myFunction() {
  /* Get the text field */
  var copyText = document.getElementById("myInput");

  /* Select the text field */
  copyText.select();
  copyText.setSelectionRange(0, 99999); /*For mobile devices*/

  /* Copy the text inside the text field */
  document.execCommand("copy");

  /* Alert the copied text */
  // alert("Copied the text: " + copyText.value);
}
</script>
</body>
</html>
