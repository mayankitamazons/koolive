<?php 
include("config.php");

if(!isset($_SESSION['admin']))
{
	header("location:login.php");
}

$a_m="riders";
if(isset($_POST['addrider'])){
    extract($_POST);
    // print_r($_POST);


    
    $post_date = strtotime(Date("Y-m-d"));
    $expire_date = strtotime($exDate);
    
     $q="INSERT INTO `riders`(`name`, `one_singal_id`,`rider_code`,`rider_mobile_no`) 
	VALUES ('$name','$rider_mobile_no','$rider_code','$one_singal_id')";
	// die;
    $query = mysqli_query($conn,$q);
    
    if($query){
        header('Location: riders.php');
    }
	else
	{
		echo "Duplicate Entet for Rider code";
	}
}

?>
<!DOCTYPE html>
<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">

<head>   
    <?php include("includes1/head.php"); ?>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="/css/dropzone.css" type="text/css" /> 
	
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
                <div class="container-fluid" id="main-content" style="padding-top:25px">

        <div class="container">
            <h3 class="mb-3">Add Rider</h3>
            <form action="addrider.php" method="POST">
                <div class="form-group">
                    <label for="title">Rider Name<span style="color:red;">*</span></label>
                    <input type="text" name="name" class="form-control" required  placeholder="Rider Name">
                </div>
                
                <div class="form-group">
                    <label for="title">Rider Mobile<span style="color:red;">*</span></label>
                    <input type="number" required name="rider_mobile_no" min=1 class="form-control"  placeholder="Rider Mobile">
                </div>
				<div class="form-group">
                    <label for="title">Rider Code<span style="color:red;">*</span></label>
                    <input type="text" required name="rider_code" min=1 class="form-control"  placeholder="Rider Code">
                </div>
				<div class="form-group">
                    <label for="title">Push notification id</label>
                    <input type="text"  name="one_singal_id" min=1 class="form-control"  placeholder="Notifcation id">
                </div>
				
                    
                
                
                <input type="submit" name="addrider" class="btn btn-lg btn-outline-primary" id="submitForm" value="Add">
            </form>
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
    <script type="text/javascript">
	 $(document).ready(function(){
		
	 });
 </script> 
	    
</body>

</html>
               