<?php 
include("config.php");

$admindata = mysqli_query($conn, "SELECT * FROM admins");
$data=mysqli_fetch_array($admindata);
extract($data);


if(!isset($_SESSION['admin']))
{
	header("location:login.php");
}


if(isset($_POST['update_setting'])){
 extract($_POST);
 print_R($_POST);
 if($order_mobile_no)
 {
	echo  $i="UPDATE `admins` SET `order_mobile_no` = '$order_mobile_no',`order_mobile_no_2` = '$order_mobile_no_2',`order_mobile_no_3` = '$order_mobile_no_3',`order_mobile_no_4` = '$order_mobile_no_4'";
	 $insert=mysqli_query($conn,$i);
	if($insert)
	{
		$_SESSION['show_msg']="Record Updated Successfully";
		header('Location:sms_setting.php');
	}		   
 }
}
	
$a_m="sms_setting";
?>
<!DOCTYPE html>
<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">

<head>
    <?php include("includes1/head.php"); ?>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="/css/dropzone.css" type="text/css" /> 
    <link rel="stylesheet" href="./css/chosen.min.css" type="text/css" /> 
	
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

            <div class="row">
                <div class="col-md-12">
                    <h3>
                        Sms Setting for Order Mobile 
                    </h3>
					<form method="post">
						<div class="row">
							<div class="col-md-3 col-sm-12">
								<div class="form-group">
									<label for="name">Mobile 1</label>
									<input type="text" class="form-control" id="order_mobile_no" value="<?php echo $order_mobile_no; ?>" name="order_mobile_no" required placeholder="Order Mobile No 1">
								</div>
							</div>
							<div class="col-md-3 col-sm-12">
								<div class="form-group">
									<label for="name">Mobile 2</label>
									<input type="text" class="form-control" id="order_mobile_no_2" value="<?php echo $order_mobile_no_2; ?>" name="order_mobile_no_2"  placeholder="Order Mobile No 2">
								</div>
							</div>
							<div class="col-md-3 col-sm-12">
								<div class="form-group">
									<label for="name">Mobile 3</label>
									<input type="text" class="form-control" id="order_mobile_no_3" value="<?php echo $order_mobile_no_3; ?>" name="order_mobile_no_3"  placeholder="Order Mobile No 3">
								</div>
							</div>
							<div class="col-md-3 col-sm-12">
								<div class="form-group">
									<label for="name">Mobile 4</label>
									<input type="text" class="form-control" id="order_mobile_no_4" value="<?php echo $order_mobile_no_4; ?>" name="order_mobile_no_4"  placeholder="Order Mobile No 4">
								</div>
							</div>
							


							
							<div class="col-md-12" style="margin-top: 20px;">
							   <input type="submit" class="btn btn-lg btn-outline-primary" name="update_setting" value="Update"/>
								
							</div>   
						</div>
					</form>
                </div>
            </div>
			</main>
        </div>
      
        <!-- /.widget-body badge -->
    </div>
    <!-- /.widget-bg -->

    <!-- /.content-wrapper -->
	<?php include("includes1/footer.php"); ?>
	<script type="text/javascript" src="/js/dropzone.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>




</body>

</html>

