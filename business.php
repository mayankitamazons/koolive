<?php

include("config.php");
$me="partner_list";
include_once('php/Section.php');

// include_once('php/SectionTable.php');

// Start of Hire's work

// Load merchant's product with QR

$p_status='';

if(!empty($_GET['status'])){

	$p_status=$_GET['status'];

}

$mobile_otp_verify="n"; 
$custom_msg="n";
$bank_data = $profile_data;
$check_number=$bank_data['mobile_number'];
$balance_inr=$user_koo_coin=$bank_data['balance_inr'];
$check_number=str_replace("60","",$check_number);
$nature_array = array(

        "Foods and Beverage, such as restaurants, healthy foods, franchise, etc",

        "Motor Vehicle, such as car wash, repair, towing, etc",

        "Hardware, such as household, building, renovation to end users",

        "Grocery Shop such as bread, fish, etc retails shops",

        "Clothes such as T-shirt, Pants, Bra, socks,etc",

        "Business to Business (B2B) including all kinds of businesses"

    );
$nature_image = array(

        "foods.jpg",

        "car.jpg",

        "household.jpg",

        "grocery.jpg",

        "clothes.jpg",

        "b2b.jpg"

    );

?>



<!DOCTYPE html>

<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">
<head><meta http-equiv="Content-Type" content="text/html; charset=windows-1252">

    <?php include("includes1/headwithmainfest.php"); ?>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.6/dist/jquery.fancybox.min.css">

	<script src="https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.6/dist/jquery.fancybox.min.js"></script>  

  <style>

   .fancybox-image

	{

		width:60%;

		height:60%;

		left:22%;

		top:18%;

	}   

  .fancybox_place_order{

    position: absolute;

    right: 0;

    left: 0;

    top: 10px;

    margin: auto;

    background: red;

    border-radius: 10px;

    width: 10em;

    display: grid;

    z-index: 99998;

    align-content: center;

    cursor: pointer;

  }

  .fancybox-slide .buttons{

    position: relative;

    /* background: red; */

    width: 20%;

    height: 20vh;

    position: absolute;

    top: -20vh;

    bottom: 0;

    margin: auto;

    z-index: 99998;

    cursor: pointer;

  }

  .fancybox-slide .buttons:after,

  .fancybox-slide .buttons:before{

    content: '';

    position: absolute;

    width: 20px;

    height: 3px;

    background: white;

    top: 0;

    bottom: 0;

    right: 0;

    left: 0;

    border-radius: 2px;

    margin: auto;

  }

  .fancybox-slide .buttons:after{

    transform: rotateZ(45deg);

    top: -12px;

  }

  .fancybox-slide .buttons:before{

    transform: rotateZ(-45deg);

    bottom: -12px;

  }

  .fancybox-slide .buttons.button-r{

    right: 2.5%;

  }

  .fancybox-slide .buttons.button-l{

    transform: rotateZ(180deg);

    left: 2.5%;

  }

    body.noscroll{

      overflow: hidden !important;

      position: fixed;

    }

    .other_products {

    display: flex;

}

    .create_date

    {

      float: right;

    }

    .comment_box {

    border: 1px solid #ccc;

    padding: 15px;

    border-radius: 5px;

    margin-bottom: 15px;

    margin-top: 15px;

    box-shadow: 0 0 5px 0px;

  }

    .submit_button

    {

      width:25% !important;

    }

    .comment{

      width:90%;

    }

  .well

  {

  

    min-height: 20px;

    background-color: #fff;

    border: 1px solid #e3e3e3;

    border-radius: 4px;

    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);

    box-shadow: inset 0 1px 1px rgba(0,0,0,.05);

  }

  .well {

    width: 100% !important;

    min-height: 20px;

    background-color: transparent!important;

    border: 0px solid #e3e3e3!important;

    border-radius: 4px;

    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);

    box-shadow: inset 0 1px 1px rgba(0,0,0,.05);

}

  .well form{

      min-height: 280px;

  }

  .pro_name

  {

   text-align: center;

    font-size: 22px;

    font-weight: 600;

    margin: 10px 0px;

    height: 60px;

    }

    .about_mer {

    width: 100%;

}

 .input-controls {

      margin-top: 10px;

      border: 1px solid transparent;

      border-radius: 2px 0 0 2px;

      box-sizing: border-box;

      -moz-box-sizing: border-box;

      height: 32px;

      outline: none;

      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);

    }

    #searchInput {

      background-color: #fff;

      font-family: Roboto;

      font-size: 15px;

      font-weight: 300;

      margin-left: 12px;

      padding: 0 11px 0 13px;

      text-overflow: ellipsis;

      width: 50%;

    }

    #searchInput:focus {

      border-color: #4d90fe;

    }

    input.quatity {

    width: 90px;

}

.common_quant {

    display: flex;

}

p.quantity {

    margin-top: 7px;

}

.order_product{

    margin-top: 15px;

    margin-left: 10px;

    font-size: 20px;

    padding-left: 10px;

    padding-right: 10px;

    margin-bottom: 10px;

}

.comm_prd{

    border: 1px #000000 solid;

    padding-left: 15px;

    margin-bottom: 10px;

}

.mBt10{

    margin-bottom: 10px;

}

@media only screen and (max-width: 767px) and (min-width: 300px)  {

    .new_grid{

      grid-template-columns: 1fr 1fr !important;

    }

    .text_add_cart {

        background: #003A66;

        width: 109px;

        text-align: center;

        padding: 10px;

        color: #fff;

        text-transform: uppercase;

        font-weight: 600;

        cursor: pointer;

        /* margin-right: 8px; */

        border-radius: 8px;

        margin-left: -10px;

    }

   .master_category_filter{

        font-size: 1.2rem;

        line-height: 0.8rem;

        margin-bottom: 5px !important;

        padding: 0.5rem 0.5rem;

    }

    .category_filter{

        font-size: 1.2rem;

        line-height:0.8rem;

        margin-bottom: 5px !important;

        padding: 0.4rem 0.9em;

    }

    .order_product{

        margin-top: 25px;

        margin-bottom: 15px;

        font-size: 18px;

        padding-left: 5px;

        padding-right: 5px;

    }

    .oth_pr{

        margin-top: 20px !important;

    }

}

.nature_image {

   width: 40px;

   height: 40px;

}

@media only screen and (max-width: 600px) and (min-width: 300px)  {

  .sidebar-expand .main-wrapper {

        margin-left: 0px!important;

    }

    .oth_pr{

        margin-top: 26px!important;

    padding: 6px!important;

    }

}

@media only screen and (max-width: 500px) and (min-width: 400px)  {

     .well{

        padding-top: 0px !important;

     }

     .pro_name {

         font-size: 18px;

         margin: 10px 0px 0px;

         height: 35px;

     }

     .set_calss.input-has-value {

        width: 180px;

     }

     

}

@media only screen and (max-width: 600px) and (min-width: 300px)  {

  .new_grid{

    grid-template-columns: 1fr 1fr !important;

  }

     .well{

        padding-top: 0px !important;

     }

h4.head_oth {

    font-size: 20px;

}

     .pro_name {

        text-align: center;

        font-size: 14px;

        overflow: hidden;

        /* white-space: nowrap; */

        height: auto;

        /* width: 100px; */

        line-height: 15px;

     }

     .text_add_cart {

         margin: 5px 0px;

         padding: 7px;

     }

     .common_quant {

        display: block;

     }

     .text_add_cart {

         background: #003A66;

         width: 109px;

         text-align: center;

         padding: 10px;

         color: #fff;

         text-transform: uppercase;

         font-weight: 600;

         cursor: pointer;

         /* margin-right: 8px; */

         border-radius: 8px;

         margin-left: -10px;

     }

     .mBt10{

         margin-bottom: 2px;

     }

     .nature_image {

       width: 25px;

       height: 25px;

    }

    .starting-bracket{

        margin-top: 0.8rem;

    }

}

@media only screen and (max-width: 600px) and (min-width: 300px)  {

   .sidebar-expand .main-wrapper {

        margin-left: 0px!important;

    }

   .text_add_cart {

        padding: 6px;

   }

   .row#main-content {

        margin-right: 0px;

        margin-left: 0px;

    }

    .oth_pr{

  height: 40px;

  }

}

@media only screen and (max-width: 1050px) and (min-width: 992px)  {

   .text_add_cart{width: 100px}

   .text_add_cart {

       width: 125px;

       margin: 0 auto;

   }

   p.quantity {

        margin-left: 35px;

   }

   .common_quant {

        display: block;

   }

   input.quatity {

        width: 130px;

   }

}

@media only screen and (max-width: 750px) and (min-width: 600px)  {

   .set_calss.input-has-value {

        width: 173px;

   }

   .about_uss {

        width: 165px;

   }

   .sidebar-expand .main-wrapper {

        margin-left: 0px;

   }

   .pro_name{

       margin-bottom: 0.4em;

       font-size: 18px;

       overflow: hidden;

       white-space: nowrap;

   }

   p {

        margin-bottom: 0.4em;

   }

}

@media only screen and (max-width: 500px) and (min-width: 300px)  {

   input.btn.btn-block.btn-primary.submit_button {

        width: 100%!important;

   }

   p.test_testing {

        margin: 2px;

   }

   .text_add_cart {

        margin: 5px auto;

   }

   input.quatity {

        width: 118px;

   }

   .well {

        min-height: 20px;

        padding: 0px 0 0;

   }

   .common_quant {

        display: block;

   }

   .set_calss.input-has-value {

        width: 160px;

   }

   .grid.row {

        margin-left: 18px;

   }

   p {

        margin-bottom: 0;

   }

}

@media only screen and (max-width: 800px) and (min-width: 750px)  {

   .sidebar-expand .main-wrapper {

        margin-left: 0px;

   }

   .pro_name{

       margin-bottom: 0.4em;

       font-size: 18px;

       overflow: hidden;

       white-space: nowrap;

   }

   .common_quant {

        display: block;

   }

   p {

        margin-bottom: 0.4em;

   }

}

@media only screen and (max-width: 800px) and (min-width: 650px)  {

   .common_quant {

        display: block;

   }

   .text_add_cart {

        width: 142px;

   }

}

/* Edited by Sumit */

@media (min-width:768px) and (max-width:1150px){

  .total_rat_abt {

      font-size: 14px!important;

      display: flex;

  }

  .well {

      min-height: 20px;

      background-color: transparent!important;

      border: 0px solid #e3e3e3!important;

      border-radius: 4px;

      -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);

      box-shadow: inset 0 1px 1px rgba(0,0,0,.05);

  }

  label {

      font-weight: 600;

      width: 100%;

  }

  .fjhj br {

      display: none;

  }

  .master_category_filter{

      background-color: #545c73;

      border-color: #4a5368;

      -webkit-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.15), 0 1px 1px rgba(0, 0, 0, 0.075);

      box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.15), 0 1px 1px rgba(0, 0, 0, 0.075);

  }

  .master_category_filter:focus, .master_category_filter.focus {

      -webkit-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.15), 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 0 3px rgba(74, 83, 104, 0.5);

      box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.15), 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 0 3px rgba(74, 83, 104, 0.5);

  }

  .master_category_filter:hover {

      color: #fff;

      background-color: #4a5368;

      border-color: #545c73;

  }

}

@media (min-width:200px) and (max-width:767px){

  .total_rat_abt {

      font-size: 14px!important;

      display: flex;

  }

  .well {

      min-height: 20px;

      background-color: transparent!important;

      border: 0px solid #e3e3e3!important;

      border-radius: 4px;

      -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);

      box-shadow: inset 0 1px 1px rgba(0,0,0,.05);

  }

  .fjhj br {

      display: none;

  }

}

.fjhj br {

    display: none;

}

label {

    font-weight: 600;

    width: 100%;

}





  </style>

 



<!--script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAEr0LmMAPOTZ-oxiy9PoDRi3YWdDE_vlI&libraries=places" async defer></script!--> 

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB4BfDrt-mCQCC1pzrGUAjW_2PRrGNKh_U&libraries=places" async defer></script>  

<style type="text/css">

.active_menu

{

	background:#d6dadf !important;

	

}

#pop_cart

{

	font-size:.8em;

}

.modal 

{

	width:93%;

}

 @media only screen and (max-width:400px) {

	 .navbar-nav > li

	 {

	 }

 }

@import url( 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );

.adminDemoVideo {

  position: relative;

  display: inline-block;

}

 @media only screen and (min-width: 700px) {

  .adminDemoVideo::before {

    content: '\f00e';

    z-index: 5;

    position: absolute;

    left: 9%;

    top: 89%;

    transform: translate( -50%, -50% );

    padding: 3px 15px 3px 25px;

    color: white;

    font-family: 'FontAwesome';

    font-size: 30px !important;

    background-color: rgba(23, 35, 34, 0.75);

    border-radius: 5px 5px 5px 5px;

}  

}

@media only screen and (max-width: 700px) {

  .adminDemoVideo::before {

    content: '\f00e';

    z-index: 5;

    position: absolute;

    left:9%;

    top:89%;

	

    transform: translate( -50%, -50% );

    padding: 3px 5px 3px 5px;

    color: white;

    font-family: 'FontAwesome';

    font-size:12px !important;

    background-color: rgba(23, 35, 34, 0.75);

    border-radius: 5px 5px 5px 5px;

}  

}

		 #partitioned {

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

}



#divInner{

  left: 0;

  position: sticky;

}



#divOuter{

  width:190px; 

  overflow:hidden

}



</style> 

<style>

.restaurant-entry

{

	border-bottom: 1px solid #eaebeb;

	border-radius: 2px;

	margin-bottom: 20px;

	background-color: white !important;

}

.entry-logo

{

	float: left;

	width: 110px;

	height: auto;

	border: 1px solid #eaebeb;

	border-radius: 2px;

	margin-top: 15px;

	margin-left: 15px;

	overflow: hidden;

}



.entry-dscr

{

	padding-left: 145px;

	margin-top: 15px;

}

.entry-dscr a

{

	color: #414551;

	font-weight: 500;

	font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, &quot;Helvetica Neue&quot;, Arial, sans-serif;

}

.theme-btn-dash

{

	border: 2px dashed #51d2b7;

	background-color: transparent;

	color:#f30;

}	

</style> 

</head>



<body  class="header-light sidebar-dark sidebar-expand pace-done">

    <?php

	  $filter="distance";

	   if(isset($_POST['sort_by'])){

		   $filter=$_POST['sort_by'];

     }
     
     //echo $filter;

	?>





    <div id="wrapper" class="wrapper">

        <!-- HEADER & TOP NAVIGATION -->

        <?php include("includes1/navbar.php"); ?>

        <!-- /.navbar -->

        <div class="content-wrapper">

            <!-- SIDEBAR -->

            <?php include("includes1/sidebar.php"); ?>

            <!-- /.site-sidebar -->

				<main class="main-wrapper clearfix" style="min-height: 522px;">

                    <div class="row" id="main-content">

      

					

				  <!-- <div class="clear"></div>

				  <a style="text-align:center;width:100%;margin-top:2%;" href="https://play.google.com/store/apps/details?id=com.koobigfamilies.app" target="blank">

					<img style="max-width:140px;" src="google.png" alt=""></a>

				  <div class="clear"></div> -->

                    

                    </div>

                     

                    <div class="col-md-12 row" style="margin-left:15px; margin-bottom: 10px; padding-left:0px;" >

                       <!-- <h2 class="favorite_name" style="display: inline-block;font-size:18px;">

                         Partner who accept <?php echo $product['special_coin_name']; ?>

					</h2> -->

					<div class="col-md-2">

					<div class="form-group">

        							<label> Sort By  </label>

        				 <form method="post" id="sorting">

							 

							 <select id="sort_by" class="form-control" name="sort_by"> 
               
               <option <?php if($_POST['sort_by']=="distance"){ echo "selected='selected'";} ?>value="distance">Distance</option>

               <option  <?php if($_POST['sort_by']=="name"){ echo "selected='selected'";} ?> value="name">Name (A-Z)</option>

							 </select>

               <input name="latitude" id="userLatitude" value="" type="hidden" />
               <input name="longitude" id="userlongitude" value="" type="hidden" />

							 </span>

							 </form>

        				</div>

					

					 </div>

					    

						 <div class="col-md-4">

						     <form method="post">

							<div class="form-group">

										<label> Search By  Company Name</label>

										<input class="form-control" value="<?php if(isset($_POST['tags_merchant'])){ echo $_POST['tags_merchant'];} ?>"  name="tags_merchant" id="tags_merchant" placeholder="Search By merchant's name"/>

										

							</div>

						 </div>

						  <div class="col-md-4">

							<div class="form-group">

								<label></label>

								<input class="form-control btn btn-primary" type="submit" value="SEARCH" style="max-width: 85px;margin-top:1%;"/>

								<input class="form-control btn btn-primary" id="resetButton" type="button" value="RESET" style="max-width: 85px;margin-top:1%;"/>

										

							</div>

						 </div>

						</form>

				   </div>

				   <?php

				    $cond='';
					$self_id=$_SESSION['login'];
				   if(isset($_POST['tags_merchant']))

				   {

					   $tags_merchant=$_POST['tags_merchant'];

					   $cond=" and name='$tags_merchant' ";

				   }

  if($filter=="name"){

    $puery="SELECT users.*,about.image,users.id FROM users left join about on about.userid=users.id where users.user_roles=2 
    and users.service_id!=0 and users.isLocked=0 and users.name!='' and users.id NOT IN(5401,6262,5476)" . $cond ." ORDER BY name asc";

   // $puery="select users.*,service.short_name,about.image from users

   // left join about on about.userid=users.id

   // left join service on service.id=users.service_id

    // where  users.user_roles=2 and users.service_id!=0 and users.isLocked=0 and users.name!='' and users.id NOT IN(5401,6262) order by users.name asc";

  } else {

    if($cond != ''){
      $puery="SELECT users.*,about.image,users.id FROM users left join about on about.userid=users.id where users.user_roles=2 
    and users.service_id!=0 and users.isLocked=0 and users.name!='' and users.id NOT IN(5401,6262,5476)" . $cond ." ORDER BY name asc";  
    }else{
      $puery=" SELECT users.*,about.image,users.id, 
      (6371 * ACOS ( COS ( RADIANS (".$_POST['latitude'].")) * COS ( RADIANS(users.latitude)) * COS(RADIANS(users.longitude) - RADIANS(".$_POST['longitude']."))
       + SIN(RADIANS(".$_POST['latitude'].")) * SIN(RADIANS(users.latitude)))) AS distance
    FROM users
    left join about on about.userid=users.id
    where  users.user_roles=2 and users.service_id!=0 and users.isLocked=0 and users.latitude!='' and users.longitude!='' and users.name!='' and users.id NOT IN(5401,6262,5476) ORDER BY distance asc";
    }

  }

//   $puery=" SELECT

//   users.*,about.image,users.id, (

//     6371 * acos (

//       cos ( radians(78.3232) )

//       * cos( radians( users.latitude ) )

//       * cos( radians( users.longitude ) - radians(65.3234) )

//       + sin ( radians(78.3232) )

//       * sin( radians( users.latitude ) )

//     )

//   ) AS distance

// FROM users

// left join about on about.userid=users.id

// where  users.user_roles=2 and users.service_id!=0 and users.isLocked=0 and users.name!='' and users.id NOT IN(5401,6262,5476) $cond



// $order_by

// LIMIT 0 , 100;";

//echo $puery;

   $u_query = mysqli_query($conn,$puery);

   $ppartner=mysqli_num_rows($u_query);

   

   ?>
                <div id="orderdata">
				 <h3>Fetching Data...</h3>
				</div>
					
                   

                   

        </div>

        

       

        

        

        

        </div>

        </main>

        </div>

     

       

        
<div class=" modal fade" id="AlerModel" role="dialog" style="width:80%;min-height: 200px;text-align: center;margin:8%;">
        <div class="element-item modal-dialog modal-dialog-centered" style="position: absolute;top: 0;bottom: 0;left: 0;right: 0;display: grid;align-content: center;">
            <!-- Modal content-->
            <div class="element-item modal-content">
                <div class="element-item modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                            
                    
                              </div>   
                                    <p id="show_msg" style="font-size:22px;font-weight:bold;"><?php echo $language['cancel']; ?></p>
                    
                                </div>
                            </div>
    </div>
 <div class="modal fade" id="LimitModel" role="dialog">
                                    <div class="modal-dialog">
                                    
                                       <!-- Modal content-->
                                       <div class="modal-content">
                                       <div class="modal-header">
                                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                                          <h4 class="modal-title">Set Limit</h4>
                                       </div>
                                       <div class="modal-body">
                                       <label for="name">Max Limit</label>
									               <input type="text" required class="form-control" maxlength="8" id="coin_max_limit" name="coin_max_limit" placeholder="Coin Max Value">
                                       </div>
                                       
                                       <label for="name">Limit Class </label>
                                          <input type="hidden" id="merchant_id" />  
                                          <input type="hidden" id="user_id" value="<?php echo $_SESSION['login']; ?>"/>      
                                          <select class="form-control" name="limit_class" id="limit_class">
                                             <?php  foreach($limit_class as $k=>$v){?>
                                             <option value="<?php echo $k?>" coin_limit="<?php echo $v;?>"><?php echo $k."-".$v." Coin" ?></option>
                                             <?php } ?>
                                             
                                          </select> 

                                       <div class="modal-footer">
                                          <button type="button" id="save"  class="btn btn-default save_limit" data-dismiss="modal">Add</button>
                                       </div>
                                       </div>
                                       
                                    </div>
                                 </div>
<!-- /.widget-body badge -->

</div>

    <!-- /.widget-bg -->



    <!-- /.content-wrapper -->



    <?php include("includes1/commonfooter.php"); ?>

 



<script>

 

// init Isotope

var $grid_sub = $('.sub_category_grid').isotope({

    // options

    layoutMode: 'fitRows'

});

var $grid = $('.grid').isotope({

  // options

});



// filter items on button click

$('.master_category_filter').on( 'click', function(e) {

    e.preventDefault();

	$('.master_category_filter').removeClass("active_menu");

    $(this).addClass("active_menu");

    var filterValue = $(this).attr('data-filter');

    $grid_sub.on( 'arrangeComplete', function ( event, filteredItems) {

        console.log(event, filteredItems);

        $(filteredItems[0].element).find('button').trigger('click');

        console.log('am called');

    });

    $grid_sub.isotope({ filter: filterValue });

 

        var filterValue = $(this).attr('data-filter');

        var position_value = $(this).attr('data-position');

    

  

});

$('.sub_category_grid .category_filter button').on( 'click',function() {

      var filterValue = $(this).attr('data-filter');

      var subcateg_show = $(this).data("subcategory");

	   $('.sub_category_grid .category_filter button').removeClass("active_menu");

		$(this).addClass("active_menu");

      console.log(filterValue);

      console.log(subcateg_show);

      $("#remarks_area .modal-body .btn-group").each(function(){

        if($(this).data("subcategory") == subcateg_show || $(this).data("subcategory") == "all"){

          $(this).show();

        }else{

          $(this).hide();

        }

      });

      $grid.isotope({ filter: filterValue });

});

</script>

<style>

.sub_category_grid button{ /* You Can Name it what you want*/

margin-right:10px;

}

.sub_category_grid button:last-child{

margin-right:0px;

/*so the last one dont push the div thas giving the space only between the inputs*/

}

img.active {

  animation: make_bigger 1s ease;

  width: 600px;

  height: 400px;

}

img.non_active {

  animation: make_smaller 1s ease;

  width: 127px;

  height: 128px;

}

@media only screen and (max-width: 750px) and (min-width: 600px)  {

form.set_calss.input-has-value {

<!--

    width: 50%;

-->

    width: 173px;

}

.about_uss {

    width: 165px;

}

.sidebar-expand .main-wrapper {

    margin-left: 0px;

}

}

@media only screen and (max-width: 500px) and (min-width: 300px)  {

#merchant_message

{

	margin-top:20%;

}

input.btn.btn-block.btn-primary.submit_button {

    width: 100%!important;

}

.common_quant {

    display: block;

}

form.set_calss.input-has-value {

    width: 100%;

    width: 170px;

    margin-left: -20px;

}

.grid.row {

    margin-left: 18px;

}

/*.pro_name {

    height: 130px;

}*/

img.make_bigger {

    height: 100px;

}

}

@media only screen and (max-width: 800px) and (min-width: 750px)  {

.sidebar-expand .main-wrapper {

    margin-left: 0px;

}

.common_quant {

    display: block;

}

}

.col-md-4{

  max-width: 100% !important;

}

.well.col-md-4{

  padding: 0 !important;

}

</style>

<?php



if($profile_data['shortcut_icon'])

$shortcut_icon=$site_url."/images/shortcut_icon/".$profile_data['shortcut_icon'];

if($shortcut_icon=='')

  $shortcut_icon='img/logo_512x512.png';

if($profile_data['id']=='5062')

$start_url=$site_url."/structure_merchant.php?merchant_id=".$profile_data['id'];

else

$start_url=$site_url."/view_merchant.php?sid=".$profile_data['mobile_number'];

?>

	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>



<script>

var merchant_tags = [];

$(document).ready(function(){
      
  $('#resetButton').click(function(e){
    e.preventDefault();

    $('#sort_by option:first').attr('selected','selected');

    $('#sorting').submit();
  });

var gotLocation = false;

function geoFindMe() {

    function success(position) {
      const latitude  = position.coords.latitude;
      const longitude = position.coords.longitude;
      
         $('#userLatitude').val(latitude);
      $('#userlongitude').val(longitude);
      var sort_by=$('#sort_by').val();
      var tags_merchant=$('#tags_merchant').val();
   
      $.ajax({
          type: "POST",
          url: "get_business_sub.php",
          data: {latitude:latitude, longitude:longitude,sort_by:sort_by,tags_merchant:tags_merchant},
          cache: false,
          success: function(data) {
             $('#orderdata').html(data);   
          }
          });
        
      }
  

    function error() {
      // console.log('Unable to retrieve your location');
	  var sort_by=$('#sort_by').val();
      var longitude=$('#userlongitude').val();
      var latitude=$('#userLatitude').val();
      var tags_merchant=$('#tags_merchant').val();
   
      $.ajax({
          type: "POST",
          url: "get_business_sub.php",
          data: {latitude:latitude, longitude:longitude,sort_by:sort_by,tags_merchant:tags_merchant},
          cache: false,
          success: function(data) {
             $('#orderdata').html(data);   
          }
          });
    }

    if (!navigator.geolocation) {
      console.log('Geolocation is not supported by your browser');
    } else {
    console.log("Locating...");
      navigator.geolocation.getCurrentPosition(success, error);
      
    }

  }
	
	  geoFindMe();
	
	$("#sort_by").change(function() {
      // var sort_by=$('#sort_by').val();
      // var longitude=$('#userlongitude').val();
      // var latitude=$('#userLatitude').val();
      // var tags_merchant=$('#tags_merchant').val();
   
      // $.ajax({
          // type: "POST",
          // url: "get_business_sub.php",
          // data: {latitude:latitude, longitude:longitude,sort_by:sort_by,tags_merchant:tags_merchant},
          // cache: false,
          // success: function(data) {
             // $('#orderdata').html(data);   
          // }
          // });
     this.form.submit();

	});

	<?php 

				$qMerchant = mysqli_query($conn, "SELECT id,name FROM users WHERE user_roles = '2' ORDER BY name ASC");

				while($row = mysqli_fetch_assoc($qMerchant)){

					echo "merchant_tags.push('" . $row['name'] . "');\n";

					echo "console.log('" . $row['name'] . "');\n";

				}
	?>

			$( "#tags_merchant" ).autocomplete({

				source: merchant_tags

			});

		

		$("#tags_merchant").change(function(){

			

		});

	 var myDynamicManifest = {

   "gcm_sender_id": "540868316921",

   "icons": [

		{

		"src": "<?php echo $shortcut_icon; ?>",

		"type": "image/png",

		"sizes": "512x512"

	  }

	  ],

	  "short_name":'<?php echo $merchant_detail['name']; ?>',

	  "name": "One stop centre for your everything",

	  "background_color": "#4A90E2",

	  "theme_color": "#4A90E2",

	  "orientation":"any",

	  "display": "standalone",

	  "start_url":'<?php echo $start_url; ?>',

	}

	const stringManifest = JSON.stringify(myDynamicManifest);

	const blob = new Blob([stringManifest], {type: 'application/json'});

	const manifestURL = URL.createObjectURL(blob);

	document.querySelector('#my-manifest-placeholder').setAttribute('href', manifestURL);

	

    //$('.master_category_filter:first-child').trigger('click');

    $('.sub_category_grid .category_filter:first-child button').trigger('click');

     $('.master_category_filter:first-child').trigger('click');

     

  

});



</script>

