<?php 
include("config.php");
$me="comission_list";
if(!isset($_SESSION['login']))
{
	header("location:login.php");
}
$loginidset=$_SESSION['login'];
$userq=mysqli_query($conn,"select * from users where id='$loginidset'");
$userdata=mysqli_fetch_array($userq);
// print_R($userdata);
// die;
function random_strings($length_of_string) 
{ 
    $str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz'; 
    return substr(str_shuffle($str_result), 0, $length_of_string); 
} 
	
if($userdata['user_refferal_code']=='')
{
	$user_refferal_code=random_strings(8);
  	mysqli_query($conn,"update users set user_refferal_code='$user_refferal_code' where id='$loginidset'");
}
else
{
	$user_refferal_code=$userdata['user_refferal_code'];
}
// echo $user_refferal_code;
// die;    
if(isset($_POST['submit']))
{
	// print_R($_POST);
	// die;
	extract($_POST);
	$merq=mysqli_query($conn,"select * from users where id='$m_id'");
	$merchantdata=mysqli_fetch_array($merq);
	 $link=$site_url."/view_merchant.php?sid=".$merchantdata['mobile_number']."&r_code=".$user_refferal_code;
	 $q="INSERT INTO `comission_list` (`user_id`, `merchant_id`,`reffereal_link`,`refferal_code`) VALUES ('$loginidset', '$m_id', '$link','$user_refferal_code')";    
	mysqli_query($conn,$q);
	// die;
	header('Location: comission_list.php');

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
										<h2><?php echo $language['add_refferal_code']; ?></h2>
									</div>
									
								<div class="form-group">
									<?php 
									 $q="SELECT id,name FROM users WHERE user_roles = '2' and name!='' and isLocked='0' and show_merchant='1' and id not in(select merchant_id from comission_list where user_id='$loginidset') ORDER BY name ASC";
									 
									 	$qMerchant = mysqli_query($conn,$q);
									 
									 
									
									?>
										<label for="tags_merchant">Choose a merchant</label>
									
									
									<select class="tags_merchant_select" name='m_id'>
									    <option value='-1'>Select Merchant</option>
										<?php 
										  
											while($row = mysqli_fetch_assoc($qMerchant)){ ?>
									       <option <?php if($m_id==$row['id']){ echo "selected";} ?> value="<?php  echo $row['id'];?>"><?php echo $row['name'];?></option>
											<?php } ?>

									</select>
								</div> 
									
									<button type="submit" value="submit" name="submit" id="formSubmit" class="btn btn-primary">Add</button>
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
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script type="text/javascript">

$(document).ready(function() {
    $(".tags_merchant_select").select2();
});
</script>
    <script src="http://ajax.aspnetcdn.com/ajax/modernizr/modernizr-2.8.3.js"></script>
    <script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="http://code.jquery.com/ui/1.11.1/jquery-ui.min.js"></script>

   
    