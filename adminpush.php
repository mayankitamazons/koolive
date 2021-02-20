<?php

class Adminpush {
    function sendMessage($re){
		global $site_url;
      extract($re);
        $content = array(
            "en" =>$message
            );
        
        $fields = array(
            'app_id' => "57f21ad6-a531-4cb6-9ecd-08fe4dd3b4f5",
            // 'include_player_ids' => array("0a97b787-578f-4c26-98f1-8bcc04087a7f","f9720947-9c4b-4440-ba1b-e9e8132dc25a"),
            'include_player_ids' => array("b5753265-4e15-44f2-8635-343cd07ac233","cb8ac864-e8c0-4e71-853b-e978069944d7","b6cd93a4-4c32-460d-a851-1fd6481e4596","d30e0bdb-76a3-443d-8244-1361f5e9a521","63f297e6-eeca-494d-8818-9ae1ed35f604","9a1f5f8d-b918-4162-85fa-16052078c1a8"),
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
