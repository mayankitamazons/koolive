<?php 
include("config.php");
$me="coupon_code_list";
if(!isset($_SESSION['login']))
{
	header("location:login.php");
}
$coupon_offer = mysqli_fetch_assoc(mysqli_query($conn, "SELECT coupon_offer FROM users WHERE id ='".$_SESSION['login']."'"));
$coupon_offer = $coupon_offer['coupon_offer'];
if ($coupon_offer == 0 || empty($coupon_offer)) {
	header('location: dashboard.php');
}
$site_url="https://www.koofamilies.com/";
$plan_id = $_GET['coupon_id'];
$bank_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$_SESSION['login']."'"));
$plan_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM coupon WHERE id='".$plan_id."'"));
// print_R($bank_data);
// die;

?>
<!DOCTYPE html>
<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">

<head>
    <?php include("includes1/head.php"); ?>
	<style>
	.well
	{
		min-height: 20px;
		padding: 19px;
		margin-bottom: 20px;
		background-color: #fff;
		border: 1px solid #e3e3e3;
		border-radius: 4px;
		-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
		box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
	}
	.account_kType{
	    margin-bottom: 10px;
	}
	/* Jupiter 24.02.19*/
	.payment_tick{
		width: 20px;
		height: 20px;
		margin-right: 15px;
	}
	.payment_label{
		margin-top: -27px;
    	margin-left: 30px;
	}
	.payment_btn{
		margin-left: 125px;
	    display: block;
	    margin-bottom: 15px;
	    margin-top: -45px;
	    line-height: 0.57143;
	}
	.custom_message_val{
		width: 100%;
		height: 200px;
		padding: 5px;
		box-sizing: border-box;
		border-radius: 5px;
		border: 1px solid #e4e9f0;
		resize: none;
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
            <?php include("includes1/sidebar.php"); ?>
            <!-- /.site-sidebar -->


            <main class="main-wrapper clearfix" style="min-height: 522px;">
                <div class="row" id="main-content" style="padding-top:25px">
					<div class="container">
					<?php
						if(isset($error))
						{
							echo "<div class='alert alert-info'>".$error."</div>";
						}
					?>
					</div>
					<div class="container" > 
					    <div class="row">
					        <div class="well col-md-12">
								<div class="row">
								<div class="col-3"><h4><small>Coupon Title:</small></h4></div>
								<div class="col-6"><h4><?php echo $plan_data['title'] ?></h4></div>
								</div>
								<div class="row">
								<div class="col-3"><h4><small><?php echo $language['coupon_code']; ?>:</small></h4></div>
								<div class="col-6"><h4><?php echo $plan_data['coupon_code'] ?></h4></div>
								</div>
								<div class="row">
								<div class="col-3"><h4><small>Description:</small></h4></div>
								<div class="col-6"><h4><?php echo $plan_data['description'] ?></h4></div>
								</div>
								<div class="row">
								<div class="col-3"><h4><small>Coupon Discount:</small></h4></div>
								<div class="col-6"><h4><?php echo 'Rs '.$plan_data['discount'] ?></h4></div>
								</div>
								<div class="row">
								<div class="col-3"><h4><small>Coupon Type:</small></h4></div>
								<div class="col-6"><h4><?php echo $plan_data['type'] ?></h4></div>
								</div>
								<div class="row">
								<div class="col-3"><h4><small><?php echo $language['valid_from']; ?>:</small></h4></div>    
								<div class="col-6"><h4><?php if($plan_data['valid_from'] && $plan_data['valid_from']!='0000-00-00 00:00:00'){echo date('F d, Y', strtotime($plan_data['valid_from']));} else {echo "--";} ?></h4></div>
								</div>
								<div class="row">
								<div class="col-3"><h4><small><?php echo $language['valid_to']; ?>:</small></h4></div>
							<div class="col-6"><h4><?php if($plan_data['valid_to'] && $plan_data['valid_from']!='0000-00-00 00:00:00'){echo date('F d, Y', strtotime($plan_data['valid_to']));} else {echo "--";} ?></h4></div>
								</div>
								<div class="row">
								<div class="col-3"><h4><small>Number of User:</small></h4></div>
								<div class="col-6"><h4><?php echo $plan_data['valid_user'] ?></h4></div>
								</div>
								<div class="row">
								<div class="col-3"><h4><small><?php echo $language['remaining_user']; ?>:</small></h4></div>
								<div class="col-6"><h4><?php echo $plan_data['remain_user'] ?></h4></div>
								</div>
								<div class="row">
								<div class="col-3"><h4><small><?php echo $language['status']; ?>:</small></h4></div>
								<div class="col-6"><h4><?php if($plan_data['status']) echo 'Active'; else echo 'Inactive' ?></h4></div>
								</div>
								<div class="row">
								<div class="col-3"><h4><small>Coupon Created on:</small></h4></div>
								<div class="col-6"><h4><?php echo date('F d, Y', strtotime($plan_data['created'])); ?></h4></div>
								</div>
								<button onclick="history.go(-1)" class="btn btn-primary">Back</button>
							</div>
						</div>
					</div>
				</div>				
			</main>
        </div>
        
    </div>
    <!-- /.widget-bg -->

    <!-- /.content-wrapper -->
    <?php include("includes1/footer.php"); ?>
</body>

</html>
<style>
select {
    height: 30px;
}
</style>

    </script>   
<style>
  .tele_num{
	font-weight: 400;
    display: block;
    width: 345%;
    padding: 0.5625rem 1.2em;
    font-size: 0.875rem;
    line-height: 1.57143;
    color: #74708d;
    background-color: #fff;
    background-image: none;
    background-clip: padding-box;
    border: 1px solid #e4e9f0;
    border-radius: 0.25rem;
    -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
    box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
    -webkit-transition: border-color ease-in-out 0.15s, -webkit-box-shadow ease-in-out 0.15s;
    transition: border-color ease-in-out 0.15s, -webkit-box-shadow ease-in-out 0.15s;
    transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;
    transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s, -webkit-box-shadow ease-in-out 0.15s;
}
h3.text_qrcode {
    width: 100%;
}
.credit_card{
 display:none;
}
.branch_details{
display:none;
}
div#multiSelectCombo {
    width: 450px!important;
}
</style>
<script src="jquery-1.9.1.min.js"></script>





    <script src="http://ajax.aspnetcdn.com/ajax/modernizr/modernizr-2.8.3.js"></script>
    <script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="http://code.jquery.com/ui/1.11.1/jquery-ui.min.js"></script>

   
    