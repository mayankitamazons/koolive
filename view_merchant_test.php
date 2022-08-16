<?php
include ("config.php");
include_once ('php/Section.php');

/* START: Rewrite URL*/
if($_GET['shopname'] && $_GET['shopname']!= '' ){
    $shopSlug = $_GET['shopname'];
    $check_slug_exists = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SQL_NO_CACHE * FROM users WHERE slug='".$shopSlug."' AND user_roles = 2"));
    $_GET['sid'] = $check_slug_exists['mobile_number'];
}
if($_GET['product'] && $_GET['product']!= '' ){
    $productSlug = $_GET['product'];
    $check_product_slug_exists = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SQL_NO_CACHE * FROM products WHERE product_slug='".$productSlug."'"));
    $_GET['pd'] = $check_product_slug_exists['id'];
}


/* END: Rewrite URL*/

include_once ('IPay88.class.php'); //include payment gateway Files
$ipay88 = new IPay88('M31571'); // MerchantCode
$ipay88->setMerchantKey('IzXIhfJHUJ'); /*YOUR_MERCHANT_KEY*/
$ipay88->generateSignature();
$ipay88_fields = $ipay88->getFields();
$payment_signature = $ipay88_fields['Signature'];

// Load merchant's product with QR
$p_status = '';
$dine_in = "n";

if (!empty($_GET['status']))
{
    $p_status = $_GET['status'];
}

$mobile_otp_verify = "n";
// print_r($_SESSION);
// die;
if (isset($_GET['sid']))
{
	session_start();
	$_SESSION['AjaxCartResponse']['merchant_id'] = $_GET['sid'];
	//$_SESSION['AjaxCartResponse']['merchant_id'] = $_SESSION['merchant_id'];
	$cart_merchant_id = $_GET['sid'];
} 
if (isset($_GET['code']) && isset($_GET['id']) && is_numeric($_GET['id']))
{
    // print_r($_GET);
    // die;
    $code = $_GET['code'];

    $apiusername = $_GET['apiusername'];

    $user_id = $_GET['id'];

    if (!isset($_GET['apiusername']))
    {

        // echo "SELECT * FROM users WHERE verification_code='$code' AND id='$user_id'";
        // die;
        $if_exists = mysqli_num_rows(mysqli_query($conn, "SELECT SQL_NO_CACHE * FROM users WHERE verification_code='$code' AND id='$user_id'"));

        // echo $if_exists;
        // die;
        if ($if_exists > 0)
        {

            mysqli_query($conn, "UPDATE users SET password_created='y',otp_verified='y',verification_code='', isLocked='0' WHERE id='$user_id'");

            //$error = "You have verified your account successfully. Now You can login to use our service.<br>";
            

            
        }
        else
        {

            $error = "Your Link is Expire,Contact Support<br>";
        }

        $loginmatch = mysqli_query($conn, "SELECT SQL_NO_CACHE * FROM users WHERE  id='$user_id'");

        // echo $if_exists;
        $user_row = mysqli_fetch_assoc($loginmatch);

        $session_id = uniqid($id . "_", true);

        $setup_session = "n";

        $id = $user_id;

        $mobile_otp_verify = "y";

        $_SESSION['user_id'] = $id;

        $_SESSION['login'] = $id;

        $_SESSION['login_user_role'] = $user_row['user_roles'];
    }
}

// print_r($_SESSION);
// die;
$shopclose = [];
function checktimestatusnew($time_detail)
{
	
    global $shopclose;

    extract($time_detail);

    //           echo "day=$day n=$n";
    //          echo "ctime:".date("H:i");
    $day = strtolower($day);

    $currenttime = date("H:i");
	// echo "</br>";
	// echo $starttime;
	// die;
    $n = strtolower(date("l"));

    //if (($currenttime > $starttime && $currenttime < $endttime) && ($day == $n))
    if ((strtotime($currenttime) > strtotime($starttime) && strtotime($currenttime) < strtotime($endttime)) && ($day == $n))
    {

        //$shop_close_status="y";
        array_push($shopclose, "y");

    }

    else

    {

        //$shop_close_status="n";
        array_push($shopclose, "n");

    }
    // print_R($shopclose);
    return $shopclose; //$shop_close_status;
    
}  
$r_code=$_GET['r_code'];
if (empty($_GET['ms']))
{
    $sid = $_GET['sid'];
    $data = $_GET['data'];
    $url_product_id = $_GET['pd'];
    if($_GET['r_code'])
        $url = "view_merchant.php?sid=" . $sid . "&ms=" . md5(rand())."&r_code=".$r_code;
    else 
     $url = "view_merchant.php?sid=" . $sid . "&ms=" . md5(rand());

    // $url = "view_merchant.php?sid=" . $sid . "&ms=" . md5(rand());
 if ($url_product_id) $url .= "&pd={$url_product_id}";
 if ($data) $url .= "&data={$data}";
    // echo $url;
    // die;
 header("Location:$url");
 exit();
}
$custom_msg = "n";
$merchant_city = '';
if (!empty($_GET['sid']))
{

    $sid = $_GET['sid'];

    $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SQL_NO_CACHE * FROM users WHERE mobile_number='$sid' and user_roles='2'"));

    $merchant_detail = $product;

    $merchant_name = $product['name'];

    $merchant_mobile_number = $product['mobile_number'];

    $merchant_city = $product['city'];

	//echo $merchant_city;

    if ($merchant_mobile_number == "60127771833")

        $_SESSION["langfile"] = "chinese";

    $_SESSION['invitation_id'] = $product['referral_id'];

    $_SESSION['merchant_id'] = $product['id'];

    $_SESSION['address_person'] = $product['address'];

    $_SESSION['latitude'] = $product['latitude'];

    $_SESSION['longitude'] = $product['longitude'];

    $_SESSION['IsVIP'] = $product['IsVIP'];

    $_SESSION['mm_id'] = $product['id'];

    $_SESSION['block_pay'] = "n";
}

//check offer available for city or not
$offer_one = '2';//no
$offer_two =  '2';//no
if($merchant_city != ''){
	$city_query = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SQL_NO_CACHE * FROM city WHERE CityName='".$merchant_city."'"));
	
	$offer_one = $city_query['offer_one']; //15 minutes offer
	$offer_two = $city_query['offer_two']; //48 hour offer
	
	//echo "=".$offer_one;

}

if (!empty($_GET['m_id']))
{

    $m_id = $_GET['m_id'];

    $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SQL_NO_CACHE * FROM users WHERE id='$m_id' and user_roles='2'"));

    $merchant_detail = $product;

    $merchant_name = $product['name'];

    $merchant_mobile_number = $product['mobile_number'];

    if ($merchant_mobile_number == "60127771833")

        $_SESSION["langfile"] = "chinese";

    $_SESSION['invitation_id'] = $product['referral_id'];

    $_SESSION['merchant_id'] = $product['id'];

    $_SESSION['address_person'] = $product['address'];

    $_SESSION['latitude'] = $product['latitude'];

    $_SESSION['longitude'] = $product['longitude'];

    $_SESSION['IsVIP'] = $product['IsVIP'];

    $_SESSION['mm_id'] = $product['id'];
}

if (!empty($_GET['oid']))
{

    $_SESSION['block_pay'] = "y";
}

// End of Hire's work
if (isset($_GET['q']) && $_GET['q'] == "verifyAgentCode")
{

    $code = $_GET['code'];

    $mobile = $_GET['mobile'];

    $mobile_check = "60" . $mobile;

    // set limit for agent as per specific merchant
    $merchant_id = $_SESSION['merchant_id'];

    // $total_order = mysqli_fetch_assoc(mysqli_query($conn,"SELECT count(id) as total_order from order_list where agent_code!='' and user_mobile='$mobile_check'"))['total_order'];
    $total_order = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SQL_NO_CACHE count(id) as total_order from order_list where  user_mobile='$mobile_check'")) ['total_order'];

    if ($total_order < 5)
    {

        $rows = mysqli_num_rows($q);

        $q = mysqli_query($conn, "SELECT SQL_NO_CACHE agent_id FROM agent_code WHERE code = '$code' ORDER BY date DESC LIMIT 1");

        $rows = mysqli_num_rows($q);

        if ($rows == 0)

            die("false");

        $id = mysqli_fetch_assoc($q) ['agent_id'];

        $q2 = mysqli_query($conn, "SELECT SQL_NO_CACHE code FROM agent_code WHERE agent_id='$id' ORDER BY date DESC LIMIT 1");

        $q2_code = mysqli_fetch_assoc($q2) ['code'];

        if ($q2_code == $code)
        {

            die("true");
        }
        else
        {

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

if (isset($_GET['q']) && $_GET['q'] == 'getNumAgentTransactions')
{

    $mobile_phone = 60 . $_GET['phone_no'];

    $u_id = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SQL_NO_CACHE id FROM users WHERE mobile_number = '$mobile_phone'")) ['id'];

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

if ($sectionsList)
{

    // $sectionTableFilter['section_id'] = (array_keys($sectionsList))[0];

}

// $sectionTablesList = $sectionTablesObj->getList($sectionTableFilter);
$bank_data = isset($_SESSION['login']) ? mysqli_fetch_assoc(mysqli_query($conn, "SELECT SQL_NO_CACHE * FROM users WHERE id='" . $_SESSION['login'] . "'")) : '';

$check_number = $bank_data['mobile_number'];
if($bank_data)
{
  $cq="select coupon.* from coupon_specific_allot inner join coupon on coupon_specific_allot.coupon_id=coupon.id where coupon_specific_allot.user_mobile_no='$check_number'";
  $special_coupon=mysqli_query($conn,$cq);
  $special_coupon_count = mysqli_num_rows($special_coupon);
  $special_coupon_list = mysqli_fetch_assoc($special_coupon);



}
$total_work = "n";
$user_koo_coin = $bank_data['balance_inr'];
$today_day=strtolower(date('l'));
$sql1 = "SELECT SQL_NO_CACHE * FROM `timings` WHERE day='$today_day' and `merchant_id` =" . $_SESSION['merchant_id'];
$tresult1 = mysqli_query($conn, $sql1);
$totaltiming = mysqli_num_rows($tresult1);
if ($totaltiming > 0)
{
    while ($ti = mysqli_fetch_assoc($tresult1))
    {

        $time_detail['day'] = $ti['day'];
        $time_detail['starttime'] = $ti['start_time'];
        $time_detail['endttime'] = $ti['end_time'];
        // print_R($time_detail);
        $tworking = checktimestatusnew($time_detail);

    }

    foreach ($tworking as $w)
    {

        if ($w == "y")
        {
            $total_work = "y";
        }

    }
}
else
{
    $total_work = "n";

}

// print_R($timingdetail);
// die;
$check_number = str_replace("60", "", $check_number);

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
$share_url = "https://www.koofamilies.com/" . "view_merchant.php?sid=" . $sid . "&vs=" . md5(rand());
$default_lang = $merchant_detail['default_lang'];  
if($default_lang==1)
{
  $langfile="english";
} else if($default_lang==2)
{
  $langfile="chinese";
} else if($default_lang==3)
{
  $langfile="malaysian";
}
$_SESSION["langfile"] =$langfile;
$login_user_id=$_SESSION['user_id'];
$q="select * from special_coin_wallet where merchant_id='6419' and user_id='$login_user_id'";
										#echo $q;
$u=mysqli_query($conn,$q);
$s_data=mysqli_fetch_assoc($u);
										  // print_R($s_data);
$koo_balance=$s_data['coin_balance'];
$s_coin_name="Koo Cashback";
?>
<?php 
$m_name = str_replace("'", ' ', $merchant_detail['name']);
$m_name=clean($m_name);

?>
<?php
if (isset($_GET['data']))
{
    $dine_in = "y";
    $getdetail = $_GET['data'];

    $getdetail = base64_decode($getdetail);

    // print_R($getdetail);
    // die;
    $epxplode = explode("hweset", $getdetail);

    // print_R($epxplode);
    // die;
    $section = $epxplode[0];

    $tablenumber = $epxplode[1];
}
if ($merchant_detail['id'] == 5062 || $merchant_detail['id'] == 1107) $dine_in = "y";
?>

<?php $login_user_id = $_SESSION['login'];

if (isset($login_user_id))
{

    $urecord = isset($login_user_id) ? mysqli_fetch_assoc(mysqli_query($conn, "SELECT SQL_NO_CACHE id,user_roles,setup_shop,balance_usd,balance_inr,balance_myr FROM users WHERE id='" . $login_user_id . "'")) : '';

    $balance_inr = $urecord['balance_inr'];

    if ($balance_inr == '')

        $balance_inr = 0;
}

?>

<!DOCTYPE html>

<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">





<head>



    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />

    <meta http-equiv="pragma" content="no-cache" />

    <meta http-equiv="expires" content="0" />

    <meta property="og:title" content="KooFamilies - Discover & Book the best restaurants at the best price">
    <meta property="og:description" content="KooFamilies - Discover & Book the best restaurants at the best price">
    <meta property="og:image" content="https://koofamilies.sirv.com/logo_yellow.jpg">
    <meta property="og:url" content="<?php echo $share_url; ?>">

    <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">

    <?php include ("includes1/v_header.php"); ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.6/dist/jquery.fancybox.min.css">
    <link rel="stylesheet" href="<?php echo $site_url; ?>/extra/css/view_merchant.css">

    <script src="<?php echo $site_url; ?>/js/jquery.fancybox.min.js" defer></script>
    <style>
    #map{ height: 500px; background: #f2f2f2; }
    /* Modal Popup OTP's input style */
    .verfiy_otp_button {
        cursor: pointer;
    }

    .myBtnLogWithOTP {
        cursor: pointer;
    }

    #part_otpInputMP {
      padding-left: 15px;
      letter-spacing: 42px;
      border: 0;
      background-image: linear-gradient(to left, black 70%, rgba(255, 255, 255, 0) 0%);
      background-position: bottom;
      background-size: 50px 1px;
      background-repeat: repeat-x;
      background-position-x: 35px;
      outline: 0;
      width: 250px;
      min-width: 220px;
  }

  #divInnerMP {
      left: 0;
      position: sticky;
  }

  #divOuterMP {
      margin-top: 20px;
      width: 190px; 
      overflow: hidden;
  }

  .resend_label {
    cursor: pointer;
}
/* Modal Popup OTP's input style */

</style> 
<!-- Images Loaded js -->

<script src="<?php echo $site_url; ?>/js/imagesloaded.pkgd.min.js" defer></script>
<script>
    function merchantclose() {
        $(".modal.in").removeClass("in").addClass("fade").hide();

        $(".modal-backdrop").remove();

        $('#merchant_message').modal('hide');
    }
</script>




<script type="text/javascript">
    var subproducts_global = [];

    var products_id_global = [];

    var lastAdd = null;
</script>




<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<!--script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB4BfDrt-mCQCC1pzrGUAjW_2PRrGNKh_U&libraries=places"></script!-->

<!--script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDJaLaYhhq7poU-0_LTu5GOmhYR4b4D0d4&libraries=places"></script!-->

<!-- <link href="location/assets/css/phppot-style.css" type="text/css" rel="stylesheet" /> -->
<!-- <script src="location/vendor/jquery/map.js" type="text/javascript"></script> -->
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyDJaLaYhhq7poU-0_LTu5GOmhYR4b4D0d4&sensor=false&callback=initAutocomplete&libraries=places&v=weekly"></script>
<!-- <script src="location/vendor/jquery/jquery-3.3.1.js" type="text/javascript"></script>  -->
<script src="https://scripts.sirv.com/sirv.js" defer></script>

<link rel="stylesheet" type="text/css" href="<?php echo $site_url; ?>/css/sweetalert.css">
<meta name="theme-color" content="#317EFB" />
<script>
  fbq('track', 'ViewContent');
</script>
</head>



<body class="header-light sidebar-dark sidebar-expand pace-done">

    <?php
    $id = $_SESSION['mm_id'];

    $merchant_detail = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SQL_NO_CACHE * FROM users WHERE id='" . $id . "'"));

// print_r($merchant_detail);
// die;
    $voice_recognition = $merchant_detail['voice_recognition'];
    if ($merchant_detail['id'] == '')
    {

        header("location:".$site_url."/merchant_find.php");
    }

    if ($merchant_detail['id'] == '5062')
    {

        $back_url = $_SERVER['HTTP_REFERER'];

        if (strpos($back_url, "structure_merchant.php") !== false)
        {

            $block_pay = "y";

            $_SESSION['block_pay'] = "y";
        }
        else
        {

            $block_pay = "n";
        }
        if ($_GET['data'] == '')
        {
            $block_pay = "y";

            $_SESSION['block_pay'] = "y";
        }
    }
    else
    {

        $block_pay = "n";

        $_SESSION['block_pay'] = "n";
    }

    $product_zoom = $merchant_detail['product_zoom'];

    $online_pay = 0;

    $discounted_product = $merchant_detail['discounted_product'];

    $online_pay = 0;

    if ($merchant_detail['credit_check'] || $merchant_detail['wallet_check'] || $merchant_detail['boost_check'] || $merchant_detail['grab_check']
     || $merchant_detail['wechat_check'] || $merchant_detail['touch_check'] || $merchant_detail['fpx_check'])
    {

        $online_pay = 1;

    // $payment_alert="y";

    }

    $online_pay = 1;

    $location_order = $merchant_detail['location_order'];

    $koo_wallet = $merchant_detail['koo_wallet'];

    $merchant_id = $merchant_detail['id'];

    $special_coin_name = $merchant_detail['special_coin_name'];

    if ($special_coin_name)
    {

        $special_coin_min = $merchant_detail['special_coin_min'];

        $special_coin_max = $merchant_detail['special_coin_max'];

        if ($_SESSION['login'])
        {

        // echo "SELECT * FROM special_coin_wallet WHERE  merchant_id='$merchant_id' and user_id ='".$_SESSION['login']."'";


            $special_wallet = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM special_coin_wallet WHERE  merchant_id='$merchant_id' and coin_active='y' and coin_balance>=0 and user_id ='" . $_SESSION['login'] . "'"));

        // print_R($special_wallet);
        // die;
            if ($special_wallet)

                $special_bal = $special_wallet['coin_balance'];

            else

                $special_bal = 0;
        }
        else
        {

            $special_bal = 0;
        }
    }
    else
    {

        $special_bal = 0;
    }

    $submerchantsql = mysqli_query($conn, "SELECT SQL_NO_CACHE * * FROM users WHERE mian_merchant='" . $merchant_detail['name'] . "' ");

    $stallcount = mysqli_num_rows($submerchantsql);

// die;
    $location_range = $merchant_detail['location_range'];

// echo json_decode($merchant_detail['custom_message'])->message;
    if ($merchant_detail['custom_message'] != '')
    {

        $merchant_message = $merchant_detail['custom_message'];

        $custom_msg = "y";

        ?>



        <div class="modal-backdrop show"></div>

        <div class="modal in" id="merchant_message" tabindex="-1" role="dialog" data-show="true" style="display:block;">



            <div class="modal-dialog modal-dialog-centered" role="document">

                <div class="modal-content">

                    <div class="modal-header">

                        <h5 class="modal-title" id="exampleModalCenterTitle"><?php echo $merchant_message; ?>
                        <!--span style="color: #f00"><?php echo $merchant_detail['name']; ?></span!-->
                    </h5>

                        <!--button type="button"   class="merchant_close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button!-->



    </div>

    <div class="modal-body">

        <?php if ($merchant_detail['custom_msg_image'])
        { ?>
            <img class="img-responsive Sirv" style="margin:0 auto;" data-src="<?php echo $image_cdn; ?>customimage/<?php echo $merchant_detail['custom_msg_image']; ?>" alt="<?php echo $merchant_message; ?>">



            <?php
        }
        else
            { ?>

                <p><?php echo $merchant_message; ?></p>

                <?php
            } ?>



        </div>

    </div>

</div>

</div>

<?php
} ?>



<div class="pace  pace-inactive">

    <div class="pace-progress" data-progress-text="100%" data-progress="99" style="transform: translate3d(100%, 0px, 0px);">

        <div class="pace-progress-inner"></div>

    </div>

    <div class="pace-activity"></div>

</div>


<div id="wrapper" class="wrapper">

    <!-- HEADER & TOP NAVIGATION -->

    <?php include ("includes1/navbar.php"); ?>

    <!-- /.navbar -->

    <div class="content-wrapper">

        <!-- SIDEBAR -->

        <?php include ("includes1/sidebar.php"); ?>

        <!-- /.site-sidebar -->



        <?php
        $id = $_SESSION['mm_id'];

        $merchant_detail = mysqli_fetch_assoc(mysqli_query($conn, "SELECT about.image as about_image,users.* FROM users LEFT JOIN about on users.id=about.userid WHERE users.id='" . $id . "'"));

        $min_order_amount = $merchant_detail['order_min_charge'];

        if (isset($_SESSION['login']))
        {

            $sql_transaction = "SELECT COUNT(id) ordered_num
            FROM order_list
            WHERE user_id='" . $_SESSION['login'] . "' and merchant_id = '" . $id . "' AND STATUS='1'";

            $result_transaction = mysqli_fetch_assoc(mysqli_query($conn, $sql_transaction));
        }
        else
        {

            $result_transaction = '';
        }

        $sql_favorite = "SELECT COUNT(id) favorite_num
        FROM favorities
        WHERE favorite_id = '" . $id . "'";

        $result_favorite = mysqli_fetch_assoc(mysqli_query($conn, $sql_favorite));

        $business1 = "";

        $business2 = "";

        for ($i = 0;$i < count($nature_array);$i++)
        {

            if ($merchant_detail['business1'] == $nature_array[$i])

                $business1 = $nature_image[$i];

            if ($merchant_detail['business2'] == $nature_array[$i])

                $business2 = $nature_image[$i];
        }

        $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT count(id) as pro_ct FROM products WHERE user_id ='" . $id . "' and status=0"));

        $total_rows = mysqli_query($conn, "SELECT * FROM products WHERE user_id ='" . $id . "' and status=0");

        $favorite = isset($_SESSION['login']) ? mysqli_query($conn, "SELECT * FROM favorities WHERE user_id = " . $_SESSION['login'] . " AND favorite_id = " . $id . "") : '';

        $count = $favorite != '' ? mysqli_num_rows($favorite) : 0;

        ?>





        <main class="main-wrapper clearfix pb-3 view-m-page" style="min-height: 561px;">

            <div class="row" id="main-content" style="padding-top:25px">

                <input type="hidden" id="shop_status" value="<?php echo $merchant_detail['shop_open']; ?>" />
                <input type="hidden" id="direct_sub" value="<?php echo $_GET['direct_sub']; ?>" />



                <?php
                if ($_SESSION['IsVIP'] == 1)
                {

                    ?>



                    <div class="box-right">



                        <div class="title">

                            <div class="title-left"> <img src="<?php echo $site_url; ?>/new/images/merchant.png">
                                <div class="title-h"> <a href="#"> Merchant Name : <?php echo $merchant_detail['name']; ?></a> </div>
                            </div>

                            <div class="title-right">

                                <div class="favorite_icon">

                                    <?php if ($count > 0)
                                    { ?>

                                        <i class="heart fa fa-heart"></i>

                                        <?php
                                    }
                                    else
                                        { ?>

                                            <i class="heart fa fa-heart-o"></i>

                                            <?php
                                        } ?>



                                        <h4 class="starting-bracket" style="display: inline-block;">(</h4>

                                            <?php if ($business1 != "")
                                            { ?>

                                                <img style="margin-top:10px;" class="nature_image" src="<?php echo $site_url; ?>/img/<?php echo $business1; ?>">

                                                <?php
                                            } ?>

                                            <?php if ($business2 != "")
                                            { ?>

                                                <img style="margin-top:10px;" class="nature_image" src="<?php echo $site_url; ?>/img/<?php echo $business2; ?>">

                                                <?php
                                            } ?>

                                            <?php if ($merchant_detail['account_type'] != '')
                                            { ?>

                                                <h4 class="transaction_num"> <?php echo $merchant_detail['account_type']; ?>, </h4>

                                                <?php
                                            } ?>

                                            <h4 class="transaction_num"><?php echo $result_transaction['ordered_num']; ?>, </h4>

                                            <h4 class="favorite_num"><?php echo $result_favorite['favorite_num']; ?>)</h4>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <div class="cont-area3">

                                <div class="white-box">

                                    <div class="btns">

                                        <div class="main-btn"> <a href="<?php echo $site_url; ?>/about_menu.php"><?php echo $language["about_us"] ?></a> </div>

                                        <div class="main-btn1"> <a href="<?php echo $site_url; ?>/rating_list.php"><?php echo $language["rating"] ?> </div>

                                            <?php if (isset($_SESSION['invitation_id']) && (!isset($_SESSION['login'])))
                                            { ?>

                                                <a class="col-md-2" href="<?php echo $site_url; ?>signup.php?invitation_id=<?php echo $_SESSION['invitation_id']; ?>"><img src="<?php echo $site_url; ?>/img/join-us.jpg" style="width: 100px;"></a>



                                                <?php
                                            } ?>

                                        </div>







                                        <div class="clear-both"> </div>



                                        <div class="head-title">Merchant</div>

                                        <div class="main-cont">

                                            It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently.

                                        </div>





                                        <div class="grey-bg">

                                            <div class="grey-left">Merchant Name </div>

                                            <div class="grey-right"> Product No : 0 </div>

                                        </div>



                                    </div>

                                </div>

                            </div>



                            <?php
                        }
                        else
                            { ?>

                                <div class="col-md-12">

                                 <!--------Start ---------->
                                 <?php if ($merchant_detail['mobile_number'] == '') { ?>
                                    <script>
                                        window.location.replace("https://www.koofamilies.com/merchant_find.php");
                                    </script>
                                <?php  } ?>

                                <div class="view-merchant-wrapper" style="margin-top:-10px">
                                 <div class="download-google-wrap" style="margin-bottom:10px">
                                   <a href="https://slack-files.com/TUWLAGXHD-F013R5GMVB9-07b7ebbed4"  target="blank"> <img  src="<?php echo $site_url; ?>/google.png"  alt=""> </a>
                                   <a href="https://apps.apple.com/us/app/id1491595615?mt=8" target="blank">   <img  src="<?php echo $site_url; ?>/appstore.png" alt=""> </a>
                               </div>
								<div style="clearfix:both"></div>
								<br/>
							<div class="view-all-shop" style="margin-bottom:36px">
                                <h6><a href="<?php echo $site_url; ?>/index.php?vs=<?=md5(rand()) ?>" style="padding-right: 4px; padding-left: 5px;right: unset;left: 10px;margin-top:30px;"> <?php echo $language['more_shops']; ?> 
								 <img class="Sirv sirv-image-loaded" data-src="https://koofamilies.sirv.com/shop.png" srcset="https://koofamilies.sirv.com/shop.png?scale.option=fill&amp;scale.width=32&amp;scale.height=32" src="https://koofamilies.sirv.com/shop.png?scale.option=fill&amp;scale.width=32&amp;scale.height=32" style="max-width: 25px;height:25px">
								  <img class="Sirv sirv-image-loaded" data-src="https://koofamilies.sirv.com/shop.png" srcset="https://koofamilies.sirv.com/shop.png?scale.option=fill&amp;scale.width=32&amp;scale.height=32" src="https://koofamilies.sirv.com/shop.png?scale.option=fill&amp;scale.width=32&amp;scale.height=32" style="max-width: 25px;height:25px">
								   <img class="Sirv sirv-image-loaded" data-src="https://koofamilies.sirv.com/shop.png" srcset="https://koofamilies.sirv.com/shop.png?scale.option=fill&amp;scale.width=32&amp;scale.height=32" src="https://koofamilies.sirv.com/shop.png?scale.option=fill&amp;scale.width=32&amp;scale.height=32" style="max-width: 25px;height:25px">
								</a></h6>
                            </div>

                               <div class="view-merchant-image">
                                <div class="bg-image">
                            
<div class="fav-icon favorite_icon" style="right:20px">
                                    <?php if ($count > 0){ ?>
                                        <i class="heart fa fa-heart" style="float:right"></i>
                                    <?php } else{ ?>
                                     <i class="heart fa fa-heart-o" style="float:right"></i>
                                 <?php } ?>
								</div>

                             
							<?php  
$file_pointer = "https://koofamilies.sirv.com/about_images/".$merchant_detail['image'];
$file_about_image = "https://koofamilies.sirv.com/about_images/".$merchant_detail['about_image'];
?>


<?php //echo $rd['image'];
if($merchant_detail['about_image']==""){
	//echo $file_pointer;
	//echo "==".@getimagesize($file_pointer).$file_pointer;
	if($merchant_detail['image']!= '' && @getimagesize($file_pointer)){

	?>   
	<img style=" background-image: url('<?php echo $site_url; ?>/images/bg1.png');" src="<?php echo $image_cdn; ?>about_images/<?php echo $merchant_detail['image']?>?w=200" alt="Image" class="w-100">
	<?php }else{?>
		<img src="<?php echo $site_url; ?>/images/bg1.png" alt="Image" class="w-100">
	<?php
	}
} 
else{  
	?>
		<img  src="<?php echo $image_cdn; ?>about_images/<?php echo $merchant_detail['about_image']?>?w=200" alt="Image" class="w-100">
	<?php 
} 
?>



							<?php /*if($merchant_detail['image']!= '' && file_exists($file_pointer)){?>
                             <img src="https://koofamilies.sirv.com/about_images/<?php echo $merchant_detail['image'];?>?w=200" alt="Image" class="w-100">
                         <?php }if($merchant_detail['about_image']!= '' && file_exists($file_about_image)){?>
                             <img src="https://koofamilies.sirv.com/about_images/<?php echo $merchant_detail['about_image'];?>?w=200" alt="Image" class="w-100">
                         <?php }else{?>
                             <img src="https://koofamilies.com/images/bg1.png" alt="Image" class="w-100">
                         <?php }*/?>
                         <div class="image-icon">
                            <h2><?php echo ucfirst(substr($merchant_detail['name'], 0, 1));?></h2>
                        </div>
                    </div>
                    <div class="place-order-info" style="background-color:#cbdaff">
                        <h4 style="color:red"><?php echo $language['difficulty']; ?> <a href="https://chat.whatsapp.com/Db2atY5JO2v5CMNeiYNs9B" target="_blank">
                            <img src="<?php echo $site_url; ?>/images/whatapp.png" style="max-width:32px;">
                        </a>
						<!--<a data-toggle="modal" data-target="#wechatModal">-->
						<a href="https://koofamilies.sirv.com/qr_wechat.jpeg" target="_blank">
                            <img src="<?php echo $site_url; ?>/img/we-chat.png" style="max-height:26px;">
                        </a>
						<!--<br/>
						<a class="merchant_about" style="background:#dedede;color: black;padding: 8px;border-radius: 9px;" href="https://www.koofamilies.com/about_menu.php"  data-toggle="modal" data-target="#wechatModal">
							<img src="img/wechat.png" style="max-width:32px;">
							WE CHAT
						</a>-->
                    </h4>

                </div>
            </div>
            <h2 class="merchant-title" style="margin-bottom:-5px"><?php echo $merchant_detail['name']; ?>

            <span class="starting-bracket" style="display: inline-block;">(<?php if ($business1 != "") { ?>
                <img class="nature_image" src="<?php echo $site_url; ?>/img/<?php echo $business1; ?>">
            <?php } ?>
            <?php if ($business2 != ""){ ?>
             <img class="nature_image" src="<?php echo $site_url; ?>/img/<?php echo $business2; ?>">
         <?php } ?>
         <?php if ($merchant_detail['account_type'] != ''){ ?>
          <?php echo $merchant_detail['account_type']; ?>,
      <?php } ?>
      <?php if (isset($result_transaction['ordered_num'])) { echo $result_transaction['ordered_num']; } ?>, 
      <?php if (isset($result_favorite['favorite_num'])){echo $result_favorite['favorite_num']; } ?>)
  </span>

  <?php   if ($merchant_detail['chat_with_merchant']){
      if ($merchant_detail['chat_group']){ ?>
         <a class="chk" href="<?php echo $merchant_detail['chat_with_merchant']; ?>" target="_blank">
            <img src="<?php echo $site_url; ?>/images/whatapp.png" style="max-width:32px;">
        </a>
    <?php } else { ?>
     <a class="chk1" href="https://api.whatsapp.com/send?phone=<?php echo $merchant_detail['chat_with_merchant'] ?>" target="_blank"> <img src="<?php echo $site_url; ?>/images/whatapp.png" style="max-width:32px;"></a>
 <?php } } else {
  $chat_merchant_list = array(6958,6956,7634,7785,7799,7839,7808,7818,7846,7912,7953,7837,7209,7462,7209,7723,7674,7663,7726,7703,7554,6960,7658,7662,7462);
  if (in_array($merchant_detail['id'], $chat_merchant_list))
     { ?>
         <a class="chk3" href="https://chat.whatsapp.com/BtuVlvwlwBJA824nRSmYd5" target="_blank"> <img src="<?php echo $site_url; ?>/images/whatapp.png" style="max-width:32px;"></a>
     <?php }else{ ?>
	 <!--
      <a class="chk4" href="https://api.whatsapp.com/send?phone=<?php echo $merchant_detail['mobile_number'] ?>" target="_blank"> <img src="images/whatapp.png" style="max-width:32px;"></a>-->
	  
	   <a class="chk3" href="https://chat.whatsapp.com/BtuVlvwlwBJA824nRSmYd5" target="_blank"> <img src="<?php echo $site_url; ?>/images/whatapp.png" style="max-width:32px;"></a>
  <?php } } ?>
</h2>
										<!--
										<div class="download-google-wrap">
                                                    <a href="https://slack-files.com/TUWLAGXHD-F013R5GMVB9-07b7ebbed4"  target="blank"> <img  src="google.png"  alt=""> </a>
                                                    <a href="https://apps.apple.com/us/app/id1491595615?mt=8" target="blank">   <img  src="appstore.png" alt=""> </a>
                                                </div>-->
                                                <div class="m-btn-box">
                                                    <a class="merchant_about" href="<?php echo $site_url; ?>/about_menu.php"><?php echo $language["about_us"] ?></a>
                                                    <a class="merchant_ratings 3button" href="<?php echo $site_url; ?>/rating_list.php"><?php echo $language["rating"] ?> </a>
                                                </div>
                                            </div>
                                            <?php if ($stallcount > 0){ ?>
                                               <div class="rating_menuss"><a class="merchant_ratings" onclick="our_stall()">Our Stalls </a></div>
                                           <?php } ?>

                                           <!-------ENd------------->



                                           <p style="color:red;" id="error_label"></p>



                                           <!--start - free delivery--->
                                           <style>
                                           @media only screen and (max-width: 761px)
                                           .tip {
                                            margin: 3em 0 2em;
                                        }

                                        .tab_fr {
                                           /* margin-left: 2.5em!important;*/
                                       }

                                       .tip {
                                        background-color: #fffbce;
                                        padding: 0 2em 2em;
                                        /* margin: 3.2em 14em 2em 0;*/
                                        display: table;
                                        margin-top:40px;
                                    }

                                    span.title_fr {
                                        background: #ffed00;
                                        padding: .6em 1.5em;
                                        font-weight: 600;
                                        font-size: 1.13em;
                                        top: -17px;
                                        left: -28px;
                                        display: inline-block;
                                        position: relative;
                                    }

                                    span.title_fr:before {
                                        font-family: FontAwesome;
                                        content: "\f0d1";
                                        margin-right: 7px;
                                        font-size: larger;
                                    }

                                    .tip p {
                                        margin: 0!important;
                                    }
                                    #ten-countdown{
                                     color:red;
                                     font-weight:bold;
                                 }
                             </style>
                             <?php 
                             $terr_id_last_order = '';
                             $lstorder_location  = '';
                             $offer_delivery_discount = 'no';
                             $discount_offer_minus = '0';

                             if (isset($_SESSION['login']))
                             {
                                 $rows_od = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM order_list WHERE user_id= ".$_SESSION['login']." order by created_on desc limit 0,1"));
	//echo "SELECT * FROM order_list WHERE user_id= ".$_SESSION['login']." order by created_on desc limit 0,1";
	$currenttimestamp = date('Y-m-d H:i:s'); //
	$free_delivery_popup = 'no'; 
	
	if(count($rows_od) > 0 ){
		$free_delivery_prompt = $rows_od['free_delivery_prompt'];
		#echo "offer".$free_delivery_prompt;
		$territory_price_array = explode("|",$rows_od['territory_price']);
		$terr_id_last_order = $territory_price_array[0];
		$terr_price = $territory_price_array[1];
		$lstorder_location = $rows_od['location'];
		
		
		$created_timestamp_od = $rows_od['created_on'];
		$curr_time_od = strtotime($currenttimestamp); //currenttime
		$od_timestmp = strtotime($created_timestamp_od); //ordertime
		
		$diff =  round(abs($od_timestmp - $curr_time_od) / 60,2);
		$chk_time = 15 - $diff;
		/*
		echo "currenttimestamp:".$currenttimestamp;
		echo '<br/>';
		
		echo "order time:".$created_timestamp_od;
		echo '<br/>';
		
		echo "last terr id".$terr_id_last_order;
		echo '<br/>';
		
		echo $diff."===".$chk_time;*/
		if($free_delivery_prompt == 0){
			// free delivery
			if($diff < 15){
				// free delivery
				$free_delivery_popup = 'yes';
			}
			if($diff > 15){
				//  NO free delivery
				$free_delivery_popup = 'no';
			}
		}
		
		
		// new code -> 48 hours offer
		//echo "Difference between two dates is " . $hour = abs($curr_time_od - $od_timestmp)/(60*60) . " hour(s)";

		$hour_48 = abs($curr_time_od - $od_timestmp)/(60*60);
		
		$upcoming_48hours = date('Y-m-d H:i:s', strtotime($rows_od['created_on'] . " +48 hours"));

		$discount_offer_minus = '0';
		if($hour_48 >=48){
			$offer_delivery_discount = 'no'; //if total hours more than 48 then no offer in delivery
			$discount_offer_minus = '0';
			
		}else{
			$offer_delivery_discount = 'yes'; //if total hours in 48 hours then -1 discount in delivery
			
			if($offer_two == 1){
				$discount_offer_minus = '1';
			}else{
				$discount_offer_minus = '0';
			}
		}



		//End code 48 hours	
		
		
		//echo "=============".$free_delivery_popup;
	}
}
?>
<script>
    function countdown( elementName, minutes, seconds )
    {
        var element, endTime, hours, mins, msLeft, time;

        function twoDigits( n )
        {
            return (n <= 9 ? "0" + n : n);
        }
        var x = 1;
        function updateTimer()
        {
		//console.log('###test###');
        msLeft = endTime - (+new Date);
        var additonal_delivery_charges = $('#additonal_delivery_charges').val();
        var terr_id_last_order = '<?php echo $terr_id_last_order;?>';
        var terr_id = $(".postcode_main_div").val();
        var terr_price = $(".postcode_main_div").find('option:selected').attr('price_optn');

        var free_delivery_status = $("#free_delivery_status").val();			

		//console.log(x);		
		if ( msLeft < 1000 ) {
			//console.log("111");		
            element.innerHTML = "0:00";
            $(".hurry_div").html('');
            $(".hurry_div").hide();
            $(".hurry_div").css('background-color','none');
			//$(".hurry_div").html('<span class="title_fr">Get Free Delivery!!</span><p>Order Now and get Free Delivery on your next order in 10 Minutes</p>');
			var d_charges = "<?php echo $merchant_detail['order_extra_charge'];?>";
			$("#delivery_label").show();
			$('#additonal_delivery_charges').val(additonal_delivery_charges);
			$("#free_delivery_status").val(0);

			if(d_charges != ''){
				$("#delivery_charges").val(d_charges);
				$("#order_extra_label").html(d_charges);
				$("#order_extra_charge").val(d_charges);
			}else{
				$("#delivery_charges").val('2.99');
				$("#order_extra_label").html('2.99');
				$("#order_extra_charge").val('2.99');
			}
			$("#postcode_delivery_charge_price").html(terr_price);
			$("#postcode_delivery_charge_hidden_price").val(terr_price);
			$("#free_delivery_status").val(0);
			if(x == 1){
				totalcart();
			}
        } else {
			//console.log("222");		
			//$("#territory_hidden_id").val(terr_id);
			//console.log(terr_id_last_order+"====="+terr_id);
			$("#free_delivery_status").val(1);
			if(terr_id_last_order == terr_id){
				//console.log("333");		
				//console.log('#same###');
				$("#delivery_charges").val('0.00');
				$("#delivery_label").show();
				$("#order_extra_label").html('0.00');
				$("#order_extra_charge").val('0.00');
				$("#free_delivery_status").val(1);

				$('#additonal_delivery_charges').val('0.00');
				
				$("#territory_hidden_id").val(terr_id);
				
				$("#postcode_delivery_charge_price").html('0.00');
				$("#postcode_delivery_charge_hidden_price").val('0.00');
				if(x == 1){totalcart();}
			}else{
				//console.log("444");		
				//console.log('nottt');
				$("#free_delivery_status").val(0);

				var d_charges = "<?php echo $merchant_detail['order_extra_charge'];?>";
				$("#delivery_label").show();
				$('#additonal_delivery_charges').val(additonal_delivery_charges);
				if(d_charges != ''){
					$("#delivery_charges").val(d_charges);
					$("#order_extra_label").html(d_charges);
					$("#order_extra_charge").val(d_charges);
				}else{
					$("#delivery_charges").val('2.99');
					$("#order_extra_label").html('2.99');
					$("#order_extra_charge").val('2.99');
				}
				$("#postcode_delivery_charge_price").html(terr_price);
               $("#postcode_delivery_charge_hidden_price").val(terr_price);
               if(x == 1){totalcart();}
           }



           time = new Date( msLeft );
           hours = time.getUTCHours();
           mins = time.getUTCMinutes();
           element.innerHTML = (hours ? hours + ':' + twoDigits( mins ) : mins) + ':' + twoDigits( time.getUTCSeconds() );
           setTimeout( updateTimer, time.getUTCMilliseconds() + 500 );
       }
       x++;
   }

   element = document.getElementById( elementName );
   endTime = (+new Date) + 1000 * (60*minutes + seconds) + 500;
   updateTimer();
}


</script>
<?php if($free_delivery_popup == 'yes' && $offer_one == 1){?>
    <script>
        $(document).ready(function(){
         countdown( "ten-countdown", <?php echo $chk_time;?>, 0 );
     });
 </script>
<?php }?>

<?php 
//echo $offer_one;
if($free_delivery_popup == 'yes' && $offer_one == 1){
	$discount_offer_minus = '0'; //offer 2 not applied;
    ?>
    <div class="tip tab_fr hurry_div" style="display:block;">
     <span class="title_fr"><?php echo $language['hurry_up'];?></span>
     <p><?php echo $language['congrt_text1'];?> 
	 <span id="ten-countdown"></span> 
	 <?php echo $language['minutes_text'];?> 
	 </p>
 </div>

<?php }else{
	//<!-- offer 2--->
	
	if($offer_delivery_discount == 'yes' && $offer_two == 1){?>
		<br/>
		<div class="tip tab_fr hurry_div" style="display:block;">
          <span class="title_fr"><?php echo $language['hurry_up'];?></span>

          <p><?php echo $language['message_48offer']; ?> <span class="" style="font-weight:bold;color:red;"><?php echo date('d F	Y',strtotime($upcoming_48hours));?></span>) </p>
      </div>
  <?php }
	//<!-- End offer 2-->
}/*else{?>
	<br/>
	<div class="tip tab_fr">
	<span class="title_fr">Get Free Delivery!!</span>
	<p>Order Now and get Free Delivery on your next order in 10 Minutes</p>
	</div>
<?php }*/?>
<!-- end - free delivery--->


<?php
$current_time = date("h:i");

$opening_hr = $merchant_detail['start_time_setup'];

$end_hr = $merchant_detail['end_time_setup'];

$date1 = DateTime::createFromFormat('H:i', $current_time);

$date2 = DateTime::createFromFormat('H:i', $opening_hr);

$date3 = DateTime::createFromFormat('H:i', $end_hr);

$go_ahead = true;

if ($merchant_detail['shop_open'] == "0")
{

    $go_ahead = false;
}

if ($go_ahead == true)
{

    ?>



<!-- START: Search--->
<?php
    if ($merchant_detail['menu_type'] == 2){ $viewFile = $site_url.'/view_merchant_layout2_ajax.php'; }
    else{ $viewFile = 'view_merchant_layout1.php'; }
	
	$curr_pageLink = $site_url."/".$_SERVER['REQUEST_URI'];
	$URLENCODE = urlencode($curr_pageLink);
   ?>

<div class="row mb-3">
	<div class="" style="margin-left:20px;display:block">
		<input class="form-control search_bar" id="search_bar" name="search_bar" placeholder="<?php echo $language['search_placeholder'];?>" style="width:146px;float:left;" value="">
		<a class="btn btn-primary search_button" style="margin-left:10px;padding-left:6px;padding-right:6px" viewFile="<?php echo $viewFile;?>" merchant_id = "<?php echo $id;?>" prduct_qty="<?php echo $product['pro_ct'];?>" dataRedirectLink = "<?php echo $URLENCODE;?>" price_hike="<?php echo $merchant_detail['price_hike'];?>" ><?php echo $language['search_btn'];?></a>
		<a href="<?php echo $site_url; ?><?php echo $_SERVER['REQUEST_URI'];?>"  class="btn btn-info fullmenu_option " style="padding-left:6px;padding-right:6px;display:none" ><?php echo $language['fullmenu_option'];?></a>
	</div>
		
</div>
<?php if($merchant_detail['order_min_charge'] > 0){?>
	
<div class="tooo" style="background: red;padding: 10px;font-weight: bold;margin-bottom: 10px;">
  <?php echo $language['minimumn_order'];?> RM <?php echo $merchant_detail['order_min_charge'];?>
</div>
<?php }?>
										
<div class="search_response">


    <?php
    if ($merchant_detail['menu_type'] == 2)
    {

        include 'view_merchant_layout2.php';
    }
    else
    {

        include 'view_merchant_layout1.php';
    }

    ?>


</div>


</div>



<!-- without picture--->

<?php
if ($merchant_detail['menu_type'] == 1)
{

            // echo "SELECT * FROM products WHERE category = '".$sub_cat."' and user_id ='".$id."' and status=0";
            // die;
    $total_rows1 = isset($category) ? mysqli_query($conn, "SELECT * FROM products WHERE category = '" . $sub_cat . "' and user_id ='" . $id . "' and status=0") : [];

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
                $i = 1;

                while ($row = mysqli_fetch_assoc($totalo_rws1))
                {

                // var_dump($row);
                    if ($row['image'] == '')
                    {

                        ?>

                        <tr>

                            <td><?php echo $i; ?> </td>

                            <input type="hidden" id="id" name="m_id" value="<?php echo $id; ?>">



                            <input type="hidden" id="id" name="p_id" value="<?php echo $row['id']; ?>">

                            <td><?php echo $row['product_name']; ?></td>

                            <?php if ($row['varient_exit'] == "y")
                            {
                                $cart_class = "with_varient";
                            }
                            else
                            {
                                $cart_class = "without_varient";
                            } ?>

                            <?php
                            if ($row['on_stock'])
                            {

                                ?>



                                <td class="text_add_cart_without  <?php echo $cart_class ?>" data-id="<?php echo $row['id'] ?>" prd_one_offer="<?php echo $row['one_qty_prd_offer'] ?>" data-code="<?php echo $row['product_type'] ?>" data-pr="<?php echo number_format((float)$row['product_price'], 2, '.', ''); ?>" data-name="<?php echo $row['product_name'] ?>" id="text_without"><?php echo $language["add_to_cart1"];?></td>

                                <?php
                            }
                            else
                            {

                                ?>

                                <p class='no_stock_add_to_cart'><?php echo $language['out_of_stock'];?></p>



                                <?php
                            }

                            ?>

                            <td><?php echo number_format((float)$row['product_price'], 2, '.', ''); ?></td>

                            <td><?php echo $row['remark']; ?></td>

                            <td><?php echo $row['product_type']; ?></td>



                        </tr>

                        <?php $i++;
                    }

                    ?>

                    <?php
                } ?>

            </tbody>

        </table>

    </div>

    <?php
} ?>



<?php /*if ($merchant_detail['mobile_number'] != "60172669613")
{ ?>
    <div class="col-md-12">
        <div class="comm_prd" style="background:#03a9f3">
            <h4 class="head_oth"><?php echo $language["order_direct"]; ?></h4>
            <div class="oth_pr" id="oth_pr"><?php echo $language['order']; ?></div>
        </div>
    </div>

    <?php
}*/ ?>



</div>

</main>

</div>

<!-- adding new-->

<?php $profile_data = isset($_SESSION['login']) ? mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='" . $_SESSION['login'] . "'")) : '';

?>

<main class="main-wrapper clearfix" style="min-height: 522px;padding-right: 13px;">

    <?php if (!isset($profile_data['user_roles']) || $profile_data['user_roles'] == '')
    { ?>

        <form method="post" id="order_place" action="<?php echo $site_url; ?>/order_cash.php">

            <?php
            $stl_key = rand();

            $_SESSION['stl_key'] = $stl_key; ?>

            <input type="hidden" name="stl_key" value="<?php echo $stl_key; ?>">





            <?php
        }
        else
            { ?>

                <form id="order_place" action="<?php echo $site_url; ?>/order_cash.php" method="post">

                    <?php
                } ?>

                <div class="mb-3">
                  <a  class="empty_cart btn btn-danger" style="display:none"><?php echo $language['empty_cart']; ?></a>
                  <?php if($_SESSION['user_id']):?>
                     <a onclick='repeatlastOrder("<?php echo $_SESSION['merchant_id'];?>","<?php echo $_SESSION['AjaxCartResponse']['merchant_id'];?>")' class="repeat_last_order btn btn-primary" style=""><?php echo $language['repeat_last_order']; ?></a>

                 <?php endif;?>
             </div>

				
            <p class=" empty-text empty-text-box" style="margin-top: -17px;margin-bottom: -8px;"><?php echo $language['the_cart_empty']; ?> </p> 


             <!-- New cart Code -->
             <div class="cart-details-wrapper cart_wrapper" id="cartsection">


               <?php 
               $product_pre_exit=0;
               if(count($_SESSION['AjaxCartResponse'][$cart_merchant_id]) >0){
                  foreach($_SESSION['AjaxCartResponse'][$cart_merchant_id] as $cart_key => $cart_val){
					$prd_one_offer = $cart_val['prd_one_offer'];
					$one_product_offer =$merchant_detail['one_product_offer'];	
					$qty_readonly = '';
					if($prd_one_offer == 1 && $one_product_offer == 1){
						$qty_readonly = "readonly='readonly'";
					}
		
                   ?>
                   <div class="cart-detail-row producttr producttr_<?php echo $cart_val['id'];?> ">
				   <?php echo "<input type='hidden' name='rebate_amount[]' class='rebate_amount' value=".$cart_val['rebate_amount']." id='".$cart_val['id']."rebate_amount'><input type='hidden' name='rebate_per[]' value=".$cart_val['rebate_per']." id='".$cart_val['id']."rebate_per'><input type= hidden name='p_id[]' value= ".$cart_val['s_id']."><input type= hidden name='p_code[]' value= ".$cart_val['code']."><input type='hidden' name='ingredients' value='".$cart_val['single_remarks']."'/><input type='hidden' name='varient_type[]' value=".$cart_val['varient_type']."><input type='hidden' id='".$cart_val['extra_child_id']."' name='extra' value='".$cart_val['extra_price']. "'><input type='hidden' style='width:70px;' class='p_total' name='p_total[]' value= ".$cart_val['p_total']." readonly  id='".$cart_val['id']."_cat_total'><input style='width:70px;' type='hidden' name='p_price[]' value='".$cart_val['product_price']."' readonly><input id='no_food_hidden_options_".$cart_val['id']."' type='hidden' name='no_food_hidden_options[]' value='".$cart_val['no_foods_options']."'>				   <input style='width:70px;text-align:right;' type='hidden' name='p_extra' id='".$cart_val['extra_child_id']."' value='".number_format($cart_val['extra_price'], 2, '.', ',')."' readonly>";?>
                    <div class="cart-d-cell c-t-cell">
                       <p class="remove-icon removebutton" remove_productid="<?php echo $cart_val['id'];?>"><i class="fa fa-trash-o" aria-hidden="true"></i></p>
                       <div class="title-qty">


                           <input type="number" <?php echo $qty_readonly;?> name='qty[]' class="mw3 product_qty quatity" min='1' maxlength='3' value="<?php echo $cart_val['quantity'];?>" id='<?php echo $cart_val['id']."_test_athy"?>' onchange='UpdateTotal("<?php echo $cart_val['id'];?>","<?php echo $cart_val['product_price'];?>")'
                           style="width: 25px;padding: 4px;height: 25px;float: right;"/>
                           <p class="prd-name pro1_name"><?php echo $cart_val['only_name'];?>| <?php echo $cart_val['code'];?> </p>
                       </div>
                   </div>


                   <?php if($cart_val['sub_varient']!= ''){?>
                      <div class="varient_list" >
                       <p class="moretext va_sub" id="moretext_<?php echo $cart_val['id']."_".$product_pre_exit;?>" style="display:none"><?php echo $cart_val['sub_varient'];?>
						<?php if($cart_val['no_foods_options'] != ''){?>
						<br/><span><?php echo $cart_val['no_foods_options'];?></span>
						<?php }?>
					   </p>
                       <a class="moreless-button moreless-button_<?php echo $cart_val['id']."_".$product_pre_exit;?>" href="javascript:void(0)" product_id="<?php echo $cart_val['id']."_".$product_pre_exit;?>"><?php echo $language['read_more1'];?></a>
                   </div>
               <?php }?>
			   <?php if($cart_val['sub_varient']== '' && $cart_val['no_foods_options']!= ''){?>
			   <div class="varient_list" >
				 <p class="moretext va_sub" id="moretext_<?php echo $cart_val['id']."_".$product_pre_exit;?>" style="display:none"><?php echo $cart_val['no_foods_options'];?></p>
				 
			    <a class="moreless-button moreless-button_<?php echo $cart_val['id']."_".$product_pre_exit;?>" href="javascript:void(0)" product_id="<?php echo $cart_val['id']."_".$product_pre_exit;?>"><?php echo $language['read_more1'];?></a>
			   </div>
			   <?php }?>


               <div class="cart-d-cell">
                   <!-- <p class="extra-text"><strong>Extra : </strong>0.00 </p>-->
               </div>

               <div class="cart-d-cell">
                   <p class="remark-text"><a href="#remarks_area" data-rid="<?php echo $cart_val['id'];?>" role="button" class="introduce-remarks btn btn-large btn-primary hideLoader" data-toggle="modal"><?php echo (($cart_val['single_remarks'] == '') ? $cart_val['remark_lable'] : $cart_val['single_remarks'])  ?></a> </p>
                   <p class="price-text"><strong><?php echo $language["total1"];?> : </strong>RM <span class="p_span_total <?php echo $cart_val['id'];?>_cat_total"><?php echo number_format($cart_val['p_total'], 2, '.', ',');?></span></p>
               </div>
           </div>
           <?php $product_pre_exit++;
       }
   }?>


</div>

<!-- END Cart code --->



<a href="#main-content" class="backtotop" >
    <p><i class="fa fa-angle-up" aria-hidden="true"></i></p>

    <!--  <p class="" style="width: 12rem !important;text-align:center;font-size: 16px;padding:14px;background-color: #003A66;color: white; font-weight: bold; border-radius: 8px;"><?php echo $language['add_more_order']; ?></p> -->
</a>
<br />



<?php
        // basic required field validation
if ($merchant_detail['section_exit'] == '0' && $merchant_detail['table_exit'] == '0' && $merchant_detail['delivery_address_exit'] == '0')
{

    $all_blank = "y";
}
else
{

    $all_blank = "n";
}

?>

<!-- New Code --->
<div class="delivary-box-wrapper location_merchant">
	<div style="<?php if ($all_blank == "y"){echo "display:none;";} ?>">
		<div class="d-flex tab-delivery">
			<div class="">
				<div class="merchant_select ">
					<input type="checkbox" id="takeaway_select" checked /> <?php echo $language['delivery']; ?>
				</div>
			</div>
			<?php if ($dine_in == "y"){ ?>
				<div class="">
					<div class=" divert">
						<input type="checkbox" id="divein" /> <?php echo $language['dinein_pickup']; ?>
					</div> 
				</div>
			<?php  } ?>
		</div>
		
		<div class="d-flex flex-wrap box-white-with-shadow">
			<div class="w-100">
             <div class="name_mer">
              <div class="w-100 ">
                <div class="form-group d-flex align-items-center">
                 <?php $deliver_place = "Delivery Place"; ?>
                 <label class="head_loca" ><?php echo $language['delivery_location']; ?></label>
                 <input type="hidden" name="user_lat" id='user_lat'>
                 <input type="hidden" id="order_time_shop_on" name="order_time_shop_on"/> 
                 <input type="hidden" name="latitude" class="latitude" value="<?php echo $merchant_detail['latitude']; ?>">
                 <input type="hidden" name="longitude" class="longitude" value="<?php echo $merchant_detail['longitude']; ?>">
                 <?php if($free_delivery_popup == 'yes' && $offer_one == 1){?>
                    <input type="hidden" name="free_delivery_status" id="free_delivery_status" value="1"/>
                <?php }else{?>
                 <input type="hidden" name="free_delivery_status" id="free_delivery_status" value="0"/>
                 <?php if($offer_delivery_discount == 'yes' && $offer_two == 1){?>
                  <input type="hidden" id="offer_delivery_discount_hidden" name="offer_delivery_discount_hidden" value="1" >
              <?php }else{?>
                  <input type="hidden" id="offer_delivery_discount_hidden" name="offer_delivery_discount_hidden" value="0" >
              <?php }?>
          <?php }?>

          <input type="hidden" name="user_long" id='user_long'>
          <div class="fetch-box" style="justify-content: unset;">
            <a  class="btn btn-primary mr-sm-1"  onclick="currentLocation();" style="margin-right:14px"><?php echo $language['fetch_map']; ?></a>
            <a  class="btn btn-primary" onclick="pastaddress();"><?php echo $language['saved_address']; ?></a> 
        </div>
    </div>
    <div class="">
       <div class="w-100">
        <div class="w-100" >
         <input class=" w-100 form-control comment" id="mapSearch" name="location" placeholder="<?php echo $language['full_address']; ?>" style="margin: 0 !important;" value="<?php echo $lstorder_location ;?>">
     </div>
     <div class="w-100" >
         <!-- <div><img src="images/worldwide-location.png"></div> -->
         <small><?php echo $language['location_more']; ?></small>
         <small id="location_error" style="display:none;color:red;font-size:16px;">
		 <?php echo $language['delivery_palce_required']; ?></small>
     </div>
 </div>
</div>
</div>
</div>

<!---Start POstcode-->
<div class=" postalbx">
    <label class="post_code"><?php echo $language['full_postcode_outstation'];?></label>
    <div class="">
     <div  class="">
       <input type="hidden" name="territory_hidden_id" id="territory_hidden_id" value=""/>
       <input type="hidden" name="merchant_territory_hidden_id" id="merchant_territory_hidden_id" value="<?php echo $merchant_detail['m_territory_id'];?>"/>
       <?php 
       $query_new = "select terr.* from territory as terr where t_label = (select t_label from territory where t_id = '".$merchant_detail['m_territory_id']."') order by t_location_name asc";
       $total_rows = mysqli_query($conn,$query_new);?>
       <select name="postcode_main_div" class="postcode_main_div form-control" >
         <option value=""><?php echo $language['full_delivery_charges'];?></option>
         <?php while ($srow=mysqli_fetch_assoc($total_rows)){
           $price = $srow["t_price"] + $merchant_detail['order_extra_charge'];
           $price = number_format($price, 2);
           if($srow["t_id"] == $terr_id_last_order){
               ?>
               <option selected value="<?php echo $srow["t_id"];?>" price_optn='<?php echo $srow["t_price"];?>'><?php echo $srow["t_location_name"].' - RM '.$price;?></option>
           <?php }else{?>
             <option value="<?php echo $srow["t_id"];?>" price_optn='<?php echo $srow["t_price"];?>'><?php echo $srow["t_location_name"].' - RM '.$price;?></option>
         <?php }?>
     <?php }?>
     <option value="-1" price_optn="0.00"><?php echo $language['others_within_kms'];?></option>
 </select>
 <div id="suggesstion-box"></div>
</div>
</div>
</div>
<!-- ENd Postcode-->
<div class="name_remakr" style="margin-top: 15px;">
    <div>
        <label class="head_loca" ><?php echo $language['remark_label']; ?></label>
        <div class="">
            <div >
                <input class="w-100 form-control comment" id="remark_extra" name="remark_extra" placeholder="<?php echo $language['service_time_person']; ?>" style="margin: 0 !important;" value="">

            </div>
        </div>
    </div>
</div>

<!-- Delivery date and time --->
<div class="delivery_date" style="margin-top: 15px;width:100%;display:none">
    <div>
        <label class="head_loca" ><?php echo $language['delivery_time']; ?></label>
		
			<input type="radio" name="del_type" id="now_type" del_type="now_type" class="del_type" checked /> <label style="display: inline;padding: 5px;" for="now_type"> <?php echo $language['delivery_now']; ?> </label>
			<input type="radio" name="del_type" id="later_type" del_type="later_type" class="del_type" /> <label style="display: inline;padding: 5px;" for="later_type"> <?php echo $language['delivery_later']; ?> </label>
			
			<?php  
				$delidate = new DateTime();
				$deliindex = 0;
			?>
			<div class="later_box" style="display:none">
				<select name = "od_delivery_date" id="od_delivery_date" class="form-control" style="
    width: 130px !important;float: left; margin-right: 10px;display:inline-flex">
					<?php while( $deliindex < 4){
						if($deliindex != 0){
							$delidate->modify("+1 day");
						}
						if($deliindex == 0){
							$dellDate = "Today, ".$delidate->format('M d');
						}else if($deliindex == 1){
							$dellDate = "Tomorrow, ".$delidate->format('M d');
						}else{
							$dellDate = $delidate->format('l, M d');
						}
					?>
					<option value="<?php echo $dellDate;?>"><?php echo $dellDate;?></option>
					<?php $deliindex++; }?>
			    </select>
				
				<?php
					//$timeIndex = 0;
					//$ddrange=range(strtotime("00:00"),strtotime("24:00"),15*60);
				?>
				<!--<select name = "od_delivery_time" id="od_delivery_time1" class="form-control" style="width: 130px !important;float: left; margin-right: 10px;display:inline-flex">
				<?php foreach($ddrange as $time){
						if($timeIndex != 96){
							$timeset = date("H:i",$time);
				}?>
					<option value="<?php echo $dellDate;?>"><?php echo $dellDate;?></option>
					<?php $timeIndex++; }?>
				</select>-->
				<input type="text"  name = "od_delivery_time" id="od_delivery_time" class="form-control" style="width:145px;display:inline-flex" Placeholder="Select Time" value="<?php echo date('H:i');?>" />
				</div>
				<!--<input type="time"  minutestep="900" name = "od_delivery_time" id="od_delivery_time" class="form-control" style="width:145px;display:inline-flex" value="<?php echo date('H:i');?>" />-->
    </div>
</div>
<div style=" clear: both;"></div>
<!-- END Delivery date and time -->

<!-- 3 button start--->
<div class="rider-box-wrapper" style="margin-top: 15px;">
    <div class="rider-box">
        <?php if ($merchant_detail['special_price_value'] && $_SESSION['langfile'] != 'malaysian'){ ?>
            <div class="c-man" >
                <div class="special_price_value mm_v">
                   <label class="PillList-item">
                    <input type="checkbox" id="special_delivery">
                    <span class="PillList-label"> <?php echo $language['chinese_delivery']; ?><br/>
                       <span class="speed">(Rm <?php echo number_format($merchant_detail['special_price_value'], 2); ?> <?php echo $language['extra_label']; ?>)</span> 
                       <span class="Icon Icon--checkLight Icon--smallest"><i class="fa fa-check"></i></span>  </span>
                   </label>
               </div>
           </div>
       <?php } ?>
       <?php if ($merchant_detail['speed_delivery']){ ?>
        <div class=" sp_main_div" >
            <div class="speed_price_value mm_v">
               <label class="PillList-item">
                <input type="checkbox" id="speed_delivery">
                <span class="PillList-label"> 
                    <?php echo $language['speed_delivery']; ?> <br/>  <span class="speed">(Rm <?php echo number_format($merchant_detail['speed_delivery'], 2); ?> <?php echo $language['extra_label']; ?>)* </span>
                    <span class="Icon Icon--checkLight Icon--smallest"><i class="fa fa-check"></i></span>  </span>
                </label>
            </div>
        </div>
    <?php } ?>
	<?php if ($merchant_detail['special_price_value'] && $_SESSION['langfile'] != 'malaysian'){ ?>
    <div class="">      
		<div class="">
			<div class="donation_button mm_v">
				<label class="PillList-item">
					<input type="checkbox" id="donation_amount">
					<span class="PillList-label" style="fon-size:16px;"> <?php echo $language['donation_button_text']; ?>
					<span class="Icon Icon--checkLight Icon--smallest"><i class="fa fa-check"></i></span>
					</span>
				</label>
			</div>
		</div>
	</div>
	<?php }else{?>
	<div class="c-man">      
		<div class="donation_button mm_v">
			<label class="PillList-item">
				<input type="checkbox" id="donation_amount">
				<span class="PillList-label" style="fon-size:16px;"> <?php echo $language['donation_button_text']; ?>
				<span class="Icon Icon--checkLight Icon--smallest"><i class="fa fa-check"></i></span>
				</span>
			</label>
		</div>
	</div>
	<?php }?>
</div>
<a href="<?php echo $site_url; ?>/koo_donation.php" target="_blank" style="text-decoration: underline;"><?php echo $language['past_donation_record']; ?></a>
</div>

<!-- 3 button end --->
<!-- Start Section Table -->
<div style="display:none;" id="sec_table_show" style="width:90px;">
  <div style="display:none;" id="section_show" style="width:90px; border: 1px solid red; float: left;">
   <label><?php echo $language['sections']; ?> <br></label>
   <select name="section_type" id="section_type" class="form-control" <?php if ($merchant_detail['section_required'] == "1"){echo "required='required'"; } ?> data-table-list-url="<?php echo $site_url; ?>/table_list.php" style="width:72px;">
    <?php foreach ($sectionsList as $sectionId => $sectionName):
     $isSelected = "";
     if ($section == $sectionId){$isSelected = "selected";}
     ?>
     <option value="<?php echo $sectionId; ?>" <?php echo $isSelected; ?>><?php echo $sectionName; ?></option>
 <?php endforeach; ?>
</select>
</div>
<div style="display:none;float: left;width:115px;" id="table_show">
   <label><?php echo $language['show_table_number']; ?></label>
   <input type="text" class="form-control table" id="table_type" name="table_type" <?php if ($merchant_detail['table_required'] == "1"){echo "required";} ?> value="<?php echo $tablenumber; ?>" />
</div>
</div>

<!-- End section Table-->
</div>
</div>
</div>
<!--- add phone code div here-->
<!-- start login and coupon -->
<div class="box-white-with-shadow" style="padding-bottom:1px">
  <div class="enter-number credentials-container">
  <!-- <div class="form-group d-flex align-items-center " id="mobile_no_label">
    <label class="head_loca mb-0" id="enter_ur_phone_label"><?php echo $language['enter_ur_phone']; ?></label>
    <div class="fetch-box justify-content-end login_view">
        <?php if($_SESSION['user_id']==''){ ?>
            <a href="login.php?redirect=<?php echo urlencode($CurPageURL);?>"><span class="btn btn-primary <?php if($me=="login"){ echo "active";} ?>"><?php echo $language['login'];?></span></a>
        <?php } ?>
    </div>
</div>-->
<!-- phone-->
<div class="phone-info-wrapper">
  <div class="phone-info-box">
    <div class="input-group mb-2 mobile_no_label" style="margin-bottom:0px !important;">
        <div class="input-group-prepend">
            <div class="input-group-text" style="background-color:#51D2B7;border-radius: 5px 0 0 5px;height: 100%;padding: 0 10px;display: grid;align-content: center;">+60</div>
        </div>
        <input type="number" autocomplete="tel" maxlength='10' id="mobile_number" class="mobile_number form-control" value="<?php if ($check_number){echo $check_number;} ?>" placeholder="<?php echo $language['key_in_phone']; ?>" name="mobile_number" required="" />
        
		<a href="javascript:void(0)" class="btn btn-primary send_otp_button" name="send_otp_button" style="display: none;padding-right: 2px; padding-left: 2px;"><?php echo $language['send_otp1'];?></a>
        <?php if($_SESSION['user_id']==''){ ?>
            <a href="<?php echo $site_url; ?>/login.php?redirect=<?php echo urlencode($CurPageURL);?>"><span class="btn btn-primary <?php if($me=="login"){ echo "active";} ?>" style="padding-right: 9px;padding-left: 9px;margin-left: 7px;"><?php echo $language['login'];?></span></a>
        <?php } ?>
		
	
		
    </div>
    <!--<small style="color:black;"> <?php echo $language['we_can_contact']; ?></small>-->
    <small id="mobile_error" style="display: none;color:#e6614f;font-size:20px;font-weight:bold;">
        <?php echo $language['key_in_valid_mobile']; ?>
    </small>
    <?php if ($bank_data){
        $member_id = $bank_data['id'];
        $mobile_check = $bank_data['mobile_number'];
        $merchant_id = $merchant_detail['id'];
        $query = "SELECT user_membership_plan.*, membership_plan.user_id as memberplan_user, membership_plan.* FROM user_membership_plan INNER JOIN membership_plan ON membership_plan.id = user_membership_plan.plan_id WHERE user_membership_plan.plan_active='y' and user_membership_plan.user_id='$member_id' and user_membership_plan.merchant_id = '$merchant_id' ";
        $user_plan = mysqli_fetch_assoc(mysqli_query($conn, $query));
        $defalut_plan = "select count(plan.id) as total_count,u.created from membership_plan as plan inner join user_membership_plan as u on u.plan_id=plan.id where plan.user_id='$merchant_id' and plan.default_plan='y'
        and u.user_id='$member_id'";
        $defalutarray = mysqli_fetch_assoc(mysqli_query($conn, $defalut_plan));
        $defalutplan = $defalutarray['total_count'];
        $created_date = $defalutarray['created'];
        $total_shop = mysqli_fetch_assoc(mysqli_query($conn, "select sum(total_cart_amount) as total_order_amount from order_list where user_id='$user_id' and merchant_id='$merchant_id' and created_on>='$created_date'"));
        if ($total_shop['total_order_amount'])
        {
            $total_shop_amount = number_format($total_shop['total_order_amount'], 2);
            $total_shop = $total_shop['total_order_amount'];
        }
        else
        {
            $total_shop_amount = 0;
            $total_shop = 0;
        }
        $local_coin = mysqli_fetch_assoc(mysqli_query($conn, "select sum(local_coin) as total_coin from local_coin_sync where merchant_id='$merchant_id' and user_mobile='$mobile_check' and order_date>='$created_date'"));
        if ($local_coin['total_coin'] > 0)
        {
            $total_shop = $local_coin['total_coin'] + $total_shop;
        }
        $user_plan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM membership_plan WHERE user_id='$merchant_id' and $total_shop BETWEEN total_min_order_amount AND total_max_order_amount"));
        if ($user_plan && $defalutplan > 0)
        {
            $plan_type = $user_plan['plan_type'];
            if ($plan_type == "fix")
            {
                $plan_label = "Rm " . $user_plan['plan_benefit'] . " off";
            }
            else
            {
                $plan_label = $user_plan['plan_benefit'] . " %";
            }
            ?>
            <small class="row" style="color:red;font-weight:bold;" id="membership_discount">
                You will recieve MemberShip Discount of <?php echo $plan_label; ?> on Total Order
            </small>
            <?php
        }
    }
    else{ ?>
        <small class="row" style="color:red;font-weight:bold;display:none;" id="membership_discount">
            You will recieve MemberShip Discount of on Total Order
        </small>
    <?php  } ?>
</div>

<?php
if (isset($_SESSION['login'])){
   $u_id = $_SESSION['login'];
   $rows = mysqli_num_rows(mysqli_query($conn, "SELECT id,user_id,agent_code FROM order_list WHERE agent_code IS NOT NULL AND NOT agent_code = '' AND user_id='$u_id'"));
}else{
   $rows = 0;
}
?>
<input type="hidden" name="membership_discount_input" id="membership_discount_input" value="<?php echo $plan_label; ?>" />
<input type="hidden" name="membership_applicable" id="membership_applicable" value="<?php if ($plan_label){echo "y";} ?>" />
<input type="hidden" name='varient_must' id='varient_must' />
<?php if ($merchant_detail['order_extra_charge']){ ?>
    <input type="hidden" class="2" name='delivery_charges' value='<?php echo $merchant_detail['order_extra_charge']; ?>' id='delivery_charges' />
<?php } else{ ?>
    <input type="hidden" class="3" name='delivery_charges' value='2.99' id='delivery_charges' />
<?php } ?>
<input type="hidden" name='additonal_delivery_charges' value='<?php echo $merchant_detail['additonal_delivery_charges']; ?>' id='additonal_delivery_charges' />
<input type="hidden" id="deliver_tax_amount" name="deliver_tax_amount" value="0">
<input type="hidden" name='varient_count' value='0' id='varient_count' />
<!-- input for coupon !-->
<input type="hidden" id="coupon_charges">
<input type="hidden" id="coupon_discount_amount">
<input type="hidden" id="coupon_discount_rate" value='0'>
<input type="hidden" id="coupon_discount" name="coupon_discount">
<input type="hidden" id="coupon_min_value">
<input type="hidden" id="coupon_max_value">
<input type="hidden" id="coupon_type">
<input type="hidden" id="system_otp" />
<input type="hidden" id="forgot_selected_wallet" />
<input type="hidden" value='0' id="otp_count" />


<?php if ($merchant_detail['coupon_offer']){ ?>
    <div class="apply-c-wrapper">
        <div class="input-group mb-2" style="margin-bottom:0px !important;">
            <input type="hidden" id="coupon_id" name="coupon_id" />
            <input type="text" autocomplete="tel" maxlength='100' id="coupon_code" class="coupon_code form-control" placeholder="<?php echo $language['enter_promo_code'];?>" name="coupon_code" />
            <div class="apply-box">
               <a class="btn btn-info " id="apply_coupon"><?php echo $language['apply_btn'];?></a>
               <?php if($special_coupon_count>1){ ?>
                <span  class="btn btn-primary coupon_list ml-1" onclick="couponlist();"><?php echo $language['coupon_list']; ?></span>
            <?php } ?>   
        </div>
    </div>
    <div id="coupon_message" style="display:none;color:red;"></div>
</div>
<?php }?>
</div>
<!-- end phone--->

</div>
</div>
<!-- end  login and coupon -->

<!--start total-cart-wrapper -->

<div class="box-white-with-shadow total-cart-wrapper">
    <h3><?php echo $language['order_summary'];?></h3>
    <div style="width:100%">
        <div class="" style="display:none;" id="total_cart_amount_label_show">
            <div class="total-title" >
                <?php $cart_label = $language['total_cart_amount']; ?>
                <?php echo ucfirst(strtolower($cart_label)); ?>:<span> Rm <span id="total_cart_value"><?php echo number_format($merchant_detail['order_extra_charge'], 2); ?></span></span>
                <br/>
                <span class="left_price" style="display:none;color:red"></span> 
            </div>
        </div>
        <div style="display:none;" class="sst_amount_label">
            <div class="total-title"  style="font-size: 15px;color:black;">
                <?php echo $language['service_fee']; ?> <?php echo $merchant_detail['sst_rate'] . " % "; ?>:<span> Rm <span class="sst_amount_value"></span></span>
            </div>
        </div>
        <div class="" style="display:none;" id="special_delivery_label">
            <div class="total-title"   >
                <?php $chinese_man = $language['chinese_delivery'];
                echo ucfirst(strtolower($chinese_man)); ?>: <span>Rm <span id="special_delivery_label_text"></span></span>
            </div>
        </div>
        <div class="" style="display:none;" id="speed_delivery_label">
            <div  class="total-title"  >
                <?php $speed_delivery = $language['speed_delivery'];
                echo ucfirst(strtolower($speed_delivery)); ?>:<span> Rm <span id="speed_delivery_label_text"></span></span>
            </div>
        </div>
        <div class="" style="display:none;" id="donation_label">
            <div class="total-title"   >
                Donation: <span>Rm <span id="donation_label_text"></span></span>
            </div>
        </div>
        <div class="" style="display:none;" id="delivery_label">
            <div class="total-title"   >
                <?php $deliver_charges_label = $language['delivery_charges']; ?>
                <?php echo ucfirst(strtolower($deliver_charges_label)); ?>: <span>Rm <span id="order_extra_label"><?php echo number_format($merchant_detail['order_extra_charge'], 2) - $discount_offer_minus; ?></span></span>
                <br/>
                <span class="free_price" style="display:none;color:red"></span>
            </div>
            <input type="hidden" class="1" name="order_extra_charge" id="order_extra_charge" value="<?php echo number_format($merchant_detail['order_extra_charge'], 2) - $discount_offer_minus; ?>" />
            <input type="hidden" name="special_delivery_amount" id="special_delivery_amount" value="0" />
            <input type="hidden" name="speed_delivery_amount" id="speed_delivery_amount" value="0" />   
            <input type="hidden" name="donation_amount_value" id="donation_amount_value" value="0" />   
            <input type="hidden" name="pickup_type" id="pickup_type" value="takein" />
        </div>
        <!---postcode--->
        <div class="" style="display:none;" id="postcode_delivery_charge">
            <div  class="total-title"   >
                <?php echo $language['outstation_charge']; ?> <span>Rm <span id="postcode_delivery_charge_price">0.00</span></span>
            </div>
            <input type="hidden" name="postcode_delivery_charge_hidden_price" id="postcode_delivery_charge_hidden_price" value="0.00"/>
        </div>
        <!---postcode--->
        <div style="display:none;" class="membership_discount_label">
            <div  class="total-title" >
                <?php echo ucfirst(strtolower($language['membership_discount'])); ?>: <span>Rm <span class="membership_discount_value"><?php echo number_format($merchant_detail['order_extra_charge'], 2); ?></span></span>
            </div>
        </div>
        <div style="display:none;" class="coupon_discount_amount_label">
            <div  class="total-title" >
                <?php echo ucfirst(strtolower($language['coupon_discount'])); ?>:<span> Rm <span class="coupon_discount_amount_value"></span></span>
            </div>
        </div>
        <div style="display:none;" class="delivery_tax_amount_label">
            <div  class="total-title" style="font-weight: bold;font-size: 15px;color:black;">
                <?php echo $language['delivery_service_tax'] ?> <?php echo $merchant_detail['delivery_rate'] . " % "; ?>: <span>Rm <span class="delivery_tax_amount_value"></span></span>
            </div>
        </div>
        <div style="display:none;color:Red;" class="final_amount_label">
            <div class="total-title"  >
                <?php $payable_amount = $language['payable_amount'];
                echo ucfirst(strtolower($payable_amount)); ?>: <span>Rm <span class="final_amount_value"><?php echo number_format($merchant_detail['order_extra_charge'], 2)- $discount_offer_minus;; ?></span></span>
            </div>
        </div>
    </div>
    <div class="checkout-container">
      <button type="button" class="btn btn-primary"  id="order_confirm_btn" session_block_pay ="<?php echo $_SESSION['block_pay'];?>" internet_banking ="<?php echo $merchant_detail['internet_banking'];?>" value="" style="font-size: 1.41rem;">
         <i class="fa fa-cart-plus" aria-hidden="true"></i> &nbsp;&nbsp;<?php echo $language["checkout_button_1"];?>
     </button>

     <div class="row main_box_div mode_btn_hide ">
         <div class="col-xs-12 sub_box_div"><?php echo $language["payment_mode_label"];?></div>
         <div class="row main_btn_div">
            <input value='0' type="hidden" id="total_rebate_amount" name="total_rebate_amount" />
            <input type="hidden" value='0' id="total_cart_amount" name="total_cart_amount" />
            <input type="hidden" value='0' id="delivery_cart_amount" name="delivery_cart_amount" />
            <input type="hidden" value='0' id="final_cart_amount" name="final_cart_amount" />
            <input type="hidden" value='0' id="rem_amount" name="rem_amount" />
            <input type="hidden" value='0' id="payable_amount" name="payable_amount" />
            <input type="hidden" id="wallet_selected" name="wallet_selected" value="n">
            <input type="hidden" id="actual_payment_mode" name="actual_payment_mode" value="">
            <input type="hidden" id="selected_wallet" name="selected_wallet" value="">
            <input type="hidden" id="selected_wallet_bal" name="selected_wallet_bal" value="">
            <input type="hidden" value='<?php echo $login_user_id; ?>' name="login_user_id" id="login_user_id" />
            <input type="hidden" value='<?php echo $login_user_id; ?>' name="login_for_wallet_id" id="login_for_wallet_id" />

            <?php if ($_SESSION['block_pay'] !== "y"){ ?>
              <?php if ($merchant_detail['cash_on_delivery'] == 1){ ?>
               <div class="col-xs-4 pdbtn-10">
                  <button type="submit" class="each_mode_btn_hide block_pay_btn btn-lg btn cash_od_btn btn-block btn-primary showLoader submit_button" name="cashpayment" actual_payment_mode="cash" id="confm" style="display: block;">
                     <i class="fa fa-money"></i>&nbsp;&nbsp;<?php echo $language["confirm_order"]; ?>
                 </button>
             </div>
         <?php }else{?>
          <div class="col-xs-4 pdbtn-10" >
              <a  class=" show_restriction each_mode_btn_hide block_pay_btn btn-lg btn btn-block btn-primary" mo_type="page" name="" actual_payment_mode="cash" style="display: block;background-color: #ef6264;border-color: #ef6264; width: 268px !important; margin-top: 15px;box-shadow: -3px 3px #fa7953, -2px 2px #fa7953, -1px 1px #fa7953;opacity:0.5">
               <i class="fa fa-money"></i>&nbsp;&nbsp;<?php echo $language["confirm_order"]; ?>
           </a>
       </div>

   <?php }?>
<?php }?>
<?php  if ($merchant_detail['internet_banking']){ ?>
   <div class="col-xs-4 pdbtn-10">
      <button type="submit" actual_payment_mode="internet_banking_free" class="each_mode_btn_hide banking_od_btn mode_btn_hide internet_banking_btn btn btn-block btn-primary submit_button internet_banking btn-lg" name="internet_banking" style="display: block;white-space: initial;">
         <i class="fa fa-bank"></i>&nbsp;&nbsp;<?php echo $language['internet_banking'];?>
		 
		<a class="showing_internetfree_offer" style="display: inline-flex;" href="javascript:void(0)" title="<?php echo $language['internet_free_tooltip'];?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo $language['internet_free_tooltip'];?>">
					<span class="media align-items-center">
					  <span class="fa fa-info-circle mr-3"></span>
					</span>
		</a>
     </button>
 </div>
<?php }?>

<div class="col-xs-4 pdbtn-10">
  <button type="button" actual_payment_mode="internet_banking_fpx" class="each_mode_btn_hide mode_btn_hide btn btn-block btn-primary submit_button btn-lg internet_banking_fpx banking_od_btn" name="internet_banking_fpx" style="display: block;">
     <i class="fa fa-bank"></i>&nbsp;&nbsp;<?php echo $language['internet_bank_fpx'];?>
 </button>
</div>

<div class="col-xs-4 pdbtn-10">
  <button type="submit" actual_payment_mode="wallet" class="each_mode_btn_hide wallet_od_btn mode_btn_hide third_order_btn btn btn-block btn-primary submit_button online_pay btn-lg" name="cashpayment" style="display: block;">
     <i class="fa fa-shopping-bag"></i>&nbsp;&nbsp<?php if ($login_user_id){ echo $language['confirm_wallet_login'];
     echo " (Rm " . number_format($koo_balance, 2) . ")";  }else { echo $language['confirm_wallet_login'];/*echo $language['confirm_wallet_login']."<span class='wallet_ajax_value'>".$language['confirm_wallet_login_only_member']."</span>";*/   }?>
 </button>
</div>
<?php if ($voice_recognition){ ?>
  <div class="col-xs-4">
   <div class="microphne-sec" style="width: 50px;margin-left: 10px;cursor: pointer;" id="m_click">
    <img src="<?php echo $site_url; ?>/images/Microphone.png" class="img-responsive microphone_click" id="microphone_click">
</div>
</div>
<?php } ?>

</div>
</div>
</div>
</div>

<!-- ENd total-cart-wrapper-->

</div>
<br/>
<b><?php echo $language['footer_important_notes']; ?></b><br/>
<span style="color:red;font-size: 17px;"><?php echo $language['sms_text']; ?></span> <br/>
<span style="color:#03a9f3"><?php echo $language['customer_urged']; ?></span><br/>
<p><?php echo $language['speed_text']; ?></p>
<span style="color:#ca1592;"> <?php echo $language['we_can_contact']; ?></span><br/>

<span id="cash_order_process" style="color:red;display:none;font-weight:bold;">Please wait......., we are processing your order..</span>
<!-- End Code --->
<a href="https://chat.whatsapp.com/FdbA1lt6YQVBNDeXuY7uWd" target="_blank"><img src="<?php echo $site_url; ?>/images/iconfinder_support_416400.png" style="width:75px;height:75px;position: fixed;left:15px;bottom: 70px;z-index:999;"></a>

<div class="location_merchant">
    <input type="hidden" id="id" name="m_id" value="<?php echo $id; ?>">
    <input type="hidden" id="r_code" name="r_code" value="<?php echo $r_code; ?>">
    <input type="hidden" id="agent_code" name="agent_code">
    <input type="hidden" name="options" value="" />
    <input type="hidden" name="price_extra" value="" />
    <input type="hidden" id="myr_input_bal" name="myr_input_bal" value="<?php echo $urecord['balance_myr']; ?>" />
    <input type="hidden" id="usd_input_bal" name="usd_input_bal" value="<?php echo $urecord['balance_usd']; ?>" />
    <input type="hidden" id="inr_input_bal" name="inr_input_bal" value="<?php echo $balance_inr; ?>" />
    <input type="hidden" id="special_input_bal" name="special_input_bal" value="<?php echo $special_bal; ?>" />
    <input type="hidden" id="koo_balance" name="koo_balance" value="<?php echo $koo_balance; ?>" />
    <input type="hidden" name="hidden_final_cart_price" id="hidden_final_cart_price" value="0"/>
</div>
<a href="#cartsection"><img src="<?php echo $site_url; ?>/images/cartnew.png" style="width:75px;height:75px;position: fixed;right: 10px;bottom: 70px;z-index:999;"></a>
</form>

<!-- Start Hidden field for ipay88 payment-->
<?php
$ipay88->setField('RefNo', 'IPAY0000000001');
$ipay88->setField('Amount', '1.00');
$ipay88->setField('Currency', 'myr');
$ipay88->setField('ProdDesc', 'koofamilies_merchnt_product');
$ipay88->setField('UserName', 'usernumber1');
$ipay88->setField('UserEmail', 'email@example.com');
$ipay88->setField('UserContact', '0123456789');
				/*$ipay88->setField('CCName', 'test');
				$ipay88->setField('ccno', '4444333322221111');
				$ipay88->setField('S_bankname', 'eWAY');
				*/$ipay88->setField('Remark', 'this is testing product and orderid #1');
				$ipay88->setField('Lang', 'utf-8');
				$ipay88->setField('ResponseURL', 'https://koofamilies.com/payment_temp_resp.php');
				//$ipay88->setField('BackendURL', 'https://koofamilies.com/payment_backresp.php');

				$ipay88->generateSignature();

				$ipay88_fields = $ipay88->getFields();
				?>
				<?php if (!empty($ipay88_fields)): ?>
					<form action="<?php echo Ipay88::$epayment_url; ?>" method="post" id="ipay88_form">
						<?php foreach ($ipay88_fields as $key => $val): ?>
                           <input type="hidden" name="<?php echo $key; ?>" id="<?php echo $key; ?>" value="<?php echo $val; ?>" />
                       <?php endforeach; ?>
                       <INPUT type="hidden" name="BackendURL" value="https://koofamilies.com/payment_backresp.php">
                       <input type="hidden" value="Submit" name="Submit"  />
                   </form>
               <?php endif; ?>
               <!-- END Hidden field for ipay88 payment-->


               <form id="paypal_form" action="<?php echo $paypalUrl; ?>" method="post">

                <div class="panel price panel-red" >

                    <input type="hidden" name="business" value="<?php echo $paypalId; ?>">

                    <input type="hidden" name="cmd" value="_xclick">

                    <input type="hidden" name="item_order_id">

                    <input type="hidden" name="item_name" value="koofamilies Order">

                    <input type="hidden" name="item_number" value="1">

                    <input type="hidden" name="no_shipping" value="1">

                    <input type="hidden" name="currency_code" value="MYR">

                    <input type="hidden" name="cancel_return" value="<?php echo $paypal_cancel_url . "&sid=" . $merchant_detail['mobile_number']; ?>">

                    <input type="hidden" name="return" value="<?php echo $paypal_success_url; ?>">





                    <input style="display:none;" type="number" class="form-control" id="paypal_amount" name="amount" placeholder="Amount (in MYR)">

                    <br><br>



                </div>

            </form>

            <?php
        // ---------------------------
        // Start of DrakkoFire's code
        // Remark Project
        // ---------------------------

            ?>

            <?php
        }
        else
            { ?>

                <h5 class="favorite_name" style="display: inline-blick;margin-left:1%;">We are temporary closed for online order now. Please try later. Sorry for any inconvenient caused !</h5>



                <?php
            } ?>

            <div id="remarks_area" class="modal fade">

                <div class="modal-dialog">

                    <div class="modal-content">

                        <div class="modal-header">

                            <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> -->

                            <h4 class="modal-title"><?php echo $language['remarks']; ?></h4>

                        </div>

                        <div class="modal-body">

                            <?php
                            $ingredients = json_decode(mysqli_fetch_row(mysqli_query($conn, "SELECT preset_words FROM users WHERE id='$id'")) [0]);

                            foreach ($ingredients as $ingredient)
                            {

                                if (!empty($ingredient))
                                {

            // $ingName = (sizeof(explode("[", $ingredient)) > 1) ? explode("[", $ingredient)[0] : $ingredient;
                                    $ingName = $ingredient->name;

                                    $ingPrice = ($ingredient->price == 0) ? null : $ingredient->price;

            // $ingPrice = (!empty(explode("]",explode("[", $ingredient)[1])[0])) ? explode("]",explode("[", $ingredient)[1])[0] : null;

                                    ?>

                                    <div style="margin: 10px 8px;" class="btn-group" data-toggle="buttons" data-subcategory='<?php echo $ingredient->subcategory; ?>'>

                                        <label class="btn btn-secondary">

                                            <input type="checkbox" name="ingredient" value="<?php echo $ingName; ?>" autocomplete="off">

                                            <?php
                                            echo ucfirst(str_replace("_", " ", $ingName));

                                            ?>

                                        </label>

                                        <?php if (!is_null($ingPrice))
                                        { ?><div class='extra-price-ingredient'><?php echo number_format($ingPrice, 2, ".", ""); ?></div><?php
                                    } ?>

                                </div>

                                <?php
                            }
                        }

                        ?>

                        <input type="hidden" name="remark" id="remark_input" class="form-control" style="margin: 10px 0" placeholder="Write here your remarks" />

                        <div id="small_text" style="position:relative;margin-top:2em;width:100%;">

                            <small style="position: absolute;bottom: 2px;left: 5px;font-weight: bold;color:red;"><?php echo $language['note_remarks']; ?></small>

                        </div>

                    </div>

                    <div class="modal-footer" style="position: relative;">

                        <button type="button" id="reset_remark" class="btn btn-danger hideLoader" data-dismiss="modal"><?php echo $language['cancel']; ?></button>

                        <button type="button" class="btn btn-success save_close hideLoader" data-dismiss="modal">

                            <?php echo $language['save_and']; ?>

                            <p class="text_add_cart" style="width: 20px; height: 20px; font-size: 12px;padding: 4px 0 0 0;">

                                <i class="fa fa-plus"></i>

                            </p>

                        </button>

                        <?php if ($merchant_detail['mobile_number'] != "60172669613")
                        { ?>

                            <button type="button" class="btn btn-default manual_input hideLoader"><?php echo $language['manual_input']; ?></button>

                            <?php
                        } ?>

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



 <!-- Modal -->
  <div class="modal fade" id="wechatModal" role="dialog" style="margin: 12px;">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
		  <h4 class="modal-title">WeChat <img src="<?php echo $site_url; ?>/img/we-chat.png" style="max-height:26px;"></h4>
          <button type="button" class="close" data-dismiss="modal" style="top: -9px; right: -9px;">&times;</button>
        </div>
        <div class="modal-body">
          <p><img src="https://koofamilies.sirv.com/qr_wechat.jpeg"></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Skip</button>
        </div>
      </div>
      
    </div>
  </div>
  
    </main>

    <!-- /.widget-body badge -->

</div>

<!-- /.widget-bg -->

<?php
} ?>

<!-- /.content-wrapper -->



<?php include ("includes1/v_footer.php"); ?>
<?php //include("includes1/view_merchant_footer.php");

?>
<script type="text/javascript" src="<?php echo $site_url; ?>/js/jquery.lazy.min.js"></script>


<div class="modal fade" id="ProductModel" role="dialog" style="width:100%!important;"> 

    <div class="modal-dialog modal-dialog-centered">

        <!-- Modal content-->

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal">&times;</button>

                <h4 class="modal-title">Product Varieties For <br /> <span id="varient_name" style="font-weight:bold;"></span></h4>



                <p id="varient" style="display:none;"></p>

                <p id="varient_type" style="display:none;"></p>

            </div>

            <form id="data">

                <div class="modal-body product_data" style="padding-bottom:0px;max-height:50vh;overflow-x: auto;">




                    <div id="product_main" class="ingredients_container">



                    </div>



                    <div class="product_extra">

                        <input id="p_pop_price" type="hidden" />

                        <table border="1px solid" style="width:80%;color:black;">

                            <tr>
                                <td> <?php echo $language['product_name'];?> </td>
                                <td> Rm </td>
                            </tr>

                            <tbody id="product_table">



                            </tbody>



                            <tr>
                                <td> <b> <?php echo $language['total'];?> : </b></td>
                                <td id="pr_total"></td>
                            </tr>

                            <tbody>
                                <tr>
                                    <td><?php echo $language['remarks'];?> :</td>
                                    <td id="remark_td"></td>
                                </tr>
                            </tbody>

                        </table>

                        <br />



                        <!--p id="pr_total"></p!-->



                    </div>





                </div>
                <p id="varient_error" style="color:red;display:none;">Please select at least one choice. Thank</p>
				<style>
				.wthvarient #pop_cart{
					margin-right: -24px !important;
					
				}
				.cart-d-cell i {
					background: #e97070 !important;
				}

				</style>
<!-- Start:with varient : no food code --->
<!-- END:with varient : no food code --->

			<div style="border-top: 1px solid #e9ecef !important;padding-top: 10px !important;<?php if($merchant_detail['no_product_options'] == 1 ){?>width: 316px;margin-left: -19px;border-top:none;padding:0px;<?php }?>" class="modal-footer product_button pop_model pop_model_withvarient">
	</div>	

                <br />

            </form>

        </div>

    </div>

</div>

<div class="modal fade" id="free_trial_model" role="dialog">

    <div class="modal-dialog">





        <!-- Modal content-->

        <div class="modal-content">



            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal">&times;</button>

                <h4 class="modal-title"></h4>

            </div>



            <div class="modal-body" style="padding-bottom:0px;">

                <div class="col-md-12" style="text-align: center;">

                    <h5><?php echo $language['free_trial']; ?></h5>

                    <button type="button" class="btn btn-primary" id="verifybutton" onclick="verifiedmobile()">Verify Now</button>

                    <span class='alert' id="resend_link_label" style="display:none;">Resend Link Shared to mobile Number</span>

                    <input type="hidden" id="verifiedmobile" />

                </div>

            </div>

            <div class="modal-footer" style="padding-bottom:2px;">



            </div>



        </div>

    </div>

</div>

<div class="modal fade" id="show_new_label" role="dialog">

    <div class="modal-dialog">





        <!-- Modal content-->

        <div class="modal-content">



            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal">&times;</button>

                <h4 class="modal-title"></h4>

            </div>



            <div class="modal-body" style="padding-bottom:0px;">

                <div class="col-md-12" style="text-align: center;">

                    <h5><?php echo $language['same_order_not_allowed'];?></h5>



                </div>

            </div>

            <div class="modal-footer" style="padding-bottom:2px;">



            </div>



        </div>

    </div>

</div>

<div class="modal fade" id="location_map_fetch" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body" style="padding-bottom:0px;padding: 0px;">
                <div class="col-md-12" >
                  <h4 style="text-align: center;">Select Location</h4>
                  <div id="map_loacation_warper">
                    Marked Location:<input class="input-box-330" type="text" name="location" id="location" value="" readonly>
                    
                    <a class="btn btn-primary btn-sm" class="close" data-dismiss="modal" onclick="set_location_to_main_field()">Add  location</a>

                    <input type="hidden" id="lat" name="lat" class="hidden" placeholder="lat" value=" <?php if( $locationData){ echo $locationData['lat'];}?>" >
                    <input type="hidden" id="lng" name="lng" class="hidden" placeholder="lng" value="<?php  if( $locationData) {echo $locationData['lng'];}?>">
                </div>
                <div class="col-md-12" style="margin-top: 10px;">
                  <div id="map"></div>
              </div>

          </div>
      </div>
  </div>
</div>
</div>
<div class="modal fade" id="past_address_section" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">SAVED ADDRESSES</h4>
            </div>
            <div class="modal-body" style="padding-bottom:0px;padding: 0px;">


                <div class="col-md-12" id="past_address_div" style="padding-bottom:2%;">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="coupon_list_section" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Special coupons </h4>
            </div>
            <div class="modal-body" style="padding-bottom:0px;padding: 0px;">


                <div class="col-md-12" id="coupon_list_div" style="padding-bottom:2%;">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="location_model" role="dialog">

    <div class="modal-dialog">





        <!-- Modal content-->

        <div class="modal-content">



            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal">&times;</button>

                <h4 class="modal-title"></h4>

            </div>



            <div class="modal-body" style="padding-bottom:0px;">

                <div class="col-md-12" style="text-align: center;">

                    <h5 id="error_location">This merchant require your permission for location in order to place order, </h5>

                    <button type="button" class="btn btn-primary" onclick="clearhistory()">How to clear Cache</button>



                </div>

            </div>

            <div class="modal-footer" style="padding-bottom:2px;">



            </div>



        </div>

    </div>

</div>

<div class="modal fade" id="clear_history_model" role="dialog">

    <div class="modal-dialog">





        <!-- Modal content-->

        <div class="modal-content">



            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal">&times;</button>

                <h4 class="modal-title"></h4>

            </div>

            <div class="modal-body" style="padding-bottom:0px;padding: 0px;">

                <div class="col-md-12" style="text-align: center;">



                    <h4 class="">

                    How to clear your app cache data?</h4>

                    <div style="text-align:left;">

                        <p>Please follow these steps below:-</p>



                        <ul style="margin-left: 0px;padding: 0px;">

                            <li><strong>Mobile Browser</strong>:-



                                <ul style="list-style-type: lower-alpha;">

                                    <li>Click on the left hand side Lock icon in address bar.</li>

                                    <li>Select Site Settings</li>



                                    <li>Go to Permission and Select Location Permission </li>

                                    <li>Allow that and press back setting Button</li>



                                </ul>

                                <li><strong>Web Desktop</strong>:-



                                    <ul style="list-style-type: lower-alpha;">

                                        <li>Click on the left end hand Lock icon in address bar.</li>

                                        <li>Select Site Settings or Clear Cookies and Site Data.. </li>



                                        <li>Remove Cahce and Cookies for KooFamiles</li>

                                        <li>Click ok, after that it will again permission allow now</li>

                                    </ul>



                                    <li><strong>Android App</strong>:-



                                        <ul style="list-style-type: lower-alpha;">

                                            <li>Go to ‘Settings’ and tap on ‘Apps’.</li>

                                            <li>Select KooFamiles</li>

                                            <li>On the ‘App Info’ interface, tap on ‘Storage’</li>

                                            <li>Clear your cache by tapping on the ‘Clear Cache’ button</li>

                                        </ul>

                                    </li>

                            <!--li><strong>iOS</strong>:-<br>
                                            There is no manual way to clear app cache data for iOS. The only solution to clear it is to delete the KooFamiles app and reinstall it again.</li>
                                            !-->

                                        </ul>

                                    </div>

                                </div>

                            </div>



                        </div>

                    </div>

                </div>

                <div class="modal fade" id="paypal_model" role="dialog">

                    <div class="modal-dialog">





                        <!-- Modal content-->

                        <div class="modal-content">



                            <div class="modal-header">

                                <button type="button" class="close" data-dismiss="modal">&times;</button>

                                <h4 class="modal-title"></h4>

                            </div>



                            <div class="modal-body" style="padding-bottom:0px;">

                                <div class="col-md-12" style="text-align: center;">

                                    <h5>

                                        Something Went Wrong to Complete Payment Try Again or Use cash method to complete it

                                    </h5>



                                </div>

                            </div>

                            <div class="modal-footer" style="padding-bottom:2px;">

                                <button id="paypal_cash" type="button" class="btn btn-primary" data-dismiss="modal" style="width:50%;margin-bottom: 3%;text-align: center;">Pay Cash</button>

                                <button id="paypal_close" type="button" class="btn btn-primary" data-dismiss="modal" style="width:50%;margin-bottom: 3%;text-align: center;">Close</button>

                            </div>



                        </div>

                    </div>

                </div>

                <div class="modal fade" id="shop_model" role="dialog" style="margin-top:15%;">>

                    <div class="modal-dialog">





                        <!-- Modal content-->

                        <div class="modal-content">



                            <div class="modal-header">

                                <button type="button" class="close" data-dismiss="modal">&times;</button>

                                <h4 class="modal-title"></h4>

                            </div>



                            <div class="modal-body" style="padding-bottom:0px;">

                                <div class="col-md-12" style="text-align: center;">

                                    <h5 id="shop_model_text">

                                        <?php if ($merchant_detail['mobile_number'] != "60172669613")
                                        { ?>

                                            Sorry, we are currently closed now. You can still place order for later

                                            <?php
                                        }
                                        else
                                            { ?>

                                                Our online order is from 8:00pm to 11.00pm only. please contact our waiter for your order.

                                                <?php
                                            } ?>

                                        </h5>
                                        <p style="font-weight:bold;color:black;" id="off_line_proceed"><?php echo $language['w5']; ?> </p>
                                        <!--h4> Do you want to visit other shops?</h4!-->



                                    </div>



                                </div>

                                <div class="modal-footer" style="padding-bottom:2px;">

                                    <div class="row">

                    <!--div class="col-md-4">
                             <button type="button" class="btn btn-primary redirect_fav" data-dismiss="modal" style="width:50%;margin-bottom: 3%;text-align: center;">Yes</button> 
                         </div!-->


                         <div class="col-md-6">
                            <button type="button" class="btn btn-primary" id="yes_button" data-dismiss="modal" style="margin-left:19%;width:50%;margin-bottom: 3%;text-align: center;color:black"><?php echo $language['yes']; ?></button>

                        </div>

                        <div class="col-md-6">
                            <button type="button" class="btn btn-primary" id="no_button" style="margin-left:19%;width:50%;margin-bottom: 3%;text-align: center;color:black;"><a href="<?php echo $site_url; ?>/index.php" style="color:black;"><?php echo $language['no']; ?></a></button>

                        </div>



                    </div>

                </div>



            </div>

        </div>

    </div>



    <div class="modal fade" id="work_model" role="dialog">

        <div class="modal-dialog">





            <!-- Modal content-->

            <div class="modal-content">



                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                    <h4 class="modal-title"></h4>

                </div>



                <div class="modal-body" style="padding-bottom:0px;">

                    <div class="col-md-12" style="text-align: center;">

                        <h5>

                            Sorry,Shop is Close Now

                        </h5>



                    </div>

                </div>

                <div class="modal-footer" style="padding-bottom:2px;">

                    <button id="close_shop" type="button" class="btn btn-primary" data-dismiss="modal" style="width:50%;margin-bottom: 3%;text-align: center;">Close</button>

                </div>



            </div>

        </div>

    </div>

    <div class=" modal fade" id="ProductAdded" role="dialog">

        <div class="element-item modal-dialog modal-dialog-centered" style="position: absolute;top: 0;bottom: 0;left: 0;right: 0;display: grid;align-content: center;">

            <!-- Modal content-->

            <div class="element-item modal-content">

                <div class="element-item modal-header">


                    <h5 class="product_name_popup" style="font-size:22px"></h5>
                    <button type="button" class="close" data-dismiss="modal" style="background:black;margin-right:2px">&times;</button>





                </div>

                <p class="product_price_popup" style="padding-top:10px;padding-bottom:10px;font-size: 18px;
"><b><?php echo $language['the_product_popup_price']; ?></b> RM <span class="span_price_popup"></span></p>
                <span style="border-bottom:1px solid #e9ecef;margin:-1px calc(-25px - 1px) 0"></span>

			<!--<p style="padding:10px;margin:0px;text-align:center;"><?php echo $language['the_product_added']; ?></p>
	
               <span style="border-bottom:1px solid #e9ecef;margin:-1px calc(-25px - 1px) 0"></span>-->

				<!-- Start without varient : no food code --->
				<?php //if($_SESSION['user_id'] == 6347){?>
					<?php if($merchant_detail['no_product_options'] == 1 ){?>
					<!--<span style="font-weight:bold;font-size: 11px; color: gray;">If this product is not available</span>-->
					<select name="no_foods_options" id="no_foods_options" class="form-control no_foods_options" style="margin-top: 7px;height: 29px;font-size: 13px;margin-bottom: -12px;padding: 0px;">
						<option value=""><?php echo $language['food_not_availble_label'];?></option>
						<option value="<?php echo $language['food_not_availble_option1'];?>"><?php echo $language['food_not_availble_option1'];?></option>
						<option value="<?php echo $language['food_not_availble_option2'];?>"><?php echo $language['food_not_availble_option2'];?></option>
						<option value="<?php echo $language['food_not_availble_option3'];?>"><?php echo $language['food_not_availble_option3'];?></option>
						<option value="<?php echo $language['food_not_availble_option4'];?>"><?php echo $language['food_not_availble_option4'];?></option>
					</select>
					<?php }?>
				<?php //}?>
				<!-- END : no food code-->


               <div id="without_varient_footer" class="modal-footer model_pop" style="padding-bottom:2px;border-top:none !important;">

                <input type="hidden" id="pop_ok" name="pop_ok">


                <button role="button" style="min-height:40px;position:static !important;" class="introduce-remarks btn btn-large btn-primary hideLoader" data-toggle="modal" data-target="#remarks_area" disabled=""><?php echo $language['remarks']; ?></button>

                <button role="button" class="close_pop btn btn-large btn-primary" style="background:#50D2B7;border:none;"><?php echo $language['ok']; ?></button>

            </div>

        </div>

    </div>

</div>

<div class=" modal fade" id="AlerModel" role="dialog" style="width:80%;min-height: 200px;text-align: center;margin:8%;">

    <div class="element-item modal-dialog modal-dialog-centered" style="position: absolute;top: 0;bottom: 0;left: 0;right: 0;display: grid;align-content: center;">

        <!-- Modal content-->

        <div class="element-item modal-content">

            <div class="element-item modal-header">

                <button type="button" class="close" data-dismiss="modal">&times;</button>





            </div>

            <p id="show_msg" style="font-size:22px;font-weight:bold;"><?php echo $language['the_product_added']; ?></p>



        </div>

    </div>

</div>
<div class=" modal fade" id="ProductshowModel" role="dialog" style="width:80%;min-height: 200px;text-align: center;margin:8%;">

    <div class="element-item modal-dialog modal-dialog-centered" style="position: absolute;top: 0;bottom: 0;left: 0;right: 0;display: grid;align-content: center;">

        <!-- Modal content-->

        <div class="element-item modal-content">


            <p id="show_msg_product" style="font-size:22px;font-weight:bold;"><?php echo $language['the_product_added']; ?></p>



        </div>

    </div>

</div>

<div class="modal fade" id="map_model" role="dialog" style="margin-top:35%;">

    <div class="modal-dialog">





        <!-- Modal content-->

        <div class="modal-content">



            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal">&times;</button>

                <h4 class="modal-title"></h4>

            </div>



            <div class="modal-body" style="padding-bottom:0px;">

                <div class="col-md-12" style="text-align: center;">

                    <h5> Sorry, you need to be within <span id='map_range'> </span> km to place order</h5>



                </div>

            </div>

            <div class="modal-footer" style="padding-bottom:2px;">



            </div>



        </div>

    </div>

</div>







<div class="modal fade" id="our_stall" role="dialog">

    <div class="modal-dialog">

        <!-- Modal content-->

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal">&times;</button>

                <h4 class="modal-title">Sub Merchant List of <?php echo $merchant_detail['name']; ?> </h4>

            </div>

            <div class="modal-body" style="padding-bottom:0px;">

                <div class="col-sm-10">

                    <div class="form-group">





                        <div class="container">



                            <table class="table table-striped submer">

                                <thead>

                                    <tr>

                                        <th>Submerchant Name</th>

                                        <th>Favorite</th>

                                    </tr>

                                </thead>



                                <tbody>

                                    <form id="sub_mer_form" action="<?php echo $site_url; ?>/structure_merchant.php" method="post">



                                        <?php
                                        $sql = mysqli_query($conn, "SELECT * FROM users WHERE mian_merchant='" . $merchant_detail['name'] . "' ");

                                        while ($data = mysqli_fetch_array($sql))
                                        { 

                                            $fav = mysqli_query($conn, "SELECT count(*) as number FROM `favorities` WHERE `favorite_id`='" . $data['id'] . "'");

                                            $cu = mysqli_fetch_array($fav);

                                            $sql2 = mysqli_query($conn, "SELECT * FROM users WHERE name='" . $data['name'] . "' ");

                                            $m = mysqli_fetch_array($sql2);

                                            echo '<tr value=' . $m['id'] . ' onclick="getId(this)"><td>' . $data['name'] . ' </td><td>( ' . $cu['number'] . ' ) <i style="font-size: 17px;" class="heart fa fa-heart"></i> </td><input type="hidden" name="merchant_id" id="merchant_id" value=""><input type="hidden" name="sub_mer_id" id="sub_mer_id" value="sub_mer_id"></tr>';
                                        }

                                        ?>



                                    </form>



                                </tbody>



                            </table>

                        </div>







                    </div>

                </div>

            </div>

            <div class="modal-footer" style="padding-bottom:2px;">

                <!--<button type="button" class="btn btn-primary" data-dismiss="modal">No</button> <a href="view_merchant.php"><input type="button" class="btn btn-primary" value="Yes"></a>-->

            </div>

        </form>

    </div>

</div>

</div>

<div class="modal fade" id="login_passwd_modal" tabindex="-1" role="dialog" aria-labelledby="login_passwd_modal_title" aria-hidden="true">

    <div class="modal-dialog" role="document">

        <div class="form-group">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">Enter your phone number</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                        <span aria-hidden="true">&times;</span>

                    </button>

                </div>

                <form action="" method="post" id="cred_ajax_popup">

                    <div class="modal-body">

                        <div class="form-group">

                            <label for="check_phone">Phone number</label>

                            <input type="text" id="check_phone" class="form-control" name="check_phone" />

                            <div class="passwd_field">

                                <label for="login_password">Password</label>

                                <input type="password" id="login_password" class="form-control" name="login_password" required />

                                <small class="wrong_login" style="display: none;color:#e6614f;">

                                    Something went wrong! Try again

                                </small>

                                <small class="acc_blocked" style="display: none;color:#e6614f;">

                                    This account is blocked, please contact support.

                                </small>

                                <small class="reg_pending" style="display: none;color:#e6614f;">

                                    This account is waiting for activation.

                                </small>

                                <small class="logged-in" style="display: none;color:#e6614f;">

                                    You are already logged into this account

                                </small>

                                <small class="success_login" style="display: none;color:#28a745;">

                                    Successfully logged in!

                                </small>

                            </div>

                        </div>

                    </div>

                    <div class="modal-footer login_footer">

                        <div class="row" style="margin: 0;">

                            <div class="col" style="padding: 0;margin: 5px;">
                                <input type="submit" class="btn btn-primary" name="login_ajax" value="<?php echo $language['login']; ?>" style="width:100%;" />

                            </div>

                            <div class="col" style="padding:0; margin: 5px;">

                                <div class="btn facebook-login" style="width:100%;height:100%;"></div>

                            </div>

                            <div class="col-sm-4" style="padding: 0;margin: 5px;">

                                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="width:100%;">Exit</button>

                            </div>

                        </div>

                    </div>

                    <div class="modal-footer register_footer" style="display: none;">

                        <div class="row" style="margin: 0;">

                            <div class="col" style="padding: 0;margin: 5px;">

                                <input type="submit" class="btn btn-primary" name="register_ajax" value="Register" style="width:100%;" />

                            </div>

                            <div class="col" style="padding:0; margin: 5px;">

                                <div class="btn facebook-login" style="width:100%;height:100%;position:relative;"></div>

                            </div>

                            <div class="col-sm-4" style="padding: 0;margin: 5px;">

                                <button id="continue_guest" type="button" class="btn btn-secondary" data-dismiss="modal" style="width:100%;">Continue as Guest</button>

                            </div>

                        </div>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

<!-- login popup for rebeat process!-->

<div class="modal fade" id="LesaaAmountModel" role="dialog" style="z-index:999999;margin-top:15%;">

    <div class="modal-dialog">

        <div class="modal-content" id="modalcontent">

            <div class="modal-header">



                <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>





            <div class="modal-body">

                <!--p style="font-size:18px;">Insufficient balance to pay. Please select other wallet or <a href="https://api.whatsapp.com/send?phone=<?php echo $merchant_detail['mobile_number'] ?>" target="_blank">contact us at whatapp

                    <img src="images/whatapp.png" style="max-width:32px;" /></a> to top up </p!-->

                    <p style="font-size: 14px;">
                      <span style="font-weight: bold;"><?php  echo $language['i_1'];?> <span style="color:red;font-weight:bold;" class="koo_check_wallet"> RM 0.0 </span>

                      <?php  echo $language['i_2'];?> </span><br>	
                      The balance of RM <span id="payable_bal" style="color:red;"></span> can be paid by <span class="btn btn-large btn-danger make_payment showLoader8">cash</span> or <span class="bank_model btn btn-primary"><u>internet banking</u></span> </br>
								<!--button class="btn btn-large btn-danger make_payment showLoader8">Yes</button>

                                   <button class="btn btn-large btn-primary extracss" data-dismiss="modal">Cancel</button!-->
                                   <br><?php  echo $language['i_3'];?>
                                   <a href="https://chat.whatsapp.com/DqyK2iwui4IL8kkHClRXkU" target="_blank"> <img src="images/whatapp.png" style="max-width:32px;" /> https://chat.whatsapp.com/DqyK2iwui4IL8kkHClRXkU</a><br>

                                   Note: <br>
                                   <?php  echo $language['i_4'];?><br>
                                   <?php  echo $language['i_5'];?><br>
                               </p>  



                           </div>



                       </div>

                   </div>

               </div>

               <div class="modal fade" id="WalletModel" role="dialog" style="z-index:999999;margin-top:3%;">

                <div class="modal-dialog">

                    <div class="modal-content" id="modalcontent">

                        <div class="modal-header">



                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                                <span aria-hidden="true">&times;</span>

                            </button>

                        </div>



                        <div class="modal-body">

                            <p style="font-size:18px;">Wallet Feature is Only Applicale for Register Member,We are Processing as Cash Wallet </p>

                            <button class="btn btn-large btn-primary" data-dismiss="modal">ok</button>

                        </div>



                    </div>

                </div>

            </div>

            <div class="modal fade" id="ProccedAmount" role="dialog" style="z-index:999999;margin-top:3%;">

                <div class="modal-dialog">

                    <div class="modal-content" id="modalcontent">

                        <div class="modal-header">



                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                                <span aria-hidden="true">&times;</span>

                            </button>

                        </div>



                        <div class="modal-body">

                            <p style="font-size:18px;" id="amount_label"> </p>

              <!--  <button class="btn btn-large btn-danger make_payment showLoader8">CASH</button>

				<span class="bank_model btn btn-primary"><u>Internet Banking(Free)</u></span>
				
				<span class="bank_model btn btn-primary"><u>Internet Banking(FPX)</u></span>
            -->


            <div class="row" style="margin: 0;padding: 0;">
               <div class="col-12 col-sm-6 cash_wallet_f_main" style="margin: 0px;padding: 0;margin-top: 10px;">
                  <span class="btn btn-primary make_payment showLoader8 cash_wallet_f" style="width: 98%;padding-left:11px;">Place order With Cashback<?php //echo $language['login_with_otp']; ?></span>
                  <?php if ($_SESSION['block_pay'] !== "y"){ ?>
                      <?php if ($merchant_detail['cash_on_delivery'] == 1){ ?>
                          <span class="btn btn-primary make_payment showLoader8 no_need_paymnetoptions" style="width: 98%;padding-left:11px;background: #03a9f3;border-color: #03a9f3;"><?php echo $language["confirm_order"]; ?></span>
                      <?php }else{?>
                          <a  class=" show_restriction no_need_paymnetoptions btn btn-primary " mo_type="wallet" name="" actual_payment_mode="cash" style="width: 98%;padding-left:11px;background: #03a9f3;border-color: #03a9f3;opacity:0.5">
                           <?php echo $language["confirm_order"]; ?>
                       </a>
                   <?php } }?>
               </div>

               <div class="col-12 <?php //if ($merchant_detail['cash_on_delivery'] == 1){ ?>col-sm-6<?php //}?>" style="margin: 0;padding: 0;margin-top: 10px;">
                  <span class="btn btn-primary bank_model no_need_paymnetoptions"  style="width: 98%;padding-left:11px;background: #68e0ae;border-color: #68e0ae;"><?php echo $language['internet_banking'];?></span>
              </div>

              <div class="col-12 col-sm-12" style="margin: 0;padding: 0;margin-top: 10px;">
                  <span class="btn btn-primary no_need_paymnetoptions internet_banking_fpx"  style="width: 98%;padding-left:11px;">Internet Banking(FPX)<?php //echo $language["login_password"]; ?> </span>
              </div>

              <div class="col-12 col-sm-12" style=" margin: 0;padding: 0;margin-top: 10px;">
                  <button class="btn btn-large btn-primary" style="width: 98%;padding-left:11px;background: border-box;;border-color: green; color: gray;" data-dismiss="modal">Cancel </button>
              </div>
          </div>





          <p>

              <br><?php  echo $language['i_3'];?>
              <a href="https://chat.whatsapp.com/DqyK2iwui4IL8kkHClRXkU" target="_blank"> <img src="images/whatapp.png" style="max-width:32px;" /> https://chat.whatsapp.com/DqyK2iwui4IL8kkHClRXkU</a><br>

              Note: <br>
              <?php  echo $language['i_4'];?><br>
              <?php  echo $language['i_5'];?><br>
          </p>



      </div>  



  </div>

</div>

</div>
<style>
.pad0{
	padding:0!Important;
}</style>

<style>
.trasfer_complete:hover{
	color:white;
}
.trasfer_complete{
	background-color: red !important;
    height: 52px  !important;
    width: 100%  !important;
    font-size:15px !important;
}

.or_p{
	text-align: center;
    padding-top: 10px;
    padding-bottom: 10px;
    margin-bottom: 0 !important;
    font-weight: bold;
    font-size: 20px;
}
.back_btn_m{
	background-color: #fb9678 !important;
    height: 52px  !important;
    width: 100%  !important;
    font-size:15px !important;
}
.back_btn_m:hover{
	color:white;
}

</style>
<?php if ($_SESSION["langfile"] == "malaysian"){?>
    <style>
    .back_btn_m{
     font-size:12px !important;
 }
 .trasfer_complete{
     font-size:12px !important;
 }
</style>
<?php }?>   
<div class="modal fade" id="InternetModel" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="form-group">
            <div class="modal-content">
                <div class="modal-header" style="padding-top: 0;padding-bottom: 1px;">
                 <h5><?php echo $language['label_pay_with_internet_banking']; ?></h5>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pad0" >
             <div class="container-fluid pad0">
              <div class="row">
               <h6 style="font-size:16px;margin-top:8px"> <?php echo $language['please_pay_exact_amount']; ?> <span style="color:red">Rm <span style="font-weight:bold;color:red" class="final_amount_value internet_free_modal_amount"></span>
			   
				<a class="showing_internetfree_offer" style="display: inline-flex;" href="javascript:void(0)" title="<?php echo $language['internet_free_tooltip'];?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo $language['internet_free_tooltip'];?>">
					<span class="media align-items-center">
					  <span class="fa fa-info-circle mr-3"></span>
					</span>
				</a>
				
               </span></h6>
               <span class="paid_wallet_if_popup"></span>
               <input type="hidden" id="payment_mode_cashback" value="0" >
               <?php if ($merchant_detail['id'] == '5062'){ ?>
                   <div class="col-md-11 pad0">
                    <b><?php echo $language['name']; ?>:</b>  Lim Foot Ping <br/>
                    <b><?php echo $language['label_bank_name']; ?>:</b>  Maybank <br/>
                    <b><?php echo $language['label_bank_account']; ?>:</b>  511234010582 <br/>
                    <?php if ($_SESSION["langfile"] == "chinese"){echo "请写商家店名在“银行参考”";}else{?>(Please write <?php echo $merchant_detail['name']; ?> in "bank reference")<?php }?></br>
                    <b><?php echo $language['payable_amount']; ?></b>  Rm <span style="font-weight:bold;" class="final_amount_value"></span> <br/>
                    <b><?php echo $language['label_enquiry']; ?></b>  <a href="https://api.whatsapp.com/send?phone=601159223660" target="_blank"> 601159223660 <img src="https://www.koofamilies.com/images/whatapp.png" style="max-width:32px;" /> </a></br>
                </div>
            <?php }else{?>
               <div class="col-md-12 pad0">
                <b><?php echo $language['name']; ?>:</b> Chong Woi Joon </br>
                <b><?php echo $language['label_bank_name']; ?>:</b> Hong Leong Bank </br>
                <b><?php echo $language['label_bank_account']; ?>:</b> 22850076859 </br>
            </div>
            <span style="border-bottom:1px solid lightgray;height:7px;width:100%"></span>

            <div class="col-md-12 pad0">

                <h6 style="margin-top:10px">Boostpay Number(Chong woi joon): <b>+60123115670</b></h6>
                <h6 style="margin:0px">Touch & Go account ( Wong Siew Foon): <b>+6014-3521349</b></h6>
                <div style="width: 100%;text-align: center;">
                 <img class="img-responsive Sirv" src="https://koofamilies.sirv.com/qr_code.png" />  
             </div>
             <b><?php if ($_SESSION["langfile"] == "chinese"){echo "请写商家店名在“银行参考”";}else{ ?>                         (Please write <?php echo $merchant_detail['name']; ?> in "bank reference")<?php } ?><b> <br/>
                <!--<b><span style="color: red;" class="final_amount_label"><?php echo $language['payable_amount']; ?>:</span></b> Rm <span style="font-weight:bold;" class="final_amount_value"></span><br/>-->
                <b><?php echo $language['label_enquiry']; ?>:</b> <a href="https://chat.whatsapp.com/LdvomJRqXoIG6aXqsf5PgX" target="_blank" style="font-size:11px"><img src="https://www.koofamilies.com/images/whatapp.png" style="max-width:23px;" />https://chat.whatsapp.com/LdvomJRqXoIG6aXqsf5PgX</a><br/>
            </div>
        <?php }?>

        <form method="post" id="image-form" class="image-form"  enctype="multipart/form-data" onSubmit="return false;" style="min-height:0px !important;">
            <div class="input-group mt-3 mb-3 input-has-value flex-wrap1 main_image_div" id="main_image_div" style="padding-bottom:13px">
             <input type="file" name="file" class="file" style="visibility: hidden;position: absolute;">
             <input type="text" class="form-control payment_proof" disabled="" placeholder="Payment Proof" id="file" style="width:100px">
             <button type="button" class="browse btn btn-primary rounded-0" style="width: 80px;background: lightgray;border: gray;">Browse</button>
             &nbsp;
             <input type="submit" name="submit" value="Submit" class="btn btn-danger btn_proof_upload rounded-0" >
             <p style="color:red" class="err_payment_proof"></p>
         </div>
         <div class="main_image_namediv" id="main_image_namediv" style="padding:10px;"></div>
     </form>
     <p style="color:blue;font-size: 13px;margin-top: -30px;"><?php echo $language['payment_prrof_text']; ?></p>



     <div class="col-md-12 pad0">
        <span style="color:red;font-size: 13px;font-weight: bold;"> 
         <?php echo $language['transfer_complete_message']; ?>
     </span>
 </div>
</div>
</div>
</div>

<span style="border-bottom:1px solid lightgray;height:7px;width:100%"></span>

<div class="modal-footer" style="padding-top: 10px;padding-bottom: 10px;border-bottom:none">
 <span id="process_internet_order" style="color:red;font-weight:bold;"></span>
 <button type="button" class="btn btn-primary trasfer_complete showLoader"><?php echo $language['confirm_order_btn']; ?><span></span></button>
 <button type="button" class="btn  back_btn_m back_to_last" style="margin-left: -3px;margin-right: -7px;" ><?php echo $language['back_to_last_page_order']; ?></button>
</div>
</div>
</div>
</div>
</div>


<div class="modal fade" id="newuser_model" tabindex="-1" role="dialog" aria-labelledby="login_passwd_modal_title" aria-hidden="true" style="margin-top:15%;">
   <div class="modal-dialog" role="document">
      <div class="form-group">
         <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                   <span aria-hidden="true">&times;</span>
               </button>
           </div>
           <div class="modal-body online_login_model">
               <div class="form-group" style="margin-bottom: 0em;">
                  <p id="with_wallet" style="font-size:16px;color:red;display:none;">
                     <span style='font-size:20px;'>&#128512;</span> 
                     <!--Congratulations, your order has just earned Rm <span class="rebate_amount_label"></span> into your Koo Coin wallet. You can use it after 24 hours !-->
                     <?php if($special_coin_name && $koo_balance>0){  echo $language['cashback_login'];?>
                 <?php } else {?>
                     Congratulation, your order is completed. <span id="with_wallet_span"> Please login to claim for your rebate of RM <span class="rebate_amount_label">15.20 </span> into your KOO Coin wallet.</span>  
                 <?php } ?>
             </p>
             <p id="without_wallet" style="font-size: 16px; color: red;display:none;">
                 <span style="font-size:20px;">😀</span> 
                 <!--Congratulations, your order has just earned Rm <span class="rebate_amount_label"></span> into your Koo Coin wallet. You can use it after 24 hours !-->
                 <?php echo $language['cashback_login']; ?>
             </p>
         </div>
         <div class="wallet_mode" style="display:none;">
          <h4>Please choose your wallet to pay</h4>
          <div class="row">
                     <!--div style="margin: 2%;max-height: 80px;text-align: left;padding: 0%;" class="col-md-3 card bg-primary text-white">
                        <div class="card-body wallet_select" wallet_name="MYR" type="myr_bal">MYR <br> <span  id="myr_bal"><?php if(isset($urecord['balance_myr'])){ echo $urecord['balance_myr'];} ?></span></div>
                        </div>
                        <div style="margin: 2%;max-height: 80px;text-align: left;padding: 0%;" class="col-md-3 card bg-info text-white">
                        <div class="card-body wallet_select" wallet_name="CF"  type="usd_bal">CF <br> <span id="usd_bal"><?php if(isset($urecord['balance_usd'])){ echo $urecord['balance_usd'];} ?></span></div>
                    </div!-->
                    <?php if($special_coin_name){ ?>
                     <div style="margin: 2%;max-height: 80px;text-align: left;padding: 0%;font-size: 13px;" class="col-md-7 card bg-info text-white">
                        <div class="card-body wallet_select" wallet_name="<?php echo $special_coin_name; ?>"  type="special_bal"  style="color:black;padding:1.00rem !important;font-size: 18px;">
                           <?php echo $special_coin_name; ?>  RM <span id="special_bal" style="color:red;font-weight:bold;"><?php if(isset($special_bal)){ echo number_format($special_bal,2);} ?></span>
                       </div>
                   </div>
                   <small style="margin-left: 3%;color:black;">(Min RM <?php echo $merchant_detail['special_coin_min'];?> to use, and Max RM <?php echo $merchant_detail['special_coin_max'];?> per transaction)</small>
                   <?php } if(($merchant_detail['koo_
                      _accept'] || $merchant_detail['koo_cashback_accept_dive_in'])) {
                       ?>

                       <div class="card-body wallet_select col-md-7" wallet_name="<?php echo $s_coin_name; ?>" type="koo cashback" style="color:black;padding:1.00rem !important;font-size: 18px;background:yellow;">

                         KOO CASHBACK RM <span id="special_bal_koocash" class="koo_check_wallet" style="color:red;font-weight:bold;">

                            <?php echo number_format($koo_balance, 2);
                        ?></span>

                    </div>  
                    <small style="margin-left: 3%;color:black;">(Min RM 1 to use, and Max RM 100.00 per transaction)</small>


                <?php } ?>
                     <!--div clas="form-group" style="margin-left:3%;font-size:15px;">
                        <?php if($merchant_detail['order_extra_charge']>0){ $s_label="Product";} else { echo "Total";}?>
                        
                        
                        
                         <?php echo $s_label;?> Amount: Rm 
                        
                         <span id="total_cart_amount_label" style="font-weight:bold;color: black;"></span>    
                        
                        <p class="select_label" style="display:none;">Selected Wallet : <span id='wallet_name'></span></br>
                        
                           
                        
                          <div style="clear:both;"></div>
                        
                          <span style="display:none;" class="delivery_extra" style="margin-bottom:1%;">
                        
                             <?php  echo ucfirst(strtolower("Delivery Charges")); ?>: Rm <span class="delivery_extra_value"></span>
                        
                        
                        
                         </span>
                        
                         <div style="clear:both;"></div>
                        
                         <span style="display:none;" class="membership_discount_label" style="margin-bottom:1%;">
                        
                             <?php  echo ucfirst(strtolower("Membership Discount")); ?>: Rm <span class="membership_discount_value"></span>
                        
                        
                        
                         </span>
                        
                         <div style="clear:both;"></div>
                        
                         <span style="display:none;" class="coupon_discount_amount_label" style="margin-bottom:1%;">
                        
                             <?php  echo ucfirst(strtolower("Coupon Discount")); ?>: Rm <span class="coupon_discount_amount_value"></span>
                        
                        
                        
                         </span>
                        
                        
                        
                        
                        
                          <div style="clear:both;"></div>
                        
                          <div  style="display:none;" class="sst_amount_label">  
                        
                        	<div style="grid-template-columns:.2fr 2fr;grid-column-gap: 10px;vertical-align: middle;align-content:center;font-weight: bold;font-size: 15px;color:black;">
                        
                        		
                        
                        	   
                        
                        		<?php  echo "Service fee "?><?php echo $merchant_detail['sst_rate']." % "; ?>: Rm <span class="sst_amount_value"></span>
                        
                        		</div>
                        
                        	</div>  
                        
                          <div style="clear:both;"></div>
                        
                            <div  style="display:none;" class="delivery_tax_amount_label">  
                        
                        	<div style="grid-template-columns:.2fr 2fr;grid-column-gap: 10px;vertical-align: middle;align-content:center;font-weight: bold;font-size: 15px;color:black;">
                        
                        		
                        
                        	   
                        
                        		<?php  echo "Delivery Service tax "?><?php echo $merchant_detail['delivery_rate']." % "; ?>: Rm <span class="delivery_tax_amount_value"></span>
                        
                        		</div>
                        
                        	</div!--> 
                         <div style="clear:both;"></div>
                         <span style="display:none;color:Red;" class="final_amount_label" style="margin-bottom:1%;">
                             <?php  echo ucfirst(strtolower($language['payable_amount'])); ?>: Rm <span style="font-weight:bold;" class="final_amount_value"></span>
                         </span>
                         <div style="clear:both;"></div>
                         <span id="wallet_payment_label" style="display:none;"></span>
                         <span id="bal_to_paid_label" style="display:none;color: green;"></span>
                     </p> 
                     <span class="btn btn-large btn-danger wallet_final_payment" style="display:none;"><?php echo $language['pay_now']; ?><span>   
                     </div>
                     <?php  if($_SESSION['block_pay']!=="y"){?>
                      <div class="form-group no-wallet">
                         <p style="text-align: center;"><?php echo $$language['or']; ?></p>
                     <!--span class="btn btn-block btn-primary cash_pay" name="cashpayment">Change to Cash Payment 
                     </span!--> 
                     <span class="btn btn-block btn-primary back_to_last"><?php echo $language['back_to_last_page']; ?>
                 </span>
                 <span id="wallet_order_process" style="color:red;display:none;">Please wait......., we are processing your order..</span>
             </div>
         <?php } ?>
     </div>
 </div>
 <div id="login_process">
   <div class="login_passwd_field" style="display:none;">
      <label for="login_password"><?php echo $language['password_login']; ?></label>  
      <input  type="password" id="login_ajax_password" class="form-control" name="login_password" required/>
      <i  onclick="myFunction()" id="eye_slash" class="fa fa-eye-slash" aria-hidden="true"></i>
      <span onclick="myFunction()" id="eye_pass"> <?php echo $language['show_password']; ?> </span>   
      <div style="clear:both"></div>

      <span class="forgot_pass" style="color:#28a745;/*! float:right; */font-size:16px;text-align: center;text-decoration: underline;/*! width: 100% !important; */">
          <?php echo $language['reset_password']; ?> 
      </span>
  </div>
  <div class="forgot-form" style="display:none;">
      <label for="login_password"><?php echo $language['reset_password']; ?> </label>
      <div class="input-group mb-2">
         <div class="input-group-prepend">
            <div class="input-group-text" style="background-color:#51D2B7;border-radius: 5px 0 0 5px;height: 100%;padding: 0 10px;display: grid;align-content: center;">+60</div>
        </div>
        <input  type="number" autocomplete="tel" maxlength='10' id="user_mobile" class="mobile_number form-control" <?php if($check_number){ echo "readonly";} ?> value="<?php if($check_number){ echo $check_number;}  ?>" placeholder="Phone number" name="mobile_number" required="" />
    </div>
    <small class="forgot_error" style="display: none;color:#e6614f;">
      Please Key in valid number
  </small>
</div>
<div class="modal-footer login_footer" style="padding:0px;">
  <div class="row" style="margin: 0;">
     <div class="col otp_fields join_now" style="padding: 0;margin: 5px;display:none;">
        <input type="submit" class="btn btn-primary login_ajax"  name="login_ajax" value="<?php echo $language['login']; ?>" style="float: right;display:none;"/>
        <small id="login_error" style="display: none;color:#e6614f;">
        </small>
    </div>
    <div class="col otp_fields forgot_now" style="padding: 0;margin: 5px;display:none;">
        <input type="submit" class="btn btn-primary forgot_reset"   value="Reset" style="width:50%;float: right;display:none;"/>
    </div>
    <small  class="reg_pending skip" id='skip' style="color:#e6614f;font-size:14px;min-width:50px;margin-top:1%;">
     <u class="btn btn-primary"><?php echo $language['skip']; ?> </u>
 </small>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>

<!-- end login popup for rebeat process!-->

<div class="modal fade" id="newmodel_check" tabindex="-1" role="dialog" aria-labelledby="login_passwd_modal_title" aria-hidden="true">

    <div class="modal-dialog" role="document">

        <div class="form-group">

            <div class="modal-content">

                <div class="modal-header">



                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                        <span aria-hidden="true">&times;</span>

                    </button>

                </div>

                <div class="modal-body">

                    <p id="show_msg_new" style="font-size: 16px;color: red;"><span style='font-size:20px;'>&#128512;</span>

                        Verfiy otp get membership discount

                    </p>
					
                    <div class="form-group otp_form" style="display:none;">

                        <!--<div id="divOuter">

                            <div id="divInner">

                                Otp code

                                <input id="partitioned" type="Number" maxlength="4" />

                                <!--small style="float:right;color:#28a745;display:none;" class="resend send_otp">Resend</small!-->

                               <!-- <small class="otp_error" style="display: none;color:#e6614f;">

                                    Invalid Otp code

                                </small>

                            </div>

                        </div>-->

                    </div>

                    <div class="popup_info_user" style="display:none">
                      <p style="font-weight: normal;"><b><?php echo $language['username1'];?> </b><span class="popup_info_user1 tsd"> - </span></p>
                      <p  style="font-weight: normal;"><b><?php echo $language['mobilenumber1'];?> </b><span class="popup_info_user2"> - </span></p>
                      <input class="koo_check_wallet" type="hidden" value= ""/>
                  </div>



                  <div class="login_passwd_field" style="display:none;">

                    <label for="login_password"> <?php echo $language['password_to_login']; ?></label>

                    <input type="password" id="login_ajax_password_new" class="form-control" name="login_password" required />



                    <i onclick="myFunctionnew()" id="eye_slash_new" class="fa fa-eye-slash" aria-hidden="true"></i>

                    <span onclick="myFunctionnew()" id="eye_pass_new"> <?php echo $language['show_password']; ?> </span>



                    <div style="clear:both"></div>
                    <small id="login_error_new" style="color:#e6614f;"></small>




                </div>
                <span class="forgot_pass" style="color:#28a745;display:none;/*! float:right; */font-size:16px;text-align: center;text-decoration: underline;/*! width: 100% !important; */">

                    <?php echo $language['reset_password']; ?>

                </span>


                <div class="forgot-form" style="display:none;">

                    <label for="login_password">Reset/Create Password</label>

                    <div class="input-group mb-2">

                        <div class="input-group-prepend">

                            <div class="input-group-text" style="background-color:#51D2B7;border-radius: 5px 0 0 5px;height: 100%;padding: 0 10px;display: grid;align-content: center;">+60</div>

                        </div>



                        <input type="number" autocomplete="tel" maxlength='12' id="forgot_mobile_number" class="mobile_number form-control" readonly <?php if ($check_number)
                        {
                            echo "readonly";
                            } ?> value="<?php if ($check_number)
                            {
                                echo $check_number;
                            } ?>" placeholder="Phone number" name="mobile_number" required="" />



                        </div>

                        <small class="forgot_error" style="display: none;color:#e6614f;">

                            Please Key in valid number

                        </small>



                    </div>

                </div>
                <p class="lg_otp_error" style="font-size:14px;color:red;display:none"></p>
                
				<?php /*?>
				<div class="modal-footer login_footer" style="display:none;">

                    <div class="row" style="margin: 0;">

                        <div class="col otp_fields join_now" style="padding: 0px;margin: 0px;display:none;">

                            <input type="submit" class="btn btn-primary login_ajax_new" name="login_ajax" value="<?php echo $language['login']; ?>" style="float: right;display:none;font-size: 12px;padding: 6px;" />
							
							<input type="button" class="btn btn-primary show_pwd_box" name="show_pwd_box" value="<?php echo $language["login_password"]; ?>" style="float: right;font-size: 12px;padding: 6px;" />
							

                            <small id="login_error_new" style="display: none;color:#e6614f;"></small>

                        </div>

                        <div class="col otp_fields forgot_now" style="padding: 0;margin: 5px;display:none;">

                            <input type="submit" class="btn btn-primary forgot_reset" value="Reset" style="width:50%;float: right;display:none;" />

                        </div>



                    </div>
<!--
                    <small class="reg_pending skip" id='skip' style="color:#e6614f;font-size:14px;min-width:50px;">

                        <u class="btn btn-primary"><?php echo $language['skip']; ?> </u>

                    </small>

                    <small class="reg_pending register_skip skip" style="color:#e6614f;font-size:14px;display:none;min-width:50px">

                        <u class="btn btn-primary"><?php echo $language['skip']; ?></u>

                    </small>-->
					
					<!-- <small id="myBtnLogWithOTPSmall" style="color:#e6614f;font-size:14px;min-width:50px;"> -->
<!--data-target="#myModal" data-backdrop="false"-->
                        <span class="btn btn-primary login_with_otp myBtnLogWithOTP" id="myBtnLogWithOTP" style="font-size: 12px;margin: 2px; padding: 6px;"><?php echo $language['login_with_otp']; ?></span>
						
                    <!-- </small> -->

                    <!-- <small class="reg_pending register_skip skip" style="color:#e6614f;font-size:14px;display:none;min-width:50px"> -->

                        <span class="btn btn-primary myBtnLogWithOTP" id="closePrevSMSModel" style="font-size: 12px;margin: 0px; padding: 6px;background:#80dc80;border-color:#80dc80"><?php echo $language['skip']; ?></span>

                    <!-- </small> -->

                </div>
                <?php */?>
                <!-- New model footer --->
                <div class="modal-footer login_footer" style="padding: 0;margin: 0;padding-top: 20px;padding-bottom: 20px;">
                 <div class="row" style="margin: 0;padding: 0;">

                   <div class="col-12 col-sm-5 lgn_div_otp" style="margin: 0px;padding: 0;margin-top: 10px;">
                       <input type="button" class="btn btn-primary show_pwd_box" name="show_pwd_box" value="<?php echo $language["login_password"]; ?>" style="width: 98%;padding-left:11px">
					  <input type="submit" class="btn btn-primary login_ajax_new" name="login_ajax" value="<?php echo $language['login_password']; ?>" style="display:none;width: 98%;" />
				  
                  </div>

                  <div class="col-12 col-sm-5" id="ne_forg_div"  class="col otp_fields forgot_now" style="padding: 0;margin-top: 10px;;display:none;">
                    <input type="submit" class="btn btn-primary forgot_reset" value="Reset" style="width: 98%;display:none;" />
                </div>

                <div class="col-9 col-sm-5 otp_fields join_now" style="margin: 0;padding: 0;margin-top: 10px;">
                  
				  <span class="btn btn-primary login_with_otp myBtnLogWithOTP" id="myBtnLogWithOTP" style="width: 98%;"><?php echo $language['login_with_otp']; ?></span>
					 
                  <!--<small id="login_error_new" style="display: none;color:#e6614f;"></small>-->
                </div>
					  <!--<div class="col-8 col-sm-4" style="margin: 0px;padding: 0;margin-top: 10px;">
						<span class="btn btn-primary login_with_otp myBtnLogWithOTP" id="myBtnLogWithOTP" style="width: 98%;"><?php echo $language['login_with_otp']; ?></span>
                   </div>-->
                   <div class="col-3 col-sm-2" style=" margin: 0;padding: 0;margin-top: 10px;">
                      <span class="btn btn-primary myBtnLogWithOTP" id="closePrevSMSModel" style="background:#80dc80;border-color:#80dc80;width: 98%;"><?php echo $language['skip']; ?></span>
                  </div>
              </div>
              <br/>
          </div>
          <!-- END new Model Footer --->

      </div>

  </div>

</div>

</div>

<!-- Modal Popup for OTP -->
<div class="modal fade" id="myModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <form method="post" action="<?php echo $site_url; ?>/v.php" id="myForm">
                <input type="hidden" name="mobile_number" id="mobile_numberC">
                <input type="hidden" name="user_rolehwe" value="1">
                <input type="hidden" name="user_roleget" value="2">
                <input type="hidden" name="user_role" value="1">
                <input type="hidden" id="login_otp">
                <input type="hidden" id="login_via" name="login_via" value="normal">
                <input type="hidden" name="back_url_link" value="<?php echo $site_url; ?>/view_merchant.php" id="back_url_link">

                <div class="modal-header">
                    <h4 class="text-primary">Key in OTP code to login</h4>
                    <button id="closeSMSModal" class="close" type="button" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <span style="display:none;color:#51d2b7;" id="otp_label"><?php echo $language['enter_ur_phone']; ?></span>
                        <span style="float: right;color: #848DD7;text-decoration: underline;display:none;" class="resend_label">Resend otp</span>
                        <div id="divOuterMP">
                            <div id="divInnerMP">
                                <input id="part_otpInputMP" name="str_otp" type="text" minlength="4" maxlength="4" required />
                            </div>
                        </div>
                        <span style="float:left;color:red;display:none;margin-top:10px;" id="otpNotSent_label"></span>
                    </div>

                </div>

                <div class="modal-footer">
                    <!--<div class="verfiy_otp_form" style="width: 50%;display:none;">
                        <input type="button" value="<?php echo $language["verify_otp"];?>" name="verfiy_otp_button" class="verfiy_otp_button" style="font-size:12px !important;padding:12px;float: right;background: #FFB87A;color:black;border: #51d2b7;" />
                    </div>-->


                    <button id="" class="" style="color: black; font-size: 20px;width: 100px;background: lightgreen;padding: 2px;border-color: lightgreen;font-weight: bold;" type="button" data-dismiss="modal"><?php echo $language['skip']; ?> </button>


                </div>

            </form>

        </div>
    </div>
</div>


<div class="modal fade" id="forgot_setup" tabindex="-1" role="dialog" aria-hidden="true" style="margin-top:4%;">

    <div class="modal-dialog" role="document">

        <div class="form-group">

            <div class="modal-content">

                <div class="modal-header">



                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                        <span aria-hidden="true">&times;</span>

                    </button>

                </div>

                <div class="modal-body">

                    <p id="forgot_msg_new" style="font-size: 16px;color: red;"><span style='font-size:20px;'>&#128512;</span>

                        Enter otp to reset password

                    </p>

                    <div class="form-group forgot_otp_form" style="display:none;">

                        <div id="forgot_divOuter">

                            <div id="forgot_divInner">

                                Otp code

                                <input id="forgot_partitioned" type="Number" maxlength="4" />

                                <!--small style="float:right;color:#28a745;display:none;" class="resend send_otp">Resend</small!-->

                                <small class="forgot_otp_error" style="display: none;color:#e6614f;">

                                    Invalid Otp code

                                </small>

                            </div>

                        </div>

                    </div>
                    <div class="form-group forgot_register_password" style="display:none;">

                        <div class="passwd_field">

                            <input type="password" id="forgot_register" class="form-control" name="forgot_register" />

                            <i onclick="myFunctionforgot()" id="eye_slash_forgot" class="fa fa-eye-slash" aria-hidden="true"></i>
                            <span id="eye_pass_forgot" onclick="myFunctionforgot()"> <?php echo $language['show_password']; ?>
                        </span>
                        <small style="color:red;">(Please set passwords at least 6 digits and above)</small>
                        <small id="forgot_register_error" style="display: none;color:#e6614f;">

                        </small>

                        <small id="forgot_reset_password_error" style="font-size:16px;display:none;color:#e6614f;"></small>

                    </div>
                </div>




            </div>

            <div class="modal-footer forgot_footer">

                <div class="row" style="margin: 0;">

                    <div class="col forgot_password_submit" style="padding: 0;margin: 5px;display:none;">
                        1
                        <input type="submit" class="btn btn-primary forgot_reset_password" name="login_ajax" value="Change Password" style="float: right;display:none;" />



                    </div>





                </div>

                <small class="reg_pending skip" id='skip' style="color:#e6614f;font-size:14px;min-width:50px;">

                    <u class="btn btn-primary"><?php echo $language['skip']; ?> </u>

                </small>



            </div>

        </div>

    </div>

</div>

</div>

<div class="modal fade" id="PasswordModel" role="dialog" style="">

    <div class="modal-dialog">

        <?php

        ?>



        <!-- Modal content-->

        <div class="modal-content">



            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal">&times;</button>



            </div>



            <div class="modal-body" style="padding-bottom:0px;">

                <div class="form-group register_password" style="<?php if ($password_created == "y")
                {
                    echo "display:none;";
                } ?>">



                <div class="passwd_field">

                    <label for="login_password">Please create your password</label>

                    <input type="password" id="register_password" class="form-control" name="login_password" />



                    <i onclick="myFunction2()" id="eye_slash_2" class="fa fa-eye-slash" aria-hidden="true"></i>

                    <span id="eye_pass_2" onclick="myFunction2()"> <?php echo $language['show_password']; ?> </span>

                    <small id="register_error" style="display: none;color:#e6614f;">



                    </small>



                </div>

            </div>



        </div>

        <div class="modal-footer" style="padding-bottom:2px;">

            <div class="row" style="margin: 0;">





                <div class="col" style="padding: 0;margin: 5px;">



                    <input type="submit" class="btn btn-primary register_ajax" name="register_ajax" value="Confirm" style="float: right;" />

                    <small id="register_error" style="display: none;color:#e6614f;">



                    </small>



                </div>





            </div>

            <small class="finalskip" style="color:#e6614f;font-size:14px;min-width:50px;">

                <u class="finalskip">Skip</u>

            </small>



        </div>



    </div>

</div>

</div>

<?php
$mid = $_SESSION['merchant_id'];

$get_workingHr = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SQL_NO_CACHE  * FROM set_working_hr WHERE merchant_id='$mid' "));

if (!empty($get_workingHr['start_time']) && !empty($get_workingHr['end_time']))
{

    $stime = $get_workingHr['start_time'];

    $show_time = date("g:i a", strtotime($get_workingHr['start_time']));

    $etime = $get_workingHr['end_time'];

    $end_time = date("g:i a", strtotime($get_workingHr['end_time']));

    $sday = $get_workingHr['start_day'];

    $eday = $get_workingHr['end_day'];
}
else
{

    $stime = '';

    $etime = '';

    $sday = '';

    $eday = '';
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
                <?php if (!empty($_SESSION['login_user_role']) && $_SESSION['login_user_role'] == 1)
                { ?>
                    <input style="display: none;" value='<?php echo $_SESSION['user_id']; ?>' id="user_id" />
                    <input style="display: none;" value='<?php echo $_SESSION['merchant_id']; ?>' id="m_id" />
                    <?php
                } ?>
                <?php if (!empty($_SESSION['login_user_role']) && $_SESSION['login_user_role'] == 2)
                { ?>
                    <input style="display: none;" value='<?php echo $_SESSION['merchant_id']; ?>' id="m_id" />
                    <?php
                } ?>
                <?php if (!isset($_SESSION['login_user_role']) && !empty($_SESSION['mm_id']) && !empty($_SESSION['merchant_id']))
                { ?>
                    <input style="display: none;" value='<?php echo $_SESSION['merchant_id']; ?>' id="m_id" />
                    <?php
                } ?>
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
                    <button id="uploadFile" type="button" class="btn btn-primary" style="width:50%;margin-bottom: 3%;text-align: center;"><i class='fa fa-spinner fa-spin ' id="spin"></i> Place Order</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="product_added_to_cart" style="margin-top: 40vh;" tabindex="-1" role="dialog" aria-labelledby="product_added_to_cart" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="product_added_to_cart"><?php echo $language['success_label'];?></h5>
            </div>
            <div class="modal-body">
                <b><?php echo $language['product_success_addded'];?></b>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="show_restriction_popup" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
             <button type="button" class="close" data-dismiss="modal">&times;</button>
             <h4 class="modal-title"> Safety Purpose</h4>
         </div>
         <div class="modal-body">
          <h5>
<?php echo $language['covid_error'];?>
          </div>
          <div class="modal-footer login_footer" style="padding:0px;">
           <button type="button" class="btn btn-primary rest_skip_button " style="font-size:14px;min-width:50px;margin-top:1%;color:white"  data-dismiss="modal" ><?php echo $language['skip']; ?></button>
		<button type="button" class="btn btn-primary rest_other_button " style="display:none;font-size:14px;min-width:50px;margin-top:1%;color:black;margin-bottom:10px"  >Change other payment method</button>

       </div>
   </div>
</div>
</div>


 <link rel="stylesheet" href="<?php echo $site_url;?>/extra/css/wickedpicker.css">
        <script type="text/javascript" src="<?php echo $site_url;?>/extra/js/wickedpicker.js"></script>

		
</body>
</html>


<?php /*
if(!isset($_SESSION['frce_refresh_popup'])){
$_SESSION['frce_refresh_popup'] = '1';
?>
<script>
$("#myModal_forcerefresh").modal({
	show: true,
	backdrop: 'static'
});
</script>
<?php }*/?>

<script>
$(function() {
				//$('#od_delivery_time').timepicker();
				$('#od_delivery_time').wickedpicker({twentyFour: false, title:
                    'Select Time', showSeconds: false,minutesInterval: 15});		
			});
			
	
			
			
    $(document).ready(function() {
        $("#myBtnLogWithOTP").click(function() {
			$("#myBtnLogWithOTP").attr('style','width: 98%;');
            var t = document.getElementById("mobile_number").value;
            if(t != '' && t.length == 10) {
            //$("#myModal").modal({backdrop: 'static', keyboard: false});
        }
        $('#otp_label').html('Otp sent on mobile number');

		//$("#myModal").modal('show');


    });

        $("#closeSMSModal").click(function() {
            $("#myModal").modal("hide");
        });

        $("#closePrevSMSModel").click(function() {
            $('#newmodel_check').modal('hide');
        });

        document.getElementById("part_otpInputMP").addEventListener( "invalid", function( event ) {
            event.preventDefault();
        }, true );

    // My SMS MODAL POPUP SCRIPT
    $(".resend_label").click(function() {
        $(".login_with_otp").click();
        setTimeout(function() {
            if($('#otp_label').html() == 'Otp sent on mobile number') {
                    //$('#otp_label').html('Otp re-sent on mobile number');
                } else {
                    //$('#otp_label').html('Otp not sent!');
                }
            }, 2000);
    });
    $(".login_with_otp").click(function() {

        $("#otpNotSent_label").show();

        $("#otpNotSent_label").css('color', '#000000');
        $("#otpNotSent_label").html("Please wait...");
        var mobile_number=document.getElementById("mobile_number").value;
        $("#mobile_numberC").val(mobile_number);
        if(mobile_number[0]==0) {
            mobile_number=mobile_number.slice(1);
        }
        var number="60"+mobile_number;
        if (number.length >= 9 && number.length <= 12 && (number[0] == 1 || number[0] == 6 || number[0] == 0)) {
                 // alert('inside');
                 $.ajax({
                    url: '<?php echo $site_url; ?>/functions.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {mobile_number: number,
                        method: "loginotp"},
                        success: function(res) {
                            var data = JSON.parse(JSON.stringify(res));
                            $("#otpNotSent_label").hide();
                            $("#otpNotSent_label").css('color', '#FF0000');
                            if (data.status == true) {
                                $("#myModal").modal('show');
                                $('#newmodel_check').modal('hide');
                                $('#login_otp').val(data.otp);
                                $('#otp_label').show();
                                $('#otp_label').css('color', 'rgb(81, 210, 183)');
                                $('#otp_label').html('Otp sent on mobile number');
                                $('.resend_label').show();
                                $('.verfiy_otp_form').show();
                                
                            }else if(data.pop_ot == 1){
                                $("#myModal").modal('show');
                                $('#newmodel_check').modal('hide');
                                $('#login_otp').val(data.otp);
                                $('#otp_label').show();
                                $('#otp_label').html(data.message);
                                $('.resend_label').show();
                                $('.verfiy_otp_form').show();

                            } else {
                                /*$('#otp_label').show();
                                $('#otp_label').css('color', '#FF0000');
                                $('#otp_label').html('Otp not sent on mobile number');
                                $('.resend_label').show();
                                alert('Something went wrong try again later');
                                */

                                $('.lg_otp_error').show();
                                $('.lg_otp_error').html(data.message);
                                $('#otp_label').html(data.message);


                            }
                        }

                    });
             }
         });
        //$(".verfiy_otp_button").click(function() {
          $('#part_otpInputMP').on('keyup', function(){ 		
            var user_input=$('#part_otpInputMP').val();
            var page_otp = $("#login_otp").val();
            var mobile_number=$('.mobile_number').val();
            var koo_check_wallet = $(".koo_check_wallet").val();

            // var xlogPass = document.getElementById("myDIV");
            // xlogPass.style.display = "none";

            if(mobile_number[0]==0) {
                mobile_number=mobile_number.slice(1);
            }
            var usermobile="60"+mobile_number;
            if(user_input=='') {
                // $('#enter_ur_phone_label').show();
                //   $('#enter_ur_phone_label').html("Enter Password");  
                //      document.getElementById("enter_ur_phone_label").style.color = 'red';
                //       $('html, body').animate({
                //              scrollTop: $("#login_pass").offset().top
                //          }, 2000);
                //      $(function(){
                //         $('#login_pass').focus();
                //      });
                
                $("#otpNotSent_label").show();
                $("#otpNotSent_label").html("Please enter OTP to continue!");
            } else {


              if(user_input.length % 4 == 0){
                  if(user_input == page_otp) {
                    $("#otpNotSent_label").hide();
                    
                    $('#login_via').val('otp');
                    var login_viaa = $('#login_via').val();
                    // $('form#myForm').submit();

                    var dataJ = {usermobile: usermobile,
                        login_password: '1',
                        membership_applicable: '',
                        merchant_id: '',
                        last_order_id: '',
                        login_via: login_viaa,
                        str_otp: user_input};

                        $.ajax({
                            url: '<?php echo $site_url; ?>/login.php',
                            type: 'POST',
                            dataType: 'json',
                            data: dataJ,

                            success: function(response) {
                                var data = JSON.parse(JSON.stringify(response));

                                if (data.status) {
                                 $('#myModal').modal('hide');
                                 if(koo_check_wallet != 0){
                                     $(".wallet_ajax_value").html(" (Rm"+koo_check_wallet+")");
                                     $(".koo_check_wallet").html(koo_check_wallet);
                                     $("#koo_balance").val(koo_check_wallet);
                                 }
                                 var login_user_role = data.data.user_roles;
                                 var login_user_id = data.data.id;

                                 localStorage.setItem('login_live_id', login_user_id);
                                 localStorage.setItem('login_live_role_id', login_user_role);

                                 $('#login_for_wallet_id').val(login_user_id);

                                 $('#login_process').hide();
                                 $('#with_wallet').hide();
                                 $('#without_wallet').hide();
                                 $('.join_now').hide();
                                 walletstep();
								//$("#myModal").modal('hide');
                            } else {
                                user_input.val('');
                                $("#otp_label").html('OTP is wrong!');
                                $("#otp_label").show();

                            }
                        }
                    });
                    }

                } else {
                    $("#otpNotSent_label").show();
                    $("#otpNotSent_label").html("OTP is wrong! Please enter correct OTP to continue.");
                }
                
            }
        });
      });
  </script>
  <script>
    // var x=navigator.permissions.query({ name: 'geolocation' });
    // alert(x);
    function set_location_to_main_field(){
      var order_lat=document.getElementById('lat').value; 
      var order_lng=document.getElementById('lng').value; 

      document.getElementById('user_lat').value=document.getElementById('lat').value; 
      document.getElementById('user_long').value=document.getElementById('lng').value;
      document.getElementById('mapSearch').value=document.getElementById('location').value;
        //document.getElementById('location_map_fetch').style.display='none';
        if (order_lat && order_lng)
        {
            $('.latitude').val(order_lat);
            $('.longitude').val(order_lng);
            calculatedisatace(order_lat, order_lng);
        }
    }
    function geocodePosition(pos) {
        geocoder.geocode({
            latLng: pos
        }, function(responses) {
            if (responses && responses.length > 0) {
              updateMarkerAddress(responses[0].formatted_address);
          } else {
      //updateMarkerAddress('Cannot determine address at this location.');
  }
});
    }
    var t_lat;
    var t_long;
// var location_permission ="denied";
function couponlist()
{
  var already_login = '<?php echo $login_user_id; ?>';
  var mobile_number=$('#mobile_number').val();
  if (mobile_number[0] == 0) {
    mobile_number = mobile_number.slice(1);
}
var data = {
    method: "pastaddress",
    mobile_number:mobile_number,
    user_id: already_login
};
if(already_login || mobile_number)
{
    $.ajax({

      url: "<?php echo $site_url; ?>/s_coupon_list.php",

      type: "post",

      data: data,

      success: function(data) {
        $('#coupon_list_section').modal('show');

        $('#coupon_list_div').html(data);
    }

});
}
} 
function pastaddress()
{
  var already_login = '<?php echo $login_user_id; ?>';
  var mobile_number=$('#mobile_number').val();
  if (mobile_number[0] == 0) {
    mobile_number = mobile_number.slice(1);
}
var data = {
    method: "pastaddress",
    mobile_number:mobile_number,
    user_id: already_login
};
if(already_login || mobile_number)
{
    $.ajax({

      url: "<?php echo $site_url; ?>/pastaddress.php",

      type: "post",

      data: data,

      success: function(data) {
        $('#past_address_section').modal('show');

        $('#past_address_div').html(data);
    }

});
}
else
{
  var msg ="<?php echo $language['login_save_address'] ?>";
  var m2="<?php echo $language['key_in_phone'] ?>";
		// alert(m2);
       $('#enter_ur_phone_label').html(m2);  
       //document.getElementById("enter_ur_phone_label").style.color = 'red';
//$("#mobile_number").focus();
		$("#mobile_number").css("border",'1px solid red');	
       $('html, body').animate({
        scrollTop: eval($("#mobile_number").offset().top -100)
    }, 2000);

       $(function(){
           $('#mobile_number').focus();
       });


       $('#show_msg').html(msg);

       $('#AlerModel').modal('show');

       setTimeout(function() {
        $("#AlerModel").modal("hide");
    }, 5000);
       $('#mobile_number').focus();

   }


}  
function currentLocation() {
  if (navigator.geolocation) {
    position=navigator.geolocation.getCurrentPosition(showPosition1,showError);
} else {

 $("#error_location").html("Sorry, To Fetch Map Location Permission is needed ");  
 $('#location_model').modal('show');
 x.innerHTML = "Geolocation is not supported by this browser.";

}
}
function showPosition1(position) {

  t_lat =position.coords.latitude;
  t_long =position.coords.longitude;
  //postioning to current location 
  if(t_lat && t_long)
  {
   $('#user_lat').val(t_lat);
   $('#user_long').val(t_long);

   var mapSearch=$('#mapSearch').val();
   if(mapSearch)
       $('#location').val(mapSearch);
   $('#location_map_fetch').modal('show');
   newPosition = new google.maps.LatLng(t_lat,t_long);
   placeMarker(newPosition);
}
} 
function showError(error) {
  if(error.code == 1) {
    $("#error_location").html("Sorry, To Fetch Map Location Permission is needed ");
    $('#location_model').modal('show');	  
    // document.getElementById("map").innerHTML ="<h5>location not given please allow permission</h5>";
   // document.getElementById("map").style.height='50px';
   // document.getElementById('map_loacation_warper').style.display='none';

      // alert("location not given please allow permission");
      // $('#location_map_fetch').modal('hide'); //It run into console
  }
}

  // function initMap() {
  //   // var myLatlng = {lat: t_lat, lng: t_long};
  //   var myLatlng = {lat: 4.21, lng: 101.97};
  //   var map = new google.maps.Map(
  //       document.getElementById('map'), {zoom: 4, center: myLatlng});

  //   // Create the initial InfoWindow.
  //   var infoWindow = new google.maps.InfoWindow(
  //       {content: 'Click the map to get Lat/Lng!', position: myLatlng});
  //   infoWindow.open(map);


  //    //currentLocation();


  //   // Configure the click listener.
  //   map.addListener('click', function(mapsMouseEvent) {
  //     // Close the current InfoWindow.
  //     infoWindow.close();

  //     // Create a new InfoWindow.
  //     infoWindow = new google.maps.InfoWindow({position: mapsMouseEvent.latLng});
  //     infoWindow.setContent(mapsMouseEvent.latLng.toString());
  //     //infoWindow.open(map);
  //     placeMarker(mapsMouseEvent.latLng);
  //   });

  // }
  function updatePosition(){
    var lat = parseFloat(document.getElementById("lat").value);
    var lon = parseFloat(document.getElementById("lng").value);
    console.log(lat,lon);
    if (lat && lon) {

        var newPosition = new google.maps.LatLng(lat,lon);
        
        placeMarker(newPosition);
    }
}
function placeMarker(location) {

 geocoder = new google.maps.Geocoder();

 //var myLatlng = new google.maps.LatLng(-24.397, 140.644);
 var mapOptions = {
  zoom: 15,
  center: location
}
var map = new google.maps.Map(document.getElementById("map"), mapOptions);
document.getElementById('map_loacation_warper').style.display='block';
document.getElementById("map").style.height='400px';
var marker = new google.maps.Marker({
    position: location,
    draggable: true
});


google.maps.event.addListener(marker, 'drag', function() {

    updateMarkerPosition(marker.getPosition());
});

google.maps.event.addListener(marker, 'dragend', function() {

    geocodePosition(marker.getPosition());
    map.panTo(marker.getPosition());
});






// To add the marker to the map, call setMap();
marker.setMap(map);




// google.maps.event.addListener(marker, "rightclick", function() {
//         marker.setIcon('blank.png'); // set image path here...
// });
}

function updateMarkerPosition(latLng) {

    document.getElementById('lat').value = latLng.lat();
    document.getElementById('lng').value = latLng.lng();

}

function updateMarkerAddress(str) {
  document.getElementById('location').value = str;
}
</script>


<script>
    // Start of DrakkoFire's code

    $(document).on("click", ".search_anchor", function() {
       if(window.location.href.includes('orderview.php')) return false;
       $('.page_loader').removeAttr('style');
       $("#load").removeAttr('style');;
       $('.page_loader').show();
       $("#load").show();
       setTimeout(function () {
           $('.page_loader').hide();
           $("#load").hide();
       }, 6000);
   });
    $(document).on("click", ".adminDemoVideo img", function() {

        // console.log("triggered");

        $parent = $(this).closest(".element-item");

        $(".element-item").removeClass("selected-item");

        $parent.addClass("selected-item");

        setTimeout(function() {

            $(".fancybox-slide").append("<div class='buttons button-r'><a href='#'></a></div><div class='buttons button-l'><a href='#'></a></div>");

        }, 10);

    });

    $(document).on("click touchstart", ".fancybox-slide .buttons", function() {

        var direction = ($(this).hasClass("button-r")) ? 1 : -1;

        var $element = $(".element-item.selected-item");

        var cat = $element.attr("class").split(" ")[2];

        // console.log(cat);

        var index;

        // Script to know the index of the selected element

        var items_list = direction == 1 ? ".element-item." + cat : $(".element-item." + cat).get().reverse();

        $(items_list).each(function(i) {

            if ($(this).hasClass("selected-item"))

                index = i;

        });

        $(".fancybox-button--close").click();

        var next_item = (index + 1);

        // console.log("Next: " + next_item);

        var len = $(".element-item." + cat).length;

        // console.log(len);

        if (direction == 1) {

            $(".element-item." + cat).each(function(i) {

                if (i >= next_item && $(this).find("a.adminDemoVideo").length > 0) {

                    // console.log(i);

                    $(this).find("a.adminDemoVideo").children("img").trigger("click");

                    return false;

                }

            });

        } else if (direction == -1) {

            $($(".element-item." + cat).get().reverse()).each(function(i) {

                if (i >= next_item && $(this).find("a.adminDemoVideo").length > 0) {

                    // console.log(i);

                    $(this).find("a.adminDemoVideo").children("img").trigger("click");

                    return false;

                }

            });

        }

        // console.log(index);

    });

    $(".bank_model").on("click", function() {
      $("#LesaaAmountModel").modal("hide"); 
      $("#ProccedAmount").modal("hide"); 
      $("#newuser_model").modal("hide");
      $("#payment_mode_cashback").val(1);
      $(".internet_banking").attr("wallet_mode","yes");
      $(".internet_banking").click();
  });  
    $(document).on("click touchstart", ".fancybox_place_order", function() {

        $(".element-item.selected-item").find(".text_add_cart").trigger("click");

        $(".fancybox-button--close").click();

    });

    $(document).on("click", ".fancybox-button--close", function() {

        $(".element-item.selected-item").removeClass("selected-item");

    });   
    $('body').on('click','.coupon_redeem',function(){

			 // alert(3);
          var sum = 0;
          var inps = document.getElementsByName('p_total[]');
          for (var i = 0; i < inps.length; i++) {
            var inp = inps[i];
            sum += parseFloat(inp.value);
        }

        coupon_code= $(this).attr('coupon_code');
        min_amount= $(this).attr('min_amount');
        max_amount= $(this).attr('max_amount');
			// alert(min_amount);
			if(sum>=min_amount)
			{
             $('#coupon_code').val(coupon_code);
             $("#apply_coupon").click();
             $('#coupon_list_section').modal('hide');
         }
         else
         {
				// swal("", "To Redeem this coupon have to increase cart value!", "error");
				alert('To Redeem this coupon have to increase cart value to min RM '+min_amount);
			}
     });
    $('body').on('click','.address_select',function(){
       var selected_location = $(this).attr('location');
       var order_lat = $(this).attr('order_lat');
       var order_lng = $(this).attr('order_lng');
		 // alert(selected_location);
		 // alert(order_lng);
		 $('#mapSearch').val(selected_location);
		 $('.latitude').val(order_lat);
		 $('#user_lat').val(order_lat);
		 $('#user_long').val(order_lng);
		 $('.longitude').val(order_lng);
        var location_order = "<?php echo $merchant_detail['location_order']; ?>";
        if (order_lat && order_lng)
        {
            calculatedisatace(order_lat, order_lng);
        }
        $('#past_address_section').modal('hide');
    });
    $("#order_place").on("submit", function() {

        $("#agent_code_input").trigger("focusout");

		//console.log($("#actual_payment_mode").val());
		//return false;

    });

    // Remark

    var varient_selected = [];

    var already_login = '<?php echo $login_user_id; ?>';



    var merchant_mobile = "<?php echo $merchant_detail['mobile_number']; ?>";

    $("#reset_remark").click(function() {

        $("#remarks_area .btn.btn-secondary.checkbox-checked.active").removeClass("checkbox-checked").removeClass("active");

    });

    $("#remarks_area").on("shown.bs.modal", function() {

        $("body").addClass("noscroll");

    });

    $('#remarks_area').on('hide.bs.modal', function(e) {

        $(this).removeClass("transaction");

        $("body").removeClass("noscroll");

        $("#remark_input").attr("type", "hidden").val('');

        $("input[name='ingredients'].selected").removeClass("selected");

        $("a.introduce-remarks.selected").removeClass("selected");

    });

    $('#remarks_area').on('click', '.save_close', function(e) {

        var selected = [];

        var extras = [];

        $(".introduce-remarks_l2").hide();


        $('div#remarks_area .btn-secondary.active').each(function() {  
			// alert(4);
			
			console.log("##myremark2##"+$(this).children("input[name='ingredient']").val());
			
            selected.push($(this).children("input[name='ingredient']").val());

            val_extra = $.trim($(this).siblings(".extra-price-ingredient").html());

            if (val_extra != '') {

                extras.push(val_extra);

            }      

        });
		// alert(extras);
         console.log("==myremark=="+extras);

        if ($("#remark_input").val() != '') {

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

        if (isNaN(qty)) {

            var qty = 1;

        }

        // alert(qty);

        var unitPrice = parseFloat($(".introduce-remarks.selected").parent().parent().find("input[name='p_price[]']").val());

        // console.log(unitPrice);

        $(".introduce-remarks.selected").parent().parent().find("input[name='p_extra']").val(input_extras);

        $(".introduce-remarks.selected").parent().parent().find("input[name='p_total[]']").val((input_extras + unitPrice < 0) ? 0 : ((input_extras + unitPrice) * qty).toFixed(2));

        $(".introduce-remarks.selected").siblings("input[name='extra']").val(extras);

        $("input[name='single_ingredients'].selected").siblings("input[name='extra']").val(extras);
		var remark_lable = '<?php echo $language["remark1"];?>';
        if (!$(".introduce-remarks.selected").parent().hasClass("pop_model")) {

            $("a.introduce-remarks.selected").html((selected.toString() == '') ? remark_lable : selected.toString().split("_").join(" "));

        } else {

            $("#remark_td").html((selected == '') ? "" : selected.toString().split("_").join(" "));

        }

        $(".introduce-remarks.selected").removeClass("selected");

		console.log('add_remark'+selected);
        $("input[name='ingredients'].selected").val('').val(selected).removeClass("selected");

        $("input[name='single_ingredients'].selected").val('').val(selected).removeClass("selected");

        if ($("#remarks_area").hasClass("transaction")) {

            if ($("#remarks_area").hasClass("no-back")) {

                $("#pop_cart[data-id='" + id + "']").click();

                $("#pop_cart").removeData("id");

                $("#remarks_area").removeClass("no-back");

            } else {

                $(".text_add_cart[data-id='" + id + "']").click();

            }

            $("#remarks_area").removeData("id");

        }

    });

    $(".redirect_fav").click(function(e) {

        window.location.href = "<?php echo $site_url; ?>/index.php";

    });

    $(".insufficient_close").click(function(e) {  

        $(this).removeClass(" btn-primary").addClass("btn-default");

        $("#LesaaAmountModel").modal("hide");  

    });

    $(".manual_input").click(function(e) {

        if ($("#remark_input").attr("type") == "hidden") {

            $("#remark_input").attr("type", "text");

        } else if ($("#remark_input").attr("type") == "text") {

            $("#remark_input").attr("type", "hidden").val('');

        }

        e.preventDefault();

    });

    $("#paypal_cash").click(function(e) {



        $.ajax({

            url: "<?php echo $site_url; ?>/functions.php",

            type: "post",

            data: {
                method: "paypalcash"
            },

            dataType: 'json',

            success: function(response) {

                var data = JSON.parse(JSON.stringify(response));

                if (data.status == true)

                {

                    window.location.replace("https://www.koofamilies.com/orderlist.php");

                } else

                {

                    alert(data.msg);

                }

            }

        });

    });

    $("#paypal_close").click(function(e) {

        window.location.replace("https://www.koofamilies.com/view_merchant.php");

    });

    $(".close_shop").click(function(e) {

        $('#shop_model').modal('hide');

    });
	//*start: Remark Update code */
	/*var remarks = [];
	
	$('.save_close').click(function() {
		var remark_input = $("#remark_input").val();
		console.log('save_close');
		console.log(remark_input);
		
		remarks.push(remark_input);
		var result = '';
        for (var i = 0; i <= remarks.length - 1; i++) {
            // console.log(remarks[i]);
            if (i != remarks.length - 1) {
                result += remarks[i] + "|";
            } else {
                result += remarks[i];
            }
        }
		$("input[name='options']").val(result);
		
            //remarks.push($(this).val());

        });*/
	//*END: Remark Update code */
    //$("input[type='submit']").click(function(e) {


     $(document).ready(function(){
      function shop_open_close(){
          var d = new Date();
		var sorry_rest = "";//"<?php echo $language['sorry_rest'] ?>";
		var selected_lang = "<?php echo $_SESSION['langfile'] ?>";
        var not_working_text = "<?php echo $merchant_detail['not_working_text']; ?>";
        var not_working_text_chiness = "<?php echo $merchant_detail['not_working_text_chiness']; ?>";
		var not_working_text_malay = "<?php echo $merchant_detail['not_working_text_malay']; ?>";
        var working_text = "<?php echo $merchant_detail['working_text']; ?>";
        var working_text_chiness = "<?php echo $merchant_detail['working_text_chiness']; ?>";
		var working_text_malay = "<?php echo $merchant_detail['working_text_malay']; ?>";
        var csv_import_merchant = "<?php echo $merchant_detail['csv_import']; ?>";
        var w1 = "<?php echo $language['w1'] ?>";
        var w2 = "<?php echo $language['w2'] ?>";
        var w3 = "<?php echo $language['w3'] ?>";
        var w4 = "";
        var w6 = "<?php echo $language['w6'] ?>";
        if(csv_import_merchant == 1){
            if (selected_lang == "chinese" && not_working_text_chiness != ''){
             var not_working_text = +not_working_text_chiness;
         }
		 if (selected_lang == "malaysian" && not_working_text_malay != ''){
             var not_working_text = +not_working_text_malay;
         }
		 
         if (selected_lang == "english" && not_working_text != ''){
             var not_working_text = "Our food delivery hours are from "+not_working_text;
         }

         if (selected_lang == "chinese" && working_text_chiness != '')
         {
             var working_text = "我们的外送时间是从 "+working_text_chiness;
         }
		 if (selected_lang == "malaysian" && working_text_malay != '')
         {
             var working_text = working_text_malay;
         }
		 
         if (selected_lang == "english" && working_text != '')
         {
             var working_text = "Our food delivery hours are from "+working_text;
         }
     }else{
       if (selected_lang == "chinese" && not_working_text_chiness != ''){
           var not_working_text = not_working_text_chiness;
       }
	   if (selected_lang == "malaysian" && not_working_text_malay != ''){
           var not_working_text = not_working_text_malay;
       }
	   
       if (selected_lang == "chinese" && working_text_chiness != '')
       {
        var working_text = working_text_chiness;
		}

}

console.log(working_text+"==========="+not_working_text);


var starttime = $('#stime').val();
var show_time = $('#show_time').val();
var end_time = $('#end_time').val();
var endttime = $('#etime').val();
var starday = $('#sday').val();
var starday1 = starday;
var endday = $('#eday').val();
var endday1 = endday;
var currenttime = $('#ctime').val();

if (starday == 'Monday') {
    starday = 1;
} else if (starday == 'Tuesday') {
    starday = 2;
} else if (starday == 'Wednesday') {
    starday = 3;
} else if (starday == 'Thursday') {
    starday = 4;
} else if (starday == 'Friday') {
    starday = 5;
} else if (starday == 'Saturday') {
    starday = 6;
} else {
    starday = 7;
}

if (endday == 'monday') {
    endday = 1;
} else if (endday == 'Tuesday') {
    endday = 2;
} else if (endday == 'Wednesday') {
    endday = 3;
} else if (endday == 'Thursday') {
    endday = 4;
} else if (endday == 'Friday') {
    endday = 5;
} else if (endday == 'Saturday') {
    endday = 6;
} else {
    endday = 7;
}

var total_work="<?php echo $total_work; ?>";
if (total_work=="y") {
} else {
   $('#shop_close_time').val('y');
   $s = moment(d + starttime + 'PM').format('LT');
   $e = moment(d + endttime + 'PM').format('LT');
   if(not_working_text)
   {
     var m ="<b style='font-size:37px;'>"+sorry_rest+"</b> "+working_text + " (" + not_working_text + "). " + w3 + "</br><span style='color:red;'>" + w4 + "</span>";
     var m1 ="<b>"+sorry_rest+"</b> "+working_text + " (" + not_working_text + "). " + w3 + "</br><span style='color:red;'>" + w4 + "</span>";
 }
 else
 {
    var m ="<b style='font-size:37px;'>"+sorry_rest+"</b> "+working_text + w3 + "</br><span style='color:red;'>" + w4 + "</span>";
    var m1 ="<b>"+sorry_rest+"</b> "+working_text + w3 + "</br><span style='color:red;'>" + w4 + "</span>";
}     
$("#shop_model_text").html(m);
$('#error_label').html(m1);
$('#shop_model').modal('show');
console.log("shop_status###");
$("#shop_model").on("hide.bs.modal", function() {
    const urlParams = new URLSearchParams(window.location.search)
    if (urlParams.has("pd")) {
     let url_product_id = urlParams.get("pd");
     $(document).find(`.text_add_cart[data-id='${url_product_id}']`).click();
 }
});


}
}

$(".mode_btn_hide").css('display','none');
$("#order_confirm_btn").click(function(){

  shop_open_close();
  var session_block_pay = $(this).attr('session_block_pay');
  var internet_banking = $(this).attr('internet_banking');

  $(".mode_btn_hide").css('display','block');
  if(session_block_pay == 'n'){
   $(".block_pay_btn").css('display','block');
}
if(internet_banking == '1'){
   $(".internet_banking_btn").css('display','block');
}
$(".third_order_btn").css('display','block');
$("#select_payment_method").css('display','block');

$("#order_confirm_btn").hide();
console.log('checking');
		//$(".remark_extra_option").trigger(click);
	});
});


$(".show_restriction").click(function() {
	var mo_type = $(this).attr('mo_type');
	$(".rest_other_button").hide();
	$(".rest_skip_button").show();
	if(mo_type == 'wallet'){
		$(".rest_other_button").show();
		$(".rest_skip_button").hide();
		$("#newuser_model").modal('hide');
	}
	$("#show_restriction_popup").modal('show');
	$("#ProccedAmount").modal('hide');
	return false;
});

$(".rest_other_button").click(function() {
	$("#newuser_model").modal('show');
	$("#show_restriction_popup").modal('hide');
	return false;
});


$(".each_mode_btn_hide").click(function(e) {


    var actual_payment_mode = $(this).attr('actual_payment_mode');

    $("#actual_payment_mode").val(actual_payment_mode);
		//$("#payment_mode_cashback").val("0");
		if(actual_payment_mode != "wallet"){
			//$("#wallet_selected").val('n');
			//$("#payable_amount").val('0');
			//$("#rem_amount").val('0');
		}
		
        var remarks = [];

        var price_extra = [];

        $('#cartsection input[name="ingredients"]').each(function() {

            console.log("==="+$(this).val());
			remarks.push($(this).val());

        });
		/*
        $('#cartsection input[name="ingredients"]').each(function() {

            remarks.push($(this).val());

        });
		*/

        $('#test input[name="extra"]').each(function() {

            price_extra.push($(this).val());

        });

        console.log("remarks:"+remarks);

        // console.log(price_extra);

        var result = '';

        for (var i = 0; i <= remarks.length - 1; i++) {

            // console.log(remarks[i]);

            if (i != remarks.length - 1) {

                result += remarks[i] + "|";

            } else {

                result += remarks[i];

            }

        }

        var result_price = '';

        for (var i = 0; i <= price_extra.length - 1; i++) {

            // console.log(price_extra[i]);

            if (i != price_extra.length - 1) {

                result_price += price_extra[i] + "|";

            } else {

                result_price += price_extra[i];

            }

        }

        $("input[name='options']").val(result);

        $("input[name='price_extra']").val(result_price);

        // console.log("result_price");

        // console.log(result_price);

    });

$("body").on("click", ".introduce-remarks", function(e) {

    e.preventDefault();

    $(this).addClass("selected");
	var prd_id = $(this).attr('data-rid');

    if ($(this).parent().parent().parent().parent().hasClass("element-item")) {

        var id = $(this).siblings("input[name='p_id']").val();

            // console.log(id);

            $("#remarks_area").addClass("transaction").attr("data-id", id);

        }

        $("#ProductAdded").modal("hide");

       // $(this).parent().parent().find("input[name='ingredients']").addClass("selected");

        $(this).parent().parent().find("input[name='single_ingredients']").addClass("selected");


		$(".producttr_"+prd_id).find("input[name='ingredients']").addClass("selected");

        //$(".producttr_"+prd_id).find("input[name='single_ingredients']").addClass("selected");


      //  var ingredients = $(this).parent().parent().find("input[name='ingredients']").val();
		var ingredients = $(".producttr_"+prd_id).find("input[name='ingredients']").val();

        $("#remarks_area .modal-body .btn-group .btn-secondary").removeClass("checkbox-checked").removeClass("active");



    });

$(".modal-footer").on("click", ".introduce-remarks", function() {

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

        if ($(this).parent().is("#without_varient_footer")) {

            $("#remarks_area").addClass("no-back");

        }
		
		var add_to_cart_lang = '<?php echo $language["add_to_cart1"];?>';
		var remark1_lang = '<?php echo $language["remark1"];?>';
	
        $('#ProductModel').modal('hide');

        $(".text_add_cart[data-id='" + id + "']").parent().siblings(".introduce-remarks").click();
		
        $(".modal_pop").html("<a href='#remarks_area' role='button' class='introduce-remarks btn btn-large btn-primary hideLoader' data-toggle='modal'>"+remark1_lang+"</a><input type='hidden' name='single_ingredients' value='" + single_remarks + "'/><input type='hidden' name='extra' value='" + p_extra + "'/><span id='pop_cart' data-id='" + id + "' data-code='" + code + "'  data-name='" + name + "' data-quantity='" + quantity + "'>"+add_to_cart_lang+"</span>");

        return false;

    })

    // Remark

    //  product varient feature 

    //$(".without_varient").on("click", function() {
	$('.search_response').on('click', '.without_varient', function(e) {
         console.log("###Without varient###");

        // $(this).hide();

		//product details show in popup
		$(".no_foods_options").val('');
		var product_name_popup = $(this).attr('product_name');
		var product_remark_popup = $(this).attr('product_remark');
		var span_price_popup = $(this).attr('product_price');
		
		$(".product_name_popup").html(product_name_popup);
		$(".product_remark_popup").remove();
		if(product_remark_popup != ''){
			$(".product_price_popup").before('<p class="product_remark_popup" style="padding-top:10px;">'+product_remark_popup+'</p>');
		}
		$(".span_price_popup").html(span_price_popup);

        $("#product_main").html("");

        $("#product_table").html("");

        var id = $(this).data("id");

        var child_id = "child_" + id;

        var product_child_id = "product_child_" + id;

        var single_remarks = $(this).parent().parent().find("input[name='single_ingredients']").val();

        var p_extra = $(this).parent().parent().find("input[name='extra']").val();

        if (p_extra == '') {

            p_extra = 0;

        }

        document.getElementById(child_id).classList.remove("fa-plus");

        var code = $(this).data("code");

        var p_price = $(this).data("pr");

        var rebate = $(this).data("rebate");

        var name = $(this).data("name");

        // console.log("Price extra: " + p_extra);

        var extra_price = 0;

        if (p_extra == '') {

            p_extra = 0;

        } else {

            p_extra = p_extra.split(",")

            for (var i = 0; i < p_extra.length; i++) {

                extra_price += parseFloat(p_extra[i]);

            }

        }

        // console.log("Extra price" + extra_price);

        var quantity = $(this).closest("form").find("input[name='quatity']").val();

        let p_total = 0;

        p_total = p_price * quantity + extra_price;

        p_total = p_total.toFixed(2);

        $("#p_pop_price").val(p_total);

        // alert(p_total);

        var qty_lable = '<?php echo $language["quantity"] ?>';

        var ok_lable = '<?php echo $language["ok"] ?>';

        var remark_lable = '<?php echo $language["remarks"] ?>';
		
		var prd_one_offer = $(this).attr('prd_one_offer');
		var one_product_offer ="<?php echo $merchant_detail['one_product_offer'];?>";	
		//console.log("prd_one_offer="+prd_one_offer);
		//console.log("one_product_offer="+one_product_offer);
		var qty_readonly = '';
		if(prd_one_offer == 1 && one_product_offer == 1){
			var qty_readonly = "readonly='readonly'";
		}

        // console.log(qty_lable);
		$(".no_foods_options").addClass("no_foods_options_"+id);
        $("#without_varient_footer").html("<div class='row'><div class='col-md-12'>" + qty_lable + ": <input name='quantity_input' min='1' "+ qty_readonly +" type='number' class='quatity' value='" + quantity + "' style='width:2.5em;text-align:center' min='0' max='99'/></div></div><a href='#remarks_area' role='button' class='introduce-remarks btn btn-large btn-primary hideLoader' data-toggle='modal' style='top:unset;bottom:3px;right:100px;border-radius: 5px;'>" + remark_lable + "</a><input type='hidden' name='single_ingredients' value='" + single_remarks + "'/><input type='hidden' name='extra' value='" + extra_price + "'/><span id='pop_cart' data-rebate='" + rebate + "' data-id='" + id + "' data-code='" + code + "' prd_one_offer ='"+prd_one_offer+"'  data-name='" + name + "' data-quantity='" + quantity + "' data-pr=" + p_price + " class='close_pop btn btn-large btn-primary' data-dismiss='modal' style='background:#50D2B7;border:none;'>" + ok_lable + "</span>");
        $("#without_varient_footer").html("<div class='row'><div class='col-md-12'>" + qty_lable + ": <input name='quantity_input' min='1' "+ qty_readonly+" type='number' class='quatity' value='" + quantity + "' style='width:2.5em;text-align:center' min='0' max='99'/></div></div><a href='#remarks_area' role='button' class='introduce-remarks btn btn-large btn-primary hideLoader' data-toggle='modal' style='top:unset;bottom:3px;right:100px;border-radius: 5px;'>" + remark_lable + "</a><input type='hidden' name='single_ingredients' value='" + single_remarks + "'/><input type='hidden' name='extra' value='" + extra_price + "'/><span id='pop_cart' data-rebate='" + rebate + "' data-id='" + id + "' data-code='" + code + "' prd_one_offer ='"+prd_one_offer+"'  data-name='" + name + "' data-quantity='" + quantity + "' data-pr=" + p_price + " class='close_pop btn btn-large btn-primary' data-dismiss='modal' style='background:#50D2B7;border:none;'>" + ok_lable + "</span>");



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

$("#without_varient_footer").on("change", "input[name='quantity_input']", function() {

    var newQuantity = $(this).val();

    $(this).parent().parent().parent().find("#pop_cart").attr("data-quantity", newQuantity);

});

//$(".with_varient").on("click", function() {
$('.search_response').on('click', '.with_varient', function(e) {
        // $(this).hide();

        // alert(4);  
		//$(".no_foods_options").val('');
        var p_price = $(this).data("pr");

        console.log(p_price);

        // $('#varient_count').val(0);

        $("#product_main").html("");

        $("#product_table").html("");

        var id = $(this).data("id");

        var varient_must = $(this).attr("data_varient_must");

        $('#varient_must').val(varient_must);

        var child_id = "child_" + id;

        var product_child_id = "product_child_" + id;

        var subproduct_selected = '';

        var single_remarks = $(this).parent().parent().find("input[name='single_ingredients']").val();

        var p_extra = $(this).parent().parent().find("input[name='extra']").val();
		
		console.log("##@@@###"+single_remarks);

        // alert(p_extra);

        document.getElementById(child_id).classList.remove("fa-plus");

        document.getElementById(child_id).classList.add("fa-check");

        document.getElementById(product_child_id).style.backgroundColor = "red";

        var code = $(this).data("code");

        var name = $(this).data("name");

        var rebate = $(this).data("rebate");

        var quantity = $(this).siblings(".quantity").children(".quatity").val();
		
		var prd_one_offer = $(this).attr('prd_one_offer');

        // alert(quantity);

        // var p_total = p_price*quantity;

        p_total = parseFloat(p_price).toFixed(2);

        $("#varient_name").html(name);

        $("#p_pop_price").val(p_total);

        $("#product_table").append("<tr><td> " + name + " </td><td> " + p_total + " </td></tr>");

        $("#pr_total").html("<b>" + p_total + "</b>");

        $("#remark_td").html((single_remarks == '') ? "" : single_remarks.toString().split("_").join(" "));

        // $("#product_info").html("<p>"+name+": Rm "+p_total+"</p>");

        //$(".no_foods_options").addClass("no_foods_options_"+id);
		
		
		
		var chk_no_product_options ="<?php echo $merchant_detail['no_product_options'];?>";	
		var temp_uid = "<?php echo $_SESSION['user_id'];?>";	
		var add_to_cart_lang = '<?php echo $language["add_to_cart1"];?>';
		var remark1_lang = '<?php echo $language["remark1"];?>';
	
		if(chk_no_product_options == 1 ){
		var no_foods_html ='<select name="no_foods_options" id="no_foods_options" class="form-control  no_foods_options_'+id+' " style="margin-top: 10px;height:29px;font-size: 13px;padding:0px;width: 150px !important;margin-right: 6px;"><option value=""><?php echo $language["food_not_availble_label"];?></option><option value="Remove it from my order"><?php echo $language["food_not_availble_option1"];?></option><option value="Cancel the entire order"><?php echo $language["food_not_availble_option2"];?></option><option value="Call me for change of food/merchant"><?php echo $language["food_not_availble_option3"];?></option><option value="Buy from other best rating merchant"><?php echo $language["food_not_availble_option4"];?></option></select>';
		
		
		$(".pop_model_withvarient").html("<div class='row' style='width:11em'></div>"+no_foods_html+"<a href='#remarks_area' role='button' class='introduce-remarks btn btn-large btn-primary hideLoader' data-toggle='modal' style='padding: 3px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;'>"+remark1_lang+"</a><input type='hidden' name='single_ingredients' value='" + single_remarks + "'/><input type='hidden' name='extra' value='" + p_extra + "'/><span id='pop_cart' prd_one_offer ='"+prd_one_offer+"' data-rebate='" + rebate + "' data-pr=" + p_price + " data-id='" + id + "' data-code='" + code + "'  data-name='" + name + "' data-quantity='" + quantity + "' style='width:300px'>"+add_to_cart_lang+"</span>");
		
		}else{
			var no_foods_html ='';
		
		$(".pop_model_withvarient").html("<div class='row' style='width:11em'></div>"+no_foods_html+"<a href='#remarks_area' role='button' class='introduce-remarks btn btn-large btn-primary hideLoader' data-toggle='modal' style='padding: 3px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;'>"+remark1_lang+"</a><input type='hidden' name='single_ingredients' value='" + single_remarks + "'/><input type='hidden' name='extra' value='" + p_extra + "'/><span id='pop_cart' data-rebate='" + rebate + "' data-pr=" + p_price + " data-id='" + id + "' data-code='" + code + "'  data-name='" + name + "' data-quantity='" + quantity + "'>"+add_to_cart_lang+"</span>");
			
		}
		
		



        for (var i = 0; i < subproducts_global.length; i++) {

            for (var j = 0; j < subproducts_global[i].length; j++) {

                if (subproducts_global[i][j]['product_id'] == id) {

                    subproduct_selected = subproducts_global[i];

                    break;

                }

            }

        }

        console.log(subproducts_global);

        var exists_in_subproducts = false;

        for (var i = 0; i < subproduct_selected.length; i++) {

            if (subproduct_selected[i]['product_id'] == id) {

                exists_in_subproducts = true;

                break;

            }

        }
        console.log(subproduct_selected.length);
        if (exists_in_subproducts) {

            var content = '';

            for (var i = 0; i < subproduct_selected.length; i++) {

                content += "<div  id='prodct_cart_" + subproduct_selected[i]['id'] + "' data-name='" + subproduct_selected[i]['name'] + "' data-id='" + subproduct_selected[i]['id'] + "' data-price='" + subproduct_selected[i]['product_price'] + "' class='ingredient product_cart'>";

                content += "<button type='button' class='btn btn-info remove-ingredient' data-name='" + subproduct_selected[i]['name'] + "' data-id='" + subproduct_selected[i]['id'] + "' data-price='" + subproduct_selected[i]['product_price'] + "' aria-label='Close'>";

                content += "<span aria-hidden='true'><i class='fa fa-plus'></i></span>";

                content += "</button><span class='ingredient-name'>" + subproduct_selected[i]['name'] + " &nbsp; Price Rm " + subproduct_selected[i]['product_price'] + "</span></div>";

                // console.log(content);



            }

            $("#product_main").html(content);

            console.log(varient_selected);

            varient_selected.forEach(function(e) {

                $("#product_main div").each(function() {

                    if ($(this).data("name") == e)

                        $(this).click();

                })

            });

            varient_selected = [];

            if (showModal) {

                $("#ProductModel").modal("show");

            } else {

                showModal = true;

            }

        }

    });

$(document).on("click", '.product_cart', function(event) {

    $('#varient_error').hide();

    var objDiv = document.querySelector("#data .product_data");
    objDiv.scrollTop = objDiv.scrollHeight;

    var content = "";

    var id = $(this).data("id");

    var varient_count = $('#varient_count').val();

    varient_count++;

        // alert(varient_count);

        $('#varient_count').val(varient_count);

        var p_price = $(this).data("price");

        var name = $(this).data("name");

        // alert(p_price);

        var p_price = parseFloat(p_price).toFixed(2);

        var p_pop_price = $("#p_pop_price").val();

        var c_id = "prodct_cart_" + id;

        // alert(c_id);

        var sum = parseFloat(p_price) + parseFloat(p_pop_price);

        // var sum= parseFloat((p_price).toFixed(2));

        var sum = parseFloat(sum).toFixed(2);

        // alert(sum);

        $("#p_pop_price").val(sum);

        $("#pr_total").html("<b>" + sum + "</b>");

        $("#varient").append("<br/>-" + name + "(Rm " + p_price + ")");

        var old_varent = $("#varient_type").html();

        $("#varient_type").html(old_varent + "," + id + "");



        // var content="<p data-id="+id+" data-pr="+p_price+">"+name +"  : Rm "+p_price +"<button  data-id="+id+" data-name="+name+" data-pr="+p_price+" type='button' class='removevarient'>X</button></p>";

        // content +="<div  id='prodct_cart_"+id+"' data-name='"+name+"' data-id='"+id+"' data-price='"+p_price+"' class='ingredient product_cart'>";

        // content +="<button type='button' class='btn btn-info remove-ingredient' data-name='"+name+"' data-id='"+name+"' data-price='"+p_price+"' aria-label='Close'>";

        // content +="<span aria-hidden='true'>X</span>";

        // content +="</button><span class='ingredient-name'>"+name+" &nbsp; Price Rm "+p_price+"</span></div>";



        // alert(content);

        var link = document.getElementById(c_id);

        link.style.display = 'none'; //or

        var show_label = "" + name + " (Rm " + p_price + ")";

        // alert(show_label);

        // $("#product_info").append(content); 

        $("#product_table").append("<tr><td> &nbsp;&nbsp;<button data-id=" + id + " data-name=" + name + " data-pr=" + p_price + " type='button' class='removevarient'><i class='fa fa-remove'></i></button>-<span show_label='" + show_label + "' class='sub_pro_name'>" + name + "</span></td><td> " + p_price + " </td></tr>");

        content = '';

    });

$(document).on("click", '.removevarient', function(event) {

    var varient_count = $('#varient_count').val();

    varient_count--;

        // alert(varient_count);

        $('#varient_count').val(varient_count);

        var id = $(this).data("id");

        var price = $(this).data("pr");

        var price = parseFloat(price).toFixed(2);

        var name = $(this).data("name");

        var c_id = "prodct_cart_" + id;

        var p_pop_price = $("#p_pop_price").val();

        var p_pop_price = parseFloat(p_pop_price).toFixed(2);

        var old_varent = $("#varient_type").html();

        var varent_list = $("#varient").html();

        // alert(varent_list);

        var r_key = "<br>-" + name + "(Rm " + price + ")";

        // alert(r_key);

        var new_varient_list = varent_list.replace(r_key, '');

        var new_vareint = old_varent.replace(id, '');

        $("#varient").html(new_varient_list);

        $("#varient_type").html(new_vareint);

        var link = document.getElementById(c_id);

        link.style.display = 'block'; //or

        var sum = parseFloat(p_pop_price) - parseFloat(price);

        var sum = parseFloat(sum).toFixed(2);

        $("#p_pop_price").val(sum);

        $("#pr_total").html("<b>" + sum + "</b>");

        jQuery(this).closest('tr').remove();

    });

$("#ProductModel").on("hide.bs.modal", function() {
    let id = $(this).find("#pop_cart").attr("data-id");
    let child_id = `child_${id}`;
    document.getElementById(child_id).classList.remove("fa-check");
    document.getElementById(child_id).classList.add("fa-plus");
});

$(document).on("click", '#pop_cart', function(event) {
//alert('chk_with_mbl');
var varient_must = $('#varient_must').val();
var last_added_id = $(".producttr").length;
var last_added_id = 0;
var go_ahead = "y";
if (varient_must == "y")
{
    var varient_count = $('#varient_count').val();
// if(varient_count>0)
// {
// }
// else
// {  
// var go_ahead="n";
// $('#varient_error').show(); 
// }
if ($(this).parent().is("#without_varient_footer")) {
// do nothing
} else {
    var varient_count = $(".removevarient").length;
// alert(varient_count);
if (varient_count < 1)
{
    $('#varient_error').show();
    return false;
}
}
} else
{
    var go_ahead = "y";
}
if (go_ahead == "y")
{
    var select_varient = $('#select_varient').val();
//var p_price = $(this).data("pr");
var p_price = $(this).data("pr");
var single_remarks = $(this).siblings("input[name='single_ingredients']").val();
var p_extra = $(this).siblings("input[name='extra']").val();
$("input[name='single_ingredients']").val('');
$(".element-item input[name='extra']").val('');
$("#ProductModel").modal("hide");
var sub_str = $("#varient").html();
var sub_count = $(".sub_pro_name").length;
var f_html = '';
var f_html = $(".sub_pro_name")
.map(function() {
    return $(this).attr('show_label');
})
.get()
.join("</br>-");
if (sub_count > 0)
    var name = $(this).data("name") + "</br> " + f_html;
else
    var name = $(this).data("name");
var s_id = $(this).data("id");
var id = $(this).data("id") + last_added_id;
// alert(id);
var qty_id_check = id + "_test_athy";
if (document.getElementById(qty_id_check)) {
    var id = id + randomNumber(1, 10000);
//object already exists
}
var rebate_per = $(this).data("rebate");
var child_id = "child_" + id;
var child_s_id = "child_" + $(this).data("id");
var product_child_s_id = "product_child_" + $(this).data("id");
var product_child_id = "product_child_" + id;
var extra_child_id = "extra_child_" + id;
var extra_price = 0;
if (p_extra == '') {
    p_extra = 0;
} else {
    p_extra = p_extra.split(",")
    for (var i = 0; i < p_extra.length; i++) {
        extra_price += parseFloat(p_extra[i]);
    }
}
var p_pop_price = $("#p_pop_price").val();
var quantity = $(this).data("quantity");
var product_price = (p_pop_price == "" || !p_pop_price) ? parseFloat($(this).data("pr")) : parseFloat(p_pop_price);
var p_total = (product_price + extra_price).toFixed(2) * parseInt(quantity);
var p_total = p_total.toFixed(2);
console.log(p_total+"===="+rebate_per);
var rebate_amount = rebatevalue(p_total, rebate_per);
$("#p_pop_price").val("");
// alert(rebate_amount);  
// console.log(p_price);
// console.log(p_extra);
// console.log(p_total);
var code = $(this).data("code");
document.getElementById(child_s_id).classList.add("fa-plus");
document.getElementById(child_s_id).classList.remove("fa-check");
document.getElementById(product_child_s_id).style.backgroundColor = "red";
var varient_type = $("#varient_type").html();
// alert(varient_type); 
var remark_lable = '<?php echo $language["remarks"] ?>';
if (product_price > 0) {
//alert('product_price');
/* save cart data to session - 23/12/20*/
var only_name  = $(this).data("name");
if(f_html != ''){
	var f_html = "-"+f_html;
}

var no_foods_options = $(".no_foods_options_"+s_id).val();
//console.log(id+"===s_id###===="+s_id+"==foods==="+no_foods_options);
if(no_foods_options == 'undefined'){
	var no_foods_options = '';
}

//console.log(id+"===s_id===="+s_id+"==foods==="+no_foods_options);

	var prd_one_offer = $(this).attr('prd_one_offer');
	var one_product_offer ="<?php echo $merchant_detail['one_product_offer'];?>";	
	var qty_readonly = '';
	if(prd_one_offer == 1 && one_product_offer == 1){
		 var qty_readonly = "readonly='readonly'";
		 $("#product_child_"+id).hide();
	}


var cartData = {};
cartData['type'] = 'save_session';
cartData['name'] = name;
cartData['no_foods_options'] = no_foods_options;
cartData['only_name'] = $(this).data("name");
cartData['sub_varient'] = f_html;
cartData['rebate_amount'] = rebate_amount;
cartData['id'] = id;
cartData['prd_one_offer'] = prd_one_offer;
cartData['one_product_offer'] = one_product_offer;
cartData['rebate_per'] = rebate_per;
cartData['product_price'] = product_price;
cartData['quantity'] = quantity;
cartData['s_id'] = s_id;
cartData['code'] = code;
cartData['single_remarks'] = single_remarks;
cartData['remark_lable'] = remark_lable;
cartData['extra_price'] = extra_price;
cartData['extra_child_id'] = extra_child_id;
cartData['p_total'] = p_total;
cartData['varient_type'] = varient_type;
jQuery.post('<?php echo $site_url; ?>/ajaxcartresponse.php', cartData, function (result) {
//var response = jQuery.parseJSON(result);
console.log(result);
});
/* End save data to cart*/	
//alert('chk2');
/*$("#test").append("<tr class='producttr'>  <td><button remove_productid="+ id +"  type='button' class='removebutton'>X</button> </td><td class='pro1_name'>" + name + "</td><td><input type='hidden' name='rebate_amount[]' class='rebate_amount' value=" + rebate_amount + " id='" + id + "rebate_amount'><input type='hidden' name='rebate_per[]' value=" + rebate_per + " id='" + id + "rebate_per'><input style='width:50px;'  onchange='UpdateTotal(" + id + "," + product_price + ")'  type=number name='qty[]' min='1' maxlength='3' class='product_qty quatity'  value=" + quantity + " id='" + id + "_test_athy'><input type= hidden name='p_id[]' value= " + s_id + "><input type= hidden name='p_code[]' value= " + code + "><input type='hidden' name='ingredients' value='" + single_remarks + "'/></td><td>" + code + "</td><td><a href='#remarks_area' data-rid='" + id + "' role='button' class='introduce-remarks btn btn-large btn-primary hideLoader' data-toggle='modal'>" + ((single_remarks == '') ? remark_lable : single_remarks) + "</a><input type='hidden' id='" + extra_child_id + "' name='extra' value='" + extra_price + "'></td><td><input style='width:70px;text-align:right;' type='text' name='p_extra' id='" + extra_child_id + "' value='" + extra_price.toFixed(2) + "' readonly></td><td><input style='width:70px;' type='text' name='p_price[]' value='" + product_price + "' readonly></td><td><input type='text' style='width:70px;' class='p_total' name='p_total[]' value= " + p_total + " readonly  id='" + id + "_cat_total'><input type='hidden' name='varient_type[]' value=" + varient_type + "></td> </tr>");
*/

/* New cart add*/
//sub varient

$subvarientsHtml = '';
$rand_iddds = randomNumber(1, 10);
//
var lang_readmore = "<?php echo $language['read_more1'];?>";
if(f_html != ''){
	$subvarientsHtml = '<div class="varient_list" ><p class="moretext va_sub" id="moretext_'+ id +'_'+$rand_iddds+'" style="display:none">'+ f_html +'<br/><span>'+no_foods_options+'</span></p><a class="moreless-button moreless-button_'+ id +'_'+$rand_iddds+'" href="javascript:void(0)" product_id="'+ id +'_'+$rand_iddds+'">'+lang_readmore+'</a></div>';
}

if(f_html == '' && no_foods_options != '' && no_foods_options == 'undefined'){
	$subvarientsHtml = '<div class="varient_list" ><p class="moretext va_sub" id="moretext_'+ id +'_'+$rand_iddds+'" style="display:none">'+ no_foods_options +'</p><a class="moreless-button moreless-button_'+ id +'_'+$rand_iddds+'" href="javascript:void(0)" product_id="'+ id +'_'+$rand_iddds+'">'+lang_readmore+'</a></div>';
}

//$cart_html1 = "<input type='hidden' name='rebate_amount[]' class='rebate_amount' value=" + rebate_amount + " id='" + id + "rebate_amount'><input type='hidden' name='rebate_per[]' value=" + rebate_per + " id='" + id + "rebate_per'><input type= hidden name='p_id[]' value= " + s_id + "><input type= hidden name='p_code[]' value= " + code + "><input type='hidden' name='ingredients' value='" + single_remarks + "'/><input type='hidden' id='" + extra_child_id + "' name='extra' value='" + extra_price + "'><input type='hidden' name='varient_type[]' value=" + varient_type + ">";

var total_label = '<?php echo $language["total1"] ;?>';
 
$cart_html1 = "<input type='hidden' name='rebate_amount[]' class='rebate_amount' value=" + rebate_amount +" id='"+ id +"rebate_amount'><input type='hidden' name='rebate_per[]' value="+ rebate_per + " id='"+ id +"rebate_per'><input type= hidden name='p_id[]' value= "+ s_id +"><input type= hidden name='p_code[]' value= "+ code +"><input type='hidden' name='ingredients' value='"+ single_remarks +"'/><input type='hidden' name='varient_type[]' value="+ varient_type +"><input type='hidden' id='"+ extra_child_id +"' name='extra' value='"+ extra_price +"'><input type='hidden' style='width:70px;' class='p_total' name='p_total[]' value= "+p_total+" readonly  id='"+ id +"_cat_total'><input style='width:70px;' type='hidden' name='p_price[]' value='"+ product_price + "' readonly><input type='hidden' name='no_food_hidden_options[]' value='"+no_foods_options+"'><input style='width:70px;text-align:right;' type='hidden' name='p_extra' id='"+ extra_child_id + "' value='"+ extra_price +"' readonly>";

$cart_html2 = '<div class="cart-detail-row producttr producttr_'+ id +'">'+$cart_html1+'<div class="cart-d-cell c-t-cell"><p class="remove-icon removebutton" prd_id = '+s_id+' one_product_offer = '+one_product_offer+' prd_one_offer='+prd_one_offer+' remove_productid='+ id +'><i class="fa fa-trash-o" aria-hidden="true"></i></p><div class="title-qty"><input type="number" name="qty[]" class="mw1 product_qty quatity" min="1" maxlength="3" '+qty_readonly+' value='+ quantity +' id="'+ id + '_test_athy" onchange="UpdateTotal(' + id + ',' + product_price + ')" style="width: 25px;padding: 4px;height: 25px;float: right;"/><p class="prd-name pro1_name">'+ only_name +'| '+code+' </p></div></div>'+$subvarientsHtml+'<div class="cart-d-cell"></div><div class="cart-d-cell"><p class="remark-text"><a href="#remarks_area" data-rid="'+ id +'" role="button" class="introduce-remarks btn btn-large btn-primary hideLoader" data-toggle="modal">'+  ((single_remarks == "") ? remark_lable : single_remarks) +'</a> </p><p class="price-text"><strong>'+total_label+' : </strong>RM <span class="p_span_total '+ id +'_cat_total">'+ p_total +'</span></p></div></div>';

$cart_final_html = $cart_html1+$cart_html2;
$(".cart_wrapper").append($cart_html2);

/*end cart*/



}
// alert('The product added');
$("#varient_type").html('');
$("#varient").html('');
// $('#show_msg_product').html("<?php echo $language['the_product_added']; ?>");  
// $('#ProductshowModel').modal('show');
// setTimeout(function(){ $("#ProductshowModel").modal("hide"); },500); 
} else
{
    $('#varient_error').show();
}
$("#product_added_to_cart").modal("show");
setTimeout(() => {
    $("#product_added_to_cart").modal("hide");
}, 1500);
totalcart();
});

    // end product varient 

    var other_product_id = 1;

    $(".oth_pr").on("click", function() {

        /*$('html,body').animate({

            scrollTop: $("#cartsection").offset().top
        },

        'slow');
*/


        /*$("#test").append("<tr>  <td><button type='button' class='removebutton'>X</button> </td><td><input style='width:120px;' type=text  id='other_product_name_" + other_product_id + "' class='other_product_name'><input type='hidden' name='rebate_amount[]' class='rebate_amount'  id='" + other_product_id + "rebate_amount'><input type='hidden' name='p_id[]' id='other_product_id_" + other_product_id + "'></td> <td><input style='width:50px;' onchange='UpdateTotalCart(" + other_product_id + ")' id='other_qty_" + other_product_id + "' type=number name='qty[]' min='1' class='product_qty quatity' value='1'></td> <td><input class='other_product_code' style='width:70px;' type= text name='p_code[]' id='other_product_code_" + other_product_id + "'><input type='hidden' name='ingredients'/></td><td> <a href='#remarks_area' role='button' class='introduce-remarks btn btn-large btn-primary hideLoader' data-toggle='modal'>Remarks</a><input type='hidden' name='extra'></td><td><input style='width:70px;text-align:right;' type='text' name='p_extra' value='0' readonly></td><td><input style='width:70px;' id='other_product_price_" + other_product_id + "' type='text' name='p_price[]' readonly></td><td><input type='text' style='width:70px;' class='p_total' name='p_total[]' readonly  id='" + other_product_id + "_cat_total'></td></tr>");
		*/
		
		
		
		$cart_html2 = '<div class="cart-detail-row producttr producttr_"><div class="cart-d-cell c-t-cell"><p class="remove-icon removebutton" ><i class="fa fa-trash-o" aria-hidden="true"></i></p><div class="title-qty"><input onchange="UpdateTotalCart(' + other_product_id + ')" id="other_qty_' + other_product_id + '" type="number" name="qty[]" min="1" class="product_qty quatity" value="1" style="width: 25px;padding: 4px;height: 25px;float: right;"><p class="prd-name pro1_name"><input style="width:120px;" type=text  id="other_product_name_' + other_product_id + '" class="other_product_name" placeholder="Product Name"><input style="width:70px;" id="no_food_hidden_options_' + other_product_id + '" type="hidden" name="no_food_hidden_options[]" readonly><input style="width:70px;" id="other_product_price_' + other_product_id + '" type="hidden" name="p_price[]" readonly><input type="hidden" style="width:70px;" class="p_total" name="p_total[]" readonly  id="'
		 + other_product_id + '_cat_total"><input type="hidden" name="rebate_amount[]" class="rebate_amount"  id="' + other_product_id + 'rebate_amount"><input type="hidden" name="p_id[]" id="other_product_id_' + other_product_id + '">| <input class="other_product_code" placeholder="Code" style="width:70px;" type= text name="p_code[]" id="other_product_code_' + other_product_id + '"><input type="hidden" name="ingredients"/> </p></div></div><div class="cart-d-cell"></div><div class="cart-d-cell"><p class="remark-text"><a href="#remarks_area" role="button" class="introduce-remarks btn btn-large btn-primary hideLoader" data-toggle="modal">Remarks</a></p><p class="price-text"><strong>Total : </strong>RM <span class="p_span_total '+ other_product_id +'_cat_total" id="'+ other_product_id +'_cat_total_span"></span></p></div></div>';
		$(".cart_wrapper").append($cart_html2);


        if (merchant_mobile != "60172669613")

        {

            var focus_id = "other_product_code_" + other_product_id;

        } else

        {

            var focus_id = "other_product_name_" + other_product_id;

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

            source: "<?php echo $site_url; ?>/auto_complete_product_name.php",

            minLength: 1,

            select: function(event, ui) {

                var id = $(this).attr('id').split('_')[3];

                var qty_id = 'other_qty_' + id;

                var qty_no = document.getElementById(qty_id).value;

                var total_cart = qty_no * (ui.item.price);

                // alert(total_cart);

                var rebate_per = ui.item.product_discount;

                var rebate_amount = rebatevalue(total_cart, rebate_per);

                $("#" + id + "rebate_amount").val(rebate_amount);

                $("#other_product_id_" + id).val(ui.item.id);

                $("#other_product_code_" + id).val(ui.item.code);

                $("#other_product_price_" + id).val(ui.item.price);

                $("#other_product_remark_" + id).val(ui.item.remark);

                var cart_id = id + "_cat_total";

                document.getElementById(cart_id).value = total_cart;
				console.log('od1');
				$("#"+id+"_cat_total_span").html(total_cart);

                totalcart();

            },

            change: function(event, ui) {

                var id = $(this).attr('id').split('_')[3];

                var p_name = 'other_product_name_' + id;

                // alert(p_name);

                if (ui.item == null && ui.item != '') {

                    var p_name = document.getElementById(p_name).value;

                    var res = '';

                    $.ajax({

                        url: '<?php echo $site_url; ?>/product_check.php',

                        type: 'POST',

                        dataType: 'json',

                        data: {
                            p_name: p_name
                        },

                        success: function(res) {

                            // var data = JSON.parse(JSON.stringify(data));

                            // var o_status=data.status;

                            // console.log(data ? "true" : "false");



                            if (res) {

                                var qty_id = 'other_qty_' + id;

                                var qty_no = document.getElementById(qty_id).value;

                                var total_cart = qty_no * (res.product_price);

                                $("#other_product_id_" + id).val(res.id);

                                $("#other_product_code_" + id).val(res.product_type);

                                $("#other_product_name_" + id).val(res.product_name);

                                $("#other_product_price_" + id).val(res.product_price);

                                $("#other_product_remark_" + id).val(res.remark);

                                var cart_id = id + "_cat_total";

                                document.getElementById(cart_id).value = total_cart;
								console.log('od11');
								$("#"+id+"_cat_total_span").html(total_cart);



                            } else {

                                alert('Select Product Name From  list or Enter Proper Product name');

                                $("#other_product_name_" + id).val("");

                            }



                        }

                    });





                }

            }

        });

        jQuery(".other_product_name").keyup(function(e) {

            var id = $(this).attr('id').split('_')[3];

            $("#other_product_id_" + id).val($(this).val());

        });

        jQuery(".other_product_code").autocomplete({

            source: "<?php echo $site_url; ?>/auto_complete_product_code.php",

            minLength: 1,

            select: function(event, ui) {

                var id = $(this).attr('id').split('_')[3];

                var qty_id = 'other_qty_' + id;

                var qty_no = document.getElementById(qty_id).value;

                var total_cart = qty_no * (ui.item.price);

                $("#other_product_id_" + id).val(ui.item.id);

                var rebate_per = ui.item.product_discount;

                var rebate_amount = rebatevalue(total_cart, rebate_per);

                $("#" + id + "rebate_amount").val(rebate_amount);

                $("#other_product_code_" + id).val(ui.item.code);

                $("#other_product_name_" + id).val(ui.item.name);

                $("#other_product_price_" + id).val(ui.item.price);

                $("#other_product_remark_" + id).val(ui.item.remark);

                var cart_id = id + "_cat_total";

                document.getElementById(cart_id).value = total_cart;
				console.log('od111');
				$("#"+id+"_cat_total_span").html(total_cart);

                totalcart();

            },

            change: function(event, ui) {

                var id = $(this).attr('id').split('_')[3];

                var pr_code = 'other_product_code_' + id;

                if (ui.item == null) {

                    var pr_code = document.getElementById(pr_code).value;

                    var res = '';

                    $.ajax({

                        url: '<?php echo $site_url; ?>/product_check.php',

                        type: 'POST',

                        dataType: 'json',

                        data: {
                            pr_code: pr_code
                        },

                        success: function(res) {

                            // var data = JSON.parse(JSON.stringify(data));

                            // var o_status=data.status;

                            // console.log(data ? "true" : "false");



                            if (res) {

                                var qty_id = 'other_qty_' + id;

                                var qty_no = document.getElementById(qty_id).value;

                                var total_cart = qty_no * (res.product_price);

                                $("#other_product_id_" + id).val(res.id);

                                $("#other_product_code_" + id).val(res.product_type);

                                $("#other_product_name_" + id).val(res.product_name);

                                $("#other_product_price_" + id).val(res.product_price);

                                $("#other_product_remark_" + id).val(res.remark);

                                var cart_id = id + "_cat_total";

                                document.getElementById(cart_id).value = total_cart;
								console.log('od123');
								$("#"+id+"_cat_total_span").html(total_cart);




                            } else {

                                alert('Select Code From Product Code list or Enter Proper code');

                                $("#other_product_code_" + id).val("");

                            }



                        }

                    });





                }

            }

        });

    });



jQuery(document).on('click', '.removebutton', function() {

    alert("Product has Removed");
    var productid = $(this).attr('remove_productid');
	
	
	var productMainid = $(this).attr('prd_id');
	var one_product_offer = $(this).attr('one_product_offer');
	var prd_one_offer = $(this).attr('prd_one_offer');
	
	
	
	
    jQuery('.producttr_'+productid).remove();
	totalcart();
        //jQuery(this).closest('tr').remove();


        /* remove product session - 23/12/20*/
        var remove_productid = jQuery(this).attr('remove_productid');
        var cartData = {};
        cartData['type'] = 'remove_product';
        cartData['id'] = remove_productid;
        jQuery.post('<?php echo $site_url; ?>/ajaxcartresponse.php', cartData, function (result) {
           console.log(result);
			//if(one_product_offer == 1 && prd_one_offer == 1){
				$("#product_child_"+productMainid).show();
				$("#product_child_"+productMainid).css( "display",'block !important;' );;
			//}
       });
        /*End cart qty update to session - 23/12/20 */



        totalcart();

        return false;

    });

$("#agent_code_input").on("keyup", function() {

    var number = $('#mobile_number').val();

    var agent_code_input = $("#agent_code_input").val();

    if (agent_code_input)

    {

        if (number.length >= 9 && number.length <= 12 && (number[0] == 1 || number[0] == 6)) {

            $.get("<?php echo $site_url; ?>//view_merchant.php", {

                q: "verifyAgentCode",

                mobile: number,

                code: $(this).val()

            }, function(data) {

                if (data == 'true')

                {

                    $('#agent_error').hide();

                    $("#agent_code_input").removeClass("is-invalid").addClass("is-valid");

                } else

                {

                    $("#agent_code_input").removeClass("is-valid").addClass("is-invalid");

                }

            });

        } else

        {

            $('#mobile_number').focus();

            var s_flag = false;

            return false;

        }

    }

});



$("#agent_code_input").on("focusout", function(e) {

    e.preventDefault();

    var number = $('#mobile_number').val();

    var agent_code_input = $("#agent_code_input").val();

    if (agent_code_input)

    {

        if (number.length >= 9 && number.length <= 12 && (number[0] == 1 || number[0] == 6)) {

            $("#agent_code").val($("#agent_code_input").val());

            $.get("<?php echo $site_url; ?>//view_merchant.php", {

                q: "verifyAgentCode",

                mobile: number,

                code: $("#agent_code_input").val()

            }, function(data) {



                if (data == 'true') {

                    $('#agent_error').hide();

                    $("#agent_code").val($("#agent_code_input").val());

                    $("#agent_code_modal").modal("hide");

                } else if (data == "max") {

                    $("#agent_code").val('');

                        // $('#agent_error').html('You Can Only introduce 5 agent code');

                        // $('#agent_error').show();



                    } else {

                        // $("#agqzent_code").val("");

                        // console.log(data);

                    }



                });

        } else

        {

            $('#mobile_number').focus();

            var s_flag = false;

            return false;

        }

    }





});
</script>



<style>
.category_filter {

    margin-bottom: 10px;

    padding: 8px;

}

.sub_category_grid {

    margin-top: 10px;

}

.other_products {

    display: none;

}

.text_add_cart {

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

.text_add_cart_without {

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
    align-items: center;
    width: 100%;

}
.comm_prd h4 { margin: 0; font-size: 16px;}

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

    display: flex;

}

.rating_menuss {

    padding: 5px;

    margin-right: 25px;

    padding: 5px;

    background: #00736A;

    width: 120px;

    text-align: center;

    padding: 5px;

    color: #fff !important;

    text-transform: uppercase;

    font-weight: 600;

    cursor: pointer;

    margin-bottom: 10px;

    margin-right: 15px;

    border-radius: 10px;
    box-shadow: -3px 3px #fa7953, -2px 2px #fa7953, -1px 1px #fa7953;
    border: 1px solid #fa7953;

}

.about_uss {

    padding: 7px !important;

    margin-right: 25px;

    padding: 5px;

    background: #00736A;

    width: 120px;

    text-align: center;

    padding: 5px;

    color: #000 !important;

    text-transform: uppercase;

    font-weight: 600;

    cursor: pointer;

    margin-bottom: 10px;

    margin-right: 15px;

    border-radius: 10px;
    box-shadow: -3px 3px #fa7953, -2px 2px #fa7953, -1px 1px #fa7953;
    border: 1px solid #fa7953;

}

a.merchant_about {

    color: #fff;

}

a.merchant_ratings {

    color: #fff;

}

p.no_stock_add_to_cart {

    width: 100%;

    padding: 15px;

    text-align: center;

    background-color: #fff;

}

.no_stock_add_to_cart {

    border-radius: 5px;

    color: #f92d2d;

    font-weight: bold;

    box-sizing: border-box;

}

.container_test.out_of_stock {

    position: relative;

}

.container_test.out_of_stock:after {

    content: '';

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

.container_test.not_available {

    position: relative;

}

.container_test.not_available:after {

    content: '';

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

    function randomNumber(min, max) {
        return Math.floor(Math.random() * (max - min) + min);
    }

    function toSeconds(date) {

        var a = date.split(':');

        return parseInt(a[0]) * 3600 + parseInt(a[1]) * 60;

    }

    function isActive(date) { // Checks if a product is active by date

        if (date == 1) {

            return true;

        }

        var dateObj = JSON.parse(date);

        // console.log(dateObj);

        var currentDate = new Date();

        var currentDay = parseInt(currentDate.getDay()) + 1;

        var currentHours = currentDate.getHours() + ":" + currentDate.getMinutes();

        console.log("Current Day: " + currentDay);

        console.log("Current Hours: " + currentHours);

        for (var i = 0; i < dateObj.length; i++) {

            var daysOnObj = dateObj[i]['days'].split("-");

            if (daysOnObj.includes(currentDay.toString())) {

                var start_seconds = toSeconds(dateObj[i]['start']);

                console.log(start_seconds);

                var end_seconds = toSeconds(dateObj[i]['end']);

                console.log(end_seconds);

                var current_seconds = toSeconds(currentHours);

                console.log(current_seconds);

                if (start_seconds < current_seconds && current_seconds < end_seconds) {

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

    var menu_type = '<?php echo $merchant_detail['menu_type']; ?>';

    if (menu_type == 1)

    {

        var master_filter = '.' + '<?php echo $master_cat; ?>';

        $grid.isotope({
            filter: master_filter
        });

    }

    // filter items on button click

    $('.master_category_filter').on('click', function(e) {
		
		$('html, body').animate({
                    scrollTop: $(".productsDiv").offset().top
		}, 2000);

        e.preventDefault();

        $('.master_category_filter').removeClass("active_menu");

        $(this).addClass("active_menu");

        var filterValue = $(this).attr('data-filter');

        $grid_sub.on('arrangeComplete', function(event, filteredItems) {

            //console.log(event, filteredItems);

            $(filteredItems[0].element).find('button').trigger('click');

           // console.log('am called');

       });
	   
	   // Start: Ajax call data
		var indexNumber = $(this).attr('data-position');
		var firstsubcategory = $(".subcategory_side_"+indexNumber).first().attr("data-subcategory");
		console.log("firstsubcategory"+firstsubcategory);
		
			$(".all-grids").hide();
			$(".show_ajax_loader").show();
			
			var categoryload = 'true';
			var catIndex = indexNumber;
			var merchant_id = $(this).attr("merchant_id");
			var prduct_qty = $(this).attr("prduct_qty");
			var hike_per = '<?php echo $merchant_detail["price_hike"];?>';
			var product_id = '<?php echo $_GET["pd"];?>';
			var one_product_offer ="<?php echo $merchant_detail['one_product_offer'];?>";	
			var cart_merchant_number ="<?php echo $cart_merchant_id;?>";	
			
			$.ajax({
				url: "<?php echo $site_url; ?>/view_merchant_layout2_ajax.php?cats="+categoryload+"&catindex="+catIndex+"&id="+merchant_id+"&prduct_qty="+prduct_qty+"&hike_per="+hike_per+"&firstsubcategory="+firstsubcategory+"&product_id="+product_id+"&one_product_offer="+one_product_offer+"&cart_merchant_number="+cart_merchant_number,
				context: document.body
			}).done(function(response) {
				$(".productmaindiv_"+product_id).hide();
				$('.search_response').find(".productsDiv").html(response);
				$(".show_ajax_loader").hide();
				$(".all-grids").hide();
				$(".prdd_div_"+firstsubcategory).show();
				//$(filterValue).first().trigger('click');
			});
        // END : AJAX Call Data
		

        $grid_sub.isotope({
            filter: filterValue
        });

        var menu_type = '<?php echo $merchant_detail['menu_type']; ?>';



        var filterValue = $(this).attr('data-filter');

        var position_value = $(this).attr('data-position');



        $("#without_table tbody").html("");

        // alert(position_value);

        // alert(menu_type);






    });

    $('.sub_category_grid .category_filter button').on('click', function() {

        var filterValue = $(this).attr('data-filter');

        var subcateg_show = $(this).data("subcategory");

        $('.sub_category_grid .category_filter button').removeClass("active_menu");

        $(this).addClass("active_menu");
		
		$(".all-grids").hide();
		$(".prdd_div_"+subcateg_show).show();

        //console.log(filterValue);

//console.log(subcateg_show);

$("#remarks_area .modal-body .btn-group").each(function() {

    if ($(this).data("subcategory") == subcateg_show || $(this).data("subcategory") == "all") {

        $(this).show();

    } else {

        $(this).hide();

    }

});

$grid.isotope({
    filter: filterValue
});

});
</script>

<style>
@media screen and (max-width: 600px) {
	.wickedpicker{
		left:90px !important;
	}
}
.sub_category_grid button {
    /* You Can Name it what you want*/

    margin-right: 10px;

}

.sub_category_grid button:last-child {

    margin-right: 0px;

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

@media only screen and (max-width: 750px) and (min-width: 600px) {

    form.set_calss.input-has-value {

        < !-- width: 50%;

        -->width: 173px;

    }

    .about_uss {

        width: 165px;

    }

    .sidebar-expand .main-wrapper {

        margin-left: 0px;

    }

}

@media only screen and (max-width: 500px) and (min-width: 300px) {

    #merchant_message {

        margin-top: 20%;

    }

    input.btn.btn-block.btn-primary.submit_button {

        width: 100% !important;

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

        @media only screen and (max-width: 800px) and (min-width: 750px) {

            .sidebar-expand .main-wrapper {

                margin-left: 0px;

            }

            .common_quant {

                display: block;

            }

        }

        .col-md-4 {

            max-width: 100% !important;

        }

        .well.col-md-4 {

            padding: 0 !important;

        }
    </style>

    <?php
    if ($bank_data['custom_msg_time'])

        $c_time = $bank_data['custom_msg_time'];

    else 

        $c_time = 5;

    $same_order = $_SESSION['same_order'];

    $free_trial = $_SESSION['free_trial'];

    $verify_mobile = $_SESSION['verify_mobile'];

    $today_limit = $_SESSION['today_limit'];

    $_SESSION['same_order'] = '';

    $_SESSION['free_trial'] = '';

    $_SESSION['today_limit'] = '';

    if ($merchant_detail['shortcut_icon'])

        $shortcut_icon = $site_url . "/images/shortcut_icon/" . $merchant_detail['shortcut_icon'];

    if ($shortcut_icon == '')

        $shortcut_icon = $site_url.'/img/logo_512x512.png';

    $start_url = $site_url . "/view_merchant.php?sid=" . $_GET['sid'];

// if($merchant_detail['section_exit'] || $merchant_detail[''])

    ?>

    <script>
        $(window).on('load', function() {

        // alert(4);

        var special_coupon_count="<?php echo $special_coupon_count; ?>";
        var product_pre_exit="<?php echo $product_pre_exit; ?>";
        var coupon_code="<?php echo $special_coupon_list['coupon_code']; ?>";
		// alert(special_coupon_count);
		// alert(coupon_code);    
		// console.log('Specific Promo code'+special_coupon_count);
		if(special_coupon_count>0 && product_pre_exit>0) 
		{
			$('#coupon_code').val(coupon_code);
			
			$("#apply_coupon").click();
		}
        $('.lazy').lazy({

            placeholder: "https://koofamilies.com/img/logo.png"
        });
        var section_check = "<?php echo $merchant_detail['section_exit'] ?>";

        var table_check = "<?php echo $merchant_detail['table_exit'] ?>";

        var sst_tax = "<?php echo $merchant_detail['sst_rate'] ?>";

        var delivery_rate = "<?php echo $merchant_detail['delivery_rate'] ?>";
        var koo_cashback_accept = "<?php echo $merchant_detail['koo_cashback_accept'] ?>";
        var koo_cashback_accept_dive_in = "<?php echo $merchant_detail['koo_cashback_accept_dive_in'] ?>";
        var dine_in = "<?php echo $dine_in; ?>";
        var d = new Date();
        var w1 = "<?php echo $language['w1'] ?>";
        var w2 = "<?php echo $language['w2'] ?>";
        var w3 = "<?php echo $language['w3'] ?>";
        var sorry_rest = "<?php echo $language['sorry_rest'] ?>";
        var i_1 = "<?php echo $language['i_1'] ?>";
        var i_2 = "<?php echo $language['i_2'] ?>";
        var i_3 = "<?php echo $language['i_3'] ?>";
        var i_4 = "<?php echo $language['i_4'] ?>";
        var i_5 = "<?php echo $language['i_5'] ?>";
        var sorry_rest=''; 
        var w4 = "";
        var w6 = "<?php echo $language['w6'] ?>";
        var selected_lang = "<?php echo $_SESSION['langfile'] ?>";
        var not_working_text = "<?php echo $merchant_detail['not_working_text']; ?>";
        var not_working_text_chiness = "<?php echo $merchant_detail['not_working_text_chiness']; ?>";
		var not_working_text_malay = "<?php echo $merchant_detail['not_working_text_malay']; ?>";
        var working_text = "<?php echo $merchant_detail['working_text']; ?>";
        var working_text_chiness = "<?php echo $merchant_detail['working_text_chiness']; ?>";
		var working_text_malay = "<?php echo $merchant_detail['working_text_malay']; ?>";
        var csv_import_merchant = "<?php echo $merchant_detail['csv_import']; ?>";
        
		//console.log(not_working_text_malay+"##===##"+working_text_malay);
        if(csv_import_merchant == 1){
            if (selected_lang == "chinese" && not_working_text_chiness != ''){
             console.log("check16110");
			 var not_working_text = +not_working_text_chiness;
			}
			
			if (selected_lang == "malaysian" && not_working_text_malay != ''){
             console.log("check9110");
			 var not_working_text = +not_working_text_malay;
			}
		 
		 if (selected_lang == "chinese" && not_working_text_chiness != ''){
             console.log("check8110");
			 var not_working_text = +not_working_text_chiness;
         }
		 
         if (selected_lang == "english" && not_working_text != ''){
             console.log("check7110");
			 var not_working_text = "Our food delivery hours are from "+not_working_text;
         }


         if (selected_lang == "chinese" && working_text_chiness != '')
         {
             console.log("check6110");
			 var working_text = "我们的外送时间是从 "+working_text_chiness;
         }
		 
		 if (selected_lang == "malaysian" && working_text_malay != '')
         {
             
			 console.log("check110");
			 var working_text = working_text_malay;
         }
		 
         if (selected_lang == "english" && working_text != '')
         {
             console.log("check4110");
			 var working_text = "Our food delivery hours are from "+working_text;
         }
     }else{
       if (selected_lang == "chinese" && not_working_text_chiness != ''){
        console.log("check510");
		var not_working_text = not_working_text_chiness;
    }
	
	if (selected_lang == "malaysian" && not_working_text_malay != ''){
         console.log("check210");
		 var not_working_text = not_working_text_malay;
    }
	
    if (selected_lang == "chinese" && working_text_chiness != '')
    {
        console.log("check410");
		var working_text = working_text_chiness;
    }
	
	if (selected_lang == "malaysian" && working_text_malay != '')
    {
         console.log("check310");
		 var working_text = working_text_malay;
    }

}


var n = d.getDay();
if (n == 0)
    var n = 7;
        // alert(dine_in);
        $('.merchant_select').css("background-color", "red");
        $('.merchant_select').css("color", "#fff");
        totalcart();
        if (dine_in == "y") {
			// alert(koo_cashback_accept_dive_in);
			if(koo_cashback_accept_dive_in==0)
               $('.koo_cashback_accept').hide();
           else
               $('.koo_cashback_accept').show();
           $('.special_price_value').hide();
           $('.sp_main_div').hide();

           $('.plastic_remark').hide();
           $('#delivery_label').hide();
           $("#divein").prop("checked", true);
           $("#takeaway_select").prop("checked", false);
            // $(".divert").trigger("click");
            // $('.divert')[0].click(); // Works too!!!
            $('#transfer_name_label').hide();

            $('.merchant_select').css("background-color", "#fff");

            $('.merchant_select').css("color", "#000");

            $('.divert').css("background-color", "red");

            $('.divert').css("color", "white");



            $('#pickup_type').val('divein');

            $('.name_mer').hide();
            $('.postalbx').hide();

            $("#takeaway_select").prop("checked", false);

            $("#divein").prop("checked", true);

            $('#sec_table_show').show();

            $('#section_show').show();

            $('#table_show').show();

            // totalcart();  

        }
        else
        {
			$('.merchant_select').trigger('click'); //added by me
			if(koo_cashback_accept)
               $('.koo_cashback_accept').show();
           else
               $('.koo_cashback_accept').hide();
       }

       var starttime = $('#stime').val();

       var show_time = $('#show_time').val();

       var end_time = $('#end_time').val();

       var endttime = $('#etime').val();

       var starday = $('#sday').val();

       var starday1 = starday;

       var endday = $('#eday').val();

       endday1 = endday;

       var currenttime = $('#ctime').val();



       if (starday == 'Monday') {

        starday = 1;

    } else if (starday == 'Tuesday') {

        starday = 2;

    } else if (starday == 'Wednesday') {

        starday = 3;

    } else if (starday == 'Thursday') {

        starday = 4;

    } else if (starday == 'Friday') {

        starday = 5;

    } else if (starday == 'Saturday') {

        starday = 6;

    } else {

        starday = 7;

    }

    if (endday == 'monday') {

        endday = 1;

    } else if (endday == 'Tuesday') {

        endday = 2;

    } else if (endday == 'Wednesday') {

        endday = 3;

    } else if (endday == 'Thursday') {

        endday = 4;

    } else if (endday == 'Friday') {

        endday = 5;

    } else if (endday == 'Saturday') {

        endday = 6;

    } else {

        endday = 7;

    }
        // alert('Current Date'+n);
        //  alert('Start Date'+starday);
        //   alert('End Date'+endday);    

        // if(n==0)   
        // var n=7;
        var total_work="<?php echo $total_work; ?>";
	// alert(total_work);
	$('#order_time_shop_on').val(total_work);       
    // alert(total_work);
        // if ((currenttime > starttime && currenttime < endttime) && (starday <= n && endday >= n)) {
            if (total_work=="y") {
				console.log("shop_status@@###");
                const urlParams = new URLSearchParams(window.location.search)
                /*if (urlParams.has("pd")) {
                    let url_product_id = urlParams.get("pd");
                    setTimeout(() => {
                        $(document).find(`.text_add_cart[data-id='${url_product_id}']`).click();
                    }, 100);
                }*/
				var pdTag = "<?php echo $_GET['pd'];?>";
				if(pdTag != ''){	
						var url_product_id = pdTag;
						setTimeout(() => { 
							$(document).find(`.text_add_cart[data-id='${url_product_id}']`).click();
						}, 100);
                }
            } else {


                // alert('Final Case');

                $('#shop_close_time').val('y');

                $s = moment(d + starttime + 'PM').format('LT');

                $e = moment(d + endttime + 'PM').format('LT');

                console.log($s);

                console.log($e);

                // $('.pro_status').hide();

                // $( "#confm" ).prop( "disabled", true );

                // $( ".online_pay" ).prop( "disabled", true );   

                // $( "#confmpayment" ).prop( "disabled", true ); 

                // $("#shop_model_text").html("Shop is Close Now , Our Working Hours are "+starday1+" "+show_time+" to "+endday1+" "+end_time+"")
                // if (not_working_text)
                    // var m = w1 + " " + show_time + " " + w2 + " " + end_time + " (" + not_working_text + "). " + w3 + "</br><span style='color:red;'>" + w4 + "</span>";
                // else
                    // var m = w1 + starday1 + " " + show_time + w2 + endday1 + " " + end_time + "." + w3 + "</br><span style='color:red;'>" + w4 + "</span>";
                // alert(not_working_text);
                if(not_working_text)
                {
                 var m ="<b style='font-size:37px;'>"+sorry_rest+"</b> "+working_text + " (" + not_working_text + "). " + w3 + "</br><span style='color:red;'>" + w4 + "</span>";
                 var m1 ="<b>"+sorry_rest+"</b> "+working_text + " (" + not_working_text + "). " + w3 + "</br><span style='color:red;'>" + w4 + "</span>";
             }
             else
             {
                var m ="<b style='font-size:37px;'>"+sorry_rest+"</b> "+working_text + w3 + "</br><span style='color:red;'>" + w4 + "</span>";
                var m1 ="<b>"+sorry_rest+"</b> "+working_text + w3 + "</br><span style='color:red;'>" + w4 + "</span>";
            }     
                // var m="Our working hours is from "+starday1+" "+show_time+" to "+endday1+" "+end_time+". You can still place order during non-working hours"+"</br><span style='color:red;'>Enjoy 5% rebate if you order before  12am for tomorrow's delivery.</span>";
                $("#shop_model_text").html(m);
                $('#error_label').html(m1);
                console.log("shop_status##"+shop_status);
                $('#shop_model').modal('show');
                // setTimeout(function(){ $("#shop_model").modal("hide"); },5000); 
				console.log("shop_status!!!###");
                $("#shop_model").on("hide.bs.modal", function() {
                    const urlParams = new URLSearchParams(window.location.search)
                    if (urlParams.has("pd")) {
                        let url_product_id = urlParams.get("pd");
                        $(document).find(`.text_add_cart[data-id='${url_product_id}']`).click();
                    }
                });





            }

            var shop_close_time = $('#shop_close_time').val();

        // alert(shop_close_time);





        if (section_check == '1' || table_check == '1')

        {

            $('#sec_table_show').show();

            if (section_check == '1')

            {

                $('#section_show').show();

            } else

            {

                $('#section_type').removeAttr('required');

            }



            if (table_check == '1')

            {

                $('#table_show').show();

            } else

            {

                $('#table_type').removeAttr('required');

            }

        } else

        {

            $('#section_type').removeAttr('required');

            $('#table_type').removeAttr('required');

        }



        $('.merchant_select').click(function(e) {
           console.log('showing');
            // getLocation();
            var special_price_value="<?php echo $merchant_detail['special_price_value'] ?>";
            var koo_cashback_accept="<?php echo $merchant_detail['koo_cashback_accept'] ?>";

            $('.divert').css("background-color", "#fff");

            $('.divert').css("color", "#000");

            $('.plastic_remark').show();

            $(this).css("background-color", "red");

            $(this).css("color", "white");

            $('.name_mer').show();
            $('.postalbx').show();

            $('#delivery_label').show();

            $('#pickup_type').val('takein');

            $("#takeaway_select").prop("checked", true);
            if(special_price_value)
                $('.special_price_value').show();
            $('.sp_main_div').show();

            $("#divein").prop("checked", false);
			// alert(koo_cashback_accept);
			if(koo_cashback_accept==1)   
			{
				$('.koo_cashback_accept').show();
			}
			else
			{
				$('.koo_cashback_accept').hide();
			}
            if (section_check == '1' || table_check == '1')

            {

                $('#sec_table_show').show();

                if (section_check == '1')

                {

                    $('#section_show').show();

                } else

                {

                    $('#section_show').hide();

                }

                if (table_check == '1')

                {

                    $('#table_show').show();

                } else

                {

                    $('#table_show').hide();

                }

            } else

            {

                $('#sec_table_show').hide();

            }

            totalcart();

        });

        $("#special_delivery").click(function(e) {
            var s_amount = "<?php echo number_format($merchant_detail['special_price_value'], 2); ?>";
            if ($('#special_delivery').prop('checked')) {
                // alert('Checkeed');
                $('#special_delivery_amount').val(s_amount);
                $('.special_price_value').css("background-color", "red");
                $('.special_price_value').css("color", "white");
            } else {
                $('.special_price_value').css("background-color", "#51D2B7");
                $('.special_price_value').css("color", "#555");
                // alert('no Checkeed');
                $('#special_delivery_amount').val(0);
            }
            totalcart();
        });
        $("#speed_delivery").click(function(e) { 
		  // alert(3);  
        var s_amount = "<?php echo number_format($merchant_detail['speed_delivery'], 2); ?>";

        if ($('#speed_delivery').prop('checked')) {
                // alert('Checkeed');
                $('#speed_delivery_amount').val(s_amount);
                $('.speed_price_value').css("background-color", "red");
                $('.speed_price_value').css("color", "white");
            } else {
                $('.speed_price_value').css("background-color", "#51D2B7");
                $('.speed_price_value').css("color", "#555");
                // alert('no Checkeed');
                $('#speed_delivery_amount').val(0);
            }
            totalcart();
        });

        $("#donation_amount").click(function(e) { 
		  // alert(3);  
        var s_amount = "5.00";

        if ($('#donation_amount').prop('checked')) {
                // alert('Checkeed');
                $('#donation_amount_value').val(s_amount);
                $('.donation_button').css("background-color", "red");
                $('.donation_button').css("color", "white");
            } else {
                $('.donation_button').css("background-color", "#51D2B7");
                $('.donation_button').css("color", "#555");
                // alert('no Checkeed');
                $('#donation_amount_value').val(0);
            }
            totalcart();
        });


        $(".divert").click(function(e) {
            // alert(3);
            console.log('dive___in');
            var koo_cashback_accept_dive_in="<?php echo $merchant_detail['koo_cashback_accept_dive_in'] ?>";
            $('.special_price_value').hide();
            $('.sp_main_div').hide();
			//speed_delivery
			if ($('#speed_delivery').prop('checked')) {
				$('#speed_delivery').prop('checked', false); // Unchecks it
				$('.speed_price_value').css("background-color", "#51D2B7");
                $('.speed_price_value').css("color", "#555");
                $('#speed_delivery_amount').val(0);
            }

            if ($('#donation_amount').prop('checked')) {
                // alert('Checkeed');
				$('#donation_amount').prop('checked', false); // Unchecks it
                $('#donation_amount_value').val(0);
                $('.donation_button').css("background-color", "#51D2B7");
                $('.donation_button').css("color", "#555");
            } 


            $('#pickup_type').val('divein');
            totalcart();
            $('#transfer_name_label').hide();
            $('.plastic_remark').hide();

            $('.merchant_select').css("background-color", "#fff");

            $('.merchant_select').css("color", "#000");

            $(this).css("background-color", "red");

            $(this).css("color", "white");

            $('#delivery_label').hide();
			// alert(koo_cashback_accept_dive_in);
			if(koo_cashback_accept_dive_in==1)
			{
				$('.koo_cashback_accept').show();
			}
			else
			{
				$('.koo_cashback_accept').hide();
			}

            $('.name_mer').hide();
            $('.postalbx').hide();

            $("#takeaway_select").prop("checked", false);

            $("#divein").prop("checked", true);

            $('#sec_table_show').show();

            $('#section_show').show();

            $('#table_show').show();



        });

        var custom_msg = "<?php echo $custom_msg; ?>";

        var same_order = "<?php echo $same_order; ?>";

        var free_trial = "<?php echo $free_trial; ?>";

        var today_limit = "<?php echo $today_limit; ?>";

        var verify_mobile = "<?php echo $verify_mobile; ?>";

        var mobile_otp_verify = "<?php echo $mobile_otp_verify; ?>";

        // alert(today_limit);
        <?php 
        $m_name = str_replace("'", ' ', $merchant_detail['name']);
        $m_name=clean($m_name);
        ?>

        var myDynamicManifest = {

            "gcm_sender_id": "540868316921",

            "icons": [

            {

                "src": "<?php echo $shortcut_icon; ?>",

                "type": "image/png",

                "sizes": "512x512"

            }

            ],

            "short_name": '<?php echo $m_name; ?>',

            "name": "One stop centre for your everything",

            "background_color": "#4A90E2",

            "theme_color": "#4A90E2",

            "orientation": "any",

            "display": "standalone",

            "start_url": "<?php echo $start_url; ?>"

        }
        const stringManifest = JSON.stringify(myDynamicManifest);

        const blob = new Blob([stringManifest], {
            type: 'application/json'
        });

        const manifestURL = URL.createObjectURL(blob);

        document.querySelector('#my-manifest-placeholder').setAttribute('href', manifestURL);





        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw_new.js')
            .then(function(registration) {
                    //console.log('Registration successful, scope is:', registration.scope);
                })
            .catch(function(error) {
                console.log('Service worker registration failed, error:', error);
            });
        }


        if (mobile_otp_verify == "y")

        {

            var msg = "Your Mobile no is Verified,Now Place order";

            $('#show_msg').html(msg);

            $('#AlerModel').modal('show');

            setTimeout(function() {
                $("#AlerModel").modal("hide");
            }, 5000);

        }

        if (today_limit == "expire")

        {


            var msg = "In a day you can only place 3 order,Try to order With different merchant";

            $('#show_msg').html(msg);

            $('#AlerModel').modal('show');

            setTimeout(function() {
                $("#AlerModel").modal("hide");
            }, 5000);

        }

        if (free_trial == "expire")

        {

            $('#verifiedmobile').val(verify_mobile);

            $('#free_trial_model').modal('show');

            // setTimeout(function(){ $("#free_trial_model").modal("hide"); },5000);

        }

        if (same_order == "y")

        {

            $("#show_new_label").modal("show");

            setTimeout(function() {
                $("#show_new_label").modal("hide");
            }, 5000);

        }

        if (custom_msg == "y")

        {

            $("#merchant_message").delay(parseInt(<?php echo $c_time; ?>) * 1000).queue(function(nxt) {

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

        var already_login = '<?php echo $login_user_id; ?>';

        var p_status = '<?php echo $p_status; ?>';

        // var delivery_charges="<?php echo $merchant_detail['order_extra_charge']; ?>";

        var delivery_charges = $('#delivery_charges').val();

        // alert(delivery_charge);  

        var free_delivery = "<?php echo $merchant_detail['free_delivery']; ?>";

        var location_order = '<?php echo $location_order; ?>';

        var shop_status = $('#shop_status').val();



        $(".heart.fa").click(function() {

            if (already_login)

            {

                // if(already_login)

                var className = $(this).attr('class');

                var favorite = "0";

                if (className.indexOf("fa-heart-o") > -1) {

                    favorite = "1";

                }

                var data = {
                    method: "favorite",
                    favorite: favorite,
                    merchant_id: '<?php echo $merchant_detail['id']; ?>',
                    user_id: already_login
                };

                $.ajax({

                    url: "<?php echo $site_url; ?>/functions.php",

                    type: "post",

                    data: data,

                    success: function(data) {

                        console.log(data);



                    }

                });

                $(this).toggleClass("fa-heart fa-heart-o");

            } else

            {

                $('#show_msg').html("Login as member to use that feature");

                $('#AlerModel').modal('show');

                // alert('Login as member to use that feature');

            }

        });



        if (p_status == "paypalcancel")

        {

            $('#paypal_model').modal('show');

        }

        if (location_order == 1)

        {

            var shop_close_time = $('#shop_close_time').val();

            if (shop_close_time == "n")

            {

                // $('.pro_status').hide();

                // $( "#confm" ).prop( "disabled", true );

                // $( ".online_pay" ).prop( "disabled", true );

                // $( "#confmpayment" ).prop( "disabled", true );   

            }

        }

        // alert(shop_status);

        if (shop_status == 0)

        {
           console.log("shop_status"+shop_status);

            // window.location.replace("https://www.koofamilies.com/favorite.php");

            // $('.pro_status').hide();

            // $( "#confm" ).prop( "disabled", true );

            // $( ".online_pay" ).prop( "disabled", true );

            // $( "#confmpayment" ).prop( "disabled", true ); 

            // $('#error_label').html("Sorry, we are currently experiencing some internet connection issue. If you want to place any order, please contact our waiter for placing order.");
            $('#error_label').html("We are temporary closed due to excessive orders or other reasons. Please visit us later or chat with us through whatapp for lastest update.  Sorry for inconvenience caused.");

            $('#shop_model').modal('show');

            // exit;

        }



        $("button.introduce-remarks").prop("disabled", false);



        var prev_number = '';

        $(".credentials-container input[name='mobile_number']").on("keyup", function() {

            // alert(3);

            var prefix = "+60";

            var number = $(this).val();

            // var already_login='<?php echo $login_user_id; ?>';

            var already_login = $('#login_user_id').val();

            // alert(already_login);

            // console.log("Previous number: " + prev_number);

            // console.log("Current number: " + (prefix + number));

            if (prev_number == prefix + number) {

                return false;

            }

            prev_number = prefix + number;

            var guest_permission = <?php echo $merchant_detail['guest_permission']; ?>;

            // alert(guest_permission);

            var guest_permission = 0;

            var login_user_id = $('#login_user_id').val();



            if (guest_permission == 0)

            {

                // alert(number[0]);

                if (number.length >= 9 && number.length <= 12 && (number[0] == 1 || number[0] == 6 || number[0] == 0)) {

                    // alert(login_user_id);
                    if (number[0] == 0) {
                        number = number.slice(1);
                    }
                    if (!already_login && !login_user_id)

                    {

                        var mobile_number = $('#mobile_number').val();
                        if (mobile_number[0] == 0) {
                            mobile_number = mobile_number.slice(1);
                        }
                        var merchant_id = '<?php echo $id; ?>';

                        var special_coin_name = '<?php echo $special_coin_name; ?>';

                        if (mobile_number)

                        {

                            // alert(mobile_number);

                            $.ajax({

                                url: '<?php echo $site_url; ?>/functions.php',

                                type: 'POST',

                                dataType: 'json',

                                data: {
                                    mobile_number: mobile_number,
                                    method: "usercheck",
                                    merchant_id: merchant_id,
                                    special_coin_name: special_coin_name
                                },

                                success: function(res) {

                                    var data = JSON.parse(JSON.stringify(res));
                                    // alert(JSON.stringify(data));

                                    console.log(data.status);
                                    $(".send_otp_button").hide();
                                    if (data.status == true)

                                    {

                                      console.log("user_in");


                                      if (plan_label != '')

                                      {

                                        var plan_benefit = data.plan_benefit;

                                        var plan_label = data.plan_label;

                                        $('#membership_discount_input').val(plan_benefit);

                                        $('#membership_applicable').val('y');

                                        $('#membership_discount').show();

                                        $('#membership_discount').html(plan_label);

                                        $('#membership_discount_label').show();

                                        $('#membership_discount_label').html(plan_label);

                                    }



										$(".login_passwd_field").hide(); //otp
                                        $('#newmodel_check').modal('show');
                                        $(".popup_info_user").show();
                                        $(".popup_info_user1").html(data.data.name);
                                        $(".popup_info_user2").html(data.data.mobile_number);
                                        $(".koo_check_wallet").val(data.data.koocash_wallet);
                                        $(".koo_check_wallet").html(data.data.koocash_wallet);
                                        $("#koo_balance").val(data.data.koocash_wallet);



                                        // alert(plan_benefit);

                                        $('#membership_discount_input').val(plan_benefit);

                                        var membership_discount_input = $('#membership_discount_input').val();

                                        $('.forgot-form').hide();




                                        var login_msg="<?php echo $language['login_msg'] ?>";
										var login_rebat_msg="<?php echo $language['login_rebat_msg'] ?>";
                                        var login_password="<?php echo $language['login_password'] ?>";
                                        if (data.userstatus == "old")

                                        {

                                            var record = data.data;

                                            $('#login_user_id').val(record.id);

                                            $('#myr_bal').html(record.balance_myr);

                                            $('#usd_bal').html(record.balance_usd);

                                            $('#inr_bal').html(record.balance_inr);

                                            $('#special_bal').html(record.balance_special);

                                            $('#myr_input_bal').val(record.balance_myr);

                                            $('#usd_input_bal').val(record.balance_usd);

                                            $('#inr_input_bal').val(record.balance_inr);

                                            $('#special_input_bal').val(record.balance_special);

                                            if (record.otp_verified == "n")

                                            {



                                                $('#show_msg_new').html('<span style="font-size:20px;">😀</span>Please Verfiy your otp to claim for your ' + membership_discount_input + ' membership discount');

                                                // $('#show_msg').html('Verify your otp to get membership discount of ');   

                                                $('.otp_form').show();

                                                var otp_count = $("#otp_count").val();

                                                var usermobile = record.mobile_number;

                                                var data = {
                                                    usermobile: usermobile,
                                                    method: "sendotp",
                                                    merchant_id: merchant_id
                                                };

                                                $.ajax({

                                                    url: '<?php echo $site_url; ?>/functions.php',

                                                    type: 'POST',

                                                    dataType: 'json',

                                                    data: data,

                                                    success: function(response) {

                                                        var data = JSON.parse(JSON.stringify(response));

                                                        if (data.status == true)

                                                        {

                                                            otp_count++;

                                                            // alert(data.otp);

                                                            $("#otp_count").val(otp_count);

                                                            $("#system_otp").val(data.otp);

                                                            $(".otp_form").show();

                                                        }



                                                    }

                                                });

                                            } else

                                            {

                                                if (membership_discount_input){

                                                    $('#show_msg_new').html('<span style="font-size:20px;">😀</span> Please login in order to claim ' + membership_discount_input + ' membership discount');
                                                }else if(data.data.koocash_wallet > 0){	
                                                 $('#show_msg_new').html('Rm '+ data.data.koocash_wallet +'<span style="color:black"> is inside your wallet. '+ login_rebat_msg+' </span>');
                                             }
                                             else{
                                                $('#show_msg_new').html('<span style="font-size:20px;">😀</span>'+login_msg);
                                            }
                                                //$('.login_passwd_field').show();
												//$('.login_ajax_new').removeClass("btn-default").addClass("btn-primary");
												//$(".lg_otp_error").hide();
												console.log('finally');

                                                $('.join_now').show();
												$('.login_passwd_field').hide();//otp

                                                //$('.login_ajax_new').show();

                                                $('.login_footer').show();



                                            }

                                            // alert(record.otp_verified);   

                                        }
                                        totalcart();

                                    }else if (data.status == 'new')
                                    {
										//open OTP Popup
                                       console.log('user_else');
                                       $(".send_otp_button").show();
                                       $(".send_otp_button").attr('user_id',data.data.id);
                                       $(".send_otp_button").attr('user_mobile_number',data.data.mobile_number);
											//$("#myModal").modal('show');
											$("#login_otp").val(data.otp);
											//$('#otp_label').show();
											//$('#otp_label').css('color', 'rgb(81, 210, 183)');
											//$('#otp_label').html('Otp sent on mobile number');
											//$('.resend_label').show();
											//$('.verfiy_otp_form').show();
											
                                     }



                                 }

                             });

}

                        // check mobile no for membership feature    

                        // alert('mobile fill');

                    }

                    $('#mobile_error').hide();

                    $("input.submit_button").prop("disabled", false);

                    $("input.submit_payment_button").prop("disabled", false);

                    $("input[name='mobile_number']").addClass("is-valid").removeClass("is-invalid");



                } else {

                    $("input.submit_button").prop("disabled", true);

                    $("input.submit_payment_button").prop("disabled", true);

                    $("input[name='mobile_number']").removeClass("is-valid").addClass("is-invalid");
                    $('#mobile_number').focus();
                    $('#mobile_error').show();



                }

            }

        });

$(".send_otp_button").click(function(){
   var user_id = $(this).attr('user_id');
   var user_mobile_number = $(this).attr('user_mobile_number');

   $.ajax({
     url: '<?php echo $site_url; ?>/functions.php',
     type: 'POST',
     dataType: 'json',
     data: {
      mobile_number: user_mobile_number,
      method: "sendotpbutton",
      user_id: user_id,
  },
  success: function(res) {
      var data = JSON.parse(JSON.stringify(res));
      console.log('user_else');
      $(".send_otp_button").show();
      $("#myModal").modal('show');
      $("#login_otp").val(data.otp);
      $('#otp_label').show();
						//$('#otp_label').css('color', 'rgb(81, 210, 183)');
						$('#otp_label').html(data.message);
						$('.resend_label').show();
						$('.verfiy_otp_form').show();
					}
                });

});
        $('#partitioned').on('keyup', function() { // consider `myInput` is class...



            var user_input = $(this).val();
            var number = $('#mobile_number').val();
            // alert(user_input);

            var page_otp = $("#system_otp").val();


            if (number.length >= 9 && number.length <= 12 && (number[0] == 1 || number[0] == 6 || number[0] == 0)) {

                if (number[0] == 0) {
                    number = number.slice(1);
                }
                var usermobile = "60" + number;
                $('#mobile_error').hide();
                if (user_input.length % 4 == 0) {

                    if (user_input == page_otp)

                    {

                        var data = {
                            usermobile: usermobile,
                            method: "otp_submit"
                        };



                        $.ajax({



                            url: '<?php echo $site_url; ?>/login.php',

                            type: 'POST',

                            dataType: 'json',

                            data: data,

                            success: function(response) {

                                if (response == 1)

                                {

                                    // location.reload();

                                    var login_user_id = $('#login_user_id').val();

                                    $('#login_for_wallet_id').val(login_user_id);

                                    $('#newmodel_check').modal('hide');

                                    $('#PasswordModel').modal('show');

                                } else

                                {

                                    $('#register_error').html('Something Went wrong to validate otp,try again');

                                    $("#login_error").show();

                                }



                            }

                        });



                    } else

                    {

                        $('.otp_error').show();

                    }



                }
            } else {
                $('#mobile_number').focus();
                $('#mobile_error').show();
                var s_flag = false;
            }



        });
        $('#forgot_partitioned').on('keyup', function() { // consider `myInput` is class...

            var user_input = $(this).val();
            // alert(user_input);
            var page_otp = $("#system_otp").val();
            var usermobile = "60" + $("#mobile_number").val();
            // alert(user_input);
            // alert(page_otp);
            if (user_input.length % 4 == 0) {
                if (user_input == page_otp) {
                    $('.forgot_otp_error').hide();
                    $('#forgot_msg_new').html('<span style="font-size:20px;">😀</span>Please create your password');
                    $('.forgot_otp_form').hide();
                    $('.forgot_password_submit').show();
                    $('.forgot_reset_password').show();
                    $('.forgot_register_password').show();

                } else {
                    $('.forgot_otp_error').show();
                }

            }
        });
        $('.forgot_reset_password').click(function() {
            var forgot_register = $('#forgot_register').val();
            // alert(forgot_register);
            if ((forgot_register.length) > 5) {
                var mobile = $("#mobile_number").val();
                if (mobile[0] == 0) {
                    mobile = mobile.slice(1);
                }
                var newpassword = $('#forgot_register').val();
                var usermobile = "60" + mobile;
                if ((newpassword.length) > 5) {
                    var data = {
                        usermobile: usermobile,
                        method: "forgotresetpassword",
                        password: newpassword
                    };
                    $.ajax({

                        url: '<?php echo $site_url; ?>/functions.php',
                        type: 'POST',
                        dataType: 'json',
                        data: data,
                        success: function(response) {
                            var data = JSON.parse(JSON.stringify(response));
                            if (data.status) {
                                $("#forgot_reset_password_error").hide();
                                var login_user_id = $('#login_user_id').val();
                                var login_user_role = data.data.user_roles;
                                localStorage.setItem('login_live_id', login_user_id);
                                localStorage.setItem('login_live_role_id', login_user_role);
                                $('#login_for_wallet_id').val(login_user_id);
                                $('#forgot_setup').modal('hide');

                                $('#show_msg').html(data.msg);
                                $('#AlerModel').modal('show');
                                setTimeout(function() {
                                    $("#AlerModel").modal("hide");
                                }, 2000);

                            } else {
                                $("#forgot_reset_password_error").html('Failed to Reset Password');
                                $("#forgot_reset_password_error").show();
                            }

                        }
                    });
                } else {

                    $("#forgot_register_error").html('Enter Atleaset 6 digit password');

                    $("#forgot_register_error").show();
                }

            } else {
                $("#forgot_reset_password_error").html('Your New Password has to be 6 digit long');
                $("#forgot_reset_password_error").show();
            }
        });

        // $('#confm').click(function(){

        // $('#order_place').attr('action', 'order_cash.php');







        // });

        $('#confmpayment').click(function() {

            $('#order_place').attr('action', 'order_place.php');



        });

        $("body").on("click", "#login_passwd", function(e) {

            var phone_num = $(this).parent().parent().find("input[name='mobile_number']").val();

            $("#login_phone_number").val("+60" + phone_num);

            e.preventDefault();

        });

        var latitude = 0;

        var longitude = 0;
        var merchant_id = "<?php echo $merchant_detail['id']; ?>";
        var location_order = "<?php echo $merchant_detail['location_order']; ?>";
        if (location_order == 1) {
            navigator.geolocation.getCurrentPosition(function(position) {

                $('#location_model').modal('hide');

                console.log("current location");

                latitude = position.coords.latitude;

                longitude = position.coords.longitude;

                // latitude=0;

                codeLatLng(latitude, longitude);

                if (latitude != '' && longitude != '')

                {

                    $('#location_model').modal('hide');

                    $('#user_lat').val(latitude);

                    $('#user_long').val(longitude);

                    if (location_order == 1)

                    {

                        calculatedisatace(latitude, longitude);

                    }

                    // $( "#confm" ).prop( "disabled", true );

                    // alert(latitude);

                } else

                {

                    // $("#error_label").html("Sorry, To Place order Location Permission is needed ");

                    // $('#location_model').modal('show');



                }

                // getFavorite("Foods and Beverage, such as restaurants, healthy foods, franchise, etc", $(".business_type").attr('user_id'));

                //getNearbyRestaurant("Foods and Beverage, such as restaurants, healthy foods, franchise, etc", $(".business_type").attr('user_id'));

            });
        }




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
                if (latitude != '' && longitude != '')

                {

                    if (location_order == '1')
                    {
                       if(window.location.href.includes('orderview.php')) return false;
                       $('.page_loader').removeAttr('style');
                       $("#load").removeAttr('style');;
                       $('.page_loader').show();
                       $("#load").show();
                       setTimeout(function () {
                           $('.page_loader').hide();
                           $("#load").hide();
                       }, 3000);
                       calculatedisatace(latitude, longitude);
                   }

               }

           });

        });



        $("button[name='login_ajax']").on("click", function(e) {

            var phone_num = $("#login_phone_number").val();

            var passwd = $("#login_password").val();

            var passwd_length_valid = (passwd.length > 2) ? true : false;

            //$(this).prop("disabled", true);
            console.log('aaa');
            if (passwd_length_valid) {

                $.post("<?php echo $site_url; ?>/login.php", {

                    mobile_phone: phone_num,

                    password: passwd

                }, function(data, result) {



                    console.log("Data:");

                    console.log(data);

                    var status_code = (data == "logged-in") ? 4 : (data == "acc-locked") ? 3 : (data == "reg_pending") ? 2 : (data == 1) ? 1 : 0;

                    console.log(status_code);

                    if (status_code == 0) {

                        $("#login_password").rFemoveClass("is-valid").addClass("is-invalid");

                        $(".third_problem.logged-in.success_login,.reg_pending,.acc_blocked").hide();

                        $(".wrong_login").show();

                    } else if (status_code == 1) {

                        $("#login_password").removeClass("is-invalid").addClass("is-valid");

                        $(".third_problem.logged-in.wrong_login,.reg_pending,.acc_blocked").hide();

                        $(".success_login").show();

                        $(".credentials-container").remove();

                        $("form[action='<?php echo $site_url; ?>/guest_user.php']").attr("action", "<?php echo $site_url; ?>/order_place.php");

                    } else if (status_code == 2) {

                        $("#login_password").removeClass("is-valid").addClass("is-invalid");

                        $(".third_problem.logged-in.success_login,.acc_blocked,.wrong_login").hide();

                        $(".reg_pending").show();

                    } else if (status_code == 3) {

                        $("#login_password").removeClass("is-valid").addClass("is-invalid");

                        $(".third_problem.logged-in.success_login,.reg_pending,.wrong_login").hide();

                        $(".acc_blocked").show();

                    } else if (status_code == 4) {

                        $("#login_password").removeClass("is-valid").addClass("is-invalid");

                        $(".third_problem.acc_blocked.success_login,.reg_pending,.wrong_login").hide();

                        $(".logged-in").show();

                    } else {

                        $("#login_password").removeClass("is-valid").addClass("is-invalid");

                        $(".logged-in.acc_blocked.success_login,.reg_pending,.wrong_login").hide();

                        $(".third_problem").show();

                    }

                })

            } else {

                if (passwd.length == 0) {

                    alert("You have to enter a password");

                } else {

                    alert("The password is too short");

                }

            }

            e.preventDefault();

        })

        //$('.master_category_filter:first-child').trigger('click');

        $('.sub_category_grid .category_filter:first-child button').click();

        //$('.filter-button-group .category_filter:first-child').trigger('click');



        imagesLoaded(document.querySelector('.grid'), function(instance) {

            setTimeout(() => {

                $('.sub_category_grid .category_filter:first-child button').click();

            }, 50);

        });



        var menu_type = '<?php echo $merchant_detail['menu_type']; ?>';

        if (menu_type == 2) {

            $('.master_category_filter:first-child').trigger('click');

        }

        $(".category_filter").click(function(e) {

            var menu_type = '<?php echo $merchant_detail['menu_type']; ?>';



            var filterValue = $(this).attr('data-filter');



            $("#without_table tbody").html("");





            if (menu_type == 1)

            {

                var data = {
                    method: "getImageProduct",
                    id: <?php echo $id; ?>,
                    category: ''
                };

                $(".new_grid").html("");

                $.ajax({

                    url: "<?php echo $site_url; ?>/functions.php",

                    type: "post",

                    data: data,

                    dataType: 'json',

                    success: function(result) {

                        console.log(result);

                        var html = "";



                        for (var i = 0; i < result.length; i++) {

                            html += "<div class='well col-md-4 element-item Cham鸳鸯'>";

                            html += " <form action='<?php echo $site_url; ?>/product_view.php' method='post' class='set_calss input-has-value' data-id='" + result[i]['id'] + "' data-code='C005' data-pr='39' style='background: #51d2b7;    padding: 12px;    border: 1px solid #e3e3e3;    border-radius: 4px;    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05); box-shadow: inset 0 1px 1px rgba(0,0,0,.05);'>";

                            if (isActive(result[i]['active_time'])) {

                                if (result[i]['on_stock'] == 1) {

                                    html += " <div class='container_test'>";

                                } else {

                                    html += " <div class='container_test out_of_stock'>";

                                }

                            } else {

                                html += " <div class='container_test not_available'>";

                            }

                            html += "<img src='<?php echo $site_url; ?>/images/product_images/" + result[i]['image'] + "' class='make_bigger' width='100%' height='150px'>";



                            html += "</div>";

                            html += "<input type='hidden' id='id' name='m_id' value='" + result[i]['user_id'] + "'>";

                            html += "<input type='hidden' id='id' name='p_id' value='" + result[i]['id'] + "'>";

                            html += "<p class='pro_name'>" + result[i]['product_name'] + "</p>";

                            html += "<p class='mBt10'></p>";

                            html += "<p class='mBt10'></p>Price : Rm" + result[i]['price'] + "<p></p>";

                            html += "<div class='common_quant'>";

							var add_to_cart_lang = '<?php echo $language["add_to_cart1"];?>';
		
                            if (isActive(result[i]['active_time'])) {

                                if (result[i]['on_stock'] == 1) {

                                    html += "<p class='text_add_cart' data-id='" + result[i]['id'] + "' data-code='" + result[i]['type'] + "' data-pr='" + result[i]['price'] + "' data-name='" + result[i]['product_name'] + "'>"+add_to_cart_lang+"</p>";

                                    html += "<div style='display:grid;grid-template-columns:.2fr 1fr;align-content:center;vertical-align:center;' class='input-has-value'>";

                                    html += " <label>X</label><input type='number' value='1' class='quatity' name='quatity' style='height:1.5em'>";

                                } else {

                                    html += "<p class='no_stock_add_to_cart'>Out of stock</p>";

                                }

                            } else {

                                html += "<p class='no_stock_add_to_cart'>This product is not available in this moment</p>";

                            }



                            html += "<p class='quantity'> </p>";

                            html += "</div>";

                            html += "</div>";

                            html += "</form>";

                            html += "</div>";

                        }

                        $(".new_grid").html(html);

                        $(".text_add_cart").on("click", function() {

                            var id = $(this).data("id");

                            var code = $(this).data("code");

                            var p_price = $(this).data("pr");

                            var name = $(this).data("name");

                            var quantity = $(this).closest("form").find("input[name='quatity']").val();

                            var p_extra = $(this).parent().parent().find("input[name='extra']").val();

                            var p_total = p_price * quantity;

                            p_total = p_total.toFixed(2);

                            var remark_lable = '<?php echo $language["remarks"] ?>';

                            $("#test").append("<tr>  <td><button type='button' class='removebutton'>X</button> </td><td>" + name + "</td><td><input style='width:50px;' mychk  onchange='UpdateTotal(" + id + " ," + p_price + ")'  type=number name='qty[]'  min='1' class='mw2 product_qty' maxlength='3'  value=" + quantity + " id='" + id + "_test_athy'><input type= hidden name='p_id[]' value= " + id + "><input type= hidden name='p_code[]' value= " + code + "><input type='hidden' name='ingredients'/></td><td>" + code + "</td><td><a href='#remarks_area' role='button' class='introduce-remarks btn btn-large btn-primary hideLoader' data-toggle='modal'>"+remark_lable+"</a><input type='hidden' name='extra' value='" + p_extra + "'></td><td><input style='width:70px;text-align:right;' type='text' name='p_extra' value='0' readonly></td>  <td><input style='width:70px;' type='text' name='p_price[]' value= " + p_price + " readonly></td><td><input type='text' style='width:70px;' name='p_total[]' value= " + p_total + " readonly  id='" + id + "_cat_total'></td> </tr>");

                            alert('The product added');

                        });



                    }

                });

}



});
$("#no_button").click(function(e) {
    var s_token = generatetokenno(6);
    var r_url = "https://www.koofamilies.com/index.php?vs=" + s_token;
    window.location.replace(r_url);
});
$("#yes_button").click(function(e) {
    $('#shop_model').modal('hide');
    var selected_type = $(this).attr('selected_type');

            // alert(selected_type);
            if (selected_type == "cash") {
                $('#shop_close_time').val('n');
                $("#confm").click();

            } else if (selected_type == "wallet") {
                $('#shop_close_time').val('n');
                $(".online_pay").click();

            } else if (selected_type == "internet") {
                $('#shop_close_time').val('n');
                $('.internet_banking').click();

            }
            return false;
        });
$("#confm").click(function(e) {
    var product_qty = $('.product_qty').val();
    var table_type = $('#table_type').val();
    var number = $('#mobile_number').val();
    var pickup_type = $('#pickup_type').val();
            // alert(pickup_type);
            var shop_close_time = $('#shop_close_time').val();
            var total_rebate = 0;
            var total_amount = 0;
            var s_flag = true;
            if ((product_qty == null) || (product_qty == '')) {
                $('#show_msg').html("<?php echo $language['no_product_added']; ?>");
                $('#AlerModel').modal('show');
                var s_flag = false;
                return false;
            }
            if (number.length >= 9 && number.length <= 12 && (number[0] == 1 || number[0] == 6 || number[0] == 0)) {
                $('#mobile_error').hide();
                if (number[0] == 0) {
                    number = number.slice(1);
                }
            } else {
                $('#mobile_number').focus();
                $('#mobile_error').show();
                var s_flag = false;
            }
            if ($('#table_type').prop('required')) {
                if (table_type == '') {
                    $('#table_type').focus();
                    var s_flag = false;
                }

            }
            if (pickup_type == "takein") {
                var mapSearch = $('#mapSearch').val();

                if (mapSearch == '') {
                    $('#location_error').show();
                    $('#mapSearch').focus();
                    var s_flag = false;
                } else {
                    $('#location_error').hide();
                }
            } else {
                $('#location_error').hide();
            }
            // alert(s_flag);
            // return false;
            if (s_flag)

            {
                totalcart();
                // alert(3);

                $(".rebate_amount").each(function() {

                    // var total_rebate+= $(this).val();

                    total_rebate += parseFloat($(this).val());

                });

                $(".p_total").each(function() {

                    // var total_rebate+= $(this).val();

                    total_amount += parseFloat($(this).val());

                });

                var delivery_charges = $('#delivery_charges').val();

                // alert('Deliver charge '+delivery_charges);

                //if (delivery_charges > 0)

                var order_min_charge = "<?php echo $merchant_detail['order_min_charge']; ?>";

                //else

                  //  var order_min_charge = 0;



                // alert('Order Min '+order_min_charge);

                // alert(order_min_charge);

                if (order_min_charge > 0)

                {



                    if (parseFloat(total_amount) < parseFloat(order_min_charge))

                    {

                        // var msg="Place Min Order for Rm "+order_min_charge;

                        var msg = "Minimum order is Rm " + order_min_charge;

                        $('#show_msg').html(msg);

                        $('#AlerModel').modal('show');

                        return false;

                        // alert('Order Amount greater');

                    }

                }



                var total_amount = total_amount.toFixed(2);



                var total_rebate = total_rebate.toFixed(2);

                $('#total_rebate_amount').val(total_rebate);

                $('#total_cart_amount').val(total_amount);

                $('#total_cart_amount_label').html(total_amount);
                



            } else {
                return false;
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

        $(".login_ajax").click(function() {
           console.log('ajaaxxx');
            // $(this).removeClass(" btn-primary").addClass("btn-default");

            // setTimeout(function() {



            // $(this).removeClass("btn-default").addClass("btn-primary");

            // }.bind(this), 5000);
            var mobile = $("#mobile_number").val();
            if (mobile[0] == 0) {
                mobile = mobile.slice(1);
            }
            var usermobile = "60" + mobile;

            var login_password = $("#login_ajax_password").val();

            var wallet_selected = $("#wallet_selected").val();



            $("#login_error").hide();

            if ((login_password.length) > 5)

            {

              //  $(this).removeClass(" btn-primary").addClass("btn-default");

                // alert(login_password);

                var data = {
                    usermobile: usermobile,
                    login_password: login_password
                };

                // alert(data);

                $.ajax({



                    url: '<?php echo $site_url; ?>/login.php',

                    type: 'POST',

                    dataType: 'json',

                    data: data,

                    success: function(response) {

                        var data = JSON.parse(JSON.stringify(response));

                        if (data.status == true)

                        {

                            var login_user_role = data.data.user_roles;

                            var login_user_id = data.data.id;

                            localStorage.setItem('login_live_id', login_user_id);

                            localStorage.setItem('login_live_role_id', login_user_role);

                            $('#login_process').hide();

                            $('#with_wallet').hide();

                            $('#without_wallet').hide();

                            $('.join_now').hide();

                            walletstep();

                        } else

                        {

                            $("#login_ajax_password").val('');

                            $("#login_error").html('Your password is incorrect.Please try with correct password again or Reset that with Forgot Password');

                            $("#login_error").show();

                        }



                    }

                });



            } else

            {
                $("#login_error").html('Enter Atleaset 6 digit password to make login');

                $("#login_error").show();

            }



        });


        $(".show_pwd_box").click(function() {
           $(".lg_otp_error").hide();	
           if($(".login_main_div").is(":visible")){

           }else{
				$(".login_passwd_field").show();//otp
				$("#myBtnLogWithOTP").attr('style','width: 98%;background: border-box; color: grey;');
				$(".forgot_pass").show();
				$(".login_ajax_new").show();
				$(".show_pwd_box").hide();
				
				return false;
			}
			
		});
        $(".login_ajax_new").click(function() {



           console.log('newww');
           $(".lg_otp_error").hide();	

           var mobile = $("#mobile_number").val();
           if (mobile[0] == 0) {
            mobile = mobile.slice(1);
        }
        var usermobile = "60" + mobile;


        var login_password = $("#login_ajax_password_new").val();

        var membership_applicable = '<?php echo $membership_applicable; ?>';

        var last_order_merchant_id = '<?php echo $last_order_merchant_id; ?>';

        var last_order_id = '<?php echo $last_order_id; ?>';

        $("#login_error_new").hide();

        if ((login_password.length) > 5)

        {

                // alert(login_password);

                var data = {
                    usermobile: usermobile,
                    login_password: login_password,
                    membership_applicable: membership_applicable,
                    merchant_id: last_order_merchant_id,
                    last_order_id: last_order_id
                };


                var koo_check_wallet = $(".koo_check_wallet").val();
                // alert(data);

                $.ajax({



                    url: '<?php echo $site_url; ?>/login.php',

                    type: 'POST',

                    dataType: 'json',

                    data: data,

                    success: function(response) {

                        var data = JSON.parse(JSON.stringify(response));

                        if (data.status)

                        {

                            // location.reload();

                            var login_user_id = $('#login_user_id').val();
                            if(koo_check_wallet != 0){
console.log('wallet1');
                                $(".wallet_ajax_value").html(" (Rm"+koo_check_wallet+")");
                                $(".koo_check_wallet").html(koo_check_wallet);
                                $("#koo_balance").val(koo_check_wallet);
                            }
                            // alert(login_user_id);   

                            var login_user_role = data.data.user_roles;



                            localStorage.setItem('login_live_id', login_user_id);

                            localStorage.setItem('login_live_role_id', login_user_role);



                            $('#login_for_wallet_id').val(login_user_id);

                            $('#newmodel_check').modal('hide');



                        } else

                        {

                            $("#login_ajax_password").val('');



                            $("#login_error_new").html('Your password is incorrect.Please try with correct password again or Reset that with Forgot Password');

                            $("#login_error_new").show();

                        }



                    }

                });



            } else

            {
                $("#login_error_new").html('Enter Atleaset 6 digit password to make login');

                $("#login_error_new").show();

            }



        });

        $(".register_ajax").click(function() {

            // return false;

            var password_created = '<?php echo $password_created; ?>';

            // alert(password_created);



            $("#register_error").hide();

            $(this).removeClass(" btn-primary").addClass("btn-default");

            setTimeout(function() {



                $(this).removeClass("btn-default").addClass("btn-primary");

            }.bind(this), 5000);

            // var user_id=('#login_user_id').val();



            var user_id = $("#login_user_id").val();

            var order_id = $("#order_id").val();



            var login_password = $("#register_password").val();

            var pass_length = login_password.length;

            if ((pass_length == 0) || (pass_length > 5))

            {

                var data = {
                    user_id: user_id,
                    register_password: login_password,
                    save_password: "y"
                };



                $.ajax({



                    url: '<?php echo $site_url; ?>/passwordsave.php',

                    type: 'POST',

                    dataType: 'json',

                    data: data,

                    success: function(response) {

                        if (response == 1)

                        {

                            // location.reload();

                            $('#PasswordModel').modal('hide');

                        } else

                        {



                            // $("#login_error").show();

                        }



                    }

                });

            } else

            {

                $("#register_error").html('The password must be six digit and above');

                $("#register_error").show();

                // $("#login_error").show();

            }





            return false;

        });

        $(".forgot_reset").click(function() {
           // $(this).hide();
           // $(this).removeClass(" btn-primary").addClass("btn-default");

           /* setTimeout(function() {



                $(this).removeClass("btn-default").addClass("btn-primary");

            }.bind(this), 5000);*/

            // var usermobile=$("#mobile_number").val();
            var mobile = $("#mobile_number").val();
            if (mobile[0] == 0) {
                mobile = mobile.slice(1);
            }
            var usermobile = "60" + mobile;

            // alert(usermobile);

            var data = {
                usermobile: usermobile,
                method: "forgotpass2"
            };

            // alert(data);

            $.ajax({



                url: '<?php echo $site_url; ?>/functions.php',

                type: 'POST',

                dataType: 'json',

                data: data,

                success: function(response) {

                    var data = JSON.parse(JSON.stringify(response));

                    // alert(data.status);

                    if (data.status)

                    {

                        $("#otp_count").val(otp_count);
                        $("#system_otp").val(data.otp);
                        $('#newmodel_check').modal('hide');
                        $('#forgot_msg_new').show();
                        $('.forgot_otp_form').show();
                        $('.forgot_footer').show();
                        $('#newuser_model').modal('hide');
                        $('#forgot_setup').modal('show');

                    } else

                    {
                        /*$(this).show();
                        $(".forgot_reset").show();

                        $(".forgot_error").html(data.msg);

                        $(".forgot_error").show();

                        $(".forgot_now ").hide();*/

                        $(".forgot_reset").show();
                        $("#myBtnLogWithOTP").hide();
                        $(".forgot_error").show();
                        $(".forgot_error").html(data.msg);


                    }



                }

            });

        });

        $(".forgot_pass").click(function() {
           $(".lgn_div_otp").hide();
           $("#ne_forg_div").show();
           $(".forgot_pass").hide();

           var mobile_no = $('#mobile_number').val();
            // alert(mobile_no);
            $('#show_msg_new').html('');
            $('#forgot_mobile_number').val(mobile_no);
            $('#user_mobile').val(mobile_no);
            $('#with_wallet').hide();

            $('#without_wallet').hide();

            $('.login_passwd_field').hide();

            $(".join_now").hide();

            $(".forgot_reset").show();

            $(".forgot_now").show();

            $('.forgot-form').show();

        });



        // paypal pay button click 

        $(".paypal_pay").click(function(e) {

            e.preventDefault();

            var product_qty = $('.product_qty').val();

            var table_type = $('#table_type').val();

            var number = $('#mobile_number').val();

            var total_rebate = 0;

            var total_amount = 0;

            var s_flag = true;

            if ((product_qty == null) || (product_qty == '')) {

                // alert('Without Prouct add cant able to go ahead.');  

                $('#show_msg').html("<?php echo $language['no_product_added']; ?>");

                $('#AlerModel').modal('show');

                var s_flag = false;

                return false;

            }

            if (number.length >= 9 && number.length <= 12 && (number[0] == 1 || number[0] == 6 || number[0] == 0)) {

                if (number[0] == 0) {
                    number = number.slice(1);
                }
            } else

            {

                $('#mobile_number').focus();
                $('#mobile_error').show();

                var s_flag = false;

            }

            if ($('#table_type').prop('required')) {

                if (table_type == '')

                {

                    $('#table_type').focus();

                    var s_flag = false;

                    // return false;

                }

            }

            if (s_flag)

            {



                $(".p_total").each(function() {

                    // var total_rebate+= $(this).val();

                    total_amount += parseFloat($(this).val());

                });

                var delivery_charges = $('#delivery_charges').val();

                // alert('Deliver charge '+delivery_charges);

                //if (delivery_charges > 0)

                var order_min_charge = "<?php echo $merchant_detail['order_min_charge']; ?>";

                //else

                    //var order_min_charge = 0;

                    if (order_min_charge > 0)

                    {



                        if (parseFloat(total_amount) < parseFloat(order_min_charge))

                        {

                        // var msg="Place Min Order for Rm "+order_min_charge;

                        var msg = "Minimum order is Rm " + order_min_charge;

                        $('#show_msg').html(msg);

                        $('#AlerModel').modal('show');

                        return false;

                        // alert('Order Amount greater');

                    }

                }

                // alert(total_amount);

                if (parseFloat(delivery_charges) > 0)

                {

                    var total_amount = parseFloat(total_amount) + parseFloat(delivery_charges);

                }

                var total_amount = total_amount.toFixed(2);

                $('#paypal_amount').val(total_amount);



                $('#total_cart_amount').val(total_amount);



                $.ajax({

                    type: 'post',

                    dataType: 'json',

                    url: '<?php echo $site_url; ?>/order_payal_cash.php',

                    data: $('#order_place').serialize(),

                    success: function(response) {

                        var data = JSON.parse(JSON.stringify(response));

                        if (data.status == true)

                        {

                            $('.paypal_pay').hide();

                            if (data.order_id)

                            {

                                // alert(data.order_id);

                                $('#item_order_id').val(data.order_id);

                                $('#paypal_form').submit();

                            } else

                            {

                                alert(data.msg);

                            }



                        } else

                        {

                            alert(data.msg);

                        }



                    }

                });

                // alert('Ready To take payment'); 

            }

            return false;

            if (s_flag)

                $('input[type=submit]', this).attr('disabled', 'disabled');



        });

$("#mapSearch").change(function(e) {

    var address = $('#mapSearch').val();
    if (address != '') {
        $('#location_error').hide();
        var geocoder = new google.maps.Geocoder();


                // alert(address);
                google.maps.event.addListener(autocomplete, 'place_changed', function() {
                    geocoder.geocode({
                        'address': address
                    }, function(results, status) {



                        if (status == google.maps.GeocoderStatus.OK) {

                            var latitude = results[0].geometry.location.lat();

                            var longitude = results[0].geometry.location.lng();
                            // alert(latitude);
                            // alert(longitude);
                            if (latitude != '' && longitude != '')

                            {

                                if (location_order == '1')
                                    calculatedisatace(latitude, longitude);

                            }






                        }

                    });
                });
            }


        });
$(".back_to_last").click(function(e) {
    $('#newuser_model').modal('hide');
    $('#InternetModel').modal('hide');
    var starttime = $('#stime').val();
    var show_time = $('#show_time').val();
    var end_time = $('#end_time').val();
    var endttime = $('#etime').val();
    var starday = $('#sday').val();
    var starday1 = starday;
    var endday = $('#eday').val();
    endday1 = endday;
    var currenttime = $('#ctime').val();
    if (starday == 'Monday') {

        starday = 1;

    } else if (starday == 'Tuesday') {

        starday = 2;

    } else if (starday == 'Wednesday') {

        starday = 3;

    } else if (starday == 'Thursday') {

        starday = 4;

    } else if (starday == 'Friday') {

        starday = 5;

    } else if (starday == 'Saturday') {

        starday = 6;

    } else {

        starday = 7;

    }

    if (endday == 'monday') {

        endday = 1;

    } else if (endday == 'Tuesday') {

        endday = 2;

    } else if (endday == 'Wednesday') {

        endday = 3;

    } else if (endday == 'Thursday') {

        endday = 4;

    } else if (endday == 'Friday') {

        endday = 5;

    } else if (endday == 'Saturday') {

        endday = 6;

    } else {

        endday = 7;

    }
    if ((currenttime > starttime && currenttime < endttime) && (starday <= n && endday >= n)) {} else {

        if (starttime.length == '' && endttime.length == '') {

                    // window.location.href = "view_merchant.php";

                } else {

                    // alert('Final Case');

                    $('#shop_close_time').val('y');

                    $s = moment(d + starttime + 'PM').format('LT');

                    $e = moment(d + endttime + 'PM').format('LT');


                }



            }

        });
$(".internet_banking").click(function(e) {
    totalcart();
    var product_qty = $('.product_qty').val();
    var table_type = $('#table_type').val();
    var membership_discount_input = $('#membership_discount_input').val();
    var number = $('#mobile_number').val();
    var already_login = $('#login_for_wallet_id').val();
    var total_rebate = 0;
    var total_amount = 0;
    var pickup_type = $('#pickup_type').val();
    var s_flag = true;
    if ((product_qty == null) || (product_qty == '')) {
        $('#show_msg').html("<?php echo $language['no_product_added']; ?>");
        $('#AlerModel').modal('show');
        var s_flag = false;
        return false;
    }
    if (number.length >= 9 && number.length <= 12 && (number[0] == 1 || number[0] == 6 || number[0] == 0)) {

        if (number[0] == 0) {
            number = number.slice(1);
        }
        $('#mobile_error').hide();
    } else {
        $('#mobile_error').show();
        $('#mobile_number').focus();
        var s_flag = false;
    }
    if ($('#table_type').prop('required')) {
        if (table_type == '') {
            $('#table_type').focus();
            var s_flag = false;
        }
    }
    if (pickup_type == "takein") {
        var mapSearch = $('#mapSearch').val();

        if (mapSearch == '') {
            $('#location_error').show();
            $('#mapSearch').focus();
            $('html, body').animate({
                scrollTop: $("#mapSearch").offset().top
            }, 2000);
            var s_flag = false;
        } else {
            $('#location_error').hide();
        }
    } else {
        $('#location_error').hide();
    }
    if (s_flag) {
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
            var order_min_charge = "<?php echo $merchant_detail['order_min_charge']; ?>";
        else
            var order_min_charge = 0;

        var order_min_charge = "<?php echo $merchant_detail['order_min_charge']; ?>";
        if (order_min_charge > 0) {
            if (parseFloat(total_amount) < parseFloat(order_min_charge)) {
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
		console.log('chk21##');
        var final_charge = final_charge.toFixed(2);
        $('#final_cart_amount_label').html(final_charge);
        var total_order_amount = 0;

        $(".p_total").each(function() {

            total_order_amount += +$(this).val();

        });

				//if("#")
				console.log("====="+$("#actual_payment_mode").val()+"============="+$("#payment_mode_cashback").val()+"====="+$(".internet_banking").attr("wallet_mode"));

				if($(".internet_banking").attr("wallet_mode") == 'yes'){
					var rem_amount = $("#rem_amount").val();
					console.log('tes'+rem_amount);
					$(".paid_wallet_if_popup").hide();
					if(rem_amount != 0){
						$(".internet_free_modal_amount").html(rem_amount);
						$(".paid_wallet_if_popup").show();
					}
				}else{
					$("#wallet_selected").val('n');
					$("#payable_amount").val('0');
					$("#rem_amount").val('0');
					$(".paid_wallet_if_popup").hide();
					$('#selected_wallet').val('');
				}
				
				$('#InternetModel').modal('show');
				$(".internet_banking").attr("wallet_mode","no");
                return false;
                $('input[type=submit]', this).attr('disabled', 'disabled');

            } else {
                return false;
            }

        });
$(".online_pay").click(function(e) {
    var product_qty = $('.product_qty').val();
    var table_type = $('#table_type').val();
    var membership_discount_input = $('#membership_discount_input').val();
            // alert(membership_discount_input);
            var number = $('#mobile_number').val();
            var already_login = $('#login_for_wallet_id').val();

			//console.log("already_login++"+already_login);
            var total_rebate = 0;

            var total_amount = 0;
            var pickup_type = $('#pickup_type').val();
            var s_flag = true;

            if ((product_qty == null) || (product_qty == '')) {

                // alert('Without Prouct add cant able to go ahead.');

                $('#show_msg').html("<?php echo $language['no_product_added']; ?>");

                $('#AlerModel').modal('show');

                var s_flag = false;

                return false;

            }

            if (number.length >= 9 && number.length <= 12 && (number[0] == 1 || number[0] == 6)) {
                $('#mobile_error').hide();

            } else

            {
                $('#mobile_error').show();

                $('#mobile_number').focus();

                var s_flag = false;

            }

            if ($('#table_type').prop('required')) {

                if (table_type == '')

                {

                    $('#table_type').focus();

                    var s_flag = false;

                    // return false;

                }

            }
            if (pickup_type == "takein") {
                var mapSearch = $('#mapSearch').val();

                if (mapSearch == '') {
                    $('#location_error').show();
                    $('#mapSearch').focus();
                    $('html, body').animate({
                        scrollTop: $("#mapSearch").offset().top
                    }, 2000);
                    var s_flag = false;
                } else {
                    $('#location_error').hide();
                }
            } else {
                $('#location_error').hide();
            }

            if (s_flag)
            {
                $("#newmodel_check").modal("hide");
                var shop_close_time = $('#shop_close_time').val();
                $(".rebate_amount").each(function() {

                    // var total_rebate+= $(this).val();

                    total_rebate += parseFloat($(this).val());

                });

                $(".p_total").each(function() {

                    // var total_rebate+= $(this).val();
					console.log('p_totla'+$(this).val());
                    total_amount += parseFloat($(this).val());

                });
console.log(total_amount);
                
                var delivery_charges = $('#delivery_charges').val();

                    // alert('Deliver charge '+delivery_charges);

                    var pickup_type = $('#pickup_type').val();

                    if (pickup_type == "divein")

                        var delivery_charges = 0;

                    if (delivery_charges > 0)

                        var order_min_charge = "<?php echo $merchant_detail['order_min_charge']; ?>";

                    else

                        var order_min_charge = 0;

                    if (order_min_charge > 0)

                    {

                        if (parseFloat(total_amount) < parseFloat(order_min_charge))
                        {

                            // var msg="Place Min Order for Rm "+order_min_charge;

                            var msg = "Minimum order is Rm " + order_min_charge;

                            $('#show_msg').html(msg);

                            $('#AlerModel').modal('show');

                            return false;

                            // alert('Order Amount greater');

                        }

                    }

                    // alert(total_rebate);

                    var total_amount = total_amount.toFixed(2);

                    if (total_rebate > 0) {

                        $('#without_wallet').hide();

                        // if(total_rebate>10)

                        // {

                        // var total_rebate=10;

                        // }

                        $('.rebate_amount_label').html(total_rebate);

                        $('.rebate_label').show();

                        if (!already_login)

                        {

                            $('#with_wallet').show();

                            // $('#without_wallet').show(); 

                        }

                        // $('#without_wallet').hide(); 

                    } else

                    {

                        $('#with_wallet').hide();

                        if (!already_login)

                        {

                            // alert(membership_discount_input);    

                            if (membership_discount_input)

                            {

                                $('#without_wallet').html('<span style="font-size:20px;">😀</span>Please login in order to claim for your ' + membership_discount_input + ' membership discount and to use wallet');

                            }

                            $('#without_wallet').show();

                        }

                    }

                    var total_rebate = total_rebate.toFixed(2);

                    $('#total_rebate_amount').val(total_rebate);

                    $('#total_cart_amount').val(total_amount);

                    $('#total_cart_amount_label').html(total_amount);

                    if (delivery_charges > 0)

                        $('.delivery_extra').show();

                    else

                        $('.delivery_extra').hide();

                    // var delivery_charges=delivery_charges.toFixed(2);

                    $('#delivery_cart_amount').val(delivery_charges);
                    var final_charge = parseFloat(total_amount) + parseFloat(delivery_charges);

                    $('#final_cart_amount').val(final_charge);
					console.log('chk11##');
                    var final_charge = final_charge.toFixed(2);
                    $('#delivery_cart_amount_label').html(delivery_charges);
                    $('#final_cart_amount_label').html(final_charge);

                    var total_order_amount = 0;

                    $(".p_total").each(function() {

                        total_order_amount += +$(this).val();

                    });

                    // alert(already_login);   

                    if (!already_login)

                    {

                        var mobile_number = $('#mobile_number').val();

                        var merchant_id = '<?php echo $id; ?>';

                        var special_coin_name = '<?php echo $special_coin_name; ?>';

                        var block_pay = '<?php echo $block_pay; ?>';

                        // alert(mobile_number);

                        // return false;

                        if (mobile_number)

                        {

                            // alert(mobile_number);

                            $.ajax({

                                url: '<?php echo $site_url; ?>/functions.php',

                                type: 'POST',

                                dataType: 'json',

                                data: {
                                    mobile_number: mobile_number,
                                    method: "usercheck",
                                    merchant_id: merchant_id,
                                    special_coin_name: special_coin_name
                                },

                                success: function(res) {

                                    var data = JSON.parse(JSON.stringify(res));

                                    // alert(JSON.stringify(data));

                                    // alert(data.status);
                                    console.log(data);
                                    console.log('usercheck');
                                    if (data.status == true)

                                    {

                                      if(data.koocash_wallet > 0){	
                                       $('#show_msg_new').html('Please login to use you wallet RM ' + data.koocash_wallet + '.');
                                   }
                                   $(".popup_info_user").show();
                                   $(".popup_info_user1").html(data.data.name);
                                   $(".popup_info_user2").html(data.data.mobile_number);
console.log('wallet2');
                                   $(".koo_check_wallet").html(data.koocash_wallet);
                                   $("#koo_balance").val(data.koocash_wallet);


                                   var plan_label = data.plan_label;

                                   var otp_verified = data.data.otp_verified;

                                        // alert(otp_verified);

                                        if (otp_verified == "y")

                                        {

                                            var plan_benefit = data.plan_benefit;

                                            $('#membership_discount_input').val(plan_benefit);

                                            // alert(plan_benefit);

                                            if (plan_label != '')

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



                                        } else

                                        {

                                            var msg = "Wallet Feature is Only For Register.Please Verify your mobile No,We are Processing as Cash Wallet";

                                            $('#show_msg').html(msg);

                                            $('#AlerModel').modal('show');

                                            setTimeout(function() {

                                                $("#WalletModel").modal("hide");

                                                $("#order_place").submit();

                                            }, 5000);

                                        }

                                    } else

                                    {

                                        $('#WalletModel').modal('show');

                                        setTimeout(function() {

                                            $("#WalletModel").modal("hide");

                                            $("#order_place").submit();

                                        }, 5000);

                                        // $("#order_place").submit();

                                        // alert('Wallet Feature is Only Applicale for Register Member,We are Processing as Cash Wallet');

                                    }

                                }

                            });

} else

{

    $('#mobile_number').foucus();

    alert('Phone number is Required');

}

                        // $(".forgot-form").show();

                    } else

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



                    return false;

                    if (s_flag)

                        $('input[type=submit]', this).attr('disabled', 'disabled');

                    totalcart();

                } else {
                    return false;
                }

            });

$(".wallet_select").click(function() {

    totalcart();
    var wallet_type = $(this).attr('type');
			 // alert(wallet_type);
            var special_coin_name = '<?php echo $special_coin_name; ?>';
			// alert(wallet_type);    
            var sst_tax = "<?php echo $merchant_detail['sst_rate'] ?>";

            var delivery_tax = "<?php echo $merchant_detail['delivery_rate'] ?>";

            var delivery_take_up = "<?php echo $merchant_detail['delivery_take_up'] ?>";

            var delivery_dive_in = "<?php echo $merchant_detail['delivery_dive_in'] ?>";

            var totalsale = 0;

            $(".p_total").each(function() {

                totalsale += +$(this).val();

            });

            if (wallet_type=="special_bal")
            {


                console.log('specialif');
                var special_coin_min = '<?php echo $special_coin_min; ?>';

                var special_coin_max = '<?php echo $special_coin_max; ?>';

                w_bal = $('#special_input_bal').val();

                var type = special_coin_name;

                var min_bal = special_coin_min;

                var max_bal = special_coin_max;

                var rebate_amount = 0;

                var wallet_name = special_coin_name;

            } else

            {  
                console.log('specialifelse');
                var type = $(this).attr('type');

                var w_bal = 0;

                // alert(type);

                var wallet_name = $(this).attr('wallet_name');

                if (type == "myr_bal")

                    w_bal = $('#myr_input_bal').val();

                if (type == "usd_bal")

                    w_bal = $('#usd_input_bal').val();

                if (type == "inr_bal")

                    w_bal = $('#inr_input_bal').val();
                if(type=="koo cashback")
                    w_bal = $('#koo_balance').val();    
                var min_bal = 1;

                var max_bal = 100;   

                var rebate_amount = $('#total_rebate_amount').val();

            }

            // alert(w_bal);

            // alert(min_bal);

            $('#wallet_selected').val('y');

            $('#selected_wallet').val(type);

            $('#selected_wallet_bal').val(w_bal);

            $('.select_label').show();



            var p_bal = 0;

            $('#wallet_name').html(wallet_name);
            var total_amount = $('#total_cart_amount').val();

            



            if (parseFloat(w_bal) > parseFloat(max_bal))

                p_bal = max_bal;

            else

                p_bal = w_bal;

            var p_bal = parseFloat(p_bal);

            var total_amount = $('#total_cart_amount').val();

            var delivery_charges = $('#delivery_charges').val();

            var pickup_type = $('#pickup_type').val();

                // alert(delivery_charges);



                if (pickup_type == "divein")

                {

                    if (delivery_dive_in == '1')

                    {

                        if (parseFloat(delivery_tax) > 0)

                        {

                            console.log("tax_1");
                            var deliver_tax_amount = calculatepercentage(totalsale, delivery_tax);

                        }

                    } else

                    {
                      console.log("tax_2");
                      var deliver_tax_amount = 0;

                  }

                  var delivery_charges = 0;

              } else if (pickup_type == "takein")

              {

                  //  var fix_delivery_val = "<?php echo $merchant_detail['order_extra_charge']; ?>";
                  var fix_delivery_val = "<?php echo $merchant_detail['delivery_charges']; ?>";

                  if (delivery_charges == 0) {
                    if (fix_delivery_val)
                        var delivery_charges = fix_delivery_val;
                    else {
                        var additonal_delivery_charges = $('#additonal_delivery_charges').val();
                        if (additonal_delivery_charges)
                            var delivery_charges = additonal_delivery_charges;
                        else
                            var delivery_charges = 2.99;
                    }

                }  


                if (delivery_take_up == '1')

                {

                    if (parseFloat(delivery_tax) > 0)

                    {
                       console.log("tax_3");
                       var deliver_tax_amount = calculatepercentage(totalsale, delivery_tax);

                   }

               } else

               {

                var deliver_tax_amount = 0;

            }

        }



        if (parseFloat(sst_tax) > 0)

        {

            var sst_amount = calculatepercentage(total_amount, sst_tax);

        }


        var membership_discount_input = $('#membership_discount_input').val();



        if (membership_discount_input)

        {

            var totalsale = 0;

            $(".p_total").each(function() {

                totalsale += +$(this).val();

            });

            var substring = "%";

            if (membership_discount_input.includes(substring))

            {

                var discount = membership_discount_input.replace("%", "");



                        // per discount 

                        var membership_discount = calculatepercentage(totalsale, discount);



                    } else

                    {

                        // fix discount 

                        var membership_discount = membership_discount_input.replace("off", "");

                        membership_discount = parseFloat(membership_discount);



                    }
                    

                    if (membership_discount > 0)

                    {

                        var total_amount = parseFloat(total_amount) - parseFloat(membership_discount);

                    }



                }

                if (parseFloat(delivery_charges) > 0)
                    var total_amount = parseFloat(total_amount) + parseFloat(delivery_charges);

                var coupon_discount = $('#coupon_discount').val();

                if (coupon_discount > 0)

                {

                    var total_amount = parseFloat(total_amount) - parseFloat(coupon_discount);

                }

                // alert(total_amount);
                


                // alert(w_bal);



                if (parseFloat(sst_tax) > 0)

                {

                    // var sst_amount=calculatepercentage(total_amount,sst_tax);

                    $('.sst_amount_label').show();

                    $('.sst_amount_value').html(parseFloat(sst_amount).toFixed(2));

                    var total_amount = (parseFloat(total_amount) + parseFloat(sst_amount));

                }

                // alert(deliver_tax_amount);

                if (parseFloat(deliver_tax_amount) > 0)

                {

                    // var sst_amount=calculatepercentage(total_amount,sst_tax);
                    console.log("2");
                    $('.delivery_tax_amount_label').show();

                    $('.delivery_tax_amount_value').html(parseFloat(deliver_tax_amount).toFixed(2));

                    var total_amount = (parseFloat(total_amount) + parseFloat(deliver_tax_amount));

                }
                var special_delivery_amount = $('#special_delivery_amount').val();
                var total_amount = (parseFloat(total_amount) + parseFloat(special_delivery_amount));
				// speed delivery
				var speed_delivery_amount = $('#speed_delivery_amount').val();
				var donation_amount_value =  $('#donation_amount_value').val();
                var total_amount = (parseFloat(total_amount) + parseFloat(speed_delivery_amount) + parseFloat(donation_amount_value));
                // alert(total_amount);
                var part_payment = "<?php echo $merchant_detail['part_payment'] ?>";
				// alert(part_payment);   
				console.log(w_bal+"==trigg=="+min_bal);
				if ((parseFloat(w_bal) >= parseFloat(min_bal)))

                {

                    console.log(part_payment);
                    if (part_payment == 0) {
                     console.log(total_amount +"===Cashback12=="+w_bal);
                     if ((parseFloat(total_amount) > parseFloat(w_bal)))

                     {
                      console.log(total_amount +"===Cashback=="+w_bal);
                      total_amount=parseFloat(total_amount).toFixed(2);

						//check
						
						//end
						
						$('#payable_bal').html(total_amount);
                       // $('#LesaaAmountModel').modal('show');
                       $('#ProccedAmount').modal('show');
                       return false;

                   }
               }


                // alert("Payment Bal"+p_bal);

                // alert("Payment 2"+total_amount);
                // alert(p_bal);
                // alert(total_amount);
                if (parseFloat(p_bal) > parseFloat(total_amount))

                    var p_bal = total_amount;

                // alert(total_amount);
                p_bal=p_bal.toFixed(2);
                // p_bal = (parseInt(p_bal * 10) / 10).toFixed(2);



                $('#wallet_payment_label').show();

                //$('#bal_payment_label').show();

                $('#wallet_payment_label').html("<br/>Paying By Wallet Rm: <span style='color:black;font-weight:bold;'>" + p_bal + "</span></br>");





                var merchant_id = '<?php echo $id; ?>';

                var selected_wallet_bal = $('#selected_wallet_bal').val();
				// alert(selected_wallet_bal);
				// alert(max_bal);    
                if (parseFloat(selected_wallet_bal) > parseFloat(max_bal))

                    selected_wallet_bal = max_bal;

                selected_wallet_bal = (parseInt(selected_wallet_bal * 10) / 10).toFixed(2);
                // selected_wallet_bal = selected_wallet_bal.toFixed(2);
				//alert(selected_wallet_bal);
                var rem = 0;
                // alert(total_amount);   

                if (parseFloat(selected_wallet_bal) < parseFloat(total_amount))

                {
                    var rem = parseFloat(total_amount) - parseFloat(selected_wallet_bal);
                    var rem = parseFloat(rem).toFixed(2);
                    var payable_amount = selected_wallet_bal;
                    var payable_amount = parseFloat(payable_amount).toFixed(2);
                    var selected_wallet_bal = parseFloat(selected_wallet_bal).toFixed(2);
                    var p_msg =i_1+" Rm " + selected_wallet_bal + i_2+ ",The Remaining Rm balance of " + rem + " will be paid by ";

                    $(".internet_free_modal_amount").html(rem);
console.log('wallet3');
                    $(".paid_wallet_if_popup").html("RM <span style='color:black;font-weight:bold;' class='koo_check_wallet'>" + selected_wallet_bal + "</span> through your wallet");
					$(".no_need_paymnetoptions").show(); //show all option, cash, internet banking, fpx. and enable place order.
					$(".cash_wallet_f").hide();
					$(".cash_wallet_f_main").removeClass('col-sm-12');
					$(".cash_wallet_f_main").addClass('col-sm-2');

                } else

                {

                    var total_amount = parseFloat(total_amount).toFixed(2);
                    // alert(total_amount);
                    var p_msg = "You are going to pay Rm " + total_amount + " throught your wallet";

                    var payable_amount = total_amount;
					$(".no_need_paymnetoptions").hide(); //remove all option, cash, internet banking, fpx. and enable place order.
					$(".cash_wallet_f").show();
					$(".cash_wallet_f_main").addClass('col-sm-12');
					$(".cash_wallet_f_main").removeClass('col-sm-2');

                }

                $('#bal_to_paid_label').show();

                $('#bal_to_paid_label').html("Balance to be paid by cash Rm: <span style='color:black;font-weight:bold;'>" + rem + "</span></br>");
                console.log("trigger click here");
                $(".wallet_final_payment").trigger("click");


                // $('.wallet_final_payment').show(); 





            } else

            {
                total_amount=parseFloat(total_amount).toFixed(2);
                console.log(total_amount);
                $('#payable_bal').html(total_amount);
                console.log(total_amount +"===Cashback1=="+w_bal);
                var p_msg = "You are going to pay RM 0.0 through your cashback,The balance of RM  <span style='color:black;font-weight:bold;' class='koo_check_wallet'>" + total_amount + "</span> can be paid by ";
                $(".internet_free_modal_amount").html(total_amount);
                $(".paid_wallet_if_popup").html("RM <span style='color:black;font-weight:bold;' class='koo_check_wallet'>" + total_amount + "</span> through your wallet");
				$(".no_need_paymnetoptions").show(); //show all option, cash, internet banking, fpx. and enable place order.
				$(".cash_wallet_f").hide();
				$(".cash_wallet_f_main").removeClass('col-sm-12');
				$(".cash_wallet_f_main").addClass('col-sm-2');

				$('#amount_label').html(p_msg);
                //$('#LesaaAmountModel').modal('show');
                $('#ProccedAmount').modal('show');


                // alert('Insufficient balance for deduction, please select other wallet or pay by cash. ');

                return false;

                // exit;

            }

            totalcart();

        });

$(".trasfer_complete").click(function() {
    $('.trasfer_complete').hide();
    $('.back_to_last').hide();
    $('#interent_or').hide();
    $('#process_internet_order').html("Please wait......., we are processing your order..");
    $(this).removeClass(" btn-primary").addClass("btn-default");

            // $('#payable_amount').val(0);
            if($('#selected_wallet').val() != ''){

            }else{
                $('#selected_wallet').val('Internet Banking');
            }
            

            $("#order_place").submit();


        });

$(".cash_pay").click(function() {

    $(this).removeClass(" btn-primary").addClass("btn-default");

    $('#payable_amount').val(0);

    $('#selected_wallet').val('');

    $("#order_place").submit();

});



$(".make_payment").click(function() {
   $("#LesaaAmountModel").modal("hide"); 
   $("#newuser_model").modal("hide"); 
   $(this).removeClass(" btn-danger").addClass("btn-default");
   $('#wallet_order_process').show();
   $('.back_to_last').hide();
   $("#order_place").submit();

});



$(".wallet_final_payment").click(function() {
            // alert(2);
			console.log('wallet_final_payment trigger');
            var sst_tax = "<?php echo $merchant_detail['sst_rate'] ?>";

            var delivery_tax = "<?php echo $merchant_detail['delivery_rate'] ?>";

            var delivery_take_up = "<?php echo $merchant_detail['delivery_take_up'] ?>";

            var delivery_dive_in = "<?php echo $merchant_detail['delivery_dive_in'] ?>";

            $('.wallet_final_payment').removeClass(" btn-danger").addClass("btn-default");

            var total_amount = $('#total_cart_amount').val();

            var rebate_amount = $('#total_rebate_amount').val();

            var merchant_id = '<?php echo $id; ?>';

            var selected_wallet_bal = $('#selected_wallet_bal').val();

            var special_coin_name = '<?php echo $special_coin_name; ?>';

            var special_coin_max = '<?php echo $special_coin_max; ?>';

            if (special_coin_name)

                var max_bal = special_coin_max;

            else

                var max_bal = 100;

            if (parseFloat(selected_wallet_bal) > parseFloat(max_bal))

                selected_wallet_bal = max_bal;

            var rem = 0;
            totalcart();
            selected_wallet_bal = (parseInt(selected_wallet_bal * 10) / 10).toFixed(2);

            var delivery_charges = $('#delivery_charges').val();

            var pickup_type = $('#pickup_type').val();
            // alert(pickup_type);
            if (pickup_type == "divein")

            {

                if (delivery_dive_in == '1')

                {

                    if (parseFloat(delivery_tax) > 0)

                    {

                        var deliver_tax_amount = calculatepercentage(total_amount, delivery_tax);

                    }

                } else

                {



                    var deliver_tax_amount = 0;

                }

                var delivery_charges = 0;

            } else if (pickup_type == "takein")

            {
                //var fix_delivery_val = "<?php echo $merchant_detail['order_extra_charge']; ?>";
                var fix_delivery_val = "<?php echo $merchant_detail['delivery_charges']; ?>";
                
                if (delivery_charges == 0) {
                    if (fix_delivery_val)
                        var delivery_charges = fix_delivery_val;
                    else {
                        var additonal_delivery_charges = $('#additonal_delivery_charges').val();
                        if (additonal_delivery_charges)
                            var delivery_charges = additonal_delivery_charges;
                        else
                            var delivery_charges = 2.99;
                    }

                }      

                if (delivery_take_up == '1')

                {

                    if (parseFloat(delivery_tax) > 0)

                    {

                        var deliver_tax_amount = calculatepercentage(total_amount, delivery_tax);

                    }

                } else

                {



                    var deliver_tax_amount = 0;

                }

            }

            if (parseFloat(sst_tax) > 0)

            {

                var sst_amount = calculatepercentage(total_amount, sst_tax);

            }

            if (parseFloat(delivery_charges) > 0)

            {

                var total_amount = parseFloat(delivery_charges) + parseFloat(total_amount);

            }

            var membership_discount_input = $('#membership_discount_input').val();

            if (membership_discount_input)

            {

                var totalsale = 0;

                $(".p_total").each(function() {

                    totalsale += +$(this).val();

                });

                var substring = "%";

                if (membership_discount_input.includes(substring))

                {

                    var discount = membership_discount_input.replace("%", "");



                    // per discount 

                    var membership_discount = calculatepercentage(totalsale, discount);



                } else

                {

                    // fix discount 

                    var membership_discount = membership_discount_input.replace("off", "");

                    membership_discount = parseFloat(membership_discount);



                }

                if (membership_discount > 0)

                {

                    var total_amount = parseFloat(total_amount) - parseFloat(membership_discount);

                }

            }



            if (parseFloat(sst_tax) > 0)

            {

                // var sst_amount=calculatepercentage(total_amount,sst_tax);

                $('.sst_amount_label').show();

                $('.sst_amount_value').html(parseFloat(sst_amount).toFixed(2));

                var total_amount = (parseFloat(total_amount) + parseFloat(sst_amount));

            }

            if (parseFloat(deliver_tax_amount) > 0)

            {
                console.log("3");
                $('.delivery_tax_amount_label').show();
                $('.delivery_tax_amount_value').html(parseFloat(deliver_tax_amount).toFixed(2));
                var total_amount = (parseFloat(total_amount) + parseFloat(deliver_tax_amount));

            } else
            {
                $('.delivery_tax_amount_label').hide();
                $('.delivery_tax_amount_value').html('');
            }
            var special_delivery_amount = $('#special_delivery_amount').val();
            if (parseFloat(special_delivery_amount) > 0) {
                var total_amount = (parseFloat(total_amount) + parseFloat(special_delivery_amount));
            }
            var speed_delivery_amount = $('#speed_delivery_amount').val();
            if (parseFloat(speed_delivery_amount) > 0) {
                var total_amount = (parseFloat(total_amount) + parseFloat(speed_delivery_amount));
            }

            var donation_amount_value = $('#donation_amount_value').val();
            if (parseFloat(donation_amount_value) > 0) {
                var total_amount = (parseFloat(total_amount) + parseFloat(donation_amount_value));
            }

            $('#deliver_tax_amount').val(deliver_tax_amount);

            if (parseFloat(selected_wallet_bal) < parseFloat(total_amount))

            {

                var rem = parseFloat(total_amount) - parseFloat(selected_wallet_bal);

                var rem = parseFloat(rem).toFixed(2);

                var payable_amount = selected_wallet_bal;

                var payable_amount = parseFloat(payable_amount).toFixed(2);



                var selected_wallet_bal = selected_wallet_bal;

                var selected_wallet_bal = (parseInt(selected_wallet_bal * 10) / 10).toFixed(2);

                var p_msg = i_1+" RM <span style='color:black;font-weight:bold;' class='koo_check_wallet'>" + selected_wallet_bal + "</span> through your wallet. The remaining balance of RM <span style='color:black;font-weight:bold;'>" + rem + "</span> is to be paid by.";

                console.log('1check');
                if(rem != 0){
                 $(".internet_free_modal_amount").html(rem);
                 $(".paid_wallet_if_popup").html("RM <span style='color:black;font-weight:bold;' class='koo_check_wallet'>" + selected_wallet_bal + "</span> through your wallet");
             }else{
                 $(".internet_free_modal_amount").html(selected_wallet_bal);
             }


				$(".no_need_paymnetoptions").show(); //show all option, cash, internet banking, fpx. and enable place order.
				$(".cash_wallet_f").hide();
				$(".cash_wallet_f_main").removeClass('col-sm-12');
				$(".cash_wallet_f_main").addClass('col-sm-2');
				

				


            } else

            {

                var total_amount = parseFloat(total_amount).toFixed(2);
                // p_bal = (parseInt(total_amount * 10)/10).toFixed(2);
console.log('wallet4');
                var p_msg =i_1+" Rm <span style='color:black;font-weight:bold;' class='koo_check_wallet'>" + total_amount + "</span> "+i_2;

                var payable_amount = total_amount;

				$(".no_need_paymnetoptions").hide(); //remove all option, cash, internet banking, fpx. and enable place order.
				$(".cash_wallet_f").show();
				$(".cash_wallet_f_main").addClass('col-sm-12');
				$(".cash_wallet_f_main").removeClass('col-sm-2');

            }   



            $('#amount_label').html(p_msg);


            $('#ProccedAmount').modal('show');

            var wallet_type = $('#selected_wallet').val();

            var user_id = $('#login_user_id').val();

            $('#rem_amount').val(rem);

            $('#payable_amount').val(payable_amount);

            // var r = confirm(p_msg);
            var r=true;
            if (r == true) {

                // alert(data);

                var wallet_type = $('#selected_wallet').val();

                var user_id = $('#login_user_id').val();

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



        if (location_order == 1)

        {
	
	
			console.log("location order:"+location_order);



           // var fix_delivery_val = "<?php echo $merchant_detail['order_extra_charge']; ?>";    
           var fix_delivery_val = "<?php echo $merchant_detail['delivery_charges']; ?>";
           var discount_offer_minus =  "<?php echo $discount_offer_minus; ?>";
           

           var user_lat = $('#user_lat').val();

           if (!user_lat)

           {
            var shop_close_time = $('#shop_close_time').val();
            if (shop_close_time == "n") {
                $('.pro_status').show();
					//console.log('new_remark1');
                    $('.introduce-remarks').show();
					//$('.introduce-remarks').hide();
                    $("#confmpayment").prop("disabled", false);
                    $("#confm").prop("disabled", false);
                    $(".online_pay").prop("disabled", false);
                }
                
                // var msg="We are unable to track your location. If your location is less than 5km, the delivery charges is Rm2.99, more than 5km=Rm1/km.";
                // var msg="<?php echo $language['distance_msg_system']; ?>";  
                // $('#show_msg').css("font-size","18px");
                // $('#show_msg').html(msg);   
                var dine_in = "<?php echo $dine_in; ?>";
                if (dine_in == "n") {
                    $('#delivery_label').show();
                    if (fix_delivery_val){
                        var delivery_charges =  parseFloat(fix_delivery_val).toFixed(2) - parseFloat(discount_offer_minus).toFixed(2);
                    }else{
                        var delivery_charges = 2.99 - parseFloat(discount_offer_minus).toFixed(2);
                    }

                    console.log("#temp comment1:=="+delivery_charges);
                   //temp comment: $('#delivery_charges').val(delivery_charges);
                   //temp comment: $('#order_extra_charge').val(delivery_charges);
                }
                $('#delivery_info_label').show();
                $('#delivery_info_label').html("</br>" + msg);

                // $('#AlerModel').modal('show'); 

                // setTimeout(function(){ $("#AlerModel").modal("hide"); },5000);

            }



        }



    });

function clearhistory()

{

    $('#location_model').hide();

    setTimeout(function() {
        $("#clear_history_model").modal("show");
    }, 1000);

        // $('#clear_history_model').show();  

    }

    function walletstep()

    {

        $('.wallet_mode').show();

    }

    function generatetokenno(length) {

        var result = '';

        var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

        var charactersLength = characters.length;

        for (var i = 0; i < length; i++) {

            result += characters.charAt(Math.floor(Math.random() * charactersLength));

        }

        return result;

    }

    function rebatevalue(a, b)

    {

        var c = (parseFloat(a) * parseFloat(b)) / 100;

        return parseFloat(c).toFixed(2);

    }

    function myFunction() {

        var x = document.getElementById("login_ajax_password");

        if (x.type === "password") {

            x.type = "text";

            $("#eye_pass").html('Hide Password');

            $('#eye_slash').removeClass("fa-eye-slash");

            $('#eye_slash').addClass("fa-eye");



        } else {

            x.type = "password";

            $("#eye_pass").html('Show Password');

            $('#eye_slash').addClass("fa-eye-slash");

            $('#eye_slash').removeClass("fa-eye");

        }

    }

    function myFunctionforgot() {
        var x = document.getElementById("forgot_register");
        if (x.type === "password") {
            x.type = "text";
            $("#eye_pass_forgot").html('Hide Password');
            $('#eye_slash_forgot').removeClass("fa-eye-slash");
            $('#eye_slash_forgot').addClass("fa-eye");

        } else {
            x.type = "password";
            $("#eye_pass_forgot").html('Show Password');
            $('#eye_slash_forgot').addClass("fa-eye-slash");
            $('#eye_slash_forgot').removeClass("fa-eye");
        }
    }

    function myFunction2() {

        var x = document.getElementById("register_password");

        if (x.type === "password") {

            x.type = "text";

            $("#eye_pass_2").html('Hide Password');

            $('#eye_slash_2').removeClass("fa-eye-slash");

            $('#eye_slash_2').addClass("fa-eye");



        } else {

            x.type = "password";

            $("#eye_pass_2").html('Show Password');

            $('#eye_slash_2').addClass("fa-eye-slash");

            $('#eye_slash_2').removeClass("fa-eye");

        }

    }

    function myFunctionnew() {

        var x = document.getElementById("login_ajax_password_new");

        if (x.type === "password") {

            x.type = "text";

            $("#eye_pass_new").html('Hide Password');

            $('#eye_slash_new').removeClass("fa-eye-slash");

            $('#eye_slash_new').addClass("fa-eye");



        } else {

            x.type = "password";

            $("#eye_pass_new").html('Show Password');

            $('#eye_slash_new').addClass("fa-eye-slash");

            $('#eye_slash_new').removeClass("fa-eye");

        }

    }

    function our_stall() {

        $('#our_stall').modal('show');



    }

    function getId(element) {

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

            $("#confm").prop("disabled", true);

            $(".online_pay").prop("disabled", true);

            $('.text_add_cart').hide();

            if (location_order == 1)

                $('#location_model').modal('show');

        }

    }

    function codeLatLng(lat, lng) {

        var geocoder = new google.maps.Geocoder();

        var latlng = new google.maps.LatLng(lat, lng);

        // alert(latlng);

        geocoder.geocode({
            'latLng': latlng
        }, function(results, status) {

            if (status == google.maps.GeocoderStatus.OK) {

                // alert(results)

                if (results[1]) {

                    //formatted address

                    // alert()

                    //find country name

                    var full_address = results[0].formatted_address;

                    $('#mapSearch').val(full_address);

                    //city data

                    // alert(city.short_name + " " + city.long_name)

                } else {

                    alert("No results found");

                }

            } else {
                var shop_close_time = $('#shop_close_time').val();
                if (shop_close_time == "n") {
                    $('.pro_status').show();
                    console.log('new_remark11');
                    $('.introduce-remarks').hide();
                    $("#confmpayment").prop("disabled", false);
                    $("#confm").prop("disabled", false);
                    $(".online_pay").prop("disabled", false);
                }
                
                
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

    function calculatedisatace(to_lat, to_long)

    {

        // alert(3);

        var from_lat = "<?php echo $merchant_detail['latitude']; ?>";

        var from_long = "<?php echo $merchant_detail['longitude']; ?>";

        var location_range = "<?php echo $merchant_detail['location_range']; ?>";

        $.ajax({

            url: '<?php echo $site_url; ?>/calculate_distance.php',

            type: 'POST',

            data: {
                from_lat: from_lat,
                from_long: from_long,
                to_lat: to_lat,
                to_long: to_long,
                merchant_id: '<?php echo $merchant_detail['id']; ?>'
            },

            dataType: 'json',

            success: function(response) {

                var result = JSON.parse(JSON.stringify(response));

                if (result.status == true)

                {

                    var data = result.data;

                    var distance = data.distance;

                } else

                {

                    var distance = 0;

                }



                // alert(to_lat);

                // alert(distance);     

                // alert(data);     



                // alert('Customer Distance: '+distance);

                console.log('Customer Distance: ' + distance);
                <?php 
                $m_name = str_replace("'", ' ', $merchant_detail['name']);
                $m_name=clean($m_name);
                ?>
                var msg = "Your distance is " + distance + " km away from " + '<?php echo $m_name; ?>';   

                $('#show_msg').html(msg);

                //$('#AlerModel').modal('show');

                setTimeout(function() {
                    $("#AlerModel").modal("hide");
                }, 2000);


                if (parseFloat(distance) > parseFloat(location_range))

                {

                    $('.pro_status').hide();

                    $("#confm").prop("disabled", true);

                    $(".online_pay").prop("disabled", true);

                    $("#confmpayment").prop("disabled", true);

                    $('#map_range').html(location_range);

                    $('.fancybox_place_order').hide();

                    $('#map_model').modal('show');

                    $('#delivery_label').hide();

                } else

                {

                    // if(distance>0)

                    // {



                    // }  

                    // check delivery charges of order 

                    // var delivery_charges="<?php echo $merchant_detail['order_extra_charge']; ?>";

                    var delivery_charges = data.delivery_charge;

                    // var delivery_charges=delivery_charges.toFixed(2);

                    // alert(delivery_charges);

                    var free_delivery = "<?php echo $merchant_detail['free_delivery']; ?>";

                    console.log('Delivery Charge: ' + delivery_charges);
                    var discount_offer_minus =  "<?php echo $discount_offer_minus; ?>";


                    // alert('Free Delivery Charges: '+free_delivery);

                    $('#order_extra_label').html(parseFloat(delivery_charges).toFixed(2) - parseFloat(discount_offer_minus));
                    var dine_in = "<?php echo $dine_in; ?>";
                    if (parseFloat(delivery_charges) > 0)

                    {

                        if (parseFloat(distance) > parseFloat(free_delivery))

                        {

                            // alert('paid');
                            console.log("#2:"+delivery_charges);
                            $('#delivery_charges').val(delivery_charges);
                            $('#additonal_delivery_charges').val(delivery_charges);
                            $('#order_extra_charge').val(delivery_charges);
                            if (dine_in == "n")
                                $('#delivery_label').show();

                        } else

                        {
                            // alert('free');
                            console.log("#3:"+delivery_charges);
                            $('#additonal_delivery_charges').val(delivery_charges);
                            $('#delivery_charges').val(delivery_charges);
                            $('#order_extra_charge').val(delivery_charges);
                            // $('#delivery_charge_status').val('free');
                            if (dine_in == "n")
                                $('#delivery_label').show();

                        }



                    } else

                    {

                        $('#delivery_label').hide();

                    }



                    var shop_close_time = $('#shop_close_time').val();
                    if (shop_close_time == "n") {
                        $('.pro_status').show();
                        console.log('new_remark111');
                        //$('.introduce-remarks').hide();

                        $("#confmpayment").prop("disabled", false);

                        $("#confm").prop("disabled", false);

                        $(".online_pay").prop("disabled", false);
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

        if (latitude && longitude)

        {

            $('#user_lat').val(latitude);

            $('#user_long').val(longitude);

            calculatedisatace(latitude, longitude);



            $('#location_model').modal('hide');

        }

    }
</script>

<script>
    $(".microphone_click").on("click", function() {
        var s_flag = true;
        var number = $('#mobile_number').val();
        var table_type = $('#table_type').val();
        var section_type = $('#section_type').val();
        var section_text = $("#section_type option:selected").text();
        if (number.length >= 9 && number.length <= 12 && (number[0] == 1 || number[0] == 6)) {
            $('#mobile_error').hide();
            $("#mobile_number").removeClass("is-invalid").addClass("is-valid");
        } else {
            // alert(3);
            if ($(this).data('type') == 'top') {
                //var $elem = $('#order_place');
                // $('html, body').animate({scrollTop: $elem.height()}, 1000);
                $('html, body').animate({
                    scrollTop: $("#mobile_no_label").offset().top
                }, 2000);
            }
            $("#mobile_number").removeClass("is-valid").addClass("is-invalid");
            $('#mobile_number').focus();
            $('#mobile_error').show();
            var s_flag = false;

        }
        if ($('#table_type').prop('required')) {
            if (table_type == '') {
                if ($(this).data('type') == 'top') {
                    //var $elem = $('#order_place');
                    // $('html, body').animate({scrollTop: $elem.height()}, 1000);
                    $('html, body').animate({
                        scrollTop: $("#table_show").offset().top
                    }, 2000);
                }
                $("#table_type").removeClass("is-valid").addClass("is-invalid");
                $('#table_type').focus();

                var s_flag = false;

                // return false;

            }

        }
        // alert(s_flag);
        if (s_flag) {
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
            function(isConfirm) {
                if (isConfirm) {
                    var audio = document.getElementById("player1_html5");
                    audio.play();
                    $('#voice_recognition').modal('show');
                    $("#spin").hide();
                    var html = "";
                    html += '<li class="list-group-item orderItem"><strong>Phone Number : </strong> <span id="tableNum" class="text-right">' + number + '</span></li>';

                    if (table_type != "") {
                        html += '<li class="list-group-item orderItem"><strong>Table Number : </strong> <span id="tableNum" class="text-right">' + table_type + '</span></li>';
                    }

                    if (section_type != "") {
                        html += '<li class="list-group-item orderItem"><strong>Section : </strong> <span id="section"class="text-right">' + section_text + '</span></li>';
                    }
                    $("#modal_list").html(html);
                    return true;
                } else {
                    swal("", "Order Not Placed!", "error");
                }
            });
        }
    });
</script>

<script>
    function verifiedmobile()

    {

        var user_mobile = $('#verifiedmobile').val();

        $('#verifybutton').hide();

        if (user_mobile)

        {

            data = {
                user_mobile: user_mobile,
                method: "resendlink"
            };

            $.ajax({

                url: "<?php echo $site_url; ?>/functions.php",

                type: "post",

                data: data,

                dataType: 'json',

                success: function(response) {

                    var data = JSON.parse(JSON.stringify(response));

                    if (data.status)

                    {

                        $('#resend_link_label').show();

                        setTimeout(function() {
                            $("#free_trial_model").modal("hide");
                        }, 3000);

                    } else

                    {

                        alert(data.msg);

                    }

                },

                error: function(data) {

                    console.log(data);

                    $('#verifybutton').hide();

                }

            });

        }

    }

    function calculatepercentage(listPrice, discount)

    {

        listPrice = parseFloat(listPrice);

        discount = parseFloat(discount);

        return ((listPrice * discount / 100)).toFixed(2); // Sale price

    }

    function totalcart()
    {
        // alert(3);
        var sst_tax = "<?php echo $merchant_detail['sst_rate'] ?>";

        var delivery_tax = "<?php echo $merchant_detail['delivery_rate'] ?>";

        var delivery_take_up = "<?php echo $merchant_detail['delivery_take_up'] ?>";

        var delivery_dive_in = "<?php echo $merchant_detail['delivery_dive_in'] ?>";

        var discount_offer_minus = "<?php echo $discount_offer_minus; ?>";

        var totalsale = 0;

        $(".p_total").each(function() {

            totalsale += +$(this).val();

        });

        var delivery_charges = $('#delivery_charges').val(); //  - parseFloat(discount_offer_minus);
        var special_delivery_amount = $('#special_delivery_amount').val();
        var speed_delivery_amount = $('#speed_delivery_amount').val();
        var pickup_type = $('#pickup_type').val();






        /*Free Delivery*/
        //console.log('#1--------------'+delivery_charges);
        var free_delivery_check = 0; 
        var pfree_delivery_check =  "<?php echo $merchant_detail['free_delivery_check'] ?>";
        if(pfree_delivery_check == 1){
           if(totalsale > 40){
            console.log('check in--------------'+totalsale);
            var free_delivery_check = 1;
            $("#order_extra_label").html('0.00');
            $('#order_extra_charge').val('0.00');
            $('#delivery_charges').val('0.00');
            var delivery_charges = $('#delivery_charges').val();
            $(".left_price").hide();
            $(".free_price").show();
            var freet_text = "(Free Delivery)";
            $(".free_price").html(freet_text);

        }else{
            var l_price = 40 - parseFloat(totalsale);

            $(".left_price").show();
            $(".free_price").hide();
            var left_text = "( -RM"+parseFloat(l_price).toFixed(2)+" left for Free Delivery)";
            $(".left_price").html(left_text);

				//console.log('free_delivery_check_in');
				var fix_delivery_val = "<?php echo $merchant_detail['order_extra_charge']; ?>";
				var delivery_charges = $('#delivery_charges').val() - parseFloat(discount_offer_minus) ;
				//if (delivery_charges == 0) {
					//console.log('free_delivery_check_in##');
					if (fix_delivery_val){
						console.log('fix');
						var delivery_charges = parseFloat(fix_delivery_val) - parseFloat(discount_offer_minus);
					}else {
						console.log('notfix');
						var additonal_delivery_charges = $('#additonal_delivery_charges').val();
						if (additonal_delivery_charges){
							console.log('addfix');
							var delivery_charges = parseFloat(additonal_delivery_charges) - parseFloat(discount_offer_minus);
						}else{
							console.log('notaddfix'+delivery_charges);
							//var delivery_charges = 2.99  - parseFloat(discount_offer_minus);
							var delivery_charges = $('#delivery_charges').val() - parseFloat(discount_offer_minus);
						}
					}
				//}
				console.log(parseFloat(delivery_charges).toFixed(2));
				$("#order_extra_label").html(parseFloat(delivery_charges).toFixed(2));
				$('#order_extra_charge').val(parseFloat(delivery_charges).toFixed(2));	
			}
		}else{
			/*if(free_delivery_check == 1){
				var delivery_charges = '0.00';
			}else{
               */	var delivery_charges = $('#delivery_charges').val() - parseFloat(discount_offer_minus);
			//}

		}
		
		//console.log('==>>'+delivery_charges);
		/*END FREE DELIVERY*/
		



        // alert(special_delivery_amount);    
        if (special_delivery_amount > 0) {
            $("#special_delivery_label").show();
            $('#special_delivery_label_text').html(special_delivery_amount);
        } else {
            $("#special_delivery_label").hide();
        }
		// speed delivery 
		// alert(speed_delivery_amount);
		if (speed_delivery_amount > 0) {
            $("#speed_delivery_label").show();
            $('#speed_delivery_label_text').html(speed_delivery_amount);
        } else {
            $("#speed_delivery_label").hide();
        }
		//donation_amount
		var donation_amount_value = $('#donation_amount_value').val();
		if (donation_amount_value > 0) {
            $("#donation_label").show();
            $('#donation_label_text').html(donation_amount_value);
        } else {
            $("#donation_label").hide();
        }
		//End amount
		
        if (delivery_charges > 0) {
            var dine_in = "<?php echo $dine_in; ?>";
            if (dine_in == "n")
                $('#delivery_label').show();

        }
        // alert(pickup_type);
		//console.log(pickup_type);
        if (pickup_type == "divein")
        {
           $(".left_price").hide();

           if (delivery_dive_in == '1')

           {

            if (parseFloat(delivery_tax) > 0)

            {
					//console.log("tax_5");
                    var deliver_tax_amount = calculatepercentage(totalsale, delivery_tax);

                }

            } else

            {


//console.log("tax_6");
var deliver_tax_amount = 0;

}

var delivery_charges = 0;
$('#delivery_charges').val(0);
} else if (pickup_type == "takein")

{
   //console.log('takein'+delivery_charges);

           // var fix_delivery_val = "<?php echo $merchant_detail['order_extra_charge']; ?>";
           var fix_delivery_val = "<?php echo $merchant_detail['delivery_charges']; ?>";
		   var mshop_id = "<?php echo $merchant_detail['id']; ?>";
		   var mshop_order_extra_charge = "<?php echo $merchant_detail['order_extra_charge']; ?>";
		   
           if (delivery_charges == 0  && free_delivery_check == 0) {
			//if (delivery_charges == 0) {	
                /*if (fix_delivery_val)
                    var delivery_charges = fix_delivery_val;
                else {
                    var additonal_delivery_charges = $('#additonal_delivery_charges').val();
                    if (additonal_delivery_charges)
                        var delivery_charges = additonal_delivery_charges;
                    else
                        var delivery_charges = 2.99;
                }*/
                /*Check new1*/
                if (fix_delivery_val){
                  console.log('fix_takein');
                  var delivery_charges = parseFloat(fix_delivery_val) - parseFloat(discount_offer_minus);
              }else {
                  console.log('notfix_takein');
                  var additonal_delivery_charges = $('#additonal_delivery_charges').val();
                  if (additonal_delivery_charges){
                   console.log('addfix_takein');
                   var delivery_charges = parseFloat(additonal_delivery_charges) - parseFloat(discount_offer_minus);
               }else{
                   console.log('notaddfix_takein'+delivery_charges);
							//var delivery_charges = 2.99  - parseFloat(discount_offer_minus);
							var delivery_charges = $('#delivery_charges').val() - parseFloat(discount_offer_minus);
							if(mshop_id == '1107'){ //for 3rd junction
								console.log('notaddfix_takein'+fix_delivery_val);
								var delivery_charges = mshop_order_extra_charge - parseFloat(discount_offer_minus);
							}
						}
					}
                    /*End new1*/

                }

            // alert(delivery_take_up);

            if (delivery_take_up == '1')

            {

                if (parseFloat(delivery_tax) > 0)

                {
				//	console.log("tax_7");
                var deliver_tax_amount = calculatepercentage(totalsale, delivery_tax);

            }

        } else

        {



            var deliver_tax_amount = 0;

        }

    }
        // alert(pickup_type);    
        // alert(delivery_charges);    

        var coupon_discount_amount = $('#coupon_discount_rate').val();
        // alert(coupon_discount_amount);
        // alert(totalsale);
        var coupon_discount = 0;

        var final_charge = parseFloat(totalsale) + parseFloat(delivery_charges) + parseFloat(special_delivery_amount)+ parseFloat(speed_delivery_amount) + parseFloat(donation_amount_value);
			//console.log("final_charge1==="+final_charge.toFixed(2));

        // alert(final_charge); 

        if (coupon_discount_amount > 0)

        {

            var coupon_min_value = $('#coupon_min_value').val();

            var coupon_max_value = $('#coupon_max_value').val();

            var coupon_type = $('#coupon_type').val();

            if ((parseFloat(totalsale) >= parseFloat(coupon_min_value)))

            {

                if ((parseFloat(totalsale) <= parseFloat(coupon_max_value)))

                {

                    if (coupon_type == "per")

                    {

                        var coupon_discount = calculatepercentage(totalsale, coupon_discount_amount);

                    } else

                    {

                        var coupon_discount = coupon_discount_amount;

                        // var coupon_discount=coupon_discount.toFixed(2);

                    }
                    // alert(coupon_discount);
                    if (coupon_discount > 0)

                    {

                        if (coupon_discount > totalsale)

                            var coupon_discount = totalsale;

                        var final_charge = parseFloat(totalsale) + parseFloat(delivery_charges) + parseFloat(special_delivery_amount)+ parseFloat(speed_delivery_amount)+ parseFloat(donation_amount_value) - parseFloat(coupon_discount);
		


                        $('#coupon_discount').val(coupon_discount);
                        $('.coupon_discount_amount_label').show();

                        $('.coupon_discount_amount_value').html(coupon_discount);

                    } else {
                        $('.coupon_discount_amount_label').hide();
                    }





                } else

                {

                    $('#coupon_message').show();

                    $('#coupon_message').html('Coupon is Only Valid up to order of RM ' + coupon_min_value);

                }



            } else

            {

                $('#coupon_message').show();

                $('#coupon_message').html('For that Coupon Min amount has to RM ' + coupon_min_value);

            }

			//postcode value
			var terr_amount = $("#postcode_delivery_charge_price").html();
			if(terr_amount != '0.00'){
				final_charge = parseFloat(final_charge) + parseFloat(terr_amount)
			}
			
            $('.final_amount_label').show();
            console.log("check1");
            $('.final_amount_value').html(parseFloat(final_charge).toFixed(2));
        }

        if (final_charge < 0)

            var final_charge = 0;

        $('#final_cart_amount').val(final_charge);

        var final_charge = parseFloat(final_charge).toFixed(2);
		console.log("final_charge==="+final_charge);

        if (delivery_charges > 0)

        {

            $('#delivery_cart_amount_label').html(parseFloat(delivery_charges).toFixed(2));

            $('.delivery_extra').show()

            $('.delivery_extra_value').html(parseFloat(delivery_charges).toFixed(2));



        }



        // $('#total_cart_value').html(totalsale.toFixed(2));

        var membership_discount_input = $('#membership_discount_input').val();



        if (membership_discount_input)

        {

            var substring = "%";

            if (membership_discount_input.includes(substring))

            {

                var discount = membership_discount_input.replace("%", "");



                // per discount 

                var membership_discount = calculatepercentage(totalsale, discount);



            } else

            {

                // fix discount 

                var membership_discount = membership_discount_input.replace("off", "");

                membership_discount = parseFloat(membership_discount);



            }

            // alert("member ship discount"+membership_discount);

            if (membership_discount)

            {

                $('.membership_discount_label').show();

                $('.membership_discount_value').html(membership_discount);


                $('.final_amount_label').show();

                var final_charge = (parseFloat(totalsale) + parseFloat(delivery_charges)) + parseFloat(special_delivery_amount)+ parseFloat(speed_delivery_amount)+ parseFloat(donation_amount_value) - parseFloat(membership_discount) - parseFloat(coupon_discount);

				//postcode price
				var terr_amount = $("#postcode_delivery_charge_price").html();
				if(terr_amount != '0.00'){
					final_charge = parseFloat(final_charge) + parseFloat(terr_amount)
				}
				console.log("check2");
				$('.final_amount_value').html(parseFloat(final_charge).toFixed(2));
            }
        }
        if (final_charge < 0)
            var final_charge = 0;

        if (parseFloat(sst_tax) > 0)
        {
            var sst_amount = calculatepercentage(totalsale, sst_tax);
            $('.sst_amount_label').show();
            $('.sst_amount_value').html(parseFloat(sst_amount).toFixed(2));
            var final_charge = (parseFloat(final_charge) + parseFloat(sst_amount));
			//console.log("sst_tax==="+final_charge.toFixed(2));
        }
        // alert(deliver_tax_amount);
        if (parseFloat(deliver_tax_amount) > 0)
        {
			//console.log("1");
			//console.log(pickup_type);    
            $('.delivery_tax_amount_label').show();
            $('.delivery_tax_amount_value').html(parseFloat(deliver_tax_amount).toFixed(2));
            var final_charge = (parseFloat(final_charge) + parseFloat(deliver_tax_amount));
			//console.log("deliver_tax_amount==="+final_charge.toFixed(2));
        } else
        {
            $('.delivery_tax_amount_label').hide();
            $('.delivery_tax_amount_value').html('');
        }

		//postcode value
       var terr_amount = $("#postcode_delivery_charge_price").html();
       if(terr_amount != '0.00'){
        final_charge = parseFloat(final_charge) + parseFloat(terr_amount);
		//console.log("terr_amount==="+final_charge.toFixed(2));
    }

    $('.final_amount_label').show();
    $('#deliver_tax_amount').val(deliver_tax_amount);
    $('#final_cart_amount_label').html(final_charge);
	console.log('chk31##');
   // console.log("check3==="+final_charge.toFixed(2));
    $('.final_amount_value').html(parseFloat(final_charge).toFixed(2));
	$('.total-cart-wrapper').hide();
	$('.empty_cart').hide();
	$(".empty-text-box").show();
	if(totalsale.toFixed(2) != '0.00'){
		$(".empty-text-box").hide();
		$('#total_cart_amount_label_show').show();
		$('.total-cart-wrapper').show();
		$('.empty_cart').show();
	}
    
    $('#total_cart_value').html(totalsale.toFixed(2));
	
	/*$0.50 discount on internet bankinf free */
	var actual_payment_mode1 = $("#actual_payment_mode").val();
	if(actual_payment_mode1 == 'internet_banking_free'){
		var internet_price_value1 = $(".internet_free_modal_amount").html();
		var chk_price = internet_price_value1 - 0.50;
		$(".internet_free_modal_amount").html(parseFloat(chk_price).toFixed(2));
	}
	
}
function UpdateTotal(id, uprice = 0) {
   var qty = $("#" + id + "_test_athy").val();
   var extra_val = $("#extra_child_" + id).val();
   if (extra_val == '')
    var extra_val = 0;
var p_t = parseFloat(uprice) + parseFloat(extra_val);
var total = parseFloat(Number(qty * p_t).toFixed(2));
        // alert(qty);
        $("#" + id + "_cat_total").val(total);
        $("." + id + "_cat_total").html(parseFloat(total).toFixed(2));

        /* update cart qty data to session - 23/12/20*/
        var cartData = {};
        cartData['type'] = 'qty_update';
        cartData['id'] = id;
        cartData['total'] = total;
        cartData['qty'] = qty;
        jQuery.post('<?php echo $site_url; ?>/ajaxcartresponse.php', cartData, function (result) {
			//var response = jQuery.parseJSON(result);
			//console.log(result);
		});
        /*End cart qty update to session - 23/12/20 */


        totalcart();
    }
    function UpdateTotalCart(id = 0) {
        // var qty = $("#"+id+"_test_athy").val();
        var qty = $("#other_qty_" + id).val();
        var unitprize = $("#other_product_price_" + id).val();
        var total = parseFloat(Number(qty * unitprize).toFixed(2));
        $("#" + id + "_cat_total").val(total);
console.log("1");
		$("#" + id + "_cat_total_span").html(total);
        totalcart();
    }
    // console.log("dsfgsdf", $('.section-dropdown'));
    $('.section-dropdown').on('change', function(e) {
        e.preventDefault();
        let $this = $(this);
        let sectionId = $this.val();
        let $tableDropDown = $('.section-tables');
        let url = $this.attr('data-table-list-url') + '?section_id=' + sectionId;
        let promise = $.ajax({
            url: url,
            type: 'get',
            dataType: 'JSON'
        });
        promise.done(function(response) {
            if (response.length < 1) {
                let option = '<option>No Table Found</option>';
                $tableDropDown.html(option);
                return;
            }
            let option = "";
            response.forEach(function(item, index) {
                option += '<option value="' + item.id + '">' + item.name + '</option>';
            });

            $tableDropDown.html(option);
        });
    });
    /* function for the repeat last order - 25/12/20 */
    function repeatlastOrder(merchant_id,merchant_mbl_id){  
      var logged_user_id = '<?php echo $_SESSION["user_id"];?>';
      var no_order_msg = '<?php echo $language["no_order_sentence"]?>';
      var cartData = {};
      cartData['type'] = 'repeat_last_order';
      cartData['logged_user_id'] = logged_user_id;
      cartData['merchant_id'] = merchant_id;
      cartData['merchant_mbl_id'] = merchant_mbl_id;
      cartData['no_order_msg'] = no_order_msg;

      jQuery.post('<?php echo $site_url; ?>/ajaxcartresponse.php', cartData, function (result) {
       $("#cartsection").html(result);
       totalcart();
   });
      totalcart();
  }
  /* end - repeat last order - 25/12/20 */
  /* empty cart  data to session - 23/12/20*/
  $(".empty_cart").click(function(){
		var cartData = {};
		cartData['type'] = 'empty_cart';
		$('.total-cart-wrapper').hide();
		//cartData['id'] = id;
		jQuery.post('<?php echo $site_url; ?>/ajaxcartresponse.php', cartData, function (result) {
			//var response = jQuery.parseJSON(result);
			//$("#ajaxresDiv").find('tr').remove();
			//$("#test").find('tr').remove();
			$(".producttr").remove();
			$(".text_add_cart").show();
			
			totalcart();
		});
		totalcart();
  });
 /* function EmptyCart() {
      var cartData = {};
      cartData['type'] = 'empty_cart';
		//cartData['id'] = id;
		jQuery.post('/ajaxcartresponse.php', cartData, function (result) {
			//var response = jQuery.parseJSON(result);
			$("#ajaxresDiv").find('tr').remove();
			$("#test").find('tr').remove();
			totalcart();
		});
		totalcart();
	}   */

</script>

<script>
    /**/
    $(function() {
        $(window).on('load', function() {
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
    $(".skip").click(function() {
        $('#newuser_model').modal('hide');
        $('#newmodel_check').modal('hide');
        $('#forgot_setup').modal('hide');
        $('#PasswordModel').modal('hide');
        // alert(show_pop);
        // nextstep();
    });

    // $("#mapSearch").focusout(function(){

    // var address=$('#mapSearch').val();

    // });

    $("#apply_coupon").click(function() {
        $('#apply_coupon').hide();
        var s_flag = true;
        var product_qty = $('.product_qty').val();
        var number = $('#mobile_number').val();
        // alert(number);
        if ((product_qty == null) || (product_qty == '')) {
           $('#show_msg').html("<?php echo $language['without_product_coupon']; ?>");
           $('#AlerModel').modal('show');
           var s_flag = false;
           $('#apply_coupon').show();
           return false;
       }
       if (number.length >= 9 && number.length <= 12 && (number[0] == 1 || number[0] == 6)) {

       } else
       {
        $('#mobile_number').focus();
        var s_flag = false;
    }
    var coupon = $('#coupon_code').val();
    var sum = 0;
    if (coupon == '')
    {
        $('#coupon_code').focus();
        var s_flag = false;
    }
    if (s_flag)
    {
        var inps = document.getElementsByName('p_total[]');
        for (var i = 0; i < inps.length; i++) {
            var inp = inps[i];
            sum += parseFloat(inp.value);
        }
        var merchant_id = "<?php echo $merchant_detail['id']; ?>";
        var total_amount = sum;
            // alert(merchant_id);
            $.ajax({
                url: '<?php echo $site_url; ?>/apply_coupon.php',
                type: 'POST',
                data: {
                    coupon: coupon,
                    user: userID,
                    amount: total_amount,
                    user_mobile: number,
                    merchant_id: merchant_id
                },
                dataType: 'json',
                success: function(response) {
                    var result = JSON.parse(JSON.stringify(response));
                    if (result.status) {
                        $('.coupon_discount_amount_label').show();
                        $('.coupon_discount_amount_value').html(result.price);
                        $('#coupon_id').val(result.id);
                        $('#coupon_min_value').val(result.min);
                        $('#coupon_discount_rate').val(result.price);
                        $('#coupon_discount_amount').val(result.price);
                        $('#coupon_discount').val(result.price);
                        $('#coupon_max_value').val(result.max);
                        $('#coupon_type').val(result.type);
                        totalcart();
                    } else {
                        $('#coupon_code').val('');
                        $('#apply_coupon').show();
                    }
                    $('#coupon_message').html(result.data);
                    $('#coupon_message').show();
                }
            });
        }
    });




    $(document).ready(function(){




     $(".internet_banking_fpx").click(function(){

		//console.log('fpx_data');
		//$("#order_place").attr('action','temp_order_save.php');
		//$(".internet_banking_fpx").
		var product_qty = $('.product_qty').val();
		var table_type = $('#table_type').val();
		var number = $('#mobile_number').val();
		var pickup_type = $('#pickup_type').val();
		var shop_close_time = $('#shop_close_time').val();
		var total_rebate = 0;
		var total_amount = 0;
		var s_flag = true;
		if ((product_qty == null) || (product_qty == '')) {
			$('#show_msg').html("<?php echo $language['no_product_added']; ?>");
			$('#AlerModel').modal('show');
			var s_flag = false;
			return false;
		}
		if (number.length >= 9 && number.length <= 12 && (number[0] == 1 || number[0] == 6 || number[0] == 0)) {
			$('#mobile_error').hide();
			if (number[0] == 0) {
				number = number.slice(1);
			}
		} else {
			$('#mobile_number').focus();
			$('#mobile_error').show();
			var s_flag = false;
		}
		/*var postcode_main_div = $('.postcode_main_div').val();
		$(".postcode_main_div").removeAttr('css');
		if(postcode_main_div == ''){
			$(".postcode_main_div").focus();
			$(".postcode_main_div").css('border','1px solid red');
			return false;
		}*/if ($('#table_type').prop('required')) {
			if (table_type == '') {
				$('#table_type').focus();
				var s_flag = false;
			}

		}
		if (pickup_type == "takein") {
			var mapSearch = $('#mapSearch').val();

			if (mapSearch == '') {
				$('#location_error').show();
				$('#mapSearch').focus();
				var s_flag = false;
			} else {
				$('#location_error').hide();
			}
		} else {
			$('#location_error').hide();
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
                //if (delivery_charges > 0)
                var order_min_charge = "<?php echo $merchant_detail['order_min_charge']; ?>";
                //else
                  //  var order_min_charge = 0;

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
            } else {
                return false;
            }
		//console.log('fpx_data___before');
		var rem_amount = $("#rem_amount").val();
		
		
		


		if(rem_amount != 0){
			var final_amount_value = rem_amount;
		}else{
			var final_amount_value = $(".final_amount_value").html();
		}
		
		$("#hidden_final_cart_price").val(final_amount_value);
		var str = $("#order_place").serializeArray();
		$(".internet_banking_fpx").prop('disabled', true);
		$("#Amount").val(final_amount_value);
		//return false;
		$.ajax({  
			type: "POST",  
			url: "<?php echo $site_url; ?>/temp_order_save.php",  
			data: str,  
			success: function(value) { 
				
				var json = $.parseJSON(value);
				//return false;
				//$("#paymentid").val();
				$("#RefNo").val(json.refNo);
				
				$("#ProdDesc").val(json.proddesc);
				$("#UserName").val(json.username);
				$("#UserEmail").val(json.useremail);
				$("#UserContact").val(json.usercontact);
				$("#Remark").val(json.remark_ipay88);
				$("#Signature").val(json.signature);
				//sleep(10);
				$("#ipay88_form").submit();
				/*setTimeout(
					  function() 
					  {
						//do something special
							$("#ipay88_form").submit();
                       }, 500);*/
                   }
               });
		return false;
	});
});

$(document).ready(function(){
		//$("#postcode").css("background","#FFF url(ajax-loader.gif) no-repeat 165px");
		$("#postcode").keyup(function(){
			var delivery_price = $(this).attr('delivery_price');
			
			var merchant_territory_hidden_id =  $(this).attr('m_terr_id');
			//console.log(merchant_territory_hidden_id);
			$.ajax({
               type: "POST",
               url: "<?php echo $site_url; ?>/ajaxcartresponse.php",
               data:'type=searchpostcode&delivery_price='+delivery_price+'&merchant_territory_hidden_id='+merchant_territory_hidden_id+'&keyword='+$(this).val(),
               beforeSend: function(){
                $("#postcode").css("background","#FFF url(ajax-loader.gif) no-repeat 165px");
            },
            success: function(data){
                $("#suggesstion-box").show();
                $("#suggesstion-box").html(data);
                $("#postcode").css("background","#FFF");
            }
        });
		});
		
		//$(".postcode_main_div").delegate(".liclass", "click", function() {
          $(".postcode_main_div").change(function() {
           $("#postcode_delivery_charge").show();
           var terr_id = $(this).val();


           var text_html = $("#liid_"+terr_id).html();
			var price = $(this).find('option:selected').attr('price_optn');//$('option:selected', this).attr('price');//$(this).attr('price');
			var terr_id_last_order = '<?php echo $terr_id_last_order;?>';
			//console.log(terr_id_last_order+"-------"+terr_id);
			var free_delivery_status = $("#free_delivery_status").val();
			//console.log(free_delivery_status);
			if(free_delivery_status == 1){
				if(terr_id_last_order == terr_id){
					//console.log('#same');
					$("#territory_hidden_id").val(terr_id);
					$("#delivery_charges").val('0.00');
					$("#delivery_label").show();
					$("#order_extra_label").html('0.00');
					$("#order_extra_charge").val('0.00');
					
					$('#additonal_delivery_charges').val('0.00');
					$("#free_delivery_status").val(1);
					
					$("#postcode_delivery_charge_price").html('0.00');
					$("#postcode_delivery_charge_hidden_price").val('0.00');
					totalcart();
				}
			}else{
				if(terr_id != 0){
				//console.log('#1');
				$("#free_delivery_status").val(0);

				var additonal_delivery_charges = $('#additonal_delivery_charges').val();
				$('#additonal_delivery_charges').val(additonal_delivery_charges);
				//$("#postcode").val(text_html);
				$("#territory_hidden_id").val(terr_id);
				$("#postcode_delivery_charge_price").html(price);
				$("#postcode_delivery_charge_hidden_price").val(price);
				$("#suggesstion-box").hide();
				var d_charges = "<?php echo $merchant_detail['order_extra_charge'];?>";
				$("#delivery_label").show();
				
				if(d_charges != ''){
					//console.log(d_charges);
					$("#delivery_charges").val(d_charges);
					$("#order_extra_label").html(d_charges);
					$("#order_extra_charge").val(d_charges);
				}else{
					$("#delivery_charges").val('2.99');
					$("#order_extra_label").html('2.99');
					$("#order_extra_charge").val('2.99');
				}
			}else{
				//console.log('#2');
				$("#free_delivery_status").val(0);

				var additonal_delivery_charges = $('#additonal_delivery_charges').val();
				$('#additonal_delivery_charges').val(additonal_delivery_charges);
				
				//$("#postcode").val('');
				$("#territory_hidden_id").val(terr_id);
				$("#postcode_delivery_charge_price").html('0.00');
				$("#postcode_delivery_charge_hidden_price").val('0.00');
				$("#suggesstion-box").hide();
			}
			
			
       }
       totalcart();

   });


          $(".image-form").on("submit", function() {
              var orderid = $(this).attr('orderid');  
              console.log(orderid);
              $("#msg").html('<div class="alert alert-info"><i class="fa fa-spin fa-spinner"></i> Please wait...!</div>');
              var formData = new FormData(this);
		//formData.append('file', this.files[0]);
		formData.append('orderid', orderid);
		var payment_proof = $(".payment_proof").val();
		$(".payment_proof").css('border','');
		if( payment_proof == ''){
			$(".payment_proof").css('border','1px solid red');
			return false;
		}else{
			$(".btn_proof_upload").hide();
			$(".err_payment_proof").html('Please wait...');
			$.ajax({
             type: "POST",
             url: "<?php echo $site_url; ?>/action_image_ajax.php",
             data: formData,
			  //data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
			  contentType: false, // The content type used when sending data to the server.
			  cache: false, // To unable request pages to be cached
			  processData: false, // To send DOMDocument or non processed data file it is set to false
			  success: function(data) {
              $(".btn_proof_upload").show();
              $(".err_payment_proof").hide();
              var json = $.parseJSON(data);
				  //console.log(json.image);
				  $("#payment_proof_name").val(json.image);
				  var img_link = 'https://www.koofamilies.com/upload/'+json.image;
				  $("#main_image_div").hide();
					$("#main_image_namediv").css('padding-bottom','40px');
				  $("#main_image_namediv").html('<a class="fancybox" target="_blank" rel="" href="'+img_link+'" style="color:white;background-color:darkgreen;padding: 10px;">Payment Proof submitted </a><a href="javascript:void(0)" class="delete_paymentproof" style="color:#000;padding: 10px;background-color: darkgreen;"><i class="fa fa-trash" style="color:white;"> </i></a>');
				  $("#main_image_namediv").show();
				  
					//location.reload(true);
             },
             error: function(data) {
                $("#msg").html(
                  '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> There is some thing wrong.</div>'
                  );
            }
        });
		}

	});



          $(".image-form").delegate(".delete_paymentproof", "click", function() {
	//$(".delete_paymentproof").click(function(){
		var orderid = $(this).attr('orderid');
		var cnfrmDelete = confirm("Are You Sure want to delete this payment proof?");
		if(cnfrmDelete==true){
			$("#main_image_namediv").hide();
			$("#main_image_div").show();
			$("#payment_proof_name").val('');
		}
	});



      });

jQuery(document).on("click", ".browse", function() {
    var file = $(this)
    .parent()
    .parent()
    .parent()
    .find(".file");
    file.trigger("click");
});
$('input[type="file"]').change(function(e) {
   var fileName = e.target.files[0].name;
   $(".payment_proof").val(fileName);
});
</script>

<script >

//
	$(".cart_wrapper").delegate(".moreless-button", "click", function() {
	//$('.moreless-button').click(function() {
		var product_id  = $(this).attr('product_id');
		$('#moretext_'+product_id).slideToggle();
		
		var lang_readmore = "<?php echo $language['read_more1'];?>";
		var lang_readless = "<?php echo $language['read_less1'];?>";

		if ($('.moreless-button_'+product_id).text() == lang_readmore) {
			$(this).text(lang_readless)
		} else {
			$(this).text(lang_readmore)
		}
	});

 $(document).ready(function(){
	
	$(".search_button").click(function(){
		var search_bar = $("#search_bar").val();
		var viewFile = $(this).attr("viewFile");
		var dataRedirectLink  = $(this).attr("dataRedirectLink");
		var merchant_id = $(this).attr("merchant_id");
		var prduct_qty = $(this).attr("prduct_qty");
		var price_hike = $(this).attr("price_hike");
		
		$(".fullmenu_option").show();
		if(search_bar != ''){
			$.ajax({
				crossDomain: true,
				url: viewFile+"?prduct_qty="+prduct_qty+"&id="+merchant_id+"&search_bar="+search_bar+"&redirect="+dataRedirectLink+"&price_hike="+price_hike,
				context: document.body
			}).done(function(response) {
			  $('.search_response').html(response);
			  $('.parent-category-menu').hide();
			  $('.subcategory_maindiv').hide();
			  $('.productsDiv').removeClass('col-8 col-sm-9');
			  $('.productsDiv').addClass('col-12 col-sm-12');
			  
			  //$('.master_category_filter:first-child').trigger('click');
			});
		}		
	});
	 
	 
	$(function () {
		$('[data-toggle="tooltip"]').tooltip()
	}) 
	
	
	$(".del_type").click(function(){
		var del_type = $(this).attr('del_type');
		$(".later_box").hide();
		$("#od_delivery_date").val('');
		$("#od_delivery_time").val('');
		
		if(del_type == 'later_type'){
			$("#od_delivery_date").val($("#od_delivery_date option:first").val());
			$("#od_delivery_time").val($("#od_delivery_time option:first").val());
			$(".later_box").show();
		}
	});
 
});

</script>
<?php if(count($_SESSION['AjaxCartResponse'][$cart_merchant_id])> 0){?>
    <script>
        $(document).ready(function(){

         totalcart();
     });
 </script>
<?php }?>
<script src="<?php echo $site_url; ?>/js/recorder.js" defer></script>
<script src="<?php echo $site_url; ?>/js/sketch_new.js" defer></script>
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
</script>    