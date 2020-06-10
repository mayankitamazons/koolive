<?php

class Adminonetest {
    function sendMessage($re){
		global $site_url;
      extract($re);
        $content = array(
            "en" =>$message
            );
        
        $fields = array(
            'app_id' => "57f21ad6-a531-4cb6-9ecd-08fe4dd3b4f5",
            'include_player_ids' => array($push_id),
            'data' => array("foo" => "bar"),
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
        
        return $response;
    }
 


}
?>
