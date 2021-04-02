<?php 
include("config.php");

if(!isset($_SESSION['login']))
{
	header("location:login.php");
}
$bank_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$_SESSION['login']."'"));

 $current_id = $bank_data['id'];
  $current_uid = $_SESSION['login'];
 
if(isset($_POST['submit']))
{
	if($_POST['Update']=="Update")
	{
		$day = addslashes($_POST['day']);
		$start_time = addslashes($_POST['start_time']);
		$end_time = addslashes($_POST['end_time']);
		$id=$_GET['Edit'];
	  	 $insert_test  =  mysqli_query($conn, "UPDATE timings SET day='$day',start_time='$start_time',end_time='$end_time' where merchant_id='$current_uid' && id='$id'");
	  	 if($insert_test)
	{
		$error = "Data Updated Successfully.";
		echo"<script> setTimeout(function() {
		    $('#error').fadeOut('hide');
		}, 500);</script>";

	}else
	{
		$error = "Data Updated Unsuccessfully.";
	}
	}

header('Location: newtimings.php');


		
	 // header('Location: '.$_SERVER['REQUEST_URI']);

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
					<div class="container" id="error">
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
							<form  method="post" enctype="multipart/form-data">
								<div class="panel price panel-red">
									<h2>Timing / Working Hours.</h2>
									<br><br>
									<?php 
									 $edit_id = $_GET['Edit'];
										$whr = mysqli_query($conn, "select * from timings where id='$edit_id'");
										$row = mysqli_fetch_array($whr);
										$s = $row['start_time'];
										$e = $row['end_time'];
									    $day = ucfirst($row['day']);
									?>
									
									<div  class="row col-md-12">
									<div  class="col-md-3">
									
									<label>Set Work day</label>
									<select class="form-control" name="day" required>
												<option value=''>Select Day</option>
												
												<option value="Monday" <?php if($day=="Monday"){ echo "selected";} ?>>Monday</option>
											<option value="Tuesday" <?php if($day=="Tuesday"){ echo "selected";} ?>> Tuesday</option>
											<option value="Wednesday" <?php if($day=="Wednesday"){ echo "selected";} ?>>Wednesday</option>
											<option value="Thursday" <?php if($day=="Thursday"){ echo "selected";} ?>>Thursday</option>
											<option value="Friday" <?php if($day=="Friday"){ echo "selected";} ?>>Friday</option>
											<option value="Saturday" <?php if($day=="Saturday"){ echo "selected";} ?>>Saturday</option>
											<option value="Sunday" <?php if($day=="Sunday"){ echo "selected";} ?>>Sunday</option>';
											

		
											
										</select>
									</div>

									
							
								
									<div class="col-md-3">
										<label>Start time</label>
										<input type="time" id="start_time" class="form-control" name="start_time" min="9:00" max="24:00" value="<?php echo $s; ?>" required> 
									</div>
									<div class="form-group col-md-2">
										<label></label>
										<h5>To</h5>
									</div>

										<div class="col-md-3">
											<label>End time</label>
											<input type="time" id="end_time" class="form-control" name="end_time" min="9:00" max="24:00" value="<?php echo $e;?>" required>
										</div>
								</div>
							<br>
									<input type="submit" class="btn btn-block btn-primary" name="submit" value="Update">
								</div>
								<input type="hidden" name="Update" value="Update">
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
