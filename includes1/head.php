<!-- <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> -->

<!--<link rel="stylesheet" href="/Dashboard_files/pace.css">-->

<script src="/Dashboard_files/pace.min.js.download" defer></script>



<meta http-equiv="X-UA-Compatible" content="IE=edge">

<meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">

<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
<meta property="og:title" content="KooFamilies - Discover & Book the best restaurants at the best price">
<meta property="og:description" content="KooFamilies - Discover & Book the best restaurants at the best price">
<meta property="og:image" content="http://euro-travel-example.com/thumbnail.jpg">
<meta property="og:url" content="https://www.koofamilies.com/index.php">

<title>koofamilies</title>

<!-- CSS -->

<link rel="stylesheet" href="./css/font-awesome.min.css">  

<link href="/Dashboard_files/sweetalert2.css" rel="stylesheet" type="text/css">

<link href="/Dashboard_files/magnific-popup.min.css" rel="stylesheet" type="text/css">

<link href="/Dashboard_files/mediaelementplayer.min.css" rel="stylesheet" type="text/css">

<link href="/Dashboard_files/perfect-scrollbar.min.css" rel="stylesheet" type="text/css">



<!-- <link href="/Dashboard_files/font-awesome.min.css" rel="stylesheet" type="text/css"> -->




<link href="/Dashboard_files/slick.min.css" rel="stylesheet" type="text/css">

<link href="/Dashboard_files/slick-theme.min.css" rel="stylesheet" type="text/css">

<link href="./Dashboard_files/style.css" rel="stylesheet" type="text/css">

<link href="./css/jquery-ui.min.css" rel="stylesheet" type="text/css">

<link href="./css/custom.css" rel="stylesheet" type="text/css">



<link href="new/css/style.css" rel="stylesheet" type="text/css"> 

<link href="./css/Montserrat.css" rel="stylesheet"> 

<link href="new/css/responsive.css" rel="stylesheet" type="text/css"> 
  
<link rel="manifest" id="my-manifest-placeholder">

  <!-- Facebook Pixel Code -->
<script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', '229277018358702');
  fbq('track', 'PageView');
</script>
<!-- End Facebook Pixel Code -->

<?php

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>

<style type="text/css">.jqstooltip { position: absolute;left: 0px;top: 0px;visibility: hidden;background: rgb(0, 0, 0) transparent;background-color: rgba(0,0,0,0.6);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000);-ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000)";color: white;font: 10px arial, san serif;text-align: left;white-space: nowrap;padding: 5px;border: 1px solid white;z-index: 10000;}.jqsfield { color: white;font: 10px arial, san serif;text-align: left;}

<!-- Edited by Sumit  -->

@media (min-width:768px) and (max-width:1150px){

.nav-link {

    display: block;

    padding: 0px;

}

 .dropdown-toggle {

    font-size: 13px !important;

}

 .dropdown-toggle img {

    width: 25px !important;



}

.user_name 

{

	color:red !important;

}



	.tele_num {

	width: 100% !important;

}

select {

	width: 100%!important;

}

.oth_pr {

    width: 100%!important;

}

.globalClass_ET{

	display:none;

}

a:link {

    -webkit-tap-highlight-color: transparent!important;

}

}

@media (min-width:0px) and (max-width:867px){

.playstore{

	max-width:100px;

}

.nav-link {

    display: block;

    padding: 0px;

}

 .dropdown-toggle {

    font-size: 13px !important;

}

 .dropdown-toggle img {

    width: 25px !important;



}



	.tele_num {

	width: 100% !important;

}

select {

	width: 100%!important;

}

.oth_pr {

    width: 100%!important;

}

.globalClass_ET{

	display:none;

}

a:link {

    -webkit-tap-highlight-color: transparent!important;

}

}

.oth_pr {

    width: 100px!important;

}

.spancls

		{

			font-weight:bold;

			color:red;

		}  

		.ui-autocomplete {

		z-index: 5000;

		}

<!-- Edited by Sumit  -->

</style>
<script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>
<script>
  var session_login="<?php echo $_SESSION['login']; ?>";
  var onesignal_player_id="<?php echo $_SESSION['onesignal_player_id']; ?>";
  
  var OneSignal = window.OneSignal || [];
  OneSignal.push(function() {
    OneSignal.init({
      appId: "57f21ad6-a531-4cb6-9ecd-08fe4dd3b4f5",
    });
	if(session_login && onesignal_player_id=='')
	{
		 OneSignal.getUserId().then(function(userId) {
		console.log("OneSignal User ID:", userId);
		  $.ajax({
				url: 'functions.php',
				type:'POST',
				dataType : 'json',
				data:{user_id:session_login,onesignal_player_id:userId,method:"onesingalidsave"},
				success: function (res) {
					console.log('Update Onesign');
			}
			});
		// (Output) OneSignal User ID: 270a35cd-4dda-4b3f-b04e-41d7463a2316    
	  });
	}
  });
</script>
