<?php
include("config.php");

function ceiling($number, $significance = 1)
{
	return ( is_numeric($number) && is_numeric($significance) ) ? (ceil(round($number/$significance))*$significance) : false;
}
   //ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
$riderid = '';
$showadmin_msg = 'no';
if($_GET['r_id'] && $_GET['r_id']== ''){
	$showadmin_msg = 'yes';
}else{
	$riderid = $_GET['r_id'];
	$query_r = "SELECT * FROM `tbl_riders` where r_status = 1 and r_id = ".$riderid;
	#echo $query_r;
	$result = mysqli_fetch_assoc(mysqli_query($conn, $query_r));

	if(count($result) == 0){
		$showadmin_msg = 'yes';
	}
}

$rider_links = "https://www.koofamilies.com/riders.php?rider=".base64_encode($_GET['r_id']);
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
		.sidebar-expand .content-wrapper, .sidebar-collapse .content-wrapper {
			padding-top: 0px !important;
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
		<?php //include("includes1/navbar.php"); ?>
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
							
							$rider_pending_cash_price = '0.00';
							$rider_pen_commission = '0.00';
							$rider_pending_cash = mysqli_query($conn,"SELECT sum(rc_cash_price) as sum_cash,sum(rc_commission) as commisssion FROM `riders_cash_history` where rc_r_id = ".$riderid." and rc_handover_admin = 0");
							$rider_cash_asscoc = mysqli_fetch_assoc($rider_pending_cash);
							$rider_pending_cash_price = $rider_cash_asscoc['sum_cash'];
							$rider_pen_commission = $rider_cash_asscoc['commisssion'];
							?>
							<div class="order_n_div slick-slide slick-cloned slick-active rider_details">
								<div class="order_head_line">
									<div>
										<span class="n_order_no">Riders: <?php echo $result['r_name'] ."(+". $result['r_mobile_number'].")";?></span>
										
									</div>
									
									<div>
										<b>Cash Boss:</b> 
										<span style="color:red;font-weight:bold">RM <?php echo number_format($rider_pending_cash_price,2);?></span>
										|
										<b> Comm: </b>
										<span style="color:red;font-weight:bold">RM <?php echo number_format($rider_pen_commission,2);?></span>
										<a class="mr-2"  target="_blank" href="riderscash.php?r_id=<?php echo $riderid;?>" title="Cash History">
											<i class="fa fa-eye" aria-hidden="true" style="color:black"></i>
										</a>
									
									
									</div>

<!--
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
									</span>-->

									<input type="hidden" name="hidden_rider_id" id="hidden_rider_id" value="<?php echo $riderid;?>"/>


								</div>


								
								
								<div class="n_order_body ">
									<div class="">
									<div class="text-right mt-2 mb-3">
									<a class="" href="<?php echo $rider_links;?>" style="cursor:pointer">Back</a>
									</div>
									
								<?php
								$rider_casharray = mysqli_query($conn,"SELECT od.invoice_no,od.created_on,mer.name,rc.* FROM `riders_cash_history` as rc Inner join order_list as od ON od.id = rc.rc_od_id Inner Join users as mer ON mer.id = od.merchant_id where rc.rc_r_id ='".$riderid."' and rc.rc_cash_price != ''");
								
							?>
								<?php /*if($ordersCount == 0){?>
								
										<div class="col-lg-12 text-center alert alert-danger m-0">
											<p class="mb-0">No orders assign Now!!</p>
										</div>	
									
								<?php }*/?>
								
								
								
								<table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col" width="57%">Order Details</th>
	  <th scope="col" width="41%">Riders Price</th>
    </tr>
  </thead>
  <tbody>
  <?php  
  $i = 1;
  while($row_cash = mysqli_fetch_assoc($rider_casharray)){?>
    <tr>
      <th scope="row"><?php echo $i;?></th>
	  
      <td>
	  <b><?php echo $row_cash['name'];?></b>
	  <br/>
	  <b>Invoice: </b>#<?php echo $row_cash['invoice_no'];?>
	  <br/>
	  <b>Date: </b><?php echo $row_cash['created_on'];?>
	  </td>
      <td> <b>Cash: </b><?php echo "RM ".number_format($row_cash['rc_cash_price'],2);?>
	  <br/>
	  <b>Comm: </b><?php if($row_cash['rc_commission']!= ''){echo "RM ".number_format($row_cash['rc_commission'],2);}?>
	  <br/>
	  <?php 
	  if($row_cash['rc_handover_admin'] == 1){
		  $pay_status = 'paid';
		  $pay_date = date('Y-m-d',strtotime($row_cash['rc_handover_date']));
		  $style='style="cursor:pointer;color:white;background-color:green;border:green"';
	  }else{
		  $pay_status = 'Pending';
		  $pay_date = '';
		   $style='style="cursor:pointer;color:white;background-color:red;border:red"';
	  }
	  ?>
	  <a href="javascript:void(0);" class="btn btn-sm btn-primary"  <?php echo $style;?> ><?php echo $pay_status;?></a>
	  <br/>
	  <?php echo $pay_date;?>
	  
	  </td>
      
    </tr>
  <?php $i++;}?>
  </tbody>
</table>
											
							

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

	
</script>	