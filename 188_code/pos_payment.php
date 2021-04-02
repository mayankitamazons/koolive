<?php
include("config.php");
$profile_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$_SESSION['login']."'"));
	$_SESSION['mm_id']= $_SESSION['login']; //merchant id
    $date = date('Y-m-d H:i:s');
	$location =$_POST['location'];

	 $table_type =$_POST['table'];
	// $cust_id =$_POST['cust_id'];
	 $section_type =$_POST['Section'];
	 $pro_id = implode(',',$_POST['pro_id']);
	$name = implode(',', $_POST['name']);
	$remark_val = implode(',', $_POST['remark_val']);
	 $price = implode(',', $_POST['price']);
	 $qty = implode(',', $_POST['qty']);
	 $varient = implode(',',$_POST['subpro_id']);
	  $p_code = implode(',',$_POST['p_code']);
     $p_code = implode(',',$_POST['p_code']);	 
	 $remark_val = implode(',',$_POST['remark_val']);

	 $m_id=$_SESSION['mm_id'];
 	$sql = "SELECT MAX(invoice_no) invoice_no
            FROM order_list
            WHERE merchant_id = '".$m_id."'";
	$invoice_no = mysqli_fetch_assoc(mysqli_query($conn, $sql))['invoice_no'];
	if($invoice_no == NULL) $invoice_no = 1;
	else $invoice_no += 1;


	$sqlFinalIns = "INSERT INTO order_list SET product_id='$pro_id',  user_id='$m_id', merchant_id='$m_id', quantity='$qty', amount='$price', remark='$remark_val', location='test', table_type='".$table_type."',section_type='$section_type',created_on='$date', invoice_no='$invoice_no',varient_type='$varient',product_code='$p_code'";
	      $test_method = mysqli_query($conn, $sqlFinalIns);
	    	if($test_method)
	    	{
	    		header("Location: ".$site_url."/orderview.php");
	    	}  
	       else{
	       	return false;
	       }


















?>