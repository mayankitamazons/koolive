<?php 
include("config.php");

if(!isset($_SESSION['admin']))
{
	header("location:login.php");
}
$a_m="riders";
$rec_limit = 40;

/* end  for limit  */

$sql = "select count(id) as total_count from tbl_riders where r_status='1'";
$row = mysqli_fetch_assoc(mysqli_query($conn,$sql));
$rec_limit = 40;
$rec_count = $row['total_count'];

if( isset($_GET{'page'} ) ) {
	$page = $_GET{'page'};
	$offset = $rec_limit * $page ;
}else {
	$page = 0;
	$offset = 0;
}
         
$left_rec = $rec_count - ($page * $rec_limit);
$query="select SQL_NO_CACHE * from tbl_riders where r_status=1 order by r_id desc LIMIT $offset, $rec_limit";
$riders = mysqli_query($conn,$query);
$total_rows = mysqli_num_rows($riders);
$total_page_num = ceil($total_rows / $limit);
$start = ($page - 1) * $limit;
$end = $page * $limit;

if(isset($_GET['data'])&&$_GET['data']=='deleteRecord'){
	$id = $_GET['id'];
	$query = mysqli_query($conn,"UPDATE `tbl_riders` SET `r_status`=0 WHERE r_id='$id'");
	if($query){echo true;}else{die();}
}
?>
<!DOCTYPE html>
<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">
<head>   
    <?php include("includes1/head.php"); ?>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="/css/dropzone.css" type="text/css" /> 
	<style>
	.kType_table_filter{
		margin-top:10px !important;
	}
	
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
	#kType_table_paginate
	{
		display:none !important;
	}
	
	.wallet_h{
	    font-size: 30px;
        color: #213669;

	}
	.kType_table{
	    border: 1px #aeaeae solid !important;
	}
	.kType_table th, .kType_table td{
	    border: 1px #aeaeae solid !important;
	}
	.kType_table thead th{
	    border-bottom: 1px  #aeaeae solid !important;
	} 
	.kType_table tbody .complain{
	    color: red;
	    text-decoration: underline;
	}
	.sort{
	    margin-bottom: 10px;
	}
	/*kType_table tbody tr.k_normal{
	    background: #ececec;
	}*/
	#kType_table tbody tr.k_user{
	    background: #bcbcbc;
	}
	#kType_table tbody tr.k_merchant{
	    background: #dcdcdc;
	}
	.select2-container--bootstrap{
	    width: 175px;
	    display: inline-block !important;
	    margin-bottom: 10px;
	}
	@media  (max-width: 750px) and (min-width: 300px)  {
	    .select2-container--bootstrap{
	        width: 300px;
	    }
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
                <div class="container-fluid" id="main-content" style="padding-top:25px">
					<h2 class="text-center wallet_h">Rider List</h2>
					<!-- <button type="button" class="btn btn-danger" onclick="window.location.href='./user.php'">Clear Page</button> -->
					<button type="button" class="btn btn-danger pull-right" onclick="window.location.href='./addrider.php'">Add Rider</button>
					<br/>
				<!--<h5> Total Records <?php  echo $rec_count;?></h5>
				<h5> Total Pages <?php  echo floor($rec_count/$rec_limit);?></h5>-->
				<?php if($rec_count>25){ ?>        
					<p style="float:right;" class="pagecount">   
					 <?php
					      
								if( $page > 0 ) {
									$last = $page - 2;
  									echo "<a href = \"$_PHP_SELF?"."page=$last\">Last $rec_limit Records</a> |";
									echo "<a href = \"$_PHP_SELF?"."page=$page\">Next $rec_limit Records</a> |";
  									
								 }else if( $page == 0 ) {
									 echo "<a href = \"$_PHP_SELF?"."page=1\">Next $rec_limit Records</a> |";
								
								 }else if( $left_rec < $rec_limit ) {
									$last = $page - 2;
									 echo "<a href = \"$_PHP_SELF?"."page=$last\">Last $rec_limit Records</a> |";
									
								 }
							?>
					</p>
					<?php } ?>
					<table class="table table-striped kType_table" id="kType_table">
					    <thead>
					        <tr>
                                <th>SR</th>
								<th>Image</th>
                                <th>Rider Name</th>
								<th>Vehicle Number</th>
                                <th>Mobile</th>
								<th>Info</th>
							    <th>Action</th>
						  </tr>
					    </thead>
					   <tbody>
                    	<?php
                        $i=1;
						$riders = mysqli_query($conn, "SELECT * from tbl_riders where r_status = 1 order by r_id desc");
					    while($row=mysqli_fetch_assoc($riders)){
							 ?>
                        	  <tr>
                                <td><?php echo $i;?></td>
								<td>
								<?php 
								if($row['r_image'] != ''){
								$image_view = $site_url."/admin_panel/uploads/riders/".$row['r_image'];?>
								<img src="<?php echo $image_view;?>" height="100px" width="100px"/>
								<?php }?>
								</td>
                                <td><?php echo isset($row['r_name'])?$row['r_name']:'';?>
								<?php if($row['r_online']=="1"){?>
								<br/>
								<span class="btn btn-sm btn-primary" style="cursor:auto"><?php echo "Online";?></span>
								<?php }?>
								</td>
								<td><?php echo isset($row['r_vehicle_number'])?$row['r_vehicle_number']:'';?></td>
                                <td><?php echo isset($row['r_mobile_number'])?$row['r_mobile_number']:'';?></td>
								<td><?php echo isset($row['r_info'])?$row['r_info']:'';?></td>
								<td>
									<a class="mr-4" href="addrider.php?r_id=<?php echo $row['r_id']?>" title="Edit">
										<i class="fa fa-pencil" aria-hidden="true"></i>
									</a>
									<a href="javascript:void(0)" class="deleteRecord mr-4" jId="<?php echo $row['r_id']?>" title="Delete">
										<i class="fa fa-trash" aria-hidden="true"></i>
									</a>
									
									<a class="mr-4"  target="_blank" href="<?php echo $row['r_link']?>" title="View Rider's page">
										<i class="fa fa-eye" aria-hidden="true"></i>
									</a>
									
									
									<a class="mr-4"  target="_blank" href="workingrider.php?r_id=<?php echo $row['r_id']?>" title="View Rider's Hours">
										<i class="fa fa-clock-o" aria-hidden="true"></i>
									</a>
									
									
                                 </td>
                              </tr>
                    	<?php
                            $i++;  
                    	}?>
                	</tbody>  
					</table>
					<?php if($rec_count>25){ ?>    
					<p style="float:right;">
					 <?php
								if( $page > 0 ) {
									$last = $page - 2;
  									echo "<a href = \"$_PHP_SELF?"."page=$last\">Last $rec_limit Records</a> |";
									echo "<a href = \"$_PHP_SELF?"."page=$page\">Next $rec_limit Records</a> |";
  									
								 }else if( $page == 0 ) {
									 echo "<a href = \"$_PHP_SELF?"."page=1\">Next $rec_limit Records</a> |";
								
								 }else if( $left_rec < $rec_limit ) {
									$last = $page - 2;
									 echo "<a href = \"$_PHP_SELF?"."page=$last\">Last $rec_limit Records</a> |";
									
								 }
							?>
					</p>
					<?php } ?>
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
	






	<script>
	    $(document).ready(function(){
	        jQuery(".dropzone").dropzone({
                sending : function(file, xhr, formData){
                },
                success : function(file, response) {
                    $(".complain_image").val(file.name);
                    
                }
            });
            $('#kType_table').DataTable({
				"bSort": false,
				"pageLength":1000,
				dom: 'Bfrtip',
				 buttons: [
					'copy', 'csv', 'excel', 'pdf', 'print'
				]
				});
				
	
		
	
//   $(".editJob").click(function(){
// 	  var id = $(this).attr('jId');
// 	  $.get("./jobs_list.php", {
// 		jobData: "data",
// 			id: id
// 		}, function(data){
// 			data = JSON.parse(data);
// 			console.log(data);
// 			$('#id').val(data['id']);
// 			$("#title").val(data['title']);
// 			$("#jPrice").val(data['price']);
// 			$("#jobDesc").val(data['job_desc']);
// 			$("#wGNo").val(data['whatsAppGroup']);
// 			$('#postDate').val(data['posted_date_utc']);
// 			$("#exExDate").val(data['expire_date_utc']);
// 			$("#jProname").val(data['job_provider_name']);
// 			$("#jProNo").val(data['job_provide_mobile']);
// 			$("#jobProDesc").val(data['job_provider_desc']);
			
// 			$("#category").prop("selected", false);
// 			$("#category option[value='" + data['job_category_id'] + "']").prop("selected", true);
// 			$("#edit_info").modal("show");
			

			

// 		});
		
	 
//   });
  $(".deleteRecord").click(function(){

	var id = $(this).attr('jId');
	var cnfrmDelete = confirm("Are You Sure Delete This Riders ?");
	if(cnfrmDelete==true){
		  $.ajax({
				url:'riders.php',
				method:'GET',
				data:{data:'deleteRecord',id:id},
				success:function(res){location.reload(true);}
		  });	
	}
  });
	
	
				
				
	});
	  
	  
	</script>
	
</body>

</html>
