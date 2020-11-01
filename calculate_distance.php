<?php
$distance=0;
// include("config.php");
$conn = mysqli_connect("localhost", "koofamil_B277", "rSFihHas];1P", "koofamil_B277");

if( isset( $_POST['from_lat'])) {
	  $merchant_id    = $_POST['merchant_id'];
	  $latitudeFrom    = $_POST['from_lat'];
    $longitudeFrom    = $_POST['from_long'];
    $latitudeTo        = $_POST['to_lat'];
    $longitudeTo    = $_POST['to_long'];
	
	// $url ="https://maps.googleapis.com/maps/api/distancematrix/json?origins=40.6655101,-73.89188969999998&destinations=41.6655101,-73.89188969999998&key=AIzaSyAqkgFdbQUhomdTY88R2OhkAKe57dnf9Kc";  
	 // $url ="https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$latitudeFrom.",".$longitudeFrom."&destinations=".$latitudeTo.",".$longitudeTo."&key=AIzaSyAqkgFdbQUhomdTY88R2OhkAKe57dnf9Kc";  
	  $url ="https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$latitudeFrom.",".$longitudeFrom."&destinations=".$latitudeTo.",".$longitudeTo."&key=AIzaSyDJaLaYhhq7poU-0_LTu5GOmhYR4b4D0d4";  
    // die;
	// Initialize a CURL session.   
	$ch = curl_init();  
	  
	// Return Page contents. 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	  
	//grab URL and pass it to the variable. 
	curl_setopt($ch, CURLOPT_URL, $url); 
	  
	$result = curl_exec($ch);
	$rd=json_decode($result,true);
	// print_R($rd);
	// die;  
	// if($rd[''])
		if($rd['status']=="OK")
		{
		   $m_distance=$rd['rows'][0]['elements'][0]['distance']['value'];
		   $distance=$m_distance/1000;
		}
	
    $unit="k";
    // Convert unit and return distance
    $unit = strtoupper($unit);
    if($unit == "K"){
         // $distance =round($miles * 1.609344, 2);
		 $distance=(int)$distance;
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
		 // print_R($plan_detail);
		 // die;
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