<?php

include('config.php');

extract($_POST);

$login_user_id=$_SESSION['login'];

if($login_user_id)

{

	$user_q=mysqli_query($conn,"select  * from users where id='$login_user_id'");

	$user_data=mysqli_fetch_assoc($user_q);
	//print_R($user_data);   
	
	if($user_data)

	{

		$a_mboile_no=$user_data['mobile_number'];

	 ?>

	 <?php if($user_data['balance_myr']){ ?>

						<div class="col-md-4 well text-center">

						<a href="transaction_history.php?coin_type=MYR">

							<h3 style="color:#51d2b7;">MYR</h3>

							<h4><?php if($user_data['balance_myr']){echo number_format($user_data['balance_myr'],2);} else{ echo "0.00";} ?></h4>

						    <!--img src="<?php echo $site_url."/img/touch.png";?>" style="width:125px;"/!-->

						</a>

						

						</div>

					   <?php }  if($user_data['balance_usd']>0) { ?>  

						<div class="col-md-4 well text-center">

							<a href="transaction_history.php?coin_type=<?php if($_SESSION['login_user_role']==2){ echo urlencode($user_data['special_coin_name']);}else { echo "CF";} ?>"><h3 style="color:#51d2b7;">

							<?php if($user_data['user_roles']==2){ echo $user_data['special_coin_name'];} ?>  

							</h3>

							

							<h4><?php if($user_data['balance_usd']){echo number_format($user_data['balance_usd'],2);} else{ echo "0.00";} ?></h4>

							<!--img src="<?php echo $site_url."/img/touch.png";?>" style="width:125px;"/!-->

							</a>

						

							

						</div>

					   <?php } if($user_data['balance_inr']){ ?>

						<!--div class="col-md-4 well text-center">

						

						<a href="transaction_history.php?coin_type=Koo CashBack">

							<h3 style="color:#51d2b7;">Koo CashBack</h3>

							<h4><?php if($user_data['balance_inr']){echo number_format($user_data['balance_inr'],2);} else{ echo "0.00";} ?></h4>
									
										

							

							</a>

						

						</div!-->

					   <?php }
					   		$condition = "";	
						 	if(isset($_GET['service_id']) && !empty($_GET['service_id'])){
						 		if($_GET['service_id'] != 'All'){
						 			//
						 			$sq="select id from users where service_id=".$_GET['service_id'];
						 			$sub_rows = mysqli_query($conn,$sq);
						 				$uids = array();
						 				while ($users=mysqli_fetch_assoc($sub_rows)){
						 						array_push($uids, $users['id']);		
						      			}	
						 		}
						 		if(!empty($uids)){
						 		    $ids = join(",",$uids); 
						 		    $condition = ' AND special_coin_wallet.merchant_id IN('.$ids.')';
						 		}
						 	}
						 	if(isset($_GET['coin_name']) && !empty($_GET['coin_name'])){
						 		if($_GET['coin_name'] != 'All'){
						 			$condition = " AND m.special_coin_name='".$_GET['coin_name']."'";
						 		}
						 		
						 	}
							if(isset($_GET['m_id']) && !empty($_GET['m_id'])){
						 		if($_GET['m_id'] != 'All'){
						 			$condition = " AND m.id='".$_GET['m_id']."'";
						 		}
						 		
						 	}


						      $sq="select special_coin_wallet.*,m.special_coin_name from special_coin_wallet  
						      inner join users as m on m.id=special_coin_wallet.merchant_id 
						      where user_id='$login_user_id' and special_coin_wallet.coin_balance>0 and special_coin_wallet.coin_active='y'".$condition;

								$totalwallet=0;

							$sub_rows = mysqli_query($conn,$sq);

						  if(mysqli_num_rows($sub_rows)>0){

							 

							while ($swallet=mysqli_fetch_assoc($sub_rows)){

								// print_R($swallet);

								// die;

								$s_merchant_id=$swallet['merchant_id'];

								if($swallet['coin_balance']>0)

								$totalwallet++;
								if($swallet['merchant_id']==6419)
								$m_url='transaction_history.php?w_type=koo cashback';
								else
								$m_url="structure_merchant.php?vs=".md5(rand())."&merchant_id=".$swallet['merchant_id'];

							

								

							

					?>

						<div class="col-md-4 well text-center">

						<a href="<?php echo $m_url;?>">

						<h3 style="color:#51d2b7;">

						

						<?php echo $swallet['special_coin_name'];?></h3> 

						

						<h4><?php if($swallet['coin_balance']){echo number_format($swallet['coin_balance'],2);} else{ echo "0.00";} ?></h4>

						<!--img src="<?php echo $site_url."/img/touch.png";?>" style="width:125px;"/!-->

						</a>

						<?php if($s_merchant_id=="5062"){

							$defalut_plan="select count(plan.id) as total_count,u.created from membership_plan as plan inner join user_membership_plan as u on u.plan_id=plan.id where plan.user_id='$s_merchant_id' and plan.default_plan='y'

							and u.user_mobile='$a_mboile_no'";

							$defalutarray = mysqli_fetch_assoc(mysqli_query($conn,$defalut_plan));

							$defalutplan=$defalutarray['total_count'];

							if($defalutplan>0)

							{

								$created_date=$defalutarray['created'];

								$local_coin=mysqli_fetch_assoc(mysqli_query($conn, "SELECT sum(local_coin) as local_coin FROM local_coin_sync WHERE user_mobile='$a_mboile_no' and merchant_id='$s_merchant_id' and order_date>='$created_date'"))['local_coin'];

								$total_amount=mysqli_fetch_assoc(mysqli_query($conn, "SELECT sum(total_cart_amount) as total_amount FROM order_list WHERE user_id='$a_user_id' and merchant_id='$s_merchant_id' and created_on>='$created_date'"))['total_amount'];

							    $total_p=number_format(($local_coin+$total_amount),2); ?>

								<h3 style="font-size:16px;">M. Point: <?php echo $total_p; ?></h3>

							<?php }        

						}?>  

						</div>   

							<?php }}  ?>   

	<?php }

}

else

{

	echo "No Wallet Found";

}



?>