<?php
include("config.php");
// print_R($_FILES);
// print_R($_POST);
// die;  
$profile_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$_SESSION['login']."'"));

if( $_POST['wallet'] == "MYR" )
{
	$verify_code = addslashes($_POST['verify_code']);
	$o_id = addslashes($_POST['o_id']);
	$m_id = addslashes($_POST['m_id']);
	$amount = addslashes($_POST['amount']);
	$wallet = 'MYR';
	$balance = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$_SESSION['login']."'"));
	$m_balance = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$m_id."'"));


	if(isset($balance['fund_password']))
	{

		if($balance['fund_password'] != $verify_code)
		{
			$error .= "Verification Code is Invalid.<br>";
			$flag = true;
		}

	}
 if($balance['balance_myr'] < $amount)
		{
			echo $error .= "Insufficient Balance In Your Wallet, Recharge Your Wallet First.";
			$flag = true;
		}

	if($flag == false)
	{


			$sender_new_balance = $balance['balance_myr'] - $amount;
			$reciever_new_balance = $m_balance['balance_myr'] + $amount;
			mysqli_query($conn, "UPDATE users SET balance_myr='$sender_new_balance' WHERE id='".$_SESSION['login']."'");
			mysqli_query($conn, "UPDATE users SET balance_myr='$reciever_new_balance' WHERE id='$m_id'");
			mysqli_query($conn, "UPDATE order_list_temp SET wallet='$wallet' WHERE id='$o_id'");
			// $order_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT merchant_id FROM order_list_temp WHERE id='".$o_id."'"));
			 // $merchant_id=$order_data['merchant_id'];
             $merchant_id=$_POST['m_id'];
			$merchant_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$merchant_id."'"));
			 $push_id=$merchant_data['moengage_unique_id'];

			if ($push_id) {
				$result=exec("/usr/bin/python myscript.py");
			 $resultarray=explode(",",$result);
			 // print_R($resultarray);
			 // die;
			 if (count($resultarray)>0) {
				 // code...
				 $data['camp_name']=$camp_name=$resultarray[0];
				 $data['sign']=$sign=$resultarray[1];
				 $data['push_email']=$push_id;
				 $data['title']='Order Ready';
				 $data['message']='Congratulation! You have a new order. Please check your order list.';
				 $data['redirectURL']= $site_url .'/orderview.php';
				 include 'push.php';
				 $user = new Push();
				 $resultpush = $user->send_push($data);

			 }
			}

	}

}

else
{
	 if(isset($_FILES["paymentproff"]) && $_FILES["paymentproff"]["error"] == 0){
		
        $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg","JPEG" => "image/JPEG", "gif" => "image/gif", "png" => "image/png");
       $filename = $_FILES["paymentproff"]["name"];
        $filetype = $_FILES["paymentproff"]["type"];
        $filesize = $_FILES["paymentproff"]["size"];
		 $file_ext=strtolower(end(explode('.',$_FILES['paymentproff']['name'])));
      
        $payment_image= $filename;
  
        // Verify file extension
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if(!array_key_exists($ext, $allowed)) die("Error: Please select a valid file format.");
    
        // Verify file size - 5MB maximum
        //~ $maxsize = 5 * 1024 * 1024;
        //~ if($filesize > $maxsize) die("Error: File size is larger than the allowed limit.");
    
        // Verify MYME type of the file
        if(in_array($filetype, $allowed)){
            // Check whether file exists before uploading it
            if(file_exists("screenshot/" . $_FILES["paymentproff"]["name"])){
                echo $_FILES["paymentproff"]["name"] . " is already exists.";
            } else{
                move_uploaded_file($_FILES["paymentproff"]["tmp_name"], "/home/koofamilies/public_html/screenshot/" . $_FILES["paymentproff"]["name"]);
               // echo "Your file was uploaded successfully.";
            } 
        } else{
            echo "Error: There was a problem uploading your file. Please try again."; 
        }
    } 
	
	if(isset($_POST['guest_id']))
{
	$guest_user_id=$_POST['guest_id'];
	$order_id=$_POST['guest_order_id'];
	 // $order_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT merchant_id FROM order_list_temp WHERE id='".$order_id."'"));
			  // $merchant_id=$order_data['merchant_id'];
			  $merchant_id=$_POST['m_id'];
			 $merchant_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$merchant_id."'"));
			   $push_id=$merchant_data['moengage_unique_id'];
			 
			   if ($push_id) {
				 $result=exec("/usr/bin/python myscript.py");
				$resultarray=explode(",",$result);
				
				if (count($resultarray)>0) {
					// code...
					$data['camp_name']=$camp_name=$resultarray[0];
					$data['sign']=$sign=$resultarray[1];
					$data['push_email']=$push_id;
					$data['title']='New Order';
					$data['message']='Congratulation! You have a new order. Please check your order list.';
					$data['redirectURL']= $site_url . '/orderview.php';
					include 'push.php';
					$user = new Push();
					$resultpush = $user->send_push($data);
					// print_R($resultpush);
					// die;
				}
			 }
	$order1 = explode("_",$_POST['wallet']);

	 $wallet = $order1['0'];
   mysqli_query($conn, "UPDATE order_list_temp SET wallet='$wallet',payment_image='$payment_image' WHERE id='$order_id'");
	$session_id = session_id();
	$finalorder_total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `order_list_temp` WHERE session_id = '$session_id' "));
	  $varient_type = $finalorder_total['varient_type'];
	 $pro_id = $finalorder_total['product_id'] ;
	 $user_id = $finalorder_total['user_id'] ;
	 $m_id = $finalorder_total['merchant_id'] ;
	 $qty_list = $finalorder_total['quantity'] ;
	 $p_price = $finalorder_total['amount'] ;
	 $p_code = $finalorder_total['product_code'] ;
	 $option = $finalorder_total['remark'] ;
	 $location = $finalorder_total['location'] ;
	 $table_type = $finalorder_total['table_type'] ;
	  $section_type = $finalorder_total['section_type'] ;
	 $date = $finalorder_total['created_on'] ;
	 $payment_image = $finalorder_total['payment_image'] ;
	 $wallet = $finalorder_total['wallet'] ;
	  $user_name = $finalorder_total['user_name'] ;
	 $user_mobile = $finalorder_total['user_mobile'] ;
	 $invoice_no = $finalorder_total['invoice_no'] ;
	 if($user_id ==0){
	 $user_id = $_SESSION['login'];
	 }
	    
		$sql = "SELECT MAX(invoice_no) invoice_no
		FROM order_list
		WHERE merchant_id = '$m_id'";
		$invoice_no = mysqli_fetch_assoc(mysqli_query($conn, $sql))['invoice_no'];
		if($invoice_no == NULL) $invoice_no = 1;
		else $invoice_no += 1; 
		
		$vsql = "SELECT MAX(id) v_id FROM order_varient";
		$v_no = mysqli_fetch_assoc(mysqli_query($conn, $vsql))['v_id'];
		if($v_no == NULL) $v_no = 1;
		else $v_no += 1;
		
  if(empty($guest_user_id)){
      
     $guest_user_id =  $_SESSION['login'] ; 
  }
     $_SESSION['login']=$guest_user_id;
	 $sqlFinalIns = "INSERT INTO order_list SET user_name='$user_name',user_mobile='$user_mobile',payment_image='$payment_image',wallet='$wallet',varient_type='$varient_type',product_id='$pro_id',  user_id='$guest_user_id', merchant_id='$m_id', quantity='$qty_list', amount='$p_price',product_code='$p_code', remark='$option', location='".$location."', table_type='".$table_type."',section_type='$section_type',created_on='$date', invoice_no='$invoice_no'";
      $test_method = mysqli_query($conn, $sqlFinalIns);
	   $order_id = mysqli_insert_id($conn);
	  $v_array=explode("|",$varient_type);
	  $vs=0;
	  $parray=explode(",",$pro_id);
	  $v_order=1;
	  foreach($v_array as $vr)
		{
			$product_id=$parray[$vs];
			if($vr)
			{
				$v_match=$vr;
				$v_match = ltrim($v_match, ',');
				$sub_rows = mysqli_query($conn, "SELECT * FROM sub_products WHERE id  in ($v_match)");
				while ($srow=mysqli_fetch_assoc($sub_rows)){
					$v_id=$srow['id'];
					 mysqli_query($conn, "INSERT INTO order_varient SET product_id='$product_id', varient_id='$v_id', invoice_no='$invoice_no',order_id='$order_id',merchant_id='$m_id',v_order='$v_order',v_code='$v_no'");
						$v_no++;
					}  
			}
			$vs++;	
			$v_order++;
		}
	  $sqlDtemp = "DELETE FROM `order_list_temp` WHERE session_id = '$session_id'  ";
		mysqli_query($conn, $sqlDtemp);
		
	  
	if($_POST['member'] == '1'){
         header("Location: ".$site_url."/orderlist.php");
     } else {
		header("Location: " .$site_url . "/order_guest.php");
     }	
			 //header("Location: " .$site_url. "/order_guest.php");
}   
else
{
	   // print_R($_POST);
// die;
			$order = explode("_",$_POST['wallet']);
		   
			 $wallet_c = $order['0'];
			 $wallet_oid = $order['1'];
			 // $order_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT merchant_id FROM order_list_temp WHERE id='".$wallet_oid."'"));
			  // $merchant_id=$order_data['merchant_id'];
               $merchant_id=$_POST['m_id'];
			 $merchant_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$merchant_id."'"));
			  $push_id=$merchant_data['moengage_unique_id'];
				//die;
				// print_R($merchant_data);
			// die;
			 if ($push_id) {
				 $result=exec("/usr/bin/python myscript.py");
				$resultarray=explode(",",$result);
				
				if (count($resultarray)>1) {
					// code...
					$data['camp_name']=$camp_name=$resultarray[0];
					$data['sign']=$sign=$resultarray[1];
					$data['push_email']=$push_id;
					$data['title']='New Order';
					$data['message']='Congratulation! You have a new order. Please check your order list.';
					$data['redirectURL']=$site_url.'/orderview.php';
					include 'push.php';
					$user = new Push();
					$resultpush = $user->send_push($data);
					// print_R($resultpush);
					// die;
				}
			 }
            $session_id = session_id();
			// echo "UPDATE order_list_temp SET wallet='$wallet_c',payment_image='$payment_image' WHERE session_id='$session_id'";
			// die;
			 mysqli_query($conn, "UPDATE order_list_temp SET wallet='$wallet_c',payment_image='$payment_image' WHERE session_id='$session_id'");
			 
			 
		
		$finalorder_total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `order_list_temp` WHERE session_id = '$session_id' "));
        $varient_type = $finalorder_total['varient_type'] ;
		$pro_id = $finalorder_total['product_id'] ;
		$user_id = $finalorder_total['user_id'] ;
		$m_id = $finalorder_total['merchant_id'] ;
		$qty_list = $finalorder_total['quantity'] ;
		$p_price = $finalorder_total['amount'] ;
		$p_code = $finalorder_total['product_code'] ;
		$option = $finalorder_total['remark'] ;
		$location = $finalorder_total['location'] ;
		$table_type = $finalorder_total['table_type'] ;
		$section_type = $finalorder_total['section_type'] ;
		$date = $finalorder_total['created_on'] ;
		$invoice_no = $finalorder_total['invoice_no'] ;
		$wallet = $finalorder_total['wallet'] ;
		$payment_image = $finalorder_total['payment_image'] ;
          $user_name = $finalorder_total['user_name'] ;
	 $user_mobile = $finalorder_total['user_mobile'] ;
		$sql = "SELECT MAX(invoice_no) invoice_no
		FROM order_list
		WHERE merchant_id = '$m_id'";
		$invoice_no = mysqli_fetch_assoc(mysqli_query($conn, $sql))['invoice_no'];
		if($invoice_no == NULL) $invoice_no = 1;
		else $invoice_no += 1;
		$vsql = "SELECT MAX(id) v_id FROM order_varient";
		$v_no = mysqli_fetch_assoc(mysqli_query($conn, $vsql))['v_id'];
		// print_R($v_no);
		// die;
		if($v_no == NULL) $v_no = 1;
		else $v_no += 1;

if(empty($user_id)){  
        
        $user_id =  $_SESSION['login'] ; 
        }   
		
		$sqlFinalIns = "INSERT INTO order_list SET user_name='$user_name',user_mobile='$user_mobile',payment_image='$payment_image',wallet='$wallet',varient_type='$varient_type',product_id='$pro_id',  user_id='$user_id', merchant_id='$m_id', quantity='$qty_list', amount='$p_price',product_code='$p_code', remark='$option', location='".$location."', table_type='".$table_type."',section_type='$section_type',created_on='$date', invoice_no='$invoice_no'";
		$test_method = mysqli_query($conn, $sqlFinalIns);
		$order_id = mysqli_insert_id($conn);
	  $v_array=explode("|",$varient_type);
	  $vs=0;
	  $v_order=1;
	  $parray=explode(",",$pro_id);
	  foreach($v_array as $vr)
		{
			$product_id=$parray[$vs];
			if($vr)
			{
				$v_match=$vr;
				$v_match = ltrim($v_match, ',');
				$sub_rows = mysqli_query($conn, "SELECT * FROM sub_products WHERE id  in ($v_match)");
				while ($srow=mysqli_fetch_assoc($sub_rows)){
					$v_id=$srow['id'];
					// echo "INSERT INTO order_varient SET product_id='$product_id', varient_id='$v_id', invoice_no='$invoice_no',order_id='$order_id',merchant_id='$m_id',v_order='$v_order',v_code='$v_no'";
					 mysqli_query($conn, "INSERT INTO order_varient SET product_id='$product_id', varient_id='$v_id', invoice_no='$invoice_no',order_id='$order_id',merchant_id='$m_id',v_order='$v_order',v_code='$v_no'");
						$v_no++;	
					}  
			}
			$v_order++;						
			$vs++;						
		}
		$sqlDtemp = "DELETE FROM `order_list_temp` WHERE session_id = '$session_id'  ";
		mysqli_query($conn, $sqlDtemp);
		
        if($profile_data['user_roles'] !=  '') {
		header("Location: ".$site_url."/orderlist.php");
 } else {
    header("Location: " .$site_url . "/order_guest.php");
     
 }   
}

}




 //~ $u_id= $_SESSION['login'];
 //~ $m_id= $_POST['m_id'];
  //~ $wallet=$_POST['wallet'];
 //~ $amount= $_POST['amount'];
 //~ $o_id= $_POST['o_id'];
 //~ if($wallet == "wallet")
 //~ {
 //~ $balance = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$u_id."'"));
 //~ $m_balance = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$m_id."'"));



	//~ if(isset($balance['fund_password']))
	//~ {

		//~ if($balance['fund_password'] != $verify_code)
		//~ {
			//~ $error .= "Verification Code is Invalid.<br>";
			//~ $flag = true;
		//~ }

	//~ }
 //~ if($balance['balance_myr'] < $amount)
		//~ {
			//~ echo $error .= "Insufficient Balance In Your Wallet, Recharge Your Wallet First.";
			//~ $flag = true;
		//~ }
	//~ if($flag == false)
	//~ {
			//~ $sender_new_balance = $balance['balance_myr'] - $amount;
			//~ $reciever_new_balance = $m_balance['balance_myr'] + $amount;
			//~ mysqli_query($conn, "UPDATE users SET balance_myr='$sender_new_balance' WHERE id='$u_id'");
			//~ mysqli_query($conn, "UPDATE users SET balance_myr='$reciever_new_balance' WHERE id='$m_id'");
			//~ mysqli_query($conn, "UPDATE order_list SET wallet='$wallet' WHERE id='$o_id'");
	//~ }
//~ }
//~ else
//~ {
		//~ mysqli_query($conn, "UPDATE order_list SET wallet='$wallet' WHERE id='$o_id'");

//~ }

?>
