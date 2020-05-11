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
				<table class="table table-striped">
	<thead>
      <tr>
        <th>Wallet</th>
        <th>Percent</th>
		<th>Last Change Time</th>
      </tr>
    </thead>
	<?php
	$charge=mysqli_query($conn,"SELECT * FROM charges");
	while ($row=mysqli_fetch_assoc($charge)){
	//if($row['wallet'] == "INR"){ continue; }
	?>
		<?php 
									if($row['wallet'] == "INR")
							{
								$wat = "CNY";
							}
							else
							{
								$wat = $row['wallet'];
							}
							
							?>
	<tbody>
	<tr>
	
	<td><?php echo $wat; ?></td>
	<td><input type="number" class="number" data-id="<?php echo $row['id']; ?>" value="<?php echo $row['percent'];?>"/> </td>
	<td><?php $date = $row['last_change']; echo  $updatedate = date("Y-m-d h:i:sa",$date); ?></td>
	</tr>
	
	
	</tbody>
	<?php
	
	}
	?>
					
				</div>
			</main>
        </div>
        <!-- /.widget-body badge -->
    </div>
    <!-- /.widget-bg -->

    <!-- /.content-wrapper -->
    <?php include("includes1/footer.php"); ?>
<script>
$(".number").change(function(){
	var percent = $(this).val();
	var id = $(this).data("id");
	
	$.ajax({
		url : 'updatecharge.php',
		type: 'POST',
		data:{recordid:id,updatedpercent:percent},
		success:function(data)
		{
			setTimeout(function(){location.reload();},2000);
		}
	});
	
});

$(".number").keyup(function(){
	var percent = $(this).val();
	var id = $(this).data("id");
	
	$.ajax({
		url : 'updatecharge.php',
		type: 'POST',
		data:{recordid:id,updatedpercent:percent},
		success:function(data)
		{
			setTimeout(function(){location.reload();},2000);
		}
	});
	
});



</script>	
</body>

</html>
