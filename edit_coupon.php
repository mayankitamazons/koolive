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
if(isset($_POST['submit']))
{	
	extract($_POST);   
	$total_min_price = $_POST['total_min_price'];
	$total_max_price = $_POST['total_max_price'];
	if($valid_from)
	$valid_from = date('Y-m-d', strtotime($_POST['valid_from']));
	else 
	$valid_from='';
	if($valid_to)
	$valid_to = date('Y-m-d', strtotime($_POST['valid_to']));
	else
	$valid_to='';
	$title = $_POST['title'];
	$discount = $_POST['discount'];
	$coupon_code = $_POST['coupon_code'];
	$valid_user = $_POST['valid_user'];
	$type = $_POST['type'];
	$status =1;
	$description = $_POST['description'];
	$user_id = $_SESSION['login'];
	$created = date('Y-m-d H:i:s');
	
	mysqli_query($conn, "UPDATE `coupon` SET `title`='$title',`coupon_code`='$coupon_code',`discount`='$discount',`total_min_price`='$total_min_price',`total_max_price`='$total_max_price',`valid_from`='$valid_from',`valid_to`='$valid_to',`type`='$type',`valid_user`='$valid_user',`description`='$description',`status`='$status' WHERE `id` = '".$plan_id."'");
	header('Location: coupon_list.php');



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
								<form method="post" enctype="multipart/form-data" id="profile_account" action="">
									<div class="panel price panel-red">
										<h2>Edit Coupon Code</h2>
									</div>
									<div class="form-group">
									  <div class="row">
										<div class="col-md-8">
										    <label><?php echo $language['title']; ?></label>
											<input type="text" id="plan_name" required maxlength="200" name="title" class="form-control" placeholder="Title" value="<?php echo $plan_data['title'] ?>">
											<span id="matchNameResponse"></span>
										</div>
									<!--div class="col-md-6">
												<label>Plan Image</label>
												<input type="file" name="plan_image" class="form-control">
												<img src="uploads/<?php echo $plan_data['plan_img'] ?>" alt="plan_img" style="width: 100px">
											</div!-->
										
										</div>
									</div>
									<div class="form-group">
									  <div class="row">
									        <div class="col-md-4">
    											<label><?php echo $language['coupon_code']; ?></label>
    											<input type="text" required name="coupon_code" id="couponcode" class="form-control" placeholder="<?php echo $language['coupon_code']; ?>" value="<?php echo $plan_data['coupon_code'] ?>">
    											<span id="matchCodeResponse"></span>
    										</div>
    										<div class="col-md-4">
    											<label>Total Coupon </label>
    											<input type="number" required name="valid_user" class="form-control" placeholder="Number of Coupon" value="<?php echo $plan_data['valid_user'] ?>">
    										</div>
									    </div>
									</div>
									<div class="form-group">
										<div class="row">
											
											<!--div class="col-md-4">
												<label>Plan Buy Amount</label>
												<input type="text" value='0' oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" maxlength="4" name="plan_amount" class="form-control" placeholder="Plan Amount" value="<?php echo $plan_data['plan_amount'] ?>">
											</div!-->
											<div class="col-md-4">
												<label><?php echo $language['order_min']; ?> Amount</label>
												<input type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" maxlength="4" name="total_min_price" class="form-control" placeholder="<?php echo $language['order_min']; ?> Amount" value="<?php echo $plan_data['total_min_price'] ?>">
										</div>
										<div class="col-md-4">
												<label><?php echo $language['order_max']; ?> Amount</label>
												<input type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" maxlength="4" name="total_max_price" class="form-control" placeholder="<?php echo $language['order_max']; ?> Amount" value="<?php echo $plan_data['total_max_price'] ?>">
										</div>
										</div>																		
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-md-4">
												<label><?php echo $language['discount']; ?> on order</label>
												<input type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"  maxlength="3" name="discount" class="form-control" placeholder="<?php echo $language['discount']; ?> on order" value="<?php echo $plan_data['discount'] ?>">
											</div>
											<div class="col-md-4">
												<label><?php echo $language['discount']; ?> Type</label>
												<select class="form-control" name="type" required>
													<option value="">Select Type</option>
													<option value="fix" <?php if ($plan_data['type'] == 'fix') {
														echo "selected";
													} ?>>Fix</option>
													<option value="per" <?php if ($plan_data['type'] == 'per') {
														echo "selected";
													} ?>>Per</option>
												</select>
											</div>
											
										</div>																		
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-md-12">
												<label>Plan Description</label>
												<textarea class="form-control" name="description"><?php echo $plan_data['description'] ?></textarea>
											</div>
										</div>																		
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-md-4">
												<label><?php echo $language['valid_from']; ?></label>
												<input type="date"  name="valid_from" class="form-control" placeholder="<?php echo $language['valid_from']; ?>" value="<?php echo date('Y-m-d', strtotime($plan_data['valid_from'])) ?>">
											</div>
											<div class="col-md-4">
												<label><?php echo $language['valid_to']; ?></label>
												<input type="date" name="valid_to" class="form-control" placeholder="<?php echo $language['valid_to']; ?>" value="<?php echo date('Y-m-d', strtotime($plan_data['valid_to'])) ?>">
											</div>
											<div class="col-md-4">
												<label>Plan Status</label>
												<div class="form-group">
													<input type="hidden" name="plan_status" value="0">
												<input type="checkbox" name="plan_status" class="form-check-inline" value="1" placeholder="Active" <?php if ($plan_data['status'] == 1) {
													echo "checked";
												} ?>>Active
												</div>
												<!-- <select class="form-control" name="plan_status" required>
													<option value="">Select Status</option>
													<option value="1">Active</option>
													<option value="0">Inactive</option>
												</select> -->
											</div>
										</div>																		
									</div>
									<button type="submit" id="formSubmit" value="submit" name="submit" class="btn btn-primary">Submit</button>
								</form>
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
<script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
<script src="jquery-1.9.1.min.js"></script>
<script>
	$('document').ready(function() {
   	$('#plan_name').blur(function() {
    	var name = $(this).val();
    	var plan_id = <?php echo $_GET['coupon_id'] ?>;
    	macthName(name, plan_id);
	});
   	function macthName(keyword1, keyword2) {
       var data1 = keyword1;
       var data2 = keyword2;
   	   $.ajax({
   	       method: 'get',
   	       url: "https://www.koofamilies.com/demo2/match_title_edit.php",
           data: { string: data1, plan_id: data2},
           success: function(response) {
               $('#matchNameResponse').html(response);
           }
       });
   };
});

</script>
<script>
	$('document').ready(function() {
   	$('#couponcode').blur(function() {
    	var name = $(this).val();
    	var plan_id = <?php echo $_GET['coupon_id'] ?>;
    	macthCode(name, plan_id);
	});
   	function macthCode(keyword1, keyword2) {
       var data1 = keyword1;
       var data2 = keyword2;
   	   $.ajax({
   	       method: 'get',
   	       url: "https://www.koofamilies.com/demo2/match_coupon_edit.php",
           data: { string: data1, plan_id: data2},
           success: function(response) {
               $('#matchCodeResponse').html(response);
           }
       });
   };
});
   
</script>




    <script src="http://ajax.aspnetcdn.com/ajax/modernizr/modernizr-2.8.3.js"></script>
    <script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="http://code.jquery.com/ui/1.11.1/jquery-ui.min.js"></script>

   
    