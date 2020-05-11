<?php
include("config.php");
 //~ $bank_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$_SESSION['login']."'"));
$total_rows = mysqli_query($conn, "SELECT * FROM users WHERE user_roles='2' ");

if(isset($_POST['submit_pass']))
{
   // print_r($_POST);	
   // die;
	$rand = $_GET['rand'];
	$mn = $_GET['mn'];
	$user_row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT user_roles,id,isLocked,referral_id,name, mobile_number,setup_shop FROM users WHERE rand_num='".$rand."'"));
	$id = $user_row['id'];
	$user_roles = $user_row['user_roles'];
	$new_password = addslashes($_POST['new_password']);
	// $confirm_new_password = addslashes($_POST['confirm_new_password']);
	$questions = addslashes($_POST['questions']);
	$answers = addslashes($_POST['answer']);
	$flag = false;
	$error = "";
	$t_query=mysqli_query($conn, "select * from users  WHERE rand_num='".$rand."'");
	$rcount = mysqli_num_rows($t_query);
	if($rcount==0)
	{
		$flag = true;
		$error .= "Your token is expire,Try again with new token<br>";
	}
	if($new_password == "")
	{
		$flag = true;
		$error .= "All Fields are required.<br>";
	} 
	
	if(strlen($new_password) < 3 || strlen($new_password) > 15)
	{
		$flag = true;
		$error .= "New Password must between 4 to 15 characters.<br>";
	}
	
	
		//~ if($security_questions != $questions)
	//~ {
		//~ $flag = true;
		//~ $error .= "Your chosen question is incorrect.<br>";
	//~ }
		//~ if($security_answer != $answers)
	//~ {
		//~ $flag = true;
		//~ $error .= "Your Answer is wrong.<br>";
	//~ }
	
	// ; 
	 session_start();
	if($flag == false)
	{
		// echo "UPDATE users SET password='$new_password', rand_num = '' WHERE rand_num='".$rand."'";
		// die;
		 mysqli_query($conn, "UPDATE users SET password='$new_password',password_created='y',rand_num = '' WHERE rand_num='".$rand."'");
		 $error = "Password Successfully Changed.";
		
		 $_SESSION['msg']="Your password has been successfully reset. please login with your new password";
		// session_destroy();
		$session_id =  uniqid($id . "_",true);
		// setcookie("session_id", $session_id, time() + 3600 * 24 * 30 * 12 * 10,"/");
		$_SESSION['login']=$id;
		$_SESSION['user_id']=$id;
		$koofamilieslsvid = encrypt_decrypt('encrypt', $_SESSION['login']);
		// setcookie("koofamilieslsvid",$koofamilieslsvid,time()+31556926 ,'/');		
		if(updateStatus($session_id,$setup_session,$id)){
				//lucky
				
				$insert="insert into stafflogin set staff_id='$id',logintime='$time',session_id='$session_id'";
				mysqli_query($conn,$insert);
				if($user_roles==1)
		    	header("location:merchant_find.php");
				else if($user_roles==2)
				header("location:orderview.php");
			    else if($user_roles==5)
				header("location:orderview-staff.php");	
			}else{
				echo "An error occuried, please, try again later.";
			}
		// header("location:merchant_find.php");
		
	}
	else
	{
		 $_SESSION['error']=$error;
	}
}
function updateStatus($session_id,$setup_session,$id){
		
		// Set User Cookie
		$salt=md5(mt_rand());
		
 		$cm = $GLOBALS['cm'];
 		$password = $GLOBALS['password'];
 		$conn = $GLOBALS['conn'];
 		$token = bin2hex(openssl_random_pseudo_bytes(64));
		if($setup_session=="y")
		$sql = "UPDATE users SET shop_open='1',session = '$session_id', token = '$token' WHERE mobile_number = '$cm' AND password = '$password'";
		else
		$sql = "UPDATE users SET session = '$session_id', token = '$token' WHERE mobile_number = '$cm' AND password = '$password'";	
		if(mysqli_query($conn, $sql)){
			return true;
		}else{
			return false;
		}
	}

   $expire_stamp = date('Y-m-d H:i:s', strtotime("+5 min"));
  // echo '<br>';
 $now_stamp    = date("Y-m-d H:i:s");

//~ echo "Right now: " . $now_stamp;
//~ echo "5 minutes from right now: " . $expire_stamp;


?>

  
<div class="col-md-6 well">

						    <form method="post" id="forgotform" name="forgotform">
        						<h3>Forgot Password</h3>
        						
        						<div class="form-group">
        							<label>New Password</label>
        							<input type="password" id="new_password" class="form-control" name="new_password" required>
									  
       <i  onclick="myFunction()" id="eye_slash" class="fa fa-eye-slash" aria-hidden="true"></i>
	  <span onclick="myFunction()" id="eye_pass"> Show Password </span>
    
        						</div>
        						<!--div class="form-group">
        							<label>Confirm New Password</label>
        							<input type="password" id="confirm_new_password" class="form-control" name="confirm_new_password" required>
									 
       <i  onclick="myFunction2()" id="eye_slash_2" class="fa fa-eye-slash" aria-hidden="true"></i>
	  <span onclick="myFunction2()" id="eye_pass_2"> Show Password </span>
     
        						</div!-->
<?php if(isset($_SESSION['error'])){ ?>
					 <p style="color:red;"><?php echo $_SESSION['error']; ?></p> 
					 <?php   unset($_SESSION['error']);} ?>        						
        						<input type="submit" value="Change Password" name="submit_pass" class="btn btn-block btn-primary">
        					</form>
        					<!--fund password-->
        					</div>
<!-- CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="./Dashboard_files/material-icons.css" rel="stylesheet" type="text/css">
<link href="./Dashboard_files/monosocialiconsfont.css" rel="stylesheet" type="text/css">
<link href="./Dashboard_files/sweetalert2.css" rel="stylesheet" type="text/css">
<link href="./Dashboard_files/magnific-popup.min.css" rel="stylesheet" type="text/css">
<link href="./Dashboard_files/mediaelementplayer.min.css" rel="stylesheet" type="text/css">
<link href="./Dashboard_files/perfect-scrollbar.min.css" rel="stylesheet" type="text/css">
<link href="./Dashboard_files/css" rel="stylesheet" type="text/css">
<link href="./Dashboard_files/css(1)" rel="stylesheet" type="text/css">
<link href="./Dashboard_files/css(2)" rel="stylesheet" type="text/css">
<link href="./Dashboard_files/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="./Dashboard_files/weather-icons.min.css" rel="stylesheet" type="text/css">
<link href="./Dashboard_files/weather-icons-wind.min.css" rel="stylesheet" type="text/css">
<link href="./Dashboard_files/daterangepicker.min.css" rel="stylesheet" type="text/css">
<link href="./Dashboard_files/morris.css" rel="stylesheet" type="text/css">
<link href="./Dashboard_files/slick.min.css" rel="stylesheet" type="text/css">
<link href="./Dashboard_files/slick-theme.min.css" rel="stylesheet" type="text/css">
<link href="./Dashboard_files/style.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<!-- Head Libs -->
<script src="js/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.15.1/jquery.validate.min.js"></script>
<script src="./Dashboard_files/modernizr.min.js.download"></script>
<style>
.col-md-6.well {
    margin: 15px auto;
    border: 1px solid #8080803b;
        width: 100%;s
}
select {
    padding: 8px;
}

</style>
 <script>
function myFunction() {
  var x = document.getElementById("new_password");
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
  var x = document.getElementById("confirm_new_password");
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
</script>
  <script type="text/javascript">
    $(document).ready(function()
	{
	    

	   $("form[name='forgotform']").validate({
    // Specify validation rules
    rules: {
      // The key name on the left side is the name attribute
      // of an input field. Validation rules are defined
      // on the right side
     
      new_password: {
        required: true,
        minlength: 6
      }
    },
    // Specify validation error messages
    messages: {
     
      new_password: {
        required: "Please provide a password",
        minlength: "Your password must be at least 6 characters long"
      },
	  
      // email: "Please enter a valid email address"
    },
    // Make sure the form is submitted to the destination defined
    // in the "action" attribute of the form when valid
    submitHandler: function(form) {
      form.submit();
    }
  });

    });
    </script>  
	
