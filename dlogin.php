<?php
$id=$did;
  $d_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$did."'"));
  
  if($d_data)
  {
	   
	function updateStatus($session_id,$setup_session,$id,$d_data){
		// setcookie("session_id", $session_id, time() + 3600 * 24 * 30 * 12 * 10,"/");
		
		// Set User Cookie
		$salt=md5(mt_rand());
		// $my_cookie_id = hash_hmac('sha512', $session_id, $salt);
		// $t_sql = "INSERT INTO pcookies SET user_id = '$id', cookie_id = '$my_cookie_id', salt = '$salt'";
		// setcookie("my_cookie_id", $my_cookie_id, time() + 3600 * 24 * 30 * 12 * 10,"/");
		// setcookie("my_token", $session_id, time() + 3600 * 24 * 30 * 12 * 10,"/");
 		$conn = $GLOBALS['conn'];
 		// $token = bin2hex(openssl_random_pseudo_bytes(64));
		if($setup_session=="y")
		$sql = "UPDATE users SET shop_open='1',session = '$session_id' WHERE id = '$id'";
		else
		$sql = "UPDATE users SET session = '$session_id' WHERE id = '$id'";	
		if(mysqli_query($conn, $sql)){
			
				$session_id =  uniqid($id . "_",true);
				/* setcookie("session_id", $session_id, time() + 3600 * 24 * 30 * 12 * 10,"/"); */
				$_SESSION['login']=$id;
				$_SESSION['user_id']=$id;
					// $koofamilieslsvid = encrypt_decrypt('encrypt', $_SESSION['login']);
	
				// setcookie("koofamilieslsvid",$koofamilieslsvid,time()+31556926 ,'/');		
				
			return true;
		}else{
			return false;
		}
	}     
	     $session_id =  uniqid($id . "_",true);
				$setup_session=$d_data['setup_shop'];
				$time=time();	
			if(updateStatus($session_id,$setup_session,$id,$d_data)){
				//lucky
				
				$insert="insert into stafflogin set staff_id='$id',logintime='$time',session_id='$session_id'";
				mysqli_query($conn,$insert);
				
			}else{
				echo "An error occuried, please, try again later.";
			}
		    // print_R($d_data);
			// die;
			if($d_data['isLocked'] == "0")
    		{
				
				$_SESSION['login'] = $did;
				$_SESSION['user_id'] = $did;
				$_SESSION['setup_shop'] = $d_data['setup_shop'];
				$_SESSION['referral_id'] = $d_data['referral_id'];
				$_SESSION["langfile"]="english";
				$_SESSION["cash_id"]="";
				
				$_SESSION['name'] = $d_data['name'];
				$_SESSION['login_user_role'] = $d_data['user_roles'];
				$_SESSION['mobile'] = $d_data['mobile_number'];
				
    		}
  }

?>