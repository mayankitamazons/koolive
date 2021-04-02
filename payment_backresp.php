<?php 
include_once ('IPay88.class.php');
$ipay88 = new IPay88('M31571');

$response = $ipay88->getResponse();
if($response['status'] == 1){
	echo "RECEIVEOK";
}

?>