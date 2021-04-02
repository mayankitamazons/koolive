<?php
include('config.php');
if(isset($_GET['language'])){
	$_SESSION["langfile"] = $_GET['language'];
} 
if (empty($_SESSION["langfile"])) { $_SESSION["langfile"] = "english"; }
    require_once ("languages/".$_SESSION["langfile"].".php");
extract($_POST);

if($sort_by)
{
if($sort_by=="sort_distance" && $type=="all")
{
	   $sql = "SELECT SQL_NO_CACHE  users.name, users.address,service.short_name,about.image,users.mobile_number,set_working_hr.*,users.order_extra_charge,users.delivery_plan,
            (6371 * ACOS ( COS ( RADIANS (".$_POST['latitude'].")) * COS ( RADIANS(users.latitude)) * COS(RADIANS(users.longitude) - RADIANS(".$_POST['longitude']."))
       + SIN(RADIANS(".$_POST['latitude'].")) * SIN(RADIANS(users.latitude)))) AS distance,users.not_working_text,users.not_working_text_chiness
      FROM users 
						   left JOIN service on users.service_id = service.id LEFT JOIN about on users.id=about.userid LEFT JOIN set_working_hr on users.id=set_working_hr.merchant_id   
						   WHERE users.user_roles = 2 and users.isLocked= 0 and users.show_merchant=1 and users.latitude!='' and users.longitude!='' group by users.id order by distance asc";   


} else if($sort_by=="sort_distance" && $type=="popular")
{
	 $sql = "SELECT SQL_NO_CACHE  users.name, users.address,service.short_name,about.image,users.mobile_number,set_working_hr.*,users.order_extra_charge,users.delivery_plan,
            (6371 * ACOS ( COS ( RADIANS (".$_POST['latitude'].")) * COS ( RADIANS(users.latitude)) * COS(RADIANS(users.longitude) - RADIANS(".$_POST['longitude']."))
       + SIN(RADIANS(".$_POST['latitude'].")) * SIN(RADIANS(users.latitude)))) AS distance,users.not_working_text,users.not_working_text_chiness
      FROM users 
						   left JOIN service on users.service_id = service.id LEFT JOIN about on users.id=about.userid LEFT JOIN set_working_hr on users.id=set_working_hr.merchant_id   
						   WHERE users.user_roles = 2 and users.isLocked= 0 and users.popular_restro=1 and users.latitude!='' and users.longitude!=''  group by users.id order by distance asc";   
} else if($sort_by=="sort_name" && $type=="sort_name")
{
	   $sql = "SELECT SQL_NO_CACHE  users.name, users.address,service.short_name,about.image,users.mobile_number,set_working_hr.*,users.order_extra_charge,users.delivery_plan,users.not_working_text,users.not_working_text_chiness FROM users 
						   left JOIN service on users.service_id = service.id LEFT JOIN about on users.id=about.userid LEFT JOIN set_working_hr on users.id=set_working_hr.merchant_id   
						   WHERE users.user_roles = 2 and users.isLocked= 0 and users.show_merchant=1 group by users.id order by users.name asc";   
                        // $result = mysqli_query($conn, $sql);
}


    $result = mysqli_query($conn, $sql);
    $count= mysqli_num_rows($result);
	
	if($count>0)
	{
		if($type=="popular")
		{
			while($rd=mysqli_fetch_assoc($result)){
                     // print_r($rd);   
					$working="y";
					 if($rd['start_day']){
						 $starday=$time_detail['starday']=$rd['start_day'];
						 $endday=$time_detail['endday']=$rd['end_day'];
						$starttime=$time_detail['starttime']=$rd['start_time'];
						$endttime=$time_detail['endttime']=$rd['end_time'];
						// print_r($time_detail);
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
					$currenttime=date("H:i");
					$n=date("N");
						if(($currenttime >$starttime && $currenttime < $endttime) && ($s_day<=$n && $e_day>=$n)){
						  $working="y";
					  }
					  else
					  {
						  $working="n";
					  }
						 // echo $working=checktimestatus($time_detail);
						 // $work_str="Working Time :".$rd['start_day']." ".$rd['start_time']." to "." ".$rd['end_day']." ".$rd['end_time'];
					 }   
					 if($working=="y")
					 {
                    ?>
                    <div class="owl-item active" style="width: 290px; margin-right: 20px;">
                    <div class="item">
			        <div class="strip">
			            <figure>
			                <!-- <span class="ribbon off">-30%</span> -->
							<?php if($rd['image']==""){ ?>
							<img src="images/logo_new.jpg" data-src="images/logo_new.jpg"  alt=""> <?php
							}else{ ?> <img src="<?php echo $image_cdn; ?>about_images/<?php echo $rd['image']?>" data-src="<?php echo $image_cdn; ?>about_images/<?php echo $rd['image']?>" alt=""> <?php }?>
			                
			                <a href="view_merchant.php?sid=<?php echo $rd['mobile_number'];?>" class="strip_info">
			                    <!-- <small>Pizza</small> -->
			                    <div class="item_title">
                                      <h3><?php echo $rd['name']?></h3>
									<small style="color:red;"><?php echo number_format($rd['distance'],2)." KM Away"; ?></small>
			                    </div>
			                </a>
			            </figure>
			            <ul>
                           <li><a href="view_merchant.php?sid=<?php echo $rd['mobile_number'];?>"><span class="loc_open">Now Open</span></a></li>
			            </ul>
			        </div>
                </div>
				</div>
					 <?php }
                }   
		}
		else if($type="all")
		{
			$count= mysqli_num_rows($result);
            $rd = mysqli_fetch_all($result);  
			   
			$condition = round($count/2);
			if($count>0){ 
			?>
			<div class="col-12">
					<div class="main_title version_2">
						<span><em></em></span>
						<div class="row">
							  <div class="col-md-8"><h2><?php echo $language['near_by_shop_2']; ?> (300)</h2></div>
							
							  <div style="margin: 20px 0 0 0;float: right;" class="col-md-2">
							  <select class="form-control all_restro_sort">
							  
								<option value="sort_name" <?php if($sort_by=="sort_name"){ echo "selected";} ?>>Sort By Name</option>
								  <option value="sort_distance" <?php if($sort_by!="sort_name"){ echo "selected";} ?>>Search nearby</option>
							  </select>
							</div>
							  <div style="margin: 20px 0 0 0;float: left;" class="col-md-2">
							<a href="merchant_find.php">View All</a>
							</div>
						</div>
						
					
					</div>
				</div>
				
				<div class="col-md-6">
					<div class="list_home">
						<ul>
                        <?php
						$langfile=$_SESSION['langfile'];
						//echo $condition;
                        for($i=0;$i<$condition;$i++){
							   
								if($rd[$i][7] && $rd[$i][8])
								{
									$s_time=date("g:i a", strtotime($rd[$i][9]));
									$e_time=date("g:i a", strtotime($rd[$i][10]));
									$work_str=$rd[$i][7]." ".$s_time." to ".$rd[$i][8]." ".$e_time;   
									 $starday=$time_detail['starday']=$rd[$i][7];
									$endday=$time_detail['endday']=$rd[$i][8];
									$starttime=$time_detail['starttime']=$rd[$i][9];
									$endttime=$time_detail['endttime']=$rd[$i][10];
									// print_r($time_detail);
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
								$currenttime=date("H:i");
								$n=date("N");
									if(($currenttime >$starttime && $currenttime < $endttime) && ($s_day<=$n && $e_day>=$n)){
									 $working="y";
								  }
								  else
								  {
									 $working="n";
								  }
								}
								else
								{
									$work_str='';
									$working="n";
								}
								if($langfile=="chinese" && $rd[$i][16]!='')
								{
									$work_str.="</br>".$rd[$i][16];
								}
								else if($rd[$i][15])
								{
									$work_str.="</br>".$rd[$i][15];
								}
							  $distance=$rd[$i][14]['distance'];
                        ?>  <li>
								<a href="view_merchant.php?sid=<?php echo $rd[$i][4];?>">
									<figure class="<?php if($working=="n"){echo "shop_close";} ?>">
									<?php if($rd[$i][3]==""){ ?>   
										<img src="images/logo_new.jpg" data-src="images/logo_new.jpg" alt="" class="lazy">
									<?php } else {  ?><img src="<?php echo $image_cdn; ?>about_images/<?php echo $rd[$i][3]?>" data-src="<?php echo $image_cdn; ?>about_images/<?php echo $rd[$i][3]?>" alt="" class="lazy"><?php } ?>
									</figure>
									<!-- <div class="score"><strong>9.5</strong></div> --
									<!--em>Italian</em!-->
									<h3><?php echo $rd[$i][0];?></h3>
								<?php if($sort_by!="sort_name"){ ?>
											<!--small style="color:black;"><?php  print_r(number_format($rd[$i][14],2)); echo " KM Away </br>"; ?></small!-->
									<?php } ?>
									<!--small><?php echo $rd[$i][2]?></small!-->
									 <small style='color:red;'><?php if($work_str==""){
										
									}else{ echo $work_str;}?></small>
									<?php if($rd[$i][12] || $rd[$i][13]){ if($rd[$i][13]){ $d_str="Flexible Delivery";} else { $d_str="MYR ".number_format($rd[$i][12],2);} } else { $d_str="Free Delivery";} ?>
									<?php if($d_str){ echo "<br><img src='img/motor.jpg' style='width:36px;'/> ".$d_str;} ?>
									
                                    </a>
                                </li>
								
                         <?php   }?>   
							
							
						</ul>
					</div>
				</div>       
				<div class="col-md-6">
					<div class="list_home">
						<ul>
                        <?php
                   
						//echo $condition;
                         for($i=$condition;$i<$count;$i++){
							   
								if($rd[$i][7] && $rd[$i][8])
								{
									$s_time=date("g:i a", strtotime($rd[$i][9]));
									$e_time=date("g:i a", strtotime($rd[$i][10]));
									$work_str=$rd[$i][7]." ".$s_time." to ".$rd[$i][8]." ".$e_time;   
									 $starday=$time_detail['starday']=$rd[$i][7];
									$endday=$time_detail['endday']=$rd[$i][8];
									$starttime=$time_detail['starttime']=$rd[$i][9];
									$endttime=$time_detail['endttime']=$rd[$i][10];
									// print_r($time_detail);
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
								$currenttime=date("H:i");
								$n=date("N");
									if(($currenttime >$starttime && $currenttime < $endttime) && ($s_day<=$n && $e_day>=$n)){
									 $working="y";
								  }
								  else
								  {
									 $working="n";
								  }
								}
								else
								{
									$work_str='';
									$working="n";
								}
								if($langfile=="chinese" && $rd[$i][16]!='')
								{
									$work_str.="</br>".$rd[$i][16];
								}
								else if($rd[$i][15])
								{
									$work_str.="</br>".$rd[$i][15];
								}
							  $distance=$rd[$i][14]['distance'];
                        ?>  <li>
								<a href="view_merchant.php?sid=<?php echo $rd[$i][4];?>">
									<figure class="<?php if($working=="n"){echo "shop_close";} ?>">
									<?php if($rd[$i][3]==""){ ?>   
										<img src="images/logo_new.jpg" data-src="images/logo_new.jpg" alt="" class="lazy">
									<?php } else {  ?><img src="<?php echo $image_cdn; ?>about_images/<?php echo $rd[$i][3]?>" data-src="<?php echo $image_cdn; ?>about_images/<?php echo $rd[$i][3]?>" alt="" class="lazy"><?php } ?>
									</figure>
									<!-- <div class="score"><strong>9.5</strong></div> --
									<!--em>Italian</em!-->
									<h3><?php echo $rd[$i][0];?></h3>
									<?php if($sort_by!="sort_name"){ ?>
											<!--small style="color:black;"><?php  print_r(number_format($rd[$i][14],2)); echo " KM Away </br>"; ?></small!-->
									<?php } ?>
									<!--small><?php echo $rd[$i][2]?></small!-->
									 <small style='color:red;'><?php if($work_str==""){
										
									}else{ echo $work_str;}?></small>
									<?php if($rd[$i][12] || $rd[$i][13]){ if($rd[$i][13]){ $d_str="Flexible Delivery";} else { $d_str="MYR ".number_format($rd[$i][12],2);} } else { $d_str="Free Delivery";} ?>
									<?php if($d_str){ echo "<br><img src='img/motor.jpg' style='width:36px;'/> ".$d_str;} ?>
									
                                    </a>
                                </li>
								
                         <?php   }?>   
							
							
						</ul>
					</div>
				</div>  
			<?php }   
		}
	}
	
}

?>
<script>
$( ".all_restro_sort" ).change(function() {
		 var sort_by=this.value;
		if(sort_by=="sort_name")
		 {   
			// location.reload(true);	
			var sort_by="sort_name";
			$.ajax({
				  type: "POST",
				  url: "r_list.php",
				  data: {sort_by:sort_by,type:"sort_name"},
				  cache: false,
				  success: function(data) {
					 $('.all_merchant_list').html(data);     
				  }
				  });
		 }     
		 else  if(sort_by=="sort_distance")
		 {
			if ("geolocation" in navigator){ //check geolocation available 
			navigator.geolocation.watchPosition(function(position) {

			
			},

			function(error) {

			if (error.code == error.PERMISSION_DENIED)

			{

			  $('#location_model').modal('show');


			}

			});
		//try to get user current location using getCurrentPosition() method
		navigator.geolocation.getCurrentPosition(function(position){ 
			var latitude=position.coords.latitude;
			var longitude=position.coords.longitude;

			if(latitude && longitude)
			{
				$.ajax({
				  type: "POST",
				  url: "r_list.php",
				  data: {latitude:latitude, longitude:longitude,sort_by:sort_by,type:"all"},
				  cache: false,
				  success: function(data) {
					 $('.all_merchant_list').html(data);     
				  }
				  });	
			}
			else
			{
				$('#location_model').modal('show');

			}
		});
			}else{
				console.log("Browser doesn't support geolocation!");
			}
		 }
		});
</script>