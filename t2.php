<?php
    $INSTANCE_ID = "17";  // TODO: Replace it with your gateway instance ID here
	$CLIENT_ID = "mayank.mangalgroup@gmail.com";  // TODO: Replace it with your Forever Green client ID here
	// $CLIENT_SECRET = "a16236d83f1b43f38310e3cd393293af";   // TODO: Replace it with your Forever Green client secret here
	$CLIENT_SECRET = "a16236d83f1b43f38310e3cd393293af";   // TODO: Replace it with your Forever Green client secret here   
	$INSTANCE_ID = "25";  // TODO: Replace it with your gateway instance ID here
	$CLIENT_ID = "woijoonchong@gmail.com";  // TODO: Replace it with your Forever Green client ID here
	$CLIENT_SECRET = "1df7f3075a7e490689e9bf1c469960a0";   // TODO: Replace it with your Forever Green client secret here
   $whatapp_group_name="欢乐园 &  Koo Delivery";    
    // $INSTANCE_ID = "25";  // TODO: Replace it with your gateway instance ID here
		// $CLIENT_ID = "woijoonchong@gmail.com";  // TODO: Replace it with your Forever Green client ID here
	// $CLIENT_SECRET = "1df7f3075a7e490689e9bf1c469960a0";   // TODO: Replace it with your Forever Green client secret here
   $whatapp_group_name="error group";
   $sms_msg="https://www.koofamilies.com/orderview.php?did=5326&vs=OTRX&oid=21345 C&M Foods Court,Koofamilies alert! Your order (532) has exceeded timeframe,please ACCEPT the order,";
   $sms_msg="test msg";
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