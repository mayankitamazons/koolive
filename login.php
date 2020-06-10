<?php
include("config.php");
if(isset($_SESSION['login']) && $_SESSION['login'])
{
	header("location: dashboard.php");
	exit;
}

// session_start();
 
if(isset($_GET['language'])){
	$_SESSION["langfile"] = $_GET['language'];
}  

// print_r($_COOKIE['session_id']);
// die;
// if(isset($_COOKIE['session_id'])) {
    // if(checkSession()) {
        // $_SESSION['login'] = 1;
        // $_SESSION['session_id'] = $_COOKIE['session_id'];
        // header("location: dashboard.php");
    // }
// }

$_SESSION['IsVIP'] = null ;
if (empty($_SESSION["langfile"])) { $_SESSION["langfile"] = "english"; }
require_once ("languages/".strtolower($_SESSION["langfile"]).".php");

function checkToken(){
	$conn = $GLOBALS['conn'];
	$tget = $_GET['tk'];
	$ref_id = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id,token, referral_id FROM users WHERE token = '$tget'"));
	if($tget === $ref_id['token']){
		$session_id =  uniqid($ref_id['id'] . "_",true);
		// $c = setcookie("session_id", $session_id, time() + 3600 * 24 * 30 * 12 * 10,"/");
	
			$_SESSION['login'] = $ref_id['id'];
			$_SESSION['referral_id'] = $ref_id['referral_id'];
			return true;
		
	}else{
		return false;
	}

}

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

function checkSession(){
	$conn = $GLOBALS['conn'];
	$session = $_COOKIE['session_id'];
	$rw = mysqli_fetch_row(mysqli_query($conn, "SELECT id FROM users WHERE session = '$session'"));
	if($rw > 0){
		return true;
	}else{
		return false;
	}
}
 
if(isset($_SESSION['login']) && !empty($_SESSION['login']))  
{
	header("location: dashboard.php");

}else if(isset($_SESSION['login']) && empty($_SESSION['login'])) {

	header('location: logout.php');

}else if(isset($_GET['tk']) && !empty($_GET['tk'])){

	if(checkToken()){

		header('location: dashboard.php');

	}else{

		header('location: ./login.php');

	}

}else if(isset($_GET['tk']) && empty($_GET['tk'])){
	header('location: ./login.php');
}  
if(isset($_SESSION['invitation_id'])){
    unset($_SESSION['invitation_id']);
}
if($_POST['checkphone'])
{
	 $checkphone=$_POST['checkphone'];

	$loginmatch = mysqli_query($conn, "SELECT id FROM users WHERE mobile_number ='".$checkphone."'");	
	$logincount=mysqli_num_rows($loginmatch);  
	if($logincount>0)
	{
		echo 1;
	}
	die;
}
if($_POST['method']=="otp_submit")
{
	 $usermobile=$_POST['usermobile'];
   $loginmatch = mysqli_query($conn, "SELECT id FROM users WHERE  mobile_number ='".$usermobile."'");	
	$logincount=mysqli_num_rows($loginmatch);  
	if($logincount>0)
	{
		$user_row = mysqli_fetch_assoc($loginmatch); 
		$id=$user_row['id'];
		   unset($_SESSION['tmp_login']);   
		$_SESSION['user_id']=$user_row['id'];
		$_SESSION['login']=$user_row['id'];
		$res1= mysqli_query($conn,"UPDATE `users` set otp_verified ='y',isLocked='0' WHERE mobile_number ='$usermobile'");
		if($res1)
		{
			echo 1;
		}
		die;
	}
	
}
if($_POST['usermobile'] && $_POST['login_password'])
{
	 $usermobile=$_POST['usermobile'];
	 $login_password=$_POST['login_password'];

	$loginmatch = mysqli_query($conn, "SELECT * FROM users WHERE password='$login_password' and mobile_number ='".$usermobile."'");	
	$logincount=mysqli_num_rows($loginmatch);  
	if($logincount>0)
	{
		
		// check membership plan of user 
		extract($_POST);
		$user_row = mysqli_fetch_assoc($loginmatch); 
		$user_id=$id=$user_row['id'];
		unset($_SESSION['tmp_login']);   
		$_SESSION['user_id']=$user_row['id'];
		$_SESSION['login']=$user_row['id'];
		$setup_session = $user_row['setup_shop'];
		$session_id =  uniqid($id . "_",true);
		if($membership_applicable=="y")
		{
			$last_orderdata=mysqli_fetch_assoc(mysqli_query($conn,"select * from order_list where id='$last_order_id'"));
			if($last_orderdata)
			{
				$total_cart_amount=$last_orderdata['total_cart_amount'];
				$membership_discount_input=$last_orderdata['membership_discount_input'];
				$m_id=$merchant_id;
				$total_shop=mysqli_fetch_assoc(mysqli_query($conn,"select sum(total_cart_amount) as total_order_amount from order_list where user_id='$user_id' and merchant_id='$merchant_id'"));
				if($total_shop['total_order_amount'])
				{
				$total_shop_amount=number_format($total_shop['total_order_amount'],2);
				$total_shop=$total_shop['total_order_amount'];
				}
				else
				{
				$total_shop_amount=0;
				$total_shop=0;
				}
				  $query="SELECT user_membership_plan.*, membership_plan.user_id as memberplan_user, membership_plan.* FROM user_membership_plan INNER JOIN membership_plan ON membership_plan.id = user_membership_plan.plan_id WHERE user_membership_plan.plan_active='y' and user_membership_plan.user_id='$user_id' and user_membership_plan.merchant_id = '$merchant_id' and membership_plan.total_max_order_amount>='$total_shop' and  membership_plan.total_min_order_amount<='$total_shop'";
				   
				
				
				    
				$user_plan = mysqli_fetch_assoc(mysqli_query($conn,$query));
				// print_R($user_plan);
				// die;
				$membership_plan_id=$user_plan['plan_id'];
				if($user_plan['plan_type'] == 'per'){
					$discount = $total_cart_amount*($user_plan['plan_benefit']/100);
					// $total_cart_amount = $total_cart_amount - $discount;
					
				}else{
					$discount = $user_plan['plan_benefit'];
					// $total_cart_amount = $total_cart_amount - $discount;
				}
				if(is_null($user_plan)){
					
					$plan_detail = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM membership_plan WHERE user_id='$m_id' and $total_shop BETWEEN total_min_order_amount AND total_max_order_amount;"));
					
					// print_r($plan_detail);
					// die;
					if(!is_null($plan_detail)){
						// disactive all old membership 
						$past_plan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM user_membership_plan WHERE user_id='$user_id' and merchant_id='$m_id'"));
						if($past_plan)
						{
							$is_upgrade="y";
						}
						else
						{
							$is_upgrade="n";
						}
						mysqli_query($conn,"update user_membership_plan set plan_active='n' where user_id='$user_id' and merchant_id='$m_id'");
						$user_member_plan = "INSERT INTO `user_membership_plan`(`user_id`, `merchant_id`, `plan_id`, `paid_via`, `paid_date`, `plan_active`, `created`, `is_upgrade`) VALUES ('$user_id','$m_id','".$plan_detail['id']."','Cash','$date','y','$date','$is_upgrade')";
						$user_plan_list = mysqli_query($conn, $user_member_plan);
						$membership_plan_id = mysqli_insert_id($conn); 
					}   
					if($plan_detail['plan_type'] == 'per'){
						
						$plan_la=$plan_detail['plan_benefit']." %";
						$discount = $total_cart_amount*($plan_detail['plan_benefit']/100);
						// $total_cart_amount = $total_cart_amount - $discount;
						
					}else{
						$plan_la="Rm ".$plan_detail['plan_benefit']." off";
						$discount = $plan_detail['plan_benefit'];
						// $total_cart_amount = $total_cart_amount - $discount;
					}
					$membership_discount_input=$plan_la;
				}
				// $total_cart_amount = $total_cart_amount - $coupon_discount;
				
				if($total_cart_amount < 0){
					$total_cart_amount = 0;
				}
				if($discount>$total_cart_amount)
					$discount=$total_cart_amount;
				// update membership discount and all data
				$query="update order_list set membership_discount_input='$membership_discount_input',membership_plan_id='$membership_plan_id',membership_discount='$discount' where id='$last_order_id'";
				mysqli_query($conn,$query);
			}  
		}
		
		// $setup_session
		updateCookieStatus($session_id,$setup_session,$id);
		$r['status']=true;
		$r['data']=$user_row;
		
		// echo 1;
	}
	else
	{
		$r['status']=false;
		$r['data']='';
		
	}
	echo json_encode($r);
		die; 
	die;
}
if($_POST['user_id'] && $_POST['method']=="registerajax")
{
	 $user_id=$_POST['user_id'];
	 $order_id=$_POST['order_id'];
	 $register_password=$_POST['register_password'];
	
	$loginmatch = mysqli_query($conn, "SELECT id FROM users WHERE id ='".$user_id."'");	
	$logincount=mysqli_num_rows($loginmatch);  
	if($logincount>0)
	{
		$user_row = mysqli_fetch_assoc($loginmatch); 
		$_SESSION['login']=$user_row['id'];
		$_SESSION['user_id']=$user_row['id'];
		$setup_session=$user_row['shop_open'];
		unset($_SESSION['tmp_login']);
		// echo "UPDATE users SET password='$register_password',newuser='join' WHERE id='$user_id'";
		// die;
		if($register_password)   
		mysqli_query($conn, "UPDATE users SET password='$register_password',guest_user='active',otp_verified='y' WHERE id='$user_id'");
	   else
		 mysqli_query($conn, "UPDATE users SET guest_user='active',otp_verified='y',password_created='y' WHERE id='$user_id'");  
		mysqli_query($conn, "UPDATE order_list SET newuser='join' WHERE id='$order_id'");
		$session_id =  uniqid($user_id . "_",true);
		updateCookieStatus($session_id,$setup_session,$user_id);
		echo 1;
	}
	die;
}
if(isset($_GET['code']) && isset($_GET['id']) && is_numeric($_GET['id']))
{
	// print_r($_GET);
	// die;
	$code = $_GET['code']; 
	$apiusername = $_GET['apiusername']; 
	$user_id = $_GET['id'];
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
			$error = "You have verified your account successfully. Now You can login to use our service.<br>";
		}
		else
		{
			$error = "Your Link is Expire,Contact Support<br> or resend link";
			$_SESSION['resend_link']='y';
				$_SESSION['cm']=$user_row2['mobile_number'];
		}
	}	   
}

if(isset($_POST['signup']))
{
 
	$name = addslashes($_POST['name']);
	$user_role = addslashes($_POST['user_role']);
	$email = addslashes($_POST['email']);
	$password = addslashes($_POST['password']);
	$security = addslashes($_POST['security']);
	$questions = addslashes($_POST['questions']);
	$countrycode = addslashes($_POST['countrycode']);
	$countrycode=60;
	$mobile_number = addslashes($_POST['mobile_number']);
	$account_type = addslashes($_POST['account_type']);
	
	$cm =	$countrycode.''.$mobile_number;

	$error = "";
	
	if($name == "")
	{
		$error .= "Name cannot be Empty.<br>";
	}
     $query="SELECT * FROM users WHERE mobile_number='$cm'";
	// die;
	$already_exists1 = mysqli_num_rows(mysqli_query($conn,$query));
	// echo $already_exists1;
	// die;
	 if($already_exists1 > 0)
	 {
		 $error .= "Mobile Number Already Exists.<br>";
	 }

	if($error == "")
	{
		$code = uniqid();

		$fund_pass = mt_rand(100000, 999999);  

		$ref_exists = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE mobile_number='$cm' AND user_roles != '3'"));
		
		if($ref_exists > 0 )
		{ 
		 $ref_id = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE mobile_number ='".$cm."'"));
		//~ $ref_id = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE mobile_number ='".$cm."'"));
		 $ref = $ref_id['referral_id'];

		}else{
			$ref = $name." ".$code;
		}
		
	
        if($account_type == ""){
            $k_date = "";
        } else {
            $k_date = date("Y-m-d");
        }
        
		$reffered_by= $_POST['referral_id'];
	    if($reffered_by != "") $date = date('Y-m-d');
	    else $date = "";
	   
		$code = uniqid();   
	    mysqli_query($conn, "INSERT INTO users SET name='$name',user_roles='$user_role',verification_code='$code',account_type='$account_type', k_date='$k_date',password='$password', joined='".time()."', isLocked='1',referral_id='$ref',referred_by='$reffered_by',security_answer= '$security',security_questions= '$questions',fund_password='$password',email='$email',mobile_number='$cm', created_at='$date', merchant_code='$merchant_code'");
        
		$user_id = mysqli_insert_id($conn);   
		$datetime = date('Y-m-d');
		if($account_type != ""){
		    mysqli_query($conn, "INSERT INTO k_type SET user_id='$user_id', type='$account_type', date='$datetime'");
		}
		$current_url = "https://".$_SERVER['HTTP_HOST']."".$_SERVER['REQUEST_URI'];
		$index_url="https://www.koofamilies.com/index.php?vs=".md5(rand());
      // if($user_role=="1")
	  // {
	    Print("Sending to one way sms .This link is only valid for 10 minutes" . gw_send_sms("APIHKXVL33N5E", "APIHKXVL33N5EHKXVL", "9787136232", "$cm", "Verify Your Account on koofamilies $index_url&code=$code&id=$user_id"));
		// gw_send_sms("APIHKXVL33N5E", "APIHKXVL33N5EHKXVL", "9787136232", "$cm", "Verify Your Account on koofamilies $current_url?code=$code&id=$user_id");
		$current_url = "https://".$_SERVER['HTTP_HOST']."".$_SERVER['REQUEST_URI'];
		$subject = "Verify Your Account | koofamilies";

		$message = "
		<html>
		<head>
		<title>Verify Your Account | koofamilies</title>
		
		<script type='text/javascript'>
    	navigator.serviceWorker.getRegistrations().then(function(registrations) {
		 for(let registration of registrations) {
		  registration.unregister()
		} })
    </script>
		</head>
		<body>
		<h3>Verify Your Account on koofamilies</h3>
		<p>You Can Verify Your Account By Visiting The Following Link :</p>
		<p style='text-align:center'><a href='$current_url?code=$code&id=$user_id'>Verify</a></p>
		</body>
		</html>
		";

		// Always set content-type when sending HTML email
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

		// More headers
		$headers .= 'From: <info@kooexchange.com>' . "\r\n";
		
		$error = "Registered Successfully, Verification Mobile Number has been sent to your Mobile Number.";
	  // }   
	  // else
	  // {
		  // $error = "Registered successfully, Please contact +60123115670 to activate your account";
	  // }

	}
		
		
	
}

if(isset($_POST['login']))
{
	$mobile_number = addslashes($_POST['mobile_number']);
	$password = addslashes($_POST['password']);
	$countrycode = addslashes($_POST['countrycode']);
	$user_role = addslashes($_POST['user_role']);
	 // $f_letter=$mobile_number[0];
		 // if($f_letter==0)
			// {
				// $mobile_number = substr($mobile_number, 1);
			// }  
	$cm =	$countrycode.''.$mobile_number;  
	// $cm2 =	$countrycode.''.$mobile_number;  
    // print_R($_POST);
	// die;
 	function updateStatus($session_id,$setup_session,$id){
		// setcookie("session_id", $session_id, time() + 3600 * 24 * 30 * 12 * 10,"/");
		
		// Set User Cookie
		$salt=md5(mt_rand());
		// $my_cookie_id = hash_hmac('sha512', $session_id, $salt);
		// $t_sql = "INSERT INTO pcookies SET user_id = '$id', cookie_id = '$my_cookie_id', salt = '$salt'";
		// setcookie("my_cookie_id", $my_cookie_id, time() + 3600 * 24 * 30 * 12 * 10,"/");
		// setcookie("my_token", $session_id, time() + 3600 * 24 * 30 * 12 * 10,"/");

 		$cm = $GLOBALS['cm'];
 		$password = $GLOBALS['password'];
 		$conn = $GLOBALS['conn'];
 		$token = bin2hex(openssl_random_pseudo_bytes(64));
		if($setup_session=="y")
		$sql = "UPDATE users SET shop_open='1',session = '$session_id', token = '$token' WHERE mobile_number = '$cm' AND password = '$password'";
		else
		$sql = "UPDATE users SET session = '$session_id', token = '$token' WHERE mobile_number = '$cm' AND password = '$password'";	
		if(mysqli_query($conn, $sql) && mysqli_query($conn, $t_sql)){
			return true;
		}else{
			return false;
		}
	}

	$error = "";
	
	if($mobile_number == "" )
	{
		$error .= "Mobile Number is not Valid.<br>";
	}
	$query1 = mysqli_query($conn, "SELECT * FROM users WHERE mobile_number='$cm' AND user_roles = '$user_role'");
	if($query1){
		$user_row1 = mysqli_num_rows($query1);
	}

	if($user_row1 == 0)
	{
			$error .= "Account not found, do you want to signup?.<br>";
	}   
	$user_row2 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE mobile_number='$cm'AND password='$password'")); 
	if($user_row2 == 0)
		{
				$error .= "You have entered wrong password, please try again.<br>";
		}  

	//~ if(!($user_role === $user_row2['user_roles'])){
		
		//~ // echo $user_role . " <---> " . $user_row2['user_roles'];
		//~ $error .= "Invalid type of account.";

	//~ }

	if($user_row2['isLocked'] == "1" && $user_row2['verification_code'] != "" )
	{
		$error .= "User registration pending, Please go through the link sent to your mobile number?.<br>";
	}
	//~ if($count == 0)
	//~ {
		//~ $error .= "Account does not exists in our Database.<br>";
	//~ } 
	if(strlen($password) >= 25 || strlen($password) <= 5)
	{
		$error .= "Password must be between 6 and 25.<br>";
	}
	// echo $error;
	// die;
	if(empty($error))
	{
		$time=time();	
		$user_row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT user_roles,id,isLocked,referral_id,name, mobile_number,setup_shop FROM users WHERE mobile_number='$cm' AND password='$password' AND user_roles = '$user_role'"));
		
		 $id = $user_row['id'];
		 $user_roles = $user_row['user_roles'];
		
		$referral_id = $user_row['referral_id'];
		$name = $user_row['name'];
		$mobile_number = $user_row['mobile_number'];
		$setup_session = $user_row['setup_shop'];
		// $_SESSION['setup_shop'] = $setup_session;
		
		if(!isset($cookie_id) || !isset($session_token)){
				$session_id =  uniqid($id . "_",true);
				// setcookie("session_id", $session_id, time() + 3600 * 24 * 30 * 12 * 10,"/");
				$_SESSION['login']=$id;
				$_SESSION['user_id']=$id;
					// $koofamilieslsvid = encrypt_decrypt('encrypt', $_SESSION['login']);
	
				// setcookie("koofamilieslsvid",$koofamilieslsvid,time()+31556926 ,'/');		
				
				
			if(updateStatus($session_id,$setup_session,$id)){
				//lucky
				
				$insert="insert into stafflogin set staff_id='$id',logintime='$time',session_id='$session_id'";
				mysqli_query($conn,$insert);
				if($user_roles==1)
		    	header("location:dashboard.php");
				else if($user_roles==2)
				header("location:dashboard.php");
			    else if($user_roles==5)

				header("location:dashboard.php");	

				header("location:dashboard.php");	 

			}else{
				echo "An error occuried, please, try again later.";
			}
		}
		if($id)
		{
			
		    if($user_row['isLocked'] == "0")
    		{
				
				$_SESSION['login'] = $id;
				$_SESSION['user_id'] = $id;
				$_SESSION['setup_shop'] = $setup_shop;
				$_SESSION['referral_id'] = $referral_id;
				$_SESSION['name'] = $name;
				$_SESSION['login_user_role'] = $user_role;
				$_SESSION['mobile'] = $mobile_number;
				
    		}
    		else
    		{
    			$error .= "Sorry, the user account is blocked, please contact support.<br>";
    		}
		}
		
		else
		{
			$error .= "Authentication failed. You entered an incorrect username or password.<br>";
		}
	}
}
	function updateCookieStatus($session_id,$setup_session,$id){
		// setcookie("session_id", $session_id, time() + 3600 * 24 * 30 * 12 * 10,"/");
		
		
		// $salt=md5(mt_rand());
		// $my_cookie_id = hash_hmac('sha512', $session_id, $salt);
		// $t_sql = "INSERT INTO pcookies SET user_id = '$id', cookie_id = '$my_cookie_id', salt = '$salt'";
		// setcookie("my_cookie_id", $my_cookie_id, time() + 3600 * 24 * 30 * 12 * 10,"/");
		// setcookie("my_token", $session_id, time() + 3600 * 24 * 30 * 12 * 10,"/");

 		
 		$conn = $GLOBALS['conn'];
 		$token = bin2hex(openssl_random_pseudo_bytes(64));
		if($setup_session=="y")
		$sql = "UPDATE users SET shop_open='1',session = '$session_id', token = '$token' WHERE id = '$id'";
		else
		$sql = "UPDATE users SET session = '$session_id', token = '$token' WHERE id = '$id'";	
		if(mysqli_query($conn, $sql) && mysqli_query($conn, $t_sql)){
			return true;
		}else{
			return false;
		}
	}
if(isset($_POST['forget']))
{
	$mobile_number = addslashes($_POST['mobile_number']);
	$countrycode = addslashes($_POST['countrycode']);
	$cm =	$countrycode.''.$mobile_number;
	$user_role = addslashes($_POST['user_role']);
	//$email = addslashes($_POST['email']);	
	$error = "";
	if($mobile_number == "" )
	{
		$error .= "Mobile Number is not Valid.<br>";
	}
	
	//~ if($email == "" || filter_var($email, FILTER_VALIDATE_EMAIL) === false)
	//~ {
		//~ $error .= "Email is not Valid.<br>";
	//~ }
	$data = mysqli_query($conn, "SELECT  password,isLocked FROM users WHERE mobile_number='$cm' AND user_roles = '$user_role' ");
	//~ $data = mysqli_query($conn, "SELECT password,isLocked FROM users WHERE email='$email'");
	$count = mysqli_num_rows($data);
	if($count == 0)
	{
		$error .= "Account does not exists in our Database.<br>";
	}
	
	$row = mysqli_fetch_assoc($data);      
	$lock_status = $row['isLocked'];
	$password = $row['password'];
	
	if($lock_status == 1)
	{
		$error .= "Sorry, the user account blocked, please contact support.<br>";
	}
	
	if($error == "")
	{
		$rand =mt_rand();
		$forgot_url = "https://".$_SERVER['HTTP_HOST']."/forgot_password.php?rand=".$rand."&mn=".$cm;
		 mysqli_query($conn, "UPDATE users SET rand_num='$rand',resetdate='".time()."' WHERE mobile_number='$cm' AND user_roles = '$user_role' ");

		Print("Sending to one way sms " . gw_send_sms("APIHKXVL33N5E", "APIHKXVL33N5EHKXVL", "9787136232", "$cm", "Password for your Account ($cm) : $forgot_url"));
		

		
		
	}
}

if(isset($_POST['forget_fund']))
{
	
	$mobile_number = addslashes($_POST['mobile_number']);
	$countrycode = addslashes($_POST['countrycode']);
	$cm =	$countrycode.''.$mobile_number;
	//$email = addslashes($_POST['email']);	
	$error = "";
	if($mobile_number == "" )
	{
		$error .= "Mobile Number is not Valid.<br>";
	}
	
	//~ if($email == "" || filter_var($email, FILTER_VALIDATE_EMAIL) === false)
	//~ {
		//~ $error .= "Email is not Valid.<br>";
	//~ }
	$data = mysqli_query($conn, "SELECT  fund_password,isLocked FROM users WHERE mobile_number='$cm'");
	
	//~ $data = mysqli_query($conn, "SELECT password,isLocked FROM users WHERE email='$email'");
	$count = mysqli_num_rows($data);
	if($count == 0)
	{
		$error .= "Account does not exists in our Database.<br>";
	}
	
	$row = mysqli_fetch_assoc($data);
	
	$lock_status = $row['isLocked'];
	$password = $row['fund_password'];
	
	if($lock_status == 1)
	{
		$error .= "Sorry, the user account blocked, please contact support.<br>";
	}
	
	if($error == "") 
	{
		
		Print("Sending to one way sms " . gw_send_sms("APIHKXVL33N5E", "APIHKXVL33N5EHKXVL", "9787136232", "$cm", "Fund Password for your Account ($cm) : $password"));
		
		
		
	}
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Login | koofamilies</title>
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

	</style>
    <!--//js-->
</head>

<body>


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
					 <?php if(isset($_SESSION['msg'])){ ?>
					 <p><?php echo $_SESSION['msg']; ?></p> 
					 <?php  unset($_SESSION['msg']);  } ?>
                        <div class="language">
							<select class="language_option form-control" name="language">
								<option <?php if($_SESSION["langfile"] == "english") echo "selected"?>>English</option>
								<option  value="chinese" <?php if($_SESSION["langfile"] == "chinese") echo "selected"?>>Chinese</option>
								<option value="malaysian" <?php if($_SESSION["langfile"] == "malaysian") echo "selected"?>>Malay</option>
							</select>
						</div>
						
							
                        <ul class="resp-tabs-list">
                            <li class="resp-tab-item" aria-controls="tab_item-0" role="tab"><span><?php echo $language["login"];?></span></li>
                            <li class="resp-tab-item" aria-controls="tab_item-1" role="tab"><label>/</label><span><?php echo $language["signup"];?></span></li>
							<li class="resp-tab-item sign_up" aria-controls="tab_item-2" role="tab"><label>/</label><span><?php echo $language["forget_password"];?></span></li>
                            <div class="clear"> </div>
                        </ul>
                        <div class="resp-tabs-container">
                            <div class="tab-1 resp-tab-content" aria-labelledby="tab_item-0">
								<?php
								 if($_SESSION['e'])
									$error=$_SESSION['e'];
								$_SESSION['e']='';
								$resend_link=$_SESSION['resend_link'];
								$send_no=$_SESSION['cm'];
								$_SESSION['resend_link']='';
								if(isset($error) && $error != "")
								{ 
									echo "<div class='alert alert-info' style='color:red;'>$error</div>";
                                    if($resend_link=="y"){
							    ?>
								<button style="margin-left:2%;"class="btn btn-primary" id="resend_link" user_mobile='<?php echo $send_no;?>'>Resend Link</button> 
									<div style="clear:both;"></div>
									</br>
									<span class='alert alert-info' id="resend_link_label" style="display:none;">Resend Link Shared to mobile Number</span>
								<?php
									}}
								?>
								<br>
								
                                 <form method="post" action="v.php">
                                <div class="login-top">	
										
                                        <input type="text" id="phone"  style="width:250px !important;" class="mobile_number intlTelInput" placeholder="<?php echo $language["telephone_number"];?>" name="mobile_number" required />
                                        <div class="select_optionss">
                                        <input type="radio" name="user_rolehwe" value="1" checked> <?php echo $language["member"];?>
										<input type="radio" name="user_rolehwe" value="2"> <?php echo $language["merchant"];?>
										
							
										<input type="hidden" name="user_role" value="1"> 
								<div style="display:none;margin-left: 89px;margin-top: 16px;" id="merchantoptionset">
									<input type="radio" name="user_roleget" value="2" checked="checked"> <?php echo $language["merchant"];?>
									<input type="radio" name="user_roleget" value="5"> <?php echo "Staff";?>
							</div>
									</div>
									
                                        <input type="password" name="password" id='login_pass' class="password" placeholder="<?php echo $language["password"];?>" required />
                                      <div class="input-group-addon">
										   <i  onclick="myFunction()" id="eye_slash" class="fa fa-eye-slash" aria-hidden="true"></i>
										  <span onclick="myFunction()" id="eye_pass"> Show Password </span>
									  </div>
									
                                 <br>
                                 <br>
								   <div class="row" style="margin-top:14%;margin-right: 10%;">
									  <input type="submit" value="<?php echo $language["login"];?>" name="login" class="submint_login" style="padding:14px;margin-top: -40px;background: #51d2b7;color:black;border: #51d2b7;" />
									</div>
                                </div>
                                </form>
                                                            <!-- newly added guest user button--->

<br>
								          <hr class="first_test"> Or <hr class="second_test">
								          <br>
								          <br>
                                 <button class="guest_user_bt" style="padding:15px;" onclick="location.href='<?php echo $site_url; ?>/merchant_find.php';"><?php echo $language["visitor_guest"];?></button>
								 
         

<!--
                           <button class="guest_user_bt" style="padding:5px;" onclick="location.href='http://kooexchange.com/demo/guest_user.php';">GUEST USER</button>
-->

           <div class="clear" style="padding:25px;"></div>
		    <!--hr class="first_test"> Or <hr class="second_test">
								 <div class="login-bottom login-bottom1" style="width:100%;clear:both;">

<a class="col-md-2" href="<?php echo $site_url;?>/facebook-login/fbconfig.php?via=login"><img src="img/login-cont-facebook.jpg" style=""></a>

</div!-->
                            </div>
                            <div class="tab-1 resp-tab-content" aria-labelledby="tab_item-1">

                                <form method="post" id="koosignup">
								<div class="login-top sign-top">
									<input type="radio" name="user_role" value="1" checked> <?php echo $language["member"];?>
									<input type="radio" name="user_role" value="2"> <?php echo $language["merchant"];?>
									<input type="text" class="name active" placeholder="User Name Here" name="name" value="<?php isset($name) ? $name : ""; ?>" id="reg_name" />
									
                                      <input type="text" class="mobile_number intlTelInput" style="width:250px !important;" placeholder="<?php echo $language["telephone_number"];?> " name="mobile_number" id="reg_mobnum" /> 

<!--
                                      <input type="text" class="company_name" placeholder="Company Name " name="company_name" id="company_name" /> 
-->
                                     
                                        <input type="password" class="password" id="Password"  placeholder="Password" name="password"  />
										 <div class="input-group-addon">
										   <i  onclick="myFunction2()" id="eye_slash_2" class="fa fa-eye-slash" aria-hidden="true"></i>
										  <span onclick="myFunction2()" id="eye_pass_2"> Show Password </span>
										   <small style="color:red;">(Please set passwords at least  6 digits and above)</small>
										</div>
                                         <input type="password" name="cpassword" id="cpassword"  placeholder="Confirm Password" class="col-md-9" >
										 <div class="input-group-addon">
										   <i  onclick="myFunction3()" id="eye_slash_3" class="fa fa-eye-slash" aria-hidden="true"></i>
										  <span onclick="myFunction3()" id="eye_pass_3"> Show Password </span> 
										  <br/>
										  <span>Defalut Password & Fund Password are same </span>
										  
										  
									  </div>
                                        <span id="message"></span> 
                                        <input type="text" class="referral_id" placeholder="Referral Id" name="referral_id" />    
                                       <div class="clear"></div> 
        								<label>Security Questions</label>
        								<select name= "questions" style="width: 100%;">
        								<option value="default">Select a desired question</option>
        								<option value="what is the name of your secondary school?">what is the name of your secondary school?</option>
        								<option value="What's the name of your best friend?">What's the name of your best friend?</option>
        								<option value="What is your favorite model of car?">What is your favorite model of car?</option>
        								<option value="Where would you like to visit again?">Where would you like to visit again?</option>
        								</select>
        								<input type="text" class="referral_id" placeholder="Security Answers" name="security" />
        								<input value="<?php isset($email) ? $email : ""; ?>" type="email" class="email" placeholder="Email" name="email"  />
        								
        								 <input type="hidden" name="signup" value="signup"/>
        								 <br>
        								 <!--label style="padding-right: 10px;">K1 / K2 Type: </label>
        								 <select name="account_type" class="form-control">
        								     <option value="">Non K1 / K2</option>
	                                         <option value="K1">K1</option>
	                                         <option value="K2">K2</option>
	                                         <option value="K1 & K2">K1 & K2</option>
	                                    </select!--> 
        								<div class="terms_condtions">
        								    <input type="checkbox" name="checkbox" value="check" id="agree" required/> I have read and agree with the <a class="termsss" href="<?php echo $site_url;?>/documents/terms/Terms and conditions for koofood.docx">"Terms and Conditions"</a> and <a class="termsss" href="<?php echo $site_url;?>/documents/terms/privacy agreement.docx">"Privacy Policy"</a>  From KOO.
        								</div>
                                           
                                        <div class="login-bottom">
                                            <div class="submit">
                                                <input type="submit" value="REGISTER" style="padding:14px;" />
                                            </div>
                                            <div class="clear"></div>
                                        </div>
                                    </div>
								</form>
                            </div>
							<div class="tab-1 resp-tab-content" aria-labelledby="tab_item-2">
                               
									
								<div class="login-top sign-top forgot_form_2">
									<label>User Login Password</label> <br>
									
									 <div class="select_optionss">
                                        <input type="radio" name="user_role" value="1" checked> <?php echo $language["member"];?>
									<input type="radio" name="user_role" value="2"> <?php echo $language["merchant"];?>
									</div>
									
								   
                                        <input type="text" id="forgot_mobile_number" class="mobile_number intlTelInput" style="width:250px !important;" placeholder="<?php $language["telephone_number"];?>" name="mobile_number" required />
										<input type="hidden"  value='0' id="otp_count"/>
										<input type="hidden" id="system_otp"/>
                                    <div class="forgot-bottom" style="margin:0px;">
                                        <div class="submit res_submit" style="margin-top:27px;">
                                                <input type="submit" value="<?php echo $language["submit"];?>" class="forgot_reset" style="padding:14px;" />
                                        </div><br/>
                                        <br />
                                        <br />
                                        <div class="clear" ></div>
                                    </div>
                                </div>
								<p id="forgot_msg_new" style="font-size: 16px;color: red;display:none;"><span style='font-size:20px;'>&#128512;</span> 

									Enter otp to reset password 

								</p>  
									<div class="form-group forgot_otp_form" style="display:none;margin-top: 7%;margin-left: 6%;">
								
					<div id="forgot_divOuter" style="margin-top: 7%;margin-left: 6%;">

						<div id="forgot_divInner">

							<small style="margin-left:20%;">Otp code</small>

							<input id="forgot_partitioned" type="Number" maxlength="4" />

							   <!--small style="float:right;color:#28a745;display:none;" class="resend send_otp">Resend</small!-->

							 <small class="forgot_otp_error" style="display: none;color:#e6614f;margin-left:20%;">

								Invalid Otp code

							</small>

						</div>

					</div>

					</div>
					  <div class="form-group forgot_register_password" style="display:none;margin-top: 7%;margin-left: 6%;">
             
							  <div class="passwd_field">
								
								<input type="password" id="forgot_register" placeholder="Enter New Password" class="form-control" name="forgot_register"/>
											
					   <i  onclick="myFunctionforgot()" id="eye_slash_forgot" class="fa fa-eye-slash" aria-hidden="true"></i>
					  <span id="eye_pass_forgot" onclick="myFunctionforgot()" > <?php echo $language['show_password']; ?> 
					 </span>
					  <small style="color:red;">(Please set passwords at least  6 digits and above)</small>
					   <small id="forgot_register_error" style="display: none;color:#e6614f;">
                 
                </small>
				
				<small id="forgot_reset_password_error" style="font-size:16px;display:none;color:#e6614f;"></small>
										
							  </div>
							  <div class="col forgot_password_submit" style="padding: 0;margin: 5px;display:none;">

							<input type="submit" class="btn btn-primary forgot_reset_password"  name="login_ajax" value="Change Password" style="float: right;display:none;"/>

							

						</div>  
				</div>
				<small id="forgot_reset_password_done" style="font-size:20px;display:none;color:black;margin-top: 7%;margin-left: 6%;"></small>
							
									
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
		 <script>
    var input = document.querySelector("#phone");
    var input2 = document.querySelector("#reg_mobnum");
    var input3 = document.querySelector("#forgot_mobile_number");
	// $("#phone").intlTelInput();
	
   window.intlTelInput(input, {
  initialCountry: "auto",
  separateDialCode:true,
  geoIpLookup: function(callback) {
    $.get('https://ipinfo.io', function() {}, "jsonp").always(function(resp) {
      var countryCode = (resp && resp.country) ? resp.country : "";
      callback(countryCode);
	  // console.log(countryCode);
    });
  },
  utilsScript: "build/js/utils.js?1585994360633" // just for formatting/placeholders etc
});
 window.intlTelInput(input2, {
  initialCountry: "auto",
  separateDialCode:true,
  geoIpLookup: function(callback) {
    $.get('https://ipinfo.io', function() {}, "jsonp").always(function(resp) {
      var countryCode = (resp && resp.country) ? resp.country : "";
      callback(countryCode);
    });  
  },
  utilsScript: "build/js/utils.js?1585994360633" // just for formatting/placeholders etc
});
window.intlTelInput(input3, {
  initialCountry: "auto",
  separateDialCode:true,
  geoIpLookup: function(callback) {
    $.get('https://ipinfo.io', function() {}, "jsonp").always(function(resp) {
      var countryCode = (resp && resp.country) ? resp.country : "";
      callback(countryCode);
    });
  },
  utilsScript: "build/js/utils.js?1585994360633" // just for formatting/placeholders etc
});
   
  </script>
   <script>
   function myFunctionforgot() {
  var x = document.getElementById("forgot_register");
  if (x.type === "password") {
    x.type = "text";
	    $("#eye_pass_forgot").html('Hide Password');
			 $('#eye_slash_forgot').removeClass( "fa-eye-slash" );
            $('#eye_slash_forgot').addClass( "fa-eye" );
			
  } else {
    x.type = "password";
	 $("#eye_pass_forgot").html('Show Password');
	  $('#eye_slash_forgot').addClass( "fa-eye-slash" );
            $('#eye_slash_forgot').removeClass( "fa-eye" );
  }
}
function myFunction() {
  var x = document.getElementById("login_pass");
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
function myFunction2() {
  var x = document.getElementById("Password");
  if (x.type === "password") {
    x.type = "text";
	    $("#eye_pass_2").html('Hide Password');
			 $('#eye_slash_2').removeClass( "fa-eye-slash" );
            $('#eye_slash_2').addClass( "fa-eye" );
			
  } else {
    x.type = "password";
	 $("#eye_pass_2").html('Show Password');
	  $('#eye_slash_2').addClass( "fa-eye-slash" );
            $('#eye_slash_2').removeClass( "fa-eye" );
  }
}
function myFunction3() {
  var x = document.getElementById("cpassword");
  if (x.type === "password") {
    x.type = "text";
	    $("#eye_pass_3").html('Hide Password');
			 $('#eye_slash_3').removeClass( "fa-eye-slash" );
            $('#eye_slash_3').addClass( "fa-eye" );
			
  } else {
    x.type = "password";
	 $("#eye_pass_3").html('Show Password');
	  $('#eye_slash_3').addClass( "fa-eye-slash" );
            $('#eye_slash_3').removeClass( "fa-eye" );
  }
}
</script>
    <script type="text/javascript">
    $(document).ready(function()
	{
		$(".forgot_reset").click(function(){
			 
              $(this).hide();
		    $(this).removeClass(" btn-primary").addClass("btn-default");

		 setTimeout(function() {

			

         $(this).removeClass("btn-default").addClass("btn-primary");

			}.bind(this), 5000);

		   // var usermobile=$("#mobile_number").val();
          var mobile=$("#forgot_mobile_number").val();
		 if(mobile=='')
		 {
			 $("#forgot_mobile_number").focus();
			 return false;
		 }
			if(mobile[0]==0)
			 {
				 mobile=mobile.slice(1);
			 }
		    var usermobile="60"+mobile;

		   // alert(usermobile);

		   var data = {usermobile:usermobile,method:"forgotpass2"};

			// alert(data);

			$.ajax({

			  

			  url :'functions.php',

			  type:'POST',

			  dataType : 'json',

			  data:data,  

			  success:function(response){

				  var data = JSON.parse(JSON.stringify(response));

				  // alert(data.status);
				// return false;
				  if(data.status)

				  {  

					$("#otp_count").val(otp_count);
					$("#system_otp").val(data.otp);
					$(".forgot_form_2").hide();
					$(".forgot_otp_form").show();
					

				  }

				  else

				  {
					  $(this).show();
					 
				  }

				  

				}		  

		  });

	   });
	       $('#forgot_partitioned').on('keyup', function(){ // consider `myInput` is class...

  var user_input = $(this).val();
  // alert(user_input);
  var page_otp=$("#system_otp").val();
   var usermobile="60"+$("#forgot_mobile_number").val();
   // alert(user_input);
   // alert(page_otp);
  if(user_input.length % 4 == 0){
	  if(user_input==page_otp)
	  {
		  $('.forgot_otp_error').hide();
		$('#forgot_msg_new').html('<span style="font-size:20px;">😀</span>Please create your password');
		$('.forgot_otp_form').hide();
		$('.forgot_password_submit').show();   
		$('.forgot_reset_password').show();
		$('.forgot_register_password').show();
		
	  }
	  else
	  {
		  $('.forgot_otp_error').show();
	  }
   
  }
});
$('.forgot_reset_password').click(function(){
		var forgot_register=$('#forgot_register').val();
		// alert(forgot_register);
		if((forgot_register.length)>5)
		{
			var mobile=$("#forgot_mobile_number").val();
			if(mobile[0]==0)
			 {
				 mobile=mobile.slice(1);
			 } 
			 var newpassword=$('#forgot_register').val();
			 var usermobile="60"+mobile;
			 if((newpassword.length)>5)
			{
			 var data = {usermobile:usermobile,method:"forgotresetpassword",password:newpassword};
			 $.ajax({
					  
					  url :'functions.php',
					  type:'POST',  
					  dataType : 'json',
					  data:data,
					  success:function(response){
							var data = JSON.parse(JSON.stringify(response)); 
						  if(data.status)
						  {
							  $("#forgot_reset_password_error").hide();
							var login_user_id=$('#login_user_id').val();
							var login_user_role=data.data.user_roles;
							localStorage.setItem('login_live_id',login_user_id);    
							localStorage.setItem('login_live_role_id',login_user_role);
							$('#login_for_wallet_id').val(login_user_id);
							
							   // alert('Your Password reset is done');
							   $("#forgot_reset_password_done").html("Your password change successfully");
							   $(".forgot_register_password").hide();
							   $("#forgot_reset_password_done").show();
							// $('#show_msg').html(data.msg);
							
							setTimeout(function(){ 
							
							window.location.replace("index.php");
							},2000); 
							 
						  }
						  else
						  {   
								$("#forgot_reset_password_error").html('Failed to Reset Password');
								$("#forgot_reset_password_error").show();
						  }
						  
						}		  
				  });
			}
			else
			{
				
				$("#forgot_register_error").html('Enter Atleaset 6 digit password');

				 $("#forgot_register_error").show();
			}
					
		}
		else
		{   
			$("#forgot_reset_password_error").html('Your New Password has to be 6 digit long');
			$("#forgot_reset_password_error").show();
		}
	});
		
		var local_id=localStorage.getItem("login_live_id");
          var login_role_id=localStorage.getItem("login_live_role_id");      
		if(local_id)
		{
				var data = {user_roles:login_role_id,method:"directlogin", login_cache_id:local_id};
				$.ajax({
					 url:"functions.php",
					 type:"post",
					 data:data,
					 dataType:'json',
					 success:function(result){
						var data = JSON.parse(JSON.stringify(result));
						if(data.status==true)
						{
							var user_roles=login_role_id;
							if(user_roles=='2')
							{
								window.location = "dashboard.php";
							} else if(user_roles=='1')
							{
								window.location = "dashboard.php";
							} else if(user_roles==4)
							{
								window.location = "dashboard.php";
							}
						}
						
					 }
				 });
			
		}	
		$('#resend_link').click(function(e) {
			$('#resend_link').hide();
			var user_mobile = $(this).attr("user_mobile");
			data = {user_mobile:user_mobile, method: "resendlink"};
			 $('#resend_link_label').show();    
			 $.ajax( {
                                url : "functions.php",
                                type:"post",
                                data : data,
                                dataType : 'json',
                                success : function(response) {
									// var data = JSON.parse(JSON.stringify(response));
									     $('#resend_link_label').show();    
                                },
                                error: function(data){
                                    console.log(data);
                                }
                });
				
				setTimeout(function() {

				$('#resend_link').show();

			  }, 300000);
				
		});
 $.validator.addMethod("valueNotEquals", function(value, element, arg){
  return arg !== value;
 }, "Value must not equal arg.");
jQuery.validator.addMethod("phoneUS", function(phone_number, element) {
    phone_number = phone_number.replace(/\\s+/g, ""); 
	return this.optional(element) || 
		phone_number.match(/^((\+[1-9]{1,4}[ \-]*)|(\([0-9]{2,3}\)[ \-]*)|([0-9]{2,4})[ \-]*)*?[0-9]{3,4}?[ \-]*[0-9]{3,4}?$/);
}, "Please specify a valid telephone number");
$.validator.addMethod('mypassword', function(value, element) {
        return this.optional(element) || (value.match(/[a-zA-Z]/) || value.match(/[0-9]/));
    },
    'Password must contain at least one numeric and one alphabetic character.');
   var theForm = $("#koosignup");
theForm.validate(

{

rules:{
    'countrycode': {
    required: true
 },

'name':{

required: true,

minlength: 1

},
//~ 'mobile_number':{

//~ required: true,
 //~ phoneUS: true,
 //~ minlength: 1,
//~ remote:{

//~ url: "validatorAJAX.php",

//~ type: "post",
//~ data: { countrycode: function(){
     //~ var countrycode = $('#countrycode').val();
     //~ return countrycode;
    //~ } ,
                  
        //~ },
//~ cache : false

//~ }
//~ },



//~ 'email':{


//~ email: true,

//~ remote:{

//~ url: "validatorAJAX.php",

//~ type: "post"

//~ }
//~ },
'password':{

required: true,
mypassword: true,
minlength: 6

},

'cpassword':{
    required: true,

equalTo: '#Password'

},


 //~ 'questions': { valueNotEquals: "default" },


 //~ 'security':{

//~ required: true,

//~ minlength: 1

//~ },



},

messages:{

'name':{

required: "The name field is mandatory!",

minlength: "Choose a username of at least 1 letters!",

},

'mobile_number':{

required: "The telephone number field is mandatory!",
remote: "The telephone number is already in use by another user!"

},

'email':{


   email: "Please enter a valid email address!",
	remote: "The email is already in use by another user!"

 },

'password':{

required: "The password field is mandatory!",

minlength: "Please enter a password at least 6 characters!"

},

'cpassword':{
required: "The confirm password field is mandatory!",

equalTo: "The two passwords do not match!"

},


    //~ 'questions': { valueNotEquals: "Please select an item!" },
//~ 'security':{

//~ required: "The security answers field is mandatory!",

//~ minlength: "Choose a username of at least 1 letters!",

//~ },

}

});
/*
if(theForm.valid() ) {
            theForm.submit(); //submitting the form
        }*/


        			$('#horizontalTab').easyResponsiveTabs({
        				type: 'default', //Types: default, vertical, accordion           
        				width: 'auto', //auto or any width like 600px
        				fit: true   // 100% fit in a container
        			});
        		});
    </script>  
        <script>  
$(document).ready(function(){ 
	 


      $("input[name='user_role']").on("click", function() {
           
            if($(this).val() == 1) 
            { 
    $("#reg_name").attr("placeholder", "User Name Here");
	}
	else
	{
		    $("#reg_name").attr("placeholder", "Company Name Here");

	}
 
            
        });
        });
        
$(document).ready(function(){ 
    
    $(".language_option").change(function(e){
        var lang = $(this).val();
        window.location.href="/login.php?language="+lang;
    });
	$(".sign_up").on("click", function() {
		
		// alert('goodgood');
		 
		   //~ $(".sign_up").css("background-color","green");
		   //~ $(".login-left").css("height", "255px !important");
		   //~ $(".res_submit").css("margin-top", "67px !important");
		   
		   
$('.logo').css('margin-top',"4px");

		  
		   //~ $(".login-left h2").css("margin-top", "0.5em !important");
		   
		
	});
});     
        
    </script>  
    <script type="text/javascript">
$(document).ready(function()
{
	
	
$("input[name='user_rolehwe']").change(function(){

//alert("sdfds");

					var getvalue=$("input[name='user_rolehwe']:checked").val();
					
					if(getvalue==1)
					{
						$("input[name='user_role']").val("1");
						$("#merchantoptionset").hide("slow");
					}
					else{
						$("#merchantoptionset").show("slow");
						$("input[name='user_role']").val("2");
					
					}
					
		});
		
		
		$("input[name='user_roleget']").change(function(){

					var getvalue=$("input[name='user_roleget']:checked").val();
					
					if(getvalue==2)
					{
						$("input[name='user_role']").val("2");
						
					}
					else{
							$("input[name='user_role']").val("5");
					
					}
					
		});
	
	
	



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
