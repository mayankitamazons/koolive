<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <!-- <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Ameen - Bootstrap Admin Dashboard HTML Template</title> -->
    <!-- Favicon icon -->
    <!-- <link rel="icon" type="image/png" sizes="16x16" href="../../assets/images/favicon.png"> -->
    <!-- Custom Stylesheet -->
    <link href="<?=base_url();?>assets/css/style.css" rel="stylesheet">
    <script src="<?=base_url();?>assets/js/modernizr-3.6.0.min.js"></script>
</head>

<body class="h-100">
    <div id="preloader">
        <div class="loader">
            <svg class="circular" viewBox="25 25 50 50"><circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="3" stroke-miterlimit="10"/></svg>
        </div>
    </div>
    <div class="login-bg h-100">
        <div class="container h-100">
            <div class="row justify-content-center h-100">
                <div class="col-xl-6">
                    <div class="form-input-content">
                        <div class="card">
                            <div class="card-body">
                                <div class="logo text-center">
                                    <a href="index.html">
                                        <img src="<?=base_url();?>assets/images/logo.png" alt="" style="width: 60px; height:60px;">
                                    </a>
                                </div>
                                <h4 class="text-center m-t-15">Log into Your Account</h4>
                                <form class="m-t-30 m-b-30 form_login">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" name="email" class="form-control input-rounded login_email" placeholder="Email" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Password</label>
                                        <input type="password" name="password" class="form-control input-rounded login_pwd" placeholder="Password" required>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <div class="form-check p-l-0">
                                                <input class="form-check-input" type="checkbox" id="basic_checkbox_1">
                                                <label class="form-check-label" for="basic_checkbox_1">Check me out</label>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6 text-right"><a href="#">Forgot Password?</a>
                                        </div>
                                    </div>
                                    <div class="text-center m-b-15 m-t-15">
                                        <button type="submit" class="btn btn-primary submit_login">Sign in</button>
                                    </div>
                                </form>
                                <div class="text-center">
                                    <!-- <h5 class="m-b-30">Or with Login</h5>
                                    <ul class="list-inline">
                                        <li class="list-inline-item m-t-10"><a href="#" class="btn btn-facebook"><i class="fa fa-facebook"></i></a>
                                        </li>
                                        <li class="list-inline-item m-t-10"><a href="#" class="btn btn-twitter"><i class="fa fa-twitter"></i></a>
                                        </li>
                                        <li class="list-inline-item m-t-10"><a href="#" class="btn btn-linkedin"><i class="fa fa-linkedin"></i></a>
                                        </li>
                                        <li class="list-inline-item m-t-10"><a href="#" class="btn btn-google-plus"><i class="fa fa-google-plus"></i></a>
                                        </li>
                                    </ul> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="<?=base_url();?>assets/plugins/common/common.min.js"></script>
    <!-- Custom script -->
    <script src="<?=base_url();?>assets/js/custom.min.js"></script>
    <script src="<?=base_url();?>assets/js/auth/login.js"></script>
</body>

</html>
    
    