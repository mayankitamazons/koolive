<?php
include("config.php");

function ceiling($number, $significance = 1)
{
	return ( is_numeric($number) && is_numeric($significance) ) ? (ceil(round($number/$significance))*$significance) : false;
}
   //ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
$riderid = '';
$showadmin_msg = 'no';
if($_GET['rider'] && $_GET['rider']== ''){
	$showadmin_msg = 'yes';
}else{
	$riderid = base64_decode($_GET['rider']);
	$query_r = "SELECT * FROM `tbl_riders` where r_status = 1 and r_id = ".$riderid;
	$result = mysqli_fetch_assoc(mysqli_query($conn, $query_r));

	if(count($result) == 0){
		$showadmin_msg = 'yes';
	}
}
?>
<!DOCTYPE html>
<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">
<head><meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
	<link rel="stylesheet" href="./css/font-awesome.min.css">
	<link rel="stylesheet" href="./css/ordercss.css">
	<?php include("includes1/headorder.php"); ?>   

	<style>
		.navbar-nav, .sidebar-toggle{
			display:none !important;
		}
		.sidebar-expand .main-wrapper {
			margin-left: 0px !important;
		}
	</style>
</head>
<body class="header-light sidebar-dark sidebar-expand pace-done">
	<div class="pace  pace-inactive">
		<div class="pace-progress" data-progress-text="100%" data-progress="99" style="transform: translate3d(100%, 0px, 0px);">
			<div class="pace-progress-inner"></div>
		</div>
		<div class="pace-activity"></div>
	</div>
	<div id="wrapper" class="wrapper">
		<!-- HEADER & TOP NAVIGATION -->
		<?php include("includes1/navbar.php"); ?>
		<!-- /.navbar -->
		<div class="content-wrapper">
			<!-- SIDEBAR -->
			<?php //include("includes1/sidebar.php"); ?>
			<!-- /.site-sidebar -->
			<main class="main-wrapper clearfix" style="min-height: 522px;">
				<div class="row" id="main-content" style="padding-top:25px">
					<div class="well">



						<!-- New design start---->
						<?php if($showadmin_msg == 'yes'){?>
							<div class="order_n_div slick-slide slick-cloned slick-active rider_details">
								<div class="n_order_body row">
									<div class="col-lg-12">
										<p>Somethings wrong !! Please contact admin for more details.</p>
									</div>	
								</div>
							</div>
						<?php }else{?>

							<div class="order_n_div slick-slide slick-cloned slick-active rider_details">
								<div class="order_head_line">
									<div>
										<span class="n_order_no">Riders: <?php echo $result['r_name'] ."(+". $result['r_mobile_number'].")";?></span>
										<span class="new_order">New Order!!</span>
									</div>

									<span class="on_off">
										<?php 
										$clsname = '';
										$subclas = "";
										if($result['r_online'] == 1){
											$clsname = 'checkbox-checked';
											$subclas = "checked='checked'";
										}?>
										<label class="switch <?php echo $clsname;?>">
											<input type="checkbox" id="togBtn" <?php echo $subclas;?> >
											<div class="slider round">
												<span class="on on_off_switch" status="on" >ON</span>
												<span class="off on_off_switch" status="off">OFF</span>
											</div>
										</label>
									</span>

									<input type="hidden" name="hidden_rider_id" id="hidden_rider_id" value="<?php echo $riderid;?>"/>


								</div>


								<?php 
								$query_od = "select ol.id as order_id,ol.rider_complete_order,ol.vendor_comission,ol.admin_bank_price,ol.admin_cash_price,ol.rider_arrive_shop,ol.rider_od_assign_time,ol.rider_accept_id,rider_info,ol.product_code, cust.name as customer_name, merchnt.name as merchnt_name,cust.address as cust_address, cust.mobile_number as cust_contact_name, merchnt.google_map as merchnt_address,merchnt.mobile_number as merchnt_contact_name, merchnt.merchant_code as merchant_code,merchnt.sst_rate, merchnt.latitude as mer_latitude, merchnt.longitude as mer_longitude, cust.latitude as cust_latitude, cust.longitude as cust_longitude, ol.created_on, ol.location as shipping_address,ol.invoice_no,ol.wallet,ol.product_id,ol.quantity,ol.amount,ol.remark,ol.deliver_tax_amount,ol.speed_delivery_amount,ol.order_extra_charge,ol.special_delivery_amount,membership_discount,ol.coupon_discount,ol.wallet_paid_amount from order_list as ol Inner join users as cust ON cust.id = ol.user_id Inner join users as merchnt ON merchnt.id = ol.merchant_id WHERE rider_info =".$riderid."  order by ol.id desc";

		//and rider_complete_order != 1
		#echo $query_od;


								$ordersFetch = mysqli_query($conn,$query_od);


								?>

								<div class="n_order_body row">
									<div class="col-lg-12">
										<div class="accordion" id="accordionExample">

											<?php 
											$i = 1;
											while($ordersData = mysqli_fetch_assoc($ordersFetch)){

												$product_qtys = explode(",",$ordersData['quantity']);
												$proudct_amounts = explode(",",$ordersData['amount']);

												$product_sum = 0;
												foreach($product_qtys as $key => $value){
							//echo $key."==".$value;
							//echo '<br/>';
													$product_sum += $value * $proudct_amounts[$key];
												}


												$sstper=$ordersData['sst_rate'];
						$total = $product_sum;//$ordersData['amount'];
						if($sstper>0){
							$incsst = ($sstper / 100) * $total;
							$incsst=@number_format($incsst, 2);
							$incsst=@number_format($incsst, 2);
							$incsst=ceiling($incsst,0.05);
							$g_total=@number_format($total+$incsst, 2);
						} else { 
							$g_total=$total;
						}
						
						$total_delivery_charge= '0.00';
						if($ordersData['order_extra_charge'] || $ordersData['special_delivery_amount'] || $ordersData['speed_delivery_amount'])
						{
							if($ordersData['special_delivery_amount']>0 && $ordersData['speed_delivery_amount']>0)
							{
								$total_delivery_charge=@number_format($ordersData['order_extra_charge'],2)."+ ".number_format($ordersData['special_delivery_amount'],2)."(Chiness Delivery)"."+ ".number_format($ordersData['speed_delivery_amount'],2)."(Speed Delivery)";
							}else if($ordersData['special_delivery_amount']>0){
								$total_delivery_charge=@number_format($ordersData['order_extra_charge'],2)."+ ".number_format($ordersData['special_delivery_amount'],2)."(Chiness Delivery)";
							}else if($ordersData['speed_delivery_amount']>0){
								$total_delivery_charge=@number_format($ordersData['order_extra_charge'],2)."+ ".number_format($ordersData['speed_delivery_amount'],2)."(Speed Delivery)";
							}
							else
							{
								$total_delivery_charge=@number_format($ordersData['order_extra_charge'],2);
							}
						}
						
						$Final_price =@number_format(($g_total+$ordersData['order_extra_charge']+$ordersData['deliver_tax_amount']+$ordersData['special_delivery_amount']+$ordersData['speed_delivery_amount'])-($ordersData['wallet_paid_amount']+$ordersData['membership_discount']+$ordersData['coupon_discount']), 2); // collect_customer_price
						
						
						$v_comisssion = $ordersData['vendor_comission'];
						$v_comisssion=number_format($v_comisssion,2);
						//echo "===".$total."<<>>>==".$v_comisssion;
						if($v_comisssion)
						{
							$merchnt_price = number_format($total-$v_comisssion,2);
						}
						else
						{
							$merchnt_price = number_format($total,2);
						}


						
						

						/*			
						$rider_od_assign_time = $ordersData['rider_od_assign_time'];
						$currentDate = date('Y-m-d H:i:s');
						$date1 = strtotime($rider_od_assign_time); 
						$date2 = strtotime($currentDate); 

						$diff = abs($date2 - $date1); 
						$hours = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24)/ (60*60)); 
						$minutes = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60)/ 60); 
						$seconds = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60 - $minutes*60)); 
						//printf("%d years, %d months, %d days, %d hours, " . "%d minutes, %d seconds", $years, $months, $days, $hours, $minutes, $seconds); 
						$minute_countdown = $hours.":".$minutes.":".$seconds;			
						*/			
						
						?>
						<div class="card">
							<div class="card-header" id="heading<?php echo $i ;?>">
								<button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapse<?php echo $i ;?>" aria-expanded="true" aria-controls="collapse<?php echo $i ;?>">
									<div>
										<span class="n_no"> <?php echo $i;?></span>
										<span class="n_order_no">ORDER NO: #<?php echo $ordersData['order_id'];?>  &nbsp;&nbsp;|&nbsp;&nbsp;<?php echo date('d M, Y',strtotime($ordersData['created_on']));?></span>

									</div>
									
									<div class="n_order_total"><b>Order Total:</b> RM.<?php echo $Final_price;?> 
									<span class="n_accept_btn" style="cursor:auto">

										<?php 
									//echo $ordersData['rider_accept_id'] ."==". $riderid;
										if($ordersData['rider_accept_id'] == $riderid){?>
											<span class="btn btn-sm btn-primary" order_id="<?php echo $ordersData['order_id'];?>">Accepted</span>

										<?php }else{?>
											<span class="btn btn-sm btn-primary accept_order" order_id="<?php echo $ordersData['order_id'];?>">Accept Order</span>
											<br/>
											<span class="ten-countdown"></span>
											<input type="hidden" name="minute_countdown" id="minute_countdown" class="minute_countdown" value=""/>
										<?php }?>


									</span>


								</div>
							</button>
						</div>

						<div id="collapse<?php echo $i ;?>" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
							<div class="card-body row">

								<div class="n_order_mer col-lg-12">
									<div class="order_n_div slick-slide slick-cloned slick-active">
										<div class="order_head_line">Step 1: Reach the Merchant's Shop and click on "Arrive At Shop" button.

											<?php if($ordersData['rider_arrive_shop'] == '0000-00-00 00:00:00'){?>
												<span class="n_accept_btn btn btn-sm btn-primary reach_shop" order_id="<?php echo $ordersData['order_id'];?>" style="cursor:auto">Arrive At Shop</span>
											<?php }else{?>
												<span class="n_accept_btn btn btn-sm btn-primary" style="cursor:auto">Arrived</span>
											<?php }?>

										</div>

										<div class="n_order_body">
											<b><?php echo ucfirst($ordersData['merchnt_name']);?></b>
											<br/>
											<b>Tel.No.:</b> +<?php echo $ordersData['merchnt_contact_name'];?>
											<br/>
											<b>Address:</b> <?php echo $ordersData['merchnt_address'];?>
											<?php 
											$latlng = $ordersData['mer_latitude'].",".$ordersData['mer_longitude'];
											if($ordersData['mer_latitude'] && $ordersData['mer_longitude'])
											{
												$msg_link = "https://www.google.com/maps/place/".urlencode($ordersData['merchnt_address']).",/@".$latlng.",17z/data=!3m1!4b1!4m5!3m4!1s0x396dc85b42c5e27f:0x22577f7c977bcb36!8m2!3d".$latlng."</br>";
											}else
											{
												$msg_link = "http://maps.google.com/maps?q=".urlencode($ordersData['merchnt_address'])."</br>";
											}	
											?>

											<a href="<?php echo $msg_link;?>" target="_blank">
												<span class="btn btn-sm btn-primary" style="cursor:pointer">View Map</span>
											</a>
										</div>
									</div>
								</div>

								<div class="n_order_mer col-lg-12">
									<div class="order_n_div slick-slide slick-cloned slick-active">
										<div class="order_head_line">
											<span>Step 2: Check Order Details and submit proof as Below.</span>
										</div>
										<div class="n_order_body">
											<span> <b>Total Product:</b> <?php echo count($product_qtys);?> </span>
											<span> <b>Amount Paid:</b> RM.<?php echo $merchnt_price;?>  </span>

											<?php //echo $ordersData['order_id']; ?>
											<input type="hidden" name="final_order_amount" id="final_order_amount_<?php echo $ordersData['order_id']; ?>" value="<?php echo $merchnt_price;?>"/>
											<?php 

									//if($ordersData['rider_m_wallet'] != ''){?>
										<form method="post" id="image-form_<?php echo $ordersData['order_id']; ?>" class="image-form row" orderid ='<?php echo $ordersData['order_id']; ?>' enctype="multipart/form-data" onSubmit="return false;">
											<div class="col-lg-6">
										<!--<div class="form-group row">
											<label for="inputEmail3" class="col-sm-2 col-form-label">Mode</label>
												<div class="form-check form-check-inline">
												  <input class="form-check-input" type="radio" name="payment_mode" id="cash_mode" value="cash">
												  <label class="form-check-label" for="cash_mode">Cash</label>
												</div>
												<div class="form-check form-check-inline">
												  <input class="form-check-input" type="radio" name="payment_mode" id="bank_mode" value="internet_banking">
												  <label class="form-check-label" for="bank_mode">Internet Banking</label>
												</div>
											</div>-->

											<div class="form-group row">
												<label for="paid_amount" class="col-lg-3 col-form-label">Bank Amount</label>
												<div class="col-lg-9">
													<input type="text" class="form-control amunt_total Bank_amount" orderid ='<?php echo $ordersData['order_id']; ?>'  id="bank_amount_<?php echo $ordersData['order_id']; ?>" name="bank_amount" placeholder="Bank Amount" value="<?php //echo $Final_price;?>">
												</div>
											</div>

											<div class="form-group row">
												<label for="paid_amount" class="col-lg-3 col-form-label">Cash Amount</label>
												<div class="col-lg-9">
													<input type="text" class="form-control cash_amount amunt_total" orderid ='<?php echo $ordersData['order_id']; ?>'  id="cash_amount_<?php echo $ordersData['order_id']; ?>" name="cash_amount" placeholder="Cash Amount" value="<?php //echo $Final_price;?>">
												</div>
											</div>





											<div class="form-group row">
												<label for="paid_amount" class="col-lg-3 col-form-label">Total AMount</label>
												<div class="col-lg-9">
													<input type="text" class="form-control paid_amount" orderid ='<?php echo $ordersData['order_id']; ?>'  id="paid_amount_<?php echo $ordersData['order_id']; ?>" name="paid_amount" placeholder="Paid Amount" value="<?php //echo $Final_price;?>">
												</div>
											</div>

											<div class="form-group row">
												<label for="receipt_photo" class="col-lg-3 col-form-label">Receipt</label>
												<div class="col-lg-9">
													<input type="file" class="form-control" name="receipt_photo" id="receipt_photo_<?php echo $ordersData['order_id']; ?>">
												</div>
											</div>
											
											
										</div>
										<div class="col-lg-6">
											
											<div class="form-group row">
												<label for="food_photo" class="col-lg-3 col-form-label">Food Photo</label>
												<div class="col-lg-9">
													<input type="file" class="form-control" name="food_photo" id="food_photo_<?php echo $ordersData['order_id']; ?>" >
												</div>
											</div>
											
											<div class="form-group row">
												<label for="price_diff" class="col-lg-3 col-form-label">Price Differnce</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" name="price_diff" id="price_diff_<?php echo $ordersData['order_id']; ?>" placeholder="Price Difference" readonly value="0.00">
												</div>
											</div>
											
											<div class="form-group row">
												<label for="reason_diff" class="col-lg-3 col-form-label">Reason Of Differnce</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" name="reason_diff" id="reason_diff_<?php echo $ordersData['order_id']; ?>" placeholder="Reason">
												</div>
											</div>
											
											
										</div>
										<div class="form-group row">
											<div class="col-lg-12">
												<button type="submit" class="btn btn-primary mprice_submit" orderid ='<?php echo $ordersData['order_id']; ?>'>Submit</button>
											</div>
										</div>
									</form>
									<?php //}?>


									
								</div>
								
								

							</div>
						</div>
						
						<div class="n_order_mer col-lg-12">
							<div class="order_n_div slick-slide slick-cloned slick-active">
								<div class="order_head_line">#3 Once food at Customer's location, Don't forgot to complete order. 

									<?php if($ordersData['rider_complete_order'] == 0){?>
										<span class="n_accept_btn btn btn-sm btn-primary complete_order" order_id="<?php echo $ordersData['order_id'];?>"  style="cursor:auto">Complete Order</span>
									<?php }else{?>
										<span class="n_accept_btn btn btn-sm btn-primary" >Completed</span>
									<?php }?>
								</div>
								<?php 
								$latlng_cust = $ordersData['cust_latitude'].",".$ordersData['cust_longitude'];
								if($ordersData['cust_latitude'] && $ordersData['cust_longitude'])
								{
									$msg_custlink = "https://www.google.com/maps/place/".urlencode($ordersData['cust_address']).",/@".$latlng_cust.",17z/data=!3m1!4b1!4m5!3m4!1s0x396dc85b42c5e27f:0x22577f7c977bcb36!8m2!3d".$latlng_cust."</br>";
								}else
								{
									$msg_custlink = "http://maps.google.com/maps?q=".urlencode($ordersData['cust_address'])."</br>";
								}	
								?>


								<div class="n_order_body">
									<b><?php echo $ordersData['customer_name'];?></b>
									<br/>
									Tel.No.:</b> +<?php echo $ordersData['cust_contact_name'];?>
									<br/>
									<b>Address:</b> <?php echo $ordersData['cust_address'];?>
									
									<b>Order Price: </b><?php echo $Final_price;?>
									<br/>
									<?php 
									$admin_bank_price = '0.00';
									$admin_cash_price = '0.00';
									if($ordersData['admin_bank_price'] != ''){
										$admin_bank_price = $ordersData['admin_bank_price'];
									}if($ordersData['admin_cash_price'] != ''){
										$admin_cash_price = $ordersData['admin_cash_price'];
									}?>
									<b>Bank Price: </b><?php echo "RM ".@number_format($admin_bank_price,2);?>
									<br/>
									<b>Cash Price: </b><?php echo "RM ".@number_format($admin_cash_price,2);?>
									
									<a href="<?php echo $msg_custlink;?>" target="_blank">
										<span class="btn btn-sm btn-primary" style="cursor:pointer">View Map</span>
									</a>
									
								</div>
								
									<!--<div class="text_div">
										<input type="textbox" name="amount_receive_cust" id="amount_receive_cust"/>
										<input type="submit" value="Amount Received"/>
									</div>-->
								</div>
							</div>

							
						</div>
					</div>
				</div>
				<?php $i++;}?>
			</div>

		</div>
	</div>

</div>
<?php }?>







<!-- NEW DESIGN END ----->


</div>
<!-- /.widget-bg -->
<!-- /.content-wrapper -->
<?php include("includes1/commonfooter.php"); ?>
<link rel="stylesheet" href="css/fancybox.css" type="text/css" media="screen" />
<script type="text/javascript" src="js/fancybox.js"></script>

</body>
</html>

<script>
	$(document).ready(function(){
		$(".switch").click(function(){
			var class_chk = $(this).hasClass('checkbox-checked');
			if(class_chk == true){
			var btn_status = 'offline';//console.log('offline');
		}else{
			//console.log('online');
			var btn_status = 'online';
		}
		var riderid = $("#hidden_rider_id").val();
		$.ajax({  
			type: "POST",  
			url: "riderajax.php",  
			data: {
				type: 'onlinefunction',
				btn_status: btn_status,
				riderid: riderid,
			},
			success: function(value) { 
			}
		});
		
		
	});
	});

	function countdown( elementName, minutes, seconds )
	{
		var element, endTime, hours, mins, msLeft, time;

		function twoDigits( n )
		{
			return (n <= 9 ? "0" + n : n);
		}
		var x = 1;
		function updateTimer()
		{
			msLeft = endTime - (+new Date);
			if ( msLeft < 1000 ) {
            //element.innerHTML = "0:00";
            $(".ten-countdown").html('0:00');
            $(".minute_countdown").val('0:00');

        } else {
        	time = new Date( msLeft );
        	hours = time.getUTCHours();
        	mins = time.getUTCMinutes();
            //element.innerHTML = (hours ? hours + ':' + twoDigits( mins ) : mins) + ':' + twoDigits( time.getUTCSeconds() );
            var time_data = (hours ? hours + ':' + twoDigits( mins ) : mins) + ':' + twoDigits( time.getUTCSeconds() );
            $(".ten-countdown").html(time_data);
            $(".minute_countdown").val(time_data);
            setTimeout( updateTimer, time.getUTCMilliseconds() + 500 );
        }
        x++;
    }

    element = document.getElementById( elementName );
    endTime = (+new Date) + 1000 * (60*minutes + seconds) + 500;
    updateTimer();
}

$(document).ready(function(){
	countdown( "ten-countdown", '3', 0 );
});


$(".accept_order").click(function(){
	var order_id = $(this).attr('order_id');
	var riderid = $("#hidden_rider_id").val();
	var minute_countdown = $(".minute_countdown").val();
	var cnfrmDelete = confirm("Are You Sure want Accept this order ?");
	if(cnfrmDelete==true){
		$.ajax({
			url:'riderajax.php',
			method:'POST',
			data:{data:'accept_order',order_id:order_id,riderid:riderid,minute_countdown:minute_countdown},
			success:function(res){
				location.reload(true);
			}
		});	
	}
});



$(".reach_shop").click(function(){
	var order_id = $(this).attr('order_id');
	var riderid = $("#hidden_rider_id").val();
	var cnfrmDelete = confirm("Are You reached the Merchant's shop?");
	if(cnfrmDelete==true){
		$.ajax({
			url:'riderajax.php',
			method:'POST',
			data:{data:'reach_shop',order_id:order_id,riderid:riderid},
			success:function(res){
				location.reload(true);
			}
		});	
	}
});


$(".complete_order").click(function(){
	var order_id = $(this).attr('order_id');
	var riderid = $("#hidden_rider_id").val();
	var cnfrmDelete = confirm("Are You sure to complete order?");
	if(cnfrmDelete==true){
		$.ajax({
			url:'riderajax.php',
			method:'POST',
			data:{data:'complete_order',order_id:order_id,riderid:riderid},
			success:function(res){
				location.reload(true);
			}
		});	
	}
});


$(document).ready(function(e) {
	
	$(".paid_amount").change(function(){
		var orderid = $(this).attr('orderid');  
		var final_order_amount = $("#final_order_amount_"+orderid).val();
		var paid_amount = $("#paid_amount_"+orderid).val();
		if(final_order_amount != paid_amount ){
			var diff = parseFloat(final_order_amount) - parseFloat(paid_amount);
			var diff_final = diff.toFixed(2);
				//console.log(diff+"===="+diff_final);
				var diff_final = diff_final.replace("-", "");
				$("#price_diff_"+orderid).val(diff_final);
			}
		});


	$(".amunt_total").change(function(){
		var orderid = $(this).attr('orderid');  
		var final_order_amount = $("#final_order_amount_"+orderid).val();
			//var paid_amount = $("#paid_amount_"+orderid).val();
			var cash_amount  = $("#cash_amount_"+orderid).val();
			var bank_amount = $("#bank_amount_"+orderid).val();
			if(cash_amount == ''){
				cash_amount = '0';
			}
			if(bank_amount == ''){
				bank_amount = '0';
			}
			var paid_amount = parseFloat(cash_amount) + parseFloat(bank_amount);
			var paid_amount = paid_amount.toFixed(2)
			$("#paid_amount_"+orderid).val(paid_amount);
			
			console.log(final_order_amount+"======="+paid_amount);
			if(final_order_amount != paid_amount ){
				console.log("----");
				var diff = parseFloat(final_order_amount) - parseFloat(paid_amount);
				var diff_final = diff.toFixed(2);
				//console.log(diff+"===="+diff_final);
				var diff_final = diff_final.replace("-", "");
				$("#price_diff_"+orderid).val(diff_final);
			}else{
				$("#price_diff_"+orderid).val('0.00');
			}
		});

	$(".image-form").on("submit", function() {
			//console.log('####');return false;
			var orderid = $(this).attr('orderid');  
			var mode = $('input[name="payment_mode"]:checked').val();
			
			var bank_amount  = $("#bank_amount_"+orderid).val();
			var cash_amount  = $("#cash_amount_"+orderid).val();
			var paid_amount = $("#paid_amount_"+orderid).val();
			var receipt_photo =$("#receipt_photo_"+orderid).val();
			var food_photo =$("#food_photo_"+orderid).val();
			var price_diff =$("#price_diff_"+orderid).val();
			var reason_diff = $("#reason_diff_"+orderid).val();
			var final_order_amount = $("#final_order_amount_"+orderid).val();
			
			//return false;
			var formData = new FormData(this);
			formData.append('data', 'merchnt_update');
			formData.append('orderid', orderid);
			formData.append('mode', mode);
			formData.append('paid_amount', paid_amount);
			formData.append('price_diff', price_diff);
			formData.append('reason_diff', reason_diff);
			formData.append('bank_amount', bank_amount);
			formData.append('cash_amount', cash_amount);
			
			$(".mode").css('border','');
			$("#bank_amount_"+orderid).css('border','');
			$("#cash_amount_"+orderid).css('border','');
			$("#receipt_photo_"+orderid).css('border','');
			$("#food_photo_"+orderid).css('border','');
			
			
			/*if( mode == 'undefined'){
				$(".mode").css('border','1px solid red');
				return false;
			}else*/ 
			if( bank_amount == ''){
				$("#bank_amount_"+orderid).css('border','1px solid red');
				return false;
			}else if( cash_amount == ''){
				$("#cash_amount_"+orderid).css('border','1px solid red');
				return false;
			}else if( receipt_photo == ''){
				$("#receipt_photo_"+orderid).css('border','1px solid red');
				return false;
			}else if( food_photo == ''){
				$("#food_photo_"+orderid).css('border','1px solid red');
				return false;
			}else{
				$.ajax({
					type: "POST",
					url: "riderajax.php",
					data: formData,
				  contentType: false, // The content type used when sending data to the server.
				  cache: false, // To unable request pages to be cached
				  processData: false, // To send DOMDocument or non processed data file it is set to false
				  success: function(data) {
				  	location.reload(true);
					  //$("#image-form_"+orderid).hide();
					},
					error: function(data) {

					}
				});
			}
			
		});



});




</script>