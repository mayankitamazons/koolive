<?php 
include("config.php");

if(!isset($_SESSION['admin']))
{
	header("location:login.php");
}

$rec_limit = 20;

/* end  for limit  */
$sql = "select count(dn_id) as total_count from tbl_donation ";
$row = mysqli_fetch_assoc(mysqli_query($conn,$sql));
$rec_limit = 20;
$rec_count = $row['total_count'];
if( isset($_GET{'page'} ) ) {
	$page = $_GET{'page'} + 1;
	$offset = $rec_limit * $page ;
}else {
	$page = 0;
	$offset = 0;
}
        
$left_rec = $rec_count - ($page * $rec_limit);
$query="SELECT * FROM `tbl_donation` order by dn_date desc LIMIT $offset, $rec_limit";

$user = mysqli_query($conn,$query);
$total_rows = mysqli_num_rows($user);
$total_page_num = ceil($total_rows / $limit);

$start = ($page - 1) * $limit;
$end = $page * $limit;
$a_m="donation";


if($_POST['method'] == 'update_payto'){
	$dn_id = $_POST['dn_id'];
	$dn_payto = $_POST['dn_payto'];
	$dn_paydate = date('Y-m-d H:i:s');
	echo $up_query = "UPDATE `tbl_donation` SET `dn_payto` = '".$dn_payto."', `dn_paydate` = '".$dn_paydate."', `dn_status` = '1' WHERE `tbl_donation`.`dn_id` = ".$dn_id;;
	$r_query = mysqli_query($conn,$up_query);
	die();
}


if(isset($_FILES["dn_receipt"]) && $_FILES["dn_receipt"]["error"] == 0){
		extract($_POST);
        $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
        $filename = $_FILES["dn_receipt"]["name"];
        $filetype = $_FILES["dn_receipt"]["type"];
        $filesize = $_FILES["dn_receipt"]["size"];
 
		$f_query= mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM `tbl_donation` where dn_id='$dn_id'"));
		

	
        // Verify file extension
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if(!array_key_exists($ext, $allowed)) die("Error: Please select a valid file format.");    
        
        // Verify MYME type of the file
        if(in_array($filetype, $allowed)){
			// Check whether file exists before uploading it
					$uniquesavename=time().uniqid(rand(1000,9999)).".png";
					
					$destFile = "donation/".$uniquesavename;
					require_once('../sirv.api.class.php');
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
						 $_FILES["dn_receipt"]["tmp_name"] 
						);
						$old_image = $f_query['dn_receipt'];
						
						if($old_image)
							{
								$old_image_path="donation/".$old_image;  
								$sirvClient->deleteFile($old_image_path);  
							}
						if($res['full_url']=='')
						{
							$destFile = "/home/koofamilies/public_html/images/donation/".$uniquesavename;
							move_uploaded_file($_FILES["dn_receipt"]["tmp_name"],$destFile); 
						}
						else
						{
							$banner_cdn_url=$uniquesavename;
						}

					} else {
					  // Connection FAILED
						$destFile = "/home/koofamilies/public_html/images/donation/".$uniquesavename;
						move_uploaded_file($_FILES["dn_receipt"]["tmp_name"],$destFile); 
					}
        } else{
            // echo "Error: There was a problem uploading your file. Please try again."; 
        }
		$update="update tbl_donation set dn_receipt='$banner_cdn_url' where dn_id='$dn_id'";
		mysqli_query($conn,$update);
		header("Location:donation.php");
exit();
    } 
	
if($_POST['type'] == 'view_more'){
	
	$query_times = "SELECT * FROM `users` where id IN (".$_POST['dn_userid'].")";
	$u_22 = mysqli_query($conn,$query_times);
	$td_rows = '';
	$s = 1;
	echo $query_times;
	while($rowData = mysqli_fetch_assoc($u_22)){
		$td_rows .='<tr><td>'.$s.'</td><td>'.$rowData['name'].'</td><td>'.$rowData['mobile_number'].'</td><td>RM 5.00</td></tr>';
		$s++;
	}
	echo $td_rows; exit;
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
					<h2 class="text-center wallet_h">Donation</h2>
					
					
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
							<th>Particular</th>
							<th>Date</th>
							<th>Total Donation</th>
							<th style="min-width:200px;">Pay To</th>
							<th style="min-width:200px;">Upload Receipt</th>
							<th>Who Pay</th>
						  </tr>
					    </thead>
					   <tbody>
                    	<?php
                    	$i=1;
                    	while($row=mysqli_fetch_assoc($user)){
							 ?>
                        	  <tr>
                        		 <td> <?php echo $i; ?> </td>
								 <td><?php  echo $row['dn_date'];?></td>
								 <td>RM <?php  echo number_format($row['dn_total'],2);?></td>
                           		 <td style="min-width:200px;">
									<textarea  style="max-width: 282px;" dn_id="<?php echo $row['dn_id']; ?>" class="form-control dn_payto" rows="3" name="dn_payto"><?php if(isset($row['dn_payto'])){ echo $row['dn_payto']; }?></textarea>
								 </td>
								 <td>
								 
									<form  action="" method="post" enctype="multipart/form-data">
									  <input name="dn_receipt" type="file">
									  <input name="dn_id" type="hidden" value="<?php echo $row['dn_id']; ?>">
									  <input type="submit" value="Upload" />
										<?php if ($row['dn_receipt']) {  ?>
											<img ref="dn_receipt" data-src="<?php echo $image_cdn; ?>donation/<?php echo $row['dn_receipt']; ?>?w=200" class="owl-lazy lazy2 Sirv" alt="">
										<?php }?>
									</form>
								</td>
								<td><a class="view_details" dn_userid="<?php echo $row['dn_userid'];?>" dn_id = "<?php echo $row['dn_id']; ?>" href="javascript:void(0)"><i class="fa fa-eye"></i></a></td>
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
	


<script src="https://scripts.sirv.com/sirv.js" defer></script>
<div id="myModal_time" class="modal fade" style="">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Who's Donation</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
				<table class="table table-striped kType_table" id="kType_table">
					    <thead>
					        <tr>
                                <th>SR</th>
                                <th>UserName</th>
								<th>PhoneNumber</th>
                                <th>Amount</th>
						  </tr>
					    </thead>
					   <tbody class="times_more">
					   </tbody>
				</table>
            </div>
			<div class="modal-footer">
		
			</div>
        </div>
    </div>
</div>

<script type="text/javascript">

	  $(document).ready(function() {
    $(".tags_merchant_select").select2();
});
</script>

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
	 $(".image").on('change', function() {
			 ///// Your code
	});
	
	
	$(".dn_payto").focusout(function(e){
		var dn_id = $(this).attr('dn_id');
		var dn_payto=this.value;
		if(dn_payto!='' && dn_id)
		{  
			$.ajax({
				 url :'donation.php',
				 type:"post",
				 data:{method:'update_payto',dn_id:dn_id,dn_payto:dn_payto},     
				 dataType:'json',
				 success:function(result){  
					
				}
			});      
		} 
	});
	
	$(".view_details").click(function(){
			var dn_userid = $(this).attr('dn_userid');
			var dn_id  = $(this).attr('dn_id');
			var cartData = {};
			cartData['dn_userid'] = dn_userid;
			cartData['dn_id'] = dn_id;
			cartData['type'] = 'view_more';
			jQuery.post('/admin_panel/donation.php', cartData, function (result) {
			//var response = jQuery.parseJSON(result);
				
				$(".times_more").html(result);
				$("#myModal_time").modal('show');
			});
				
		});
		
	
			
	});
	  
	  
	</script>
	
</body>

</html>
