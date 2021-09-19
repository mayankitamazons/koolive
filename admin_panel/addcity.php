<?php 
include("config.php");

if(!isset($_SESSION['admin']))
{
	header("location:login.php");
}

$a_m="city";
if(isset($_POST['addcity'])){
    extract($_POST);
    // print_r($_POST);


    
    $post_date = strtotime(Date("Y-m-d"));
    $expire_date = strtotime($exDate);
    
      $q="INSERT INTO `city`(`CityName`, `StateName` , `status`) 
	VALUES ('$city_name','$state_name' , 1)";
    $query = mysqli_query($conn,$q);
    
    if($query){
        header('Location: city.php');
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
            <h3 class="mb-3">Add City</h3>
            <form action="addcity.php" method="POST">
                <div class="form-group">
                    <label for="title">State Name<span style="color:red;">*</span></label>
                    <input type="text" required name="state_name" class="form-control"  placeholder="State Name">
                </div>
                
                <div class="form-group">
                    <label for="title">City Name<span style="color:red;">*</span></label>
                    <input type="text" name="city_name" class="form-control" required  placeholder="City Name">
                </div>
		
                <input type="submit" name="addcity" class="btn btn-lg btn-outline-primary" id="submitForm" value="Add">
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
               