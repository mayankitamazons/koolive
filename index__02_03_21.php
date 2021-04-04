<?php 
include('config.php');
$_SESSION["locationsort"] = 'Kulai';
/* language - 18/01/2021 */
/*if($_SESSION['langfile'] == 'english'){
	$open_language_modal = 1; //1=>open popup 0=>not open
}
if(isset($_SESSION['user_id']) && $_SESSION['user_id']!= ''){
		//fetch languge of User from Database:
		$query_user = mysqli_query($conn, "SELECT user_language FROM users WHERE id =".$_SESSION["user_id"]."");
		$userArray = mysqli_fetch_assoc($query_user);
		$userLanguage = $userArray['user_language'];
		//echo "====".$userLanguage."SELECT user_language FROM users WHERE id =".$_SESSION["user_id"];
		if($userLanguage != '' ){
			$_SESSION["langfile"] = $userLanguage;
			$open_language_modal = 0; //1=>open popup 0=>not open
		}else{
			//open popup
			$open_language_modal = 1; //1=>open popup 0=>not open
		}
}else{
	if($_SESSION['langfile']!='english'){
		$open_language_modal = 0; //1=>open popup 0=>not open
	}
}
if(isset($_GET['language'])){
	$open_language_modal = 0; //1=>open popup 0=>not open
	$_SESSION["langfile"] = $_GET['language'];
	if(isset($_SESSION['user_id']) && $_SESSION['user_id']!= ''){
		if($userLanguage != $_GET['language']){
			//update language for user
			$update_sql = "UPDATE users SET user_language='".$_GET['language']."' WHERE id=".$_SESSION["user_id"];
			$update_language = mysqli_query($conn, $update_sql);		
		}
	}
}
*/
if(isset($_GET['language'])){
	$_SESSION["langfile"] = $_GET['language'];
}
if(isset($_SESSION["langfile"]) && $_SESSION["langfile"] !=''){
	require_once ("languages/".$_SESSION["langfile"].".php");
}
/* language - 18/01/2021*/


if(isset($_REQUEST['locationsort'])){
	
	$_SESSION["locationsort"] = @$_GET['locationsort'];
	
}
$LocaSt=$_SESSION['locationsort'];
if($_SESSION['locationsort'] && $LocaSt)
{
	$LOCSQL = "and users.city='$LocaSt'" ;
}
else
{
	$LOCSQL='';
}








if (empty($_SESSION["langfile"])) { $_SESSION["langfile"] = "english"; }
require_once ("languages/".$_SESSION["langfile"].".php");

if(empty($_GET['vs']))
{
	$url="index.php?vs=".md5(rand());

header("Location:$url");
exit();
} 
if(isset($_POST['merchant_select_form']))
{
	if($_POST['merchant_select'])
	{
		$sid=$_POST['merchant_select'];
		
		$url="https://www.koofamilies.com/view_merchant.php?sid=".$sid."&ms=".md5(rand());
		header("Location:$url");
		exit();
}
}	

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="KooFamilies - Discover & Book the best restaurants at the best price">
    <meta name="author" content="Ansonika">
    <title>KooFamilies - One stop centre for your everything</title>

    <!-- Favicons-->
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" type="image/x-icon" href="img/apple-touch-icon-57x57-precomposed.png">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="72x72" href="img/apple-touch-icon-72x72-precomposed.png">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="114x114" href="img/apple-touch-icon-114x114-precomposed.png">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="144x144" href="img/apple-touch-icon-144x144-precomposed.png">

    <!-- GOOGLE WEB FONT -->
   
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- BASE CSS -->
    <link href="extra/css/bootstrap_customized.min.css" rel="stylesheet">
    <link href="extra/css/style.css" rel="stylesheet">

    <!-- SPECIFIC CSS -->
    <link href="extra/css/home.css" rel="stylesheet">

    <!-- YOUR CUSTOM CSS -->
    <link href="extra/css/custom.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="css/sweetalert.css">
   <link rel="manifest" id="my-manifest-placeholder">
  
    <meta name="theme-color" content="#317EFB"/>
<script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async></script>
<script>
  var OneSignal = window.OneSignal || [];
  OneSignal.push(function() {
    OneSignal.init({
      appId: "57f21ad6-a531-4cb6-9ecd-08fe4dd3b4f5",
    });
	 OneSignal.getUserId().then(function(userId) {  
    console.log("OneSignal User ID:", userId);
	$('#one_player_id').val(userId);
    // alert("OneSignal User ID:", userId);   
    // (Output) OneSignal User ID: 270a35cd-4dda-4b3f-b04e-41d7463a2316    
  });
  });
  
	document.onreadystatechange = function() {
	var state = document.readyState;
		console.log('condition');
		document.getElementById('load').style.visibility = "hidden";
		$('#load').parent().css({
			"background": "transparent",
			"z-index": "-1"
		});
	}
	
</script>


	

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</head>
<?php if(empty(@$_SESSION["locationsort"])) { ?>
<script>
    $(document).ready(function(){
        //$("#myModal").modal('show');
    });
</script>
<?php } ?>
<div id="myModal" class="modal fade" style="">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Select Ordering location <?php echo $_SESSION["locationsort"] ; ?></h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
               
				<!--<p> <button type="button" class="btn btn-danger" data-dismiss="modal" id="search_location_1">Search Shops based on current location</button>				</p>-->
                <form>
					<?php 
					$sql = mysqli_query($conn, "SELECT CityName  FROM city WHERE 0=0 GROUP BY CityName");
					$selected = '';
					while($data = mysqli_fetch_array($sql))
					{
					?>
					<!--<p><a data-dismiss="modal" href="" onclick="window.location.assign('https://www.koofamilies.com/index.php?locationsort=<?php echo $data['CityName'] ?>') ; return false;"><button class="locationbutton btn btn-primary"><?php echo $data['CityName'] ?></button></a></p>
					-->
					<p><a href="javascript:void(0);" link="https://www.koofamilies.com/index.php?locationsort=<?php echo $data['CityName'] ?>" class="locationbutton btn btn-primary"><?php echo $data['CityName'] ?></a></p>
					
					<?php	
					}	  
					?>
                </form>
					<img src="img/ajax-loader.gif" class="ajx_location_resp" style="display:none;"/>
					&nbsp;
					<span class="please_wait_text1" style="display:none;color:red">Please wait ....</span>
				
				
				
            </div>
			<div class="modal-footer">
         
        </div>
        </div>
    </div>
</div>



<?php /* Language Model - 18/01/2021 */?>
<?php /*if($open_language_modal == 1 && $_SESSION["locationsort"] != ''){?>
<script>
    $(document).ready(function(){
		$("#myModal_language").modal({
			show: false,
			backdrop: 'static'
		});
	
		$("#myModal_language").modal('show');
		
    });
</script>
<div id="myModal_language" class="modal fade" style="">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Select your Language </h5>
                <!--<button type="button" class="close" data-dismiss="modal">&times;</button>-->
            </div>
            <div class="modal-body">
				<form name="language_form" id="language_form">
					<a href="index.php?vs=<?php echo md5(rand()); ?>&language=english" class="btn btn-primary btn_language" >English</a>
					<a href="index.php?vs=<?php echo md5(rand()); ?>&language=chinese" class="btn btn-success btn_language" >华语</a>
					<a href="index.php?vs=<?php echo md5(rand()); ?>&language=malaysian" class="btn btn-info btn_language" >Malay</a>
					<br/>
					<img src="img/ajax-loader.gif" class="ajx_lang_resp" style="display:none;padding-top:10px"/>
					&nbsp;
					<span class="please_wait_text" style="display:none;color:red">Please wait ....</span>
					
				</form>
            </div>
			<div class="modal-footer">
		
			</div>
        </div>
    </div>
</div>

<?php }*/?>
<?php /* Language Model - 18/01/2021 */?>





<body>
	<div class="page_loader">
		<span id="load"></span>
	</div>
	<style>
	/*loader Css */
	.page_loader {
		position: fixed;
		left: 0px;
		top: 0px;
		width: 100%;
		height: 100%;
		z-index: 999999;
		background-color: rgba(255, 255, 255, 0.5);
	}

	#load {
		background-image: url("loader.gif");
		background-position: center center;
		background-repeat: no-repeat;
		bottom: 0;
		height: auto;
		left: 0;
		margin: auto;
		position: absolute;
		right: 0;
		top: 0;
		width: 100%;
		max-width: 200px;
		background-size: contain;
	}

	.load_parentcss {
		background: "transparent" !important;
		z-index: "-1" !important;
	}
</style>
	<?php
	 
      function checktimestatus($time_detail)
	  {
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
	 	$currenttime=date("H:i");
		$n=date("N");
		    if(($currenttime >$starttime && $currenttime < $endttime) && ($s_day<=$n && $e_day>=$n)){
			  $shop_close_status="y";
		  }
		  else
		  {
			  $shop_close_status="n";
		  }
		return $shop_close_status;
	  }	
if(isset($_GET['code']) && isset($_GET['id']) && is_numeric($_GET['id']))
{
	// print_r($_GET);
	// die;
	$code = $_GET['code']; 
	$apiusername = $_GET['apiusername']; 
	$user_id = $_GET['id'];
	$show_flash='';
	if(!isset($_GET['apiusername']))
	{
		// echo "SELECT * FROM users WHERE verification_code='$code' AND id='$user_id'";
		// die;
		$user_row2 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE  id='$user_id'")); 
		$if_exists = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM users WHERE verification_code='$code' AND id='$user_id'"));
	    // echo $if_exists;
		// die;
		if($if_exists > 0)
		{
			  mysqli_query($conn, "UPDATE users SET password_created='y',otp_verified='y',verification_code='', isLocked='0' WHERE id='$user_id'");
			$show_flash = "You have verified your account successfully. Now You can login to use our service.";
		}
		else
		{
			$show_flash = "Your Link is Expire,Contact Support or resend link";
			$_SESSION['resend_link']='y';
				$_SESSION['cm']=$user_row2['mobile_number'];
		}
	}	   
}
	  
    ?>				
	<header class="header clearfix element_to_stick">
		<div class="container">
		<div id="logo">
			<a href="index.php?vs=<?php echo md5(rand()); ?>">
                <!-- koofamilies logo -->
				 <img src="svgLog_second.svg" width="140" height="35" alt="" class="logo_normal">
                <img src="svgLog_first.svg" width="140" height="35" alt="" class="logo_sticky">
               
                <!-- koofamilies logo -->
			</a>
		</div>
		 <ul id="top_menu">
			
			<?php if(isset($_SESSION['login'])){	?>
			<li><a href="favorite.php" class="wishlist_bt_top" title="Your favorite">Your wishlist</a></li>
			<?php } ?>
		</ul>
		<!-- /top_menu -->   
		<a href="#0" class="open_close">
			<i class="icon_menu"></i><span>Menu</span>
		</a>
		<nav class="main-menu">
			<div id="header_menu">
				<a href="#0" class="open_close">
					<i class="icon_close"></i><span>Menu</span>
				</a>
				<a href="index.php?vs=<?php echo md5(rand()); ?>">KooFamilies</a>
				
			</div>
			 <ul>


			 <!--li class="submenu"><a href="register.php" class="show-submenu" style="font-size:16px"><?php echo $language['register']; ?></a></li!-->


				<?php if(!isset($_SESSION['login'])){	?>
					<!--li class="submenu"><a href="register.php" class="show-submenu" style="font-size:16px"><?php echo $language['rider']; ?></a></li!-->
					<li class="submenu"><a href="login.php" class="show-submenu" style="font-size:16px"><?php echo $language['login']; ?></a></li>
				<?php } else {?>
				<li class="submenu"><a href="dashboard.php" class="show-submenu" style="font-size:16px"><?php echo $language['dashboard']; ?></a></li>
				<li class="submenu"><a href="favorite.php" class="show-submenu" style="font-size:16px"><?php echo $language['my_fav']; ?></a></li>
				<?php } ?>
				
				<li class="submenu">
					<a href="#0" class="show-submenu"> <i class="fa fa-language" style="font-size:18px;" aria-hidden="true"></i> Language</a>
					<ul class="">
						
						<li><a href="index.php?vs=<?php echo md5(rand()); ?>&language=english">English</a></li>
						<li><a href="index.php?vs=<?php echo md5(rand()); ?>&language=chinese">Chinese</a></li>
						<li><a href="index.php?vs=<?php echo md5(rand()); ?>&language=malaysian">Malay</a></li>  
						
						
					</ul>
				</li>   
			</ul>
		</nav>
	</div>
	</header>   
	<!-- /header -->
	
	<main>

		<div class="hero_single version_2">
			<div class="opacity-mask" data-opacity-mask="rgba(0, 0, 0, 0.6)">
				<div class="container">
					
					<div class="row justify-content-center" style="margin-top: 170px;">
					
						
					
					
					
						<div class="col-xl-9 col-lg-10 col-md-8">
							<form method="post" id="merchant_submit_form" action="#">
									<div class="row no-gutters custom-search-input">
											<div class="col-lg-4">

											<div class="form-group">
                                            <input class="form-control" autocomplete="off" name="product_search" id="product_search" type="text" placeholder="<?=$language['search_product_name']; ?>">
                                                <i class="icon_search"></i>
                                            </div>
                                        </div>
										<div class="col-lg-6">
											<div class="form-group">
											
											<span id="please_wait" style="color:red;display:none;">Please wait.....</span>
												<select class="merchant_select form-control" name="merchant_select">
												   <option value="-1"><?php echo $language['search_by_company']; ?></option>
													<?php
														
														$select =mysqli_query($conn,"SELECT SQL_NO_CACHE  name,id,mobile_number FROM users WHERE name LIKE isLocked='0' and show_merchant=1  and user_roles=2 $LOCSQL");
														
														while ($row=mysqli_fetch_assoc($select)) 
														{
														 ?>
														 <option value="<?php echo $row['mobile_number']; ?>"><?php echo $row['name'];?></option>
														<?php }   
													?>
												</select>
											 <!--input type="text" id="one_player_id"/!-->
											</div>
										</div>   
										
										<div class="col-lg-2">
											
											<input type="submit" id="merchant_select_form" name="merchant_select_form" value="<?php echo $language['search']; ?>" style="margin-top: 0px;background-color:#e75480;color:black">
										</div>
									</div>
								
								</form>
						</div>
					</div> 
					<!-- /row -->
					<div style="margin-top:2px;">
					
				    <span id="please_wait_location" style="color:red;display:none;">Please wait.....</span>
					<button type="button"  id="search_location" style="margin-top:2%;background-color:#589442;color;black;padding: 13px;width: 100%;color: black;border-radius: 4px;" class="btn btn-primary"><?php echo $language['search_by_location']; ?></button>
					<!--span id="search_location" style="margin-top: 2%;background-color: #589442;padding: 13px;" class="btn btn-primary">Search by location</span!--> 
					</div>
					
					<div class="col-xl-12 col-lg-12 col-md-12">
					<button type="button" data-toggle="modal" data-target="#myModal" style="margin-top:2%;background-color:pink;padding: 13px;width: 100%;color: black;border-radius: 4px;color;black" ><?php echo $language['reselect_location']; ?></button>
					</div>
					
					<div class="container" style="margin-top: 5px;">
					 <?php 
				$sql="select SQL_NO_CACHE set_working_hr.*,users.mobile_number,users.name,users.address,users.login_status,users.id,about.image,users.shop_open,cs.shift_pos 
					 from classification_arrange_system as cs inner join users on users.id=cs.merchant_id LEFT JOIN about on  users.id=about.userid LEFT JOIN set_working_hr on users.id=set_working_hr.merchant_id 
					 where cs.classfication_id='3' and users.user_roles = 2  and users.shop_open=1 $LOCSQL group by users.id
                ORDER BY cs.shift_pos  ASC  limit 20";
				
				


				// $sql  = "SELECT SQL_NO_CACHE  set_working_hr.*,users.mobile_number,users.name,users.address,users.login_status,users.id,about.image,users.shop_open FROM `users` 
                // LEFT JOIN about on  users.id=about.userid LEFT JOIN set_working_hr on users.id=set_working_hr.merchant_id where users.user_roles = 2 and users.popular_restro=1 and users.shop_open=1 group by users.id
                // ORDER BY users.popular_sort_order  ASC  limit 20";   
                $result = mysqli_query($conn,$sql);                  
                $totalpo=mysqli_num_rows($result);
                 if($totalpo>0){ 
            
            ?>
			<div class="main_title" style="margin:0px;">
				<span><em></em></span>
				<div class="row">
				  <div class="col-md-8"><h2 style="text-align: left;color: white;"><?php echo $language['popular_restaurants']; ?></h2></div>
				 
				  <div style="margin:5px 0 0 0;float: left;" class="col-md-2">
				<a href="merchant_find.php">View All</a>
				</div>
				</div>
				
            </div>
           
			
			<div class="owl-carousel owl-theme carousel_4">
            
			<?php if(mysqli_num_rows($result)>0){
                while($rd=mysqli_fetch_assoc($result)){
                     // print_r($rd);
					$working="y";
					 if($rd['start_day']){
						 $time_detail['starday']=$rd['start_day'];
						 $time_detail['endday']=$rd['end_day'];
						 $time_detail['starttime']=$rd['start_time'];
						 $time_detail['endttime']=$rd['end_time'];
						
						 $working=checktimestatus($time_detail);
						 // $work_str="Working Time :".$rd['start_day']." ".$rd['start_time']." to "." ".$rd['end_day']." ".$rd['end_time'];
					 }   
					 if($working=="y")
					 {
                    ?>
                    
                    <div class="item">   
			        <div class="strip showLoader6">
			            <figure>
			                <!-- <span class="ribbon off">-30%</span> -->
							<?php if($rd['image']==""){ ?>
							<img src="images/logo_new.jpg" data-src="images/logo_new.jpg" class="owl-lazy" alt=""> <?php
							}else{ ?> <img  data-src="<?php echo $image_cdn; ?>about_images/<?php echo $rd['image']?>?w=200" class="owl-lazy lazy2 Sirv" alt=""> <?php }?>
			                
			                <a href="view_merchant.php?vs=<?=md5(rand()) ?>&sid=<?php echo $rd['mobile_number'];?>" class="strip_info">
			                    <!-- <small>Pizza</small> -->
			                    <div class="item_title">
                                      <h3><?php echo $rd['name']?></h3>
									
			                        <!--small><?php if($work_str==""){
										echo "<br>";
									}else{ echo $work_str;}?></small!-->
			                    </div>
			                </a>
			            </figure>
			            <ul>
                            <?php
                               
                            if($rd['shop_open'] == 1 || $working=='y'){ ?>   
                               <li><a href="view_merchant.php?vs=<?=md5(rand()) ?>&sid=<?php echo $rd['mobile_number'];?>"><span class="loc_open">Now Open</span></a></li>
                         <?php   }else{ ?>
                                <li><span class="loc_closed">Now Closed</span></li>
                        <?php    }
                            ?>
			                
			            </ul>
			        </div>
                </div>
					 <?php }
                }
            } ?>
			    
			  
			</div>
				 <?php } ?>
			
		</div>
				</div>
			</div>
				
		</div>
		<?php 
		// list all classfication business 
		if($_SESSION["langfile"]=="malaysian")
		 $l_q="select sql_no_cache * from classfication_service where status='y' and id!='3' and mal_version='y'";
		else
		$l_q="select sql_no_cache * from classfication_service where status='y' and id!='3'";    
	 
		$l_query=mysqli_query($conn,$l_q);
		 $current_lang=$_SESSION["langfile"];
		 $classi_name=[];
		 $classi_count=[];
		while($l_r=mysqli_fetch_assoc($l_query))
		{
			$classi_name[]="classi_".$l_r['id'];
			
			$c_id=$l_r['id'];
			   $sql="select SQL_NO_CACHE users.banner_image,users.name, users.address,service.short_name,about.image,users.mobile_number,set_working_hr.*,
			  users.order_extra_charge,users.delivery_plan,users.not_working_text,users.not_working_text_chiness 
			  from classification_arrange_system as cs   inner join users on users.id=cs.merchant_id left JOIN service on users.service_id = service.id LEFT JOIN about on users.id=about.userid LEFT JOIN set_working_hr on users.id=set_working_hr.merchant_id where cs.classfication_id='$c_id' 
			  and users.user_roles = 2 and users.shop_open=1 $LOCSQL group by users.id ORDER BY cs.shift_pos ASC limit 20 ";
			
			 $classification_name=$l_r['classification_name']; 
			if($current_lang=="chinese" && $l_r['classification_name_chiness'])
			{
				$classification_name=$l_r['classification_name_chiness']; 
			} if($current_lang=="malaysian" && $l_r['classification_name_mal'])
			{
			   $classification_name=$l_r['classification_name_mal']; 	
			}
			$result = mysqli_query($conn, $sql);
			$count= mysqli_num_rows($result);
			
			if($count>0)
			{
		?>
		<div class="container <?php echo "classi_".$l_r['id']; ?> classi">
				
			<div class="main_title">
				<span><em></em></span>
				<div class="row">
				  <div class="col-md-8"><h2 style="text-align: left;"><?php echo $classification_name; ?></h2></div>
				  <div style="margin: 20px 0 0 0;float: right;" class="col-md-2">
				  <!--select class="form-control popular_filter">
					<option value="sort_name">Sort By Name</option>
					   <option value="sort_distance">Search nearby</option>
				  </select!-->
				</div>
				 
				</div>
				
            </div>
           
			
			<div class="owl-carousel owl-theme carousel_4">
            
			<?php if(mysqli_num_rows($result)>0){
                while($rd=mysqli_fetch_assoc($result)){
                     // print_r($rd);
					$working="y";
					 if($rd['start_day']){
						 $time_detail['starday']=$rd['start_day'];
						 $time_detail['endday']=$rd['end_day'];
						 $time_detail['starttime']=$rd['start_time'];
						 $time_detail['endttime']=$rd['end_time'];
						$work_str=$rd['start_day']." ".$rd['start_time']." to ".$rd['end_day']." ".$rd['end_time']; 
						 $working=checktimestatus($time_detail);
						 // $work_str="Working Time :".$rd['start_day']." ".$rd['start_time']." to "." ".$rd['end_day']." ".$rd['end_time'];
					 } 
					// $working="y";
					if($working=="y"){
					
                    ?>
                    
                    <div class="item <?php echo "classi_".$l_r['id']."_child";?>">
			        <div class="strip showLoader6">
			            <figure>
			                <!-- <span class="ribbon off">-30%</span> -->
							<?php if($rd['banner_image']){  ?>
								<img ref="banner_image" data-src="<?php echo $image_cdn; ?>banner_image/<?php echo $rd['banner_image']?>?w=400" class="owl-lazy lazy2 Sirv" alt="">
							<?php } else {if($rd['image']==""){ ?>
							<img src="images/logo_new.jpg" data-src="images/logo_new.jpg" class="owl-lazy" alt=""> <?php
							}else{ ?> <img  data-src="<?php echo $image_cdn; ?>about_images/<?php echo $rd['image']?>?w=200" class="owl-lazy lazy2 Sirv" alt=""> <?php } }?>
			                
			                <a href="view_merchant.php?vs=<?=md5(rand()) ?>&sid=<?php echo $rd['mobile_number'];?>" class="strip_info">
			                    <!-- <small>Pizza</small> -->
			                    <div class="item_title">
                                      <h3><?php echo $rd['name']?></h3>
									
			                        <small><?php if($work_str==""){
										echo "<br>";
									}else{ echo $work_str;}?></small>
									<?php if($rd[12] || $rd[13]){ if($rd[13]){ $d_str="Flexible Delivery";} else { $d_str="MYR ".number_format($rd[12],2);} } else { $d_str="Free Delivery";} ?>
									
			                    </div>
			                </a>
							
			            </figure>
						
			        </div>   
                </div>
					<?php }
                }
            } ?>
			    
			  
			</div>
				
			
		</div>
			<?php }} ?>
		<!-- /bg_gray -->
		<!-- popular restaurants  -->
		<div class="container margin_60_40 search_location_div">
			<div class="row all_merchant_list">
				<div class="col-12">
					<div class="main_title version_2">
						<span><em></em></span>
						<div class="row">
							  <div class="col-md-8"><h2><?php echo $language['near_by_shop_2']; ?>
							   &nbsp;
							  <img src="img/ajax-loader.gif" class="ajx_shop_resp" style="display:none"/></h2></div>   
							
							  <div style="margin: 20px 0 0 0;float: right;" class="col-md-2">
							  <select class="form-control all_restro_sort">
								<option value="sort_name" selected>Sort By Name</option>
								   <option value="sort_distance">Search nearby</option>
							  </select>
							</div>
							  <div style="margin: 20px 0 0 0;float: left;" class="col-md-2">
							<a href="merchant_find.php">View All</a>
							</div>
						</div>
						
					
					</div>
				</div>
				
				<div class="col-sm-12">
					<?php 
						$sort_by = 'sort_name';
						$type = 'sort_name';
						$page = 1;
						$per_page = 20;
						$offset = ($page - 1) * $per_page;
						//echo 'test';
					?>
					 
					<div class="row config_all_merchant_list" data-template="home" data-sort_by="<?php echo $sort_by ?>" data-type="<?php echo $type; ?>" data-page="<?php echo $page; ?>" data-per_page="<?php echo $per_page; ?>">
						<!-- html here -->
					</div>
				</div>
			
			</div>
			<!-- /row -->
			<p class="text-center d-block d-md-block d-lg-none"><a href="merchant_find.php" class="btn_1">View All</a></p>
			<!-- /button visibile on tablet/mobile only -->
		</div>   
		<!-- /container -->


<!-- banner under popular restaurant  -->
		<div class="call_section lazy" data-bg="url(img/chef-cell-banner.jpg)">
		    <div class="container clearfix">
		        <div class="col-lg-5 col-md-6 float-left wow">
		            <div class="box_1">
		                <h3>Are you a Restaurant Owner?</h3>
		                <p>Join Us to increase your online visibility. You'll have access to even more customers who are looking to enjoy your tasty dishes at home.</p>
		                <a href="login.php" class="btn_1">JOIN NOW</a>
		            </div>
		        </div>
    		</div>
    	</div>
   		<!--/call_section-->
<!-- /banner under popular restaurant  -->   
	</main>
	<!-- /main -->










	<footer>
		<div class="container">
			<div class="row">
				<div class="col-lg-6 col-md-6">
					<h3 data-target="#collapse_1">Quick Links</h3>
					<div class="collapse dont-collapse-sm links" id="collapse_1">
						<ul>
                            
							<!--<li><a href="about.php">About us</a></li>-->
							<li><a href="login.php">Add your restaurant</a></li>
							<li><a href="favorite.php">Favorite Merchant</a></li>
							<li><a href="login.php">My account</a></li>
							
							<li><a href="about.php">Contacts</a></li>
						</ul>
					</div>
				</div>   
				<!--div class="col-lg-3 col-md-6">
					<h3 data-target="#collapse_2">Categories</h3>
					<div class="collapse dont-collapse-sm links" id="collapse_2">
						<ul>
							<li><a href="#">Top Categories</a></li>
							<li><a href="#">Best Rated</a></li>
							<li><a href="#">Best Price</a></li>
							<li><a href="#">Latest Submissions</a></li>
						</ul>
					</div>
				</div!-->
				<div class="col-lg-3 col-md-6">
						<h3 data-target="#collapse_3">Contacts</h3>
					<div class="collapse dont-collapse-sm contacts" id="collapse_3">
						<ul>
							<li><i class="icon_house_alt"></i>Kemajuaan ladang Cermerlang Sdn. Bhd. 1400, Jalan Lagenda 50, </br>Taman Lagenda Putra Kulai, Johor, 81000, Malaysia</li>
							<li><i class="icon_mobile"></i>+60 123-11-5670</li>
							<li><i class="icon_mail_alt"></i><a href="#0">info@koopay.com</a></li>
						</ul>
					</div>
				</div>
				<div class="col-lg-3 col-md-6">
						<h3 data-target="#collapse_4">Keep in touch</h3>
					<div class="collapse dont-collapse-sm" id="collapse_4">
						<div id="newsletter">
							<div id="message-newsletter"></div>
							<form method="post" action="assets/newsletter.php" name="newsletter_form" id="newsletter_form">
								<div class="form-group">
									<input type="email" name="email_newsletter" id="email_newsletter" class="form-control" placeholder="Your email">
									<button type="submit" id="submit-newsletter"><i class="arrow_carrot-right"></i></button>
								</div>
							</form>
						</div>
						<div class="follow_us">
							<h5>Follow Us</h5>
							<ul>
								<!--li><a href="#0"><img src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==" data-src="img/twitter_icon.svg" alt="" class="lazy"></a></li!-->
								<li><a href="https://www.facebook.com/koofamilies/" target="_blank"><img src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==" data-src="img/facebook_icon.svg" alt="" class="lazy"></a></li>
								<li><a href="#0"><img src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==" data-src="img/instagram_icon.svg" alt="" class="lazy"></a></li>
								<li><a href="#0"><img src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==" data-src="img/youtube_icon.svg" alt="" class="lazy"></a></li>
							</ul>
						</div>  
					</div>
				</div>
			</div>
			<!-- /row-->
			<hr>
			<div class="row add_bottom_25">
				<!--div class="col-lg-6">
					<ul class="footer-selector clearfix">
						<li>
							<div class="styled-select lang-selector">
								<select>
									<option value="English" selected>English</option>
									<option value="French">French</option>
									<option value="Spanish">Spanish</option>
									<option value="Russian">Russian</option>
								</select>
							</div>
						</li>
						<li>
							<div class="styled-select currency-selector">
								<select>
									<option value="US Dollars" selected>US Dollars</option>
									<option value="Euro">Euro</option>
								</select>
							</div>
						</li>
						<li><img src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==" data-src="img/cards_all.svg" alt="" width="198" height="30" class="lazy"></li>
					</ul>
				</div!-->
				<div class="col-lg-6">
					<ul class="additional_links">
						<li><a href="privacy.php">Terms and conditions</a></li>
						<li><a href="privacy.php">Privacy</a></li>
						<li><span>© 2020 KooFamilies</span></li>
					</ul>
				</div>
			</div>
		</div>
	</footer>
	<!--/footer-->

	<div id="toTop"></div><!-- Back to top button -->
	
	<div class="layer"></div><!-- Opacity Mask Menu Mobile -->
	
	<!-- Sign In Modal -->
	<div id="sign-in-dialog" class="zoom-anim-dialog mfp-hide">
		<div class="modal_header">
			<h3>Sign In</h3>
		</div>
		<form>
			<div class="sign-in-wrapper">
				<a href="#0" class="social_bt facebook">Login with Facebook</a>
				<a href="#0" class="social_bt google">Login with Google</a>
				<div class="divider"><span>Or</span></div>
				<div class="form-group">
					<label>Email</label>
					<input type="email" class="form-control" name="email" id="email">
					<i class="icon_mail_alt"></i>
				</div>
				<div class="form-group">
					<label>Password</label>
					<input type="password" class="form-control" name="password" id="password" value="">
					<i class="icon_lock_alt"></i>
				</div>
				<div class="clearfix add_bottom_15">
					<div class="checkboxes float-left">
						<label class="container_check">Remember me
						  <input type="checkbox">
						  <span class="checkmark"></span>
						</label>
					</div>
					<div class="float-right mt-1"><a id="forgot" href="javascript:void(0);">Forgot Password?</a></div>
				</div>
				<div class="text-center">
					<input type="submit" value="Log In" class="btn_1 full-width mb_5">
					Don’t have an account? <a href="login.php">Sign up</a>
				</div>
				<div id="forgot_pw">
					<div class="form-group">
						<label>Please confirm login email below</label>
						<input type="email" class="form-control" name="email_forgot" id="email_forgot">
						<i class="icon_mail_alt"></i>
					</div>
					<p>You will receive an email containing a link allowing you to reset your password to a new preferred one.</p>
					<div class="text-center"><input type="submit" value="Reset Password" class="btn_1"></div>
				</div>
			</div>
		</form>
		<!--form -->
	</div>
	<!-- /Sign In Modal -->
		  <div class="modal fade" id="location_model" role="dialog">

        <div class="modal-dialog">

         



            <!-- Modal content-->

            <div class="modal-content">

             

                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                    <h4 class="modal-title"></h4>

                </div>

                 

                    <div class="modal-body" style="padding-bottom:0px;">

                        <div class="col-md-12" style="text-align: center;">

                          <h5>To Sort By Distance Location permission is required </h5>

                         <button type="button" class="btn btn-primary" onclick="clearhistory()">How to clear Cache</button>

                

                        </div>

                    </div>

                    <div class="modal-footer" style="padding-bottom:2px;">

                    

                    </div>

               

            </div>

        </div>

 </div>
 <link href="https://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" rel="Stylesheet"></link>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script type="text/javascript" src="js/sweetalert.min.js" defer></script>
  <script src="extra/js/common_scripts.min.js" defer></script>
  <script src="extra/js/common_func.js" defer></script>
  
  <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<script src="extra/js/select2.min_da99e0cfb43d832f77954298a0557ca5.js" defer></script>
  <!-- SPECIFIC SCRIPTS -->
  <script src="extra/js/modernizr.min.js" defer></script>
  <!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-5D6JWJ3RCH"></script>
<script src="//rum-static.pingdom.net/pa-60309b987c17460013000172.js" async></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-5D6JWJ3RCH');
</script>
	   <script type="text/javascript">
var map;
function initMap() {
var mapCenter = new google.maps.LatLng(47.6145, -122.3418); //Google map Coordinates
map = new google.maps.Map($("#map")[0], {
	  center: mapCenter,
	  zoom: 8
	});
}

</script>

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
	$(document).ready(function() {
		
		jQuery(document).on("click", '.btn_language', function() {
			//delay('20');
			$(this).delay('10');
			$('.please_wait_text').show();
			$('.ajx_lang_resp').show();
			$(this).prop('disabled', true);
			$(this).css('background-color', 'gray');
		});
		
		jQuery(document).on("click", '.locationbutton', function() {
			//delay('20');
			console.log('chkkkk');
			var link = $(this).attr('link');
			//$(this).delay('10');
			$('.please_wait_text1').show();
			$('.ajx_location_resp').show();
			$(this).prop('disabled', true);
			$(this).css('background-color', 'gray');
			window.location.href = link;
			
			//$('.page_loader2').show();
			//$("#load2").show();
			return false;
		});
		
		jQuery(document).on("click", '.showLoader6', function() {
			if(window.location.href.includes('orderview.php')) return false;
			$('.page_loader').removeAttr('style');
			$("#load").removeAttr('style');;
			$('.page_loader').show();
			console.log("1");
			$("#load").show();
			setTimeout(function () {
				$('.page_loader').hide();
				$("#load").hide();
			}, 10000);
		});
		<?php
           foreach($classi_name as $cl)
		   {
			
		?>
	    var clas_name="<?php echo $cl ?>";
		// alert(clas_name);
		   <?php } ?>
		var s_token=generatetokenno(16);
	var r_url="https://koofamilies.com/index.php?vs="+s_token;

	 var myDynamicManifest = {

   "gcm_sender_id": "540868316921",

   "icons": [

		{

		"src": "https://koofamilies.com/img/logo_512x512.png",

		"type": "image/png",

		"sizes": "512x512"

	  }

	  ],

	  "short_name":'koofamilies Pos System',

	  "name": "koofamilies Pos System",

	  "background_color": "#4A90E2",

	  "theme_color": "#317EFB",

	  "orientation":"any",

	  "display": "standalone",

	  "start_url":r_url

	} 
	const stringManifest = JSON.stringify(myDynamicManifest);

	const blob = new Blob([stringManifest], {type: 'application/json'});

	const manifestURL = URL.createObjectURL(blob);

	document.querySelector('#my-manifest-placeholder').setAttribute('href', manifestURL);

	
	
	

if ('serviceWorker' in navigator) {
  window.addEventListener('load', function() {
    navigator.serviceWorker.register('/sw_new.js').then(function(registration) {
      // Registration was successful
      console.log('ServiceWorker registration successful with scope: ', registration.scope);
    }, function(err) {
      // registration failed :(
      console.log('ServiceWorker registration failed: ', err);
    });
  });
}
		var show_flash="<?php echo $show_flash; ?>";
		  // alert(errror);
		  if(show_flash)
		  {
			  swal("Welcome to KooFamilies!",show_flash, "success");
			  setTimeout(function(){ 
			  window.location.href = "index.php";
			  },5000); 
		  }

		  $('.lazy2').lazy({
		   
            placeholder: "https://koofamilies.com/img/logo.png"
        });
		if ("geolocation" in navigator){ //check geolocation available 
			navigator.geolocation.watchPosition(function(position) {

			
			},

			function(error) {

			if (error.code == error.PERMISSION_DENIED)

			{

			  // $('#location_model').modal('show');


			}

			});
		//try to get user current location using getCurrentPosition() method
		/*navigator.geolocation.getCurrentPosition(function(position){ 
			var latitude=position.coords.latitude;
			var longitude=position.coords.longitude;
           var sort_by="sort_distance";
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
				// $('#location_model').modal('show');

			}
		});*/
			}else{
				console.log("Browser doesn't support geolocation!");
			}  
    $('.merchant_select').select2();
	   $('.merchant_select').on('change', function(){
		   $('#please_wait').show();
		  // alert(this.value);
		  var selected_merchant_id=this.value;
		  if(selected_merchant_id!='-1')
		  {
			  var s_token=generatetokenno(6);
			var m_url="https://www.koofamilies.com/view_merchant.php?sid="+selected_merchant_id+"&ms="+s_token;
			// alert(m_url);
			if(window.location.href.includes('orderview.php')) return false;
			$('.page_loader').removeAttr('style');
			$("#load").removeAttr('style');;
			$('.page_loader').show();
			
			$("#load").show();
			window.location.href =m_url;	
			setTimeout(function () {
				$('.page_loader').hide();
				$("#load").hide();
			}, 6000);
					
		  }
		});
		 $("#merchant_submit_form").on("submit", function(e){
            if($(".merchant_select").val() == "-1"){
                e.preventDefault();
                
                if($("#product_search").val() !== '')
                    // window.location.href = `./product_search.php?p=${encodeURIComponent($("#product_search").val()).toLowerCase()}&lat=${coordinates.lat}&lng=${coordinates.lng}`;
                    window.location.href = `./product_search.php?p=${encodeURIComponent($("#product_search").val()).toLowerCase()}`;

            }
		   $('#please_wait').show();

		 })
		$( ".popular_filter" ).change(function() {
		 var sort_by=this.value;
		 if(sort_by=="sort_name")
		 {
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

			  // $('#location_model').modal('show');


			}

			});
		//try to get user current location using getCurrentPosition() method
		/*navigator.geolocation.getCurrentPosition(function(position){ 
			var latitude=position.coords.latitude;
			var longitude=position.coords.longitude;

			if(latitude && longitude)
			{
				$.ajax({
				  type: "POST",
				  url: "r_list.php",
				  data: {latitude:latitude, longitude:longitude,sort_by:sort_by,type:"popular"},
				  cache: false,
				  success: function(data) {
					 $('.owl-stage').html(data);   
				  }
				  });	
			}
			else
			{
				// $('#location_model').modal('show');

			}
		});*/
			}else{
				console.log("Browser doesn't support geolocation!");
			}
		 }
		}); 
			$( "#search_location_1").click(function() {
			 var sort_by="sort_distance";
			 $('#please_wait_location').show();
			$('.all_restro_sort').val("sort_distance");
		 // alert(sort_by);
		 // return false;
		  $("#search_location_1").css("background-color", "gray");
      
		 if(sort_by=="sort_name")
		 {   
			// location.reload(true);	
			var sort_by="sort_name";
			$.ajax({
				  type: "POST",
				  url: "config_r_list.php",
				  data: {sort_by:sort_by,type:"sort_name"},
				  cache: false,
				  success: function(data) {
					 $('.all_merchant_list').html(data);     
				  }
				  });
		 }  
		 else  if(sort_by=="sort_distance")
		 {
			  $('html, body').animate({
					'scrollTop' : $(".search_location_div").position().top
				});
			if ("geolocation" in navigator){ //check geolocation available 
			navigator.geolocation.watchPosition(function(position) {

			
			},

			function(error) {

			if (error.code == error.PERMISSION_DENIED)

			{

			  // $('#location_model').modal('show');


			}

			});
		//try to get user current location using getCurrentPosition() method
		/*navigator.geolocation.getCurrentPosition(function(position){ 
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
					   $('#please_wait_location').hide();
					 $('.all_merchant_list').html(data);     
				  }
				  });	
			}
			else
			{
				// $('#location_model').modal('show');

			}
		});*/
			}else{
				console.log("Browser doesn't support geolocation!");
			}
		 }  
			 
		});  
		
		
	
	
		$( "#search_location").click(function() {
			 var sort_by="sort_distance";
			 $('#please_wait_location').show();
			$('.all_restro_sort').val("sort_distance");
		 // alert(sort_by);
		 // return false;
		  $("#search_location").css("background-color", "gray");
      
		 if(sort_by=="sort_name")
		 {   
			// location.reload(true);	
			var sort_by="sort_name";
			$.ajax({
				  type: "POST",
				  url: "config_r_list.php",
				  data: {sort_by:sort_by,type:"sort_name"},
				  cache: false,
				  success: function(data) {
					 $('.all_merchant_list').html(data);     
				  }
				  });
		 }  
		 else  if(sort_by=="sort_distance")
		 {
			  $('html, body').animate({
					'scrollTop' : $(".search_location_div").position().top
				});
			if ("geolocation" in navigator){ //check geolocation available 
			navigator.geolocation.watchPosition(function(position) {

			
			},

			function(error) {

			if (error.code == error.PERMISSION_DENIED)

			{

			  // $('#location_model').modal('show');


			}

			});
		//try to get user current location using getCurrentPosition() method
		/*navigator.geolocation.getCurrentPosition(function(position){ 
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
					   $('#please_wait_location').hide();
					 $('.all_merchant_list').html(data);     
				  }
				  });	
			}
			else
			{
				// $('#location_model').modal('show');

			}
		});*/
			}else{
				console.log("Browser doesn't support geolocation!");
			}
		 }  
			 
		});
		// $(document).find( ".all_restro_sort" ).change();
		// $('.all_restro_sort').val("sort_name");
		// var page = 1;
		var run_ajax_filter = function(){
			var sort_by = $('.all_restro_sort').val();
			var template = $('.config_all_merchant_list').attr('data-template');
			var per_page = $('.config_all_merchant_list').attr('data-per_page');
			var page = $('.config_all_merchant_list').attr('data-page');

			if(sort_by == "sort_name"){   
			// location.reload(true);	
				var sort_by="sort_name";

				$('.page_loader').hide();
				$(".ajx_shop_resp").show();
				console.log("3--test1");
				$.ajax({
				  	type: "POST",
				  	url: "config_r_list.php",
				  	data: {sort_by:sort_by,type:"sort_name", page: page, per_page: per_page, template: template},
				  	cache: false,
				  	success: function(data) {
						$(".ajx_shop_resp").hide();
					 	$('.config_all_merchant_list').html(data);     
					 	$('.page_loader').hide();
				  	}
				});
		 	}  
		 	else if(sort_by=="sort_distance"){
				if ("geolocation" in navigator){ //check geolocation available 
					/*navigator.geolocation.watchPosition(
						function(position) {},
						function(error) {
							if (error.code == error.PERMISSION_DENIED){}
						}
					);*/
					//try to get user current location using getCurrentPosition() method
					navigator.geolocation.getCurrentPosition(function(position){ 
						var latitude=position.coords.latitude;
						var longitude=position.coords.longitude;
						if(latitude && longitude){
							$('.page_loader').show();
							$(".ajx_shop_resp").show();
							console.log("5");
							$.ajax({
				  				type: "POST",
				  				url: "config_r_list.php",
				  				data: {latitude:latitude, longitude:longitude,sort_by:sort_by,type:"all", page: page, per_page: per_page, template: template},
				  				cache: false,
				  				success: function(data) {
									$(".ajx_shop_resp").hide();
					 				$('.config_all_merchant_list').html(data);     
					 				$('.page_loader').hide();
				  				}
				  			});	
						}
						else{
							// $('#location_model').modal('show');
						}
					});
				}
				else{
					console.log("Browser doesn't support geolocation!");
				}
		 	}
		};
		run_ajax_filter();
		$(document).on('click', '.config-btn-page-btn', function(e){
			e.preventDefault();
			var this_page = $(this).attr('data-page');
			$('.config_all_merchant_list').attr('data-page', this_page);
			// $( ".all_restro_sort" ).trigger('change');
			run_ajax_filter();
		});
		$( ".all_restro_sort" ).change(function() {
			$('.config_all_merchant_list').attr('data-page', 1);
			run_ajax_filter();
		/*
		 var sort_by=this.value;
		 // alert(sort_by);
		 // return false;
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

			  // $('#location_model').modal('show');


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
				// $('#location_model').modal('show');

			}
		});
			}else{
				console.log("Browser doesn't support geolocation!");
			}
		 }
		*/}); 
		});
	</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB4BfDrt-mCQCC1pzrGUAjW_2PRrGNKh_U&libraries=places" async defer></script> 
<script src="https://scripts.sirv.com/sirv.js" defer></script> 
		 <script type="text/javascript" src="extra/js/jquery.lazy.min_74facba505554b93155d59a4d2d7e78b.js" defer></script>
 <!--<a href="https://api.whatsapp.com/send?phone=60123945670" target="_blank"><img src ="images/iconfinder_support_416400.png" style="width:75px;height:75px;position: fixed;left:15px;bottom: 70px;z-index:999;"></a>
 -->
 <a href="https://chat.whatsapp.com/LdvomJRqXoIG6aXqsf5PgX" target="_blank"><img src ="images/iconfinder_support_416400.png" style="width:75px;height:75px;position: fixed;left:15px;bottom: 70px;z-index:999;"></a>

</body>
</html>