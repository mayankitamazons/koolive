<?php 
include("config.php");
$User = mysqli_query($conn, "SELECT * FROM `users` ORDER BY `users`.`mobile_number` ASC");
while($rw = mysqli_fetch_array($User)) {
$ID = $rw['id'] ; 
$Name = $rw['name'] ; 

$url = "https://api.cometondemand.net/api/v2/createUser";
$post_fields = "UID=$ID&name=$Name";
//$headers = ['api-key' => '52013x45cf53ef29068b0e37d03a026248298b'];

$make_call = callAPI('POST', "$url", json_encode($post_fields));
$response = json_decode($make_call, true);
print_r($response) ;
echo $response ;
}



function callAPI($method, $url, $data){
   $curl = curl_init();

   switch ($method){
      case "POST":
         curl_setopt($curl, CURLOPT_POST, 1);
         if ($data)
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
         break;
      case "PUT":
         curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
         if ($data)
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);			 					
         break;
      default:
         if ($data)
            $url = sprintf("%s?%s", $url, http_build_query($data));
   }

   // OPTIONS:
   curl_setopt($curl, CURLOPT_URL, $url);
   curl_setopt($curl, CURLOPT_HTTPHEADER, array(
      'APIKEY: 52013x45cf53ef29068b0e37d03a026248298b',
      'Content-Type: application/json',
   ));
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

   // EXECUTE:
   $result = curl_exec($curl);
   if(!$result){die("Connection Failure");}
   curl_close($curl);
   return $result;
}
?>


