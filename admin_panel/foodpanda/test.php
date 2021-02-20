<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
 error_reporting(E_ALL);
 // $command = escapeshellcmd('enter_rest_link_get_everything.py https://www.foodpanda.my/restaurant/q8pd/xing-fu-tang-sutera');
 $command = escapeshellcmd('allcityname.py');
    $output = shell_exec($command);
    echo $output;
?>