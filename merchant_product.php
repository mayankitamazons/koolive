<?php 
include("config.php");
$merchant_tab="y";
$product_tab="y";
$me="add_product_button";
if(!isset($_SESSION['login']))
{
	header("location:login.php");
}
$bank_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$_SESSION['login']."'"));
 $current_id = $bank_data['id'];
 $image_cdn_url='';
 $discounted_product = $bank_data['discounted_product'];
	$total_rows = mysqli_query($conn, "SELECT * FROM category WHERE user_id ='".$_SESSION['login']."' and status=0");
$profile_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$_SESSION['login']."'"));
// print_R($profile_data);
// die;
if($profile_data['user_roles']==5)
{
	$loginidset=$profile_data['parentid'];
}
else
{

	$loginidset=$_SESSION['login'];

}

if(isset($_POST['submit']))
{
		ini_set("post_max_size", "30M");
		ini_set("upload_max_filesize", "30M");
		ini_set("memory_limit", "20000M"); 

		//~ $currentDir = getcwd();
	    //~ $uploadDirectory = "koofamilies.com/images/product_images/";
		$error = array();
		$image_pic_error = "";
		$image_code_error = "";
		$maxsize    = 1000000;
		$productname = addslashes($_POST['productname']);
		// $category = addslashes($_POST['category']);
		$category = $_POST['category'];
		$product_type = addslashes($_POST['product_type']);
		$product_price = addslashes($_POST['product_price']);
		$remark = addslashes($_POST['product_remark']);
		$print_ip_address=$_POST['print_ip_address'];
		$printer_ip_2=$_POST['printer_ip_2'];
		$printer_profile=$_POST['printer_profile'];
		$usb_name=$_POST['usb_name'];
		$product_discount=$_POST['product_discount'];
		$add_to_cart_button=$_POST['add_to_cart_button'];
		$image = $_FILES["image_pic"]["name"];	
		$code = $_FILES["code_pic"]["name"];
      if($category)
 		{
    // echo "SELECT id FROM category WHERE user_id ='".$loginidset."' and category_name ='".$category."'";
	// die;
	$categories = mysqli_query($conn, "SELECT id FROM category WHERE user_id ='".$loginidset."' and category_name ='".$category."'");
	$categoryrow=mysqli_fetch_assoc($categories);
	$category_id=$categoryrow['id'];
	 }
	 // print_R($categoryrow);
	 // die;
     if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Check if file was uploaded without errors
    if(isset($_FILES["image_pic"]) && $_FILES["image_pic"]["error"] == 0){
        $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg","JPEG" => "image/JPEG", "gif" => "image/gif", "png" => "image/png");
        $filename = $_FILES["image_pic"]["name"];
        $filetype = $_FILES["image_pic"]["type"];
        $filesize = $_FILES["image_pic"]["size"];
		
        // Verify file extension
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if(!array_key_exists($ext, $allowed)){
        	$image_pic_error = "Error: Please select a valid file format.";
        }
        else
        {
        // Verify file size - 5MB maximum
        //~ $maxsize = 5 * 1024 * 1024;
        //~ if($filesize > $maxsize) die("Error: File size is larger than the allowed limit.");
    
			// Verify MYME type of the file
			if(($_FILES['image_pic']['size'] >= $maxsize) || ($_FILES["image_pic"]["size"] == 0)) {
				$image_pic_error = 'Product image is too large try to updated image less then 1 mb.';
			}
			else
			{
				if(in_array($filetype, $allowed)){
					// Check whether file exists before uploading it
					$uniquesavename=time().uniqid(rand(1000,9999)).".png";
					
					$destFile = "product/".$uniquesavename;
					require_once('sirv.api.class.php');
					$sirvClient = new SirvClient(
					  // S3 bucket
					  'koofamilies',
					  // S3 Access Key
					  'click4mayank@gmail.com',
					  // S3 Secret Key
					  'iFOyO1LVMp7EOYIW3IP9VOn76UBFFWdxGaDzuJGj2tHlHMP0'
					);
					if ($sirvClient->testConnection()) {
					  // Connection SUCCEEDED
					  // echo "connected";
						$res = $sirvClient->uploadFile(
						  // File path on Sirv
						  $destFile,
						  // Local file name
						 $_FILES["image_pic"]["tmp_name"] 
						);
						
						if($res['full_url']=='')
						{
							$destFile = "/home/koofamilies/public_html/images/product_images/".$uniquesavename;
							move_uploaded_file($_FILES["image_pic"]["tmp_name"],$destFile); 
						}
						else
						{
							$image_cdn_url=$res['full_url'];
						}

					} else {
					  // Connection FAILED
						$destFile = "/home/koofamilies/public_html/images/product_images/".$uniquesavename;
						move_uploaded_file($_FILES["image_pic"]["tmp_name"],$destFile); 
					}
					
				} 
				else
				{
					$image_pic_error = "Error: There was a problem uploading your file. Please try again.";
				}
			}
		}
    }
    if(isset($_FILES["code_pic"]) && $_FILES["code_pic"]["error"] == 0){
        $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg","JPEG" => "image/JPEG", "gif" => "image/gif", "png" => "image/png");
        $filename = $_FILES["code_pic"]["name"];
        $filetype = $_FILES["code_pic"]["type"];
        $filesize = $_FILES["code_pic"]["size"];
        
        // Verify file extension
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if(!array_key_exists($ext, $allowed)) {
        	$image_code_error = "Error: Please select a valid file format."; 
        }else {

        // Verify file size - 5MB maximum
        //~ $maxsize = 5 * 1024 * 1024;
        //~ if($filesize > $maxsize) die("Error: File size is larger than the allowed limit.");
    
			// Verify MYME type of the file
			if(($_FILES['code_pic']['size'] >= $maxsize) || ($_FILES["code_pic"]["size"] == 0)) {
				$image_code_error = 'Product code image is too large try to updated image less then 1 mb.';

			}
			else
			{
				if(in_array($filetype, $allowed)){
					// Check whether file exists before uploading it
					if(file_exists("upload/" . $_FILES["code_pic"]["name"])){
						$image_code_error = $_FILES["code_pic"]["name"] . " is already exists."; 
					} else{
						move_uploaded_file($_FILES["code_pic"]["tmp_name"], "/home/koofamilies/public_html/images/product_images/" . $_FILES["code_pic"]["name"]);
					} 
				} else{
					$image_code_error = "Error: There was a problem uploading your file. Please try again."; 
				}
			}
		}
    } 
}

    $current_date= date("Y/m/d");  
	if($add_to_cart_button =="on") $add_to_cart_button = '1';
	else $add_to_cart_button = "0";
	 $insert="INSERT INTO products SET add_to_cart_button='$add_to_cart_button',image_cdn_url='$image_cdn_url',product_discount='$product_discount',product_name='$productname',user_id='$current_id', category='$category',category_id='$category_id',product_type='$product_type',product_price='$product_price', remark = '$remark', image='$uniquesavename', code='$code',created_date='$current_date',print_ip_address='$print_ip_address',printer_ip_2='$printer_ip_2',printer_profile='$printer_profile',usb_name='$usb_name'";
	
	mysqli_query($conn,$insert);

}
?>
<!DOCTYPE html>
<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">

<head>
    <?php include("includes1/head.php"); ?>
	<style>
	.well
	{
		min-height: 20px;
		padding: 19px;
		margin-bottom: 20px;
		background-color: #fff;
		border: 1px solid #e3e3e3;
		border-radius: 4px;
		-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
		box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
	}
	</style>
</head>

<body class="header-light sidebar-dark sidebar-expand pace-done">

    <div class="pace  pace-inactive">
        <div class="pace-progress" data-progress-text="100%" data-progress="99" style="transform: translate3d(100%, 0px, 0px);">
            <div class="pace-progress-inner"></div>
        </div>
        <div class="pace-activity"></div>
    </div>

    <div id="wrapper" class="wrapper">
        <!-- HEADER & TOP NAVIGATION -->
        <?php include("includes1/navbar.php"); ?>
        <!-- /.navbar -->
        <div class="content-wrapper">
            <!-- SIDEBAR -->
            <?php include("includes1/sidebar.php"); ?>
            <!-- /.site-sidebar -->


            <main class="main-wrapper clearfix" style="min-height: 522px;">
                <div class="row" id="main-content" style="padding-top:25px">
					<div class="container">
					</div>
					<div class="container" >
					    <div class="row">
					        <div class="well col-md-10">
							<form method="post" method="post" enctype="multipart/form-data">
								<div class="panel price panel-red">
									<h2>Product Details</h2>
									<small style="color:red">
									Note : If you want to add any product, please go to view product page to edit the existing product, namely "Empty product". 
									</small>
									<br><br>
									<div class="form-group">
										<label>Product Name</label>
										<input type="text" name="productname" maxlength="35" class="form-control" value="" required>
									</div>
							
									<div class="form-group">
										<label>Category</label>
<!--
										<input type="text" name="category"  value="" required>
-->
										
										<select  name="category" class="form-control">
									
											<?php 
										while ($row=mysqli_fetch_assoc($total_rows)) {
	// {                                   $category = str_replace(' ', '-', $row["category_name"]);?>
								 <option value="<?php echo $row['category_name'];?>"><?php echo $row["category_name"];?></option>
								 <br>
							<?php }
								?>
										</select>
									</div>
									
									<div class="form-group">
										<label>Product Code</label>
										<input type="text" name="product_type" class="form-control" value="" >
									</div>
									<div class="form-group">
										<label>Product Price</label>
										<input type="text" name="product_price" class="form-control" value="" >
									</div>   
									 <div class="form-group">
                                        <label>Printer Type <?php  $printer_profile=$row['printer_profile']; ?></label>
										
                                        <select class='' name="printer_profile" style="">
										  <option <?php if($printer_profile == 'ip') echo 'selected'; ?> value="ip">IP PRINTER</option>
                                            <option <?php if($printer_profile == 'usb') echo 'selected'; ?> value="usb">USB</option>
                                          
                                        </select>
                                    </div>
									<div class="form-group">
										<label>USB Sharing Name </label>
										<input type="text" name="usb_name" class="form-control" value="<?php if(isset($bank_data['usb_name'])){ echo $bank_data['usb_name']; }?>">
									   <p>Hint :From the Control Panel, open Devices and Printers.
										Right-click the printer you want to share. Click Printer Properties, and then select the Sharing tab.
										Check Share this Printer. Under Share name, select a shared name to identify the printer. Click OK. </p>
									</div>  
									<div class="form-group">
										<label>Print Ip address</label>
										<input type="text" name="print_ip_address" class="form-control" value="" >
									</div>
									<div class="form-group">
										<label>Print Ip address 2</label>
										<input type="text" name="printer_ip_2" class="form-control" value="" >
									</div>

									<div class="form-group">
										<label>Remark</label>
										<input type="text" name="product_remark" class="form-control" value="" >
									</div>
									<?php if($discounted_product){ ?>
									
									<div class="form-group">
										<label>Discount (in %)</label>
										<input type="text" name="product_discount" class="form-control" placeholder="Discount on Product in %">
									</div>
									<?php } ?>

									<div class="form-group">
										<label>Image</label><br>
										<input type="file" name="image_pic">
										<br>
										<?php
										if(isset($image_pic_error))
										{
											echo "<span style='color:red;'>".$image_pic_error."</span>";
										}
										?>
									</div>
									<div class="form-group">
										<label>Picture Code</label><br>
										<input type="file" name="code_pic">
										<br>
										<?php
										if(isset($image_code_error))
										{
											echo "<span style='color:red;'>".$image_code_error."</span>";
										}
										?>
									</div>
									<div class="form-group">
									  <label>Add to Cart Button</label>
									  <input type="checkbox" class="form-control" name="add_to_cart_button" checked/>
									</div>
									<br>
									<input type="submit" class="btn btn-block btn-primary" name="submit" value="Submit">
								</div>
							</form>
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
</body>

</html>
<style>
select {
    height: 30px;
}
</style>
