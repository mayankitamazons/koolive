<?php
include("config.php");
function ceiling($number, $significance = 1)
{
	return ( is_numeric($number) && is_numeric($significance) ) ? (ceil(round($number/$significance))*$significance) : false;
}
if(isset($_GET['id']) && $_GET['id'] != ''){
	$order_id = $_GET['id'];
	$merchant = $_GET['merchant'];
	$profile = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SQL_NO_CACHE * FROM users WHERE id ='".$merchant."'"));
	//$order = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SQL_NO_CACHE * FROM order_list WHERE id ='".$order_id."'"));
	$orders_details = "select ol.id as order_id,ol.product_code, cust.name as customer_name, merchnt.name as merchnt_name,merchnt.google_map as merchnt_address,merchnt.mobile_number as merchnt_contact_name, merchnt.merchant_code as merchant_code,merchnt.sst_rate, ol.created_on, ol.location as shipping_address,ol.invoice_no,ol.wallet,ol.product_id,ol.quantity,ol.amount,ol.remark,ol.deliver_tax_amount,ol.speed_delivery_amount,ol.order_extra_charge,ol.special_delivery_amount,membership_discount,ol.coupon_discount,ol.wallet_paid_amount from order_list as ol Inner join users as cust ON cust.id = ol.user_id Inner join users as merchnt ON merchnt.id = ol.merchant_id WHERE ol.id ='".$order_id."'";
	#echo $orders_details;
	$ordersData = mysqli_fetch_assoc(mysqli_query($conn,$orders_details));
	
	
	$products_id = $ordersData['product_id'];
	$products_id = trim($products_id,",");
	$product_qtys = explode(",",$ordersData['quantity']);
	$proudct_amounts = explode(",",$ordersData['amount']);
	$remarks = explode("|", $ordersData['remark']);
	$product_code = explode(",", $ordersData['product_code']);
	$query = "SELECT * FROM `products`where id IN ($products_id)";
	//echo $query;
	$total_rows = mysqli_query($conn,$query);
	
	/*echo '<pre>';
	print_R($ordersData);
	print_R($row);
	exit;*/

}else{
header("Location:https://www.koofamilies.com/"); 
}		
?>

<!doctype html>
                        <html>
                            <head>
                                <meta charset='utf-8'>
                                <title>Invoice</title>
                                <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/css/bootstrap.min.css' rel='stylesheet'>
                                <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css' rel='stylesheet'>
                                <style>@import url('https://fonts.googleapis.com/css2?family=Montserrat&display=swap');

body {
    background-color: #fff;
    font-family: 'Montserrat', sans-serif
}
.p-5 {
    padding: 16px!important;
}
.mt-5 {
    margin-top: 1rem!important;
}
.table{
	margin-bottom: 0px !important;
}
.card {
    border: 1px solid gray;
}

.logo {
    background-color: #eeeeeea8
}

.totals tr td {
    font-size: 13px
}

.footer {
    background-color: #eeeeeea8
}

.footer span {
    font-size: 12px
}
.fnt13{
	font-size:13px !important;
}
.product-qty span {
    font-size: 12px;
    color: slategray
}
.subtotla_td{
	border:none;
}
.subtotla_td td{
	border:none;
	font-size:12px;
}
</style>
                                <script type='text/javascript' src=''></script>
                                <script type='text/javascript' src='https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/js/bootstrap.bundle.min.js'></script>
                                <script type='text/javascript'></script>
                            </head>
                            <body oncontextmenu='return false' class='snippet-body'>
                            <div class="container mt-5 mb-5">
    <div class="row d-flex justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="text-left logo p-2 px-5"> <b>Invoice No</b> #<?php echo $ordersData['invoice_no'];?> 
				<span style="float:right"><b>Payment Type:</b> <?php echo ucfirst($ordersData['wallet']);?></span>
				</div>
                <div class="invoice p-5" >
                    <div style="text-align:center;">
					<h6 ><?php echo ucfirst($ordersData['merchnt_name']);?></h6> 
					<span class="fnt13" ><b>Code</b>: <?php echo $ordersData['merchant_code'];?>  </span>
					&nbsp; | &nbsp;
					<span class="fnt13"   ><b>Tel.No.</b>: +<?php echo $ordersData['merchnt_contact_name'];?> </span>
					<br/>
					 <span class="fnt13"><b>Address</b>: <?php echo $ordersData['merchnt_address'];?></span>
					</div>
					<!--
					<span class="font-weight-bold d-block mt-4">Hello, Chris</span> 
					<span>You order has been confirmed and will be shipped in next two days!</span>
					-->
                    <div class="payment border-top mt-3 mb-3 table-responsive" style="margin-bottom: 0px !important;margin-top: 0px !important;">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td width="40%">
                                        <div class="py-2"> <span class="d-block text-muted">Order Date</span> <span class="fnt13" ><?php echo date('d M, Y',strtotime($ordersData['created_on']));?></span> </div>
                                    </td>
										<td width="20%"></td>
                                    <td width="40%">
                                        <div class="py-2"> <span class="d-block text-muted">Shipping Address</span> <span class="fnt13"><?php echo $ordersData['shipping_address'];?></span> </div>
                                    </td>
									
									
									
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="product border-bottom table-responsive">
                        <table class="table table-borderless_1">
                            <tbody>
							
								<tr style="background-color:#eceaea">
                                    <td width="5%"><b> No </b></td>
									<td width="50%"><b> Products </b></td>
									<td width="20%"><b> Price (RM) </b></td>
									<td width="25%"><b> Final Price (RM) </b></td>
                                </tr>
								
								<?php 
									$i = 1;
									$j = 0;
									while ($row = mysqli_fetch_assoc($total_rows)){
										
								?>
								<tr>
                                    <td > <?php echo $i;?> </td>
									<td> 
											<span class="font-weight-bold" style="font-size:15px"><?php echo $row['product_name'];?></span>
											<div class="product-qty"> 
												<span>Code: <?php echo $product_code[$j];?></span> 
												<span class="d-block">Quantity: <?php echo $product_qtys[$j];?></span> 
												<span>Remark: <?php echo $remarks[$j];?></span> 
											</div>
									</td>
									<td > <?php echo number_format($proudct_amounts[$j], 2);?> </td>
									<td > <?php echo number_format($proudct_amounts[$j] * $product_qtys[$j], 2);?></td>
                                </tr>
								<?php 
									$sum_qty += $product_qtys[$j];
									$sum += $proudct_amounts[$j] * $product_qtys[$j];
									$j++;
									$i++;
								}
								
								$sstper=$ordersData['sst_rate'];
								$total=$sum;
								if($sstper>0){
									$incsst = ($sstper / 100) * $total;
									$incsst=@number_format($incsst, 2);
									$incsst=@number_format($incsst, 2);
									$incsst=ceiling($incsst,0.05);
									$g_total=@number_format($total+$incsst, 2);
								} else { $g_total=$total;}
								?>
								
								
								<tr class="subtotla_td">
                                        <td colspan="3">
                                            <div class="text-right"> <span class="text-muted">Subtotal</span> </div>
                                        </td>
                                        <td>
                                            <div class="text-right"> <span><?php echo number_format($sum, 2);?></span> </div>
                                        </td>
                                    </tr>
									<?php if($sstper){?>
									<tr class="subtotla_td">
                                        <td colspan="3">
                                            <div class="text-right"> <span class="text-muted">Service fee <?php echo $sstper;?> %</span> </div>
                                        </td>
                                        <td>
                                            <div class="text-right"> <span><?php echo $incsst;?></span> </div>
                                        </td>
                                    </tr>
									<?php }?>
									<?php if($ordersData['deliver_tax_amount'] != ''){
										$deliver_tax_amount = $ordersData['deliver_tax_amount'];
									}else{
										$deliver_tax_amount = '0.00';
									}?>
                                    <tr class="subtotla_td">
                                        <td colspan="3">
                                            <div class="text-right"> <span class="text-muted">Delivery Tax</span> </div>
                                        </td>
                                        <td>
                                            <div class="text-right"> <span><?php echo number_format($deliver_tax_amount, 2);?></span> </div>
                                        </td>
                                    </tr>
									<?php
									$total_delivery_charge= '0.00';
									if($ordersData['order_extra_charge'] || $ordersData['special_delivery_amount'] || $ordersData['speed_delivery_amount'])
									{
										if($ordersData['special_delivery_amount']>0 && $ordersData['speed_delivery_amount']>0)
										{
											$total_delivery_charge=@number_format($ordersData['order_extra_charge'],2)."+ ".number_format($ordersData['special_delivery_amount'],2)."(Chiness Delivery)"."+ ".number_format($ordersData['speed_delivery_amount'],2)."(Speed Delivery)";
										}else if($ordersData['special_delivery_amount']>0){
											$total_delivery_charge=@number_format($ordersData['order_extra_charge'],2)."+ ".number_format($ordersData['special_delivery_amount'],2)."(Chiness Delivery)";
										}else if($ordersData['speed_delivery_amount']>0){
											$total_delivery_charge=@number_format($ordersData['order_extra_charge'],2)."+ ".number_format($ordersData['speed_delivery_amount'],2)."(Speed Delivery)";
										}
										else
										{
											$total_delivery_charge=@number_format($ordersData['order_extra_charge'],2);
										}
									}
									?>
                                    <tr class="subtotla_td">
                                        <td colspan="3">
                                            <div class="text-right"> <span class="text-muted">Delivery Charges</span> </div>
                                        </td>
                                        <td>
                                            <div class="text-right"> <span><?php echo $total_delivery_charge;?></span> </div>
                                        </td>
                                    </tr>
									<?php if($ordersData['membership_discount']){?>
									 <tr class="subtotla_td">
                                        <td colspan="3">
                                            <div class="text-right"> <span class="text-muted">Membership discount</span> </div>
                                        </td>
                                        <td>
                                            <div class="text-right"> <span><?php echo number_format($ordersData['membership_discount'], 2);?></span> </div>
                                        </td>
                                    </tr>
									<?php }?>
									<?php if($ordersData['coupon_discount']){?>
									<tr class="subtotla_td">
                                        <td colspan="3">
                                            <div class="text-right"> <span class="text-muted">Coupon discount</span> </div>
                                        </td>
                                        <td>
                                            <div class="text-right"> <span><?php echo number_format($ordersData['coupon_discount'], 2);?></span> </div>
                                        </td>
                                    </tr>
									<?php }?>
									
									<?php 
									
									$g_final=@number_format(($g_total+$ordersData['order_extra_charge']+$ordersData['deliver_tax_amount']+$ordersData['special_delivery_amount']+$ordersData['speed_delivery_amount'])-($ordersData['membership_discount']+$ordersData['coupon_discount']),2);?>
									
                                    <tr class="subtotla_td">
                                        <td colspan="3">
                                            <div class="text-right"> <span class="font-weight-bold">Grand Total</span> </div>
                                        </td>
                                        <td>
                                            <div class="text-right"> <span class="font-weight-bold"><?php echo $g_final;?></span> </div>
                                        </td>
                                    </tr>
									
									
									<?php if($ordersData['wallet_paid_amount']){?>
									<tr class="subtotla_td">
                                        <td colspan="3">
                                            <div class="text-right"> <span class="text-muted">Paid by wallet:</span> </div>
                                        </td>
                                        <td>
                                            <div class="text-right"> <span><?php echo number_format($ordersData['wallet_paid_amount'], 2);?></span> </div>
                                        </td>
                                    </tr>
									<?php }?>
									
									
									<?php 
									$g_total2=@number_format(($g_total+$ordersData['order_extra_charge']+$ordersData['deliver_tax_amount']+$ordersData['special_delivery_amount']+$ordersData['speed_delivery_amount'])-($ordersData['wallet_paid_amount']+$ordersData['membership_discount']+$ordersData['coupon_discount']), 2);
									?>
									
									<tr class="border-top border-bottom subtotla_td">
                                        <td colspan="3">
                                            <div class="text-right"> <span class="font-weight-bold">Balance Payment</span> </div>
                                        </td>
                                        <td>
                                            <div class="text-right"> <span class="font-weight-bold"><?php echo $g_total2;?></span> </div>
                                        </td>
                                    </tr>
		   
								
	</tbody>
                        </table>
                    </div>
                    
					
                    <p class="font-weight-bold mb-0 fnt13">Goods Sold Are Not Exchange!!<br/>
					Thanks for shopping with us!</p> <span class="fnt13">KooFamilies Team</span>
                </div>
                <div class="d-flex justify-content-between footer p-3"> <span>Need Help? Contact on <a href="https://chat.whatsapp.com/J4wcS4riADaBBrvY60c8f3" target="_blank">
<img src="https://www.koofamilies.com/images/whatapp.png" style="max-width:19px;">
</a></span> 
</div>
            </div>
        </div>
    </div>
</div>
                            </body>
                        </html>
						
<script>
//window.print();
</script>