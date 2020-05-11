<?php 
include("config.php");

if(!isset($_SESSION['admin']))
{
	header("location:login.php");
}

if(isset($_POST['submit']))
{
	$old_password = addslashes($_POST['old_password']);
	$new_password = addslashes($_POST['new_password']);
	$confirm_new_password = addslashes($_POST['confirm_new_password']);
	
	$flag = false;
	$error = "";
	
	if($old_password == "" || $new_password == "" || $confirm_new_password == "")
	{
		$flag = true;
		$error .= "All Fields are required.<br>";
	}
	
	$user_old_password = mysqli_fetch_assoc(mysqli_query($conn, "SELECT password FROM admins WHERE id='".$_SESSION['admin']."'"))['password'];
	if($user_old_password != $old_password)
	{
		$flag = true;
		$error .= "Old Password is incorrect.<br>";
	}
	
	if(strlen($new_password) < 7 || strlen($new_password) > 15)
	{
		$flag = true;
		$error .= "New Password must between 7 to 15 characters.<br>";
	}
	
	if($new_password != $confirm_new_password)
	{
		$flag = true;
		$error .= "New Password does not match.<br>";
	}
	
	if($flag == false)
	{
		mysqli_query($conn, "UPDATE admins SET password='$new_password' WHERE id='".$_SESSION['admin']."'");
		$error = "Password Successfully Changed.";
	}
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
						if(isset($error) && $error != "")
						{
							echo "<div class='alert alert-info'>".$error."</div>";
						}
					?>
					</div>
					<form method="post" class="well col-md-6" style="margin:0px auto;">
						<h3>Change Password</h3>
						<div class="form-group">
							<label>Old Password</label>
							<input type="password" class="form-control" name="old_password" required>
						</div>
						<div class="form-group">
							<label>New Password</label>
							<input type="password" class="form-control" name="new_password" required>
						</div>
						<div class="form-group">
							<label>Confirm New Password</label>
							<input type="password" class="form-control" name="confirm_new_password" required>
						</div>
						<input type="submit" value="Change Password" name="submit" class="btn btn-lg btn-block">
					</form>
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