<?php
include("config.php");

if($_GET['rpage'] && $_GET['rpage'] == 'riders' ){
	
	$_SESSION['rr_page'] = $_GET['rpage'];
}

if($_GET['mdadmin'] && $_GET['mdadmin'] == '1' ){
	
	unset($_SESSION['rr_page']);
	$_SESSION['mdadmin_page'] = $_GET['mdadmin'];
}

if(isset($_POST['login']))
{
	$email = addslashes($_POST['email']);
	$password = addslashes($_POST['password']);
	
	$error = "";
	if($error == "")
	{
		
		
		if($_SESSION['mdadmin_page'] == '1'){
			$id = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM s_admin WHERE username='$email' AND password3='$password'"))['id'];
		}else{
			$id = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM s_admin WHERE username='$email' AND password2='$password'"))['id'];
		}
		
		
		   
		if($id)
		{
			
			
			if($_SESSION['rr_page'] == 'riders'){
				$_SESSION['madmin'] = $id;
				header("location:riders.php");
			}else if($_SESSION['mdadmin_page'] == '1'){
				$_SESSION['mdadmin']  = $id;
				header("location:delete_merchant.php");
			}else{
				$_SESSION['madmin'] = $id;
				header("location:user.php");   
			}
			
		}
		else
		{
			$error .= "Credentials are not valid.<br>";
		}
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
        <h1>User System</h1>
        <div class="login-form">
            <div class="login-left">
                <div class="logo">
                    <img style="    max-width: 92%;" src="images/Icon-user.png" alt="" />
                    <h2>Hello Admin,</h2>
                    <p>Welcome to kooAdmin</p>
                </div>
            </div>
            <div class="login-right" style="padding-bottom: 72px;">
                <div class="sap_tabs">
                    <div id="horizontalTab" style="display: block; width: 100%; margin: 0px;">
                        <ul class="resp-tabs-list">
                            <li class="resp-tab-item" aria-controls="tab_item-0" role="tab"><span>Login</span></li>
                            
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
                                        <input type="text" name="email" class="email" placeholder="Username" required="" />
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
                                        <input type="text" name="email" class="email" id="forget_email" placeholder="Username" required="" />
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
        <p> &copy; 2015 | All rights reserved | Developed by <a href="#" target="_blank">Koofamilies</a></p>
    </div>	
</body>

</html>