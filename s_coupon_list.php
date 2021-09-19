<?php
include("config.php");

extract($_POST);
if($already_login || $mobile_number)
{
	 $mobile_number="60".$mobile_number;
	
	 $cq="select coupon.* from coupon_specific_allot inner join coupon on coupon_specific_allot.coupon_id=coupon.id where coupon_specific_allot.user_mobile_no='$mobile_number'";
	$special_coupon=mysqli_query($conn,$cq);
	$special_coupon_count = mysqli_num_rows($special_coupon);
	$total_rows=mysqli_num_rows($pq);
	$past_array=[];
	$i=0;  
	while($r=mysqli_fetch_assoc($special_coupon))
	{
		extract($r);
		$total_min_price=$r['total_min_price'];
					$total_max_price=$r['total_max_price'];
					if($total_min_price==0 && $total_max_price>0)
					$text="Coupon is Valid up to RM ".$total_max_price;   
					if($total_min_price>0 && $total_max_price>0)
					$text="Coupon is Valid Only Order Between RM ".$total_min_price." to RM ".$total_max_price;
		$coupon_id=$r['id'];
		$total_user_array = mysqli_fetch_assoc(mysqli_query($conn, "SELECT count(id) as total_record FROM `order_list` WHERE `user_mobile` = '$mobile_number' and `coupon_id` = '$coupon_id'"));
		$per_user_count=$r['per_user_count'];
		$total_use=$total_user_array['total_record'];
		if(($per_user_count>$total_use)){		
	?>
	<div class="row">
<div class="col-sm-8 coupon_label" style="padding: 4%;border-bottom: 1px solid;"  coupon_code="<?php echo $r['coupon_code']; ?>">
			<?php echo $r['coupon_code'];
			 echo "</br>";
			 if($type=="fix")
			 {
				 echo "Rm ". number_format($r['discount'],2)." off";
			 } else if($type=="per")
			 {
				echo number_format($r['discount'],2)." % off";     
			 }
			 echo "</br>";
			 ?>
			 <span style="color:red">(<?php echo $text; ?>)</span>
			 <?php 
			 
			?>
			
			
		</div>    
  <div class="col-sm-4">
    <span class="btn btn btn-primary coupon_redeem" min_amount="<?php echo $r['total_min_price']; ?>" max_amount="<?php echo $r['total_max_price']; ?>" style="float:right;" coupon_code="<?php echo $r['coupon_code']; ?>" >Redeem</span>
  </div>
 
</div>
		  
	<?php 
		}	 
	}
		
	
}  
?>