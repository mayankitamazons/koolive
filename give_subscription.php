<?php 
include("config.php");
$memebership_tab="y";
$me="subscription_plan";
if(!isset($_SESSION['login']))
{
	header("location:login.php");
}
$subscription_set = mysqli_fetch_assoc(mysqli_query($conn, "SELECT membership_plan FROM users WHERE id ='".$_SESSION['login']."'"));
$subscription_set = $subscription_set['membership_plan'];
if ($subscription_set == 0 || empty($subscription_set)) {
	header('location: dashboard.php');
}
$plan_id = $_GET['plan_id'];
$subscription_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id,user_id,plan_name FROM membership_plan WHERE id='$plan_id' and user_id ='".$_SESSION['login']."'"));
$subscription_mer_id = $subscription_name['user_id'];
$subscription_id = $subscription_name['id'];
$subscription_name = $subscription_name['plan_name'];

$site_url="https://www.koofamilies.com/";
$bank_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$_SESSION['login']."'"));
// $plans_available = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM membership_plan WHERE user_id='".$_SESSION['login']."'"));

$sql = "SELECT * FROM membership_plan WHERE user_id='".$_SESSION['login']."'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $plans_available[] = $row;
    }
} else {
    echo "0 results";
}

// print_R($bank_data);
// die;
if(isset($_POST['submit']))
{	
	$mobile = $_POST['mobile_number'];
	if (substr($mobile, 0,2) === "60") {
			
	}
	else
	{
		$mobile="60".$mobile;
	}
	$plan_id = $_GET['plan_id'];
	// echo "SELECT users.id,user_id FROM `users` INNER JOIN `user_membership_plan` ON users.id = user_membership_plan.user_id WHERE `mobile_number`= $mobile and `merchant_id` = $subscription_mer_id and `plan_id` = $plan_id and plan_active='y'";
	// die;
	$data = mysqli_num_rows(mysqli_query($conn, "SELECT users.id,user_id FROM `users` INNER JOIN `user_membership_plan` ON users.id = user_membership_plan.user_id WHERE `mobile_number`= $mobile and `merchant_id` = $subscription_mer_id and `plan_id` = $plan_id and plan_active='y'"));
	
	if ($data <= 0) {
		$data_user = 0;
		$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id,name,mobile_number FROM `users` WHERE `mobile_number`= $mobile"));
		$user_doesnt_exist = 0;
		$successful = 0;
		$user_id = $user['id'];
		$plan_id = $_GET['plan_id'];
		$merchant_id = $_SESSION['login'];
		$paid_via = "cash";
		$date = date('Y-m-d H:i:s');
		// var_dump($date);
		// exit;
		if (is_null($user)) {
			$user_doesnt_exist = 1;
			$successful = 0;
		}
		else{  
			mysqli_query($conn,"update user_membership_plan set plan_active='n' where user_id='$user_id' and merchant_id='$merchant_id'");
			$token_membership=gen();
			mysqli_query($conn, "INSERT INTO `user_membership_plan`(`user_id`,`user_mobile`,`merchant_id`, `plan_id`, `paid_via`, `paid_date`, `plan_active`, `created`,`token_membership`) 
			VALUES('$user_id','$mobile','$merchant_id', '$plan_id', '$paid_via', '$date', 'y', '$date','$token_membership')");  
			$successful = 1;
			if(isset($_GET['mobile']))
			{
				header('Location:memberlist.php');
			}				
		}
	}else{
		$data_user = 1;
	}
}
 function gen(){
        $num = rand(100000,999999);
$query_idgetrs = "SELECT * FROM user_membership_plan where token_membership = $num";
$idgetrs = mysqli_query($conn, $query_idgetrs);
$row = mysqli_num_rows($idgetrs);

        if($row >= 1){
        gen();
        }
        return $num;
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
					        	<?php if ($successful): ?>
					        		<h4>User Assigned Successfully!</h4>
					        	<?php endif ?>
					        	<?php if ($user_doesnt_exist): ?>
					        		<h4>The specified User Doesn't Exist!</h4>
					        	<?php endif ?>
					        	<?php if ($data_user): ?>
					        		<h4>Already Assigned!</h4>
					        	<?php endif ?>
								<form method="post" enctype="multipart/form-data" id="profile_account" action="">
									<div class="panel price panel-red">
										<h2>Give Subscription of <?php echo $subscription_name; ?></h2>
									</div>
									<div class="row">
											<div class="input-group mb-2" style="margin-bottom:0px !important;">
												<div class="input-group-prepend">
												  <div class="input-group-text" style="background-color:#51D2B7;border-radius: 5px 0 0 5px;height: 100%;padding: 0 10px;display: grid;align-content: center;">+60</div>
												</div>
											
												<input type="number" autocomplete="tel" maxlength='10' id="mobile_number" style="max-width:220px;"  class="mobile_number form-control" value="<?php  if(isset($_GET['mobile'])){ echo $_GET['mobile'];}?>" placeholder="key in phone number" name="mobile_number" required="" />
												<button type="submit" value="submit" name="submit" class="btn btn-primary">Add</button>
											</div>
										
									</div>
									
									
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
<script src="jquery-1.9.1.min.js"></script>





    <script src="http://ajax.aspnetcdn.com/ajax/modernizr/modernizr-2.8.3.js"></script>
    <script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="http://code.jquery.com/ui/1.11.1/jquery-ui.min.js"></script>

   
    