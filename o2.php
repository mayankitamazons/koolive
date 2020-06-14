<?php


    function sendMessage($re){
		global $site_url;
      extract($re);
        $content = array(
            "en" =>$message
            );
        
        $fields = array(
            'app_id' => "2d16476c-b25d-4de8-8ed8-19fd2b0767c6",
            'include_player_ids' => "all",
            'data' => array("type" => "coupon"),
            'contents' => $content,
			'url'=>$redirectURL
        );
        
        $fields = json_encode($fields);
        // print("\nJSON sent:\n");
        // print($fields);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);
		print_R($response);
        return $response;
    }
	$re['message']="Welcome";
	$re['redirectURL']="";
sendMessage($re); 



?>
