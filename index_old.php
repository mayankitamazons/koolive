<?php
include("config.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
 
  <!--Meta-->
  <meta charset="UTF-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="description" content="A complete landing page solution for any business">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <!--Favicon-->
  <link rel="icon" href="">
  
  <!-- Title-->
  <title>Koo Exchange</title>
  
  <!--Google fonts-->
  <link href="https://fonts.googleapis.com/css?family=Dosis:400,500,600,700%7COpen+Sans:400,600,700" rel="stylesheet">
  
	<!--Icon fonts-->
	<link rel="stylesheet" href="assets/vendor/strokegap/style.css">
	<link rel="stylesheet" href="assets/vendor/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="assets/vendor/linearicons/style.css">
  
  <!-- Stylesheet-->
  <!--
// ////////////////////////////////////////////////
// To Reduce server request and improved page speed drastically all third-party plugin bundle in assets/css/bundle.css
// If you wanna add those manually bellow is the sequence 
// ///////////////////////////////////////////////
-->
<!--  <link rel="stylesheet" href="assets/vendor/bootstrap/dist/css/bootstrap.min.css">  
  <link rel="stylesheet" href="assets/vendor/slick-carousel/slick/slick.css">
  <link rel="stylesheet" href="assets/vendor/fancybox/dist/jquery.fancybox.min.css">
  <link rel="stylesheet" href="assets/vendor/animate.css/animate.min.css">-->
  
  <link rel="stylesheet" href="assets/css/bundle.css">
  <link rel="stylesheet" href="assets/css/style.css">
  
  <style>
  
  .border {
    border: 1px solid #e9ecef00!important;}
.logo{
    color: #fff;
    font-size: 37px;
}
.u-mt-90 {
    margin-top: 10rem !important;
}
 .step-number {
    left: 20px;
    width: 50px;
    height: 50px;
    padding-top: 2px;
    font-size: 32px;
    font-weight: 200;
    text-align: center;
    background: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 50%;
}
.label{
    color: #fff;
    font-size: 23px;
}
.label_p{
    color: #fff;
    font-size: 23px;
} 

.select{
    border: 1px solid #e9eff5;
    padding: 9px 30px;
    border-radius: 4px;
    width: 100%;
    margin-bottom: 10px;
}
 </style>
</head>

 <body id="top">
 
 
<!--[if lt IE 8]>
<p>You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->



 <header class="header header-shrink header-inverse fixed-top">
  <div class="container">
		<nav class="navbar navbar-expand-lg px-md-0">
			<a class="navbar-brand" href="index.php">
				<span class="logo-default">
					<h3 class="logo">Koo Exchange</h3>
				</span>
				<span class="logo-inverse">
					<h3>Koo Exchange</h3>
				</span>
			</a>

			<div class="navbar-toggler" data-toggle="collapse" data-target="#navbarNav">
				<span class="lnr lnr-text-align-right nav-hamburger"></span>
				<span class="lnr lnr-cross nav-close"></span>
			</div>

			<div class="collapse navbar-collapse" id="navbarNav">
				<ul class="navbar-nav ml-auto">
					<li class="nav-item active">
						<a class="nav-link" href="about.php">About Us</a>
					</li>
					<?php
					if(!isset($_SESSION['login']))
					{
					?>
					<li class="nav-item active">
						<a class="nav-link" href="login.php">Login</a>
					</li>
					<li class="nav-item dropdown">
						<a class="nav-link" href="login.php">Sign Up
						</a>
					</li>
					<?php
					}
					else
					{
					?>
					<li class="nav-item dropdown">
						<a class="nav-link" href="dashboard.php">Dashboard
						</a>
					</li>
					<?php
					}
					?>
					<li class="nav-item dropdown">
						<a class="nav-link" href="#">Download App</a>
					</li>
				</ul>
			</div>

		</nav>
  </div> <!-- END container-->
</header> <!-- END header -->

<section class="u-py-100 u-h-100vh u-flex-center" style="  background-image: -webkit-linear-gradient(90deg, #113a82 0%, #157af9 100%);
    background-image: -o-linear-gradient(90deg, #113a82 0%, #157af9 100%);
    background-image: linear-gradient(0deg, #113a82 0%, #157af9 100%);
    background-image: url(img/circles.png), -webkit-linear-gradient(90deg, #113a82 0%, #157af9 100%);
    background-image: url(img/circles.png), -o-linear-gradient(90deg, #113a82 0%, #157af9 100%);
    background-image: url(img/circles.png), linear-gradient(0deg, #113a82 0%, #157af9 100%);
    -webkit-background-size: cover;
    background-size: cover;
">
  <div class="container">
   
    <div class="row">
      <div class="col-6" style="margin-top:10px;">
        <h1 class="display-4 u-fw-600 text-white u-mb-40">
         Safe, Easy, Immediate Payment and Earn Income Everyday
        </h1>
        <p class="u-fs-22 text-white u-lh-1_8 u-mb-40">
		Build a cashless society, Give us a Safe and Integrity Society, ( Your money is guaranteed by Certified Lawyer of Malaysia )
        </p>
		<a href="login.php" class="btn btn btn-rounded btn-primary m-2 px-md-5 py-3">
        	 GET START 
        </a>
      </div> 
		 <div class="col-6 text-center"><img src="img/mobile.png"class="img-responsive"></div>

     
        <!-- END col-lg-6-->
    </div> <!-- END row-->
    
    
  </div> <!-- END container-->
</section> <!-- END intro-hero-->
<section>
	<div class="container">
		<h2 class="h1 text-center">
		Three steps to pay / transfer money / earn income / top up Wechat/Alipay
		</h2>
		<div class="row align-items-center">			
			<div class="col-lg-6">
			<div class="media mt-4">
				<span class="step-number text-primary u-fs-28 mr-3 mt-2">1</span>
					<div class="media-body">
						<h4 class="h4">
							<a href="login.php">Sign Up</a>
						</h4>
						<p>
							Sign up for a free Koopay / Wechat / Alipay Free wallet on web, iOS or Android and through easy verification process.
						</p>
					</div>
				</div>
			<div class="media mt-4">
				<span class="step-number text-primary u-fs-28 mr-3 mt-2">2</span>
					<div class="media-body">
						<h4 class="h4">
						<a href="login.php">Deposit money</a>
						</h4>
						<p>
							Select your preferred deposit method like bank, Wechat, Alipay or credit card and deposit money into your own wallet. ( Your money is safely guarantee by lawyer in Malaysia )
						</p>
					</div>
				</div>
				<div class="media mt-4">
				<span class="step-number text-primary u-fs-28 mr-3 mt-2">3</span>
					<div class="media-body">
						<h4 class="h4">
						<a href="login.php">Make payment / transfer / withdraw money (Free of charge)</a>
						</h4>
						<p>
							You can easily make payment or transfer money through 100% secure method of authorisation code or finger print. 
						</p>
					</div>
				</div>
				<div class="media mt-4">
				<span class="step-number text-primary u-fs-28 mr-3 mt-2">4</span>
					<div class="media-body">
						<h4 class="h4">
						<a href="login.php">Earn Income</a>
						</h4>
						<p>
							You can start to earn money by advertising on your social networks. And you can use the money to pay for your bills. 
						</p>
					</div>
				</div>
				<div class="media mt-4">
				<span class="step-number text-primary u-fs-28 mr-3 mt-2">5</span>
					<div class="media-body">
						<h4 class="h4">
						<a href="login.php">Donation </a>
						</h4>
						<p>
							If you like our idea and would like to donate to our team to improve the society. Or you can Join our Crowdfunding

						</p>
					</div>
				</div>
			</div> <!-- END col-lg-6 -->
			<div class="col-lg-5 ml-auto text-center">
				<img class="wow fadeInRight w-100 rounded" src="img/mobile1.png" alt="">
			</div> <!-- END col-lg-6 pl-lg-5 -->
		</div> <!-- END row-->
	</div> <!-- END container-->
</section>

<section>
  <div class="container">
   <div class="row align-items-center">
  
		<div class="col-lg-6 my-4">
		
			<img src="img/add.jpg" alt="">
		</div> <!-- END col-md-6-->
		<div class="col-lg-5 ml-auto mt-5 mb-4">
			<h2 class="u-mb-30">
				 Everyone can advertise
			</h2>
			<p>
				Everyone has friends, he/she can help to promote merchant’s products in his/her social networks, and earn income by doing so.
			</p>
			<div class="text-center">
						<a href="login.php" class="btn btn btn-rounded btn-primary m-2 px-md-5 py-3">
        	 GET START 
        </a>
					</div>
		</div> <!-- END col-md-6-->
   </div> <!-- END 	row-->

   <!-- END 	row-->

    <!-- END 	row-->
  </div> <!-- END container-->
</section>

<section class="text-center px-3 py-5">
	 <h1 class="m-0">Top<span class="text-primary">Up</span></h1>
   <div class="u-h-4 u-w-50 bg-primary rounded mt-4 mx-auto u-mb-40"></div>
</section>
	
	
<section>
  <div class="container">
    <div class="row text-center">
     
    <!-- END col-lg-4 col-md-6-->
      
      <!-- END col-lg-4 col-md-6-->
      
      <div class="col-lg-4 col-md-6 u-mt-30">
        <div class="bg-white px-4 py-5 px-md-5 u-h-100p rounded box-shadow-v1">
         
          <h4 class="u-fs-26 u-pt-30 u-pb-20">
          <a href="login.php">Top Up Wechat</a>
          </h4>
          <p>
            You can top up your Wechat with someone who wish to top up for you. The top up and withdrawal fee is free of charge. The fee is 0.5% for maker of advertisement and 0.5% for taker of advertisement. 
          </p>
        </div>
      </div> <!-- END col-lg-4 col-md-6-->

	  <div class="col-lg-4 col-md-6 u-mt-30">
        <div class="bg-white px-4 py-5 px-md-5 u-h-100p rounded box-shadow-v1">
         
          <h4 class="u-fs-26 u-pt-30 u-pb-20">
          <a href="login.php">Top Up Alipay</a>
          </h4>
          <p>
            You can top up your Alipay with someone who wish to top up for you. The top up and withdrawal fee is free of charge. The fee is 0.5% for maker of advertisement and 0.5% for taker of advertisement. 
          </p>
        </div>
      </div> <!-- END col-lg-4 col-md-6-->
	  
	  <div class="col-lg-4 col-md-6 u-mt-30">
        <div class="bg-white px-4 py-5 px-md-5 u-h-100p rounded box-shadow-v1">
         
          <h4 class="u-fs-26 u-pt-30 u-pb-20">
          <a href="login.php">Top Up KooPay</a>
          </h4>
          <p>
            You can top up your KooPay with someone who wish to top up for you. The top up and withdrawal fee is free of charge. The fee is 0.5% for maker of advertisement and 0.5% for taker of advertisement. 
          </p>
        </div>
      </div> <!-- END col-lg-4 col-md-6-->
      
    </div> <!-- END row-->
  </div> <!-- END container-->
</section>



	<section class="text-center px-3 py-5">
	 <h1 class="m-0">Payment</h1>
	 <h3><span class="text-primary">Make payment online directly to following merchants</span></h3>
   <div class="u-h-4 u-w-50 bg-primary rounded mt-4 mx-auto u-mb-40"></div>
</section>


<section class="u-py-100 u-flex-center" style="  background-image: -webkit-linear-gradient(90deg, #113a82 0%, #157af9 100%);
    background-image: -o-linear-gradient(90deg, #113a82 0%, #157af9 100%);
    background-image: linear-gradient(0deg, #113a82 0%, #157af9 100%);
    background-image: url(img/circles.png), -webkit-linear-gradient(90deg, #113a82 0%, #157af9 100%);
    background-image: url(img/circles.png), -o-linear-gradient(90deg, #113a82 0%, #157af9 100%);
    background-image: url(img/circles.png), linear-gradient(0deg, #113a82 0%, #157af9 100%);
    -webkit-background-size: cover;
    background-size: cover;
">
  <div class="container">
    <div class="row text-center">
     
      <div class="col-lg-3 col-md-4 u-mt-30">
        <div class="bg-white px-4 py-5 px-md-5 u-h-100p rounded box-shadow-v1">
			<img src="img/astro.png" style="width:100%">
        </div>
      </div> <!-- END col-lg-4 col-md-6-->
      
      <div class="col-lg-3 col-md-4 u-mt-30">
        <div class="bg-white px-4 py-5 px-md-5 u-h-100p rounded box-shadow-v1">
          <img src="img/celcom.png" style="width:100%">
        </div>
      </div> <!-- END col-lg-4 col-md-6-->
      
      <div class="col-lg-3 col-md-4 u-mt-30">
        <div class="bg-white px-4 py-5 px-md-5 u-h-100p rounded box-shadow-v1">
         <img src="img/grab.png" style="width:100%">
        </div>
      </div> <!-- END col-lg-4 col-md-6-->
      
	  <div class="col-lg-3 col-md-4 u-mt-30">
        <div class="bg-white px-4 py-5 px-md-5 u-h-100p rounded box-shadow-v1">
         <img src="img/maxis.png" style="width:100%">
        </div>
      </div> <!-- END col-lg-4 col-md-6-->
      
	  <div class="col-lg-3 col-md-4 u-mt-30">
        <div class="bg-white px-4 py-5 px-md-5 u-h-100p rounded box-shadow-v1">
         <img src="img/syabas.png" style="width:100%">
        </div>
      </div> <!-- END col-lg-4 col-md-6-->
	  
	  <div class="col-lg-3 col-md-4 u-mt-30">
        <div class="bg-white px-4 py-5 px-md-5 u-h-100p rounded box-shadow-v1">
         <img src="img/tenaga.png" style="width:100%">
        </div>
      </div> <!-- END col-lg-4 col-md-6-->
	  
    </div> <!-- END row-->
  </div> <!-- END container-->
</section>

<footer>
	<section class="">
		<div class="container">
		<div class="row">
			<div class="col-lg-6 mb-5 mb-lg-0">
				<h2>Koo Exchange</h2>
				<p class="u-my-40">
					Safe, Easy, Immediate Payment and Earn Income Everyday.
					Build a cashless society, Give us a Safe and Integrity Society,
					( Your money is guaranteed by Certified Lawyer of Malaysia )
				</p>
			</div>
			<div class="col-lg-6 ml-auto mb-5 mb-lg-0">
				<h4>Contact Info</h4>
				<div class="u-h-4 u-w-50 bg-primary rounded mt-3 u-mb-40"></div>
				<ul class="list-unstyled">
					<li class="mb-2">
						<span class="icon icon-Phone2 text-primary mr-2"></span><a href="tel:+607-6626205">+607-6626205</a>, <a href="tel:+012-3115670">+012-3115670</a>
					</li>
					<li class="mb-2">
						<span class="icon icon-Mail text-primary mr-2"></span> <a href="mailto:info@koopay.com">info@koopay.com</a>
					</li>
					<li class="mb-2">
						<span class="icon icon-Pointer text-primary mr-2"></span>Kemajuaan ladang Cermerlang Sdn. Bhd. 
          1400, Jalan Lagenda 50, Taman Lagenda Putra
          Kulai, Johor, 81000, Malaysia

					</li>
				</ul>
			</div>
		</div> <!-- END row-->
	</div> <!-- END container-->
	</section> <!-- END section-->
	
	
	<section class="u-py-40">
		<div class="container">				
			<p class="mb-0 text-center"> 
				© Copyright 2017  -  Created by <a class="text-primary" href="#" target="_blank">KooExchange</a>
			</p>
		</div>
	</section>
</footer>
     
	          
		<div class="scroll-top bg-white box-shadow-v1">
			<i class="fa fa-angle-up" aria-hidden="true"></i>
		</div> 
		

<!--
// ////////////////////////////////////////////////
// To Reduce server request and improved page speed drastically all third-party plugin bundle in assets/js/bundle.js
// If you wanna add those manually bellow is the sequence 
// ///////////////////////////////////////////////
-->
<!--
		<script src="assets/vendor/jquery/dist/jquery.min.js"></script>
		<script src="assets/vendor/popper.js/dist/popper.min.js"></script>
		<script src="assets/vendor/bootstrap/dist/js/bootstrap.min.js"></script>
		<script src="assets/vendor/slick-carousel/slick/slick.min.js"></script>
		<script src="assets/vendor/fancybox/dist/jquery.fancybox.min.js"></script>
		<script src="assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
		<script src="assets/vendor/isotope/dist/isotope.pkgd.min.js"></script>
		<script src="assets/vendor/parallax.js/parallax.min.js"></script>
		<script src="assets/vendor/wow/dist/wow.min.js"></script>
		<script src="assets/vendor/vide/dist/jquery.vide.min.js"></script>
		<script src="assets/vendor/typed.js/lib/typed.min.js"></script>
		<script src="assets/vendor/appear-master/dist/appear.min.js"></script>
		<script src="assets/vendor/jquery.countdown/dist/jquery.countdown.min.js"></script>
		<script src="assets/js/smoothscroll.js"></script>
-->
	
		<script src="assets/js/bundle.js"></script>
		<script src="assets/js/fury.js"></script>
		
  </body>	
</html>
