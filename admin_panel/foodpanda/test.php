<?php
	echo '=======================allcityname================<br/>';
    $command = escapeshellcmd('python3 allcityname.py');
    $output = shell_exec($command);
    echo $output;
	
	echo '<br/>=======================select_city_all_restaurants================<br/>';
	$command = escapeshellcmd('python3 select_city_all_restaurants.py kuala-lumpur');
    $output = shell_exec($command);
    echo $output;
	
	
	echo '<br/>======================enter_rest_link_get_everything =================<br/>';
	$command = escapeshellcmd('python3 enter_rest_link_get_everything.py');
    $output = shell_exec($command);
    echo $output;
	
	
?>