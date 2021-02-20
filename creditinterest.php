<?php 
session_start();
include("config.php");
// get all client whose special coin wallet exit 
$m_data = mysqli_query($conn, "SELECT id,min_coin_value,interest_rate FROM users WHERE id in(5062) and interest_rate>0");
$total_credit=0;
$j++;
while($row = mysqli_fetch_assoc($m_data))
{
	$s_m_id=$row['id'];
	$interest_rate=$row['interest_rate'];
	$min_coin_value=$row['min_coin_value'];
	  
	$query="select scoin.*,users.name,users.mobile_number from special_coin_wallet as scoin inner join users on users.id=scoin.user_id where scoin.coin_balance>=$min_coin_value and scoin.merchant_id='".$s_m_id."'";

	$qdata=mysqli_query($conn,$query);
	
	while($srow = mysqli_fetch_assoc($qdata))
	{
	
	  // add interest amount to u 
		$tra_id=$srow['id'];
		$receiver_id=$srow['user_id'];
		$cur_bal=$srow['coin_balance'];
		$per_val= ($interest_rate / 100) * $cur_bal;
		$daily_int=$per_val/365;
		$total_credit=$total_credit+$daily_int;
		$new_bal=$cur_bal+$daily_int;
		$finalnew=number_format($new_bal,2);
		$update=mysqli_query($conn, "UPDATE special_coin_wallet SET coin_balance='$finalnew' WHERE id='$tra_id'");
		// add entry in trascation too 
		$sender_id=3136;
		$datetime = date('Y-m-d H:i:s');
		$created_on = strtotime($datetime);
		$sql = 'INSERT INTO tranfer (sender_id, amount, receiver_id, wallet, created_on) VALUES ("'.$sender_id.'", "'.$daily_int.'", "'.$receiver_id.'", "'.$wallet_type.'", "'.$created_on.'")';
		$transfer = mysqli_query($conn, $sql);   
       $j++;
		
		
		
	}
}
echo "Total Interest Added ".number_format($total_credit,2)." for ".$j." users";	  							

?>