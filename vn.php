<?php
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Proxies.
include("config.php");
include_once('php/Section.php');
// include_once('php/SectionTable.php');
// Start of Hire's work
// Load merchant's product with QR
$p_status='';
$dine_in="n";
if(!empty($_GET['status'])){

	$p_status=$_GET['status'];

}
$mobile_otp_verify="n"; 
// print_r($_SESSION);
// die;
if(isset($_GET['code']) && isset($_GET['id']) && is_numeric($_GET['id']))
{
    // print_r($_GET);

	// die;

	$code = $_GET['code']; 

	$apiusername = $_GET['apiusername']; 

	$user_id = $_GET['id'];

	if(!isset($_GET['apiusername']))

	{

		// echo "SELECT * FROM users WHERE verification_code='$code' AND id='$user_id'";

		// die;

		$if_exists = mysqli_num_rows(mysqli_query($conn,"SELECT SQL_NO_CACHE * FROM users WHERE verification_code='$code' AND id='$user_id'"));

	    // echo $if_exists;

		// die;

		if($if_exists > 0)

		{

			  mysqli_query($conn, "UPDATE users SET password_created='y',otp_verified='y',verification_code='', isLocked='0' WHERE id='$user_id'");

			//$error = "You have verified your account successfully. Now You can login to use our service.<br>";

			

		}

		else

		{

			$error = "Your Link is Expire,Contact Support<br>";

		}

		$loginmatch = mysqli_query($conn,"SELECT SQL_NO_CACHE * FROM users WHERE  id='$user_id'");

	    // echo $if_exists;

		$user_row = mysqli_fetch_assoc($loginmatch); 

		$session_id =  uniqid($id . "_",true);

		$setup_session ="n";

		$id=$user_id;

		$mobile_otp_verify="y";

			$_SESSION['user_id']=$id;

		$_SESSION['login']=$id;

		$_SESSION['login_user_role']=$user_row['user_roles'];   

		

	}	   

}

// print_r($_SESSION);

// die;

$custom_msg="n";

if(!empty($_GET['sid'])){

    $sid = $_GET['sid'];

    $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SQL_NO_CACHE * FROM users WHERE mobile_number='$sid' and user_roles='2'"));

	$merchant_detail=$product;

    $merchant_name = $product['name'];

    $merchant_mobile_number = $product['mobile_number'];

	if($merchant_mobile_number=="60127771833")

		$_SESSION["langfile"] = "chinese";   

    $_SESSION['invitation_id'] = $product['referral_id'];

    $_SESSION['merchant_id'] = $product['id'];

    $_SESSION['address_person'] = $product['address'] ;

    $_SESSION['latitude'] = $product['latitude'] ; 

    $_SESSION['longitude'] = $product['longitude'] ;

    $_SESSION['IsVIP'] = $product['IsVIP'] ;

    $_SESSION['mm_id']= $product['id'];

	$_SESSION['block_pay']="n";

     

} 

if(!empty($_GET['m_id'])){

    $m_id = $_GET['m_id'];

    $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SQL_NO_CACHE * FROM users WHERE id='$m_id' and user_roles='2'"));

	$merchant_detail=$product;

    $merchant_name = $product['name'];

    $merchant_mobile_number = $product['mobile_number'];

	if($merchant_mobile_number=="60127771833")

		$_SESSION["langfile"] = "chinese";   

    $_SESSION['invitation_id'] = $product['referral_id'];

    $_SESSION['merchant_id'] = $product['id'];

    $_SESSION['address_person'] = $product['address'] ;

    $_SESSION['latitude'] = $product['latitude'] ; 

    $_SESSION['longitude'] = $product['longitude'] ;

    $_SESSION['IsVIP'] = $product['IsVIP'] ;

    $_SESSION['mm_id']= $product['id'];

	

     

} 

if(!empty($_GET['oid'])){

	$_SESSION['block_pay']="y";

}

// End of Hire's work

if(isset($_GET['q']) && $_GET['q'] == "verifyAgentCode"){

  $code = $_GET['code'];

  $mobile = $_GET['mobile'];

  $mobile_check="60".$mobile;

  // set limit for agent as per specific merchant 

   $merchant_id=$_SESSION['merchant_id'];



   // $total_order = mysqli_fetch_assoc(mysqli_query($conn,"SELECT count(id) as total_order from order_list where agent_code!='' and user_mobile='$mobile_check'"))['total_order'];

   $total_order = mysqli_fetch_assoc(mysqli_query($conn,"SELECT SQL_NO_CACHE count(id) as total_order from order_list where  user_mobile='$mobile_check'"))['total_order'];

  

  if($total_order<5)

  {

	  $rows = mysqli_num_rows($q);

	  $q = mysqli_query($conn,"SELECT SQL_NO_CACHE agent_id FROM agent_code WHERE code = '$code' ORDER BY date DESC LIMIT 1");

	  $rows = mysqli_num_rows($q);

	  if($rows == 0)

		die("false");

	  $id = mysqli_fetch_assoc($q)['agent_id'];

	  $q2 = mysqli_query($conn, "SELECT SQL_NO_CACHE code FROM agent_code WHERE agent_id='$id' ORDER BY date DESC LIMIT 1");

	  $q2_code = mysqli_fetch_assoc($q2)['code'];

	  if($q2_code == $code){

		die("true");

	  }else{

		echo $id . "\n";

		echo $q2_code . "\n";

		die("false");

	  }

  }

  else

  {

	die("max");  

  }

}

if(isset($_GET['q']) && $_GET['q'] == 'getNumAgentTransactions'){

  $mobile_phone = 60 . $_GET['phone_no'];

  $u_id = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SQL_NO_CACHE id FROM users WHERE mobile_number = '$mobile_phone'"))['id'];

  

  $rows = mysqli_num_rows(mysqli_query($conn, "SELECT SQL_NO_CACHE id,user_id,agent_code FROM order_list WHERE agent_code IS NOT NULL AND NOT agent_code = '' AND user_id='$u_id'"));

  echo $rows;

  die();

}

$sectionsObj = new Section($conn);

// $sectionTablesObj = new SectionTable($conn);

$sectionsFilter = [

  'user_id' => isset($_SESSION['merchant_id']) ? $_SESSION['merchant_id'] : null,

  'status' => true

];

$sectionsList = $sectionsObj->getList($sectionsFilter);

$sectionTableFilter = [

  'status' => true

];

if($sectionsList) {

  // $sectionTableFilter['section_id'] = (array_keys($sectionsList))[0];

}

// $sectionTablesList = $sectionTablesObj->getList($sectionTableFilter);

$bank_data = isset($_SESSION['login']) ? mysqli_fetch_assoc(mysqli_query($conn, "SELECT SQL_NO_CACHE * FROM users WHERE id='".$_SESSION['login']."'")) : '';

$check_number=$bank_data['mobile_number'];

$user_koo_coin=$bank_data['balance_inr'];
  
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
<?php 
		if(isset($_GET['data']))
		{
              $dine_in="y";
              $getdetail=$_GET['data'];

             $getdetail=base64_decode($getdetail);

       // print_R($getdetail);

       // die;

              $epxplode=explode("hweset",$getdetail);

        // print_R($epxplode);

        // die;

              $section=$epxplode[0];

              $tablenumber=$epxplode[1];

        }
?>

<?php  $login_user_id=$_SESSION['login'];

  if(isset($login_user_id))

  {

	    $urecord = isset($login_user_id) ? mysqli_fetch_assoc(mysqli_query($conn, "SELECT SQL_NO_CACHE id,user_roles,setup_shop,balance_usd,balance_inr,balance_myr FROM users WHERE id='".$login_user_id."'")) : '';

    	$balance_inr=$urecord['balance_inr'];	

		if($balance_inr=='')

			$balance_inr=0;

  }

   

 ?>

<!DOCTYPE html>

<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">



  

<head>



  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />

	<meta http-equiv="pragma" content="no-cache" />

	<meta http-equiv="expires" content="0" />

	

  <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">

    <?php include("includes1/headwithmainfest.php"); ?>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.6/dist/jquery.fancybox.min.css">

	<script src="https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.6/dist/jquery.fancybox.min.js"></script>  

  

  <!-- Images Loaded js -->

  <script src="https://unpkg.com/imagesloaded@4/imagesloaded.pkgd.min.js"></script>
  <script>
  function merchantclose()
   {
	    $(".modal.in").removeClass("in").addClass("fade").hide();

		  $(".modal-backdrop").remove();

		  $('#merchant_message').modal('hide'); 
   }
  </script>


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

/* Edited by Sumit  */

.introduce-remarks{

  height: 28px;  

  line-height: 12px;

}

#ProductModel .introduce-remarks{

  height: auto;

}

input[name='p_total[]'],input[name='p_price[]']{

  text-align: right;

}

/* Style for new products layout */

.new_grid{

  display: grid;

  grid-template-columns: 1fr 1fr 1fr;

  grid-column-gap: 40px;

  grid-row-gap: 20px;

}

/* End of Style for new products layout */

/* Style for remarks */

.extra-price-ingredient{

  position: absolute;

  top:-15px;

  right: -15px;

  background: #1dd800;

  color: white;

  width: 40px;

  height: 40px;

  z-index: 1000;

  display: grid;

  vertical-align: middle;

  align-content: center;

  text-align: center;

  border-radius: 50%;

}

#remarks_area .btn.btn-secondary{

  background-color: #e4e7ea;

  border-color: #e4e7ea;

  color: #555;

}

#remarks_area .btn.btn-secondary.checkbox-checked.active{

  background-color: #727b84 !important;

  border-color: #727b84 !important;

  color: #fff !important;

}

#remarks_area .modal-body{

  max-height: 70vh;

  overflow-y: auto;

}

/*End of style for remarks*/

.product_button span{

  background: #003A66;

  text-align: center;

  padding: 10px;

  color: #fff;

  font-size: 10px;

  text-transform: uppercase;

  font-weight: 500;

  cursor: pointer;

  border-radius: 8px;

  display: inline-block;

  margin-left:20px;

  padding:10px;

}

  .ingredient{

    border: 1px solid #03a9f3;

    color :#03a9f3;

    width: 95%;

    border-radius: 5px;

    padding: 3px;

    box-sizing: border-box;

    letter-spacing: 1px;

    margin: 8px 0;

    -webkit-touch-callout: none; 

    -webkit-user-select: none;

    -khtml-user-select: none;

    -moz-user-select: none; 

    -ms-user-select: none; 

    user-select: none;

  }

  .ingredient span:nth-child(even){

    padding-left: 10px;

    font-weight: bold;

  }

  #ingredients_container{

    display: grid;

    grid-template-columns: 1fr 1fr;

  }

  .credentials-container{

    width: 60%;

    

  }

  .credentials-container > div{

    display: grid;

    grid-template-columns: 2fr 3fr;

    grid-column-gap: 5px;

    grid-row-gap: 5px;

    margin-bottom: 10px;

  }

  .credentials-container > div > *{

    width: 100%;

  }

  #reg_field,#passwd_field{

    display: block;

    margin-top: 10px;

    width: 40%;

/*    grid-column-start: 1;
    grid-column-end: 3;
    grid-column-gap: 10px;
    grid-row-gap: 2px;
*/  }

  @media (max-width: 767px) {

  #remarks_area .modal-body{

    max-height: 60vh;

    overflow-y: auto;

  }

   #ProductModel > .modal-dialog{

    max-width: 90%;

   } 

   .credentials-container{

      width: 100%;

      margin-bottom: 20px;

    }

    .credentials-container > div{

      grid-template-columns: 1fr;

    }

    #reg_field{

      grid-template-columns: 1fr;

    }

    #passwd_field > input{

      grid-column-start: 1 !important;

      grid-column-end: 3 !important;

    }

    #reg_field, #passwd_field{

      width: 100%;

    }

  }

  input[type='submit'][disabled],button[disabled]{

    cursor: not-allowed;

  }

  input::-webkit-outer-spin-button,

  input::-webkit-inner-spin-button {

      /* display: none; <- Crashes Chrome on hover */

      -webkit-appearance: none;

      margin: 0; /* <-- Apparently some margin are still there even though it's hidden */

  }

  #login_passwd_modal .login_main button.btn{

    width: 100%;

  }

  #login_passwd_modal .login_main .row{

    margin-bottom: 20px;

  }

  @media (max-width: 767px) {

   #ProductModel > .modal-dialog{

    max-width: 90%;

   } 

  }

  body.noscroll{

      overflow: hidden !important;

      position: fixed;

    }

#remarks_area .modal-body{

  max-height: 70vh;

  overflow-y: auto;

}

 @media (max-width: 767px) {

  #remarks_area .modal-body{

    max-height: 60vh;

    overflow-y: auto;

  }

}

 img.lazy {
         min-width:175px;
		 min-height:80px;
        display: block;
      
        /* optional way, set loading as background */
        background-image: url('https://koofamilies.com/img/logo.png');
        background-repeat: no-repeat;
        background-position: 50% 50%;
    }
                  
  </style>

  <script type="text/javascript">

      var subproducts_global = [];

      var products_id_global = [];

	   var lastAdd = null;

  </script>




<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB4BfDrt-mCQCC1pzrGUAjW_2PRrGNKh_U&libraries=places" async defer></script>
<script src="https://scripts.sirv.com/sirv.js"></script>
<style type="text/css">
.active_menu
{
  background:#d6dadf !important;
}
  #pop_cart {
    font-size: .8em;
  }

  .modal {
    width: 93%;
  }

  @media only screen and (max-width:400px) {
    .navbar-nav>li {}
  }

  @import url('https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');

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
      transform: translate(-50%, -50%);
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
      left: 9%;
      top: 89%;
      transform: translate(-50%, -50%);
      padding: 3px 5px 3px 5px;
      color: white;
      font-family: 'FontAwesome';
      font-size: 12px !important;
      background-color: rgba(23, 35, 34, 0.75);
      border-radius: 5px 5px 5px 5px;
    }
  }

  #record {
    margin: 0px auto;
    font-weight: bold;
    cursor: pointer;
    width: 100px;
    height: 100px;
    text-align: center;
    -moz-border-radius: 50px;
    -webkit-border-radius: 50px;
    border-radius: 50px;
    background: green;
  }

  #stop {
    margin: 0px auto;
    font-weight: bold;
    cursor: pointer;
    width: 100px;
    height: 100px;
    text-align: center;
    -moz-border-radius: 50px;
    -webkit-border-radius: 50px;
    border-radius: 50px;
    background-color: red;
  }

  #recordText {
    padding-top: 40px;
    color: #fff;
  }

  .mejs__container {
    display: none;
  }


  /************ New css 04022020 ***************/

  #controls {
    display: flex;
  }
  button.pbtn{
    flex-grow: 1;
    height: 2.5rem;
    min-width: 2rem;
    border: none;
    border-radius: 0.15rem;
    background: #fb9678;
    margin-left: 2px;
    box-shadow: inset 0 -0.15rem 0 rgba(0, 0, 0, 0.2);
    cursor: pointer;
    display: flex;
    justify-content: center;
    align-items: center;
    color:#ffffff;
    font-weight: bold;
    font-size: 1rem;
  }
  button.pbtn:hover, button:focus {
    outline: none;
    background: #fa7953;
  }
  button.pbtn::-moz-focus-inner {
    border: 0;
  }
  button.pbtn:active {
    box-shadow: inset 0 1px 0 rgba(0, 0, 0, 0.2);
    line-height: 3rem;
  }
  button.pbtn:disabled {
    pointer-events: none;
    background: lightgray;
  }

  button.pbtn:first-child {
    margin-left: 0;
  }

  audio {
    display: block;
    width: 100%;
    margin-top: 0.2rem;
  }

  #recordingsList{
    padding: 0px;
    margin: 20px 0px;
  }

  li {
    list-style: none;
    margin-bottom: 1rem;
  }


  /************ New css 04022020 ***************/


  /*************19-02-20****************/

/*  @media only screen and (max-width: 576px) {
   For mobile phones: 
  [class*="col-"] {
    width: 100%;
  }
  }
*/

  @media only screen and (max-width: 576px) {
   input.btn.btn-block.btn-primary.submit_button.online_pay {
    margin-left: 10px;
  }
  .mp {
    margin-left: 20em !important;
    margin-top: 10px;
    }
  
  .mp1 {
        margin-left: 20em !important;
        margin-top: -35px;
    }
  }

  @media only screen and (max-width: 576px) {
    .mp1 {
        margin-left: 2em !important;
        margin-top: 0px;
    } 
  }

	@media only screen and (max-width: 411px) {
		.microphne-sec {
      		margin-left: -6px !important;
      		padding-top: 10px;
    	}
	}
  @media only screen and (max-width: 375px) {
   input.btn.btn-block.btn-primary.submit_button.online_pay {
    margin-left: 10px;
  }
  .mp {
    margin-left: 20em !important;
    margin-top: 10px;
    }
	.mp1 {
	    margin-left: 11em !important;
	    margin-top: -38px;
	 }
   .microphne-sec {
      margin-left: -10px !important;
      padding-top: 10px;
    }
  }
  @media only screen and (max-width: 360px) {
   input.btn.btn-block.btn-primary.submit_button.online_pay {
    margin-top: 10px;
    }
    .online_label{
    	margin-left: 0px !important;
    }
    .online_label input{
      margin-top: 0px !important;
      margin-left: 2px !important;

    }
  }
  @media only screen and (max-width: 320px) {
    .micro-flex{
      align-items: center;
    }
    .online_label input{
      margin-top: 0px !important;
      margin-left: 0px !important;
    }
    .online_label{
      margin-left: 0px !important;
    }  
    .microphne-sec{
      margin-top: 0px;
      margin-left: 0px !important;
    }
  }
  

  /*************19-02-20***************/


</style>
<link rel="stylesheet" type="text/css" href="css/sweetalert.css">
</head>



<body  class="header-light sidebar-dark sidebar-expand pace-done">

     <?php

         $id = $_SESSION['mm_id'];

        $merchant_detail = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$id."'"));

		// print_r($merchant_detail);

		// die;
		 $voice_recognition = $merchant_detail['voice_recognition']; 
		if($merchant_detail['id']=='')

		{

			header("location:merchant_find.php");

		}

		if($merchant_detail['id']=='5062')

		{

			$back_url=$_SERVER['HTTP_REFERER'];

			if(strpos($back_url,"structure_merchant.php") !== false){

				$block_pay="y";

				$_SESSION['block_pay']="y";

			} else{



				   $block_pay="n";

			}

		}

		else

		{

			$block_pay="n";

			$_SESSION['block_pay']="n";

		}

		$product_zoom=$merchant_detail['product_zoom'];

		 $online_pay=0;

		 $discounted_product = $merchant_detail['discounted_product'];

		 $online_pay=0;

	 if($merchant_detail['credit_check'] || $merchant_detail['wallet_check'] || $merchant_detail['boost_check'] || $merchant_detail['grab_check']

	 || $merchant_detail['wechat_check'] || $merchant_detail['touch_check'] || $merchant_detail['fpx_check'])

	 {

		  $online_pay=1;

		  // $payment_alert="y";

	 }	

	  $online_pay=1;

     $location_order=$merchant_detail['location_order'];

     $koo_wallet=$merchant_detail['koo_wallet'];

     $merchant_id=$merchant_detail['id'];

     $special_coin_name=$merchant_detail['special_coin_name'];

	 if($special_coin_name)

	 {

		$special_coin_min=$merchant_detail['special_coin_min'];

		$special_coin_max=$merchant_detail['special_coin_max'];

		if($_SESSION['login'])

		{

			// echo "SELECT * FROM special_coin_wallet WHERE  merchant_id='$merchant_id' and user_id ='".$_SESSION['login']."'";

			

		$special_wallet = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM special_coin_wallet WHERE  merchant_id='$merchant_id' and coin_active='y' and coin_balance>=0 and user_id ='".$_SESSION['login']."'"));

		// print_R($special_wallet);

		// die;

		if($special_wallet)

		 $special_bal=$special_wallet['coin_balance'];

				else

				 $special_bal=0;

		}

		else

		{

		  $special_bal=0;  	

		}

		

	 }

	 else

	 {

	    $special_bal=0;	 

	 }

	  $submerchantsql = mysqli_query($conn, "SELECT * FROM users WHERE mian_merchant='".$merchant_detail['name']."' ");

        $stallcount=mysqli_num_rows($submerchantsql); 

    // die;

    $location_range=$merchant_detail['location_range'];

        // echo json_decode($merchant_detail['custom_message'])->message;

        if($merchant_detail['custom_message']!=''){  

         

          $merchant_message =$merchant_detail['custom_message'];

		  $custom_msg="y";

        ?>

     

        <div class="modal-backdrop show"></div> 

		 <div class="modal in" id="merchant_message" tabindex="-1" role="dialog" data-show="true" style="display:block;">

        

          <div class="modal-dialog modal-dialog-centered" role="document">

            <div class="modal-content">

              <div class="modal-header">

			   <h5 class="modal-title" id="exampleModalCenterTitle"><?php echo $merchant_message; ?> <!--span style="color: #f00"><?php echo $merchant_detail['name'];?></span!--></h5>

			   <!--button type="button"   class="merchant_close" data-dismiss="modal" aria-label="Close">

            <span aria-hidden="true">&times;</span>

          </button!-->

			  

			 </div>

              <div class="modal-body">

			  <?php if($merchant_detail['custom_msg_image']){?>

                 <img class="img-responsive" style="margin:0 auto;" src="customimage/<?php echo $merchant_detail['custom_msg_image'];?>" alt="<?php echo $merchant_message; ?>">

             

			  <?php } else { ?>

			  <p><?php  echo $merchant_message; ?></p>

			  <?php } ?>

               

              </div>

            </div>

          </div>

        </div>

      <?php  } ?>



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



                    <?php

                     $id = $_SESSION['mm_id'];

					

                    $merchant_detail = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$id."'"));

					$min_order_amount=$merchant_detail['order_min_charge'];

                    if( isset($_SESSION['login']) ) {

                        $sql_transaction = "SELECT COUNT(id) ordered_num
                            FROM order_list
                            WHERE user_id='".$_SESSION['login']."' and merchant_id = '".$id."' AND STATUS='1'";

                        $result_transaction = mysqli_fetch_assoc(mysqli_query($conn,$sql_transaction));    

                    } else {

                        $result_transaction = '';

                    }

                    

                    $sql_favorite = "SELECT COUNT(id) favorite_num
                    FROM favorities
                    WHERE favorite_id = '".$id."'";

                    $result_favorite = mysqli_fetch_assoc(mysqli_query($conn,$sql_favorite));

                    

                    $business1 = "";

                    $business2 = "";

                    for($i = 0; $i < count($nature_array); $i++){

                    if($merchant_detail['business1'] == $nature_array[$i])

                    $business1 = $nature_image[$i];

                    if($merchant_detail['business2'] == $nature_array[$i])

                    $business2 = $nature_image[$i];

                    }

                    

                    $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT count(id) as pro_ct FROM products WHERE user_id ='".$id."' and status=0"));

                    $total_rows = mysqli_query($conn, "SELECT * FROM products WHERE user_id ='".$id."' and status=0");  

          

                    $favorite = isset($_SESSION['login']) ? mysqli_query($conn, "SELECT * FROM favorities WHERE user_id = ".$_SESSION['login']." AND favorite_id = ".$id."") : '';

                    $count = $favorite != '' ? mysqli_num_rows($favorite) : 0;

                    ?>

                    

                    

                    <main class="main-wrapper clearfix" style="min-height: 522px;">

                    <div class="row" id="main-content" style="padding-top:25px">

          <input type="hidden" id="shop_status" value="<?php echo $merchant_detail['shop_open']; ?>"/>

                    <?php

                    if($_SESSION['IsVIP'] ==1){ 

                      ?>

                    

                        <div class="box-right">

                            

                            <div class="title">

                                <div class="title-left"> <img src="new/images/merchant.png"> <div class="title-h">  <a href="#"> Merchant Name : <?php echo $merchant_detail['name'];?></a> </div>  </div> 

                            <div class="title-right"> 

                                <div class="favorite_icon">

                                <?php if($count > 0) {?>

                                <i class="heart fa fa-heart"></i>

                                <?php } else {?>

                                <i class="heart fa fa-heart-o"></i>

                                <?php }?>

                                

                                <h4 class="starting-bracket" style="display: inline-block;">(</h4>

                                <?php if($business1 != ""){ ?>

                                <img style="margin-top:10px;" class="nature_image" src="<?php echo $site_url;?>/img/<?php echo $business1;?>">

                                <?php }?>

                                <?php if($business2 != ""){ ?>

                                <img style="margin-top:10px;" class="nature_image" src="<?php echo $site_url;?>/img/<?php echo $business2;?>">

                                <?php }?>

                                <?php if($merchant_detail['account_type'] != ''){?>

                                <h4 class="transaction_num"> <?php echo $merchant_detail['account_type'];?>, </h4>

                                <?php }?>

                                <h4 class="transaction_num"><?php echo $result_transaction['ordered_num'];?>, </h4>

                                <h4 class="favorite_num"><?php echo $result_favorite['favorite_num'];?>)</h4>

                                </div>

                                </div>

                            </div> 

                        </div> 

                        <div class="cont-area3"> 

                        <div class="white-box"> 

                        <div class="btns">

                        <div class="main-btn"> <a  href="<?php echo $site_url; ?>/about_menu.php"><?php echo $language["about_us"]?></a> </div>

                        <div class="main-btn1">  <a  href="<?php echo $site_url; ?>/rating_list.php"><?php echo $language["rating"]?> </div>

                        <?php if(isset($_SESSION['invitation_id']) && (!isset($_SESSION['login']))){?>

                        <a class="col-md-2" href="signup.php?invitation_id=<?php echo $_SESSION['invitation_id'];?>"><img src="img/join-us.jpg" style="width: 100px;"></a>

						

                        <?php }?>

                        </div>

                        

                        

                    

                        <div class="clear-both"> </div>

                        

                        <div class="head-title">Merchant</div>

                        <div class="main-cont">

                        It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently.

                        </div>

                        

                        

                        <div class="grey-bg"> 

                        <div class="grey-left">Merchant Name  </div> 

                        <div class="grey-right"> Product No : 0 </div> 

                        </div> 

                        

                        </div>

                        </div>

                    </div> 

                    

                    <?php }else{ ?> 

                    <div class="col-md-12">

                    <div class="total_rat_abt">
					 <a class="col-md-2 rating_menuss" style="background:red;" href="index.php"><?php echo $language['more_shops']; ?></a>

					
                    <div class="about_uss"><a class="merchant_about" href="<?php echo $site_url; ?>/about_menu.php"><?php echo $language["about_us"]?></a></div>

                    <div class="rating_menuss"><a class="merchant_ratings" href="<?php echo $site_url; ?>/rating_list.php"><?php echo $language["rating"]?> </a></div>

                    <div class="rating_menuss">

					<?php if($merchant_detail['google_map']){ ?>

						<a class="merchant_ratings" target="_blank" href="https://maps.google.com/maps?q=<?php echo  $merchant_detail['google_map']; ?>"> <?php echo $language['location']; ?></a> 

						
    
						<?php  } else { ?>

						<a class="merchant_ratings"  href="#"><?php echo $language['location']; ?></a> 

						

						<?php }  ?>

					<!--a class="merchant_ratings" href="<?php echo $site_url; ?>/location.php?address=<?php echo  $_SESSION['address_person'] ?>"><?php echo $language['location']?> </a!-->

					

					</div>

                    

					<?php if($stallcount>0){ ?>

					 <div class="rating_menuss"><a class="merchant_ratings" onclick="our_stall()">Our Stalls </a></div>

					 <?php } ?>

                    

                    <!--a class="col-md-2" href="signup.php?invitation_id=<?php echo $_SESSION['invitation_id'];?>"><img src="img/join-us.jpg" style="width: 100px;"></a!-->

                    <!--a class="col-md-2 rating_menuss" href="signup_referral.php?m_id=<?php echo $merchant_detail['id']; ?>"><?php echo "ADD MEMBER"; ?></a!-->
                   

                  

                    </div>

					

				  <div class="clear"></div>
					  <div class="row">
						  <div class="col-xs-4">
											  <a style="text-align:center;width:100%;margin-top:2%;" href="https://play.google.com/store/apps/details?id=com.app.koofamily" target="blank">
							  <img style="max-width:140px;" src="google.png" alt="">
							</a>
						  </div>
						  <?php if($voice_recognition){ ?>
						  <div class="col-xs-8">
							<div style="width: 40px;margin-left: 20px;cursor: pointer;" class="mp1">
							  <img src="images/Microphone.png" data-type="top" class="img-responsive microphone_click" id="microphone_click">
							</div>            
						  </div>
						  <?php } ?>   
						</div>


					

				  <div class="clear"></div>

                    

                    </div>

                      <p style="color:red;" id="error_label"></p>

                    

                    <div class="col-md-12 row favorite" style="margin-left:15px; margin-bottom: 10px; padding-left:0px;" >

                    <div style="clear:both;">

					<?php 

					if($merchant_detail['mobile_number']==''){?>

						<script>

						window.location.replace("https://www.koofamilies.com/merchant_find.php");

						</script>

					<?php }?>

                    <h4 class="favorite_name" style="display: inline-blick;">
                       <?php if($merchant_detail['id']==6958 || $merchant_detail['id']==6956) {  ?>
                      <a href="https://api.whatsapp.com/send?phone=60123115670" target="_blank">Chat with <?php echo $merchant_detail['name']; ?>
					   <?php } else { ?>
					    <a href="https://api.whatsapp.com/send?phone=<?php  echo $merchant_detail['mobile_number']?>" target="_blank">Chat with <?php echo $merchant_detail['name']; ?>
					   <?php } ?>  

					<img src="images/whatapp.png" style="max-width:40px;"/></a>   

					</h4>

                    </div>

                    <h4 class="favorite_name" style="display: inline-block;margin-left:1%;"> Name: <?php echo $merchant_detail['name'];?></h4>   

                    <div class="favorite_icon">

                    <?php if($count > 0) {?>

                    <i class="heart fa fa-heart"></i>

                    <?php } else {?>

                    <i class="heart fa fa-heart-o"></i>

                    <?php }?>

                    </div>

                    <h4 class="starting-bracket" style="display: inline-block;">(</h4>

                    <?php if($business1 != ""){ ?>

                    <img style="margin-top:10px;" class="nature_image" src="<?php echo $site_url;?>/img/<?php echo $business1;?>">

                    <?php }?>

                    <?php if($business2 != ""){ ?>

                    <img style="margin-top:10px;" class="nature_image" src="<?php echo $site_url;?>/img/<?php echo $business2;?>">

                    <?php }?>

                    <?php if($merchant_detail['account_type'] != ''){?>

                    <h4 class="transaction_num"> <?php echo $merchant_detail['account_type'];?>, </h4>

                    <?php }?>

                    <h4 class="transaction_num"><?php if( isset( $result_transaction['ordered_num'] ) ) {echo $result_transaction['ordered_num'];}?>, </h4>

                    <h4 class="favorite_num"><?php if( isset($result_favorite['favorite_num']) ) {echo $result_favorite['favorite_num'];}?>)</h4>

                    </div>

            <?php

               $current_time = date("h:i");

               $opening_hr=$merchant_detail['start_time_setup'];

               $end_hr=$merchant_detail['end_time_setup'];

              $date1 = DateTime::createFromFormat('H:i', $current_time);

             $date2 = DateTime::createFromFormat('H:i', $opening_hr);

             $date3 = DateTime::createFromFormat('H:i', $end_hr);

            $go_ahead=true;

            // if($merchant_detail['shop_open']=="0")

            // {

                 // $go_ahead=false;

            // }

              if($go_ahead==true){  

          ?>

               <!-- if store is open !-->

          <?php if($merchant_detail['mobile_number']!="60172669613"){ ?>

                    <!--div class="comm_prd">
                    <h4 class="head_oth"><?php echo $language["order_direct"];?></h4>
                   
          <div class="oth_pr" id="oth_pr">
                    Order 
                    </div>
          
                    </div!-->

          <?php } ?>

          



                     <?php

                        if($merchant_detail['menu_type']==2){

                            include 'view_merchant_layout2.php';

                        } else {  

                            include 'view_merchant_layout1.php';

                        }

                    ?>  

                   

                   

                </div>

        

        <!-- without picture--->

        <?php

        if($merchant_detail['menu_type']==1) {

    // echo "SELECT * FROM products WHERE category = '".$sub_cat."' and user_id ='".$id."' and status=0";

    // die;

        $total_rows1 = isset($category) ? mysqli_query($conn, "SELECT * FROM products WHERE category = '".$sub_cat."' and user_id ='".$id."' and status=0") : [];

        ?>

        <div class="without_picture">

        

        <table class="table table-striped" id="without_table">

        <thead>

        <tr>

         <th>S.no</th>

         <th>Product Name</th>

         <th>Action</th>

         <th>Remark</th>

         <th>Price</th>

         <th>Code</th>

        </tr>

        </thead>

        <tbody>

        <?php

        $i =1;

        while ($row=mysqli_fetch_assoc($totalo_rws1)){

          // var_dump($row);

          if($row['image'] == '') {

        

        

        ?>

          <tr>

              <td><?php echo $i; ?> </td>

              <input type="hidden" id="id" name="m_id" value="<?php echo $id;?>">

            

                <input type="hidden" id="id" name="p_id" value="<?php echo $row['id'];?>">

            <td><?php echo $row['product_name']; ?></td>

         <?php if($row['varient_exit']=="y") { $cart_class="with_varient";} else { $cart_class="without_varient";} ?>

            <?php 

              if($row['on_stock']){

             ?>

            

            <td  class="text_add_cart_without  <?php echo $cart_class ?>"  data-id = "<?php echo $row['id'] ?>" data-code = "<?php echo $row['product_type'] ?>"  data-pr = "<?php echo number_format((float)$row['product_price'], 2, '.', ''); ?>" data-name = "<?php echo $row['product_name'] ?>" id="text_without">Add to Cart</td>

            <?php 

              }else{

                ?>

                  <p class='no_stock_add_to_cart'>Out of stock</p>



                <?php

              }

             ?>

            <td><?php echo number_format((float)$row['product_price'], 2, '.', ''); ?></td>

            <td><?php echo $row['remark']; ?></td>

            <td><?php echo $row['product_type']; ?></td>

            

            </tr>

        <?php  $i++; }

        ?>

        <?php } ?>

        </tbody>

        </table>

        </div>

        <?php } ?>

        

        <?php if($merchant_detail['mobile_number']!="60172669613"){ ?>

        <div class="comm_prd">

        <h4 class="head_oth"><?php echo $language["order_direct"];?></h4>

    

        <div class="oth_pr" id="oth_pr"><?php echo $language['order']; ?></div>

      

        </div>

    <?php } ?>

        

        </div>

        </main>

        </div>

        <!-- adding new-->

        <?php $profile_data = isset($_SESSION['login']) ? mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$_SESSION['login']."'")) : '';

        ?>

        <main class="main-wrapper clearfix" style="min-height: 522px;">  

        <?php if( !isset($profile_data['user_roles']) || $profile_data['user_roles'] ==  '') { ?>

        <form method="post" id="order_place" action="order_cash.php">

        <?php

        $stl_key = rand();

        $_SESSION['stl_key'] = $stl_key; ?>

        <input type="hidden" name="stl_key" value="<?php echo $stl_key; ?>">

        

        

        <?php } else { ?>

        <form id="order_place" action="order_cash.php" method="post">

        <?php } ?>

        

       <table class="table table-striped" id="cartsection" style="width:100%;">

        <thead>

        <tr> 

        <th></th>

        <th><?php echo ucfirst(strtolower($language["product_name"])); ?></th>

        <th><?php echo $language['qty']; ?></th>



        <th><?php echo ucfirst(strtolower($language["product_code"])); ?></th>

        

        <th><?php echo ucfirst(strtolower($language["remark"])); ?></th>



        <th><?php echo ucfirst(strtolower($language['extras'])); ?></th>

        

    <th><?php echo $language['unit_price']; ?></th>

        <th><?php echo $language['total']; ?></th>

        </tr>

        <tbody id="test"> </tbody>

        </thead>

        

        </table>

        

        <a href="#main-content"><p class="" style="width: 12rem !important;text-align:center;font-size: 16px;padding:14px;background-color: #003A66;color: white; font-weight: bold; border-radius: 8px;"><?php  echo $language['add_more_order']; ?></p></a> <br/>

        

        <?php

        // basic required field validation

		if($merchant_detail['section_exit']=='0' && $merchant_detail['table_exit']=='0' && $merchant_detail['delivery_address_exit']=='0')   

		{

			$all_blank="y";

		}

		else

		{

			$all_blank="n";

		}

		?>

        <div class="location_merchant"> 

		<div style="<?php if($all_blank=="y"){ echo "display:none;";} ?>">

			 <div class="row" style="max-width: 50%;min-height: 41px;margin-bottom: 2%;padding-bottom: 1%;margin-left:2%;">

				<div class="col-md-4 merchant_select " style="box-shadow:rgba(0, 0, 0, 0.16) 0px 2px 5px 0px, rgba(0, 0, 0, 0.12) 0px 2px 10px 0px;font-weight: bold; border-right: 1px solid black; padding-top: 2%;padding-bottom:1%; background: red none repeat scroll 0% 0%; color: rgb(255, 255, 255);">

				<input type="checkbox" id="takeaway_select" checked/> Delivery</div>  

				<div class="col-md-4 divert" style="box-shadow:rgba(0, 0, 0, 0.16) 0px 2px 5px 0px, rgba(0, 0, 0, 0.12) 0px 2px 10px 0px;font-weight: bold; padding-top: 2%;padding-bottom:1%;background: rgb(81, 210, 183) none repeat scroll 0% 0%; color: rgb(85, 85, 85);">

				<input type="checkbox" id="divein"/> Dine in/pick up</div>

			</div>  

        <div class="name_mer">

          <div style="display:grid;grid-template-columns:.2fr 2fr;grid-column-gap: 10px;vertical-align: middle;align-content:center;>
            <?php $deliver_place="Delivery Place"; ?>
            <label class="head_loca" style="display:grid;align-content:center;text-align:left;"><?php  echo $language['delivery_location']; ?></label>

            <input type="hidden" name="latitude" value="<?php echo $merchant_detail['latitude'];?>">

           

            <input type="hidden" name="longitude" value="<?php echo $merchant_detail['longitude'];?>">

       <input type="hidden" name="user_lat" id='user_lat'>

	     

            <input type="hidden" name="user_long" id='user_long'>

            

         <div class="row">

          <div style="float:left;width:80%;margin-left:5%;"> 

		  <input class="form-control comment" id="mapSearch" name="location" placeholder="location"   style="margin: 0 !important;">   

            </div>

          <div style="float:left;display:none;"><img src="https://img.icons8.com/office/16/000000/worldwide-location.png"></div>

        </div>

             

      </div>

      </div> 

	

	    

	 

</br>

 <!--span class="plastic_remark" style="color:red;font-size:12px;"><?php  echo $language['food_package']; ?></span!-->
 <div class="plastic_remark">

          <div style="display:grid;grid-template-columns:.2fr 2fr;grid-column-gap: 10px;vertical-align: middle;align-content:center;>
           
            <label class="head_loca" style="display:grid;align-content:center;text-align:left;"><?php  echo $language['packaging']; ?></label>

       

        <div class="row">

          <div style="float:left;width:80%;margin-left:5%;"> 

			<select class="form-control" name="plastic_box">
			  <option value=""><?php echo $language['select_packaging']; ?></option>
			  <option value="plastic container"><?php echo $language['plastic_box']; ?></option>
			  <option value="BPA 100% Tupperware"><?php echo $language['bpa_box']; ?></option>
			</select>

            </div>

          

        </div>



             

      </div>

      </div>
 </br>

  <div class="name_remakr">

          <div style="display:grid;grid-template-columns:.2fr 2fr;grid-column-gap: 10px;vertical-align: middle;align-content:center;>
           
            <label class="head_loca" style="display:grid;align-content:center;text-align:left;"><?php  echo $language['remark']; ?></label>

       

        <div class="row">

          <div style="float:left;width:80%;margin-left:5%;"> 

		  <input class="form-control comment" id="remark_extra" name="remark_extra" placeholder="Serving time, Persons, etc"   style="margin: 0 !important;">   

            </div>

          

        </div>



             

      </div>

      </div>

	  		 

  <div style="float:left;width:100%;top:0;display:none;" id="sec_table_show">   

   

    

    <div style="float:left;width:20%;display:none;" id="section_show">

        <label><?php echo $language['sections']; ?> <br></label>

             <!--input type="text" class="form-control table" name="section_type" value="<?php echo $section; ?>"/!-->

        <select name="section_type"  id="section_type"

              class="form-control"  <?php if($merchant_detail['section_required']=="1"){ echo "required='required'";} ?>

              data-table-list-url="<?php echo $site_url; ?>/table_list.php">

       

       

          <?php foreach($sectionsList as $sectionId => $sectionName): ?>

            <?php

              $isSelected = "";

              if($section == $sectionId) {

                $isSelected = "selected";

              }

            ?>   

            <option value="<?php echo $sectionId; ?>" <?php echo $isSelected; ?>><?php echo $sectionName; ?></option>

          <?php endforeach; ?>

        </select>

    </div>

	



    <div style="float:left;width:40%;display:none;" id="table_show">

           



            <label><?php echo $language['show_table_number']; ?></label>

            <input type="text" class="form-control table"  id="table_type" name="table_type"  <?php if($merchant_detail['table_required']=="1"){ echo "required";} ?>  value="<?php echo $tablenumber; ?>"/>

         <!--select name="table_type" class="form-control section-tables" required>
              
            </select!-->

    </div>

	

		

	

    

			  </div>   

   

  </div>

  

      

    <div class="credentials-container" id="mobile_no_label">

    

    <h5><?php echo $language['enter_ur_phone']; ?></h5>

    <div>

      <div class="input-group mb-2 mobile_no_label"  style="margin-bottom:0px !important;">

        <div class="input-group-prepend">

          <div class="input-group-text" style="background-color:#51D2B7;border-radius: 5px 0 0 5px;height: 100%;padding: 0 10px;display: grid;align-content: center;">+60</div>

        </div>

    

        <input type="number" autocomplete="tel" maxlength='10' id="mobile_number" style="max-width:220px;"  class="mobile_number form-control" value="<?php if($check_number){ echo $check_number;}  ?>" placeholder="key in phone number" name="mobile_number" required="" />

         

    </div>



    <small>

   <?php echo $language['we_can_contact'];  ?>

    </small>

	

    <small id="mobile_error" style="display: none;color:#e6614f;font-size:20px;font-weight:bold;">

     <?php echo $language['key_in_valid_mobile']; ?>

    </small>

	<?php if($bank_data){

	     $member_id=$bank_data['id'];

	     $mobile_check=$bank_data['mobile_number'];

		 $merchant_id=$merchant_detail['id'];

		 $query="SELECT user_membership_plan.*, membership_plan.user_id as memberplan_user, membership_plan.* FROM user_membership_plan INNER JOIN membership_plan ON membership_plan.id = user_membership_plan.plan_id WHERE user_membership_plan.plan_active='y' and user_membership_plan.user_id='$member_id' and user_membership_plan.merchant_id = '$merchant_id' ";

			$user_plan = mysqli_fetch_assoc(mysqli_query($conn,$query));

			$defalut_plan="select count(plan.id) as total_count,u.created from membership_plan as plan inner join user_membership_plan as u on u.plan_id=plan.id where plan.user_id='$merchant_id' and plan.default_plan='y'
				and u.user_id='$member_id'";

			$defalutarray = mysqli_fetch_assoc(mysqli_query($conn,$defalut_plan));

			$defalutplan=$defalutarray['total_count'];

			$created_date=$defalutarray['created'];

			$total_shop=mysqli_fetch_assoc(mysqli_query($conn,"select sum(total_cart_amount) as total_order_amount from order_list where user_id='$user_id' and merchant_id='$merchant_id' and created_on>='$created_date'"));

				if($total_shop['total_order_amount'])

				{

					$total_shop_amount=number_format($total_shop['total_order_amount'],2);

					$total_shop=$total_shop['total_order_amount'];

				}

				else

				{

					$total_shop_amount=0;

					$total_shop=0;

				}

				$local_coin = mysqli_fetch_assoc(mysqli_query($conn,"select sum(local_coin) as total_coin from local_coin_sync where merchant_id='$merchant_id' and user_mobile='$mobile_check' and order_date>='$created_date'"));

				if($local_coin['total_coin']>0)

				{

					$total_shop=$local_coin['total_coin']+$total_shop;

				}

				$user_plan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM membership_plan WHERE user_id='$merchant_id' and $total_shop BETWEEN total_min_order_amount AND total_max_order_amount"));

			if($user_plan && $defalutplan>0)

			{  

				$plan_type=$user_plan['plan_type']; 

										if($plan_type=="fix")

										{

										$plan_label="Rm ".$user_plan['plan_benefit']." off";

										}

										else

										{											

											$plan_label=$user_plan['plan_benefit']." %";

										}

			?>

				 <small class="row" style="color:red;margin-left:2%;font-weight:bold;"  id="membership_discount">

				 

				 You will recieve MemberShip Discount of <?php echo $plan_label;?> on Total Order    

				 </small>

			<?php }} else { ?>

			<small class="row" style="color:red;margin-left:2%;font-weight:bold;display:none;" id="membership_discount">

				 You will recieve MemberShip Discount of  on Total Order    

				 </small>

			<?php } ?>

      <img id="loader-credentials" src="<?php echo $site_url;?>/img/loader.gif" style="display:none;width:40px;height:40px;grid-column-start: 2;grid-column-end: 3;"/>

      

   </div>

   <?php 

   if(isset($_SESSION['login'])){

      $u_id = $_SESSION['login'];

      $rows = mysqli_num_rows(mysqli_query($conn, "SELECT id,user_id,agent_code FROM order_list WHERE agent_code IS NOT NULL AND NOT agent_code = '' AND user_id='$u_id'"));

   }else{

     $rows = 0;

   }

   ?>

     <?php if($total_order<5){ ?>

    <!--div class="row">
      <div class="col-md-4 form-group">
        <input type="text" id="agent_code_input" class="form-control" placeholder="Introduce your agent code..."/>
        <small id="agent_error" style="display:none;color:red;"></small>
	  </div>
    </div!-->

	

   <?php } if($merchant_detail['coupon_offer']){ ?>

		

        <div class="row">

                <div class="col-md-12">

                    <div class="input-group mb-2" style="margin-bottom:0px !important;">

					<input type="hidden" id="coupon_id" name="coupon_id"/>

                        <input type="text" autocomplete="tel" maxlength='100' id="coupon_code" style="min-width:220px;"  class="coupon_code form-control"  placeholder="Enter Promo Code" name="coupon_code"/>

                        <a class="btn btn-info" id="apply_coupon">Apply</a>

                    </div>

                </div>

		</div>

        <div clas="row">

                <div class="col-md-12">

                    <div id="coupon_message" style="display:none;color:red;"></div>

                    

                </div>

        </div>

	 <?php } ?>

			 <input type="hidden" name="membership_discount_input" id="membership_discount_input" value="<?php  echo $plan_label;?>" />

			 <input type="hidden" name="membership_applicable" id="membership_applicable" value="<?php if($plan_label){ echo "y";} ?>" />

			             <input type="hidden" name='varient_must' id='varient_must'/>

        <input type="hidden" name='delivery_charges' value='2.99' id='delivery_charges'/> 

  <input type="hidden" id="deliver_tax_amount" name="deliver_tax_amount" value="0">

					

        <input type="hidden" name='varient_count' value='0' id='varient_count'/>

		<!-- input for coupon !-->

		<input type="hidden" id="coupon_charges">

		<input type="hidden" id="coupon_discount_amount">

		<input type="hidden" id="coupon_discount" name="coupon_discount">

		<input type="hidden" id="coupon_min_value">

		<input type="hidden" id="coupon_max_value">

		<input type="hidden" id="coupon_type">

		<input type="hidden" id="system_otp"/>

		<input type="hidden"  value='0' id="otp_count"/>

		<!-- end for coupon !-->

			<div class="" style="display:none;" id="total_cart_amount_label_show">  

			<div style="grid-template-columns:.2fr 2fr;grid-column-gap: 10px;vertical-align: middle;align-content:center;font-weight: bold;font-size: 15px;">

				<?php $cart_label="Total Cart Amount"; ?>

			   

				<?php  echo ucfirst(strtolower($cart_label)); ?>: Rm <span id="total_cart_value"><?php echo number_format($merchant_detail['order_extra_charge'],2); ?></span>

			</div>

			

			

		</div>

	 <div class="" style="display:none;" id="delivery_label">  

			<div style="grid-template-columns:.2fr 2fr;grid-column-gap: 10px;vertical-align: middle;align-content:center;font-weight: bold;font-size: 15px;margin-top:1%;">

				<?php $deliver_charges_label="Delivery Charge"; ?>

			   

				<?php  echo ucfirst(strtolower($deliver_charges_label)); ?>: Rm <span id="order_extra_label"><?php echo number_format($merchant_detail['order_extra_charge'],2); ?></span>

			</div>

			  <input type="hidden" name="order_extra_charge" id="order_extra_charge" />

			   <input type="hidden" name="pickup_type" id="pickup_type" value="takein"/>

			
		</div>   

		 

			<div style="display:none;" class="membership_discount_label">  

			<div style="grid-template-columns:.2fr 2fr;grid-column-gap: 10px;vertical-align: middle;align-content:center;font-weight: bold;font-size: 15px;">



			   

				<?php  echo ucfirst(strtolower("Membership Discount")); ?>: Rm <span class="membership_discount_value"><?php echo number_format($merchant_detail['order_extra_charge'],2); ?></span>

			</div>

			

			

		</div>

		<div style="display:none;" class="coupon_discount_amount_label">  

				<div style="grid-template-columns:.2fr 2fr;grid-column-gap: 10px;vertical-align: middle;align-content:center;font-weight: bold;font-size: 15px;">



			   

				<?php  echo ucfirst(strtolower("Coupon Discount")); ?>: Rm <span class="coupon_discount_amount_value"></span>

				</div>

			</div>

			<div  style="display:none;" class="sst_amount_label">  

				<div style="grid-template-columns:.2fr 2fr;grid-column-gap: 10px;vertical-align: middle;align-content:center;font-weight: bold;font-size: 15px;color:black;">

					

				   

					<?php  echo "Service fee: "?><?php echo $merchant_detail['sst_rate']." % "; ?>: Rm <span class="sst_amount_value"></span>

				</div>

			</div>  

			<div  style="display:none;" class="delivery_tax_amount_label">  

				<div style="grid-template-columns:.2fr 2fr;grid-column-gap: 10px;vertical-align: middle;align-content:center;font-weight: bold;font-size: 15px;color:black;">

					

				   

					<?php  echo "Delivery Service tax "?><?php echo $merchant_detail['delivery_rate']." % "; ?>: Rm <span class="delivery_tax_amount_value"></span>
					
				</div>

			</div> 

			<div  style="display:none;color:Red;" class="final_amount_label">  

				<div style="grid-template-columns:.2fr 2fr;grid-column-gap: 10px;vertical-align: middle;align-content:center;font-weight: bold;font-size: 15px;">

					

				   

					<?php  echo ucfirst(strtolower("Payable Amount")); ?>: Rm <span class="final_amount_value"><?php echo number_format($merchant_detail['order_extra_charge'],2); ?></span>

				</div>

			</div>

			



	</div>

 

  

    <div class="container">

    <div class="row">

	    <div class="col-xs-4" style="margin-left:2%;">

		<input value='0' type="hidden" id="total_rebate_amount" name="total_rebate_amount"/>

		<input type="hidden" value='0' id="total_cart_amount" name="total_cart_amount"/>

		<input type="hidden" value='0' id="delivery_cart_amount" name="delivery_cart_amount"/>

		<input type="hidden" value='0' id="final_cart_amount" name="final_cart_amount"/>

		<input type="hidden" value='0' id="rem_amount" name="rem_amount"/>

		<input type="hidden" value='0' id="payable_amount" name="payable_amount"/>

		<input type="hidden" id="wallet_selected" name="wallet_selected" value="n">

        <input type="hidden" id="selected_wallet" name="selected_wallet" value="">

         <input type="hidden" id="selected_wallet_bal" name="selected_wallet_bal" value="">

		<input type="hidden" value='<?php echo $login_user_id;?>' name="login_user_id" id="login_user_id"/>

		<input type="hidden" value='<?php echo $login_user_id;?>' name="login_for_wallet_id" id="login_for_wallet_id"/>

			<?php  if($_SESSION['block_pay']!=="y"){?>

		   <input type="submit" style="width:100% !important;border: 1px black solid;" class="btn btn-block btn-primary submit_button" name="cashpayment"  id="confm"  value="<?php echo $language["confirm_order"];?>">

		   <?php } ?>

		</div>     

        <div class="col-xs-4 online_label" style="margin-left:1%;">

		<?php if($discounted_product || $special_coin_name){ ?>

            <input type="submit"  style="width:100% !important;border: 1px black solid;" class="btn btn-block btn-primary submit_button online_pay" name="cashpayment" 

			value='<?php if($login_user_id){  echo $language['confirm_wallet_with_login'];} else {echo $language['confirm_wallet'];} if($_SESSION['login']){ if($special_coin_name){ echo "(".$special_coin_name." $".number_format($special_bal,2).")";} else {if($user_koo_coin){ echo number_format($user_koo_coin,2);}}} ?>'/>   

        

		<?php } else { ?>   

		 

		<?php } ?>   

        </div>
         
		<?php if($merchant_detail['paypal_enable']=="y"){ ?>

       <div class="col-xs-3" style="margin-left:1%;">

	   
    
	   <input type="image" class="paypal_pay" value="submit" src="<?php echo $site_url;?>/images/paypaln.png" alt="submit Button" onmouseover="this.src='<?php echo $site_url;?>/images/paypaln.png'" style="max-width: 250px;max-height: 250px;">

		

		

	   </div>

		<?php } if($merchant_detail['internet_banking']){ ?>
		 <div class="col-xs-3" style="margin-left:1%;">
			<input type="submit"  style="width:100% !important;border: 1px black solid;" class="btn btn-block btn-primary submit_button internet_banking" name="internet_banking" value="Internet Banking(free)"/>   
		</div>
		<?php } if($voice_recognition){ ?>
		<div class="col-xs-4">
          <div class="microphne-sec" style="width: 50px;margin-left: 10px;cursor: pointer;" id="m_click">
            <img src="images/Microphone.png" class="img-responsive microphone_click" id="microphone_click">
          </div>
        </div>
		<?php  }?>

        <div class="container">
      <div class="row">
        <div class="col-md-12" style="padding: 10px 0px;">
          <audio id="player1" controls="controls">
            <source src="<?php echo $path; ?>" type="audio/wav">
            </audio>
          </div>
        </div>
      </div>

    

    </div>

	<div class="row">

	   

	</div>

</div>

    

    

         <input type="hidden" id="id" name="m_id" value="<?php echo $id;?>">

         <input type="hidden" id="agent_code" name="agent_code">

        <input type="hidden" name="options" value="" />  

        <input type="hidden" name="price_extra" value="" />  

        <input type="hidden" id="myr_input_bal" name="myr_input_bal" value="<?php echo $urecord['balance_myr']; ?>" />  

        <input type="hidden" id="usd_input_bal" name="usd_input_bal" value="<?php echo $urecord['balance_usd']; ?>" />  

        <input type="hidden" id="inr_input_bal" name="inr_input_bal" value="<?php echo $balance_inr; ?>" />  

        <input type="hidden" id="special_input_bal" name="special_input_bal" value="<?php echo $special_bal; ?>" />  

    

       

       

        </div>

        </div>

            <a href="#cartsection"><img src ="images/cartnew.png" style="width:75px;height:75px;position: fixed;right: 10px;bottom: 70px;z-index:999;"></a>

        </form>

		<form id="paypal_form" action="<?php echo $paypalUrl; ?>" method="post">

								<div class="panel price panel-red" style="padding:50px 5px;">

									<input type="hidden" name="business" value="<?php echo $paypalId; ?>">

									<input type="hidden" name="cmd" value="_xclick">

									<input type="hidden" name="item_order_id">

									<input type="hidden" name="item_name" value="koofamilies Order">

									<input type="hidden" name="item_number" value="1">

									<input type="hidden" name="no_shipping" value="1">

									<input type="hidden" name="currency_code" value="MYR">

									<input type="hidden" name="cancel_return" value="<?php echo $paypal_cancel_url."&sid=".$merchant_detail['mobile_number']; ?>">

									<input type="hidden" name="return" value="<?php echo $paypal_success_url; ?>">

									

									

									<input style="display:none;" type="number" class="form-control"  id="paypal_amount" name="amount" placeholder="Amount (in MYR)">

									<br><br>

									

								</div>   

		</form>

        <?php 

        // ---------------------------

        // Start of DrakkoFire's code 

        // Remark Project

        // ---------------------------

        ?>

      <?php } else { ?>

             <h5 class="favorite_name" style="display: inline-blick;margin-left:1%;">We are temporary closed for online order now. Please try later. Sorry for any inconvenient caused !</h5>

           

            <?php } ?>

        <div id="remarks_area" class="modal fade">

          <div class="modal-dialog">

              <div class="modal-content">

                  <div class="modal-header">

                      <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> -->

                      <h4 class="modal-title"><?php echo $language['remarks']; ?></h4>

                  </div>

                  <div class="modal-body">

                    <?php 

                      $ingredients = json_decode(mysqli_fetch_row(mysqli_query($conn,"SELECT preset_words FROM users WHERE id='$id'"))[0]);

                      foreach ($ingredients as $ingredient) {

                        if(!empty($ingredient)){

                          // $ingName = (sizeof(explode("[", $ingredient)) > 1) ? explode("[", $ingredient)[0] : $ingredient;

                          $ingName = $ingredient->name;

                          $ingPrice = ($ingredient->price == 0) ? null : $ingredient->price;

                          // $ingPrice = (!empty(explode("]",explode("[", $ingredient)[1])[0])) ? explode("]",explode("[", $ingredient)[1])[0] : null;

                          ?>

                           <div style="margin: 10px 8px;" class="btn-group" data-toggle="buttons" data-subcategory='<?php echo $ingredient->subcategory; ?>'>

                              <label class="btn btn-secondary">

                                <input type="checkbox" name="ingredient" value="<?php echo $ingName; ?>" autocomplete="off"> 

                                <?php

                                    echo ucfirst(str_replace("_", " ",$ingName)); 

                                ?>

                              </label>

                              <?php  if(!is_null($ingPrice)){ ?><div class='extra-price-ingredient'><?php echo number_format($ingPrice,2,".",""); ?></div><?php } ?>

                            </div>

                            <?php

                        }

                      }

                    ?>

                    <input type="hidden" name="remark" id="remark_input" class="form-control" style="margin: 10px 0" placeholder="Write here your remarks"/>

                    <div id="small_text" style="position:relative;margin-top:2em;width:100%;">

                      <small style="position: absolute;bottom: 2px;left: 5px;font-weight: bold;color:red;"><?php echo $language['note_remarks']; ?></small>

                    </div>

                  </div>

                  <div class="modal-footer" style="position: relative;">

                    <button type="button" id="reset_remark" class="btn btn-danger" data-dismiss="modal"><?php echo $language['cancel']; ?></button>

                    <button type="button" class="btn btn-success save_close" data-dismiss="modal">

                    <?php echo $language['save_and']; ?>

                    <p class="text_add_cart" style="width: 20px; height: 20px; font-size: 12px;padding: 4px 0 0 0;">

                      <i class="fa fa-plus"></i>

                    </p> 

                    </button>

          <?php if($merchant_detail['mobile_number']!="60172669613"){ ?>

                    <button type="button" class="btn btn-default manual_input"><?php echo $language['manual_input']; ?></button>

          <?php } ?>

                  </div>

              </div>

          </div>

        </div>

        <?php 

        // ---------------------------

        // Remark project

        // End of DrakkoFire's code 

        // ---------------------------

        ?>

</main>

<!-- /.widget-body badge -->

</div>

    <!-- /.widget-bg -->

<?php } ?>

    <!-- /.content-wrapper -->



    <?php include("includes1/commonfooter.php"); ?>
	 <script type="text/javascript" src="js/sweetalert.min.js"></script>
	
	 <script type="text/javascript" src="js/jquery.lazy.min.js"></script>
	   

 <?php include("modellist.php"); ?>

<?php 

$mid = $_SESSION['merchant_id'];

$get_workingHr = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SQL_NO_CACHE  * FROM set_working_hr WHERE merchant_id='$mid' "));

if(!empty($get_workingHr['start_time']) && !empty($get_workingHr['end_time'])){

$stime =    $get_workingHr['start_time'];

$show_time = date("g:i a", strtotime($get_workingHr['start_time']));

$etime =    $get_workingHr['end_time'];

$end_time = date("g:i a", strtotime($get_workingHr['end_time']));

$sday =    $get_workingHr['start_day'];

$eday =   $get_workingHr['end_day'];

}else{

$stime =    '';

  $etime =   ''; 

  $sday =   ''; 

  $eday =   ''; 

}

?> 

<input type='hidden' name="shop_close_time" id="shop_close_time" value="n">

<input type='hidden' name="stime" id="stime" value="<?php echo $stime; ?>">

<input type='hidden' name="stime" id="show_time" value="<?php echo $show_time; ?>">

<input type='hidden' name="etime" id="etime" value="<?php echo $etime; ?>">

<input type='hidden' name="end_time" id="end_time" value="<?php echo $end_time; ?>">

<input type='hidden' name="sday" id="sday" value="<?php echo $sday; ?>">

<input type='hidden' name="eday" id="eday" value="<?php echo $eday; ?>">

<input type='hidden' name="ctime" id="ctime" value="<?php echo date("H:i"); ?>">

<div class="modal fade" id="voice_recognition" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"></h4>
      </div>
      <form class="recordVoice" role="form" method="post" enctype="multipart/form-data" id="custom_audio_form">
        <input style="display: none;" value='<?php echo $_SESSION['login_user_role']; ?>' id="login_user_role" />
        <?php if (!empty($_SESSION['login_user_role']) && $_SESSION['login_user_role'] == 1) { ?>
          <input style="display: none;" value='<?php echo $_SESSION['user_id']; ?>' id="user_id" />
          <input style="display: none;" value='<?php echo $_SESSION['merchant_id']; ?>' id="m_id" />
        <?php } ?>
        <?php if (!empty($_SESSION['login_user_role']) && $_SESSION['login_user_role'] == 2) { ?>
          <input style="display: none;" value='<?php echo $_SESSION['merchant_id']; ?>' id="m_id" />
        <?php } ?>
        <?php if (!isset($_SESSION['login_user_role']) && !empty($_SESSION['mm_id']) && !empty($_SESSION['merchant_id'])) { ?>
          <input style="display: none;" value='<?php echo $_SESSION['merchant_id']; ?>' id="m_id" />
        <?php } ?>
        <div class="modal-body">
            <div class="form-group">
              <ul class="list-group" id="modal_list"></ul>
            </div>
            <div class="form-group">
               <div class="recordTime">
                <span id="minutes">00</span>:<span id="seconds">00</span>
            </div>
            <input type="hidden" id="UserID" value="<?php echo $_SESSION['login']; ?>">
            <div id="controls">
              <button type="button" id="recordButton" class="pbtn">Record</button>
              <button type="button" id="pauseButton" class="pbtn" disabled>Pause</button>
              <button type="button" id="stopButton" class="pbtn" disabled>Stop</button>
            </div>
            <ol id="recordingsList"></ol>
            <div id="audioError"></div>
        </div>
        <span style="color:green;" id="UploadMessage"></span>
        <span style="color:red;" id="audioRecordError"></span>
        </div>
      <div class="modal-footer" style="padding-bottom:2px;">
        <button id="uploadFile" type="button" class="btn btn-primary" style="width:50%;margin-bottom: 3%;text-align: center;" ><i class='fa fa-spinner fa-spin ' id="spin"></i> Place Order</button>
      </div>
    </form>
</div>
</div>
</div>
</body>



</html>



  <script>

    // Start of DrakkoFire's code

    $(document).on("click", ".adminDemoVideo img", function(){

      // console.log("triggered");

      $parent = $(this).closest(".element-item");

      $(".element-item").removeClass("selected-item");

      $parent.addClass("selected-item");

      setTimeout(function() {

        $(".fancybox-slide").append("<div class='buttons button-r'><a href='#'></a></div><div class='buttons button-l'><a href='#'></a></div>");

      }, 10);

    });

    $(document).on("click touchstart", ".fancybox-slide .buttons", function(){

      var direction = ($(this).hasClass("button-r")) ? 1 : -1;

      var $element = $(".element-item.selected-item");

      var cat = $element.attr("class").split(" ")[2];

      // console.log(cat);

      var index;

      // Script to know the index of the selected element

      var items_list = direction == 1 ? ".element-item." + cat : $(".element-item." + cat).get().reverse();

      $(items_list).each(function(i){

          if($(this).hasClass("selected-item"))

            index = i;

        });

      $(".fancybox-button--close").click();

      var next_item = (index + 1);

      // console.log("Next: " + next_item);

      var len = $(".element-item." + cat).length;

      // console.log(len);

      if(direction == 1){

        $(".element-item." + cat).each(function(i){

          if(i >= next_item && $(this).find("a.adminDemoVideo").length > 0){

            // console.log(i);

            $(this).find("a.adminDemoVideo").children("img").trigger("click");

            return false;

          }

        });

      }else if(direction == -1){

        $($(".element-item." + cat).get().reverse()).each(function(i) {

          if(i >= next_item && $(this).find("a.adminDemoVideo").length > 0){

            // console.log(i);

            $(this).find("a.adminDemoVideo").children("img").trigger("click");

            return false;

          }

        });

      }

      // console.log(index);

    });

    $(document).on("click touchstart", ".fancybox_place_order", function(){

      $(".element-item.selected-item").find(".text_add_cart").trigger("click");

      $(".fancybox-button--close").click();

    });

    $(document).on("click", ".fancybox-button--close", function(){

      $(".element-item.selected-item").removeClass("selected-item");

    });

    $("#order_place").on("submit", function(){

      $("#agent_code_input").trigger("focusout");

    });

    // Remark

	   var varient_selected = [];

	var already_login='<?php echo $login_user_id; ?>';

	 

   var merchant_mobile=<?php echo $merchant_detail['mobile_number'];?>;

    $("#reset_remark").click(function(){

      $("#remarks_area .btn.btn-secondary.checkbox-checked.active").removeClass("checkbox-checked").removeClass("active");

    });

   $("#remarks_area").on("shown.bs.modal", function(){

      $("body").addClass("noscroll");

    });

    $('#remarks_area').on('hide.bs.modal', function (e) {

        $(this).removeClass("transaction");

     $("body").removeClass("noscroll");

        $("#remark_input").attr("type","hidden").val('');

        $("input[name='ingredients'].selected").removeClass("selected");

        $("a.introduce-remarks.selected").removeClass("selected");

    });

    $('#remarks_area').on('click','.save_close', function (e) {

        var selected = [];

        var extras = [];

        $('div#remarks_area .btn-secondary.checkbox-checked.active').each(function() {

            selected.push($(this).children("input[name='ingredient']").val());

            val_extra = $.trim($(this).siblings(".extra-price-ingredient").html());

            if(val_extra != ''){

              extras.push(val_extra);

            }

        });

        // console.log(extras);

        if($("#remark_input").val() != ''){

          selected.push($("#remark_input").val().split(' ').join('_'));

        }

        var input_extras = 0;

        for (var i = 0; i < extras.length; i++) {

          input_extras += parseFloat(extras[i]);

        }

        var id = $("#remarks_area").data("id");

        // console.log(input_extras);

        // console.log(selected.toString().split("_").join(" "));

        var qty = parseFloat($(".introduce-remarks.selected").parent().parent().find("input[name='qty[]']").val());

		if(isNaN(qty)) {

		var qty = 1;

		}

		// alert(qty);

        var unitPrice = parseFloat($(".introduce-remarks.selected").parent().parent().find("input[name='p_price[]']").val());

        // console.log(unitPrice);

        $(".introduce-remarks.selected").parent().parent().find("input[name='p_extra']").val(input_extras);

        $(".introduce-remarks.selected").parent().parent().find("input[name='p_total[]']").val((input_extras + unitPrice < 0) ? 0 : ((input_extras + unitPrice)*qty).toFixed(2));

        $(".introduce-remarks.selected").siblings("input[name='extra']").val(extras);

        $("input[name='single_ingredients'].selected").siblings("input[name='extra']").val(extras);

        if(!$(".introduce-remarks.selected").parent().hasClass("pop_model")){

          $("a.introduce-remarks.selected").html((selected.toString() == '') ? "Remarks" : selected.toString().split("_").join(" "));

        }else{

          $("#remark_td").html((selected == '') ? "" : selected.toString().split("_").join(" "));

        }

        $(".introduce-remarks.selected").removeClass("selected");

        $("input[name='ingredients'].selected").val('').val(selected).removeClass("selected");

        $("input[name='single_ingredients'].selected").val('').val(selected).removeClass("selected");

        if($("#remarks_area").hasClass("transaction")){

          if($("#remarks_area").hasClass("no-back")){

            $("#pop_cart[data-id='" + id + "']").click();

            $("#pop_cart").removeData("id");

            $("#remarks_area").removeClass("no-back");

          }else{

            $(".text_add_cart[data-id='" + id + "']").click();

          }

          $("#remarks_area").removeData("id");

        }

    });

	 $(".redirect_fav").click(function(e){

		  window.location.href = "index.php";

	 });

	 $(".insufficient_close").click(function(e){

		  $(this).removeClass(" btn-primary").addClass("btn-default");

		  $("#LesaaAmountModel").modal("hide");

	 });

    $(".manual_input").click(function(e){

      if($("#remark_input").attr("type") == "hidden"){

        $("#remark_input").attr("type","text");

      }else if($("#remark_input").attr("type") == "text"){

        $("#remark_input").attr("type","hidden").val('');

      }

      e.preventDefault();

    });

	 $("#paypal_cash").click(function(e){ 

	 

		$.ajax({

             url:"functions.php",

             type:"post",

             data:{method:"paypalcash"},

             dataType:'json',

             success:function(response){

				 var data = JSON.parse(JSON.stringify(response));

					if(data.status==true)

					{

					   window.location.replace("https://www.koofamilies.com/orderlist.php");

					}

					else

					{

						alert(data.msg);  

					}

			 }

		});			 

	 });

   $("#paypal_close").click(function(e){    

      window.location.replace("https://www.koofamilies.com/view_merchant.php");

   });

   $(".close_shop").click(function(e){

      $('#shop_model').modal('hide');

   });

    $("input[type='submit']").click(function(e){

      var remarks = []; 

      var price_extra = [];

      $('#test input[name="ingredients"]').each(function() {

             remarks.push($(this).val());

        });

      $('#test input[name="extra"]').each(function() {

             price_extra.push($(this).val());

        });

      // console.log("Price extra (Array):");

      // console.log(price_extra);

      var result = '';

        for (var i = 0; i <= remarks.length - 1; i++) {

          // console.log(remarks[i]);

          if(i != remarks.length - 1){

            result += remarks[i] + "|";

          }else{

            result += remarks[i];

          }

        }

      var result_price = '';

      for (var i = 0; i <= price_extra.length - 1; i++) {

          // console.log(price_extra[i]);

          if(i != price_extra.length - 1){

            result_price += price_extra[i] + "|";

          }else{

            result_price += price_extra[i];

          }

        }

      $("input[name='options']").val(result);

      $("input[name='price_extra']").val(result_price);

      console.log("result_price");

      console.log(result_price);

    });

    $("body").on("click",".introduce-remarks", function(e){

		  e.preventDefault();

      $(this).addClass("selected");

      if($(this).parent().parent().parent().parent().hasClass("element-item")){

        var id = $(this).siblings("input[name='p_id']").val();

        console.log(id);

        $("#remarks_area").addClass("transaction").attr("data-id", id);

      }

      $("#ProductAdded").modal("hide");

      $(this).parent().parent().find("input[name='ingredients']").addClass("selected");

      $(this).parent().parent().find("input[name='single_ingredients']").addClass("selected");

      var ingredients = $(this).parent().parent().find("input[name='ingredients']").val();

      $("#remarks_area .modal-body .btn-group .btn-secondary").removeClass("checkbox-checked").removeClass("active");

    

    });

    $(".modal-footer").on("click", ".introduce-remarks", function(){

      // alert(3);

	  // alert(3);

       // if($(this).parent().is("#without_varient_footer")){

        

      // } else {

        // var varient_count = $(".removevarient").length;

		

        // if(varient_count < 1)

        // {  

            // $('#varient_error').show(); 

            // return false;

        // }

      // }

      var single_remarks = $(this).parent().parent().find("input[name='single_ingredients']").val();

      var quantity = $(this).closest("form").find("input[name='quatity']").val();

      var name = $(this).siblings("#pop_cart").data("name");

      var code = $(this).siblings("#pop_cart").data("code");

      var id = $(this).siblings("#pop_cart").data("id");

      var p_extra = $(this).siblings("input[name='extra']").val();

      $("#remarks_area").addClass("transaction").attr("data-id", id);

      $(this).siblings("input[name='single_ingredients']").addClass("selected")

      if($(this).parent().is("#without_varient_footer")){

        $("#remarks_area").addClass("no-back");

      }

      $('#ProductModel').modal('hide');

      $(".text_add_cart[data-id='" + id + "']").parent().siblings(".introduce-remarks").click();

      $(".modal_pop").html("<a href='#remarks_area' role='button' class='introduce-remarks btn btn-large btn-primary' data-toggle='modal'>Remarks</a><input type='hidden' name='single_ingredients' value='" + single_remarks + "'/><input type='hidden' name='extra' value='" + p_extra + "'/><span id='pop_cart' data-id='"+id+"' data-code='"+code+"'  data-name='"+name+"' data-quantity='"+quantity+"'>Add to Cart</span>");

      return false;

    })

    // Remark

    //  product varient feature 

   $(".without_varient").on("click", function(){

    console.log("Without varient");

     // $(this).hide();

     $("#product_main").html("");

     $("#product_table").html("");

    var id = $(this).data("id");

    var child_id="child_"+id;

    var product_child_id="product_child_"+id;

    var single_remarks = $(this).parent().parent().find("input[name='single_ingredients']").val();

    var p_extra = $(this).parent().parent().find("input[name='extra']").val();

    if(p_extra == ''){

      p_extra = 0;

    }

    document.getElementById(child_id).classList.remove("fa-plus");

    var code = $(this).data("code");

    var p_price = $(this).data("pr");

	 var rebate = $(this).data("rebate");

    var name = $(this).data("name");

    console.log("Price extra: " + p_extra);

    var extra_price = 0;

    if(p_extra == ''){

      p_extra = 0;

    }else{

      p_extra = p_extra.split(",")

      for(var i = 0; i < p_extra.length; i++){

        extra_price += parseFloat(p_extra[i]);

      }

    }  

    console.log("Extra price" + extra_price);

    // $("#varient_name").html(name);

    // alert(extra_price);   

    var quantity = $(this).closest("form").find("input[name='quatity']").val();

    var p_total = p_price*quantity+extra_price;

    var p_total=p_total.toFixed(2);

    // alert(p_total);

	var qty_lable='<?php echo $language["quantity"]?>';

	var ok_lable='<?php echo $language["ok"]?>';

	var remark_lable='<?php echo $language["remarks"]?>';

	console.log(qty_lable);

	  $("#without_varient_footer").html("<div class='row'><div class='col-md-12'>" + qty_lable + ": <input name='quantity_input' min='1' type='number' class='quatity' value='" + quantity + "' style='width:2.5em;text-align:center' min='0' max='99'/></div></div><a href='#remarks_area' role='button' class='introduce-remarks btn btn-large btn-primary' data-toggle='modal' style='top:unset;bottom:3px;right:100px;border-radius: 5px;'>"+remark_lable+"</a><input type='hidden' name='single_ingredients' value='" + single_remarks + "'/><input type='hidden' name='extra' value='" + extra_price + "'/><span id='pop_cart' data-rebate='"+rebate+"' data-id='"+id+"' data-code='"+code+"'  data-name='"+name+"' data-quantity='"+quantity+"' data-pr=" + p_price + " class='close_pop btn btn-large btn-primary' data-dismiss='modal' style='background:#50D2B7;border:none;'>"+ok_lable+"</span>");

    

   // $("#without_varient_footer").html("<a href='#remarks_area' role='button' class='introduce-remarks btn btn-large btn-primary' data-toggle='modal' style='top:unset;bottom:3px;right:100px;border-radius: 5px;'>Remarks</a><input type='hidden' name='single_ingredients' value='" + single_remarks + "'/><input type='hidden' name='extra' value='" + extra_price + "'/><span id='pop_cart' data-id='"+id+"' data-code='"+code+"'  data-name='"+name+"' data-quantity='"+quantity+"' data-pr=" + p_price + " class='close_pop btn btn-large btn-primary' data-dismiss='modal' style='background:#50D2B7;border:none;'>Ok</span>");

    // $("#test").append("<tr>  <td><button type='button' class='removebutton'>X</button> </td><td>"+name+"</td><td><input style='width:50px;'  onchange='UpdateTotal("+id+" ,"+p_price+")'  type=number name='qty[]' maxlength='3' class='product_qty'  value="+quantity+" id='"+id+"_test_athy'><input type= hidden name='p_id[]' value= "+id+"><input type= hidden name='p_code[]' value= "+code+"><input type='hidden' name='ingredients' value='" + single_remarks + "'/></td><td>"+code+"</td><td><a href='#remarks_area' role='button' class='introduce-remarks btn btn-large btn-primary' data-toggle='modal'>" + ((single_remarks == '') ? "Remarks" : single_remarks) +  "</a><input type='hidden' name='extra' value='"+extra_price+"'></td><td><input style='width:70px;text-align:right;' type='text' name='p_extra' value='" + extra_price.toFixed(2) + "' readonly></td><td><input style='width:70px;' type='text' name='p_price[]' value= "+p_price.toFixed(2)+" readonly></td><td><input type='text' style='width:70px;' name='p_total[]' value= " + p_total + " readonly  id='"+id+"_cat_total'><input type='hidden' name='varient_type[]' value=''></td> </tr>");

    document.getElementById(child_id).classList.add("fa-check");

     document.getElementById(product_child_id).style.backgroundColor = "red";

            // alert('The product added');

    $('#pop_ok').val(id);

    $("#ProductAdded").modal("show");

     document.getElementById(child_id).classList.add("fa-plus");

     document.getElementById(child_id).classList.remove("fa-check");   

     document.getElementById(product_child_id).style.backgroundColor = "red";

             // $(".text_add_cart").show(); 

        // $(".element-item input[name='extra']").val('');

        // $(".text_add_cart input[name='extra']").val('');

        // $("input[name='single_ingredients']").val('');

    

   });

	$("#without_varient_footer").on("change", "input[name='quantity_input']", function(){

      var newQuantity = $(this).val();

      $(this).parent().parent().parent().find("#pop_cart").attr("data-quantity", newQuantity);

   });

   $(".with_varient").on("click", function(){

    // $(this).hide();

	// alert(4);  

    var p_price = $(this).data("pr");

    console.log(p_price);

     // $('#varient_count').val(0);

     $("#product_main").html("");

     $("#product_table").html("");

    var id = $(this).data("id");

    var varient_must = $(this).attr("data_varient_must");

    $('#varient_must').val(varient_must);

    var child_id="child_"+id;

    var product_child_id="product_child_"+id;

    var subproduct_selected = '';

    var single_remarks = $(this).parent().parent().find("input[name='single_ingredients']").val();

    var p_extra = $(this).parent().parent().find("input[name='extra']").val();

	// alert(p_extra);

     document.getElementById(child_id).classList.remove("fa-plus");

     document.getElementById(child_id).classList.add("fa-check");

     document.getElementById(product_child_id).style.backgroundColor = "red";

    var code = $(this).data("code");

    var name = $(this).data("name");

	var rebate = $(this).data("rebate");

    var quantity = $(this).siblings(".quantity").children(".quatity").val();

        // alert(quantity);

    // var p_total = p_price*quantity;

    p_total = parseFloat(p_price).toFixed(2);

    $("#varient_name").html(name);

        $("#p_pop_price").val(p_total);     

         $("#product_table").append("<tr><td> "+name+" </td><td> "+p_total+" </td></tr>");  

       $("#pr_total").html("<b>"+p_total+"</b>");

       $("#remark_td").html((single_remarks == '') ? "" : single_remarks.toString().split("_").join(" "));

      // $("#product_info").html("<p>"+name+": Rm "+p_total+"</p>");

      $(".pop_model").html("<div class='row' style='width:11em'></div><a href='#remarks_area' role='button' class='introduce-remarks btn btn-large btn-primary' data-toggle='modal'>Remarks</a><input type='hidden' name='single_ingredients' value='" + single_remarks + "'/><input type='hidden' name='extra' value='" + p_extra + "'/><span id='pop_cart' data-rebate='"+rebate+"' data-pr=" + p_price + " data-id='"+id+"' data-code='"+code+"'  data-name='"+name+"' data-quantity='"+quantity+"'>Add to Cart</span>");

        

      for(var i = 0; i < subproducts_global.length; i++){

        for(var j = 0; j < subproducts_global[i].length; j++){

          if(subproducts_global[i][j]['product_id'] == id){

            subproduct_selected = subproducts_global[i];

            break;

          }

        }

      }

      // console.log(subproduct_selected);

      var exists_in_subproducts = false;

      for(var i = 0; i < subproduct_selected.length; i++){

        if(subproduct_selected[i]['product_id'] == id){

          exists_in_subproducts = true;

          break;

        }

      }

      if(exists_in_subproducts){

        var content = '';

        for(var i = 0; i < subproduct_selected.length; i++){

          content +="<div  id='prodct_cart_"+subproduct_selected[i]['id']+"' data-name='"+subproduct_selected[i]['name']+"' data-id='"+subproduct_selected[i]['id']+"' data-price='"+subproduct_selected[i]['product_price']+"' class='ingredient product_cart'>";

              content +="<button type='button' class='btn btn-info remove-ingredient' data-name='"+subproduct_selected[i]['name']+"' data-id='"+subproduct_selected[i]['id']+"' data-price='"+subproduct_selected[i]['product_price']+"' aria-label='Close'>";

              content +="<span aria-hidden='true'><i class='fa fa-plus'></i></span>";

              content +="</button><span class='ingredient-name'>"+subproduct_selected[i]['name']+" &nbsp; Price Rm "+subproduct_selected[i]['product_price']+"</span></div>";

          // console.log(content);

          

        }

        $("#product_main").html(content);

        console.log(varient_selected);

        varient_selected.forEach(function(e){

          $("#product_main div").each(function(){

            if($(this).data("name") == e)

              $(this).click();

          })

        });

        varient_selected = [];

        if (showModal) {

          $("#ProductModel").modal("show");

        }else{

          showModal = true;

        }

      }

  });

  $(document).on("click", '.product_cart', function(event) { 

    $('#varient_error').hide();   

     var content = "";

       var id = $(this).data("id");

     var varient_count=$('#varient_count').val();

     varient_count++;

     // alert(varient_count);

     $('#varient_count').val(varient_count);

    var p_price = $(this).data("price");

    var name = $(this).data("name");

    // alert(p_price);

    var p_price=parseFloat(p_price).toFixed(2);

    var p_pop_price=$("#p_pop_price").val();

    var c_id="prodct_cart_"+id;

    // alert(c_id);

    var sum=parseFloat(p_price)+parseFloat(p_pop_price);

    // var sum= parseFloat((p_price).toFixed(2));

    var sum=parseFloat(sum).toFixed(2);

    // alert(sum);

    $("#p_pop_price").val(sum);

    $("#pr_total").html("<b>"+sum+"</b>");

    $("#varient").append("<br/>-"+name+"(Rm "+p_price +")");

    var old_varent=$("#varient_type").html();

    $("#varient_type").html(old_varent+","+id+"");

       

    // var content="<p data-id="+id+" data-pr="+p_price+">"+name +"  : Rm "+p_price +"<button  data-id="+id+" data-name="+name+" data-pr="+p_price+" type='button' class='removevarient'>X</button></p>";

    // content +="<div  id='prodct_cart_"+id+"' data-name='"+name+"' data-id='"+id+"' data-price='"+p_price+"' class='ingredient product_cart'>";

              // content +="<button type='button' class='btn btn-info remove-ingredient' data-name='"+name+"' data-id='"+name+"' data-price='"+p_price+"' aria-label='Close'>";

              // content +="<span aria-hidden='true'>X</span>";

              // content +="</button><span class='ingredient-name'>"+name+" &nbsp; Price Rm "+p_price+"</span></div>";

            

    // alert(content);

     var link = document.getElementById(c_id);

    link.style.display = 'none'; //or

	var show_label=""+name+" (Rm "+p_price+")";

	// alert(show_label);

     // $("#product_info").append(content); 

         $("#product_table").append("<tr><td> &nbsp;&nbsp;<button data-id="+id+" data-name="+name+" data-pr="+p_price+" type='button' class='removevarient'><i class='fa fa-remove'></i></button>-<span show_label='"+show_label+"' class='sub_pro_name'>"+name+"</span></td><td> "+p_price+" </td></tr>");       

     content='';

  });

  $(document).on("click", '.removevarient', function(event) { 

      var varient_count=$('#varient_count').val();

     varient_count--;

     // alert(varient_count);

     $('#varient_count').val(varient_count);

     var id = $(this).data("id");

     var price = $(this).data("pr");

      var price=parseFloat(price).toFixed(2);

     var name = $(this).data("name");

     var c_id="prodct_cart_"+id;

     var p_pop_price=$("#p_pop_price").val();

     var p_pop_price=parseFloat(p_pop_price).toFixed(2);

     var old_varent=$("#varient_type").html();

     var varent_list=$("#varient").html();

     // alert(varent_list);

     var r_key="<br>-"+name+"(Rm "+price +")";

     // alert(r_key);

     var new_varient_list = varent_list.replace(r_key,'');

     var new_vareint = old_varent.replace(id,'');

    $("#varient").html(new_varient_list);

    $("#varient_type").html(new_vareint);

     var link = document.getElementById(c_id);

    link.style.display = 'block'; //or

     var sum=parseFloat(p_pop_price)-parseFloat(price);

      var sum=parseFloat(sum).toFixed(2);

    $("#p_pop_price").val(sum);   

    $("#pr_total").html("<b>"+sum+"</b>");

    jQuery(this).closest('tr').remove();

    

  });

    $(document).on("click", '#pop_cart', function(event) {   

   // alert(3);

   var varient_must=$('#varient_must').val();

    var last_added_id = $(".producttr").length;

   var go_ahead="y";

   if(varient_must=="y")

   {

     var varient_count=$('#varient_count').val(); 

     // if(varient_count>0)

     // {

      

     // }

     // else

     // {  

       // var go_ahead="n";

       // $('#varient_error').show(); 

     // }

	  if($(this).parent().is("#without_varient_footer")){

        // do nothing

      } else {

        var varient_count = $(".removevarient").length;

		// alert(varient_count);

        if(varient_count < 1)

        {  

            $('#varient_error').show(); 

            return false;

        }

      }

   }

   else   

   {

    var go_ahead="y";

   }

   if(go_ahead=="y")

   {

    var select_varient=$('#select_varient').val();

    //var p_price = $(this).data("pr");

    var p_price = $(this).data("pr");

    var single_remarks = $(this).siblings("input[name='single_ingredients']").val();

    var p_extra = $(this).siblings("input[name='extra']").val();

     $("input[name='single_ingredients']").val('');

     $(".element-item input[name='extra']").val('');

      $("#ProductModel").modal("hide");

      var sub_str=$("#varient").html();

	   var sub_count = $(".sub_pro_name").length;

         var f_html='';

      var f_html=$(".sub_pro_name")

  .map(function() {

    return $(this).attr('show_label');    

  })

  .get()  

  .join("</br>-");

	 if(sub_count>0)

      var name=$(this).data("name")+"</br>-"+f_html;

	else

	  var name=$(this).data("name");

      var s_id=$(this).data("id");

      var id=$(this).data("id")+last_added_id;

	  // alert(id);

	  var rebate_per=$(this).data("rebate");

      var child_id="child_"+id;

      var child_s_id="child_"+$(this).data("id");

      var product_child_s_id="product_child_"+$(this).data("id");

      var product_child_id="product_child_"+id;

      var extra_child_id="extra_child_"+id;

      var extra_price = 0;

      if(p_extra == ''){

        p_extra = 0;

      }else{

        p_extra = p_extra.split(",")

        for(var i = 0; i < p_extra.length; i++){

        extra_price += parseFloat(p_extra[i]);

        }

      }

      var p_pop_price = $("#p_pop_price").val();

	  var quantity=$(this).data("quantity");

      var product_price = (p_pop_price == "" || !p_pop_price) ? parseFloat($(this).data("pr")) : parseFloat(p_pop_price);

     var p_total = (product_price + extra_price).toFixed(2) * parseInt(quantity);

	 var p_total=p_total.toFixed(2);

	   var rebate_amount=rebatevalue(p_total,rebate_per);  

      $("#p_pop_price").val("");

	  // alert(rebate_amount);  

      console.log(p_price);

      console.log(p_extra);  

      console.log(p_total);

      

      var code=$(this).data("code");

      document.getElementById(child_s_id).classList.add("fa-plus");

      document.getElementById(child_s_id).classList.remove("fa-check");

      document.getElementById(product_child_s_id).style.backgroundColor = "red";

      var varient_type=$("#varient_type").html();

      // alert(varient_type); 

	  var remark_lable='<?php echo $language["remarks"]?>';

    $("#test").append("<tr class='producttr'>  <td><button type='button' class='removebutton'>X</button> </td><td>"+name+"</td><td><input type='hidden' name='rebate_amount[]' class='rebate_amount' value="+rebate_amount+" id='"+id+"rebate_amount'><input type='hidden' name='rebate_per[]' value="+rebate_per+" id='"+id+"rebate_per'><input style='width:50px;'  onchange='UpdateTotal("+id+","+product_price+")'  type=number name='qty[]' min='1' maxlength='3' class='product_qty quatity'  value="+quantity+" id='"+id+"_test_athy'><input type= hidden name='p_id[]' value= "+s_id+"><input type= hidden name='p_code[]' value= "+code+"><input type='hidden' name='ingredients' value='" + single_remarks + "'/></td><td>"+code+"</td><td><a href='#remarks_area' data-rid='"+id+"' role='button' class='introduce-remarks btn btn-large btn-primary' data-toggle='modal'>" + ((single_remarks == '') ? remark_lable : single_remarks) +  "</a><input type='hidden' id='"+extra_child_id+"' name='extra' value='"+extra_price+"'></td><td><input style='width:70px;text-align:right;' type='text' name='p_extra' id='"+extra_child_id+"' value='" + extra_price.toFixed(2) + "' readonly></td><td><input style='width:70px;' type='text' name='p_price[]' value='"+product_price+"' readonly></td><td><input type='text' style='width:70px;' class='p_total' name='p_total[]' value= "+ p_total + " readonly  id='"+id+"_cat_total'><input type='hidden' name='varient_type[]' value="+varient_type+"></td> </tr>");

     

	  // alert('The product added');

      $("#varient_type").html('');   

      $("#varient").html('');  
	  
			// $('#show_msg_product').html("<?php echo $language['the_product_added']; ?>");  

			 // $('#ProductshowModel').modal('show');
			 // setTimeout(function(){ $("#ProductshowModel").modal("hide"); },500); 

   }    

   else

   {

    $('#varient_error').show();

   }

   totalcart();

  });

  // end product varient 

  var other_product_id = 1;

   $(".oth_pr").on("click", function(){

       $('html,body').animate({

        scrollTop: $("#cartsection").offset().top},

        'slow');  

           

     $("#test").append("<tr>  <td><button type='button' class='removebutton'>X</button> </td><td><input style='width:120px;' type=text  id='other_product_name_"+other_product_id+"' class='other_product_name'><input type='hidden' name='rebate_amount[]' class='rebate_amount'  id='"+other_product_id+"rebate_amount'><input type='hidden' name='p_id[]' id='other_product_id_"+other_product_id+"'></td> <td><input style='width:50px;' onchange='UpdateTotalCart("+other_product_id+")' id='other_qty_"+other_product_id+"' type=number name='qty[]' min='1' class='product_qty quatity' value='1'></td> <td><input class='other_product_code' style='width:70px;' type= text name='p_code[]' id='other_product_code_"+other_product_id+"'><input type='hidden' name='ingredients'/></td><td> <a href='#remarks_area' role='button' class='introduce-remarks btn btn-large btn-primary' data-toggle='modal'>Remarks</a><input type='hidden' name='extra'></td><td><input style='width:70px;text-align:right;' type='text' name='p_extra' value='0' readonly></td><td><input style='width:70px;' id='other_product_price_"+other_product_id+"' type='text' name='p_price[]' readonly></td><td><input type='text' style='width:70px;' class='p_total' name='p_total[]' readonly  id='"+other_product_id+"_cat_total'></td></tr>");

    

	  if(merchant_mobile!="60172669613")

  { 

   var focus_id="other_product_code_"+other_product_id;

  }

  else

  {

     var focus_id="other_product_name_"+other_product_id; 

  } 

    document.getElementById(focus_id).focus(); 

  other_product_id++;

    /* jQuery(".other_product_name").autocomplete({
      source: "auto_complete_product_name.php",
      minLength: 1, 
      select: function(event, ui) {
        var id = $(this).attr('id').split('_')[3];
    var qty_id='other_qty_'+id;
    var qty_no=document.getElementById(qty_id).value;
     var total_cart=qty_no*(ui.item.price);
     // alert(total_cart);
        $("#other_product_id_"+id).val(ui.item.id);
        $("#other_product_code_"+id).val(ui.item.code);
        $("#other_product_price_"+id).val(ui.item.price);
        $("#other_product_remark_"+id).val(ui.item.remark);
        var cart_id=id+"_cat_total";
    document.getElementById(cart_id).value =total_cart;
      }
    });
    jQuery(".other_product_name").keyup(function(e){
      var id = $(this).attr('id').split('_')[3];
      $("#other_product_id_"+id).val($(this).val());
    });
     jQuery(".other_product_code").autocomplete({
      source: "auto_complete_product_code.php",
      minLength: 1, 
      select: function(event, ui) {
        var id = $(this).attr('id').split('_')[3];
    var qty_id='other_qty_'+id;
    var qty_no=document.getElementById(qty_id).value;
     var total_cart=qty_no*(ui.item.price);
     // alert(total_cart);
        $("#other_product_id_"+id).val(ui.item.id);
        $("#other_product_code_"+id).val(ui.item.code);
    $("#other_product_name_"+id).val(ui.item.name);
        $("#other_product_price_"+id).val(ui.item.price);
        $("#other_product_remark_"+id).val(ui.item.remark);
        var cart_id=id+"_cat_total";
    document.getElementById(cart_id).value =total_cart;
      }
    }); */

    // jQuery(".other_product_code").autocomplete({

      // source: "auto_complete_product_code.php",

      // minLength: 1,

      // select: function(event, ui) {

        // if(!isActive(ui.item.active_time)){

          // alert("This item is not active at the moment");

          // $(".other_product_code").val('');

        // }else{

          // if(ui.item.on_stock == 0){

            // alert("This item is not on stock");

          // }else{

            // var id = $(this).attr('id').split('_')[3];

            // var qty_id='other_qty_'+id;

            // var qty_no=document.getElementById(qty_id).value;

            // var total_cart=qty_no*(ui.item.price);

            // console.log(ui);

            // $("#other_product_id_"+id).val(ui.item.id);

            // $("#other_product_code_"+id).val(ui.item.code);

            // $("#other_product_name_"+id).val(ui.item.name);

            // $("#other_product_price_"+id).val(ui.item.price);

            // $("#other_product_remark_"+id).val(ui.item.remark);

            // var cart_id=id+"_cat_total";

            // document.getElementById(cart_id).value =total_cart;

          // }

        // }

      // }

    // });

      jQuery(".other_product_name").autocomplete({

      source: "auto_complete_product_name.php",

      minLength: 1, 

      select: function(event, ui) {

        var id = $(this).attr('id').split('_')[3];

    var qty_id='other_qty_'+id;

    var qty_no=document.getElementById(qty_id).value;

     var total_cart=qty_no*(ui.item.price);

     // alert(total_cart);

		var rebate_per=ui.item.product_discount;

		var rebate_amount=rebatevalue(total_cart,rebate_per);  

		$("#"+id+"rebate_amount").val(rebate_amount);

        $("#other_product_id_"+id).val(ui.item.id);

        $("#other_product_code_"+id).val(ui.item.code);

        $("#other_product_price_"+id).val(ui.item.price);

        $("#other_product_remark_"+id).val(ui.item.remark);

        var cart_id=id+"_cat_total";

    document.getElementById(cart_id).value =total_cart;

	 totalcart();

      },

      change: function(event, ui) {

      var id = $(this).attr('id').split('_')[3];

    var p_name='other_product_name_'+id;

    // alert(p_name);

    if (ui.item == null) {

      var p_name=document.getElementById(p_name).value;

      var res='';

            $.ajax({

                url: 'product_check.php',

               type:'POST',

        dataType : 'json',

         data:{p_name:p_name},

                success: function (res) {

          // var data = JSON.parse(JSON.stringify(data));

          // var o_status=data.status;

                    // console.log(data ? "true" : "false");

          

             if(res){

              var qty_id='other_qty_'+id;

              var qty_no=document.getElementById(qty_id).value;

              var total_cart=qty_no*(res.product_price);

              $("#other_product_id_"+id).val(res.id);

              $("#other_product_code_"+id).val(res.product_type);

              $("#other_product_name_"+id).val(res.product_name);

              $("#other_product_price_"+id).val(res.product_price);

              $("#other_product_remark_"+id).val(res.remark);

              var cart_id=id+"_cat_total";

              document.getElementById(cart_id).value =total_cart;

              

            }else{   

              alert('Select Product Name From  list or Enter Proper Product name');

              $("#other_product_name_"+id).val("");

            }

                    

                }

            });

      

       

    }

  }

    });

    jQuery(".other_product_name").keyup(function(e){

      var id = $(this).attr('id').split('_')[3];

      $("#other_product_id_"+id).val($(this).val());

    });

    jQuery(".other_product_code").autocomplete({

      source: "auto_complete_product_code.php",

      minLength: 1, 

      select: function(event, ui) {

        var id = $(this).attr('id').split('_')[3];

    var qty_id='other_qty_'+id;

    var qty_no=document.getElementById(qty_id).value;

     var total_cart=qty_no*(ui.item.price);

        $("#other_product_id_"+id).val(ui.item.id);

		var rebate_per=ui.item.product_discount;

		var rebate_amount=rebatevalue(total_cart,rebate_per);  

		$("#"+id+"rebate_amount").val(rebate_amount);

       $("#other_product_code_"+id).val(ui.item.code);

    $("#other_product_name_"+id).val(ui.item.name);

        $("#other_product_price_"+id).val(ui.item.price);

        $("#other_product_remark_"+id).val(ui.item.remark);

        var cart_id=id+"_cat_total";

    document.getElementById(cart_id).value =total_cart;

	totalcart();

      },

    change: function(event, ui) {

      var id = $(this).attr('id').split('_')[3];

    var pr_code='other_product_code_'+id;

    if (ui.item == null) {

      var pr_code=document.getElementById(pr_code).value;

      var res='';

            $.ajax({

                url: 'product_check.php',

               type:'POST',

        dataType : 'json',

         data:{pr_code:pr_code},

                success: function (res) {

          // var data = JSON.parse(JSON.stringify(data));

          // var o_status=data.status;

                    // console.log(data ? "true" : "false");

          

             if(res){

              var qty_id='other_qty_'+id;

              var qty_no=document.getElementById(qty_id).value;

              var total_cart=qty_no*(res.product_price);

              $("#other_product_id_"+id).val(res.id);

              $("#other_product_code_"+id).val(res.product_type);

              $("#other_product_name_"+id).val(res.product_name);

              $("#other_product_price_"+id).val(res.product_price);

              $("#other_product_remark_"+id).val(res.remark);

              var cart_id=id+"_cat_total";

              document.getElementById(cart_id).value =total_cart;

			  

              

            }else{   

              alert('Select Code From Product Code list or Enter Proper code');

              $("#other_product_code_"+id).val("");

            }

                    

                }

            });

      

       

    }

  }

    });

  });

  

     jQuery(document).on('click', 'button.removebutton', function () {

         alert("Product has Removed");

         jQuery(this).closest('tr').remove();

		 totalcart();

         return false;

     });

     $("#agent_code_input").on("keyup", function(){

		 var number=$('#mobile_number').val();

		 var agent_code_input=$("#agent_code_input").val();

			if(agent_code_input)

			{

				if(number.length >= 9 && number.length <= 12 && (number[0] == 1 || number[0]==6)){

				$.get("./view_merchant.php", {

				  q: "verifyAgentCode",

				  mobile: number,

				  code: $(this).val()

				}, function(data){

				  if(data == 'true')

				  {

					  $('#agent_error').hide();

					$("#agent_code_input").removeClass("is-invalid").addClass("is-valid");

				  }

				  else

				  {

					$("#agent_code_input").removeClass("is-valid").addClass("is-invalid");

				  }

				});

			 }  

			 else

			  {

				  $('#mobile_number').focus();

				   var s_flag=false;

				   return false;

			  }

			}

     });



     $("#agent_code_input").on("focusout", function(e){

       e.preventDefault();

	    var number=$('#mobile_number').val();

	    var agent_code_input=$("#agent_code_input").val();

		if(agent_code_input)

		{

			 if(number.length >= 9 && number.length <= 12 && (number[0] == 1 || number[0]==6)){

					 $("#agent_code").val($("#agent_code_input").val());

				   $.get("./view_merchant.php", {

					  q: "verifyAgentCode",

					  mobile: number,

					  code: $("#agent_code_input").val()

					}, function(data){



					  if(data == 'true'){

						  $('#agent_error').hide();

						$("#agent_code").val($("#agent_code_input").val());

						$("#agent_code_modal").modal("hide");

					  }else if(data=="max"){

						  $("#agent_code").val('');

						  // $('#agent_error').html('You Can Only introduce 5 agent code');

						  // $('#agent_error').show();

						  

					  }else {

						// $("#agqzent_code").val("");

						console.log(data);

					  }



					});

			  }

			  else

			  {

				  $('#mobile_number').focus();

				   var s_flag=false;

				   return false;

			  }

		}

	



     });

  </script>



<style>

.category_filter{

    margin-bottom: 10px;

  padding: 8px;

}

.sub_category_grid{

    margin-top: 10px;

}

.other_products{

display:none;

}

.text_add_cart{

    background: #003A66;

    width: 120px;

    text-align: center;

    padding: 10px;

    color: #fff;

    text-transform: uppercase;

    font-weight: 600;

    cursor: pointer;

    margin-right: 8px;

    border-radius: 8px;

}

.text_add_cart_without{

    background: #003A66;

    width: 120px;

    text-align: center;

    padding: 10px;

    color: #fff;

    text-transform: uppercase;

    font-weight: 600;

    cursor: pointer;

    margin-right: 8px;

    border-radius: 8px;

}

.comm_prd {

    display: flex;

}

.oth_pr {

    background: #003A66;

    width: 80px;

    text-align: center;

    margin: 10px 10px;

    padding: 10px;

    color: #fff;

    font-size: 18px;

    text-transform: uppercase;

    font-weight: 500;

    cursor: pointer;

    border-radius: 8px;

}

.total_rat_abt {

    font-size: 17px;

    display:flex;

}

.rating_menuss {

   padding: 5px;

    margin-right: 25px;

    padding: 5px;

    background: #00736A;

    width: 120px;

    text-align: center;

    padding: 5px;

    color: #fff!important;

    text-transform: uppercase;

    font-weight: 600;

    cursor: pointer;

    margin-bottom: 10px;

    margin-right: 15px;

    border-radius: 10px;

}

.about_uss {

    padding: 7px!important;

    margin-right: 25px;

    padding: 5px;

    background: #00736A;

    width: 120px;

    text-align: center;

    padding: 5px;

    color: #fff!important;

    text-transform: uppercase;

    font-weight: 600;

    cursor: pointer;

    margin-bottom: 10px;

    margin-right: 15px;

    border-radius: 10px;

}

a.merchant_about {

    color: #fff;

}

a.merchant_ratings {

    color: #fff;

}

p.no_stock_add_to_cart{

  width: 100%;

  padding: 15px;

  text-align: center;

  background-color: #fff;

}

.no_stock_add_to_cart{

  border-radius: 5px;

  color: #f92d2d;

  font-weight: bold;

  box-sizing: border-box;

}

.container_test.out_of_stock{

  position: relative;

}

.container_test.out_of_stock:after{

  content:'';

  position: absolute;

  height: 100%;

  width: 100%;

  z-index: 10;

  top: 0;

  left: 0;

  background-image: url(images/out-of-stock.png);

  background-repeat: no-repeat;

  background-size: 75%;

  background-position: left top;

}

.container_test.not_available{

  position: relative;

}

.container_test.not_available:after{

  content:'';

  position: absolute;

  height: 100%;

  width: 100%;

  z-index: 10;

  left: 0;

  top: 0;

  background-image: url(images/no-available.png);

  background-repeat: no-repeat;

  background-size: 80%;

  background-position: center;

}

</style>

<script>

  var showModal = true;

function toSeconds(date){

  var a = date.split(':'); 

  return parseInt(a[0]) * 3600 + parseInt(a[1]) * 60; 

}

function isActive(date){ // Checks if a product is active by date

  if(date == 1){

    return true;

  }

  var dateObj = JSON.parse(date);

  console.log(dateObj);

  var currentDate = new Date();

  var currentDay = parseInt(currentDate.getDay()) + 1;

  var currentHours = currentDate.getHours() + ":" + currentDate.getMinutes();

  console.log("Current Day: " + currentDay);

  console.log("Current Hours: " + currentHours);

  for(var i = 0; i < dateObj.length; i++){

    var daysOnObj = dateObj[i]['days'].split("-");

    if(daysOnObj.includes(currentDay.toString())){

      var start_seconds = toSeconds(dateObj[i]['start']);

      console.log(start_seconds);

      var end_seconds = toSeconds(dateObj[i]['end']);

      console.log(end_seconds);

      var current_seconds = toSeconds(currentHours);

      console.log(current_seconds);

      if(start_seconds < current_seconds && current_seconds < end_seconds){

        return true;

      }

    }

  }

    return false;

}

$('.make_bigger').click(function() {

  //~ $('.active').not(this).addClass('non_active');

  $('.active').not(this).removeClass('active');

  if ($(this).hasClass('active')) {

    $(this).removeClass('active');

  } else {

    $(this).removeClass('non_active');

    $(this).addClass('active');

  }

});

// init Isotope

var $grid_sub = $('.sub_category_grid').isotope({

    // options

    layoutMode: 'fitRows'

});

var $grid = $('.grid').isotope({

  // options

});

var menu_type='<?php echo $merchant_detail['menu_type'];?>';

if(menu_type==1)

{

var master_filter='.'+'<?php echo $master_cat;?>';

$grid.isotope({ filter:master_filter });

}

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

  var menu_type='<?php echo $merchant_detail['menu_type'];?>';

    

        var filterValue = $(this).attr('data-filter');

        var position_value = $(this).attr('data-position');

    

        $("#without_table tbody").html("");  

    // alert(position_value);

    // alert(menu_type);

       

   
  

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

if($bank_data['custom_msg_time'])

$c_time=$bank_data['custom_msg_time'];

else

$c_time=5;	

$same_order=$_SESSION['same_order'];

$free_trial=$_SESSION['free_trial'];

$verify_mobile=$_SESSION['verify_mobile'];

$today_limit=$_SESSION['today_limit'];

$_SESSION['same_order']='';

$_SESSION['free_trial']='';

$_SESSION['today_limit']='';

if($merchant_detail['shortcut_icon'])

$shortcut_icon=$site_url."/images/shortcut_icon/".$merchant_detail['shortcut_icon'];

if($shortcut_icon=='')

  $shortcut_icon='https://koofamilies.com/img/logo_512x512.png';


  $start_url=$site_url."/view_merchant.php?sid=".$_GET['sid'];

// if($merchant_detail['section_exit'] || $merchant_detail[''])

?>

<script>

$(window).on('load', function(){

	 // alert(4);
       $('.lazy').lazy({
		   
            placeholder: "https://koofamilies.com/img/logo.png"
        });
	 var section_check="<?php echo $merchant_detail['section_exit'] ?>";

	 var table_check="<?php echo $merchant_detail['table_exit'] ?>";

	 var sst_tax="<?php echo $merchant_detail['sst_rate']?>";

	 var delivery_rate="<?php echo $merchant_detail['delivery_rate']?>";
	  var dine_in="<?php echo $dine_in; ?>";
	  var d = new Date();

       var n = d.getDay();
       if(n==0)
	    var n=7;
      // alert(dine_in);
	  if(dine_in=="y")
	  {
		   $('.plastic_remark').hide();
		   $('#delivery_label').hide();
		 $("#divein"). prop("checked", true);
		 $("#takeaway_select"). prop("checked", false);
		  // $(".divert").trigger("click");
		// $('.divert')[0].click(); // Works too!!!
		$('#transfer_name_label').hide();
       
		$('.merchant_select').css("background-color", "#51D2B7");

		$('.merchant_select').css("color", "#555");

		$('.divert').css("background-color", "red");

		$('.divert').css("color", "white");

		  

		  $('#pickup_type').val('divein');

		  $('.name_mer').hide();

		   $("#takeaway_select"). prop("checked",false);

			$("#divein"). prop("checked",true);

			$('#sec_table_show').show();

			$('#section_show').show();

			$('#table_show').show();

			// totalcart();  

	  }
	  
       var starttime   =  $('#stime').val();

       var show_time   =  $('#show_time').val();

       var end_time   =  $('#end_time').val();

       var endttime    =  $('#etime').val();

       var starday     =  $('#sday').val();

       var starday1 = starday;

       var endday      =  $('#eday').val();

       endday1 =endday; 

       var currenttime =  $('#ctime').val();

       

       if(starday=='Monday'){

           starday =1;

       }else if(starday=='Tuesday'){

           starday =2;

       }else if(starday=='Wednesday'){

           starday =3;

       }else if(starday=='Thursday'){

           starday =4;

       }else if(starday=='Friday'){

           starday =5;

       }else if(starday=='Saturday'){

           starday =6;

       }else{

            starday =7;

       }

       if(endday=='monday'){

           endday =1;

       }else if(endday=='Tuesday'){

           endday =2;

       }else if(endday=='Wednesday'){

           endday =3;

       }else if(endday=='Thursday'){

           endday =4;

       }else if(endday=='Friday'){

           endday =5;

       }else if(endday=='Saturday'){

           endday =6;

       }else{

            endday =7;

       }   
	 // alert('Current Date'+n);
	  //  alert('Start Date'+starday);
	 //   alert('End Date'+endday);    
	   
	   // if(n==0)   
		   // var n=7;
		       if((currenttime >starttime && currenttime < endttime) && (starday<=n && endday>=n)){
			}else{

           if(starttime.length=='' && endttime.length==''){

                 // window.location.href = "view_merchant.php";

           }else{

			   // alert('Final Case');

           $('#shop_close_time').val('y');

           $s =  moment(d + starttime +'PM').format('LT');

           $e =  moment(d+ endttime +'PM').format('LT');

           console.log($s);   

           console.log($e);

			// $('.pro_status').hide();

			 // $( "#confm" ).prop( "disabled", true );

			 // $( ".online_pay" ).prop( "disabled", true );   

			 // $( "#confmpayment" ).prop( "disabled", true ); 

             // $("#shop_model_text").html("Shop is Close Now , Our Working Hours are "+starday1+" "+show_time+" to "+endday1+" "+end_time+"")
			 var m="Our working hours is from "+starday1+" "+show_time+" to "+endday1+" "+end_time+". You can still place order during non-working hours"+"</br><span style='color:red;'>Enjoy 5% rebate if you order before  12am for tomorrow's delivery.</span>";
             $("#shop_model_text").html(m);
			 $('#error_label').html(m);

             $('#shop_model').modal('show');
			 setTimeout(function(){ $("#shop_model").modal("hide"); },5000); 

           }   

           

       } 

     var shop_close_time=$('#shop_close_time').val();

     // alert(shop_close_time);

		 

	 	 

	 if(section_check=='1' || table_check=='1')

	 {

		 $('#sec_table_show').show();

		 if(section_check=='1')

		 {

			$('#section_show').show();

		 }

		 else

		 {

			 $('#section_type').removeAttr('required');

		 }

		 

		 if(table_check=='1')

		 {

			$('#table_show').show();

		  }

		  else

		  {

			   $('#table_type').removeAttr('required');

		  }   

	 }

	 else

	 {

		$('#section_type').removeAttr('required'); 

		$('#table_type').removeAttr('required'); 

	 }

	 
   
	 $('.merchant_select').click(function(e) {

		// alert(3);

		$('.divert').css("background-color", "#51D2B7");

		$('.divert').css("color", "#555");

		$('.plastic_remark').show();

		$(this).css("background-color", "red");

		$(this).css("color", "white");

		 $('.name_mer').show();

		 $('#delivery_label').show();

		 $('#pickup_type').val('takein');

		 $("#takeaway_select"). prop("checked", true);

		 $("#divein"). prop("checked",false);

		  if(section_check=='1' || table_check=='1')

		 {

			 $('#sec_table_show').show();

			 if(section_check=='1')

			 {

				$('#section_show').show();

			 }

			 else

			 {

				$('#section_show').hide(); 

			 }

			 if(table_check=='1')

			 {

				$('#table_show').show();

			 }

			  else

			  {

				  $('#table_show').hide(); 

			  }

		 }

		 else

		 {

			$('#sec_table_show').hide(); 

		 }

		 totalcart();

	}); 

	 $(".divert").click(function(e){
	    // alert(3);
		 $('#pickup_type').val('divein');
		totalcart();  
		 $('#transfer_name_label').hide();
		 $('.plastic_remark').hide();

		$('.merchant_select').css("background-color", "#51D2B7");

		$('.merchant_select').css("color", "#555");

		$(this).css("background-color", "red");

		$(this).css("color", "white");

		  $('#delivery_label').hide();

		 

		  $('.name_mer').hide();

		   $("#takeaway_select"). prop("checked",false);

			$("#divein"). prop("checked",true);

			$('#sec_table_show').show();

			$('#section_show').show();

			$('#table_show').show();

			

	  });

	 var custom_msg="<?php echo $custom_msg; ?>";

	 var same_order="<?php echo $same_order; ?>";

	 var free_trial="<?php echo $free_trial; ?>";

	 var today_limit="<?php echo $today_limit; ?>";

	 var verify_mobile="<?php echo $verify_mobile; ?>";

	 var mobile_otp_verify="<?php echo $mobile_otp_verify; ?>";

	 // alert(today_limit);

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

	  "start_url":"<?php echo $start_url; ?>"

	}
	const stringManifest = JSON.stringify(myDynamicManifest);

	const blob = new Blob([stringManifest], {type: 'application/json'});

	const manifestURL = URL.createObjectURL(blob);

	document.querySelector('#my-manifest-placeholder').setAttribute('href', manifestURL);

	
	
	

if ('serviceWorker' in navigator) {
  navigator.serviceWorker.register('/sw_new.js')
  .then(function(registration) {
    console.log('Registration successful, scope is:', registration.scope);
  })
  .catch(function(error) {
    console.log('Service worker registration failed, error:', error);
  });
}
	
	
	if(mobile_otp_verify=="y")

	 {

		var msg="Your Mobile no is Verified,Now Place order";  

		$('#show_msg').html(msg);

		$('#AlerModel').modal('show'); 

		setTimeout(function(){ $("#AlerModel").modal("hide"); },5000); 

	 }

	 if(today_limit=="expire")

	 {

		// var msg="Your distance is "+distance+" km away from "+'<?php echo $merchant_detail['name']; ?>';

		var msg="In a day you can only place 3 order,Try to order With different merchant";  

		$('#show_msg').html(msg);

		$('#AlerModel').modal('show'); 

		setTimeout(function(){ $("#AlerModel").modal("hide"); },5000);

	 }  

	  if(free_trial=="expire")

	 {

		 $('#verifiedmobile').val(verify_mobile);

		$('#free_trial_model').modal('show');

		// setTimeout(function(){ $("#free_trial_model").modal("hide"); },5000);

	 }

	 if(same_order=="y")

	 {

		$("#show_new_label").modal("show");

		setTimeout(function(){ $("#show_new_label").modal("hide"); },5000);

	 }

	 if(custom_msg=="y")

	 {

		  $("#merchant_message").delay(parseInt(<?php echo $c_time;?>) * 1000).queue(function(nxt){

			$(".modal.in").removeClass("in").addClass("fade").hide();

			$(".modal-backdrop").remove();

			$("#merchant_message").modal("hide");

			nxt();

		  });

	 }  

	  // $('#merchant_message').modal('show'); 

	

	window.history.pushState(null, "", window.location.href);        

      window.onpopstate = function() {

          window.history.pushState(null, "", window.location.href);

      };

   $('#test').html();

   var already_login='<?php echo $login_user_id; ?>';

   var p_status='<?php echo $p_status; ?>';

	// var delivery_charges="<?php echo $merchant_detail['order_extra_charge']; ?>";

	var delivery_charges=$('#delivery_charges').val();

	// alert(delivery_charge);  

	 var free_delivery="<?php echo $merchant_detail['free_delivery']; ?>";

  var location_order='<?php echo $location_order; ?>';

  var shop_status=$('#shop_status').val();

      

	  $(".heart.fa").click(function() {

		  if(already_login)

		  {

		  // if(already_login)

			var className = $(this).attr('class');

			var favorite = "0";

			if(className.indexOf("fa-heart-o") > -1){

				favorite = "1";

			}

			var data = {method: "favorite", favorite: favorite, merchant_id:'<?php echo $merchant_detail['id']; ?>', user_id:already_login};

			$.ajax({

				url:"functions.php",

				type:"post",

				data:data,    

				success:function(data){

					console.log(data);

				

				}

			});

			$(this).toggleClass("fa-heart fa-heart-o");

		  }

		  else

		  {

			  $('#show_msg').html("Login as member to use that feature");

			 $('#AlerModel').modal('show');

			  // alert('Login as member to use that feature');

		  }

		});

	

if(p_status=="paypalcancel")

{

	$('#paypal_model').modal('show');

}

  if(location_order==1)

  {

	var shop_close_time=$('#shop_close_time').val();

    if(shop_close_time=="n")

	{

		// $('.pro_status').hide();

		 // $( "#confm" ).prop( "disabled", true );

		 // $( ".online_pay" ).prop( "disabled", true );

		 // $( "#confmpayment" ).prop( "disabled", true );   

	}

  }

  // alert(shop_status);

  if(shop_status==0)

  {

	  // window.location.replace("https://www.koofamilies.com/favorite.php");

    // $('.pro_status').hide();

     // $( "#confm" ).prop( "disabled", true );

     // $( ".online_pay" ).prop( "disabled", true );

     // $( "#confmpayment" ).prop( "disabled", true ); 

    $('#error_label').html("Sorry, we are currently experiencing some internet connection issue. If you want to place any order, please contact our waiter for placing order.");

    $('#shop_model').modal('show');

    // exit;

  }  

  

  $("button.introduce-remarks").prop("disabled", false);

 

      var prev_number = '';

     $(".credentials-container input[name='mobile_number']").on("keyup", function(){

		 // alert(3);

      var prefix = "+60";

      var number = $(this).val();

	  // var already_login='<?php echo $login_user_id; ?>';

	  var already_login=$('#login_user_id').val();

	  // alert(already_login);

      // console.log("Previous number: " + prev_number);

      // console.log("Current number: " + (prefix + number));

      if(prev_number == prefix+number){

        return false;

      }

      prev_number = prefix+number;

    var guest_permission=<?php echo $merchant_detail['guest_permission']; ?>; 

    // alert(guest_permission);

	var guest_permission=0;

	var login_user_id=$('#login_user_id').val();   

	

    if(guest_permission==0)

    {

		// alert(number[0]);

      if(number.length >= 9 && number.length <= 12 && (number[0] == 1 || number[0]==6)){ 

		  // alert(login_user_id);

		 if(!already_login && !login_user_id)   

		{

			var mobile_number=$('#mobile_number').val();

			var merchant_id='<?php echo $id; ?>';

			var special_coin_name='<?php echo $special_coin_name; ?>';   

			if(mobile_number)

			{

				// alert(mobile_number);

				 $.ajax({

					url: 'functions.php',

					type:'POST',

					dataType : 'json',

					data:{mobile_number:mobile_number,method:"usercheck",merchant_id:merchant_id,special_coin_name:special_coin_name},

					success: function (res) {

					var data = JSON.parse(JSON.stringify(res));

					// alert(JSON.stringify(data));

					// alert(data.status);

					  if(data.status==true)

					  {

						 

						   if(plan_label!='')

							{

								var plan_benefit=data.plan_benefit;   

								var plan_label=data.plan_label;  

								$('#membership_discount_input').val(plan_benefit);

								$('#membership_applicable').val('y');

								$('#membership_discount').show();

								$('#membership_discount').html(plan_label);

								$('#membership_discount_label').show();

								$('#membership_discount_label').html(plan_label);

							}  

						$('#newmodel_check').modal('show'); 

						

						// alert(plan_benefit);

						$('#membership_discount_input').val(plan_benefit);

						var membership_discount_input=$('#membership_discount_input').val();

						$('.forgot-form').hide();

						

						    

						if(data.userstatus=="old")

						{

							var record=data.data;

							$('#login_user_id').val(record.id);   

							$('#myr_bal').html(record.balance_myr);

							$('#usd_bal').html(record.balance_usd);

							$('#inr_bal').html(record.balance_inr);

							$('#special_bal').html(record.balance_special);

							$('#myr_input_bal').val(record.balance_myr);

							$('#usd_input_bal').val(record.balance_usd);

							$('#inr_input_bal').val(record.balance_inr);

							$('#special_input_bal').val(record.balance_special);  

							if(record.otp_verified=="n")

							{

								

								$('#show_msg_new').html('<span style="font-size:20px;">😀</span>Please Verfiy your otp to claim for your '+ membership_discount_input+' membership discount');

							    // $('#show_msg').html('Verify your otp to get membership discount of ');	

								$('.otp_form').show();

								var otp_count=$("#otp_count").val();

								var usermobile=record.mobile_number;

								var data = {usermobile:usermobile, method: "sendotp",merchant_id:merchant_id};  

								$.ajax({

										url :'functions.php',

										type:'POST',

										dataType : 'json',

										data:data,   

										success:function(response){

										  var data = JSON.parse(JSON.stringify(response));

										  if(data.status==true)

										  {

											  otp_count++;

											  // alert(data.otp);

											  $("#otp_count").val(otp_count);

											  $("#system_otp").val(data.otp);

											 $(".otp_form").show(); 

										  }

										  

										}		  

								  });

							}

							else

							{

								if(membership_discount_input)

								$('#show_msg_new').html('<span style="font-size:20px;">😀</span> Please login in order to claim '+ membership_discount_input+' membership discount');

								else

								$('#show_msg_new').html('<span style="font-size:20px;">😀</span> Please login in order to Complete Order');	

								$('.login_passwd_field').show();

								$('.join_now').show();

								$('.login_ajax_new').show();

								$('.login_footer').show();

							   	

							}

							// alert(record.otp_verified);   

						}

						totalcart();

					  }

						

						

					}

				});

			}

			// check mobile no for membership feature    

			// alert('mobile fill');

		}

         $('#mobile_error').hide();

       $("input.submit_button").prop("disabled",false);

       $("input.submit_payment_button").prop("disabled",false);

       $("input[name='mobile_number']").addClass("is-valid").removeClass("is-invalid");

       

      }else{

       $("input.submit_button").prop("disabled",true);

       $("input.submit_payment_button").prop("disabled",true);

         $("input[name='mobile_number']").removeClass("is-valid").addClass("is-invalid");

       $('#mobile_error').show();

       

      }

    }

    });

      $('#partitioned').on('keyup', function(){ // consider `myInput` is class...



  var user_input = $(this).val();

  // alert(user_input);

  var page_otp=$("#system_otp").val();

   var usermobile="60"+$("#mobile_number").val();

   // alert(user_input);

   // alert(page_otp);

  if(user_input.length % 4 == 0){

	  if(user_input==page_otp)

	  {

		  var data = {usermobile:usermobile,method:"otp_submit"};

		   

		$.ajax({

			  

			  url :'login.php',

			  type:'POST',

			  dataType : 'json',

			  data:data,

			  success:function(response){

				  if(response==1)

				  {

					  // location.reload();

					  var login_user_id=$('#login_user_id').val();

					  $('#login_for_wallet_id').val(login_user_id);

					  $('#newmodel_check').modal('hide'); 

					  $('#PasswordModel').modal('show');

				  }

				  else

				  {

					 $('#register_error').html('Something Went wrong to validate otp,try again');

					  $("#login_error").show();

				  }

				  

				}		  

		  });

		

	  }

	  else

	  {

		  $('.otp_error').show();

	  }

   

  }

});

  // $('#confm').click(function(){

       // $('#order_place').attr('action', 'order_cash.php');

       

         

        

    // });

    $('#confmpayment').click(function(){

       $('#order_place').attr('action', 'order_place.php');

       

    });

    $("body").on("click","#login_passwd", function(e){

      var phone_num = $(this).parent().parent().find("input[name='mobile_number']").val();

      $("#login_phone_number").val("+60" + phone_num);

      e.preventDefault();

    });

    var latitude = 0;

    var longitude = 0;

    

  

   var searchBox = new google.maps.places.SearchBox(document.getElementById('mapSearch'));

            searchBox.addListener('places_changed', function() {

                var places = searchBox.getPlaces();

                if (places.length == 0) {

                    return;

                }

                places.forEach(function(place) {

                    var latitude = place.geometry.location.lat();

                    var longitude = place.geometry.location.lng();

                    $(".latitude").val(latitude);

                    $(".longitude").val(longitude);

                    var address = $("#mapSearch").val();
	                if(latitude!='' && longitude!='')

						{

							if(location_order=='1')
								calculatedisatace(latitude,longitude);

						}

                });

            });

    

    $("button[name='login_ajax']").on("click",function(e){

      var phone_num = $("#login_phone_number").val();

      var passwd = $("#login_password").val();

      var passwd_length_valid = (passwd.length > 2) ? true : false;

      $(this).prop("disabled",true);

      if(passwd_length_valid){

        $.post("./login.php", {

          mobile_phone: phone_num,

          password: passwd

        }, function(data,result){

          

          console.log("Data:");

          console.log(data);

          var status_code = (data == "logged-in") ? 4 : (data == "acc-locked") ? 3 : (data == "reg_pending") ? 2 : (data == 1) ? 1 : 0 ;

          console.log(status_code);

          if(status_code == 0){

            $("#login_password").rFemoveClass("is-valid").addClass("is-invalid");

            $(".third_problem.logged-in.success_login,.reg_pending,.acc_blocked").hide();

            $(".wrong_login").show();

          }else if(status_code == 1){

            $("#login_password").removeClass("is-invalid").addClass("is-valid");

            $(".third_problem.logged-in.wrong_login,.reg_pending,.acc_blocked").hide();

            $(".success_login").show();

            $(".credentials-container").remove();

            $("form[action='guest_user.php']").attr("action","order_place.php");

          }else if(status_code == 2){

            $("#login_password").removeClass("is-valid").addClass("is-invalid");

            $(".third_problem.logged-in.success_login,.acc_blocked,.wrong_login").hide();

            $(".reg_pending").show();

          }else if(status_code == 3){

            $("#login_password").removeClass("is-valid").addClass("is-invalid");

            $(".third_problem.logged-in.success_login,.reg_pending,.wrong_login").hide();

            $(".acc_blocked").show();

          }else if(status_code == 4){

            $("#login_password").removeClass("is-valid").addClass("is-invalid");

            $(".third_problem.acc_blocked.success_login,.reg_pending,.wrong_login").hide();

            $(".logged-in").show();

          }else{

            $("#login_password").removeClass("is-valid").addClass("is-invalid");

            $(".logged-in.acc_blocked.success_login,.reg_pending,.wrong_login").hide();

            $(".third_problem").show();

          }

        })

      }else{

        if(passwd.length == 0){

          alert("You have to enter a password");

        }else{

          alert("The password is too short");

        }

      }

      e.preventDefault();

    })

    //$('.master_category_filter:first-child').trigger('click');

    $('.sub_category_grid .category_filter:first-child button').click();

    //$('.filter-button-group .category_filter:first-child').trigger('click');



    imagesLoaded( document.querySelector('.grid'), function( instance ) {

      setTimeout(() => {

        $('.sub_category_grid .category_filter:first-child button').click();

      }, 50);

    });



  var menu_type='<?php echo $merchant_detail['menu_type'];?>';

  if(menu_type==2){

        $('.master_category_filter:first-child').trigger('click');

    }

    $(".category_filter").click(function(e){

    var menu_type='<?php echo $merchant_detail['menu_type'];?>';

    

        var filterValue = $(this).attr('data-filter');

    

        $("#without_table tbody").html("");  

    

       

    if(menu_type==1)

    {

      var data = {method:"getImageProduct", id: <?php echo $id;?>, category:''};

        $(".new_grid").html("");

      $.ajax({

             url:"functions.php",

             type:"post",

             data:data,

             dataType:'json',

             success:function(result){

              console.log(result);

                var html="";

        

                for(var i = 0; i < result.length; i++){

                    html += "<div class='well col-md-4 element-item Cham鸳鸯'>";

          html += " <form action='product_view.php' method='post' class='set_calss input-has-value' data-id='"+result[i]['id']+"' data-code='C005' data-pr='39' style='background: #51d2b7;    padding: 12px;    border: 1px solid #e3e3e3;    border-radius: 4px;    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05); box-shadow: inset 0 1px 1px rgba(0,0,0,.05);'>";

          if(isActive(result[i]['active_time'])){

            if(result[i]['on_stock'] == 1){

              html += " <div class='container_test'>";      

            }else{

              html += " <div class='container_test out_of_stock'>";      

            }

          }else{

              html += " <div class='container_test not_available'>";      

          }

          html +="<img src='<?php echo $site_url; ?>/images/product_images/"+result[i]['image']+"' class='make_bigger' width='100%' height='150px'>";

                    // html += "<td>"+result[i]['product_name']+"</td>";

                    // html += "<td id='text_without' class='text_add_cart_without' data-id='"+result[i]['id']+"' data-code='"+result[i]['type']+"' data-pr='"+result[i]['price']+"' data-name='"+result[i]['product_name']+"'>Add to cart</td>";

                    // html += "<td>"+result[i]['price']+"</td>";

                    // html += "<td>"+result[i]['remark']+"</td>";

                    // html += "<td>"+result[i]['type']+"</td>";

                    html += "</div>";

                    html += "<input type='hidden' id='id' name='m_id' value='"+result[i]['user_id']+"'>";

                    html += "<input type='hidden' id='id' name='p_id' value='"+result[i]['id']+"'>";

                    html += "<p class='pro_name'>"+result[i]['product_name']+"</p>";

          html += "<p class='mBt10'></p>";

          html += "<p class='mBt10'></p>Price : Rm"+result[i]['price']+"<p></p>";

                    html += "<div class='common_quant'>";

          //html += "<p class='text_add_cart' data-id='"+result[i]['id']+"' data-code='C005' data-pr='39' data-name='Carlsberg Smooth (B)X3'>Add to Cart</p>";

          if(isActive(result[i]['active_time'])){

            if(result[i]['on_stock'] == 1){

              html += "<p class='text_add_cart' data-id='"+result[i]['id']+"' data-code='"+result[i]['type']+"' data-pr='"+result[i]['price']+"' data-name='"+result[i]['product_name']+"'>Add to Cart</p>";

              html += "<div style='display:grid;grid-template-columns:.2fr 1fr;align-content:center;vertical-align:center;' class='input-has-value'>";

              html += " <label>X</label><input type='number' value='1' class='quatity' name='quatity' style='height:1.5em'>";

            }else{

              html += "<p class='no_stock_add_to_cart'>Out of stock</p>";

            }

          }else{

              html += "<p class='no_stock_add_to_cart'>This product is not available in this moment</p>";

          }

       //    if(result[i]['on_stock'] == 1){

            // html += "<p class='text_add_cart' data-id='"+result[i]['id']+"' data-code='"+result[i]['type']+"' data-pr='"+result[i]['price']+"' data-name='"+result[i]['product_name']+"'>Add to Cart</p>";

       //      html += "<div style='display:grid;grid-template-columns:.2fr 1fr;align-content:center;vertical-align:center;' class='input-has-value'>";

            // html += " <label>X</label><input type='number' value='1' class='quatity' name='quatity' style='height:1.5em'>";

       //    }else{

       //      html += "<p class='no_stock_add_to_cart'>Out of stock</p>";

       //    }

          html += "<p class='quantity'> </p>";

                    html += "</div>";

                    html += "</div>";

                    html += "</form>";

                    html += "</div>";

                }

                $(".new_grid").html(html);

      $(".text_add_cart").on("click", function(){

        var id = $(this).data("id");

        var code = $(this).data("code");

        var p_price = $(this).data("pr");

        var name = $(this).data("name");

        var quantity = $(this).closest("form").find("input[name='quatity']").val();

        var p_extra = $(this).parent().parent().find("input[name='extra']").val();

        var p_total = p_price*quantity;

        p_total = p_total.toFixed(2);

        var remark_lable='<?php echo $language["remarks"]?>';

        $("#test").append("<tr>  <td><button type='button' class='removebutton'>X</button> </td><td>"+name+"</td><td><input style='width:50px;'  onchange='UpdateTotal("+id+" ,"+p_price+")'  type=number name='qty[]'  min='1' class='product_qty' maxlength='3'  value="+quantity+" id='"+id+"_test_athy'><input type= hidden name='p_id[]' value= "+id+"><input type= hidden name='p_code[]' value= "+code+"><input type='hidden' name='ingredients'/></td><td>"+code+"</td><td><a href='#remarks_area' role='button' class='introduce-remarks btn btn-large btn-primary' data-toggle='modal'>Remarks</a><input type='hidden' name='extra' value='" + p_extra + "'></td><td><input style='width:70px;text-align:right;' type='text' name='p_extra' value='0' readonly></td>  <td><input style='width:70px;' type='text' name='p_price[]' value= "+p_price+" readonly></td><td><input type='text' style='width:70px;' name='p_total[]' value= "+p_total+" readonly  id='"+id+"_cat_total'></td> </tr>");

        alert('The product added');

      });

                

             }

         });

    }

     var data = {method:"getNoneImageProduct", id: <?php echo $id;?>, category: ''};

        $.ajax({

             url:"functions.php",

             type:"post",

             data:data,

             dataType:'json',

             success:function(result){

                var html="";

                for(var i = 0; i < result.length; i++){

                    html += "<tr>";

                    html += "<td>"+(i + 1)+"</td>";

                    html += "<td>"+result[i]['product_name']+"</td>";

                    if(isActive(result[i]['active_time'])){

                      if(result[i]['on_stock'] == 1){

                        html += "<td id='text_without' class='text_add_cart_without' data-id='"+result[i]['id']+"' data-code='"+result[i]['type']+"' data-pr='"+result[i]['price']+"' data-name='"+result[i]['product_name']+"'>Add to cart</td>";

                      }else{

                        html += "<td class='no_stock_add_to_cart'>Out of stock</td>";

                      }

                    }else{

                        html += "<td class='no_stock_add_to_cart'>Not available</td>";

                    }

                    html += "<td>"+result[i]['price']+"</td>";

                    html += "<td>"+result[i]['remark']+"</td>";

                    html += "<td>"+result[i]['type']+"</td>";

                    html += "</tr>";

                }

                $("#without_table tbody").html(html);

                $(".text_add_cart_without").on("click", function(){

    var id = $(this).data("id");

    //~ alert(id);

    var code = $(this).data("code");

    //~ alert(code);

    var p_price = $(this).data("pr");

    //~ alert(p_price);

    var name = $(this).data("name");

    // alert(name);

    var quantity = 1 ;

    //alert(quantity) ;

    if(quantity ==''){

        

        var quantity = 1 ;

    }

    var p_total = p_price *quantity ;

    p_total = p_total.toFixed(2);

	var remark_lable='<?php echo $language["remarks"]?>';

	 $("#test").append("<tr class='producttr'>  <td><button type='button' class='removebutton'>X</button> </td><td>"+name+"</td><td><input type='hidden' name='rebate_amount[]' class='rebate_amount' value="+rebate_amount+" id='"+id+"rebate_amount'><input type='hidden' name='rebate_per[]' value="+rebate_per+" id='"+id+"rebate_per'><input style='width:50px;'  onchange='UpdateTotal("+id+","+product_price+")'  type=number name='qty[]' min='1' maxlength='3' class='product_qty quatity'  value="+quantity+" id='"+id+"_test_athy'><input type= hidden name='p_id[]' value= "+s_id+"><input type= hidden name='p_code[]' value= "+code+"><input type='hidden' name='ingredients' value='" + single_remarks + "'/></td><td>"+code+"</td><td><a href='#remarks_area' data-rid='"+id+"' role='button' class='introduce-remarks btn btn-large btn-primary' data-toggle='modal'>" + ((single_remarks == '') ? remark_lable : single_remarks) +  "</a><input type='hidden' id='"+extra_child_id+"' name='extra' value='"+extra_price+"'></td><td><input style='width:70px;text-align:right;' type='text' name='p_extra' id='"+extra_child_id+"' value='" + extra_price.toFixed(2) + "' readonly></td><td><input style='width:70px;' type='text' name='p_price[]' value='"+product_price+"' readonly></td><td><input type='text' style='width:70px;' class='p_total' name='p_total[]' value= "+ p_total + " readonly  id='"+id+"_cat_total'><input type='hidden' name='varient_type[]' value="+varient_type+"></td> </tr>");

       alert('<?php echo $language["the_product_added"]?>');   

  });

                

             }

         });

    });

	$("#confm").click(function(e){

		 var product_qty=$('.product_qty').val();

		 var table_type=$('#table_type').val();

		 var number=$('#mobile_number').val();

		 

		// alert(table_type);

		 // alert(order_min_charge);

		 var total_rebate=0;

		 var total_amount=0;

		var s_flag=true;

		if ((product_qty == null) || (product_qty=='')){

			// alert('Without Prouct add cant able to go ahead.');

			$('#show_msg').html("<?php echo $language['no_product_added']; ?>");  

			 $('#AlerModel').modal('show');

			

			var s_flag=false;

			return false;  

		  }

		 if(number.length >= 9 && number.length <= 12 && (number[0] == 1 || number[0]==6)){
			$('#mobile_error').hide();
		  }

		  else

		  {
			  $('#mobile_number').focus();
				$('#mobile_error').show();
			   var s_flag=false;

		  }

		  

		  if($('#table_type').prop('required')){

			  // alert('table type');

			  if(table_type=='')

			  {

				  $('#table_type').focus();

				var s_flag=false;

				// return false;

			  }

		  }   

		  // alert(s_flag);

		   if(s_flag)

		   {
				totalcart();  
			    // alert(3);

			    $(".rebate_amount").each(function(){

				   // var total_rebate+= $(this).val();

				   total_rebate += parseFloat($(this).val());

			   });

			   $(".p_total").each(function(){

				   // var total_rebate+= $(this).val();

				   total_amount += parseFloat($(this).val());

			   });

			    var delivery_charges=$('#delivery_charges').val();  

				// alert('Deliver charge '+delivery_charges);

			   if(delivery_charges>0)

				   var order_min_charge="<?php echo $merchant_detail['order_min_charge'];?>";

					else

					var order_min_charge=0;

					

				// alert('Order Min '+order_min_charge);

			    // alert(order_min_charge);

			   if(order_min_charge>0)

			   {

				   

				   if(parseFloat(total_amount)<parseFloat(order_min_charge))

				   {

					   // var msg="Place Min Order for Rm "+order_min_charge;

					   var msg="Minimum order is Rm "+order_min_charge;

					   $('#show_msg').html(msg);

						$('#AlerModel').modal('show');

						return false;

					   // alert('Order Amount greater');

				   }

			   }

			   

			   var total_amount=total_amount.toFixed(2);

			  

			     var total_rebate=total_rebate.toFixed(2);

			   $('#total_rebate_amount').val(total_rebate);

			   $('#total_cart_amount').val(total_amount);

			   $('#total_cart_amount_label').html(total_amount);

				$('input[type=submit]', this).attr('disabled', 'disabled');

				$(this).removeClass(" btn-primary").addClass("btn-default");

				

		   }

	});

  // $('form').submit(function(){

    // $('input[type=submit]', this).attr('disabled', 'disabled');

// });

  // $('#order_place').submit(function () {

    // var product_qty=$('.product_qty').val();

    // var s_flag=true;

    // if ((product_qty == null) || (product_qty=='')){

        // alert('Without Prouct add cant able to go ahead.');

        // var s_flag=false;

        // return false;

      // }

      

      // if(s_flag)

      // $('input[type=submit]', this).attr('disabled', 'disabled');

        // });

	// code for rebeat process by mayank 

	  $(".login_ajax").click(function(){

		   // $(this).removeClass(" btn-primary").addClass("btn-default");

		 // setTimeout(function() {

			

         // $(this).removeClass("btn-default").addClass("btn-primary");

			// }.bind(this), 5000);

		    var usermobile="60"+$("#mobile_number").val();

		    var login_password=$("#login_ajax_password").val();

		    var wallet_selected=$("#wallet_selected").val();

			    

			 $("#login_error").hide();

			if((login_password.length)>5)

			{

				   $(this).removeClass(" btn-primary").addClass("btn-default");

				  // alert(login_password);

					var data = {usermobile:usermobile,login_password:login_password};

					// alert(data);

					$.ajax({

					  

					  url :'login.php',

					  type:'POST',

					  dataType : 'json',

					  data:data,

					  success:function(response){

							var data = JSON.parse(JSON.stringify(response));

						  if(data.status==true)

						  {

							 var login_user_role=data.data.user_roles;

							 var login_user_id=data.data.id;

							 localStorage.setItem('login_live_id',login_user_id);

						     localStorage.setItem('login_live_role_id',login_user_role);

							 $('#login_process').hide();

							 $('#with_wallet').hide(); 

							 $('#without_wallet').hide(); 

							 $('.join_now').hide();

							 walletstep();

						  }

						  else

						  {

							$("#login_ajax_password").val('');

							$("#login_error").html('Your password is incorrect.Please try with correct password again or Reset that with Forgot Password');

							$("#login_error").show();

						  }

						  

						}		  

				  });

		  

			}

			else

			{    $("#login_error").html('Enter Atleaset 6 digit password to make login');

				 $("#login_error").show();

			}

		 

	   });

	    $(".login_ajax_new").click(function(){  

        

		   $(this).removeClass(" btn-primary").addClass("btn-default");

		 setTimeout(function() {

			

         $(this).removeClass("btn-default").addClass("btn-primary");

			}.bind(this), 5000);

		    var usermobile="60"+$("#mobile_number").val();

		    var login_password=$("#login_ajax_password_new").val();

			var membership_applicable='<?php echo $membership_applicable;?>';

			var last_order_merchant_id='<?php echo $last_order_merchant_id;?>';

			var last_order_id='<?php echo $last_order_id;?>';

			 $("#login_error_new").hide();

			if((login_password.length)>5)

			{

				  // alert(login_password);

					var data = {usermobile:usermobile,login_password:login_password,membership_applicable:membership_applicable,merchant_id:last_order_merchant_id,last_order_id:last_order_id};

					

					// alert(data);

					$.ajax({

					  

					  url :'login.php',

					  type:'POST',

					  dataType : 'json',

					  data:data,

					  success:function(response){

							var data = JSON.parse(JSON.stringify(response)); 

						  if(data.status)

						  {

							  // location.reload();

							  var login_user_id=$('#login_user_id').val();

							  // alert(login_user_id);   

							var login_user_role=data.data.user_roles;



						localStorage.setItem('login_live_id',login_user_id);    

						localStorage.setItem('login_live_role_id',login_user_role);



							$('#login_for_wallet_id').val(login_user_id);

							 $('#newmodel_check').modal('hide'); 

							  

						  }

						  else

						  {   

							  $("#login_ajax_password").val('');

							   

							   $("#login_error_new").html('Your password is incorrect.Please try with correct password again or Reset that with Forgot Password');

							  $("#login_error_new").show();

						  }

						  

						}		  

				  });

		  

			}

			else

			{    $("#login_error_new").html('Enter Atleaset 6 digit password to make login');

				 $("#login_error_new").show();

			}

		 

	   });

	    $(".register_ajax").click(function(){    

			// return false;

			var password_created='<?php  echo $password_created;?>';

			// alert(password_created);

			

				   $("#register_error").hide();

					$(this).removeClass(" btn-primary").addClass("btn-default");

				 setTimeout(function() {  

					

				 $(this).removeClass("btn-default").addClass("btn-primary");

					}.bind(this), 5000);

					// var user_id=('#login_user_id').val();

					

					var user_id=$("#login_user_id").val();

					var order_id=$("#order_id").val();

					

					var login_password=$("#register_password").val();  

					var pass_length=login_password.length;  

					if((pass_length==0) || (pass_length>5))

					{    

					var data = {user_id:user_id,register_password:login_password,save_password:"y"};

				   

					$.ajax({

					  

					  url :'passwordsave.php',

					  type:'POST',

					  dataType : 'json',

					  data:data,

					  success:function(response){

						  if(response==1)

						  {

							  // location.reload();

							  $('#PasswordModel').modal('hide'); 

						  }

						  else

						  {

							 

							  // $("#login_error").show();

						  }

						  

						}		  

				  });

					}

					else

					{

						$("#register_error").html('The password must be six digit and above');

						$("#register_error").show();

						// $("#login_error").show();

					}

			

			 

			return false;

	   });

	   $(".forgot_reset").click(function(){

		    $(this).removeClass(" btn-primary").addClass("btn-default");

		 setTimeout(function() {

			

         $(this).removeClass("btn-default").addClass("btn-primary");

			}.bind(this), 5000);

		   var usermobile=$("#mobile_number").val();

		   // alert(usermobile);

		   var data = {usermobile:usermobile,method:"forgotpass2"};

			// alert(data);

			$.ajax({

			  

			  url :'functions.php',

			  type:'POST',

			  dataType : 'json',

			  data:data,  

			  success:function(response){

				  var data = JSON.parse(JSON.stringify(response));

				  // alert(data.status);

				  if(data.status)

				  {  

						$('.forgot_error').css('color', 'black');

					   $(".forgot_error").html(data.msg);

					   

					  $(".forgot_error").show();

					  

					  $(".forgot_now ").hide();  

				  }

				  else

				  {

					  $(".forgot_reset").show();

					  $(".forgot_error").html(data.msg);

					  $(".forgot_error").show();

					  $(".forgot_now ").hide();

				  }

				  

				}		  

		  });

	   });

		$(".forgot_pass").click(function(){

				$('#with_wallet').hide();

				$('#without_wallet').hide();

				$('.login_passwd_field').hide();

			   $(".join_now").hide();

			   $(".forgot_reset").show();

			   $(".forgot_now").show();

			  $('.forgot-form').show();

		  });  

		  

		  // paypal pay button click 

		$(".paypal_pay").click(function(e){

			 e.preventDefault();

			var product_qty=$('.product_qty').val();

			var table_type=$('#table_type').val();

			var number=$('#mobile_number').val();

			var total_rebate=0;

			var total_amount=0;

			var s_flag=true;

			if ((product_qty == null) || (product_qty=='')){

			// alert('Without Prouct add cant able to go ahead.');  

				$('#show_msg').html("<?php echo $language['no_product_added']; ?>"); 

			 $('#AlerModel').modal('show');

			var s_flag=false;

			return false;  

			}

			if(number.length >= 9 && number.length <= 12 && (number[0] == 1 || number[0]==6)){

			}

			else

			{

				$('#mobile_number').focus();

			   var s_flag=false;

			}

			if($('#table_type').prop('required')){

			  if(table_type=='')

			  {

				  $('#table_type').focus();

				var s_flag=false;

				// return false;

			  }

		  }

		  if(s_flag)

		  {

			

			   $(".p_total").each(function(){

				   // var total_rebate+= $(this).val();

				   total_amount += parseFloat($(this).val());

			   });

			    var delivery_charges=$('#delivery_charges').val();  

				// alert('Deliver charge '+delivery_charges);

				if(delivery_charges>0)

				   var order_min_charge="<?php echo $merchant_detail['order_min_charge'];?>";

					else

					var order_min_charge=0;

				if(order_min_charge>0)

				{  

				   

				   if(parseFloat(total_amount)<parseFloat(order_min_charge))

				   {

					   // var msg="Place Min Order for Rm "+order_min_charge;

					   var msg="Minimum order is Rm "+order_min_charge;

					   $('#show_msg').html(msg);

						$('#AlerModel').modal('show');

						return false;

					   // alert('Order Amount greater');

				   }  

				}

			   // alert(total_amount);

			   if(parseFloat(delivery_charges)>0)

			   {

				   var total_amount=parseFloat(total_amount)+parseFloat(delivery_charges);

			   }

			    var total_amount=total_amount.toFixed(2);

			   	$('#paypal_amount').val(total_amount);   

			  

				$('#total_cart_amount').val(total_amount);

			

			  $.ajax({

				type: 'post',

				  dataType : 'json',

				url: 'order_payal_cash.php',

				data: $('#order_place').serialize(),

				success: function (response) {

					var data = JSON.parse(JSON.stringify(response));

					if(data.status==true)

					{

						$('.paypal_pay').hide();

						if(data.order_id)

						{

							// alert(data.order_id);

							$('#item_order_id').val(data.order_id);

							$('#paypal_form').submit();

						}

						else

						{

							alert(data.msg);

						}

						

					}

					else

					{

						alert(data.msg);

					}

				 

				}

			  });

		     // alert('Ready To take payment'); 

		  }

		  return false;

		  if(s_flag)

		  $('input[type=submit]', this).attr('disabled', 'disabled');

		

	 });

	 // $("#mapSearch").change(function(e){

		// var address=$('#mapSearch').val();

		// var geocoder = new google.maps.Geocoder();

		
        // alert(address);
		 // google.maps.event.addListener(autocomplete, 'place_changed', function() {
				// geocoder.geocode( { 'address': address}, function(results, status) {



		  // if (status == google.maps.GeocoderStatus.OK) {

			// var latitude = results[0].geometry.location.lat();

			// var longitude = results[0].geometry.location.lng();
            // alert(latitude);
            // alert(longitude);
			// if(latitude!='' && longitude!='')

			// {

				// if(location_order=='1')
					// calculatedisatace(latitude,longitude);

			// }
			

						

					

		  // } 

		// }); 
			// });
		

	// });
    $(".internet_banking").click(function(e) {
    var product_qty = $('.product_qty').val();
	var table_type = $('#table_type').val();
	var membership_discount_input = $('#membership_discount_input').val();
	var number = $('#mobile_number').val();
	var already_login = $('#login_for_wallet_id').val();
	var total_rebate = 0;
	var total_amount = 0;
	var s_flag = true;
	if ((product_qty == null) || (product_qty == '')) {
		$('#show_msg').html("<?php echo $language['no_product_added']; ?>");
		$('#AlerModel').modal('show');
		var s_flag = false;
		return false;
	}
	if (number.length >= 9 && number.length <= 12 && (number[0] == 1 || number[0] == 6)) {

    } else
	{
		$('#mobile_number').focus();
		var s_flag = false;
	}
	if ($('#table_type').prop('required')) {
		if (table_type == '')
		{
			$('#table_type').focus();
			var s_flag = false;
		}
	}
	if (s_flag)
	{
		totalcart();
		$(".rebate_amount").each(function() {
       total_rebate += parseFloat($(this).val());
		});
		$(".p_total").each(function() {
			total_amount += parseFloat($(this).val());
		});
		var delivery_charges = $('#delivery_charges').val();
		var pickup_type = $('#pickup_type').val();
		if (pickup_type == "divein")
			var delivery_charges = 0;
		if (delivery_charges > 0)
		var order_min_charge = "<?php echo $merchant_detail['order_min_charge'];?>";
		else
		var order_min_charge = 0;
		if (order_min_charge > 0)
		{
			if (parseFloat(total_amount) < parseFloat(order_min_charge))
			{
				var msg = "Minimum order is Rm " + order_min_charge;
				$('#show_msg').html(msg);
				$('#AlerModel').modal('show');
				return false;
			}
		}   
		var total_amount = total_amount.toFixed(2);
		var total_rebate = total_rebate.toFixed(2);
		$('#total_rebate_amount').val(total_rebate);
		$('#total_cart_amount').val(total_amount);
		$('#total_cart_amount_label').html(total_amount);
		if (delivery_charges > 0)
		$('.delivery_extra').show();
		else
		$('.delivery_extra').hide();
		$('#delivery_cart_amount').val(delivery_charges);
		var final_charge = parseFloat(total_amount) + parseFloat(delivery_charges);
		$('#final_cart_amount').val(final_charge);
		var final_charge = final_charge.toFixed(2);
		$('#final_cart_amount_label').html(final_charge);
		var total_order_amount = 0;

        $(".p_total").each(function() {

            total_order_amount += +$(this).val();

        });
      $('#InternetModel').modal('show');    
		return false;
	$('input[type=submit]', this).attr('disabled', 'disabled');
	
	}

});
	$(".online_pay").click(function(e){

			

			

		 var product_qty=$('.product_qty').val();

		  var table_type=$('#table_type').val();   

		  var membership_discount_input=$('#membership_discount_input').val();   

		// alert(membership_discount_input);

		 var number=$('#mobile_number').val();

		 var already_login=$('#login_for_wallet_id').val();

		 var total_rebate=0;

		 var total_amount=0;

		var s_flag=true;   

		if ((product_qty == null) || (product_qty=='')){

			// alert('Without Prouct add cant able to go ahead.');

				$('#show_msg').html("<?php echo $language['no_product_added']; ?>"); 

			 $('#AlerModel').modal('show');

			

			var s_flag=false;

			return false;  

		  }

		  if(number.length >= 9 && number.length <= 12 && (number[0] == 1 || number[0]==6)){

		  }

		  else

		  {

			  $('#mobile_number').focus();

			   var s_flag=false;

		  }

		  if($('#table_type').prop('required')){

			  if(table_type=='')

			  {

				  $('#table_type').focus();

				var s_flag=false;

				// return false;

			  }

		  }

		     

		  if(s_flag)

		  {

			 

			   $(".rebate_amount").each(function(){

				   // var total_rebate+= $(this).val();

				   total_rebate += parseFloat($(this).val());

			   });

			   

			   $(".p_total").each(function(){

				   // var total_rebate+= $(this).val();

				   total_amount += parseFloat($(this).val());

			   });

			    var delivery_charges=$('#delivery_charges').val();  

				// alert('Deliver charge '+delivery_charges);

				var pickup_type=$('#pickup_type').val();

				if(pickup_type=="divein")

				var delivery_charges=0;

			   if(delivery_charges>0)

				   var order_min_charge="<?php echo $merchant_detail['order_min_charge'];?>";

					else

					var order_min_charge=0;

			   if(order_min_charge>0)

			   {  

				   

				   if(parseFloat(total_amount)<parseFloat(order_min_charge))

				   {

					   // var msg="Place Min Order for Rm "+order_min_charge;

					   var msg="Minimum order is Rm "+order_min_charge;

					   $('#show_msg').html(msg);

						$('#AlerModel').modal('show');

						return false;

					   // alert('Order Amount greater');

				   }

			   }

			   // alert(total_rebate);

			   var total_amount=total_amount.toFixed(2);

			   if(total_rebate>0)
				{

				   $('#without_wallet').hide();

				   // if(total_rebate>10)

				   // {

					   // var total_rebate=10;

				   // }

				   $('.rebate_amount_label').html(total_rebate);

				  $('.rebate_label').show(); 

					if(!already_login)

					{

						 $('#with_wallet').show();

						// $('#without_wallet').show(); 

					}

				  // $('#without_wallet').hide(); 

			   }

			   else

			   {

				   $('#with_wallet').hide();

					if(!already_login)

					{  

				        // alert(membership_discount_input);    

				        if(membership_discount_input)

						{

							

							$('#without_wallet').html('<span style="font-size:20px;">😀</span>Please login in order to claim for your '+ membership_discount_input+' membership discount and to use wallet');

						}

						$('#without_wallet').show();   

					}

					

			   }

			    var total_rebate=total_rebate.toFixed(2);

			   $('#total_rebate_amount').val(total_rebate);

			   $('#total_cart_amount').val(total_amount);

			   $('#total_cart_amount_label').html(total_amount);

				if(delivery_charges>0)

			    $('.delivery_extra').show();  

				else

				$('.delivery_extra').hide();  

					

					// var delivery_charges=delivery_charges.toFixed(2);

					$('#delivery_cart_amount').val(delivery_charges);

					

					var final_charge=parseFloat(total_amount)+parseFloat(delivery_charges);

					$('#final_cart_amount').val(final_charge);

					var final_charge=final_charge.toFixed(2);    

					// var delivery_charges=delivery_charges.toFixed(2);

					// alert(delivery_charges);      

					// alert(final_charge);      

					// $('#delivery_cart_amount_label').show();

					$('#delivery_cart_amount_label').html(delivery_charges);

					$('#final_cart_amount_label').html(final_charge);

				var total_order_amount = 0;

				$(".p_total").each(function(){

					total_order_amount += +$(this).val();

				});

			

			  // alert(total_order_amount);   

			 if(!already_login)

			{

				var mobile_number=$('#mobile_number').val();

				var merchant_id='<?php echo $id; ?>';

				var special_coin_name='<?php echo $special_coin_name; ?>';

				var block_pay='<?php echo $block_pay; ?>';

				// alert(block_pay);

				// return false;

				if(mobile_number)

				{

				// alert(mobile_number);

				 $.ajax({

					url: 'functions.php',

					type:'POST',

					dataType : 'json',

					data:{mobile_number:mobile_number,method:"usercheck",merchant_id:merchant_id,special_coin_name:special_coin_name},

					success: function (res) {

					var data = JSON.parse(JSON.stringify(res));

					// alert(JSON.stringify(data));

					// alert(data.status);

						if(data.status==true)

						{

							var plan_label=data.plan_label;

							var otp_verified=data.data.otp_verified;

							// alert(otp_verified);

							if(otp_verified=="y")

							{

								var plan_benefit=data.plan_benefit;

								 $('#membership_discount_input').val(plan_benefit);

								// alert(plan_benefit);

								if(plan_label!='')

								{

									

									$('#membership_discount').show();

									$('#membership_discount').html(plan_label);

									$('#membership_applicable').val('y');

									

								}

								$('.online_pay').removeClass(" btn-primary").addClass("btn-default");

								$('#myr_bal').html(data.data.balance_myr);

								$('#usd_bal').html(data.data.balance_usd);

								$('#inr_bal').html(data.data.balance_inr);

								$('#special_bal').html(data.data.balance_special);

								$('#myr_input_bal').val(data.data.balance_myr);

								$('#usd_input_bal').val(data.data.balance_usd);

								$('#inr_input_bal').val(data.data.balance_inr);

								$('#special_input_bal').val(data.data.balance_special);

								$('#newuser_model').modal('show');

								$('.forgot-form').hide();

								$('.forgot_reset').hide();

								$('.login_passwd_field').show();

								$('.join_now').show();   

								$('.login_ajax').show();

							}

							else

							{

								var msg="Wallet Feature is Only For Register.Please Verify your mobile No,We are Processing as Cash Wallet";  

								$('#show_msg').html(msg);

								$('#AlerModel').modal('show');

								setTimeout(function(){

								$("#WalletModel").modal("hide"); 

								$("#order_place").submit();

								},5000);  

								

								

							}   

							

						}  

						else

						{ 

							$('#WalletModel').modal('show');

							setTimeout(function(){

								$("#WalletModel").modal("hide"); 

								$("#order_place").submit();

								},5000);  

							

							

							// $("#order_place").submit();

							

							// alert('Wallet Feature is Only Applicale for Register Member,We are Processing as Cash Wallet');

							

						}

						

					}

				});

				}

				else

				{

					$('#mobile_number').foucus();

					alert('Phone number is Required');

				}

				

				 // $(".forgot-form").show();

			}

			else

			{

				$('#newuser_model').modal('show');

				$('#login_process').hide();

				$('.forgot-form').hide();

				$('.forgot_reset').hide();

				$('.login_passwd_field').show();

				$('.join_now').show();   

				$('.login_ajax').show();

			    $('.online_pay').removeClass(" btn-primary").addClass("btn-default");

				walletstep();

			} 

		  }

		  return false;

		  if(s_flag)

		  $('input[type=submit]', this).attr('disabled', 'disabled');

		totalcart();

	 });

	$(".wallet_select").click(function(){

			totalcart();

		    var special_coin_name='<?php  echo $special_coin_name;?>';

			var sst_tax="<?php echo $merchant_detail['sst_rate']?>";

			var delivery_tax="<?php echo $merchant_detail['delivery_rate']?>";

			var delivery_take_up="<?php echo $merchant_detail['delivery_take_up']?>";

			var delivery_dive_in="<?php echo $merchant_detail['delivery_dive_in']?>";

			var totalsale = 0;

			$(".p_total").each(function(){

				totalsale += +$(this).val();

			});

			if(special_coin_name)

			{

				var special_coin_min='<?php  echo $special_coin_min;?>';

				var special_coin_max='<?php  echo $special_coin_max;?>';

				w_bal=$('#special_input_bal').val(); 

				var type =special_coin_name;

				var min_bal=special_coin_min;

				var max_bal=special_coin_max;

				var rebate_amount=0;

				var wallet_name=special_coin_name;

			}

			else

			{

				var type = $(this).attr('type');

				var w_bal =0;

				// alert(type);

				var wallet_name = $(this).attr('wallet_name');

				if(type=="myr_bal")

				w_bal=$('#myr_input_bal').val(); 

				if(type=="usd_bal")

				w_bal=$('#usd_input_bal').val(); 

				if(type=="inr_bal")

				w_bal=$('#inr_input_bal').val(); 

				var min_bal=0.5;

				var max_bal=100;

				var rebate_amount=$('#total_rebate_amount').val();

			}

			// alert(w_bal);

			// alert(min_bal);

			 $('#wallet_selected').val('y');

				$('#selected_wallet').val(type);

				$('#selected_wallet_bal').val(w_bal);

				$('.select_label').show();

				

				var p_bal=0;

				  $('#wallet_name').html(wallet_name);

				if((parseFloat(w_bal)>=parseFloat(min_bal)))

				{

					

					if(parseFloat(w_bal)>parseFloat(max_bal))

						p_bal=max_bal;

					else 

					p_bal=w_bal;	

				   var p_bal=parseFloat(p_bal);

					var total_amount=$('#total_cart_amount').val();

					var delivery_charges=$('#delivery_charges').val();  

					var pickup_type=$('#pickup_type').val();

					// alert(pickup_type);

					

					if(pickup_type=="divein")

					{

						if(delivery_dive_in=='1')

						{

							if(parseFloat(delivery_tax)>0)

							{

								var deliver_tax_amount=calculatepercentage(totalsale,delivery_tax);

							}

						}

						else

						{

							 var deliver_tax_amount=0;

						}

						var delivery_charges=0;

					}

					else if(pickup_type=="takein")

					{

						if(delivery_take_up=='1')

						{

							if(parseFloat(delivery_tax)>0)

							{

								var deliver_tax_amount=calculatepercentage(totalsale,delivery_tax);

							}

						}

						else

						{

						 var deliver_tax_amount=0;

						}

					} 

					

					if(parseFloat(sst_tax)>0)

					{

						var sst_amount=calculatepercentage(total_amount,sst_tax);

					}

					if(parseFloat(delivery_charges)>0)

						var total_amount=parseFloat(total_amount)+parseFloat(delivery_charges);

					var membership_discount_input=$('#membership_discount_input').val(); 

					

					if(membership_discount_input)

					{

						var totalsale = 0;

						$(".p_total").each(function(){

							totalsale += +$(this).val();

						});

						var substring="%";

						if(membership_discount_input.includes(substring))

						{

							var discount = membership_discount_input.replace("%","");

							

							// per discount 

							var membership_discount=calculatepercentage(totalsale,discount);

							

						}

						else

						{

						  // fix discount 

						  var membership_discount=membership_discount_input.replace("off","");

						   membership_discount = parseFloat(membership_discount);

						  

						}

						if(membership_discount>0)

						{

							var total_amount=parseFloat(total_amount)-parseFloat(membership_discount);

						}

						

					}

					var coupon_discount=$('#coupon_discount').val();

					if(coupon_discount>0)

					{

						var total_amount=parseFloat(total_amount)-parseFloat(coupon_discount);

					}

					 // alert(total_amount);



					 // alert(w_bal);

					 

					 if(parseFloat(sst_tax)>0)

						{

							// var sst_amount=calculatepercentage(total_amount,sst_tax);

							$('.sst_amount_label').show(); 

							$('.sst_amount_value').html(parseFloat(sst_amount).toFixed(2));  		

							var total_amount=(parseFloat(total_amount)+parseFloat(sst_amount));

						} 

					// alert(deliver_tax_amount);

					if(parseFloat(deliver_tax_amount)>0)

						{

							// var sst_amount=calculatepercentage(total_amount,sst_tax);

							$('.delivery_tax_amount_label').show(); 

							$('.delivery_tax_amount_value').html(parseFloat(deliver_tax_amount).toFixed(2));  		

							var total_amount=(parseFloat(total_amount)+parseFloat(deliver_tax_amount));

						}    
					var part_payment="<?php echo $merchant_detail['part_payment']?>";
					if(part_payment==0)
					{
						if((parseFloat(total_amount)>parseFloat(w_bal)))

						{

							$('#LesaaAmountModel').modal('show');
							return false;

						}
					}


						// alert("Payment Bal"+p_bal);

						// alert("Payment 2"+total_amount);

							if(parseFloat(p_bal)>parseFloat(total_amount))    

							var p_bal=total_amount;

							// alert()

						   p_bal = (parseInt(p_bal * 10)/10).toFixed(2);

						   

							$('#wallet_payment_label').show();

							//$('#bal_payment_label').show();

						   $('#wallet_payment_label').html("Paying By Wallet Rm: <span style='color:black;font-weight:bold;'>"+p_bal+"</span></br>");

						

							

							var merchant_id='<?php echo $id; ?>';

							var selected_wallet_bal=$('#selected_wallet_bal').val();

							if(parseFloat(selected_wallet_bal)>parseFloat(max_bal))

								selected_wallet_bal=max_bal;

							 selected_wallet_bal = (parseInt(selected_wallet_bal * 10)/10).toFixed(2);

							var rem=0;

							if(parseFloat(selected_wallet_bal)<parseFloat(total_amount))

							{

								var rem=parseFloat(total_amount)-parseFloat(selected_wallet_bal);

								var rem=parseFloat(rem).toFixed(2);

								var payable_amount=selected_wallet_bal;

									var payable_amount=parseFloat(payable_amount).toFixed(2);

									var selected_wallet_bal=parseFloat(selected_wallet_bal).toFixed(2);

								var p_msg="You are going to pay Rm "+selected_wallet_bal+" throught your wallet ,The Remaining Rm balance of "+rem+" will be paid by you via Cash";	

							}

							else

							{

								var total_amount=parseFloat(total_amount).toFixed(2);

							  var p_msg="You are going to pay Rm "+total_amount+" throught your wallet";	

							  var payable_amount=total_amount;

							}

						  $('#bal_to_paid_label').show();

						  $('#bal_to_paid_label').html("Balance to be paid by cash Rm: <span style='color:black;font-weight:bold;'>"+rem+"</span></br>");

						  $(".wallet_final_payment").trigger("click");

						  // $('.wallet_final_payment').show(); 

					

					

				}        

				else

				{  

					

					$('#LesaaAmountModel').modal('show');

					// alert('Insufficient balance for deduction, please select other wallet or pay by cash. ');

					return false;

					// exit;

				}

			totalcart();

	  });   

	  $(".trasfer_complete").click(function(){
		  $('.trasfer_complete').hide();
		  $(this).removeClass(" btn-primary").addClass("btn-default");

		  // $('#payable_amount').val(0);

		  $('#selected_wallet').val('Internet Banking');

		   $("#order_place").submit();

	  });

	  $(".cash_pay").click(function(){

		  $(this).removeClass(" btn-primary").addClass("btn-default");

		  $('#payable_amount').val(0);

		  $('#selected_wallet').val('');

		   $("#order_place").submit();

	  });

	 

	   $(".make_payment").click(function(){

		$(this).removeClass(" btn-danger").addClass("btn-default");

		$("#order_place").submit();

	  });

		

	  $(".wallet_final_payment").click(function(){

		  var sst_tax="<?php echo $merchant_detail['sst_rate']?>";

		var delivery_tax="<?php echo $merchant_detail['delivery_rate']?>";

		var delivery_take_up="<?php echo $merchant_detail['delivery_take_up']?>";

		var delivery_dive_in="<?php echo $merchant_detail['delivery_dive_in']?>";

		$('.wallet_final_payment').removeClass(" btn-danger").addClass("btn-default");

		var total_amount=$('#total_cart_amount').val();

		var rebate_amount=$('#total_rebate_amount').val();

		var merchant_id='<?php echo $id; ?>';

		var selected_wallet_bal=$('#selected_wallet_bal').val();

		var special_coin_name='<?php  echo $special_coin_name;?>';

		var special_coin_max='<?php  echo $special_coin_max;?>';

		if(special_coin_name)

			var max_bal=special_coin_max;

		else 

			var max_bal=10;

		if(parseFloat(selected_wallet_bal)>parseFloat(max_bal))

			selected_wallet_bal=max_bal;

		var rem=0;

		selected_wallet_bal = (parseInt(selected_wallet_bal * 10)/10).toFixed(2);

		var delivery_charges=$('#delivery_charges').val();  

		var pickup_type=$('#pickup_type').val();

		if(pickup_type=="divein")

		{

			if(delivery_dive_in=='1')

			{

				if(parseFloat(delivery_tax)>0)

				{

					var deliver_tax_amount=calculatepercentage(total_amount,delivery_tax);

				}

			}

			else

			{

				

				var deliver_tax_amount=0;

			}

			var delivery_charges=0;

		}

		else if(pickup_type=="takein")

		{

			if(delivery_take_up=='1')

			{

				if(parseFloat(delivery_tax)>0)

				{

					var deliver_tax_amount=calculatepercentage(total_amount,delivery_tax);

				}

			}

			else

			{

				

				var deliver_tax_amount=0;

			}

		} 

		if(parseFloat(sst_tax)>0)

		{

			var sst_amount=calculatepercentage(total_amount,sst_tax);

		}

		if(parseFloat(delivery_charges)>0)

		{

			var total_amount=parseFloat(delivery_charges)+parseFloat(total_amount);

		}

		var membership_discount_input=$('#membership_discount_input').val(); 

		if(membership_discount_input)

					{

						var totalsale = 0;

						$(".p_total").each(function(){

							totalsale += +$(this).val();

						});

						var substring="%";

						if(membership_discount_input.includes(substring))

						{

							var discount = membership_discount_input.replace("%","");

							

							// per discount 

							var membership_discount=calculatepercentage(totalsale,discount);

							

						}

						else

						{

						  // fix discount 

						  var membership_discount=membership_discount_input.replace("off","");

						   membership_discount = parseFloat(membership_discount);

						  

						}

						if(membership_discount>0)

						{

							var total_amount=parseFloat(total_amount)-parseFloat(membership_discount);

						}

					}

		

		if(parseFloat(sst_tax)>0)

		{

			// var sst_amount=calculatepercentage(total_amount,sst_tax);

			$('.sst_amount_label').show(); 

			$('.sst_amount_value').html(parseFloat(sst_amount).toFixed(2));  		

			var total_amount=(parseFloat(total_amount)+parseFloat(sst_amount));

		}

		if(parseFloat(deliver_tax_amount)>0)

		{

			$('.delivery_tax_amount_label').show(); 

			$('.delivery_tax_amount_value').html(parseFloat(deliver_tax_amount).toFixed(2));  		

			var total_amount=(parseFloat(total_amount)+parseFloat(deliver_tax_amount));

		}

		else

		{

			$('.delivery_tax_amount_label').hide(); 

			$('.delivery_tax_amount_value').html('');  	

		}

		// alert(deliver_tax_amount);

      $('#deliver_tax_amount').val(deliver_tax_amount);		

		if(parseFloat(selected_wallet_bal)<parseFloat(total_amount))

		{ 

			var rem=parseFloat(total_amount)-parseFloat(selected_wallet_bal);

			var rem=parseFloat(rem).toFixed(2);

			var payable_amount=selected_wallet_bal;

				var payable_amount=parseFloat(payable_amount).toFixed(2);

				

				var selected_wallet_bal=selected_wallet_bal;

				var selected_wallet_bal = (parseInt(selected_wallet_bal * 10)/10).toFixed(2);

			var p_msg="You are going to pay RM <span style='color:black;font-weight:bold;'>"+selected_wallet_bal+"</span> through your wallet. The remaining balance of RM <span style='color:black;font-weight:bold;'>"+rem+"</span> is to be paid by cash.";	

		

		}

		else

		{

			var total_amount=parseFloat(total_amount).toFixed(2);

		  var p_msg="You are going to pay Rm <span style='color:black;font-weight:bold;'>"+total_amount+"</span> through your wallet";	

		  var payable_amount=total_amount;

		}

		 

		$('#amount_label').html(p_msg);

		$('#ProccedAmount').modal('show');

		var wallet_type=$('#selected_wallet').val();

		var user_id=$('#login_user_id').val();

		$('#rem_amount').val(rem);  

		$('#payable_amount').val(payable_amount);

		// var r = confirm(p_msg);

			if (r == true) {

			    	// alert(data);

					var wallet_type=$('#selected_wallet').val();

					var user_id=$('#login_user_id').val();

					$('#rem_amount').val(rem);

					$('#payable_amount').val(payable_amount);

					 // $("#order_place").submit();

					// var order_id=$("#order_id").val();

					// var data = {order_id:order_id,user_id:user_id,method:"walletpay",w_type:wallet_type,total_amount:total_amount,rem:rem,payable_amount:payable_amount,merchant_id:merchant_id,rebate_amount:rebate_amount};

					// $.ajax({

						// url :'functions.php',

					  // type:'POST',

					  // dataType : 'json',

					  // data:data,

					  // success:function(response){

						  // var data = JSON.parse(JSON.stringify(response));

						  // if(data.status)

						  // {

							  // location.reload();

						  // }

						  // else

						  // {

							  // alert('Failed to make payment From Wallet');

						  // }

						  

						// }		  

						// });

			} else {

			 // alert('Wallet reject');

			 // location.reload();

			}

		

		

	});

	// end for rebaeat process

	

	if(location_order==1)

	{

		

		

		var user_lat=$('#user_lat').val();

		if(!user_lat)

		{
			var shop_close_time=$('#shop_close_time').val();
			if(shop_close_time=="n")
			{
				$('.pro_status').show();
				$('.introduce-remarks').hide();
				$( "#confmpayment" ).prop( "disabled", false );
				$( "#confm" ).prop( "disabled", false );
				$( ".online_pay" ).prop( "disabled", false );
			}
			// var msg="Required Your Location to order at "+'<?php echo $merchant_detail['name']; ?>';  
            // var msg="We are unable to track your location. If your location is less than 5km, the delivery charges is Rm2.99, more than 5km=Rm1/km.";
			// var msg="<?php echo $language['distance_msg_system']; ?>";  
			// $('#show_msg').css("font-size","18px");
			// $('#show_msg').html(msg);
			var dine_in="<?php echo $dine_in; ?>";
			if(dine_in=="n")
			{
				$('#delivery_label').show();
				var delivery_charges=2.99;
				$('#delivery_charges').val(delivery_charges);
				$('#order_extra_charge').val(delivery_charges);
			}
			$('#delivery_info_label').show();
			$('#delivery_info_label').html("</br>"+msg);
    
			// $('#AlerModel').modal('show'); 

			// setTimeout(function(){ $("#AlerModel").modal("hide"); },5000);

		}  

		

	}

  

});
   
   function clearhistory()

   {

	$('#location_model').hide();  

	setTimeout(function(){ $("#clear_history_model").modal("show"); },1000);

	 // $('#clear_history_model').show();  

   }

   function  walletstep()

	{

		$('.wallet_mode').show();

	}

	function rebatevalue(a,b)

	{

		  var c = (parseFloat(a)*parseFloat(b))/100;

		return parseFloat(c).toFixed(2);

	}

	function myFunction() {

  var x = document.getElementById("login_ajax_password");

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

  var x = document.getElementById("register_password");

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

function myFunctionnew() {

  var x = document.getElementById("login_ajax_password_new");

  if (x.type === "password") {

    x.type = "text";

	    $("#eye_pass_new").html('Hide Password');

			 $('#eye_slash_new').removeClass( "fa-eye-slash" );

            $('#eye_slash_new').addClass( "fa-eye" );

			

  } else {

    x.type = "password";   

	 $("#eye_pass_new").html('Show Password');

	  $('#eye_slash_new').addClass( "fa-eye-slash" );

            $('#eye_slash_new').removeClass( "fa-eye" );

  }

}

  function our_stall(){

      $('#our_stall').modal('show');

   

  }

 function  getId(element) {

     // alert("row" + element.closest('tr').rowIndex);

      var id = $(element).closest('tr').find('td:first-child').text();

      $("#merchant_id").val(id);

    $("#sub_mer_form").submit();

    

}

  function getLocation() {

    // alert(3);

  if (navigator.geolocation) {

    navigator.geolocation.getCurrentPosition(showPosition);

  } else { 

    // x.innerHTML = "Geolocation is not supported by this browser.";

  $( "#confm" ).prop( "disabled", true );

  $( ".online_pay" ).prop( "disabled", true );

  $('.text_add_cart').hide();

  if(location_order==1)

  $('#location_model').modal('show');

  }

}

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
        var shop_close_time=$('#shop_close_time').val();
			if(shop_close_time=="n")
			{
				$('.pro_status').show();
				$('.introduce-remarks').hide();
				$( "#confmpayment" ).prop( "disabled", false );
				$( "#confm" ).prop( "disabled", false );
				$( ".online_pay" ).prop( "disabled", false );
			}
			// var msg="Required Your Location to order at "+'<?php echo $merchant_detail['name']; ?>';  
            // var msg="We are unable to track your location. We may call you to advise the delivery charges in case of take away.";
            // var msg="We are unable to track your location. our delivery charges is Rm 2.99 for first 3km, and 1km for RM1 subsequently.";
			// var msg="Kindly approve your location access for calculation of delivery fee. Orelse, we have to manually compute the fee, which is <5km=Rm3 & Rm1 for subsequent 1km.";
			// $('#error_label').show();
			    // $('#error_label').html(msg);
			// $('#show_msg').html(msg);
			
			
			// $('#delivery_info_label').html("</br>"+msg);
			
    
			// $('#AlerModel').modal('show'); 

			// setTimeout(function(){ $("#AlerModel").modal("hide"); },5000);
        // alert("Geocoder failed due to: " + status);

      }

    });

  }

function calculatedisatace(to_lat,to_long)

{

  // alert(3);

  var from_lat="<?php echo $merchant_detail['latitude']; ?>";

  var from_long="<?php echo $merchant_detail['longitude']; ?>";

  var location_range="<?php echo $merchant_detail['location_range']; ?>";

  $.ajax({

               url: 'calculate_distance.php',  

               type: 'POST',

               data:{from_lat:from_lat,from_long:from_long,to_lat:to_lat,to_long:to_long,merchant_id:'<?php echo $merchant_detail['id']; ?>'},

			    dataType:'json',

                 success: function(response) {

					  var result = JSON.parse(JSON.stringify(response));

					  if(result.status==true)

					  {

						  var data=result.data;

						  var distance=data.distance;  

					  }

					  else

					  {

						var distance=0;  

					  }

				  

           // alert(to_lat);

           // alert(distance);     

           // alert(data);     

		   

			// alert('Customer Distance: '+distance);

			console.log('Customer Distance: '+distance);

			

             if(parseFloat(distance) > parseFloat(location_range))

             {

				$('.pro_status').hide();

				$( "#confm" ).prop( "disabled", true );

				$( ".online_pay" ).prop( "disabled", true );

				$( "#confmpayment" ).prop( "disabled", true );

				$('#map_range').html(location_range);

				$('.fancybox_place_order').hide();

				$('#map_model').modal('show');

				$('#delivery_label').hide();   

             }

             else

             {   

				if(distance>0)

				 {

					var msg="Your distance is "+distance+" km away from "+'<?php echo $merchant_detail['name']; ?>';

					$('#show_msg').html(msg);

					$('#AlerModel').modal('show'); 

					setTimeout(function(){ $("#AlerModel").modal("hide"); },2000);

				}  

				 // check delivery charges of order 

			    // var delivery_charges="<?php echo $merchant_detail['order_extra_charge']; ?>";

				var delivery_charges=data.delivery_charge;

				// var delivery_charges=delivery_charges.toFixed(2);

				// alert(delivery_charges);
                
				var free_delivery="<?php echo $merchant_detail['free_delivery']; ?>";

				console.log('Delivery Charge: '+delivery_charges);

				// alert('Free Delivery Charges: '+free_delivery);

				$('#order_extra_label').html(parseFloat(delivery_charges).toFixed(2));       
				var dine_in="<?php echo $dine_in; ?>";
				if(parseFloat(delivery_charges)>0)

				{

					if(parseFloat(distance)>parseFloat(free_delivery))

					{ 

						// alert('paid');

						$('#delivery_charges').val(delivery_charges);

						$('#order_extra_charge').val(delivery_charges);
						if(dine_in=="n")
						$('#delivery_label').show();

					}

					else

					{

						  // alert('free');

						$('#delivery_charges').val(delivery_charges);

						$('#order_extra_charge').val(delivery_charges);

						// $('#delivery_charge_status').val('free');
						if(dine_in=="n")
						$('#delivery_label').show();

					}

					

				}

				else

				{

					$('#delivery_label').hide();

				}

				

				var shop_close_time=$('#shop_close_time').val();
				if(shop_close_time=="n")
				{
				   $('.pro_status').show();

				   $('.introduce-remarks').hide();

				   $( "#confmpayment" ).prop( "disabled", false );

				  $( "#confm" ).prop( "disabled", false );

				  $( ".online_pay" ).prop( "disabled", false );
				}

             }

          }

           });

}   

function showPosition(position) {

  

  // x.innerHTML = "Latitude: " + position.coords.latitude + 

  // "<br>Longitude: " + position.coords.longitude;

    var latitude = position.coords.latitude;

    var longitude = position.coords.longitude;

  if(latitude && longitude)

  {

    $('#user_lat').val(latitude);

    $('#user_long').val(longitude);

    calculatedisatace(latitude,longitude);

    

    $('#location_model').modal('hide');

  }     

}   

</script>

<script>
  $('input[name="mobile_number"]').blur(function() {
    if (!$(this).val()) {
      $("#mobile_error").show();
    } else {
      $("#mobile_error").hide();
    }
  });
  $(".microphone_click").on("click", function(){   
  var s_flag=true;
    var number=$('#mobile_number').val();
	 var table_type = $('#table_type').val();
    var section_type = $('#section_type').val();
    var section_text     = $("#section_type option:selected").text();
	if(number.length >= 9 && number.length <= 12 && (number[0] == 1 || number[0]==6)){
			$('#mobile_error').hide();
			  $("#mobile_number").removeClass("is-invalid").addClass("is-valid");
		  }

		  else
			{
				// alert(3);
				if($(this).data('type') == 'top'){
					//var $elem = $('#order_place');
					// $('html, body').animate({scrollTop: $elem.height()}, 1000);
					$('html, body').animate({ scrollTop: $("#mobile_no_label").offset().top }, 2000);
				  }
				   $("#mobile_number").removeClass("is-valid").addClass("is-invalid");
			  $('#mobile_number').focus();
				$('#mobile_error').show();
			   var s_flag=false;

		  }
		   if($('#table_type').prop('required')){
			if(table_type=='')
			{
				if($(this).data('type') == 'top'){
					//var $elem = $('#order_place');
					// $('html, body').animate({scrollTop: $elem.height()}, 1000);
					$('html, body').animate({ scrollTop: $("#table_show").offset().top }, 2000);
				  }
					 $("#table_type").removeClass("is-valid").addClass("is-invalid");
				  $('#table_type').focus();  

				var s_flag=false;

				// return false;

			  }

		  }  
		  // alert(s_flag);
		  if(s_flag)
		  {
			  // alert('no errro');
			  swal({
				title: "Are you sure?",
				text: "Do you want to order with audio?",
				type: "info",
				showCancelButton: true,
				confirmButtonClass: "btn btn-danger",
				confirmButtonText: "OK",
				cancelButtonText: "Cancel",
				closeOnConfirm: true,
				closeOnCancel: false
				 },
				 function(isConfirm)
				 {
				   if (isConfirm){
					  var audio = document.getElementById("player1_html5");
					  audio.play();
					  $('#voice_recognition').modal('show');
						$("#spin").hide();
						var html ="";
						  html += '<li class="list-group-item orderItem"><strong>Phone Number : </strong> <span id="tableNum" class="text-right">'+number+'</span></li>';
		 
						if(table_type != "")
						{
							html += '<li class="list-group-item orderItem"><strong>Table Number : </strong> <span id="tableNum" class="text-right">'+table_type+'</span></li>'; 
						}

						if(section_type != "")
						{
						  html +='<li class="list-group-item orderItem"><strong>Section : </strong> <span id="section"class="text-right">'+section_text+'</span></li>'; 
						}
						$("#modal_list").html(html);
					  return true;
					}else {
						swal("", "Order Not Placed!", "error"); 
					}
				 });
		  }
  });

</script>

<script>

function verifiedmobile()

{

	var user_mobile=$('#verifiedmobile').val();

	$('#verifybutton').hide();

	if(user_mobile)

	{

		data = {user_mobile:user_mobile, method: "resendlink"};

			 $.ajax( {

                                url : "functions.php",

                                type:"post",

                                data : data,

                                dataType : 'json',

                                success : function(response) {

									var data = JSON.parse(JSON.stringify(response));

									if(data.status)

									{

										$('#resend_link_label').show(); 

										setTimeout(function(){ $("#free_trial_model").modal("hide"); },3000);

									}

									else  

									{

										alert(data.msg);

									}

                                },

                                error: function(data){

                                    console.log(data);

									$('#verifybutton').hide();

                                }

                });

	}

}

function calculatepercentage(listPrice, discount)

{

	 listPrice = parseFloat(listPrice);

  discount  = parseFloat(discount);

  return (( listPrice * discount / 100 )).toFixed(2); // Sale price

}

function totalcart()

{ 

    var sst_tax="<?php echo $merchant_detail['sst_rate']?>";

    var delivery_tax="<?php echo $merchant_detail['delivery_rate']?>";

    var delivery_take_up="<?php echo $merchant_detail['delivery_take_up']?>";

    var delivery_dive_in="<?php echo $merchant_detail['delivery_dive_in']?>";

	var totalsale = 0;

    $(".p_total").each(function(){

        totalsale += +$(this).val();

    });

	var delivery_charges=$('#delivery_charges').val();

	var pickup_type=$('#pickup_type').val();

	// alert(delivery_charges);          
   if(delivery_charges>0)
   {
	   var dine_in="<?php echo $dine_in; ?>";
	 	if(dine_in=="n")
		$('#delivery_label').show();
  
   }
	if(pickup_type=="divein")

	{

		if(delivery_dive_in=='1')

		{

			if(parseFloat(delivery_tax)>0)

			{

				var deliver_tax_amount=calculatepercentage(totalsale,delivery_tax);

			}

		}

		else

		{

			

			var deliver_tax_amount=0;

		}

		var delivery_charges=0;
		$('#delivery_charges').val(0);
	}

	else if(pickup_type=="takein")

	{

		// alert(delivery_take_up);

		if(delivery_take_up=='1')

		{

			if(parseFloat(delivery_tax)>0)

			{

				var deliver_tax_amount=calculatepercentage(totalsale,delivery_tax);

			}

		}

		else

		{

			

			var deliver_tax_amount=0;

		}

	} 

	var coupon_discount_amount=$('#coupon_discount_amount').val();

	var coupon_discount=0;

	var final_charge=parseFloat(totalsale)+parseFloat(delivery_charges);  

	

	if(coupon_discount_amount>0)

	{

	  	var coupon_min_value=$('#coupon_min_value').val();

	  	var coupon_max_value=$('#coupon_max_value').val();

	  	var coupon_type=$('#coupon_type').val();

		if((parseFloat(totalsale)>=parseFloat(coupon_min_value)))

		{

			if((parseFloat(totalsale)<=parseFloat(coupon_max_value)))

			{

				if(coupon_type=="per")

				{

					var coupon_discount=calculatepercentage(totalsale,coupon_discount_amount);

				}

				else

				{

					var coupon_discount=coupon_discount_amount;

					// var coupon_discount=coupon_discount.toFixed(2);

				}

				if(coupon_discount>0)

				{

					if(coupon_discount>totalsale)

						var coupon_discount=totalsale;

					var final_charge=parseFloat(totalsale)+parseFloat(delivery_charges)-parseFloat(coupon_discount);

					

					$('#coupon_discount').val(coupon_discount);

				}  

				$('.coupon_discount_amount_label').show();

				$('.coupon_discount_amount_value').html(coupon_discount);

				

			}

			else

			{

				$('#coupon_message').show();

				$('#coupon_message').html('Coupon is Only Valid up to order of RM '+coupon_min_value);

			}

			

		}

		else

		{

			$('#coupon_message').show();

			$('#coupon_message').html('For that Coupon Min amount has to RM '+coupon_min_value);

		}

		$('.final_amount_label').show();

		$('.final_amount_value').html(parseFloat(final_charge).toFixed(2));

	}

	if(final_charge<0)

		var final_charge=0;

	$('#final_cart_amount').val(final_charge);

	var final_charge=parseFloat(final_charge).toFixed(2);

	if(delivery_charges>0)

	{

		$('#delivery_cart_amount_label').html(parseFloat(delivery_charges).toFixed(2));

		$('.delivery_extra').show()

		$('.delivery_extra_value').html(parseFloat(delivery_charges).toFixed(2));



	}

	

	// $('#total_cart_value').html(totalsale.toFixed(2));

	var membership_discount_input=$('#membership_discount_input').val(); 

	

	if(membership_discount_input)

	{

		var substring="%";

		if(membership_discount_input.includes(substring))

		{

			var discount = membership_discount_input.replace("%","");

			

			// per discount 

			var membership_discount=calculatepercentage(totalsale,discount);

			

		}

		else

		{

		  // fix discount 

		  var membership_discount=membership_discount_input.replace("off","");

		   membership_discount = parseFloat(membership_discount);

		  

		}

		   // alert("member ship discount"+membership_discount);

		if(membership_discount)

		{

			$('.membership_discount_label').show();	

			$('.membership_discount_value').html(membership_discount);   

			$('.final_amount_label').show();

			var final_charge=(parseFloat(totalsale)+parseFloat(delivery_charges))-parseFloat(membership_discount)-parseFloat(coupon_discount);

			$('.final_amount_value').html(parseFloat(final_charge).toFixed(2));   

		}

      

	}

	if(final_charge<0)

		var final_charge=0;

	if(parseFloat(sst_tax)>0)

	{

		var sst_amount=calculatepercentage(totalsale,sst_tax);

		$('.sst_amount_label').show(); 

		$('.sst_amount_value').html(parseFloat(sst_amount).toFixed(2));  		

		var final_charge=(parseFloat(final_charge)+parseFloat(sst_amount));

	}  

	// alert(deliver_tax_amount);

	if(parseFloat(deliver_tax_amount)>0)

	{

		$('.delivery_tax_amount_label').show(); 

		$('.delivery_tax_amount_value').html(parseFloat(deliver_tax_amount).toFixed(2));  		

		var final_charge=(parseFloat(final_charge)+parseFloat(deliver_tax_amount));

	}

	else

	{

		$('.delivery_tax_amount_label').hide(); 

		$('.delivery_tax_amount_value').html('');  	

	}

	$('.final_amount_label').show();  

	$('#deliver_tax_amount').val(deliver_tax_amount);

	$('#final_cart_amount_label').html(final_charge); 

	$('.final_amount_value').html(parseFloat(final_charge).toFixed(2));   	

    $('#total_cart_amount_label_show').show();	

	$('#total_cart_value').html(totalsale.toFixed(2));

}

function UpdateTotal(id, uprice= 0){

	// alert(id);           

	// alert(uprice);     

  var qty = $("#"+id+"_test_athy").val();

  var extra_val = $("#extra_child_"+id).val();

  // var extra = $("#"+id+"_test_athy").parent().parent().find("input[name='p_extra']").val().parseInt();

  //alert(qty);

  // alert(extra_val);

  if(extra_val=='')

	  var extra_val=0;

  var p_t=parseFloat(uprice)+parseFloat(extra_val);

  var total =  parseFloat(Number(qty*p_t).toFixed(2));

  // alert(qty);

  $("#"+id+"_cat_total").val(total);

  totalcart();

}

function UpdateTotalCart(id=0){

  // var qty = $("#"+id+"_test_athy").val();

  var qty = $("#other_qty_"+id).val();

  var unitprize = $("#other_product_price_"+id).val();

  var total =  parseFloat(Number(qty*unitprize).toFixed(2));

  $("#"+id+"_cat_total").val(total);

  

  totalcart();

}

// console.log("dsfgsdf", $('.section-dropdown'));

$('.section-dropdown').on('change', function(e) {

  e.preventDefault();

  let $this = $(this);

  let sectionId = $this.val();

  let $tableDropDown = $('.section-tables');

  let url = $this.attr('data-table-list-url') + '?section_id='+sectionId;

  let promise = $.ajax({

                  url: url,

                  type: 'get',

                  dataType: 'JSON'

                });

  promise.done(function(response) {

    

    if(response.length < 1) {

      let option = '<option>No Table Found</option>';

      $tableDropDown.html(option);

      return;

    }

    let option = "";

    response.forEach(function(item, index) {

      option += '<option value="'+item.id+'">'+item.name+'</option>';

    });

    $tableDropDown.html(option);

  });

});

</script>

  <script>

     

        

          

          /**/

        $(function () {

          

              $(window).on('load', function(){

             

            var searchBox = new google.maps.places.SearchBox(document.getElementById('mapSearch'));

            searchBox.addListener('places_changed', function() {

                var places = searchBox.getPlaces();

                if (places.length == 0) {

                    return;

                }

                places.forEach(function(place) {

                    var latitude = place.geometry.location.lat();

                    var longitude = place.geometry.location.lng();

                    $(".latitude").val(latitude);

                    $(".longitude").val(longitude);

                    var address = $("#mapSearch").val();

					// alert(location_order);  

					// if(latitude>0 && longitude>0)

					// {

						// if(location_order==1)

						// {

						 // calculatedisatace(latitude,longitude);

						// }

					// }

                });

				

            });

            });

        });

    </script>

<script>

   

    var $discount1;

    var userID = "<?php echo $_SESSION['login']; ?>";

   // var user_mobile=$('#mobile_number').val();

   $(".skip").click(function(){

	  $('#newmodel_check').modal('hide');

	  $('#PasswordModel').modal('hide');

	  // alert(show_pop);

			// nextstep();

		});

    // $("#mapSearch").focusout(function(){

		// var address=$('#mapSearch').val();

	// });

	    

    $("#apply_coupon").click(function(){

		var s_flag=true;   

		var product_qty=$('.product_qty').val();

		 var number=$('#mobile_number').val();

		 // alert(number);

		if ((product_qty == null) || (product_qty=='')){

			// alert('Without Prouct add cant able to go ahead.');

				$('#show_msg').html("<?php echo $language['no_product_added']; ?>"); 

			 $('#AlerModel').modal('show');

			

			var s_flag=false;

			return false;  

		  }

		if(number.length >= 9 && number.length <= 12 && (number[0] == 1 || number[0]==6)){

		  }

		  else

		  {

			  $('#mobile_number').focus();

			   var s_flag=false;

		  }

		  

        var coupon = $('#coupon_code').val();

        var sum = 0;

		if(coupon=='')

		{

			 $('#coupon_code').focus();

			   var s_flag=false;

		}

		if(s_flag)

		{

			var inps = document.getElementsByName('p_total[]');

			for (var i = 0; i <inps.length; i++) {

				var inp=inps[i];

				sum += parseFloat(inp.value);

			}  

			var total_amount = sum;

			$.ajax({

				url: 'apply_coupon.php',

				type: 'POST',

				data:{coupon:coupon,user:userID, amount:total_amount,user_mobile:number},

				dataType:'json',

				success: function(response) {

					var result = JSON.parse(JSON.stringify(response));

					if(result.status){

						

						$('#coupon_id').val(result.id);

						$('#coupon_min_value').val(result.min);

						$('#coupon_discount_amount').val(result.price);

						$('#coupon_max_value').val(result.max);

						$('#coupon_type').val(result.type);

						totalcart();

					}
					else
					{

						

					}

					$('#coupon_message').html(result.data);

					$('#coupon_message').show();

				}

			});

		}   

    });
</script>   
<script src="https://cdn.rawgit.com/mattdiamond/Recorderjs/08e7abd9/dist/recorder.js"></script>
<script src="js/sketch_new.js"></script>


