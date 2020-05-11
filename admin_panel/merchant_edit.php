<?php 
include("config.php");

if(!isset($_SESSION['admin']))
{
	header("location:login.php");
}
if(isset($_GET['id']))
{
 $id = $_GET['id'];
}

if(isset($_POST['upload'])){
    $image_path = $_POST["image_path"];
    if($image_path != ""){
        $sql = "
            UPDATE users
            SET image='$image_path'
            WHERE id='$id';
        ";
        mysqli_query($conn, $sql);
    }
}
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users Where id='".$id."'"));

?>
<!DOCTYPE html>
<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">

<head>

<style>
.pagination {
  display: inline-block;
  padding-left: 0;
  margin: 20px 0;
  border-radius: 4px;
 }
 td.del {
    cursor: pointer;
}
 .pagination>li {
  display: inline;
 }
 .pagination>li:first-child>a, .pagination>li:first-child>span {
  margin-left: 0;
  border-top-left-radius: 4px;
  border-bottom-left-radius: 4px;
 }
 .pagination>li:last-child>a, .pagination>li:last-child>span {
  border-top-right-radius: 4px;
  border-bottom-right-radius: 4px;
 }
 .pagination>li>a, .pagination>li>span {
  position: relative;
  float: left;
  padding: 6px 12px;
  margin-left: -1px;
  line-height: 1.42857143;
  color: #337ab7;
  text-decoration: none;
  background-color: #fff;
  border: 1px solid #ddd;
 }
 .pagination a {
  text-decoration: none !important;
 }
 .pagination>.active>a, .pagination>.active>a:focus, .pagination>.active>a:hover, .pagination>.active>span, .pagination>.active>span:focus, .pagination>.active>span:hover {
  z-index: 3;
  color: #fff;
  cursor: default;
  background-color: #337ab7;
  border-color: #337ab7;
 }
 .hide_number{
     display: block;
 }
</style>
 
    <?php include("includes1/head.php"); ?>
    <link rel="stylesheet" type="text/css" href="/admin_panel/css/dropzone.css">
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
                <h3 style="margin-top: 0px; padding-top: 25px;">User Detail</h3>
                <div class="row" id="main-content" style="padding-top:25px">
				    
			        <div class="col-sm-3">
			            <label>Name:</label>
			            <input type="text" disabled value="<?php echo $user['name'];?>" class="form-control">
			        </div>
			        <div class="col-sm-3">
			            <label>Company Name:</label>
			            <input type="text" disabled value="<?php echo $user['company'];?>" class="form-control">
			        </div>
			        <div class="col-sm-3">
			            <label>Mobile Number:</label>
			            <input type="text" disabled value="<?php echo $user['mobile_number'];?>" class="form-control">
			        </div>
			        <div class="col-sm-3">
			            <label>Email:</label>
			            <input type="text" disabled value="<?php echo $user['email'];?>" class="form-control">
			        </div>
			        <div class="col-sm-3">
			            <label>Merchant Code:</label>
			            <input type="text" disabled value="<?php echo $user['merchant_code'];?>" class="form-control">
			        </div>
			        <div class="col-sm-3">
			            <label>Address:</label>
			            <input type="text" disabled value="<?php echo $user['address'];?>" class="form-control">
			        </div>
			        <div class="col-sm-3">
			            <label>Company Number:</label>
			            <input type="text" disabled value="<?php echo $user['register'];?>" class="form-control">
			        </div>
			        <div class="col-sm-3">
			            <label>GST Number:</label>
			            <input type="text" disabled value="<?php echo $user['gst'];?>" class="form-control">
			        </div>
			        <div class="col-sm-3">
			            <label>SST Number:</label>
			            <input type="text" disabled value="<?php echo $user['sst'];?>" class="form-control">
			        </div>
			        <div class="col-sm-3">
			            <label>Business1:</label>
			            <input type="text" disabled value="<?php echo $user['business1'];?>" class="form-control">
			        </div>
			        <div class="col-sm-3">
			            <label>Business2:</label>
			            <input type="text" disabled value="<?php echo $user['business2'];?>" class="form-control">
			        </div>
			        <div class="col-sm-3">
			            <label>Referral ID:</label>
			            <input type="text" disabled value="<?php echo $user['referral_id'];?>" class="form-control">
			        </div>
			        <div class="col-sm-3">
			            <label>Introducer ID:</label>
			            <input type="text" disabled value="<?php echo $user['referred_by'];?>" class="form-control">
			        </div>
			        <div class="col-sm-3">
			            <label>Location:</label>
			            <input type="text" disabled value="<?php echo $user['google_map'];?>" class="form-control">
			        </div>
			        <div class="col-sm-3">
			            <label>Hide Number:</label>
			            <input class="hide_number" type="checkbox" name="number_lock" <?php if($user['number_lock'] == '1') echo "checked='checked'";?>" ><br>
			        </div>
			        <div class="col-sm-3">
			            <label>K1/K2 Type:</label>
			            <input type="text" disabled value="<?php echo $user['account_type'];?>" class="form-control">
			        </div>
			        <div class="col-sm-3" style="text-align:center;">
			            <label>Merchant Image:</label>
			            <form action="" method="post">
			                <input type="hidden" class="image_path" name="image_path" value="" >
			                <input type="hidden" name="upload" value='upload'>
			                <div action="image_upload.php" class="dropzone" enctype="multipart/form-data" method="get" style="margin-bottom: 10px;">
    							<div class="fallback">
    						    	<input name="file" type="file" multiple />
    						    </div>
    						</div>
						    <button class="btn btn-primary" type="submit">Upload</button>
			            </form>
			        </div>
				</div>
                <div class="row">
                    <div class="col-sm-2">
                        <a class='form-control btn btn-default' style='margin-top: 10px;' href='merchant.php'>Back</a>
                    </div>
                </div>
			</main>
        </div>
        <!-- /.widget-body badge -->
    </div>
    <!-- /.widget-bg -->

    <!-- /.content-wrapper -->
    <?php include("includes1/footer.php"); ?>
    <script type="text/javascript" language="javascript" src="/admin_panel/js/dropzone.js"></script>
</body>
<script>
    jQuery(".dropzone").dropzone({
        success : function(file, response) {
            $(".image_path").val("/admin_panel" + response.target_file);
            console.log(response); 
           console.log("sssss");
            
        }
    });
</script>
</html>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">

<script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>

