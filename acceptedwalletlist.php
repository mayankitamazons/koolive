<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL); 
include("config.php");
extract($_POST);

if (isset($_POST['transfer_to'])) {
	$mobile_number = $_POST['transfer_to'];
	if (substr($mobile_number, 0,2) === "60") {
			
	}
	else
	{
		$mobile_number="60".$mobile_number;
	}
	$tmp = mysqli_query($conn, "SELECT user_roles,name,id,special_coin_name FROM users WHERE mobile_number = '$mobile_number'");
	$buf = mysqli_fetch_assoc($tmp);
	$receiver_id=$buf['id'];
	 $rec_user_roles=$buf['user_roles']; 
	if($rec_user_roles==2){ 
		$finallist=[];
		    // $q="select special_coin_wallet.*,m.special_coin_name,m.mobile_number as merchant_no from special_coin_wallet inner join users as m on m.id=special_coin_wallet.merchant_id where special_coin_wallet.coin_balance>0 and user_id='$sender_id' and special_coin_wallet.merchant_id in(SELECT merchant_id FROM unrecoginize_coin WHERE user_id ='$receiver_id' )";
			
			// echo 	$q="select special_coin_wallet.*,m.special_coin_name,m.mobile_number as merchant_no from special_coin_wallet inner join users as m on m.id=special_coin_wallet.merchant_id where special_coin_wallet.coin_balance>0 and user_id='$sender_id' and special_coin_wallet.merchant_id in((SELECT merchant_id FROM unrecoginize_coin WHERE user_id ='$receiver_id'),'$receiver_id' )";
		$q="select special_coin_wallet.*,m.special_coin_name,m.mobile_number as merchant_no from special_coin_wallet inner join users as m on m.id=special_coin_wallet.merchant_id where special_coin_wallet.coin_balance>0 and user_id='$sender_id' and special_coin_wallet.merchant_id in(SELECT merchant_id FROM unrecoginize_coin WHERE user_id ='$receiver_id' union select id from users where id='$receiver_id' )";
		$useracceptq = mysqli_query($conn,$q);
		$i=0;
		while($wlist = mysqli_fetch_assoc($useracceptq))
		{
				//print_R($wlist);
				$wallet_merchant_id=$wlist['merchant_id'];
				 $q="SELECT * FROM unrecoginize_coin WHERE status=1 and merchant_id='" . $wallet_merchant_id . "' and user_id='" . $receiver_id . "'";
				$merchantaccept = mysqli_fetch_assoc(mysqli_query($conn,$q));
				  $totalcoin_a = "SELECT sum(amount) as totalamount FROM tranfer WHERE MONTH(created_date) = MONTH(CURRENT_DATE()) AND YEAR(created_date) = YEAR(CURRENT_DATE()) and receiver_id='$receiver_id' and coin_merchant_id='$wallet_merchant_id'";
				$acceptedcoin_a = mysqli_fetch_assoc(mysqli_query($conn, $totalcoin_a));
				 $acceptedcoin = floatval($acceptedcoin_a['totalamount']);
				
				 $coin_max_limit = $merchantaccept['coin_max_limit'];
				
				$partnerbal = partnerbal($wallet_merchant_id, $conn);
				$coin_limit = $merchantaccept['coin_limit'];
				$limitclass = $coin_limit - $partnerbal;
				$pending_limit = $coin_max_limit - $acceptedcoin;
				
					if($receiver_id==$wallet_merchant_id)
					{
						
						$pending_limit=100;
					}
					 else if ($limitclass <= $pending_limit)
					 {
						
						$pending_limit = $limitclass;
					 }
					
					 if($rec_user_roles==1)
					 $pending_limit=$coin_balance;
				if($pending_limit>1){
					$pending_limit=number_format($pending_limit,2);
					$coin_balance=$wlist['coin_balance'];
					if($coin_balance<$pending_limit)
					$pending_limit=$coin_balance;
					
					$wlist['pending_limit']=$pending_limit;
					
					$msg = "This number can only accept maximum of RM " .$pending_limit." ".$coin_name;
					$wlist['pending_limit_msg']=$msg;
					
					$finallist[$i]=$wlist;
					$i++;
				}
				
				

		}
		// sender is merchant 
		$senderq = mysqli_query($conn, "SELECT user_roles,name,id,special_coin_name,balance_usd FROM users WHERE id = '$sender_id'");
		$senderdata = mysqli_fetch_assoc($senderq);
		
		$sender_own_bal=$senderdata['balance_usd'];
		if($sender_own_bal>0){
			$w['merchant_id']=$sender_id;
			$w['coin_balance']=$sender_own_bal;
			$w['special_coin_name']=$senderdata['special_coin_name'];
			$w['pending_limit']=$sender_own_bal;
			$finallist[$i]=$w;
		}
		
		if(count($finallist)>0){
		
			foreach($finallist as $wal){ 
				
				
				if($wal['coin_balance']>0){ if($multi_wallet=="true"){?>
						<div class="col-md-12 form-group" data-name="<?=$wal['special_coin_name'] ?>" data-wallet-id="<?=$wal['merchant_id'] ?>" data-amount="<?=number_format($wal['coin_balance'],2) ?>">
														<label for="wallet-<?=$i ?>"><?=$wal['special_coin_name'] ?> <small>(<?=number_format($wal['coin_balance'],2) ?>)</small></label>
														<input type="number" class="form-control mutilewallet_input" s_merchant_id="<?=$wal['merchant_id'] ?>" data-max="<?=number_format($wal['pending_limit'],2) ?>" id="wallet_<?=$wal['merchant_id'] ?>" name="wallet_val">
														<span class="max-pending" id="span_<?=$wal['merchant_id'] ?>" style="color: red;font-size:10px;" data-wallet="<?=$wal['special_coin_name'] ?>"><?php echo $wal['pending_limit_msg']; ?></span>
													</div>
			<?php } else {?>
				<option  pending_limit="<?php echo $wal['pending_limit']; ?>" s_merchant_id="<?php echo $wal['merchant_id'];?>" wallet_label="dynamic" merchant_no="<?php echo $wal['merchant_no']; ?>"  value="<?php  echo $wal['special_coin_name'];?>"><?php  echo $wal['special_coin_name']."- <b>".number_format($wal['coin_balance'],2)."</b>- (Max Limit ".$wal['pending_limit']." )";?></option>  
			<?php } } 
			
			}
		}
		else{
			if($multi_wallet=="true"){ ?>
              <div class="col-md-12 form-group" style="color:red;font-size:16px;">Merchant is not Accepting any coin which you have in your wallet</div>
			  <style>
				  #confirm_transfer{
					  display:none !important;
				  }
			  </style>
			<?php } else {  
				echo "<option>No Wallet to trasfer</option>";
				?>
				 <style>
				  #confirm_transfer{
					  display:none !important;
				  }
			  </style>    
			<?php }
		}
		
	}
	else
	{
		
		$sq="select special_coin_wallet.*,m.special_coin_name,m.mobile_number as merchant_no from special_coin_wallet  inner join users as m on m.id=special_coin_wallet.merchant_id where user_id='$sender_id'";
		$sub_rows = mysqli_query($conn,$sq);
		$all_wallet=mysqli_fetch_all($sub_rows,MYSQLI_ASSOC);
		// sender is merchant 
		$senderq = mysqli_query($conn, "SELECT mobile_number,user_roles,name,id,special_coin_name,balance_usd FROM users WHERE id = '$sender_id'");
		$senderdata = mysqli_fetch_assoc($senderq);
		
		$sender_own_bal=$senderdata['balance_usd'];
		// if($sender_own_bal>0){
		// 	$w['merchant_id']=$sender_id;
		// 	$w['coin_balance']=$sender_own_bal;
		// 	$w['special_coin_name']=$senderdata['special_coin_name'];
		// 	$all_wallet[]=$w;
		// }
		if($multi_wallet=="true"){
			if($sender_own_bal>0){ ?>
			<div class="col-md-12 form-group" data-name="<?=$senderdata['special_coin_name'] ?>" data-wallet-id="<?=$sender_id ?>" data-amount="<?=number_format($sender_own_bal,2) ?>">
													<label for="wallet-<?=$i ?>"><?=$senderdata['special_coin_name'] ?> <small>(<?=number_format($sender_own_bal,2) ?>)</small></label>
													<input type="number" class="form-control mutilewallet_input" s_merchant_id="<?=$sender_id ?>" data-max="<?=number_format($wal['coin_balance'],2) ?>" id="wallet_<?=$wal['merchant_id'] ?>" name="wallet_val">
													<span class="max-pending" id="span_<?=$wal['merchant_id'] ?>" style="color: red;font-size:10px;" data-wallet="<?=$senderdata['special_coin_name'] ?>"><?php echo $senderdata['pending_limit_msg']; ?></span>
					</div>

		<?php }} else  if($sender_own_bal>0){?>
			<option s_merchant_id="<?php echo $sender_id; ?>" wallet_label="dynamic" merchant_no="<?php echo $senderdata['mobile_number']; ?>" value="CF"><?php  echo $senderdata['special_coin_name']."- <b>".number_format($sender_own_bal,2)?></option>
			<?php }   
		foreach($all_wallet as $wal){ 
			if($wal['coin_balance']>0){ if($multi_wallet=="true"){?>
					<div class="col-md-12 form-group" data-name="<?=$wal['special_coin_name'] ?>" data-wallet-id="<?=$wal['merchant_id'] ?>" data-amount="<?=number_format($wal['coin_balance'],2) ?>">
													<label for="wallet-<?=$i ?>"><?=$wal['special_coin_name'] ?> <small>(<?=number_format($wal['coin_balance'],2) ?>)</small></label>
													<input type="number" class="form-control mutilewallet_input" s_merchant_id="<?=$wal['merchant_id'] ?>" data-max="<?=number_format($wal['coin_balance'],2) ?>" id="wallet_<?=$wal['merchant_id'] ?>" name="wallet_val">
													<span class="max-pending" id="span_<?=$wal['merchant_id'] ?>" style="color: red;font-size:10px;" data-wallet="<?=$wal['special_coin_name'] ?>"><?php echo $wal['pending_limit_msg']; ?></span>
					</div>
				
		<?php } else { ?>
			<option  pending_limit="<?php echo $wal['pending_limit']; ?>" s_merchant_id="<?php echo $wal['merchant_id'];?>" wallet_label="dynamic" merchant_no="<?php echo $wal['merchant_no']; ?>"  value="<?php  echo $wal['special_coin_name'];?>"><?php  echo $wal['special_coin_name']."- <b>".number_format($wal['coin_balance'],2)?></option>  
		<?php } }
		} 
		
	} 

}
function partnerbal($coin_merchant_id,$conn)
{
	 $q="SELECT sum(coin_balance) as total_amount FROM `special_coin_wallet` inner join users on users.id=special_coin_wallet.user_id and users.user_roles='2' WHERE `merchant_id` ='$coin_merchant_id'";
	
	$parq=mysqli_query($conn,$q);  
	$p_total=mysqli_fetch_assoc($parq);
	return $p_total['total_amount'];
} 
?>