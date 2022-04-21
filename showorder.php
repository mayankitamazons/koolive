<?php 
include('config.php');
// print_R($_SESSION);
// die;
 if(!isset($_SESSION['s_admin']))
 {
	 header("location:rl.php");
 }
$shopclose = array();
if(!function_exists('checktimestatus')){
	function checktimestatus($time_detail)
	  {
		global $shopclose;
			extract($time_detail);
 //			 echo "day=$day n=$n";
// 		 	echo "ctime:".date("H:i");
			$day=strtolower($day);
			$currenttime=date("H:i");
			$n=strtolower(date("l"));
			//echo $day."===".$n;
			if(($currenttime >$starttime && $currenttime < $endttime) && ($day==$n)){
				  //$shop_close_status="y";
				  //echo 'here';
				   array_push($shopclose,"y");
			}
			else
			{ 
			  //$shop_close_status="n";
			  array_push($shopclose,"n");
			}
			//print_R($shopclose);
			return $shopclose;//$shop_close_status;
	  }	
}
if(empty($_GET['ms']))
{
	$sid=$_GET['sid'];
	$url="showorder.php?sid=".$sid."&ms=".md5(rand());

header("Location:$url");
exit();
}
if (empty($_SESSION["langfile"])) { $_SESSION["langfile"] = "english"; }
    require_once ("languages/".$_SESSION["langfile"].".php");
// $query="select  order_list.*,u.shop_open,u.working_text,u.working_text_chiness,u.not_working_text_chiness,u.not_working_text,u.name as merchant_name,u.latitude,u.longitude,u.mobile_number as merchant_mobile_number,u.whatsapp_link,u.foodpanda_link,u.vendor_comission as vc_user, u.price_hike as price_hike_user from order_list inner join users as u on u.id=order_list.merchant_id  order by order_list.id desc limit 0,100";
$rider_where  = '';
$infrm_where = '';
$accept_where = '';
$notdone_where = '';
if($_GET['order'] == 'pendings'){
	$rider_where = " where  rider_info = 0 and cancel_order!=1 and created_on >'2021-04-07 12:00:00'";
}


if($_GET['order'] == 'accepted'){
	$accept_where = " where  rider_info != 0 and rider_accept_id = 0 and cancel_order!=1 and created_on >'2021-04-07 12:00:00'";
}



if($_GET['order'] == 'infrms'){
	//$infrm_where = " where  inform_rider_arrive_minute	= 0 and cancel_order!=1 and created_on >'2021-04-21 12:00:00'";
	$infrm_where = " where  inform_mecnt_status = 0 and cancel_order!=1 and created_on >'2021-04-21 12:00:00'";
	
}

if($_GET['order'] == 'notdone'){
	$cu_time = date('Y-m-d H:i:s');
	$notdone_where = ' where rider_complete_order = 0 and cancel_order != 1 and created_on >"2021-04-07 12:00:00" and time_to_sec(timediff("'.$cu_time.'", order_list.created_on)) / 3600 > 1';
}


if (isset($_GET['pageno'])) {
    $pageno = $_GET['pageno'];
} else {
    $pageno = 1;
}
$no_of_records_per_page = 100;
$offset = ($pageno-1) * $no_of_records_per_page; 

$query="select order_list.user_id as od_user_id,user.otp_verified,order_list.*,user.mobile_number as user_mobile_number,u.google_map,user.id as l_user_id,user.user_remark,user.lat_lng,u.merchant_remark,u.shop_open,u.working_text,u.sst_rate,u.working_text_chiness,u.not_working_text_chiness,u.not_working_text,u.name as merchant_name,u.c1_code,u.merchant_short_name,u.merchant_remark_image,user.latitude,user.longitude,u.mobile_number as merchant_mobile_number,u.whatsapp_link,u.foodpanda_link,u.vendor_comission as vc_user, u.price_hike as price_hike_user from order_list 
inner join users as u on u.id=order_list.merchant_id  
inner join users as user on user.id=order_list.user_id
".$rider_where." ".$infrm_where." ".$accept_where." ".$notdone_where."
order by order_list.id desc limit ".$offset.",".$no_of_records_per_page." ";

#echo $query;

$current_time = date('Y-m-d H:i:s');
function ceiling($number, $significance = 1)
{
	return ( is_numeric($number) && is_numeric($significance) ) ? (ceil(round($number/$significance))*$significance) : false;
}

if($profile_data['user_roles']==5)
{
	$loginidset=$profile_data['parentid'];
}
else
{

	$loginidset=$_SESSION['login'];

}

$parent_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='$loginidset'"));

$s_admin_google_link = '';
$select_s_admin = mysqli_fetch_assoc(mysqli_query($conn, "SELECT google_link FROM `s_admin`"));
$s_admin_google_link = $select_s_admin['google_link'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<!-- Google Tag Manager -->
<?php include("includes1/head_google_script.php"); ?>
<!-- End Google Tag Manager -->
  <title>Latest order</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">-->
  <link rel="stylesheet" href="/css/bootstrap.min.css">
  
  <link rel="stylesheet" href="./css/font-awesome.min.css">  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>-->
  <script src="/js/bootstrap.min.js"></script>

</head>
<body>
<!-- Google Tag Manager (noscript) -->
<?php include("includes1/body_google_script.php"); ?>
<!-- End Google Tag Manager (noscript) -->
<?php 

//order not completed in 1 hour
$cu_time = date('Y-m-d H:i:s');
$order_not_done_count = 0;
//echo 'select TIMEDIFF("'.$cu_time.'", od.created_on) ,od.id,od.created_on,od.invoice_no ,now(), od.rider_complete_time from order_list as od inner join users as u on u.id=od.merchant_id inner join users as user on user.id=od.user_id where rider_complete_order = 0 and cancel_order != 1 and created_on >"2021-04-07 12:00:00"';
//select time_to_sec(timediff("2021-06-02 18:24:01", od.created_on)) / 3600 as time_difff ,od.id,od.created_on,od.invoice_no ,now(), od.rider_complete_time from order_list as od inner join users as u on u.id=od.merchant_id inner join users as user on user.id=od.user_id where rider_complete_order = 0 and cancel_order != 1 and created_on >"2021-04-07 12:00:00" and   time_to_sec(timediff("2021-06-02 17:24:01", od.created_on)) / 3600 > 1
$order_not_done = mysqli_fetch_assoc(mysqli_query ($conn,'select count(*) as not_done_count from order_list as od inner join users as u on u.id=od.merchant_id inner join users as user on user.id=od.user_id where rider_complete_order = 0 and cancel_order != 1 and created_on >"2021-04-07 12:00:00" and time_to_sec(timediff("'.$cu_time.'", od.created_on)) / 3600 > 1'));
$order_not_done_count = $order_not_done['not_done_count'];



$order_pedning_count = 0;
$pending_orders =  mysqli_query($conn,'select count(*) as order_cnt from order_list inner join users as u on u.id=order_list.merchant_id inner join users as user on user.id=order_list.user_id where rider_info = 0 and cancel_order!=1 and created_on >"2021-04-07 12:00:00"');
$pending_order_count = mysqli_fetch_assoc($pending_orders);

$order_pedning_count = $pending_order_count['order_cnt'];


$rider_wait_accept = 0;
$rider_wait_orders = mysqli_query($conn,'select count(*) as rider_wait_cnt from order_list inner join users as u on u.id=order_list.merchant_id inner join users as user on user.id=order_list.user_id where rider_info != 0 and rider_accept_id = 0 and cancel_order!=1 and created_on >"2021-04-07 12:00:00"');
$rider_wait_results = mysqli_fetch_assoc($rider_wait_orders);
$rider_wait_accept = $rider_wait_results['rider_wait_cnt'];


$order_inf_count = 0;
$pending_infrms =  mysqli_query($conn,'select count(*) as order_cnt from order_list inner join users as u on u.id=order_list.merchant_id inner join users as user on user.id=order_list.user_id where  inform_mecnt_status = 0 and cancel_order!=1 and created_on >"2021-04-21 12:00:00"');

#echo 'select count(*) as order_cnt from order_list inner join users as u on u.id=order_list.merchant_id inner join users as user on user.id=order_list.user_id where inform_rider_arrive_minute = 0 and cancel_order!=1 and created_on >"2021-04-21 12:00:00"';
$order_inf_counts = mysqli_fetch_assoc($pending_infrms);

$order_inf_count = $order_inf_counts['order_cnt'];



$riders_processjob = mysqli_query($conn,"select r.r_id,count(od.id)as ordercount from tbl_riders as r LEFT JOIN order_list as od ON od.rider_info = r.r_id and rider_complete_order = 0  and cancel_order!=1  where r_status = 1 group by r.r_id");
/*and r_online = 1 */
$riderJobArray = array();
while($rjob_result = mysqli_fetch_assoc($riders_processjob)){
	$riderJobArray[$rjob_result['r_id']] = $rjob_result['ordercount'];
}	

$riders_query_2 = "select * from tbl_riders where r_status = 1 and r_online = 1";
//$riders_query = "select (select count(*) as dd from order_list as od where od.rider_info = r.r_id and rider_complete_order = 0 ) as rider_process_count,r.* from tbl_riders as r where r_status = 1 and r_online = 1";
$ridersFetch2 = mysqli_query($conn,$riders_query_2);

$riders_query_offline = "select * from tbl_riders where r_status = 1 and r_online = 0";
//$riders_query = "select (select count(*) as dd from order_list as od where od.rider_info = r.r_id and rider_complete_order = 0 ) as rider_process_count,r.* from tbl_riders as r where r_status = 1 and r_online = 1";
$ridersFetchOffline = mysqli_query($conn,$riders_query_offline);


//FPX count

$fpxCount = 0;
$fpx_count_order =  mysqli_fetch_assoc(mysqli_query($conn,"SELECT count(t_id)as count_fpx FROM `tbl_temp_saveorder` as ts Inner JOIN users as cust ON cust.id = ts.t_user_id Inner JOIN users as mer ON mer.id = ts.t_m_id where t_transid = '' and t_payment_status = '' and t_createddate >= '2021-04-22 20:51:06' and t_showstatus = 0 ORDER BY `ts`.`t_id` DESC"));
$fpxCount = $fpx_count_order['count_fpx'];


$fpx_last_order =  mysqli_fetch_assoc(mysqli_query($conn,"SELECT t_createddate FROM `tbl_temp_saveorder` as ts Inner JOIN users as cust ON cust.id = ts.t_user_id Inner JOIN users as mer ON mer.id = ts.t_m_id where t_transid = '' and t_payment_status = '' and t_createddate >= '2021-04-22 20:51:06' and t_showstatus = 0 ORDER BY `ts`.`t_id` DESC limit 1"));

$fpx_last_date = $fpx_last_order['t_createddate'];
$fpx_date = new DateTime($fpx_last_date);
$now_zone = new DateTime(Date('Y-m-d H:i:s'));
$interval_fpx = $fpx_date->diff($now_zone);
$minutes_fpx = $interval_fpx->i;


$gp_check =  mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM `google_check`"));
$gp_check_time = new DateTime($gp_check['google_sheet_check_time']);
$interval_gp = $gp_check_time->diff($now_zone);
$minutes_gp = $interval_gp->i;

?>

<div class="container" style="margin-left:3px !important;width:100%">
  <h2>Latest Order <?php if(count($fpx_last_order) != 0){?>
  <a href="fpx_orders.php" target="_blank" class="btn-sm btn-yellow <?php if($minutes_fpx >=5){?>blink_info_button<?php }?>" style="cursor:pointer;color: white;padding: 10px;font-weight: bold;background-color:red;margin:3px;">FPX Order</a>
  <?php }?>
  
  <?php 
  //echo $minutes_gp;
  $cls_gl = '';
  if($minutes_gp >=5 ){
	  $cls_gl = 'blink_info_button';
  }?>
  <a href="javascript:void(0)" class="btn-sm btn-yellow google_sheet_check <?php echo $cls_gl;?> " style="cursor:pointer;color: white;padding: 10px;font-weight: bold;background-color:red!important;border-color:red !important;margin:3px;">Google Sheet</a>
  
  </h2>
  
  
  
  
  <?php 
	$green_riders = array();
	$red_riders = array();
	
	while($r_value1 = mysqli_fetch_assoc($ridersFetch2)){
		if($riderJobArray[$r_value1['r_id']] == 0){
			$r_css = "green";
			$green_riders[] = $r_value1;
		}else if($riderJobArray[$r_value1['r_id']] != 0){
			$r_css = "red";
			$red_riders[] = $r_value1;
		}else{
			$r_css = "black";
		}
		//echo "Jobs:".$riderJobArray[$r_value1['r_id']]." ".$r_value1['r_name']."(".$r_value1['r_mobile_number'].")"; 
}
?>
<div>
<?php foreach($green_riders as $gkey => $gvalue){
		$currentDATE = $gvalue['button_click_time'];
		//$date_riders=date_create($currentDATE);
		//$riderTimeDiff  = date_diff($date_riders, date_create($current_time));
		//$diff_rider_hour = $riderTimeDiff->h;
		$timestamp1 = strtotime($currentDATE);
		$timestamp2 = strtotime($current_time); //currentdatetime
		$diff_rider_hour = round(abs($timestamp2 - $timestamp1)/(60*60));	

        if(intval($diff_rider_hour) >= 8){?>
			<a style="background-color:#4b1879;border-color:#4b1879;cursor:pointer;margin:3px;" diff_rider_hour = "<?php echo $diff_rider_hour;?>" riderId="<?php echo $gvalue['r_id'];?>" href="javascript:void(0);" class="status btn btn-primary button_click_rideradmin" ridername ="<?php echo $gvalue['r_name'];?>"><?php echo $gvalue['r_name']." (".$riderJobArray[$gvalue['r_id']].")";?></a>
		<?php }else{?>
			<a style="background-color:green;border-color:green;cursor:pointer;margin:3px;" diff_rider_hour = "<?php echo $diff_rider_hour;?>" riderId="<?php echo $gvalue['r_id'];?>" target="_blank" href="<?php echo $gvalue['r_link'];?>" class="status btn btn-primary"><?php echo $gvalue['r_name']." (".$riderJobArray[$gvalue['r_id']].")";?></a>
		<?php }?> 


<?php }?>

<?php foreach($red_riders as $rkey => $rvalue){?>
<a style="background-color:red;border-color:red;cursor:pointer;margin:3px;" target="_blank" href="<?php echo $rvalue['r_link'];?>" class="status btn btn-primary"><?php echo $rvalue['r_name']." (".$riderJobArray[$rvalue['r_id']].")";?></a>
<?php }?>
</div>

<br/>
<div style=" margin:unset;padding:unset">
<h4>Offline Riders:</h4>
<?php  while($r_value_offline = mysqli_fetch_assoc($ridersFetchOffline)){
	if(date('Y-m-d') != $r_value_offline['offline_update_date']){
		$style_offriders = 	"background-color:red;border-color:red;cursor:pointer;margin:3px;";
		$class_offline1 = "blink_info_button";
	}else{
		$style_offriders = 	"background-color:lightblue;border-color:lightblue;cursor:pointer;margin:3px;";
		$class_offline1 = "";
	}
	?>
<a style="<?php echo $style_offriders;?>" href="javascript:void(0)" 
offline_admin_name ="<?php echo $r_value_offline['offline_admin_name'];?>" 
offline_reason ="<?php echo $r_value_offline['offline_reason'];?>" 
offline_time ="<?php echo $r_value_offline['offline_time'];?>" 
ridername ="<?php echo $r_value_offline['r_name'];?>" 
riderId="<?php echo $r_value_offline['r_id'];?>" class="<?php echo $class_offline1;?> status btn btn-primary offline_riders"><?php echo $r_value_offline['r_name']." (".$riderJobArray[$r_value_offline['r_id']].")";?></a>
<?php }?>
</div>          
 <nav aria-label="Page navigation example" style="text-align:right">
	<ul class="pagination">
		<?php for ($x = 1; $x <= 5; $x++) {
			$clas_active = "";
			if($pageno == $x){
				$clas_active = "active";
			}
			?>	
			<li class="page-item <?php echo $clas_active;?>"><a class="page-link " href="showorder.php?sid=<?php echo $sid;?>&ms=<?php echo md5(rand());?>&pageno=<?php echo $x;?>"><?php echo $x;?></a></li>
		<?php }?>
	</ul>
	</nav>
  <table class="table">
    <thead>
      <tr>
        <th>S.No</th>
        
        <th>DATE OF ORDER</th>
		 <th>Order Status</th>
		   <th>Action</th>
		   <th>Write up</th>
        <th>Detail <a href='showorder.php?sid=<?php echo $sid;?>&ms=<?php echo md5(rand());?>&order=infrms'>
		<span style="color:red;font-size:22px">(<?php echo $order_inf_count;?>)</span></a>
		</th>
		<th>User Detail</th>
		<th>Rider Info 
		<a href='showorder.php?sid=<?php echo $sid;?>&ms=<?php echo md5(rand());?>&order=pendings' title="Rider Assign Pending">
		<span style="color:red;font-size:22px">(<?php echo $order_pedning_count?>)</span></a>
		
		<a href="showorder.php?sid=<?php echo $sid;?>&ms=<?php echo md5(rand());?>&order=accepted" title="Waiting of Rider Accept">
		<span style="color:red;font-size:22px">(<?php echo $rider_wait_accept;?>)</span></a>
		
		
		<a href="showorder.php?sid=<?php echo $sid;?>&ms=<?php echo md5(rand());?>&order=notdone" title="Order not completed in 1 hour">
		<span style="color:red;font-size:22px">(<?php echo $order_not_done_count;?>)</span></a>
		
		
		
		
		
		</th>
        <th>Selling Price</th>
		<th>Purchase Price</th>
        
        <th>Merchant Name</th>
        <th>Merchant Address</th>
        <th>Food Panda Link</th>
        <th>Whatsapp  Link</th>
        <th>Merchant Mobile Number</th>
      </tr>
    </thead>
    <tbody>
	<?php 
     $qu=mysqli_query($conn,$query);
	 
	 if (isset($_GET['pageno']) && $_GET['pageno']!= 1 ) {
		$c=(($_GET['pageno']-1)*100)+1;
	 }else{
		$c=1; 
	 }
	 while($r=mysqli_fetch_array($qu))
	 {
		   $created =$row['created_on'];
		 $date=date_create($created);
		 $row=$r;
		        if($r['status'] == 0)
								{
									$sta =$language['pending'];
									$s_color="red";
									$n_status=1;
								}
                                else if($r['status'] == 1) 
								{
									
									$sta =$language['done_in_delivery'];
									$s_color="green";
									$n_status=4;
								}
								else if($r['status'] == 4 || $r['status']==5) 
								{
									$sta =$language['done_in_delivery'];
									$s_color="green";
								}
                                else 
								{
									$n_status=1;
									$sta =$language['accepted'];
									// $sta = "Accepted";
									$s_color="";
								}
								if($row['rider_complete_order'] == 1){
									$n_status='';
									$sta ='completed';
									// $sta = "Accepted";
									$s_color="green";
								}	
								if($row['cancel_order'] == 1){
									$n_status='';
									$sta ='Cancelled';
									// $sta = "Accepted";
									$s_color="#eca7a7";
									$b_color = "border-color:#eca7a7";
								}
								
		 $dteDiff  = date_diff($date, date_create($current_time));
                              $diff_day = $dteDiff->d;
                              if($diff_day != '0') $diff_day .= ' days ';
                              else $diff_day = '';
                              $diff_hour = $dteDiff->h;
                              if(intval($diff_hour) < 10) $diff_hour = '0'.$diff_hour.':'; else $diff_hour = $diff_hour.':';
                              $diff_minute = $dteDiff->i;
                              if($diff_minute < 10) $diff_minute = '0'.$diff_minute.':'; else $diff_minute = $diff_minute.':';
                              $diff_second = $dteDiff->s;
                              if($diff_second < 10) $diff_second = '0'.$diff_second;
                              $diff_time = $diff_day.'<br>'.$diff_hour.$diff_minute.$diff_second;	
							  $created_on = $row['created_on'];
								$old_date = new DateTime($created_on);
								$now = new DateTime(Date('Y-m-d H:i:s'));
								//echo date('Y-m-d H:i:s');
								//echo '<br/>';
								$interval = $old_date->diff($now);
		
							$row_cls = '';
							if($r['cancel_order'] != 1 ){
								if($row['rider_complete_order'] != 1)
								{
									if($interval->h >= 1){
										$row_cls = 'blink_info_button';
									}
								}
							}
		
		
	?>
      <tr class="<?php echo $row_cls;?>" style="background-color:white !important;">
        <td >
		<?php //echo "====>>>".$interval->h;?>
		<?php echo $c;  $c++;?></td>
		
		                            <td style="width:150px"><?php //echo date_format($date,"m/d/Y h:i A");  ?>   
									
									<?php echo date('m/d/Y h:i A',strtotime($row['created_on']))	;  ?>
								
                                <?php echo '<br>'; echo $new_time[1] ?>
                                <?php 
                                  if($row['status'] == 0){?>
                                    <p style="color: red;"><?php echo $diff_time; ?></p> <?php 
                                  }?>
								  
								  <!-- payment proof -->
							<br/>
					<p style="width:150px;">
					   <a target="_blank" href="merchant_order.php?orderid=<?php echo $row['id']; ?>" class="btn-sm btn-yellow" style="background-color:lightgreen;border-color:lightgreen;margin-top:10px;width:150px;color:white">
					  Merchant Order
						  </a>
						  </p>
							<!-- End Payment Proof-->
							
							<?php 
							$cls_del = 'blink_info_button';
							if($row['rider_info'] != 0){
								$cls_del = '';
							}?>
							
							<?php /*?>
							<?php
							$speed_man = '';
							$chinese_man = '';
							if($row['special_delivery_amount']!= '0'){
								$chinese_man = 'Chiness Delivery';
							?>
							<label class="btn-sm btn-yellow <?php echo $cls_del;?>" style="background-color:red;border-color:red;margin-top:10px;width:150px;color:white">
								<?php echo $chinese_man;?>
							</label>
							<?php }?>
							<?php if($row['speed_delivery_amount']!= '0'){
								$speed_man = 'Speed Delivery';
							?>
							<label class="btn-sm btn-yellow <?php echo $cls_del;?>" style="background-color:red;border-color:red;margin-top:10px;width:150px;color:white">
								<?php echo $speed_man;?>
							</label>
							<?php }?>
							<?php */?>
							
							
							
							
							<!-- Speed Delivery--->
							<!-- Chinese Man Delivery--->
							
							
							
							
                            </td>
		   <td><input type="button" next_status="<?php echo $n_status; ?>" style="background-color:<?php echo $s_color;?>;<?php echo $b_color;?>" class= "status btn btn-primary" value="<?php  echo $sta;?>" status="<?php echo $row['status'];?>" data-invoce='<?php echo $row['invoice_no'];?>' data-id="<?php echo $row['id']; ?>"/>



<br/>		<!-- Show error in order if total not match 24/01/2021--->		<?php $amount_val_array = array();
    $quantity_ids_array = array();
    $product_ids_array = array();
    $product_price_array = array();
    $product_varient_array = array();
    $v_array = array();
    $v_comisssion_chk = number_format($r['vendor_comission'], 2);
	$price_hike_user = number_format($r['price_hike_user'],2); //from users table
	$vendor_comission_user = number_format($r['vc_user'],2); //from users table
	
	
    $special_delivery_amount_chk = $r['special_delivery_amount'];
    $amount_val_array = explode(",", $r['amount']);
    $quantity_ids_array = explode(",", $r['quantity']);
    $product_ids_array = explode(",", $r['product_id']);
    $varient_type = $r['varient_type'];
    if ($varient_type)
    {
        $v_str = $r['varient_type'];
        $v_array = explode("|", $v_str);
    }
    $totalArray = array();
    foreach ($amount_val_array as $key => $value)
    {
        if ($quantity_ids_array[$key] && $value)
        {
            $totalArray[] = $value * $quantity_ids_array[$key];
        }
    }
    $total = array_sum($totalArray);
    if ($v_comisssion_chk)
    {
        $cash_term_payment = number_format($total - $v_comisssion_chk, 2);
    }
    else
    {
        if ($special_delivery_amount_chk)
        {
            $cash_term_payment = number_format($total, 2) + number_format($special_delivery_amount_chk, 2);
        }
        else
        {
            $cash_term_payment = number_format($total, 2);
        }
    }
    $i = 0;
    foreach ($product_ids_array as $key)
    {
        if (is_numeric($key))
        {
            $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id ='" . $key . "'"));
            $product_varient_array[$key]['varient'] = $product['varient_must'];
            $product_price_array[] = $product['product_price'] * $quantity_ids_array[$i];
        }
        if ($v_array[$i])
        {
            $show_error = 'no';
            $v_match = $v_array[$i];
            $v_match = ltrim($v_match, ',');
            $sub_rows = mysqli_query($conn, "SELECT * FROM sub_products WHERE id  in ($v_match)");
            while ($srow = mysqli_fetch_assoc($sub_rows))
            {
                //$product_price_array[] = number_format($srow['product_price'], 2);
				$subprice =  $srow['product_price'] * $quantity_ids_array[$i];
				$product_price_array[] = number_format($subprice,2); 
                $sub_product_varient_array[$key] = 'yes';
                $product_varient_array[$key][$srow['id']] = $product['varient_must'];
            }
        }
        else
        {
            if ($product['varient_must'] == 'y')
            {
                if (count($v_array) == 0)
                {
                    $show_error = 'yes';
                }
                else
                {
                    $show_error = 'no';
                }
            }
        }
        $i++;
        $p++;
    }
	
	//echo $Final_price;
	
    $allproduct_price = array_sum($product_price_array);
    $allproduct_price_digit = bcadd(sprintf('%F', $allproduct_price) , '0', 1);
    $cash_term_payment_digit = bcadd(sprintf('%F', $cash_term_payment) , '0', 1);
    $price_diff = abs($cash_term_payment_digit - $allproduct_price_digit);
	echo "cashprice = ".$cash_term_payment_digit;	
	echo '<br/>';
	echo "totalproduct = ".$allproduct_price_digit;
	echo '<br/>';
	echo "Diff = ".$price_diffprice_diff; // 28

	echo '<br/>';
	echo "VC = ".$vendor_comission_user;
	echo '<br/>';
	echo "Hike = ".$price_hike_user;
	echo '<br/>';
	
	if(round($price_diff) > 1){
	   // echo 'here';
	   echo '<br/><br/><input type="button" style="background-color:red" class= "btn btn-danger" value="Error in order" />';
	}									
   /* if ($show_error == 'yes')
    {
        echo '<br/><br/><input type="button" style="background-color:red" class= "btn btn-danger" value="Error in order" />';
    }
    if ($allproduct_price_digit != $cash_term_payment_digit)
    {
        echo '<br/><br/><input type="button" style="background-color:red" class= "btn btn-danger" value="Error in order" />';
    } */?>		<!-- Show error in order if total not match 24/01/2021--->																								



</td>
<td>
<a target="_blank" class="btn btn-danger" style="background-color:#41bade;border-color:#41bade" href="orderview.php?did=<?php echo $row['merchant_id'];?>&vs=<?php  echo md5(rand());?>">
Check order</a>




<?php 
$rider_arrive_shop_time = $row['rider_arrive_shop'];
$arriv_time = new DateTime($rider_arrive_shop_time);
$now_arriv = new DateTime(Date('Y-m-d H:i:s'));
$interval_arriv = $arriv_time->diff($now_arriv);
$hours_arriv = $interval_arriv->h;
$minutes_arriv = $interval_arriv->i;
//echo $minutes_arriv;
//if($minutes_arriv >= 3){
	if($row['rider_arrive_shop'] != '0000-00-00 00:00:00'){
		//if($row['update_merchnt_details'] == '0000-00-00 00:00:00'){
			if($row['order_cancel'] != 1 && $row['rider_accept_id'] != 0){
				//if($row['rider_complete_order'] != 1){
					//echo '<span style="color:red">Riders not updated cash or Bank price!!</span>';
					$rider_cash_bank_price = $row['rider_bank_amount'] + $row['rider_cash_amount'];
					$price_diff_a = $cash_term_payment_digit - $rider_cash_bank_price;
					$show_price_diff = number_format($price_diff_a,2);
					//echo $show_price_diff;
					$chk_p_cond = str_replace("-","",$show_price_diff);
					//if($row['rider_m_price_diff'] != ''){
					if($show_price_diff != ''){
						$class_diff = '';
						$diff_style = '';
						//if($row['rider_m_price_diff'] >1){
							
						//if($price_diff_a > 1 ){	
						if($chk_p_cond > 1 ){	
							if($row['rider_reason_dif'] == ''){
							//if($price_diff_a != ''){	
								$class_diff="blink_info_button";
								$diff_style = "style='padding:5px;color:white;'";
							}
	
?>
<br/>

<b>Price Diff:</b> <label <?php echo $diff_style;?> class="<?php echo $class_diff;?>"><?php echo $show_price_diff;//$row['rider_m_price_diff'];?></label>
<br/>
<b>Reason:</b><textarea class="form-control rider_reason_dif" name="rider_reason_dif" id="rider_reason_dif" order_id="<?php echo $row['id']; ?>" style="width:150px"/><?php echo $row['rider_reason_dif'];?></textarea>
<?php 					}
					}
				//}
			}
	}
//}

?>

<br/>
		<?php if($row['remark_extra'] != ''){?>
		<p style="color:red;font-size:20px"><b>Remark: </b><?php echo $row['remark_extra'];?></p>
		<?php }?>
		
		<?php if($row['otp_verified'] == 'n'){?>
		
		<p style="font-size:14px;margin-bottom:10px;background-color:red;border-color:red" class="btn btn-primary verified_user verified_<?php echo $row['id'];?> <?php if($cash_term_payment_digit > '50'){?>  blink_info_button <?php }?>" user_id ='<?php echo $row['od_user_id']; ?>' order_id='<?php echo $row['id'];?>' >Unverified User</p>
		<?php }?>
		
		
		
		  <?php if($row['payment_proof'] != '' ){?>
		    <br/>
						  <label class="btn-sm btn-yellow" style="background-color:darkgreen;margin-top:10px;width:150px">
						  <a class="fancybox" rel="" href="<?php echo $site_url.'/upload/'.$row['payment_proof'];?>" style="color:white" >
Payment Proof </a>
<a href="javascript:void(0)" class="delete_paymentproof" orderid="<?php echo $row['id']; ?>" style="color:#000"><i class="fa fa-trash" style="margin-left:20px;color:white"> </i></a>
						  </label>
					  <?php }else{?>
					  <!--<label class="btn-sm btn-yellow" style="background-color:#fb9678;border-color:#fb9678;margin-top:10px;width:150px;color:white">
					  No payment proof !!
						  </label>-->
						  <?php if($row['wallet'] == 'Internet Banking'){?>
						  <br/>
						  <form method="post" id="image-form_<?php echo $row['id']; ?>" class="image-form" orderid='<?php echo $row['id']; ?>' enctype="multipart/form-data" onSubmit="return false;" style="min-height:0px !important;width:203px">
										<div class="input-group mt-3 mb-3 input-has-value flex-wrap">
											
											<input type="file" name="file" class="file" style="visibility: hidden;position: absolute;">
											<input type="text" class="form-control payment_proof" disabled="" placeholder="Payment Proof" id="file" style="width:85px">
											<button type="button" class="browse btn btn-primary rounded-0" style="width: 53px;padding-left: 0px;padding-right: 0px;">Browse</button>
											
											&nbsp;
											<input type="submit" name="submit" value="Submit" class="btn btn-danger btn_proof_upload rounded-0" style="width: 53px;padding-left: 0px;padding-right: 0px;">
										</div>
										</form>
						  <?php }?>
					  <?php }?>
		
		
		
</td>
<td >
			
			<a style="display:none" class="btn btn-primary copy-text cust_<?php echo $row['id'];?>" onclick="copyToCustAdd('#writeup_set_<?php  echo $row['id'];?>','cust_<?php echo $row['id'];?>','<?php echo $row['id'];?>')"> Copy </a>
			
			<a style="display:none" class="btn btn-primary  copy-text orderdeatils_<?php echo $row['id'];?>" onclick="copyToOrderDetails('#od_copy_details_<?php  echo $row['id'];?>','orderdeatils_<?php echo $row['id'];?>')"> Copy Order Details </a>
			<br/>
			<span class="writeup_set" id="writeup_set_<?php  echo $row['id'];?>" order_id='<?php echo $row['id']; ?>'><i class="fa fa-copy" style="font-size:25px;margin-left: 10%;"></i><span></td>
		
							
		<td  style="font-size:18px;min-width:200px" >
		<!--
		<span class="s_order_detail btn btn-blue" total_bill="<?php echo number_format($total_bill,2); ?>" order_id='<?php echo $row['id']; ?>'><?php echo $language['detail']; ?></span>-->
		
		<!-- b1 code--->
		<p style="<?php if($row['b1_code']  == ''){echo 'display:block;';}else{echo 'display:none;';}?>" class="gc_main_<?php echo $row['id']; ?>">
		<input type="text" class="form-control b1_code" name="b1_code" id="b1_code<?php echo $row['id']; ?>" order_id='<?php echo $row['id']; ?>' placeholder="B1 Code" style="width:80px;float:left" value="<?php echo $row['b1_code'];?>">
		<a  name="button" order_id='<?php echo $row['id']; ?>' class="btn btn-danger generate_code rounded-0" style="width: 103px;padding-left: 0px;padding-right: 0px;">Generate Code</a>
		</p>
		<p style="<?php //if($row['b1_code']  == ''){echo 'display:none;';}else{echo 'display:block;';}?>" class="copycode_<?php echo $row['id']; ?>">
			<?php $fpcode = '';if($row['foodpanda_link'] != ''){$fpcode = '-FP';}
			 if($r['merchant_short_name'] != ''){ $merchant_short_name = $r['merchant_short_name']; }else{ $merchant_short_name = substr($r['merchant_name'],0,7);}
			 $odc_remark = '';
			  $bcode = '';
			 if($row['remark_extra'] != ''){$odc_remark = "-".$row['remark_extra'];}
			 if($row['b1_code'] != ''){ $bcode = "-".$row['b1_code'];}
			 if($row['wallet'] == 'Internet Banking'){
				$pay_type = 'Bank';	
			 }else if($row['wallet'] == 'cash'){
				$pay_type = 'Cash';	
			 }else if($row['wallet'] == 'fpx'){
				$pay_type = 'fpx';	
			 }else{
				$pay_type = 'wallet';	 
			 }
			 
			$speed_man1 = '';
			$chinese_man1 = '';
			if($row['special_delivery_amount']!= '0'){
				$chinese_man1 = '-Chiness';
			}
			if($row['speed_delivery_amount']!= '0'){
				$speed_man1 = '-Speed';
			}
			$odTime = '';
			if($row['od_delivery_date'] != '' && $row['od_delivery_time'] != '00:00:00' ){
				$deliveryTime = '';
				if($row['od_delivery_time'] != '00:00:00' || $row['od_delivery_time'] != '' ){
					$deliveryTime = "(".$row['od_delivery_time'].")";
				}
				$odTime = "-".$row['od_delivery_date']." ".$deliveryTime;
			}

			 
			?>
			<span id="codecontent_<?php echo $row['id']; ?>">
			Yet-<?php echo $r['c1_code']; ?><span id="b1value_<?php echo $row['id']; ?>"><?php echo $bcode; ?></span><?php echo "-".$merchant_short_name.$fpcode."-".$row['invoice_no']."-".$pay_type.$odc_remark.$chinese_man1.$speed_man1.$odTime; ?>
			</span>
			<a class="btn btn-primary copy-text gccopy_<?php echo $row['id'];?>" onclick="gccopy('#codecontent_<?php  echo $row['id'];?>','gccopy_<?php echo $row['id'];?>','<?php echo $row['id'];?>')"> Copy </a>
		</p>
		<!-- END b1 code -->
		<!--- info Merchant Button-->
		<?php 
		
		$years = $interval->y;
		$months = $interval->m;
		$days = $interval->d;
		$hours = $interval->h;
		$minutes = $interval->i;
		$alert_show = 'no';
		if($years == 0 && $months == 0 && $days== 0 && $hours == 0 ){
			if($minutes <= 6){
			}else{
				//echo 'ALERT';
				$alert_show = 'yes';
			}
		}else{
			//echo 'ALERT2';
			$alert_show = 'yes';
		}
		
		
		//inform_step1 blink
		$alert_show_ss = 'no';
		if($row['inform_shop_open'] ==1){
			$inform_shop_time = $row['inform_shop_time'];
			$inform_shop_time1 = new DateTime($inform_shop_time);
			$now_ss = new DateTime(Date('Y-m-d H:i:s'));
			$interval_ss = $inform_shop_time1->diff($now_ss);
			/*$years = $interval_ss->y;
			$months = $interval_ss->m;
			$days = $interval_ss->d;
			$hours = $interval_ss->h;
			*/$minutes_ss = $interval_ss->i;
			$alert_show_ss = 'no';
			//echo $minutes_ss;echo '<br/>';
			if($minutes <= 20){
			}else{
				//echo 'ALERT';
				$alert_show_ss = 'yes';
			}
		}
		
		//echo $alert_show_ss;
		// echo $interval->d.' days<br>';
		// echo $interval->y.' years<br>';
		// echo $interval->m.' months<br>';
		if($r['status']!=4)
		{
		// echo $interval->h.' hours<br>';
		// echo $interval->i.' minutes<br>';
		// echo $interval->s.' seconds<br>';
		
			$order_place_date = new DateTime($created_on);
			
			if($r['rider_complete_order'] == 1){
				$order_complete_time = $r['rider_complete_time'];
				$order_now = new DateTime($order_complete_time);
			}else{
				$order_now = new DateTime(Date('Y-m-d H:i:s'));
			}
			//echo date('Y-m-d H:i:s');
			//echo '<br/>';
			$interval_complete = $order_place_date->diff($order_now);
								
			if($interval_complete->d){
				$order_main_complete_time =  $interval_complete->d."Day,".$interval_complete->h." Hour:".$interval_complete->i." min :"."</br>";
				//$order_main_complete_time =   $interval->d."Day,".$interval->h." Hour: ".$interval->i." Min :"."</br>";
			}else{
				$order_main_complete_time =  $interval_complete->h." Hour :".$interval_complete->i." Min"."</br>";
				//$order_main_complete_time =   $interval->h." Hour : ".$interval->i." Min"."</br>";
			}
			//echo $interval->h;
			if($interval_complete->h >= 1){
				$t_style = "background-color:red;border-color:red;color:black;";
			}else{
				$t_style = "background-color:#f3db07;border-color:#f3db07;color:black;";
			}

		?>
<?php if($row['cancel_order'] == 0){?>
		<span class="btn btn-sm btn-danger" style="<?php echo $t_style;?>font-weight: bold;cursor:auto;text-transform: uppercase;margin-bottom:10px;font-size:16px;"><?php echo $order_main_complete_time;?></span>
		<br/>
<?php }?>
		<?php
	
		}
		?>

<style>
.blink_info_button {
  -webkit-border-radius: 10px;
  
  -webkit-animation: glowing 1500ms infinite;
  -moz-animation: glowing 1500ms infinite;
  -o-animation: glowing 1500ms infinite;
  animation: glowing 1500ms infinite;
}
@-webkit-keyframes glowing {
  0% { background-color: #B20000; -webkit-box-shadow: 0 0 3px #B20000; }
  50% { background-color: #FF0000; -webkit-box-shadow: 0 0 40px #FF0000; }
  100% { background-color: #B20000; -webkit-box-shadow: 0 0 3px #B20000; }
}

@-moz-keyframes glowing {
  0% { background-color: #B20000; -moz-box-shadow: 0 0 3px #B20000; }
  50% { background-color: #FF0000; -moz-box-shadow: 0 0 40px #FF0000; }
  100% { background-color: #B20000; -moz-box-shadow: 0 0 3px #B20000; }
}

@-o-keyframes glowing {
  0% { background-color: #B20000; box-shadow: 0 0 3px #B20000; }
  50% { background-color: #FF0000; box-shadow: 0 0 40px #FF0000; }
  100% { background-color: #B20000; box-shadow: 0 0 3px #B20000; }
}

@keyframes glowing {
  0% { background-color: #B20000; box-shadow: 0 0 3px #B20000; }
  50% { background-color: #FF0000; box-shadow: 0 0 40px #FF0000; }
  100% { background-color: #B20000; box-shadow: 0 0 3px #B20000; }
}
.edit_info_merchant{
	padding: 10px;
	cursor:pointer;
}.green{
	color:green;
}
.red{
	color:red;
}
</style>
		<?php //if($row['inform_mecnt_status'] != '0' ){
			
			if($row['inform_mecnt_status'] == 1){
				//$labels = 'Inform already';
				$labels = 'Fp Order nama Koo Family';
				$lab_cls = 'green';
			}else if($row['inform_mecnt_status'] == 2){
				$labels = 'Sudah call guna 5670';//'Cannot reach  merchant, now inform customer rider is otw checking';
				$lab_cls = 'red';
			}else if($row['inform_mecnt_status'] == 3){
				$labels = 'FP & beli sendiri juga';//'Rider buy himself';
				$lab_cls = 'green';
			}else if($row['inform_mecnt_status'] == 4){
				$labels = 'FP & sudah Call guna 5670';//'Order Cancelled';
				$lab_cls = 'red';
			}else if($row['inform_mecnt_status'] == 5){
				$labels = 'Semua beli sendiri';//'Order Cancelled';
				$lab_cls = 'red';
			}else if($row['inform_mecnt_status'] == 6){
				$labels = 'Order Cancelled';
				$lab_cls = 'red';
			}else if($row['inform_mecnt_status'] == 7){
				$labels = 'Sudah group order, guna Koo Family';
				$lab_cls = 'red';
			}else{
				$labels = '';
				$lab_cls = '';
			} 
			?>
		
			<span class="<?php echo $lab_cls;?>" order_id='<?php echo $row['id']; ?>' >
			
				<label style="font-size:12px;font-weight:normal"><?php echo $labels;?></label>
			
			<?php if($row['informpop_name'] != ''){ echo '<br/> RM '.$row['informpop_name'];}?>
			
			<?php if($row['informtime_complete'] != ''){ echo '<br/>'.$row['informtime_complete']." minutes";}?>
			
			
			
			
			<?php if($row['info_merchant_admin'] != ''){?>
			<br/>Admin: <span class="red"><?php echo $row['info_merchant_admin'];?></span>
			<?php }?>
			</span>
			
			<?php if($row['info_merchant_admin'] != '' || $labels != '' ){?>
			<span class="edit_info_merchant info_merchant" invoice_no='<?php echo $row['invoice_no']; ?>' informpop_name='<?php echo $row['informpop_name'];?>' informtime_complete='<?php echo $row['informtime_complete']; ?>'  order_id='<?php echo $row['id']; ?>' title="Edit" inform_mecnt_status="<?php echo $row['inform_mecnt_status'];?>" admin_name="<?php echo $row['info_merchant_admin'];?>" ><i class="fa fa-pencil"></i></span>
			<?php }?>
			
			
		<?php //}else{
					$class_alert = '';
					$class_alert_ss = '';
				if($alert_show == 'yes'){
					$class_alert = 'blink_info_button';
				}
				if($alert_show_ss == 'yes'){
					$class_alert_ss = 'blink_info_button';
				}
			?>
			<!--			
			<span class="btn btn-danger info_merchant <?php echo $class_alert;?>" invoice_no='<?php echo $row['invoice_no']; ?>' order_id='<?php echo $row['id']; ?>'>Admin 名字成功通知商家</span>
			-->
			
			<?php if($row['inform_shop_open'] == 0 && $row['inform_rider_arrive_minute']== 0 && $labels == ''){?>
				<br/><span class="btn btn-danger info_merchant_shop <?php echo $class_alert;?>" invoice_no='<?php echo $row['invoice_no']; ?>' order_id='<?php echo $row['id']; ?>'>马上下单商家,通知领取时间 <!--Is the shop open and food available?--></span>
			<?php }else if($row['inform_shop_open'] == 1 && $row['inform_rider_arrive_minute']== 1 && $labels == ''){ ?>
				<br/><span class="btn btn-danger info_merchant <?php echo $class_alert_ss;?>" informpop_name='<?php echo $row['informpop_name'];?>' informtime_complete='<?php echo $row['informtime_complete']; ?>' invoice_no='<?php echo $row['invoice_no']; ?>' order_id='<?php echo $row['id']; ?>' style="background-color:#b13e3b !important;border-color:#b13e3b !important;">Admin 名字成功通知商家</span>
			<?php }else if($row['inform_shop_open'] == 1 && $labels == ''){?>
				<br/><span class="btn btn-danger info_merchant_food <?php echo $class_alert_ss;?>" invoice_no='<?php echo $row['invoice_no']; ?>' order_id='<?php echo $row['id']; ?>' style="background-color:lightpink !important;color:black;border-color:lightpink !important;">估计司机抵达前30分钟，再提醒商家 <!--Are you sure that rider can arrive within 30minutes?--></span>
			<?php }?>

		<?php //}?>
		
			
		<!-- END---->
		
		<!-- Start Cancel Order --->
		<?php //if($row['cancel_order'] == 1){?>
		<?php //}else{?>
		<!--<a style="background-color:#eca7a7;border-color:#eca7a7;" class="btn btn-danger cancel_order cancel_order_<?php echo $row['id']; ?>" order_id='<?php echo $row['id']; ?>' href="javascript:void(0);">Cancel Order</a>
		-->
		<?php //echo $row['cancel_reason'];?>
		
		<!--
		<select name="inform_mecnt_status" id="inform_mecnt_status<?php echo $row['id']; ?>" class="form-control inform_mecnt_status" style="margin-bottom:10px;"  order_id='<?php echo $row['id']; ?>'>
			<option <?php if($row['inform_mecnt_status'] == 0){echo 'selected';}?> value="0">Select Status</option>
			<option <?php if($row['inform_mecnt_status'] == 1){echo 'selected';}?> value="1">Fp Order nama Koo Family</option>
			<option <?php if($row['inform_mecnt_status'] == 2){echo 'selected';}?> value="2">Sudah call guna 5670</option>
			<option <?php if($row['inform_mecnt_status'] == 7){echo 'selected';}?> value="7">Sudah group order, guna Koo Family</option>
			<option <?php if($row['inform_mecnt_status'] == 3){echo 'selected';}?> value="3">FP & beli sendiri juga</option>
			<option <?php if($row['inform_mecnt_status'] == 4){echo 'selected';}?> value="4">FP & sudah Call guna 5670</option>
			<option <?php if($row['inform_mecnt_status'] == 5){echo 'selected';}?> value="5">Semua beli sendiri</option>
			<option <?php if($row['inform_mecnt_status'] == 6){echo 'selected';}?> value="6">Order Cancelled</option>
		</select>-->
		
		
		<input type="text" class="form-control code_admin_code <?php if($interval_complete->i >= 6 && $row['info_merchant_admin'] == '' ){  if($row['cancel_order'] == 0){ echo 'blink_info_button';} }?> " name="code_admin_code" id="code_admin_code<?php echo $row['id']; ?>" order_id='<?php echo $row['id']; ?>' placeholder="外地加钱吗？Admin Name" style="float:right;height:10%;;background-color:white!important;margin-top:10px;margin-bottom:10px" value="<?php echo $row['info_merchant_admin'];?>">
						
						
						
		<select name="cancel_reason" id="cancel_reason" class="cancel_order form-control"  order_id='<?php echo $row['id']; ?>'>
			<option value="0">Select Cancel Reason</option>
			<option <?php if($row['cancel_reason'] == 1){echo 'selected';}?> value="1">Non-working hours</option>
			<option <?php if($row['cancel_reason'] == 2){echo 'selected';}?> value="2">Shop close temporary</option>
			<option <?php if($row['cancel_reason'] == 3){echo 'selected';}?> value="3">Foods not available</option>
			<option <?php if($row['cancel_reason'] == 4){echo 'selected';}?> value="4">Customer change mind</option>
			<option <?php if($row['cancel_reason'] == 6){echo 'selected';}?> value="6">Duplicate Order</option>
			<option <?php if($row['cancel_reason'] == 7){echo 'selected';}?> value="7">Testing Order</option>
			<option <?php if($row['cancel_reason'] == 5){echo 'selected';}?> value="5">Shop close today only</option>
			<option <?php if($row['cancel_reason'] == 8){echo 'selected';}?> value="8">shop close forever</option>
		</select>
		
		<?php 
		$foods_style = '';
		if($row['foods_reason'] == 0 || $row['foods_reason'] == ''){
				$foods_style=" border:1px solid red";
		}?>
		
		<select name="foods_reason" id="foods_reason" class="foods_reason foods_reason_<?php echo $row['id']; ?> form-control"  order_id='<?php echo $row['id']; ?>' <?php if($row['cancel_reason'] == 3){?> style="display:block;margin-top:10px;<?php echo $foods_style;?>"<?php }else{?>style="display:none;"<?php }?>>
			<option value="0">Select Foods Reason</option>
			<option <?php if($row['foods_reason'] == 1){echo 'selected';}?> value="1">Temporary not available, update menu</option>
			<option <?php if($row['foods_reason'] == 2){echo 'selected';}?> value="2">Today sold out</option>
			<option <?php if($row['foods_reason'] == 3){echo 'selected';}?> value="3">No more selling product, update menu</option>
		</select>
		
		
		<input type="text" class="form-control cancel_admin cancel_person_<?php echo $row['id']; ?>" name="cancel_person" id="cancel_person" value="<?php echo $row['cancel_person'];?>" order_id="<?php echo $row['id']; ?>" style="float:right;height:10%;;background-color:white!important;margin-top:10px;margin-bottom:10px" placeholder="Name">
		
		<?php //}?>
		<?php if($row['cancel_order'] == 1){?>
		<br/>
		<a style="background-color:#c13535;border-color:#c13535;" class="btn btn-danger reverse_cancel reverse_cancel_<?php echo $row['id']; ?>" order_id='<?php echo $row['id']; ?>' href="javascript:void(0);">Reverse Cancel</a>
		<?php }?>
		<!-- END Cancel order-->
		
		</td>
		
			<td>
		<div style="width:200px !important;">
		
		<?php 
		if($r['user_name']){  echo $r['user_name']."- ".$r['user_mobile']; } else { echo $r['user_mobile'];} ?>
		
		<a href="https://api.whatsapp.com/send?phone=<?php  echo $r['user_mobile_number']?>" target="_blank">
							
							<img src="images/whatapp.png" style="max-width:40px;"/></a>
							
							<a target="_blank" href="../dashboard.php?did=<?php echo $r['od_user_id'];?>">
							<!--<a target="_blank" href="../dashboard.php?did=<?php echo $r['l_user_id'];?>">-->
							<i style="font-size:30px;" class="fa fa-info"></i></a>
							<br/>
		<!--
		<b>Latitude:</b> <?php echo $r['latitude'];?><br/>
		<b>Longtitude:</b> <?php echo $r['longitude'];?><br/!-->
		<!--<input type="text" class="form-control lat_lng" name="lat_lng" selected_user_id="<?php echo $r['l_user_id']; ?>" value="<?php echo $r['lat_lng']; ?>" placeholder="latitude & longitude"/>-->
		
		<?php if($row['ipay_p_id'] != 0){?>
							<?php if($row['ipay_payment_status'] == 0){?>
								<br/>
								 <label class= "btn btn-primary status"  style="background-color:red"> <?php echo $row['ipay_message']; ?></label>
								 <br/>Transaction Id: 
								 <?php echo $row['pay_transid']; ?>
							<?php }?>
							<?php if($row['ipay_payment_status'] == 1){?>
								<br/>
								 <label class= "btn btn-primary status"  style="background-color:green"> Success<?php //echo $row['ipay_message']; ?></label>
								 <br/>Transaction Id: 
								 <?php echo $row['pay_transid']; ?>
								 </br>
							<?php }?>
						<?php }?>
						
				<a class="" target="_blank" href="http://maps.google.com/maps?q=<?php echo  $row['location']; ?>">  <?php echo $row['location']; ?></a>
				<?php 
				$created_on = $row['created_on'];
				$created_on1 = new DateTime($created_on);
				$now21 = new DateTime(Date('Y-m-d H:i:s'));
				$interval_21 = $created_on1->diff($now21);
				$hours_21 = $interval_21->h;
				$minutes_21 = $interval_21->i;
				$cls_txt = '';
				$cls_cnfrm =  '';
				
				if($minutes_21 >=5){
					if($row['cancel_order'] != 1){
						if($row['admin_code'] == ''){
							$cls_txt = "blink_info_button";
						}
						if($row['admin_confirmed_by'] == ''){
							$cls_cnfrm = "blink_info_button";
						}
					}
					
				}

				
				?>
		<p style="padding-bottom:10px">
			 Code: <input type="text" class="<?php echo $cls_txt;?> form-control admin_code" name="admin_code" id="admin_code" value="<?php echo $row['admin_code'];?>" order_id="<?php echo $row['id']; ?>" style="width:50%;float:right;height:10%;background-color:white!important;"/>
			 </p>
			 <p style="padding-bottom:10px">
		Commission: <input type="number" class="form-control admin_commission_price acp_<?php echo $row['id']; ?>" name="admin_commission_price" id="admin_commission_price" value="<?php echo $row['admin_commission_price'];?>" order_id="<?php echo $row['id']; ?>" <?php echo $price_readonly;?> style="width:50%;float:right;height:10%;;background-color:white!important;" />	
				
				</p>
		<p>
		Confirm By: <input type="text" class="<?php echo $cls_cnfrm;?> form-control admin_confirmed_by" name="admin_confirmed_by" id="admin_confirmed_by" value="<?php echo $row['admin_confirmed_by'];?>" order_id="<?php echo $row['id']; ?>" style="width:50%;float:right;height:10%;;background-color:white!important;" />
		</p>
			</div>
		</td>
		
	
		
		<!--
		<td style="min-width:190px;"><input type="text" selected_user_id="<?php echo $row['id']; ?>"  name="rider_info" placeholder="%" class="form-control rider_info" value="<?php echo $row['rider_info'];?>"></td>
		-->
		<td style="min-width:190px;">
		<!--
		<input type="text" selected_user_id="<?php echo $row['id']; ?>"  name="rider_info" placeholder="%" class="form-control rider_info" value="<?php echo $row['rider_info'];?>">
		-->
		<?php //if($row['cancel_order'] != 1){?>
		<!-- Start select online Riders-->
		
		<style>
		select#rider_info option {
			background: white;
		}
		select.rider_info_select{
			background:red;
			color:black;
			font-weight:bold;
			border-color:red;
			
		}
</style>

					<?php 
						$s_label1 = 'We are still desperately trying to contact the merchant,<br/> once the order is confirmed with merchant, we will inform you. Meanwhile, <br/>our rider is on his way to merchant shop checking.';
						$s_label2 = 'Rider Listings';
						$s_label3 = 'Shop closed, Cancel!';
						$s_label4 = 'Merchant is preparing your foods. Please wait. Rider is waiting';
						if($_SESSION["langfile"] == 'chinese'){
							$s_label1 = '我们正在尽最大努力联系商家以确认你的订单。我们的司机已经出发到商家地点以确认商家是否营业！';
							$s_label2 = '骑手列表';
							$s_label3 = '商家休息，订单取消！';
							$s_label4 = '商家正在准备食物，食物完成后，我们的司机就会把美食送上';
						}
						?>
						<?php 
						$op_class = '';
						$created_ons = $row['created_on'];
						$created_ons1 = new DateTime($created_ons);
						$nows21 = new DateTime(Date('Y-m-d H:i:s'));
						$intervals_21 = $created_ons1->diff($nows21);
						$minutess_21 = $intervals_21->i;
						$hr_21 = $intervals_21->h ;
						$sstyle_c = '';
						if($row['s_rider_option'] == 0 || $row['s_rider_option'] ==''){
							
							
							if($minutess_21 >= 20 || $hr_21 >=1 ){
								if($row['cancel_order'] != 1){
									$op_class = 'blink_info_button';
									$sstyle_c = 'style="color:white"';
								}
							}
						}?>
						<select name="s_rider_option" id="s_rider_option_<?php echo $row['id']; ?>" class="form-control s_rider_option <?php echo $op_class;?>" <?php echo $sstyle_c ;?>  order_id="<?php echo $row['id']; ?>">
								<option value="0">Select Option</option>
								<option <?php if($row['s_rider_option'] == '1'){ echo 'selected';}?> value="1"><?php echo $s_label1;?></option>
								<option <?php if($row['s_rider_option'] == '2'){ echo 'selected';}?> value="2"><?php echo $s_label2;?></option>
								<option <?php if($row['s_rider_option'] == '3'){ echo 'selected';}?> value="3"><?php echo $s_label3;?></option>
								<option <?php if($row['s_rider_option'] == '4'){ echo 'selected';}?> value="4"><?php echo $s_label4;?></option>
						</select>
						<br/>
						
						<?php 
						
						
				
				
						$cls_rider_blink = "";
						$select_rider = "Select Riders";
						//echo "===".$minutess_21;
						if($minutess_21 >=15){
							if($row['rider_info'] == 0){
								//echo 'in';
								$cls_rider_blink = "blink_info_button";
								$select_rider = "Call Boss Now!!!";
							}
						}	
						?>
						<select name="rider_info" id="rider_info" class="form-control rider_info  <?php if($row['rider_info'] == 0){?>rider_info_select <?php echo $cls_rider_blink;?> <?php }?>" order_id="<?php echo $row['id']; ?>">
						<option value="0" style="color:black"  ><?php echo $select_rider;?></option>
						<?php 
						$riders_query = "select * from tbl_riders where r_status = 1 ";
						/*and r_online = 1*/
						$ridersFetch = mysqli_query($conn,$riders_query);
						$riderNew_array = array();
						while($r_value = mysqli_fetch_assoc($ridersFetch)){
							$riderNew_array[$r_value['r_id']]['link'] = $r_value['r_link'];
							$riderNew_array[$r_value['r_id']]['r_live_location'] = $r_value['r_live_location'];
							if($riderJobArray[$r_value['r_id']] == 0){
								$r_css = "green";
							}else if($riderJobArray[$r_value['r_id']] != 0){
								$r_css = "red";
							}else{
								$r_css = "black";
							}
							?>
							<?php if($row['rider_info'] == $r_value['r_id']){?>
							<option selected  value="<?php echo $r_value['r_id']; ?>" style="color:<?php echo $r_css;?>" ><?php echo "Jobs:".$riderJobArray[$r_value['r_id']]." ".$r_value['r_name']."(".$r_value['r_mobile_number'].")"; ?></option>
							<?php }else{
								if($r_value['r_online'] == 1){?>
							<option   value="<?php echo $r_value['r_id']; ?>" style="color:<?php echo $r_css;?>"><?php echo "Jobs:".$riderJobArray[$r_value['r_id']]." ".$r_value['r_name']."(".$r_value['r_mobile_number'].")"; ?></option>
								<?php }}?>
						<?php }?>
						</select>
						<?php //if($row['rider_info'] == 26){
						if($row['rider_tel_number'] != ''){ 
							$b_css = "float:right;height:10%;;background-color:white!important;margin-top:10px;margin-bottom:10px;";
							 if($row['rider_tel_number'] == ''){ 
								$b_css = 'float:right;height:10%;;background-color:white!important;margin-top:10px;margin-bottom:10px;border:1px solid red';
							 }
							?>
						<input type="text" class="form-control rider_tel_number rider_tel_number_<?php echo $row['id']; ?>" name="rider_tel_number" id="rider_tel_number"  order_id="<?php echo $row['id']; ?>" style="<?php echo $b_css;?>" placeholder="Phone Number" value="<?php echo $row['rider_tel_number']; ?>">
						<?php }else{?>
						<input type="text" class="form-control rider_tel_number rider_tel_number_<?php echo $row['id']; ?>" name="rider_tel_number" id="rider_tel_number"  order_id="<?php echo $row['id']; ?>" style="float:right;height:10%;;background-color:white!important;margin-top:10px;margin-bottom:10px;display:none" placeholder="Phone Number" value="<?php echo $r['rider_tel_number']; ?>" >
						<?php }?>
						
						
						<br/>
						<select name="rider_admin_option" id="rider_admin_option_<?php echo $row['id']; ?>" class="form-control rider_admin_option"  order_id="<?php echo $row['id']; ?>">
							<option value="0">Select Instructions</option>
							<!--<option  <?php if($row['rider_admin_option'] == '1'){ echo 'selected';}?> value="1">Lepas siap order atas, baru buat ini</option>
							--><option <?php if($row['rider_admin_option'] == '2'){ echo 'selected';}?> value="2">Lepas siap semua orders, baru buat order ini</option>
							<option  <?php if($row['rider_admin_option'] == '3'){ echo 'selected';}?> value="3">Sekali jalan</option>
							<option  <?php if($row['rider_admin_option'] == '4'){ echo 'selected';}?> value="4">Lepas ambil semua makanan, Hantar ini dulu</option>
							
							<option  <?php if($row['rider_admin_option'] == '4'){ echo 'selected';}?> value="3">Speed</option>
						</select>

						<br/>
						<?php 
						//echo "==".$row['rider_info'];
						if($row['rider_info'] != '0'){?>
						<a href="<?php echo $riderNew_array[$row['rider_info']]['link'];?>" target="_blank" class="btn btn-sm btn-primary " >Rider Link</a>
						<?php if($row['rider_complete_order'] != 1){?>
						<a href="<?php echo $riderNew_array[$row['rider_info']]['r_live_location'];?>" target="_blank" class="btn btn-sm btn-primary " >Live Location</a>
						<?php }?>
						<br/>
						<?php }?>
						<?php
						//echo $row['rider_info'];
						if($row['rider_info'] != '' && $row['rider_info'] != '0'){
							$rider_od_assign_time = $row['rider_od_assign_time'];
							$assign_date = new DateTime($rider_od_assign_time);
							$now = new DateTime(Date('Y-m-d H:i:s'));
							$interval = $assign_date->diff($now);
							$hours = $interval->h;
							$minutes = $interval->i;
							
							if($minutes > 3){
								//echo $minutes."===".$row['rider_accept_id'];
								if($row['rider_accept_id'] == 0){
								?>
								<?php if($row['cancel_order'] != 1){?>
								<br/>
								<span class="btn btn-sm btn-danger <?php if($row['cancel_order'] == 0){?> blink_info_button <?php }?>">Rider not accept order yet!!</span>
								<?php }?>
								<?php	
								}
							}
						} 
						
						if($row['rider_info'] != '' && $row['rider_info'] != '0'){
							$rider_od_accept_time = $row['rider_od_accept_time'];
							$accept_time = new DateTime($rider_od_accept_time);
							$now2 = new DateTime(Date('Y-m-d H:i:s'));
							$interval_2 = $accept_time->diff($now2);
							$hours_2 = $interval_2->h;
							$minutes_2 = $interval_2->i;
							
							//echo $minutes_2;
							if($minutes_2 > 20){
								//echo $minutes."===".$row['rider_accept_id'];
								if($row['rider_accept_id'] != 0 && $row['rider_arrive_shop'] == '0000-00-00 00:00:00'){
								?>
								<?php if($row['cancel_order'] != 1){?>
								<br/>
								<span class="btn btn-sm btn-danger <?php if($row['cancel_order'] == 0){?> blink_info_button <?php }?>">Rider not reached shop yet!!</span>
								<?php }?>
								<?php	
								}
							}
						} 
						//echo $row['rider_complete_order']."-----".$row['rider_arrive_shop'];
						?>
						
						<!-- END select online Riders-->
						<p style="font-size:20px"><b>Invoice No:</b> # <?php echo $row['invoice_no']; ?>
						<br/>
						<b><?php echo $r['merchant_name']; ?></b>
						</p>
						<?php
							$speed_man = '';
							$chinese_man = '';
							if($row['special_delivery_amount']!= '0'){
								$chinese_man = 'Chiness Delivery';
							?>
							<label class="btn-sm btn-yellow <?php echo $cls_del;?>" style="background-color:red;border-color:red;margin-top:10px;width:150px;color:white">
								<?php echo $chinese_man;?>
							</label>
							<?php }?>
							<?php if($row['speed_delivery_amount']!= '0'){
								$speed_man = 'Speed Delivery';
							?>
							<label class="btn-sm btn-yellow <?php echo $cls_del;?>" style="background-color:red;border-color:red;margin-top:10px;width:150px;color:white">
								<?php echo $speed_man;?>
							</label>
							<?php }?>
							
	 <?php //}?>
		</td>
       <td>
	   <?php 
	   
		$sstper=$row['sst_rate'];
		if($sstper>0){
			$incsst = ($sstper / 100) * $total;
			$incsst=@number_format($incsst, 2);
			$incsst=@number_format($incsst, 2);
			$incsst=ceiling($incsst,0.05);
			$g_total=@number_format($total+$incsst, 2);
		} else { 
			$g_total=$total;
		}
		
		$territory_price_array = explode("|",$row['territory_price']);
		$terr_id = $territory_price_array[0];
		$territory_price = $territory_price_array[1];
								
								
								
		$collect_price =@number_format(($g_total+$row['order_extra_charge']+$row['deliver_tax_amount']+$row['special_delivery_amount']+$row['speed_delivery_amount']+$row['donation_amount']+$territory_price)-($row['wallet_paid_amount']+$row['membership_discount']+$row['coupon_discount']), 2);
	
		$admin_cash_price = '';
	    if($row['admin_cash_price'] == ''){
			if($row['wallet'] == 'cash'){
				$admin_cash_price = $collect_price;
				$run_cash = mysqli_query($conn,"update order_list Set admin_cash_price = '".$collect_price."' where id = ".$row['id']);
			}
		}else{
			$admin_cash_price = $row['admin_cash_price'];
		}
	   //echo $row['admin_cash_price'];
	   $price_readonly = '';
	   if($row['rider_complete_order'] == 1){
		   $price_readonly = 'readonly="readonly"';
	   }
	   if($_GET['role'] && $_GET['role'] == 'admin'){
		   $price_readonly = '';
	   }
	   ?>
	   <p style="padding-bottom:10px;">
		Bank price: <input type="text" class="form-control admin_bank_price" name="admin_bank_price" id="admin_bank_price" value="<?php echo $row['admin_bank_price'];?>" order_id="<?php echo $row['id']; ?>" <?php echo $price_readonly;?> style="width:71px;float:right;height:10%;;background-color:white!important;" />
		</p>
		<p style="padding-bottom:10px;">
		Cash price: <input type="text" class="form-control admin_cash_price" name="admin_cash_price" id="admin_cash_price" value="<?php echo $admin_cash_price;?>" order_id="<?php echo $row['id']; ?>" <?php echo $price_readonly;?>  style="width:71px;float:right;height:10%;;background-color:white!important;"/>
		</p>
		
		<?php 
		//echo "==".$row['order_cancel'];
		if($row['cancel_order'] == 1){
		}else{
			if($row['rider_complete_order'] != 1){
						if($row['rider_arrive_shop'] != '0000-00-00 00:00:00'){
						?><br/>
							<span class="btn btn-sm btn-danger" style="background-color:green;border-color:green">司机到店，可以准备下张单</span>
						<?php }
		}
		}?>
		<br/>
		<p style="font-size:16px;padding-top:10px"><b><span class="btn btn-sm btn-danger" style="background-color:lightgreen;border-color:lightgreen;font-weight: bold;
    text-transform: uppercase;color:black"><?php echo $row['wallet'];?></span></b></p>
	
	<p>
		
		 <textarea type="text" class="form-control food_receipt_seen <?php if(($interval_complete->h >= 1 || $interval_complete->i >= 45) && $row['food_receipt_seen'] == '' ){  if($row['cancel_order'] == 0 ){ echo 'blink_info_button';} }?> " name="food_receipt_seen" id="food_receipt_seen<?php echo $row['id']; ?>" order_id='<?php echo $row['id']; ?>' placeholder="Admin investigation : 20% & late 1 hour delivery" style="float:right;height:100px;min-width:150px;background-color:white!important;" maxlength="200"><?php if($row['food_receipt_seen'] != ''){echo trim($row['food_receipt_seen']);}?></textarea>
		<div id="countlimit<?php echo $row['id']; ?>"></div>
		
	</p>
		
	   </td>
	   
	    <td style="min-width:190px;">
		<p style="padding-bottom:10px;">
	   Bank price: <input type="text" class="form-control rider_bank_amount" name="rider_bank_amount" id="rider_bank_amount" value="<?php echo $row['rider_bank_amount'];?>" order_id="<?php echo $row['id']; ?>" <?php echo $price_readonly;?>  style="width:71px;float:right;height:10%;;background-color:white!important;" />
		</p>
		<p style="padding-bottom:10px;">
		Cash price: <input type="text" class="form-control rider_cash_amount" name="rider_cash_amount" id="rider_cash_amount" value="<?php echo $row['rider_cash_amount'];?>" order_id="<?php echo $row['id']; ?>" <?php echo $price_readonly;?> style="width:71px;float:right;height:10%;;background-color:white!important;"/>
		</p>
		<p style="padding-bottom:10px;">
		
		Merchant Remark: 
		<textarea style="min-width: 120px;"  selected_user_id="<?php echo $row['merchant_id']; ?>" class="form-control merchant_remark" rows="2" name="merchant_remark" placeholder="Merchant Remark"><?php if(isset($r['merchant_remark'])){ echo $r['merchant_remark']; }?></textarea>
		</p>
		
		<p>
		
			<?php if($row['merchant_remark_image'] != '' ){?>
				<label class="btn-sm btn-yellow" style="background-color:darkgreen;width:155px">
					<a class="fancybox" rel="" href="<?php echo $site_url.'/upload/merchant_remark/'.$row['merchant_remark_image'];?>" style="color:white" >
					商家 Touch &go </a>
					<a href="javascript:void(0)" class="delete_meremark" selected_user_id="<?php echo $row['merchant_id']; ?>" orderid="<?php echo $row['id']; ?>" style="color:#000"><i class="fa fa-trash" style="margin-left:20px;color:white"> </i></a>
				</label>
			<?php }else{?>
					<form method="post" id="remark-form_<?php echo $row['id']; ?>" class="remark-form" orderid='<?php echo $row['id']; ?>' selected_user_id="<?php echo $row['merchant_id']; ?>" enctype="multipart/form-data" onSubmit="return false;" style="min-height:0px !important;width:203px">
						<div class="input-group mt-3 mb-3 input-has-value flex-wrap">
							<input type="file" name="file_remark" class="file_remark" style="visibility: hidden;position: absolute;">
							<input type="text" class="form-control remark_proof" disabled="" placeholder="Touch & Go" id="file_remark" style="width:85px">
							<button type="button" class="browse_remark btn btn-primary rounded-0" style="width: 53px;padding-left: 0px;padding-right: 0px;">Browse</button>
							&nbsp;
							<input type="submit" name="submit" value="Submit" class="btn btn-danger btn_remark_upload rounded-0" style="width: 53px;padding-left: 0px;padding-right: 0px;">
						</div>
					</form>
			<?php }?>
		</p>
		
		<p style="padding-bottom:10px;">
		
		User Remark: 
		<textarea style="min-width: 120px;"  od_user_id="<?php echo $r['od_user_id']; ?>" class="form-control user_remark" rows="2" name="user_remark" placeholder="User Remark"><?php if(isset($r['user_remark'])){ echo $r['user_remark']; }?></textarea>
		</p>
		
		
		
	   </td>
	   
	   
        	<!--<td class="writeup_set" id="writeup_set_<?php  echo $row['id'];?>" order_id='<?php echo $row['id']; ?>'><i class="fa fa-copy" style="font-size:25px;margin-left: 10%;"></i></td>-->
		
		
		<td><?php echo $r['merchant_name']; ?>
		
		<br/>
		
		<?php 
		$english_word = str_replace("Our food delivery hours are from","",$r['working_text']);
		
		//if($_GET['s']){
			
			if($r['shop_open']){
			$today_day=strtolower(date('l'));
		    $sql1="SELECT  * FROM `timings` WHERE day='$today_day' and `merchant_id` =".$r['merchant_id'];
			//echo $sql1;
			$result1 = mysqli_query($conn,$sql1);
			
			while($ti=mysqli_fetch_assoc($result1))
				{ 
					//echo '1';
					$time_detail['day']=$ti['day'];
					$time_detail['starttime']=$ti['start_time'];
					$time_detail['endttime']=$ti['end_time'];
					//echo '1';
					$tworking=checktimestatus($time_detail);  
					//exit;
				}
				//echo count($tworking);
				if(count($tworking)>0)
				{
					foreach($tworking as $w)
					{  
						if($w=="y")
						{
							$working="y";
							break;
						}
						else
						{
							//echo '1';
							$working="n";
						}
					}
				}
				else
				{
					//echo '2';
					$working="n";
				}
				$shopclose=[];
		}  
		
		
		//}
			//echo "==".$working;					
		?>
		<?php if($working == 'n'){?>
		<p style="color:red;">请改正如果错误，</p>
		<p style="color:red;background:white !important;padding:5px" class="blink_info_button">外送时间是: <?php echo $english_word." ".$r['not_working_text'];?></p>
		<?php }else{?>
		<p >请改正如果错误，</p>
		<p >外送时间是: <?php echo $english_word." ".$r['not_working_text'];?></p>
		
		<?php }?>
		</td>
		<td>
		<textarea style="min-width: 120px;"  selected_user_id="<?php echo $row['merchant_id']; ?>" class="form-control google_map" rows="5" name="google_map"><?php if(isset($r['google_map'])){ echo $r['google_map']; }?></textarea>
		
		
		  						</td> 
		<td>
		<textarea  style="min-width: 190px;width:190px;height:63px;" selected_user_id="<?php echo $row['merchant_id']; ?>" class="form-control foodpanda_link" rows="5" name="foodpanda_link"><?php if(isset($r['foodpanda_link'])){ echo $r['foodpanda_link']; }?></textarea>
		<br/>
	<?php if($r['foodpanda_link'] != ''){?>
		<a href="<?php echo $r['foodpanda_link']; ?>" class="btn-sm btn-yellow" target="_blank" style="background-color:green;border-color:green;margin-top:10px;width:150px;color:white">
			FP Link
		</a>
	<?php }?>
	
		</td>   
		<td>
		<?php if($r['whatsapp_link'] != ''){?>
		<a href="<?php echo $r['whatsapp_link']; ?>" target="_blank">
				<img src="images/Whats.png" >
				</a>
		<?php }?>
		</td>   
        <td><?php echo $r['merchant_mobile_number']; ?></td>
     



      </tr>
	 <?php $i++;} ?>
      
    </tbody>
  </table>
  
 
  <nav aria-label="Page navigation example" style="text-align:center">
	<ul class="pagination">
		<?php for ($x = 1; $x <= 5; $x++) {
			$clas_active = "";
			if($pageno == $x){
				$clas_active = "active";
			}
			?>	
			<li class="page-item <?php echo $clas_active;?>"><a class="page-link " href="showorder.php?sid=<?php echo $sid;?>&ms=<?php echo md5(rand());?>&pageno=<?php echo $x;?>"><?php echo $x;?></a></li>
		<?php }?>
	</ul>
	</nav>


</div>
<div class="modal fade" id="orderdetailmodel" role="dialog" style="margin-top:12%;"> 
							<div class="modal-dialog">
							<!-- Modal content-->		
							<div class="modal-content" style="min-height:550px;">	
							<div class="modal-header">	
							<button type="button" class="close" data-dismiss="modal">&times;</button>						
							<h4 class="modal-title">Order Detail</h4>	
							</div>					
							<form id ="orderdetailform">		
							<div class="modal-body" style="padding-bottom:0px;">
							<div class="col-sm-10" id="orderdata">		  				
											
							</div>						
												
							</form>						
							</div>						
							</div>						
							</div>
						</div>
						
												
<!-- info merchnt modal-->
<div id="myModal_infomerchnt" class="modal fade" role="dialog" style="margin-top:12%;">
    <div class="modal-dialog" style="width:46%">
        <div class="modal-content" style="width:50%" >
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
				<h5 class="modal-title">Info Merchant</h5>
            </div>
            <div class="modal-body">
				
				<p> Invoice No: #<span class="invoice_no"></span></p>
				<br/>
				<p> order No: #<span class="order_no"></span></p>
				<br/>
				<form name="infomerchnt_form" id="infomerchnt_form" class="form-inline" >
					<div class="form-group mx-sm-3 mb-2" style="margin-bottom:10px">
						<select name="inform_mecnt_status" id="inform_mecnt_status" class="form-control" style="width:70%">
							<!--<option value="0">Select Status</option>
							<option value="1">Inform already</option>
							<option value="2">Cannot reach  merchant, now inform customer rider is otw checking</option>
							<option value="3">Rider buy himself</option>
							<option value="4">Order Cancelled</option>-->
							
							
							<option value="0">Select Status</option>
							<option value="1">Fp Order nama Koo Family</option>
							<option value="2">Sudah call guna 5670</option>
							<option value="7">Sudah group order, guna Koo Family</option>
							<option value="3">FP & beli sendiri juga</option>
							<option value="4">FP & sudah Call guna 5670</option>
							<option value="5">Semua beli sendiri</option>
							<option value="6">Order Cancelled</option>
							
							
							
							

						</select>
						
						<input type="number" name="informtime_complete" id="informtime_complete" class="form-control informtime_complete" placeholder="Time To Complete" value="" style="display:none;margin-top:10px;" />
						
						<input type="textbox" name="informpop_name" id="informpop_name" class="form-control informpop_name" placeholder="How Much??" value="" style="display:none;margin-top:10px" />
					</div>
					
					<br/>
					<div class="form-group mx-sm-3 mb-2">
						<!--<input type="text" class="form-control" name="code_admin_code" id="code_admin_code" placeholder="订单状况和 Admin name" style="width:70%">-->
						<input type="hidden" class="" name="admin_order_id" id="admin_order_id" value=""/>
					</div>
					<button type="button" class="btn btn-primary mb-2 submit_admin_popup">Submit</button>
					<br/>
					<img src="img/ajax-loader.gif" class="ajx_lang_resp" style="display:none;padding-top:10px"/>
					&nbsp;
					<span class="please_wait_text" style="display:none;color:red">Please wait ....</span>
				</form>
            </div>
			<div class="modal-footer">
		
			</div>
        </div>
    </div>
</div>


<!-- END-->	


<!-- rideradmin modal-->
<div id="myModal_rideradmin" class="modal fade" role="dialog" style="margin-top:12%;">
    <div class="modal-dialog modal-sm" style="">
        <div class="modal-content" style="" >
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
				<h5 class="modal-title"><b>请确保司机分享8个小时Live location</b></h5>
            </div>
            <div class="modal-body">
				
				<p> <b>Rider Name:</b> <span class="rider_pop_nmae"></span></p>
					<div class="form-group mx-sm-3 mb-2" style="margin-bottom:10px">
					<b>Admin Name:</b> <input type="textbox" name="button_click_admin" id="button_click_admin" class="form-control button_click_admin" placeholder="Admin Name" value="" style=";margin-top:10px;" />

					</div>
					
					<div class="form-group mx-sm-3 mb-2">
						<input type="hidden" class="" name="admin_rider_id" id="admin_rider_id" value=""/>
					</div>
					<button type="button" class="btn btn-primary mb-2 submit_admin_riders">Submit</button>
					<img src="img/ajax-loader.gif" class="ajx_lang_resp" style="display:none;padding-top:10px"/>
					&nbsp;
					<span class="please_wait_text" style="display:none;color:red">Please wait ....</span>
            </div>
			<div class="modal-footer">
		
			</div>
        </div>
    </div>
</div>

<!--END-->


<!-- Offline rider popup-->

<!-- rideradmin modal-->
<div id="myModal_offline_rideradmin" class="modal fade" role="dialog" style="margin-top:12%;">
    <div class="modal-dialog modal-sm" style="">
        <div class="modal-content" style="" >
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
				<h5 class="modal-title"><b>Offline Rider:</b> <span class="off_rider_pop_nmae"></span></h5>
            </div>
            <div class="modal-body">
				<div class="form-group mx-sm-3 mb-2" style="margin-bottom:10px">
					<b>Admin Name:</b> <input type="text" name="button_click_off_admin" id="button_click_off_admin" class="form-control button_click_off_admin" placeholder="Admin Name" value="" style=";margin-top:10px;" />
				</div>
				<div class="form-group mx-sm-3 mb-2" style="margin-bottom:10px">
					<b>Why?</b> 
					<textarea rows="5" name="button_click_admin_why" id="button_click_admin_why" class="form-control button_click_admin_why" placeholder="why?" value="" style=";margin-top:10px;"></textarea>
				</div>
				
				<div class="form-group mx-sm-3 mb-2" style="margin-bottom:10px">
					<b>What Time?</b> <input type="text" name="button_click_admin_time" id="button_click_admin_time" class="form-control button_click_admin_time" placeholder="What Time?" value="" style=";margin-top:10px;" />
				</div>
				
				
				<div class="form-group mx-sm-3 mb-2">
					<input type="hidden" class="" name="admin_off_rider_id" id="admin_off_rider_id" value=""/>
				</div>
				<button type="button" class="btn btn-primary mb-2 submit_admin_off_riders">Submit</button>
				<img src="img/ajax-loader.gif" class="ajx_lang_resp" style="display:none;padding-top:10px"/>
				&nbsp;
				<span class="please_wait_text" style="display:none;color:red">Please wait ....</span>
            </div>
			<div class="modal-footer">
		
			</div>
        </div>
    </div>
</div>

<!--END-->
<!-- END-->
<script>



$(document).ready(function(){
	$(".offline_riders").click(function(){
		var riderId = $(this).attr('riderId');
		var ridername = $(this).attr('ridername');
		var offline_admin_name = $(this).attr('offline_admin_name');
		var offline_reason = $(this).attr('offline_reason');
		var offline_time = $(this).attr('offline_time');
		
		$("#button_click_off_admin").val(offline_admin_name);
		$("#button_click_admin_why").val(offline_reason);
		$("#button_click_admin_time").val(offline_time);
		
		$("#myModal_offline_rideradmin").modal('show');
		$(".off_rider_pop_nmae").html(ridername);
		$("#admin_off_rider_id").val(riderId);
	});
	
	$(".submit_admin_off_riders").click(function(){
		var admin_off_rider_id = $("#admin_off_rider_id").val();
		var button_click_off_admin = $("#button_click_off_admin").val();
		var button_click_admin_why = $("#button_click_admin_why").val();
		var button_click_admin_time = $("#button_click_admin_time").val();
		
			$("#button_click_off_admin").css('border','');
			$("#button_click_admin_why").css('border','');
			$("#button_click_admin_time").css('border','');
			
		if(button_click_off_admin == ''){
			$("#button_click_off_admin").css('border','1px solid red');
			$("#button_click_off_admin").focus();
			return false;
		}else if(button_click_admin_why == ''){
			$("#button_click_admin_why").css('border','1px solid red');
			$("#button_click_admin_why").focus();
			return false;
		}else if(button_click_admin_time == ''){
			$("#button_click_admin_time").css('border','1px solid red');
			$("#button_click_admin_time").focus();
			return false;
		}else{
			var cnfrmDelete = confirm("Are you sure to submit?");
			if(cnfrmDelete==true){
				 $.ajax({
					url :'functions.php',
					type:"post",
					data:{method:"offline_rider_button_click",button_click_off_admin:button_click_off_admin,admin_off_rider_id:admin_off_rider_id,button_click_admin_why:button_click_admin_why,button_click_admin_time:button_click_admin_time},
					dataType:'json',
					success:function(result){  
							var data = JSON.parse(JSON.stringify(result));   
							if(data.status==true){location.reload(true);}
							else{alert('Failed to update');	}
						}
				});      
			}
		}

	});
		
});

/*START: */
	$(document).ready(function(){
		$(".button_click_rideradmin").click(function(){
			var riderId = $(this).attr('riderId');
			var ridername = $(this).attr('ridername');
			$("#myModal_rideradmin").modal('show');
			$(".rider_pop_nmae").html(ridername);
			$("#admin_rider_id").val(riderId);
		});
		
		
		$(".submit_admin_riders").click(function(){
			var admin_rider_id = $("#admin_rider_id").val();
			var button_click_admin = $("#button_click_admin").val();
			if(button_click_admin == ''){
				$("#button_click_admin").css('border','1px solid red');
				$("#button_click_admin").focus();
				return false;
			}else{
				var cnfrmDelete = confirm("Are you sure to verified this rider?");
				if(cnfrmDelete==true){
					 $.ajax({
						url :'functions.php',
						type:"post",
						data:{button_click_admin:button_click_admin,method:"rider_button_click",admin_rider_id:admin_rider_id},
						dataType:'json',
						success:function(result){  
							var data = JSON.parse(JSON.stringify(result));   
							if(data.status==true)
							{  
							   location.reload(true);
							}
							else
							{alert('Failed to update');	}
							
							}
				});      
				
				}
			}
			
		});
		
		
	});
	/*END*/	
	
	
function generatetokenno(length) {
   var result           = '';
   var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
   var charactersLength = characters.length;
   for ( var i = 0; i < length; i++ ) {
      result += characters.charAt(Math.floor(Math.random() * charactersLength));
   }
   return result;
}
$(document).ready(function(){
	$(".writeup_set").click(function(e){
		  var s_id = $(this).attr('order_id');
		  var input_id="writeup_set_"+s_id;
		  $.ajax({
                        type: "POST",
                        url: "writeupshow.php",
                        data: {s_id:s_id},
                        success: function(data) {
							// alert(data);
							// $(this).text(data);
							document.getElementById(input_id).innerHTML =data;
							$(".orderdeatils_"+s_id).show();
							$(".cust_"+s_id).show();
							// $('#write_up_input').val(data);
							 // var copyText = document.getElementById("write_up_input");
							  // copyText.select();
							  // copyText.setSelectionRange(0, 99999)
							  // document.execCommand("copy");
							  // alert("Copied the text: " + copyText.value);
                        },
                        error: function(result) {
                            alert('error');
                        }
                });
		  // $("#orderdetailmodel").modal("show"); 
	  });
	 $(".s_order_detail").click(function(e){
		  var s_id = $(this).attr('order_id');
		  var total_bill = $(this).attr('total_bill');
		  $.ajax({
                        type: "POST",
                        url: "singleorder.php",
                        data: {s_id:s_id,total_bill:total_bill},
                        success: function(data) {
							$('#orderdata').html(data);
                        },
                        error: function(result) {
                            alert('error');
                        }
                });
		  $("#orderdetailmodel").modal("show"); 
	  });
	   $(".lat_lng").focusout(function(e){
		var selected_user_id= $(this).attr('selected_user_id');
		// var selected_user_id= $(this).attr('selected_user_id');
		var lat_lng=this.value; 
		// alert(name);
		if(selected_user_id)
		{  
		  $.ajax({
						url :'functions.php',
						 type:"post",
						 data:{lat_lng:lat_lng,method:"adminprofilesave",selected_user_id:selected_user_id},     
						 dataType:'json',
						 success:function(result){  
							var data = JSON.parse(JSON.stringify(result));   
							if(data.status==true)
							{  
							   // location.reload(true);
								
							}
							else
							{alert('Failed to update');	}
							
							}
				});      
		}
		});
		$(".foodpanda_link").focusout(function(e){
		var selected_user_id= $(this).attr('selected_user_id');
		// var selected_user_id= $(this).attr('selected_user_id');
		var foodpanda_link=this.value; 
		// alert(name);
		if(selected_user_id)
		{  
		  $.ajax({
						url :'../functions.php',
						 type:"post",
						 data:{foodpanda_link:foodpanda_link,method:"adminprofilesave",selected_user_id:selected_user_id},     
						 dataType:'json',
						 success:function(result){  
							var data = JSON.parse(JSON.stringify(result));   
							if(data.status==true)
							{  
							   // location.reload(true);
								
							}
							else
							{alert('Failed to update');	}
							
							}
				});      
		}
		});  
		$(".google_map").focusout(function(e){
		var selected_user_id= $(this).attr('selected_user_id');
		// var selected_user_id= $(this).attr('selected_user_id');
		var google_map=this.value; 
		// alert(name);
		if(selected_user_id)
		{  
		  $.ajax({
						url :'../functions.php',
						 type:"post",
						 data:{google_map:google_map,method:"adminprofilesave",selected_user_id:selected_user_id},     
						 dataType:'json',
						 success:function(result){  
							var data = JSON.parse(JSON.stringify(result));   
							if(data.status==true)
							{  
							   // location.reload(true);
								
							}
							else
							{alert('Failed to update');	}
							
							}
				});      
		}
		});
		
		
		$(".user_remark").focusout(function(e){
			var selected_user_id= $(this).attr('od_user_id');
			var user_remark=this.value; 
			// alert(name);
			
			if(selected_user_id)
			{  
			  $.ajax({
					 url :'functions.php',
					 type:"post",
					 data:{user_remark:user_remark,method:"user_remarksave",selected_user_id:selected_user_id},     
					 dataType:'json',
					 success:function(result){  
						var data = JSON.parse(JSON.stringify(result));   
						if(data.status==true)
						{  
						   // location.reload(true);
							
						}
						else
						{alert('Failed to update');	}
						
						}
					});      
			}
		});
		
		$(".merchant_remark").focusout(function(e){
		var selected_user_id= $(this).attr('selected_user_id');
		var merchant_remark=this.value; 
		// alert(name);
		if(selected_user_id)
		{  
		  $.ajax({
				 url :'functions.php',
				 type:"post",
				 data:{merchant_remark:merchant_remark,method:"merchnt_remarksave",selected_user_id:selected_user_id},     
				 dataType:'json',
				 success:function(result){  
					var data = JSON.parse(JSON.stringify(result));   
					if(data.status==true)
					{  
					   // location.reload(true);
						
					}
					else
					{alert('Failed to update');	}
					
					}
				});      
		}
		});
		
		
		
	  /* $(".rider_info").focusout(function(e){
		var selected_user_id= $(this).attr('selected_user_id');
		var rider_info=this.value;
		if(rider_info!='' && selected_user_id)
		{  
		  $.ajax({
						url :'functions.php',
						 type:"post",
						 data:{rider_info:rider_info,method:"riderdetailsave",order_id:selected_user_id},     
						 dataType:'json',  
						 success:function(result){  
							var data = JSON.parse(JSON.stringify(result));   
							if(data.status==true)
							{
							  // location.reload(true);
								
							}
							else
							{alert('Failed to update');	}
							
							}
				});      
		} 
	});
	  
	  */	


/*rider javacript*/
		//
		$(".rider_tel_number").focusout(function(e){
			var order_id= $(this).attr('order_id');
			var tel_phone = $(this).val();
			
			$.ajax({
				 url :'functions.php',
				 type:"post",
				 data:{tel_phone:tel_phone,method:"riderstel_phone",order_id:order_id},     
				 dataType:'json',
				 success:function(result){ 
						location.reload(true);
				 }
			});
						
			
		})

	 	$(".rider_info").change(function(e){
		var order_id= $(this).attr('order_id');
		var s_rider_option = $("#s_rider_option_"+order_id).val();
		
		var rider_text=this.value;
		
		
		if(rider_text == 26){
			$(".rider_tel_number_"+order_id).show();
			$(".rider_tel_number").css('border','1px solid red');
			
			if($(".rider_tel_number_"+order_id).val() == ''){
				$(this).val(0);
				$(".rider_info").css('border','1px solid red');
				return false;
			}
		}
		
		
		var admin_commission_price = $(".acp_"+order_id).val();
		
		if(admin_commission_price == ''){
			$(".rider_info").val(0);
			alert('请确认商家有食物后，确认商家和顾客地址 code, 佣金 commission后，才派单给司机!!');
			return false;
		}
		
		$(".rider_tel_number_"+order_id).hide();
		$(".rider_tel_number").css('border','');
		

		if(rider_text!='' && order_id)
		{
			if(rider_text == 0){
				var cnfrmDelete = confirm("Are You Sure Withdraw Current Riders ?");
			}else{
				var cnfrmDelete = confirm("Are You Sure Assign This Riders ?");
				
			}
			if(cnfrmDelete==true){
				  
				  $.ajax({
							url :'functions.php',
							 type:"post",
							 data:{rider_info:rider_text,method:"riderdetailsave",order_id:order_id},     
							 dataType:'json',
							 success:function(result){  
								var data = JSON.parse(JSON.stringify(result));   
								if(data.status==true)
								{
									if(s_rider_option == 0){
										$("#s_rider_option_"+order_id).val('2');
										//console.log('here');
										$(".s_rider_option").trigger('change');
									}
									location.reload(); 
								}
								else
								{alert('Failed to update');	}
								
								}
						});      
				} else{
					//$(this).val('');
					location.reload(); 
				}
		}
		
		
	});
	

	$(".s_rider_option").change(function(){
		var order_id= $(this).attr('order_id');
		var rider_option_text=this.value;
		if(rider_option_text!='')
		{
			$.ajax({
				url :'functions.php',
				type:"post",
				data:{rider_option_text:rider_option_text,method:"ridersoptionsave",order_id:order_id},     
				dataType:'json',
				success:function(result){  
				var data = JSON.parse(JSON.stringify(result));   
				if(data.status==true)
				{
				}
				else
				{alert('Failed to update');	}
				
				}
			}); 
						
		}
	});
	
	
	  
	$(".rider_admin_option").change(function(){
		var order_id= $(this).attr('order_id');
		var rider_option_text=this.value;
		if(rider_option_text!='')
		{
			$.ajax({
				url :'functions.php',
				type:"post",
				data:{rider_option_text:rider_option_text,method:"rider_admin_option",order_id:order_id},     
				dataType:'json',
				success:function(result){  
				var data = JSON.parse(JSON.stringify(result));   
				if(data.status==true)
				{
				}
				else
				{alert('Failed to update');	}
				
				}
			}); 
						
		}
	});
	  
	  
/*End riders*/
	setInterval(function(){ 
		var s_token=generatetokenno(16);
		var r_url="https://www.koofamilies.com/showorder.php?ms="+s_token;
		window.location.replace(r_url);
		}, 
			90000);      
	});


/* Info Merchnt*/
$(document).ready(function(){
	$(".info_merchant_shop").click(function(){
		var ordeid = $(this).attr('order_id');
		var admin_name = $(this).attr('admin_name');
		var invoice_no = $(this).attr('invoice_no');
		var inform_mecnt_status = $(this).attr('inform_mecnt_status');
		$(".invoice_no").html(invoice_no);
		$(".order_no").html(ordeid);
		
		console.log("+++"+inform_mecnt_status);
		$("#code_admin_code").val(admin_name);
		$("#admin_order_id").val(ordeid);
		$("#inform_mecnt_status").val(inform_mecnt_status);
		//$("#myModal_infomerchnt").modal('show');
		var cnfrmDelete = confirm("商家确认开店和有食物?");
		
		
		
		if(cnfrmDelete==true){
			$.ajax({
			url:'functions.php',
			method:'POST',
			data:{data:'infomerchantstepone',ordeid:ordeid},
			success:function(res){
				//console.log(res);
				location.reload(true);
				//var red_url = window.location.href+"&order_id="+order_id;
				//window.location.href = red_url;
				//show$(location).attr('href', 'http://stackoverflow.com')

				/*$(".accept_order").addClass('disabled');
				$(".accept_order").html('Accepted');
				$(".disabled").removeClass('accept_order');*/
				
			}
		});	
		}
	});
	
	$(".info_merchant_food").click(function(){
		var ordeid = $(this).attr('order_id');
		var admin_name = $(this).attr('admin_name');
		var invoice_no = $(this).attr('invoice_no');
		var inform_mecnt_status = $(this).attr('inform_mecnt_status');
		$(".invoice_no").html(invoice_no);
		$(".order_no").html(ordeid);
		
		console.log("+++"+inform_mecnt_status);
		$("#code_admin_code").val(admin_name);
		$("#admin_order_id").val(ordeid);
		$("#inform_mecnt_status").val(inform_mecnt_status);
		//$("#myModal_infomerchnt").modal('show');
		var cnfrmDelete = confirm("司机30分钟内到达商店？");
		if(cnfrmDelete==true){
			$.ajax({
			url:'functions.php',
			method:'POST',
			data:{data:'infomerchantsteptwo',ordeid:ordeid},
			success:function(res){
				//console.log(res);
				$("#myModal_infomerchnt").modal('show');
		
				//location.reload(true);
				//var red_url = window.location.href+"&order_id="+order_id;
				//window.location.href = red_url;
				//show$(location).attr('href', 'http://stackoverflow.com')

				/*$(".accept_order").addClass('disabled');
				$(".accept_order").html('Accepted');
				$(".disabled").removeClass('accept_order');*/
				
			}
		});	
		}
	});
	
	$(".info_merchant").click(function(){
		var ordeid = $(this).attr('order_id');
		var admin_name = $(this).attr('admin_name');
		var invoice_no = $(this).attr('invoice_no');
		var inform_mecnt_status = $(this).attr('inform_mecnt_status');
		var informpop_name = $(this).attr('informpop_name');
		var informtime_complete = $(this).attr('informtime_complete');
		$(".invoice_no").html(invoice_no);
		$(".order_no").html(ordeid);
		
		console.log("+++"+inform_mecnt_status);
		$("#code_admin_code").val(admin_name);
		$("#admin_order_id").val(ordeid);
		$("#inform_mecnt_status").val(inform_mecnt_status);
		
		$("#inform_mecnt_status").val(inform_mecnt_status);
		
		if(inform_mecnt_status == 2 || inform_mecnt_status == 7 || inform_mecnt_status == 4){
			$("#informpop_name").show();
			$("#informpop_name").val(informpop_name);
			
			$("#informtime_complete").show();
			$("#informtime_complete").val(informtime_complete);
			
		}
		
		
		$("#myModal_infomerchnt").modal('show');
		
	});
	
	
	
	/*$(".info_merchant").click(function(){
		var ordeid = $(this).attr('order_id');
		var admin_name = $(this).attr('admin_name');
		var invoice_no = $(this).attr('invoice_no');
		var inform_mecnt_status = $(this).attr('inform_mecnt_status');
		$(".invoice_no").html(invoice_no);
		$(".order_no").html(ordeid);
		
		console.log("+++"+inform_mecnt_status);
		$("#code_admin_code").val(admin_name);
		$("#admin_order_id").val(ordeid);
		$("#inform_mecnt_status").val(inform_mecnt_status);
		$("#myModal_infomerchnt").modal('show');
	});
	
	*/
	$(".submit_admin_popup").click(function(){
		var ordeid = $("#admin_order_id").val();
		var admin_code = '';//$("#code_admin_code").val();
		
		var inform_mecnt_status = $("#inform_mecnt_status").val();
		var informpop_name = '';
		var informtime_complete = '';
		
		if(inform_mecnt_status == 2 || inform_mecnt_status == 7 || inform_mecnt_status == 4){
			var informpop_name = $("#informpop_name").val();
			var informtime_complete = $("#informtime_complete").val();
			
			$("#informpop_name").css('border','');
			$("#informtime_complete").css('border','');
			
			if(informpop_name == ''){
				$("#informpop_name").focus();
				$("#informpop_name").css('border','2px solid red');
				return false;
			}else if(informtime_complete == ''){
				$("#informtime_complete").focus();
				$("#informtime_complete").css('border','2px solid red');
				return false;
			}
		}
		//console.log("+++"+admin_code);
		$("#inform_mecnt_status").css('border','');
		
		/*if(inform_mecnt_status == '0'){
			$("#inform_mecnt_status").focus();
			$("#inform_mecnt_status").css('border','1px solid red');
			return false;
		}else{*/
			
			var cnfrm = confirm("Are You Sure Inform the Merchant?");
			if(cnfrm==true){
				
				$(".ajx_lang_resp").show();
				$(".please_wait_text").show();
				
				$.ajax({
					url:'functions.php',
					method:'POST',
					data:{data:'infomerchnt',admin_code:admin_code,ordeid:ordeid,inform_mecnt_status:inform_mecnt_status,informpop_name:informpop_name,informtime_complete:informtime_complete},
					success:function(res){
						//console.log(res);
						location.reload(true);
						$(".ajx_lang_resp").hide();
						$(".please_wait_text").hide();
					}
				});	
			}
			
		//}
	});
	
	$("#inform_mecnt_status").change(function(){
		var inform_mecnt_status = $(this).val();
		$("#informpop_name").hide();
		$("#informtime_complete").hide();
		
		//console.log('new'+inform_mecnt_status);
		//2 -> Sudah call guna 5670, 7 -> Sudah group order, guna Koo Family, 4 ->FP & sudah Call guna 5670
		if(inform_mecnt_status == 2 || inform_mecnt_status == 7 || inform_mecnt_status == 4){
			$("#informpop_name").show();
			$("#informtime_complete").show();
		}


	});
});
/*END*/


</script>
<link rel="stylesheet" href="css/fancybox.css" type="text/css" media="screen" />
<script type="text/javascript" src="js/fancybox.js"></script>

<script>
$(document).ready(function() {
	$(".fancybox").fancybox({
		openEffect	: 'none',
		closeEffect	: 'none'
	});
}); 

jQuery(document).on("click", ".browse", function() {
		  var file = $(this)
			.parent()
			.parent()
			.parent()
			.find(".file");
		  file.trigger("click");
		});
		
$('input[type="file"]').change(function(e) {
	  var fileName = e.target.files[0].name;
	  $(".payment_proof").val(fileName);

	  /*var reader = new FileReader();
	  reader.onload = function(e) {
		// get loaded data and render thumbnail.
		document.getElementById("preview").src = e.target.result;
	  };
	  // read the image file as a data URL.
	  reader.readAsDataURL(this.files[0]);*/
});


/* START: Merchant remark image*/
jQuery(document).on("click", ".browse_remark", function() {
		  var file = $(this)
			.parent()
			.parent()
			.parent()
			.find(".file_remark");
		  file.trigger("click");
		});
		
$('.file_remark').change(function(e) {
	  var fileName = e.target.files[0].name;
	  $(".remark_proof").val(fileName);
});

$(document).ready(function(){	  
	$(".remark-form").on("submit", function() {
			var orderid = $(this).attr('orderid');  
			var selected_user_id = $(this).attr('selected_user_id');  
			
			$("#msg").html('<div class="alert alert-info"><i class="fa fa-spin fa-spinner"></i> Please wait...!</div>');
			var formData = new FormData(this);
			formData.append('orderid', orderid);
			formData.append('selected_user_id', selected_user_id);
			var remark_proof = $(".remark_proof").val();
			$(".remark_proof").css('border','');
			if( remark_proof == ''){
				$(".remark_proof").css('border','1px solid red');
				return false;
			}else{
				$.ajax({
				  type: "POST",
				  url: "action_remarkimage_ajax.php",
				  data: formData,
				  contentType: false, // The content type used when sending data to the server.
				  cache: false, // To unable request pages to be cached
				  processData: false, // To send DOMDocument or non processed data file it is set to false
				  success: function(data) {
					  location.reload(true);
				  },
				  error: function(data) {
					$("#msg").html(
					  '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> There is some thing wrong.</div>'
					);
				  }
				});
			}
			
});

$(".delete_meremark").click(function(){
	var orderid = $(this).attr('orderid');
	var selected_user_id = $(this).attr('selected_user_id');  
	var cnfrmDelete = confirm("Are You Sure want to delete this remark proof?");
	if(cnfrmDelete==true){
		  $.ajax({
			url:'functions.php',
			method:'POST',
			data:{method:'delete_meremark',selected_user_id:selected_user_id},
			success:function(res){location.reload(true);}
		  });	
	}
});
	
	
});
/* END: Merchant remark image*/
/*Copy code*/
function gccopy(element,clsn,orderid) {
	var $temp = $("<textarea>");
	$("body").append($temp);
	$("#b1value_"+orderid).removeAttr('id');
	var html = $(element).html();
	html = html.replace(/<span><\/span>/g, ""); // or \r\n
	html = html.replace(/<span>/g, ""); // or \r\n
	html = html.replace(/<\/span>/g, ""); // or \r\n
	//console.log(html);
	$temp.val(html).select();
	document.execCommand("copy");
	$temp.remove();
	$("."+clsn).html('Copied'); 	
}

/*Copy writeup*/
function copyToCustAdd(element,clsn,orderid) {
	  var $temp = $("<textarea>");
	  $("body").append($temp);
	  $("#od_copy_details_"+orderid).removeAttr('id');
	  var html = $(element).html();
	  $(element).find('p').attr('id',"od_copy_details_"+orderid);
	  html = html.replace(/<br>/g, "\n"); // or \r\n
	  html = html.replace(/<b>/g, ""); // or \r\n
	  html = html.replace(/<\/b>/g, ""); // or \r\n
	  html = html.replace(/<u>/g, ""); // or \r\n
	  html = html.replace(/<\/u>/g, ""); // or \r\n
	  html = html.replace(/<\/p>/g, ""); // or \r\n
	  html = html.replace(/<p>/g, ""); // or \r\n
	  html = html.replace(/&nbsp;/g, ""); // or \r\n
	  
	  
	  $temp.val(html).select();
	  document.execCommand("copy");
	  $temp.remove();
	  $("."+clsn).html('copied'); 	
}
//copy orderdetails
function copyToOrderDetails(element,clsn) {
	  var $temp = $("<textarea>");
	  $("body").append($temp);
	  var html = $(element).html();
	  html = html.replace(/<br>/g, "\n"); // or \r\n
	  html = html.replace(/<b>/g, ""); // or \r\n
	  html = html.replace(/<\/b>/g, ""); // or \r\n
	  html = html.replace(/<u>/g, ""); // or \r\n
	  html = html.replace(/<\/u>/g, ""); // or \r\n
	   html = html.replace(/&nbsp;/g, ""); // or \r\n
	  $temp.val(html).select();
	  document.execCommand("copy");
	  $temp.remove();
	  $("."+clsn).html('Copied Orderdetails'); 	
}

$(document).ready(function(){
	
	$(".google_sheet_check").click(function(){
		var orderid = $(this).attr('order_id');
		var cnfrmDelete = confirm("Have you checked the google sheet for waiting list?");
		if(cnfrmDelete==true){
			$.ajax({
					url:'functions.php',
					method:'POST',
					data:{method:'google_sheet_check',orderid:orderid},
					success:function(res){
						//console.log(res);
						//location.reload(true);
						//var g_link = "https://docs.google.com/spreadsheets/d/1iKQUeXmVtEueblY-6-DrGgKPG7QC-O_z-bAOOONvPNA/edit?usp=sharing";
						var g_link = "<?php echo $s_admin_google_link;?>";
						window.open(g_link, '_blank');
						
						
						
					}
			});	
		}
	});
	
	

	$(".reverse_cancel").click(function(){
		var orderid = $(this).attr('order_id');
		var cnfrmDelete = confirm("Are You sure to reverse the cancel order?");
		if(cnfrmDelete==true){
			$.ajax({
					url:'functions.php',
					method:'POST',
					data:{method:'reverse_cancel',orderid:orderid},
					success:function(res){
						//console.log(res);
						location.reload(true);
					}
				});	
		}
	});
	
	$(".foods_reason").change(function(){
		var orderid = $(this).attr('order_id');
		var foods_reason = $(this).val();
		$.ajax({
			url:'functions.php',
			method:'POST',
			data:{method:'foods_reason',orderid:orderid,foods_reason:foods_reason},
			success:function(res){
				//console.log(res);
				//location.reload(true);
			}
		});	
	});
	
	$(".cancel_order").change(function(){
		var orderid = $(this).attr('order_id');
		var cancel_reason = $(this).val();
		var cancel_person = $(".cancel_person_"+orderid).val();
		$(".cancel_person_"+orderid).css('border','');
		if(cancel_person == ''){
			//$(".cancel_person_"+orderid).focus();
			$(".cancel_person_"+orderid).css('border','1px solid red');
			alert('Please enter name first.');
			return false;
		}else{
			$(".foods_reason").hide();
			$(".foods_reason_"+orderid).css('border','');
			if(cancel_reason == 3){
				$(".foods_reason_"+orderid).show();
				$(".foods_reason_"+orderid).css('border','1px solid red');
			}
			if(cancel_reason == 2){
				var cnfrmDelete = confirm("重要！通知顾客取消订单先！记得在商家第一页备注！");
			}else{
				var cnfrmDelete = confirm("重要！ 通知顾客取消订单先！！");
			}
			
			if(cnfrmDelete==true){
				$.ajax({
					url:'functions.php',
					method:'POST',
					data:{method:'cancelorder',orderid:orderid,cancel_reason:cancel_reason},
					success:function(res){
						//console.log(res);
						location.reload(true);
					}
				});	
			}
		}
		//var cnfrmDelete = confirm("Are You sure to cancel order?");
		//if(cnfrmDelete==true){
			
		//}
	});
	
	
	$(".generate_code").click(function(){
		var orderid = $(this).attr('order_id');
		var b1_Code = $("#b1_code"+orderid).val();
		if(b1_Code == ''){
			$("#b1_code"+orderid).css('border','1px solid red');
			return false;
		}else{
			$("#b1value_"+orderid).html("-"+b1_Code);
			
			
			$.ajax({
				url:'functions.php',
				method:'POST',
				data:{method:'b1_code',orderid:orderid,b1_Code:b1_Code},
				success:function(res){
					$(".gc_main_"+orderid).hide();
					$(".copycode_"+orderid).show();
					//location.reload(true);
				}
			});	
		}
	});
	
	$(".code_admin_code").focusout(function(){
		var orderid = $(this).attr('order_id');
		var code_admin_code = $(this).val();
			$.ajax({
				url:'functions.php',
				method:'POST',
				data:{method:'inform_mecnt_status',orderid:orderid,code_admin_code:code_admin_code},
				success:function(res){
					//console.log(res);
					location.reload(true);
				}
			});	
		
	});
	
	var max = 200;
	$(".food_receipt_seen").keyup(function(e){
		var orderid = $(this).attr('order_id');
		$("#countlimit"+orderid).text("Characters left: " + (max - $(this).val().length));
	});
	
	$(".food_receipt_seen").focusout(function(){
		var orderid = $(this).attr('order_id');
		var food_receipt_seen = $(this).val();
			$.ajax({
				url:'functions.php',
				method:'POST',
				data:{method:'food_receipt_seen',orderid:orderid,food_receipt_seen:food_receipt_seen},
				success:function(res){
					//console.log(res);
					//location.reload(true);
				}
			});	
		
	});
	
	
	
	
	
	
	
	
	
	$(".cancel_admin").focusout(function(){
		var orderid = $(this).attr('order_id');
		var cancel_person = $(this).val();
		console.log(".click_event")
		$.ajax({
				url:'functions.php',
				method:'POST',
				data:{method:'cancelperson',orderid:orderid,cancel_person:cancel_person},
				success:function(res){
					//console.log(res);
					location.reload(true);
				}
			});	
	});
	
	
	$(".verified_user").click(function(e){
		var order_id= $(this).attr('order_id');
		var user_id = $(this).attr('user_id');
		    $.ajax({
				url :'functions.php',
				type:"post",
				data:{user_id:user_id,method:"verified_user",order_id:order_id},     
				dataType:'json',
				success:function(result){  
					var data = JSON.parse(JSON.stringify(result));   
					$(".verified_"+order_id).hide();
				}
			});      
	});
	
	
	  $(".image-form").on("submit", function() {
			var orderid = $(this).attr('orderid');  
			console.log(orderid);
			$("#msg").html('<div class="alert alert-info"><i class="fa fa-spin fa-spinner"></i> Please wait...!</div>');
			var formData = new FormData(this);
			//formData.append('file', this.files[0]);
			formData.append('orderid', orderid);
			var payment_proof = $(".payment_proof").val();
			$(".payment_proof").css('border','');
			if( payment_proof == ''){
				$(".payment_proof").css('border','1px solid red');
				return false;
			}else{
				$.ajax({
				  type: "POST",
				  url: "action_image_ajax.php",
				  data: formData,
				  //data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
				  contentType: false, // The content type used when sending data to the server.
				  cache: false, // To unable request pages to be cached
				  processData: false, // To send DOMDocument or non processed data file it is set to false
				  success: function(data) {
					  location.reload(true);
				  },
				  error: function(data) {
					$("#msg").html(
					  '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> There is some thing wrong.</div>'
					);
				  }
				});
			}
			
});

$(".delete_paymentproof").click(function(){
	var orderid = $(this).attr('orderid');
	var cnfrmDelete = confirm("Are You Sure want to delete this payment proof?");
	if(cnfrmDelete==true){
		  $.ajax({
			url:'orderlist.php',
			method:'GET',
			data:{
				data:'deleteRecord',
				orderid:orderid
				},
			success:function(res){location.reload(true);}
		  });	
	}
});
	
	
});

$(".rider_bank_amount").focusout(function(e){
		var order_id= $(this).attr('order_id');
		var bank_text = this.value;
		console.log(bank_text);
		if(bank_text!='' && order_id)
		{  
		    $.ajax({
				url :'functions.php',
				 type:"post",
				 data:{bank_text:bank_text,method:"rider_bank_amount",order_id:order_id},     
				 dataType:'json',
				 success:function(result){  
					var data = JSON.parse(JSON.stringify(result));   
					if(data.status==true)
					{
					}
					else
					{alert('Failed to update');	}
					}
			});      
		} 
	});
	
	$(".rider_cash_amount").focusout(function(e){
		var order_id= $(this).attr('order_id');
		var bank_text = this.value;
		console.log(bank_text);
		if(bank_text!='' && order_id)
		{  
		    $.ajax({
				url :'functions.php',
				 type:"post",
				 data:{bank_text:bank_text,method:"rider_cash_amount",order_id:order_id},     
				 dataType:'json',
				 success:function(result){  
					var data = JSON.parse(JSON.stringify(result));   
					if(data.status==true)
					{
					}
					else
					{alert('Failed to update');	}
					}
			});      
		} 
	});
	
 
/* start :: save admin_bank_price & admin_cash_price*/
	$(".admin_bank_price").focusout(function(e){
		var order_id= $(this).attr('order_id');
		var bank_text = this.value;
		console.log(bank_text);
		if(bank_text!='' && order_id)
		{  
		    $.ajax({
				url :'functions.php',
				 type:"post",
				 data:{bank_text:bank_text,method:"admin_bank_price",order_id:order_id},     
				 dataType:'json',
				 success:function(result){  
					var data = JSON.parse(JSON.stringify(result));   
					if(data.status==true)
					{
					}
					else
					{alert('Failed to update');	}
					}
			});      
		} 
	});
	
	$(".admin_cash_price").focusout(function(e){
		var order_id= $(this).attr('order_id');
		var bank_text = this.value;
		console.log(bank_text);
		if(bank_text!='' && order_id)
		{  
		    $.ajax({
				url :'functions.php',
				 type:"post",
				 data:{bank_text:bank_text,method:"admin_cash_price",order_id:order_id},     
				 dataType:'json',
				 success:function(result){  
					var data = JSON.parse(JSON.stringify(result));   
					if(data.status==true)
					{
					}
					else
					{alert('Failed to update');	}
					}
			});      
		} 
	});
	
		$(".admin_commission_price").focusout(function(e){
		var order_id= $(this).attr('order_id');
		var comm_text = this.value;
		console.log(comm_text);
		if(comm_text!='' && order_id)
		{  
		    $.ajax({
				url :'functions.php',
				 type:"post",
				 data:{comm_text:comm_text,method:"admin_commission_price",order_id:order_id},     
				 dataType:'json',
				 success:function(result){  
					var data = JSON.parse(JSON.stringify(result));   
					if(data.status==true)
					{
					}
					else
					{alert('Failed to update');	}
					}
			});      
		} 
	});

	
	
	$(".rider_reason_dif").focusout(function(e){
		var order_id= $(this).attr('order_id');
		var reason_text = this.value;
		  
		    $.ajax({
				url :'functions.php',
				 type:"post",
				 data:{reason_text:reason_text,method:"rider_reason_dif",order_id:order_id},     
				 dataType:'json',
				 success:function(result){  
					var data = JSON.parse(JSON.stringify(result));   
					if(data.status==true)
					{
					}
					else
					{alert('Failed to update');	}
					}
			});      
	});
	
	
	$(".admin_code").focusout(function(e){
		var order_id= $(this).attr('order_id');
		var admin_code = this.value;
		  
		    $.ajax({
				url :'functions.php',
				 type:"post",
				 data:{admin_code:admin_code,method:"admin_code",order_id:order_id},     
				 dataType:'json',
				 success:function(result){  
					var data = JSON.parse(JSON.stringify(result));   
					if(data.status==true)
					{
					}
					else
					{alert('Failed to update');	}
					}
			});      
	});
	
	$(".admin_confirmed_by").focusout(function(e){
		var order_id= $(this).attr('order_id');
		var confirm_by = this.value;
		  
		    $.ajax({
				url :'functions.php',
				 type:"post",
				 data:{confirm_by:confirm_by,method:"admin_confirmed_by",order_id:order_id},     
				 dataType:'json',
				 success:function(result){  
					var data = JSON.parse(JSON.stringify(result));   
					if(data.status==true)
					{
					}
					else
					{alert('Failed to update');	}
					}
			});      
	});
	/* END :: save admin_bank_price & admin_cash_price*/
	 
	
</script>



</body>

</html>