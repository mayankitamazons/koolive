<?php 
include('config.php');
if(!isset($_SESSION['s_admin']))
{
	header("location:rl.php");
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
 $query="select  order_list.*,u.shop_open,u.working_text,u.working_text_chiness,u.not_working_text_chiness,u.not_working_text,u.name as merchant_name,u.mobile_number as merchant_mobile_number,u.foodpanda_link,u.vendor_comission as vc_user, u.price_hike as price_hike_user from order_list inner join 
users as u on u.id=order_list.merchant_id  order by order_list.id desc limit 0,100";

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

<div class="container">
  <h2>Latest Order</h2>
          
  <table class="table">
    <thead>
      <tr>
        <th>S.No</th>
        <th>Invoice Number</th>
        <th>DATE OF ORDER</th>
		 <th>Order Status</th>
        <th>Detail</th>
		<th>Rider Info</th>
        <th>Write up</th>
      
        <th>User Detail</th>
        <th>Merchant Name</th>
        <th>Food Panda Link</th>
        <th>Merchant Mobile Number</th>
       

        <th>Action</th>

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
		<td><?php echo $row['id']; ?></td/>  
		                            <td><?php echo date_format($date,"m/d/Y h:i A");  ?>   
								
                                <?php echo '<br>'; echo $new_time[1] ?>
                                <?php 
                                  if($row['status'] == 0){?>
                                    <p style="color: red;"><?php echo $diff_time; ?></p> <?php 
                                  }?>
                            </td>
		   <td><input type="button" next_status="<?php echo $n_status; ?>" style="background-color:<?php echo $s_color;?>" class= "status btn btn-primary" value="<?php  echo $sta;?>" status="<?php echo $row['status'];?>" data-invoce='<?php echo $row['invoice_no'];?>' data-id="<?php echo $row['id']; ?>"/>



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
			if($minutes <= 15){
			}else{
				//echo 'ALERT';
				$alert_show = 'yes';
			}
		}else{
			//echo 'ALERT2';
			$alert_show = 'yes';
		}
		/*echo $interval->d.' days<br>';
		echo $interval->y.' years<br>';
		echo $interval->m.' months<br>';
		echo $interval->h.' hours<br>';
		echo $interval->i.' minutes<br>';
		echo $interval->s.' seconds<br>';
		*/?>

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
}</style>
		<?php if($row['info_merchant_admin'] != ''){?>
			<span class="" order_id='<?php echo $row['id']; ?>'>Admin: <?php echo $row['info_merchant_admin'];?></span>
		<?php }else{
					$class_alert = '';
				if($alert_show == 'yes'){
					$class_alert = 'blink_info_button';
				}
			?>
						
			<span class="btn btn-danger info_merchant <?php echo $class_alert;?>" order_id='<?php echo $row['id']; ?>'> Inform Merchant</span>
		<?php }?>
		<!-- END---->
		</td>
		
		
		
		<td style="min-width:190px;"><input type="text" selected_user_id="<?php echo $row['id']; ?>"  name="rider_info" placeholder="%" class="form-control rider_info" value="<?php echo $row['rider_info'];?>"></td>
        	<td class="writeup_set" id="writeup_set_<?php  echo $row['id'];?>" order_id='<?php echo $row['id']; ?>'><i class="fa fa-copy" style="font-size:25px;margin-left: 10%;"></i></td>
		<td>
		<?php if($r['user_name']){  echo $r['user_name']."- ".$r['user_mobile']; } else { echo $r['user_mobile'];} ?>
		</td>
		<td><?php echo $r['merchant_name']; ?>
		<br/>
		
		<?php 
		$chin_word = str_replace("我们的外送服务时间每日早","",$r['working_text_chiness']);
		$chin_word_2 = str_replace("您仍然可以在非工作时间下单。","",$chin_word);
		$english_word = str_replace("Our food delivery hours are from","",$r['working_text']);
		?>
		<p style="color:red">外送时间是: <?php echo $english_word." ".$r['not_working_text'];?></p>

		</td>
		<td><a href="<?php echo $r['foodpanda_link']; ?>" target="_blank"><?php echo $r['foodpanda_link']; ?></a></td>   
        <td><?php echo $r['merchant_mobile_number']; ?></td>
     

<td><a target="_blank" href="orderview.php?did=<?php echo $row['merchant_id'];?>&vs=<?php  echo md5(rand());?>">Check order</a></td>

      </tr>
	 <?php $i++;} ?>
      
    </tbody>
  </table>
</div>
<div class="modal fade" id="orderdetailmodel" role="dialog" style="margin-top:12%;"> 
							<div class="modal-dialog">
							<!-- Modal content-->		
							<div class="modal-content" style="min-height:350px;">	
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
<div id="myModal_infomerchnt" class="modal fade" style="">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
				<h5 class="modal-title">Info Merchant</h5>
            </div>
            <div class="modal-body">
				<form name="infomerchnt_form" id="infomerchnt_form" class="form-inline" >
					<div class="form-group mx-sm-3 mb-2">
						<input type="text" class="form-control" name="code_admin_code" id="code_admin_code" placeholder="Code">
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
	   $(".rider_info").focusout(function(e){
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
	  
	  	    setInterval(function(){ 
				
					var s_token=generatetokenno(16);
						var r_url="http://188.166.187.218/showorder.php?ms="+s_token;
					 window.location.replace(r_url);
			}, 
				60000);      
	   
});

/* Info Merchnt*/
$(document).ready(function(){
	$(".info_merchant").click(function(){
		var ordeid = $(this).attr('order_id');
		$("#admin_order_id").val(ordeid);
		$("#myModal_infomerchnt").modal('show');
	});
	
	$(".submit_admin_popup").click(function(){
		var ordeid = $("#admin_order_id").val();
		var admin_code = $("#code_admin_code").val();
		
		console.log("+++"+admin_code);
		$("#code_admin_code").css('border','');
		
		if(admin_code == ''){
			$("#code_admin_code").focus();
			$("#code_admin_code").css('border','1px solid red');
			return false;
		}else{
			
			var cnfrm = confirm("Are You Sure Inform the Merchant?");
			if(cnfrm==true){
				
				$(".ajx_lang_resp").show();
				$(".please_wait_text").show();
				
				$.ajax({
					url:'functions.php',
					method:'POST',
					data:{data:'infomerchnt',admin_code:admin_code,ordeid:ordeid},
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
</body>

</html>