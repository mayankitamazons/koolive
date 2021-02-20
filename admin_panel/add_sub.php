<?php 
include("config.php");

//~ //if(!isset($_SESSION['login']))
//~ //{
	//~ //print_r($_SESSION);
//~ //exit;
	//~ //header("location:login.php");
//~ //}
//~ print_r($bank_data);

//~ $bank_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$_SESSION['login']."'"));
 //~ $current_id = $bank_data['id'];
//~ exit;
if(isset($_POST['submit']))
{
	//~ print_r($_POST);
		$productname = addslashes($_POST['productname']);
		//~ $category = addslashes($_POST['category']);
		$product_type = addslashes($_POST['product_type']);
		$period = addslashes($_POST['period']);
		$package_fee = addslashes($_POST['package_fee']);
		$catgeories = addslashes($_POST['catgeories']);
		$product_type = addslashes($_POST['product_type']);
		$website = addslashes($_POST['website']);
		$video = addslashes($_POST['video']);
		$sms = addslashes($_POST['sms']);
		$about = addslashes($_POST['about']);
		$referral = addslashes($_POST['referral']);
		$welcome = addslashes($_POST['welcome']);
		$restaurant = addslashes($_POST['restaurant']);
		$renewal = addslashes($_POST['renewal']);
	//insert code //
	$test_code=   mysqli_query($conn, "INSERT INTO subscription SET subscription_name='$productname',subscription_rate='$package_fee', subscription_qyt='$product_type',about='$about',subscription_period='$period',categories='$catgeories',company_website='$website',company_video='$video',advertisement_sms='$sms',referral_commision='$referral',welcome_note='$welcome',search_resataraunt='$restaurant',renewal_fee='$renewal'");

}
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
					        <div class="well col-md-10">
							<form method="post" method="post" enctype="multipart/form-data">
								<div class="panel price panel-red">
									<h2>Package Details</h2>
									<br><br>
								<div class="form-group">
										<label>Package Name</label>
										<input type="text" name="productname" class="form-control" value="" required>
									</div>
								<div class="form-group">
										<label>Subscritpion Period</label>
										<select class="form-control" name="period">
											<option value="">Select Options</option>
											<option value='1 month' >1 month</option>
											<option value='1 year' >1 year</option>
										</select>
<!--
										<input type="text" name="period" class="form-control" value="" required>
-->
									</div>
										<div class="form-group">
										<label>Package Fee</label>
										<input type="text" name="package_fee" class="form-control" value="" required>
									</div>
								<div class="form-group">
										<label>Categories Created</label>
										<select class="form-control" name="catgeories">
											<option value="">Select Options</option>
											<option value='Yes' >Yes</option>
											<option value='No' >No</option>
										</select>
									</div>
									
									<div class="form-group">
										<label>Product Listing</label>
										<input type="text" name="product_type" class="form-control" value="" required>
									</div>
								
								<div class="form-group">
										<label>Display of company website</label>
										<select class="form-control" name="website">
											<option value="">Select Options</option>
											<option value='Yes' >Yes</option>
											<option value='No' >No</option>
										</select>
								<div class="form-group">
										<label>Upload of company video</label>
										<select class="form-control" name="video">
											<option value="">Select Options</option>
											<option value='Yes' >Yes</option>
											<option value='No' >No</option>
										</select>
									</div>
								<div class="form-group">
										<label>Advertisement sms </label>
										<select class="form-control" name="sms">
											<option value="">Select Options</option>
											<option value='Yes' >Yes</option>
											<option value='No' >No</option>
										</select>
									</div>
								<div class="form-group">
										<label>About Us </label>
										<input type="text" name="about" class="form-control" value="" required>
									</div>
								<div class="form-group">
										<label> Referral commission</label>
										<select class="form-control" name="referral">
											<option value="">Select Options</option>
											<option value='Yes' >Yes</option>
											<option value='No' >No</option>
										</select>
									</div>
								<div class="form-group">
										<label>  Merchant welcome note</label>
										<select class="form-control" name="welcome">
											<option value="">Select Options</option>
											<option value='Yes' >Yes</option>
											<option value='No' >No</option>
										</select>
									</div>
								<div class="form-group">
										<label>Search nearby restaurant</label>
										<select class="form-control" name="restaurant">
											<option value="">Select Options</option>
											<option value='Yes' >Yes</option>
											<option value='No' >No</option>
										</select>
									</div>
									<div class="form-group">
										<label>Renewal Fee</label>
										<input type="text" name="renewal" class="form-control" value="" >
									</div>
									<br>
									<input type="submit" class="btn btn-block btn-primary" name="submit" value="Submit">
								</div>
							</form>
						</div>
						
					</div>
				</div>
			</main>
        </div>
        <!-- /.widget-body badge -->
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
