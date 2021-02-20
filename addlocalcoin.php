<?php 
include("config.php");
if(!isset($_SESSION['login']))
{
	header("location:login.php");
}

if(isset($_GET['mobile']))
{
	$mobile_get = $_GET['mobile'];
	$plan_id = $_GET['plan_id'];
}
else
{
	header('location: dashboard.php');
}

$date = date('Y-m-d H:i:s');
// print_R($bank_data);
// die;
if(isset($_POST['submit']))
{	
	$mobile = $_POST['mobile_number'];
	$mobile_check = $_POST['mobile_number'];
	$local_coin = $_POST['local_coin'];
	
	// $data = mysqli_num_rows(mysqli_query($conn, "SELECT users.id,user_id FROM `users` INNER JOIN `user_membership_plan` ON users.id = user_membership_plan.user_id WHERE `mobile_number`= $mobile and `merchant_id` = $subscription_mer_id"));
	$data = mysqli_num_rows(mysqli_query($conn, "SELECT id,name,mobile_number FROM `users` WHERE `mobile_number`= $mobile"));
	if ($data >0) {
		$data_user = 0;
		$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id,name,mobile_number FROM `users` WHERE `mobile_number`= $mobile"));
		$user_doesnt_exist = 0;
		$successful = 0;
		$user_id = $user['id'];
		// $plan_id = $_GET['plan_id'];
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
			
			$insert=mysqli_query($conn, "INSERT INTO `local_coin_sync`(`merchant_id`, `user_id`, `user_mobile`, `local_coin`,`order_date`) VALUES('$merchant_id', '$user_id', '$mobile', '$local_coin','$date')");
			$successful = 1;
			if($insert)
			{
				// upgrade user plan 
				$upgrade=upgradeplan($conn,$user_id,$merchant_id,$mobile_check);   
				if($plan_id)
				{
					// $ul="show_subscription.php?plan_id=".$plan_id;
					header('Location:show_subscription.php?plan_id=' . $plan_id);
				}
				else
				{
				header('Location:smemberlist.php');	
				}
			}
		}
	}else{
		$data_user = 1;
	}
}
function upgradeplan($conn,$user_id,$merchant_id,$mobile_check)
{
	$date = date('Y-m-d H:i:s'); 
	$m_id=$merchant_id;
	$defalut_plan="select count(plan.id) as total_count,u.created from membership_plan as plan inner join user_membership_plan as u on u.plan_id=plan.id where plan.user_id='$m_id' and plan.default_plan='y'
				and u.user_id='$user_id'";
	$defalutarray = mysqli_fetch_assoc(mysqli_query($conn,$defalut_plan));
	$defalutplan=$defalutarray['total_count'];
	if($defalutplan>0)
	{
		$created_date=$defalutarray['created'];
		$total_shop=mysqli_fetch_assoc(mysqli_query($conn,"select sum(total_cart_amount) as total_order_amount from order_list where user_id='$user_id' and merchant_id='$m_id' and created_on>='$created_date'"));
		if($total_shop['total_order_amount'])
		{
			$total_shop_amount=number_format($total_shop['total_order_amount'],2);
			$total_shop=$total_shop['total_order_amount'];
		}
		else
		{
			$total_shop_amount=0;
			$total_shop=0;
		}
		$local_coin = mysqli_fetch_assoc(mysqli_query($conn,"select sum(local_coin) as total_coin from local_coin_sync where merchant_id='$m_id' and user_mobile='$mobile_check' and order_date>='$created_date'"));
		if($local_coin['total_coin']>0)
		{
			$total_shop=$local_coin['total_coin']+$total_shop;
		}
    		
		  $query="SELECT user_membership_plan.*, membership_plan.user_id as memberplan_user, membership_plan.* FROM user_membership_plan INNER JOIN membership_plan ON membership_plan.id = user_membership_plan.plan_id WHERE user_membership_plan.plan_active='y' and user_membership_plan.user_id='$user_id' and user_membership_plan.merchant_id = '$m_id' and membership_plan.total_max_order_amount>='$total_shop' and  membership_plan.total_min_order_amount<='$total_shop'";
		// check local order synch coin detail 
		// echo "select sum(local_coin) as total_coin from local_coin_sync where merchant_id='$m_id' and user_mobile='$mobile_check' and order_date>='$created_date'";
		// die;
		
		
		$user_plan = mysqli_fetch_assoc(mysqli_query($conn,$query));
		// print_R($user_plan);
		// die;
		$membership_plan_id=$user_plan['plan_id'];
		if(is_null($user_plan)){
				$plan_detail = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM membership_plan WHERE user_id='$m_id' and $total_shop BETWEEN total_min_order_amount AND total_max_order_amount;"));
				// print_r($plan_detail);
				// die;
				if(!is_null($plan_detail)){
								// disactive all old membership 
								$past_plan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM user_membership_plan WHERE user_id='$user_id' and merchant_id='$m_id'"));
								if($past_plan)
								{
									$is_upgrade="y";
								}
								else
								{
									$is_upgrade="n";
								}
								mysqli_query($conn,"update user_membership_plan set plan_active='n' where user_id='$user_id' and merchant_id='$m_id'");
								$token_membership=gen();
								$user_member_plan = "INSERT INTO `user_membership_plan`(`user_id`,`user_mobile`,`merchant_id`, `plan_id`, `paid_via`, `paid_date`, `plan_active`, `created`, `is_upgrade`,`token_membership`)
								VALUES ('$user_id','$mobile_check','$m_id','".$plan_detail['id']."','Cash','$date','y','$date','$is_upgrade','$token_membership')";
								$user_plan_list = mysqli_query($conn, $user_member_plan);
								$membership_plan_id = mysqli_insert_id($conn); 
							}
							
							
						}  
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
					        		<h4>Local Point Added Successfully!</h4>
					        	<?php endif ?>
					        	<?php if ($user_doesnt_exist): ?>
					        		<h4>The specified User Doesn't Exist!</h4>
					        	<?php endif ?>
					        	<?php if ($data_user): ?>
					        		<h4>The specified User Doesn't Exist!</h4>
					        	<?php endif ?>
								<form method="post" enctype="multipart/form-data" id="profile_account" action="">
									<div class="panel price panel-red">
										<h2>Add Local Point </h2>
									</div>
									<div class="row">
										<div class="form-group col-md-4">
											<label>User Mobile Number</label>
											<input type="text" required name="mobile_number" value="<?php echo $mobile_get; ?>" class="form-control" placeholder="Enter User's Mobile Number">
										</div>
										<div class="form-group col-md-4">
											<label>Local point</label>
											<input type="text" required name="local_coin" class="form-control" placeholder="Local Point">
										</div>
									</div>
									<button type="submit" value="submit" name="submit" class="btn btn-primary">Submit</button>
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

   
    