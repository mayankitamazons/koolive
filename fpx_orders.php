<?php 
include('config.php');
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

if(isset($_GET['data'])&&$_GET['data']=='deleteRecord'){
	$id = $_GET['t_id'];
	$query_del = mysqli_query($conn,"UPDATE `tbl_temp_saveorder` SET `t_showstatus` = '2' WHERE `tbl_temp_saveorder`.`t_id` = $id;");
	if($query_del){echo true;}else{die();}
}



if (empty($_SESSION["langfile"])) { $_SESSION["langfile"] = "english"; }
    require_once ("languages/".$_SESSION["langfile"].".php");


//echo $query;

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
  <title>FPX order</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="./css/font-awesome.min.css">  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">
  <h2>FPX Order</h2>
          
  <table class="table">
    <thead>
      <tr>
        <th>S.No</th>
		<th>Merchant/Users</th>
		<th>Products</th>
	    <th>Prices</th>
      </tr>
    </thead>
    <tbody>
	<?php 
	$fetch_data = "SELECT cust.id as cust_id, cust.name as cust_name, cust.mobile_number as cust_number, mer.id as mer_id, mer.name as mer_name, mer.mobile_number as mer_number,mer.sst_rate, ts.* FROM `tbl_temp_saveorder` as ts Inner JOIN users as cust ON cust.id = ts.t_user_id Inner JOIN users as mer ON mer.id = ts.t_m_id where t_transid = '' and t_payment_status = '' and t_createddate >= '2021-04-22 20:51:06' and t_showstatus = 0 ORDER BY `ts`.`t_id` DESC";
	$fetch_temp = mysqli_query($conn,$fetch_data);
	
	$fpx_count = mysqli_num_rows($fetch_temp);
	//echo $fpx_count;
	$c=1;
	if($fpx_count != 0){
	while($temp_orders=mysqli_fetch_array($fetch_temp))
	{
		$order_data = unserialize ($temp_orders['t_order_response']);
		
		//echo '<pre>';
		//print_R($order_data);
		$section_type= $order_data['section_type'];
		if($section_type)
		{
		  $section_type = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name FROM sections WHERE id ='".$section_type."'"));
		}
		
		$amount_val = $order_data['p_price'];
		$quantity_ids = $order_data['qty'];
		
		$total = 0;
		foreach ($amount_val as $key => $value){
			if( $quantity_ids[$key] && $value ) {
				$total =  $total + ($quantity_ids[$key] *$value );
			} 
		}
		$remark_ids = $order_data['options'];	
		
		//print_R($amount_val);
		$product_code = $order_data['p_code'];
		$product_ids = $order_data['p_id'];
		
		$productsprice = $order_data['p_price'];
		$varient_type = $order_data['varient_type'];
		
		$sstper=$temp_orders['sst_rate'];
		$incsst = ($sstper / 100) * $total;
		$incsst=@number_format($incsst, 2);
		$incsst=ceiling($incsst,0.05);
		$incsst=@number_format($incsst, 2);
		$g_total=@number_format($total+$incsst, 2);
		//$territory_price = $order_data['territory_hidden_id']."|".$postcode_delivery_charge_hidden_price;
		
		//$territory_price_array = explode("|",$order_data['territory_price']);
		$terr_id = $order_data['territory_hidden_id'];
		$territory_price = $order_data['postcode_delivery_charge_hidden_price'];
								
		
		
	?>
      <tr>
        <td><?php echo $c;?></td>
		<td>
		<b><?php echo date('M d,Y H:i:s',strtotime($temp_orders['t_createddate']));?></b>
		<br/>
		<p style="border: 1px solid lightgray; padding: 7px;">
			<b><?php echo $temp_orders['mer_name']?></b>
			<a href="https://api.whatsapp.com/send?phone=<?php  echo $temp_orders['mer_number']?>" target="_blank"><img src="images/whatapp.png" style="max-width:32px;"/> </a>
			<br/>
			<?php echo "+".$temp_orders['mer_number']?>
		</p>
		<p style="border: 1px solid lightgray; padding: 7px;">
			<b><?php echo $temp_orders['cust_name']?></b><br/>
			<?php echo "+".$temp_orders['cust_number']?><br/>
			<b>Address:</b> <?php echo $order_data['location'];?>
		</p>	
		<?php if($section_type['name'] != '' || $temp_orders['table_type'] != '' || $order_data['remark_extra'] != '' ){?>
		<p style="border: 1px solid lightgray; padding: 7px;">
			<?php if($section_type['name'] != ''){?> <b>Section:</b><?php echo $section_type['name'];?> <br/><?php }?>
			<?php if($temp_orders['table_type'] != ''){?><b>Table:</b> <?php echo $temp_orders['table_type'];?><br/><?php }?>
			<?php if($order_data['remark_extra'] != ''){?><b>Order remark:</b> <?php echo $order_data['remark_extra'];?><br/><?php }?>
		</p>
		<?php }?>
		</td>
		
		
		<td>
		
		 <?php 
		 $k = 0;
		 foreach ($product_ids as $key ){?>
		 <p style="border: 1px solid lightgray; padding: 7px;">
		 <?php 
			
				$product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id ='".$key."'"));
				echo $product['product_name']." ".$product_code[$k]." <b>Qty:</b>".$quantity_ids[$k]." <br/><b>Price:</b> RM".$productsprice[$k].'<br>';
			?>
			<?php if($order_data['varient_type'][$k]){?>
			<b>Varients:</b>
			<?php 
			
							$v_array1=$order_data['varient_type'][$k];
							$v_array=explode(",",$v_array1);
							foreach($v_array as $vr)
							{
								if($vr)
								{
									$v_match=$vr;
									$v_match = ltrim($v_match, ',');
									$sub_rows = mysqli_query($conn, "SELECT * FROM sub_products WHERE id  in ($v_match)");
									while ($srow=mysqli_fetch_assoc($sub_rows)){
										echo $srow['name'];
										echo "</br>";
									}
								}
							} 
							}
							  ?>
		 <?php $k++;?></p> 
		 <?php } ?>
		
						 
		</td>
		
		
		
		
		
		
		 <td>
		 <b>Total:</b><?php echo  @number_format($total, 2);?> <br/>
		 <?php if($incsst != '0.00'){?><b>Service Fee:</b> <?php echo $incsst; ?><br/><?php }?>
		 <b>Grand total:</b><?php  echo $g_total;?><br/>
		 
		 <?php if($order_data['deliver_tax_amount'] != '0.00'){?><b>Delivery Tax:</b> <?php  echo @number_format($order_data['deliver_tax_amount'],2); ?><br/><?php }?>
		 
		 <b>Delivery Charges : </b> 
		 
		 <?php 
			$ter_label = '';
			if($territory_price != '0.00'){
				$ter_label = "+".@number_format($territory_price,2);
			}
						if($order_data['special_delivery_amount']>0 && $order_data['speed_delivery_amount']>0){
								//echo '1';
								echo @number_format($order_data['order_extra_charge'],2)."+ ".number_format($order_data['special_delivery_amount'],2)."(Chiness Delivery)"."</br>+".number_format($order_data['speed_delivery_amount'],2)."(Speed Delivery)".$ter_label;
								}
								else if($order_data['special_delivery_amount']>0 && $order_data['speed_delivery_amount']==0){
								//echo '2';
								echo @number_format($order_data['order_extra_charge'],2)."+ ".number_format($order_data['special_delivery_amount'],2)."(Chiness Delivery)".$ter_label;
									
								}
								else if($order_data['special_delivery_amount']==0 && $order_data['speed_delivery_amount']>0){
									//echo '3';
									echo @number_format($order_data['order_extra_charge'],2)."+ ".number_format($order_data['speed_delivery_amount'],2)."(Speed Delivery)".$ter_label;
								}
								else {
									//echo '4';
									//echo "..".$order_data['order_extra_charge'];
									echo @number_format($order_data['order_extra_charge'],2).$ter_label; 
									} ?>
									
								<?php /*if($terr_id == '-1'){?>
								<label class="btn-sm btn-primary" style="cursor:pointer;background-color:red"> Check AreaName</label>
								<?php }*/?>
									
								<?php if($order_data['free_delivery_prompt'] == 1){?>
								<label class="btn-sm btn-primary" style="cursor:pointer;background-color:green"> 10-minute free delivery</label>
								<?php }?><br/>
								
		 <?php if($order_data['membership_discount'] != ''){?><b>Membership discount:</b> <?php  echo @number_format($order_data['membership_discount'],2); ?><br/><?php }?>
		 <?php if($order_data['coupon_discount'] != ''){?><b>Coupon discount:</b> <?php echo @number_format($order_data['coupon_discount'],2); ?><br/><?php }?>
		 <b>Final Total :</b> <?php  echo @number_format(($g_total+$order_data['order_extra_charge']+$territory_price+$order_data['deliver_tax_amount']+$order_data['special_delivery_amount']+$order_data['speed_delivery_amount'])-($order_data['membership_discount']+$order_data['coupon_discount']),2); ?><br/>
		 
		 <?php echo $order_data['wallet_paid_amount'] ;
		 if($order_data['wallet_paid_amount'] != ''){?> <b>paid by Wallet:</b> <?php  echo @number_format($order_data['wallet_paid_amount'],2); ?><br/><?php }?>
		 <!--<b>Balance Payment:</b> <?php echo @number_format(($g_total+$order_data['order_extra_charge']+$territory_price+$order_data['deliver_tax_amount']+$order_data['special_delivery_amount']+$order_data['speed_delivery_amount'])-($order_data['wallet_paid_amount']+$order_data['membership_discount']+$order_data['coupon_discount']), 2); ?><br/>-->
		 
		 <br/>
		 <span class="btn-sm btn-yellow deleteRecord" t_id="<?php echo $temp_orders['t_id'];?>" style="cursor:pointer;color: white;padding: 10px;font-weight: bold;background-color:red" >Delete Order</span>
		 
		 <a href="fpx_move_order.php?t_id=<?php echo $temp_orders['t_id'];?>" class="btn-sm btn-yellow " style="color: white;padding: 10px;font-weight: bold;background-color:green;cursor:pointer">Move to Order</a>
		 
		 </td>
		
		                           
      </tr>
	<?php $c++;} }else{?>
	<tr><td colspan="3" style="text-align:center;color:red"> No FPX order found !!</td></tr>
	<?php }?>
      
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
	  
	  
	  $(".deleteRecord").click(function(){
		var t_id = $(this).attr('t_id');
		var cnfrmDelete = confirm("Are You Sure Delete This order ?");
		if(cnfrmDelete==true){
			  $.ajax({
					url:'fpx_orders.php',
					method:'GET',
					data:{
						data:'deleteRecord',
						t_id:t_id
						},
					success:function(res){location.reload(true);}
			  });	
		}
	});


});

	

</script>


</body>

</html>