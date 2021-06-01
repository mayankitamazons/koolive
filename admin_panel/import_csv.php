<?php 
include("config.php");

if($_GET['mode'] == 'post'){
	
}else{
	unset($_SESSION['errors_fp']); 
	unset($_SESSION['success_fp']); 
}
if(!isset($_SESSION['admin']))
{
	header("location:login.php");
}

$a_m="foodpanda";

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
						<div class="card">
							<div class="card-header">
                        		<h3>Upload CSV</h3>
                    		</div>
							
							<div class="card-body">
							<?php if(count($_SESSION['errors_fp']) > 0 ){
								foreach($_SESSION['errors_fp'] as $ef => $eval){
								echo '<div class="alert alert-danger" role="alert">'.$eval.'</div>';
								}
							}?>
							<?php if($_SESSION['success_fp'] != ''){
								
								echo '<div class="alert alert-success" role="alert">'.$_SESSION['success_fp'] .'</div>';
							}?>
							<form action="db_csv.php" method="POST" class = "form-inline1 form_csv" role = "form" enctype="multipart/form-data">
								
								<?php 
								$city_query = mysqli_query($conn,'SELECT * FROM `city`');
								?>
								


								<div class = "form-group">
									<select class="form-control" name="city_name" id="city_name" style="width:150px" >
										<option value="0">Select City</option>
										<?php while($city_name = mysqli_fetch_assoc($city_query)){?>	
										<option value="<?php echo $city_name['CityName'];?>"><?php echo $city_name['CityName'];?></option>
										<?php }?>
                                    </select>
								</div>
								<br/>
								<div class = "form-group">
									<label class = "sr-only" for = "inputfile">File input</label>
									<input type = "file" name="image" id = "inputfile">
									<br/>
									<span class="please_wait" style="display:none;color: red;float: right;width: 100%;font-weight: bold;">PLEASE WAIT.....</span>
										
								</div>
								<br/>
								<button type = "submit" id="upload_submit" name="addCat" class = "btn btn-success ml-2 addCat">SUBMIT</button>
								
							
							</form>
							</div>
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
	<script type="text/javascript" src="/js/dropzone.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
	


<script>
$(document).ready(function(){
	
	$(".form_csv").submit(function(){
		var city_name = $("#city_name").val();
		$('#city_name').css({border: ''});
		$('#inputfile').css({border: ''});
		if(city_name  == 0){
			$("#city_name").css('border','2px solid red');
			return false;
		}else if($("#inputfile").val() == ''){
			$("#inputfile").css('border','2px solid red');
			return false;
		}else{
			$(this).attr('disabled','disabled');
			$(".please_wait").show();
			return true;
		}
		
		
	});
	/*
	
	$("#inputfile").click(function(){
		$("#upload_submit").removeAttr('disabled');
		$(".please_wait").hide();
		$(".alert-danger").hide();
		$(".alert-success").hide();
		
		
	});*/
});
</script>


	
	
</body>

</html>
