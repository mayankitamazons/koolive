<?php
include('config.php');
$id=$_REQUEST['recordid'];
$status=$_REQUEST['updatedstatus'];
$date=time();

mysqli_autocommit($conn,FALSE);

if($status == "2")
{
	$user_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT user_id,wallet_from,amount_from,wallet_to,amount_to FROM ex_requests WHERE id='$id'"));

	if($user_data['wallet_from'] == "USD")
	{
		$current_balance = mysqli_fetch_assoc(mysqli_query($conn, "SELECT balance_usd FROM users WHERE id='".$user_data['user_id']."'"))['balance_usd'];
		$new_balance = $current_balance - $user_data['amount_from'];
		mysqli_query($conn, "UPDATE users SET balance_usd='$new_balance' WHERE id='".$user_data['user_id']."'");
	}
	elseif($user_data['wallet_from'] == "INR")
	{
		$current_balance = mysqli_fetch_assoc(mysqli_query($conn, "SELECT balance_inr FROM users WHERE id='".$user_data['user_id']."'"))['balance_inr'];
		$new_balance = $current_balance - $user_data['amount_from'];
		mysqli_query($conn, "UPDATE users SET balance_cny='$new_balance' WHERE id='".$user_data['user_id']."'");
	}
	elseif($user_data['wallet_from'] == "MYR")
	{
		$current_balance = mysqli_fetch_assoc(mysqli_query($conn, "SELECT balance_myr FROM users WHERE id='".$user_data['user_id']."'"))['balance_myr'];
		$new_balance = $current_balance - $user_data['amount_from'];
		mysqli_query($conn, "UPDATE users SET balance_myr='$new_balance' WHERE id='".$user_data['user_id']."'");
	}
	
	if($user_data['wallet_to'] == "USD")
	{
		$current_balance = mysqli_fetch_assoc(mysqli_query($conn, "SELECT balance_usd FROM users WHERE id='".$user_data['user_id']."'"))['balance_usd'];
		$new_balance2 = $current_balance + $user_data['amount_to'];
		mysqli_query($conn, "UPDATE users SET balance_usd='$new_balance2' WHERE id='".$user_data['user_id']."'");
	}
	elseif($user_data['wallet_to'] == "INR")
	{
		$current_balance = mysqli_fetch_assoc(mysqli_query($conn, "SELECT balance_inr FROM users WHERE id='".$user_data['user_id']."'"))['balance_inr'];
		$new_balance2 = $current_balance + $user_data['amount_to'];
		mysqli_query($conn, "UPDATE users SET balance_inr='$new_balance2' WHERE id='".$user_data['user_id']."'");
	}
	elseif($user_data['wallet_to'] == "MYR")
	{
		$current_balance = mysqli_fetch_assoc(mysqli_query($conn, "SELECT balance_myr FROM users WHERE id='".$user_data['user_id']."'"))['balance_myr'];
		$new_balance2 = $current_balance + $user_data['amount_to'];
		mysqli_query($conn, "UPDATE users SET balance_myr='$new_balance2' WHERE id='".$user_data['user_id']."'");
	}
	
	mysqli_query($conn, "INSERT INTO notifications SET user_id='".$user_data['user_id']."', type='withdraw_request', notification='Your Withdrawal Request (ID : $id) has been successfully completed.', created_on='".time()."', readStatus='0'");
}

$update=mysqli_query($conn,"UPDATE ex_requests SET `status`='$status' , `status_date`='$date' WHERE id='$id' ");

mysqli_commit($conn);
die;
?>