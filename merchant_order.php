<?php error_reporting(0);
   include("config.php");?>
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
<?php 
function ceiling($number, $significance = 1)
{
	return ( is_numeric($number) && is_numeric($significance) ) ? (ceil(round($number/$significance))*$significance) : false;
}
?>
<style>
.table td, .table th {
    padding: 4px !important;
   
}
</style>

	<div class="pace  pace-inactive">
		<div class="pace-progress" data-progress-text="100%" data-progress="99" style="transform: translate3d(100%, 0px, 0px);">
			<div class="pace-progress-inner"></div>
		</div>
		<div class="pace-activity"></div>
	</div>
	<div id="wrapper" class="wrapper">
		<!-- HEADER & TOP NAVIGATION -->
		<?php //include("includes1/navbar.php"); ?>
		<!-- /.navbar -->
		<div class="content-wrapper1">
			<!-- SIDEBAR -->
			<?php //include("includes1/sidebar.php"); ?>
			<!-- /.site-sidebar -->
			<main class="main-wrapper clearfix  orderlistdetail-wrapper" style="min-height: 522px;margin-left:0px;!important;padding:0px !important">
				<div class="row" id="main-content" style="padding-top:3px">
					<div class="well od-row-wrapper ">
						
						<?php
						$dt = new DateTime();
						$today =  $dt->format('Y-m-d');
						$today_order = explode(" ",$created_new);
						?>
							
							
							<?php 
							if($_SESSION['login'])
							{
							   $user_id=$_SESSION['login'];
							}
							if($_GET['orderid'] && $_GET['orderid'] != ''){
								$wh_od = " order_list.id = ".$_GET['orderid'];
							}
							$query="SELECT  order_list.id as order_id,order_list.order_extra_charge as od_extra_charge,order_list.*, sections.name as section_name,m.id as merchant_id,m.name as merchant_name,m.sst_rate as m_sst_rate,m.mobile_number as merchant_mobile_number,m.google_map as mer_add,m.*,u.id as user_id,u.* FROM order_list left join 
							 sections on order_list.section_type = sections.id inner join users as m on m.id=order_list.merchant_id  left 
							join users as u on u.mobile_number=order_list.user_mobile 
							 WHERE ".$wh_od." ORDER BY `created_on` DESC ";
							// echo $query;
							$total_rows = mysqli_query($conn,$query);
							$fetchData = mysqli_fetch_assoc($total_rows);
	 
							
							
							$product_ids = explode(",",$fetchData['product_id']);
							$quantity_ids = explode(",",$fetchData['quantity']);
							$product_code = explode(",",$fetchData['product_code']);
							$remark_ids = explode("|",$fetchData['remark']);
							$c = array_combine($product_ids, $quantity_ids);
							$amount_val = explode(",",$fetchData['amount']);
							
							
							$amount_data = array_combine($product_ids, $amount_val);
							$total_data = array_combine($quantity_ids, $amount_val);
                   
							$section_type=$fetchData['section_type'];
							if($section_type)
							{
							  $section_type = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name FROM sections WHERE id ='".$section_type."'"));
							}
								
						
							
							?>
							<div class="form-row">
								<div class="col-lg-12">
									<div class="order_n_div slick-slide slick-cloned slick-active order_details "  style="padding:0px;!important">
										<div class="order_head_line">
											<span class="n_order_no w-100"><?php echo $fetchData['merchant_name'];?>  </span>
											<span class="fnt13"><b>Tel.No.</b>: +<?php echo $fetchData['merchant_mobile_number'];?> </span>
										</div>
										<div class="n_order_body form-row" style="padding:0px;!important">
											<div class="n_order_mer col-lg-12">
												<div class="n_mer_name">
													
													<span class="fnt13"><b>Address</b>: <?php echo $fetchData['mer_add'];?></span>
													<?php if($fetchData['remark_extra'] != ''){?>
													<br>
													<span class="fnt13"><b>Order Remark</b>: <?php echo $fetchData['remark_extra'];?></span>
													<?php }?>
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
											<span class="n_order_no w-100">Invoice: # <?php echo $fetchData['invoice_no'];?></span>
										</div>
										<div class="orderdetail-table table-responsive p-3">

											
											<table class="table">
												<thead>
													<tr>
														<th scope="col" class="first-head"  >#</th>
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
													//echo $v_str;
													//print_R($v_array); 
													
													$i = 1;
													$j = 0;
												
													foreach ($product_ids as $key ){
														$product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id ='".$key."'"));
                                
													?>
													
														<tr>
															<th class="mobile-hide first-head" data-label="#" scope="row"><?php echo $i;?></th>
															<td data-label="Products" class="secnd-head"><?php echo $product['product_name'];?>
																<br/>
																
																<p class="moretext" id="moretext_<?php echo $key;?>" style="display:block;margin-bottom:0px!important;">
																	<span><strong>Unit Price : </strong> <?php echo "RM ".$amount_val[$j];?><br/>
																	<span><strong>Qty : </strong> <?php echo $quantity_ids[$j];?></span>
																	<?php if($remark_ids[$j] != ''){?>
																	<br/>
																	<span><strong>Remark : </strong> 
																	<?php echo $remark_ids[$j];?>
																	</span>
																	<?php }?>
																	<?php if($v_array[$j] != ''){?>
																	<br/>
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
																	<?php }?>
																</p>
															</td>
															<td data-label="Final Price" class="third-head">
															
															<?php 
															
															$p_total = 0;
															if( $quantity_ids[$j]) {
																$p_total =  $p_total + ($quantity_ids[$j] *$amount_val[$j] );
															} 
															echo  "RM ".@number_format($p_total, 2);
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
													?><tr class="table-borderless">	
															<td class="mobile-hide"  colspan="2"><span class="costright">Total</span></td>
															<td  data-label="Subtotal"><?php  echo "RM ".number_format($total,2);?></td>
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
