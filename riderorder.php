<?php 
include('config.php');
// error_reporting(-1);
if(empty($_GET['ms']))
{
	$sid=$_GET['sid'];
	$url="riderorder.php?ms=".md5(rand());

header("Location:$url");
exit();
}
if (empty($_SESSION["langfile"])) { $_SESSION["langfile"] = "english"; }
    require_once ("languages/".$_SESSION["langfile"].".php");
$query="select riders.name as rider_name,order_list.*,u.merchant_remark,u.google_map as merchant_map,u.name as merchant_name,u.mobile_number as merchant_mobile_number from order_list inner join 
users as u on u.id=order_list.merchant_id  left join riders on order_list.rider_id=riders.id   order by order_list.id desc limit 0,30";
$current_time = date('Y-m-d H:i:s');
function ceiling($number, $significance = 1)
{
	return ( is_numeric($number) && is_numeric($significance) ) ? (ceil(round($number/$significance))*$significance) : false;
}
?>  
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Latest Rider order</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="./css/font-awesome.min.css">  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
  <h2>Latest Rider Order</h2>
          
  <table class="table">
    <thead>
      <tr>
        <th>S.No</th>
        <th>Order id</th>
		<th>Rider info</th>
		<th>Rider name</th>
        
     <th>Order Remark</th>
         <th>Order Status</th>
        
			<th>Merchant Detail</th>
			
			<th>Merchant Remark</th>
			<th>DATE OF ORDER</th>
        <th>Merchant Address </th>
        <th>User Address </th>
      
		
		   
      </tr>
    </thead>
    <tbody>
	<?php 
     $qu=mysqli_query($conn,$query);
	 $i=1;
	 while($r=mysqli_fetch_array($qu))
	 {
		   $created =$row['created_on'];
		 $date=date_create($created);
		 $row=$r;
		        if($r['status'] == 0)
								{
									$sta =$language['pending'];
									$s_color="red";
									$n_status=1;
								}
                                else if($r['status'] == 1) 
								{
									
									$sta =$language['done_in_delivery'];
									$s_color="green";
									$n_status=4;
								}
								else if($r['status'] == 4 || $r['status']==5) 
								{
									$sta =$language['done_in_delivery'];
									$s_color="green";
								}
                                else 
								{
									$n_status=1;
									$sta =$language['accepted'];
									// $sta = "Accepted";
									$s_color="";
								}
		 $dteDiff  = date_diff($date, date_create($current_time));
                              $diff_day = $dteDiff->d;
                              if($diff_day != '0') $diff_day .= ' days ';
                              else $diff_day = '';
                              $diff_hour = $dteDiff->h;
                              if(intval($diff_hour) < 10) $diff_hour = '0'.$diff_hour.':'; else $diff_hour = $diff_hour.':';
                              $diff_minute = $dteDiff->i;
                              if($diff_minute < 10) $diff_minute = '0'.$diff_minute.':'; else $diff_minute = $diff_minute.':';
                              $diff_second = $dteDiff->s;
                              if($diff_second < 10) $diff_second = '0'.$diff_second;
                              $diff_time = $diff_day.'<br>'.$diff_hour.$diff_minute.$diff_second;	
	    if($r['merchant_map'])
		{
			$location_m=rawurlencode($r['merchant_map']);
			$m_map="http://maps.google.com/maps?q=".$location_m;
		}
		 if($r['location'])
		{
			$location_u=rawurlencode($r['location']);
			$u_map="http://maps.google.com/maps?q=".$location_u;
		}
	?>
      <tr>  
        <td><?php echo $i; ?></td>
        <td><?php echo $r['id']; ?></td>
		<td>
		<?php if($r['rider_info']==''){ ?>
		<span class="btn btn-primary assign_order" order_id="<?php echo $r['id']; ?>" style="font-size:16px;">Assign</span>
		<?php } else {   $r['rider_info'];}?>
		
		</td>
		<td><?php echo $r['rider_name']; ?></td>
		<td><?php echo $r['remark_extra']; ?></td>
		 <td><input type="button" next_status="<?php echo $n_status; ?>" style="background-color:<?php echo $s_color;?>" class= "status btn btn-primary" value="<?php  echo $sta;?>" status="<?php echo $row['status'];?>" data-invoce='<?php echo $row['invoice_no'];?>' data-id="<?php echo $row['id']; ?>"/>
	
</td>                             
		
		
		<td><?php echo $r['merchant_name']." / ".$r['merchant_mobile_number']; ?></td>
		<td><?php echo $r['merchant_remark']; ?></td>
		 <td><?php echo date_format($date,"m/d/Y h:i A");  ?>
                                <?php echo '<br>'; echo $new_time[1] ?>
                                <?php 
                                  if($row['status'] == 0){?>
                                    <p style="color: red;"><?php echo $diff_time; ?></p> <?php 
                                  }?>
                            </td>   
		
        <td><a href="<?php echo $m_map; ?>" target="_blank"><?php echo $r['merchant_map']; ?></a></td>
        <td><a href="<?php echo $u_map; ?>" target="_blank"><?php echo $r['location']; ?></a></td>
        



      </tr>
	 <?php $i++;} ?>   
      
    </tbody>
  </table>
</div>
<div class="modal fade" id="orderdetailmodel" role="dialog" style="margin-top:12%;"> 
							<div class="modal-dialog">
							<!-- Modal content-->		
							<div class="modal-content">	
							<div class="modal-header">	
							<button type="button" class="close" data-dismiss="modal">&times;</button>						
							<h4 class="modal-title">Order Detail <?php echo $job_assign; ?></h4>	
							</div>					
							<form id ="orderdetailform" method="POST">	
							<input type="hidden" id="access_order_id" name="access_order_id"/>
							<div class="modal-body" style="padding-bottom:0px;">
                             <div class="form-group">
								  <label for="usr">Access Key:</label>
								  <input type="password" name="r_code" class="form-control" id="usr">
								</div>
								<div class="form-group">
								  
								  <input type="submit" class="form-control btn btn-primary" name="access" value="Access">
								</div>

												
							</form>						
							</div>						
							</div>						
							</div>
	</div>  
<?php 
 if($_POST['access'] && $_POST['r_code'] && $_POST['access_order_id'])
	{
		$r_code=$_POST['r_code'];
		$o_id=$_POST['access_order_id'];
		$o_q=mysqli_query($conn,"select * from order_list where id='$o_id'");
		$o_data=mysqli_fetch_array($o_q);
		if($o_data['rider_info']=='')
		{
			$q="select * from riders where rider_code='$r_code'";
			$query=mysqli_query($conn,$q);
			$riderdata=mysqli_fetch_array($query);
			if($riderdata)
			{
				$post_date =Date("Y-m-d h:i");  
				$rider_mobile_no=$riderdata['rider_mobile_no'];
				$rider_id=$riderdata['id'];
				 $up="UPDATE `order_list` SET `rider_id` = '$rider_id',`rider_alert`='y',`rider_info`='$rider_mobile_no',`rider_alot_time`='$post_date' WHERE `order_list`.`id` ='$o_id'";
				
				mysqli_query($conn,$up);
				header("Location:https://www.koofamilies.com/riderorder.php");
			}	
		}
		else
		{
			header("Location:https://www.koofamilies.com/riderorder.php");
		}
	}
?>
<script>
function generatetokenno(length) {
   var result           = '';
   var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
   var charactersLength = characters.length;
   for ( var i = 0; i < length; i++ ) {
      result += characters.charAt(Math.floor(Math.random() * charactersLength));
   }
   return result;
}
$(document).ready(function(){
	$(".assign_order").click(function(e){
		  var s_id = $(this).attr('order_id');
		  $('#access_order_id').val(s_id);
		 $("#orderdetailmodel").modal("show"); 
	  });  
	 
	  
	  	    setInterval(function(){ 
				
					var s_token=generatetokenno(16);
						var r_url="https://www.koofamilies.com/riderorder.php?ms="+s_token;
					 window.location.replace(r_url);
			}, 
				60000);      
	   
});

</script>
</body>

</html>
