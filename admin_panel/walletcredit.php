<?php
include("config.php");
if( isset( $_POST['method']) && ( $_POST['method'] == "walletcredit" )) {
	extract($_POST);
	$order_data =  mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM order_list WHERE id='$order_id'"));
	if($order_data['agent_code'])
	{
		$agent_code=$order_data['agent_code'];
	}
	$fecha = new DateTime();
	$timestamp = $fecha->getTimestamp();
	$merchant_id=$order_data['merchant_id'];
	 if($agent_code == '' || $agent_code == null){
			$existsAgentTransaction = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM agent_commissions WHERE order_id = '$order_id'"));
			if($existsAgentTransaction){
				$agent_id = $existsAgentTransaction['agent_id'];
				$agent_code = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM agent_code WHERE agent_id = '$agent_id' AND date < '$order_data[created_on]' ORDER BY date DESC LIMIT 1"))['code'];
			}
		}
	$order_date = $order_data['created_on'];
	
	if($agent_code)
	{
	$agent_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM agent_code WHERE code = '$agent_code' AND date < '$order_date' ORDER BY date DESC LIMIT 1"));
	 
	}
	$agent_id = $agent_data['agent_id'];
	$merchant = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * from users where id='$merchant_id'"));
	$coin_name = $merchant['special_coin_name'];
	 $agent_user_id = mysqli_fetch_assoc(mysqli_query($conn, "SELECT user_id FROM agents WHERE id='$agent_id'"))['user_id'];
	$new_merchant_val=$merchant['balance_usd'];
	if($new_merchant_val < 0){
			echo json_encode(['status' => 'not_balance']);
			die();
		}
	if($rebate)
	{
	   $new_merchant_val=$new_merchant_val-$rebate;	
	}
	if($new_merchant_val < 0){
			echo json_encode(['status' => 'not_balance']);
			die();
		}
		if($agent_code)
		{
			$commission = mysqli_fetch_assoc(mysqli_query($conn, "SELECT qty FROM agent_commissions WHERE order_id='$order_id' AND status='0'"))['qty'];
			if($commission)
			{
				$avalailable_commission = isset($commission) ? true : false;
				$walletExists = (mysqli_num_rows(mysqli_query($conn, "SELECT id FROM special_coin_wallet WHERE user_id='$agent_user_id' AND merchant_id = '$order_data[merchant_id]'")) == 0) ? false : true;
			   
				$new_merchant_val -= $commission;
				if($new_merchant_val < 0){
							echo json_encode(['status' => 'not_balance']);
							die();
				}
			}
		}
		$s_flag=false;
	// credit rebate for user
	if($rebate>0)
	{
		$sq="select * from special_coin_wallet where merchant_id='$merchant_id' and user_id='$user_id'";
		$sub_rows = mysqli_query($conn,$sq);
		if(mysqli_num_rows($sub_rows)>0){
			$walletbal = mysqli_fetch_assoc($sub_rows);
			$t_id=$walletbal['id'];
			$cur_coin=$walletbal['coin_balance'];
			$new_coin=$cur_coin+$rebate;
			$added_coin=$walletbal['added_balance']+$rebate;
			$query="UPDATE special_coin_wallet SET coin_balance='$new_coin',added_balance='$added_coin' WHERE `id`='$t_id'";
			$insert=mysqli_query($conn,$query);
			
		}
		else
		{
			$new_coin=$rebate;
			// insert new entry in special coin wallet 
			$query="INSERT INTO special_coin_wallet (user_id,merchant_id,coin_balance,added_balance) VALUES ('$user_id', '$merchant_id','$new_coin','$new_coin')";
			$insert=mysqli_query($conn,$query);
		
		}
		if($insert)
		{
		  mysqli_query($conn, "INSERT INTO tranfer(id, sender_id, invoice_no, amount, receiver_id, wallet, created_on, status, details, type_method) VALUES ('','$order_data[merchant_id]','$order_data[id]','$rebate','$user_id','$coin_name', '$timestamp','1','Rebate for order','ewallet')");
		  $s_flag=true;
		}
		if($commission && $agent_code)
		{
			// add commission for agent 
			if($walletExists){
				$special_coin_qty = floatval(mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM special_coin_wallet WHERE user_id='$agent_user_id' AND merchant_id='$order_data[merchant_id]'"))['coin_balance']);
				$special_coin_qty = ($special_coin_qty == null) ? 0 : $special_coin_qty;
				$comm_bal = floatval($special_coin_qty) + $commission;
				if($avalailable_commission){
					$query2="UPDATE special_coin_wallet SET coin_balance='$comm_bal' WHERE user_id='$agent_user_id' AND merchant_id = '$order_data[merchant_id]'";
					
				}
				}else{
					$query2="INSERT INTO special_coin_wallet(user_id, merchant_id, coin_balance,added_balance) VALUES ('$agent_user_id','$order_data[merchant_id]','$commission','$commission')";
				}
			// echo $query2;
			// die;
			if($s_flag)
			{
			   $insert2=mysqli_query($conn,$query2);
			   
			}
			else
			{
				echo json_encode(['status' => 'wrong']);
				die();
			}
		}
		if($insert)
		{
			$date=date('Y-m-d h:i');
			mysqli_query($conn,"UPDATE `order_list` SET `rebate_credited` = 'y',rebate_credited_date='$date' WHERE `order_list`.`id` ='$order_id'");
			if($avalailable_commission){
				$fecha = new DateTime();
				$timestamp = $fecha->getTimestamp();
				//  For agent commission
				if($agent_code)
				{
					mysqli_query($conn,"UPDATE `agent_commissions` SET `status` = '1' WHERE `order_id` ='$order_id'");
					mysqli_query($conn, "INSERT INTO tranfer(id, sender_id, invoice_no, amount, receiver_id, wallet, created_on, status, details, type_method) VALUES ('','$order_data[merchant_id]','$order_data[id]','$commission','$agent_user_id','$coin_name', '$timestamp','1','Commission by merchant','ewallet')");
				}
			}
			// deduct bal from merchant 
			$query3="UPDATE users SET balance_usd = '$new_merchant_val' WHERE id = '$order_data[merchant_id]'";
			$insert3=mysqli_query($conn,$query3);
			$item = array('msg'=>"Inserted",'status'=>true);
		}  
	
	}
	else
	{
		echo json_encode(['status' => 'not_balance']);
		die();
	}
	echo json_encode($item);
		die;   

}
if( isset( $_POST['method']) && ( $_POST['method'] == "walletdeduct" )) {
	extract($_POST);
	
	if($rebate)
	{
		$order_data =  mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM order_list WHERE id='$order_id'"));
		if($order_data['agent_code'])
		{
			$agent_code=$order_data['agent_code'];
		}
		$merchant_id=$order_data['merchant_id'];
		// $profile_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$user_id."'"));
		$user_wallet = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM special_coin_wallet WHERE user_id='".$user_id."'"));
		$merchant = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * from users where id='$merchant_id'"));
		$coin_name = $merchant['special_coin_name'];
		$fecha = new DateTime();
		$timestamp = $fecha->getTimestamp();
		if($user_wallet)
		{
			$cur_coin=$user_wallet['coin_balance'];
			$t_id=$user_wallet['id'];
			$new_coin=$cur_coin-$rebate;
			$query="UPDATE special_coin_wallet SET coin_balance='$new_coin' WHERE `id`='$t_id'";
			$insert=mysqli_query($conn,$query);
		}
		$new_merchant_val=$merchant['balance_usd'];
		$new_merchant_val=$new_merchant_val+$rebate;
		$query3="UPDATE users SET balance_usd = '$new_merchant_val' WHERE id = '$merchant_id'";
		$insert3=mysqli_query($conn,$query3);
		if($insert)
		{
			mysqli_query($conn, "INSERT INTO tranfer(id, sender_id, invoice_no, amount, receiver_id, wallet, created_on, status, details, type_method) VALUES ('','$user_id','$order_data[id]','$rebate','$merchant_id','$coin_name', '$timestamp','1','Recredit due to rebate cancel','ewallet')");
			mysqli_query($conn,"UPDATE `order_list` SET `rebate_credited` = 'n',rebate_credited_date='$date' WHERE `order_list`.`id` ='$order_id'");
			$item = array('msg'=>"Updated",'status'=>true);
		}
	}
	else
	{
		$item = array('msg'=>mysqli_error($conn),'status'=>false);
	}
	echo json_encode($item);
	die;

}
?>