<?php 
include("config.php");
$merchant_tab="y";
$category_tab="y";

$me="add_category_button";
if(!isset($_SESSION['login']))
{
	header("location:login.php");
}
$bank_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$_SESSION['login']."'"));
 $current_id = $bank_data['id'];
   $current_date= date("Y/m/d");
if(isset($_POST['submit']))
{
	extract($_POST);
	$categoryname = addslashes($_POST['categoryname']);
	$catparent = $_POST['catparent'] ;
	mysqli_query($conn, "INSERT INTO  category SET rider1='$rider1',rider2='$rider2',rider3='$rider3',rider4='$rider4',rider5='$rider5',category_name='$categoryname', catparent='$catparent', user_id='$current_id',status= '0',created_date='$current_date'");
	header("location:view_category.php");
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
					<?php
						if(isset($error))
						{
							echo "<div class='alert alert-info'>".$error."</div>";
						}
					?>
					</div>
					<div class="container" >
					    <div class="row">
					        <div class="well col-md-10">
							<form method="post" method="post" enctype="multipart/form-data">
								<div class="panel price panel-red">
									<h2>Category Details</h2>
									<br><br>
									<?php
									$Cat_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM cat_mater WHERE UserID='".$_SESSION['login']."'"));
								
									$catData = explode(',' , $Cat_data['CatName']) ;
									?>
									<div class="form-group">
										<label>category Name</label>
										<select name ="catparent" required >
										 <option>Select Parent Category</option> 
										<?php
										$Count = 1 ; 
										foreach($catData as $Catname){
										 ?>
										 <option value='<?php echo $Count ?>'><?php echo $Catname ?></option>   
										  <?php
										  $Count = $Count + 1  ;
										}
										
										?>
										</select>
									</div>
									<div class="form-group">
										<label>category Name</label>
										<input type="text" name="categoryname" class="form-control" value="" required>
									</div>
									<div class="form-group">
										<label>Whatsapp 1</label>
										<input type="text" name="rider1" class="form-control">
									</div>
									<div class="form-group">
										<label>Whatsapp  2</label>
										<input type="text" name="rider2" class="form-control">
									</div>
									<div class="form-group">
										<label>Whatsapp  3</label>
										<input type="text" name="rider3" class="form-control">
									</div>
									<div class="form-group">
										<label>Whatsapp  4</label>
										<input type="text" name="rider4" class="form-control">
									</div>
									<div class="form-group">
										<label>Whatsapp 5</label>
										<input type="text" name="rider5" class="form-control">
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