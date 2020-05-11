<?php
include("config.php");

if(isset($_SESSION['admin']))
{
	header("location:index.php");
}


if(isset($_POST['login']))
{
	$email = addslashes($_POST['email']);
	$password = addslashes($_POST['password']);
	
	$error = "";
	
	if($email == "" || filter_var($email, FILTER_VALIDATE_EMAIL) === false)
	{
		$error .= "Email is not Valid.<br>";
	}
	
	if(strlen($password) >= 15 || strlen($password) <= 7)
	{
		$error .= "Password must be between 7 and 15.<br>";
	}
	
	if($error == "")
	{
		$id = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM admins WHERE email='$email' AND password='$password'"))['id'];
		if($id)
		{
			$_SESSION['admin'] = $id;
			header("location:index.php");
		}
		else
		{
			$error .= "Credentials are not valid.<br>";
		}
	}
}

if(isset($_POST['forget']))
{
	$email = addslashes($_POST['email']);	
	$error = "";
	
	if($email == "" || filter_var($email, FILTER_VALIDATE_EMAIL) === false)
	{
		$error .= "Email is not Valid.<br>";
	}
	
	$data = mysqli_query($conn, "SELECT password FROM admins WHERE email='$email'");
	$count = mysqli_num_rows($data);
	if($count == 0)
	{
		$error .= "Account does not exists in our Database.<br>";
	}
	
	$row = mysqli_fetch_assoc($data);
	$password = $row['password'];
	
	if($error == "")
	{
		$subject = "Forget Password | Admin | KooExchange";

		$message = "
		<html>
		<head>
		<title>Forget Password | Admin | KooExchange</title>
		</head>
		<body>
		<p>Password for your Admin Account ($email) : $password</p>
		</body>
		</html>
		";

		// Always set content-type when sending HTML email
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

		// More headers
		$headers .= 'From: <info@dmrpayu.com>' . "\r\n";
		//$headers .= 'Cc: myboss@example.com' . "\r\n";

		mail($email,$subject,$message,$headers);
		$error = "Password has been sent to your Email Address.<br>";
	}
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Admin Panel | KooExchange</title>
    <!--Custom Theme files-->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="keywords" content="Tab Login Form widget template Responsive, Login form web template,Flat Pricing tables,Flat Drop downs  Sign up Web Templates, Flat Web Templates, Login signup Responsive web template, SmartPhone Compatible web template, free WebDesigns for Nokia, Samsung, LG, SonyEricsson, Motorola web design"
    />
    <script type="application/x-javascript">
        addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); }
    </script>
    <!-- Custom Theme files -->
    <link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
    <!--web-fonts-->
    <link href='//fonts.googleapis.com/css?family=Signika:400,300,600,700' rel='stylesheet' type='text/css'>
    <link href='//fonts.googleapis.com/css?family=Righteous' rel='stylesheet' type='text/css'>
    <!--//web-fonts-->
    <!--js-->
    <script src="js/jquery.min.js"></script>
    <script src="js/easyResponsiveTabs.js" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function () {
        			$('#horizontalTab').easyResponsiveTabs({
        				type: 'default', //Types: default, vertical, accordion           
        				width: 'auto', //auto or any width like 600px
        				fit: true   // 100% fit in a container
        			});
        		});
    </script>
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
	</style>
    <!--//js-->
</head>

<body>
    <!-- main -->
    <div class="main">
        <h1>Login Form</h1>
        <div class="login-form">
            <div class="login-left">
                <div class="logo">
                    <img style="    max-width: 92%;" src="images/Icon-user.png" alt="" />
                    <h2>Hello Admin,</h2>
                    <p>Welcome to KooExchange</p>
                </div>
            </div>
            <div class="login-right" style="padding-bottom: 72px;">
                <div class="sap_tabs">
                    <div id="horizontalTab" style="display: block; width: 100%; margin: 0px;">
                        <ul class="resp-tabs-list">
                            <li class="resp-tab-item" aria-controls="tab_item-0" role="tab"><span>Login</span></li>
                            <li class="resp-tab-item" aria-controls="tab_item-1" role="tab"><label>/</label><span>Forget Password</span></li>
                            <div class="clear"> </div>
                        </ul>
                        <div class="resp-tabs-container">
                            <div class="tab-1 resp-tab-content" aria-labelledby="tab_item-0">
								<?php
								if(isset($error) && $error != "")
								{
									echo "<div class='alert alert-danger'>$error</div>";
								}
								if(isset($success) && $success != "")
								{
									echo "<div class='alert alert-success'>$success</div>";
								}
								?>
                                <form method="post">
                                <div class="login-top">
                                        <input type="email" name="email" class="email" placeholder="Email" required="" />
                                        <input type="password" name="password" class="password" placeholder="Password" required="" />
                                    
                                    <div class="login-bottom login-bottom1">
                                        <div class="submit">
                                                <input type="submit" value="LOGIN" name="login" />
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                                </form>
                            </div>
							
							<div class="tab-1 resp-tab-content" aria-labelledby="tab_item-1">
								<?php
								if(isset($error) && $error != "")
								{
									echo "<div class='alert alert-danger'>$error</div>";
								}
								if(isset($success) && $success != "")
								{
									echo "<div class='alert alert-success'>$success</div>";
								}
								?>
                                <form method="post" style="padding-bottom: 56px;">
                                <div class="login-top">
                                        <input type="email" name="email" class="email" id="forget_email" placeholder="Email" required="" />
                                </div>
								<div class="login-bottom login-bottom1">
                                        <div class="submit2" style="margin-left: 20px;">
                                                <input type="submit" value="SUBMIT" name="forget"  id="submit"/>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                </form>
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
        <p> &copy; 2015 | All rights reserved | Developed by <a href="#" target="_blank">WebDealSoft Technology</a></p>
    </div>	
</body>

</html>