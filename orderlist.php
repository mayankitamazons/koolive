<?php
   include("config.php");
  // ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL); 
	$me="orderlist";
	if($_SESSION['new_order']=="y")
	{
	   $new_order="y";
	}
	else
	{
		$new_order="n";
	}
	
		
if(isset($_GET['data'])&&$_GET['data']=='deleteRecord'){
	$orderid = $_GET['orderid'];
	$sql_proof = "UPDATE `order_list` SET `payment_proof` = '' WHERE `order_list`.`id` = ".$orderid;
	$result_proof = mysqli_query($conn,$sql_proof);   
	if($result_proof){echo true;}else{die();}
}


	function ceiling($number, $significance = 1)
	{
		return ( is_numeric($number) && is_numeric($significance) ) ? (ceil(round($number/$significance))*$significance) : false;
	}
   if(isset($_GET['did']))
	{
		$did=$_GET['did'];   
		if($_GET['ot']=="y")
		{
			// echo "UPDATE `users` SET `otp_verified` = 'y' WHERE `users`.`id` ='$did'";
			// die;     
			mysqli_query($conn,"UPDATE `users` SET `otp_verified` = 'y' WHERE `users`.`id` ='$did'");
		}  
		include_once('dlogin.php');
		
	}  
   // $new_order="y";
   // $show_alert="y";
   // print_R($_SESSION);
  // die;   
  // function checkSession(){
  // $conn = $GLOBALS['conn'];
  // $session = $_COOKIE['session_id']; 
  // $rw = mysqli_fetch_row(mysqli_query($conn, "SELECT id FROM users WHERE session = '$session'"));
  // if($rw > 0){
    // return true;
  // }else{
    // return false;
  // }
// }
  // print_R($_SESSION);
  // die;
  //echo "==".$_SESSION['tmp_login'];
  //unset($_SESSION['tmp_login']);
  
  
  //https://www.koofamilies.com/orderlist.php?payid=12085&vs=6f493a2d2155f8e45b670d8b3b0d938f
  
  if($_GET['payid']){
	  session_start();
	  $_SESSION['tmp_login'] = $_GET['payid'];
	  $merchant_id = $_SESSION['AjaxCartResponse']['merchant_id'];
	  unset($_SESSION['AjaxCartResponse'][$merchant_id]);  
					
	  $limit="0,1";	
	  //echo $_GET['payid']."==".$_SESSION['tmp_login'];exit;
	  
  }
  //echo "==".$_SESSION['tmp_login'];
   if(isset($_SESSION['tmp_login']))
   {
	 $user_id=$_SESSION['tmp_login']; 
      $limit="0,1";	 
   }
   else
   {
	   $user_id=$_SESSION['login'];
	   $limit="0,50";
   }
   if($_SESSION['login'])
   {
	   $user_id=$_SESSION['login'];
   }
  
     $query="SELECT  order_list.id as order_id,order_list.order_extra_charge as od_extra_charge,order_list.*, sections.name as section_name,m.id as merchant_id,m.name as merchant_name,m.sst_rate as m_sst_rate,m.mobile_number as merchant_mobile_number,m.special_coin_name as merchant_special_coin_name,m.*,u.id as user_id,u.* FROM order_list left join 
	 sections on order_list.section_type = sections.id inner join users as m on m.id=order_list.merchant_id  left 
	join users as u on u.mobile_number=order_list.user_mobile 
	 WHERE order_list.user_id ='".$user_id."' ORDER BY `created_on` DESC LIMIT $limit";
	 //echo $query;
// die;
     $total_rows = mysqli_query($conn,$query);
	  $uq="SELECT  order_list.*,m.mobile_number as merchant_mobile,m.name as merchant_name,u.user_refferal_code FROM order_list inner join users as m on m.id=order_list.merchant_id left 
	join users as u on u.mobile_number=order_list.user_mobile 
	WHERE order_list.user_id ='$user_id' ORDER BY `created_on` DESC limit 0,1";
	 // die;     
	 $user_order = mysqli_fetch_assoc(mysqli_query($conn,$uq)); 
	 $totalcount=count($user_order);
	 
	 
	 /* Start riders array */
	 $riders_query = "select * from tbl_riders where r_status = 1";
	 $ridersFetch = mysqli_query($conn,$riders_query);
	 $ridersArray = array();
	 while($rider_rows = mysqli_fetch_array($ridersFetch)){
		 $ridersArray[$rider_rows['r_id']]['name'] = $rider_rows['r_name'];
		 $ridersArray[$rider_rows['r_id']]['r_mobile_number'] = $rider_rows['r_mobile_number'];
		 $ridersArray[$rider_rows['r_id']]['r_info'] = $rider_rows['r_info'];
		 $ridersArray[$rider_rows['r_id']]['r_image'] = $rider_rows['r_image'];
		 $ridersArray[$rider_rows['r_id']]['r_vehicle_number'] = $rider_rows['r_vehicle_number'];
		 $ridersArray[$rider_rows['r_id']]['r_live_location'] = $rider_rows['r_live_location'];
	 }
	 /* END riders array */
	 
	 
	 // print_R($user_order);
	 // die;
	  $total_rebate_amount=$user_order['total_rebate_amount'];
	  $total_rebate_amount=number_format($total_rebate_amount,2);
	  $membership_applicable=$user_order['membership_applicable'];
	  $membership_discount_input=$user_order['membership_discount_input'];
	  $last_order_merchant_id=$user_order['merchant_id'];
	  $last_order_id=$user_order['id'];
	  $rebate_credited=$user_order['rebate_credited'];
	  $wallet=$user_order['wallet'];
	
	 $check_number=$user_order['user_mobile'];
	 $check_number=str_replace("60","",$check_number);
	 $order_id=$user_order['id'];
	 $section_saved=$user_order['section_saved'];
	 $show_alert=$user_order['show_alert'];
	 if($user_order)
	 {
		 $newuser=$user_order['newuser'];
	 }
	 else
	 {
		 $newuser='n';
	 }
	 if($user_order['user_id'])
	   {
		    $user_id=$user_order['user_id'];
	    $uinfo = mysqli_query($conn,"select  * from users where id='$user_id'");
        $user_data = mysqli_fetch_array($uinfo);
		// print_R($user_data);
		// die;
		$password_created=$user_data['password_created'];
		$otp_verified=$user_data['otp_verified'];
		$user_password=$user_data['password'];
	   }
	  if(isset($_POST['method'])){
	$user = $_POST['user'];
	$payment = $_POST['payment'];
	$sql = "SELECT  * FROM payments WHERE type='$payment' and user = '$user'";
	$result = mysqli_query($conn, $sql);
	$payment = mysqli_fetch_assoc($result);
	$res = array(
		"name" => $payment['name'],
		"mobile" => $payment['mobile'],
		"remark" => $payment['remark'],
		"qr_code" => $payment['qr_code']
	);
	echo json_encode($res);
	exit();
}

	if($user_order['merchant_id'])
   {
	   $merchant_id=$user_order['merchant_id'];
	    $info = mysqli_query($conn,"select  * from users where id='$merchant_id'");
		$online_pay=$user_order['online_pay'];
		$payment_alert=$user_order['payment_alert'];
		$prepaid=$user_order['prepaid'];
        $merchant_data = mysqli_fetch_array($info);
		if($online_pay && $prepaid=="n")
		{
			$cash_check = $merchant_data['cash_check'];
			$credit_check = $merchant_data['credit_check'];
			$wallet_check = $merchant_data['wallet_check'];
			$boost_check = $merchant_data['boost_check'];
			$grab_check = $merchant_data['grab_check'];
			$wechat_check = $merchant_data['wechat_check'];
			$touch_check = $merchant_data['touch_check'];
			$fpx_check = $merchant_data['fpx_check'];
			$cash_image = "available";
			$credit_image = "available";
			$wallet_image = "available";
			$boost_image = "available";
			$grab_image = "available";
			$wechat_image = "available";
			$touch_image = "available";
			$fpx_image = "available";
			if($cash_check == "0")
				$cash_image = "unavailable";
			if($credit_check == "0")
				$credit_image = "unavailable";
			if($wallet_check == "0")
				$wallet_image = "unavailable";
			if($boost_check == "0")
				$boost_image = "unavailable";
			if($grab_check == "0")
				$grab_image = "unavailable";
			if($wechat_check == "0")
				$wechat_image = "unavailable";
			if($touch_check == "0")
				$touch_image = "unavailable";
			if($fpx_check == "0")
				$fpx_image = "unavailable";
		}
		// echo "select * from Offers_statement where merchant_id ='$merchant_id'";
        $offerquery = mysqli_query($conn,"select   * from offers_statement  where merchant_id ='$merchant_id'");
        $offerdata = mysqli_fetch_array($offerquery);
		// print_R($offerdata);
		// die;
   }
	 if($show_alert=="y" && $section_saved=="y")
	 {
		$res1= mysqli_query($conn,"UPDATE `order_list` set show_alert ='n' WHERE id ='$order_id'");
	     
	 }
	 // $show_alert="y";
	$first_section_id=$user_order['section_type'];
	$first_table_id=$user_order['table_type'];
	  $merchant_id=$user_order['merchant_id'];
	    $open_order_id=$user_order['id'];
	 if($first_section_id=="" || $first_table_id=="")
	 {
		 if($section_saved=="n")
		 {
		 if($totalcount>0)
		 {
		include_once('php/Section.php');
		// include_once('php/SectionTable.php');

		$sectionsObj = new Section($conn);
		// $sectionTablesObj = new SectionTable($conn);
		$sectionsFilter = [
		  'user_id' => isset($merchant_id) ? $merchant_id : null,
		  'status' => true
		];
		
		$sectionsList = $sectionsObj->getList($sectionsFilter);
		
		 
		 $open_order_id=$user_order['id'];
		 }
		 if($new_order=="y")
		 $show_pop="y";
		else
		 $show_pop="n";
		// if(($first_section_id=='' && $merchant_data['section_on_orderlist']=="y") || ($first_table_id=='' && $merchant_data['table_on_orderlist']=="y")){
		 // $show_pop="y";
		// }
		// else
		// {
			// $show_pop='n';
		// }
		 }
		 else
		 {
			 $show_pop="n";
		 }
	 }
	  if($new_order=="y")
		 $show_pop="y";
	 $created_new =$user_order['created_on'];
      $status1 =$user_order['status'];
   	$_SESSION['mm_id'] = "";
   	$_SESSION['o_id'] = "";
	// echo $show_pop;
	// die;
	// print_R($user_order);
	// die;
	$last_order_utc=$user_order['created_timestamp'];
	  $last_order_utc=strtotime($last_order_utc);

	 $current_date=date('Y-m-d H:i:s');
	$current_utc=strtotime($current_date);
	 $diff=abs($current_utc-$last_order_utc);
	 $years = floor($diff / (365*60*60*24));  
  
$months = floor(($diff - $years * 365*60*60*24) 
                               / (30*60*60*24));  
 
 $days = floor(($diff - $years * 365*60*60*24 -  
             $months*30*60*60*24)/ (60*60*24));   
			 // die;
	if($days>1)
	{
		 $show_pop="n";
		 if($section_saved=="n")
		 $res1= mysqli_query($conn,"UPDATE `order_list` set show_alert ='n',section_saved='y' WHERE id ='$order_id'");
	}	

   	?>
<!DOCTYPE html>
<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">
   <head><meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
      <link rel="stylesheet" href="./css/font-awesome.min.css">
	<link rel="stylesheet" href="./css/ordercss.css">
     <style type="text/css">
			
@media screen and (max-width:767px){
.navbar-nav>li>a{padding:0 0.33333em !important; } 
}

#divInner{
  left: 0;
  position: sticky;
}

#divOuter{
  
  overflow:hidden
}
 #forgot_partitioned {
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
-webkit-box-shadow: inset 0px 100px 0px 0px rgba(255, 255, 255, 0.5);
box-shadow: inset 0px 100px 0px 0px rgba(255, 255, 255, 0.5);
} 
#forgot_divInner{
  left: 0;
  position: sticky;
}
#forgot_divOuter{
  width:190px; 
  overflow:hidden
}
				</style>
      <style>
		  .test_product{
		        padding-right: 125px!important;
		    }
		td.products_namess {
            text-transform: lowercase;
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
         td {
            border-right: 2px solid #efefef;
		}
		th {
            border-right: 2px solid #efefef;
        }
        tr.br_bk {
            border-bottom: 3px double #000;
        }
        .table tbody + tbody {
            border: none!important;
        }
         tr.red {
         color: red;
         }
         label.dp_lab {
         cursor: pointer;
         }
         .pagination {
         display: inline-block;
         padding-left: 0;
         margin: 20px 0;
         border-radius: 4px;
         }
         .pagination>li {
         display: inline;
         }
         .pagination>li:first-child>a, .pagination>li:first-child>span {
         margin-left: 0;
         border-top-left-radius: 4px;
         border-bottom-left-radius: 4px;
         }
         .pagination>li:last-child>a, .pagination>li:last-child>span {
         border-top-right-radius: 4px;
         border-bottom-right-radius: 4px;
         }
         .pagination>li>a, .pagination>li>span {
         position: relative;
         float: left;
         padding: 6px 12px;
         margin-left: -1px;
         line-height: 1.42857143;
         color: #337ab7;
         text-decoration: none;
         background-color: #fff;
         border: 1px solid #ddd;
         }
         .pagination a {
         text-decoration: none !important;
         }
         .pagination>.active>a, .pagination>.active>a:focus, .pagination>.active>a:hover, .pagination>.active>span, .pagination>.active>span:focus, .pagination>.active>span:hover {
         z-index: 3;
         color: #fff;
         cursor: default;
         background-color: #337ab7;
         border-color: #337ab7;
         }
         .product_name{
		     width: 100%;
		 }
		  .total_order{
		 font-weight:bold;
		 }
		 .gr{
		     color:green;
		 }
		 .or{
             color: orange;
         }
		 .red.gr{
		     color:green;
		 }
.location_head{
width:200px;
}
.new_tablee {
    width: 200px!important;
    display: block;
    word-break: break-word;
}
td.test_productss {
    white-space: nowrap;
    /*width: 200px!important;*/
    display: block;
}
th.product_name.test_product {
    width: 200px!important;
}
@media only screen and (max-width: 600px) and (min-width: 300px){
table.table.table-striped {
    white-space: unset!important;
}
}
#SectionModel .btn.btn-secondary{
  background-color: #e4e7ea;
  border-color: #e4e7ea;
  color: #555;
}
#SectionModel .btn.btn-secondary.checkbox-checked.active{
  background-color: #727b84 !important;
  border-color: #727b84 !important;
  color: #fff !important;
}
#SectionModel .modal-body{
  max-height: 70vh;
  overflow-y: auto;
}
 @media (max-width: 767px) {
  #SectionModel .modal-body{
    max-height: 60vh;
    overflow-y: auto;
  }
   #SectionModel > .modal-dialog{
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
  
#SectionModel .modal-body{
  max-height: 70vh;
  overflow-y: auto;
}
 @media (max-width: 767px) {
  #SectionModel .modal-body{
    max-height: 60vh;
    overflow-y: auto;
  }
  #SectionModel{
	  max-width:400px;
  }
}

      </style>
	  <style>
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
    margin-bottom: 10px;
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

	</style>
	         
      <?php include("includes1/headorder.php"); ?>   
      <?php // include("mpush.php"); ?>
	  <link rel="manifest" id="my-manifest-placeholder">
	   <meta name="theme-color" content="#317EFB"/>
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
      <main class="main-wrapper clearfix orderlistdetail-wrapper" style="min-height: 522px;">
         <div class="row" id="main-content" style="padding-top:25px">
         <div class="well">.
		  <!-- <a style="text-align:center;width:100%;" href="https://play.google.com/store/apps/details?id=com.koobigfamilies.app" target="blank">
					<img style="max-width:140px;" src="google.png" alt=""></a> -->
			
			
                    <div class="total_rat_abt">  
					 <a class="col-md-2 btn btn-primary status showLoader6" style="background:red;color:black !important;" href="index.php?vs=<?=md5(rand()) ?>"><?php echo $language['more_shops']; ?></a>
					<?php  if($user_order){?>
					<?php 
					$lastshop_link  = "";
					$lastshop_link = $site_url.'/view_merchant.php?vs='.md5(rand()).'&sid='.$user_order['merchant_mobile'].'&oid='.$last_order_id;?>
			
					 <a class="btn btn-primary" href="<?php echo $lastshop_link; ?>"><?php echo $language["last_order_merchant"];?></a>
					<?php } ?>

					
                   

                    </div>      
            <h3>Order list
			<a style="text-align:center;width:100%;margin-top:2%;" href="https://slack-files.com/TUWLAGXHD-F013R5GMVB9-07b7ebbed4" target="blank">
							  <img style="max-width:140px;" src="google.png" alt="">
			</a>
			<a style="text-align:center;width:100%;margin-top:2%;" href="https://apps.apple.com/us/app/id1491595615?mt=8" target="blank">
                                <img style="max-width:140px;max-height:40px;" src="appstore.png" alt="">
            </a> 	 			
			</h3>  
			<br/>
			
			<?php /* start -- 10 minute delivery countdown*/?>			
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
			
			$offer_one = '2';//no
			$offer_two =  '2';//no
			
			$city_query1 = mysqli_query($conn, "SELECT SQL_NO_CACHE * FROM city WHERE offer_one = 1");
			$totalcity_rows = mysqli_num_rows($city_query1);
			$city_query = mysqli_fetch_assoc($city_query1);
			
				if(count($city_query) > 0){
					$offer_one = 1; //15 minutes offer
				}
				$city_query_offer2 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SQL_NO_CACHE * FROM city where offer_two = 1 "));
				if(count($city_query_offer2) > 0){
					$offer_two = $city_query['offer_two']; //48 hour offer
				}
				
				
				
				
				$rows_od = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM order_list WHERE user_id= ".$_SESSION['login']." order by created_on desc limit 0,1"));
				//echo "SELECT * FROM order_list WHERE user_id= ".$_SESSION['login']." order by created_on desc limit 0,1";
				$currenttimestamp = date('Y-m-d H:i:s'); //
				$free_delivery_popup = 'no'; 
				if(count($rows_od) > 0 ){
					$free_delivery_prompt = $rows_od['free_delivery_prompt'];
					$created_timestamp_od = $rows_od['created_on'];
					$curr_time_od = strtotime($currenttimestamp); //currenttime
					$od_timestmp = strtotime($created_timestamp_od); //ordertime
					
					$diff =  round(abs($od_timestmp - $curr_time_od) / 60,2);
					$chk_time = 15 - $diff;
					
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
		
				}
				//echo "====".$free_delivery_popup;
			
			?>
<?php if($free_delivery_popup == 'yes' && $offer_one == 1){?>
	<br/>
	<div class="tip tab_fr hurry_div" style="display:block">
	<span class="title_fr">Hurry Up!!</span>
	<p>Congratulation!! Place your free Delivery order within <span id="ten-countdown"></span> Minutes</p>
	<br/>
	<a href="index.php?vs=<?=md5(rand()) ?>" Class="btn btn-sm btn-primary" style="background-color:green;border-color:green;color:white"> Place Next Order Now!!</a>
	</div>

<?php }?>
<?php 	/* end -- 10 minute delivery countdown*/?>

            <?php
               $dt = new DateTime();
               $today =  $dt->format('Y-m-d');
               $today_order = explode(" ",$created_new);
                if( $today == $today_order[0] && $status1 == 1 ){ ?>
                <div style="display: none;">
<audio autoplay> <source src="<?php echo $site_url;?>/images/sound/doorbell-1.mp3" type="audio/mpeg"> Your browser does not support the audio tag. </audio>
    </div>
    <?php } ?>
	<style>
	.status_td{
		max-width:200px;
	}</style>
	<div id="main">
		<div class="main-container">
			<div class="main-accordion" id="faq">
			<?php  $i =1;
                  while ($row=mysqli_fetch_assoc($total_rows)){
					  

						$wallet=$row['wallet'];
						if($wallet=="myr_bal")
						$wal_label="MYR WALLET";
						else if($wallet=="inr_bal")
						$wal_label="KOO COIN";
						 else if($wallet=="usd_bal")
						$wal_label="CF WALLET";
						else if($wallet=="cash")
							$wal_label="CASH";
						else $wal_label=$wallet;
                  	$product_ids = explode(",",$row['product_id']);
                  	$quantity_ids = explode(",",$row['quantity']);
                  	$product_code = explode(",",$row['product_code']);
                  	$remark_ids = explode("|",$row['remark']);
                  	$c = array_combine($product_ids, $quantity_ids);
                  	$amount_val = explode(",",$row['amount']);
                    $amount_data = array_combine($product_ids, $amount_val);
                    $total_data = array_combine($quantity_ids, $amount_val);
                    $created =$row['created_on'];
                    $date=date_create($created);
					$section_type=$row['section_type'];
					 $section_id=$section_type;
					 $sstper=$row['m_sst_rate'];
					 $merchant_id=$user_order['merchant_id'];
					 if($section_type)
					 {
					  $section_type = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name FROM sections WHERE id ='".$section_type."'"));
					 
					 }
					 $table_type=$row['table_type'];
                    $new_time = explode(" ",$created);
					
					$total = 0;
					foreach ($amount_val as $key => $value){
						if( $quantity_ids[$key] && $value ) {
							$total =  $total + ($quantity_ids[$key] *$value );
						} 
					}
					$sstper=$row['m_sst_rate'];			
					$incsst = ($sstper / 100) * $total;
					$incsst=@number_format($incsst, 2);
					$incsst=ceiling($incsst,0.05);
					 $incsst=@number_format($incsst, 2);
					$g_total=@number_format($total+$incsst, 2);
					$territory_price_array = explode("|",$row['territory_price']);
					$terr_id = $territory_price_array[0];
					$territory_price = $territory_price_array[1];
					
                    //echo date_format($date,"Y-m-d")."==".date('Y-m-d');
					
					
                  ?>
				  
			<!-- Loop of order --->
			<div class="card">
				<div class="card-header" id="faqhead<?php echo $i;?>" <?php if(date_format($date,"Y-m-d") == date('Y-m-d')){echo 'style="background:green !important;"';}?> >
					<a href="#" class="btn btn-header-link <?php if($i != 1){echo 'collapsed';}?> " data-toggle="collapse" data-target="#faq<?php echo $i;?>"
					aria-expanded="true" aria-controls="faq<?php echo $i;?>" <?php if(date_format($date,"Y-m-d") == date('Y-m-d')){echo 'style="background:green !important;"';}?>>
						<span class="tn_order_no"> 
				
						<?php echo $row['merchant_name'];  ?>
						
						| Invoice: #<?php echo $row['invoice_no'];?> </span>
						<span class="tn_order_date">  Date: <?php echo date_format($date,"M d,Y H:i:s");  ?></span>
						<span class="tn_order_total">  Order Total: RM <?php  echo @number_format(($g_total+$row['od_extra_charge']+$territory_price+$row['deliver_tax_amount']+$row['special_delivery_amount']+$row['speed_delivery_amount']+$row['donation_amount'])-($row['membership_discount']+$row['coupon_discount']),2); ?> </span>
					</a>
				</div>

				<div id="faq<?php echo $i;?>" class="collapse <?php if($i == 1){echo 'show';}?>" aria-labelledby="faqhead<?php echo $i;?>" data-parent="#faq">
					<div class="card-body">
						<div class="form-row">
							<div class="col-md-6 col-lg-6">
								
								<!--<div class="tn_mer_name">
									<span><?php echo $row['merchant_name'];  ?></span>
									<br/>
								</div>-->
								<span class="tm_product_qty"><b>Payment Mode:</b> <?php echo $wal_label;?> </span><br/>
								<?php if($row['ipay_p_id'] != 0){?>
									<?php if($row['ipay_payment_status'] == 0){?>
										<span class="tm_product_qty" style="background-color:red;padding:10px"><b><?php echo $row['ipay_message']; ?></b></span><br/>
										<span class="tm_product_qty"><b>Transaction Id:</b><?php echo $row['pay_transid']; ?></span><br/>
									<?php }?>
									<?php if($row['ipay_payment_status'] == 1){?>
										 <span class="tm_product_qty"   style="background-color:green;padding:10px"> Success<?php //echo $row['ipay_message']; ?></span><br/>
										 <span class="tm_product_qty"><b>Transaction Id:</b><?php echo $row['pay_transid']; ?></span><br/>
									<?php }?>
								<?php }?>
						
								<span class="tm_product_qty"><b>Remark:</b> <?php echo $row['remark_extra'];?> </span>
								<br/>
								<?php if($row['free_delivery_prompt'] == 1){?>
								<label class="btn-sm btn-primary" style="cursor:pointer;background-color:green;width:40%"> 10-minute free delivery</label>
								<?php }?>
								
								<?php if($row['coin_rebate_value'] != ''){?>
								<p class="" style="color:red;width:40%"><?php echo $row['merchant_special_coin_name']." : <b>".@number_format($row['coin_rebate_value'],2)."</b>";?></p>
								<?php }?>
								
								
								
								<div class="btn-box-wrap mt-4">
										
										<span class="btn-border s_order_detail " order_id='<?php echo $row['order_id']; ?>'   title="View Details">
										<i class="fa fa-cart-arrow-down  mr-2"></i>Product Detail
										</span>
										
										<?php 
										
										$final_odd_total = @number_format(($g_total+$row['od_extra_charge']+$territory_price+$row['deliver_tax_amount']+$row['special_delivery_amount']+$row['speed_delivery_amount']+$row['donation_amount'])-($row['membership_discount']+$row['coupon_discount']),2);
										
										?>
										<span class="btn-border bank_detail" final_odd_total =<?php echo $final_odd_total;?> merchant_id="<?php echo $row['merchant_id']; ?>"  row="<?php echo $row['name']; ?>" title="Bank Details">
											<i class="fa fa-university   mr-2"></i>Bank Detail
										</span>
											
											
										<?php if($row['status']){
						    				    if(($row['status']!=1 && $row['reviewed']==1 )){$review_given="y";}else{$review_given="n";}
										?>  
											<span class="btn-border review_detail" invoice_id="<?php echo $row['invoice_no']; ?>" order_id="<?php echo $row['order_id']; ?>" review_status="<?php echo $review_given; ?>" skiped_review="<?php echo $row['skiped_review']; ?>" ><i class="fa fa-star   mr-2"></i>Feedback</span>
										<?php } ?>
					  
									
										
										</div> 
										
										<div class="btn-box-wrap mt-4">
										<span class="btn-border mx-1" style="">
									 <a href="https://chat.whatsapp.com/LdvomJRqXoIG6aXqsf5PgX" target="_blank" style="color:black">	
									 <i class="fa fa-whatsapp" aria-hidden="true"></i>

											Helpline
											</a>
											
										</span>
										<?php if($row['status'] == 4 || $row['status']==5 || $row['status'] ==2 || $row['status']==1){ ?>  
									<?php if($row['cancel_order'] != 1 ){?>
									<span class="btn-border mx-1" style="">
									 <a target="_blank" style="color:black" href="print.php?id=<?php echo $row['order_id'];?>&merchant=<?php echo $row['merchant_id']?>">
											<i class="fa fa-print   mr-2"></i>PRINT
											</a>
											
										</span>
									<?php }?>
                      <?php }?>  
										
										
										</div>
										
										
										
									
								
										<?php if($row['payment_proof'] != '' ){?>
										<label class="btn-border mx-0" style="">
											<a class="fancybox" rel="" href="<?php echo $site_url.'/upload/'.$row['payment_proof'];?>" style="color:#000">
											Payment Proof </a>
											<a href="javascript:void(0)" class="delete_paymentproof" orderid="<?php echo $row['order_id']; ?>" style="color:#000"><i class="fa fa-trash" style="margin-left:20px"> </i></a>
										</label>
										<?php }else{?>
										
										<form method="post" id="image-form_<?php echo $row['order_id']; ?>" class="image-form" orderid='<?php echo $row['order_id']; ?>' enctype="multipart/form-data" onSubmit="return false;" style="min-height:0px !important;">
										<div class="input-group mt-3 mb-3 input-has-value flex-wrap">
											
											<input type="file" name="file" class="file" style="visibility: hidden;position: absolute;">
											<input type="text" class="form-control payment_proof" disabled="" placeholder="Payment Proof" id="file" style="width:130px">
											<br>
											<div class="input-group-append">
												<button type="button" class="browse btn btn-primary rounded-0">Browse</button>
											</div>
											&nbsp;&nbsp;
											<input type="submit" name="submit" value="Upload" class="btn btn-danger btn_proof_upload rounded-0">
										</div>
										</form>
										<?php }?>
										
										
										
										
									</div>
									<div class="col-md-6 pl-md-5">
										<div class="n_order_delivery  ">
											<p class="n_ship_add pt-0">Shipping Address</p>
											<p class="tn_add">
											<a class="" target="_blank" href="http://maps.google.com/maps?q=<?php echo  $row['location']; ?>"> <?php echo $row['location'];?></a>
											</p>
											<p class="tn_add"><b>Tel. No:</b> <?php if($row['number_lock'] == 0){echo $row['mobile_number'];}else{echo '-';}?></p>
										</div>
										

							<?php if($row['cancel_order'] != 1 && $row['rider_complete_order'] != 1 && $row['rider_info'] != 0){//if($row['cancel_order'] != 1){?>
							<hr class="mt-3 mb-4 mx-0" />
							<div class="media rider-media align-items-center flex-column flex-sm-row">
							<?php 	if($row['rider_info'] != '0'){
										$rider_name = $ridersArray[$row['rider_info']]['name'];
										$r_mobile_number = $ridersArray[$row['rider_info']]['r_mobile_number'];
										$r_live_location = $ridersArray[$row['rider_info']]['r_live_location'];
										$r_vehicle_number = $ridersArray[$row['rider_info']]['r_vehicle_number'];
										$r_image = $ridersArray[$row['rider_info']]['r_image'];
									}
									if($row['s_rider_option'] != 0){
										$s_label1 = 'We are still desperately trying to contact the merchant,<br/> once the order is confirmed with merchant, we will inform you. Meanwhile, <br/>our rider is on his way to merchant shop checking.';
										$s_label2 = 'Rider Listings';
										$s_label3 = 'Shop closed, Cancel!';
										$s_label4 = 'Merchant is preparing your foods. Please wait. Rider is waiting';
										if($_SESSION["langfile"] == 'chinese'){
											$s_label1 = '我们正在尽最大努力联系商家以确认你的订单。我们的司机已经出发到商家地点以确认商家是否营业！';
											$s_label2 = '骑手列表';
											$s_label3 = '商家休息，订单取消！';
											$s_label4 = '商家正在准备食物，食物完成后，我们的司机就会把美食送上';
										}
										if($row['s_rider_option'] == 1){
											$rs_label = $s_label1;
											echo '<p style="color:red">'.$s_label1.'</p>';
										}else if($row['s_rider_option'] == 2){
											$rs_label = $s_label2;
											//echo '<p>'.$s_label2.'</p>';
										}else if($row['s_rider_option'] == 3){
											$rs_label = $s_label3;
											echo '<p style="color:red">'.$s_label3.'</p>';
										}else if($row['s_rider_option'] == 4){
											$rs_label = $s_label4;
											echo '<p style="color:red">'.$s_label4.'</p>';
										}
									}		
										$hours_2 = 0;
										if($row['rider_complete_time']!= '0000-00-00 00:00:00'){
											$rider_od_complete_time = $row['rider_complete_time'];
											$complete_time = new DateTime($rider_od_complete_time);
											$now2 = new DateTime(Date('Y-m-d H:i:s'));
											$interval_2 = $complete_time->diff($now2);
											$hours_2 = $interval_2->h;
											$minutes_2 = $interval_2->i;
										}
										if($row['rider_complete_order'] != 1){
												if($hours_2 < 1){
													if($row['s_rider_option'] == 2){
														$rider_img = $site_url."/admin_panel/uploads/riders/".$r_image;?>
													
														<?php if($rider_img == ''){ $rider_img = 'https://dummyimage.com/100x100/ddd/000'; }?>
													<img  src="<?php echo $rider_img;?>" alt="Image" height="100px" width="100px" class="mb-3 mb-sm-0  mr-sm-3">
													<div class="media-body">
														<p><strong>Rider Name : </strong> <?php echo $rider_name;?></p>
														<p><strong>Contact Number :</strong> <?php echo $r_mobile_number;?> 
														<a href="https://api.whatsapp.com/send?phone=<?php echo $r_mobile_number;?>" target="_blank">
															<img src="images/whatapp.png" style="max-width:32px;"/>
														</a>
														<br/>
														
														(<?php echo $language["rider_contact_text"] ;?>)
														</p>
														<p><strong>Vehicle Number : </strong> <?php echo $r_vehicle_number;?></p>
														<p><strong>Tracking :  </strong> 
														<?php if($row['rider_arrive_shop'] != '0000-00-00 00:00:00' || $row['rider_complete_order'] == 1){?>
														<a href="<?php echo $r_live_location;?>" class="mr-2"><i class="fa fa-map-marker" aria-hidden="true"></i></a> Track Location
														<?php }else{?>
														<a href="#" class="mr-2"><i class="fa fa-map-marker" aria-hidden="true"></i></a>Attempting to connect rider's Location.... Try 10 minutes later. 
														<?php }?>
														</p>
													</div>
													
												<?php }
											 }
										}?>
							</div>		
							<?php }?>
						<!-- END Riderinfo -->
							<?php
                        	$n_status='';  
							$s_cls = '';
							$s_cls1 = '';
							$s_cls2 = '';
							$s_cls3 = '';
                                if($row['status'] == 0)
								{
									$sta =$language['pending'];
									$s_color="red";
									$n_status=1;
									$s_cls = 'active';
								}
                                else if($row['status'] == 1) 
								{
									
									$sta =$language['done_in_delivery'];
									$s_color="green";
									$n_status=4;
									$s_cls = 'active';
								}
								else if($row['status'] == 4 || $row['status']==5) 
								{
									$sta =$language['done_in_delivery'];
									$s_color="green";
									$s_cls = 'active';
								}
                                else 
								{
									$n_status=1;
									$sta =$language['accepted'];
									// $sta = "Accepted";
									$s_color="";
									$s_cls = 'active';
								}
								
								if($row['rider_complete_order'] == 1){
									$n_status='';
									$sta ='completed';
									// $sta = "Accepted";
									$s_color="green";
									$s_cls = 'active';
								}	
								if($row['cancel_order'] == 1){
									$n_status='';
									$sta ='Cancelled';
									// $sta = "Accepted";
									$s_color="red";
									$b_color = "border-color:red";
								}
								
								
						?>
						

										<div class="n_order_track ">
											<p class="n_ship_add">Delivery Status</p>
											<?php if($row['cancel_order'] == 1){?>
											<label class= "btn btn-primary status" data-id="<?php echo $row['order_id']; ?>" style="cursor:pointer;width:150px;background-color:<?php echo $s_color;?>;<?php echo $b_color;?>"> <?php echo $sta; ?></label>
											<?php }else{?>
											<div class="n_breadcrumb flat d-flex">
												
												<?php //echo $row['status'];?>
											<?php if($row['rider_complete_order'] == 1){?>
												<a href="#" class="active">Pending</a>
												<a href="#" class="active">Accepted</a>
												<a href="#" class="active">In delivery</a>
												<a href="#" class="active">Completed</a>
											<?php }else if($row['status'] == 1){?>
												<a href="#" class="active">Pending</a>
												<a href="#" class="active">Accepted</a>
												<a href="#" class="">In delivery</a>
												<a href="#" class="">Completed</a>
											<?php }else if($row['status'] == 4){?>
												<a href="#" class="active">Pending</a>
												<a href="#" class="active">Accepted</a>
												<a href="#" class="active">In delivery</a>
												<a href="#" class="">Completed</a>
											<?php }else if($row['status'] == 2){?>
												<a href="#" class="active">Pending</a>
												<a href="#" class="active">Accepted</a>
												<a href="#" class="">In delivery</a>
												<a href="#" class="">Completed</a>
											<?php }else{?>
												<a href="#" class="active">Pending</a>
												<a href="#" class="">Accepted</a>
												<a href="#" class="">In delivery</a>
												<a href="#" class="">Completed</a>
											<?php }?>
											
											</div>
											<?php }?>
											<a href="orderdetails.php?orderid=<?php echo $row['order_id']; ?>" class="btn-border d-block" title="View Details">
												<i class="fa fa-cart-arrow-down mr-2"></i>View Detail</a>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
			<!-- ENd Loop of order -->
				<?php $i++;}?>
			</div>
		</div>
	</div>	
		
			
	<?php /*?>
            <table class="table table-striped">
               <thead>
                  <tr>
                     <th><?php echo $language["items"];?></th>
                     <th><?php echo $language["date_of_order"];?></th>
                     <th>No</th>
					 <th>Invoice Number</th>
            <!--th>Agent code</th!-->
					 <th class="test_product" style="min-width:240px;"><?php echo $language["row"];?></th>
					 <th class="status_td"><?php echo $language["status"];?></th>
					 <th style="color:#09caab;"><?php echo "Order Details";?></th>
					 <th><?php echo $language["rider_info"];?></th>
					 <th style="color:#09caab;"><?php echo "Merchant Bank Detail";?></th>
					 					 <th style="color:#09caab;"><?php echo "Feedback";?></th>
										   <th><?php echo $language["print"];?></th> 
					 
					   <th>Section</th>
					 <th><?php echo $language["table_number"];?></th>

					 <th class="location_head"><?php echo $language["location"];?></th>
					 <th><?php echo $language["telephone_number"];?></th>
                     <th><?php echo $language["product_code"];?></th>
                     <th class="product_name test_product"><?php echo $language["product_name"];?></th>
                    <th class="product_name test_product"><?php echo "VARIENT";?></th>
                     <th class="product_name test_product"><?php echo $language["remark"];?></th>
                     <th><?php echo $language["amount"];?></th>
					<th><?php echo $language["quantity"];?></th>
                        <th><?php echo $language["total"];?></th>
							
							 <th><?php echo "Service Fee %"?></th>
							 <!--th><?php echo "Grand Total (Inc ".$sstper." % Service Fee)";?></th!-->
							 <th><?php echo "Grand Total";?></th>
							<th><?php echo "Delivery Tax"; ?></th>
							 <th><?php echo $language["delivery_charges"];?></th>
							 <th><?php echo $language["membership_discount"];?></th>
							 <th>Coupon Discount</th>
							  <th><?php echo $language["final_total"];?></th>
                           
                            <th><?php echo $language['paid_by_wallet'];?></th>
                            <th><?php echo $language['bal_payment'];?></th>
                     <th><?php echo $language["mode_of_payment"];?></th>
                     <!--th><?php echo $language["rating_comment"];?></th!-->
                     
                     <!--th>K1/K2</th!-->
                  </tr>
               </thead>
               <?php  $i =1;
                  while ($row=mysqli_fetch_assoc($total_rows)){
				  
				  //print_r($row);
				 // echo "<hr>";
					$wallet=$row['wallet'];
						if($wallet=="myr_bal")
						$wal_label="MYR WALLET";
						else if($wallet=="inr_bal")
						$wal_label="KOO COIN";
						 else if($wallet=="usd_bal")
						$wal_label="CF WALLET";
						else if($wallet=="cash")
							$wal_label="CASH";
						else $wal_label=$wallet;
                  	$product_ids = explode(",",$row['product_id']);
                  	$quantity_ids = explode(",",$row['quantity']);
                  	$product_code = explode(",",$row['product_code']);
                  	$remark_ids = explode("|",$row['remark']);
                  	$c = array_combine($product_ids, $quantity_ids);
                  	$amount_val = explode(",",$row['amount']);
                    $amount_data = array_combine($product_ids, $amount_val);
                    $total_data = array_combine($quantity_ids, $amount_val);
                    //var_dump($amount_val);
					// $order_list = mysqli_query($conn, "SELECT * FROM order_list WHERE user_id ='".$_SESSION['login']."' ORDER BY `created_on` DESC");
                  
                  
                    $created =$row['created_on'];
                    $date=date_create($created);
					$section_type=$row['section_type'];
					 $section_id=$section_type;
					 $sstper=$row['m_sst_rate'];
					 $merchant_id=$user_order['merchant_id'];
					 if($section_type)
					 {
					  $section_type = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name FROM sections WHERE id ='".$section_type."'"));
					 
					 }
					 $table_type=$row['table_type'];
                    $new_time = explode(" ",$created);
                   // $user_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id ='".$row['merchant_id']."'"));
                    //$account_type = mysqli_fetch_assoc(mysqli_query($conn, "SELECT k_merchant FROM k1k2_history WHERE id ='".$row['id']."'"))['account_type'];
                  ?>
               <tbody>
                  <?php

                   if($row['status'] == 1) $callss = "gr";
                     else if($row['status'] == 2) $callss = "or";
                     else $callss = " ";
                     $todayorder = $today == $new_time[0] ? "red" : "";
                   $i1 =1;
                      ?>
                  <tr  data-id="<?php echo $row['order_id']; ?>" class="<?php echo $todayorder; ?> <?php echo $callss; ?> br_bk" >
                     <td><?php echo  $i; ?></td>
                     <td><?php echo date_format($date,"Y/m/d");  ?>
                     <?php echo '<br>'; echo $new_time[1] ?>
                                         </td>
                     <td><?php
                        foreach ($quantity_ids as $key => $val)
                        {

                        echo $i1; echo '<br>';
                         $i1++;
                        }
                        ?></td>   
						 <td><?php echo ($row['invoice_no']%1000);?></td>
                    <!--td></td!-->  
                    <td>
					<a  style="text-decoration:underline;font-weight:bold;font-size:16px;" href="<?php echo $site_url; ?>/view_merchant.php?vs=<?=md5(rand()) ?>&sid=<?php echo $row['merchant_mobile_number'];?>&oid=<?php echo $row['order_id']; ?>"><?php echo $row['merchant_name'];  ?>
					
					</br></br>
						<?php 
						if($row['chat_with_merchant'])
							{
							if($row['chat_group'])
							{
								?>
							<a href="<?php echo $row['chat_with_merchant']; ?>" target="_blank"><img src="images/whatapp.png" style="max-width:32px;"/> <?php echo $language['merchant_hotline']; ?></a>
							<?php }
							else  
							{
							  ?>
							 	<a href="https://api.whatsapp.com/send?phone=<?php  echo $row['chat_with_merchant']?>" target="_blank"><img src="images/whatapp.png" style="max-width:32px;"/> <?php echo $language['merchant_hotline']; ?></a>
							<?php }
							}
							else
							{
						   $chat_merchant_list = array(6958,6956, 7634,7785,7799,7839,7808,7818,7846,7912,7953,7837,7209,7462,7209,7723,7674,7663,7726,7703,7554,6960,7658,7662,7462); 
						   if (in_array($row['merchant_id'], $chat_merchant_list)) { ?>
						
						  <a href="https://chat.whatsapp.com/J4wcS4riADaBBrvY60c8f3" target="_blank"><img src="images/whatapp.png" style="max-width:32px;"/> <?php echo $language['merchant_hotline']; ?></a>
						   <?php } else { ?>
							<a href="https://api.whatsapp.com/send?phone=<?php  echo $row['merchant_mobile_number']?>" target="_blank">
							<img src="images/whatapp.png" style="max-width:32px;"/> <?php echo $language['merchant_hotline']; ?></a>
							<?php } } ?>  
							
							
							
							<!--/br> </br>  <a href="https://chat.whatsapp.com/KeUcnzasq2M1x5AUBsjJGF" target="_blank">	<img src="images/whatapp.png" style="max-width:32px;"/> <?php echo $language['delivery_hotline']; ?></a!-->
							<!--/br> </br>  <a href="https://api.whatsapp.com/send?phone=60123945670" target="_blank">	<img src="images/whatapp.png" style="max-width:32px;"/> <?php echo $language['delivery_hotline']; ?></a!-->
							</br> </br>  <a href="https://chat.whatsapp.com/LdvomJRqXoIG6aXqsf5PgX" target="_blank">	<img src="images/whatapp.png" style="max-width:32px;"/> <?php echo $language['delivery_hotline']; ?></a>
							</br> </br>  <a href="https://api.whatsapp.com/send?phone=60137285670" target="_blank">	<img src="images/whatapp.png" style="max-width:32px;"/> <?php echo "Feedback/complaint"; ?></a>
					</td>  
                        <!--  -->
						<td class="status_td">
                       <?php
                        	$n_status='';  
                                if($row['status'] == 0)
								{
									$sta =$language['pending'];
									$s_color="red";
									$n_status=1;
								}
                                else if($row['status'] == 1) 
								{
									
									$sta =$language['done_in_delivery'];
									$s_color="green";
									$n_status=4;
								}
								else if($row['status'] == 4 || $row['status']==5) 
								{
									$sta =$language['done_in_delivery'];
									$s_color="green";
								}
                                else 
								{
									$n_status=1;
									$sta =$language['accepted'];
									// $sta = "Accepted";
									$s_color="";
								}
								
								if($row['rider_complete_order'] == 1){
									$n_status='';
									$sta ='completed';
									// $sta = "Accepted";
									$s_color="green";
								}	
								if($row['cancel_order'] == 1){
									$n_status='';
									$sta ='Cancelled';
									// $sta = "Accepted";
									$s_color="#eca7a7";
									$b_color = "border-color:#eca7a7";
								}
								
								
						?>
                        <?php if($row['popup']==0 && $row['status'] == 1 )
                        {
                            //echo $row['id'];
                        }
                        ?>
                        <label class= "btn btn-primary status" data-id="<?php echo $row['order_id']; ?>" style="cursor:pointer;width:150px;background-color:<?php echo $s_color;?>;<?php echo $b_color;?>"> <?php echo $sta; ?></label>
						
						<?php if($row['ipay_p_id'] != 0){?>
							<?php if($row['ipay_payment_status'] == 0){?>
								<br/>
								 <label class= "btn btn-primary status"  style="background-color:red"> <?php echo $row['ipay_message']; ?></label>
								 <br/>Transaction Id: 
								 <?php echo $row['pay_transid']; ?>
							<?php }?>
							<?php if($row['ipay_payment_status'] == 1){?>
								<br/>
								 <label class= "btn btn-primary status"  style="background-color:green"> Success<?php //echo $row['ipay_message']; ?></label>
								 <br/>Transaction Id: 
								 <?php echo $row['pay_transid']; ?>
							<?php }?>
						<?php }?>
                     </td> 
					 <td style="font-size:18px;" class="s_order_detail btn btn-blue" order_id='<?php echo $row['order_id']; ?>'> Detail</td>
					 <td>
					 <?php //echo $row['rider_info']; ?>
					 <?php if($row['cancel_order'] != 1){?>
					 <!-- Showing riderinfo--->
					 <?php if($row['rider_info'] != '0'){
						 //echo "===".$row['rider_info'];
						 //print_r($ridersArray[$row['rider_ifo']]);
						$rider_name = $ridersArray[$row['rider_info']]['name'];
						$r_mobile_number = $ridersArray[$row['rider_info']]['r_mobile_number'];
						$r_live_location = $ridersArray[$row['rider_info']]['r_live_location'];
						$r_vehicle_number = $ridersArray[$row['rider_info']]['r_vehicle_number'];
						$r_image = $ridersArray[$row['rider_info']]['r_image'];
					 }?>
					 <div style="width:200px">
					<?php if($row['s_rider_option'] != 0){
						$s_label1 = 'We are still desperately trying to contact the merchant,<br/> once the order is confirmed with merchant, we will inform you. Meanwhile, <br/>our rider is on his way to merchant shop checking.';
						$s_label2 = 'Rider Listings';
						$s_label3 = 'Shop closed, Cancel!';
						$s_label4 = 'Merchant is preparing your foods. Please wait. Rider is waiting';
						if($_SESSION["langfile"] == 'chinese'){
							$s_label1 = '我们正在尽最大努力联系商家以确认你的订单。我们的司机已经出发到商家地点以确认商家是否营业！';
							$s_label2 = '骑手列表';
							$s_label3 = '商家休息，订单取消！';
							$s_label4 = '商家正在准备食物，食物完成后，我们的司机就会把美食送上';
						}
						if($row['s_rider_option'] == 1){
							echo $s_label1;
						}else if($row['s_rider_option'] == 2){
							//echo $s_label2;
						}else if($row['s_rider_option'] == 3){
							echo $s_label3;
						}else if($row['s_rider_option'] == 4){
							echo $s_label4;
						}
						?>
					<?php }?>
					</div>
					<?php 
					//echo $row['rider_complete_time']."===".Date('Y-m-d H:i:s');
					$hours_2 = 0;
					if($row['rider_complete_time']!= '0000-00-00 00:00:00'){
						$rider_od_complete_time = $row['rider_complete_time'];
						$complete_time = new DateTime($rider_od_complete_time);
						$now2 = new DateTime(Date('Y-m-d H:i:s'));
						$interval_2 = $complete_time->diff($now2);
						$hours_2 = $interval_2->h;
						$minutes_2 = $interval_2->i;
					}
					if($row['rider_complete_order'] != 1){
					if($hours_2 < 1){
					?>
						<?php if($row['s_rider_option'] == 2){?>
						<div style="width:200px">
						<?php	
						$rider_img = $site_url."/admin_panel/uploads/riders/".$r_image;
						if($r_image != ''){?>
						<img src="<?php echo $rider_img;?>" height="50px" width="50px">
						<?php }?>
						
						<b>Name:</b> <?php echo $rider_name;?><br/>
						<b>Number:</b> <?php echo $r_mobile_number;?><br/>
						<b>Vehicle:</b> <?php echo $r_vehicle_number;?><br/>
						
						<?php if($row['rider_arrive_shop'] != '0000-00-00 00:00:00' || $row['rider_complete_order'] == 1){?>
						<b>Track:</b> <a href="<?php echo $r_live_location;?>" class=""><label class="btn btn-sm btn-primary">Track Location </label></a>
						<?php }else{?>
						<b>Track:</b>  <b style="color:red">Attempting to connect rider's Location.... Try 10 minutes later.</b> 
						<?php }?>
						
						
						</div>
					<?php }
				 }else{
					?>
					<?php if($row['s_rider_option'] == 2){?>
					<b>Name:</b> <?php echo $rider_name;?><br/>
					<?php }?>
				  <?php }?>
				  
					<?php }?>
					
					
					
					<!-- END Riderinfo -->
				  <?php }?>
					 
					 </td>
					 
					  <td><span class="btn btn-yellow bank_detail" merchant_id="<?php echo $row['merchant_id']; ?>"  row="<?php echo $row['name']; ?>" style="color:black;">Bank Detail</span>
					  <!--- Payment proof --->
					  <br/>
					  <?php 
					  //echo "===".$row['payment_proof'];
					  if($row['payment_proof'] != '' ){?>
						  <label class="btn-sm btn-yellow" style="background-color:#6ea6d6;margin-top:10px;width:150px">
						  <a class="fancybox" rel="" href="<?php echo $site_url.'/upload/'.$row['payment_proof'];?>" style="color:white" >
Payment Proof </a>
						  <a href="javascript:void(0)" class="delete_paymentproof" orderid='<?php echo $row['order_id']; ?>' style="color:white"><i class="fa fa-trash" style="margin-left:20px"> </i></a>
						  </label>
					  <?php }else{?>
						<form method="post" id="image-form_<?php echo $row['order_id']; ?>" class="image-form" orderid='<?php echo $row['order_id']; ?>' enctype="multipart/form-data" onSubmit="return false;">
							<div class="form-group">
								<input type="file" name="file" class="file" style="visibility: hidden;position: absolute;">
								<div class="input-group my-3">
									<input type="text" class="form-control payment_proof" disabled placeholder="Payment Proof" id="file" style="width:130px">
									<br/>
									<div class="input-group-append">
										<button type="button" class="browse btn btn-primary">Browse...</button>
									</div>
									&nbsp;&nbsp;
									<input type="submit" name="submit" value="Upload" class="btn btn-danger btn_proof_upload">
								</div>
							</div>
						</form>
					  <?php }?>
					  <!--- END payment proof -->
					  </td>
					   <td>
					  <?php if($row['status']){
						    				    if(($row['status']!=1 && $row['reviewed']==1 ))
												  {
																$review_given="y";

												  }
															else
												  {
																$review_given="n";
												  }
						  ?>  
					   	 <span class="btn btn-purple review_detail" invoice_id="<?php echo $row['invoice_no']; ?>" order_id="<?php echo $row['order_id']; ?>" review_status="<?php echo $review_given; ?>" style="color:black;" skiped_review="<?php echo $row['skiped_review']; ?>">Feedback</span>

					  <?php } ?>
					  </td> 
					    <?php if($row['status'] == 4 || $row['status']==5 || $row['status'] ==2 || $row['status']==1){ ?>    
                       
                      <td><a target="_blank" href="print.php?id=<?php echo $row['order_id'];?>&merchant=<?php echo $row['merchant_id']?>">Print</a></td>   
                      <!--td><?php echo $user_name['account_type']; ?></td!-->
                      <?php }?>  
					   <td><?php echo $section_type['name'];?></td>
                         <td><?php echo $row['table_type'];?></td>
                        
                        <td class="location_<?php echo $row['id']; ?> new_tablee">
							<a class="" target="_blank" href="http://maps.google.com/maps?q=<?php echo  $row['location']; ?>">  
						
							<?php echo $row['location'];?></a></td>


                         <?php if($row['number_lock'] == 0){?>
                            <td><?php echo $row['mobile_number'];?></td>
                        <?php } else {?>
                            <td></td>
                        <?php }?>

                     <td>
                        <?php
                           foreach ($product_code as $key)
                           {
                           //$product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id ='".$key."'"));
                                if($key == "") echo '- <br>';
                                else echo $key.'<br>'; 
                               
                           }
                           ?>

                     </td>
                     <td class="products_namess test_productss">
                        <?php foreach ($product_ids as $key ){
							if(is_numeric($key)){
                                $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id ='".$key."'"));
                                echo $product['product_name'].'<br>';
					        }else {
						        echo $key.'<br>';
					        }
                         } ?>
                         </td>
					<td><?php if($row['varient_type']){$v_str=$row['varient_type'];
							$v_array=explode("|",$v_str);
							foreach($v_array as $vr)
							{
								
								if($vr)
								{
									$v_match=$vr;
									$v_match = ltrim($v_match, ',');
									$sub_rows = mysqli_query($conn, "SELECT * FROM sub_products WHERE id  in ($v_match)");
									while ($srow=mysqli_fetch_assoc($sub_rows)){
										echo $srow['name'];
										echo "&nbsp;&nbsp;";
									}
								}
								 else
								 {
									 echo "</br>";
								 }
								 echo "<br/>";
					} }
							  ?>
							</td>
                     <td>
                        <?php
                           foreach ($remark_ids as $vall)
                           {

                           	echo $vall.'<br>';

                           }
                           ?>
                     </td>
					 <td class="amount_<?php echo $row['id'];?>">

                                <?php
							
                                $q_id = 0;

                                foreach ($amount_val as $key => $value){

                                    $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id ='".$key."'"));
								
                                    if($value == '0') { ?>

                                        <p class="pop_upss" data-id="<?php echo $row['id']; ?>"  style='margin-bottom: 0px;' data-prodid="<?php echo $product_ids[$key]; ?>"> <i class="fa fa-pencil-square-o" aria-hidden="true"></i></p>

                                    <?php  }

                                    if( $quantity_ids[$key] && $value ) {
										
                                        echo @number_format($value, 2).'<br>';

                                    } else {

                                        echo '0<br>';
										// echo '<p class="pop_upss" data-id=' . $row['id'] . '  style="margin-bottom: 0px;display:block;" data-prodid="' . $key . '""><i class="fa fa-pencil-square-o" aria-hidden="true"></i>0</p>';

                                    }

                                    $q_id++;

                                } ?>

                            </td> 
							<td class="quantity_<?php echo $row['id'];?>"><?php
							
                                foreach ($quantity_ids as $key)
                                {  
                                    echo $key;
                                    echo '<br>';
                                }
                                ?></td>
                            <td class="total_order total_<?php echo $row['id']?>">
                                <?php
								
								 
								
                                $total = 0;
                                foreach ($amount_val as $key => $value){
                                    if( $quantity_ids[$key] && $value ) {
                                        $total =  $total + ($quantity_ids[$key] *$value );
                                    } 
                                }
                                echo  @number_format($total, 2);
                                ?>
                            </td>
						
							<?php $incsst = ($sstper / 100) * $total;
							    $incsst=@number_format($incsst, 2);
								$incsst=ceiling($incsst,0.05);
								 $incsst=@number_format($incsst, 2);
							    $g_total=@number_format($total+$incsst, 2);
								$territory_price_array = explode("|",$row['territory_price']);
								$terr_id = $territory_price_array[0];
								$territory_price = $territory_price_array[1];
							 ?>
							  <td><?php echo $incsst; ?></td>
							    <td><?php  echo $g_total;?></td>
							  <td><?php  echo @number_format($row['deliver_tax_amount'],2); ?></td>
							
								
							
							
							<td><?php  
							if($row['special_delivery_amount']>0 && $row['speed_delivery_amount']>0){
								//echo '1';
								echo @number_format($row['od_extra_charge'],2)."+ ".number_format($row['special_delivery_amount'],2)."(Chiness Delivery)"."</br>+".number_format($row['speed_delivery_amount'],2)."(Speed Delivery)"."+".@number_format($territory_price,2);
								
								
								}
								else if($row['special_delivery_amount']>0 && $row['speed_delivery_amount']==0){
								//echo '2';
								echo @number_format($row['od_extra_charge'],2)."+ ".number_format($row['special_delivery_amount'],2)."(Chiness Delivery)"."+".@number_format($territory_price,2);
									
								}
								else if($row['special_delivery_amount']==0 && $row['speed_delivery_amount']>0){
									//echo '3';
									echo @number_format($row['od_extra_charge'],2)."+ ".number_format($row['speed_delivery_amount'],2)."(Speed Delivery)"."+".@number_format($territory_price,2);
								}
								else {
									//echo '4';
									//echo "..".$row['order_extra_charge'];
									echo @number_format($row['od_extra_charge'],2)."<br/>+<br/>".@number_format($territory_price,2); 
									} ?>
									
								<?php if($terr_id == '-1'){?>
								<label class="btn-sm btn-primary" style="cursor:pointer;background-color:red"> Check AreaName</label>
								<?php }?>
									
								<?php if($row['free_delivery_prompt'] == 1){?>
								<label class="btn-sm btn-primary" style="cursor:pointer;background-color:green"> 10-minute free delivery</label>
								<?php }?>
								
								
							</td>
							<td><?php  echo @number_format($row['membership_discount'],2); ?></td>
							<td><?php echo @number_format($row['coupon_discount'],2); ?></td>
							<td><?php  echo @number_format(($g_total+$row['od_extra_charge']+$territory_price+$row['deliver_tax_amount']+$row['special_delivery_amount']+$row['speed_delivery_amount'])-($row['membership_discount']+$row['coupon_discount']),2); ?></td>
							
							<td><?php  echo @number_format($row['wallet_paid_amount'],2); ?></td>
							<td><?php echo @number_format(($g_total+$row['od_extra_charge']+$territory_price+$row['deliver_tax_amount']+$row['special_delivery_amount']+$row['speed_delivery_amount'])-($row['wallet_paid_amount']+$row['membership_discount']+$row['coupon_discount']), 2); ?></td>   
                           
                          
                    <td><?php echo $wal_label;  ?></td>   
							<!--td>   
								<?php //if($row['status']== '1'){ ?>
								<label class="dp_lab"  data-id="<?php echo $row['id'];  ?>" data-oid="<?php echo $total;?>" data-orid="<?php echo $row['id']; ?>">Click Here</label>
								<?php// }   ?>
							</td!-->                
                    
                  </tr>
                  <?php  	 $i++; ?>

               </tbody>
          <?php       }

                     ?>
            </table>
            <div style="margin:0px auto;">
               <ul class="pagination">
                  <?php
                   global $total_page_num ;
                     for($i = 1; $i <= $total_page_num; $i++)
                     {
                      if($i == $page)
                      {
                       $active = "class='active'";
                      }
                      else
                      {
                       $active = "";
                      }
                      echo "<li $active><a href='?page=$i'>$i</a></li>";
                     }
                     ?>
               </ul>
            </div>
            <div>
                <!-- add new code-->
				<!-- edit amount--->
	        <div class="modal fade" id="myModal" role="dialog">
                <div class="modal-dialog">
                <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Edit Amount</h4>
                        </div>
                        <div class="modal-body" style="padding-bottom:0px;">

		                    <div class="col-sm-10">  
                                <form id ="data">
                                    <div class="form-group">
                                        <label>Amount</label>
		 	                            <input type="text" name="amount" id = "amount" class="form-control" value="" required>
                                        <input type="hidden" id="id" name="id" value="">
                                        <input type="hidden" id="p_id" name="p_id" value="">
                                    </div>
                                </div>
		                    </div>
                        <div class="modal-footer" style="padding-bottom:2px;">
                			<button>Submit</button>
                        </div>
                        </form>
                    </div>
                 <div class="modal fade" id="myModal" role="dialog" >
                    <div class="modal-dialog modal-lg">
                      <div class="modal-content" id="modalcontent">
                       <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
				</div>
			<!-- end edit function--->
	
		<!-- end new code--->

      </main>
      </div>
      
	  <?php */?>
	  
	  <!-- /.widget-body badge -->
      </div>
      <!-- /.widget-bg -->
      <!-- /.content-wrapper -->
      <?php include("includes1/commonfooter.php"); ?>
	    <!-- Modal -->
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
					    <div class="form-group register_password" style="<?php  if($password_created=="y"){ echo "display:none;"; }?>">
             
							  <div class="passwd_field">
								<label for="login_password">Please create your password</label>
								<input type="password" id="login_password" class="form-control" name="login_password"/>
											
					   <i  onclick="myFunction2()" id="eye_slash_2" class="fa fa-eye-slash" aria-hidden="true"></i>
					  <span id="eye_pass_2" onclick="myFunction2()" > Show Password </span>
					   <small id="register_error" style="display: none;color:#e6614f;">
                 
                </small>
										
							  </div>
				</div>
						
                    </div>
                    <div class="modal-footer" style="padding-bottom:2px;">
                        <div class="row" style="margin: 0;">
			 
             
						  <div class="col" style="padding: 0;margin: 5px;">
							
							<input type="submit" class="btn btn-primary register_ajax"  name="register_ajax" value="Confirm" style="float: right;"/>
							 <small id="register_error" style="display: none;color:#e6614f;">
							 
							</small>
							
						  </div>
						         
					   
						</div>
						  <small  class="finalskip"  style="color:#e6614f;font-size:14px;min-width:50px;">
						  <u class="finalskip btn btn-primary">Skip</u>
						</small>    
						
                    </div>
                  
            </div>
        </div>
  </div>
  <style>
.text_payment{
    width: 50%!important;
    text-align: center;
    margin: 0 auto;
}
.pay_wallet{
    font-size: 14px;
    text-align: center;
}
.order_whole {
    text-align: center;
    border: 1px solid;
    width: 50%;
    margin: 0 auto;
    padding: 15px;
}
.wallet_hr{
    width: 510px;
    margin-left: -15px;
    border-top: 1px solid black;
}
/*jupiter 24.02.19*/
	.img60{
		width: 40px;
		height: auto;
	}
	.payment_title{
		margin-top: 0.8rem;
		font-size: 16px;
	}
	.table td{
		border-top: 1px solid black !important;
	}
/**/
 @media (min-width: 360px) and (max-width:650px) {
.order_whole {
    text-align: center;
    border: 1px solid;
    width: 100%;
    margin: 0 12px;
    padding: 14px;
}
.wallet_hr {
    width: 325px;
}
}
 @media (min-width: 700px) and (max-width:800px) {

.wallet_hr {
    width: 335px;
   }
}
 @media (min-width: 650px) and (max-width:700px) {

.wallet_hr {
    width: 307px;    
}
}
 @media (min-width: 430px) and (max-width:400px) {

.wallet_hr {
    width: 360px!important;
}
}

</style>
   <div class="modal fade" id="OnlineModel" role="dialog" style="">
   <div class="modal-dialog">
           <?php 

          

            ?>

            <!-- Modal content-->
            <div class="modal-content">
              
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
					
                </div>
                 
                    <div class="modal-body" style="padding-bottom:0px;">
					       <p>Select Payment Mode</p>
							<select class="form-control text_payment required" id="text_payment" style="font-weight: bold;" name="wallet">  
        						<!--<option class="text_csah" value='cash_<?php echo $order_total['id'];?>'><?php echo $language["cash"] ;?></option>
        						<option value='MYR'><?php echo $language["wallet"];?></option>  !-->
								<?php if($merchant['mobile_number']!="60172669613"){ ?>
        						
								<?php if($credit_check == "1"){?>
    						    	<option title="Credit Card">Credit Card</option>
    						    <?php }?>
        						<?php if($boost_check == "1"){?>
    						    	<option title="Boost Pay" value='1'>Boost Pay</option>
    						    <?php }?>
    						    <?php if($grab_check == "1"){?>
    						    	<option value='2' title="Grab Pay">Grab Pay</option>
    						    <?php }?>
    						    <?php if($wechat_check == "1"){?>
    						    	<option value='3' title="WeChat">WeChat</option>
    						    <?php }?>
    						    <?php if($touch_check == "1"){?>
    						    	<option value='4' title="Touch & Go">Touch & Go</option>
    						    <?php }?>
    						    <?php if($fpx_check == "1"){?>
    						    	<option value='5' title="FPX">FPX</option>
    						    <?php } }?>
        					</select>  
                      
						   <input type="hidden" id="id" name="m_id" value="<?php echo $m_id;?>">
						   <input type="hidden" id="amount" name="amount" value="<?php echo $total;?>">
						    <input type="hidden" id="member" name="member" value="<?php echo $member;?>">
					       <input type="hidden" id="o_id" name="o_id" value="<?php echo $order_total['id'];?>">
						    <?php if(isset($_GET['user_id'])){  ?>
						    <input type="hidden" id="guest_id" name="guest_id" value="<?php echo $_GET['user_id'];?>">
						    <input type="hidden" id="guest_order_id" name="guest_order_id" value="<?php echo $_GET['order_id'];?>">
						   <?php } ?>
						  
						<!-- jupiter 24.02.19 -->
							<?php if($merchant['mobile_number']!="60172669613"){    ?>
					  <div class="payment_section">
					  	<table class="table" border="1" style="margin-top: 10px; " >
					  		<tbody>
					  			<!--tr>
					  				<td><h5 class="payment_title">Cash</h5></td>
						  			<td><img src="images/payments/cash.png" class="img60"></td>
						  			<td><img src="images/payments/<?= $cash_image;?>.jpg" class="img60"></a></td>
					  			</tr!-->
								<?php if($credit_check == "1"){?>
					  			<tr>
					  				<td><h5 class="payment_title">Credit Card</h5></td>
						  			<td><img src="images/payments/credit.jpg" class="img60"></td>
						  			<td><img src="images/payments/<?= $credit_image;?>.jpg" class="img60"></a></td>
					  			</tr>
								<?php } ?>
					  			<!--tr>
					  				<td><h5 class="payment_title">Wallet</h5></td>
						  			<td><img src="images/payments/wallet.png" class="img60"></td>
						  			<td><img src="images/payments/<?= $wallet_image;?>.jpg" class="img60"></a></td>
					  			</tr!-->
								<?php if($boost_check == "1"){?>
					  			<tr>
					  				<td><h5 class="payment_title">Boost Pay</h5></td>
						  			<td><img src="images/payments/boost.png" class="img60"></td>
						  			<td>
						  				
						  				<a href="#" data-toggle="modal" data-target="#paymentModal" user="<?= $merchant_id;?>" class="payment_btn" payment="1" title="Boost Pay"><img src="images/payments/available.jpg" class="img60">
						  				</a>
						  				
						  			
						  			</td>
					  			</tr>
									<?php }?>
									<?php if($grab_check == "1"){?>
					  			<tr>
					  				<td><h5 class="payment_title">Grab Pay</h5></td>
						  			<td><img src="images/payments/grab.jpg" class="img60"></td>
						  			<td>
						  				
						  				<a href="#" data-toggle="modal" data-target="#paymentModal" user="<?= $merchant_id;?>" class="payment_btn" payment="2" title="Grab Pay"><img src="images/payments/available.jpg" class="img60">
						  				</a>
						  			
						  					
						  			</td>
					  			</tr>
								<?php }?>
									<?php if($wechat_check == "1"){?>
					  			<tr>
					  				<td><h5 class="payment_title">WeChat</h5></td>
						  			<td><img src="images/payments/wechat.jpg" class="img60"></td>
						  			<td>
						  			
						  				<a href="#" data-toggle="modal" data-target="#paymentModal" user="<?= $merchant_id;?>" class="payment_btn" payment="3" title="WeChat"><img src="images/payments/available.jpg" class="img60">
						  				</a>
						  			
						  			
						  			</td>
					  			</tr>
									<?php }?>
									<?php if($touch_check == "1"){?>
					  			<tr>
					  				<td><h5 class="payment_title">Touch & Go</h5></td>
						  			<td><img src="images/payments/touch.png" class="img60"></td>
						  			<td>
						  				
						  				<a href="#" data-toggle="modal" data-target="#paymentModal" user="<?= $merchant_id;?>" class="payment_btn" payment="4"title="Touch & Go"><img src="images/payments/available.jpg" class="img60">
						  				</a>
						  				
						  				
						  			</td>
					  			</tr>
								<?php }?>
					  			<?php if($fpx_check == "1"){?>
								<tr>
					  				<td><h5 class="payment_title">FPX</h5></td>
						  			<td><img src="images/payments/fpx.png" class="img60"></td>
						  			<td>
						  				
						  				<a href="#" data-toggle="modal" data-target="#paymentModal" user="<?= $merchant_id;?>" class="payment_btn" payment="5" title="FPX"><img src="images/payments/available.jpg" class="img60">
						  				</a>
						  			
						  			</td>
					  			</tr>
									<?php }?>
					  		</tbody>
					  	</table>
					  </div>  
					  	<?php } ?>
					  	
                         
                    </div>
                    <div class="modal-footer" style="padding-bottom:2px;">
                        <div class="row" style="margin: 0;">
			 
             
						  <div class="col" style="">
							
							<!--input type="submit" class="btn btn-primary online_ajax"  name="online_ajax" value="Confirm" style="float: right;"/!-->
							 <small id="register_error" style="display: none;color:#e6614f;">
							 
							</small>
							
						  </div>
						         
					   
						</div>
						  <small  class="skiponline"  style="color:#e6614f;font-size:14px;min-width:50px;">
						  <u class="skiponline">Skip</u>
						</small>    
						
                    </div>
                  
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
								
								<input type="password" id="forgot_register" class="form-control" name="forgot_register"/>
											
					   <i  onclick="myFunctionforgot()" id="eye_slash_forgot" class="fa fa-eye-slash" aria-hidden="true"></i>
					  <span id="eye_pass_forgot" onclick="myFunctionforgot()" > <?php echo $language['show_password']; ?> 
					 </span>
					  <small style="color:red;">(Please set passwords at least  6 digits and above)</small>
					   <small id="forgot_register_error" style="display: none;color:#e6614f;">
                 
                </small>
				
				<small id="forgot_reset_password_error" style="font-size:16px;display:none;color:#e6614f;"></small>
										
							  </div>
				</div>

					
			   

				</div>

				<div class="modal-footer forgot_footer">

						<div class="row" style="margin: 0;">

						<div class="col forgot_password_submit" style="padding: 0;margin: 5px;display:none;">

							<input type="submit" class="btn btn-primary forgot_reset_password"  name="login_ajax" value="Change Password" style="float: right;display:none;"/>

							

						</div>  



				   

						</div>

					<small  class="reg_pending skip" id='skip' style="color:#e6614f;font-size:14px;min-width:50px;">

					  <u class="btn btn-primary"><?php echo $language['skip']; ?> </u>

					</small>

					

			  </div>

			</div>

		</div>

	</div>

</div>
<div id="reviewdetailmodel" class="modal fade" role="dialog">
				  			<div class="modal-dialog">

							    <!-- Modal content-->
							    <div class="modal-content" id="review_model">
									 <div class="modal-header">
										  <button type="button" class="close" data-dismiss="modal">×</button>           
										  <h4 class="modal-title" id="see_or_write_review" style="font-size: 21px;">Write a review for <span id="order_product_name"></span></h4>
									   
									</div>

				  			
							   <div class="modal-body" id="reviewdetailmodel_load" style="padding-bottom:0px;margin-top: -22px;">
      <p style="font-size: 14px;" id="your_feedback">Your feedback is extermely important to us in order to provide best service to you</p>
      <!--center>
         <div class="row" >
            <div class="col-md-5"><button id="marchant_review_button" type="button" style="font-size: 12px;cursor: pointer;" name="marchant_review_button"  class="btn btn-primary">Review to Marchant</button></div>
            <div class="col-md-1"></div>
            <div class="col-md-5"><button id="deliveryman_review_button" type="button" style="font-size: 12px;cursor: pointer;" name="deliveryman_review_button" class="btn btn-secondary">Review to Deliveryman</button>
            </div>
      </center!-->
     
      <div id="merchant_review" style="font-size:14px;">
      <br> <center style="font-size: 14px;margin-top: -5px;text-align: center;font-weight: bold;"><b>Give feedback for service</b></center><hr>
        Q 1. Are you satisfied with the food quality?<br>
      <div class="row">
	  
      <div class="col-2 col-md-2 col-xs-2"><img id="review_q1_a1" src="assets\img\smile\laughing_green.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
      <div class="col-2 col-md-2 col-xs-2"><img id="review_q1_a2" src="assets\img\smile\happy_grey.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
      <div class="col-2 col-md-2 col-xs-2"><img id="review_q1_a3" src="assets\img\smile\surprised_grey.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
      <div class="col-2 col-md-2 col-xs-2"><img id="review_q1_a4" src="assets\img\smile\sad_grey.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
      <div class="col-2 col-md-2 col-xs-2"><img id="review_q1_a5" src="assets\img\smile\verysad_grey.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
      <div class="col-2 col-md-2 col-xs-2"></div>
      </div>
      <hr>
       Q 2. Are you happy with deliveryman service?<br> 
      <div class="row">
      <div class="col-2 col-md-2 col-xs-2"><img id="review_q2_a1" src="assets\img\smile\laughing_green.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
      <div class="col-2 col-md-2 col-xs-2"><img id="review_q2_a2" src="assets\img\smile\happy_grey.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
      <div class="col-2 col-md-2 col-xs-2"><img id="review_q2_a3" src="assets\img\smile\surprised_grey.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
      <div class="col-2 col-md-2 col-xs-2"><img id="review_q2_a4" src="assets\img\smile\sad_grey.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
      <div class="col-2 col-md-2 col-xs-2"><img id="review_q2_a5" src="assets\img\smile\verysad_grey.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
      <div class="col-2 col-md-2 col-xs-2"></div>
      </div>
		<hr>
      Q 3. Any additional comments? ?<br>
      <textarea class="form-control rounded-0" id="addiComments" rows="1"></textarea>
      <!--hr>
      Q 6. Do you allow us to contact you for further clarification ?<br>
      <input type="radio" name="clarification" value="No" checked>No</input>&nbsp;&nbsp;&nbsp;
      <input type="radio" name="clarification" value="Yes">Yes</input>
      <br!-->
      <p id="review_error" style="color: red;"></p>
      <br>
      </div> 
      <center><button id="review_check" type="button" name="review_check" onclick="checkNext()" style="color:black;"   class="btn btn-primary" order_id="">Feedback now</button>
      <button id="review_skip" type="button" onclick="skip_review()"   class="btn btn-primary" style="color:black;"    order_id="">Feedback later</button></center>
<br>	  
      </div>
   </div>
</div>
</div>
  <!-- Modal -->
     <div id="paymentModal" class="modal fade" role="dialog">
				  			<div class="modal-dialog">

							    <!-- Modal content-->
							    <div class="modal-content">
								<form  id="paymentdata" method="POST"  enctype="multipart/form-data" action="payment_submit.php">    
					      			<div class="modal-header">
					        			<button type="button" class="close" data-dismiss="modal">&times;</button>
					        			<h4 class="modal-title payment_header">Modal Header<img src="images/payments/boost.png"></h4>
					      				
					      			</div>
				      				<div class="modal-body" style="text-align: left;">
				      					<input type="hidden" value="" name="wallet" id="payment_type" class="payment_type">
				      				
				      					<input type="hidden" value="<?php echo $open_order_id;?>" name="payment_order_id">
					        			<h5 class="">Please pay to <span class="row">sdf</span></h5>
					        			<h5>Mobile Number +60 <span class="mobile"></span></h5>
					        			<h5>QR Code:</h5>
					        			<img class="qr_code_image">
					        			<h5 class="">Reference: <span class="reference"></span></h5>
										 <div class="form-group" style="width:70%;">
    <label for="pwd">Upload image (proof of payment):</label>
    <input type="file" class="form-control" name="paymentproff" id="paymentproff">
  </div>
					        			<button type="submit" class="btn btn-primary confirm_payment_btn"  style="margin-bottom: 10px;">I have paid to the merchant</button>
					        			<button type="button" class="btn btn-default different_method" data-dismiss="modal">I want to pay with another method</button>
										  <small  class="finalskip"  style="color:#e6614f;font-size:14px;min-width:50px;float:right;">
						  <u class="skiponline">Skip</u>
						</small>    
						
					      			</div>
					      			
					      	</form>
					    		</div>

				  			</div>
						</div>
  <div class="modal fade" id="SectionModel" role="dialog" style="">
   <div class="modal-dialog">
           <?php 

          

            ?>

            <!-- Modal content-->
            <div class="modal-content">
              <form method="post" action="sectionsave.php">
                <div class="modal-header">
                    <button type="button" class="close" id='sectionclose' data-dismiss="modal">&times;</button>
					<?php if($merchant_data['mian_merchant']){ ?>
                    <h4 class="modal-title">Main Merchant (<?php echo $merchant_data['mian_merchant'];?>)</h4>
					<?php } ?>
                </div>
                 
                    <div class="modal-body" style="padding-bottom:0px;">
					    <?php if(($new_order=="y") && ($show_alert=="y")){  if($total_rebate_amount>0 && $rebate_credited!='y'){?>
             <!--p style="font-size: 16px;color: red;"><span style='font-size:20px;'>&#128512;</span> Congratulations ! Your order has been sent to our kitchen!</p!-->
           		
					<p style="font-size: 16px;color: red;"><span style='font-size:20px;'>&#128512;</span> Congratulations ! your order is completed. Your rebate amount is RM <?php echo $total_rebate_amount;?>, which will be credited to your wallet after 3 working days.</p>
					
						<?php } else { ?>
						  <p style="font-size: 16px;color: red;"><span style='font-size:20px;'>&#128512;</span> Congratulations ! Your order has been completed and send to kitchen! just wait for your foods to be served</p>
			   
						<?php }}  ?>     
						<?php if($first_table_id=='' || $first_section_id==''){ ?>
                        <div class="col-md-12" style="text-align: center;">
						<?php if($first_table_id=='' && $merchant_data['table_on_orderlist']=="y"){ ?>
                         <span><strong> If you have found your table, kindly key in now:-</strong></span>
						<?php } else if($first_section_id=='' && $merchant_data['section_on_orderlist']){ ?>
						 <span><strong> If you have found your Section, kindly key in now:-</strong></span>
						<?php } ?>
                        
						 <?php if($first_section_id=='' && $merchant_data['section_on_orderlist']=="y"){ ?>
                         <center><div class=" col-md-6 form-group">
                            <label>Section :</label>
                             <select id='section_type' name="section_type" required  class="form-control" data-table-list-url="<?php echo $site_url; ?>/table_list.php">
			  
						  <?php 
						  foreach($sectionsList as $sectionId => $sectionName): ?>
							<?php
							  $isSelected = "";
							  if($section_id == $sectionId) {
								$isSelected = "selected";
							  }
							?>   
							<option value="<?php echo $sectionId; ?>" <?php echo $isSelected; ?>><?php echo $sectionName; ?></option>
						  <?php endforeach; ?>
						</select>
						 </div></center> <?php } if($first_table_id=='' && $merchant_data['table_on_orderlist']=="y"){ ?>
                          <center> <div class=" col-md-6 form-group">
                            <label>Table Number :</label>
                             <input type="text" class="form-control" id='table_booking' name="table_booking" value="" placeholder="Table Number" required>  
                          </div></center>
						 <?php } ?>
						 <?php if($offerdata['discp'] && $merchant_data['mian_merchant']){ ?>   
                          <p><strong><?php echo $offerdata['discp'];?> </strong></p>
						 <?php } ?>
                          <input type="hidden" name="merchant_id" id="merchant_id" value="<?php echo $merchant_data['mian_merchant'];?>">
                          <input type="hidden" name="more_order" id="more_order" value="moreOrder">
                          <input type="hidden" name="current_oid" value="">
                          <input type="hidden" name="order_id" value="<?php echo $user_order['id']; ?>">
                        </div>
						<?php  } ?>
                    </div>
					<?php if(($first_section_id=='' && $merchant_data['section_on_orderlist']=="y") || ($first_table_id=='' && $merchant_data['table_on_orderlist']=="y")){ ?>
                    <div class="modal-footer" style="padding-bottom:2px;">
                        <div> <img style="max-height:100px !important;" src="Drink-pink.png"></div>  
						<?php if($offerdata['discp'] && $merchant_data['mian_merchant']){ ?>   
                        <button type="submit" class="btn btn-primary" style="background-color: red;">Yes order with <?php echo $merchant_data['mian_merchant'];?>
						</button>
						<?php } else { ?>
						<button type="submit" class="btn btn-primary" style="float: right;">Confirm</button>
						
						<?php  } ?>
						  <!--small  class="finalskip"  style="color:#e6614f;font-size:14px;">
						  <u>Skip</u>
						</small!-->
						
                    </div>
					<?php } ?>
                </form>
            </div>
        </div>
  </div>
  <div class="modal fade" id="InternetModel" tabindex="-1" role="dialog" aria-hidden="true">
 <div class="modal-dialog" role="document">
     <div class="form-group">
	    <div class="modal-content">
		   <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			 <div class="modal-body">
					

                    <div class="" style="">

                        <h4 style="margin-top: 0px;">Banking Detail</h4>

                        <div class="row">
						
							<div class="col-md-12 pad0" style="font-size:18px">
							<p>Please pay Exact Amount to ： RM <span id="bank_merchant_order_total" style="color:red;font-size:18px;font-weight:bold"></span></p>
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
								<b><?php echo $language['label_enquiry']; ?>:</b> <a href="https://chat.whatsapp.com/LdvomJRqXoIG6aXqsf5PgX" target="_blank" style="font-size:12px"><img src="https://www.koofamilies.com/images/whatapp.png" style="max-width:23px;" />https://chat.whatsapp.com/LdvomJRqXoIG6aXqsf5PgX</a><br/>
							</div>
							
						
						
							<!--<div clas="form-group" style="margin-left:3%;font-size:15px;">
								

                                <div style="clear:both;"></div>
								<p> Please pay Exact Amount to ：</br>
										Name: Chong Woi Joon  </br>
									Bank name: Hong Leong Bank </br>
									Bank account : 22850076859 </br>
									<b style="font-size:18px;">Boostpay Number 6012-3115670</b> 
									
									</br>
									<b style="margin:0px">Touch & Go account ( Chong Woi joon): <b>+6012-3115670</b></b>
									<?php if($_SESSION["langfile"]=="chinese"){ echo "请写商家店名在“银行参考”";} else {?>
									    (Please write <span id="bank_merchant_name"></span>   in "bank reference")
									 <?php } ?>
								</br>
									 Enquiry:  <a href="https://api.whatsapp.com/send?phone=60123945670" target="_blank"> 60123945670 <img src="images/whatapp.png" style="max-width:32px;"/> </a>
									 </br>
								</p>  
							</div>-->

                      

                    </div>


                </div>

            </div>
		</div>  
	 </div>
 </div>
</div>
 <div class="modal fade" id="orderdetailmodel" role="dialog">						
							<div class="modal-dialog">
							<!-- Modal content-->		
							<div class="modal-content">	
							<div class="modal-header">	
							<button type="button" class="close" data-dismiss="modal">&times;</button>						
							<h4 class="modal-title">Order Detail</h4>	
							</div>					
							<form id ="orderdetailform">		
							<div class="modal-body" style="padding-bottom:0px;">
							<div class="col-sm-10" id="orderdata">						
											
							</div>						
												
							</form>						
							</div>						
							</div>						
							</div>
						</div>
<div class="modal fade" id="newuser_model" tabindex="-1" role="dialog" aria-labelledby="login_passwd_modal_title" aria-hidden="true">
  <div class="modal-dialog" role="document" >
    <div class="form-group">
      <div class="modal-content">
	    
        <div class="modal-header">
        
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
     
          <div class="modal-body">
             <div class="form-group">
              
			  <input type="hidden" id="user_mobile" value="<?php echo $user_order['user_mobile']; ?>"/>
			  <input type="hidden" id="user_id" value="<?php echo $user_order['user_id']; ?>"/>
			  <input type="hidden" id="order_id" value="<?php echo $user_order['id']; ?>"/>
			  <input type="hidden" id="system_otp"/>
			  <input type="hidden"  value='0' id="otp_count"/>
			     <p id="show_msg" style="font-size: 16px;color: red;"><span style='font-size:20px;'>&#128512;</span> 
				  <?php 
				   if(($total_rebate_amount>0)  && $rebate_credited!="y"){
					   $o_msg="Congratulations ! your order is completed. Your rebate amount is RM ".$total_rebate_amount.", which will be credited to your wallet after 3 working days.";
				   }
				   else
				   {
					   $o_msg="Congratulations ! Your order has been completed and send to kitchen! just wait for your foods to be served"; 
				   }
				  if(($new_order=="y") && ($show_alert=="y")){
					   if(($total_rebate_amount>0)  && $rebate_credited!="y"){
						   if($_SESSION['tmp_login'] && $_SESSION['login']==''){
							   echo "Congratulation, your order is completed. Please login to claim for your rebate of RM ".$total_rebate_amount." into your KOO Coin wallet.";
						   } else if($otp_verified=="n")
						   {
							echo "Congratulations! Your order is completed. Your rebate of RM ".$total_rebate_amount." will be credited after you have verified your telephone number through the below OTP code (sent to you through SMS)";
						   } else {
							  echo $o_msg;
						   }
					   }
					   else { echo "Congratulations ! Your order has been completed and send to kitchen! just wait for your foods to be served";}
				  }  else { echo "Congratulations ! Your order has been completed and send to kitchen! just wait for your foods to be served";}?>
				 </p>
			</div>
			<?php 
			function random_strings($length_of_string) 
			{ 
				$str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz'; 
				return substr(str_shuffle($str_result), 0, $length_of_string); 
			} 
			if($user_order['user_refferal_code']=='')
			{
				$user_refferal_code=random_strings(8);
				// "update users set user_refferal_code='$user_refferal_code' where id='$user_id'";
				mysqli_query($conn,"update users set user_refferal_code='$user_refferal_code' where id='$user_id'");
			}
			else
			{
				$user_refferal_code=$user_order['user_refferal_code'];
			}
			$ref_link=$site_url."/view_merchant.php?sid=".$user_order['merchant_mobile']."&r_code=".$user_refferal_code;  
			  
					 $share_link=urlencode("Hey use my link to place order on koofamilies $ref_link"); 
					 $share_link=$language['ref_1']." ".$user_order['row']." ".$language['ref_2']." ".$language['ref_3']."\n".$ref_link;
										$share_link=urlencode($share_link);
					?>
			<div class="form-group">
			    <p><b>Meanwhile,  Earn 4% rebate by sharing your experience by copying the following link and share to your friends
				</br><a href="<?php echo $ref_link; ?>" target="_blank"><?php echo $ref_link;?></a>.</b></br>
				<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $ref_link; ?>" target="_blank">
						  				<img src="https://cdn4.iconfinder.com/data/icons/social-messaging-ui-color-shapes-2-free/128/social-facebook-circle-512.png"  style="max-width:9%;" alt="" />
				</a>
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   
										
										 <a href="https://wa.me/?text=<?php echo $share_link; ?>"
        data-action="<?php echo urlencode($ref_link); ?>"
        target="_blank">   
										
										 	<img src="https://e7.pngegg.com/pngimages/10/271/png-clipart-iphone-whatsapp-logo-whatsapp-call-icon-grass-mobile-phones.png"  style="max-width:13%;" alt="" />
										</a>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href="https://twitter.com/intent/tweet?url=<?php echo urlencode($ref_link); ?>&via=getboldify&text=<?php echo $share_link; ?>" target="_blank">  
<img src="https://cdn3.iconfinder.com/data/icons/social-media-circle/512/circle-twitter-512.png" style="max-width:13%;"/> </a>

				
										
											</br>
										Or become our salesman, earn money by sharing <!--<a href="comission_list.php?vs=<?php echo md5(rand());?>">(learn more)</a>-->
										</p>
										
			</div>
			 
			<div class="form-group otp_form" style="display:none;">
				<div id="divOuter">
					<div id="divInner">
					Otp code
						<input id="partitioned" type="Number" style="border:1px solid black;" maxlength="4" />
						   <!--small style="float:right;color:#28a745;display:none;" class="resend send_otp">Resend</small!-->
						 <small class="otp_error" style="display: none;color:#e6614f;">
							Invalid Otp code
						</small>
					</div>
				</div>
			</div>
				
			  <div class="login_passwd_field" style="display:none;">
                <label for="login_password">Password to login</label>
                <input  type="password" id="login_ajax_password" class="form-control" name="login_password" required/>
				
       <i  onclick="myFunction()" id="eye_slash" class="fa fa-eye-slash" aria-hidden="true"></i>
	  <span onclick="myFunction()" id="eye_pass"> Show Password </span>
 
			   <div style="clear:both"></div>	
				<span class="forgot_pass" style="color:#28a745;/*! float:right; */font-size:20px;text-align: center;text-decoration: underline;/*! width: 100% !important; */display: inline-block;margin-left: 18%;">
                  Reset/Create Password
                </span>
              
              </div>
			    <div class="forgot-form" style="display:none;">
				  <label for="login_password">Reset/Create  Password</label>
      <div class="input-group mb-2">
        <div class="input-group-prepend">
          <div class="input-group-text" style="background-color:#51D2B7;border-radius: 5px 0 0 5px;height: 100%;padding: 0 10px;display: grid;align-content: center;">+60</div>
        </div>
		
        <input  type="number" autocomplete="tel" maxlength='10' id="mobile_number" class="mobile_number form-control" <?php if($check_number){ echo "readonly";} ?> value="<?php if($check_number){ echo $check_number;}  ?>" placeholder="Phone number" name="mobile_number" required="" />
       
	  </div>
	  <small id="forgot_error" style="display: none;color:#e6614f;">
		 Please Key in valid number
		</small>
      <img id="loader-credentials" src="<?php echo $site_url;?>img/loader.gif" style="display:none;width:40px;height:40px;grid-column-start: 2;grid-column-end: 3;"/>
    </div>
	<div class="row">
	   <p style="font-size: 16px;margin-bottom: 0px;margin-left:1%;">Download our app for future easy ordering</p>
		<a href="https://play.google.com/store/apps/details?id=com.app.koofamily" target="_blank">
			  <img style="max-height:40px;" src="google.png"/></a>
			  <a  href="https://apps.apple.com/us/app/id1491595615?mt=8" target="blank">
                                <img style="max-width:140px;max-height:40px;" src="appstore.png" alt="">
                            </a>
			</div>
			  
          </div>  
          <div class="modal-footer login_footer">
            <div class="row" style="margin: 0;">
			 
             
              <div class="col otp_fields join_now" style="padding: 0;margin: 5px;display:none;">
                
                <input type="submit" class="btn btn-primary login_ajax"  name="login_ajax" value="Confirm" style="float: right;display:none;"/>
				 <small id="login_error" style="display: none;color:#e6614f;">
                 
                </small>
				
              </div>
			  <div class="col otp_fields forgot_now" style="padding: 0;margin: 5px;display:none;">
                <input type="submit" class="btn btn-primary forgot_reset"   value="Reset" style="width:50%;float: right;display:none;"/>
              </div>         
           
            </div>
			 <small  class="reg_pending skip" id='skip' style="color:#e6614f;font-size:14px;min-width:50px;">
                  <u class="btn btn-primary">Got it !</u>
                </small>
				 <small  class="reg_pending register_skip skip" style="color:#e6614f;font-size:14px;display:none;min-width:50px">
                  <u class="btn btn-primary">Got it !</u>
                </small>
			
          </div>
		  
      </div>
    </div>
  </div>
</div>
   <a href="https://chat.whatsapp.com/FdbA1lt6YQVBNDeXuY7uWd" target="_blank"><img src ="images/iconfinder_support_416400.png" style="width:75px;height:75px;position: fixed;left:15px;bottom: 70px;z-index:999;"></a>
 <link rel="stylesheet" href="css/fancybox.css" type="text/css" media="screen" />
   <script type="text/javascript" src="js/fancybox.js"></script>

   </body>
</html>
  <script>
  function myFunctionforgot() {
  var x = document.getElementById("forgot_register");
  if (x.type === "password") {
    x.type = "text";
	    $("#eye_pass_forgot").html('Hide Password');
			 $('#eye_slash_forgot').removeClass( "fa-eye-slash" );
            $('#eye_slash_forgot').addClass( "fa-eye" );
			
  } else {
    x.type = "password";
	 $("#eye_pass_forgot").html('Show Password');
	  $('#eye_slash_forgot').addClass( "fa-eye-slash" );
            $('#eye_slash_forgot').removeClass( "fa-eye" );
  }
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
  var x = document.getElementById("login_password");
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
<?php $login_user_id=$_SESSION['login']; ?>
<script type="text/javascript">
   $(document).ready(function(){
	   var s_token=generatetokenno(16);
	var r_url="https://www.koofamilies.com/index.php?vs="+s_token;
	    	 var myDynamicManifest = {

   "gcm_sender_id": "540868316921",

   "icons": [

		{

		"src": "https://koofamilies.com/img/logo_512x512.png",

		"type": "image/png",

		"sizes": "512x512"

	  }

	  ],

	  "short_name":'koofamilies Pos System',

	  "name": "One stop centre for your everything",

	  "background_color": "#4A90E2",

	  "theme_color": "#317EFB",

	  "orientation":"any",

	  "display": "standalone",

	  "start_url":r_url

	} 
	const stringManifest = JSON.stringify(myDynamicManifest);

	const blob = new Blob([stringManifest], {type: 'application/json'});

	const manifestURL = URL.createObjectURL(blob);

	document.querySelector('#my-manifest-placeholder').setAttribute('href', manifestURL);

	
	
	

if ('serviceWorker' in navigator) {
  window.addEventListener('load', function() {
    navigator.serviceWorker.register('/sw_new.js').then(function(registration) {
      // Registration was successful
      console.log('ServiceWorker registration successful with scope: ', registration.scope);
    }, function(err) {
      // registration failed :(
      console.log('ServiceWorker registration failed: ', err);
    });
  });
}
	    var new_order='<?php echo $new_order; ?>';
	    var new_order='<?php echo $new_order; ?>';
		var show_pop='<?php echo $show_pop; ?>';
		var already_login='<?php echo $login_user_id; ?>';
		var newuser='<?php echo $newuser; ?>';
		var show_alert='<?php echo $show_alert; ?>';
		// alert(show_pop);
		nextstep();
		// if(show_pop=="y")
		// {
			// $('#SectionModel').modal('show');
			
		// }
		// else
		// {
			// nextstep();
		// }
	    // alert(new_order);
		  // nextstep();
		// alert($('#SectionModel').hasClass( "show"));
		
				
		
	
		
   });
  	   $(".forgot_reset").click(function(){
              $(this).hide();
		    $(this).removeClass(" btn-primary").addClass("btn-default");

		 setTimeout(function() {

			

         $(this).removeClass("btn-default").addClass("btn-primary");

			}.bind(this), 5000);

		   // var usermobile=$("#mobile_number").val();
          var mobile=$("#mobile_number").val();
			if(mobile[0]==0)
			 {
				 mobile=mobile.slice(1);
			 }
		    var usermobile="60"+mobile;

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

					$("#otp_count").val(otp_count);
					$("#system_otp").val(data.otp);   
					$('#newmodel_check').modal('hide'); 
					$('#forgot_msg_new').show();
					$('.forgot_otp_form').show();
					$('.forgot_footer').show();
					$('#newuser_model').modal('hide'); 
					$('#forgot_setup').modal('show'); 

				  }

				  else

				  {
						 $(this).show();
					  $(".forgot_reset").show();

					  $(".forgot_error").html(data.msg);

					  $(".forgot_error").show();

					  $(".forgot_now ").hide();

				  }

				  

				}		  

		  });

	   });

 $(".forgot_pass").click(function(){

				var mobile_no=$('#mobile_number').val();
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
   $(".send_otp").click(function(){
	  // $(".resend").hide();
	  
	  var otp_count=$("#otp_count").val();
	  if(otp_count<3)
	  {
	  var usermobile=$("#user_mobile").val();
	  var user_id="<?php echo $user_id; ?>";
	  // var usermobile=+919001025477;
	   //target:'#picture';
	    // $(this).hide();
		var data = {usermobile:usermobile, method: "sendotpwithlink",user_id:user_id};  
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
				  
				  $("#otp_count").val(otp_count);
				  $("#system_otp").val(data.otp);
				 $(".otp_form").show(); 
			  }
			  
			}		  
	  });
	   // setTimeout(function () {
						// $(".resend").show();
					// }, 15000);
	  }
	  else
	  {
		  // $('#register_error').html('You Extend Send Otp limit');
		  // $('#register_error').show();
	  }
	 
  });
   $(".skiponline").click(function(){
     var s_token=generatetokenno(16);
						var r_url="https://www.koofamilies.com/orderlist.php?vs="+s_token;
						window.location.replace(r_url);
   });
   $(".finalskip").click(function(){
	   // alert(3);
	 $('#newuser_model').modal('hide');
	 $('#SectionModel').modal('hide');
	 $('#PasswordModel').modal('hide');
		});
		$("#sectionclose").click(function(){
	  // alert(show_pop);
			nextstep();
		});
  $(".skip").click(function(){
	  $('#newuser_model').modal('hide');
	  // alert(show_pop);
			// nextstep();
		});
		$(".different_method").click(function(){
			$("#OnlineModel").modal("show");
			$("#paymentModal").modal("hide");
		});
	    $(".confirm_payment_btn").click(function(e){
		var payment = $(".payment_type").val();
		// alert(payment);
		if(payment == "1"){
			payment = "Boost Pay";
		}
		 
		$(".text_payment").val(payment);
		$("#paymentModal").modal("hide");
		 // document.getElementById("paymentdata").submit();
	});
	     $(".payment_btn").click(function(e) {
			 // alert(3);
			e.preventDefault();
		 var title = $(this).attr('title');
		 var payment = $(this).attr('payment');
		
			var user='<?php echo $merchant_id; ?>';
				$(".payment_type").val(payment);
	  // var action = $(this).val() == "MYR" ? "wallet_pay.php" : "payment.php";
	  // $("#data").attr("action", action);
	   // alert(payment);
	   	$(".payment_type").val(payment);
		if((payment=='1') || (payment=="2") || (payment=="3") || (payment=="4") || (payment=="5"))
		{
			// alert(payment);
			var title = $(this).attr('title');
			// alert(title);
		     $.ajax({
            url : 'orderlist.php',
            type : 'post',
            dataType : 'json',   
            data: {payment: payment, user:user, method: "getPayment"},
            success: function(data){
				$("#OnlineModel").modal("hide");
				// alert(payment);
            	if(payment == "1"){
            		$image = "boost.png";
            	} else if(payment == "2"){
            		$image = "grab.jpg";
            	} else if(payment == "3"){
            		$image = "wechat.jpg";
            	} else if(payment == "2"){
            		$image = "touch.png";
            	} else if(payment == "5"){
            		$image = "fpx.png";
            	}
				// var title = $(".text_payment").find(':selected').attr('title'); 
				
            	$(".payment_header").html(title + "&nbsp <img style='width:90px;' src='images/payments/"+$image+"'>");
            	$(".row").html(data['name']);
            	$(".mobile").html(data['mobile']);
            	$(".qr_code_image").attr({"src": "uploads/"+data['qr_code']});
            	$(".reference").html(data['remark']);
				
				// alert(title);
				$('#payment_type').val(title);
				$("#paymentModal").modal("show");
            }
			}); 	
		}			

		
		
	});
		$("select.text_payment").change(function(e) {
			e.preventDefault();
		var payment=$(this).val();
		// alert(payment);
		var title = $(".text_payment").find(':selected').attr('title');   
		// var title = $(this).attr('title');
			// alert(title);
			var user='<?php echo $merchant_id; ?>';
				$(".payment_type").val(payment);
	  // var action = $(this).val() == "MYR" ? "wallet_pay.php" : "payment.php";
	  // $("#data").attr("action", action);
	   // alert(payment);
	   	$(".payment_type").val(payment);
		if((payment=='1') || (payment=="2") || (payment=="3") || (payment=="4") || (payment=="5"))
		{
			// alert(payment);
			
		     $.ajax({
            url : 'orderlist.php',
            type : 'post',
            dataType : 'json',
            data: {payment: payment, user:user, method: "getPayment"},
            success: function(data){
				$("#OnlineModel").modal("hide");
				// alert(payment);
            	if(payment == "1"){
            		$image = "boost.png";
            	} else if(payment == "2"){
            		$image = "grab.jpg";
            	} else if(payment == "3"){
            		$image = "wechat.jpg";
            	} else if(payment == "2"){
            		$image = "touch.png";
            	} else if(payment == "5"){
            		$image = "fpx.png";
            	}
				var title = $(".text_payment").find(':selected').attr('title'); 
            	$(".payment_header").html(title + "&nbsp <img style='width:90px;' src='images/payments/"+$image+"'>");
            	$(".row").html(data['name']);
            	$(".mobile").html(data['mobile']);
            	$(".qr_code_image").attr({"src": "uploads/"+data['qr_code']});
            	$(".reference").html(data['remark']);
				var title = $(".text_payment").find(':selected').attr('title');  
				// alert(title);
				$('#payment_type').val(title);
				$("#paymentModal").modal("show");
            }
			}); 	
		}			

		
		
	});
		function nextstep()
		{
			
			var online_pay='<?php echo $online_pay; ?>';
			var prepaid='<?php echo $prepaid; ?>';
			var payment_alert='<?php echo $payment_alert; ?>';
			var show_alert='<?php echo $show_alert; ?>';
		    var new_order='<?php echo $new_order; ?>';
			var show_pop='<?php echo $show_pop; ?>';
			var already_login='<?php echo $login_user_id; ?>';
			var newuser='<?php echo $newuser; ?>';
		    var show_alert='<?php echo $show_alert; ?>';
		    var otp_verified='<?php echo $otp_verified; ?>';
		    var password_created='<?php echo $password_created; ?>';
			// alert(password_created);  
			// var new_order="y";
			// var show_alert="y";
			var membership_discount_input='<?php echo $membership_discount_input;?>';
			
			$('#SectionModel').modal('hide');
			// alert(membership_discount_input);
			if(membership_discount_input)
						{
							if(!already_login)
							{
								$('#show_msg').html('<span style="font-size:20px;">😀</span>Congratulation, your order is already completed. Please login in order to claim for your '+membership_discount_input+' membership discount and to use wallet');
							}
							else
							{
								$('#show_msg').html('<span style="font-size:20px;">😀</span>Congratulation, your order is already completed. You will get '+membership_discount_input+' as membership discount');
							}  
						}
			$('#SectionModel').modal('hide');
			// alert(show_alert);
			// alert(password_created);
			// alert(otp_verified);  
			
			if((password_created=="n") && (otp_verified=="y"))
			{
				 // alert('show');   
				$('#PasswordModel').modal('show');
			}
			
			if(new_order=="y")
			{
				
					// alert(show_alert);
				// alert(show_pop);
				if((show_alert=="y"))    
				{
					$('#newuser_model').modal('show'); 
				}   
			  
			   if((newuser=="y") && (show_alert=='y'))
			   {
				    
			   
					   
				   if(otp_verified=="n")
				   {
					   $('.otp_form').show();
					   var otp_count=$("#otp_count").val();
					    var usermobile=$("#user_mobile").val();
						var user_id="<?php user_id;?>";
						  // var usermobile=+919001025477;
						   //target:'#picture';
							// $(this).hide();
							// alert(usermobile);
							// return false;
							var data = {usermobile:usermobile, method: "sendotpwithlink",user_id:user_id};
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
						   // setTimeout(function () {
											// $(".resend").show();
										// }, 15000);
				   }
				   else
				   {
					   
				   }
			   }
			   if((newuser=="n") && (show_alert=='y'))
			   {
				   // alert(already_login);
				   if(!already_login)
					{
						<?php   unset($_SESSION['new_order']); ?>
					   $('.login_passwd_field').show();
					   $('.join_now').show();   
					   $('.login_ajax').show();
					   // $(".forgot-form").show();
					}
			   }
					
				
				
			   
		   }
		   
		   
		}
   $(".login_ajax").click(function(){
		   $(this).removeClass(" btn-primary").addClass("btn-default");
		 setTimeout(function() {
			
         $(this).removeClass("btn-default").addClass("btn-primary");
			}.bind(this), 5000);
		    var usermobile=$("#user_mobile").val();
		    var login_password=$("#login_ajax_password").val();
			var membership_applicable='<?php echo $membership_applicable;?>';
			var last_order_merchant_id='<?php echo $last_order_merchant_id;?>';
			var last_order_id='<?php echo $last_order_id;?>';
			 $("#login_error").hide();
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
							  var s_token=generatetokenno(16);
						var r_url="https://www.koofamilies.com/orderlist.php?vs="+s_token;
						window.location.replace(r_url);
							  
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
	     $(".review_detail").click(function(e){
			$("#order_product_name").html("");
			$("#see_or_write_review").html('');
			var s_id = $(this).attr('order_id');
		  var invoice_id = $(this).attr('invoice_id');
		  var review_status = $(this).attr('review_status');
		  if(review_status=="n")
		  {
				var r_text="Write review for invoice no: "+invoice_id;
				$("#see_or_write_review").html(r_text);
				//alert();
				$("#order_product_name").html("Invoice no: "+invoice_id);
				$("#order_product_button").attr("order_id",5);
				$("#review_check").attr("order_id",s_id);
				        $.ajax({
                type: "POST",
                url: "newreview.php",
                data: {s_id:s_id,login_as:1},
                success: function(data) {
                  $('#reviewdetailmodel_load').html(data);

                },
                error: function(result) {
                    alert('error');
                }
            });
				$("#reviewdetailmodel").modal("show"); 
		  }
		  else
		  {
			 
         
			  var s_id = $(this).attr('order_id');
		var r_text="Your past review for invoice no: "+invoice_id;
		$("#see_or_write_review").html(r_text);
        $.ajax({
                type: "POST",
                url: "getreview.php",
                data: {s_id:s_id,login_as:1},
                success: function(data) {
                  $('#reviewdetailmodel_load').html(data);

                },
                error: function(result) {
                    alert('error');
                }
            });
        $("#reviewdetailmodel").modal("show"); 
		  }
		
		  



	  });
	    $(".online_ajax").click(function(){
			var wallet=$("#text_payment option:selected" ).text();
			var payment_order_id=$("#order_id").val();
			var data = {wallet:wallet,payment_order_id:payment_order_id};
			$.ajax({
					  
					  url :'payment_submit.php',
					  type:'POST',
					  dataType : 'json',
					  data:data,
					  success:function(response){
						 
					var s_token=generatetokenno(16);
						var r_url="https://www.koofamilies.com/orderlist.php?vs="+s_token;
						window.location.replace(r_url);
						}		  
				  });
	   });
	   $(".bank_detail").click(function(e){
		    var row = $(this).attr('row');
			var final_odd_total = $(this).attr('final_odd_total');
			$('#bank_merchant_name').html(row);   
			$('#bank_merchant_order_total').html(final_odd_total);   
		    $("#InternetModel").modal("show"); 
	   });
	    $(".s_order_detail").click(function(e){
		  var s_id = $(this).attr('order_id');
		  $.ajax({
                        type: "POST",
                        url: "singleorder.php?u=y",
                        data: {s_id:s_id},
                        success: function(data) {
							$('#orderdata').html(data);
                        },
                        error: function(result) {
                            alert('error');
                        }
                });
		  $("#orderdetailmodel").modal("show"); 
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
					var user_id=<?php echo $user_id; ?>;
					var order_id=$("#order_id").val();
					
					var login_password=$("#login_password").val();  
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
							  var s_token=generatetokenno(16);
						var r_url="https://www.koofamilies.com/orderlist.php?vs="+s_token;
						window.location.replace(r_url);
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
$('.forgot_reset_password').click(function(){
		var forgot_register=$('#forgot_register').val();
		// alert(forgot_register);
		if((forgot_register.length)>5)
		{
			var mobile=$("#mobile_number").val();
			if(mobile[0]==0)
			 {
				 mobile=mobile.slice(1);
			 } 
			 var newpassword=$('#forgot_register').val();
			 var usermobile="60"+mobile;
			 if((newpassword.length)>5)
			{
			 var data = {usermobile:usermobile,method:"forgotresetpassword",password:newpassword};
			 $.ajax({
					  
					  url :'functions.php',
					  type:'POST',  
					  dataType : 'json',
					  data:data,
					  success:function(response){
							var data = JSON.parse(JSON.stringify(response)); 
						  if(data.status)
						  {
							  $("#forgot_reset_password_error").hide();
							var login_user_id=$('#login_user_id').val();
							var login_user_role=data.data.user_roles;
							localStorage.setItem('login_live_id',login_user_id);    
							localStorage.setItem('login_live_role_id',login_user_role);
							$('#login_for_wallet_id').val(login_user_id);
							 $('#forgot_setup').modal('hide');
							   
							$('#show_msg').html(data.msg);
							$('#AlerModel').modal('show'); 
							setTimeout(function(){ $("#AlerModel").modal("hide"); },2000); 
							 
						  }
						  else
						  {   
								$("#forgot_reset_password_error").html('Failed to Reset Password');
								$("#forgot_reset_password_error").show();
						  }
						  
						}		  
				  });
			}
			else
			{
				
				$("#forgot_register_error").html('Enter Atleaset 6 digit password');

				 $("#forgot_register_error").show();
			}
					
		}
		else
		{   
			$("#forgot_reset_password_error").html('Your New Password has to be 6 digit long');
			$("#forgot_reset_password_error").show();
		}
	});	   
	       $('#forgot_partitioned').on('keyup', function(){ // consider `myInput` is class...

  var user_input = $(this).val();
  // alert(user_input);
  var page_otp=$("#system_otp").val();
   var usermobile="60"+$("#mobile_number").val();
   // alert(user_input);
   // alert(page_otp);
  if(user_input.length % 4 == 0){
	  if(user_input==page_otp)
	  {
		  $('.forgot_otp_error').hide();
		$('#forgot_msg_new').html('<span style="font-size:20px;">😀</span>Please create your password');
		$('.forgot_otp_form').hide();
		$('.forgot_password_submit').show();   
		$('.forgot_reset_password').show();
		$('.forgot_register_password').show();
		
	  }
	  else
	  {
		  $('.forgot_otp_error').show();
	  }
   
  }
});
    $('#partitioned').on('keyup', function(){ // consider `myInput` is class...

  var user_input = $(this).val();
  // alert(user_input);
  var page_otp=$("#system_otp").val();
   var usermobile=$("#user_mobile").val();
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
					 
					  var s_token=generatetokenno(16);
						var r_url="https://www.koofamilies.com/orderlist.php?vs="+s_token;
						window.location.replace(r_url);
					  $('#newuser_model').modal('hide');   
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
</script>




<script>
setInterval(function(){ 
        
       

           var s_token=generatetokenno(16);
						var r_url="https://www.koofamilies.com/orderlist.php?vs="+s_token;
						window.location.replace(r_url);

        
    }, 
    60000);    

 </script>
<script type="text/javascript">
  
  
              //For Review Script
             var review_q1 = 0;
             var review_q2 = 0;
             var review_q3 = 0;
             var review_q4 = 0;
             var review_q5 = 0;
             var review_q6 = 0;
             var review_q7 = 0;
             var review_q8 = 0;
             var review_q9 = 0;
             var review_q10 = 0;

             var q1=1;
             var q2=1;
             var q3=1;
             var q4=1;
             var q5=1;
             var q6=1;
             var q7=1;
             var q8=1;
             
            function review_q1_clear() {
                $("#review_q1_a1").attr("src", "assets/img/smile/laughing_grey.png");
                $("#review_q1_a2").attr("src", "assets/img/smile/happy_grey.png");
                $("#review_q1_a3").attr("src", "assets/img/smile/surprised_grey.png");
                $("#review_q1_a4").attr("src", "assets/img/smile/sad_grey.png");
                $("#review_q1_a5").attr("src", "assets/img/smile/verysad_grey.png");
              }
                  function review_q2_clear() {
                $("#review_q2_a1").attr("src", "assets/img/smile/laughing_grey.png");
                $("#review_q2_a2").attr("src", "assets/img/smile/happy_grey.png");
                $("#review_q2_a3").attr("src", "assets/img/smile/surprised_grey.png");
                $("#review_q2_a4").attr("src", "assets/img/smile/sad_grey.png");
                $("#review_q2_a5").attr("src", "assets/img/smile/verysad_grey.png");
              }


              $(document).ready(function(){
                
              //update image on click
              //q1
              $("#review_q1_a1").click(function(){
                  review_q1_clear();
                  review_q1=1;
                  q1=1;
                  $("#review_q1_a1").attr("src", "assets/img/smile/laughing_green.png");
              });
              $("#review_q1_a2").click(function(){
                  review_q1_clear();
                  review_q1=1;
                  q1=2;
                  $("#review_q1_a2").attr("src", "assets/img/smile/happy_green.png");
              });
               $("#review_q1_a3").click(function(){
                  review_q1_clear();
                  review_q1=1;
                  q1=3;
                  $("#review_q1_a3").attr("src", "assets/img/smile/surprised_green.png");
              }); 
               $("#review_q1_a4").click(function(){
                  review_q1_clear();
                  review_q1=1;
                  q1=4;
                  $("#review_q1_a4").attr("src", "assets/img/smile/sad_green.png");
              }); 
               $("#review_q1_a5").click(function(){
                  review_q1_clear();
                  review_q1=1;
                  q1=5;
                  $("#review_q1_a5").attr("src", "assets/img/smile/verysad_green.png");
              });
               //q2
                $("#review_q2_a1").click(function(){
                  review_q2_clear();
                  review_q2=1;
                  q2=1;
                  $("#review_q2_a1").attr("src", "assets/img/smile/laughing_green.png");
              });
              $("#review_q2_a2").click(function(){
                  review_q2_clear();
                  review_q2=1;
                  q2=2;
                  $("#review_q2_a2").attr("src", "assets/img/smile/happy_green.png");
              });
               $("#review_q2_a3").click(function(){
                  review_q2_clear();
                  review_q2=1;
                  q2=3;
                  $("#review_q2_a3").attr("src", "assets/img/smile/surprised_green.png");
              }); 
               $("#review_q2_a4").click(function(){
                  review_q2_clear();
                  review_q2=1;
                  q2=4;
                  $("#review_q2_a4").attr("src", "assets/img/smile/sad_green.png");
              }); 
               $("#review_q2_a5").click(function(){
                  review_q2_clear();
                  review_q2=1;
                  q2=5;
                  $("#review_q2_a5").attr("src", "assets/img/smile/verysad_green.png");
              });


               



            });
          
          $("#marchant_review_button").click(function(){
              $("#merchant_review").css("display", "block");
              $("#deliveryman_review").css("display", "none");

               if ( $("#merchant_review_button").hasClass('btn-secondary') )  
                $("#merchant_review_button").addClass('btn-primary').removeClass('btn-secondary');

               if ( $("#deliveryman_review_button").hasClass('btn-primary') )  
                $("#deliveryman_review_button").addClass('btn-secondary').removeClass('btn-primary');

              

          });

          $("#deliveryman_review_button").click(function(){
              $("#merchant_review").css("display", "none");
              $("#deliveryman_review").css("display", "block");
               $("#your_feedback").css("display", "none");

               if ( $("#merchant_review_button").hasClass('btn-primary') )  
                $("#merchant_review_button").addClass('btn-secondary').removeClass('btn-primary');

               if ( $("#deliveryman_review_button").hasClass('btn-secondary') )  
                $("#deliveryman_review_button").addClass('btn-primary').removeClass('btn-secondary');
              
          });
         function skip_review(){
           var s_id = $("#review_check").attr('order_id');
           $.ajax({
                      
                      url :'skiped_review.php',
                      type:'POST',
                      data:{
                              order_id : s_id,
                            },
                      success:function(response){
                        $('#reviewdetailmodel').modal('hide');
                        }     
                    });
          
         }
       //nikhil-->
          function checkNext(){
              var s_id = $("#review_check").attr('order_id');
			  // alert(s_id);
              $("#review_error").html("");
			  $('#review_check').hide();
			  $('#review_skip').hide();
              
            var ele = document.getElementsByName('clarification'); 
            var q9;
            for(i = 0; i < ele.length; i++) { 
                if(ele[i].checked) 
               q9=ele[i].value; 
          }
            var q10 = $.trim($("#addiComments").val());
            
              $.ajax({
                      
                      url :'review_insert.php',
                      type:'POST',
                      data:{
                              order_id : s_id,
                              q1 : q1,
                              q2 : q2,
                              q3 : q2,
                              q4 : q4,
                              q5 : q5,
                              q6 : q6,
                              q7 : q7,
                              q8 : q8,
                              q9 : q9,
                              remark : q10,

                            },  
                      success:function(response){
                        $("#review_model").html("<center><p style='color:green;'>Thanks for Review to improve our System. We appreciate your feedback.</p></center>"); 
                        $("#review_check").css("display", "none");
                        
                        // setTimeout(function(){
							// var s_token=generatetokenno(16);
						// var r_url="https://www.koofamilies.com/orderlist.php?vs="+s_token;
						// window.location.replace(r_url);
                        // }, 5000);

                        }     
                    });   
          }
</script>
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
        msLeft = endTime - (+new Date);
		if ( msLeft < 1000 ) {
            element.innerHTML = "0:00";
			$(".hurry_div").html('');
			$(".hurry_div").css('background-color','none');
        } else {
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
jQuery(document).ready(function(){
	countdown( "ten-countdown", <?php echo $chk_time;?>, 0 );
});
</script>

 
<!--nikhil--->


<!--payment proof--->
<script>
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

	  /*var reader = new FileReader();
	  reader.onload = function(e) {
		// get loaded data and render thumbnail.
		document.getElementById("preview").src = e.target.result;
	  };
	  // read the image file as a data URL.
	  reader.readAsDataURL(this.files[0]);*/
});


$(document).ready(function(e) {
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
				$.ajax({
				  type: "POST",
				  url: "action_image_ajax.php",
				  data: formData,
				  //data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
				  contentType: false, // The content type used when sending data to the server.
				  cache: false, // To unable request pages to be cached
				  processData: false, // To send DOMDocument or non processed data file it is set to false
				  success: function(data) {
					  location.reload(true);
					  //$("#image-form_"+orderid).hide();
					  //$(".bank_detail").append('<label class="btn-sm btn-yellow" style="background-color:#6ea6d6;margin-top:10px;width:150px">Payment Proof<a href="javascript:void(0)" class="delete_paymentproof" orderid='+orderid+'><i class="fa fa-trash" style="margin-left:20px"> </i></a></label>');
					/*if (data == 1 || parseInt(data) == 1) {
					  $("#msg").html(
						'<div class="alert alert-success"><i class="fa fa-thumbs-up"></i> Data updated successfully.</div>'
					  );
					} else {
					  $("#msg").html(
						'<div class="alert alert-info"><i class="fa fa-exclamation-triangle"></i> Extension not good only try with <strong>GIF, JPG, PNG, JPEG</strong>.</div>'
					  );
					}*/
				  },
				  error: function(data) {
					$("#msg").html(
					  '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> There is some thing wrong.</div>'
					);
				  }
				});
			}
			
});

$(".delete_paymentproof").click(function(){
	var orderid = $(this).attr('orderid');
	var cnfrmDelete = confirm("Are You Sure want to delete this payment proof?");
	if(cnfrmDelete==true){
		  $.ajax({
			url:'orderlist.php',
			method:'GET',
			data:{
				data:'deleteRecord',
				orderid:orderid
				},
			success:function(res){location.reload(true);}
		  });	
	}
});

$(document).ready(function() {
	$(".fancybox").fancybox({
		openEffect	: 'none',
		closeEffect	: 'none'
	});
});  

});
		
		
</script>
<!-- End Payment proof-->