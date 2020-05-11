<?php 
include("config.php");

if(!isset($_SESSION['login']))
{
	header("location:login.php");
}
	if(!empty($_GET['l'])){
					 // extract($_GET);
					 $merchant_id=$_GET['merchant_id'];
					 $l=$_GET['l'];
					$r_url="index.php?merchant_id=".$merchant_id."&l=".$l;
					header("Location:$r_url"); 
					
			}
if(empty($_GET['vs']))
{
	$url="dashboard.php?vs=".md5(rand());

header("Location:$url");
exit();
}
$me="dashboard";
?>
<!DOCTYPE html>
<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">
<head>
	<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Expires" content="0" />
	<?php include("includes1/head.php"); ?>
	<style>
		/*.sidebar-toggle .ripple{     padding: 0 100px; }*/
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
		.wallet_h{
			font-size: 30px;
			color: #213669;
		}
		.spancls
		{
			font-weight:bold;
			color:red;
		}
		
	</style>
	<script src="https://scripts.sirv.com/sirv.js" ></script>  
	<!-- Manifest -->
	<link rel="manifest" href="manifest.json">   
</head>
<body class="header-light sidebar-dark sidebar-expand pace-done">
	<div id="wrapper" class="wrapper">
		<!-- HEADER & TOP NAVIGATION -->
		<?php include("includes1/navbar.php"); ?>
		<?php
		   // print_R($profile_data);
				// die;
			 $login_user_id=$_SESSION['login'];
			$lastt="select t.sender_id,t.amount,t.created_on,s.name as sender_name,s.mobile_number as sender_mobile,r.name as receiver_name,r.mobile_number as reciver_mobile,w.special_coin_name from tranfer as t inner join users as s on s.id=t.sender_id 
			inner join users as r on r.id=t.receiver_id inner join users as w on w.id=t.coin_merchant_id where (t.sender_id='$login_user_id' or t.receiver_id='$login_user_id') order by t.id desc limit 0,1";
            $lastq=mysqli_query($conn,$lastt);
			$l=mysqli_fetch_assoc($lastq);
			if($l)
			{
				if($l['sender_name']=='')
					$l['sender_name']=$l['sender_mobile'];
				if($l['receiver_name']=='')
					$l['receiver_name']=$l['reciver_mobile'];
				$date_label=$l['created_on'];
				$time_label=date('h:i A',$date_label)." on ".date('d/m/Y',$date_label);
				if($login_user_id==$l['sender_id'])
				{
					if($_SESSION['langfile']=="chinese")
					$s_msg="RM <span class='spancls'>".$l['amount']."</span> 的 <span class='spancls'>".$l['special_coin_name']."</span> 已经成功装入 <span class='spancls' style='color:#51d2b7;'>".$l['receiver_name']."</span> 于 ".$time_label;
					else
					$s_msg="RM <span class='spancls'>".$l['amount']."</span> of <span class='spancls'>".$l['special_coin_name']."</span> has been successfully transfer to <span class='spancls' style='color:#51d2b7;'>".$l['receiver_name']."</span> at ".$time_label;
				
				}
				else
				{
					if($_SESSION['langfile']=="chinese")
					// $s_msg="RM <span class='spancls'>".$l['amount']."</span> 的 <span class='spancls'>".$l['special_coin_name']."</span> 已经成功装入 <span class='spancls' style='color:#51d2b7;'>".$l['receiver_name']."</span> 于 ".$time_label;
				    $s_msg="RM <span class='spancls'>".$l['amount']."</span> 的 <span class='spancls'>".$l['special_coin_name']."</span> 已成功从 <span class='spancls' style='color:#51d2b7;'>".$l['receiver_name']."</span>在 ".$time_label." 加载";
					else
					$s_msg="RM <span class='spancls'>".$l['amount']."</span> of <span class='spancls'>".$l['special_coin_name']."</span> has been successfully Received From  <span class='spancls' style='color:#51d2b7;'>".$l['sender_name']."</span> at ".$time_label;   
				}
			}
			if($_SESSION['login'])
		//if($_COOKIE['PHPSESSID'])
			{
				$user_id=$_SESSION['login'];
				//$user_id=$_COOKIE['PHPSESSID'];
				
				$name=$profile_data['name'];
				$email=$profile_data['email'];
				$mobile_number=$profile_data['mobile_number'];
				$a_user_id=$profile_data['id'];
				$user_name=$profile_data['name'];   
				$a_mboile_no=$profile_data['mobile_number'];
				// file_put_contents("./sessioned-user.txt", $user_id);
				$moengage_unique_id=$profile_data['moengage_unique_id'];
				if($moengage_unique_id=='')
				{
					function generateRandomString($length = 4) {
						$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
						$charactersLength = strlen($characters);
						$randomString = '';
						for ($i = 0; $i < $length; $i++) {
							$randomString .= $characters[rand(0, $charactersLength - 1)];
						}
						return $randomString;
					}
					$unique_id=generateRandomString();
					$unique=$unique_id."moncon3n2".$mobile_number;       
				}   
		?>
		<?php if($moengage_unique_id=='')
			{ include('mpush.php'); ?>   
			
			<script type="text/javascript">
				<?php if($moengage_unique_id==''){
					mysqli_query($conn, "UPDATE users SET moengage_unique_id='".$unique."' WHERE id='".$_SESSION['login']."'");
					?>
					Moengage.add_user_attribute("koo_id", "<?php echo $user_id; ?>");
					<?php if($name){  ?> Moengage.add_first_name("<?php echo $name; ?>"); <?php } ?>
					<?php if($name){  ?> Moengage.add_user_name("<?php echo $name; ?>"); <?php } ?>
					<?php if($email){  ?> Moengage.add_email("<?php echo $email; ?>"); <?php } ?>
					<?php if($mobile_number){  ?> Moengage.add_user_name("<?php echo $mobile_number; ?>"); <?php } ?>
					Moengage.add_unique_user_id("<?php echo $unique; ?>"); // UNIQUE_ID is used to uniquely identify a user.       
				<?php } ?>  
			</script>   
			<?php
		}
	}
	?>      
		<!-- /.navbar -->
		<div class="content-wrapper">
			<!-- SIDEBAR -->
			<?php include("includes1/sidebar.php"); ?>
			<!-- /.site-sidebar -->
			<?php
				// who accept the c&m coin_active
			if($profile_data['special_coin_name'] && $profile_data['user_roles']=="2")
			{
				$merchant_id=$profile_data['id'];
				$puery="select unrecoginize_coin.*,users.name as merchant_name,users.special_coin_name from unrecoginize_coin inner join users on users.id=unrecoginize_coin.merchant_id where unrecoginize_coin.merchant_id='$merchant_id' and status=1 order by unrecoginize_coin.id desc";
				$p_query = mysqli_query($conn,$puery);
				$ppartner=mysqli_num_rows($p_query);
			}
			?>
			<main class="main-wrapper clearfix" style="min-height: 522px;">
				<div class="container-fluid" id="main-content" style="padding-top:25px">
				<!--span class="btn btn-primary"  data-toggle="modal" data-target="#qrmodel"><?php echo $language['my_qr_code'];?></span!-->
				<span class="btn btn-primary last_tras" style="color:black;"><?php echo $language['last_tras'];?></span>
				<button class="btn btn-primary" style="color:black;" onclick='transfer("<?php echo $_SESSION['login'];?>,0,0")'><?php echo $language['transfer'];?></button>
				<a href="transaction_history.php" style="color:black;" class="btn btn-primary"><?php echo $language['transaction_history']; ?></a>
				<?php if($balance['user_roles']==2){ ?>
				<button class="btn btn-primary"style="color:black;"    onclick='topup("<?php echo $_SESSION['login'];?>")' id="top_up"><?php echo $language['top_up'];?></button>   
				 
				 <?php if($ppartner>0){ ?>
				 <a class="btn btn-primary" style="color:black;"  href="coinpartner.php?m_id=<?php echo $balance['id'];?>"><?php echo $language['accept_partner_list'];?></a>   
				 
				 <?php } ?>
				<?php } ?>   
 			 		 <a class="btn btn-primary" style="color:black;background:red;"  href="index.php?vs=<?php echo rand(); ?>"><?php echo $language['more_shops'];?>
						 <img class="Sirv" data-src="https://koofamilies.sirv.com/shop.png" alt="" />
						 <img class="Sirv" data-src="https://koofamilies.sirv.com/shop.png" alt="" />
						 <img class="Sirv" data-src="https://koofamilies.sirv.com/shop.png" alt="" />
					 </a>      

				     <?php
				 	        $sqcoin="select SQL_NO_CACHE m.id as m_id,m.name as m_name,special_coin_wallet.*,m.special_coin_name from special_coin_wallet  
            				inner join users as m on m.id=special_coin_wallet.merchant_id 
            				where user_id='$login_user_id' and special_coin_wallet.coin_balance>0 and special_coin_wallet.coin_active='y'";
            						      
            			$coin_rows = mysqli_query($conn,$sqcoin);
            			$coin_rows2 = mysqli_query($conn,$sqcoin);
            			$totalcointcount=mysqli_num_rows($coin_rows); 	 
                       if($totalcointcount>0 || $balance['balance_usd']){ ?>
						<h2 class="text-center wallet_h"><?php echo $language['wallet_balance'];?></h2>
						
						<div class="row">
							<?php if($totalcointcount){ ?>
								<div class="col-md-4" >
									<div class="form-group">
									<?php
									  $sq="SELECT * FROM `service` where status=1";
									  $cat_rows = mysqli_query($conn,$sq);
									   if(mysqli_num_rows($cat_rows)>0){						
											
									?>
										<select name= "service_id" id= "service_id" class="form-control business_type" style="width:100%">

										   <option value="0"><?php echo "All Industry" ?></option>
										   <?php while ($cat=mysqli_fetch_assoc($cat_rows)){ ?>
										   <option value="<?php echo $cat["id"]; ?>"><?php echo $cat['service_name']; ?></option>
									   <?php } ?>
										</select>
									   <?php } ?>
									  </div>
								</div>
							 <?php } ?>
							 <div class="col-md-4">
								<div class="form-group">        
						   
									   <?php
									   if(mysqli_num_rows($coin_rows)>0){						
											
									?>
										<select name= "special_coin" id= "special_coin" class="form-control business_type " >
										   <option value="0"><?php echo "All Coin" ?></option>
										   <?php while ($coin=mysqli_fetch_assoc($coin_rows)){ ?>
										   <option value="<?php echo $coin["special_coin_name"]; ?>"><?php echo $coin['special_coin_name']; ?></option>
									   <?php } ?>
										</select>
									   <?php } ?>
								</div>
							</div>
							 <div class="col-md-4">
								<div class="form-group"> 
									 <?php
									   if(mysqli_num_rows($coin_rows2)>0){						
											
									?>
										<select name= "m_name" id= "m_name" class="form-control">
										   <option value="0"><?php echo "All Merchant" ?></option>
										   <?php while ($coin=mysqli_fetch_assoc($coin_rows2)){ ?>
										   <option value="<?php echo $coin["m_id"]; ?>"><?php echo $coin['m_name']; ?></option>   
									   <?php } ?>
										</select>   
									   <?php } ?>
								</div>
							</div>
						</div>
						<div class="row wallet_list">
							 
						</div>
					   <?php  }
						$notifications = mysqli_query($conn, "SELECT * FROM notifications WHERE user_id=".$_SESSION['login']." AND readStatus='0' ORDER BY id DESC LIMIT 10");
						$noticount=mysqli_num_rows($notifications);
						if($noticount>0)
						{
					?>
						<h2 class="wallet_h text-center"><?php echo $language['notification'];?></h2> 
						<div class="row">
							<table class="table table-striped" style="width:100% !important;">
								<tr>   
									<th><?php echo $language['type'];?></th>
									<th><?php echo $language['notification'];?></th>
									<th><?php echo $language['arrived_on'];?></th>
									
								</tr>
								<?php
								
								while($notification = mysqli_fetch_assoc($notifications))
								{
									?>
									<tr>
										<td><?php echo $notification['type']; ?></td>
										<td><?php echo $notification['notification']; ?></td>
										<td><?php echo date("d-m-Y h:i A",$notification['created_on']); ?></td>
									</tr>
									<?php
								}
								mysqli_query($conn, "UPDATE notifications SET readStatus='1' WHERE user_id=".$_SESSION['login']);
								?>
							</table>
							<?php
							if(mysqli_num_rows($notifications) == 0)
							{
								echo "<div style='text-align:center; color: red; font-size: 17px;'>".$language['no_more_notification']."</div>";
							}
							?>
						</div>
						<?php } ?>
				</div>

<!--a href="transaction_history.php" class="btn btn-primary"><?php echo $language['transaction_history']; ?></a!-->


<div id="topup_model" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->  
		<div class="modal-content">
			<div class="modal-header">
				<h3><?php echo $language['self_topup']; ?></h3>
				<button type="button" class="close"data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body" style="text-align: left;padding:0px;">
				<div class="credentials-container">
					<h5 class="top_pass"><?php echo $language['enter_password']; ?></h5>
					<div>
						<div class="input-group mb-2 top_pass" style="margin-bottom:5px !important;">
							<input type="password" autocomplete="tel" id="topup_pass" class="form-control" style="min-width:250px;" placeholder="" name="topup_pass" required="" />
							<input type="submit" id="topup_submit" class="btn btn-primary" value="<?php echo $language['confirm'];?>"/>
						</div>
					
						<div class="input-group mb-2" style="margin-bottom:5px !important;">
							<span class="error-block-topup-pass" for="fund_pass" style="display: none; color: red"><?php echo $language['password_wrong']; ?></span>
						</div>
							<div class="input-group mb-2 top_pass_2" style="margin-bottom:5px !important;display:none;">
								<div class="input-group-prepend">
									<div class="input-group-text" style="background-color:#51D2B7;border-radius: 5px 0 0 5px;height: 100%;padding: 0 10px;display: grid;align-content: center;"><?php echo $language['amount']; ?></div>
								</div>
								<input type="text" maxlength="8" autocomplete="tel" id="topup_amount"  oninput="this.value = this.value.replace(/[^0-9.]/g, '');" class="topup_amount form-control" style="min-width:250px;" placeholder="Top up amount" name="topup_amount" required="" />
							</div>
							<div class="input-group mb-2" style="margin-bottom:5px !important;">
								<span class="error-block-for-topup-amount" style="display: none;color: red"><?php echo $language['please_trasfer_amount']; ?></span>
							</div>
							<div class="input-group mb-2 top_pass_2" style="margin-bottom:5px !important;display:none;">
								<input type="button" id="self_topup" class="btn btn-primary" value="<?php echo $language['confirm'];?>" style="width: 40%;" />
								<input type="button" id="cancel_topup" class="btn btn-primary" value="<?php echo $language['cancel'];?>" style="width: 40%; margin-left:20%;">
							</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


			</main>
		</div>
		<!-- /.widget-body badge -->
	</div>
	<!-- /.widget-bg -->
	<input type="hidden" id="private" style="position: fixed;right: 0; bottom: 50px;">

    <!-- /.content-wrapper -->
	<?php include("includes1/footer.php"); ?>
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
	
	<div class="modal fade" id="TModel" role="dialog" style="">  
   <div class="modal-dialog">
          

            <!-- Modal content-->
            <div class="modal-content">
              
                <div class="modal-header">
                    <button type="button" class="close final_done" data-dismiss="modal">&times;</button>
					
                </div>
                 
                    <div class="modal-body" style="padding-bottom:0px;">
					     <p id="show_msg_t" style="font-size:22px;font-weight:bold;"><?php echo $s_msg; ?></p>
                    
						
                    </div>
                    <div class="modal-footer" style="padding-bottom:2px;">
                        <div class="row" style="margin: 0;">
			 
						<div class="input-group mb-2" style="margin-bottom:5px !important;">
								
								<input type="button"  class="btn btn-primary final_done"  value="<?php echo "DONE"; ?>" style="width: 40%; margin-left:20%;">
							</div>
						 
						         
					   
						</div>
						  
						
                    </div>
                  
            </div>
        </div>
  </div>         
</body>
	<!-- <script>
		
		// It has been commented because it does not exist such file service-worker.js and it throws an error on console
	  if ('serviceWorker' in navigator) {
	    navigator.serviceWorker.register('/service-worker.js')
	      .then(function(reg){
	        console.log("Service Worker loaded correctly");
	      }).catch(function(err) {
	        console.log("Service Worker error: ", err)
	      });
	  }
	</script> -->



<script>
 $(document).ready(function(){
        var local_id=localStorage.getItem("login_live_id");
	    // alert(local_id);
		$.ajax({
		  url: "wallet_list.php",
		  context: document.body
		}).done(function(response) {
		  // $('#products').remove(); // you can keep this line if you think is necessary
		  $('.wallet_list').html(response);
		});   
		if(local_id=='' || local_id==null)
		{
			localStorage.setItem('login_live_id','<?php echo $_SESSION['login'];?>');
			localStorage.setItem('login_live_role_id','<?php echo $_SESSION['login_user_role'];?>');
			// localStorage.setItem('login_live_role_id','<?php echo "2";?>');
		}   
		
  
 });
    $('.last_tras').click(function (){
		$('#TModel').modal('show');  
	});
	$('#cancel_topup').click(function (){
		$('#topup_model').modal('hide');
	});
	$('#service_id').on("change",function() {
	  var selectedCat = $(this).children("option:selected").val();
	  $.ajax({
		  url: "wallet_list.php?service_id="+selectedCat,
		  context: document.body
		}).done(function(response) {
		  // $('#products').remove(); // you can keep this line if you think is necessary
		  $('.wallet_list').html(response);
		});
	});
	
	$('#special_coin').on("change",function() {
	  var selectedCoin = $(this).children("option:selected").val();
	  $.ajax({
		  url: "wallet_list.php?coin_name="+selectedCoin,
		  context: document.body
		}).done(function(response) {
		  // $('#products').remove(); // you can keep this line if you think is necessary
		  $('.wallet_list').html(response);
		});
	});
	$('#m_name').on("change",function() {
	  var selectedCoin = $(this).children("option:selected").val();
	  $.ajax({
		  url: "wallet_list.php?m_id="+selectedCoin,
		  context: document.body
		}).done(function(response) {
		  // $('#products').remove(); // you can keep this line if you think is necessary
		  $('.wallet_list').html(response);
		});
	});
	
	// top up process 
	function topup(user_id) {
	
		$('.error-block-topup-pass').hide();
		$('#top_user_id').val(user_id);
		$('#topup_model').modal('show');
	}
		function myFunction() {
		  var x = document.getElementById("new_fund_password");
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
	$('#topup_submit').click(function () {
		if ($('#topup_pass').val() == '<?php echo $profile_data["fund_password"];?>') {
			$('.top_pass').hide();
			$('.top_pass_2').show();
		}
		else {
			$('.error-block-topup-pass').show();
		}
		
	});
	// $('#transfer_wallet_type').on("change",function() {
	  // var wallet_label = $('#transfer_wallet_type option:selected').attr('wallet_label');
	  // if(wallet_label=="dynamic")
	  // {
		// var merchant_no = $('#transfer_wallet_type option:selected').attr('merchant_no');
		// $('#transfer_to').val(merchant_no);
	  // }
	  // else
	  // {
		  
	  // }
	// });
	$('#self_topup').click(function () {
		$('#self_topup', this).attr('disabled', 'disabled');      
		var topup_amount=$('#topup_amount').val();
		var top_user_id="<?php echo $_SESSION['login'];?>";
		// alert(top_user_id);
		if(topup_amount)
		{
			if(topup_amount>99000)
			{
				alert('You cannot do top up more than 99000$ at once');
			}
			else
			{
				var btn = document.getElementById('self_topup');
				btn.disabled = true;
				$(this).removeClass(" btn-primary").addClass("btn-default");
				$.ajax({
					  
					  url: "functions.php",
					 type:'POST',
					  dataType : 'json',
					  data: {topup_amount:topup_amount,user_id:top_user_id,method:"topupmerchant"},
					  success:function(response){
						  var data = JSON.parse(JSON.stringify(response));
						  if(data.status==true)
						  {
							  alert('Topup Successfully');
							location.reload(true);  
						  }  
						  else
						  {
							  
							  alert(data.msg);
							  btn.disabled = false;
							$('#self_topup').removeClass("btn-default").addClass("btn-primary");
						  }
						 
						}
					  });
			}
		}
		else
		{
			$('.error-block-for-topup-amount').show();
		}
		
	});  
	
</script>
<script>
	$('#private').click(function (){
		$.post('private.php', {'sender_id':"<?php echo $_SESSION['login'];?>"}, function(data) {
			console.log(data);
		});
	});
</script>  
</html>

