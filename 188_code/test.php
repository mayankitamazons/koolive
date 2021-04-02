<?php 
include('config.php');
 $arrange_count="select count(id) as total_record from arrange_system where user_id='1107' and page_type='p'";
						 $a_count=mysqli_fetch_assoc(mysqli_query($conn,$arrange_count));
					
						echo $acount=$a_count['total_record'];
?>