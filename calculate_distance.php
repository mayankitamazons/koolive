<?php
$distance=0;
include("config.php");
if( isset( $_POST['from_lat'])) {
	  $merchant_id    = $_POST['merchant_id'];
	  $latitudeFrom    = $_POST['from_lat'];
    $longitudeFrom    = $_POST['from_long'];
    $latitudeTo        = $_POST['to_lat'];
    $longitudeTo    = $_POST['to_long'];
	
	// Calculate distance between latitude and longitude
    $theta    = $longitudeFrom - $longitudeTo;
    $dist    = sin(deg2rad($latitudeFrom)) * sin(deg2rad($latitudeTo)) +  cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) * cos(deg2rad($theta));
    $dist    = acos($dist);
    $dist    = rad2deg($dist);
    $miles    = $dist * 60 * 1.1515;
    $unit="k";
    // Convert unit and return distance
    $unit = strtoupper($unit);
    if($unit == "K"){
         $distance =round($miles * 1.609344, 2);
		 $distance=(int) $distance;
		 $d['distance']=$distance;
		 $merchant_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT delivery_plan,order_extra_charge FROM users WHERE id='$merchant_id'"));
		 // print_R($merchant_data);
		 // die;
		 if($merchant_data['delivery_plan'])
		 {
			$plan_detail = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM delivery_plan WHERE merchant_id='$merchant_id' and status='y' and $distance BETWEEN min_distance AND max_distance;"));
		 } else if($merchant_data['order_extra_charge'])
		 {
			 $plan_detail['charge']=$merchant_data['order_extra_charge'];
		 }
		 else
		 {
			$plan_detail['charge']=0; 
		 }
		 if($plan_detail)
		 $d['delivery_charge']=$plan_detail['charge']; 
		else
			$d['delivery_charge']=0; 
		 $res=array('status'=>true,'data'=>$d);  
    }
	else
	{
		$res=array('status'=>false,'data'=>'');
	}
	// $item = array('distance'=>$distance);
	// echo json_encode($item);
	// die;
}
else
{
	$res=array('status'=>false,'data'=>'');
}
echo json_encode($res);
die;	
?>