<?php
$s="https://www.smss360.com/api/sendsms.php?email=cwoijoon@gmail.com&key=fc984ae598c61e3d8a86b1faee7357c7&recipient=60123&message=test%20001&referenceID=ba65b9w12cc1fge";
$ch = curl_init();  
	  
	// Return Page contents. 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	  
	//grab URL and pass it to the variable. 
	curl_setopt($ch, CURLOPT_URL, $s); 
	  
	$result = curl_exec($ch);
	print_R($result);
?>