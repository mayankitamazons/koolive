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
	#echo $query_r;
	$result = mysqli_fetch_assoc(mysqli_query($conn, $query_r));

	if(count($result) == 0){
		$showadmin_msg = 'yes';
	}
}

$rider_links = "http://188.166.187.218/riders.php?rider=".$_GET['rider'];
?>
<!DOCTYPE html>
<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">
<head><meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
	<link rel="stylesheet" href="./css/font-awesome.min.css">
	<link rel="stylesheet" href="./css/ordercss.css">
	<script src="./Dashboard_files/jquery.min.js.download"></script>

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
			<main class="main-wrapper clearfix main-rider-page" style="min-height: 522px;">
				<div class="row" id="main-content" style="padding-top:25px">
					<div class="col-md-12 well">



						<!-- New design start---->
						<?php if($showadmin_msg == 'yes'){?>
							<div class="m-0 order_n_div slick-slide slick-cloned slick-active rider_details">
								<div class="n_order_body ">
									<div class="col-lg-12 text-center alert alert-danger m-0">
										<p class="mb-0">Somethings wrong !! Please contact admin for more details.</p>
									</div>	
								</div>
							</div>
						<?php }else{?>

							<?php 
							$chk_rider_count = mysqli_query($conn,"select * from order_list where rider_info =".$riderid." and rider_accept_id = 0");
							$rider_count = mysqli_num_rows($chk_rider_count);
							?>
							<div class="order_n_div slick-slide slick-cloned slick-active rider_details">
								<div class="order_head_line">
									<div>
										<span class="n_order_no">Riders: <?php echo $result['r_name'] ."(+". $result['r_mobile_number'].")";?></span>
										<?php if($rider_count != 0){?>
											<a href="<?php echo $site_url."/riders.php?rider=".$_GET['rider']?>" class="new_order">New Order!!</a>
										<?php }?>
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
								
								$query_od = "select ol.id as order_id,ol.update_merchnt_details,ol.rider_bank_amount,ol.rider_cash_amount,ol.rider_m_price_diff,ol.rider_reason_dif,ol.rider_m_receipt_img,ol.rider_m_food_img,ol.invoice_no,ol.rider_od_time_count,ol.rider_complete_order,ol.vendor_comission,ol.admin_bank_price,ol.admin_cash_price,ol.rider_arrive_shop,ol.rider_od_assign_time,ol.rider_od_accept_time,ol.rider_arrive_shop,ol.rider_complete_time,ol.rider_accept_id,rider_info,ol.product_code, cust.name as customer_name, merchnt.name as merchnt_name,ol.location as cust_address, cust.mobile_number as cust_contact_name, merchnt.google_map as merchnt_address,merchnt.mobile_number as merchnt_contact_name, merchnt.merchant_code as merchant_code,merchnt.sst_rate, merchnt.latitude as mer_latitude, merchnt.longitude as mer_longitude, ol.order_lat as cust_latitude, ol.order_lng as cust_longitude, ol.created_on, ol.location as shipping_address,ol.invoice_no,ol.wallet,ol.product_id,ol.quantity,ol.amount,ol.remark,ol.deliver_tax_amount,ol.speed_delivery_amount,ol.order_extra_charge,ol.special_delivery_amount,membership_discount,ol.coupon_discount,ol.wallet_paid_amount from order_list as ol Inner join users as cust ON cust.id = ol.user_id Inner join users as merchnt ON merchnt.id = ol.merchant_id WHERE rider_info =".$riderid."  order by ol.rider_od_assign_time desc";

								//and rider_complete_order != 1
								//echo $query_od;


								$ordersFetch = mysqli_query($conn,$query_od);

								

								?>

								<div class="n_order_body ">
									<div class="">
										<div class="accordion m-0" id="accordionExample">

											<?php 
											$i = 1;
											while($ordersData = mysqli_fetch_assoc($ordersFetch)){
												
												
												/*Time check*/
												//rider_complete_time
												//rider_arrive_shop
												//rider_od_accept_time
												//rider_od_assign_time	
												$rider_od_assign_time = $ordersData['rider_od_assign_time'];
												$assign_date = new DateTime($rider_od_assign_time);
												$now = new DateTime(Date('Y-m-d H:i:s'));
												$interval = $assign_date->diff($now);
												$years = $interval->y;
												$months = $interval->m;
												$days = $interval->d;
												$hours = $interval->h;
												$minutes = $interval->i;
												
												$total_seconds = 0;
												//$first_timer = $minutes.":".$interval->s;
												
												if($interval->h == 0){
													$first_timer = "00:".$interval->i.":".$interval->s;
												}else{
													$first_timer = $interval->h.":".$interval->i.":".$interval->s;
												}
												$total_seconds = ($interval->h * 3600) + ($interval->i * 60) + $interval->s;
												//echo $total_seconds;
												//echo '<br/>';
												
												//echo $interval->h."===".$interval->i."===".$interval->s."<br/>";
												
												$total_seconds2 = round($total_seconds /3600);
												$total_seconds3 = round($total_seconds /60);
												//echo $total_seconds2."==".$total_seconds3;
												
												$rider_od_accept_time = $ordersData['rider_od_accept_time'];
												$accept_date = new DateTime($rider_od_accept_time);
												$arrive_date = new DateTime($ordersData['rider_arrive_shop']);
												$interval_accept = $accept_date->diff($arrive_date); // difference between accept time to reach shop
												$arrival_time = '';
												
												if($interval_accept->h == 0){
													$arrival_time = "00:".$interval_accept->i.":".$interval_accept->s;
												}else{
													$arrival_time = $interval_accept->h.":".$interval_accept->i.":".$interval_accept->s;
												}
												
												$rider_complete_time = $ordersData['rider_complete_time'];
												$complete_date = new DateTime($rider_complete_time);
												$ac_date = new DateTime($rider_od_accept_time);
												$interval_complete = $complete_date->diff($ac_date); // difference between accept time to complete order
												$complete_time = '';
												if($interval_complete->h == 0){
													$complete_time = "00:".$interval_complete->i.":".$interval_complete->s;
												}else{
													$complete_time = $interval_complete->h.":".$interval_complete->i.":".$interval_complete->s;
												}
												
												
												
												
												/*End time Check*/

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
						<script>
						
						jQuery(document).ready(function(){
	countdown( "ten-countdown", '3', 0 );
	
	
	var hrsLabel = jQuery("#hr<?php echo $ordersData['order_id'];?>");
	var minutesLabel = jQuery("#minutes<?php echo $ordersData['order_id'];?>");
	var secondsLabel = jQuery("#seconds<?php echo $ordersData['order_id'];?>");
	var totalSeconds = '<?php echo $total_seconds;?>';//0;
	
	console.log(totalSeconds);
	
	<?php /*if($ordersData['rider_accept_id'] != '0'){?>
	var totalSeconds = 0;
	
<?php }*/?>

setInterval(setTime, 1000);

function setTime() {
	++totalSeconds;

	var d = Number(totalSeconds);

	var h = Math.floor(d / 3600);
	var m = Math.floor(d % 3600 / 60);
	var s = Math.floor(d % 3600 % 60);

	var hDisplay = h > 0 ? h + (h == 1 ? "" : "") : "00";
	var mDisplay = m > 0 ? m + (m == 1 ? "" : "") : "00";
	var sDisplay = s > 0 ? s + (s == 1 ? "" : "") : "00";



	hrsLabel.html(hDisplay);
	secondsLabel.html(sDisplay);
	minutesLabel.html(mDisplay);
	jQuery(".minute_countdown<?php echo $ordersData['order_id'];?>").val(hDisplay+":"+mDisplay+":"+sDisplay);
}
						});
						</script>
						<div class="card">
							<div class="card-header" id="heading<?php echo $i ;?>">
								<button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapse<?php echo $i ;?>" aria-expanded="true" aria-controls="collapse<?php echo $i ;?>">
									<div>
										<span class="n_no"> <?php echo $i;?></span>
										<span class="n_order_no">ORDER NO: #<?php echo $ordersData['invoice_no'];?>  &nbsp;&nbsp;|&nbsp;&nbsp;<?php echo date('d M, Y',strtotime($ordersData['created_on']));?></span>
										
									</div>
									
									<div class="n_order_total">
										<div class="mr-3 mt-2">
											<b>Order Total:</b> RM.<?php echo $Final_price;?> 
											<?php if($complete_time!= ''){?>
										<br/>
										<?php if($ordersData['rider_complete_time'] != '0000-00-00 00:00:00'){?>
										<div class="od_compl_time">
											<b>Order Completed in:</b> <?php echo $complete_time;?>
										</div>
										<?php }?>
									<?php }?>
										</div>
										
										
										
										
										<span class="n_accept_btn" style="cursor:auto">

											<?php 
									//echo $ordersData['rider_accept_id'] ."==". $riderid;
											if($ordersData['rider_accept_id'] == $riderid){?>
												<span class="btn btn-sm btn-primary disabled" order_id="<?php echo $ordersData['order_id'];?>">Accepted</span>
												<br/>
												<?php echo $ordersData['rider_od_time_count'];?>

											<?php }else{?>
												<span class="btn btn-sm btn-primary accept_order" order_id="<?php echo $ordersData['order_id'];?>">Accept Order</span>
												<br/>
												<?php if($ordersData['rider_accept_id'] == '0'){?>
													<span id="hr<?php echo $ordersData['order_id'];?>" class="hrs_cls">00</span>:<span id="minutes<?php echo $ordersData['order_id'];?>" class="minutes_cls">00</span>:<span id="seconds<?php echo $ordersData['order_id'];?>" class="seconds_cls">00</span>
												<?php }?>

												<!--<span class="ten-countdown"></span>-->
												<input type="hidden" name="minute_countdown" id="minute_countdown<?php echo $ordersData['order_id'];?>" class="minute_countdown" value=""/>
											<?php }?>


											

										</span>


									</div>
									
								</button>
							</div>

							<?php 
							$cls_show = '';
							if($_GET['order_id'] == $ordersData['order_id'] ){
								$cls_show = 'show';
							}?>
							<div id="collapse<?php echo $i ;?>" class="collapse <?php echo $cls_show;?>" aria-labelledby="headingOne" data-parent="#accordionExample">
								<div class="card-body ">

									<div class="n_order_mer">
										<div class="order_n_div slick-slide slick-cloned slick-active">

											<div class="order_head_line">
												<?php //Step 1: Reach the Merchant's Shop and click on "Arrive At Shop" button.	?>

												<b><?php echo ucfirst($ordersData['merchnt_name']);?></b>
												<br/>
												
												<a href="tel:+<?php echo $ordersData['merchnt_contact_name'];?>" style="color:black">
													<b><i class="fa fa-phone" aria-hidden="true"></i></b> +<?php echo $ordersData['merchnt_contact_name'];?>
												</a>
												<br/>
												
												<div class="count-wrap">
													<?php if($ordersData['rider_arrive_shop'] == '0000-00-00 00:00:00'){?>
														<span class="n_accept_btn btn btn-sm btn-primary reach_shop" order_id="<?php echo $ordersData['order_id'];?>" style="cursor:auto">Arrive At Shop</span>
														<?php /*if($ordersData['rider_accept_id'] != '0'){?>
															<span id="hr" class="hrs_cls">00</span>:<span id="minutes" class="minutes_cls">00</span>:<span id="seconds" class="seconds_cls">00</span>
														<?php }*/?>

													<?php }else{?>
														<span class="n_accept_btn btn btn-sm btn-primary disabled" style="cursor:auto">Arrived</span>
														<br  class="d-none d-md-block"/>
														<?php echo $arrival_time;?>
													<?php }?>
												</div>

											</div>

											<div class="n_order_body">
												
												<p id="mer_<?php echo $ordersData['order_id'];?>">
													<b><i class="fa fa-map-marker" aria-hidden="true"></i></b> <?php echo $ordersData['merchnt_address'];?>
													
													<a class="copy-text mer_<?php echo $ordersData['order_id'];?>" onclick="copyToMerAdd('#mer_<?php echo $ordersData['order_id'];?>','mer_<?php echo $ordersData['order_id'];?>')"> Copy </a>
													
												</p>
												
												


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
													<span class="btn btn-sm btn-primary view-map" style="cursor:pointer">View Map</span>
												</a>
											</div>
										</div>
									</div>

									<div class="n_order_mer ">
										<div class="order_n_div slick-slide slick-cloned slick-active">
											<div class="order_head_line">
												<span>Check Order Details.</span>
												<span class="expand expnd_<?php echo $ordersData['order_id']; ?>" orderid ='<?php echo $ordersData['order_id']; ?>'>Expand</span>
												
											</div>
											<div class="n_order_body submit_details_<?php echo $ordersData['order_id']; ?>" style="display:none">
												<span> <b>Total Product:</b> <?php echo count($product_qtys);?> </span>
												<span> <b>Amount Paid:</b> RM.<?php echo $merchnt_price;?>  </span>

												<?php //echo $ordersData['order_id']; ?>
												<input type="hidden" name="final_order_amount" id="final_order_amount_<?php echo $ordersData['order_id']; ?>" value="<?php echo $merchnt_price;?>"/>
												<?php 

									//if($ordersData['rider_m_wallet'] != ''){?>
										<form method="post" id="image-form_<?php echo $ordersData['order_id']; ?>" class="image-form" orderid ='<?php echo $ordersData['order_id']; ?>' enctype="multipart/form-data" onSubmit="return false;">
											<div class="d-flex flex-wrap">
												

												<div class="col-lg-4">
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
												<label for="paid_amount" class="col-lg-4 col-form-label">Bank Amount</label>
												<div class="col-lg-8">
													<input type="text" class="form-control amunt_total Bank_amount" orderid ='<?php echo $ordersData['order_id']; ?>'  id="bank_amount_<?php echo $ordersData['order_id']; ?>" name="bank_amount" placeholder="Bank Amount" value="<?php echo $ordersData['rider_bank_amount'];?>">
												</div>
											</div>

											<div class="form-group row">
												<label for="paid_amount" class="col-lg-4 col-form-label">Cash Amount</label>
												<div class="col-lg-8">
													<input type="text" class="form-control cash_amount amunt_total" orderid ='<?php echo $ordersData['order_id']; ?>'  id="cash_amount_<?php echo $ordersData['order_id']; ?>" name="cash_amount" placeholder="Cash Amount" value="<?php echo $ordersData['rider_cash_amount'];?>">
												</div>
											</div>


											<div class="form-group row" style="display:none">
												<label for="paid_amount" class="col-lg-3 col-form-label">Total AMount</label>
												<div class="col-lg-9">
													<input type="hidden" class="form-control paid_amount" orderid ='<?php echo $ordersData['order_id']; ?>'  id="paid_amount_<?php echo $ordersData['order_id']; ?>" name="paid_amount" placeholder="Paid Amount" value="<?php echo $Final_price;?>">
												</div>
											</div>
											
											
										</div>
										<div class="col-lg-4">
											
											<div class="form-group row">
												<label for="receipt_photo" class="col-lg-4 col-form-label">Receipt
													<?php if($ordersData['rider_m_receipt_img']!= ''){?>
														<a href="<?php echo $site_url.'/upload/order_receipt/'.$ordersData['rider_m_receipt_img']?>"><i class="fa fa-eye"></i></a>
													<?php }?>
												</label>
												<div class="col-lg-8">
													<input type="file" class="form-control" name="receipt_photo" id="receipt_photo_<?php echo $ordersData['order_id']; ?>">
													
												</div>
											</div>
											
											<div class="form-group row">
												<label for="food_photo" class="col-lg-4 col-form-label">Food Photo
													<?php if($ordersData['rider_m_food_img']!= ''){?>
														<a href="<?php echo $site_url.'/upload/order_food/'.$ordersData['rider_m_food_img']?>"><i class="fa fa-eye"></i></a>
													<?php }?>
												</label>
												<div class="col-lg-8">
													<input type="file" class="form-control" name="food_photo" id="food_photo_<?php echo $ordersData['order_id']; ?>" >
												</div>
											</div>
											
											
											
										</div>
										<div class="col-lg-4">
											<div class="form-group row">
												<label for="price_diff" class="col-lg-6 col-form-label">Price Differnce</label>
												<div class="col-lg-6">
													<input type="hidden" class="form-control" name="price_diff" id="price_diff_<?php echo $ordersData['order_id']; ?>" placeholder="Price Difference" readonly value="<?php echo $ordersData['rider_m_price_diff'];?>">
													<span class="price_difff" id="price_difftext_<?php echo $ordersData['order_id']; ?>" >
														<?php 
														if($ordersData['rider_m_price_diff'] != ''){
															echo $ordersData['rider_m_price_diff'];
														}else{
															echo '0.00';
														}?>
													</span>
												</div>
											</div>
											
											<div class="form-group row">
												<label for="reason_diff" class="col-lg-6 col-form-label">Reason Of Differnce</label>
												<div class="col-lg-6">
													<input type="text" class="form-control" name="reason_diff" id="reason_diff_<?php echo $ordersData['order_id']; ?>" placeholder="Reason" value="<?php echo $ordersData['rider_reason_dif'];?>">
												</div>
											</div>
										</div>


									</div>
									<div class="form-group row">
										<div class="col-lg-12 text-center">
											<button type="submit" class="btn btn-primary mprice_submit" orderid ='<?php echo $ordersData['order_id']; ?>'>Submit</button>
										</div>
									</div>
								</form>
								<?php //}?>



							</div>


						</div>
					</div>

					<div class="n_order_mer">
						<div class="order_n_div slick-slide slick-cloned slick-active">

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
							<div class="order_head_line">
								<?php if($ordersData['customer_name'] != ''){?>
									<b><?php echo $ordersData['customer_name'];?></b>
									<br/>
								<?php }?>
								<a href="tel:+<?php echo $ordersData['cust_contact_name'];?>" style="color:black">

									<i class="fa fa-phone" aria-hidden="true"></i></b> +<?php echo $ordersData['cust_contact_name'];?>
								</a>
								<br/> 
								<a href="<?php echo $msg_custlink;?>" target="_blank">
									<span class="btn btn-sm btn-primary view-map" style="cursor:pointer">View Map</span>
								</a>
								
								
							</div>
							


							<div class="n_order_body">

								<?php if($ordersData['cust_address'] != ''){?>
									<p class="copy_cust" id="cust_<?php echo $ordersData['order_id'];?>" order_id="<?php echo $ordersData['order_id'];?>">
										<b><i class="fa fa-map-marker" aria-hidden="true"></i></b> <?php echo $ordersData['cust_address'];?>
										
										<a class="copy-text cust_<?php echo $ordersData['order_id'];?>" onclick="copyToCustAdd('#cust_<?php echo $ordersData['order_id'];?>','cust_<?php echo $ordersData['order_id'];?>')"> Copy </a>
									</p>
									
								<?php }?>
								<br/>
								<b>Price: </b><?php echo $Final_price;?> &nbsp; |&nbsp; <b>Mode:</b> <?php echo $ordersData['wallet'];?>
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
								&nbsp;|&nbsp;
								<b>Cash Price: </b><?php echo "RM ".@number_format($admin_cash_price,2);?>

								<div class="count-wrap text-left py-3">
									<?php if($ordersData['rider_complete_order'] == 0){?>
										<span class="n_accept_btn btn btn-sm btn-primary complete_order float-none" order_id="<?php echo $ordersData['order_id'];?>"  style="cursor:auto">Complete Order</span>
									<?php }else{?>
										<span class="n_accept_btn btn btn-sm btn-primary disabled" >Completed</span>
										<br class="d-none d-md-block"/>
										<?php echo $complete_time;?>
									<?php }?>
								</div>
								
								
<input type="hidden" name="check_fst_step" id="check_fst_step<?php echo $ordersData['order_id'];?>" value="<?php echo $ordersData['rider_accept_id'];?>"/>

<?php if($ordersData['rider_arrive_shop'] == '0000-00-00 00:00:00'){?>
<input type="hidden" name="check_snd_step" id="check_snd_step<?php echo $ordersData['order_id'];?>" value="0"/>
<?php }else{?>
<input type="hidden" name="check_snd_step" id="check_snd_step<?php echo $ordersData['order_id'];?>" value="1"/>
<?php }?>

<?php if($ordersData['update_merchnt_details'] == '0000-00-00 00:00:00'){?>
<input type="hidden" name="check_thrd_step" id="check_thrd_step<?php echo $ordersData['order_id'];?>" value="0"/>
<?php }else{?>
<input type="hidden" name="check_thrd_step" id="check_thrd_step<?php echo $ordersData['order_id'];?>" value="1"/>
<?php }?>


								
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
		
		
		
		$(".switch").change(function(e) {
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
	//countdown( "ten-countdown", '3', 0 );
	
	
	var hrsLabel = $(".hrs_cls");
	var minutesLabel = $(".minutes_cls");
	var secondsLabel = $(".seconds_cls");
	var totalSeconds = '<?php echo $total_seconds;?>';//0;
	
	console.log(totalSeconds);
	
	

//setInterval(setTime, 1000);

function setTime1() {
	++totalSeconds;

	var d = Number(totalSeconds);

	var h = Math.floor(d / 3600);
	var m = Math.floor(d % 3600 / 60);
	var s = Math.floor(d % 3600 % 60);

	var hDisplay = h > 0 ? h + (h == 1 ? "" : "") : "00";
	var mDisplay = m > 0 ? m + (m == 1 ? "" : "") : "00";
	var sDisplay = s > 0 ? s + (s == 1 ? "" : "") : "00";



	hrsLabel.html(hDisplay);
	secondsLabel.html(sDisplay);
	minutesLabel.html(mDisplay);
	$(".minute_countdown").val(hDisplay+":"+mDisplay+":"+sDisplay);
}

function pad(val) {
	var valString = val + "";
	if (valString.length < 2) {
		return "0" + valString;
	} else {
		return valString;
	}
}



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
				var red_url = window.location.href+"&order_id="+order_id;
				window.location.href = red_url;
				//show$(location).attr('href', 'http://stackoverflow.com')

				/*$(".accept_order").addClass('disabled');
				$(".accept_order").html('Accepted');
				$(".disabled").removeClass('accept_order');*/
				
			}
		});	
	}
});



$(".reach_shop").click(function(){
	var order_id = $(this).attr('order_id');
	
	var check_fst_step = $("#check_fst_step"+order_id).val();
	var riderid = $("#hidden_rider_id").val();
	if(check_fst_step == 0){
		alert('Please accept the order first');
		return false;
	}else{
		var cnfrmDelete = confirm("Are You reached the Merchant's shop?");
		if(cnfrmDelete==true){
			$.ajax({
				url:'riderajax.php',
				method:'POST',
				data:{data:'reach_shop',order_id:order_id,riderid:riderid},
				success:function(res){
					//location.reload(true);
					var red_url = '<?php echo $rider_links;?>'+'&order_id='+order_id;
					window.location.href = red_url;
				}
			});	
		}
	}
	
});


$(".complete_order").click(function(){
	var order_id = $(this).attr('order_id');
	var riderid = $("#hidden_rider_id").val();
	var check_fst_step = $("#check_fst_step"+order_id).val();
	var check_thrd_step = $("#check_thrd_step"+order_id).val();
	
	if(check_thrd_step == 0){
		alert('Please complete the second step.');
		return false;
	}else{
		var cnfrmDelete = confirm("Are You sure to complete order?");
		if(cnfrmDelete==true){
			$.ajax({
				url:'riderajax.php',
				method:'POST',
				data:{data:'complete_order',order_id:order_id,riderid:riderid},
				success:function(res){
					//location.reload(true);
					var red_url = '<?php echo $rider_links;?>';
					window.location.href = red_url;
				}
			});	
		}
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
				$("#price_difftext_"+orderid).html(diff_final);
				
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
				$("#price_difftext_"+orderid).html(diff_final);
			}else{
				$("#price_diff_"+orderid).val('0.00');
				$("#price_difftext_"+orderid).html('0.00');
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
			
			var check_snd_step = $("#check_snd_step"+orderid).val();
	
			if(check_snd_step == 0){
				alert('Please reach the shop and click on "Arrive at shop"');
				return false;
			}
			
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
				  	//location.reload(true);
					  //$("#image-form_"+orderid).hide();
					  $(".mprice_submit").html('Updated');
					  var red_url = window.location.href+"&order_id="+orderid;
					  window.location.href = red_url;
					},
					error: function(data) {

					}
				});
			}
			
		});



});


$(document).ready(function(){
	$(".expand").on("click", function () {
		var orderid = $(this).attr('orderid');

		var txt = $(".submit_details_"+orderid).is(':visible') ? 'Expand' : 'Hide';
		$(".expand").text(txt);
		$('.submit_details_'+orderid).slideToggle();
	});

});

function copyToCustAdd(element,clsn) {
	var $temp = $("<input>");
	$("body").append($temp);
	$temp.val($(element).text()).select();
	document.execCommand("copy");
	$temp.remove();
	$("."+clsn).html('copied');
}


function copyToMerAdd(element,clsn) {
	var $temp = $("<input>");
	$("body").append($temp);
	$temp.val($(element).text()).select();
	document.execCommand("copy");
	$temp.remove();
	$("."+clsn).html('copied');
}


</script>	