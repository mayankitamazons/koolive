<?php 
include("config.php");
$_SESSION['pwd_show'] = 0;
if(isset($_POST['mobile_number']))
{
	// print_R($_POST);
	// die;
	
	$back_url_link = $_POST['back_url_link']; // last visited link set in hidden field
	$redirect_last_url = '';
	if($_POST['back_url_link']!= '') {
		$redirect_last_url = "?redirect=".urlencode($_POST['back_url_link']); //add redirect param;	
	}	
	
	$mobile_number = addslashes($_POST['mobile_number']);
	// if (substr($mobile_number, 0,2) === "60") {
			
	// }
	// else
	// {
		// $mobile_number="60".$mobile_number;
	// }     
	extract($_POST);
	$login_via = addslashes($_POST['login_via']);
	$otp = addslashes($_POST['str_otp']);
	$password = addslashes($_POST['password']);
	$countrycode = addslashes($_POST['countrycode']);
	$countrycode="60";
	$user_role = addslashes($_POST['user_role']);
	$mobile_number_first = substr(trim($mobile_number), 0, 1);
	if($mobile_number_first == 0){
		$cm =	'6'.$mobile_number;
	}else{
		$cm =	$countrycode.''.$mobile_number;
	}
	
	
    // print_R($_POST);
	// die;
 	function updateStatus($session_id,$setup_session,$id){
		
 		$cm = $GLOBALS['cm'];
 		$password = $GLOBALS['password'];
 		// $conn = $GLOBALS['conn'];
 		$token = bin2hex(openssl_random_pseudo_bytes(64));
		if($setup_session=="y")
		$sql = "UPDATE users SET shop_open='1' WHERE mobile_number = '$cm' AND password = '$password'";
		else
		$sql = "UPDATE users SET WHERE mobile_number = '$cm' AND password = '$password'";	
		
		if(mysqli_query($conn, $sql)){
			return true;
		}else{
			return false;
		}
	}

	$error = "";
	
	if($mobile_number == "" )
	{
		$error .= "Mobile Number is not Valid.<br>";
		$_SESSION['e']=$error;
		header("location:login.php".$redirect_last_url); 
	}
	$query1 = mysqli_query($conn, "SELECT * FROM users WHERE mobile_number='$cm' AND user_roles = '$user_role'");
	if($query1){
		$user_row1 = mysqli_num_rows($query1);
	}

	if($user_row1 == 0)
	{
			$error .= "Account not found, do you want to signup?.<br>";
			$_SESSION['e']=$error;
		header("location:login.php".$redirect_last_url); 
		die;
	}   
	if($login_via!="otp")
	{  
		$user_row2 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE mobile_number='$cm' AND password='$password'")); 
	}
	else
	{
		$user_row2 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE mobile_number='$cm' AND login_token='$otp'")); 
		
	}
		if($user_row2 == 0)
			{
					if($login_via=="otp") {
						$error .= "You have entered wrong code, please try again.<br>";
					}
					else {
						$_SESSION['pwd_show'] = 1;
						$error .= "You have entered wrong password, please try again.<br>";
					}
					$_SESSION['e']=$error;
			header("location:login.php".$redirect_last_url); 
			die;
			}  
	

	//~ if(!($user_role === $user_row2['user_roles'])){
		
		//~ // echo $user_role . " <---> " . $user_row2['user_roles'];
		//~ $error .= "Invalid type of account.";

	//~ }

	if($user_row2['isLocked'] == "1" )
	{
		$error .= "Account Registration pending, Please go through the link sent to your mobile number?.<br>";
			$_SESSION['e']=$error;
			$_SESSION['resend_link']='y';
			$_SESSION['cm']=$cm;
		// if($user_row2['user_roles']=='1')
		// {
			// $error .= "User registration pending, Please go through the link sent to your mobile number?.<br>";
			// $_SESSION['e']=$error;
			// $_SESSION['resend_link']='y';
			// $_SESSION['cm']=$cm;
		// }
		// else
		// {   
			// $error .="Your Account is Locked ,Please contact +60123115670 to activate your account";
			// $_SESSION['e']=$error;
		// }
		header("location:login.php".$redirect_last_url); 
		die;
	}
	//~ if($count == 0)
	//~ {
		//~ $error .= "Account does not exists in our Database.<br>";
	//~ } 
	if($login_via!="otp")
	{
		if(strlen($password) >= 25 || strlen($password) <= 5)
		{
			$_SESSION['pwd_show'] = 1;
			$error .= "Password must be between 6 and 25.<br>";
			$_SESSION['e']=$error;
			header("location:login.php".$redirect_last_url); 
		}
	}
	
	
	// echo $error;
	// die;
	if(empty($error))
	{
		$time=time();	
		// echo $q="SELECT user_roles,id,isLocked,referral_id,name, mobile_number,setup_shop FROM users WHERE mobile_number='$cm' AND password='$password' AND user_roles = '$user_role'";  
		print_R($_POST);
		if($login_via=="otp")
		{
			$q1="SELECT SQL_NO_CACHE onesignal_player_id,user_roles,id,isLocked,referral_id,name, mobile_number,setup_shop FROM users WHERE mobile_number='$cm' AND login_token='$otp' AND user_roles = '$user_role'";
			$user_row = mysqli_fetch_assoc(mysqli_query($conn,$q1));
			
			//update as a verified
			$q_o ="UPDATE `users` SET `otp_verified` = 'y' WHERE `mobile_number` ='$cm'";
			mysqli_query($conn,$q_o);
			
		}
		else
		{	
			$q1="SELECT SQL_NO_CACHE onesignal_player_id,user_roles,id,isLocked,referral_id,name, mobile_number,setup_shop FROM users WHERE mobile_number='$cm' AND password='$password' AND user_roles = '$user_role'";
			$user_row = mysqli_fetch_assoc(mysqli_query($conn,$q1));
		}
		 $id = $user_row['id'];
		 $user_roles = $user_row['user_roles'];
		
		$referral_id = $user_row['referral_id'];
		$name = $user_row['name'];
		$mobile_number = $user_row['mobile_number'];
		$setup_session = $user_row['setup_shop'];
		// $_SESSION['setup_shop'] = $setup_session;
		
	     $_SESSION['login']=$id;
				$_SESSION['user_id']=$id;
			if($user_row){
				if($id)
				{
					$_SESSION['pwd_show'] = 0;
					if($user_row['isLocked'] == "0")
					{
						
						$_SESSION['login'] = $id;
						$_SESSION['user_id'] = $id;
						$_SESSION['setup_shop'] = $setup_shop;
						$_SESSION['referral_id'] = $referral_id;
						$_SESSION['name'] = $name;
						$_SESSION['login_user_role'] = $user_role;
						$_SESSION['mobile'] = $mobile_number;
						$_SESSION['onesignal_player_id'] = $user_row['onesignal_player_id'];
						  
					}
					else
					{
						$_SESSION['pwd_show'] = 1;
						$error .= "Sorry, the user account is blocked, please contact support.<br>";
						$_SESSION['e']=$error;
						header("location:login.php".$redirect_last_url);
					}
				}
				else
				{
					$_SESSION['pwd_show'] = 1;
					$error .= "Authentication failed. You entered an incorrect username or password.<br>";
					$_SESSION['e']=$error;
					header("location:login.php".$redirect_last_url);
				}
				//lucky
				if($setup_session=="y")
				$sql = "UPDATE users SET shop_open='1' WHERE mobile_number = '$cm' AND password = '$password'";
				else
				$sql = "UPDATE users SET WHERE mobile_number = '$cm' AND password = '$password'";
				
				$insert="insert into stafflogin set staff_id='$id',logintime='$time',session_id='$session_id'";
				mysqli_query($conn,$sql);
				mysqli_query($conn,$q);
				mysqli_query($conn,$insert);												
								$back_url_link = $_POST['back_url_link']; // last visited link set in hidden field
				if($back_url_link != '')
				header("location:".$back_url_link);
				else if($user_roles==1)
		    	header("location:index.php");
				else if($user_roles==2)
				header("location:dashboard.php");
			    else if($user_roles==5)
				header("location:dashboard.php");	


				
				// header("location:dashboard.php");	 

			}else{
				$_SESSION['e']="An error occuried, please, try again later.";
				header("location:login.php".$redirect_last_url);  	
				echo "An error occuried, please, try again later.";
			}
		
		
	}
	else
	{
		$_SESSION['e']=$error;
		header("location:login.php".$redirect_last_url);  
	}
}  
?>