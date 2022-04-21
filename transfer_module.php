<?php
// session_start();
include("config.php");

if (empty($_SESSION["langfile"])) { $_SESSION["langfile"] = "english"; }
   require_once ("languages/".$_SESSION["langfile"].".php"); 
 $max_limit_msg=$language['trasfer_max_limit'];   
// die;
function generatePIN($digits = 6){
    $i = 0; //counter
    $pin = ""; //our default pin is blank.
    while($i < $digits){
        //generate a random number between 0 and 9.
        $pin .= mt_rand(0, 9999);
        $i++;
    }
    return $pin;
}  
function gw_send_sms($user,$pass,$sms_from,$sms_to,$sms_msg){           
    $query_string = "api.aspx?apiusername=".$user."&apipassword=".$pass;
    $query_string .= "&senderid=".rawurlencode($sms_from)."&mobileno=".rawurlencode($sms_to);
    $query_string .= "&message=".rawurlencode(stripslashes($sms_msg)) . "&languagetype=1";        
    $url = "http://gateway.onewaysms.com.au:10001/".$query_string;       
    $fd = @implode ('', file ($url));      
    if ($fd){                       
      if ($fd > 0) {
      //Print("MT ID : " . $fd);
      $ok = "success";
      }        
      else {
      print("Please refer to API on Error : " . $fd);
      $ok = "fail";
      }
    }else {                       
        // no contact with gateway                      
        $ok = "fail";       
    }           
    return $ok;  
} 
$c_label_name='';
$fixwallet = array("MYR", "INR", "CF");   

if (isset($_POST['mobile_number'])) {
    
	$mobile_number = $_POST['mobile_number'];
    $mobile_number_first = substr(trim($mobile_number), 0, 1);
	if($mobile_number_first == 0){
		$mobile_number = ltrim($mobile_number, 0);
	}
	if (substr($mobile_number, 0,2) === "60") {
			
	}
	else
	{
		$mobile_number="60".$mobile_number;
	}
	$multi_wallet = $_POST['multi_wallet'];
	$sender_id = $_POST['sender_id'];
	$wallet_type = $_POST['wallet_type'];
	$transfer_amount = $_POST['transfer_amount'];
	$wallet_merchant_id=$_POST['wallet_merchant_id'];
	$tmp = mysqli_query($conn, "SELECT user_roles,name,id,special_coin_name FROM users WHERE mobile_number = '$mobile_number'");

    if($multi_wallet && is_array($wallet_merchant_id)){
     
        $coin_name = [];
        foreach($wallet_merchant_id as $merchant_id){
            $coin_name[] = mysqli_fetch_assoc(mysqli_query($conn, "SELECT special_coin_name FROM users WHERE id='".$merchant_id."'"))['special_coin_name'];
        }
    }else{
        
        $coin_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT special_coin_name FROM users WHERE id='".$wallet_merchant_id."'"))['special_coin_name'];
    }
   
	if (mysqli_num_rows($tmp) > 0) {

		$buf = mysqli_fetch_assoc($tmp);
		$mer_id=$data->id = $buf['id'];
		$data->special_coin_name = $buf['special_coin_name'];
		 $rec_user_roles=$buf['user_roles'];  
		
		$sender = mysqli_query($conn, "SELECT mobile_number,name,user_roles,id, balance_inr, balance_myr, balance_usd,special_coin_name FROM users WHERE id = ".$sender_id);
		if (mysqli_num_rows($sender) > 0) { 
			$sender = mysqli_fetch_assoc($sender);
           
			if ($sender['balance_myr'] == '') {
				$data->MYR = 0;
			}
			else {
				$data->MYR = $sender['balance_myr'];
			}
			if ($sender['balance_usd'] == '') {
				$data->CF = 0;
			} else {
				$data->CF = $sender['balance_usd'];
			}
			if ($sender['balance_inr'] == '') {
				$data->CF = 0;
			} else {
				$data->INR = $sender['balance_inr'];
			}
			$data->new_coin_trasfer="n";
			$sender_user_role=$sender['user_roles'];
            if($multi_wallet == 'true'){
                $s_coin_name = [];
                $s_bal = [];
                foreach($wallet_merchant_id as $merchant_id){
                    if($merchant_id==$sender_id){
                       
                        $s_current_coin_name=$data->$s_current_coin_name = $sender['special_coin_name'];
                        if($sender['balance_usd'] < $wallet_type[$s_current_coin_name]){
                            $res['status'] = false;
                            $res['data'] = '';
                            $res['msg'] = 'Insufficient balance in wallet ' . $s_current_coin_name;
                            echo json_encode($res);
                            die;
                        }
                    }
                    else{
                        $sq = mysqli_query($conn, "select special_coin_wallet.*,m.special_coin_name from special_coin_wallet  inner join users as m on m.id=special_coin_wallet.merchant_id where merchant_id='$merchant_id' and user_id='$sender_id'");
                        while ($swallet = mysqli_fetch_assoc($sq)){
                            $s_coin_name[] = $swallet['special_coin_name'];
                            $s_current_coin_name = $swallet['special_coin_name'];
                            $s_bal[] = $swallet['coin_balance'];
                            $data->$s_current_coin_name = $swallet['coin_balance'];
                            if($swallet['coin_balance'] < $wallet_type[$s_current_coin_name]){
                                $res['status'] = false;
                                $res['data'] = '';
                                $res['msg'] = 'Insufficient balance in wallet ' . $s_current_coin_name;
                                echo json_encode($res);
                                die;
                            }
                        }
                }

                }
               
            }else{
                 $sq="select special_coin_wallet.*,m.special_coin_name from special_coin_wallet  inner join users as m on m.id=special_coin_wallet.merchant_id where merchant_id='$wallet_merchant_id' and user_id='$sender_id'";
                
                $sub_rows = mysqli_query($conn,$sq);
                if(mysqli_num_rows($sub_rows)>0){
                    while ($swallet=mysqli_fetch_assoc($sub_rows)){
                        $s_coin_name = $swallet['special_coin_name'];
                        $s_bal = $swallet['coin_balance'];
                        $data->$s_coin_name=$swallet['coin_balance'];
                        if($s_bal < $_POST['transfer_amount']){
                            $res['status']=false;
                            $res['data']='';
                            $res['msg']='Insufficient balance';
                            echo json_encode($res);
                            die;
                        }
                    }
                }
            }
           
            if (!in_array($wallet_type,$fixwallet)) {
                if($multi_wallet){
                    $total_bal = 0;
                    foreach($s_bal as $bal){
                        $total_bal += $bal;
                    }
                }else{
                    $total_bal = $s_bal;
                }
            }

            

            if($data)
			{
				if($buf['name'])
					$data->reciver_label=$buf['name'];
				else
					$data->reciver_label=$mobile_number;
				$data->transfer_amount=$_POST['transfer_amount'];
				$data->sender_label=$coin_name;
				$data->remark_label=$remark;
				$data->time_label=date('h:i A')." on ".date('d/m/Y');
				$res['status']=true;
				$res['data']=$data;
				$res['msg']='Can Trasfer to that mobile';
			}
			else
			{
				$res['status']=false;
				$res['data']='';
			}
			
			
		}else{
			// echo -1;
			$res['status']=false;
			$res['data']='';
			$res['msg']='Invalid Sender Detail';
		}
		// print_r($tmp);
	} else {
	        // new mobile no for reciver    
			$sender = mysqli_query($conn, "SELECT mobile_number,name,referral_id,user_roles,id, balance_inr, balance_myr, balance_usd FROM users WHERE id = ".$sender_id);
			if (mysqli_num_rows($sender) > 0) { 
				$sender = mysqli_fetch_assoc($sender);
				if ($sender['balance_myr'] == '') {
				$data->MYR = 0;
				}
				else {
					$data->MYR = $sender['balance_myr'];
				}
				if ($sender['balance_usd'] == '') {
					$data->CF = 0;
				} else {
					$data->CF = $sender['balance_usd'];
				}
				if ($sender['balance_inr'] == '') {
					$data->CF = 0;
				} else {
					$data->INR = $sender['balance_inr'];
				}
				$sender_user_role=$sender['user_roles'];
				$reffered_by=$sender['referral_id'];
				$referral_id=$mobile_number;
                 
				if($sender_user_role==1)
				{
					$mer_id=$_POST['wallet_merchant_id'];
					$sq="select special_coin_wallet.*,m.special_coin_name from special_coin_wallet  inner join users as m on m.id=special_coin_wallet.merchant_id where merchant_id='$mer_id' and user_id='$sender_id'";
					$sub_rows = mysqli_query($conn,$sq);
						  if(mysqli_num_rows($sub_rows)>0){
							while ($swallet=mysqli_fetch_assoc($sub_rows)){
								$s_coin_name=$swallet['special_coin_name'];
								$s_bal=$swallet['coin_balance'];
								$data->$s_coin_name=$swallet['coin_balance'];
							}
						  }
					if (!in_array($wallet_type,$fixwallet)) {
						if($s_bal>=$amount)
								{
								}
								else
								{
									$res['status']=false;
									$res['data']='';
									$res['msg']='Not sufficient Bal to Transfer';
									echo json_encode($res);
									die;
								}
					}
				}
				if($data)
				{
					
					$data->reciver_label=$mobile_number;
					
					$data->sender_label=$coin_name;
					$data->remark_label=$remark;
					$data->time_label=date('h:i A')." on ".date('d/m/Y');
					$reff_as=$_POST['reff_as'];
					if($reff_as=="merchant")
						$user_roles=2;
					else 
						$user_roles=1;
					 $q="INSERT INTO users SET  isLocked='1',user_roles='$user_roles', joined='".time()."',referral_id='$mobile_number',referred_by='$reffered_by',mobile_number='$mobile_number'";
					
					$insert=mysqli_query($conn,$q); 
					if($insert)
					{
						$data->new_coin_trasfer="y";
						$data->transfer_amount=$_POST['transfer_amount'];
						$reciver_id =$data->id =mysqli_insert_id($conn);
						$res['status']=true;
						$res['data']=$data;
						$res['msg']='Can Trasfer to that mobile';
					}
					else
					{
						$res['status']=false;
						$res['data']='';
						$res['msg']='Invalid Reciver Mobile Number';
					}
				}
				else
				{
					$res['status']=false;
					$res['data']='';
				}
			}
	}
    
    echo json_encode($res);
	die;
} else {
    
	$t_flag=false;
	$new_coin_trasfer=$_POST['new_coin_trasfer'];
	$multi_wallet = filter_var($_POST['multi_wallet'], FILTER_VALIDATE_BOOLEAN);
	$wallet_merchant_id=$_POST['wallet_merchant_id'];
	if($new_coin_trasfer=="y")
	{
		$coin_active="y";
		$ref_coin="y";
	}
	else
	{
		$coin_active="y";
		$ref_coin="n";
	}
	 $coin_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT special_coin_name FROM users WHERE id='".$wallet_merchant_id."'"))['special_coin_name'];
 
    if($multi_wallet == true){
        $amount = [];
        $wallet_coin_name = [];
        foreach($_POST['wallet_type'] as $w_name => $w_amount){
            if($w_name == 'CF' || $w_name == 'INR' || $w_name == 'MYR'){
                $wallet_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT special_coin_name FROM users WHERE id = '$wallet_merchant_id[$w_name]'"))['special_coin_name'];
                $wallet_coin_name[] = $wallet_name;
            }else{
                $wallet_coin_name[] = $w_name;
            }
            $amount[] = $w_amount;
        }
    }else{
        $wallet_coin_name=$coin_name;
        $amount = $_POST['amount'];
    }
    $sender_name = $_POST['sender_name'];
    $sender_mobile = $_POST['sender_mobile'];
    // $created_on = $_POST['created'] / 1000;
    $datetime = date('Y-m-d H:i:s');
    $created_on = strtotime($datetime);
    $sender_id = $_POST['sender_id'];
    $receiver_id = $_POST['receiver_id'];
    $merchant_send = $_POST['merchant_send'];
	$wallet_type = $_POST['wallet_type'];
	$special_wallet = $_POST['special_wallet'];
	if($multi_wallet == true)
        $details = "Multi-wallet Transfer";
    else
        $details = "Wallet Transfer";
    $tmp = 'SELECT name,balance_usd, balance_myr, balance_inr FROM users WHERE id="'.$sender_id.'"';
	$sender = mysqli_fetch_assoc(mysqli_query($conn, $tmp));
	
	$sender_myr = $sender['balance_myr'];
	$sender_inr = $sender['balance_inr'];
	$sender_usd = $sender['balance_usd'];
	$sender_name = $sender['name'];
    
	if($_POST['trasnfer_type'])
	{
		
	  $trasnfer_type=$_POST['trasnfer_type'];	
	  $transfer_to=$_POST['transfer_to'];
	  if (substr($transfer_to, 0,2) === "60") {
				
		}
		else
		{
			$transfer_to="60".$transfer_to;
		}
	  if($trasnfer_type=="member")
	  {
		  $tmp = 'SELECT  * FROM users WHERE mobile_number="'.$transfer_to.'"';
	  }
	  else  if($trasnfer_type=="merchant")
	  {
		  $tmp = 'SELECT  * FROM users WHERE mobile_number="'.$transfer_to.'"';
	  } else
	  {
		$tmp = 'SELECT  * FROM users WHERE mobile_number="'.$transfer_to.'"';  
	  }
	}
	else
	{
		$tmp = 'SELECT  name,moengage_unique_id,user_roles,mobile_number,balance_usd, balance_myr, balance_inr FROM users WHERE id="'.$receiver_id.'"';
	}
	$receiver = mysqli_fetch_assoc(mysqli_query($conn, $tmp));
	$receiver_name = $receiver['name'];  
	
	$receiver_myr = $receiver['balance_myr'];
	$receiver_user_roles = $receiver['user_roles'];
	$receiver_inr = $receiver['balance_inr'];
	$receiver_usd = $receiver['balance_usd'];
	$receiver_mobile= $receiver['mobile_number'];
	if($receiver_name)
		$r_label=$receiver_name;
	else
		$r_label=$receiver_mobile;  
	$rmoengage_unique_id= $receiver['moengage_unique_id'];
	$sender_query = 'UPDATE users SET ';
	$receiver_query = 'UPDATE users SET ';

	if(!$multi_wallet){
	    $wallet_merchant_id = [$wallet_type => $wallet_merchant_id];
    }

    if(!$multi_wallet)
        $amount = [$amount];

    $amount_index_g = 0;
    $amount_index_inside = 0;
            foreach ($wallet_merchant_id as $coin => $m_id) {
                $merchantaccept = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM unrecoginize_coin WHERE status=1 and merchant_id='" . $m_id . "' and user_id='" . $receiver_id . "'"));
                if (!$merchantaccept && $m_id != $receiver_id && $receiver['user_roles'] == 2) {
                    $msg = "This Number is Not Accepting " . $coin;
                    $p_detail = array('status' => false, 'msg' => $msg, 'coin_name' => $coin);
                    echo json_encode($p_detail);
                    die;
                }

                if ($receiver['user_roles'] == 2 && $new_coin_trasfer != "y") {
                    // check merchant limit for that coin

                    $merchantaccept = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM unrecoginize_coin WHERE status=1 and merchant_id='" . $m_id . "' and user_id='" . $receiver_id . "'"));
                    if ($merchantaccept) {
                        $totalcoin_a = "SELECT sum(amount) as totalamount FROM tranfer WHERE MONTH(created_date) = MONTH(CURRENT_DATE()) AND YEAR(created_date) = YEAR(CURRENT_DATE()) and receiver_id='$receiver_id' and coin_merchant_id='$m_id'";
                        // $totalcoin_b = "SELECT sum(transfer_multi_wallet.amount) as totalamount FROM tranfer INNER JOIN transfer_multi_wallet ON tranfer.coin_merchant_id = '0'  AND MONTH(created_date) = MONTH(CURRENT_DATE()) AND YEAR(created_date) = YEAR(CURRENT_DATE()) AND tranfer.id = transfer_multi_wallet.transfer_id AND transfer_multi_wallet.merchant = '$m_id' AND transfer_multi_wallet.receiver = '$receiver_id'";
                        $acceptedcoin_a = mysqli_fetch_assoc(mysqli_query($conn, $totalcoin_a));
                        // $acceptedcoin_b = mysqli_fetch_assoc(mysqli_query($conn, $totalcoin_b));
                         $acceptedcoin = floatval($acceptedcoin_a['totalamount']);
                        $totalamount = $acceptedcoin + $amount[$amount_index_inside];
                         $coin_max_limit = $merchantaccept['coin_max_limit'];
                        $partnerbal = partnerbal($m_id, $conn);
                        $coin_limit = $merchantaccept['coin_limit'];
                        $limitclass = $coin_limit - $partnerbal;
                        $pending_limit = $coin_max_limit - $acceptedcoin;
                       // $pending_limit;
                     
                        if ($limitclass <= $pending_limit)
                            $pending_limit = $limitclass;
                       
                        if ($pending_limit > 0) {

                            if ($amount[$amount_index_inside] > $pending_limit) {

                                $pending_limit = number_format($pending_limit, 2);
                                $msg = "The maximum limit to accept is only " . $pending_limit . ", please transfer a lower amount for " . $coin;
                                $p_detail = array('status' => false, 'msg' => $msg, 'coin_name' => $coin);
                                echo json_encode($p_detail);
                                die;
                            }
                            if ($limitclass <= 0) {
                                $p_detail = array('status' => false, 'msg' => $max_limit_msg, 'coin_name' => $coin);
                                echo json_encode($p_detail);
                                die;
                            } else {
                                $max_check = $limitclass;
                                // echo $totalamount;
                                // echo $max_check;
                                // die;
                                if ($totalamount > $max_check) {
                                    if ($limitclass < $pending_limit)
                                        $pending_limit = $limitclass;
                                    if ($pending_limit != $amount[$amount_index_inside] && $pending_limit < $amount[$amount_index_inside]) {
                                        if ($pending_limit > 0) {
                                            $pending_limit = number_format($pending_limit, 2);
                                            $msg = "The maximum limit to accept is only " . $pending_limit . ", please transfer a lower amount";
                                        } else {
                                            $msg = $max_limit_msg;
                                        }
                                        $p_detail = array('status' => false, 'msg' => $msg, 'coin_name' => $coin_name);

                                        echo json_encode($p_detail);
                                        die;
                                    }

                                }

                            }
                        } else {
                            $p_detail = array('status' => false, 'msg' => $max_limit_msg, 'coin_name' => $coin_name);
                            echo json_encode($p_detail);
                            die;
                        }

                    } else if($m_id != $receiver_id) {
                        $msg = "This Number is Not Accepting " . $coin;
                        $p_detail = array('status' => false, 'msg' => $msg, 'coin_name' => $coin);
                        echo json_encode($p_detail);
                        die;
                    }
                }
                $amount_index_inside++;
            }
            $amount_index = 0;
          
    foreach($wallet_merchant_id as $coin => $merchant) {
    //    if($wallet_merchant_id==$sender_id)
    //    $coin="";
        if (in_array($coin, $fixwallet)) {
          //  echo "coin in fix wallet ".$coin;

            if ($coin == 'MYR') {
                $sender_bal = $sender_myr = floatval($sender_myr) - floatval($amount[$amount_index_g]);
                $receiver_bal = $receiver_myr = floatval($receiver_myr) + floatval($amount[$amount_index_g]);
                $sender_query = $sender_query . 'balance_myr="' . $sender_myr . '" ';
                $receiver_query = $receiver_query . 'balance_myr="' . $receiver_myr . '" ';
            }
            if ($coin == 'INR') {
                $sender_bal = $sender_inr = floatval($sender_inr) - floatval($amount[$amount_index_g]);
                $receiver_bal = $receiver_inr = floatval($receiver_inr) + floatval($amount[$amount_index_g]);
                $sender_query = $sender_query . 'balance_inr="' . $sender_inr . '" ';
                $receiver_query = $receiver_query . 'balance_inr="' . $receiver_inr . '" ';
            }
            if ($coin == 'CF') {
                $sender_bal = $sender_usd = floatval($sender_usd) - floatval($amount[$amount_index_g]);
                $receiver_bal = $receiver_usd = floatval($receiver_usd) + floatval($amount[$amount_index_g]);
                $sender_query = $sender_query . 'balance_usd="' . $sender_usd . '" ';
                $receiver_query = $receiver_query . 'balance_usd="' . $receiver_usd . '" ';
            }

             $sender_query = $sender_query . ' WHERE id=' . $sender_id;

            // amount to credit in reciver  wallet with special coin

            if ($receiver['user_roles'] == 2 && $new_coin_trasfer != "y") {
                foreach ($wallet_merchant_id as $m_id) {
                    $merchantaccept = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM unrecoginize_coin WHERE status=1 and merchant_id='" . $m_id . "' and user_id='" . $receiver_id . "'"));
                    if (!$merchantaccept) {
                        $msg = "This Number is Not Accepting " . $coin_name;
                        $p_detail = array('status' => false, 'msg' => $msg, 'coin_name' => $coin_name);
                        echo json_encode($p_detail);
                        die;
                    }
                }
              
                foreach ($wallet_merchant_id as $m_id) {
                    $merchantaccept = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM unrecoginize_coin WHERE status=1 and merchant_id='" . $m_id . "' and user_id='" . $receiver_id . "'"));
                    if ($merchantaccept) {
                        $totalcoin_a = "SELECT sum(amount) as totalamount FROM tranfer WHERE MONTH(created_date) = MONTH(CURRENT_DATE()) AND YEAR(created_date) = YEAR(CURRENT_DATE()) and receiver_id='$receiver_id' and coin_merchant_id='$m_id'";
                        $totalcoin_b = "SELECT sum(transfer_multi_wallet.amount) as totalamount FROM tranfer INNER JOIN transfer_multi_wallet ON tranfer.coin_merchant_id = '0'  AND MONTH(created_date) = MONTH(CURRENT_DATE()) AND YEAR(created_date) = YEAR(CURRENT_DATE()) AND tranfer.id = transfer_multi_wallet.transfer_id AND transfer_multi_wallet.merchant = '$m_id' AND transfer_multi_wallet.receiver = '$receiver_id'";
                        $acceptedcoin_a = mysqli_fetch_assoc(mysqli_query($conn, $totalcoin_a));
                        $acceptedcoin_b = mysqli_fetch_assoc(mysqli_query($conn, $totalcoin_b));
                        $acceptedcoin = floatval($acceptedcoin_a['totalamount']) + floatval($acceptedcoin_b['totalamount']);
                        $totalamount = $acceptedcoin + $amount[$amount_index_g];
                        $coin_max_limit = $merchantaccept['coin_max_limit'];
                        $partnerbal = partnerbal($m_id, $conn);
                        $coin_limit = $merchantaccept['coin_limit'];
                        $limitclass = $coin_limit - $partnerbal;
                        $pending_limit = $coin_max_limit - $acceptedcoin;
                        if ($limitclass <= $pending_limit)
                            $pending_limit = $limitclass;
                        // echo $pending_limit;
                        // die;
                        if ($pending_limit > 0) {

                            if ($amount[$amount_index] > $pending_limit) {

                                $pending_limit = number_format($pending_limit, 2);
                                $msg = "The maximum limit to accept is only " . $pending_limit . " for {$coin_name}, please transfer a lower amount";
                                $p_detail = array('status' => false, 'msg' => $msg, 'coin_name' => $coin_name);
                                echo json_encode($p_detail);
                                die;
                            }
                            if ($limitclass <= 0) {
                                $p_detail = array('status' => false, 'msg' => $max_limit_msg, 'coin_name' => $coin_name);
                                echo json_encode($p_detail);
                                die;
                            } else {
                                $max_check = $limitclass;
                                // echo $limitclass;
                                // echo $max_check;
                                // die;
                                if ($totalamount > $max_check) {
                                    if ($limitclass < $pending_limit)
                                        $pending_limit = $limitclass;
                                    if ($limitclass < $pending_limit)
                                        $pending_limit = $limitclass;
                                    if ($pending_limit != $amount && $pending_limit < $amount) {
                                        if ($pending_limit > 0) {
                                            $pending_limit = number_format($pending_limit, 2);
                                            $msg = "The maximum limit to accept is only " . $pending_limit . " for {$coin_name}, please transfer a lower amount";
                                        } else {
                                            $msg = $max_limit_msg;
                                        }
                                        $p_detail = array('status' => false, 'msg' => $msg, 'coin_name' => $coin_name);

                                        echo json_encode($p_detail);
                                        die;
                                    }

                                }

                            }
                        }

                    } else {
                        $msg = "This Number is Not Accepting " . $coin_name;
                        $p_detail = array('status' => false, 'msg' => $msg, 'coin_name' => $coin_name);
                        echo json_encode($p_detail);
                        die;
                    }
                    $amount_index++;
                } // end foreach loop

            } // end if()

            // if($receiver['user_roles']==1 || $merchant_special_case=="y" || $new_coin_trasfer=="y")
            // {
            foreach ($wallet_merchant_id as $m_id) {
                $merchantaccept = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM unrecoginize_coin WHERE status=1 and merchant_id='" . $m_id . "' and user_id='" . $receiver_id . "'"));
                if (!$merchantaccept && $receiver_user_roles == 2) {
                    $msg = "This Number is Not Accepting " . $coin_name;
                    $p_detail = array('status' => false, 'msg' => $msg, 'coin_name' => $coin_name);
                    echo json_encode($p_detail);
                    die;
                }
            }

            $special_coin = mysqli_fetch_assoc(mysqli_query($conn, "select * from special_coin_wallet where user_id='$receiver_id' and merchant_id='$merchant'"));
            if ($special_coin['id']) {

                $receiver_bal = $receiver_amount = $new_coin = $special_coin['coin_balance'] + $amount[$amount_index_g];
                $total_added = $special_coin['added_balance'] + $amount[$amount_index_g];
                // echo "update special_coin_wallet set coin_balance='$new_coin',added_balance='$total_added' where user_id='$receiver_id' and merchant_id='$merchant'";
                // die;

                $q2 = mysqli_query($conn, "update special_coin_wallet set coin_balance='$new_coin',added_balance='$total_added' where user_id='$receiver_id' and merchant_id='$merchant'");


            } else {
                // make new entry
                // echo "INSERT INTO special_coin_wallet (user_id,merchant_id,coin_balance) VALUES ('$receiver_id', '$sender_id', '$amount')";


                if ($new_coin_trasfer == "y") {
                    $login_token = generatePIN();
                    $cm = $receiver['mobile_number'];
                    // echo "UPDATE `users` SET `login_token`='$login_token' WHERE `users`.`id` ='$receiver_id'";
                    // die;
                    mysqli_query($conn, "UPDATE `users` SET `login_token`='$login_token' WHERE `users`.`id` ='$receiver_id'");
                    if ($cm) {
                        if ($receiver_user_roles == 2) {
                            $m_url = "https://www.koofamilies.com?merchant_id=" . $merchant . "&l=" . $login_token . "&t=" . $amount;
                            // $msg="My dear friend, I have a gift voucher of RM".$amount." of ".$mer_name."for you.You can get it by clicking on the links.".$m_url."Enjoy my gift to you !!!";
                            // $msg="My dear friend, I ".$sender_name." have a gift voucher of RM".$amount." of ".$mer_name." for you. You can get it by clicking on the links. ".$m_url." . Enjoy my gift to you !!!";
                            $msg = "Hello, i am from " . $sender_name . ". I would like to invite you to join our community. Kindly accept my invitation by clicking on the following link." . $m_url;
                        } else {
                            $m_url = "https://www.koofamilies.com/structure_merchant.php?merchant_id=" . $merchant . "&l=" . $login_token . "&t=" . $amount;
                            // $msg="My dear friend, I have a gift voucher of RM".$amount." of ".$mer_name."for you.You can get it by clicking on the links.".$m_url."Enjoy my gift to you !!!";
                            $msg = "My dear friend, I " . $sender_name . " have a gift voucher of RM" . $amount . " of " . $mer_name . " for you. You can get it by clicking on the links. " . $m_url . " . Enjoy my gift to you !!!";
                        }
                        $details = "Welcome Wallet Transfer";
                        gw_send_sms("APIHKXVL33N5E", "APIHKXVL33N5EHKXVL", "9787136232", "$cm", "$msg");
                    }


                }
                $q2 = mysqli_query($conn, "INSERT INTO special_coin_wallet (user_id,merchant_id,coin_balance,added_balance,user_mobile,coin_active,ref_coin) VALUES ('$receiver_id', '$merchant','$amount[$amount_index_g]','$amount','$receiver_mobile','$coin_active','$ref_coin')");
                // $sender_amount=$amount;
                $receiver_bal = $receiver_amount;

            }
            // }


            if ($q2)
                $q1 = mysqli_query($conn, $sender_query);

            // $receiver_query = $receiver_query.' WHERE id='.$receiver_id;
            // $q2=mysqli_query($conn, $receiver_query);
            if ($q1 && $q2)
                $t_flag = true;

        } 
        else {

            
          
             $merchant_id=$merchant;
            
                if ($merchant_id == $receiver_id) {
                    
                    // coin trasfer to owner of coin holder
                    $special_coin_sender = mysqli_fetch_assoc(mysqli_query($conn, "select * from special_coin_wallet where user_id='$sender_id' and merchant_id='$merchant_id'"));
                    $s_wallet_id = $special_coin_sender['id'];
                    $sender_bal = $sender_amount = floatval($special_coin_sender['coin_balance']) - floatval($amount[$amount_index]);
                    $q1 = mysqli_query($conn, "update special_coin_wallet set coin_balance='$sender_bal' where id='$s_wallet_id'");
                    // increase merchant bal
                    $receiver_bal = $receiver_usd = floatval($receiver_usd) + floatval($amount[$amount_index]);
                    $receiver_query = $receiver_query . 'balance_usd="' . $receiver_usd . '" ';
                    $receiver_query = $receiver_query . ' WHERE id=' . $receiver_id;
                    // die;
                    
                    $q2 = mysqli_query($conn, $receiver_query);
                    if ($q1 && $q2)
                        $t_flag = true;
                } else {


                    

                    // trasfer to other
                   
                    if ($merchant_send == "y" || $merchant_id==$sender_id) {
                        $merchant_detail = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name FROM users WHERE id='" . $merchant_id . "'"));
                        // $mer_bal=
                        $s_coin_name = $merchant_detail['special_coin_name'];
                        $mer_name = $merchant_detail['name'];
                        $special_coin = mysqli_fetch_assoc(mysqli_query($conn, "select * from special_coin_wallet where user_id='$receiver_id' and merchant_id='$merchant_id'"));
                        // $sender_usd=$sender_usd-$amount;

                        if ($special_coin['id']) {

                            $receiver_bal = $receiver_amount = $new_coin = $special_coin['coin_balance'] + $amount[$amount_index];
                            $total_added = $special_coin['added_balance'] + $amount[$amount_index];
                            // echo "update special_coin_wallet set coin_balance='$new_coin' where user_id='$receiver_id' and merchant_id='$sender_id'";
                            // die;

                            $q1 = mysqli_query($conn, "update special_coin_wallet set coin_balance='$new_coin',added_balance='$total_added' where user_id='$receiver_id' and merchant_id='$merchant_id'");


                        } else {
                            // make new entry
                            // echo "INSERT INTO special_coin_wallet (user_id,merchant_id,coin_balance) VALUES ('$receiver_id', '$sender_id', '$amount')";
                            $new_coin=$amount[$amount_index];
                            echo "New Coin </br>";
                            echo "INSERT INTO special_coin_wallet (user_id,merchant_id,coin_balance,added_balance,user_mobile,coin_active,ref_coin) VALUES ('$receiver_id', '$merchant_id','$new_coin','$new_coin','$receiver_mobile','$coin_active','$ref_coin')";
                            echo "</br>";
                            $q1 = mysqli_query($conn, "INSERT INTO special_coin_wallet (user_id,merchant_id,coin_balance,added_balance,user_mobile,coin_active,ref_coin) VALUES ('$receiver_id', '$merchant_id','$new_coin','$new_coin','$receiver_mobile','$coin_active','$ref_coin')");
                            // $sender_amount=$amount;
                            $new_inserted_id = mysqli_insert_id($conn);
                            if ($new_coin_trasfer == "y") {
                                $login_token = generatePIN();
                                $cm = $receiver['mobile_number'];
                                // echo "UPDATE `users` SET `login_token`='$login_token' WHERE `users`.`id` ='$receiver_id'";
                                // die;
                                mysqli_query($conn, "UPDATE `users` SET `login_token`='$login_token' WHERE `users`.`id` ='$receiver_id'");
                                if ($cm) {
                                    if ($receiver_user_roles == 2) {
                                        $m_url = "https://www.koofamilies.com/dashboard.php?merchant_id=" . $merchant_id . "&l=" . $login_token . "&t=" . $amount;
                                        // $msg="My dear friend, I have a gift voucher of RM".$amount." of ".$mer_name."for you.You can get it by clicking on the links.".$m_url."Enjoy my gift to you !!!";
                                        // $msg="My dear friend, I ".$sender_name." have a gift voucher of RM".$amount." of ".$mer_name." for you. You can get it by clicking on the links. ".$m_url." . Enjoy my gift to you !!!";
                                        $msg = "Hello, i am from " . $sender_name . ". I would like to invite you to join our community. Kindly accept my invitation by clicking on the following link." . $m_url;
                                    } else {
                                        $m_url = "https://www.koofamilies.com/structure_merchant.php?merchant_id=" . $merchant_id . "&l=" . $login_token . "&t=" . $amount;
                                        // $msg="My dear friend, I have a gift voucher of RM".$amount." of ".$mer_name."for you.You can get it by clicking on the links.".$m_url."Enjoy my gift to you !!!";
                                        $msg = "My dear friend, I " . $sender_name . " have a gift voucher of RM" . $amount . " of " . $mer_name . " for you. You can get it by clicking on the links. " . $m_url . " . Enjoy my gift to you !!!";
                                    }
                                    $details = "Welcome Wallet Transfer";
                                    gw_send_sms("APIHKXVL33N5E", "APIHKXVL33N5EHKXVL", "9787136232", "$cm", "$msg");
                                }


                            }
                            $receiver_bal = $receiver_amount;

                        }
                        $sender_bal = $sender_amount = $sender_usd = floatval($sender_usd) - floatval($amount[$amount_index]);
                        // $sender_amount=$sender_bal;
                        $q2 = mysqli_query($conn, "update users set balance_usd='$sender_usd' where id='$sender_id'");
                        if ($q1 && $q2)
                            $t_flag = true;
                        $sender_bal = $sender_usd;
                    } else {
                        // send from user
                        //foreach ($wallet_merchant_id as $wallet_name => $merch_id) { // Loops through all the merchants names. $wallet_merchant_id = ['WALLET NAME' => 'MERCHANT ID']
                        $merchant_detail = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name, special_coin_name FROM users WHERE id='" . $merchant_id . "'"));

                        $s_coin_name = $merchant_detail['special_coin_name'];
                        $mer_name = $merchant_detail['name'];
                        if ($multi_wallet)
                            $amount_sgl = $amount[$amount_index]; // Grab amount from the wallet_type array
                        else
                            $amount_sgl = $_POST['amount'];

                        $special_coin = mysqli_fetch_assoc(mysqli_query($conn, "select * from special_coin_wallet where user_id='$receiver_id' and merchant_id='$merchant_id'"));
                        $special_coin_sender = mysqli_fetch_assoc(mysqli_query($conn, "select * from special_coin_wallet where user_id='$sender_id' and merchant_id='$merchant_id'"));

                        if ($special_coin_sender) {
                            $s_wallet_id = $special_coin_sender['id']; // Sender special coin id
                            if ($special_coin['id']) // Receiver special coin id
                            {

                                $receiver_bal = $receiver_amount = $new_coin = $special_coin['coin_balance'] + $amount_sgl;
                                $total_added = $special_coin['added_balance'] + $amount_sgl;
                                // echo "update special_coin_wallet set coin_balance='$new_coin',added_balance='$total_added' where user_id='$receiver_id' and merchant_id='$merchant_id'";
                                // echo "</br>";
                                $q1 = mysqli_query($conn, "update special_coin_wallet set coin_balance='$new_coin',added_balance='$total_added' where user_id='$receiver_id' and merchant_id='$merchant_id'");


                            } else {
                                // make new entry
                                // echo "INSERT INTO special_coin_wallet (user_id,merchant_id,coin_balance) VALUES ('$receiver_id', '$sender_id', '$amount')";
                                $new_coin=$amount_sgl;

                                if ($new_coin_trasfer == "y") {
                                    $login_token = generatePIN();
                                    $cm = $receiver['mobile_number'];
                                    // echo "UPDATE `users` SET `login_token`='$login_token' WHERE `users`.`id` ='$receiver_id'";
                                    // die;
                                    mysqli_query($conn, "UPDATE `users` SET `login_token`='$login_token' WHERE `users`.`id` ='$receiver_id'");
                                    if ($cm) {
                                        if ($receiver_user_roles == 2) {
                                            $m_url = "https://www.koofamilies.com/dashboard.php?merchant_id=" . $merchant_id . "&l=" . $login_token . "&t=" . $amount_sgl;
                                            // $msg="My dear friend, I have a gift voucher of RM".$amount." of ".$mer_name."for you.You can get it by clicking on the links.".$m_url."Enjoy my gift to you !!!";
                                            // $msg="My dear friend, I ".$sender_name." have a gift voucher of RM".$amount." of ".$mer_name." for you. You can get it by clicking on the links. ".$m_url." . Enjoy my gift to you !!!";
                                            $msg = "Hello, i am from " . $sender_name . ". I would like to invite you to join our community. Kindly accept my invitation by clicking on the following link." . $m_url;
                                        } else {
                                            $m_url = "https://www.koofamilies.com/structure_merchant.php?merchant_id=" . $merchant_id . "&l=" . $login_token . "&t=" . $amount_sgl;
                                            // $msg="My dear friend, I have a gift voucher of RM".$amount." of ".$mer_name."for you.You can get it by clicking on the links.".$m_url."Enjoy my gift to you !!!";
                                            $msg = "My dear friend, I " . $sender_name . " have a gift voucher of RM" . $amount . " of " . $mer_name . " for you. You can get it by clicking on the links. " . $m_url . " . Enjoy my gift to you !!!";
                                        }
                                    }
                                    $details = "Welcome Wallet Transfer";
                                    gw_send_sms("APIHKXVL33N5E", "APIHKXVL33N5EHKXVL", "9787136232", "$cm", "$msg");


                                }
                               
                                $q1 = mysqli_query($conn, "INSERT INTO special_coin_wallet (user_id,merchant_id,coin_balance,added_balance,user_mobile,coin_active,ref_coin) VALUES ('$receiver_id', '$merchant_id','$new_coin','$new_coin','$receiver_mobile','$coin_active','$ref_coin')");
                                // $sender_amount=$amount;
                                $receiver_bal = $receiver_amount;

                            }
                            $sender_bal = $sender_amount = floatval($special_coin_sender['coin_balance']) - floatval($amount_sgl);
                            $q2 = mysqli_query($conn, "update special_coin_wallet set coin_balance='$sender_bal' where id='$s_wallet_id'");
                            // $sender_bal=$sender_usd;
                            if ($q1 && $q2)
                                $t_flag = true;

                        } else {
                            if($coin != 'CF'){
                                $p_detail = array('status' => false, 'msg' => "Sender Dont Have Balance", 'coin_name' => $coin_name);

                                echo json_encode($p_detail);
                                die;
                            }
                        }
                        //} // End Loop


                    }

                }
               
           


        }
        if($c_label_name)
        $c_label_name=$c_label_name." ,".$amount[$amount_index]." ".$coin;
        else
        $c_label_name=$amount[$amount_index]." ".$coin;
        $amount_index++;
       
    }
	if($t_flag)
	{
		$remark='';
		if(isset($_POST['remark']))
		$remark=$_POST['remark'];
        $merchant_info = [];
       
        foreach($wallet_merchant_id as $m_id){
            $merchant_info[] = $m_id;
        }
       
		if($multi_wallet){
            $total = 0;
            foreach($wallet_type as $a){
                $total += $a;
            }
          
            $coin_name = implode(",", $wallet_coin_name);
            $merchant_id = 0;
        }else {
            $total = $_POST['amount'];
            $merchant_id = $merchant_info[0];
            $sql = 'INSERT INTO tranfer (sender_id, amount, receiver_id, wallet, created_on,details,coin_merchant_id,remark) VALUES ("'.$sender_id.'", "'.$total.'", "'.$receiver_id.'", "'.$coin_name.'", "'.$created_on.'", "'.$details.'","'.$merchant_id.'","'.$remark.'")';
            $transfer = mysqli_query($conn, $sql);
    
            if(!$transfer)
                $error = mysqli_error($conn);
    
            $last_id = mysqli_insert_id($conn);
    
             $sql_for_transaction = 'INSERT INTO transactions (sender_id, amount, receiver_id, wallet, created_on) VALUES ("'.$sender_id.'", "'.$total.'", "'.$receiver_id.'", "'.$coin_name.'", "'.$created_on.'")';
            $transaction = mysqli_query($conn, $sql_for_transaction);
        }
      

        if($multi_wallet){
            // $amt = amount
            $w_i = [];
            $i = 0;
            
            foreach($wallet_type as $coin => $amt){
              //  print_R($wallet_type);
                $multi_wallet_transfer = mysqli_query($conn, "INSERT INTO transfer_multi_wallet (sender, receiver, merchant, transfer_id, amount,remark) VALUES ('$sender_id', '$receiver_id', '$merchant_info[$i]', '$last_id', $amt,$remark)");
				  $sql = 'INSERT INTO tranfer (sender_id, amount, receiver_id, wallet, created_on,details,coin_merchant_id,remark) VALUES ("'.$sender_id.'", "'.$amt.'", "'.$receiver_id.'", "'.$merchant_info[$i].'", "'.$created_on.'", "'.$details.'","'.$merchant_info[$i].'","'.$remark.'")';
                
				$transfer = mysqli_query($conn, $sql);
                if(!$multi_wallet_transfer)
                    $error = mysqli_error($conn);
                $i++;
            }
        }

        if($sender_name)
		// $noti_string = 'You Successfully Received '.$amount.' '.$coin_name.' from '.$sender_name;
		//$noti_string=$sender_name." has just sent ".$total.' '.$coin_name." to ".$r_label;
        $noti_string=$sender_name." has just sent ".$c_label_name." to ".$r_label;
		else
		// $noti_string = 'You Successfully Received '.$amount.' '.$coin_name.' from '.$sender_mobile;
		// $noti_string=$sender_mobile." has just sent ".$total.' '.$coin_name." to ".$r_label;
        $noti_string=$sender_mobile." has just sent ".$c_label_name." to ".$r_label;
       // echo $noti_string;
		if ($rmoengage_unique_id) {
						$result=exec("/usr/bin/python myscript.py");
					 $resultarray=explode(",",$result);
					 // print_R($resultarray);
					 // die;
					 if (count($resultarray)>0) {
						 // code...
						$data['camp_name']=$camp_name=$resultarray[0];
						$data['sign']=$sign=$resultarray[1];
						$data['push_email']=$rmoengage_unique_id;
						$data['title']='Coin Transfer';
						$data['message']=$noti_string;
						$data['redirectURL']= $site_url .'/dashboard.php';
						include 'push.php';
						$user = new Push();
						$resultpush = $user->send_push($data);
						// print_R($resultpush);   
						// die;  
					 }
					}
		 $noti = 'INSERT into notifications (user_id, notification , type, created_on, readStatus) VALUES ("'.$receiver_id.'", "'.$noti_string.'", "receive", "'.$created_on.'", "0")';
		$notification = mysqli_query($conn, $noti);  
       
		$p_detail=array('status'=>true,'msg'=>"Fund Trasfer Done",'sender_bal'=>$sender_bal,'receiver_bal'=>$receiver_bal,'coin_name'=>$coin_name);
		 echo json_encode($p_detail);
		 die;  
	}
	// echo $noti;

}
function partnerbal($coin_merchant_id,$conn)
{
	 $q="SELECT sum(coin_balance) as total_amount FROM `special_coin_wallet` inner join users on users.id=special_coin_wallet.user_id and users.user_roles='2' WHERE `merchant_id` ='$coin_merchant_id'";
	
	$parq=mysqli_query($conn,$q);  
	$p_total=mysqli_fetch_assoc($parq);
	return $p_total['total_amount'];
} 
function partnerbalold($coin_merchant_id,$conn)
{
	$q="select sum(amount) as total_amount from tranfer as t where receiver_id in(select user_id from unrecoginize_coin where merchant_id='$coin_merchant_id') and coin_merchant_id='$coin_merchant_id'";
	$parq=mysqli_query($conn,$q);
	$p_total=mysqli_fetch_assoc($parq);
	return $p_total['total_amount'];
}  
