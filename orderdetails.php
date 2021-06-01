<?php error_reporting(0);
   include("config.php");?>
   <?php 
   function ceiling($number, $significance = 1)
	{
		return ( is_numeric($number) && is_numeric($significance) ) ? (ceil(round($number/$significance))*$significance) : false;
	}
	?>
<!DOCTYPE html>
<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">
<head><meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
	<link rel="stylesheet" href="./css/font-awesome.min.css">
	<link rel="stylesheet" href="./css/ordercss.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" >
	
	<?php include("includes1/headorder.php"); ?>   
	<?php // include("mpush.php"); ?>
	<link rel="manifest" id="my-manifest-placeholder">
	<meta name="theme-color" content="#317EFB"/>
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
			<?php include("includes1/sidebar.php"); ?>
			<!-- /.site-sidebar -->
			<main class="main-wrapper clearfix  orderlistdetail-wrapper" style="min-height: 522px;">
				<div class="row" id="main-content" style="padding-top:25px">
					<div class="well od-row-wrapper">
						
						<h3>Order Details</h3>  
						<?php
						$dt = new DateTime();
						$today =  $dt->format('Y-m-d');
						$today_order = explode(" ",$created_new);
						if( $today == $today_order[0] && $status1 == 1 ){ ?>
							<div style="display: none;">
								<audio autoplay> <source src="<?php echo $site_url;?>/images/sound/doorbell-1.mp3" type="audio/mpeg"> Your browser does not support the audio tag. </audio>
								</div>
							<?php } ?>
							
							
							<?php 
							if($_SESSION['login'])
							{
							   $user_id=$_SESSION['login'];
							}
							if($_GET['orderid'] && $_GET['orderid'] != ''){
								$wh_od = " order_list.id = ".$_GET['orderid'];
							}
							$query="SELECT  order_list.id as order_id,order_list.order_extra_charge as od_extra_charge,order_list.*, sections.name as section_name,m.id as merchant_id,m.name as merchant_name,m.sst_rate as m_sst_rate,m.mobile_number as merchant_mobile_number,m.*,u.id as user_id,u.* FROM order_list left join 
							 sections on order_list.section_type = sections.id inner join users as m on m.id=order_list.merchant_id  left 
							join users as u on u.mobile_number=order_list.user_mobile 
							 WHERE ".$wh_od." ORDER BY `created_on` DESC ";
							// echo $query;
							$total_rows = mysqli_query($conn,$query);
							$fetchData = mysqli_fetch_assoc($total_rows);
	 
							
							 /* Start riders array */
							 $riders_query = "select * from tbl_riders where r_status = 1";
							 $ridersFetch = mysqli_query($conn,$riders_query);
							 $ridersArray = array();
							 while($rider_rows = mysqli_fetch_array($ridersFetch)){
								 $ridersArray[$rider_rows['r_id']]['name'] = $rider_rows['r_name'];
								 $ridersArray[$rider_rows['r_id']]['r_mobile_number'] = $rider_rows['r_mobile_number'];
								 $ridersArray[$rider_rows['r_id']]['r_info'] = $rider_rows['r_info'];
								 $ridersArray[$rider_rows['r_id']]['r_image'] = $rider_rows['r_image'];
								 $ridersArray[$rider_rows['r_id']]['r_vehicle_number'] = $rider_rows['r_vehicle_number'];
								 $ridersArray[$rider_rows['r_id']]['r_live_location'] = $rider_rows['r_live_location'];
							 }
							 /* END riders array */
							 $wallet = $fetchData['wallet'];
							 if($wallet=="myr_bal")
								$wal_label="MYR WALLET";
							else if($wallet=="inr_bal")
							$wal_label="KOO COIN";
							 else if($wallet=="usd_bal")
							$wal_label="CF WALLET";
							else if($wallet=="cash")
								$wal_label="CASH";
							else $wal_label=$wallet;
							
							$product_ids = explode(",",$fetchData['product_id']);
							$quantity_ids = explode(",",$fetchData['quantity']);
							$product_code = explode(",",$fetchData['product_code']);
							$remark_ids = explode("|",$fetchData['remark']);
							$c = array_combine($product_ids, $quantity_ids);
							$amount_val = explode(",",$fetchData['amount']);
							$amount_data = array_combine($product_ids, $amount_val);
							$total_data = array_combine($quantity_ids, $amount_val);
                   
						
						
							
							?>
							<div class="form-row">
								<div class="col-lg-6">
									<div class="order_n_div slick-slide slick-cloned slick-active order_details ">
										<div class="order_head_line">
											<span class="n_order_no">ORDER NO: #<?php echo $fetchData['invoice_no'];?></span>
											<span class="n_order_type"><b>Payment Type:</b> <?php echo $wal_label;?></span>
										</div>
										<div class="n_order_body form-row">
											<div class="n_order_mer col-lg-12">
												<div class="n_mer_name">
													<span class="fnt13"><b>Tel.No.</b>: +<?php echo $fetchData['user_mobile'];?> </span>
													<br>
													<span class="fnt13"><b>Address</b>: <?php echo $fetchData['location'];?></span>
													<br>
													<span class="fnt13"><b>Table</b>: <?php echo $fetchData['table_type'];?></span> &nbsp;&nbsp;| &nbsp;&nbsp;
													<span class="fnt13"><b>Section</b>: <?php echo $fetchData['name'];?></span>
													<br>
													<span class="fnt13"><b>Remark</b>:<?php echo $fetchData['remark_extra'];?></span>
													<br>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="order_n_div slick-slide slick-cloned slick-active order_details ">
										<div class="order_head_line">
											<span class="n_order_no w-100">Merchant Details</span>
										</div>
										<div class="n_order_body form-row">
											<div class="n_order_mer col-lg-12">
												<div class="n_mer_name">
													<span><b><?php echo $fetchData['merchant_name'];?></b></span>
													<span class="m_hotline"><a href="https://chat.whatsapp.com/J4wcS4riADaBBrvY60c8f3" target="_blank"><img src="https://www.koofamilies.com/images/whatapp.png" style="max-width:22px;"></a></span>
													<br/>
													<span class="fnt13"><b>Code</b>: <?php echo $fetchData['merchant_code'];?> </span>
													<br/>
													<span class="fnt13"><b>Tel.No.</b>: +<?php echo $fetchData['merchant_mobile_number'];?> </span>
													<br>
													<!--<span class="fnt13"><b>Address</b>: <?php echo $fetchData['remark_extra'];?></span>
													<br>-->
												</div>
											</div>
										</div>
									</div>
								</div>								
							</div>
							<div class="form-row">
							<?php 
							//echo $fetchData['rider_info']."====".$fetchData['cancel_order']."===>".$fetchData['rider_complete_order'];
							if($fetchData['cancel_order'] != 1 && $fetchData['rider_complete_order'] != 1 && $fetchData['rider_info'] != 0){?>
								<div class="col-md-6">
									<div class="order_n_div slick-slide slick-cloned slick-active order_details ">
										<div class="order_head_line">
											<span class="n_order_no">Rider Information</span>
										</div>
										<div class="n_order_body form-row">
											<div class="n_order_mer col-lg-12">
												<div class="n_mer_name">
														
														
														
							<div class="media rider-media align-items-center flex-column flex-sm-row">
													
							<?php 	if($fetchData['rider_info'] != '0'){
										$rider_name = $ridersArray[$fetchData['rider_info']]['name'];
										$r_mobile_number = $ridersArray[$fetchData['rider_info']]['r_mobile_number'];
										$r_live_location = $ridersArray[$fetchData['rider_info']]['r_live_location'];
										$r_vehicle_number = $ridersArray[$fetchData['rider_info']]['r_vehicle_number'];
										$r_image = $ridersArray[$fetchData['rider_info']]['r_image'];
									}
									if($fetchData['s_rider_option'] != 0){
										$s_label1 = 'We are still desperately trying to contact the merchant,<br/> once the order is confirmed with merchant, we will inform you. Meanwhile, <br/>our rider is on his way to merchant shop checking.';
										$s_label2 = 'Rider Listings';
										$s_label3 = 'Shop closed, Cancel!';
										$s_label4 = 'Merchant is preparing your foods. Please wait. Rider is waiting';
										if($_SESSION["langfile"] == 'chinese'){
											$s_label1 = '我们正在尽最大努力联系商家以确认你的订单。我们的司机已经出发到商家地点以确认商家是否营业！';
											$s_label2 = '骑手列表';
											$s_label3 = '商家休息，订单取消！';
											$s_label4 = '商家正在准备食物，食物完成后，我们的司机就会把美食送上';
										}
										if($fetchData['s_rider_option'] == 1){
											$rs_label = $s_label1;
											echo '<p style="color:red">'.$s_label1.'</p>';
										}else if($fetchData['s_rider_option'] == 2){
											$rs_label = $s_label2;
											//echo '<p>'.$s_label2.'</p>';
										}else if($fetchData['s_rider_option'] == 3){
											$rs_label = $s_label3;
											echo '<p style="color:red">'.$s_label3.'</p>';
										}else if($fetchData['s_rider_option'] == 4){
											$rs_label = $s_label4;
											echo '<p style="color:red">'.$s_label4.'</p>';
										}
									}		
										$hours_2 = 0;
										if($fetchData['rider_complete_time']!= '0000-00-00 00:00:00'){
											$rider_od_complete_time = $fetchData['rider_complete_time'];
											$complete_time = new DateTime($rider_od_complete_time);
											$now2 = new DateTime(Date('Y-m-d H:i:s'));
											$interval_2 = $complete_time->diff($now2);
											$hours_2 = $interval_2->h;
											$minutes_2 = $interval_2->i;
										}
										if($fetchData['rider_complete_order'] != 1){
												if($hours_2 < 1){
													if($fetchData['s_rider_option'] == 2){
														$rider_img = $site_url."/admin_panel/uploads/riders/".$r_image;?>
													
														<?php if($rider_img == ''){ $rider_img = 'https://dummyimage.com/100x100/ddd/000'; }?>
													<img  src="<?php echo $rider_img;?>" alt="Image" height="100px" width="100px" class="mb-3 mb-sm-0  mr-sm-3">
													<div class="media-body">
														<p><strong>Rider Name : </strong> <?php echo $rider_name;?></p>
														<p><strong>Contact Number :</strong> <?php echo $r_mobile_number;?> </p>
														<p><strong>Vehicle Number : </strong> <?php echo $r_vehicle_number;?></p>
														<p><strong>Tracking :  </strong> 
														<?php if($fetchData['rider_arrive_shop'] != '0000-00-00 00:00:00' || $fetchData['rider_complete_order'] == 1){?>
														<a href="<?php echo $r_live_location;?>" class="mr-2"><i class="fa fa-map-marker" aria-hidden="true"></i></a> Track Location
														<?php }else{?>
														<a href="#" class="mr-2"><i class="fa fa-map-marker" aria-hidden="true"></i></a>Attempting to connect rider's Location.... Try 10 minutes later. 
														<?php }?>
														</p>
													</div>
													
												<?php }
											 }
										}?>
							</div>		
							
						<!-- END Riderinfo -->
													
												</div>
											</div>
										</div>
									</div>
								</div>
								<?php }?>
								
								<div class="col-md-6">
									<div class="order_n_div slick-slide slick-cloned slick-active order_details ">
										<div class="order_head_line">
											<span class="n_order_no w-100">Delivery Status</span>
										</div>
										<div class="n_order_body form-row">
											<div class="n_order_mer col-lg-12">
												<div class="n_mer_name">
													<div class=" ">
													<?php
                        	$n_status='';  
							$s_cls = '';
							$s_cls1 = '';
							$s_cls2 = '';
							$s_cls3 = '';
                                if($fetchData['status'] == 0)
								{
									$sta =$language['pending'];
									$s_color="red";
									$n_status=1;
									$s_cls = 'active';
								}
                                else if($fetchData['status'] == 1) 
								{
									
									$sta =$language['done_in_delivery'];
									$s_color="green";
									$n_status=4;
									$s_cls = 'active';
								}
								else if($fetchData['status'] == 4 || $fetchData['status']==5) 
								{
									$sta =$language['done_in_delivery'];
									$s_color="green";
									$s_cls = 'active';
								}
                                else 
								{
									$n_status=1;
									$sta =$language['accepted'];
									// $sta = "Accepted";
									$s_color="";
									$s_cls = 'active';
								}
								
								if($fetchData['rider_complete_order'] == 1){
									$n_status='';
									$sta ='completed';
									// $sta = "Accepted";
									$s_color="green";
									$s_cls3 = 'active';
								}	
								if($fetchData['cancel_order'] == 1){
									$n_status='';
									$sta ='Cancelled';
									// $sta = "Accepted";
									$s_color="red";
									$b_color = "border-color:red";
								}
								
								
						?>
										<?php if($fetchData['cancel_order'] == 1){?>
											<label class= "btn btn-primary status" data-id="<?php echo $fetchData['order_id']; ?>" style="cursor:pointer;width:150px;background-color:<?php echo $s_color;?>;<?php echo $b_color;?>"> <?php echo $sta; ?></label>
											<?php }else{?>
											<div class="n_breadcrumb flat d-flex">
												<a href="#" class="<?php echo $s_cls;?>">Pending</a>
												<a href="#" class="<?php if($fetchData['status'] == 1){ echo $s_cls;};?>">Accepted</a>
												<a href="#" class="<?php if($fetchData['status'] == 4){ echo $s_cls;};?>">In delivery</a>
												<a href="#" class="<?php if($fetchData['rider_complete_order'] == 1){ echo $s_cls;};?>">Completed</a>
											</div>
											<?php }?>


													</div>
												</div>
											</div>
										</div>
									</div>
								</div>								
							</div>

							<!-- New design start---->
							
							
							<!-- NEW DESIGN END ----->
							
							<div class="clearfix"></div>
							<!-- PRoduct Details-->
							<div class="form-row">
								<div class="col-md-12">
									<div class="m-0 slick-slide slick-cloned slick-active n_products_details order_details n_orderdetails">
										<div class="order_head_line">
											<span class="n_order_no w-100">Product Details</span>
										</div>
										<div class="orderdetail-table table-responsive p-3">

											
											<table class="table">
												<thead>
													<tr>
														<th scope="col" class="first-head" >#</th>
														<th scope="col" class="secnd-head">Products</th>
														<th scope="col" class="third-head">Price</th>
													</tr>
													</thead>
													<tbody>
													
													<?php 
											
													if($fetchData['varient_type']){
														$v_str=$fetchData['varient_type'];
														$v_array = explode("|",$v_str);
													}
													echo $v_str;
													print_R($v_array); 
													
													$i = 1;
													$j = 0;
												
													foreach ($product_ids as $key ){
														$product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id ='".$key."'"));
                                
													?>
													
														<tr>
															<th class="mobile-hide first-head" data-label="#" scope="row"><?php echo $i;?></th>
															<td data-label="Products" class="secnd-head"><?php echo $product['product_name'];?>
																<br/>
																<p class="moretext" id="moretext_<?php echo $key;?>">
																	<span><strong>Code : </strong> <?php echo $product_code[$j];?></span><br/>
																	<span><strong>Qty : </strong> <?php echo $quantity_ids[$j];?></span><br/>
																	<span><strong>Varients : </strong></span>
																	<?php 
																	//foreach($v_array[$j] as $vr)
																	//{
																		
																		if($v_array[$j])
																		{
																			$v_match=$v_array[$j];
																			$v_match = ltrim($v_match, ',');
																			$sub_rows = mysqli_query($conn, "SELECT * FROM sub_products WHERE id  in ($v_match)");
																			while ($srow=mysqli_fetch_assoc($sub_rows)){
																				echo $srow['name'];
																				echo "&nbsp;&nbsp;";
																			}
																		}
																		 else
																		 {
																			 echo "</br>";
																		 }
																	
																	//}
																	?>
																	<br/>
																	<span><strong>Remark : </strong> 
																	<?php echo $remark_ids[$j];?>
																	</span>
																</p>
																<a class="moreless-button moreless-button_<?php echo $key;?>" href="javascript:void(0)" product_id ="<?php echo $key;?>">Read more</a>

															</td>
														<!-- <td data-label="Remark">Remark</td>
															<td data-label="Price">12.21</td> -->
															<td data-label="Final Price" class="third-head">
															
															<?php 
															$p_total = 0;
															if( $quantity_ids[$j]) {
																$p_total =  $p_total + ($quantity_ids[$j] *$amount_val[$j] );
															} 
															echo  @number_format($p_total, 2);
															?>
															
															</td>
														</tr>
														<?php 
														
														$i++;
														$j++;}
														?>
														<?php 
														$total = 0;
														foreach ($amount_val as $key => $value){
															if( $quantity_ids[$key] && $value ) {
																$total =  $total + ($quantity_ids[$key] *$value );
															} 
														}
														//echo  @number_format($total, 2);
								$sstper=$fetchData['sst_rate'];
														$incsst = ($sstper / 100) * $total;
															$incsst=@number_format($incsst, 2);
															$incsst=ceiling($incsst,0.05);
															 $incsst=@number_format($incsst, 2);
															$g_total=@number_format($total+$incsst, 2);
															$territory_price_array = explode("|",$fetchData['territory_price']);
															$terr_id = $territory_price_array[0];
															$territory_price = $territory_price_array[1];
														 ?>
							 
														<?php
									$total_delivery_charge= '0.00';
									if($fetchData['order_extra_charge'] || $fetchData['special_delivery_amount'] || $fetchData['speed_delivery_amount'])
									{
										if($fetchData['special_delivery_amount']>0 && $fetchData['speed_delivery_amount']>0)
										{
											$total_delivery_charge=@number_format($fetchData['order_extra_charge'],2)."+ ".number_format($fetchData['special_delivery_amount'],2)."(Chiness Delivery)"."+ ".number_format($fetchData['speed_delivery_amount'],2)."(Speed Delivery)";
										}else if($fetchData['special_delivery_amount']>0){
											$total_delivery_charge=@number_format($fetchData['order_extra_charge'],2)."+ ".number_format($fetchData['special_delivery_amount'],2)."(Chiness Delivery)";
										}else if($fetchData['speed_delivery_amount']>0){
											$total_delivery_charge=@number_format($fetchData['order_extra_charge'],2)."+ ".number_format($fetchData['speed_delivery_amount'],2)."(Speed Delivery)";
										}
										else
										{
											$total_delivery_charge=@number_format($fetchData['order_extra_charge'],2);
										}
									}
									?>
									
														<tr class="table-borderless">	
															<td class="mobile-hide"  colspan="2"><span class="costright">Subtotal</span></td>
															<td  data-label="Subtotal"><?php  echo $g_total;?></td>
														</tr>

														<tr class="table-borderless">	
															<td class="mobile-hide"  colspan="2"><span class="costright">Service Fee <?php echo $sstper;?>%</span></td>
															<td  data-label="Service Fee 6%"><?php echo $incsst; ?></td>
														</tr>

														<tr class="table-borderless">	
															<td  class="mobile-hide" colspan="2"><span class="costright">Delivery Tax</span></td>
															<td  data-label="Delivery Tax"><?php  echo @number_format($fetchData['deliver_tax_amount'],2); ?></td>
														</tr>

														<tr class="table-borderless">	
															<td  class="mobile-hide" colspan="2"><span class="costright">Delivery Charges</span></td>
															<td  data-label="Delivery Charges"><?php echo $total_delivery_charge;?></td>
														</tr>

<?php if($fetchData['membership_discount']){?>
														<tr class="table-borderless">	
															<td  class="mobile-hide" colspan="2"><span class="costright">Membership Discount</span></td>
															<td  data-label="Membership Discount"><?php  echo @number_format($fetchData['membership_discount'],2); ?></td>
														</tr>
<?php }?>

<?php if($fetchData['coupon_discount']){?>
														<tr class="table-borderless">	
															<td class="mobile-hide"  colspan="2"><span class="costright">Coupon Discount</span></td>
															<td  data-label="Coupon Discount"><?php echo @number_format($fetchData['coupon_discount'],2); ?></td>
														</tr>
<?php }?>
<?php 
									
									$g_final=@number_format(($g_total+$fetchData['order_extra_charge']+$fetchData['deliver_tax_amount']+$fetchData['special_delivery_amount']+$fetchData['speed_delivery_amount'])-($fetchData['membership_discount']+$fetchData['coupon_discount']),2);?>
									
														<tr class="table-borderless">	
															<td  class="mobile-hide" colspan="2"><span class="costright">Grand Total</span></td>
															<td  data-label="Grand Total"><?php echo $g_final;?></td>
														</tr>

<?php if($ordersData['wallet_paid_amount']){?>
														<tr class="table-borderless">	
															<td  class="mobile-hide" colspan="2"><span class="costright">Paid By Wallet</span></td>
															<td  data-label="Paid By Wallet"><?php  echo @number_format($fetchData['wallet_paid_amount'],2); ?></td>
														</tr>
<?php }?>

														<tr class="table table-borderless">	
															<td  class="mobile-hide" colspan="2"><span class="costright">Balance Payment</span></td>
															<td  data-label="Balance Payment"><?php echo @number_format(($g_total+$fetchData['od_extra_charge']+$territory_price+$fetchData['deliver_tax_amount']+$fetchData['special_delivery_amount']+$fetchData['speed_delivery_amount'])-($fetchData['wallet_paid_amount']+$fetchData['membership_discount']+$fetchData['coupon_discount']), 2); ?></td>
														</tr>



													</tbody>
												</table>
											</div>


										</div>
									</div>
								</div>


								<!-- END Product Details -->



								<!-- /.widget-body badge -->
							</div>
							<!-- /.widget-bg -->
							<!-- /.content-wrapper -->
							<?php include("includes1/commonfooter.php"); ?>




							<a href="https://chat.whatsapp.com/LdvomJRqXoIG6aXqsf5PgX" target="_blank"><img src ="images/iconfinder_support_416400.png" style="width:75px;height:75px;position: fixed;left:15px;bottom: 70px;z-index:999;"></a>
							<link rel="stylesheet" href="css/fancybox.css" type="text/css" media="screen" />
							<script type="text/javascript" src="js/fancybox.js"></script>

							<script >

								$('.moreless-button').click(function() {
									var product_id  = $(this).attr('product_id');
									$('#moretext_'+product_id).slideToggle();
									if ($('.moreless-button_'+product_id).text() == "Read more") {
										$(this).text("Read less")
									} else {
										$(this).text("Read more")
									}
								});
							</script>

						</body>
						</html>
