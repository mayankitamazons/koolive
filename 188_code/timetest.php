<?php
     include('config.php');
      function checktimestatus($time_detail)
	  {
	      print_r($time_detail);
		extract($time_detail);
		switch ($starday) {
			case "Monday":
				$s_day=1;
				break;
			case "Tuesday":
				$s_day=2;
				break;
			case "Wednesday":
				$s_day=3;
				break;
			case "Thursday":
				$s_day=4;
				break;
			case "Friday":
				$s_day=5;
				break;
			case "Saturday":
				$s_day=6;
				break;
			default:
				$s_day=7;
		}
		switch ($endday) {
			case "Monday":
				$e_day=1;
				break;
			case "Tuesday":
				$e_day=2;
				break;
			case "Wednesday":
				$e_day=3;
				break;
			case "Thursday":
				$e_day=4;
				break;
			case "Friday":
				$e_day=5;
				break;
			case "Saturday":
				$e_day=6;
				break;
			default:
				$e_day=7;
		} 
		echo "Start date".$s_day;
		echo "</br> End Date".$e_day;
	  echo "</br>";	
     echo 	"Current time".$currenttime=date("H:i");
		echo "</br>Today date".$n=date("N");
	
		   if(($currenttime >$starttime && $currenttime < $endttime) && ($s_day<=$n && $e_day>=$n)){
			  $shop_close_status="y";
		  }
		  else
		  {
			  $shop_close_status="n";
		  }
		  echo $shop_close_status;
		  die;
		return $shop_close_status;
	  }		
	
		$t['starday']="Monday";
		$t['endday']="Sunday";
		$t['starttime']="07:00";
		$t['endttime']="17:00";
		$status=checktimestatus($t);
?>