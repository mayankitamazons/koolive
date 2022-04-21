<?php 
include("config.php");
//ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL); 
if(!isset($_SESSION['admin']))
{
	header("location:login.php");
}
$loop_count = 2;
$loc_status = 'Add';
$locArray = array();
if(isset($_GET['data'])&&$_GET['data']=='deleteRecord'){
	$id = $_GET['l_id'];
	$query = mysqli_query($conn,"DELETE FROM `location` WHERE `location`.`l_id` ='$id'");
	if($query){echo true;}else{die();}
}
if(isset($_GET['cityid']) && $_GET['cityid']!= '')
{
	$city_id=$_GET['cityid'];
	$query=mysqli_query($conn,"select * from city where CityID ='$city_id'");
	$c_data=mysqli_fetch_array($query);
	$CityName = $c_data['CityName'];
	
	$sql_count = "select count(l_id) as total_count from location where l_city_id='$city_id'";
	$row = mysqli_fetch_assoc(mysqli_query($conn,$sql_count));
	$rec_count = $row['total_count'];
	if($rec_count > 0){
		$loop_count = $rec_count;
		$loc_status = 'Edit';
	}
	$query_multi=mysqli_query($conn,"select * from location where l_city_id='$city_id'");
	
	$k = 0;
	
	while($c_data_many = mysqli_fetch_assoc($query_multi))
	{
		$locArray[$k] = $c_data_many;
		$k++;
	}
	/*echo '<pre>';
	print_R($locArray);
	
	exit;*/
}
else
{
	$update=0;
} 
if(isset($_POST['add_location'])){
 extract($_POST);
 $l_createddate = date('Y-m-d H:i:s');
 $cityid = $_POST['cityid'];
 if($l_name)
 {
	for ($i = 0; $i < count($_POST['l_name']); $i++){
		$l_name = $_POST['l_name'][$i];
		$q="INSERT INTO `location`(`l_city_id`,`l_name`, `l_createddate`,`l_status`)
			VALUES ('$cityid','$l_name', '$l_createddate',1)";
		$insert=mysqli_query($conn,$q);
	} 
	if($insert)
	{
		$_SESSION['show_msg']="New location added";
		header('Location:locations.php?cityid='.$cityid);
	}		
 }
}
if(isset($_POST['update_location'])){
 extract($_POST);
 $l_updateddate = date('Y-m-d H:i:s');
 $l_createddate = date('Y-m-d H:i:s');
 
	$idcount = count($_POST['l_id']);
	
	for ($i = 0; $i < count($_POST['l_name']); $i++){
		$l_id  = $_POST['l_id'][$i];
		$l_name = $_POST['l_name'][$i];
		$cityid = $_POST['cityid'];
		
		if($l_id != '0'){
			//Update query
			$update_qry="UPDATE `location` SET `l_city_id`='$cityid',`l_name`='$l_name',`l_updateddate`='$l_updateddate' WHERE `l_id` = '".$l_id."'";    
			$insert=mysqli_query($conn,$update_qry);
			
		}else{
			//Insert query
			$q="INSERT INTO `location`(`l_city_id`,`l_name`, `l_createddate`,`l_status`)
			VALUES ('$cityid','$l_name', '$l_createddate',1)";
			$insert=mysqli_query($conn,$q);
		}
		
	
	}
	
	if($insert)
	{
		$_SESSION['show_msg']="Record Updated Successfully";
		header('Location:locations.php?cityid='.$cityid);
	}		   
}
	
$a_m="locations";
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
                        <b><?php echo $CityName."'s" ;?></b> Location 
                    </h3>
					<form method="post" action="">
						<div class="panel price panel-red">
						</div>
						<div class="form-group">
						<input type="hidden" class="cityid " id="cityid" name="cityid" value="<?php echo $city_id;?>" >
						<?php $j = 0; ?>	
                        <?php 
						$l_name = '';
						$l_id = '0';
						
						for ($i = 0; $i < $loop_count; $i++) { 
							if(isset($_GET['cityid']) && $_GET['cityid']!= ''){
								 $l_name = $locArray[$i]['l_name'];
								 $l_id = $locArray[$i]['l_id'];
							}
						?>
							<div class="row input_fields_wrap testclass l_id_<?php echo $l_id;?>">
							<br/>
							<input type="hidden" class="l_id " id="l_id_<?php echo $i;?>" name="l_id[]" value="<?php echo $l_id;?>" >
							
								<div class="col-md-4">
									<label><?php echo "Area Name";?>&nbsp;<span style="color:red;">*</span></label>
									<input type="text" required name="l_name[]" id="l_name_<?php echo $i;?>" value="<?php echo $l_name; ?>" class="form-control" placeholder="Area Name">
								</div>
								<?php if($i == 0){?>
								<div class="col-md-1 remove_div">
									<label>&nbsp;</label><br/>
									<a href="#" class="add_field">
									<i class="fa fa-plus-square" style="font-size: 30px;"></i>
									</a>
								</div>
								<?php }else{?>
								<div class="col-md-1 remove_div">
									<label>&nbsp;</label><br/>
									<a href="javascript:void(0)" class="remove_field">
									<i class="fa fa-minus-square" style="font-size: 30px;"></i>
									</a>
								</div>
								<?php }?>
								
							</div>
						
						<?php $j++;}?>
							
							
							
							
						</div>
						
									
									 <input type="submit" class="btn btn-lg btn-outline-primary" name="<?php if(isset($loc_status) && $loc_status == 'Edit'){ echo "update_location";} else { echo "add_location";} ?>" value="<?php if(isset($loc_status) && $loc_status == 'Edit'){ echo "Edit";} else { echo "Add";} ?>"/>
									 &nbsp;&nbsp;&nbsp;
									 <a href="city.php" class="btn btn-lg btn-outline-primary btn_back"> Back </a>
								
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

	<script>
	$(document).ready(function() {
		$(".pad-wrapper").delegate(".add_field", "click", function() {
		//$(".add_field").click(function(){
			//alert('here');
			var lastSrno = jQuery('.testclass').length;
            lastSrno = parseInt(lastSrno);
            lastSrno = lastSrno + 1;
            var sb_index = lastSrno;
			console.log(sb_index);
		
var content_div = '<div class="row input_fields_wrap testclass"><br/><input type="hidden" class="l_id" id="l_id_'+sb_index+'" name="l_id[]" value="0" ><div class="col-md-4"><label>Area Name&nbsp;<span style="color:red;">*</span></label>	<input type="text" required name="l_name[]" id="l_name_'+sb_index+'" value="" class="form-control" placeholder="Area Name"></div><div class="col-md-1 remove_div"><label>&nbsp;</label><br/><a href="javascript:void(0)" class="remove_field"><i class="fa fa-minus-square" style="font-size: 30px;"></i></a></div></div>';


			$(".testclass").last().after(content_div);
		});
		
		$(".pad-wrapper").delegate(".remove_field", "click", function() {
			console.log('new');
			console.log($(this).parents('.testclass').find(".l_id").val());
			var l_id =  $(this).parents('.testclass').find(".l_id").val();
				console.log(typeof l_id);
			var cnfrmDelete = confirm("Are You Sure Delete ?");
			if(cnfrmDelete==true){
				
				if(l_id != '0'){
					console.log('true');
				  $.ajax({
						url:'locations.php',
						method:'GET',
						data:{
							data:'deleteRecord',
							l_id:l_id
							},
						success:function(res){
							$('.l_id_'+l_id).remove();
						}
				  });	
				}else{
					//console.log('false');
					$(this).parents('.testclass').remove();
				}
			}
            //$(this).parents('.testclass').remove();
            return false;
        });
		
	});
</script>
</body>
</html>