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

// if(isset($_GET['data'])&&$_GET['data']=='deleteRecord'){
// 	$id = $_GET['id'];
// 	$query = mysqli_query($conn,"UPDATE `jobs` SET `view`='0' WHERE id='$id'");
// 	if($query){echo true;}else{die();}
// }

									
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
                                <th>Post Date</th>
                                <th>Expire Date</th>
                                <th>Advance salery</th>
                                <th>Salery Type</th>
                                <th>Job Category</th>

                                <th>Job Provider Name</th>
                                <th>Job Provider Mobile</th>
                                
                                <th>Status</th>
							    <th>Action</th>
						  </tr>
					    </thead>
					   <tbody>
                    	<?php
                        $i=1;
						$jobs = mysqli_query($conn, "SELECT jobs.*, job_category.category_name FROM `jobs` INNER JOIN job_category on jobs.job_category_id = job_category.id where jobs.postedStatus = '0' order by id desc");
					
                    	while($row=mysqli_fetch_assoc($jobs)){
							 ?>
                        	  <tr>
                                <td><?php echo $i;?></td>
                                <td><?php echo isset($row['title'])?$row['title']:'';?></td>
                                <td><?php echo isset($row['job_desc'])?substr($row['job_desc'],0,80):'';?></td>
                                <td><?php echo isset($row['price'])?$row['price']:'';?></td>
                                <td><?php $postDate = $row['posted_date_utc']; echo Date("d-m-Y H:i A",$postDate)?></td>
                                <td><?php $exDate = $row['expire_date_utc']; echo Date("d-m-Y",$exDate);?></td>
                                <td><?php echo isset($row['advance_salery'])?$row['advance_salery']:'';?></td>
                                <td><?php echo isset($row['salaryType'])?$row['salaryType']:'';?></td>
                                <td><?php echo isset($row['category_name'])?$row['category_name']:'';?></td>
                                <td><?php echo isset($row['job_provider_name'])?$row['job_provider_name']:'';?></td>
                                <td><?php echo isset($row['job_provide_mobile'])?$row['job_provide_mobile']:'';?></td>
                                
                                <td>
									<?php 
									 $edate = $row['expire_date_utc'];
									 $cdate = strtotime(Date("Y-m-d"));

										if($cdate<$edate){echo "Posted";}else{echo "Expired";}
									?>
								</td>
                        		 <td>
                                     <a href="javascript:void(0)" class="acceptRecord" uid="<?php echo $row['id']?>">Accept</a>
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
	

    <div class="modal fade" id="edit_info" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Accept Job</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
                <form action="postedJob.php" method="POST">
                    <div class="form-group">
                        <label for="title">WhatsApp Group<span style="color:red;">*</span></label>
                        <input type="text"   name="wGNo" class="form-control"  placeholder="Whatsapp Group">
                        <input type="hidden" name="id" id="aid" >
                    </div>
                    <div class="form-group">
                        <label for="comment">Job Provider Description</label>
						<textarea name="jobProDesc" class="form-control" required  placeholder="Job Provide Description" rows="5" ></textarea>
						<span style="color:red;font-weight:600;" id="error"></span>
                    </div>
                
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" name="accept-Job" class="btn btn-primary save_close">Save changes</button>
                </form>
			</div>
			</div>
		</div>
	</div>




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
				

            $(".deleteRecord").click(function(){
                var id = $(this).attr('jId');
                //   alert(id+"deleteRecord called");
                $.ajax({
                        url:'postedJob.php',
                        method:'GET',
                        data:{data:'deleteRecord',id:id},
                        success:function(res){location.reload(true);}
                });
                
                
            });
	
            $(".acceptRecord").on("click", function(){
                 var id = $(this).attr('uid');
                //  alert(id);
                $('#aid').val(id);
                 $("#edit_info").modal("show");
            });
				
				
	});
	  
	  
	</script>

	
</body>

</html>
<?php
if(isset($_POST['accept-Job'])){
	extract($_POST);
	// print_r($_POST);
	if($wGno==''){
        $wn = 60123945670;
    }else{
        $wn = $wGNo;
    }
   
	$query = mysqli_query($conn,"UPDATE `jobs` SET `job_provider_desc`='$jobProDesc',`whatsAppGroup`='$wn',`view`='1',`postedStatus`='1'  WHERE id = '$id'");
	if($query){
        header('Location: jobs_list.php');
    }
	
}

?>