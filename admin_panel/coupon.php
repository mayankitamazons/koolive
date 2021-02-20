<?php 
include("config.php");

if(!isset($_SESSION['admin']))
{
	header("location:login.php");
}
$rec_limit = 500;

/* end  for limit  */

 $sql = "select count(id) as total_count from coupon Where coupon_type ='uni'";

$row = mysqli_fetch_assoc(mysqli_query($conn,$sql));
$rec_limit = 500;
  $rec_count = $row['total_count'];

if( isset($_GET{'page'} ) ) {
            $page = $_GET{'page'};
            $offset = $rec_limit * $page ;
         }else {
            $page = 0;
            $offset = 0;
         }
         
$left_rec = $rec_count - ($page * $rec_limit);
    $query="select SQL_NO_CACHE * from coupon Where coupon_type = 'uni' order by id desc LIMIT $offset, $rec_limit";

$user = mysqli_query($conn,$query);
$total_rows = mysqli_num_rows($user);
$total_page_num = ceil($total_rows / $limit);

$start = ($page - 1) * $limit;
$end = $page * $limit;
$a_m="coupon";
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
					<h3><?php echo "Universal Coupon List";?> <a href="addcoupon.php" class="btn btn-primary pull-right"><?php echo "Add Coupon Code";?></a></h3>

					
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
							<th>Coupon Code</th>
							<th>Title</th>
							<th>Order min</th>
							<th>Order max</th>
							<th>offer</th>
							<th>Valid From</th>
							<th>Valid to</th>
							
							<th>Remaning Coupon</th>
							<th>Per user use</th>
							<th>Status </th>
							<th>Action </th>
							
							
						  </tr>
					    </thead>
					   <tbody>
                    	<?php

					
		$i=1;
							while($row = mysqli_fetch_assoc($user))

							{

								$l_user_id=$row['user_id'];

								

								?>

								<tr>
									<td><?php echo $i; ?></td>
									<td><?php echo $row['coupon_code']; ?></td>
									<td><?php echo $row['title']; ?></td>

									<td><?php echo $row['total_min_price']; ?></td>
									<td><?php echo $row['total_max_price']; ?></td>

									<td><?php $plan_type=$row['type']; 
										if($plan_type=="fix")
										$plan_label="RM ".$row['discount']." off";
										else 
											$plan_label=$row['discount']." % off";
										echo $plan_label;
									?></td>

									

									<td><?php if(($row['valid_from']!='0000-00-00 00:00:00')){ echo date('M d, Y', strtotime($row['valid_from']));} else { echo "--";}  ?>
									<td><?php if(($row['valid_to']!='0000-00-00 00:00:00')){ echo date('M d, Y', strtotime($row['valid_to']));} else { echo "--";}  ?>
								    <td><?php echo $row['remain_user']; ?></td>
								    <td><?php echo $row['per_user_count']; ?></td>

									<td><?php if ($row['status'] == '1'): ?>

										<badge class="badge badge-success"><?php echo $language["active"];?></badge>

									<?php endif ?>

									<?php if ($row['status'] == '0'): ?>

										<badge class="badge badge-danger">Inactive</badge>

									<?php endif ?>

                        	    </td>

                        	    <td>
									
									<a class="mr-4" href="addcoupon.php?c_id=<?php echo $row['id'] ?>">
									<i class="fa fa-pencil" aria-hidden="true"></i>
									</a>
									<a class="mr-4" href="show_coupon.php?coupon_id=<?php echo $row['id'] ?>">
									<i class="fa fa-list" aria-hidden="true"></i>
									</a>
									<!--<a class="mr-4" href="give_subscription.php?plan_id=<?php echo $row['id'] ?>">-->
									<!--<i class="fa fa-user-plus" aria-hidden="true"></i>-->
									<!--</a>-->
								</td>

									

								</tr>

								<?php

								// die;
								$i++;
							}

							?>
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
				
	
				
				
	});
	  
	  
	</script>
	
</body>

</html>
