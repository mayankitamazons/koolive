<?php error_reporting(0);
?><!DOCTYPE html>
<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">

<head><meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" href="./css/font-awesome.min.css">
	<link rel="stylesheet" href="./css/ordercss.css">
    <!--  <link rel="stylesheet" href="https://www.koofamilies.com/Dashboard_files/bootstrap.min.js.download">

    -->


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
					<div class="well">.
		  <!-- <a style="text-align:center;width:100%;" href="https://play.google.com/store/apps/details?id=com.koobigfamilies.app" target="blank">
		  	<img style="max-width:140px;" src="google.png" alt=""></a> -->


		  	<div class="total_rat_abt">  
		  		<a class="col-md-2 btn btn-primary status showLoader6" style="background:red;color:black !important;" href="index.php?vs=<?=md5(rand()) ?>"><?php echo $language['more_shops']; ?></a>
		  		<?php  if($user_order){?>
		  			<?php 
		  			$lastshop_link  = "";
		  			$lastshop_link = $site_url.'/view_merchant.php?vs='.md5(rand()).'&sid='.$user_order['merchant_mobile'].'&oid='.$last_order_id;?>

		  			<a class="btn btn-primary" href="<?php echo $lastshop_link; ?>"><?php echo $language["last_order_merchant"];?></a>
		  		<?php } ?>




		  	</div>      
		  	<h3>Order list
		  		<a style="text-align:center;width:100%;margin-top:2%;" href="https://play.google.com/store/apps/details?id=com.app.koofamily" target="blank">
		  			<img style="max-width:140px;" src="google.png" alt="">
		  		</a>
		  		<a style="text-align:center;width:100%;margin-top:2%;" href="https://apps.apple.com/us/app/id1491595615?mt=8" target="blank">
		  			<img style="max-width:140px;max-height:40px;" src="appstore.png" alt="">
		  		</a> 	 			
		  	</h3>  
		  	<?php
		  	$dt = new DateTime();
		  	$today =  $dt->format('Y-m-d');
		  	$today_order = explode(" ",$created_new);
		  	if( $today == $today_order[0] && $status1 == 1 ){ ?>
		  		<div style="display: none;">
		  			<audio autoplay> <source src="<?php echo $site_url;?>/images/sound/doorbell-1.mp3" type="audio/mpeg"> Your browser does not support the audio tag. </audio>
		  			</div>
		  		<?php } ?>


		  		<div id="main">
		  			<div class="main-container">
		  				<div class="main-accordion" id="faq">
		  					<div class="card">
		  						<div class="card-header" id="faqhead1">
		  							<a href="#" class="btn btn-header-link" data-toggle="collapse" data-target="#faq1"
		  							aria-expanded="true" aria-controls="faq1">
		  							<span class="tn_order_no">ORDER NO: #23449 </span>
		  							<span class="tn_order_date">  Date: Feb 23, 2021</span>
		  							<span class="tn_order_total">  Order Total: RM.20.00 </span>

		  						</a>
		  					</div>

		  					<div id="faq1" class="collapse show" aria-labelledby="faqhead1" data-parent="#faq">
		  						<div class="card-body">
		  							<div class="form-row">
		  								<div class="col-md-6 col-lg-6">
		  									
		  									<div class="tn_mer_name">
		  										<span>士林台湾小食 Shilin Taiwan Street Snacks(Kulai)</span>
		  										<span class="tm_hotline"><a href="https://chat.whatsapp.com/J4wcS4riADaBBrvY60c8f3" target="_blank"><i class="fa fa-whatsapp" aria-hidden="true" style="font-size: 20px;"></i></a></span>
		  										<br/>
		  									</div>

		  									<span class="tm_product_qty"><b>Ordered Product:</b> 10 </span> <br/>

		  									<span class="tm_product_qty"><b>Payment Mode:</b> INTERNET Banking </span>
		  									<div class="btn-box-wrap mt-4">
		  										<span class="btn-border" order_id="8286"   title="View Details">
		  											<i class="fa fa-cart-arrow-down  mr-2"></i>Product Detail</span> 


		  											<span class="btn-border" merchant_id="8286" row="" title="Bank Details">
		  												<i class="fa fa-university   mr-2"></i>Bank Detail</span>

		  												<span class="btn-border" invoice_id="92" order_id="36663" review_status="n" skiped_review="0"><i class="fa fa-star   mr-2"></i>Feedback</span>
		  											</div> 
		  											<label class="btn-border mx-0" style="">
		  												<a class="fancybox" rel="" href="http://188.166.187.218/upload/20210310054309.jpg" style="color:#000">
		  												Payment Proof </a>
		  												<a href="javascript:void(0)" class="delete_paymentproof" orderid="36701" style="color:#000"><i class="fa fa-trash" style="margin-left:20px"> </i></a>
		  											</label>

		  											<div class="input-group mt-3 mb-3 input-has-value flex-wrap">
		  												<input type="text" class="form-control payment_proof" disabled="" placeholder="Payment Proof" id="file" style="width:130px">
		  												<br>
		  												<div class="input-group-append">
		  													<button type="button" class="browse btn btn-primary rounded-0">Browse</button>
		  												</div>
		  												&nbsp;&nbsp;
		  												<input type="submit" name="submit" value="Upload" class="btn btn-danger btn_proof_upload rounded-0">
		  											</div>
		  											
		  										</div>
		  										<div class="col-md-6 pl-md-5">
		  											<div class="n_order_delivery  ">
		  												<p class="n_ship_add pt-0">Shipping Address</p>
		  												<p class="tn_add">7411 jln sena35/20 indahpura 81000 
		  												kulai johor</p>
		  												<p class="tn_add"><b>Tel. No:</b> 60197223933</p>
		  											</div>
		  											<hr class="mt-3 mb-4 mx-0" />

		  											<div class="media rider-media align-items-center flex-column flex-sm-row">
		  												<img  src="https://dummyimage.com/100x100/ddd/000" alt="Image" class="mb-3 mb-sm-0  mr-sm-3">
		  												<div class="media-body">
		  													<p><strong>Rider Name : </strong> Lorem Ipsum</p>
		  													<p><strong>Contact Number :</strong> 14141 14141 </p>
		  													<p><strong>Vehicle Number : </strong> 123123</p>
		  													<p><strong>Tracking :  </strong> <a href="#" class="mr-2"><i class="fa fa-map-marker" aria-hidden="true"></i></a>Attempting to connect rider's Location.... Try 10 minutes later. </p>
		  													
		  												</div>
		  											</div>

		  											<div class="n_order_track ">
		  												<p class="n_ship_add">Delivery Status</p>
		  												<div class="n_breadcrumb flat d-flex">
		  													<a href="#" class="active">Pending</a>
		  													<a href="#">Accepted</a>
		  													<a href="#">In delivery</a>
		  													<a href="#">Delivered</a>
		  												</div>
		  												<a href="orderdetails.php" class="btn-border d-block" order_id="8286"  title="View Details">
		  													<i class="fa fa-cart-arrow-down mr-2"></i>View Detail</a>
		  												</div>
		  											</div>
		  										</div>
		  									</div>
		  								</div>
		  							</div>
		  							<div class="card">
		  								<div class="card-header" id="faqhead2">
		  									<a href="#" class="btn btn-header-link collapsed" data-toggle="collapse" data-target="#faq2"
		  									aria-expanded="true" aria-controls="faq2">
		  									<span class="tn_order_no">ORDER NO: #23449 </span>
		  									<span class="tn_order_date"><b>Date:</b> Feb 23, 2021</span>
		  									<span class="tn_order_total"><b>Order Total:</b> RM.20.00 </span>
		  								</a>
		  							</div>

		  							<div id="faq2" class="collapse" aria-labelledby="faqhead2" data-parent="#faq">
		  								<div class="card-body">
		  									<div class="form-row">
		  										<div class="col-md-6 col-lg-6">

		  											<div class="tn_mer_name">
		  												<span>士林台湾小食 Shilin Taiwan Street Snacks(Kulai)</span>
		  												<span class="tm_hotline"><a href="https://chat.whatsapp.com/J4wcS4riADaBBrvY60c8f3" target="_blank">
		  													<i class="fa fa-whatsapp" aria-hidden="true" style="font-size: 20px;"></i></a></span>
		  													<br/>
		  												</div>

		  												<span class="tm_product_qty"><b>Ordered Product:</b> 10 </span> <br/>

		  												<span class="tm_product_qty"><b>Payment Mode:</b> INTERNET Banking </span>
		  												<div class="btn-box-wrap mt-4">
		  													<span class="btn-border" order_id="8286"   title="View Details">
		  														<i class="fa fa-cart-arrow-down  mr-2"></i>Product Detail</span> 


		  														<span class="btn-border" merchant_id="8286" row="" title="Bank Details">
		  															<i class="fa fa-university   mr-2"></i>Bank Detail</span>

		  															<span class="btn-border" invoice_id="92" order_id="36663" review_status="n" skiped_review="0"><i class="fa fa-star   mr-2"></i>Feedback</span>
		  														</div> 
		  														<label class="btn-border mx-0" style="">
		  															<a class="fancybox" rel="" href="http://188.166.187.218/upload/20210310054309.jpg" style="color:#000">
		  															Payment Proof </a>
		  															<a href="javascript:void(0)" class="delete_paymentproof" orderid="36701" style="color:#000"><i class="fa fa-trash" style="margin-left:20px"> </i></a>
		  														</label>

		  														<div class="input-group mt-3 mb-3 input-has-value flex-wrap">
		  															<input type="text" class="form-control payment_proof" disabled="" placeholder="Payment Proof" id="file" style="width:130px">
		  															<br>
		  															<div class="input-group-append">
		  																<button type="button" class="browse btn btn-primary rounded-0">Browse</button>
		  															</div>
		  															&nbsp;&nbsp;
		  															<input type="submit" name="submit" value="Upload" class="btn btn-danger btn_proof_upload rounded-0">
		  														</div>

		  													</div>
		  													<div class="col-md-6 pl-md-5">
		  														<div class="n_order_delivery  ">
		  															<p class="n_ship_add pt-0">Shipping Address</p>
		  															<p class="tn_add">7411 jln sena35/20 indahpura 81000 
		  															kulai johor</p>
		  															<p class="tn_add"><b>Tel. No:</b> 60197223933</p>
		  														</div>
		  														<hr class="mt-3 mb-4 mx-0" />
		  														<div class="media rider-media align-items-center flex-column flex-sm-row">
		  															<img  src="https://dummyimage.com/100x100/ddd/000" alt="Image" class="mb-3 mb-sm-0  mr-sm-3">
		  															<div class="media-body">
		  																<p><strong>Rider Name : </strong> Lorem Ipsum</p>
		  																<p><strong>Contact Number :</strong> 14141 14141 </p>
		  																<p><strong>Vehicle Number : </strong> 123123</p>
		  																<p><strong>Tracking :  </strong><a href="#" class=" mr-2"><i class="fa fa-map-marker" aria-hidden="true"></i></a>Attempting to connect rider's Location.... Try 10 minutes later. </p>
		  															</div>
		  														</div>


		  														<div class="n_order_track ">
		  															<p class="n_ship_add">Delivery Status</p>
		  															<div class="n_breadcrumb flat  d-flex">
		  																<a href="#" class="active">Pending</a>
		  																<a href="#">Accepted</a>
		  																<a href="#">In delivery</a>
		  																<a href="#">Delivered</a>
		  															</div>
		  															<a href="orderdetails.php" class="btn-border d-block" order_id="8286"  title="View Details">
		  																<i class="fa fa-cart-arrow-down mr-2"></i>View Detail</a>
		  															</div>
		  														</div>
		  													</div>
		  												</div>
		  											</div>
		  										</div>
		  		<!-- 	<div class="card">
		  				<div class="card-header" id="faqhead3">
		  					<a href="#" class="btn btn-header-link collapsed" data-toggle="collapse" data-target="#faq3"
		  					aria-expanded="true" aria-controls="faq3">
		  					<span class="tn_order_no">ORDER NO: #23449 </span>
		  					<span class="tn_order_date"><b>Date:</b> Feb 23, 2021</span>
		  					<span class="tn_order_total"><b>Order Total:</b> RM.20.00 </span>

		  				</a>
		  			</div>

		  			<div id="faq3" class="collapse" aria-labelledby="faqhead3" data-parent="#faq">
		  				<div class="card-body">
		  					Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf
		  					moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.
		  					Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda
		  					shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea
		  					proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim
		  					aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
		  				</div>
		  			</div>
		  		</div> -->
		  	</div>
		  </div>
		</div>

<?php /*?>
		<!-- New design start---->
		<div class="order_n_div slick-slide slick-cloned slick-active">
			<div class="order_head_line">
				<span class="n_order_no">ORDER NO: #23449 </span>
				<span class="n_order_date"><b>Date:</b> Feb 23, 2021</span>
				<span class="n_order_total"><b>Order Total:</b> RM.20.00 </span>
			</div>


			<div class="n_order_body row">

				<div class="n_order_mer col-lg-5">

					<div class="n_mer_name">
						<span>士林台湾小食 Shilin Taiwan Street Snacks(Kulai)</span>
						<span class="m_hotline"><a href="https://chat.whatsapp.com/J4wcS4riADaBBrvY60c8f3" target="_blank"><img src="https://www.koofamilies.com/images/whatapp.png" style="max-width:32px;"></a></span>
						<br/>
					</div>

					<span class="m_product_qty"><b>Ordered Product:</b> 10 </span>

					<span class="m_product_qty"><b>Payment Mode:</b> INTERNET Banking </span>

					<span class="s_order_detail btn btn-primary btn-sm" order_id="8286"  title="View Details">
						<i class="fa fa-cart-arrow-down"></i>Product Detail</span>


						<span class="btn btn-yellow bank_detail btn-sm" merchant_id="8286" row="" style="color:black;" title="Bank Details">
							<i class="fa fa-university"></i>Bank Detail</span>

							<span class="btn btn-purple review_detail btn-sm" invoice_id="92" order_id="36663" review_status="n" style="color:black;" skiped_review="0"><i class="fa fa-star"></i>Feedback</span>

							<div class="input-group my-3 input-has-value flex-wrap">
								<input type="text" class="form-control payment_proof" disabled="" placeholder="Payment Proof" id="file" style="width:130px">
								<br>
								<div class="input-group-append">
									<button type="button" class="browse btn btn-primary">Browse...</button>
								</div>
								&nbsp;&nbsp;
								<input type="submit" name="submit" value="Upload" class="btn btn-danger btn_proof_upload">
							</div>
							<label class="btn-sm btn-yellow" style="background-color:#6ea6d6;margin-top:10px;width:150px">
								<a class="fancybox" rel="" href="http://188.166.187.218/upload/20210310054309.jpg" style="color:white">
								Payment Proof </a>
								<a href="javascript:void(0)" class="delete_paymentproof" orderid="36701" style="color:white"><i class="fa fa-trash" style="margin-left:20px"> </i></a>
							</label>

						</div>

						<div class="n_order_delivery col-lg-3">
							<span class="n_ship_add">Shipping Address</span>
							<p class="n_add">7411 jln sena35/20 indahpura 81000 
							kulai johor</p>
							<p class="n_add"><b>Tel. No:</b> 60197223933</p>
						</div>

						<div class="n_order_track col-lg-4">
							<span class="n_ship_add">Delivery Status</span>
							<div class="n_breadcrumb flat">
								<a href="#" class="active">Pending</a>
								<a href="#">Accepted</a>
								<a href="#">Done, in delivery</a>
							</div>
							<br/>
							<a href="orderdetails.php" class="s_order_detail btn btn-primary btn-sm" order_id="8286"  title="View Details">
								<i class="fa fa-cart-arrow-down"></i>View Detail</a>
							</div>
						</div>

					</div>
					<!-- NEW DESIGN END ----->

					<?php */?>

					<!-- /.widget-body badge -->
				</div>
				<!-- /.widget-bg -->
				<!-- /.content-wrapper -->
				<?php include("includes1/commonfooter.php"); ?>
				<!-- Modal -->
				<div class="modal fade" id="PasswordModel" role="dialog" style="">
					<div class="modal-dialog">
						<?php 



						?>

						<!-- Modal content-->
						<div class="modal-content">

							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal">&times;</button>

							</div>

							<div class="modal-body" style="padding-bottom:0px;">
								<div class="form-group register_password" style="<?php  if($password_created=="y"){ echo "display:none;"; }?>">

									<div class="passwd_field">
										<label for="login_password">Please create your password</label>
										<input type="password" id="login_password" class="form-control" name="login_password"/>

										<i  onclick="myFunction2()" id="eye_slash_2" class="fa fa-eye-slash" aria-hidden="true"></i>
										<span id="eye_pass_2" onclick="myFunction2()" > Show Password </span>
										<small id="register_error" style="display: none;color:#e6614f;">

										</small>

									</div>
								</div>

							</div>
							<div class="modal-footer" style="padding-bottom:2px;">
								<div class="row" style="margin: 0;">


									<div class="col" style="padding: 0;margin: 5px;">

										<input type="submit" class="btn btn-primary register_ajax"  name="register_ajax" value="Confirm" style="float: right;"/>
										<small id="register_error" style="display: none;color:#e6614f;">

										</small>

									</div>


								</div>
								<small  class="finalskip"  style="color:#e6614f;font-size:14px;min-width:50px;">
									<u class="finalskip btn btn-primary">Skip</u>
								</small>    

							</div>

						</div>
					</div>
				</div>

				<div class="modal fade" id="OnlineModel" role="dialog" style="">
					<div class="modal-dialog">
						<?php 



						?>

						<!-- Modal content-->
						<div class="modal-content">

							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal">&times;</button>

							</div>

							<div class="modal-body" style="padding-bottom:0px;">
								<p>Select Payment Mode</p>
								<select class="form-control text_payment required" id="text_payment" style="font-weight: bold;" name="wallet">  
        						<!--<option class="text_csah" value='cash_<?php echo $order_total['id'];?>'><?php echo $language["cash"] ;?></option>
        							<option value='MYR'><?php echo $language["wallet"];?></option>  !-->
        							<?php if($merchant['mobile_number']!="60172669613"){ ?>

        								<?php if($credit_check == "1"){?>
        									<option title="Credit Card">Credit Card</option>
        								<?php }?>
        								<?php if($boost_check == "1"){?>
        									<option title="Boost Pay" value='1'>Boost Pay</option>
        								<?php }?>
        								<?php if($grab_check == "1"){?>
        									<option value='2' title="Grab Pay">Grab Pay</option>
        								<?php }?>
        								<?php if($wechat_check == "1"){?>
        									<option value='3' title="WeChat">WeChat</option>
        								<?php }?>
        								<?php if($touch_check == "1"){?>
        									<option value='4' title="Touch & Go">Touch & Go</option>
        								<?php }?>
        								<?php if($fpx_check == "1"){?>
        									<option value='5' title="FPX">FPX</option>
        								<?php } }?>
        							</select>  

        							<input type="hidden" id="id" name="m_id" value="<?php echo $m_id;?>">
        							<input type="hidden" id="amount" name="amount" value="<?php echo $total;?>">
        							<input type="hidden" id="member" name="member" value="<?php echo $member;?>">
        							<input type="hidden" id="o_id" name="o_id" value="<?php echo $order_total['id'];?>">
        							<?php if(isset($_GET['user_id'])){  ?>
        								<input type="hidden" id="guest_id" name="guest_id" value="<?php echo $_GET['user_id'];?>">
        								<input type="hidden" id="guest_order_id" name="guest_order_id" value="<?php echo $_GET['order_id'];?>">
        							<?php } ?>

        							<!-- jupiter 24.02.19 -->
        							<?php if($merchant['mobile_number']!="60172669613"){    ?>
        								<div class="payment_section">
        									<table class="table" border="1" style="margin-top: 10px; " >
        										<tbody>
					  			<!--tr>
					  				<td><h5 class="payment_title">Cash</h5></td>
						  			<td><img src="images/payments/cash.png" class="img60"></td>
						  			<td><img src="images/payments/<?= $cash_image;?>.jpg" class="img60"></a></td>
						  		</tr!-->
						  		<?php if($credit_check == "1"){?>
						  			<tr>
						  				<td><h5 class="payment_title">Credit Card</h5></td>
						  				<td><img src="images/payments/credit.jpg" class="img60"></td>
						  				<td><img src="images/payments/<?= $credit_image;?>.jpg" class="img60"></a></td>
						  			</tr>
						  		<?php } ?>
					  			<!--tr>
					  				<td><h5 class="payment_title">Wallet</h5></td>
						  			<td><img src="images/payments/wallet.png" class="img60"></td>
						  			<td><img src="images/payments/<?= $wallet_image;?>.jpg" class="img60"></a></td>
						  		</tr!-->
						  		<?php if($boost_check == "1"){?>
						  			<tr>
						  				<td><h5 class="payment_title">Boost Pay</h5></td>
						  				<td><img src="images/payments/boost.png" class="img60"></td>
						  				<td>

						  					<a href="#" data-toggle="modal" data-target="#paymentModal" user="<?= $merchant_id;?>" class="payment_btn" payment="1" title="Boost Pay"><img src="images/payments/available.jpg" class="img60">
						  					</a>


						  				</td>
						  			</tr>
						  		<?php }?>
						  		<?php if($grab_check == "1"){?>
						  			<tr>
						  				<td><h5 class="payment_title">Grab Pay</h5></td>
						  				<td><img src="images/payments/grab.jpg" class="img60"></td>
						  				<td>

						  					<a href="#" data-toggle="modal" data-target="#paymentModal" user="<?= $merchant_id;?>" class="payment_btn" payment="2" title="Grab Pay"><img src="images/payments/available.jpg" class="img60">
						  					</a>

						  					
						  				</td>
						  			</tr>
						  		<?php }?>
						  		<?php if($wechat_check == "1"){?>
						  			<tr>
						  				<td><h5 class="payment_title">WeChat</h5></td>
						  				<td><img src="images/payments/wechat.jpg" class="img60"></td>
						  				<td>

						  					<a href="#" data-toggle="modal" data-target="#paymentModal" user="<?= $merchant_id;?>" class="payment_btn" payment="3" title="WeChat"><img src="images/payments/available.jpg" class="img60">
						  					</a>


						  				</td>
						  			</tr>
						  		<?php }?>
						  		<?php if($touch_check == "1"){?>
						  			<tr>
						  				<td><h5 class="payment_title">Touch & Go</h5></td>
						  				<td><img src="images/payments/touch.png" class="img60"></td>
						  				<td>

						  					<a href="#" data-toggle="modal" data-target="#paymentModal" user="<?= $merchant_id;?>" class="payment_btn" payment="4"title="Touch & Go"><img src="images/payments/available.jpg" class="img60">
						  					</a>


						  				</td>
						  			</tr>
						  		<?php }?>
						  		<?php if($fpx_check == "1"){?>
						  			<tr>
						  				<td><h5 class="payment_title">FPX</h5></td>
						  				<td><img src="images/payments/fpx.png" class="img60"></td>
						  				<td>

						  					<a href="#" data-toggle="modal" data-target="#paymentModal" user="<?= $merchant_id;?>" class="payment_btn" payment="5" title="FPX"><img src="images/payments/available.jpg" class="img60">
						  					</a>

						  				</td>
						  			</tr>
						  		<?php }?>
						  	</tbody>
						  </table>
						</div>  
					<?php } ?>


				</div>
				<div class="modal-footer" style="padding-bottom:2px;">
					<div class="row" style="margin: 0;">


						<div class="col" style="">
							
							<!--input type="submit" class="btn btn-primary online_ajax"  name="online_ajax" value="Confirm" style="float: right;"/!-->
							<small id="register_error" style="display: none;color:#e6614f;">

							</small>
							
						</div>


					</div>
					<small  class="skiponline"  style="color:#e6614f;font-size:14px;min-width:50px;">
						<u class="skiponline">Skip</u>
					</small>    

				</div>

			</div>
		</div>
	</div>
	<div class="modal fade" id="forgot_setup" tabindex="-1" role="dialog" aria-hidden="true" style="margin-top:4%;">

		<div class="modal-dialog" role="document">

			<div class="form-group">

				<div class="modal-content">

					<div class="modal-header">



						<button type="button" class="close" data-dismiss="modal" aria-label="Close">

							<span aria-hidden="true">&times;</span>

						</button>

					</div>

					<div class="modal-body">

						<p id="forgot_msg_new" style="font-size: 16px;color: red;"><span style='font-size:20px;'>&#128512;</span> 

							Enter otp to reset password 

						</p>  

						<div class="form-group forgot_otp_form" style="display:none;">

							<div id="forgot_divOuter">

								<div id="forgot_divInner">

									Otp code

									<input id="forgot_partitioned" type="Number" maxlength="4" />

									<!--small style="float:right;color:#28a745;display:none;" class="resend send_otp">Resend</small!-->

									<small class="forgot_otp_error" style="display: none;color:#e6614f;">

										Invalid Otp code

									</small>

								</div>

							</div>

						</div>
						<div class="form-group forgot_register_password" style="display:none;">

							<div class="passwd_field">
								
								<input type="password" id="forgot_register" class="form-control" name="forgot_register"/>

								<i  onclick="myFunctionforgot()" id="eye_slash_forgot" class="fa fa-eye-slash" aria-hidden="true"></i>
								<span id="eye_pass_forgot" onclick="myFunctionforgot()" > <?php echo $language['show_password']; ?> 
							</span>
							<small style="color:red;">(Please set passwords at least  6 digits and above)</small>
							<small id="forgot_register_error" style="display: none;color:#e6614f;">

							</small>

							<small id="forgot_reset_password_error" style="font-size:16px;display:none;color:#e6614f;"></small>

						</div>
					</div>

					


				</div>

				<div class="modal-footer forgot_footer">

					<div class="row" style="margin: 0;">

						<div class="col forgot_password_submit" style="padding: 0;margin: 5px;display:none;">

							<input type="submit" class="btn btn-primary forgot_reset_password"  name="login_ajax" value="Change Password" style="float: right;display:none;"/>

							

						</div>  





					</div>

					<small  class="reg_pending skip" id='skip' style="color:#e6614f;font-size:14px;min-width:50px;">

						<u class="btn btn-primary"><?php echo $language['skip']; ?> </u>

					</small>

					

				</div>

			</div>

		</div>

	</div>

</div>
<div id="reviewdetailmodel" class="modal fade" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content" id="review_model">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">×</button>           
				<h4 class="modal-title" id="see_or_write_review" style="font-size: 21px;">Write a review for <span id="order_product_name"></span></h4>

			</div>


			<div class="modal-body" id="reviewdetailmodel_load" style="padding-bottom:0px;margin-top: -22px;">
				<p style="font-size: 14px;" id="your_feedback">Your feedback is extermely important to us in order to provide best service to you</p>
      <!--center>
         <div class="row" >
            <div class="col-md-5"><button id="marchant_review_button" type="button" style="font-size: 12px;cursor: pointer;" name="marchant_review_button"  class="btn btn-primary">Review to Marchant</button></div>
            <div class="col-md-1"></div>
            <div class="col-md-5"><button id="deliveryman_review_button" type="button" style="font-size: 12px;cursor: pointer;" name="deliveryman_review_button" class="btn btn-secondary">Review to Deliveryman</button>
            </div>
        </center!-->

        <div id="merchant_review" style="font-size:14px;">
        	<br> <center style="font-size: 14px;margin-top: -5px;text-align: center;font-weight: bold;"><b>Give feedback for service</b></center><hr>
        	Q 1. Are you satisfied with the food quality?<br>
        	<div class="row">

        		<div class="col-2 col-md-2 col-xs-2"><img id="review_q1_a1" src="assets\img\smile\laughing_green.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
        		<div class="col-2 col-md-2 col-xs-2"><img id="review_q1_a2" src="assets\img\smile\happy_grey.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
        		<div class="col-2 col-md-2 col-xs-2"><img id="review_q1_a3" src="assets\img\smile\surprised_grey.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
        		<div class="col-2 col-md-2 col-xs-2"><img id="review_q1_a4" src="assets\img\smile\sad_grey.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
        		<div class="col-2 col-md-2 col-xs-2"><img id="review_q1_a5" src="assets\img\smile\verysad_grey.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
        		<div class="col-2 col-md-2 col-xs-2"></div>
        	</div>
        	<hr>
        	Q 2. Are you happy with deliveryman service?<br> 
        	<div class="row">
        		<div class="col-2 col-md-2 col-xs-2"><img id="review_q2_a1" src="assets\img\smile\laughing_green.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
        		<div class="col-2 col-md-2 col-xs-2"><img id="review_q2_a2" src="assets\img\smile\happy_grey.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
        		<div class="col-2 col-md-2 col-xs-2"><img id="review_q2_a3" src="assets\img\smile\surprised_grey.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
        		<div class="col-2 col-md-2 col-xs-2"><img id="review_q2_a4" src="assets\img\smile\sad_grey.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
        		<div class="col-2 col-md-2 col-xs-2"><img id="review_q2_a5" src="assets\img\smile\verysad_grey.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
        		<div class="col-2 col-md-2 col-xs-2"></div>
        	</div>
        	<hr>
        	Q 3. Any additional comments? ?<br>
        	<textarea class="form-control rounded-0" id="addiComments" rows="1"></textarea>
      <!--hr>
      Q 6. Do you allow us to contact you for further clarification ?<br>
      <input type="radio" name="clarification" value="No" checked>No</input>&nbsp;&nbsp;&nbsp;
      <input type="radio" name="clarification" value="Yes">Yes</input>
      <br!-->
      <p id="review_error" style="color: red;"></p>
      <br>
  </div> 
  <center><button id="review_check" type="button" name="review_check" onclick="checkNext()" style="color:black;"   class="btn btn-primary" order_id="">Feedback now</button>
  	<button id="review_skip" type="button" onclick="skip_review()"   class="btn btn-primary" style="color:black;"    order_id="">Feedback later</button></center>
  	<br>	  
  </div>
</div>
</div>
</div>
<!-- Modal -->
<div id="paymentModal" class="modal fade" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<form  id="paymentdata" method="POST"  enctype="multipart/form-data" action="payment_submit.php">    
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title payment_header">Modal Header<img src="images/payments/boost.png"></h4>

				</div>
				<div class="modal-body" style="text-align: left;">
					<input type="hidden" value="" name="wallet" id="payment_type" class="payment_type">

					<input type="hidden" value="<?php echo $open_order_id;?>" name="payment_order_id">
					<h5 class="">Please pay to <span class="row">sdf</span></h5>
					<h5>Mobile Number +60 <span class="mobile"></span></h5>
					<h5>QR Code:</h5>
					<img class="qr_code_image">
					<h5 class="">Reference: <span class="reference"></span></h5>
					<div class="form-group" style="width:70%;">
						<label for="pwd">Upload image (proof of payment):</label>
						<input type="file" class="form-control" name="paymentproff" id="paymentproff">
					</div>
					<button type="submit" class="btn btn-primary confirm_payment_btn"  style="margin-bottom: 10px;">I have paid to the merchant</button>
					<button type="button" class="btn btn-default different_method" data-dismiss="modal">I want to pay with another method</button>
					<small  class="finalskip"  style="color:#e6614f;font-size:14px;min-width:50px;float:right;">
						<u class="skiponline">Skip</u>
					</small>    

				</div>

			</form>
		</div>

	</div>
</div>
<div class="modal fade" id="SectionModel" role="dialog" style="">
	<div class="modal-dialog">
		<?php 



		?>

		<!-- Modal content-->
		<div class="modal-content">
			<form method="post" action="sectionsave.php">
				<div class="modal-header">
					<button type="button" class="close" id='sectionclose' data-dismiss="modal">&times;</button>
					<?php if($merchant_data['mian_merchant']){ ?>
						<h4 class="modal-title">Main Merchant (<?php echo $merchant_data['mian_merchant'];?>)</h4>
					<?php } ?>
				</div>

				<div class="modal-body" style="padding-bottom:0px;">
					<?php if(($new_order=="y") && ($show_alert=="y")){  if($total_rebate_amount>0 && $rebate_credited!='y'){?>
						<!--p style="font-size: 16px;color: red;"><span style='font-size:20px;'>&#128512;</span> Congratulations ! Your order has been sent to our kitchen!</p!-->

						<p style="font-size: 16px;color: red;"><span style='font-size:20px;'>&#128512;</span> Congratulations ! your order is completed. Your rebate amount is RM <?php echo $total_rebate_amount;?>, which will be credited to your wallet after 3 working days.</p>

					<?php } else { ?>
						<p style="font-size: 16px;color: red;"><span style='font-size:20px;'>&#128512;</span> Congratulations ! Your order has been completed and send to kitchen! just wait for your foods to be served</p>

					<?php }}  ?>     
					<?php if($first_table_id=='' || $first_section_id==''){ ?>
						<div class="col-md-12" style="text-align: center;">
							<?php if($first_table_id=='' && $merchant_data['table_on_orderlist']=="y"){ ?>
								<span><strong> If you have found your table, kindly key in now:-</strong></span>
							<?php } else if($first_section_id=='' && $merchant_data['section_on_orderlist']){ ?>
								<span><strong> If you have found your Section, kindly key in now:-</strong></span>
							<?php } ?>

							<?php if($first_section_id=='' && $merchant_data['section_on_orderlist']=="y"){ ?>
								<center><div class=" col-md-6 form-group">
									<label>Section :</label>
									<select id='section_type' name="section_type" required  class="form-control" data-table-list-url="<?php echo $site_url; ?>/table_list.php">

										<?php 
										foreach($sectionsList as $sectionId => $sectionName): ?>
											<?php
											$isSelected = "";
											if($section_id == $sectionId) {
												$isSelected = "selected";
											}
											?>   
											<option value="<?php echo $sectionId; ?>" <?php echo $isSelected; ?>><?php echo $sectionName; ?></option>
										<?php endforeach; ?>
									</select>
									</div></center> <?php } if($first_table_id=='' && $merchant_data['table_on_orderlist']=="y"){ ?>
										<center> <div class=" col-md-6 form-group">
											<label>Table Number :</label>
											<input type="text" class="form-control" id='table_booking' name="table_booking" value="" placeholder="Table Number" required>  
										</div></center>
									<?php } ?>
									<?php if($offerdata['discp'] && $merchant_data['mian_merchant']){ ?>   
										<p><strong><?php echo $offerdata['discp'];?> </strong></p>
									<?php } ?>
									<input type="hidden" name="merchant_id" id="merchant_id" value="<?php echo $merchant_data['mian_merchant'];?>">
									<input type="hidden" name="more_order" id="more_order" value="moreOrder">
									<input type="hidden" name="current_oid" value="">
									<input type="hidden" name="order_id" value="<?php echo $user_order['id']; ?>">
								</div>
							<?php  } ?>
						</div>
						<?php if(($first_section_id=='' && $merchant_data['section_on_orderlist']=="y") || ($first_table_id=='' && $merchant_data['table_on_orderlist']=="y")){ ?>
							<div class="modal-footer" style="padding-bottom:2px;">
								<div> <img style="max-height:100px !important;" src="Drink-pink.png"></div>  
								<?php if($offerdata['discp'] && $merchant_data['mian_merchant']){ ?>   
									<button type="submit" class="btn btn-primary" style="background-color: red;">Yes order with <?php echo $merchant_data['mian_merchant'];?>
								</button>
							<?php } else { ?>
								<button type="submit" class="btn btn-primary" style="float: right;">Confirm</button>

							<?php  } ?>
						  <!--small  class="finalskip"  style="color:#e6614f;font-size:14px;">
						  <u>Skip</u>
						</small!-->
						
					</div>
				<?php } ?>
			</form>
		</div>
	</div>
</div>
<div class="modal fade" id="InternetModel" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="form-group">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					

					<div class="" style="">

						<h4 style="margin-top: 0px;">Banking Detail</h4>

						<div class="row">
							<div clas="form-group" style="margin-left:3%;font-size:15px;">
								

								<div style="clear:both;"></div>
								<p> Please pay Exact Amount to ：</br>
								Name: Chong Woi Joon  </br>
							Bank name: Hong Leong Bank </br>
						Bank account : 22850076859 </br>
						<b style="font-size:18px;">Boostpay Number 6012-3115670</b>  
					</br>
					<?php if($_SESSION["langfile"]=="chinese"){ echo "请写商家店名在“银行参考”";} else {?>
						(Please write <span id="bank_merchant_name"></span>   in "bank reference")
					<?php } ?>
				</br>
				Enquiry:  <a href="https://api.whatsapp.com/send?phone=60123945670" target="_blank"> 60123945670 <img src="images/whatapp.png" style="max-width:32px;"/> </a>
			</br>
		</p>  
	</div>



</div>


</div>

</div>
</div>  
</div>
</div>
</div>
<div class="modal fade" id="orderdetailmodel" role="dialog">						
	<div class="modal-dialog">
		<!-- Modal content-->		
		<div class="modal-content">	
			<div class="modal-header">	
				<button type="button" class="close" data-dismiss="modal">&times;</button>						
				<h4 class="modal-title">Order Detail</h4>	
			</div>					
			<form id ="orderdetailform">		
				<div class="modal-body" style="padding-bottom:0px;">
					<div class="col-sm-10" id="orderdata">						

					</div>						

				</form>						
			</div>						
		</div>						
	</div>
</div>
<div class="modal fade" id="newuser_model" tabindex="-1" role="dialog" aria-labelledby="login_passwd_modal_title" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="form-group">
			<div class="modal-content">

				<div class="modal-header">

					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>

				<div class="modal-body">
					<div class="form-group">

						<input type="hidden" id="user_mobile" value="<?php echo $user_order['user_mobile']; ?>"/>
						<input type="hidden" id="user_id" value="<?php echo $user_order['user_id']; ?>"/>
						<input type="hidden" id="order_id" value="<?php echo $user_order['id']; ?>"/>
						<input type="hidden" id="system_otp"/>
						<input type="hidden"  value='0' id="otp_count"/>
						<p id="show_msg" style="font-size: 16px;color: red;"><span style='font-size:20px;'>&#128512;</span> 
							<?php 
							if(($total_rebate_amount>0)  && $rebate_credited!="y"){
								$o_msg="Congratulations ! your order is completed. Your rebate amount is RM ".$total_rebate_amount.", which will be credited to your wallet after 3 working days.";
							}
							else
							{
								$o_msg="Congratulations ! Your order has been completed and send to kitchen! just wait for your foods to be served"; 
							}
							if(($new_order=="y") && ($show_alert=="y")){
								if(($total_rebate_amount>0)  && $rebate_credited!="y"){
									if($_SESSION['tmp_login'] && $_SESSION['login']==''){
										echo "Congratulation, your order is completed. Please login to claim for your rebate of RM ".$total_rebate_amount." into your KOO Coin wallet.";
									} else if($otp_verified=="n")
									{
										echo "Congratulations! Your order is completed. Your rebate of RM ".$total_rebate_amount." will be credited after you have verified your telephone number through the below OTP code (sent to you through SMS)";
									} else {
										echo $o_msg;
									}
								}
								else { echo "Congratulations ! Your order has been completed and send to kitchen! just wait for your foods to be served";}
							}  else { echo "Congratulations ! Your order has been completed and send to kitchen! just wait for your foods to be served";}?>
						</p>
					</div>
					<?php 
					function random_strings($length_of_string) 
					{ 
						$str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz'; 
						return substr(str_shuffle($str_result), 0, $length_of_string); 
					} 
					if($user_order['user_refferal_code']=='')
					{
						$user_refferal_code=random_strings(8);
				// "update users set user_refferal_code='$user_refferal_code' where id='$user_id'";
						mysqli_query($conn,"update users set user_refferal_code='$user_refferal_code' where id='$user_id'");
					}
					else
					{
						$user_refferal_code=$user_order['user_refferal_code'];
					}
					$ref_link=$site_url."/view_merchant.php?sid=".$user_order['merchant_mobile']."&r_code=".$user_refferal_code;  

					$share_link=urlencode("Hey use my link to place order on koofamilies $ref_link"); 
					$share_link=$language['ref_1']." ".$user_order['row']." ".$language['ref_2']." ".$language['ref_3']."\n".$ref_link;
					$share_link=urlencode($share_link);
					?>
					<div class="form-group">
						<p><b>Meanwhile,  Earn 4% rebate by sharing your experience by copying the following link and share to your friends
						</br><a href="<?php echo $ref_link; ?>" target="_blank"><?php echo $ref_link;?></a>.</b></br>
						<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $ref_link; ?>" target="_blank">
							<img src="https://cdn4.iconfinder.com/data/icons/social-messaging-ui-color-shapes-2-free/128/social-facebook-circle-512.png"  style="max-width:9%;" alt="" />
						</a>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   

						<a href="https://wa.me/?text=<?php echo $share_link; ?>"
							data-action="<?php echo urlencode($ref_link); ?>"
							target="_blank">   

							<img src="https://e7.pngegg.com/pngimages/10/271/png-clipart-iphone-whatsapp-logo-whatsapp-call-icon-grass-mobile-phones.png"  style="max-width:13%;" alt="" />
						</a>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<a href="https://twitter.com/intent/tweet?url=<?php echo urlencode($ref_link); ?>&via=getboldify&text=<?php echo $share_link; ?>" target="_blank">  
							<img src="https://cdn3.iconfinder.com/data/icons/social-media-circle/512/circle-twitter-512.png" style="max-width:13%;"/> </a>



						</br>
						Or become our salesman, earn money by sharing <!--<a href="comission_list.php?vs=<?php echo md5(rand());?>">(learn more)</a>-->
					</p>

				</div>

				<div class="form-group otp_form" style="display:none;">
					<div id="divOuter">
						<div id="divInner">
							Otp code
							<input id="partitioned" type="Number" style="border:1px solid black;" maxlength="4" />
							<!--small style="float:right;color:#28a745;display:none;" class="resend send_otp">Resend</small!-->
							<small class="otp_error" style="display: none;color:#e6614f;">
								Invalid Otp code
							</small>
						</div>
					</div>
				</div>
				
				<div class="login_passwd_field" style="display:none;">
					<label for="login_password">Password to login</label>
					<input  type="password" id="login_ajax_password" class="form-control" name="login_password" required/>

					<i  onclick="myFunction()" id="eye_slash" class="fa fa-eye-slash" aria-hidden="true"></i>
					<span onclick="myFunction()" id="eye_pass"> Show Password </span>

					<div style="clear:both"></div>	
					<span class="forgot_pass" style="color:#28a745;/*! float:right; */font-size:20px;text-align: center;text-decoration: underline;/*! width: 100% !important; */display: inline-block;margin-left: 18%;">
						Reset/Create Password
					</span>

				</div>
				<div class="forgot-form" style="display:none;">
					<label for="login_password">Reset/Create  Password</label>
					<div class="input-group mb-2">
						<div class="input-group-prepend">
							<div class="input-group-text" style="background-color:#51D2B7;border-radius: 5px 0 0 5px;height: 100%;padding: 0 10px;display: grid;align-content: center;">+60</div>
						</div>

						<input  type="number" autocomplete="tel" maxlength='10' id="mobile_number" class="mobile_number form-control" <?php if($check_number){ echo "readonly";} ?> value="<?php if($check_number){ echo $check_number;}  ?>" placeholder="Phone number" name="mobile_number" required="" />

					</div>
					<small id="forgot_error" style="display: none;color:#e6614f;">
						Please Key in valid number
					</small>
					<img id="loader-credentials" src="<?php echo $site_url;?>img/loader.gif" style="display:none;width:40px;height:40px;grid-column-start: 2;grid-column-end: 3;"/>
				</div>
				<div class="row">
					<p style="font-size: 16px;margin-bottom: 0px;margin-left:1%;">Download our app for future easy ordering</p>
					<a href="https://play.google.com/store/apps/details?id=com.app.koofamily" target="_blank">
						<img style="max-height:40px;" src="assets/img/google-play-button.png"/></a>
						<a  href="https://apps.apple.com/us/app/id1491595615?mt=8" target="blank">
							<img style="max-width:140px;max-height:40px;" src="appstore.png" alt="">
						</a>
					</div>

				</div>  
				<div class="modal-footer login_footer">
					<div class="row" style="margin: 0;">


						<div class="col otp_fields join_now" style="padding: 0;margin: 5px;display:none;">

							<input type="submit" class="btn btn-primary login_ajax"  name="login_ajax" value="Confirm" style="float: right;display:none;"/>
							<small id="login_error" style="display: none;color:#e6614f;">

							</small>

						</div>
						<div class="col otp_fields forgot_now" style="padding: 0;margin: 5px;display:none;">
							<input type="submit" class="btn btn-primary forgot_reset"   value="Reset" style="width:50%;float: right;display:none;"/>
						</div>         

					</div>
					<small  class="reg_pending skip" id='skip' style="color:#e6614f;font-size:14px;min-width:50px;">
						<u class="btn btn-primary">Got it !</u>
					</small>
					<small  class="reg_pending register_skip skip" style="color:#e6614f;font-size:14px;display:none;min-width:50px">
						<u class="btn btn-primary">Got it !</u>
					</small>

				</div>

			</div>
		</div>
	</div>
</div>
<a href="https://chat.whatsapp.com/LdvomJRqXoIG6aXqsf5PgX" target="_blank"><img src ="images/iconfinder_support_416400.png" style="width:75px;height:75px;position: fixed;left:15px;bottom: 70px;z-index:999;"></a>
<link rel="stylesheet" href="css/fancybox.css" type="text/css" media="screen" />
<script type="text/javascript" src="js/fancybox.js"></script>


</body>
</html>
<script>
	function myFunctionforgot() {
		var x = document.getElementById("forgot_register");
		if (x.type === "password") {
			x.type = "text";
			$("#eye_pass_forgot").html('Hide Password');
			$('#eye_slash_forgot').removeClass( "fa-eye-slash" );
			$('#eye_slash_forgot').addClass( "fa-eye" );
			
		} else {
			x.type = "password";
			$("#eye_pass_forgot").html('Show Password');
			$('#eye_slash_forgot').addClass( "fa-eye-slash" );
			$('#eye_slash_forgot').removeClass( "fa-eye" );
		}
	}
	function myFunction() {
		var x = document.getElementById("login_ajax_password");
		if (x.type === "password") {
			x.type = "text";
			$("#eye_pass").html('Hide Password');
			$('#eye_slash').removeClass( "fa-eye-slash" );
			$('#eye_slash').addClass( "fa-eye" );
			
		} else {
			x.type = "password";
			$("#eye_pass").html('Show Password');
			$('#eye_slash').addClass( "fa-eye-slash" );
			$('#eye_slash').removeClass( "fa-eye" );
		}
	}
	function myFunction2() {
		var x = document.getElementById("login_password");
		if (x.type === "password") {
			x.type = "text";
			$("#eye_pass_2").html('Hide Password');
			$('#eye_slash_2').removeClass( "fa-eye-slash" );
			$('#eye_slash_2').addClass( "fa-eye" );
			
		} else {
			x.type = "password";   
			$("#eye_pass_2").html('Show Password');
			$('#eye_slash_2').addClass( "fa-eye-slash" );
			$('#eye_slash_2').removeClass( "fa-eye" );
		}
	}
</script>
<?php $login_user_id=$_SESSION['login']; ?>
<script type="text/javascript">
	$(document).ready(function(){
		var s_token=generatetokenno(16);
		var r_url="http://188.166.187.218/index.php?vs="+s_token;
		var myDynamicManifest = {

			"gcm_sender_id": "540868316921",

			"icons": [

			{

				"src": "https://koofamilies.com/img/logo_512x512.png",

				"type": "image/png",

				"sizes": "512x512"

			}

			],

			"short_name":'koofamilies Pos System',

			"name": "One stop centre for your everything",

			"background_color": "#4A90E2",

			"theme_color": "#317EFB",

			"orientation":"any",

			"display": "standalone",

			"start_url":r_url

		} 
		const stringManifest = JSON.stringify(myDynamicManifest);

		const blob = new Blob([stringManifest], {type: 'application/json'});

		const manifestURL = URL.createObjectURL(blob);

		document.querySelector('#my-manifest-placeholder').setAttribute('href', manifestURL);





		if ('serviceWorker' in navigator) {
			window.addEventListener('load', function() {
				navigator.serviceWorker.register('/sw_new.js').then(function(registration) {
      // Registration was successful
      console.log('ServiceWorker registration successful with scope: ', registration.scope);
  }, function(err) {
      // registration failed :(
      console.log('ServiceWorker registration failed: ', err);
  });
			});
		}
		var new_order='<?php echo $new_order; ?>';
		var new_order='<?php echo $new_order; ?>';
		var show_pop='<?php echo $show_pop; ?>';
		var already_login='<?php echo $login_user_id; ?>';
		var newuser='<?php echo $newuser; ?>';
		var show_alert='<?php echo $show_alert; ?>';
		// alert(show_pop);
		nextstep();
		// if(show_pop=="y")
		// {
			// $('#SectionModel').modal('show');
			
		// }
		// else
		// {
			// nextstep();
		// }
	    // alert(new_order);
		  // nextstep();
		// alert($('#SectionModel').hasClass( "show"));
		

		

		
	});
	$(".forgot_reset").click(function(){
		$(this).hide();
		$(this).removeClass(" btn-primary").addClass("btn-default");

		setTimeout(function() {

			

			$(this).removeClass("btn-default").addClass("btn-primary");

		}.bind(this), 5000);

		   // var usermobile=$("#mobile_number").val();
		   var mobile=$("#mobile_number").val();
		   if(mobile[0]==0)
		   {
		   	mobile=mobile.slice(1);
		   }
		   var usermobile="60"+mobile;

		   // alert(usermobile);

		   var data = {usermobile:usermobile,method:"forgotpass2"};

			// alert(data);

			$.ajax({



				url :'functions.php',

				type:'POST',

				dataType : 'json',

				data:data,  

				success:function(response){

					var data = JSON.parse(JSON.stringify(response));

				  // alert(data.status);

				  if(data.status)

				  {  

				  	$("#otp_count").val(otp_count);
				  	$("#system_otp").val(data.otp);   
				  	$('#newmodel_check').modal('hide'); 
				  	$('#forgot_msg_new').show();
				  	$('.forgot_otp_form').show();
				  	$('.forgot_footer').show();
				  	$('#newuser_model').modal('hide'); 
				  	$('#forgot_setup').modal('show'); 

				  }

				  else

				  {
				  	$(this).show();
				  	$(".forgot_reset").show();

				  	$(".forgot_error").html(data.msg);

				  	$(".forgot_error").show();

				  	$(".forgot_now ").hide();

				  }

				  

				}		  

			});

		});

	$(".forgot_pass").click(function(){

		var mobile_no=$('#mobile_number').val();
				// alert(mobile_no);
				$('#show_msg_new').html('');
				$('#forgot_mobile_number').val(mobile_no);
				$('#user_mobile').val(mobile_no);
				$('#with_wallet').hide();

				$('#without_wallet').hide();

				$('.login_passwd_field').hide();

				$(".join_now").hide();

				$(".forgot_reset").show();

				$(".forgot_now").show();

				$('.forgot-form').show();

			}); 
	$(".send_otp").click(function(){
	  // $(".resend").hide();
	  
	  var otp_count=$("#otp_count").val();
	  if(otp_count<3)
	  {
	  	var usermobile=$("#user_mobile").val();
	  	var user_id="<?php echo $user_id; ?>";
	  // var usermobile=+919001025477;
	   //target:'#picture';
	    // $(this).hide();
	    var data = {usermobile:usermobile, method: "sendotpwithlink",user_id:user_id};  
	    $.ajax({

	    	url :'functions.php',
	    	type:'POST',
	    	dataType : 'json',
	    	data:data,
	    	success:function(response){
	    		var data = JSON.parse(JSON.stringify(response));
	    		if(data.status==true)
	    		{
	    			otp_count++;

	    			$("#otp_count").val(otp_count);
	    			$("#system_otp").val(data.otp);
	    			$(".otp_form").show(); 
	    		}

	    	}		  
	    });
	   // setTimeout(function () {
						// $(".resend").show();
					// }, 15000);
				}
				else
				{
		  // $('#register_error').html('You Extend Send Otp limit');
		  // $('#register_error').show();
		}

	});
	$(".skiponline").click(function(){
		var s_token=generatetokenno(16);
		var r_url="http://188.166.187.218/orderlist_new.php?vs="+s_token;
						//window.location.replace(r_url);
					});
	$(".finalskip").click(function(){
	   // alert(3);
	   $('#newuser_model').modal('hide');
	   $('#SectionModel').modal('hide');
	   $('#PasswordModel').modal('hide');
	});
	$("#sectionclose").click(function(){
	  // alert(show_pop);
	  nextstep();
	});
	$(".skip").click(function(){
		$('#newuser_model').modal('hide');
	  // alert(show_pop);
			// nextstep();
		});
	$(".different_method").click(function(){
		$("#OnlineModel").modal("show");
		$("#paymentModal").modal("hide");
	});
	$(".confirm_payment_btn").click(function(e){
		var payment = $(".payment_type").val();
		// alert(payment);
		if(payment == "1"){
			payment = "Boost Pay";
		}

		$(".text_payment").val(payment);
		$("#paymentModal").modal("hide");
		 // document.getElementById("paymentdata").submit();
		});
	$(".payment_btn").click(function(e) {
			 // alert(3);
			 e.preventDefault();
			 var title = $(this).attr('title');
			 var payment = $(this).attr('payment');

			 var user='<?php echo $merchant_id; ?>';
			 $(".payment_type").val(payment);
	  // var action = $(this).val() == "MYR" ? "wallet_pay.php" : "payment.php";
	  // $("#data").attr("action", action);
	   // alert(payment);
	   $(".payment_type").val(payment);
	   if((payment=='1') || (payment=="2") || (payment=="3") || (payment=="4") || (payment=="5"))
	   {
			// alert(payment);
			var title = $(this).attr('title');
			// alert(title);
			$.ajax({
				url : 'orderlist_new.php',
				type : 'post',
				dataType : 'json',   
				data: {payment: payment, user:user, method: "getPayment"},
				success: function(data){
					$("#OnlineModel").modal("hide");
				// alert(payment);
				if(payment == "1"){
					$image = "boost.png";
				} else if(payment == "2"){
					$image = "grab.jpg";
				} else if(payment == "3"){
					$image = "wechat.jpg";
				} else if(payment == "2"){
					$image = "touch.png";
				} else if(payment == "5"){
					$image = "fpx.png";
				}
				// var title = $(".text_payment").find(':selected').attr('title'); 
				
				$(".payment_header").html(title + "&nbsp <img style='width:90px;' src='images/payments/"+$image+"'>");
				$(".row").html(data['name']);
				$(".mobile").html(data['mobile']);
				$(".qr_code_image").attr({"src": "uploads/"+data['qr_code']});
				$(".reference").html(data['remark']);
				
				// alert(title);
				$('#payment_type').val(title);
				$("#paymentModal").modal("show");
			}
		}); 	
		}			

		
		
	});
	$("select.text_payment").change(function(e) {
		e.preventDefault();
		var payment=$(this).val();
		// alert(payment);
		var title = $(".text_payment").find(':selected').attr('title');   
		// var title = $(this).attr('title');
			// alert(title);
			var user='<?php echo $merchant_id; ?>';
			$(".payment_type").val(payment);
	  // var action = $(this).val() == "MYR" ? "wallet_pay.php" : "payment.php";
	  // $("#data").attr("action", action);
	   // alert(payment);
	   $(".payment_type").val(payment);
	   if((payment=='1') || (payment=="2") || (payment=="3") || (payment=="4") || (payment=="5"))
	   {
			// alert(payment);
			
			$.ajax({
				url : 'orderlist_new.php',
				type : 'post',
				dataType : 'json',
				data: {payment: payment, user:user, method: "getPayment"},
				success: function(data){
					$("#OnlineModel").modal("hide");
				// alert(payment);
				if(payment == "1"){
					$image = "boost.png";
				} else if(payment == "2"){
					$image = "grab.jpg";
				} else if(payment == "3"){
					$image = "wechat.jpg";
				} else if(payment == "2"){
					$image = "touch.png";
				} else if(payment == "5"){
					$image = "fpx.png";
				}
				var title = $(".text_payment").find(':selected').attr('title'); 
				$(".payment_header").html(title + "&nbsp <img style='width:90px;' src='images/payments/"+$image+"'>");
				$(".row").html(data['name']);
				$(".mobile").html(data['mobile']);
				$(".qr_code_image").attr({"src": "uploads/"+data['qr_code']});
				$(".reference").html(data['remark']);
				var title = $(".text_payment").find(':selected').attr('title');  
				// alert(title);
				$('#payment_type').val(title);
				$("#paymentModal").modal("show");
			}
		}); 	
		}			

		
		
	});
	function nextstep()
	{

		var online_pay='<?php echo $online_pay; ?>';
		var prepaid='<?php echo $prepaid; ?>';
		var payment_alert='<?php echo $payment_alert; ?>';
		var show_alert='<?php echo $show_alert; ?>';
		var new_order='<?php echo $new_order; ?>';
		var show_pop='<?php echo $show_pop; ?>';
		var already_login='<?php echo $login_user_id; ?>';
		var newuser='<?php echo $newuser; ?>';
		var show_alert='<?php echo $show_alert; ?>';
		var otp_verified='<?php echo $otp_verified; ?>';
		var password_created='<?php echo $password_created; ?>';
			// alert(password_created);  
			// var new_order="y";
			// var show_alert="y";
			var membership_discount_input='<?php echo $membership_discount_input;?>';
			
			$('#SectionModel').modal('hide');
			// alert(membership_discount_input);
			if(membership_discount_input)
			{
				if(!already_login)
				{
					$('#show_msg').html('<span style="font-size:20px;">😀</span>Congratulation, your order is already completed. Please login in order to claim for your '+membership_discount_input+' membership discount and to use wallet');
				}
				else
				{
					$('#show_msg').html('<span style="font-size:20px;">😀</span>Congratulation, your order is already completed. You will get '+membership_discount_input+' as membership discount');
				}  
			}
			$('#SectionModel').modal('hide');
			// alert(show_alert);
			// alert(password_created);
			// alert(otp_verified);  
			
			if((password_created=="n") && (otp_verified=="y"))
			{
				 // alert('show');   
				 $('#PasswordModel').modal('show');
				}

				if(new_order=="y")
				{

					// alert(show_alert);
				// alert(show_pop);
				if((show_alert=="y"))    
				{
					$('#newuser_model').modal('show'); 
				}   

				if((newuser=="y") && (show_alert=='y'))
				{



					if(otp_verified=="n")
					{
						$('.otp_form').show();
						var otp_count=$("#otp_count").val();
						var usermobile=$("#user_mobile").val();
						var user_id="<?php user_id;?>";
						  // var usermobile=+919001025477;
						   //target:'#picture';
							// $(this).hide();
							// alert(usermobile);
							// return false;
							var data = {usermobile:usermobile, method: "sendotpwithlink",user_id:user_id};
							$.ajax({

								url :'functions.php',
								type:'POST',
								dataType : 'json',
								data:data,   
								success:function(response){
									var data = JSON.parse(JSON.stringify(response));
									if(data.status==true)
									{
										otp_count++;
									  // alert(data.otp);
									  $("#otp_count").val(otp_count);
									  $("#system_otp").val(data.otp);
									  $(".otp_form").show(); 
									}

								}		  
							});
						   // setTimeout(function () {
											// $(".resend").show();
										// }, 15000);
									}
									else
									{

									}
								}
								if((newuser=="n") && (show_alert=='y'))
								{
				   // alert(already_login);
				   if(!already_login)
				   {
				   	<?php   unset($_SESSION['new_order']); ?>
				   	$('.login_passwd_field').show();
				   	$('.join_now').show();   
				   	$('.login_ajax').show();
					   // $(".forgot-form").show();
					}
				}

				
				

			}


		}
		$(".login_ajax").click(function(){
			$(this).removeClass(" btn-primary").addClass("btn-default");
			setTimeout(function() {

				$(this).removeClass("btn-default").addClass("btn-primary");
			}.bind(this), 5000);
			var usermobile=$("#user_mobile").val();
			var login_password=$("#login_ajax_password").val();
			var membership_applicable='<?php echo $membership_applicable;?>';
			var last_order_merchant_id='<?php echo $last_order_merchant_id;?>';
			var last_order_id='<?php echo $last_order_id;?>';
			$("#login_error").hide();
			if((login_password.length)>5)
			{
				  // alert(login_password);
				  var data = {usermobile:usermobile,login_password:login_password,membership_applicable:membership_applicable,merchant_id:last_order_merchant_id,last_order_id:last_order_id};

					// alert(data);
					$.ajax({

						url :'login.php',
						type:'POST',
						dataType : 'json',
						data:data,
						success:function(response){
							var data = JSON.parse(JSON.stringify(response)); 
							if(data.status)
							{
								var s_token=generatetokenno(16);
								var r_url="http://188.166.187.218/orderlist_new.php?vs="+s_token;
						//window.location.replace(r_url);

					}
					else
					{
						$("#login_ajax_password").val('');

						$("#login_error").html('Your password is incorrect.Please try with correct password again or Reset that with Forgot Password');
						$("#login_error").show();
					}

				}		  
			});

				}
				else
					{    $("#login_error").html('Enter Atleaset 6 digit password to make login');
				$("#login_error").show();
			}

		});
		$(".review_detail").click(function(e){
			$("#order_product_name").html("");
			$("#see_or_write_review").html('');
			var s_id = $(this).attr('order_id');
			var invoice_id = $(this).attr('invoice_id');
			var review_status = $(this).attr('review_status');
			if(review_status=="n")
			{
				var r_text="Write review for invoice no: "+invoice_id;
				$("#see_or_write_review").html(r_text);
				//alert();
				$("#order_product_name").html("Invoice no: "+invoice_id);
				$("#order_product_button").attr("order_id",5);
				$("#review_check").attr("order_id",s_id);
				$.ajax({
					type: "POST",
					url: "newreview.php",
					data: {s_id:s_id,login_as:1},
					success: function(data) {
						$('#reviewdetailmodel_load').html(data);

					},
					error: function(result) {
						alert('error');
					}
				});
				$("#reviewdetailmodel").modal("show"); 
			}
			else
			{


				var s_id = $(this).attr('order_id');
				var r_text="Your past review for invoice no: "+invoice_id;
				$("#see_or_write_review").html(r_text);
				$.ajax({
					type: "POST",
					url: "getreview.php",
					data: {s_id:s_id,login_as:1},
					success: function(data) {
						$('#reviewdetailmodel_load').html(data);

					},
					error: function(result) {
						alert('error');
					}
				});
				$("#reviewdetailmodel").modal("show"); 
			}





		});
		$(".online_ajax").click(function(){
			var wallet=$("#text_payment option:selected" ).text();
			var payment_order_id=$("#order_id").val();
			var data = {wallet:wallet,payment_order_id:payment_order_id};
			$.ajax({

				url :'payment_submit.php',
				type:'POST',
				dataType : 'json',
				data:data,
				success:function(response){

					var s_token=generatetokenno(16);
					var r_url="http://188.166.187.218/orderlist_new.php?vs="+s_token;
						//window.location.replace(r_url);
					}		  
				});
		});
		$(".bank_detail").click(function(e){
			var row = $(this).attr('row');
			$('#bank_merchant_name').html(row);   
			$("#InternetModel").modal("show"); 
		});
		$(".s_order_detail").click(function(e){
			var s_id = $(this).attr('order_id');
			$.ajax({
				type: "POST",
				url: "singleorder.php?u=y",
				data: {s_id:s_id},
				success: function(data) {
					$('#orderdata').html(data);
				},
				error: function(result) {
					alert('error');
				}
			});
			$("#orderdetailmodel").modal("show"); 
		});
		$(".register_ajax").click(function(){
			// return false;
			var password_created='<?php  echo $password_created;?>';
			// alert(password_created);
			
			$("#register_error").hide();
			$(this).removeClass(" btn-primary").addClass("btn-default");
			setTimeout(function() {  

				$(this).removeClass("btn-default").addClass("btn-primary");
			}.bind(this), 5000);
			var user_id=<?php echo $user_id; ?>;
			var order_id=$("#order_id").val();

			var login_password=$("#login_password").val();  
			var pass_length=login_password.length;
			if((pass_length==0) || (pass_length>5))
			{    
				var data = {user_id:user_id,register_password:login_password,save_password:"y"};

				$.ajax({

					url :'passwordsave.php',
					type:'POST',
					dataType : 'json',
					data:data,
					success:function(response){
						if(response==1)
						{
							var s_token=generatetokenno(16);
							var r_url="http://188.166.187.218/orderlist_new.php?vs="+s_token;
						//window.location.replace(r_url);
						$('#PasswordModel').modal('hide'); 
					}
					else
					{

							  // $("#login_error").show();
							}  

						}		  
					});
			}
			else
			{
				$("#register_error").html('The password must be six digit and above');
				$("#register_error").show();
						// $("#login_error").show();
					}


					return false;
				}); 
		$('.forgot_reset_password').click(function(){
			var forgot_register=$('#forgot_register').val();
		// alert(forgot_register);
		if((forgot_register.length)>5)
		{
			var mobile=$("#mobile_number").val();
			if(mobile[0]==0)
			{
				mobile=mobile.slice(1);
			} 
			var newpassword=$('#forgot_register').val();
			var usermobile="60"+mobile;
			if((newpassword.length)>5)
			{
				var data = {usermobile:usermobile,method:"forgotresetpassword",password:newpassword};
				$.ajax({

					url :'functions.php',
					type:'POST',  
					dataType : 'json',
					data:data,
					success:function(response){
						var data = JSON.parse(JSON.stringify(response)); 
						if(data.status)
						{
							$("#forgot_reset_password_error").hide();
							var login_user_id=$('#login_user_id').val();
							var login_user_role=data.data.user_roles;
							localStorage.setItem('login_live_id',login_user_id);    
							localStorage.setItem('login_live_role_id',login_user_role);
							$('#login_for_wallet_id').val(login_user_id);
							$('#forgot_setup').modal('hide');

							$('#show_msg').html(data.msg);
							$('#AlerModel').modal('show'); 
							setTimeout(function(){ $("#AlerModel").modal("hide"); },2000); 

						}
						else
						{   
							$("#forgot_reset_password_error").html('Failed to Reset Password');
							$("#forgot_reset_password_error").show();
						}

					}		  
				});
			}
			else
			{
				
				$("#forgot_register_error").html('Enter Atleaset 6 digit password');

				$("#forgot_register_error").show();
			}

		}
		else
		{   
			$("#forgot_reset_password_error").html('Your New Password has to be 6 digit long');
			$("#forgot_reset_password_error").show();
		}
	});	   
	       $('#forgot_partitioned').on('keyup', function(){ // consider `myInput` is class...

	       	var user_input = $(this).val();
  // alert(user_input);
  var page_otp=$("#system_otp").val();
  var usermobile="60"+$("#mobile_number").val();
   // alert(user_input);
   // alert(page_otp);
   if(user_input.length % 4 == 0){
   	if(user_input==page_otp)
   	{
   		$('.forgot_otp_error').hide();
   		$('#forgot_msg_new').html('<span style="font-size:20px;">😀</span>Please create your password');
   		$('.forgot_otp_form').hide();
   		$('.forgot_password_submit').show();   
   		$('.forgot_reset_password').show();
   		$('.forgot_register_password').show();

   	}
   	else
   	{
   		$('.forgot_otp_error').show();
   	}

   }
});
    $('#partitioned').on('keyup', function(){ // consider `myInput` is class...

    	var user_input = $(this).val();
  // alert(user_input);
  var page_otp=$("#system_otp").val();
  var usermobile=$("#user_mobile").val();
   // alert(user_input);
   // alert(page_otp);
   if(user_input.length % 4 == 0){
   	if(user_input==page_otp)
   	{
   		var data = {usermobile:usermobile,method:"otp_submit"};

   		$.ajax({

   			url :'login.php',
   			type:'POST',
   			dataType : 'json',
   			data:data,
   			success:function(response){
   				if(response==1)
   				{

   					var s_token=generatetokenno(16);
   					var r_url="http://188.166.187.218/orderlist_new.php?vs="+s_token;
						//window.location.replace(r_url);
						$('#newuser_model').modal('hide');   
					}
					else
					{
						$('#register_error').html('Something Went wrong to validate otp,try again');
						$("#login_error").show();
					}

				}		  
			});

   	}
   	else
   	{
   		$('.otp_error').show();
   	}

   }
});
</script>




<script>
	setInterval(function(){ 



		var s_token=generatetokenno(16);
		var r_url="http://188.166.187.218/orderlist_new.php?vs="+s_token;
						//window.location.replace(r_url);


					}, 
					60000);    

				</script>
				<script type="text/javascript">


              //For Review Script
              var review_q1 = 0;
              var review_q2 = 0;
              var review_q3 = 0;
              var review_q4 = 0;
              var review_q5 = 0;
              var review_q6 = 0;
              var review_q7 = 0;
              var review_q8 = 0;
              var review_q9 = 0;
              var review_q10 = 0;

              var q1=1;
              var q2=1;
              var q3=1;
              var q4=1;
              var q5=1;
              var q6=1;
              var q7=1;
              var q8=1;

              function review_q1_clear() {
              	$("#review_q1_a1").attr("src", "assets/img/smile/laughing_grey.png");
              	$("#review_q1_a2").attr("src", "assets/img/smile/happy_grey.png");
              	$("#review_q1_a3").attr("src", "assets/img/smile/surprised_grey.png");
              	$("#review_q1_a4").attr("src", "assets/img/smile/sad_grey.png");
              	$("#review_q1_a5").attr("src", "assets/img/smile/verysad_grey.png");
              }
              function review_q2_clear() {
              	$("#review_q2_a1").attr("src", "assets/img/smile/laughing_grey.png");
              	$("#review_q2_a2").attr("src", "assets/img/smile/happy_grey.png");
              	$("#review_q2_a3").attr("src", "assets/img/smile/surprised_grey.png");
              	$("#review_q2_a4").attr("src", "assets/img/smile/sad_grey.png");
              	$("#review_q2_a5").attr("src", "assets/img/smile/verysad_grey.png");
              }


              $(document).ready(function(){

              //update image on click
              //q1
              $("#review_q1_a1").click(function(){
              	review_q1_clear();
              	review_q1=1;
              	q1=1;
              	$("#review_q1_a1").attr("src", "assets/img/smile/laughing_green.png");
              });
              $("#review_q1_a2").click(function(){
              	review_q1_clear();
              	review_q1=1;
              	q1=2;
              	$("#review_q1_a2").attr("src", "assets/img/smile/happy_green.png");
              });
              $("#review_q1_a3").click(function(){
              	review_q1_clear();
              	review_q1=1;
              	q1=3;
              	$("#review_q1_a3").attr("src", "assets/img/smile/surprised_green.png");
              }); 
              $("#review_q1_a4").click(function(){
              	review_q1_clear();
              	review_q1=1;
              	q1=4;
              	$("#review_q1_a4").attr("src", "assets/img/smile/sad_green.png");
              }); 
              $("#review_q1_a5").click(function(){
              	review_q1_clear();
              	review_q1=1;
              	q1=5;
              	$("#review_q1_a5").attr("src", "assets/img/smile/verysad_green.png");
              });
               //q2
               $("#review_q2_a1").click(function(){
               	review_q2_clear();
               	review_q2=1;
               	q2=1;
               	$("#review_q2_a1").attr("src", "assets/img/smile/laughing_green.png");
               });
               $("#review_q2_a2").click(function(){
               	review_q2_clear();
               	review_q2=1;
               	q2=2;
               	$("#review_q2_a2").attr("src", "assets/img/smile/happy_green.png");
               });
               $("#review_q2_a3").click(function(){
               	review_q2_clear();
               	review_q2=1;
               	q2=3;
               	$("#review_q2_a3").attr("src", "assets/img/smile/surprised_green.png");
               }); 
               $("#review_q2_a4").click(function(){
               	review_q2_clear();
               	review_q2=1;
               	q2=4;
               	$("#review_q2_a4").attr("src", "assets/img/smile/sad_green.png");
               }); 
               $("#review_q2_a5").click(function(){
               	review_q2_clear();
               	review_q2=1;
               	q2=5;
               	$("#review_q2_a5").attr("src", "assets/img/smile/verysad_green.png");
               });


               



           });

              $("#marchant_review_button").click(function(){
              	$("#merchant_review").css("display", "block");
              	$("#deliveryman_review").css("display", "none");

              	if ( $("#merchant_review_button").hasClass('btn-secondary') )  
              		$("#merchant_review_button").addClass('btn-primary').removeClass('btn-secondary');

              	if ( $("#deliveryman_review_button").hasClass('btn-primary') )  
              		$("#deliveryman_review_button").addClass('btn-secondary').removeClass('btn-primary');



              });

              $("#deliveryman_review_button").click(function(){
              	$("#merchant_review").css("display", "none");
              	$("#deliveryman_review").css("display", "block");
              	$("#your_feedback").css("display", "none");

              	if ( $("#merchant_review_button").hasClass('btn-primary') )  
              		$("#merchant_review_button").addClass('btn-secondary').removeClass('btn-primary');

              	if ( $("#deliveryman_review_button").hasClass('btn-secondary') )  
              		$("#deliveryman_review_button").addClass('btn-primary').removeClass('btn-secondary');

              });
              function skip_review(){
              	var s_id = $("#review_check").attr('order_id');
              	$.ajax({

              		url :'skiped_review.php',
              		type:'POST',
              		data:{
              			order_id : s_id,
              		},
              		success:function(response){
              			$('#reviewdetailmodel').modal('hide');
              		}     
              	});

              }
       //nikhil-->
       function checkNext(){
       	var s_id = $("#review_check").attr('order_id');
			  // alert(s_id);
			  $("#review_error").html("");
			  $('#review_check').hide();
			  $('#review_skip').hide();

			  var ele = document.getElementsByName('clarification'); 
			  var q9;
			  for(i = 0; i < ele.length; i++) { 
			  	if(ele[i].checked) 
			  		q9=ele[i].value; 
			  }
			  var q10 = $.trim($("#addiComments").val());

			  $.ajax({

			  	url :'review_insert.php',
			  	type:'POST',
			  	data:{
			  		order_id : s_id,
			  		q1 : q1,
			  		q2 : q2,
			  		q3 : q2,
			  		q4 : q4,
			  		q5 : q5,
			  		q6 : q6,
			  		q7 : q7,
			  		q8 : q8,
			  		q9 : q9,
			  		remark : q10,

			  	},  
			  	success:function(response){
			  		$("#review_model").html("<center><p style='color:green;'>Thanks for Review to improve our System. We appreciate your feedback.</p></center>"); 
			  		$("#review_check").css("display", "none");

                        // setTimeout(function(){
							// var s_token=generatetokenno(16);
						// var r_url="http://188.166.187.218/orderlist_new.php?vs="+s_token;
						// window.location.replace(r_url);
                        // }, 5000);

                    }     
                });   
			}
		</script>

		<!--nikhil--->

		<!--payment proof--->
		<script>
			jQuery(document).on("click", ".browse", function() {
				var file = $(this)
				.parent()
				.parent()
				.parent()
				.find(".file");
				file.trigger("click");
			});

			$('input[type="file"]').change(function(e) {
				var fileName = e.target.files[0].name;
				$(".payment_proof").val(fileName);

	  /*var reader = new FileReader();
	  reader.onload = function(e) {
		// get loaded data and render thumbnail.
		document.getElementById("preview").src = e.target.result;
	  };
	  // read the image file as a data URL.
	  reader.readAsDataURL(this.files[0]);*/
	});


			$(document).ready(function(e) {
				$(".image-form").on("submit", function() {
					var orderid = $(this).attr('orderid');  
					console.log(orderid);
					$("#msg").html('<div class="alert alert-info"><i class="fa fa-spin fa-spinner"></i> Please wait...!</div>');
					var formData = new FormData(this);
			//formData.append('file', this.files[0]);
			formData.append('orderid', orderid);
			var payment_proof = $(".payment_proof").val();
			$(".payment_proof").css('border','');
			if( payment_proof == ''){
				$(".payment_proof").css('border','1px solid red');
				return false;
			}else{
				$.ajax({
					type: "POST",
					url: "action_image_ajax.php",
					data: formData,
				  //data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
				  contentType: false, // The content type used when sending data to the server.
				  cache: false, // To unable request pages to be cached
				  processData: false, // To send DOMDocument or non processed data file it is set to false
				  success: function(data) {
				  	location.reload(true);
				  	$("#image-form_"+orderid).hide();
					  //$(".bank_detail").append('<label class="btn-sm btn-yellow" style="background-color:#6ea6d6;margin-top:10px;width:150px">Payment Proof<a href="javascript:void(0)" class="delete_paymentproof" orderid='+orderid+'><i class="fa fa-trash" style="margin-left:20px"> </i></a></label>');
					/*if (data == 1 || parseInt(data) == 1) {
					  $("#msg").html(
						'<div class="alert alert-success"><i class="fa fa-thumbs-up"></i> Data updated successfully.</div>'
					  );
					} else {
					  $("#msg").html(
						'<div class="alert alert-info"><i class="fa fa-exclamation-triangle"></i> Extension not good only try with <strong>GIF, JPG, PNG, JPEG</strong>.</div>'
					  );
					}*/
				},
				error: function(data) {
					$("#msg").html(
						'<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> There is some thing wrong.</div>'
						);
				}
			});
			}
			
		});

				$(".delete_paymentproof").click(function(){
					var orderid = $(this).attr('orderid');
					var cnfrmDelete = confirm("Are You Sure want to delete this payment proof?");
					if(cnfrmDelete==true){
						$.ajax({
							url:'orderlist_new.php',
							method:'GET',
							data:{
								data:'deleteRecord',
								orderid:orderid
							},
							success:function(res){location.reload(true);}
						});	
					}
				});

				$(document).ready(function() {
					$(".fancybox").fancybox({
						openEffect	: 'none',
						closeEffect	: 'none'
					});
				});  

			});


		//window.print();
	</script>
<!-- End Payment proof-->