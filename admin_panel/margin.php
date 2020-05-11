<?php 
include("config.php");

if(!isset($_SESSION['admin']))
{
	header("location:login.php");
}

?>
<!DOCTYPE html>
<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">

<head>
    <?php include("includes1/head.php"); ?>
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
				<div class="col-sm-6" style="
    margin-left: 20%;
    background-color: white;
    padding-top: 15px;
    padding-bottom: 15px;" >
			  <h4 style="background-color: orange; text-align: center;
    padding-bottom: 10px;
    padding-top: 10px;
    width: 100%;"> Exchange Margin</h4> 
	
	<?php 
	$margin=mysqli_query($conn,"SELECT * FROM ex_margin");
	$row=mysqli_fetch_array($margin);
	?>
	      	<form>
			<div class="form-group">
      <label for="email">Margin :</label>
      <input type="text" required="" id="data" class="form-control" placeholder="enter margin" value="<?php echo $row['margin']; ?>">
    </div>
	<button type="button" id="submit">Submit</button>
	</form>
			
			</main>
        </div>
        <!-- /.widget-body badge -->
    </div>
    <!-- /.widget-bg -->

    <!-- /.content-wrapper -->
    <?php include("includes1/footer.php"); ?>
<script>
$("#submit").click(function(){
	var data = $("#data").val();
$.ajax({
		url : 'updatemargin.php',
		type: 'POST',
		data:{updateddata:data},
		success:function(data)
		{
			setTimeout(function(){location.reload();},2000);
		}
	});
});	
</script>	
</body>

</html>