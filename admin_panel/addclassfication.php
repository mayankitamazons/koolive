<?php 
include("config.php");

$users = mysqli_query($conn, "SELECT * FROM users WHERE user_roles = '1' order by id desc");
$merchants = mysqli_query($conn, "SELECT * FROM users where user_roles='2'");

$merchant = "";
$user = "";
if(!isset($_SESSION['admin']))
{
	header("location:login.php");
}
if($_GET['c_id'])
{
	$c_id=$_GET['c_id'];
	$query=mysqli_query($conn,"select * from classfication_service where id='$c_id'");
	$c_data=mysqli_fetch_array($query);
	extract($c_data);
}
else
{
	$update=0;
}   

if(isset($_POST['add_merchant'])){
 extract($_POST);
 if($classification_name)
 {
	 if($mal_version =="on"){ $mal_version = 'y';} else {$mal_version='n';}
	 $i="INSERT INTO `classfication_service` (`classification_name`, `classification_name_chiness`, `classification_name_mal`,`mal_version`) VALUES
	 ('$classification_name','$classification_name_chiness','$classification_name_mal','mal_version')";
	 $insert=mysqli_query($conn,$i);
	if($insert)
	{
		$_SESSION['show_msg']="New Classfication type added";
		header('Location:classficationmerchant.php');
	}		
 }
}
if(isset($_POST['update_merchant'])){
 extract($_POST);
 if($classification_name)
 {
	if($mal_version =="on"){ $mal_version = 'y';} else {$mal_version='n';}
	 $i="UPDATE `classfication_service` SET `mal_version` = '$mal_version',`classification_name_chiness` = '$classification_name_chiness',`classification_name` = '$classification_name',`classification_name_mal` = '$classification_name_mal'  WHERE `classfication_service`.`id` ='$c_id'";
	 $insert=mysqli_query($conn,$i);
	if($insert)
	{
		$_SESSION['show_msg']="Record Updated Successfully";
		header('Location:classficationmerchant.php');
	}		   
 }
}
	
$a_m="agents";
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
                        <?php if($_GET['c_id']){ echo "Edit";} else { echo "Add";} ?> a Classfication Type
                    </h3>
					<form method="post">
						<div class="row">
							<div class="col-md-3 col-sm-12">
								<div class="form-group">
									<label for="name">Type name</label>
									<input type="text" class="form-control" id="classification_name" value="<?php echo $classification_name; ?>" name="classification_name" required placeholder="Type name">
								</div>
							</div>
							<div class="col-md-3 col-sm-8">
								<label for="generate-button">Chinese Name</label>
								<input type="text" class="form-control" name="classification_name_chiness" value="<?php echo $classification_name_chiness; ?>"  required/>
							</div>
							<div class="col-md-3 col-sm-8">
								<label for="generate-button">Malaysian Name</label>
								<input type="text" class="form-control" name="classification_name_mal"  value="<?php echo $classification_name_mal; ?>"  id="classification_name_mal"/>
							</div>
							<div class="col-md-3 col-sm-8">
								<label for="generate-button">Malaysian version</label>
								<input type="checkbox" <?php if($mal_version!="n"){  echo "checked";}?>  class="form-control" name="mal_version"/>
							</div>


							
							<div class="col-md-12" style="margin-top: 20px;">
							   <input type="submit" class="btn btn-lg btn-outline-primary" name="<?php if($_GET['c_id']){ echo "update_merchant";} else { echo "add_merchant";} ?>" value="<?php if($_GET['c_id']){ echo "Edit";} else { echo "Add";} ?>"/>
								
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

