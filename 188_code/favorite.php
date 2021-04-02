<?php 
include("config.php"); 
$find_merchant_tab="y";
$me="favorite";
if(isset($_SESSION['login'])){
$user_id = $_SESSION['login'];
} else {   
$user_id = "";
}
$total_rows = mysqli_query($conn, "SELECT * FROM users WHERE user_roles='2' ORDER BY name ASC ");
$user_mobile =  isset($_SESSION['login']) ? mysqli_fetch_assoc(mysqli_query($conn, "SELECT mobile_number FROM users WHERE id='".$_SESSION['login']."'"))['mobile_number'] : '';

$error = "";
if(isset($_GET['error_type'])){
$type = $_GET['error_type'];
if($type == 2)
$error= "The merchant you are trying to find was already introduced by another member.";
if($type == 1)
$error= "The merchant's phone number is incorrect.";
}
   ?>
<!DOCTYPE html>
<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">
   <head>
      <?php include("includes1/head.php"); ?>
      <style>
         .sidebar {
         background: #eceff1;
         }
         div#app {
         margin-bottom: 20px;
         }
         .form-control {
         display: block;
         }
         table.table {
         width: 400px;
         }
         .well
         {
         min-height: 20px;
         padding: 19px;
         margin-bottom: 20px;
         background-color: #fff;
         border: 1px solid #e3e3e3;
         border-radius: 4px;
         -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
         box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
         }
         .ct_ctycode {     
         margin-bottom: 12px;  
         }
         a.dropdownlivalue {
         padding: 10px;
         }
         @media (min-width: 370px) and (max-width:380px) {
         button.btn.btn-block.btn-primary.testts.merchant_nam {
         font-size: 11px!important;
         }
         button.btn.btn-block.btn-primary.testts.tele_num {
         font-size: 11px!important;
         }
         button.btn.btn-block.btn-primary.testts.scan_code {
         font-size: 11px!important;
         }
         button.btn.btn-block.btn-primary.testts.fav_list {
         font-size: 11px!important;
         }
         button.btn.btn-block.btn-primary.testts.search_shopss {
         font-size: 11px!important;
         }
         }
         @media (min-width: 328px) and (max-width:628px) {
         .navbar-nav li a
         {
         padding: 0px;
         }
         .ripple {
         padding: 3px, 10px;
         }
         div#merchant_name {
         padding: 0;
         margin-left: 2px;
         }
         div#tele_number {
         padding: 0;
         margin-left: 2px;
         }
         div#scan_qrcode {
         padding: 0;
         margin-left: 0px;
         }
         .col-md-12.test_test {
         padding: 0;
         margin: 0;
         }
         .col-md-12.test_test {
         margin-bottom: 5px!important;
         margin: 0;
         }
         button.btn.btn-block.btn-primary.testts.merchant_nam {
         font-size: 12px;
         }
         button.btn.btn-block.btn-primary.testts.tele_num {
         font-size: 12px;
         }
         button.btn.btn-block.btn-primary.testts.scan_code {
         font-size: 12px;
         }
         button.btn.btn-block.btn-primary.testts.fav_list {
         font-size: 12px;
         }
         button.btn.btn-block.btn-primary.testts.search_shopss {
         font-size: 12px;
         }
         
         }
         .col-md-12.test_test {
         display: flex;
         }
         .col-md-3.test_qwertys {
         color: #fff;
         background-color: #fb9678;
         border-color: #fb9678;
         -webkit-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.15), 0 1px 1px rgba(0, 0, 0, 0.075);
         box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.15), 0 1px 1px rgba(0, 0, 0, 0.075); 
         margin-left:0px;
         }
         .col-md-3.test_qwertys1 {
         color: #fff;
         background-color: #fb9678;
         border-color: #fb9678;
         -webkit-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.15), 0 1px 1px rgba(0, 0, 0, 0.075);
         box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.15), 0 1px 1px rgba(0, 0, 0, 0.075); 
         margin-left: 12px;
         }
         .col-md-3.test_qwertys2 {
         color: #fff;
         background-color: #fb9678;
         border-color: #fb9678;
         -webkit-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.15), 0 1px 1px rgba(0, 0, 0, 0.075);
         box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.15), 0 1px 1px rgba(0, 0, 0, 0.075); 
         margin-left: 12px;
         }
         button.btn.btn-block.btn-primary.testts:hover {
         border-color: #f99678;
         background-color: #f99678;
         }
         .col-md-12.test_test.dollar {
    margin-bottom: 10px;
} 

 
      </style>
   </head>
   <body class="header-light sidebar-dark sidebar-expand pace-done">
      <div class="pace  pace-inactive">
         <div class="pace-progress" data-progress-text="100%" data-progress="99" style="transform: translate3d(100%, 0px, 0px);">
            <div class="pace-progress-inner"></div>
         </div>
         <div class="pace-activity"></div>
      </div>
      <div id="wrapper" class="wrapper"> 
      <!-- HEADER & TOP NAVIGATION -->
      <?php include("includes1/navbar.php"); ?>
      <!-- /.navbar -->
      <div class="content-wrapper">
         <!-- SIDEBAR -->
         <?php include("includes1/sidebar.php"); ?>
         <!-- /.site-sidebar -->
         <main class="main-wrapper clearfix" style="min-height: 522px;">
            <div class="row" id="main-content" style="">
			 <!-- <a style="text-align:center;width:100%;margin-top:2%;" href="https://play.google.com/store/apps/details?id=com.koobigfamilies.app" target="blank">
					<img style="max-width:140px;" src="google.png" alt=""></a> -->
               
				<div class="row">
				    <div class="well col-md-8" id="favourate_list">
                      
					  <h5>Search By Industry</h5>
                     <div class="form-group">
					       <input type="hidden" name="login_user_id" id="login_user_id" value="<?php echo $_SESSION['login'] ?>" />	
                        <select style="color:black;" name= "business" class="form-control business_type" user_id="<?php echo $_SESSION['login']?>" >
						  <?php 
							$sql = mysqli_query($conn, "SELECT * FROM service WHERE status=1"); 
								while($data = mysqli_fetch_array($sql))
	                           {
						  ?>
                           <option value="<?php echo $data['id']; ?>"><?php echo $data['service_name']; ?></option>
							   <?php } ?>
                           <!--option>Motor Vehicle, such as car wash, repair, towing, etc</option>
                           <option>Hardware, such as household, building, renovation to end users</option>
                           <option>Grocery Shop such as bread, fish, etc retails shops</option>
                           <option>Clothes such as T-shirt, Pants, Bra, socks,etc</option>
                           <option>Business to Business (B2B) including all kinds of businesses</option!-->
                        </select>
                     </div>
					 <h3 class="btn btn-primary" style="background:red;"> <?php echo $language['fav_list']; ?></h3>
                   
					<table class="table table table-striped kType_table favorite_table">
					
					</table>
					<button type="button" style="background:red;" class='btn btn-default nearby_restaurant_btn' user_id="<?php echo $user_id;?>"><?php echo $language['find_near_by_business']; ?></button>
					<div id="map" style="height: 400px; display:none;"></div>
					
                     <div id="map" style="height: 400px; display:none;"></div>
                     <table class="table table table-striped kType_table" id="nearby_restaurant">
                       
                     </table>
                  </div>
				</div>
				<!-- merchant name-->
                  <div class="well col-md-8 mer_nam" id="mer_name_2">
                     <h4><?php echo $language['merchant_name']; ?></h4>
                     <form action="structure_merchant.php" method="post">
                        <?php 
                           // $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT count(id) as pro_ct FROM products WHERE user_id ='".$id."' and status=0" ));
                           ?>
                        <div>
                           <input type="text"  id="txtname" autocomplete="off" name="merchant_id" class="form-control" placeholder=" Search By company name"> 
                           <br> 
                           <ul class="dropdown-menu txtname" role="menu" aria-labelledby="dropdownMenu"  id="Dropdown_name2">
                           </ul>
                        </div>
                        <!---new----->
                        <button class="btn btn-block btn-primary"> <?php echo $language['view']; ?> </button>
                     </form>
                  </div>
                  <!-- end merchant name -->
         </div>
         <!-- /.widget-body badge -->
      </div>
      <!-- /.widget-bg -->
      <!-- /.content-wrapper -->
      <?php include("includes1/footer.php"); ?>
   </body>
</html>
<style>
   select.ct_ctycode.text_name {
   width: 100%;
   }
   a:not([href]):not([tabindex]):hover {
   color: #6a6a6a;
   text-decoration: none;
   }
</style>

<script src="https://use.fontawesome.com/ff2be4c29f.js"></script>
<style>
   .qrcode-text {
   padding-right:1.7em;
   margin-right:0
   }
   .qrcode-text-btn {
   background: url(https://dab1nmslvvntp.cloudfront.net/wp-content/uploads/2017/07/1499401426qr_icon.svg) 50% 50% no-repeat;
   height: 37px;
   width: 30px;
   margin-left: -32px;
   cursor: pointer;
   z-index: 999;
   }
   .qrcode-text-btn > input[type=file] {
   position:absolute; 
   width:1px; 
   height:1px; 
   opacity:0;
   cursor: pointer;
   }
   @media only screen and (max-width: 760px) and (min-width: 360px)  {
   div#app {
   width: 290px!important;
       height: 90%;
   }
   #app {
   display: block!important;
   }
   div#main-content {
   width: 355px;
   }
   .test {
   display: block!important;
   float: left;
   }
   table.table {
   width: 280px;
   }
   .form-control {
   display: block;
   width: 260px!important;
   }
   }
</style>

<!-- adding new---->
    <link rel="icon" type="image/png" href="favicon.png">
   
   <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB4BfDrt-mCQCC1pzrGUAjW_2PRrGNKh_U&libraries=places" async defer></script> 
<style>
   div#app {
   margin-bottom:20px;
   }
   .mejs__controls {
   display: none;
   }
   #app {
   background: #263238;
   display: flex;
   align-items: stretch;
   justify-content: stretch;
   height: 85%;
   }
   .text_mobile{
   font-size: 18px;
   }
   .test {
   display: flex;
   float: left;
   }
   h4#scan_qrcode {
   cursor: pointer;
   }

   button.btn.btn-block.btn-primary.testts.scan_code {
    color: #000;
    background-color: #34caab;
    border-color: #34caab;
}
   button.btn.btn-block.btn-primary.testts.tele_num {
    color: #000;
    background-color: #34caab;
    border-color: #34caab;
}
   button.btn.btn-block.btn-primary.testts.merchant_nam {
    color: #000;
    background-color: #34caab;
    border-color: #34caab;
}
.col-md-3.test_qwertys {
    color: #000;
    background-color: #34caab;
    border-color: #34caab;
}
.col-md-3.test_qwertys1 {
    color: #000;
    background-color: #34caab;
    border-color: #34caab;
}
.col-md-3.test_qwertys2 {
    color: #000;
    background-color: #34caab;
    border-color: #34caab;
}
button.btn.btn-block.btn-primary.testts.search_shopss {
    color: #000;
    background-color: #34caab;
    border-color: #34caab;
}
button.btn.btn-block.btn-primary.testts.fav_list {
    color: #000;
    background-color: #34caab;
    border-color: #34caab;
}

.preview-container {
    width: 35%!important;
    margin: 0 auto;
    margin-top: 12px;
}
.preview-container:before {
    display: block;
    content: "";
    width: 23px;
    height: 23px;
    position: relative;
    top: 19px;
    left: -150px;
    border-top: 3px solid #fff;
    border-left: 3px solid #fff;
}
.preview-container:after {
   
   
       display: block;
    content: "";
    width: 23px;
    height: 23px;
    position: relative;
    bottom: 19px;
    right: -150px;
    border-bottom: 3px solid #fff;
    border-right: 3px solid #fff;
    
}
.mejs__mediaelement:before {
   display: block;
    float: right;
    content: "";
    width: 23px;
    height: 23px;
    position: relative;
    top: -4px;
    right: -4px;
    border-top: 3px solid #fff;
    border-right: 3px solid #fff;
}
.mejs__mediaelement:after {
   
     display: block;
    content: "";
    width: 23px;
    height: 23px;
    position: relative;
    bottom: -22px;
    left: -2px;
    border-bottom: 3px solid #fff;
    border-left: 3px solid #fff;
    
}
@media (min-width: 760px) and (max-width:800px) {
.preview-container {
    width: 53%!important;
    margin: 0 auto;
    margin-top: 12px;
}
.mejs__mediaelement:after {
    display: block;
    content: "";
    width: 23px;
    height: 23px;
    position: relative;
    bottom: -55px;
    left: -3px;
}
}
@media (min-width: 328px) and (max-width:750px) {
.preview-container:before {
    display: block;
    content: "";
    width: 23px;
    height: 23px;
    position: relative;
    top: 10px;
    left: -110px;
    border-top: 3px solid #fff;
    border-left: 3px solid #fff;
}
.preview-container {
    width: 85%!important;
    margin: 0 auto;
    margin-top: 12px;
    height: 230px;
}
.preview-container:after {
    display: block;
    content: "";
    width: 23px;
    height: 23px;
    position: relative;
    bottom: 16px;
    right: -108px;
}
.mejs__mediaelement:after {
    display: block;
    content: "";
    width: 23px;
    height: 23px;
    position: relative;
    bottom: -3px;
    left: -3px;
}
.preview-container:before {
    display: block;
    content: "";
    width: 23px;
    height: 23px;
    position: relative;
    top: 15px;
    left: -110px;
}
.mejs__mediaelement:before {
    display: block;
    float: right;
    content: "";
    width: 23px;
    height: 23px;
    position: relative;
    top: -4px;
    right: -2px;
}


}


.mejs__container {
    max-width: 95%;
   
}.preview-container:before




</style>


 <style>
a.btn.btn-block.testts.fav_list {
    color: black;
}  
.preview-container {
    width: 35%!important;
    margin: 0 auto;
    margin-top: 12px;
}
.preview-container:before {
    display: block;
    content: "";
    width: 23px;
    height: 23px;
    position: relative;
    top: 79px;
    left: -94px;
    z-index:999;
    border-top: 3px solid red;
    border-left: 3px solid red;
}
.preview-container:after {
   
   
       display: block;
    content: "";
    width: 23px;
    height: 23px;
    position: relative;
    bottom: 110px;
    right: -83px;
    border-bottom: 3px solid red;
    border-right: 3px solid red;
    
}
.mejs__mediaelement:before {
   display: block;
    float: right;
    content: "";
    width: 23px;
    height: 23px;
    position: relative;
    top: 20px;
    right: -3px;
    border-top: 3px solid red;
    border-right: 3px solid red;
}
.mejs__mediaelement:after {
   
     display: block;
    content: "";
    width: 23px;
    height: 23px;
    position: relative;
    bottom: 26px;
    left: -3px;
    border-bottom: 3px solid red;
    border-left: 3px solid red;
    
}
		  
		  
.sidebar {
    background: #eceff1;
}
div#app {
    margin-bottom: 20px;
}

.form-control {
    display: block;
    width: 350px;
}
table.table {
    width: 400px;
}

         .well
         {
         min-height: 20px;
         padding: 7px;
         margin-bottom: 20px;
         background-color: #fff;
         border: 1px solid #e3e3e3;
         border-radius: 4px;
         -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
         box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
         }
         .ct_ctycode {     
         margin-bottom: 12px;  
         }
    
 a.dropdownlivalue {
    padding: 10px;
}

@media (min-width: 328px) and (max-width:628px) {
.navbar-nav li a
{
	padding: 0px;
}
.ripple {
	
	padding: 3px, 10px;
}

}
@media (min-width: 760px) and (max-width:800px) {
.preview-container {
    width: 53%!important;
    margin: 0 auto;
    margin-top: 12px;
}
.mejs__mediaelement:after {
    display: block;
    content: "";
    width: 23px;
    height: 23px;
    position: relative;
    bottom: -55px;
    left: -3px;
}
}
@media (min-width: 328px) and (max-width:750px) {
.preview-container:before {
    display: block;
    content: "";
    width: 23px;
    height: 23px;
    position: relative;
    top: 155px;
    left: -50px;
    
}

.preview-container {
    width: 85%!important;
    margin: 0 auto;
    margin-top: 12px;
    height:350px;
        

}
.preview-container:after {
    display: block;
    content: "";
    width: 23px;
    height: 23px;
    position: relative;
       bottom: 77px !important;
    right: -40px;
        z-index:999;


}
.mejs__mediaelement:after {
    display: block;
    content: "";
    width: 23px;
    height: 23px;
    position: relative;
        top: -77px !important;
    left: 55px !important;
    z-index: 999999 !important;

}
.preview-container:before {
    display: block;
    content: "";
    width: 23px;
    height: 23px;
    position: relative;
   top: 153px;
    left: -40px;
        z-index:999;


}
.mejs__mediaelement:before {
    display: block;
    float: right;
    content: "";
    width: 23px;
    height: 23px;
    position: relative;
      top: 130px;
    right: 55px;	
        z-index:999;

}
#preview_from_mejs {
   height:240px;
}


.mejs__container {
    max-width: 85%;
   
}
.mejs__overlay-play
{
	
    height: 190px !important;
}
}


.mejs__container {
    max-width: 95%;
   
}
.mejs__mediaelement {
    width: 90% !important;
    float: right;
    text-align: center;
        margin-left: 12px;
}

    
      </style>
<script type="text/javascript">
   var $ = jQuery; 
   $(function() 
   {
    $( "#txtname" ).autocomplete({
    source: 'auto_complete.php'
    });
    
   });
   
   //~ var TitleAttr = $("#stl_scan").attr('title');
   //~ alert(TitleAttr);
   
   $(document).ready(function () {
       
       $("#mr_name").keyup(function () {
           $.ajax({
               type: "POST",
               url: "auto_complete.php",
               data: {
                   keyword: $("#mr_name").val()
               },
               dataType: "json",
               success: function (data) {
                   if (data.length > 0) {
                       $('#Dropdown_name').empty();
                       $('#mr_name').attr("data-toggle", "dropdown");
                       $('#Dropdown_name').dropdown('toggle');
                   }
                   else if (data.length == 0) {
                       $('#mr_name').attr("data-toggle", "");
                   }
                   $.each(data, function (key,value) {
   
                       if (data.length >= 0)
                           $('#Dropdown_name').append('<li role="presentation" ><a class="dropdownlivalue">' + value + '</a></li>');
                   });
               }
           });
       });
       
       $('ul.txtname').on('click', 'li a', function () {
           $('#mr_name').val($(this).text());
           $("#Dropdown_name").css("display", "none");
   
       });
       
       var latitude = 0;
       var longitude = 0;
	     navigator.geolocation.watchPosition(function(position) {
    // alert("i'm tracking you!");
    },
    function(error) {
    if (error.code == error.PERMISSION_DENIED)
    {
      // alert('Permission Not given');
     
    }
	else
	{
		// alert('Mo');
	}
    });
	var b_type=$('.business_type').val();
        var data = {latitude:latitude,longitude:longitude,method: "getFavoriteByBusiness", type:b_type, user_id: $(this).attr('user_id')};
           var login_user_id=$('#login_user_id').val();
		   // alert(login_user_id);
           getFavorite(b_type,login_user_id);
		navigator.geolocation.getCurrentPosition(function(position) {
		   
				console.log("current location");
			   latitude = position.coords.latitude;
			   longitude = position.coords.longitude;
		  // latitude=0;
			 codeLatLng(latitude, longitude);
			 var b_type=$('.business_type').val();
        var data = {latitude:latitude,longitude:longitude,method: "getFavoriteByBusiness", type:b_type, user_id: $(this).attr('user_id')};
           var login_user_id=$('#login_user_id').val();
		   // alert(login_user_id);
           getFavorite(b_type,login_user_id);
		   if(latitude>0 && longitude>0)
		   {
			 
			   // $( "#confm" ).prop( "disabled", true );
			  // alert(latitude);
		   }
		   else
		   {
			  // $("#error_label").html("Sorry, To Place order Location Permission is needed ");
			 // $('#location_model').modal('show');
			 
		   }
			   
			});
	
        $(".business_type").change(function(e){
			// alert(latitude);
           var data = {latitude:latitude,longitude:longitude,method: "getFavoriteByBusiness", type:e.target.value, user_id: $(this).attr('user_id')};
           
           getFavorite(e.target.value, $(this).attr('user_id'));
           
           // $("#nearby_restaurant tbody").html("");
       });
       function codeLatLng(lat, lng) {
    var geocoder= new google.maps.Geocoder();
    var latlng = new google.maps.LatLng(lat, lng);
  // alert(latlng);
    geocoder.geocode({'latLng': latlng}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
      // alert(results)
        if (results[1]) {
         //formatted address
         // alert()
        //find country name
           var full_address=results[0].formatted_address;
       $('#mapSearch').val(full_address);
        //city data
        // alert(city.short_name + " " + city.long_name)
        } else {
          alert("No results found");
        }
      } else {
        alert("Geocoder failed due to: " + status);
      }
    });
  }
       function getNearbyRestaurant(business, id){
           // 	var data = {method: "getNearbyRestaurants", type:business, user_id: id,latitude:latitude,longitude:longitude};
		     var data = {stype: "nearby", type:business, user_id: id,latitude:latitude,longitude:longitude};
            $.ajax({data:data,type: 'post',url: "getlist.php", success: function(result){
			$("#nearby_restaurant").html(result);
		}});near_table
       } 
       
       function getFavorite(business, id){
		   // alert(2);
           var data = {stype: "fav", type:business, user_id: id,latitude:latitude,longitude:longitude};
		   $.ajax({data:data,type: 'post',url: "getlist.php", success: function(result){
      $(".favorite_table").html(result);
    }});
	}   
     $(".nearby_restaurant_btn").click(function(e){
           e.preventDefault();
           
           getNearbyRestaurant($(".business_type").val(), $(this).attr('user_id')); 
           
       });
       
   });
</script>
