<?php 
include("config.php");
//ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL); 
if(!isset($_SESSION['admin']))
{
	header("location:login.php");
}
$loop_count = 2;
$terrArray = array();

if(isset($_GET['data'])&&$_GET['data']=='deleteRecord'){
	$id = $_GET['t_id'];
	$query = mysqli_query($conn,"DELETE FROM `territory` WHERE `territory`.`t_id` ='$id'");
	if($query){echo true;}else{die();}
}

if(isset($_GET['t_id']) && $_GET['t_id']!= '')
{
	$t_id=$_GET['t_id'];
	$query=mysqli_query($conn,"select * from territory where t_id='$t_id'");
	$c_data=mysqli_fetch_array($query);
	$category_label = $c_data['t_label'];
	
	$sql_count = "select count(t_id) as total_count from territory where t_label='$category_label'";
	$row = mysqli_fetch_assoc(mysqli_query($conn,$sql_count));
	$rec_count = $row['total_count'];
	$loop_count = $rec_count;
	
	$query_multi=mysqli_query($conn,"select * from territory where t_label='$category_label'");
	
	$k = 0;
	
	while($c_data_many = mysqli_fetch_assoc($query_multi))
	{
		$terrArray[$k] = $c_data_many;
	$k++;
	}
	/*echo '<pre>';
	print_R($terrArray);
	
	exit;*/
}
else
{
	$update=0;
}   
if(isset($_POST['add_territory'])){
 extract($_POST);
 $t_createddate = date('Y-m-d H:i:s');
 $t_updateddate = date('Y-m-d H:i:s');

 if($t_label)
 {
	for ($i = 0; $i < count($_POST['t_postcode']); $i++){
		$t_postcode = $_POST['t_postcode'][$i];
		$t_price = $_POST['t_price'][$i];
		$t_location_name = $_POST['t_location_name'][$i];
		$q="INSERT INTO `territory`(`t_label`, `t_postcode`, `t_price`, `t_location_name`, `t_createddate`,`t_updateddate`)
			VALUES ('$t_label', '$t_postcode', '$t_price', '$t_location_name', '$t_createddate', '$t_updateddate')";
			$insert=mysqli_query($conn,$q);
	} 
	
	if($insert)
	{
		$_SESSION['show_msg']="New Territory added";
		header('Location:territory.php');
	}		
 }
}
if(isset($_POST['update_territory'])){
 extract($_POST);
 $t_updateddate = date('Y-m-d H:i:s');
 $t_createddate = date('Y-m-d H:i:s');
 

	$idcount = count($_POST['t_id']);
	$postcount = count($_POST['t_postcode']);
	
    
	
	for ($i = 0; $i < count($_POST['t_postcode']); $i++){
		$t_id = $_POST['t_id'][$i];
		$t_postcode = $_POST['t_postcode'][$i];
		$t_price = number_format($_POST['t_price'][$i],2);
		$t_location_name = $_POST['t_location_name'][$i];
		
		
		if($t_id != '0'){
			//Update query
			$update_qry="UPDATE `territory` SET `t_label`='$t_label',`t_postcode`='$t_postcode',`t_price`='$t_price',`t_location_name`='$t_location_name',`t_updateddate`='$t_updateddate' WHERE `t_id` = '".$t_id."'";    
			$insert=mysqli_query($conn,$update_qry);
			
		}else{
			//Insert query
			$q="INSERT INTO `territory`(`t_label`, `t_postcode`, `t_price`, `t_location_name`, `t_createddate`,`t_updateddate`)
			VALUES ('$t_label', '$t_postcode', '$t_price', '$t_location_name', '$t_createddate', '$t_updateddate')";
			$insert=mysqli_query($conn,$q);
		}
		
	
	}
	
	if($insert)
	{
		$_SESSION['show_msg']="Record Updated Successfully";
		header('Location:territory.php');
	}		   
}
	
$a_m="territory";
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
                        <?php if(isset($_GET['t_id'])){ echo "Edit";} else { echo "Add";} ?> Territory
                    </h3>
					<form method="post" action="">
						<div class="panel price panel-red">
						</div>
						<div class="form-group">
							 <div class="row">
								<div class="col-md-11">
									<label><?php echo "Category"; ?>&nbsp;<span style="color:red;">*</span></label>
									<input type="text" id="t_label" required maxlength="200" name="t_label" value="<?php if(isset($_GET['t_id'])){ echo $terrArray[0]['t_label'];} ?>" class="form-control" placeholder="<?php echo "Category Name"; ?>">
									<span id="matchNameResponse"></span>
								</div>
								</div>
							
						<?php $j = 0; ?>	
                        <?php 
						$t_price = '';
						$t_id = '';
						$t_postcode = '';
						$t_location_name = '';
						$t_id = '0';
						for ($i = 0; $i < $loop_count; $i++) { 
							if(isset($_GET['t_id']) && $_GET['t_id']!= ''){
								 $t_price = $terrArray[$i]['t_price'];
								 $t_id = $terrArray[$i]['t_id'];
								 $t_postcode = $terrArray[$i]['t_postcode'];
								 $t_location_name = $terrArray[$i]['t_location_name'];
							}
						?>
							<div class="row input_fields_wrap testclass t_id_<?php echo $t_id;?>">
							<br/>
							<input type="hidden" class="t_id " id="t_id_<?php echo $i;?>" name="t_id[]" value="<?php echo $t_id;?>" >
							
							
								<div class="col-md-4">
									<label><?php echo "Postcode";?>&nbsp;<span style="color:red;">*</span></label>
									<input type="text" required name="t_postcode[]" id="t_postcode_<?php echo $i;?>" value="<?php echo $t_postcode; ?>" class="form-control" value="<?php echo $t_price; ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" maxlength="8"  placeholder="<?php echo "Postcode";?>">
									<span id="matchCodeResponse"></span>
								</div>
								<div class="col-md-3">
									<label><?php echo "Price";?>&nbsp;<span style="color:red;">*</span></label>
									<input type="text" required name="t_price[]" id="t_price_<?php echo $i;?>" value="<?php echo $t_price; ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" maxlength="4" class="form-control" placeholder="<?php echo "Price";?>">
									<span id="matchCodeResponse"></span>
								</div>
								<div class="col-md-4">
									<label><?php echo "Area Name";?>&nbsp;<span style="color:red;">*</span></label>
									<input type="text" required name="t_location_name[]" id="t_location_name_<?php echo $i;?>" value="<?php echo $t_location_name; ?>" class="form-control" placeholder="Area Name">
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
						
									
									 <input type="submit" class="btn btn-lg btn-outline-primary" name="<?php if(isset($_GET['t_id'])){ echo "update_territory";} else { echo "add_territory";} ?>" value="<?php if(isset($_GET['t_id'])){ echo "Edit";} else { echo "Add";} ?>"/>
									 &nbsp;&nbsp;&nbsp;
									 <a href="territory.php" class="btn btn-lg btn-outline-primary btn_back"> Back </a>
								
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
		
var content_div = '<div class="row input_fields_wrap testclass"><br/><input type="hidden" class="t_id" id="t_id_'+sb_index+'" name="t_id[]" value="0" ><div class="col-md-4"><label>Postcode&nbsp;<span style="color:red;">*</span></label><input type="text" required name="t_postcode[]" id="t_postcode_'+sb_index+'" value="" class="form-control" value="" oninput="" maxlength="8"  placeholder="Postcode"><span id="matchCodeResponse"></span></div><div class="col-md-3"><label>Price&nbsp;<span style="color:red;">*</span></label><input type="text" required name="t_price[]" id="t_price_'+sb_index+'" value="" oninput="" maxlength="4" class="form-control" placeholder="Price"><span id="matchCodeResponse"></span></div><div class="col-md-4"><label>Area Name&nbsp;<span style="color:red;">*</span></label>	<input type="text" required name="t_location_name[]" id="t_location_name_'+sb_index+'" value="" class="form-control" placeholder="Area Name"></div><div class="col-md-1 remove_div"><label>&nbsp;</label><br/><a href="javascript:void(0)" class="remove_field"><i class="fa fa-minus-square" style="font-size: 30px;"></i></a></div></div>';

//var content_div = '<input type="hidden" class="t_id" id="t_id_" name="t_id[]" value='' >';
							

			$(".testclass").last().after(content_div);
		});
		
		$(".pad-wrapper").delegate(".remove_field", "click", function() {
		//$(".remove_field").click(function(){
			console.log('new');
			console.log($(this).parents('.testclass').find(".t_id").val());
			var t_id =  $(this).parents('.testclass').find(".t_id").val();
				console.log(typeof t_id);
			var cnfrmDelete = confirm("Are You Sure Delete ?");
			if(cnfrmDelete==true){
				
				if(t_id != '0'){
					console.log('true');
				  $.ajax({
						url:'addterritory.php',
						method:'GET',
						data:{
							data:'deleteRecord',
							t_id:t_id
							},
						success:function(res){
							$('.t_id_'+t_id).remove();
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