<?php 
include('config.php');
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

$query="select  order_list.*,u.shop_open,u.working_text,u.working_text_chiness,u.not_working_text_chiness,u.not_working_text,u.name as merchant_name,user.latitude,user.longitude,u.mobile_number as merchant_mobile_number,u.whatsapp_link,u.foodpanda_link,u.vendor_comission as vc_user, u.price_hike as price_hike_user from order_list 
inner join users as u on u.id=order_list.merchant_id  
inner join users as user on user.id=order_list.user_id
order by order_list.id desc limit 0,100";


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

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Latest order</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="./css/font-awesome.min.css">  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>

<?php 
$order_pedning_count = 0;
$pending_orders =  mysqli_query($conn,'select count(*) as order_cnt from order_list where rider_info = 0 and cancel_order!=1 and created_on >"2021-04-07 12:00:00"');
$pending_order_count = mysqli_fetch_assoc($pending_orders);

$order_pedning_count = $pending_order_count['order_cnt'];

$riders_processjob = mysqli_query($conn,"select r.r_id,count(od.id)as ordercount from tbl_riders as r LEFT JOIN order_list as od ON od.rider_info = r.r_id and rider_complete_order = 0 where r_status = 1 and r_online = 1 group by r.r_id");
$riderJobArray = array();
while($rjob_result = mysqli_fetch_assoc($riders_processjob)){
	$riderJobArray[$rjob_result['r_id']] = $rjob_result['ordercount'];
}	
						
?>
<div class="container">
  <h2>Latest Order</h2>
          
  <table class="table">
    <thead>
      <tr>
        <th>S.No</th>
        <th>Invoice Number</th>
        <th>DATE OF ORDER</th>
		 <th>Order Status</th>
		   <th>Action</th>
        <th>Detail</th>
		<th>Rider Info <span style="color:red;font-size:22px">(<?php echo $order_pedning_count?>)</span></th>
        <th>Write up</th>
      
        <th>User Detail</th>
       
        <th>Merchant Name</th>
        <th>Food Panda Link</th>
        <th>Whatsapp  Link</th>
        <th>Merchant Mobile Number</th>
       

      

      </tr>
    </thead>
    <tbody>
	<?php 
     $qu=mysqli_query($conn,$query);
	 $i=1;
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
	?>
      <tr>
        <td><?php echo $i; ?></td>
		<td><?php echo $row['invoice_no']; ?></td/>  
		                            <td><?php echo date_format($date,"m/d/Y h:i A");  ?>   
								
                                <?php echo '<br>'; echo $new_time[1] ?>
                                <?php 
                                  if($row['status'] == 0){?>
                                    <p style="color: red;"><?php echo $diff_time; ?></p> <?php 
                                  }?>
								  
								  <!-- payment proof -->
							<br/>
					  <?php if($row['payment_proof'] != '' ){?>
						  <label class="btn-sm btn-yellow" style="background-color:#6ea6d6;margin-top:10px;width:150px">
						  <a class="fancybox" rel="" href="<?php echo $site_url.'/upload/'.$row['payment_proof'];?>" style="color:white" >
Payment Proof </a>
						  </label>
					  <?php }else{?>
					  <label class="btn-sm btn-yellow" style="background-color:#fb9678;border-color:#fb9678;margin-top:10px;width:150px;color:white">
					  No payment proof !!
						  </label>
					  <?php }?>
							<!-- End Payment Proof-->
							
							
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
    $allproduct_price = array_sum($product_price_array);
    $allproduct_price_digit = bcadd(sprintf('%F', $allproduct_price) , '0', 1);
    $cash_term_payment_digit = bcadd(sprintf('%F', $cash_term_payment) , '0', 1);
    $price_diff = abs($cash_term_payment_digit - $allproduct_price_digit);
	echo "cashprice = ".$cash_term_payment_digit;	
	echo '<br/>';
	echo "totalproduct = ".$allproduct_price_digit;
	echo '<br/>';
	echo "Diff = ".$price_diff; // 28

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
<td><a target="_blank
" href="orderview.php?did=<?php echo $row['merchant_id'];?>&vs=<?php  echo md5(rand());?>">Check order</a></td>
							
		<td  style="font-size:18px;" >
		
		<span class="s_order_detail btn btn-blue" total_bill="<?php echo number_format($total_bill,2); ?>" order_id='<?php echo $row['id']; ?>'><?php echo $language['detail']; ?></span>
		
		<!--- info Merchant Button-->
		<br/>
		<?php 
		$created_on = $row['created_on'];
		$old_date = new DateTime($created_on);
		$now = new DateTime(Date('Y-m-d H:i:s'));
		//echo date('Y-m-d H:i:s');
		//echo '<br/>';
		$interval = $old_date->diff($now);
		$years = $interval->y;
		$months = $interval->m;
		$days = $interval->d;
		$hours = $interval->h;
		$minutes = $interval->i;
		$alert_show = 'no';
		if($years == 0 && $months == 0 && $days== 0 && $hours == 0 ){
			if($minutes <= 7){
			}else{
				//echo 'ALERT';
				$alert_show = 'yes';
			}
		}else{
			//echo 'ALERT2';
			$alert_show = 'yes';
		}
		// echo $interval->d.' days<br>';
		// echo $interval->y.' years<br>';
		// echo $interval->m.' months<br>';
		if($r['status']!=4)
		{
		// echo $interval->h.' hours<br>';
		// echo $interval->i.' minutes<br>';
		// echo $interval->s.' seconds<br>';
		if($interval->d)
		echo $interval->d."Day,".$interval->h." Hour:".$interval->i." min :"."</br>";
		else
		echo $interval->h." Hour :".$interval->i." Min"."</br>";
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
		<?php if($row['inform_mecnt_status'] != '0' || $row['info_merchant_admin']!= ''){
			
			if($row['inform_mecnt_status'] == 1){
				$labels = 'Inform already';
				$lab_cls = 'green';
			}else if($row['inform_mecnt_status'] == 2){
				$labels = 'Cannot reach  merchant, now inform customer rider is otw checking';
				$lab_cls = 'red';
			}else if($row['inform_mecnt_status'] == 3){
				$labels = 'Rider buy himself';
				$lab_cls = 'green';
			}else if($row['inform_mecnt_status'] == 4){
				$labels = 'Order Cancelled';
				$lab_cls = 'red';
			}else{
				$labels = '';
				$lab_cls = '';
			} 
			?>
		
			<span class="<?php echo $lab_cls;?>" order_id='<?php echo $row['id']; ?>' >
			<?php echo $labels;?>
			<br/>
			<?php if($row['info_merchant_admin'] != ''){?>
			Admin: <?php echo $row['info_merchant_admin'];?>
			<?php }?>
			</span>
			
			
			<span class="edit_info_merchant info_merchant" invoice_no='<?php echo $row['invoice_no']; ?>'   order_id='<?php echo $row['id']; ?>' title="Edit" inform_mecnt_status="<?php echo $row['inform_mecnt_status'];?>" admin_name="<?php echo $row['info_merchant_admin'];?>" ><i class="fa fa-pencil"></i></span>
			<br/>
			<b>Invoice No:</b> # <?php echo $row['invoice_no']; ?>
		<?php }else{
					$class_alert = '';
				if($alert_show == 'yes'){
					$class_alert = 'blink_info_button';
				}
			?>
						
			<span class="btn btn-danger info_merchant <?php echo $class_alert;?>" invoice_no='<?php echo $row['invoice_no']; ?>' order_id='<?php echo $row['id']; ?>'>Admin 名字成功通知商家</span>
			<br/>
			<b>Invoice No:</b> # <?php echo $row['invoice_no']; ?>
		<?php }?>
		<!-- END---->
		
		<!-- Start Cancel Order --->
		<?php if($row['cancel_order'] == 1){?>
		<?php }else{?>
		<br/>
		<a style="background-color:#eca7a7;border-color:#eca7a7;" class="btn btn-danger cancel_order cancel_order_<?php echo $row['id']; ?>" order_id='<?php echo $row['id']; ?>' href="javascript:void(0);">Cancel Order</a>
		<?php }?>
		<br/>
		
		
		
		<!-- END Cancel order-->
		
		</td>
		
		<!--
		<td style="min-width:190px;"><input type="text" selected_user_id="<?php echo $row['id']; ?>"  name="rider_info" placeholder="%" class="form-control rider_info" value="<?php echo $row['rider_info'];?>"></td>
		-->
		<td style="min-width:190px;">
		<!--
		<input type="text" selected_user_id="<?php echo $row['id']; ?>"  name="rider_info" placeholder="%" class="form-control rider_info" value="<?php echo $row['rider_info'];?>">
		-->
		<?php if($row['cancel_order'] != 1){?>
		<!-- Start select online Riders-->
		
		<style>
		select#rider_info option {
			background: white;
		}
		select.rider_info_select{
			background:red;
			color:white;
			font-weight:bold;
			border-color:red;
			
		}
</style>
						<?php 
						$riders_query = "select * from tbl_riders where r_status = 1 and r_online = 1";
						//$riders_query = "select (select count(*) as dd from order_list as od where od.rider_info = r.r_id and rider_complete_order = 0 ) as rider_process_count,r.* from tbl_riders as r where r_status = 1 and r_online = 1";
						$ridersFetch = mysqli_query($conn,$riders_query);
						?>
						<select name="rider_info" id="rider_info" class="form-control rider_info <?php if($row['rider_info'] == 0){?>rider_info_select <?php }?>" order_id="<?php echo $row['id']; ?>">
						<option value="0" style="color:black"  >Select Riders</option>
						<?php 
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
							<?php }else{?>
							<option   value="<?php echo $r_value['r_id']; ?>" style="color:<?php echo $r_css;?>"><?php echo "Jobs:".$riderJobArray[$r_value['r_id']]." ".$r_value['r_name']."(".$r_value['r_mobile_number'].")"; ?></option>
							<?php }?>
						<?php }?>
						</select>
						
						<br/>
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
						<select name="s_rider_option" id="s_rider_option_<?php echo $row['id']; ?>" class="form-control s_rider_option"  order_id="<?php echo $row['id']; ?>">
								<option value="0">Select Option</option>
								<option <?php if($row['s_rider_option'] == '1'){ echo 'selected';}?> value="1"><?php echo $s_label1;?></option>
								<option <?php if($row['s_rider_option'] == '2'){ echo 'selected';}?> value="2"><?php echo $s_label2;?></option>
								<option <?php if($row['s_rider_option'] == '3'){ echo 'selected';}?> value="3"><?php echo $s_label3;?></option>
								<option <?php if($row['s_rider_option'] == '4'){ echo 'selected';}?> value="4"><?php echo $s_label4;?></option>
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
								<br/>
								<span class="btn btn-sm btn-danger blink_info_button">Rider not accept order yet!!</span>
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
								<br/>
								<span class="btn btn-sm btn-danger blink_info_button">Rider not reached shop yet!!</span>
								<?php	
								}
							}
						} 
						
						
						?>
						<!-- END select online Riders-->
	 <?php }?>
		</td>
       
	   
        	<!--<td class="writeup_set" id="writeup_set_<?php  echo $row['id'];?>" order_id='<?php echo $row['id']; ?>'><i class="fa fa-copy" style="font-size:25px;margin-left: 10%;"></i></td>-->
		<td >
			
			<a style="display:none" class="btn btn-primary copy-text cust_<?php echo $row['id'];?>" onclick="copyToCustAdd('#writeup_set_<?php  echo $row['id'];?>','cust_<?php echo $row['id'];?>','<?php echo $row['id'];?>')"> Copy </a>
			
			<a style="display:none" class="btn btn-primary  copy-text orderdeatils_<?php echo $row['id'];?>" onclick="copyToOrderDetails('#od_copy_details_<?php  echo $row['id'];?>','orderdeatils_<?php echo $row['id'];?>')"> Copy Order Details </a>
			<br/>
			<span class="writeup_set" id="writeup_set_<?php  echo $row['id'];?>" order_id='<?php echo $row['id']; ?>'><i class="fa fa-copy" style="font-size:25px;margin-left: 10%;"></i><span></td>
		
		
		<td>
		<?php 
		if($r['user_name']){  echo $r['user_name']."- ".$r['user_mobile']; } else { echo $r['user_mobile'];} ?>
		
		<br/>
		<b>Latitude:</b> <?php echo $r['latitude'];?><br/>
		<b>Longtitude:</b> <?php echo $r['longitude'];?><br/>
		
		
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
							<?php }?>
						<?php }?>
						</br>
				<a class="" target="_blank" href="http://maps.google.com/maps?q=<?php echo  $row['location']; ?>">  <?php echo $row['location']; ?></a>
		</td>
		
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
		<p style="color:red">请改正如果错误，</p>
		<p style="color:red">外送时间是: <?php echo $english_word." ".$r['not_working_text'];?></p>
		<?php }else{?>
		<p >请改正如果错误，</p>
		<p >外送时间是: <?php echo $english_word." ".$r['not_working_text'];?></p>
		
		<?php }?>
		</td>
		<td><a href="<?php echo $r['foodpanda_link']; ?>" target="_blank"><?php echo $r['foodpanda_link']; ?></a></td>   
		<td><a href="<?php echo $r['whatsapp_link']; ?>" target="_blank"><?php echo $r['whatsapp_link']; ?></a></td>   
        <td><?php echo $r['merchant_mobile_number']; ?></td>
     



      </tr>
	 <?php $i++;} ?>
      
    </tbody>
  </table>
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
							<option value="0">Select Status</option>
							<option value="1">Inform already</option>
							<option value="2">Cannot reach  merchant, now inform customer rider is otw checking</option>
							<option value="3">Rider buy himself</option>
							<option value="4">Order Cancelled</option>
						</select>
					</div>
					
					<br/>
					<div class="form-group mx-sm-3 mb-2">
						<input type="text" class="form-control" name="code_admin_code" id="code_admin_code" placeholder="Admin 名字成功通知商家" style="width:70%">
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
<script>
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
	 	$(".rider_info").change(function(e){
		var order_id= $(this).attr('order_id');
		var s_rider_option = $("#s_rider_option_"+order_id).val();
		
		var rider_text=this.value;
		
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
	
	
	  
/*End riders*/
	  setInterval(function(){ 
				
					var s_token=generatetokenno(16);
						var r_url="https://www.koofamilies.com/showorder.php?ms="+s_token;
					 window.location.replace(r_url);
			}, 
				60000);      
	   
});


/* Info Merchnt*/
$(document).ready(function(){
	$(".info_merchant").click(function(){
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
	
	$(".submit_admin_popup").click(function(){
		var ordeid = $("#admin_order_id").val();
		var admin_code = $("#code_admin_code").val();
		
		var inform_mecnt_status = $("#inform_mecnt_status").val();
		//console.log("+++"+admin_code);
		$("#inform_mecnt_status").css('border','');
		
		if(inform_mecnt_status == '0'){
			$("#inform_mecnt_status").focus();
			$("#inform_mecnt_status").css('border','1px solid red');
			return false;
		}else{
			
			var cnfrm = confirm("Are You Sure Inform the Merchant?");
			if(cnfrm==true){
				
				$(".ajx_lang_resp").show();
				$(".please_wait_text").show();
				
				$.ajax({
					url:'functions.php',
					method:'POST',
					data:{data:'infomerchnt',admin_code:admin_code,ordeid:ordeid,inform_mecnt_status:inform_mecnt_status},
					success:function(res){
						//console.log(res);
						location.reload(true);
						$(".ajx_lang_resp").hide();
						$(".please_wait_text").hide();
					}
				});	
			}
			
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
	$(".cancel_order").click(function(){
		var orderid = $(this).attr('order_id');
		var cnfrmDelete = confirm("Are You sure to cancel order?");
		if(cnfrmDelete==true){
			$.ajax({
					url:'functions.php',
					method:'POST',
					data:{method:'cancelorder',orderid:orderid},
					success:function(res){
						//console.log(res);
						location.reload(true);
					}
				});	
		}
	});
});
 
</script>



</body>

</html>