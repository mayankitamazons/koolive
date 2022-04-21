<?php 
include("config.php");
//ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL); 
if(!isset($_SESSION['admin']))
{
	header("location:login.php");
}
$loop_count = 2;
$terrArray = array();
$a_m="producturl";

if($_POST['update']){
	$post_count = count($_POST);
	foreach ($_POST['prd_id'] as $key => $p_id) {
		$prd_id = $p_id;
		$prd_slug = $_POST['product_slug'][$key];
		$update_query = "UPDATE `products` SET `product_slug` = '".$prd_slug."',shop_url_update=1 WHERE `products`.`id` = ".$prd_id;
		$runquery = mysqli_query($conn,$update_query);  
	}
	header('Location:producturl.php');
	exit;
	
}
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
                        Update Product URL
                    </h3>
					<form method="post" action="">
						<div class="panel price panel-red">
						</div>
						<div class="form-group">
							
							
						<?php $j = 0; ?>	
                        <?php 
						$product_query = mysqli_query($conn,"select u.name as user_name, p.* from products AS p join users as u ON u.id = p.user_id where p.status=0 and p.shop_url_update = 0 order by p.id asc limit 0,100");
						
						
						while ($row=mysqli_fetch_assoc($product_query)){
							$prd_id = $row['id'];
							
						?>
							<div class="row input_fields_wrap testclass t_id_<?php echo $prd_id;?>">
							<br/>
							<input type="hidden" class="prd_id " id="prd_id<?php echo $j;?>" name="prd_id[]" value="<?php echo $prd_id;?>" >
							
								<div class="col-md-4">
									<label><?php echo "Shop Name";?>&nbsp;<span style="color:red;">*</span></label>
									<input type="text" readonly  name="user_name[]" id="user_name_<?php echo $j;?>" class="form-control" value="<?php echo $row['user_name']; ?>" >
								</div>
								
								<div class="col-md-4">
									<label><?php echo "Product Name";?>&nbsp;<span style="color:red;">*</span></label>
									<input type="text" readonly  name="product_name[]" id="product_name_<?php echo $j;?>" class="form-control" value="<?php echo $row['product_name']; ?>" >
								</div>
								<div class="col-md-4">
									<label><?php echo "URL Name";?>&nbsp;<span style="color:red;">*</span></label>
									<input type="text"  name="product_slug[]" id="product_slug_<?php echo $j;?>" value="<?php echo $row['product_slug']; ?>" class="form-control" >
								</div>
							</div>
						
						<?php $j++;}?>
							
							
							
							
						</div>
						
									
									 <input type="submit" class="btn btn-lg btn-outline-primary" name="update" value="Update"/>

								
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