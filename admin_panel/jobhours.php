<?php 
include("config.php");
if(!isset($_SESSION['admin']))
{
	header("location:login.php");
}
$a_m="riders";

if($_POST['type'] == 'view_more'){
	
	$query_times = "SELECT * FROM `order_list` where rider_info =".$_POST['r_id']." and date(created_on) = '".$_POST['unique_date']."' order by id desc";
	$riders_22 = mysqli_query($conn,$query_times);
	$td_rows = '';
	$s = 1;
	echo $query_times;
	while($rowData = mysqli_fetch_assoc($riders_22)){
		$rider_od_assign_time = $rowData['rider_od_assign_time'];
		if($rowData['rider_complete_order'] == 1){
			$rider_complete_time = $rowData['rider_complete_time'];
		}else{
			$rider_complete_time = date('Y-m-d H:i:s');
		}
		
		$assign_time = new DateTime($rider_od_assign_time);
		$complete_time = new DateTime($rider_complete_time);
		$interval = $assign_time->diff($complete_time);
		
		$years = $interval->y;
		$months = $interval->m;
		$days = $interval->d;
		$hours = $interval->h;
		$minutes = $interval->i;
		
		$totaltime = $interval->h.":".$interval->i.":".$interval->s;
		$td_rows .='<tr><td>'.$s.'</td><td>'.$rowData['id'].' <b>Invoice:</b> #'.$rowData['invoice_no'].'</td><td>'.$totaltime.'</td></tr>';
		$s++;
	}
	echo $td_rows; exit;
}

if($_GET['r_id']){
	$r_id =$_GET['r_id'];
}         
$query="select * from order_list where rider_info = ".$r_id." and cancel_order != 1 and date(created_on) >='2021-05-01' order by id desc";

$riders = mysqli_query($conn,$query);

$query_22 = mysqli_query($conn,"select * from tbl_riders where r_id=".$r_id);
$c_data_22=mysqli_fetch_assoc($query_22);
	
	
$query_onoff="SELECT * FROM `rider_onoff` where rd_r_id =".$r_id;
$riders_onoff = mysqli_query($conn,$query_onoff);
$rideronoffArray = array();
while($riders_onoffarray = mysqli_fetch_array($riders_onoff)){
	
	
	if($riders_onoffarray['rd_online'] == 0){
		$rd_offline_time = $riders_onoffarray['rd_offline_time'];
	}else{
		$rd_offline_time = date('Y-m-d H:i:s');
	}
	$ontime = new DateTime($rd_offline_time);
	$rd_online_time = $riders_onoffarray['rd_online_time'];
	$offtime = new DateTime($rd_online_time);
	$interval_on = $ontime->diff($offtime);
	
	$years_on = $interval_on->y;
	$months_on = $interval_on->m;
	$days_on = $interval_on->d;
	$hours_on = $interval_on->h;
	$minutes_on = $interval_on->i;
	
	$total_seconds_on = 0;
	
	
	//if($riders_onoffarray['rd_online'] == 0){
		$totaltime_on = $interval_on->h.":".$interval_on->i.":".$interval_on->s;
		$total_seconds_on = ($interval_on->h * 3600) + ($interval_on->i * 60) + $interval_on->s;
	//}				
		$rideronoffArray[$riders_onoffarray['rd_online_date']]['time'][] = $total_seconds_on; 
		$rideronoffArray[$riders_onoffarray['rd_online_date']]['rd_r_id'] = $riders_onoffarray['rd_r_id']; 
	
}


$ridersArray = array();
while($riders_array = mysqli_fetch_array($riders)){
	$rider_od_assign_time = $riders_array['rider_od_assign_time'];
	//$rider_complete_time = $riders_array['rider_complete_time'];
	if($riders_array['rider_complete_order'] == 1){
		$rider_complete_time = $riders_array['rider_complete_time'];
	}else{
		$rider_complete_time = date('Y-m-d H:i:s');
	}
		
	
	$assign_time = new DateTime($rider_od_assign_time);
	$complete_time = new DateTime($rider_complete_time);
	
	$interval = $assign_time->diff($complete_time);
	
	$years = $interval->y;
	$months = $interval->m;
	$days = $interval->d;
	$hours = $interval->h;
	$minutes = $interval->i;
	
	/*
	echo "years===".$years;
	echo "<br/>";
	echo "months===".$months;
	echo "<br/>";
	echo "days===".$days;
	echo "<br/>";
	echo "hours===".$hours;
	echo "<br/>";
	echo "minutes===".$minutes;
	echo "<br/>";
	*/

	$total_seconds = 0;
	//if($riders_array['rd_online'] == 0){
		$totaltime = $interval->h.":".$interval->i.":".$interval->s;
		$total_seconds = ($interval->h * 3600) + ($interval->i * 60) + $interval->s;
	//}					
		$created_on = date("Y-m-d",strtotime($riders_array['created_on']));
		$ridersArray[$created_on]['time'][] = $total_seconds; 
		$ridersArray[$created_on]['rd_r_id'] = $riders_array['rider_info']; 
	
	
	
	
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
					<h2 class="text-center wallet_h"><?php echo $c_data_22['r_name']."'s";?> job Hours</h2>
					<br/>
					<table class="table table-striped kType_table" id="kType_table">
					    <thead>
					        <tr>
                                <th>SR</th>
                                <th>Date</th>
								<th>Online Hours(H:i:s)</th>
								<th>Total Hours(H:i:s)</th>
								<th>Job-Less Hours(H:i:s)</th>
                                <th>Actions</th>
						  </tr>
					    </thead>
					   <tbody>
                    	<?php
						$i = 1;
						if(count($ridersArray) > 0){
                       	foreach($ridersArray as $key1 => $value1){
							$totalhrs = array_sum($ridersArray[$key1]['time']);
							$onlinehrs = array_sum($rideronoffArray[$key1]['time']);
							$joblesshrs = $onlinehrs - $totalhrs;
							
							 ?>
                        	  <tr>
                                <td><?php echo $i;?></td>
                                <td><?php echo $key1;?></td>
								<td><?php echo gmdate("H:i:s", $onlinehrs);?></td>
								<td><?php echo gmdate("H:i:s", $totalhrs);?></td>
								<td><?php echo gmdate("H:i:s", $joblesshrs);?></td>
								<td><a class="view_details" unique_date="<?php echo $key1;?>" rd_r_id = "<?php echo $value1['rd_r_id']; ?>" href="javascript:void(0)"><i class="fa fa-eye"></i></a></td>
                                
                              </tr>
                    	<?php
                            $i++;  
                    	}
						}else{?>
						<tr>
                                <td colspan="6" style="text-align:center"> No Records Found</td>
						</tr>
						<?php }?>
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
	

<div id="myModal_time" class="modal fade" style="">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">TIMES Summary</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
				<table class="table table-striped kType_table" id="kType_table">
					    <thead>
					        <tr>
                                <th>SR</th>
                                <th>Order Id</th>
								<th>Total Time</th>
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
<script>
    $(document).ready(function(){
		$(".view_details").click(function(){
			var unique_date = $(this).attr('unique_date');
			var r_id = $(this).attr('rd_r_id');
			var cartData = {};
			cartData['unique_date'] = unique_date;
			cartData['r_id'] = r_id;
			cartData['type'] = 'view_more';
			jQuery.post('/admin_panel/jobhours.php', cartData, function (result) {
			//var response = jQuery.parseJSON(result);
			console.log(result);
				
				$(".times_more").html(result);
				$("#myModal_time").modal('show');
			});
				
		});
		//
	});
</script>
</body>
</html>
