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
            'include_player_ids' => array("0a97b787-578f-4c26-98f1-8bcc04087a7f","31102769-15f8-4ce7-a434-9d99185c8e36","f14d01a2-4f5b-4f45-8c1e-c85ac870b254",
			"f179f475-05eb-45c5-a2ac-734492de111d","b1c1ac94-d9b5-4951-9f54-bb8e552ded1b","85fbe8b0-b5cd-4f2e-bd8d-7674f9d9aee0",
			"a5100ce4-2f2b-4213-b7dc-664169560899","cdafa111-8ae5-438f-b623-81c1c82e59eb","057d29dd-2602-406b-a401-eb0f6bc89419","7df9c2d2-6f57-4736-9f9b-260edbfe08df"),
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
