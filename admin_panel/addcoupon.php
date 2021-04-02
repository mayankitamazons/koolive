<?php 
include("config.php");

if(!isset($_SESSION['admin']))
{
	header("location:login.php");
}
if($_GET['c_id'])
{
	$c_id=$_GET['c_id'];
	$query=mysqli_query($conn,"select * from coupon where id='$c_id'");
	$c_data=mysqli_fetch_array($query);
	extract($c_data);
	

}
else
{
	$update=0;
}   

if(isset($_POST['add_coupon_code'])){
 extract($_POST);
 print_R($_POST);
 // die;
 if($coupon_code)
 {
	$q="INSERT INTO `coupon`(`user_id`, `title`, `coupon_code`, `discount`, `total_min_price`, `total_max_price`, `valid_from`, `valid_to`, `type`, `valid_user`, `remain_user`, `description`, `status`, `created`,`per_user_count`,`coupon_type`,`coupon_allot`,`specific_user_ids`)
	VALUES ('$user_id', '$title', '$coupon_code', '$discount', '$total_min_price', '$total_max_price', '$valid_from', '$valid_to', '$type', '$valid_user', '$valid_user', '$description', '1', '$created','$per_user_count','uni','$coupon_allot','$specific_user_ids')";
   
	 $insert=mysqli_query($conn,$q);
	if($insert)
	{
		if($coupon_allot==2)
		{
			if($specific_user_ids)
			{
				$spe_array=explode(',',$specific_user_ids);
				$coupon_id=mysqli_insert_id($conn);
				
				foreach($spe_array as $s)
				{
					 $s_ids="INSERT INTO `coupon_specific_allot` (`user_mobile_no`, `coupon_id`) VALUES ('$s','$coupon_id')";
					$insert2=mysqli_query($conn,$s_ids);
				}
				
			}
		}
		$_SESSION['show_msg']="New Coupon Code  added";
		header('Location:coupon.php');
	}		
 }
}
if(isset($_POST['update_coupon'])){
 extract($_POST);
 if($valid_from)
	$valid_from = date('Y-m-d', strtotime($_POST['valid_from']));
	else 
	$valid_from='';
	if($valid_to)
	$valid_to = date('Y-m-d', strtotime($_POST['valid_to']));
	else
	$valid_to='';
 if($coupon_code)
 {

	$i="UPDATE `coupon` SET `title`='$title',`coupon_code`='$coupon_code',`discount`='$discount',`total_min_price`='$total_min_price',`total_max_price`='$total_max_price',`valid_from`='$valid_from',`valid_to`='$valid_to',`type`='$type',`valid_user`='$valid_user',`description`='$description',`status`='$status',`per_user_count`='$per_user_count' WHERE `id` = '".$c_id."'";    
	
	 $insert=mysqli_query($conn,$i);
	if($insert)
	{
		$_SESSION['show_msg']="Record Updated Successfully";
		header('Location:coupon.php');
	}		   
 }
}
	
$a_m="coupon";
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
                        <?php if($_GET['c_id']){ echo "Edit";} else { echo "Add";} ?> Coupon Code
                    </h3>
					<form method="post" action="">
									<div class="panel price panel-red">
										
									</div>
									<div class="form-group">
									  <div class="row">
										<div class="col-md-8">
											<label><?php echo "Coupon Title"; ?></label>
											<input type="text" id="plan_name" required maxlength="200" name="title" value="<?php echo $title; ?>" class="form-control" placeholder="<?php echo "Coupon Title"; ?>">
											<span id="matchNameResponse"></span>
										</div>
									    
										
										</div>
									</div>
									<div class="form-group">
									  <div class="row">
									        <div class="col-md-4">
    											<label><?php echo "Coupon Code";?></label>
    											<input type="text" required name="coupon_code" id="couponcode" value="<?php echo $coupon_code; ?>" class="form-control" placeholder="<?php echo "Coupon Code";?>">
    											<span id="matchCodeResponse"></span>
    										</div>
    										<div class="col-md-4">
    											<label>Total Coupon </label>
    											<input type="number" required name="valid_user" value="<?php echo $valid_user; ?>" class="form-control" placeholder="Number of Coupon">
    										</div>
									    </div>
									</div>
									<div class="form-group">
										<div class="row">
											
											<!--div class="col-md-4">
												<label>Plan Buy Amount</label>
												<input type="text" value='0' oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" maxlength="4" name="plan_amount" class="form-control" placeholder="Plan Amount">
											</div!-->
											<div class="col-md-4">
												<label><?php echo "Order Min"; ?> Amount</label>
												<input type="text" value="<?php echo $total_min_price; ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" maxlength="4" name="total_min_price" class="form-control" placeholder="<?php echo $language['order_min']; ?> Amount">
											</div>
											<div class="col-md-4">
													<label><?php echo "Order Max"; ?> Amount</label>
													<input type="text"  value="<?php echo $total_max_price; ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" maxlength="4" name="total_max_price" class="form-control" placeholder="<?php echo $language['order_max']; ?> Amount">
											</div>
											<div class="col-md-4"> 
													<label>Per user count</label>
													<input type="text" value="<?php echo $per_user_count; ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" value='1' maxlength="4" name="per_user_count" class="form-control" placeholder="Per User Count">
											</div>
										</div>																		
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-md-4">
												<label><?php echo "Discount"; ?> on order</label>
												<input type="text" value="<?php echo $discount; ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"  maxlength="3" name="discount" class="form-control" placeholder="<?php echo $language['discount']; ?> on order">
											</div>
											<div class="col-md-4">
												<label><?php echo $language['discount']; ?> Type</label>
												<select class="form-control" name="type" required>
													<option value="">Select Type</option>
													<option value="fix" <?php if($type=="fix"){echo "selected";} ?> >Fix</option>
													<option value="per" <?php if($type=="per"){echo "selected";} ?> >Per</option>
												</select>
											</div>
											
										</div>																		
									</div>
									<div class="form-group">
										<div class="col-md-4">
												<label><?php echo $language['discount']; ?> Coupon For </label>
												<select class="form-control" name="coupon_allot" required>
													<option value="">Select Type</option>
													<option value=1 <?php if($coupon_allot=="1"){echo "selected";} ?> >All</option>
													<option value=2 <?php if($coupon_allot=="2"){echo "selected";} ?> >Specific Users</option>
												</select>
											</div>
										<div class="row">
											<div class="col-md-8">
												<label>Specific user mobile with comma speartor like 60123115670,60-123321111</label>
												<textarea class="form-control" name="specific_user_ids" placeholder="Write User Mobile no...."><?php echo $specific_user_ids; ?></textarea>
											</div>
										</div>																		
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-md-8">
												<label>Coupon Description</label>
												<textarea class="form-control" name="description" placeholder="Write Description...."><?php echo $description; ?></textarea>
											</div>
										</div>																		
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-md-4">
												<label><?php echo $language['valid_from']; ?></label>
												<input type="date"  name="valid_from" class="form-control" value="<?php echo $valid_from; ?>" placeholder="<?php echo $language['valid_from']; ?>">
											</div>
											<div class="col-md-4">
												<label><?php echo $language['valid_to']; ?></label>
												<input type="date" name="valid_to" class="form-control" value="<?php echo $valid_to; ?>" placeholder="<?php echo $language['valid_to']; ?>">
											</div>
											<!--div class="col-md-4">
												<label>Plan Status</label>
												<div class="form-group">
													<input type="hidden" name="plan_status" value="0">
												<input type="checkbox" name="plan_status" class="form-check-inline" value="1" placeholder="Active" checked>Active
												</div>
												<!-- <select class="form-control" name="plan_status" required>
													<option value="">Select Status</option>
													<option value="1">Active</option>
													<option value="0">Inactive</option>
												</select> -->
											</div!-->
										</div>																		
									</div>   
									 <input type="submit" class="btn btn-lg btn-outline-primary" name="<?php if($_GET['c_id']){ echo "update_coupon";} else { echo "add_coupon_code";} ?>" value="<?php if($_GET['c_id']){ echo "Edit";} else { echo "Add";} ?>"/>
								
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

