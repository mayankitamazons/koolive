<?php 
include("config.php");



if(isset($_POST['submit']))
{
	
	
	$wallet = addslashes($_POST['wallet']);
	$mount_actual = addslashes($_POST['amount_actual']);
	$user_name = addslashes($_POST['user_name']);
	
	$flag = false;
	$error = "";
	
	
	
	if(!is_numeric($amount_actual) || $amount_actual == 0)
	{
		$error .= "Amount is not Valid.<br>";
		$flag = true;
	}
		
	$balance = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name,balance_usd,balance_inr,balance_myr FROM users WHERE id='".$user_name."'"));

	if($wallet == "INR")
		{
			$sender_new_balance = $balance['balance_inr'] + $mount_actual;
			mysqli_query($conn, "UPDATE users SET balance_inr='$sender_new_balance' WHERE id='$user_name'");
			//~ mysqli_query($conn, "UPDATE tranfer SET status='1' WHERE id='$tra_id'");
		 }
		else if($wallet == "MYR")
		{
			 $sender_new_balance = $balance['balance_myr'] + $mount_actual;
			 mysqli_query($conn, "UPDATE users SET balance_myr='$sender_new_balance' WHERE id='$user_name'");
		}
		else if($wallet == "USD")
		{
			 $sender_new_balance = $balance['balance_usd'] + $mount_actual;
			mysqli_query($conn, "UPDATE users SET balance_usd='$sender_new_balance' WHERE id='$user_name'");
			//~ mysqli_query($conn, "UPDATE tranfer SET status='1' WHERE id='$tra_id'");
		}
		else
		{
					$error = "Your Request has been Successfully Submitted.";

		}
		

	
	
	
	

}
$a_m="wallet";
?>

<!DOCTYPE html>
<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">

<head>
<style>
.pagination {
  display: inline-block;
  padding-left: 0;
  margin: 20px 0;
  border-radius: 4px;
 }
 .pagination>li {
  display: inline;
 }
 .pagination>li:first-child>a, .pagination>li:first-child>span {
  margin-left: 0;
  border-top-left-radius: 4px;
  border-bottom-left-radius: 4px;
 }
 .pagination>li:last-child>a, .pagination>li:last-child>span {
  border-top-right-radius: 4px;
  border-bottom-right-radius: 4px;
 }
 .pagination>li>a, .pagination>li>span {
  position: relative;
  float: left;
  padding: 6px 12px;
  margin-left: -1px;
  line-height: 1.42857143;
  color: #337ab7;
  text-decoration: none;
  background-color: #fff;
  border: 1px solid #ddd;
 }
 .pagination a {
  text-decoration: none !important;
 }
 .pagination>.active>a, .pagination>.active>a:focus, .pagination>.active>a:hover, .pagination>.active>span, .pagination>.active>span:focus, .pagination>.active>span:hover {
  z-index: 3;
  color: #fff;
  cursor: default;
  background-color: #337ab7;
  border-color: #337ab7;
 }
</style>
 
    <?php include("includes1/head.php"); ?>
</head>

<body class="header-light sidebar-dark sidebar-expand pace-done">

<!--    <div class="pace  pace-inactive">
        <div class="pace-progress" data-progress-text="100%" data-progress="99" style="transform: translate3d(100%, 0px, 0px);">
            <div class="pace-progress-inner"></div>
        </div>
        <div class="pace-activity"></div>
    </div>
    -->

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
	<div class="col-md-3"></div>
					<div class="well col-md-6">
						<form method="post" onsubmit="return submitForm()">
							<div class="panel price panel-red input-has-value" style="padding:50px 5px;">
																<h2>Add Member/Client Wallet</h2>
								<br><br>
								
								<select class="form-control" name="user_name" id="user_name" required="true">
									<option value="">Select Member/Client Name</option>
								<?php $user = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");
								
								while ($row=mysqli_fetch_assoc($user)){ ?>
									<option value="<?php echo $row[id] ?>"> <?php echo $row[name] ?></option>
									
								<?php }
								?>
								</select>
									
								<br><br>
								<select class="form-control" name="wallet" onchange="amountExec()" id="wallet"required="true" >
									<option value="">Select Wallet</option>
									<option value="MYR">Malaysian Ringgit</option>
									<option value="USD">US Dollar</option>
									<option value="INR">Chinese Yuan</option>
								</select>
								<br><br>
								<input type="number" step="0.01" class="form-control" onkeyup="amountExec()" name="amount_actual" id="amount_actual" placeholder="Amount You Want" required="true" >
								<br><br>
							
								<textarea class="form-control" name="user_note" placeholder="You Can Add Details"></textarea>
								
								<div class="input-group mb-3">
<!--
								  <div class="input-group-prepend">
									<button class="btn btn-outline-secondary" id="generateCode" type="button">Generate</button>
								  </div>
-->
								 
								</div>
								<br><br>
								<input type="submit" class="btn btn-block btn-primary" name="submit" value="Request">
							</div>
						</form>
					</div>
					<div class="col-md-3"></div>
  
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
<style>
	.well.col-md-6 {

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
</html>
