<?php
$INSTANCE_ID = "17";  // TODO: Replace it with your gateway instance ID here
	$CLIENT_ID = "click4mayank@gmail.com";  // TODO: Replace it with your Forever Green client ID here
	$CLIENT_SECRET = "6a64ba92889e4c34a4f491904ed3035d";   // TODO: Replace it with your Forever Green client secret here
   $whatapp_group_name="marketer group";    
   // $whatapp_group_name="error group";
   $sms_msg="https://www.koofamilies.com/orderview.php?did=5326&vs=OTRX&oid=21345 C&M Foods Court,Koofamilies alert! Your order (532) has exceeded timeframe,please ACCEPT the order,";
	$postData = array(
	  'group_admin' => '60123115670',  // TODO: Specify the WhatsApp number of the group creator, including the country code
	  'group_name' => $whatapp_group_name,    // TODO: Specify the name of the group
	  'message' => $sms_msg  // TODO: Specify the content of your message
	);

	$headers = array(
	  'Content-Type: application/json',
	  'X-WM-CLIENT-ID: '.$CLIENT_ID,
	  'X-WM-CLIENT-SECRET: '.$CLIENT_SECRET
	);

	$url = 'http://api.whatsmate.net/v3/whatsapp/group/text/message/' . $INSTANCE_ID;
	$ch = curl_init($url);

	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));

	$response = curl_exec($ch);

	echo "Response: ".$response;   
?>