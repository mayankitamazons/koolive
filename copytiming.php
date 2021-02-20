<?php
   include("config.php");
   if(isset($_POST['submit']))
   {
	  
	   if($_POST['main_merchant'] && $_POST['child_merchant'])
	   {
		  
		   // catgry copy 
		     extract($_POST);
			 
			$m_id=$_POST['main_merchant'];
			$c_id=$_POST['child_merchant'];
		
			
				$query = mysqli_query($conn,"select * from timings  where merchant_id='$m_id'");
				while ($row=mysqli_fetch_assoc($query)){
					$cat_id=$row['id'];
					
					$day=$row['day'];
					$start_time=$row['start_time'];
					$end_time=$row['end_time'];
					
					mysqli_query($conn, "INSERT INTO  timings SET day='$day',start_time='$start_time',merchant_id='$c_id',end_time='$end_time'");
		
				}
		  
			// arrange system copy 
			
			echo "Timing Copy Successfully";
			
	   }
   } 
?>
<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
  <h2>Copy Timing From One Merchant to other</h2>
  <form method="post" action=''>
    <div class="form-group">
      <label for="email">Main Merchant:</label>
      <input type="text" class="form-control" name="main_merchant" placeholder="Enter Merchant User id">
    </div>
    <div class="form-group">
      <label for="pwd">Other Merchant:</label>
     <input type="text" class="form-control" name="child_merchant" placeholder="Enter Merchant User id">
    </div>  
    	
    <small><b>Note : That tool copy Timing</b></small>
    <input type="submit" class="btn btn-block btn-primary" name="submit" value="Update Details">
  </form>
</div>

</body> 
</html>