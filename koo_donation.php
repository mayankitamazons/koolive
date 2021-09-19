<?php error_reporting(0);
   include("config.php");?>
   <?php 
   function ceiling($number, $significance = 1)
	{
		return ( is_numeric($number) && is_numeric($significance) ) ? (ceil(round($number/$significance))*$significance) : false;
	}
	
	
	
if($_POST['type'] == 'view_more'){
	
	$query_times = "SELECT * FROM `users` where id IN (".$_POST['dn_userid'].")";
	$u_22 = mysqli_query($conn,$query_times);
	$td_rows = '';
	$s = 1;
	echo $query_times;
	while($rowData = mysqli_fetch_assoc($u_22)){
		$number = substr($rowData['mobile_number'], 0, 6)."***".substr($rowData['mobile_number'], 10, 11);
		$td_rows .='<tr><td>'.$s.'</td><td>'.$rowData['name'].'</td><td>'.$number.'</td><td>RM 5.00</td></tr>';
		$s++;
	}
	echo $td_rows; exit;
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
						
						<h3><?php echo $language['donation_label'];?></h3>  
						
							
						
							<!-- NEW DESIGN END ----->
							
							<!-- PRoduct Details-->
							<div class="form-row">
								<div class="col-md-12">
									<div class="m-0 slick-slide slick-cloned slick-active n_products_details order_details n_orderdetails">
										<div class="order_head_line">
											<span class="n_order_no w-100"><?php echo $language['donation_label'];?></span>
										</div>
										<div class="orderdetail-table table-responsive p-3">

											
											<table class="table">
												<thead>
													<tr>
														<th scope="col" class="first-head" >#</th>
														<th scope="col" class="" style="width:100px"><?php echo $language['donation_date'];?></th>
														<th scope="col" class="third-head"><?php echo $language['donation_amount'];?></th>
														<th scope="col" class="third-head"><?php echo $language['donation_payto'];?></th>
														<!--<th scope="col" class="third-head">Proof</th>-->
														
													</tr>
													</thead>
													<tbody>
													
													<?php 
											
													$query="SELECT * FROM `tbl_donation` where  dn_date < '".date('Y-m-d')."' order by dn_date desc ";
													$donation = mysqli_query($conn,$query);
													$donation_rows = mysqli_num_rows($donation);
													$i =1;
													if($donation_rows > 0){
													while($donation_result = mysqli_fetch_assoc($donation)){
                                
													?>
													
														<tr>
															<th class="mobile-hide first-head" data-label="#" scope="row"><?php echo $i;?></th>
															<th class="mobile-hide first-head" data-label="#" scope="row"><?php echo $donation_result['dn_date'];?>
															<p style="font-size:12px"><a class="view_details" style="color:black" dn_userid="<?php echo $donation_result['dn_userid'];?>" dn_id = "<?php echo $donation_result['dn_id']; ?>" href="javascript:void(0)"><!----><b><?php echo $language['donation_list_of_donor'];?></b><i class="fa fa-eye" style="padding-left:2px"></i></a>
															</p>
															<?php if($donation_result['dn_receipt'] != ''){?>
															<p style=" background: lightgreen; color: white;padding: 10px; width: 122px;">
															<a class="fancybox" rel="" href="<?php echo $image_cdn; ?>donation/<?php echo $donation_result['dn_receipt']; ?>" style="color:#000">
															Donation Proof </a>
															</p>
															<?php }?>
															
															
															</th>
															<th class="mobile-hide first-head" data-label="#" scope="row"><?php echo "RM ".number_format($donation_result['dn_total']);?></th>
															<th class="mobile-hide first-head" data-label="#" scope="row">
															<?php if($donation_result['dn_payto'] != ''){?>
															<a href="https://m.facebook.com/story.php?story_fbid=1784816628392782&id=100005933858318" target="_blank" style="color:black"><?php echo $donation_result['dn_payto'];?></a>
															<?php }?>
															
															
															
															
															
															<?php if($donation_result['dn_payto'] == ''){?>
															<p style="color:red">Donation in process.</p>
															<?php }?>
															
															</th>
															<!--<th class="mobile-hide first-head" data-label="#" scope="row">
															<?php if ($donation_result['dn_receipt']) {  ?>
																	<img ref="dn_receipt" src="<?php echo $image_cdn; ?>donation/<?php echo $donation_result['dn_receipt']; ?>?w=200" class="owl-lazy lazy2 Sirv" alt="">
																<?php }?>
															</th>-->
															
														</tr>
														<?php 
														
														$i++;
														$j++;}
														?>
													<?php }else{?>
														<tr>
															<td colspan="5" style="text-align: center;
    color: red;
    font-weight: bold;"> Donation not proceed yet!!</td>
														</tr>
													<?php }?>
														
							 
								
													</tbody>
												</table>
											</div>


										</div>
									</div>
								</div>


								<!-- END Product Details -->



								<!-- /.widget-body badge -->
							<!-- /.widget-bg -->
							<!-- /.content-wrapper -->
							<?php include("includes1/commonfooter.php"); ?>




							<a href="https://chat.whatsapp.com/LdvomJRqXoIG6aXqsf5PgX" target="_blank"><img src ="images/iconfinder_support_416400.png" style="width:75px;height:75px;position: fixed;left:15px;bottom: 70px;z-index:999;"></a>
							<link rel="stylesheet" href="css/fancybox.css" type="text/css" media="screen" />
							<script type="text/javascript" src="js/fancybox.js"></script>

						

						</body>
						</html>

<div id="myModal_time" class="modal fade" style="">
    <div class="modal-dialog" style="padding-top:70px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo $language['donation_list_of_donor'];?>
				
				 </h5>
               <button type="button" class="close" data-dismiss="modal" style="padding: 0px;top: 0em;  right: 0em;width: 2em; height: 2em;">×</button>
            </div>
            <div class="modal-body">
				<table class="table table-striped kType_table" id="kType_table">
					    <thead>
					        <tr>
                                <th>SR</th>
                                <th>UserName</th>
								<th>PhoneNumber</th>
                                <th>Amount</th>
						  </tr>
					    </thead>
					   <tbody class="times_more">
					   </tbody>
				</table>
            </div>
			<div class="modal-footer">
		
			</div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
	$(".view_details").click(function(){
		var dn_userid = $(this).attr('dn_userid');
		var dn_id  = $(this).attr('dn_id');
		var cartData = {};
		cartData['dn_userid'] = dn_userid;
		cartData['dn_id'] = dn_id;
		cartData['type'] = 'view_more';
		jQuery.post('/koo_donation.php', cartData, function (result) {
			
			$(".times_more").html(result);
			$("#myModal_time").modal('show');
		});
			
	});
	
$(".fancybox").fancybox({
		openEffect	: 'none',
		closeEffect	: 'none'
	});	
});

</script>