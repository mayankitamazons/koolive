<?php 
include ("config.php");
if($_REQUEST['type'] == 'supportlink'){
	$response = array('Url'=>$helpLink);
	echo json_encode($response);	
	exit;
}

?>