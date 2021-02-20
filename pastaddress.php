<?php
include("config.php");

extract($_POST);
if($already_login || $mobile_number)
{
	 $mobile_number="60".$mobile_number;
	  $q="SELECT  SQL_NO_CACHE  location,order_lat,order_lng,id from order_list where (user_id='$already_login' or user_mobile='$mobile_number') and location!='test' and location!='' and order_lat!='' and order_lng!='' order by id desc limit 0,5";
	$pq =mysqli_query($conn,$q);

	$total_rows=mysqli_num_rows($pq);
	$past_array=[];
	$i=0;  
	
	if($total_rows>0)
	{
		 while($r=mysqli_fetch_assoc($pq))
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