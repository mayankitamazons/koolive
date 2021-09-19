<?php 
include("config.php");
//ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL); 
/*if(!isset($_SESSION['admin']))
{
	header("location:login.php");
}*/

if(!isset($_SESSION['madmin']))
{
	header("location:login2.php");
}


$loop_count = 10;
$terrArray = array();

if(isset($_GET['r_id']) && $_GET['r_id']!= '')
{
	$r_id = $_GET['r_id'];
	$query = mysqli_query($conn,"select * from tbl_riders where r_id=".$r_id);
	$c_data=mysqli_fetch_assoc($query);
	
	
}
else
{
	$update=0;
}   
if(isset($_POST['add_riders'])){
	 extract($_POST);
	 $r_createddate = date('Y-m-d H:i:s');
	 $r_updateddate = date('Y-m-d H:i:s');
	 $image_file = '';
	if($_FILES['r_image']['name']!=""){
		$infoExt        =   getimagesize($_FILES['r_image']['tmp_name']);
		if(strtolower($infoExt['mime']) == 'image/gif' || strtolower($infoExt['mime']) == 'image/jpeg' || strtolower($infoExt['mime']) == 'image/jpg' || strtolower($infoExt['mime']) == 'image/png'){
			$file	=	preg_replace('/\\s+/', '-', time().$file);
			$path   =   $_SERVER['DOCUMENT_ROOT'].'/admin_panel/uploads/riders/';
			//move_uploaded_file($_FILES['file']['tmp_name'],$path);
			  $name_file=date('Ymdhis');//this part is for creating random name for image
			  $ext=end(explode(".", $_FILES["r_image"]["name"]));//gets extension	
			  $image_file = $name_file.".".$ext;
			  if (move_uploaded_file($_FILES["r_image"]["tmp_name"], $path.$image_file)) {
				       
			  } 
			
		}	
	}
	$q="INSERT INTO `tbl_riders` (`r_name`, `r_mobile_number`, `r_info`, `r_image`,`r_vehicle_number`,`r_live_location`, `r_createddate`, `r_updateddate`, `r_status`,`one_signal_id`)VALUES 
	( '$r_name', '$r_mobile_number', '$r_info','".$image_file."','".$r_vehicle_number."','".$r_live_location."', '$r_createddate', '$r_updateddate',1,'$one_signal_id')";
	$insert=mysqli_query($conn,$q);
	$last_id = mysqli_insert_id($conn);
	
	$r_link = $site_url."/riders.php?rider=".base64_encode($last_id);
	$update_qry="UPDATE `tbl_riders` SET `r_link`='$r_link' WHERE `r_id` = '".$last_id."'";    
	$update_link = mysqli_query($conn,$update_qry);
	
	if($insert){
        header('Location: riders.php');
    }
			

}
if(isset($_POST['update_riders'])){
	 extract($_POST);
	 $r_updateddate = date('Y-m-d H:i:s');
	 $r_createddate = date('Y-m-d H:i:s');
	 
	// $image_file = '';
	 
	if($_FILES['r_image']['name']!=""){
		$infoExt        =   getimagesize($_FILES['r_image']['tmp_name']);
		if(strtolower($infoExt['mime']) == 'image/gif' || strtolower($infoExt['mime']) == 'image/jpeg' || strtolower($infoExt['mime']) == 'image/jpg' || strtolower($infoExt['mime']) == 'image/png'){
			$file	=	preg_replace('/\\s+/', '-', time().$file);
			$path   =   $_SERVER['DOCUMENT_ROOT'].'/admin_panel/uploads/riders/';
			//move_uploaded_file($_FILES['file']['tmp_name'],$path);
			  $name_file=date('Ymdhis');//this part is for creating random name for image
			  $ext=end(explode(".", $_FILES["r_image"]["name"]));//gets extension	
			  $image_file = $name_file.".".$ext;
			  if (move_uploaded_file($_FILES["r_image"]["tmp_name"], $path.$image_file)) {
				       
			  } 
			
		}	
	}
	
	if($image_file != ''){
		$update_qry="UPDATE `tbl_riders` SET `r_name`='$r_name',`r_mobile_number`='$r_mobile_number',`r_info`='$r_info',`r_image` = '".$image_file."',`r_vehicle_number` = '".$r_vehicle_number."', `r_live_location` = '".$r_live_location."',	`r_updateddate`='$r_updateddate',`one_signal_id`='$one_signal_id' WHERE `r_id` = '".$_GET['r_id']."'";    
	
	}else{
		$update_qry="UPDATE `tbl_riders` SET `r_name`='$r_name',`r_mobile_number`='$r_mobile_number',`r_info`='$r_info',`r_vehicle_number` = '".$r_vehicle_number."', `r_live_location` = '".$r_live_location."',	`r_updateddate`='$r_updateddate',`one_signal_id`='$one_signal_id' WHERE `r_id` = '".$_GET['r_id']."'";    
	
	}  
	
	//echo $update_qry;
	$insert=mysqli_query($conn,$update_qry);
	
	if($insert)
	{
		$_SESSION['show_msg']="Record Updated Successfully";
		header('Location:riders.php');
	}		   
}
	
$a_m="riders";
?>
<!DOCTYPE html>
<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">

<head>
    <?php include("includes1/head.php"); ?>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="/css/dropzone.css" type="text/css" /> 
    <link rel="stylesheet" href="./css/chosen.min.css" type="text/css" /> 
	
	<style>
	.btn_back{
		color:green !important;
		border-color: green !important;
	}
	.btn_back:hover{
		color:white !important;
		border-color: green !important;
		background-color:green !important;
	}
	.remove_field{
		color: red;
		
	}
	.remove_field:hover{
		color: #51d2b7;
	}
	.remove_div{
		padding-top: 5px;
	}
	.add_field{
		color:blue;
	}
	.add_field:hover{
		color:#51d2b7;
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

            <div class="row pad-wrapper">
                <div class="col-md-12">
                    <h3>
                        <?php if(isset($_GET['r_id'])){ echo "Edit";} else { echo "Add";} ?> riders
                    </h3>
					<form method="post" action="" enctype="multipart/form-data">
						<div class="panel price panel-red">
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-md-6">
									<label><?php echo "Name"; ?>&nbsp;<span style="color:red;">*</span></label>
									<input type="text" id="r_name" required maxlength="200" name="r_name" value="<?php if(isset($_GET['r_id'])){ echo $c_data['r_name'];} ?>" class="form-control" placeholder="<?php echo "Name"; ?>">
									<span id="matchNameResponse"></span>
								</div>
								
								<div class="col-md-6">
									<label><?php echo "Mobile Number"; ?>&nbsp;<span style="color:red;">*</span></label>
									<input type="text" id="r_mobile_number" required maxlength="200" name="r_mobile_number" value="<?php if(isset($_GET['r_id'])){ echo $c_data['r_mobile_number'];} ?>" class="form-control" placeholder="<?php echo "Mobile Number"; ?>">
									<span id="matchNameResponse"></span>
								</div>
							</div>
							
							<div class="row">
								<div class="col-md-6">
									<label><?php echo "Image"; ?>&nbsp;<span style="color:red;">*</span></label>
									<?php if($c_data['r_image']!= ''){?>
									<?php $image_view = $site_url."/admin_panel/uploads/riders/".$c_data['r_image'];?>
									<a href="<?php echo $image_view;?>" target="_blank"><i class="fa fa-eye"></i></a>
									<?php }?>
									<input type="file" id="r_image" <?php if(isset($_GET['r_id'])){}else{?>required<?php }?> maxlength="200" name="r_image" value="" class="form-control" placeholder="<?php echo "Image"; ?>">
									
									<span id="matchNameResponse"></span>
									
								</div>
								
								<div class="col-md-6">
									<label><?php echo "Vehicle Number"; ?>&nbsp;<span style="color:red;">*</span></label>
									<input type="text" id="r_vehicle_number" required maxlength="200" name="r_vehicle_number" value="<?php if(isset($_GET['r_id'])){ echo $c_data['r_vehicle_number'];} ?>" class="form-control" placeholder="<?php echo "Vehicle Number"; ?>">
									<span id="matchNameResponse"></span>
								</div>
							</div>
							
							
							
							<div class="row">
								<div class="col-md-12">
									<label><?php echo "Info"; ?>&nbsp;<span style="color:red;">*</span></label>
									<input type="text" id="r_info" required name="r_info" value="<?php if(isset($_GET['r_id'])){ echo $c_data['r_info'];} ?>" class="form-control" placeholder="<?php echo "Info"; ?>">
									<span id="matchNameResponse"></span>
								</div>
								
								<div class="col-md-6">
									<label><?php echo "Live Location"; ?>&nbsp;<span style="color:red;">*</span></label>
									<input type="text" id="r_live_location" required name="r_live_location" value="<?php if(isset($_GET['r_id'])){ echo $c_data['r_live_location'];} ?>" class="form-control" placeholder="<?php echo "Live Location"; ?>">
									<span id="matchNameResponse"></span>
								</div>
								<div class="col-md-6">
									<label><?php echo "Push notification id"; ?>&nbsp;<span style="color:red;">*</span></label>
									<input type="text" id="one_signal_id" required name="one_signal_id" value="<?php if(isset($_GET['r_id'])){ echo $c_data['one_signal_id'];} ?>" class="form-control" placeholder="<?php echo "Push notification"; ?>">
									<small>to add new device  <a href="" target="_blank">click here</a></small>
								</div>
								
								
								
								
								
							</div>
							
							
						</div>
						
									
						 <input type="submit" class="btn btn-lg btn-outline-primary" name="<?php if(isset($_GET['r_id'])){ echo "update_riders";} else { echo "add_riders";} ?>" value="<?php if(isset($_GET['r_id'])){ echo "Edit";} else { echo "Add";} ?>"/>
						 &nbsp;&nbsp;&nbsp;
						 <a href="riders.php" class="btn btn-lg btn-outline-primary btn_back"> Back </a>
					
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