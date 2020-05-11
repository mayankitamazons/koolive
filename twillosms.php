<?php

// Update the path below to your autoload.php,
// see https://getcomposer.org/doc/01-basic-usage.md
$to="+916949171307";
$sms_msg="test sms";
require __DIR__ . '/twilio-php-master/src/Twilio/autoload.php';

		use Twilio\Rest\Client;

		// Find your Account Sid and Auth Token at twilio.com/console
		// DANGER! This is insecure. See http://twil.io/secure
		$sid    = "ACd2d7bbc9e20236ef84e8292cef0614ad";
		$token  = "61eb0a820833368ecb6be77dd5784744";
		$twilio = new Client($sid, $token);

		$message = $twilio->messages
						  ->create("whatsapp:".$to, // to
								   [
									   "from" => "whatsapp:+14155238886",
									   "body" =>$sms_msg
								   ]
						  );

print($message);
print($message->sid);
