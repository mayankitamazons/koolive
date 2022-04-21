<?php 

include('config.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

session_start();


if(isset($_GET['cid']))
{
	$did=$_GET['cid'];   
	include_once('dlogin.php');

}



/* language - 18/01/2021 */
if($_SESSION['langfile'] == 'english'){
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


if(isset($_SESSION["langfile"]) && $_SESSION["langfile"] !=''){
	require_once ("languages/".$_SESSION["langfile"].".php");
}
/* language - 18/01/2021*/


if(isset($_REQUEST['locationsort'])){
	$_SESSION["locationsort"] = @$_REQUEST['locationsort'];
	//echo 'here'.$_GET['locationsort'];
}
$LocaSt=$_SESSION['locationsort'];
if($_SESSION['locationsort'] && $LocaSt)
{
	$LOCSQL = "and users.city='$LocaSt'" ;
	$l_state = "and u.city='$LocaSt'" ;
}
else
{
	$LOCSQL='';
	$l_state = '';
}

/*State Location*/
if(isset($_REQUEST['statesort'])){
	$_SESSION["statesort"] = @$_GET['statesort'];
}
if(isset($_REQUEST['onlystate']) && $_REQUEST['onlystate'] == 1 ){
	unset($_SESSION["locationsort"]);
}
$LocaState=$_SESSION['statesort'];
if($_SESSION['statesort'] && $LocaState)
{
	$LOCSTATESQL = " and u.m_state='$LocaState'" ;
}
else
{
	$LOCSTATESQL = '';
}

/* End state Location*/



if (empty($_SESSION["langfile"])) { $_SESSION["langfile"] = "english"; }
require_once ("languages/".$_SESSION["langfile"].".php");

$join_lang = '';
if($_SESSION['langfile']){
	//$join_lang = "&language=".$_SESSION['langfile']
}
					
// if(empty($_GET['vs']))
// {
	// $url="index.php?vs=".md5(rand()).$join_lang;

// header("Location:$url");
// exit();
// }
 
if(isset($_POST['merchant_select_form']))
{
	if($_POST['merchant_select'])
	{
		$sid=$_POST['merchant_select'];
		
		$check_slug_exists = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SQL_NO_CACHE * FROM users WHERE slug='".$sid."' AND user_roles = 2"));
		$m_languge = $check_slug_exists['default_lang'];
		//$url="http://k00families.com/view_merchant.php?sid=".$sid."&ms=".md5(rand());
		$url="http://koofamilies.com/merchant/".$m_languge."/".$sid."/".md5(rand());
			
		header("Location:$url");
		exit();
}
}	
?>
<?php
if($_GET['force_refresh'] == 'yes'){
	header('Clear-Site-Data: "cache", "cookies", "storage", "executionContexts"');
	//$url="https://www.koofamilies.com/";
	//header("Location:$url");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
<!-- Google Tag Manager -->
<?php include("includes1/head_google_script.php"); ?>
<!-- End Google Tag Manager -->
	<title>KooFamilies - Food & Grocery Delivery Service Near Me in Malaysia - Order Now Online</title>
	
    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="food delivery malaysia, food delivery near me, food delivery kuala lumpur, malaysia food delivery">
	<meta name="description" content="KooFamilies is an online food and grocery delivery service near me in Malaysia. Discover & book the best restaurants at the best price">
   
    <meta name="author" content="Ansonika">
   
	<meta name="google-site-verification" content="Z6bJ5nn73VFsUqndnr_-_5PamtATM-aN9iVSKGZmo0E" />
	<!-- Global site tag (gtag.js) - Google Analytics
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-R5Q7NVYRCL"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());

	  gtag('config', 'G-R5Q7NVYRCL');
	</script>
 -->

    <!-- Favicons-->
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" type="image/x-icon" href="img/apple-touch-icon-57x57-precomposed.png">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="72x72" href="img/apple-touch-icon-72x72-precomposed.png">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="114x114" href="img/apple-touch-icon-114x114-precomposed.png">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="144x144" href="img/apple-touch-icon-144x144-precomposed.png">

    <!-- GOOGLE WEB FONT -->
   
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- BASE CSS -->
	<link rel="stylesheet" href="./css/font-awesome.min.css">

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
  /*var OneSignal = window.OneSignal || [];
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
  });*/
  
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
<script src="jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

</head>





<?php /* START: INDEX OLD CODE -28-08-2021*/?>
<?php /*if(empty(@$_SESSION["locationsort"])) { ?>
<script>
    $(document).ready(function(){
        $("#myModal").modal('show');
    });
</script>
<?php } ?>
<div id="myModal" class="modal fade" style="top:100px">
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
					<p><a href="javascript:void(0);" link="https://www.koofamilies.com/index.php?locationsort=<?php echo $data['CityName'] ?>" class="locationbutton btn btn-primary"><?php echo $data['CityName'] ?></a></p>
					
					
					<?php	
					}	  
					?>
				</form>
					<img src="ajax-loader.gif" class="ajx_location_resp" style="display:none;"/>
					&nbsp;
					<span class="please_wait_text1" style="display:none;color:red">Please wait ....</span>
				
				
            </div>
			<div class="modal-footer">
         
        </div>
        </div>
    </div>
</div>
<?php */?>

<?php /* Language Model - 18/01/2021 ?>
<?php if($open_language_modal == 1 && $_SESSION["locationsort"] != ''){?>
<script>
    $(document).ready(function(){
		$("#myModal_language").modal({
			show: false,
			backdrop: 'static'
		});
	
		$("#myModal_language").modal('show');
		
    });
</script>
<div id="myModal_language" class="modal fade" style="top:100px">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Select your Language <?php //echo "===".$_SESSION["locationsort"];?> </h5>
                <!--<button type="button" class="close" data-dismiss="modal">&times;</button>-->
            </div>
            <div class="modal-body">
				<form name="language_form" id="language_form">
				
					<a href="index.php?vs=<?php echo md5(rand()); ?>&language=english&locationsort=<?php echo $_SESSION["locationsort"] ;?>" class="btn btn-primary btn_language" >English</a>
					<a href="index.php?vs=<?php echo md5(rand()); ?>&language=chinese&locationsort=<?php echo $_SESSION["locationsort"] ;?>" class="btn btn-success btn_language" >华语</a>
					<a href="index.php?vs=<?php echo md5(rand()); ?>&language=malaysian&locationsort=<?php echo $_SESSION["locationsort"] ;?>" class="btn btn-info btn_language" >Malay</a>
					<br/>
					<img src="ajax-loader.gif" class="ajx_lang_resp" style="display:none;padding-top:10px"/>
					&nbsp;
					<span class="please_wait_text" style="display:none;color:red">Please wait ....</span>
					
							  
				</form>
            </div>
			<div class="modal-footer">
		
			</div>
        </div>
    </div>
</div>
<?php }?>
<?php  Language Model - 18/01/2021 */?>

<?php /* END: INDEX OLD CODE -28-08-2021*/?>
<?php /*NEW INDEX CODE*/?>
<?php if(empty(@$_SESSION["locationsort"]) && empty(@$_SESSION["statesort"])) { ?>
	<script>
		$(document).ready(function(){
			$("#myModal").modal('show');
		});
	</script>
<?php } ?>
<script>
function toggle(n) {
    var menus = document.getElementsByClassName("submenu");
	var element = document.getElementsByClassName("submenuparent");
	
	for(var i=0;i<menus.length;i++){
		  console.log(element[i]+"===="+i);
        if((i == (n-1)) && (menus[i].style.display != "block")){
            menus[i].style.display = "block";
			if (typeof element[i] === 'undefined') {}else{element[i].classList.add("active_city");}
        }else{
            menus[i].style.display = "none";
			if (typeof element[i] === 'undefined') {}else{element[i].classList.remove("active_city");}
		}
    } 
};
</script>
<div id="myModal" class="modal fade" style="">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header pt-1 pl-2 pr-2 pb-2" style="padding:unset">
				<?php 
				$h_name = $_SESSION["statesort"];
				if($_SESSION["locationsort"] != ''){
					$h_name = $_SESSION["statesort"].", ".$_SESSION["locationsort"];
				}?>
				<h5 class="modal-title">Select Ordering location <?php echo $h_name; ?></h5>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">

				<style>

				.tabs {
					margin: 2px 5px 0px 5px;
					padding-bottom: 10px;
					cursor: pointer
				}

				.tabs:hover,
				.tabs a.active {
					border-bottom: 1px solid #2196F3
				}
				.tab-pannel-box a.tabs {
					margin: 0 4px 0px 0;
					background: #dee2e6;
					color: #000;
					padding: 6px 20px;
					border: 0 !important;
					text-decoration: none !important;
				}

				.tab-pannel-box  a.tabs.active ,.tab-pannel-box  a.tabs:hover {background: #e75480;color: #fff;}
				.tab-pannel-box  .nav-tabs {
					border-bottom: 1px solid #dee2e6;
					height: 29px;
				}
				.modal-content {
					z-index: 9;
				}
				.tab-content h6 {
					margin-top: 10px;
					color: red;
				}
				@media screen and (max-width: 991px){
					.tab-pannel-box a.tabs {
						padding: 6px 9px;
					}
				}
				.submenu {
					display: none;
					list-style-type: none;
				}
				.locationNewbutton {
					margin-top: 2%;
					background-color: unset !important;
					padding: unset !important;
					color: #000;
					font-size: unset !important;
				}
				.locationSubNewbutton{
					margin-top: 2%;
					background-color: unset !important;
					padding: unset !important;
					color: #000;
					font-size: unset !important;
				}
				.dont_see_location{
					font-weight: bold;
					color: #cb6678;
					cursor: pointer;
					margin-bottom: unset !important;
				}
				.list-group-item{
					padding:unset !important;
				}
				.city_pp{
					margin: 3px !important;
				}
				.active_city{
					background:#f7dee3 !important;
					font-weight:bold !important;
				}
			</style>


			<div role="tabpanel" class="tab-pannel-box">
				<!-- Nav tabs -->
				<ul class="nav nav-tabs" role="tablist">
					
					<li role="presentation" ><a href="#browseTab" class="tabs active" aria-controls="browseTab" role="tab" data-toggle="tab">Search By City</a>

					</li>
					<li role="presentation" class="active "><a href="#uploadTab" class="tabs " aria-controls="uploadTab" role="tab" data-toggle="tab">Search By State</a>

					</li>
				</ul>
				<!-- Tab panes -->
				<div class="tab-content">
						<div role="tabpanel" class="tab-pane active" id="browseTab">
						<!--<h6><strong>Note :</strong> lorem</h6>-->
						<form>
							
							<select class='m_state_drop form-control ' name="m_state" style="height: auto;width: 100%; margin-top: 10px;font-size: 18px;font-weight: bold;" data-id="<?php echo $row['id']; ?>">
								<option value="1">Select State</option>
								<?php
								$sql11 = mysqli_query($conn, "SELECT StateName FROM city GROUP BY StateName order by StateName asc");
								while($data11 = mysqli_fetch_array($sql11))
								{
									if($_SESSION["statesort"] == $data11['StateName']){
										echo'<option value="'.strtolower($data11['StateName']).'" selected>'.$data11['StateName'].'</option>';
									}else{
										echo'<option value="'.strtolower($data11['StateName']).'" selected>'.$data11['StateName'].'</option>';
									}

								}
								?>
							</select>

							<ul  class="list-group pt-3 text-center main_ul_sec" 
							<?php //if(!isset($_SESSION["statesort"])){ echo 'style="list-style-type: none;display:none"';}else{echo 'style="list-style-type: none;"';}?>>
							<?php 
							$sql = mysqli_query($conn, "SELECT CityID,StateName,CityName  FROM city WHERE 0=0 GROUP BY CityName");
							$selected = '';
							$ck= 1;
							while($data = mysqli_fetch_array($sql))
							{
								$ss_style = "display:block;";
								if(strtolower($_SESSION["statesort"]) == strtolower($data['StateName'])){
									$ss_style = "display:block;";
								}else if(strtolower($data['StateName']) == 'johar'){
									$ss_style = "display:block;";
								}
								
								$count_shops1 = mysqli_fetch_assoc(mysqli_query($conn,"SELECT count(*) as shop_count FROM `users` as u WHERE u.isLocked = '0' AND u.user_roles = '2' AND u.show_merchant = 1 and u.city = '".$data['CityName']."' and u.m_state = '".$data['StateName']."' "));
								$temp_city_name = $data['CityName'];
								
							
								?>
								<li class="list-group-item submenuparent aa_city <?php echo strtolower($data['StateName']);?>" id="submenu_<?php echo $ck;?>">
								<p class="city_pp <?php echo strtolower($data['StateName']);?>" style="font-size:18px;font-weight:bold;<?php echo $ss_style;?>"><a href="javascript:void(0);" link="https://koofamilies.com/index_updatedesign.php?vs=<?php echo md5(rand()); ?>&locationsort=<?php echo $data['CityName'];?>&statesort=<?php echo $data['StateName']; ?>&language=<?php echo $_SESSION["langfile"];?>" class="locationbutton locationNewbutton"><?php echo $temp_city_name;//$data['CityName'] ?> (<?php echo $count_shops1['shop_count'];?>  <?php echo $language['shops'];?>)</a> 
								<a href="#" onclick="toggle(<?php echo $ck;?>);" >
									<span class="align-items-center">
										  <span class="fa fa-info-circle mr-3"></span>
									</span>
								</a>
								</p>
								<ul class="submenu list-group" style="background-color:white;">
									<?php $sql_location = mysqli_query($conn, "select * from location where l_status =1 and l_city_id=".$data['CityID']);
									while($data_location = mysqli_fetch_array($sql_location)){?>
										<li class="list-group-item locationbutton locationSubNewbutton" style="font-weight:normal;cursor:pointer" link="https://koofamilies.com/index_updatedesign.php?vs=<?php echo md5(rand()); ?>&locationsort=<?php echo $data['CityName'];?>&statesort=<?php echo $data['StateName']; ?>&language=<?php echo $_SESSION["langfile"];?>" ><?php echo $data_location['l_name'];?></li>
									<?php }?>
								</ul>
							</li>
							<?php $ck++;}?>
							</ul>
						</form>
						<p  class="ajx_location_resp" style="display:none;">
						<img src="ajax-loader.gif" class="ajx_location_resp" />
						&nbsp;
						<span class="please_wait_text1" style="display:none;color:red">Please wait ....</span>
						</p>
					</div>
			
			
					<div role="tabpanel" class="tab-pane " id="uploadTab">
						<!--<h6><strong>Note :</strong> lorem</h6>-->
						<h6><?php echo $language['the_state_notes'];?></h6>
						<form>
							<?php 
							$sql1 = mysqli_query($conn, "SELECT StateName FROM city GROUP BY StateName order by StateName asc");
							$selected1 = '';
							while($data1 = mysqli_fetch_array($sql1))
							{
								$count_shops_state = mysqli_fetch_assoc(mysqli_query($conn,"SELECT count(*) as shop_count FROM `users` as u WHERE u.isLocked = '0' AND u.user_roles = '2' AND u.show_merchant = 1 and u.m_state = '".$data1['StateName']."'"));
								
								?>
								<p><a href="javascript:void(0);" link="https://www.koofamilies.com/index_updatedesign.php?statesort=<?php echo $data1['StateName']; ?>&onlystate=1&language=<?php echo $_SESSION["langfile"];?>" class="locationbutton btn btn-primary"><?php echo $data1['StateName'] ?> (<?php echo $count_shops_state['shop_count'];?>  <?php echo $language['shops'];?>)</a></p>
								
								
								<?php	
							}	  
							?>
						</form>
						<p  class="ajx_location_resp" style="display:none;">
						<img src="ajax-loader.gif" class="ajx_location_resp" style="display:none;"/>
						&nbsp;
						<span class="please_wait_text1" style="display:none;color:red">Please wait ....</span>
						</p>
					</div>
					<p class="dont_see_location text-center" style="">Don't see your locaion here?</p>
				</div>
			</div>


		</div>
	</div>
</div>
</div>


<?php /* Don't see location popup*/?>
<script>
	$(document).ready(function(){
		$(".dont_see_location").click(function(){
			$("#myModal").modal('hide');
			$("#dontseeLocation").modal('show');
		});
		
		$("#submit_user_area").click(function (){
			var locationsList = $("#locationsList").val();
			$(".user_match_city").hide();
			$(".contact_admin_location").hide();
			$("#locationsList").css('border','');
			if(locationsList == ''){
				$("#locationsList").css('border','1px solid red');
				return false;
			}
			$('.please_wait_text1').show();
			$('.ajx_location_resp').show();
			$.ajax({
				url :'functions.php',
				 type:"post",
				 data:{locationsList:locationsList,method:"locationsList"},     
				 dataType:'json',
				 success:function(result){  
					$('.please_wait_text1').hide();
					$('.ajx_location_resp').hide();
					var data = JSON.parse(JSON.stringify(result));  
					if(data.status==true)
					{  
					   $(".user_match_city").show();
					   $(".user_match_city").html(data.msg);
					   $(".contact_admin_location").hide();
					}
					else
					{
						$(".user_match_city").hide();
						$(".contact_admin_location").show();
					}
					
				}
			});   
			
		});
	});
</script>
<div class="modal fade" id="dontseeLocation" tabindex="-1" role="dialog" aria-labelledby="dontseeLocation" aria-hidden="true">
  <div class="modal-dialog  modal-sm modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Can't find your location?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>We will help you to search for the city.!!</p>
		<form>
          <div class="form-group">
		    <label for="recipient-name" class="col-form-label">Your Area:</label>
              <input list="locationsLists" name="locationsList" id="locationsList" placeholder="">
			  <datalist id="locationsLists">
			  <?php $sql_location_1 = mysqli_query($conn, "select * from location where l_status =1");
				while($data_location_1 = mysqli_fetch_array($sql_location_1)){?>
				<option value="<?php echo $data_location_1['l_name'];?>">
			  <?php }?>
			  </datalist>
          </div>
			<p class="ajx_location_resp" style="display:none;">
			<img src="ajax-loader.gif"  />
			&nbsp;
			<span class="please_wait_text1" style="display:none;color:red">Please wait ....</span>
			</p>
		  <p class="user_match_city" style="display:none"><p>
		  <p style="display:none" class="contact_admin_location text-danger"> Sorry ! We can't able to find you location. Please contact the <a class="" target="_blank" href="https://chat.whatsapp.com/BNE7IoWEfwq5hWagQ2J9Fy">support team</a><p>
		  
        </form>
      </div>
      <div class="pb-2 text-center">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="submit_user_area" style="background: pink;border-color: pink;">Submit</button>
		<!--<button type="button" class="btn btn-primary" id="contact_admin_support" style="display:none">Contact Support</button>-->
      </div>
    </div>
  </div>
</div>
<?php /* END Don't see location popup*/?>

<?php /* Language Model - 18/01/2021 */?>
<?php if($open_language_modal == 1 && ($_SESSION["locationsort"] != '' || $_SESSION["statesort"] != '')){?>
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
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Select your Language </h5>
					<!--<button type="button" class="close" data-dismiss="modal">&times;</button>-->
				</div>
				<div class="modal-body">
					<form name="language_form" id="language_form">
						<a href="index.php?vs=<?php echo md5(rand()); ?>&language=english&locationsort=<?php echo $_SESSION["locationsort"];?>&statesort=<?php echo $_SESSION["statesort"];?>" class="btn btn-primary btn_language" >English</a>
						<a href="index.php?vs=<?php echo md5(rand()); ?>&language=chinese&locationsort=<?php echo $_SESSION["locationsort"];?>&statesort=<?php echo $_SESSION["statesort"];?>" class="btn btn-success btn_language" >华语</a>
						<a href="index.php?vs=<?php echo md5(rand()); ?>&language=malaysian&locationsort=<?php echo $_SESSION["locationsort"];?>&statesort=<?php echo $_SESSION["statesort"];?>" class="btn btn-info btn_language" >Malay</a>
						<br/>
						<img src="ajax-loader.gif" class="ajx_lang_resp" style="display:none;padding-top:10px"/>
						&nbsp;
						<span class="please_wait_text" style="display:none;color:red">Please wait ....</span>


					</form>
				</div>
			</div>
		</div>
	</div>
<?php }?>
<?php /* Language Model - 18/01/2021 */?>


<?php /*END INDEX CODE*/?>

<!--- Force Refresh -->
<?php //if($_GET['mode'] =='dev'){?>
<script>
    $(document).ready(function(){
		$(".myrefresh").click(function(){
			$("#myModal_forcerefresh").modal({
			show: false,
			backdrop: 'static'
			});
			$("#myModal_forcerefresh").modal('show');
			$("#myModal_language").modal('hide'); 
			$("#myModal").modal('hide');
			return false;
		});
		
    });
</script>
<?php //}?>
<div id="myModal_forcerefresh" class="modal fade" style="top:100px">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cache/Cookies </h5>
                <!--<button type="button" class="close" data-dismiss="modal">&times;</button>-->
            </div>
            <div class="modal-body">
				
				<h3>
				<?php if($_SESSION["langfile"] == 'chinese'){?>
				由于系统升级，我们这里将协助你把旧记录清楚。如果页面没有更新, <a href="https://koofamilies.com" target="_blank" class="" style="color:red;text-decoration:underline">请按这里</a>.
<?php }else{ ?>
			Due to upgrade of system, we need you to clear page history to avoid order error. if this page has no respond, please click <a href="https://koofamilies.com" target="_blank" class="" style="color:red;text-decoration:underline">HERE</a>

			<!--	This can clear cache/cookies that you kept with this website. It may take up to 2 minutes to complete. To proceed, please click 'Yes". If no respond, please click <a href="https://koofamilies.com" target="_blank" class="" style="color:red;text-decoration:underline">HERE</a>-->
				<?php }?>
				</h3>
			 </div>
			<div class="modal-footer">
				<a href="#" class=" force_no btn btn-primary btn_language" data-dismiss="modal" >NO</a>
				<a href="index.php?vs=<?php echo md5(rand()); ?>&force_refresh=yes" class="force_yes btn btn-success btn_language" >YES</a>
           
			</div>
        </div>
    </div>
</div>

<!-- END --->



<body>
<!-- Google Tag Manager (noscript) -->
<?php include("includes1/body_google_script.php"); ?>
<!-- End Google Tag Manager (noscript) -->

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
	
	
	<div class="page_loader2">
		<span id="load2"></span>
	</div>
	<style>
	/*loader Css */
	.page_loader2 {
		display:none;
		position: fixed;
		left: 0px;
		top: 0px;
		width: 100%;
		height: 100%;
		z-index: 999999;
		background-color: rgba(255, 255, 255, 0.5);
	}

	#load2 {
		background-image: url("ajax-loader2.gif");
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

	.load_parentcss2 {
		background: "transparent" !important;
		z-index: "-1" !important;
	}
	@media (max-width: 991px){
#logo {
    float: none;
    width: 76%; !important;
    text-align: center;
}
#logo img {
   margin-top:11px;
  }
  
  .login_mbl_button {
    display: block !important;
	
	}
}
.login_mbl_button{
	display:none ;
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
		<div id="logo" >
			<a href="index.php?vs=<?php echo md5(rand()); ?>">
                <!-- koofamilies logo -->
				 <img src="svgLog_second.svg" width="140" height="35" alt="" class="logo_normal">
                <img src="svgLog_first.svg" width="140" height="35" alt="" class="logo_sticky">
               
                <!-- koofamilies logo -->
			</a>
		</div>
		 <ul id="top_menu">
			<li style="">
			<?php if(!isset($_SESSION['login'])){	?>
					<a href="login.php" class="login_mbl_button btn btn-primary" style="font-size:16px"><?php echo $language['login']; ?></a>
			<?php }?>
			</li>
				
			<li style="color: white;font-size: 40px;border-radius: 14px;">
				<!--<a href="index.php?vs=<?php echo md5(rand()); ?>&force_refresh=yes"  title="Refresh" class="myrefresh"><i class="fa fa-refresh" style="padding-right:8px"></i></a>-->
				<a href="javascript:void(0)"  title="Refresh" class="myrefresh"><i class="fa fa-refresh" style="padding-right:8px"></i></a>
			</li>
			
			<?php if(isset($_SESSION['login'])){	
			 $coin_query="SELECT * FROM `special_coin_wallet` WHERE `user_id` = ".$_SESSION['login']." AND `merchant_id` = 6419 and coin_balance > 0";
			 #echo $coin_query;
			 $coindata = mysqli_fetch_assoc(mysqli_query($conn,$coin_query));
			 $coincount = mysqli_num_rows(mysqli_query($conn,$coin_query));
			if($coincount > 0){
			?>
			<li style="color: white;font-size: 18px;background: #589442;padding: 7px;border-radius: 14px;margin-top:14px;"><i class="fa fa-money" style="padding-right:8px"></i><?php echo "RM".number_format($coindata['coin_balance'],2); ?></li>
			
			
			<?php }} ?>
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

				<li class="submenu">
					<button onclick="window.location='https://www.koofamilies.com/become_merchant.php';" /  class="btn mb-2 mb-md-0 btn-primary btn-block" style="background: #ec8f6a;border: #ec8f6a;font-weight: bold;color: black;padding:8px"><?php echo $language['become_merchant_free']; ?></button>
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
											
											<span id="please_wait" style="color:red;display:none;">Please wait.....
											<?php //echo "===".$_SESSION['langfile'];?></span>
												<select class="merchant_select form-control" name="merchant_select">
												   <option value="-1"><?php echo $language['search_by_company']; ?></option>
													<?php
													//echo "(SELECT SQL_NO_CACHE  u.id,u.name,u.user_language,u.id,u.mobile_number,count(od.id) as order_count,'on' as tstatus FROM users as u Inner JOIN order_list as od ON od.merchant_id = u.id LEFT JOIN timings on u.id=timings.merchant_id WHERE name LIKE u.isLocked='0' and u.show_merchant=1 and u.default_lang !='2' and u.user_roles=2 $l_state and day = '".date(l)."' AND '".date('H:i')."' BETWEEN start_time and end_time group by od.merchant_id order by order_count desc) UNION ALL (SELECT SQL_NO_CACHE  u.id,u.name,u.user_language,u.id,u.mobile_number,count(od.id) as order_count,'off' as tstatus FROM users as u Inner JOIN order_list as od ON od.merchant_id = u.id LEFT JOIN timings on u.id=timings.merchant_id WHERE name LIKE u.isLocked='0' and u.show_merchant=1 and u.default_lang !='2' and u.user_roles=2 $l_state and day = '".date(l)."' AND '".date('H:i')."' NOT BETWEEN start_time and end_time group by od.merchant_id order by order_count desc)" ;
														if($_SESSION['langfile'] == 'malaysian'){
															//don't show the chinese merchnt shop
															$q_shop = "SELECT SQL_NO_CACHE  name,user_language,id,mobile_number FROM users WHERE name LIKE isLocked='0' and show_merchant=1 and default_lang !='2' and user_roles=2 $LOCSQL $LOCSTATESQL";
															//$q_shop = "(SELECT SQL_NO_CACHE  u.id,u.name,u.user_language,u.id,u.mobile_number,count(od.id) as order_count,'on' as tstatus FROM users as u Inner JOIN order_list as od ON od.merchant_id = u.id LEFT JOIN timings on u.id=timings.merchant_id WHERE name LIKE u.isLocked='0' and u.show_merchant=1 and u.default_lang !='2' and u.user_roles=2 $l_state and day = '".date(l)."' AND '".date('H:i')."' BETWEEN start_time and end_time group by od.merchant_id order by order_count desc) UNION ALL (SELECT SQL_NO_CACHE  u.id,u.name,u.user_language,u.id,u.mobile_number,count(od.id) as order_count,'off' as tstatus FROM users as u Inner JOIN order_list as od ON od.merchant_id = u.id LEFT JOIN timings on u.id=timings.merchant_id WHERE name LIKE u.isLocked='0' and u.show_merchant=1 and u.default_lang !='2' and u.user_roles=2 $l_state and day = '".date(l)."' AND '".date('H:i')."' NOT BETWEEN start_time and end_time group by od.merchant_id order by order_count desc)";
															$q_shop_on = "SELECT SQL_NO_CACHE  u.default_lang,u.slug,u.id as user_id,u.name,u.user_language,u.id,u.mobile_number,count(od.id) as order_count,'on' as tstatus FROM users as u LEFT JOIN order_list as od ON od.merchant_id = u.id LEFT JOIN timings on u.id=timings.merchant_id WHERE name LIKE u.isLocked='0' and u.show_merchant=1 and u.default_lang !='2' and u.user_roles=2 $l_state $LOCSTATESQL and ((day = '".date(l)."' AND '".date('H:i')."' BETWEEN start_time and end_time)  OR (day = '' )) group by u.id order by order_count desc";
															
														}else{
															$q_shop = "SELECT SQL_NO_CACHE  name,user_language,id,mobile_number FROM users WHERE name LIKE isLocked='0' and show_merchant=1  and user_roles=2 $LOCSQL $LOCSTATESQL";
															//$q_shop = "(SELECT SQL_NO_CACHE  u.id,u.name,u.user_language,u.id,u.mobile_number,count(od.id) as order_count,'on' as tstatus FROM users as u Inner JOIN order_list as od ON od.merchant_id = u.id LEFT JOIN timings on u.id=timings.merchant_id WHERE name LIKE u.isLocked='0' and u.show_merchant=1 and u.user_roles=2 $l_state and day = '".date(l)."' AND '".date('H:i')."' BETWEEN start_time and end_time group by od.merchant_id order by order_count desc) UNION ALL (SELECT SQL_NO_CACHE  u.id,u.name,u.user_language,u.id,u.mobile_number,count(od.id) as order_count,'off' as tstatus FROM users as u Inner JOIN order_list as od ON od.merchant_id = u.id LEFT JOIN timings on u.id=timings.merchant_id WHERE name LIKE u.isLocked='0' and u.show_merchant=1 and u.user_roles=2 $l_state and day = '".date(l)."' AND '".date('H:i')."' NOT BETWEEN start_time and end_time group by od.merchant_id order by order_count desc)";
															
															$q_shop_on = "SELECT SQL_NO_CACHE  u.default_lang,u.slug,u.id  as user_id,u.name,u.user_language,u.id,u.mobile_number,count(od.id) as order_count,'on' as tstatus FROM users as u LEFT JOIN order_list as od ON od.merchant_id = u.id LEFT JOIN timings on u.id=timings.merchant_id WHERE name LIKE u.isLocked='0' and u.show_merchant=1  and u.user_roles=2 $l_state $LOCSTATESQL and ((day = '".date(l)."' AND '".date('H:i')."' BETWEEN start_time and end_time)  OR (day = '' ))  group by u.id order by order_count desc";
															
															
															#echo $q_shop_off;
															
														}
														#echo $q_shop_on;
														//$select =mysqli_query($conn,"SELECT SQL_NO_CACHE  name,user_language,id,mobile_number FROM users WHERE name LIKE isLocked='0' and show_merchant=1  and user_roles=2 $LOCSQL");
														//$select =mysqli_query($conn,$q_shop);
														
														$select_on =mysqli_query($conn,$q_shop_on);
													
														
														
														
														
														/*while ($row=mysqli_fetch_assoc($select)) 
														{
															$statusonoff ='';
															if($row['tstatus'] == 'off'){
																	$statusonoff = $language['shop_index_status'];
															}
														 ?>
														 <option value="<?php echo $row['mobile_number']; ?>" ><?php echo $row['name'];?> </option>
														<?php }  */ 
														$shop_openArray = array();
														while ($row_on=mysqli_fetch_assoc($select_on)) 
														{
															$shop_openArray[$row_on['user_id']] = $row_on['user_id'];
														 ?>
														 <option m_languge="<?php echo $row_on['default_lang'] ;?>" value="<?php echo $row_on['slug']; ?>" ><?php echo $row_on['name'];?> <?php //echo $row_on['order_count'];?> </option>
														<?php } ?>
														
														
														<?php 
														$where_shoponusers = '';
														if(count($shop_openArray) > 0){
															$alreay_exits_shop = implode(",",$shop_openArray);
															$where_shoponusers = "and u.id NOT IN ($alreay_exits_shop)";
														}
														if($_SESSION['langfile'] == 'malaysian'){
															$q_shop_off = "SELECT SQL_NO_CACHE u.default_lang, u.slug, u.id as user_id,u.name,u.user_language,u.id,u.mobile_number,count(od.id) as order_count,'off' as tstatus FROM users as u LEFT JOIN order_list as od ON od.merchant_id = u.id LEFT JOIN timings on u.id=timings.merchant_id WHERE name LIKE u.isLocked='0' and u.show_merchant=1 and u.default_lang !='2' $where_shoponusers  and u.user_roles=2 $l_state $LOCSTATESQL and (day = '".date(l)."' OR '".date('H:i')."' NOT BETWEEN start_time and end_time ) group by u.id order by order_count desc";
														}else{
															//$q_shop_off = "SELECT SQL_NO_CACHE u.default_lang, u.slug, u.id  as user_id,u.name,u.user_language,u.id,u.mobile_number,count(od.id) as order_count,'off' as tstatus FROM users as u LEFT JOIN order_list as od ON od.merchant_id = u.id LEFT JOIN timings on u.id=timings.merchant_id WHERE name LIKE u.isLocked='0' and u.show_merchant=1 $where_shoponusers  and u.user_roles=2 $l_state $LOCSTATESQL and (day = '".date(l)."' OR '".date('H:i')."' NOT BETWEEN start_time and end_time) group by u.id order by order_count desc";
															
															$q_shop_off = "SELECT SQL_NO_CACHE u.default_lang, u.slug, u.id  as user_id,u.name,u.user_language,u.id,u.mobile_number,count(od.id) as order_count,'off' as tstatus FROM users as u LEFT JOIN order_list as od ON od.merchant_id = u.id LEFT JOIN timings on u.id=timings.merchant_id and (day = '".date(l)."' OR '".date('H:i')."' NOT BETWEEN start_time and end_time) WHERE name LIKE u.isLocked='0' and u.show_merchant=1 $where_shoponusers  and u.user_roles=2 $l_state $LOCSTATESQL  group by u.id order by order_count desc";
															
														}
														
														#echo $q_shop_off;
															$select_off =mysqli_query($conn,$q_shop_off);
														?>
														<?php 
														while ($row_off=mysqli_fetch_assoc($select_off)) 
														{
															
														 ?>
														 <option m_languge="<?php echo $row_off['default_lang'] ;?>" value="<?php echo $row_off['slug']; ?>"  style="color:red"><?php echo $row_off['name'];?>  <?php //echo  $row_on['order_count'];?>  <span ><?php //echo $language['shop_index_status'];?></span> </option>
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
					
					<?php 
					//count the shops
					
					$count_shops = mysqli_fetch_assoc(mysqli_query($conn,"SELECT count(*) as shop_count FROM `users` as u WHERE u.isLocked = '0' AND u.user_roles = '2' AND u.show_merchant = 1 $l_state $LOCSTATESQL"));
					//echo "SELECT count(*) as shop_count FROM `users` as u WHERE u.isLocked = '0' AND u.user_roles = '2' AND u.show_merchant = 1 $l_state $LOCSTATESQL";
					?>
					<button type="button"  id="search_location" style="margin-top:2%;background-color:#589442;color;black;padding: 7px;width: 250px;color: black;border-radius: 4px;" class="btn btn-primary"><?php echo $language['search_by_location']; ?> (<?php echo $count_shops['shop_count'];?>  <?php echo $language['shops'];?>)</button>
					<!--span id="search_location" style="margin-top: 2%;background-color: #589442;padding: 13px;" class="btn btn-primary">Search by location</span!--> 
					</div>
					
					<div class="col-xl-12 col-lg-12 col-md-12">
					<button type="button" data-toggle="modal" data-target="#myModal" style="margin-top:2%;background-color:pink;padding: 7px;width: 250px;color: black;border-radius: 4px;color;black" >
					<?php
					//echo $_SESSION['locationsort']."==".$open_language_modal;
								/*if($_SESSION['locationsort'] == 'Kulai'){
									echo $language['reselect_location_kulai'];
								}
								if($_SESSION['locationsort'] == 'Skudai/Tmn Rini'){
									echo $language['reselect_location_skudai'];
								}*/
								 echo $language['reselect_location']; ?>

						<?php 
						$h2_name = $_SESSION["statesort"];
						if($_SESSION["locationsort"] != ''){
							if($_SESSION["locationsort"]  == 'Skudai/Tmn Rini'){
								$temp_city_name = 'JB/Skudai/M.Austin/Gelang P. (50Km)';
								$h2_name = $_SESSION["statesort"].",".$temp_city_name;
							}else{
								$h2_name = $_SESSION["statesort"].",".$_SESSION["locationsort"];
							}
							
						}?>
						<b><?php echo "(".$h2_name.")";?></b>
						
								

					
					</button>
					</div>
					<div class="col-xl-12 col-lg-12 col-md-12" style="margin-top:2%">
					<a onclick="window.location='https://www.koofamilies.com/become_merchant.php';"   class="" style="font-weight: bold;color: white;padding:13px;font-size:20px;text-decoration:underline"><?php echo $language['become_merchant_free']; ?></a></li>
					</div>
				</div>
			</div>
				
		</div>
		
		
		<!----Start CODE 10.02.2021------------->
		<style>
		.service_m_item {
    text-align: center;
    margin-bottom: 25px;
}
.service_m_item .service_img_inner {
    border: 1px dashed #cecece;
    display: inline-block;
    border-radius: 50%;
    padding: 5px;
}.service_m_item .service_img {
    overflow: hidden;
    position: relative;
    z-index: 3;
}.rounded-circle {
    border-radius: 50%!important;
}.service_main_item_inner{
	width:100%;
	margin-top:45px;
}.service_text{
	padding:10px;
}
.h4_shop_cat_round{
	font-size:20px !important;
}
img {
    max-width: 100%;
}@media screen and (max-width:991px){
.service_text {
    padding: 10px 0;
    text-align: center;
}

.service_text a {
    display: block;
}

.service_text a h4 {
    font-size: 17px !important;
}
.service_main_item_inner{
	margin-left:0px  !important; 
}
}
img.loading-icon {
    margin: 0 auto;
}

.carousel_4 {
	margin-top: 30px;
}
.carousel_4 .strip figure{
	background-color: #ffffff;
}
.carousel_4 .owl-item .owl-lazy {
    max-width: 100% !important;
    height: 100%;
    object-fit: cover;
}
.carousel_4 .strip figure{
	height: 290px;
}
.carousel_4 .strip figure .item_title {
    min-height: 60px;
    display: flex;
    justify-content: center;
    align-items: center;
    background: yellow !important;
    padding: 10px !important;
}
.strip figure .item_title h3
{
	color:black !important;
}
.carousel_4  .owl-nav {
    top: 50%;
    transform: translateY(-50%);
    right: 0;
    left: 0;
}

.carousel_4 button.owl-prev {
    left: -40px;
    position: absolute;
}
.carousel_4 button.owl-prev {
    left: -40px;
    position: absolute;
}
.carousel_4 button.owl-next{
	 right: -40px;
    position: absolute;
}
@media screen and (max-width:1310px){
	.carousel_4 button.owl-prev {
		left: 0;
		background: #ddd !important;
	}
	.carousel_4 button.owl-next{
		right: 0px;
		background: #ddd !important;
	}
}
@media screen and (max-width:767px){
	span.select2.select2-container.select2-container--default {
	    width: 100% !important;
	}
}
.more_category_box{
display:none;
}
</style>
		<div class="row service_main_item_inner">
					<?php 
						$class_query = "SELECT * FROM `classfication_service` where status = 'y'  ORDER BY `classfication_service`.`category_order` ASC";
						$select_query =mysqli_query($conn,$class_query);
						$s = 1;	
						while ($row_class=mysqli_fetch_assoc($select_query)) 
						{
							$cls_more_cat = '';
							$manystatus = '';
								if($s > 6){
									$cls_more_cat = 'more_category_box';
									$manystatus = 'many';
								}
								if($_SESSION['langfile'] == "chinese" ){
									$cates_name = $row_class['classification_name_chiness'];
								}else{
									$cates_name = $row_class['classification_name'];
								}

						//echo $s;
					?>

					

					<?php if($s == 6){
						//if($s > 6){
						?>
						
						<div class="col-lg-2 col-4">
							<div class="service_m_item">
								<div class="service_img_inner">
									<div class="service_img">
										<img class="rounded-circle more_category" category="more_cate" src="images/more_category.png" alt="">
									</div>
								</div>
								<div class="service_text">
									<a href="javascript:void(0)" class="more_category" category="more_cat"><h4 class="more_cat_text">
									
									<?php echo $language['more_categories'];?>
									</h4></a>
								</div>
							</div>
						</div>
				
					<?php //}
}else{
if($row_class['image'] != ''){
	$imgs = $row_class['image'];
}else{
	$imgs = 'no-image.png';
}
?>
<div class="col-lg-2 col-4 <?php echo $cls_more_cat;?> ">
                        <div class="service_m_item">
                            <div class="service_img_inner">
                                <div class="service_img">
                                    <img class="rounded-circle shop_cat_round" morestatus="<?php echo $manystatus;?>" category="<?php echo $row_class['id'];?>" src="images/<?php echo $imgs;?>" alt="">
                                </div>
                            </div>
                            <div class="service_text">
                                <a href="javascript:void(0)" morestatus="<?php echo $manystatus;?>" class="shop_cat_round" category="<?php echo $row_class['id'];?>"><h4 class="h4_shop_cat_round"><?php echo $cates_name;?></h4></a>
                            </div>
                        </div>
                    </div>
					<?php }?>
					
				<?php $s++;}?>
<?php /*?>
                    <div class="col-lg-2 col-4">
                        <div class="service_m_item">
                            <div class="service_img_inner">
                                <div class="service_img">
                                    <img class="rounded-circle shop_cat_round" category="popular" src="images/popular.png" alt="">
                                </div>
                            </div>
                            <div class="service_text">
                                <a href="javascript:void(0)" class="shop_cat_round" category="popular"><h4 class="h4_shop_cat_round">Popular Shops</h4></a>
                            </div>
                        </div>
                    </div>
					<div class="col-lg-2 col-4">
                        <div class="service_m_item">
                            <div class="service_img_inner">
                                <div class="service_img">
                                    <img class="rounded-circle shop_cat_round" category="veg" src="images/vegan.png" alt="">
                                </div>
                            </div>
                            <div class="service_text">
                                <a href="javascript:void(0)" class="shop_cat_round" category="veg"><h4 class="h4_shop_cat_round">Vegetarian Shops</h4></a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-2 col-4">
                        <div class="service_m_item">
                            <div class="service_img_inner">
                                <div class="service_img">
                                    <img class="rounded-circle shop_cat_round" category="milktea" src="images/milk_tea.png" alt="">
                                </div>
                            </div>
                            <div class="service_text">
                                <a href="javascript:void(0)" class="shop_cat_round" category="milktea"><h4 class="h4_shop_cat_round">Milk Teas Shops</h4></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-4">
                        <div class="service_m_item">
                            <div class="service_img_inner">
                                <div class="service_img">
                                    <img class="rounded-circle shop_cat_round" category="fastfood" src="images/fast-food.png" alt="">
                                </div>
                            </div>
                            <div class="service_text">
                                <a href="javascript:void(0)" class="shop_cat_round" category="fastfood"><h4>Fast-food Shops</h4></a>
                            </div>
                        </div>
                    </div>
					<div class="col-lg-2 col-4">
                        <div class="service_m_item">
                            <div class="service_img_inner">
                                <div class="service_img">
                                    <img class="rounded-circle shop_cat_round" category="seafood" src="images/sea-food.png" alt="">
                                </div>
                            </div>
                            <div class="service_text">
                                <a href="javascript:void(0)" class="shop_cat_round" category="seafood"><h4>Sea-Food Shops</h4></a>
                            </div>
                        </div>
                    </div>
					
					<div class="col-lg-2 col-4">
                        <div class="service_m_item">
                            <div class="service_img_inner">
                                <div class="service_img">
                                    <img class="rounded-circle shop_cat_round" category="steambot" src="images/steam-bot.png" alt="">
                                </div>
                            </div>
                            <div class="service_text">
                                <a href="javascript:void(0)" class="shop_cat_round" category="steambot"><h4>Steambot shops</h4></a>
                            </div>
                        </div>
                    </div>
                
<?php */?>
                </div>
				
			<!--start ajax response category data-->
			<style>
			.index_hotel:hover{
				padding:10px !important;
				background-color: #007bff !important;;
			}
			</style>
			<span class="scroll_top_ajax"></span>
		<div class="container ajax_shop_response" style="margin-top: 5px;text-align:center">
		<img src="images/loading-icon.gif" class="loading-icon" style="display:none"/>
		</div>
						
			<!--End ajax response category data-->	
				
		
		<!----END CODE 10.02.2021------------->
		
		<!----START CODE 27.10.2021------------->
		<style>
		.special_product_div .strip {
			max-width: 300px;
			width: 50%;
			margin: 00 auto;
		}
		</style>
		<?php 
		function ceiling($number, $significance = 1)
{
    return (is_numeric($number) && is_numeric($significance)) ? (ceil(round($number / $significance)) * $significance) : false;
}
$LOCSQL11 = '';
//echo $_SESSION['locationsort'];
if($_SESSION['locationsort'])
{
	$LOCSQL11 = " and u.city='".$_SESSION['locationsort']."'" ;
}

$sprod = 'select *,u.id as u_id,u.slug as user_slug,prd.image AS p_image
from users as u
INNER JOIN products as prd ON prd.user_id = u.id
INNER JOIN about ON prd.user_id = about.userid
where u.user_roles = 2 and prd.show_index_page = 1 and u.shop_open=1 '.$LOCSQL11.'';
$q11 = mysqli_query($conn, $sprod);
$longitude = isset($_GET['lng']) ? floatval($_GET['lng']) : null;
$latitude = isset($_GET['lat']) ? floatval($_GET['lat']) : null;
$sprodrecords = mysqli_num_rows($q11);



		?>
		<div class="container special_product_div" <?php if($sprodrecords <= 0){?>style="display:none"<?php }?>>
			<div class="row special_product_div1">
				<div class="col-12">
					<div class="main_title version_2">
						<span><em></em></span>
						<div class="row">
							<div class="col-md-8">
								<h2><?php echo $language['special_products']; ?></h2>
							</div>
							
							<div class="owl-carousel owl-theme carousel_4">

<?php

while ($product = mysqli_fetch_assoc($q11)) {
		if ($longitude && $latitude)
		$user_id = $product['u_id'];
		//$avgRating = $product['avg_rating'];
		//$number_ratings = $product['ratings'];
		$wk_query_on = mysqli_query($conn,"select start_time , end_time , merchant_id, DAY FROM `timings` WHERE merchant_id = ".$user_id." and  timings.day = '".date(l)."' AND '".date('H:i')."' BETWEEN timings.start_time and timings.end_time");
		$onrecords = mysqli_num_rows($wk_query_on);
		
		$wk_query_off = mysqli_query($conn,"select start_time , end_time , merchant_id, DAY FROM `timings` WHERE merchant_id = ".$user_id." and  timings.day = '".date(l)."' AND '".date('H:i')."' NOT BETWEEN timings.start_time and timings.end_time");
		$offrecords = mysqli_num_rows($wk_query_off);
		
		$get_workingHr = mysqli_fetch_assoc(mysqli_query($conn, "SELECT start_time , end_time , merchant_id, DAY FROM `timings` WHERE   DAY = DAYNAME( NOW() )AND  merchant_id = '$user_id' "));
		/*$get_workingHr = mysqli_fetch_assoc(mysqli_query($conn, "SELECT start_time , end_time , merchant_id, DAY FROM `timings` WHERE   DAY = DAYNAME( NOW() )AND  merchant_id = '$user_id' "));
		$inWorkingHours = false;
						
		if (!empty($get_workingHr['start_time']) && !empty($get_workingHr['end_time'])) {
			$currentTime    = strtotime(date("H:i"));
			if (strtotime($get_workingHr['start_time']) < $currentTime && $currentTime < strtotime(	$get_workingHr['end_time'])) {
				$inWorkingHours = true;
			}else{
				$work_str="Working Time :".$get_workingHr['start_day']." ".$get_workingHr['start_time']." to "." ".$get_workingHr['end_day']." ".$get_workingHr['end_time'];
			}
		}*/
		?>
		<div class="item" >
			<div class="card strip showLoader6">
				<?php if($product['free_delivery_check'] ==1 || $product['order_min_charge'] > 0){?>
				<style>
					figure {
						position: relative;
					}
					.tooo {
						position: absolute;
						z-index: 2;
						top: 0;
						left: 0;
						right: auto;
						transform: none;
						background-color: #e06b80;
						margin: 0 auto;
						height: 34px;
						width: 183px;
					}
					.tooo p{
					  color : white;
					  font-weight:bold;
					  font-size : 16px;
					  padding : 5px
					}
				</style>
				<?php }?>
								
				<figure class="showLoader6" style="border-radius: 5px 5px 0 0;height:200px">
					<?php if($product['free_delivery_check'] ==1){?>
						<div class="tooo" style="width:140px;height: 27px;">
						  <p style="font-size:12px;"><?php echo $language['free_delivery_40']; ?></p>
						</div>
					<?php }?>
					<?php if($product['order_min_charge'] > 0){?>
						<div class="tooo" <?php if($product['free_delivery_check'] == 1){?>style="width:140px;background:red;top:27px;height: 27px;"<?php }else{?>style="width:140px;background:red;height: 27px;"<?php }?>>
						  <p style="font-size:12px;"><?php echo $language['mini_order_value']; ?> RM <?php echo $product['order_min_charge'];?></p>
						</div>
					<?php }?>
					
					<?php if($product['one_product_offer'] == 1 && $product['one_qty_prd_offer'] == 1){?>
					<div class="tooo" <?php if($product['free_delivery_check'] == 1 && $product['order_min_charge'] > 0){?>style="width:140px;background:#cd7011;top:54px;height: 27px;"<?php }else if($product['order_min_charge'] > 0 || $product['free_delivery_check'] == 1 ){?>style="width:140px;background:#cd7011;height: 27px;top:27px;"<?php }else{?>style="width:140px;background:#cd7011;height: 27px;"<?php }?>>
						  <p style="font-size:12px;"><?php echo $language['limit_1_order']; ?></p>
					</div>
					<?php }?>
						
						
					<?php if ($product['p_image']) {?>
						<img src="<?= "{$image_cdn}product/{$product['p_image']}" ?>" data-src="<?= "{$image_cdn}about_images/{$product['image']}" ?>" class="img-fluid lazy loaded" alt="" data-was-processed="true">
					<?php } else {?>
                        <img src="<?= "{$image_cdn}about_images/{$product['image']}" ?>" data-src="<?= "{$image_cdn}about_images/{$product['image']}" ?>" class="img-fluid lazy loaded" alt="" data-was-processed="true">
                    <?php } ?>
					<?php 
						$default_lang = $product['default_lang'];  
						if($default_lang==1)
						{
						  $langfile="english";
						} else if($default_lang==2)
						{
						  $langfile="chinese";
						} else if($default_lang==3)
						{
						  $langfile="malaysian";
						}
						$new_URL = $site_url .'/merchant/'.$langfile.'/'.$product['user_slug'].'/'.$product['product_slug'].'/'.md5(rand());
					?>
						<a href="<?php echo $new_URL?>" class="strip_info">
						<div class="item_title index_hotel">
						<h3><?= $product['name'] ?></h3><small><br></small>
						</div>
						
						</a>
				</figure>
				
				
				<div class="card-body pt-0 pl-2 pr-2 pb-2">
					<div class="row mr-0 ml-0">
						<?php /*if ($avgRating) {?>
                            <div class="col-md-12 p-0">
                                <div class="score float-right">
                                    <span class="text-secondary">Merchant<br />rating</span>
                                    <strong><?= ($avgRating) ? number_format($avgRating, 1) + 0 : '-' ?> / 5</strong>
                                </div>
                                <?= $product['name'] ?>
                            </div>
							
							<div class="col-md-12 p-0">
								<div class="float-right">
									<small>Has <?= $number_ratings ?> reviews</small>
								</div>
							</div>
                        <?php } else {*/ ?>
							
						<?php //} ?>
						
                        <?php if($offrecords > 0){?>
                            <div class="col-md-12 p-0 text-danger">
								<?php echo $product['working_text'];?> 
								<?php if($product['not_working_text'] != ''){
									echo ",".$product['not_working_text'];
								}?>
							</div>
                        <?php  } ?>
						
						<?php 
							if($product['price_hike'] > 0){
								//echo '1<br/>';
								$product_price_hike = $product['product_price'] *(($product['price_hike'] / 100) + 1);
							}else{
								//echo '2<br/>';
								$product_price_hike = $product['product_price'];
							}
							//echo $product['product_price']."====".$product['price_hike']."<<>>".$product_price_hike;
						?>
						
						
						<div class="col-md-12 p-0" >
							<p><b><?= $product['product_name'] ?></b> <span class="text-info" style="    float: right;background: none; width: auto; height: auto;">( Rm <?= ceiling($product_price_hike, 0.05); ?> ) </span></p>
						</div>
						
						<?php /*if($product['one_product_offer'] == 1 && $product['one_qty_prd_offer'] == 1){?>
							<br/><?php echo $language['only_one_qty_per_order'];?>
						<?php }*/?>
						<?php
							$start_day_diminutive = date('D',strtotime($working_hours->start_day));
							$end_day_diminutive = date('D',strtotime($working_hours->end_day));
						?>
						<!--
                            <div class="col-md-12 p-0"><b>Delivery hours:</b> 
								<span class="text-info" style="float:right;background:none"><?= $get_workingHr['start_time'] ?> - <?= $get_workingHr['end_time'] ?></span>
                            </div>-->
                                      
                                        
                        </div>
                    </div>
                </div>
            </div>
                <?php }?>
							</div>
							
						</div>	
					</div>	
				</div>
			</div>
		</div>	
					
								
		<!----END CODE  27.10.2021------------->
		
		
		
		<!-- popular restaurants  margin_60_40-->
		<div class="container  search_location_div">
			<div class="row all_merchant_list">
				<div class="col-12">
					<div class="main_title version_2">
						<span><em></em></span>
						<div class="row">
							  <div class="col-md-8"><h2><?php echo $language['near_by_shop_2']; ?>
							  &nbsp;
							  <img src="ajax-loader.gif" class="ajx_shop_resp" style="display:none"/>
							  </h2>
							  
							  </div>   
							
							  <div style="margin: 20px 0 0 0;float: right;" class="col-md-2">
							  <select class="form-control all_restro_sort">
								<option value="sort_name" selected><?php echo $language['sort_by_name'];?></option>
								   <option value="sort_distance"><?php echo $language['sort_by_nearby'];?></option>
							  </select>
							</div>
							  <div style="margin: 20px 0 0 0;float: left;" class="col-md-2">
							<a href="merchant_find.php"><?php echo $language['view_all_text'] ;?></a>
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
		                <a href="https://www.koofamilies.com/become_merchant.php" class="btn_1">JOIN NOW</a>
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
                            
							<li><a href="about.php">About us</a></li>
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
 <link href="jquery-ui.css" rel="Stylesheet"></link>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script type="text/javascript" src="js/sweetalert.min.js" defer></script>
  <script src="extra/js/common_scripts.min.js" defer></script>
  <script src="extra/js/common_func.js" defer></script>
  
  <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<script src="extra/js/select2.min_da99e0cfb43d832f77954298a0557ca5.js" defer></script>
  <!-- SPECIFIC SCRIPTS -->
  <script src="extra/js/modernizr.min.js" defer></script>
  
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
			
			$('.please_wait_text').show();
			$('.ajx_lang_resp').show();
			$(this).prop('disabled', true);
			$(this).css('background-color', 'gray');
		});
		
		jQuery(document).on("click", '.locationbutton', function() {
			//delay('20');
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
		  var m_languge = '<?php echo $_SESSION["langfile"];?>';
		  if(selected_merchant_id!='-1')
		  {
			  var s_token=generatetokenno(6);
			//var m_url="https://www.koofamilies.com/view_merchant.php?sid="+selected_merchant_id+"&ms="+s_token;
			var m_url="<?php echo $site_url;?>/merchant/"+m_languge+"/"+selected_merchant_id+"/"+s_token;
			
			// alert(m_url);
			if(window.location.href.includes('orderview.php')) return false;
			$('.page_loader').removeAttr('style');
			$("#load").removeAttr('style');;
			$('.page_loader').show();
			console.log("12");
			$("#load").show();
			setTimeout(function () {
				$('.page_loader').hide();
				$("#load").hide();
			}, 10000);
			window.location.href =m_url;			
		  }
		});
		
		 $("#merchant_submit_form").on("submit", function(e){
            if($(".merchant_select").val() == "-1"){
                e.preventDefault();
                
                if($("#product_search").val() !== ''){
                    var languge_set = '<?php echo $_SESSION["langfile"];?>';
					var locationsort = '<?php echo $_SESSION["locationsort"];?>';
					var p_name = encodeURIComponent($("#product_search").val()).toLowerCase();
					// window.location.href = `./product_search.php?p=${encodeURIComponent($("#product_search").val()).toLowerCase()}&lat=${coordinates.lat}&lng=${coordinates.lng}`;
					window.location.href = './product_search.php?p='+p_name+'&language='+languge_set+'&locationsort='+locationsort;
                    //window.location.href = `./product_search.php?p=${encodeURIComponent($("#product_search").val()).toLowerCase()}`;
				}

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
		
		$(".m_state_drop").change(function(){
			var m_state = $(this).val();
			$(".aa_city").hide();
			$(".main_ul_sec").show();
			if(m_state == 1){
				$(".aa_city").hide();
			}else{
				$("."+m_state).show();
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

			$(".ajx_shop_resp").show();
			if(sort_by == "sort_name"){   
			// location.reload(true);	
				var sort_by="sort_name";

				$('.page_loader').show();
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
							console.log("5");
							$(".ajx_shop_resp").show();
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
		
		
		
	
		//ajax shop response
		$(document).ready(function(){
			$(".shop_cat_round").click(function(){
				//$(".ajax_shop_response").focus();
				//var elmnt = document.getElementsByClassName("scroll_top_ajax");
				//	elmnt[0].scrollIntoView();
					
				$('html, body').animate({
                    scrollTop: $(".ajax_shop_response").offset().top
                }, 2000);
				
				
				$(".ajax_shop_response").html('<img src="images/loading-icon.gif" class="loading-icon" style="display:none"/>');
				$(".loading-icon").show();
				var category = $(this).attr('category');
				var morestatus = $(this).attr('morestatus');
				var cartData = {};
				cartData['category'] = category;
				cartData['morestatus'] = morestatus;
				cartData['type'] = category;
				jQuery.post('/shopcategoryresponse.php', cartData, function (result) {
				//var response = jQuery.parseJSON(result);
				//console.log(result);
					$(".loading-icon").hide();
					$(".ajax_shop_response").html(result);
					
					
					$('.carousel_4').owlCarousel({
			items: 4,
			loop: false,
			margin: 20,
			dots:false,
            lazyLoad:true,
			navText: ["<i class='arrow_carrot-left'></i>","<i class='arrow_carrot-right'></i>"],
			nav:true,
			responsive: {
			0: {
				items: 1,
				nav: false,
				dots:true
			},
			560: {
				items: 2,
				nav: false,
				dots:true
			},
			768: {
				items: 2,
				nav: false,
				dots:true
			},
			991: {
				items: 3,
				nav: true,
				dots:false
			},
			1230: {
				items: 4,
				nav: true,
				dots:false
			}
		}
		});


				});

			});
		});
	</script>
	
<?php 
/* START: stores link*/
if(isset($_GET['stores']) && $_GET['stores'] != ''){ 
	$stores = $_GET['stores'];
	$ss_style = $_GET['many']
?>
	<?php if($ss_style == 'many'){?>
		<script>
		var session_lang = "<?php echo $_SESSION['langfile'];?>";
		var more_cat = 'More categories';
		var hide_cat = 'Hide categories';
			
		if(session_lang == "chinese" ){
			var more_cat = '更多种类';
			var hide_cat = '隐藏类型';
		}

		var txt = $(".more_category_box").is(':visible') ? more_cat : hide_cat;
		$(".more_cat_text").text(txt);
		$(".more_category_box").slideToggle();
		</script>
	<?php }?>
<script>
	$('html, body').animate({
		scrollTop: $(".scroll_top_ajax").offset().top
	}, 2000);
				
	$(".ajax_shop_response").html('<img src="images/loading-icon.gif" class="loading-icon" style="display:none"/>');
	$(".loading-icon").show();
	var category = '<?php echo $stores;?>';
	var cartData = {};
	cartData['category'] = category;
	cartData['type'] = category;
	jQuery.post('/shopcategoryresponse.php', cartData, function (result) {
		$(".loading-icon").hide();
		$(".ajax_shop_response").html(result);
		$('.carousel_4').owlCarousel({
			items: 4,
			loop: false,
			margin: 20,
			dots:false,
            lazyLoad:true,
			navText: ["<i class='arrow_carrot-left'></i>","<i class='arrow_carrot-right'></i>"],
			nav:true,
			responsive: {
			0: {
				items: 1,
				nav: false,
				dots:true
			},
			560: {
				items: 2,
				nav: false,
				dots:true
			},
			768: {
				items: 2,
				nav: false,
				dots:true
			},
			991: {
				items: 3,
				nav: true,
				dots:false
			},
			1230: {
				items: 4,
				nav: true,
				dots:false
			}
		}
		});

	});

	
	
</script>
<?php }
/* END: stores link*/?>	
<script>
			$(document).ready(function(){
				$(".more_category").on("click", function () {
					var session_lang = "<?php echo $_SESSION['langfile'];?>";
					var more_cat = 'More categories';
					var hide_cat = 'Hide categories';
						
					if(session_lang == "chinese" ){
						var more_cat = '更多种类';
						var hide_cat = '隐藏类型';
					}

					var txt = $(".more_category_box").is(':visible') ? more_cat : hide_cat;
					$(".more_cat_text").text(txt);
					$(".more_category_box").slideToggle();
				});

			});
			</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB4BfDrt-mCQCC1pzrGUAjW_2PRrGNKh_U&libraries=places" async defer></script> 
<script src="https://scripts.sirv.com/sirv.js" defer></script> 
		 <script type="text/javascript" src="extra/js/jquery.lazy.min_74facba505554b93155d59a4d2d7e78b.js" defer></script>
 <a href="<?php echo $helpLink ;?>" target="_blank"><img src ="images/iconfinder_support_416400.png" style="width:75px;height:75px;position: fixed;left:15px;bottom: 70px;z-index:999;"></a>

</body>
</html>
<!-- Facebook Pixel Code -->
<script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', '229277018358702');
  fbq('track', 'PageView');
</script>
<!-- End Facebook Pixel Code -->
</script>