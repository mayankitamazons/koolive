<?php 
include('config.php');
$shopclose=[];
function checktimestatusnew($time_detail)
	{ 
			global $shopclose;
			extract($time_detail);
// 			 echo "day=$day n=$n";
// 		 	echo "ctime:".date("H:i");
			$day=strtolower($day);
			$currenttime=date("H:i");
			$n=strtolower(date("l"));
			if(($currenttime >$starttime && $currenttime < $endttime) && ($day==$n)){
				  //$shop_close_status="y";
				   array_push($shopclose,"y");
			}
			else
			{ 
			  //$shop_close_status="n";
			  array_push($shopclose,"n");
			}
			return $shopclose;//$shop_close_status;
		}
if(isset($_GET['language'])){
	$_SESSION["langfile"] = $_GET['language'];
} 
if(empty($_SESSION["langfile"])) { $_SESSION["langfile"] = "english"; }
require_once ("languages/".$_SESSION["langfile"].".php");

$sort_by = 'sort_name';
$type = 'sort_name';
$page = 1;
$per_page = 20;
$template = 'home';
$langfile=$_SESSION['langfile'];
extract($_POST);
$offset = ($page - 1) * $per_page;
$LocaSt = $_SESSION['locationsort'];
if($LocaSt){
$LOCSQL = "and users.city='$LocaSt'" ;
}
	
if(isset($sort_by) && $sort_by=="sort_distance" && $type=="all"){
	$sql = "SELECT   users.name, users.address,service.short_name,about.image,users.mobile_number,timings.*,users.order_extra_charge,users.delivery_plan,users.shop_open,
       		(6371 * ACOS ( COS ( RADIANS (".$_POST['latitude'].")) * COS ( RADIANS(users.latitude)) * COS(RADIANS(users.longitude) - RADIANS(".$_POST['longitude']."))
       		+ SIN(RADIANS(".$_POST['latitude'].")) * SIN(RADIANS(users.latitude)))) AS distance,users.not_working_text,users.not_working_text_chiness
      		FROM users 
			left JOIN service on users.service_id = service.id 
			LEFT JOIN about on users.id=about.userid 
			LEFT JOIN timings on users.id=timings.merchant_id   
			WHERE users.user_roles = 2 and users.isLocked= 0 and users.latitude!='' and users.longitude!='' and users.show_merchant='1' $LOCSQL
			group by users.id 
			order by distance asc";   

	$sql_count = "SELECT   COUNT(users.name) as count
      		FROM users 
			left JOIN service on users.service_id = service.id 
			LEFT JOIN about on users.id=about.userid 
			LEFT JOIN timings on users.id=timings.merchant_id   
			WHERE users.user_roles = 2 and users.isLocked= 0 and users.latitude!='' and users.longitude!='' $LOCSQL
			group by users.id 
			order by distance asc";
} 
else if(isset($sort_by) && $sort_by=="sort_distance" && $type=="popular"){
	 $sql = "SELECT   users.name, users.address,service.short_name,about.image,users.mobile_number,timings.*,users.order_extra_charge,users.delivery_plan,users.shop_open,
        	(6371 * ACOS ( COS ( RADIANS (".$_POST['latitude'].")) * COS ( RADIANS(users.latitude)) * COS(RADIANS(users.longitude) - RADIANS(".$_POST['longitude']."))
   			+ SIN(RADIANS(".$_POST['latitude'].")) * SIN(RADIANS(users.latitude)))) AS distance,users.working_text,users.working_text_chiness,users.banner_image,users.not_working_text,users.not_working_text_chiness
      		FROM users 
			left JOIN service on users.service_id = service.id 
			LEFT JOIN about on users.id=about.userid 
			LEFT JOIN timings on users.id=timings.merchant_id   
			WHERE users.user_roles = 2 and users.isLocked= 0 and users.popular_restro=1 and users.latitude!='' and users.longitude!='' and users.show_merchant='1' $LOCSQL
			group by users.id 
			order by distance asc";   

	$sql_count = "SELECT   COUNT(users.name) as count
      		FROM users 
			left JOIN service on users.service_id = service.id 
			LEFT JOIN about on users.id=about.userid 
			LEFT JOIN timings on users.id=timings.merchant_id   
			WHERE users.user_roles = 2 and users.isLocked= 0 and users.popular_restro=1 and users.latitude!='' and users.longitude!=''  $LOCSQL
			group by users.id 
			order by distance asc";  

} 
else if(isset($sort_by) && $sort_by=="sort_name" && $type=="sort_name"){
	/*$sql = "SELECT   users.name, users.address,service.short_name,about.image,users.mobile_number,timings.*,users.order_extra_charge,users.delivery_plan,users.shop_open,
			users.working_text,users.working_text_chiness,users.banner_image,users.not_working_text,users.not_working_text_chiness	
			FROM users 
			left JOIN service on users.service_id = service.id 
			LEFT JOIN about on users.id=about.userid 
			LEFT JOIN timings on users.id=timings.merchant_id   
			WHERE users.user_roles = 2 and users.isLocked= 0 and users.show_merchant='1' $LOCSQL
			group by users.id 
			order by users.name asc";  */

/*
$sql = "SELECT   users.name, (SELECT count(*) FROM `timings` WHERE day = '".date(l)."' AND '".date('H:i')."' BETWEEN start_time and end_time and `merchant_id` = users.id) as opening, users.address,service.short_name,about.image,users.mobile_number,timings.*,users.order_extra_charge,users.delivery_plan,users.shop_open,users.working_text,users.working_text_chiness,users.banner_image,users.not_working_text,users.not_working_text_chiness	
	FROM users left JOIN service on users.service_id = service.id LEFT JOIN about on users.id=about.userid 
	LEFT JOIN timings on users.id=timings.merchant_id WHERE users.user_roles = 2 and users.isLocked= 0 and users.show_merchant='1' $LOCSQL
	group by users.id order by `opening` DESC, users.name asc";
	*/
	
	if($_SESSION['langfile'] == 'malaysian'){
		$lang_shop = " and users.default_lang !='2' ";
	}else{
		$lang_shop = "";
	}
	
	$sql = "(SELECT users.name, users.address,service.short_name,about.image,users.mobile_number,timings.*,users.order_extra_charge,users.delivery_plan,users.shop_open, users.working_text,users.working_text_chiness,users.banner_image,users.not_working_text,users.not_working_text_chiness FROM users left JOIN service on users.service_id = service.id LEFT JOIN about on users.id=about.userid LEFT JOIN timings on users.id=timings.merchant_id WHERE users.user_roles = 2 and users.isLocked= 0 and users.show_merchant='1' ".$lang_shop." and day = '".date(l)."' AND '".date('H:i')."' BETWEEN start_time and end_time $LOCSQL group by users.id order by users.name asc)
UNION ALL
(SELECT users.name, users.address,service.short_name,about.image,users.mobile_number,timings.*,users.order_extra_charge,users.delivery_plan,users.shop_open, users.working_text,users.working_text_chiness,users.banner_image,users.not_working_text,users.not_working_text_chiness FROM users left JOIN service on users.service_id = service.id LEFT JOIN about on users.id=about.userid LEFT JOIN timings on users.id=timings.merchant_id WHERE users.user_roles = 2 and users.isLocked= 0 and users.show_merchant='1' ".$lang_shop." and day = '".date(l)."' AND '".date('H:i')."' NOT BETWEEN start_time and end_time $LOCSQL group by users.id order by users.name asc
)";  
			
	

	$sql_count = "SELECT   COUNT(users.name) as count
			FROM users 
			left JOIN service on users.service_id = service.id 
			LEFT JOIN about on users.id=about.userid 
			LEFT JOIN timings on users.id=timings.merchant_id   
			WHERE users.user_roles = 2 and users.isLocked= 0 and users.show_merchant='1' $LOCSQL
			group by users.id 
			order by users.name asc";    
}
$sql_count = $sql;
 $sql .= " LIMIT $offset,$per_page";  

$result_count = mysqli_query($conn, $sql_count);
$total = mysqli_num_rows($result_count);
// $total = $data['count'];
// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
$result = mysqli_query($conn, $sql);
$count = mysqli_num_rows($result);

if(!function_exists('checktimestatus')){
	function checktimestatus($time_detail)
	  {
		global $shopclose;
			extract($time_detail);
// 			 echo "day=$day n=$n";
// 		 	echo "ctime:".date("H:i");
			$day=strtolower($day);
			$currenttime=date("H:i");
			$n=strtolower(date("l"));
			if(($currenttime >$starttime && $currenttime < $endttime) && ($day==$n)){
				  //$shop_close_status="y";
				   array_push($shopclose,"y");
			}
			else
			{ 
			  //$shop_close_status="n";
			  array_push($shopclose,"n");
			}
			return $shopclose;//$shop_close_status;
	  }	
}

if($count > 0){
	if($template == 'home'){
		$show_next = true;
		$show_prev = true;
		$max_pages = ceil($total / $per_page);
		if($page == $max_pages){
			$show_next = false;
		}
		if($page == 1){
			$show_prev = false;
		}
		?>
			<div class="col-sm-12">
				<div class="text-center">
					<?php 
						if($show_prev){
							?>
								<button type="button" class="btn btn-default config-btn-page-btn" data-page="<?php echo ($page - 1); ?>">&lt;</button>
							<?php 
						}
						if($max_pages > 6){
							$start_page = $page - 3;
							$end_page = $page + 3;

							if($start_page < 0){
								$end_page = $end_page + $start_page;
								$start_page = 1;
							}
							if($end_page > $max_pages){
								$start_page = $start_page - ($max_pages - $end_page);
								$end_page = $max_pages;
							}
						}
						else{
							$start_page = 1;
							$end_page = $max_pages;
						}

						for ($i = $start_page; $i <= $end_page; $i++) { 
							?>
								<button type="button" class="btn btn-default config-btn-page-btn" data-page="<?php echo $i; ?>" <?php echo $i == $page ? 'disabled' : ''; ?>><?php echo $i; ?></button>
							<?php 
						}

						if($show_next){
							?>
								<button type="button" class="btn btn-default config-btn-page-btn" data-page="<?php echo ($page + 1); ?>">&gt;</button>
							<?php 
						}
					?>
				</div>
			</div>
		<?php 
	}
	$today_day=strtolower(date('l'));
	// while($rd = mysqli_fetch_assoc($result)){
	while($rd = mysqli_fetch_array($result)){
		// echo '<pre>';
		//print_r($rd);
		// echo '</pre>';
		$working='y';
		
		if($template == 'home'){
			?>
				<div class="col-md-6">
					<div class="list_home">
						<ul>   
							<?php 
								if($rd['day'] && $rd['shop_open']){
									   $sql1="SELECT  * FROM `timings` WHERE day='$today_day' and `merchant_id` =".$rd['merchant_id'];
										$result1 = mysqli_query($conn,$sql1);
										while($ti=mysqli_fetch_assoc($result1))
											{ 
												$time_detail['day']=$ti['day'];
												$time_detail['starttime']=$ti['start_time'];
												$time_detail['endttime']=$ti['end_time'];
												// print_R($time_detail);
												
												$tworking=checktimestatus($time_detail);  
											}
										// print_R($tworking);   
										// echo "</br>Merchant Gap ";
										if(count($tworking)>0)
										{
											foreach($tworking as $w)
											{  
												if($w=="y")
												{
													$working="y";
													break;
												}
												else
												{
													$working="n";
												}
											}
										}
										else
										{
											$working="n";
										}
									   $shopclose=[];
									   
								 }  
								else{
									if($rd['shop_open']==0)
									{
										$work_str='';
										$working='n';
										
									}
									else
									{
										$work_str='';
										$working='y';
									}
								}
								// print_R($working);
								if($working=='n')
								{
									
									if($langfile == "chinese" && $rd['working_text_chiness']!=''){
										$work_str.="</br>".$rd['working_text_chiness'];
										if($rd['not_working_text_chiness'])
										$work_str.=" -".$rd['not_working_text_chiness'];
									}
									else if($rd['working_text']){
										$work_str.="</br>".$rd['working_text'];
										if($rd['not_working_text'])
										$work_str.=" -".$rd['not_working_text'];
									}  
								}
	                        ?>  
	                        <li class="showLoader6">
								<a href="view_merchant.php?vs=<?=md5(rand()) ?>&sid=<?php echo $rd['mobile_number'];?>">
									<figure class="<?php //if($working=="n"){echo "shop_close";} ?>">
										<?php 
											if($rd['image']==""){ 
												?>   
													<img src="images/logo_new.jpg" data-src="images/logo_new.jpg" alt="" class="lazy">
												<?php
											} 
											else{  
												?>
													<img  src="<?php echo $image_cdn; ?>about_images/<?php echo $rd['image']?>?w=200" alt="" class="lazy lazy2">
												<?php 
											} 
										?>
									</figure>
									<!-- <div class="score"><strong>9.5</strong></div> --
									<!--em>Italian</em!-->
									<h3><?php echo $rd['name'];?></h3>
									
									<small style='color:red;'>
										<?php 
											if($work_str == ""){}
											else{ echo $work_str;}
										?>
									</small>
									<?php 
										if($rd['order_extra_charge'] || $rd['delivery_plan']){ 
											if($rd[13]){ 
												//$d_str="Flexible Delivery";
												$d_str="";
											} 
											else{ 
												$d_str="MYR ".number_format($rd['order_extra_charge'],2);
											} 
										} 
										else{ 
											$d_str="Free Delivery";
										} 
										if($d_str){ 
											echo "<br><img src='https://koofamilies.sirv.com/about_images/motor.jpg'/> ".$d_str;
										} 
									?>
									<?php if($working=="n"){?>
									<!--<span class="loc_open">Shop closed</span>  -->
									<?php } ?>
								</a>
	                        </li>
						</ul>
					</div>
				</div>
			<?php 
		}
		$work_str='';
	}
	// for pagination
	if($template == 'home'){
		$show_next = true;
		$show_prev = true;
		$max_pages = ceil($total / $per_page);
		if($page == $max_pages){
			$show_next = false;
		}
		if($page == 1){
			$show_prev = false;
		}
		?>
			<div class="col-sm-12">
				<div class="text-center">
				&nbsp;
							  <img src="img/ajax-loader.gif" class="ajx_shop_resp" style="display:none"/> &nbsp; 
					<?php 
						if($show_prev){
							?>
								<button type="button" class="btn btn-default config-btn-page-btn" data-page="<?php echo ($page - 1); ?>">&lt;</button>
							<?php 
						}
						if($max_pages > 6){
							$start_page = $page - 3;
							$end_page = $page + 3;

							if($start_page < 0){
								$end_page = $end_page + $start_page;
								$start_page = 1;
							}
							if($end_page > $max_pages){
								$start_page = $start_page - ($max_pages - $end_page);
								$end_page = $max_pages;
							}
						}
						else{
							$start_page = 1;
							$end_page = $max_pages;
						}

						for ($i = $start_page; $i <= $end_page; $i++) { 
							?>
								<button type="button" class="btn btn-default config-btn-page-btn" data-page="<?php echo $i; ?>" <?php echo $i == $page ? 'disabled' : ''; ?>><?php echo $i; ?></button>
							<?php 
						}

						if($show_next){
							?>
								<button type="button" class="btn btn-default config-btn-page-btn" data-page="<?php echo ($page + 1); ?>">&gt;</button>
							<?php 
						}
					?>
				</div>
			</div>
		<?php 
	}
}
else{
	?>
		<div class="col-sm-12">No records found.</div>
	<?php 
}



// file ends here...