<?php
include("config.php");
$q="SELECT  SQL_NO_CACHE  location,order_lat,order_lng,id from order_list where   location!='test' and location!='-' and location!='' and order_lat='' and id>25541  order by id desc limit 0,1000";
$pq =mysqli_query($conn,$q);
$u=0;
while($r=mysqli_fetch_assoc($pq))
{
	extract($r);
	$location=$r['location'];
	if($order_lat =='' && $order_lng ==''){
        // Get lat long from google
        $latlong    =   get_lat_long($location); // create a function with the name "get_lat_long" given as below
		// print_R($latlong);
		// die;
        $map        =   explode(',' ,$latlong);
        $mapLat         =   $map[0];
        $mapLong    =   $map[1];   
		if($mapLat && $mapLong)
		{
			 $q2="update order_list set order_lat='$mapLat',order_lng='$mapLong' where id='$id'";
			$update=mysqli_query($conn,$q2);
			// die;    
			if($update)
			$u++;
		}
	echo "Record updated ".$u."</br>";	
  }
  


// function to get  the address
// die;
}
echo "Total updated ",$u;
function get_lat_long($address){
	$ch = curl_init();  
	
    $address = str_replace(" ", "+", $address);
	$url="https://maps.google.com/maps/api/geocode/json?address=$address&sensor=false&key=AIzaSyDJaLaYhhq7poU-0_LTu5GOmhYR4b4D0d4";
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	  
	//grab URL and pass it to the variable. 
	curl_setopt($ch, CURLOPT_URL, $url); 
	  
	$result = curl_exec($ch);
	$json=json_decode($result,true);
	// print_R($json);
	// die;
	if($json['status']=="OK")
	{
	 $lat=$json['results'][0]['geometry']['location']['lat'];
	 $lng=$json['results'][0]['geometry']['location']['lng'];
	 if($lat && $lng)
	 return $lat.','.$lng;
	 else
	 return;
	}
	else
	{
		return;
	}
	
   
}
?>