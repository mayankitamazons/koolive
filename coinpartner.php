<?php
include("config.php");

// include_once('php/SectionTable.php');
// Start of Hire's work
// Load merchant's product with QR
$p_status='';
if(!empty($_GET['status'])){
	$p_status=$_GET['status'];
}
$mobile_otp_verify="n"; 
$custom_msg="n";
// $_GET['m_id']=5326;
if(!empty($_GET['m_id'])){
    $m_id = $_GET['m_id'];
    $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='$m_id' and user_roles='2'"));
    $merchant_name = $product['name'];
    $merchant_mobile_number = $product['mobile_number'];
	if($merchant_mobile_number=="60127771833")
		$_SESSION["langfile"] = "chinese";   
    $_SESSION['invitation_id'] = $product['referral_id'];
    $merchant_id=$_SESSION['merchant_id'] = $product['id'];
    $_SESSION['address_person'] = $product['address'] ;
    $_SESSION['latitude'] = $product['latitude'] ; 
    $_SESSION['longitude'] = $product['longitude'] ;
    $_SESSION['IsVIP'] = $product['IsVIP'] ;
    $_SESSION['mm_id']= $product['id'];
    $_SESSION['special_coin_name']= $product['special_coin_name'];
	
     
} 
// $sectionTablesList = $sectionTablesObj->getList($sectionTableFilter);
$bank_data = isset($_SESSION['login']) ? mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$_SESSION['login']."'")) : '';
$check_number=$bank_data['mobile_number'];
$user_koo_coin=$bank_data['balance_inr'];
$check_number=str_replace("60","",$check_number);
$nature_array = array(
        "Foods and Beverage, such as restaurants, healthy foods, franchise, etc",
        "Motor Vehicle, such as car wash, repair, towing, etc",
        "Hardware, such as household, building, renovation to end users",
        "Grocery Shop such as bread, fish, etc retails shops",
        "Clothes such as T-shirt, Pants, Bra, socks,etc",
        "Business to Business (B2B) including all kinds of businesses"
    );
$nature_image = array(
        "foods.jpg",
        "car.jpg",
        "household.jpg",
        "grocery.jpg",
        "clothes.jpg",
        "b2b.jpg"
    );
?>
<?php  $login_user_id=$_SESSION['login'];
  if(isset($login_user_id))
  {
	    $urecord = isset($login_user_id) ? mysqli_fetch_assoc(mysqli_query($conn, "SELECT id,user_roles,setup_shop,balance_usd,balance_inr,balance_myr FROM users WHERE id='".$login_user_id."'")) : '';
    	$balance_inr=$urecord['balance_inr'];	
		if($balance_inr=='')
			$balance_inr=0;
  }
 ?>
<!DOCTYPE html>
<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">

  
<head><meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
    <?php include("includes1/headwithmainfest.php"); ?>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.6/dist/jquery.fancybox.min.css">
	<script src="https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.6/dist/jquery.fancybox.min.js"></script>  
  <style>
   .fancybox-image
	{
		width:60%;
		height:60%;
		left:22%;
		top:18%;
	}   
  .fancybox_place_order{
    position: absolute;
    right: 0;
    left: 0;
    top: 10px;
    margin: auto;
    background: red;
    border-radius: 10px;
    width: 10em;
    display: grid;
    z-index: 99998;
    align-content: center;
    cursor: pointer;
  }
  .fancybox-slide .buttons{
    position: relative;
    /* background: red; */
    width: 20%;
    height: 20vh;
    position: absolute;
    top: -20vh;
    bottom: 0;
    margin: auto;
    z-index: 99998;
    cursor: pointer;
  }
  .fancybox-slide .buttons:after,
  .fancybox-slide .buttons:before{
    content: '';
    position: absolute;
    width: 20px;
    height: 3px;
    background: white;
    top: 0;
    bottom: 0;
    right: 0;
    left: 0;
    border-radius: 2px;
    margin: auto;
  }
  .fancybox-slide .buttons:after{
    transform: rotateZ(45deg);
    top: -12px;
  }
  .fancybox-slide .buttons:before{
    transform: rotateZ(-45deg);
    bottom: -12px;
  }
  .fancybox-slide .buttons.button-r{
    right: 2.5%;
  }
  .fancybox-slide .buttons.button-l{
    transform: rotateZ(180deg);
    left: 2.5%;
  }
    body.noscroll{
      overflow: hidden !important;
      position: fixed;
    }
    .other_products {
    display: flex;
}
    .create_date
    {
      float: right;
    }
    .comment_box {
    border: 1px solid #ccc;
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 15px;
    margin-top: 15px;
    box-shadow: 0 0 5px 0px;
  }
    .submit_button
    {
      width:25% !important;
    }
    .comment{
      width:90%;
    }
  .well
  {
  
    min-height: 20px;
    background-color: #fff;
    border: 1px solid #e3e3e3;
    border-radius: 4px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
    box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
  }
  .well {
    width: 100% !important;
    min-height: 20px;
    background-color: transparent!important;
    border: 0px solid #e3e3e3!important;
    border-radius: 4px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
    box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
}
  .well form{
      min-height: 280px;
  }
  .pro_name
  {
   text-align: center;
    font-size: 22px;
    font-weight: 600;
    margin: 10px 0px;
    height: 60px;
    }
    .about_mer {
    width: 100%;
}
 .input-controls {
      margin-top: 10px;
      border: 1px solid transparent;
      border-radius: 2px 0 0 2px;
      box-sizing: border-box;
      -moz-box-sizing: border-box;
      height: 32px;
      outline: none;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
    }
    #searchInput {
      background-color: #fff;
      font-family: Roboto;
      font-size: 15px;
      font-weight: 300;
      margin-left: 12px;
      padding: 0 11px 0 13px;
      text-overflow: ellipsis;
      width: 50%;
    }
    #searchInput:focus {
      border-color: #4d90fe;
    }
    input.quatity {
    width: 90px;
}
.common_quant {
    display: flex;
}
p.quantity {
    margin-top: 7px;
}
.order_product{
    margin-top: 15px;
    margin-left: 10px;
    font-size: 20px;
    padding-left: 10px;
    padding-right: 10px;
    margin-bottom: 10px;
}
.comm_prd{
    border: 1px #000000 solid;
    padding-left: 15px;
    margin-bottom: 10px;
}
.mBt10{
    margin-bottom: 10px;
}
@media only screen and (max-width: 767px) and (min-width: 300px)  {
    .new_grid{
      grid-template-columns: 1fr 1fr !important;
    }
    .text_add_cart {
        background: #003A66;
        width: 109px;
        text-align: center;
        padding: 10px;
        color: #fff;
        text-transform: uppercase;
        font-weight: 600;
        cursor: pointer;
        /* margin-right: 8px; */
        border-radius: 8px;
        margin-left: -10px;
    }
   .master_category_filter{
        font-size: 1.2rem;
        line-height: 0.8rem;
        margin-bottom: 5px !important;
        padding: 0.5rem 0.5rem;
    }
    .category_filter{
        font-size: 1.2rem;
        line-height:0.8rem;
        margin-bottom: 5px !important;
        padding: 0.4rem 0.9em;
    }
    .order_product{
        margin-top: 25px;
        margin-bottom: 15px;
        font-size: 18px;
        padding-left: 5px;
        padding-right: 5px;
    }
    .oth_pr{
        margin-top: 20px !important;
    }
}
.nature_image {
   width: 40px;
   height: 40px;
}
@media only screen and (max-width: 600px) and (min-width: 300px)  {
  .sidebar-expand .main-wrapper {
        margin-left: 0px!important;
    }
    .oth_pr{
        margin-top: 26px!important;
    padding: 6px!important;
    }
}
@media only screen and (max-width: 500px) and (min-width: 400px)  {
     .well{
        padding-top: 0px !important;
     }
     .pro_name {
         font-size: 18px;
         margin: 10px 0px 0px;
         height: 35px;
     }
     .set_calss.input-has-value {
        width: 180px;
     }
     
}
@media only screen and (max-width: 600px) and (min-width: 300px)  {
  .new_grid{
    grid-template-columns: 1fr 1fr !important;
  }
     .well{
        padding-top: 0px !important;
     }
h4.head_oth {
    font-size: 20px;
}
     .pro_name {
        text-align: center;
        font-size: 14px;
        overflow: hidden;
        /* white-space: nowrap; */
        height: auto;
        /* width: 100px; */
        line-height: 15px;
     }
     .text_add_cart {
         margin: 5px 0px;
         padding: 7px;
     }
     .common_quant {
        display: block;
     }
     .text_add_cart {
         background: #003A66;
         width: 109px;
         text-align: center;
         padding: 10px;
         color: #fff;
         text-transform: uppercase;
         font-weight: 600;
         cursor: pointer;
         /* margin-right: 8px; */
         border-radius: 8px;
         margin-left: -10px;
     }
     .mBt10{
         margin-bottom: 2px;
     }
     .nature_image {
       width: 25px;
       height: 25px;
    }
    .starting-bracket{
        margin-top: 0.8rem;
    }
}
@media only screen and (max-width: 600px) and (min-width: 300px)  {
   .sidebar-expand .main-wrapper {
        margin-left: 0px!important;
    }
   .text_add_cart {
        padding: 6px;
   }
   .row#main-content {
        margin-right: 0px;
        margin-left: 0px;
    }
    .oth_pr{
  height: 40px;
  }
}
@media only screen and (max-width: 1050px) and (min-width: 992px)  {
   .text_add_cart{width: 100px}
   .text_add_cart {
       width: 125px;
       margin: 0 auto;
   }
   p.quantity {
        margin-left: 35px;
   }
   .common_quant {
        display: block;
   }
   input.quatity {
        width: 130px;
   }
}
@media only screen and (max-width: 750px) and (min-width: 600px)  {
   .set_calss.input-has-value {
        width: 173px;
   }
   .about_uss {
        width: 165px;
   }
   .sidebar-expand .main-wrapper {
        margin-left: 0px;
   }
   .pro_name{
       margin-bottom: 0.4em;
       font-size: 18px;
       overflow: hidden;
       white-space: nowrap;
   }
   p {
        margin-bottom: 0.4em;
   }
}
@media only screen and (max-width: 500px) and (min-width: 300px)  {
   input.btn.btn-block.btn-primary.submit_button {
        width: 100%!important;
   }
   p.test_testing {
        margin: 2px;
   }
   .text_add_cart {
        margin: 5px auto;
   }
   input.quatity {
        width: 118px;
   }
   .well {
        min-height: 20px;
        padding: 0px 0 0;
   }
   .common_quant {
        display: block;
   }
   .set_calss.input-has-value {
        width: 160px;
   }
   .grid.row {
        margin-left: 18px;
   }
   p {
        margin-bottom: 0;
   }
}
@media only screen and (max-width: 800px) and (min-width: 750px)  {
   .sidebar-expand .main-wrapper {
        margin-left: 0px;
   }
   .pro_name{
       margin-bottom: 0.4em;
       font-size: 18px;
       overflow: hidden;
       white-space: nowrap;
   }
   .common_quant {
        display: block;
   }
   p {
        margin-bottom: 0.4em;
   }
}
@media only screen and (max-width: 800px) and (min-width: 650px)  {
   .common_quant {
        display: block;
   }
   .text_add_cart {
        width: 142px;
   }
}
/* Edited by Sumit */
@media (min-width:768px) and (max-width:1150px){
  .total_rat_abt {
      font-size: 14px!important;
      display: flex;
  }
  .well {
      min-height: 20px;
      background-color: transparent!important;
      border: 0px solid #e3e3e3!important;
      border-radius: 4px;
      -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
      box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
  }
  label {
      font-weight: 600;
      width: 100%;
  }
  .fjhj br {
      display: none;
  }
  .master_category_filter{
      background-color: #545c73;
      border-color: #4a5368;
      -webkit-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.15), 0 1px 1px rgba(0, 0, 0, 0.075);
      box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.15), 0 1px 1px rgba(0, 0, 0, 0.075);
  }
  .master_category_filter:focus, .master_category_filter.focus {
      -webkit-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.15), 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 0 3px rgba(74, 83, 104, 0.5);
      box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.15), 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 0 3px rgba(74, 83, 104, 0.5);
  }
  .master_category_filter:hover {
      color: #fff;
      background-color: #4a5368;
      border-color: #545c73;
  }
}
@media (min-width:200px) and (max-width:767px){
  .total_rat_abt {
      font-size: 14px!important;
      display: flex;
  }
  .well {
      min-height: 20px;
      background-color: transparent!important;
      border: 0px solid #e3e3e3!important;
      border-radius: 4px;
      -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
      box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
  }
  .fjhj br {
      display: none;
  }
}
.fjhj br {
    display: none;
}
label {
    font-weight: 600;
    width: 100%;
}


  </style>
 

<!--script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAEr0LmMAPOTZ-oxiy9PoDRi3YWdDE_vlI&libraries=places" async defer></script!--> 
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB4BfDrt-mCQCC1pzrGUAjW_2PRrGNKh_U&libraries=places" async defer></script>  
<style type="text/css">
.active_menu
{
	background:#d6dadf !important;
	
}
#pop_cart
{
	font-size:.8em;
}
.modal 
{
	width:93%;
}
 @media only screen and (max-width:400px) {
	 .navbar-nav > li
	 {
	 }
 }
@import url( 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
.adminDemoVideo {
  position: relative;
  display: inline-block;
}
 @media only screen and (min-width: 700px) {
  .adminDemoVideo::before {
    content: '\f00e';
    z-index: 5;
    position: absolute;
    left: 9%;
    top: 89%;
    transform: translate( -50%, -50% );
    padding: 3px 15px 3px 25px;
    color: white;
    font-family: 'FontAwesome';
    font-size: 30px !important;
    background-color: rgba(23, 35, 34, 0.75);
    border-radius: 5px 5px 5px 5px;
}  
}
@media only screen and (max-width: 700px) {
  .adminDemoVideo::before {
    content: '\f00e';
    z-index: 5;
    position: absolute;
    left:9%;
    top:89%;
	
    transform: translate( -50%, -50% );
    padding: 3px 5px 3px 5px;
    color: white;
    font-family: 'FontAwesome';
    font-size:12px !important;
    background-color: rgba(23, 35, 34, 0.75);
    border-radius: 5px 5px 5px 5px;
}  
}
		 #partitioned {
  padding-left: 15px;
  letter-spacing: 42px;
  border: 0;
  background-image: linear-gradient(to left, black 70%, rgba(255, 255, 255, 0) 0%);
  background-position: bottom;
  background-size: 50px 1px;
  background-repeat: repeat-x;
  background-position-x: 35px;
  width: 220px;
  min-width:220px;
}

#divInner{
  left: 0;
  position: sticky;
}

#divOuter{
  width:190px; 
  overflow:hidden
}

</style> 
<style>
.restaurant-entry
{
	border-bottom: 1px solid #eaebeb;
	border-radius: 2px;
	margin-bottom: 20px;
	background-color: white !important;
}
.entry-logo
{
	float: left;
	width: 110px;
	height: auto;
	border: 1px solid #eaebeb;
	border-radius: 2px;
	margin-top: 15px;
	margin-left: 15px;
	overflow: hidden;
}

.entry-dscr
{
	padding-left: 145px;
	margin-top: 15px;
}
.entry-dscr a
{
	color:#51d2b7;
	font-weight: 500;
	font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, &quot;Helvetica Neue&quot;, Arial, sans-serif;
}
.theme-btn-dash
{
	border: 2px dashed #51d2b7;
	background-color: transparent;
	color:#f30;
}	
</style> 
</head>

<body  class="header-light sidebar-dark sidebar-expand pace-done">
    


    <div id="wrapper" class="wrapper">
        <!-- HEADER & TOP NAVIGATION -->
        <?php include("includes1/navbar.php"); ?>
        <!-- /.navbar -->
        <div class="content-wrapper">
            <!-- SIDEBAR -->
            <?php include("includes1/sidebar.php"); ?>
            <!-- /.site-sidebar -->
				<main class="main-wrapper clearfix" style="min-height: 522px;">
                    <div class="row" id="main-content">
      
					
				  <!-- <div class="clear"></div>
				  <a style="text-align:center;width:100%;margin-top:2%;" href="https://play.google.com/store/apps/details?id=com.koobigfamilies.app" target="blank">
					<img style="max-width:140px;" src="google.png" alt=""></a>
				  <div class="clear"></div> -->
                    
                    </div>
                     
                    <div class="col-md-12 row favorite" style="margin-left:15px; margin-bottom: 10px; padding-left:0px;" >
                       <h2 class="favorite_name" style="display: inline-block;font-size:18px;">
                        <?php echo $language['partner_who_accept']." ".$_SESSION['special_coin_name']; ?>        
					</h2>
                  
				   </div>
					<?php
					   include 'coinpartner_sub.php';
                       
                    ?>  
                   
                   
        </div>
        
       
        
        
        
        </div>
        </main>
        </div>
     
       
        

<!-- /.widget-body badge -->
</div>
    <!-- /.widget-bg -->

    <!-- /.content-wrapper -->

    <?php include("includes1/commonfooter.php"); ?>
  <!-- fund pass models !-->
<div id="fund_wallet_model" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<h4><?php echo $language['transfer']; ?> to <span class="trasfer_merchant_name"></span></h4>
				<button type="button" class="close"data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body" style="text-align: left;">
				<div class="credentials-container">
					<h5><?php echo $language['enter_password']; ?></h5>
					<div>
						<div class="input-group mb-2" style="margin-bottom:5px !important;">
							<input type="password" autocomplete="tel" id="fund_pass" class="fund_pass form-control" style="min-width:160px;" placeholder="" name="fund_pass" required="" />
							<input type="submit" id="confirm_fund" class="btn btn-primary" value="<?php echo $language['confirm']; ?>"/>  
						</div>
						<div class="input-group mb-2" style="margin-bottom:5px !important;">

						    <i  onclick="myFunctionfund()" id="eye_slash_fund" class="fa fa-eye-slash" aria-hidden="true"></i>
							<span onclick="myFunctionfund()" id="eye_pass_fund"> <?php echo $language['show_password']; ?>  </span>

							<span class="error-block-fund-pass" for="fund_pass" style="display: none; color: red"><?php echo $language['password_wrong']; ?></span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="fund_user_model" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<p><?php echo $language['transfer']; ?> <span id="total_wallet_amount"></span></p>
				<button type="button" class="close"data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body" style="text-align: left;padding:0px;">
				<div class="credentials-container">
					<div>
							<?php if($profile_data['name']==''){ ?>
							 <h5><?php echo "Please create your username, which is recognised by your friend"; ?></h5>
							<div class="input-group mb-2" style="margin-bottom:5px !important;">
							   <input type="text" autocomplete="tel" id="fund_username"  class="form-control" style="min-width:225px;" placeholder="Username" name="fund_username" required="" />
							</div>   
							<?php }  else {?>
							 <input type="hidden" autocomplete="tel" id="fund_username"  class="form-control" style="min-width:225px;" placeholder="Username" name="fund_username" value="<?php echo $profile_data['name'];?>" />
						    <?php } if($u_role_id==1){?>
							
							<input type="hidden" id="merchant_send" value="n"/>
							<?php }  else {?>

							  	<input type="hidden" id="merchant_send" value="n"/>

							  	
							<?php } ?>
							<div class="input-group mb-2" style="margin-bottom:5px !important;">
								<span class="error-block-fund-username" for="fund_pass" style="display: none; color: red"></span>
							</div>
								<input type="hidden" id="fund_user_id"/>
								
							<?php if($profile_data['fund_password']==''){ ?>
							<div class="input-group mb-2" style="margin-bottom:5px !important;">
								<div class="input-group-prepend">
									<div class="input-group-text" style="background-color:#51D2B7;border-radius: 5px 0 0 5px;height: 100%;padding: 0 10px;display: grid;align-content: center;"><?php echo "Password"; ?></div>
								</div>
								<input type="password" autocomplete="tel"   oninput="this.value = this.value.replace(/[^0-9.]/g, '');"   maxlength="8"  id="new_fund_password" class="form-control" style="min-width:250px;" placeholder="Create Fund Password" name="new_fund_password" required />
								
									 
							</div> 
							<div class="input-group mb-2" style="margin-bottom:5px !important;">
								<i  onclick="myFunction()" id="eye_slash" class="fa fa-eye-slash" aria-hidden="true"></i>
								<span onclick="myFunction()" id="eye_pass"> <?php echo $language['show_password']; ?> </span>   
							</div>
							<?php } else { ?>
							<input type="hidden" id="new_fund_password" value="<?php echo $profile_data['fund_password']; ?>"/>
							<?php } ?>
							<div class="input-group mb-2" style="margin-bottom:5px !important;">
								<span class="error-block-for-newfundpassword" style="display: none;color: red"></span>
							</div>
							<div class="input-group mb-2" style="margin-bottom:5px !important;">
								<input type="button" id="create_fund" class="btn btn-primary" value="<?php echo "Create"; ?>" style="width: 40%;" />
								<input type="button"  class="btn btn-primary cancel_transfer" value="<?php echo $language['cancel']; ?>" style="width: 40%; margin-left:20%;">
							</div>
						
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="fund_wallet_input_modal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<h4><?php echo $language['trasfer_info']; ?><h4>
				<button type="button" class="close"data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body" style="text-align: left;">
				<div class="credentials-container">
					<div>
						<form action="dashboard.php" method="post" id="form-transfer">
							<input type="hidden" name="sender_id" id="sender_id" value="<?php echo $_SESSION['login']; ?>" />
							<div class="input-group mb-2" style="margin-bottom:5px !important;">
								<div class="input-group-prepend">
									<div class="input-group-text" style="background-color:#51D2B7;border-radius: 5px 0 0 5px;height: 100%;padding: 0 10px;display: grid;align-content: center;">Transfer To +60</div>
								</div>
								<input type="number" autocomplete="tel" id="transfer_to" oninput="this.value = this.value.replace(/[^0-9.]/g, '');" maxlength="12" class="transfer_to form-control"  placeholder="mobile phone number" name="transfer_to" required="" />
							</div>
							<div class="card user_info" style="display:none;border:1px solid #51D2B7">
								
								  <div class="card-body">
									<h5 class="card-title user_name" id="user_name" style="text-align:center;color:red !important;"></h5>
								</div>
							</div>
							<h4 class="intro_user" style="display:none;font-size: 16px;color: red;">Number Look's New </h4>
							<div class="input-group mb-2 intro_user" style="display:none;margin-bottom:5px !important;">
								
								<div class="input-group-prepend">
									<div class="input-group-text" style="background-color:#51D2B7;border-radius: 5px 0 0 5px;height: 100%;padding: 0 10px;display: grid;align-content: center;"><?php echo "Reffer As"; ?></div>
								</div>
								<!-- <input type="text" id="transfer_wallet_type" class="transfer_wallet_type form-control" style="min-width:250px;" placeholder="Wallet Type" name="transfer_wallet_type" required="" /> -->
								<select id="reffer_as" class="form-control"  name="reffer_as" required="">
									<option value="member">Member</option>
									<option value="merchant">Merchant</option>
								</select>
							</div>
							
							<div class="input-group mb-2" style="margin-bottom:5px !important;">
								<span class="error-block-for-mobile" style="display: none;color: red"><?php echo $language['invalid_mobile']; ?></span>
							</div>
							<div class="input-group mb-2" style="margin-bottom:5px !important;">
								<div class="input-group-prepend">
									<div class="input-group-text" style="background-color:#51D2B7;border-radius: 5px 0 0 5px;height: 100%;padding: 0 10px;display: grid;align-content: center;"><?php echo $language['amount']; ?></div>
								</div>  
								<input type="text" autocomplete="tel"  oninput="this.value = this.value.replace(/[^0-9.]/g, '');"   maxlength="8"  id="transfer_amount" class="transfer_amount form-control"  placeholder="amount of transfer" name="transfer_amount" required="" />
							</div>
							<div class="input-group mb-2" style="margin-bottom:5px !important;">
								<span class="error-block-for-amount" style="display: none;color: red"><?php echo $language['please_trasfer_amount']; ?></span>
							</div>
							<div class="input-group mb-2" style="margin-bottom:5px !important;">
								<div class="input-group-prepend">
									<div class="input-group-text" style="background-color:#51D2B7;border-radius: 5px 0 0 5px;height: 100%;padding: 0 10px;display: grid;align-content: center;"><?php echo $language['wallet']; ?></div>
								</div>
								<!-- <input type="text" id="transfer_wallet_type" class="transfer_wallet_type form-control" style="min-width:250px;" placeholder="Wallet Type" name="transfer_wallet_type" required="" /> -->
								<select id="transfer_wallet_type" class="transfer_wallet_type form-control"  name="transfer_wallet_type" required="">
									<option value=""><?php echo $language['select_wallet']; ?></option>
									<?php if($balance['balance_myr']) {?>
									<option value="MYR">MYR</option> <?php }  if(($balance['balance_usd']>0) && $profile_data['user_roles']=='2' && $profile_data['special_coin_name']) { ?>     
									<option s_merchant_id="<?php echo $balance['id'];?>" wallet_label="dynamic" merchant_no="<?php echo $balance['mobile_number']; ?>"  value="CF"><?php echo $balance['special_coin_name']."- <b>".number_format($profile_data['balance_usd'],2)."</b>";?></option> <?php } if($balance['balance_inr']) { ?>
									<option value="INR">KOO Coin</option><?php } 
									 $sq="select special_coin_wallet.*,m.special_coin_name,m.mobile_number as merchant_no from special_coin_wallet  inner join users as m on m.id=special_coin_wallet.merchant_id where user_id='$a_user_id'";
						  
						$sub_rows = mysqli_query($conn,$sq);
									$all_wallet=mysqli_fetch_all($sub_rows,MYSQLI_ASSOC); if(count($all_wallet)>0){
										// print_R($all_wallet);
										
							foreach($all_wallet as $wal){ if($wal['coin_balance']){?>
									<option  s_merchant_id="<?php echo $wal['merchant_id'];?>" wallet_label="dynamic" merchant_no="<?php echo $wal['merchant_no']; ?>"  value="<?php  echo $wal['special_coin_name'];?>"><?php  echo $wal['special_coin_name']."- <b>".number_format($wal['coin_balance'],2)."</b>";?></option>  
							<?php }} } ?>
								</select>
							</div>
							<div class="input-group mb-2" style="margin-bottom:20px !important;">
								<span class="error-block-for-wallet-type" style="display: none;color: red"><?php echo $language['plz_select_wallet']; ?></span>
							</div>
							<div class="input-group mb-2" style="margin-bottom:5px !important;">
								<div class="input-group-prepend">
									<div class="input-group-text" style="background-color:#51D2B7;border-radius: 5px 0 0 5px;height: 100%;padding: 0 10px;display: grid;align-content: center;"><?php echo "Remark"; ?></div>
								</div>  
								<textarea name="remark" id='remark' rows='1' cols='4'  class='form-control'></textarea>   
								
							</div>
							

							<div class="input-group mb-2" style="margin-bottom:5px !important;">
								<span class="current-balance" style="display: none;color: #595d70;display: none;"><?php echo $language['cur_bal']; ?>:<b></b></span>
							</div>

							<div class="input-group mb-2" style="margin-bottom:5px !important;">
								<input type="button" id="confirm_transfer" class="btn btn-primary" value="<?php echo $language['confirm']; ?>" style="width: 40%;" />
								<input type="button"  class="btn btn-primary cancel_transfer"  value="<?php echo $language['cancel']; ?>" style="width: 40%; margin-left:20%;">
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class=" modal fade" id="AlerModel" role="dialog" style="width:80%;min-height: 200px;text-align: center;margin:8%;">
        <div class="element-item modal-dialog modal-dialog-centered" style="position: absolute;top: 0;bottom: 0;left: 0;right: 0;display: grid;align-content: center;">
            <!-- Modal content-->
            <div class="element-item modal-content">
                <div class="element-item modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                            
                    
                              </div>   
                                    <p id="show_msg" style="font-size:22px;font-weight:bold;"><?php echo $language['cancel']; ?></p>
                    
                                </div>
                            </div>
    </div>

	<div class="modal fade" id="TModel" role="dialog" style="">  
   <div class="modal-dialog">
          

            <!-- Modal content-->
            <div class="modal-content">
              
                <div class="modal-header">
                    <button type="button" class="close final_done" data-dismiss="modal">&times;</button>
					
                </div>
                 
                    <div class="modal-body" style="padding-bottom:0px;">
					     <p id="show_msg_t" style="font-size:22px;font-weight:bold;"><?php echo $s_msg; ?></p>
                    
						
                    </div>
                    <div class="modal-footer" style="padding-bottom:2px;">
                        <div class="row" style="margin: 0;">
			 
						<div class="input-group mb-2" style="margin-bottom:5px !important;">
								
								<input type="button"  class="btn btn-primary final_done"  value="<?php echo "DONE"; ?>" style="width: 40%; margin-left:20%;">
							</div>
						 
						         
					   
						</div>
						  
						
                    </div>
                  
            </div>
        </div>
  </div> 

<!-- end fund pass models !-->

</body>

</html>
<!-- fund pass models script !-->
<script>
function transfer(user_id,mobile_number,merchant_name) {
		// var user_login="<?php echo $_SESSION['login']; ?>";
		// alert(user_id);
		if(user_id)
		{
			$('.trasfer_merchant_name').html(merchant_name);
			$('#transfer_to').val(mobile_number);
			$('.user_info').show();
			$('.user_name').html("Transfer to "+merchant_name);
			$('#PartnerModel').modal('hide');
			var user_name="<?php echo $profile_data['name']; ?>";
			// alert(user_name);
			var db_fb_password="<?php echo $profile_data['fund_password']; ?>";
			var u_role_id="<?php echo $u_role_id; ?>";
			$('#fund_user_id').val(user_id);
			if(user_name=='')
			{
				$('.error-block-fund-username').hide();
				$('#fund_user_model').modal('show');
			}   
			else
			{
				if(db_fb_password=='')
				{
				   $('#fund_user_model').modal('show');	
				}  
				else
				{
					
						$('.error-block-fund-username').html('Username is Required');
						$('.error-block-fund-username').show();
						$('#fund_user_id').val(user_id);
						$('#fund_wallet_model').modal('show');
						// $('#fund_wallet_input_modal').modal('show');
				}
			}
		}
		else
		{
			var msg="To You wallet Feature Account has to be login";
			$('#show_msg').html(msg);
			$('#AlerModel').modal('show'); 
			setTimeout(function(){ $("#AlerModel").modal("hide"); },2000);
		}
	}
	$('#confirm_fund').click(function () {
		fund_engine();
	});
	function fund_engine(){
		if ($('#fund_pass').val() == '<?php echo $profile_data["fund_password"];?>') {
			$('.error-block-fund-pass').hide();
			// alert('success');
			$('#fund_wallet_model').modal('hide');
			$('#fund_wallet_input_modal').modal('show');
			// $('#fund_pass').val('');

		}else {
			$('.error-block-fund-pass').show();
		}
	}
	$('.cancel_transfer').click(function (){
		location.reload();
		$('#fund_wallet_input_modal').modal('hide');
		$('#fund_user_model').modal('hide');
	});
		function myFunction() {
		  var x = document.getElementById("new_fund_password");
		  if (x.type === "password") {
			x.type = "text";
				$("#eye_pass").html('Hide Password');
					 $('#eye_slash').removeClass( "fa-eye-slash" );
					$('#eye_slash').addClass( "fa-eye" );
					
		  } else {
			x.type = "password";
			 $("#eye_pass").html('Show Password');
			  $('#eye_slash').addClass( "fa-eye-slash" );
					$('#eye_slash').removeClass( "fa-eye" );
		  }
		}
		
	
	$('#create_fund').click(function () {
		//fund_engine();
		var fund_username=$('#fund_username').val();
		var new_fund_password=$('#new_fund_password').val();
		var user_id=$('#fund_user_id').val();
		// alert(user_id);
		if(fund_username!='')
		{
			$('.error-block-fund-username').hide();
			if(new_fund_password!='')
			{
				$.ajax({
					  
					  url: "functions.php",
					 type:'POST',
					  dataType : 'json',
					  data: {user_id:user_id,fund_username:fund_username,method:"savename",fund_password:new_fund_password},   
					  success:function(response){
							var btn = document.getElementById('create_fund');
							btn.disabled = true;
							$(this).removeClass("btn-primary").addClass("btn-default");
						  var data = JSON.parse(JSON.stringify(response));
						  if(data.status==true)
						  {
							   $('#fund_user_model').modal('hide');
							$('#fund_wallet_input_modal').modal('show');  
						  }
						  else
						  {
							alert(data.msg);
							btn.disabled = false;
							$('#').removeClass("btn-default").addClass("btn-primary"); 
						  }
						 
						}
				}); 	
			}
			else
			{
			   $('.error-block-for-newfundpassword').html('Fund Password is Required');
				$('.error-block-for-newfundpassword').show();	
			}
			
		}
		else
		{
			$('.error-block-fund-username').html('Username is Required');
			$('.error-block-fund-username').show();
		}
	});
</script>

<!-- end fund pass models script !-->

<script>
 
// init Isotope
var $grid_sub = $('.sub_category_grid').isotope({
    // options
    layoutMode: 'fitRows'
});
var $grid = $('.grid').isotope({
  // options
});
var menu_type='<?php echo $merchant_detail['menu_type'];?>';
if(menu_type==1)
{
var master_filter='.'+'<?php echo $master_cat;?>';
$grid.isotope({ filter:master_filter });
}
// filter items on button click
$('.master_category_filter').on( 'click', function(e) {
    e.preventDefault();
	$('.master_category_filter').removeClass("active_menu");
    $(this).addClass("active_menu");
    var filterValue = $(this).attr('data-filter');
    $grid_sub.on( 'arrangeComplete', function ( event, filteredItems) {
        console.log(event, filteredItems);
        $(filteredItems[0].element).find('button').trigger('click');
        console.log('am called');
    });
    $grid_sub.isotope({ filter: filterValue });
 
        var filterValue = $(this).attr('data-filter');
        var position_value = $(this).attr('data-position');
    
  
});
$('.sub_category_grid .category_filter button').on( 'click',function() {
      var filterValue = $(this).attr('data-filter');
      var subcateg_show = $(this).data("subcategory");
	   $('.sub_category_grid .category_filter button').removeClass("active_menu");
		$(this).addClass("active_menu");
      console.log(filterValue);
      console.log(subcateg_show);
      $("#remarks_area .modal-body .btn-group").each(function(){
        if($(this).data("subcategory") == subcateg_show || $(this).data("subcategory") == "all"){
          $(this).show();
        }else{
          $(this).hide();
        }
      });
      $grid.isotope({ filter: filterValue });
});
</script>
<style>
.sub_category_grid button{ /* You Can Name it what you want*/
margin-right:10px;
}
.sub_category_grid button:last-child{
margin-right:0px;
/*so the last one dont push the div thas giving the space only between the inputs*/
}
img.active {
  animation: make_bigger 1s ease;
  width: 600px;
  height: 400px;
}
img.non_active {
  animation: make_smaller 1s ease;
  width: 127px;
  height: 128px;
}
@media only screen and (max-width: 750px) and (min-width: 600px)  {
form.set_calss.input-has-value {
<!--
    width: 50%;
-->
    width: 173px;
}
.about_uss {
    width: 165px;
}
.sidebar-expand .main-wrapper {
    margin-left: 0px;
}
}
@media only screen and (max-width: 500px) and (min-width: 300px)  {
#merchant_message
{
	margin-top:20%;
}
input.btn.btn-block.btn-primary.submit_button {
    width: 100%!important;
}
.common_quant {
    display: block;
}
form.set_calss.input-has-value {
    width: 100%;
    width: 170px;
    margin-left: -20px;
}
.grid.row {
    margin-left: 18px;
}
/*.pro_name {
    height: 130px;
}*/
img.make_bigger {
    height: 100px;
}
}
@media only screen and (max-width: 800px) and (min-width: 750px)  {
.sidebar-expand .main-wrapper {
    margin-left: 0px;
}
.common_quant {
    display: block;
}
}
.col-md-4{
  max-width: 100% !important;
}
.well.col-md-4{
  padding: 0 !important;
}
</style>
<?php
if($bank_data['custom_msg_time'])
$c_time=$bank_data['custom_msg_time'];
else
$c_time=5;	
$same_order=$_SESSION['same_order'];
$free_trial=$_SESSION['free_trial'];
$verify_mobile=$_SESSION['verify_mobile'];
$today_limit=$_SESSION['today_limit'];
$_SESSION['same_order']='';
$_SESSION['free_trial']='';
$_SESSION['today_limit']='';
if($merchant_detail['shortcut_icon'])
$shortcut_icon=$site_url."/images/shortcut_icon/".$merchant_detail['shortcut_icon'];
if($shortcut_icon=='')
  $shortcut_icon='img/logo_512x512.png';
if($merchant_detail['id']=='5062')
$start_url=$site_url."/structure_merchant.php?merchant_id=".$merchant_detail['id'];
else
$start_url=$site_url."/view_merchant.php?sid=".$merchant_detail['mobile_number'];
?>
<script>
$(document).ready(function(){
	 // alert(4);
	 var custom_msg="<?php echo $custom_msg; ?>";
	 var same_order="<?php echo $same_order; ?>";
	 var free_trial="<?php echo $free_trial; ?>";
	 var today_limit="<?php echo $today_limit; ?>";
	 var verify_mobile="<?php echo $verify_mobile; ?>";
	 var mobile_otp_verify="<?php echo $mobile_otp_verify; ?>";
	 // alert(today_limit);
	 var myDynamicManifest = {
   "gcm_sender_id": "540868316921",
   "icons": [
		{
		"src": "<?php echo $shortcut_icon; ?>",
		"type": "image/png",
		"sizes": "512x512"
	  }
	  ],
	  "short_name":'<?php echo $merchant_detail['name']; ?>',
	  "name": "One stop centre for your everything",
	  "background_color": "#4A90E2",
	  "theme_color": "#4A90E2",
	  "orientation":"any",
	  "display": "standalone",
	  "start_url":'<?php echo $start_url; ?>',
	}
	const stringManifest = JSON.stringify(myDynamicManifest);
	const blob = new Blob([stringManifest], {type: 'application/json'});
	const manifestURL = URL.createObjectURL(blob);
	document.querySelector('#my-manifest-placeholder').setAttribute('href', manifestURL);
	
    //$('.master_category_filter:first-child').trigger('click');
    $('.sub_category_grid .category_filter:first-child button').trigger('click');
     $('.master_category_filter:first-child').trigger('click');
     
  
});

</script>  
