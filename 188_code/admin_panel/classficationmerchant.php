<?php 
include("config.php");

if(!isset($_SESSION['admin']))
{
	header("location:login.php");
}

$a_m="classfication_merchant";
?>
<!DOCTYPE html>
<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">

<head>   
    <?php include("includes1/head.php"); ?>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="/css/dropzone.css" type="text/css" /> 
	
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
                <div class="container-fluid" id="main-content" style="padding-top:25px">
					<h2 class="text-center wallet_h">Classfication Type List
					<span style="float:right;" class="btn btn-primary"><a href="addclassfication.php">Add</a></span></h2>
				
					<?php if(isset($_SESSION['show_msg'])){ echo $_SESSION['show_msg']; unset($_SESSION['show_msg']);} ?>
					<table class="table table-striped kType_table" id="kType_table"> 
					    <thead>
					        <tr>
							<th>Particular</th>
							<th>Type Name</th>
							<th>Chinese Name</th>
							<th>Malaysian Name</th>
							
							<th>Action </th>
							
							
						  </tr>
					    </thead>
					   <tbody>
                    	<?php
                    	$i=1;
						$query=mysqli_query($conn,"select * from classfication_service where status='y'");
                    	while($row=mysqli_fetch_assoc($query)){
							 ?>
                        	  <tr>
									<td> <?php echo $i; ?> </td>
									<td class="name" data-id=<?php echo $row['id']; ?> style="cursor:pointer;"><?php echo $row['classification_name'];  ?></td>
									<td><?php echo $row['classification_name_chiness'];  ?></td>
									<td><?php echo $row['classification_name_mal'];?></td>
									<td>
									<a href="addnew_merchant.php?c_id=<?php echo $row['id']; ?>">List </a>
									<a href="addclassfication.php?c_id=<?php echo $row['id']; ?>">Edit </a>
									<!--a href="delete_classmerchant.php?c_id=<?php echo $row['id']; ?>">Delete </a!-->
									</td>
									<td>
									<?php if($row['status']==1){ ?>
									 <button class="btn btn-xs btn-success change_status"  change_type="n" data_id="<?php echo $row['id']?>">Active</button>
									<?php } else {?>
									 <button class="btn btn-xs btn-danger change_status" form_type="user" change_type="y" data_id="<?php echo $row['id']?>">De-Active</button>
									<?php } ?>
									</td>
							</tr>
                    	<?php
                            $i++;  
                    	}?>
                	</tbody>
					</table>   
					
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
	






	
	
</body>

</html>
