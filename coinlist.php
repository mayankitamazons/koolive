<?php
include("config.php");

extract($_POST);
if($already_login || $mobile_number)
{
	 $mobile_number="60".$mobile_number;
	  $cq="select coupon.* from coupon_specific_allot inner join coupon on coupon_specific_allot.coupon_id=coupon.id where coupon_specific_allot.user_mobile_no='$mobile_number'";
	$special_coupon=mysqli_query($conn,$cq);
	$special_coupon_count = mysqli_num_rows($special_coupon);
	
	$past_array=[];
	$i=0;  
	
	if($total_rows>0)
	{
		 while($r=mysqli_fetch_assoc($special_coupon))
		 {
			 extract($r);
			 
			 if($order_lat && $order_lng && $location)
			 {
				 if (in_array($r['location'], $past_array))
				 {
				 }
				 else {
				 ?>
			  <div class="col-md-4 address_select" location="<?php echo $r['location']; ?>" order_lat="<?php echo $r['order_lat']; ?>" order_lng="<?php echo $r['order_lng']; ?>">
			  <?php echo $r['location']; ?>
			  </div>
			 <?php 
			 
				$past_array[$i]=$r['location'];
				$i++;
				 }
				 
			 }
		 
		 }
		
	}
}
?>