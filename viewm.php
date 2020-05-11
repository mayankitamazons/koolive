<?php
include("config.php");
if(!empty($_GET['sid'])){
	$sid = $_GET['sid'];
	$product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SQL_NO_CACHE * FROM users WHERE users.mobile_number='$sid' and users.user_roles='2'"));
	$merchant_detail=$product;
	$merchant_name = $product['name'];
	$merchant_mobile_number = $product['mobile_number'];
	if($merchant_mobile_number=="60127771833")
	$_SESSION["langfile"] = "chinese";   
	$_SESSION['invitation_id'] = $product['referral_id'];
	$_SESSION['merchant_id'] = $product['id'];
	$_SESSION['address_person'] = $product['address'] ;
	$_SESSION['latitude'] = $product['latitude'] ; 
	$_SESSION['longitude'] = $product['longitude'] ;
	$_SESSION['IsVIP'] = $product['IsVIP'] ;
	$_SESSION['mm_id']= $product['id'];
	$_SESSION['block_pay']="n";
}

?>
<!DOCTYPE html>
<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">
<head>
	<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
	<meta http-equiv="pragma" content="no-cache" />
	<meta http-equiv="expires" content="0" />
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
	<?php include("includes1/headwithmainfest.php"); ?>
	<link rel="stylesheet" type="text/css" href="css/v.css">
	
	<script src="https://scripts.sirv.com/sirv.js"></script>
	 <script type="text/javascript">
		var subproducts_global = [];
		var products_id_global = [];
		var lastAdd = null;
	</script>
</head>
<body  class="header-light sidebar-dark sidebar-expand pace-done">
<?php 
        if($merchant_detail['custom_message']!=''){  
			$merchant_message =$merchant_detail['custom_message'];
			$custom_msg="y";

        ?>
		<div class="modal-backdrop show"></div> 
			<div class="modal in" id="merchant_message" tabindex="-1" role="dialog" data-show="true" style="display:block;">
			<div class="modal-dialog modal-dialog-centered" role="document">

            <div class="modal-content">

              <div class="modal-header">

			   <h5 class="modal-title" id="exampleModalCenterTitle"><?php echo $merchant_message; ?> <!--span style="color: #f00"><?php echo $merchant_detail['name'];?></span!--></h5>

			   <button type="button" class="merchant_close" data-dismiss="modal" aria-label="Close">

            <span aria-hidden="true">&times;</span>

          </button>

			  

			 </div>

              <div class="modal-body">

			  <?php if($merchant_detail['custom_msg_image']){?>

                 <img class="img-responsive" style="margin:0 auto;" src="customimage/<?php echo $merchant_detail['custom_msg_image'];?>" alt="<?php echo $merchant_message; ?>">

             

			  <?php } else { ?>

			  <p><?php  echo $merchant_message; ?></p>

			  <?php } ?>

               

              </div>

            </div>

          </div>

        </div>

      <?php  } ?>
	   <div class="pace  pace-inactive">

        <div class="pace-progress" data-progress-text="100%" data-progress="99" style="transform: translate3d(100%, 0px, 0px);">

            <div class="pace-progress-inner"></div>

        </div>

        <div class="pace-activity"></div>

    </div>
	<div id="wrapper" class="wrapper">
	<?php include("includes1/navbar.php"); ?>

        <!-- /.navbar -->

        <div class="content-wrapper">

            <!-- SIDEBAR -->

            <?php include("includes1/sidebar.php"); ?>
			 <main class="main-wrapper clearfix" style="min-height: 522px;">
			 <div class="row" id="main-content" style="padding-top:25px">
			     <input type="hidden" id="shop_status" value="<?php echo $merchant_detail['shop_open']; ?>"/>
			 </div>
			 </main>
			
			
		</div>
	</div>
</body>
