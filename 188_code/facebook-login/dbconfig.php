<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'koofamil_B277');    // DB username
define('DB_PASSWORD', 'rSFihHas];1P');    // DB password
define('DB_DATABASE', 'koofamil_B277');      // DB name
$connection = mysql_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD) or die( "Unable to connect");
$database = mysql_select_db(DB_DATABASE) or die( "Unable to select database");  
?>