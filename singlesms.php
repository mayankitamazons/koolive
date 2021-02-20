<?php
$INSTANCE_ID = "17";  // TODO: Replace it with your gateway instance ID here
	$CLIENT_ID = "click4mayank@gmail.com";  // TODO: Replace it with your Forever Green client ID here
	$CLIENT_SECRET = "6a64ba92889e4c34a4f491904ed3035d";   // TODO: Replace it with your Forever Green client secret here

	$postData = array(
	  'number' =>'919649171307',  // TODO: Specify the recipient's number here. NOT the gateway number
	  'message' =>"Welcome msg"
	);

	$headers = array(
	  'Content-Type: application/json',
	  'X-WM-CLIENT-ID: '.$CLIENT_ID,
	  'X-WM-CLIENT-SECRET: '.$CLIENT_SECRET
	);

	$url = 'http://api.whatsmate.net/v3/whatsapp/single/text/message/' . $INSTANCE_ID;
	$ch = curl_init($url);

	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));

	$response = curl_exec($ch);

	echo "Response: ".$response;
	curl_close($ch);
?>