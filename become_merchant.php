<?php
include("config.php");
// session_start();
 
$_SESSION['IsVIP'] = null ;
if (empty($_SESSION["langfile"])) { $_SESSION["langfile"] = "english"; }
require_once ("languages/".strtolower($_SESSION["langfile"]).".php");


function gw_send_sms($user,$pass,$sms_from,$sms_to,$sms_msg){           
    $query_string = "api.aspx?apiusername=".$user."&apipassword=".$pass;
    $query_string .= "&senderid=".rawurlencode($sms_from)."&mobileno=".rawurlencode($sms_to);
    $query_string .= "&message=".rawurlencode(stripslashes($sms_msg)) . "&languagetype=1";        
     $url = "http://gateway.onewaysms.com.au:10001/".$query_string;  
    
	// Initialize a CURL session. 
	$ch = curl_init();  
	  
	// Return Page contents. 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	  
	//grab URL and pass it to the variable. 
	curl_setopt($ch, CURLOPT_URL, $url); 
	  
	$result = curl_exec($ch); 
	 $ok = "success"; 
	      
    return $ok;  
}    

if($_POST['become_merchant']){
	
	
	$shop_name = $_POST['shop_name'];
	$telephone_number = $_POST['telephone_number'];
	$email = $_POST['email'];
	$m_introducer_phone = $_POST['m_introducer_phone'];
	$m_address = $_POST['m_address'];
	$date = date('Y-m-d H:i:s');
	
	$f_query = mysqli_query($conn,"select * from tbl_beome_merchant where m_phone = '".$telephone_number."' ");
	$fetch_record = mysqli_num_rows($f_query);
	
	if($fetch_record == 0){
		$i_query = mysqli_query($conn, "INSERT INTO `tbl_beome_merchant` (`m_shopname`, `m_phone`,`m_email`,`m_introducer_phone`,`m_address`,`m_createddate`, `m_status`) VALUES ('".$shop_name."', '".$telephone_number."', '".$email."','".$m_introducer_phone."','".$m_address."','".$date."',1)");
		$sms_to = '+60123115670';
		$msg_email = '';
		if($email != ''){
			$msg_email = "(".$email.")";
		}	
		$m_introducer_phone_t = '';
		if($m_introducer_phone != ''){
			$m_introducer_phone_t = ", Introducer: ".$m_introducer_phone;
		}

		$m_address_t = '';
		if($m_address != ''){
			$m_address_t = ", Address: ".$m_address;
		}		
		
		$sms_msg = 'You have inquiry for new Merchant, Shop:'.$shop_name.", Number: ".$telephone_number." ".$msg_email.' '.$m_introducer_phone_t.' '.$m_address_t.' ';   
		//echo $sms_msg;
		$smsend = gw_send_sms("APIHKXVL33N5E", "APIHKXVL33N5EHKXVL", "9787136232", $sms_to,$sms_msg);  
		$_SESSION['msg'] = "<div class='alert alert-success' style='color:white;background-color:green;'>If you do not receive reply from us within 24 hours, please contact +6012-3115670 for details</div>";
		
	}else{
		$_SESSION['msg'] = "<div class='alert alert-info' style='color:white;background-color:red;'>You have already registered with this number. Please contact to Admin.</div>";
	}
	
	
	
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>Become Merchant | koofamilies</title>
    <!--Custom Theme files-->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="keywords" content="Tab Login Form widget template Responsive, Login form web template,Flat Pricing tables,Flat Drop downs  Sign up Web Templates, Flat Web Templates, Login signup Responsive web template, SmartPhone Compatible web template, free WebDesigns for Nokia, Samsung, LG, SonyEricsson, Motorola web design"
    />
    <script type="application/x-javascript">
        addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); }
    </script>
    <script type="text/javascript">
    	navigator.serviceWorker.getRegistrations().then(function(registrations) {
		 for(let registration of registrations) {
		  registration.unregister()
		} })
    </script>
    <!-- Custom Theme files -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
    <!--web-fonts-->
    <link href='css/Signika.css' rel='stylesheet' type='text/css'>
    <link href='css/Righteous.css' rel='stylesheet' type='text/css'>
    <link href="css/custom.css" rel="stylesheet" type="text/css" media="all" />
	<link rel="stylesheet" href="intlTelInput/css/intlTelInput.css">
    <!--//web-fonts-->

        <!-- jquery validation plugin //-->
        
<link rel="stylesheet" href="css/smooth.css">


        
        <style type="text/css">
        #resend_link
		{
			padding: 14px;
margin-top: -40px;
background:red;
color:
white;
border:
#51d2b7;
		}
		
        .hidden{
        
        display:none;
        
        }
		.intlTelInput{
	width: 250px;
	height: 35px;
	border: none;
	border-bottom: 2px solid #cecfd3;
	font-size: 1em;
}
        
		#forgot_divOuter{
  width:190px; 
  overflow:hidden
} 
 #forgot_partitioned {
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
-webkit-box-shadow: inset 0px 100px 0px 0px rgba(255, 255, 255, 0.5);
box-shadow: inset 0px 100px 0px 0px rgba(255, 255, 255, 0.5);
} 
        </style>
    <!--js-->
 
   
	<style>
	.alert {
	padding: 15px;
    margin: 15px;
    border: 1px solid transparent;
    border-radius: 4px;
	}
	.alert-danger {
    color: #a94442;
    background-color: #f2dede;
    border-color: #ebccd1;
	}
	img.logo_main {
    display: block;
    text-align: center;
    margin: 0 auto;
}
.login-right input[type=number]{
    outline: none;
    font-size: 1em;
    color: #000;
    padding: 19px 30px 10px 10px;
    margin: 0;
    width: 89.87%;
    border: none;
    border-bottom: 2px solid #cecfd3;
    -webkit-appearance: none;
}
	</style>
    <!--//js-->
</head>

<body>

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
    <!-- main -->
    <div class="main">
<!--
        <h1>koofamilies</h1>
-->
        <!--img src="images/logo_new.jpg" width="170px" height="100px" class="logo_main"!-->
        <div class="login-form">
            <div class="login-left">
                <div class="logo" style="margin-top: 55px;">
                    <a href="index.php?vs=<?php echo rand(); ?>"><img style="    max-width: 92%;" src="images/Icon-user.png" alt="" /></a>
                    <h2>Hello </h2>
                    <p>Welcome to koofamilies</p>
                </div>
            </div>
            <div class="login-right">
                <div class="sap_tabs">
                    <div id="horizontalTab" style="display: block; width: 100%; margin: 0px;">
					
                        
						
							
                        <ul class="resp-tabs-list">
                            <li class="resp-tab-item resp-tab-active" aria-controls="tab_item-0" role="tab"><span style="font-size: 27px;font-weight: bold;text-align: center;width: 100%;"><?php echo $language["m_become_merchant"]; ?></span></li>
                         <div class="clear"> </div>
                        </ul>
                        
						<br/>
							<?php if(isset($_SESSION['msg'])){ ?>
							<?php echo $_SESSION['msg']; ?> 
							<?php  unset($_SESSION['msg']);  } ?>

						<br/>
                        <div class="resp-tabs-container">
                            <div class="tab-1 resp-tab-content resp-tab-content-active" aria-labelledby="tab_item-0" style="display:block">
								
								
                                <form method="post" action="become_merchant.php">
                                <div class="login-top">	
										
                                       <input type ="text" name="shop_name" id="shop_name" placeholder="<?php echo $language["m_shop_name"]; ?>" required />
									   <br/><br/>
									   <input type ="number" name="telephone_number" id="telephone_number" placeholder="<?php echo $language["m_contact_number"]; ?>" required />
									   <br/><br/>
									   <input type ="email" name="email" id="email" placeholder="<?php echo $language["m_email"]; ?>"   />
									   <br/><br/>
									   <input type ="text" name="address" id="address" placeholder="<?php echo $language["m_address"]; ?>"   />
									   <br/><br/>
									   <input type ="number" name="m_introducer_phone" id="m_introducer_phone" placeholder="<?php echo $language["m_intoducer"]; ?>" />
									   <br/>
									   <span style="color:red"><?php echo $language["m_intoducer_text"]; ?></span>
									
                                 <br>
								   <div class="row" style="margin-top:17%;margin-right: 10%;">
									  <input type="submit" value="<?php echo $language["m_become_merchant"]; ?>" name="become_merchant" id="become_merchant" class="submint_login showLoader6" style="padding:14px;margin-top: -40px;background: #51d2b7;color:black;border: #51d2b7;" />
									</div>
                                </div>
                                </form>
                                
								
           <div class="clear" style="padding:25px;"></div>
		 
                            </div>
                        
                        </div>
                    </div>
                </div>
				     </div>
            <div class="clear"> </div>
        </div>
    </div>
    <!--//main -->
    <div class="copyright">
        <p> &copy; 2018 | All rights reserved koofamilies</p>
    </div>
</body>
<style>
    #koosignup label.error
    {
        
        color:red;
        
    }
    .select_optionss {
    margin-top: 12px;
}
</style>
<script src="js/jquery.min.js"></script>
<script src="intlTelInput/js/intlTelInput.js"></script>

<script src="js/jquery-ui.js"></script>
        <script type="text/javascript" src="js/jquery.validate.js"></script>
    <script src="js/easyResponsiveTabs.js" type="text/javascript"></script>
 <script type="text/javascript">
    $(document).ready(function()
	{
		
	});

</script>
 <a href="https://api.whatsapp.com/send?phone=60123945670" target="_blank"><img src ="images/iconfinder_support_416400.png" style="width:75px;height:75px;position: fixed;left:15px;bottom: 70px;z-index:999;"></a>

</html>

<!-- newly added by tamil--->
<style>
.login-top {
    margin-top: 0em;
    padding: 12px 12px 0px;
}
button.guest_user_bt {
    margin: 0 auto;
    display: block;
    width: 90%;
    background:#FFB87A;
    color:#1F868B;
    font-weight: 600;
    font-size: 16px;
    border: 2px solid #FFB87A;
    margin-right: 20px;
    cursor: pointer;
}
hr.second_test {
    width: 150px;
    float: right;
}
.terms_condtions {
    margin-top: 12px;
}t
.submit {
     float: none; 
}
hr.first_test {
    width: 150px;
    float: left;
    margin-left: 20px;
    margin-right: 14px;
}
.submint_login {gin 2
    margin: 0 auto;
    display: block;
    width: 90%;
    font-weight: 600;
    font-size: 16px!important;
  
}
input[type="submit"] {
    margin: 0 auto;
    display: block;
    width: 90%;
    font-weight: 600;
    font-size: 16px!important;
    margin-right:0px;
}

.login-left h2 {
     margin-top: 1.5em;
}
.login-form {
	background: url(../images/banner.jpg)no-repeat 0px 0px;
    background-size: cover;
}

@media (min-width: 328px) and (max-width:628px) {  
.login-right 
{
	padding:20px !important;
	min-height:320px !important;
}
}

</style>
