<?php error_reporting(0);?>
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
					<div class="well">
						
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
							<div class="form-row">
								<div class="col-lg-6">
									<div class="order_n_div slick-slide slick-cloned slick-active order_details ">
										<div class="order_head_line">
											<span class="n_order_no">ORDER NO: #23449</span>
											<span class="n_order_type"><b>Payment Type:</b> Cash</span>
										</div>

										<div class="n_order_body row">
											<div class="n_order_mer col-lg-12">

												<div class="n_mer_name">

													<span class="fnt13"><b>Tel.No.</b>: +60127633363 </span>
													<br>
													<span class="fnt13"><b>Address</b>: 181, Jalan Kenanga 29/4, Bandar Indahpura, Kulai, Johor, Malaysia</span>
													<br>
													<span class="fnt13"><b>Table</b>: A</span> &nbsp;&nbsp;| &nbsp;&nbsp;
													<span class="fnt13"><b>Section</b>: New</span>
													<br>
													<span class="fnt13"><b>Remark</b>:Make it faster</span>


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

										<div class="n_order_body row">
											<div class="n_order_mer col-lg-12">

												<div class="n_mer_name">
													<span><b>士林台湾小食 Shilin Taiwan Street Snacks(Kulai)</b></span>
													<span class="m_hotline"><a href="https://chat.whatsapp.com/J4wcS4riADaBBrvY60c8f3" target="_blank"><img src="https://www.koofamilies.com/images/whatapp.png" style="max-width:22px;"></a></span>
													<br/>
													<span class="fnt13"><b>Code</b>: KOO_00022 </span>
													<br/>
													<span class="fnt13"><b>Tel.No.</b>: +60127633363 </span>
													<br>
													<span class="fnt13"><b>Address</b>: 181, Jalan Kenanga 29/4, Bandar Indahpura, Kulai, Johor, Malaysia</span>
													<br>
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
														<th scope="col">#</th>
														<th scope="col">Products</th>
														<!-- <th scope="col">Remark</th>
															<th scope="col">Price</th> -->
															<th scope="col">Final Price</th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<th class="mobile-hide" data-label="#" scope="row">1</th>
															<td data-label="Products">double sweet chilli fish burger large mcvalue meal

																<br/>
																<span>Code: 512</span><br/>
																<span>Qty: 2</span><br/>
																<span>Varients: Drink - Large Coca-Cola</span>
																<span>Remark: Remark</span>

															</td>
														<!-- <td data-label="Remark">Remark</td>
															<td data-label="Price">12.21</td> -->
															<td data-label="Final Price">12.21</td>
														</tr>
														<tr >
															<th  class="mobile-hide" data-label="#" scope="row">2</th>
															<td  data-label="Products">黑胡椒鸡扒意大利面 Black Pepper Chicken Chop Spaghetti
																<br/>
																<span>Qty: 2</span><br/>
																<span>Varients: 2</span>
																<span>Remark: Remark</span>

															</td>
														<!-- <td data-label="Remark">Remark</td>
															<td data-label="Price">12.21</td> -->
															<td data-label="Final Price">12.21</td>
														</tr>

														<tr class="table-borderless">	
															<td class="mobile-hide"  colspan="2"><span class="costright">Subtotal</span></td>
															<td  data-label="Subtotal">12.40</td>
														</tr>

														<tr class="table-borderless">	
															<td class="mobile-hide"  colspan="2"><span class="costright">Service Fee 6%</span></td>
															<td  data-label="Service Fee 6%">0.65</td>
														</tr>

														<tr class="table-borderless">	
															<td  class="mobile-hide" colspan="2"><span class="costright">Delivery Tax</span></td>
															<td  data-label="Delivery Tax">0.65</td>
														</tr>

														<tr class="table-borderless">	
															<td  class="mobile-hide" colspan="2"><span class="costright">Delivery Charges</span></td>
															<td  data-label="Delivery Charges">0.65</td>
														</tr>


														<tr class="table-borderless">	
															<td  class="mobile-hide" colspan="2"><span class="costright">Grand Total</span></td>
															<td  data-label="Grand Total">0.65</td>
														</tr>

														<tr class="table-borderless">	
															<td  class="mobile-hide" colspan="2"><span class="costright">Membership Discount</span></td>
															<td  data-label="Membership Discount">0.65</td>
														</tr>

														<tr class="table-borderless">	
															<td class="mobile-hide"  colspan="2"><span class="costright">Coupon Discount</span></td>
															<td  data-label="Coupon Discount">0.65</td>
														</tr>

														<tr class="table-borderless">	
															<td  class="mobile-hide" colspan="2"><span class="costright">Paid By Wallet</span></td>
															<td  data-label="Paid By Wallet">0.65</td>
														</tr>

														<tr class="table table-borderless">	
															<td  class="mobile-hide" colspan="2"><span class="costright">Balance Payment</span></td>
															<td  data-label="Balance Payment">0.65</td>
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


						</body>
						</html>
