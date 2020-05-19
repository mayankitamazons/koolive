<?php 
include("config.php");

if(!isset($_SESSION['admin']))
{
	header("location:login.php");
}
$rec_limit = 40;

/* end  for limit  */

 $sql = "select count(id) as total_count from jobs ";

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
     $query="select SQL_NO_CACHE * from jobs order by title desc LIMIT $offset, $rec_limit";

$user = mysqli_query($conn,$query);
$total_rows = mysqli_num_rows($user);
$total_page_num = ceil($total_rows / $limit);

$start = ($page - 1) * $limit;
$end = $page * $limit;
// $a_m="merchant";
if(isset($_GET['jobData']) && $_GET['jobData']=="data"){
	$id = $_GET['id'];
	$res = mysqli_fetch_assoc(mysqli_query($conn,"select * from jobs where id = '$id'"));
	// print_r($res);
	echo json_encode($res);
	die();
}
if(isset($_POST['update-job'])){
	extract($_POST);
	print_r($_POST);
	if($exDate==''){
		$finalExDate = $exExDate;
	}else{$finalExDate =strtotime($exDate);}
	
	$query = mysqli_query($conn,"UPDATE `jobs` SET `title`='$title',`job_desc`='$jobDesc',`price`='$jPrice',`posted_date_utc`='$postDate',`expire_date_utc`='$finalExDate',`job_category_id`='$category',`job_provider_name`='$jProname',`job_provide_mobile`='$jProNo',`job_provider_desc`='$jobProDesc'  WHERE id = '$id'");
	header('Location: jobs_list.php');
	
}
if(isset($_GET['data'])&&$_GET['data']=='deleteRecord'){
	$id = $_GET['id'];
	$query = mysqli_query($conn,"UPDATE `jobs` SET `view`='0' WHERE id='$id'");
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
					<h2 class="text-center wallet_h">Jobs List</h2>
					<!-- <button type="button" class="btn btn-danger" onclick="window.location.href='./user.php'">Clear Page</button> -->
					<button type="button" class="btn btn-danger pull-right" onclick="window.location.href='./addjobs.php'">Add Jobs</button>
				<h3> Total Records <?php  echo $rec_count;?></h3>
				<h4> Total Pages <?php  echo floor($rec_count/$rec_limit);?></h4>
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
                                <th>Job Title</th>
                                <th>Job Desc</th>
                                <th>Price</th>
                                <th>Posted Date</th>
                                <th>Expire Date</th>
                                <th>Job Category</th>
                                <th>Job Provider Name</th>
                                <th>Job Provider Mobile</th>
                                <th>Job Provider Desc</th>
                                <th>Status</th>
							    <th>Action</th>
						  </tr>
					    </thead>
					   <tbody>
                    	<?php
                        $i=1;
						$jobs = mysqli_query($conn, "SELECT jobs.*, job_category.category_name FROM `jobs` INNER JOIN job_category on jobs.job_category_id = job_category.id where jobs.view = '1'");
					
                    	while($row=mysqli_fetch_assoc($jobs)){
							 ?>
                        	  <tr>
                                <td><?php echo $i;?></td>
                                <td><?php echo isset($row['title'])?$row['title']:'';?></td>
                                <td><?php echo isset($row['job_desc'])?$row['job_desc']:'';?></td>
                                <td><?php echo isset($row['price'])?$row['price']:'';?></td>
                                <td><?php $postDate = $row['posted_date_utc']; echo Date("Y-m-d",$postDate)?></td>
                                <td><?php $exDate = $row['expire_date_utc']; echo Date("Y-m-d",$exDate);?></td>
                                <td><?php echo isset($row['category_name'])?$row['category_name']:'';?></td>
                                <td><?php echo isset($row['job_provider_name'])?$row['job_provider_name']:'';?></td>
                                <td><?php echo isset($row['job_provide_mobile'])?$row['job_provide_mobile']:'';?></td>
                                <td><?php echo isset($row['job_provider_desc'])?$row['job_provider_desc']:'';?></td>
                                <td><?php echo isset($row['job_status'])?$row['job_status']:'';?></td>
                        		 <td>
                                     <a href="javascript:void(0)" class="deleteRecord" jId="<?php echo $row['id']?>">Delete</a>
                                     <a href="javascript:void(0)" class="editJob" jId="<?php echo $row['id']?>">Edit</a>
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
	<!--edit model  -->
	<div class="modal fade" id="edit_info" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Modify Jobs</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form action="jobs_list.php" method="POST">
					<div class="form-group">
						<label for="title">Job Title <span style="color:red;">*</span></label>
						<input type="text" name="title" id="title" class="form-control"  placeholder="Job Title">
						<input type="hidden" name="id" id="id" >
					</div>
					<div class="form-group">
						<label for="title">Job Category<span style="color:red;">*</span></label>
						<select name="category" id="category" class="form-control" required>
							<option value="">--select job category--</option>
							<?php $catQuery = mysqli_query($conn,"select * from job_category where status ='y'");
								while($row=mysqli_fetch_assoc($catQuery)){?>
									<option value="<?php echo $row['id']?>" > <?php echo $row['category_name'];?> </option>
						<?php }?>
						</select>
					</div>
					<div class="form-group">
						<label for="title">Job Price <span style="color:red;">*</span></label>
						<input type="number" required name="jPrice" id="jPrice" class="form-control"  placeholder="Price">
					</div>
					<div class="form-group">
						<label for="comment">Job Description</label>
						<textarea name="jobDesc" id="jobDesc" class="form-control" placeholder="Job Description" rows="5" id="comment"></textarea>
					</div>
					<div class="form-group">
						<label for="birthday">Expire Date</label>
						<input type="date" class="form-control" name="exDate" id="exDate">
						<input type="hidden" name="exExDate" id="exExDate" >
						<input type="hidden" name="postDate" id="postDate" >
					</div>
					<div class="form-group">
						<label for="title">Job Provider Name <span style="color:red;">*</span></label>
						<input type="text" name="jProname" class="form-control" id="jProname"  placeholder="Job Provide Name">
					</div>
					<div class="form-group">
						<label for="title">Job Provider Contact <span style="color:red;">*</span></label>
						<input type="tel" required  maxlength=10 pattern="[0-9]{10}" required name="jProNo" id="jProNo" class="form-control"  placeholder="Job Provide Mobile">
					</div>
					<div class="form-group">
						<label for="comment">Job Provider Description</label>
						<textarea name="jobProDesc" class="form-control" placeholder="Job Provide Description" rows="5" id="jobProDesc"></textarea>
					</div>
					
				
			</div>
			<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="submit" name="update-job" class="btn btn-lg btn-outline-primary">Save Change</button>
				</form>
			</div>
			</div>
		</div>
	</div>
      <!-- edit model end -->
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
				
	
		
	
  $(".editJob").click(function(){
	  var id = $(this).attr('jId');
	  $.get("./jobs_list.php", {
		jobData: "data",
			id: id
		}, function(data){
			data = JSON.parse(data);
			console.log(data);
			$('#id').val(data['id']);
			$("#title").val(data['title']);
			$("#jPrice").val(data['price']);
			$("#jobDesc").val(data['job_desc']);
			$('#postDate').val(data['posted_date_utc']);
			$("#exExDate").val(data['expire_date_utc']);
			$("#jProname").val(data['job_provider_name']);
			$("#jProNo").val(data['job_provide_mobile']);
			$("#jobProDesc").val(data['job_provider_desc']);
			
			$("#category").prop("selected", false);
			$("#category option[value='" + data['job_category_id'] + "']").prop("selected", true);
			$("#edit_info").modal("show");
			

			

		});
		
	 
  });
  $(".deleteRecord").click(function(){
	  var id = $(this).attr('jId');
	//   alert(id+"deleteRecord called");
	  $.ajax({
			url:'jobs_list.php',
			method:'GET',
			data:{data:'deleteRecord',id:id},
			success:function(res){location.reload(true);}
	  });
	  
  });
	
	/*user delete function */
	
	// $('.del').click(function(){
    //     var id=$(this).data("del");
        
    //     $(".confirm-btn").attr({'user-id': id});
    // });
    // $('.confirm-btn').click(function(){
    //     var id = $(this).attr('user-id');
    //     $.ajax({
    //         url:'user_delete.php',
    //         type:'POST',
    //         data:{id:id},
    //         success: function(data) {
    //             location.reload();
    //         }
    //     });
    // });
				
				
	});
	  
	  
	</script>
	
</body>

</html>
