<!-- Scripts -->
<script src="./Dashboard_files/jquery.min.js.download"></script>
<script src="./Dashboard_files/popper.min.js.download"></script>
<script src="./Dashboard_files/bootstrap.min.js.download"></script>
<script src="./Dashboard_files/jquery.magnific-popup.min.js.download"></script>
<script src="./Dashboard_files/mediaelementplayer.min.js.download"></script>
<script src="./Dashboard_files/metisMenu.min.js.download"></script>
<script src="./Dashboard_files/perfect-scrollbar.jquery.js.download"></script>
<script src="./Dashboard_files/sweetalert2.min.js.download"></script>
<script src="./Dashboard_files/jquery.counterup.min.js.download"></script>
<script src="./Dashboard_files/jquery.waypoints.min.js.download"></script>
<script src="./Dashboard_files/Chart.min.js.download"></script>
<script src="./Dashboard_files/Chart.bundle.min.js.download"></script>
<script src="./Dashboard_files/utils.js.download"></script>
<script src="./Dashboard_files/jquery.knob.min.js.download"></script>
<script src="./Dashboard_files/jquery.sparkline.min.js.download"></script>
<script src="./Dashboard_files/excanvas.js.download"></script>
<script src="./Dashboard_files/mithril.js.download"></script>
<script src="./Dashboard_files/widgets.js.download"></script>
<script src="./Dashboard_files/moment.min.js.download"></script>
<script src="./Dashboard_files/underscore-min.js.download"></script>
<script src="./Dashboard_files/clndr.min.js.download"></script>
<script src="./Dashboard_files/jquery-ui.min.js.download"></script>
<script src="./Dashboard_files/morris.min.js.download"></script>
<script src="./Dashboard_files/raphael.min.js.download"></script>
<script src="./Dashboard_files/daterangepicker.min.js.download"></script>
<script src="./Dashboard_files/slick.min.js.download"></script>
<script src="./Dashboard_files/theme.js.download"></script>
<script src="./Dashboard_files/isotop.min.js"></script>
<script src="./Dashboard_files/custom.js.download"></script>
<script src="//rum-static.pingdom.net/pa-60309b987c17460013000172.js" async></script>
<script src="https://unpkg.com/multiple-select@1.5.2/dist/multiple-select.min.js"></script>
<script src="./js/custom.js"></script>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-5D6JWJ3RCH"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-5D6JWJ3RCH');
</script>

 <script>
      var loadDeferredStyles = function() {
        var addStylesNode = document.getElementById("deferred-styles");
        var replacement = document.createElement("div");
        replacement.innerHTML = addStylesNode.textContent;
        document.body.appendChild(replacement)
        addStylesNode.parentElement.removeChild(addStylesNode);
      };
      var raf = window.requestAnimationFrame || window.mozRequestAnimationFrame ||
          window.webkitRequestAnimationFrame || window.msRequestAnimationFrame;
      if (raf) raf(function() { window.setTimeout(loadDeferredStyles, 0); });
      else window.addEventListener('load', loadDeferredStyles);
    </script>

<style>
	/*loader Css */
	table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
}
th, td {
  padding: 15px;
}
	.page_loader {
		position: fixed;
		left: 0px;
		top: 0px;
		width: 100%;
		height: 100%;
		z-index: 999999;
		background-color: rgba(255, 255, 255, 0.5);
	}

	#load {
		background-image: url("loader.gif");
		background-position: center center;
		background-repeat: no-repeat;
		bottom: 0;
		height: auto;
		left: 0;
		margin: auto;
		position: absolute;
		right: 0;
		top: 0;
		width: 100%;
		max-width: 200px;
		background-size: contain;
	}

	.load_parentcss {
		background: "transparent" !important;
		z-index: "-1" !important;
	}
</style>
<script>
	// ===== Page Loader ==== 
	document.onreadystatechange = function() {
		var state = document.readyState;
		if (state == 'complete') {
			setTimeout(function() {
				document.getElementById('interactive');
				document.getElementById('load').style.visibility = "hidden";
				$('#load').parent().css({
					"background": "transparent",
					"z-index": "-1"
				});
			}, 1000);
		}
	}

    if(window.location.href.includes('orderview.php')) {
        let elem = document.getElementById("load");
        elem.parentElement.remove();
    }

	function removeLoader() {
		$("#load").removeAttr('style');
		$('.page_loader').hide();
		$("#load").hide();
	}
	jQuery(window).on('load', function() {
		//wait for page load PLUS two seconds.
        setTimeout(removeLoader, 2000);
	});
	jQuery(document).on("click", '.showLoader', function() {
        if(window.location.href.includes('orderview.php')) return false;
		$('.page_loader').removeAttr('style');
		$("#load").removeAttr('style');;
		$('.page_loader').show();
		$("#load").show();
		setTimeout(function () {
			$('.page_loader').hide();
			$("#load").hide();
		}, 3000);
	});
	jQuery(document).on("click", '.showLoader6', function() {
        if(window.location.href.includes('orderview.php')) return false;
		$('.page_loader').removeAttr('style');
		$("#load").removeAttr('style');;
		$('.page_loader').show();
		$("#load").show();
		setTimeout(function () {
			$('.page_loader').hide();
			$("#load").hide();
		}, 10000);
	});
	jQuery(document).on("click", '.search_anchor', function() {
        if(window.location.href.includes('orderview.php')) return false;
		$('.page_loader').removeAttr('style');
		$("#load").removeAttr('style');;
		$('.page_loader').show();
		$("#load").show();
		setTimeout(function () {
			$('.page_loader').hide();
			$("#load").hide();
		}, 10000);
	});
</script>


<!-- BEGIN JIVOSITE CODE {literal} -->
<!--script type='text/javascript'>
(function(){ var widget_id = 'QCJcJ4Qb9Q';var d=document;var w=window;function l(){ var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = '//code.jivosite.com/script/widget/'+widget_id; var ss = document.getElementsByTagName('script')[0]; ss.parentNode.insertBefore(s, ss);}if(d.readyState=='complete'){l();}else{if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})();
</script!-->
<div id="fund_user_model" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<p><?php echo $language['transfer']; ?> <span id="total_wallet_amount"></span></p>
				<button type="button" class="close"data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body" style="text-align: left;padding:0px;">
				<div class="credentials-container">
					<div>
							<?php if($profile_data['name']==''){ ?>
							 <h5><?php echo "Please create your username, which is recognised by your friend"; ?></h5>
							<div class="input-group mb-2" style="margin-bottom:5px !important;">
							   <input type="text" autocomplete="tel" id="fund_username"  class="form-control" style="min-width:225px;" placeholder="Username" name="fund_username" required="" />
							</div>   
							<?php }  else {?>
							 <input type="hidden" autocomplete="tel" id="fund_username"  class="form-control" style="min-width:225px;" placeholder="Username" name="fund_username" value="<?php echo $profile_data['name'];?>" />
						    <?php } if($u_role_id==1){?>
							
							<input type="hidden" id="merchant_send" value="n"/>
							<?php }  else {?>
							  	<input type="hidden" id="merchant_send" value="n"/>
							  	     
							<?php } ?>
							<div class="input-group mb-2" style="margin-bottom:5px !important;">
								<span class="error-block-fund-username" for="fund_pass" style="display: none; color: red"></span>
							</div>
								<input type="hidden" id="fund_user_id"/>
								
							<?php if($profile_data['fund_password']==''){ ?>
							<div class="input-group mb-2" style="margin-bottom:5px !important;">
								<div class="input-group-prepend">
									<div class="input-group-text" style="background-color:#51D2B7;border-radius: 5px 0 0 5px;height: 100%;padding: 0 10px;display: grid;align-content: center;"><?php echo "Password"; ?></div>
								</div>
								<input type="password" autocomplete="tel"   oninput="this.value = this.value.replace(/[^0-9.]/g, '');"   maxlength="8"  id="new_fund_password" class="form-control" style="min-width:250px;" placeholder="Create Fund Password" name="new_fund_password" required />
								
									 
							</div> 
							<div class="input-group mb-2" style="margin-bottom:5px !important;">
								<i  onclick="myFunction()" id="eye_slash" class="fa fa-eye-slash" aria-hidden="true"></i>
								<span onclick="myFunction()" id="eye_pass"> <?php echo $language['show_password']; ?> </span>   
							</div>
							<?php } else { ?>
							<input type="hidden" id="new_fund_password" value="<?php echo $profile_data['fund_password']; ?>"/>
							<?php } ?>
							<div class="input-group mb-2" style="margin-bottom:5px !important;">
								<span class="error-block-for-newfundpassword" style="display: none;color: red"></span>
							</div>
							<div class="input-group mb-2" style="margin-bottom:5px !important;">
								<input type="button" id="create_fund" class="btn btn-primary" value="<?php echo "Create"; ?>" style="width: 40%;" />
								<input type="button"  class="btn btn-primary cancel_transfer" value="<?php echo $language['cancel']; ?>" style="width: 40%; margin-left:20%;">
							</div>
						
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="fund_wallet_model" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<h4><?php echo $language['transfer']; ?> <span id="total_wallet_amount"></span></h4>
				<button type="button" class="close"data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body" style="text-align: left;">
				<div class="credentials-container">
					<h5><?php echo $language['enter_fund_password']; ?></h5>
					<div>
						<div class="input-group mb-2" style="margin-bottom:5px !important;">
							<input type="password" autocomplete="tel" id="fund_pass" class="fund_pass form-control" style="min-width:160px;" placeholder="" name="fund_pass" required="" />
							<input type="submit" id="confirm_fund" class="btn btn-primary" value="<?php echo $language['confirm']; ?>"/>  

						
						</div>
						<div class="input-group mb-2" style="margin-bottom:5px !important;">
							<i  onclick="myFunctionfund()" id="eye_slash_fund" class="fa fa-eye-slash" aria-hidden="true"></i>
							<span onclick="myFunctionfund()" id="eye_pass_fund"> <?php echo $language['show_password']; ?>  </span>
			 
							<span class="error-block-fund-pass" for="fund_pass" style="display: none; color: red"><?php echo $language['fund_password_wrong']; ?></span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="qr_scan_model" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<h4>Qr Scan</h4>
				<button type="button" class="close"data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body" style="text-align: left;">
				<div class="credentials-container">
				   <video id="preview"></video>
				</div>
			</div>
		</div>     
	</div>
</div>
<div id="fund_wallet_input_modal" class="modal fade" role="dialog" style=" overflow-y: auto;">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<h5><?php echo $language['trasfer_info']; ?><h5>
				<button type="button" class="close"data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body" style="text-align: left;">
				<div class="credentials-container">
					<div>
						<form action="dashboard.php" method="post" id="form-transfer">
							<input type="hidden" name="sender_id" id="sender_id" value="<?php echo $_SESSION['login']; ?>" />
							<div class="input-group mb-2" style="margin-bottom:5px !important;">
								<div class="input-group-prepend">
									<div class="input-group-text" style="background-color:#51D2B7;border-radius: 5px 0 0 5px;height: 100%;padding: 0 10px;display: grid;align-content: center;"><?php echo $language['transfer_to']; ?> +60</div>
								</div>
								<input type="number" autocomplete="tel" id="transfer_to" oninput="this.value = this.value.replace(/[^0-9.]/g, '');" maxlength="12" class="transfer_to form-control"  placeholder="<?php echo $language['mobile_number']; ?>" name="transfer_to" required="" />
							</div>
							<div class="input-group mb-2" style="margin-bottom:5px !important;">
								<span class="error-block-for-mobile" style="display: none;color: red"><?php echo $language['invalid_mobile']; ?></span>
							</div>  
							 <span style="margin-left:46%;">Or </span>
						
							<div class="row" style="padding-left: 8%;margin: 0%;min-height: 40px;margin-bottom:2%;">
							  <div class="col-md-6 merchant_select" style="font-weight:bold;border-right: 1px solid black;padding-top: 2%;background: #51D2B7;">
							  <input type="checkbox" id="merchant_select_checkbox"/><?php echo $language['transfer_by_name']; ?></div>
							  <!--div class="col-md-4 qr_select" style="font-weight:bold;padding-top: 2%;background: #51D2B7;">
							   <input type="checkbox" id="qr_select_checkbox"/> <?php echo $language['qr_code']; ?></div!-->
							   
							   <?php if($_SESSION['login_user_role']=='2'){ ?>
							    <div class="col-md-6 cashout" style="font-weight:bold;padding-top: 2%;background: #51D2B7;">
							   <input type="checkbox" id="cashout"/> <?php echo $language['cashout']; ?></div> <?php } ?>
							</div>
							<div class="input-group mb-2" id="transfer_name_label" style="display:none;margin-bottom:5px !important;">
								
								<input type="text" autocomplete="tel" id="transfer_name"  maxlength="12" class="transfer_name form-control"  placeholder="Search By User Name" name="transfer_name" required="" />
							</div>   
							<div class="card user_info" style="display:none;border:1px solid #51D2B7">
								
								  <div class="card-body">
									<h5 class="card-title user_name" id="user_name" style="text-align:center;"><?php echo $language['transfer_to']; ?> <span class="user_mobile" style="font-weight:bold;color:red;"></span></h5>   
								</div>
							</div>
							 
							<h4 class="both_user" style="display:none;font-size: 16px;">Number exit in both user and merchant whom you want to Transfer </h4>
							<input type="hidden" id="both_user_role" value="n"/>
							<div class="input-group mb-2 both_user" style="display:none;margin-bottom:5px !important;">
								
								<div class="input-group-prepend">
									<div class="input-group-text" style="background-color:#51D2B7;border-radius: 5px 0 0 5px;height: 100%;padding: 0 10px;display: grid;align-content: center;"><?php echo "Transfer To whom"; ?></div>
								</div>
								<!-- <input type="text" id="transfer_wallet_type" class="transfer_wallet_type form-control" style="min-width:250px;" placeholder="Wallet Type" name="transfer_wallet_type" required="" /> -->
								<select id="trasfer_as" class="form-control"  name="trasfer_as" required="">
									<option value="-1">Select User Type</option>
									<option value="1">Member</option>
									<option value="2">Merchant</option>
								</select>
							</div> 
							<div class="input-group mb-2" style="margin-bottom:5px !important;">
								<span class="error-block-for-trasfer-as" style="display: none;color: red"><?php echo "Select Whom to Transfer"; ?></span>
							</div>
							<h4 class="intro_user" style="display:none;font-size: 16px;color: red;">Number Look's New </h4>
							<div class="input-group mb-2 intro_user" style="display:none;margin-bottom:5px !important;">
								
								<div class="input-group-prepend">
									<div class="input-group-text" style="background-color:#51D2B7;border-radius: 5px 0 0 5px;height: 100%;padding: 0 10px;display: grid;align-content: center;"><?php echo "Reffer As"; ?></div>
								</div>
								<!-- <input type="text" id="transfer_wallet_type" class="transfer_wallet_type form-control" style="min-width:250px;" placeholder="Wallet Type" name="transfer_wallet_type" required="" /> -->
								<select id="reffer_as" class="form-control"  name="reffer_as" required="">
									<option value="member">Member</option>
									<option value="merchant">Merchant</option>
								</select>
							</div>
							
							<div class="input-group mb-2 transfer_amount_div" style="margin-bottom:5px !important;">
								<div class="input-group-prepend">
									<div class="input-group-text" style="background-color:#51D2B7;border-radius: 5px 0 0 5px;height: 100%;padding: 0 10px;display: grid;align-content: center;"><?php echo $language['amount']; ?></div>
								</div>  
								<input type="text" autocomplete="tel"  oninput="this.value = this.value.replace(/[^0-9.]/g, '');"   maxlength="8"  id="transfer_amount" class="transfer_amount form-control"  placeholder="<?php echo $language['amount_to_transfer']; ?>" name="transfer_amount" required="" />
							</div>
							<div class="input-group mb-2" style="margin-bottom:5px !important;">
								<span class="error-block-for-amount" style="display: none;color: red"><?php echo $language['please_trasfer_amount']; ?></span>
							</div>
							
							<div class="input-group mb-2" style="margin-bottom:5px !important;">
								<div class="input-group-prepend">
									<div class="input-group-text" style="background-color:#51D2B7;border-radius: 5px 0 0 5px;height: 100%;padding: 0 10px;display: grid;align-content: center;"><?php echo $language['wallet']; ?></div>
								</div>
								<!-- <input type="text" id="transfer_wallet_type" class="transfer_wallet_type form-control" style="min-width:250px;" placeholder="Wallet Type" name="transfer_wallet_type" required="" /> -->
								<select id="transfer_wallet_type" class="transfer_wallet_type form-control"  name="transfer_wallet_type" required="">
									<option value=""><?php echo $language['select_wallet']; ?></option>
									<?php if($balance['balance_myr']>0) {?>
									<option value="MYR">MYR</option> <?php }  if(($balance['balance_usd']>0) && $profile_data['user_roles']=='2' && $profile_data['special_coin_name']) { ?>     
									<option s_merchant_id="<?php echo $balance['id'];?>" wallet_label="dynamic" merchant_no="<?php echo $balance['mobile_number']; ?>"  value="CF"><?php echo $balance['special_coin_name']."- <b>".number_format($profile_data['balance_usd'],2)."</b>";?></option> <?php } if($balance['balance_inr']>0) { ?>
									<option value="INR">KOO Coin</option><?php } 
									 $sq="select special_coin_wallet.*,m.special_coin_name,m.mobile_number as merchant_no from special_coin_wallet  inner join users as m on m.id=special_coin_wallet.merchant_id where user_id='$a_user_id'";
						  
						$sub_rows = mysqli_query($conn,$sq);
									$all_wallet=mysqli_fetch_all($sub_rows,MYSQLI_ASSOC); if(count($all_wallet)>0){
										// print_R($all_wallet);
										
							foreach($all_wallet as $wal){ if($wal['coin_balance']>0){?>
									<option  s_merchant_id="<?php echo $wal['merchant_id'];?>" wallet_label="dynamic" merchant_no="<?php echo $wal['merchant_no']; ?>"  value="<?php  echo $wal['special_coin_name'];?>"><?php  echo $wal['special_coin_name']."- <b>".number_format($wal['coin_balance'],2)."</b>";?></option>  
							<?php }} } ?>
								</select>
							</div>
							<div class="input-group mb-2" style="margin-bottom:20px !important;">
								<span class="error-block-for-wallet-type" style="display: none;color: red"><?php echo $language['plz_select_wallet']; ?></span>
							</div>
							<div class="mb-2" style="max-height: 30vh;overflow-y: auto;">
								<div class="row" id="wallet_amounts" style="display: none;">
								<?php 
								
								if($balance['balance_myr']) {?>
									<?php 
										var_dump($balance);/*
									?>

									<option value="MYR" data-amount="<?=number_format($balance['balance_myr'], 2) ?>" data-wallet-name="<?=$balance['special_coin_name'] ?>">MYR</option> <?php }  if(($balance['balance_usd']>0) && $profile_data['user_roles']=='2' && $profile_data['special_coin_name']) { ?>     
									<option s_merchant_id="<?php echo $balance['id'];?>" data-amount="<?=number_format($balance['balance_usd'], 2) ?>" wallet_label="dynamic" merchant_no="<?php echo $balance['mobile_number']; ?>" data-wallet-name="<?=$balance['special_coin_name'] ?>"  value="CF"><?php echo $balance['special_coin_name']."- <b>".number_format($profile_data['balance_usd'],2)."</b>";?></option> <?php } if($balance['balance_inr']) { ?>
									<option value="INR" data-amount="<?=number_format($balance['balance_inr'], 2) ?>" data-wallet-name="<?=$balance['special_coin_name'] ?>">KOO Coin</option><?php */ 
								} 
									if(count($all_wallet)>0){
										$i = 0;
										foreach($all_wallet as $wal){
											if($wal['coin_balance']){?>
												<div class="col-md-12 form-group" data-name="<?=$wal['special_coin_name'] ?>" data-wallet-id="<?=$wal['merchant_id'] ?>" data-amount="<?=number_format($wal['coin_balance'],2) ?>">
													<label for="wallet-<?=$i ?>"><?=$wal['special_coin_name'] ?> <small>(<?=number_format($wal['coin_balance'],2) ?>)</small></label>
													<input type="number" class="form-control" s_merchant_id="<?=$wal['merchant_id'] ?>" data-max="<?=number_format($wal['coin_balance'],2) ?>" id="wallet-<?=$i ?>" name="wallet_val">
													<span class="error-block-for-wallet" style="display: none;color: red" data-wallet="<?=$wal['special_coin_name'] ?>"></span>
												</div>
											<!-- <option  s_merchant_id="<?php echo $wal['merchant_id'];?>" data-amount="<?=number_format($wal['coin_balance'],2) ?>" data-wallet-id="<?=$wal['id'] ?>" wallet_label="dynamic" merchant_no="<?php echo $wal['merchant_no']; ?>"  value="<?php  echo $wal['special_coin_name'];?>"><?php  echo $wal['special_coin_name']."- <b>".number_format($wal['coin_balance'],2)."</b>";?></option>   -->
								<?php 
											} 
											$i++;
										}
									}
								?>
									<!-- It will be auto-filled -->
								</div>
							</div>

							<div class="mb-2">
								<button id="autofill-wallets" style="display: none;" class="btn btn-primary">Autofill</button>
							</div>

							<div class="mb-2">
								<input type="checkbox" name="multiple_wallet" id="multiple_wallet">
								<label for="multiple_wallet">Use more than one wallet</label>
							</div>
							<div class="input-group mb-2" style="margin-bottom:5px !important;">
								<div class="input-group-prepend">
									<div class="input-group-text" style="background-color:#51D2B7;border-radius: 5px 0 0 5px;height: 100%;padding: 0 10px;display: grid;align-content: center;"><?php echo $language['remarks']; ?></div>
								</div>  
								<textarea name="remark" id='remark' rows='1' cols='4'  class='form-control'></textarea>   
								
							</div>
							

							<div class="input-group mb-2" style="margin-bottom:5px !important;">
								<span class="current-balance" style="display: none;color: #595d70;display: none;"><?php echo $language['cur_bal']; ?>:<b></b></span>
							</div>

							<div class="input-group mb-2" style="margin-bottom:5px !important;">
								<input type="button" id="confirm_transfer" class="btn btn-primary" value="<?php echo $language['confirm']; ?>" style="width: 40%;" />
								<input type="button"  class="btn btn-primary cancel_transfer"  value="<?php echo $language['cancel']; ?>" style="width: 40%; margin-left:20%;">
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- {/literal} END JIVOSITE CODE -->
<script type="text/javascript">
function generatetokenno(length) {
   var result           = '';
   var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
   var charactersLength = characters.length;
   for ( var i = 0; i < length; i++ ) {
      result += characters.charAt(Math.floor(Math.random() * charactersLength));
   }
   return result;
}


<!--  trasnfer module !-->
    function transfer(user_id,mobile_number,merchant_name) {
		// var user_login="<?php echo $_SESSION['login']; ?>";
		// alert(user_id);
		
		if(user_id)
		{
			if(merchant_name)
			$('.trasfer_merchant_name').html(merchant_name);
			if(mobile_number)
			{
				$('#transfer_to').val(mobile_number);
				$('.user_info').show();
				
				$('.user_name').html("Transfer to "+merchant_name);
			}  
			$('#PartnerModel').modal('hide');
			var user_name="<?php echo clean($profile_data['name']); ?>";
			// alert(user_name);
			var db_fb_password="<?php echo $profile_data['fund_password']; ?>";
			var u_role_id="<?php echo $u_role_id; ?>";
			$('#fund_user_id').val(user_id);
			if(user_name=='')
			{
				$('.error-block-fund-username').hide();
				$('#fund_user_model').modal('show');
			}   
			else
			{
				if(db_fb_password=='')
				{
				   $('#fund_user_model').modal('show');	
				}  
				else
				{
					
						$('.error-block-fund-username').html('Username is Required');
						$('.error-block-fund-username').show();
						$('#fund_user_id').val(user_id);
						$('#fund_wallet_model').modal('show');
						// $('#fund_wallet_input_modal').modal('show');
				}
			}
		}
		else
		{
			var msg="To You wallet Feature Account has to be login";
			$('#show_msg').html(msg);
			$('#AlerModel').modal('show'); 
			setTimeout(function(){ $("#AlerModel").modal("hide"); },2000);
		}
	}
	
	$('#create_fund').click(function () {
		//fund_engine();
		var fund_username=$('#fund_username').val();
		var new_fund_password=$('#new_fund_password').val();
		var user_id=$('#fund_user_id').val();
		// alert(user_id);
		if(fund_username!='')
		{
			$('.error-block-fund-username').hide();
			if(new_fund_password!='')
			{
				$.ajax({
					  
					  url: "functions.php",
					 type:'POST',
					  dataType : 'json',
					  data: {user_id:user_id,fund_username:fund_username,method:"savename",fund_password:new_fund_password},   
					  success:function(response){
						  
							var btn = document.getElementById('create_fund');
							btn.disabled = true;
							$(this).removeClass("btn-primary").addClass("btn-default");
						  var data = JSON.parse(JSON.stringify(response));
						  if(data.status==true)
						  {
							   $('#fund_user_model').modal('hide');
							$('#fund_wallet_input_modal').modal('show');  
						  }
						  else
						  {
							alert(data.msg);
							btn.disabled = false;
							$('#').removeClass("btn-default").addClass("btn-primary"); 
						  }
						 
						}
				}); 	
			}
			else
			{
			   $('.error-block-for-newfundpassword').html('Fund Password is Required');
				$('.error-block-for-newfundpassword').show();	
			}
			
		}
		else
		{
			$('.error-block-fund-username').html('Username is Required');
			$('.error-block-fund-username').show();
		}
	}); 
	  
	$('.cancel_transfer').click(function (){
		location.reload(true);
		$('#fund_wallet_input_modal').modal('hide');
		$('#fund_user_model').modal('hide');
	});
	$('#confirm_fund').click(function () {
		fund_engine();
	});
	function fund_engine(){
		if ($('#fund_pass').val() == '<?php echo $profile_data["fund_password"];?>') {
			$('.error-block-fund-pass').hide();
			// alert('success');
			$('#fund_wallet_model').modal('hide');
			$('#fund_wallet_input_modal').modal('show');
			// $('#fund_pass').val('');

		}else {
			$('.error-block-fund-pass').show();
		}
	}
<!--  end trasnfer module !-->
function myFunctionfund() {
  var x = document.getElementById("fund_pass");
  if (x.type === "password") {
    x.type = "text";
	    $("#eye_pass_fund").html('Hide Password');
			 $('#eye_slash_fund').removeClass( "fa-eye-slash" );
            $('#eye_slash_fund').addClass( "fa-eye" );
			
  } else {
    x.type = "password";   
	 $("#eye_pass_fund").html('Show Password');
	  $('#eye_slash_fund').addClass( "fa-eye-slash" );
            $('#eye_slash_fund').removeClass( "fa-eye" );
  }
}
$(document).ready(function(e){
	// alert(3);
	$(".myrefresh").click(function(){
			$("#myModal_forcerefresh").modal({
			show: false,
			backdrop: 'static'
			});
			$("#myModal_forcerefresh").modal('show');
			$("#myModal_language").modal('hide'); 
			$("#myModal").modal('hide');
			return false;
		});
	  var merchant_tags = [];
	 $("input[type='submit']").on("click", function(){
        $(this).removeClass(" btn-primary").addClass("btn-default");
		setTimeout(function() {
			
         $(this).removeClass("btn-default").addClass("btn-primary");
			}.bind(this), 5000);
	}); 
	
	$('#cancel_trasfer').click(function(){
		location.reload();
	});
	$("#transfer_wallet_type_multiple").multipleSelect({
			selectAll: false
		});
		$("#transfer_wallet_type_multiple").multipleSelect("disable").hide();

		$("#multiple_wallet").on("change", function(){
			var multi_wallet = $("#multiple_wallet").is(":checked");
		var telVal=$('#transfer_to').val();
		var sender_id="<?php echo $_SESSION['login']; ?>";
		// alert(multi_wallet);
		if(telVal!='')
		{
			
				
			
			$("#error_must_fill_no").hide();
			$.ajax({
						url: "acceptedwalletlist.php",
					 	type:'POST',
						 cache: false,
					  	data: {transfer_to:telVal,sender_id:sender_id,multi_wallet:multi_wallet},   
					  	success:function(response){
							  if(response){
								  
									$('#confirm_transfer').show();
										if(multi_wallet)
										{
											$('.transfer_amount_div').hide();
											$('#wallet_amounts').html(response);
											$("#wallet_amounts").show();
											$("#autofill-wallets").show();
											$("#transfer_wallet_type").hide();

										}
										else if(multi_wallet==false)
										{
											$('.transfer_amount_div').show();
											$("#wallet_amounts").hide();
											$("#autofill-wallets").hide();
											$("#transfer_wallet_type").show();
											$('#transfer_wallet_type').html(response);

										}
									
							}
							
							
						 
						}
					});
			

		}
		else
		{
			$("#error_must_fill_no").show();
			$("#wallet_amounts").hide();
		}
		});
		$("#autofill-wallets").on("click", function(e){
			e.preventDefault();

			var amount = parseFloat($("#transfer_amount").val());
			var total = amount;
			$("#wallet_amounts .form-group").each(function(){
				if(total <= 0){
					$(this).find("input").val(0);
				}else{
					var max = parseFloat($(this).attr("data-amount"));
					if(max > total){
						$(this).find("input").val(total.toFixed(2));
						total -= max;
					}else{
						$(this).find("input").val(max.toFixed(2));
						total -= max;
					}
				}
			});


		});
	$('#final_confirm').click(function(){
		var number=$('#transfer_to').val();
		var multiwallet = $("#multiple_wallet").is(":checked");
		//alert(multiwallet);
		if(multiwallet){
			var transfer_wallet_type 	= 	{};
			var wallet_merchant_id 		= 	{};
			$("#wallet_amounts input").each(function(){
			  
				var name = $(this).parent().attr("data-name");
				
				var value = $(this).val();
				if(value != '' && value != 0){
					wallet_merchant_id[name] = $(this).attr("s_merchant_id");
					transfer_wallet_type[name] = $(this).val();
				} 
			});

		}else{
			var transfer_wallet_type=$('#transfer_wallet_type').val();
			var wallet_merchant_id=$("#transfer_wallet_type option:selected").attr("s_merchant_id");
		}
		console.log(transfer_wallet_type);
		var both_user_role=$('#both_user_role').val();
		var trasfer_as=$('#trasfer_as').val();
		var reffer_as=$('#reffer_as').val();
		
		// alert(wallet_merchant_id);
		var transfer_amount=$('#transfer_amount').val();
		// alert(reffer_as);   
		// alert(transfer_amount);
		var low_bal_label="<?php echo $language['low_bal'] ?>";
		// alert(low_bal_label);
		if (parseFloat(transfer_amount) >= 0.01)    
		{
			$('span.error-block-for-amount').hide();	
				// if(number.length >= 9 && number.length <= 14){
				if(number.length >= 9 && number.length <= 12 && (number[0] == 1 || number[0]==6 || number[0]==0)){
				$('.error-block-for-mobile').hide();
				// alert(both_user_role);
				// alert(trasfer_as);
				if(transfer_amount)
				{
					if(both_user_role=="y")
					{
						if(trasfer_as=='-1')
						{
							$('#trasfer_as').focus();
							$('.error-block-for-trasfer-as').show();
							return false;
						}
					}
					$('.error-block-for-trasfer-as').hide();
				 
					if(transfer_wallet_type)
					{
						$('.error-block-for-wallet-type').hide();
						var btn = document.getElementById('confirm_transfer');
						btn.disabled = true;        
						$(this).removeClass(" btn-primary").addClass("btn-default");
						btn.innerText = 'Posting...';
						$('.error-block-for-mobile').hide();
						$.post('transfer_module.php', {trasfer_as:trasfer_as,both_user_role:both_user_role,reff_as:reffer_as,wallet_merchant_id:wallet_merchant_id,transfer_amount:transfer_amount,wallet_type:transfer_wallet_type,mobile_number:$('#transfer_to').val(), sender_id: "<?php echo $_SESSION['login'];?>",multi_wallet: multiwallet}, function (data){
						 var objresult = JSON.parse(data);
						 $('.error-block-for-wallet').hide();
						if (objresult.status==true) {
							
							var data=objresult.data;
							// var balance_data = JSON.parse(data);
							var balance_data = data;  

							var postdata = {};
							postdata.created = new Date();
							postdata.created = postdata.created.getTime();
							postdata.sender_name = '<?php echo clean($profile_data["name"]) ?>';
							postdata.sender_mobile = '<?php echo $profile_data["mobile_number"] ?>';
							postdata.sender_id = $('#sender_id').val();
							postdata.receiver_id = balance_data['id'];
							postdata.amount = $('#transfer_amount').val();
							postdata.remark = $('#remark').val();   
							postdata.merchant_send =$('#merchant_send').val();
							postdata.new_coin_trasfer =balance_data['new_coin_trasfer']; 
							// postdata.login_token =balance_data['login_token']; 
							var reciver_label=balance_data['reciver_label'];
							var time_label=balance_data['time_label'];
							var sender_label=balance_data['sender_label'];  
							postdata.wallet_merchant_id =wallet_merchant_id;
							postdata.multi_wallet = multiwallet;
							postdata.wallet_type = transfer_wallet_type;
							if (postdata.sender_id == postdata.receiver_id) {
							
								$('.error-block-for-mobile').html('Cant able to send amount to self no');
								
									$('.error-block-for-mobile').show();
									btn.disabled = false;
								// btn.innerText = 'Posting...';
								   $('#confirm_transfer').removeClass("btn-default").addClass("btn-primary");
							}
							else {
								$('.error-block-for-mobile').hide();
								if (postdata.amount == '') {
									$('.error-block-for-amount').show();
								}else {
									$('.error-block-for-amount').hide();
									if (postdata.wallet_type == '') {
										$('.error-block-for-wallet-type').show();
									} else {
										$('span.current-balance>b').html(parseFloat(balance_data[postdata.wallet_type]));
										$('span.current-balance').show();

										$('.error-block-for-wallet-type').hide();
										// alert(parseFloat(postdata.amount));
										// alert(parseFloat(balance_data[postdata.wallet_type]));
										if (parseFloat(postdata.amount) > parseFloat(balance_data[postdata.wallet_type])) {
											$('span.error-block-for-amount').html(low_bal_label);
											$('span.error-block-for-amount').show();
												btn.disabled = false;
											$('#confirm_transfer').removeClass("btn-default").addClass("btn-primary");
										}
										else {
											$('span.error-block-for-amount').html('Please type amount to transfer');
											$('span.error-block-for-amount').hide();
											 //alert('success');
										    $.ajax({
													url :'transfer_module.php',
													type:'POST',
													dataType : 'json',
													data:postdata,   
													success:function(response){
														var data = JSON.parse(JSON.stringify(response));
														$('span.current-balance>b').html(parseFloat(balance_data[postdata.wallet_type]+" "+data.coin_name));  
														if(data.status==true)
														{
															// var msg="Fund Trasfer Successfully";
															var trasfer_lang="<?php echo $_SESSION['langfile']; ?>";  
															// alert(multiwallet);
															if(multiwallet){
																if(trasfer_lang=="chinese"){
																	var msg="RM <span class='spancls'>"+transfer_amount+"</span> 的  <span class='spancls'>"+sender_label+"</span> 已经成功装入 <span class='spancls' style='color:#51d2b7;'>"+reciver_label+"</span> 于 "+time_label;	
																	msg += "<table id='wallet_sent_table'>";
																	if(sender_label.length > 1){
																		let index = 0;
																		for(var key in transfer_wallet_type){
																			msg += "<tr><td>" + sender_label[index] + "</td><td>" + transfer_wallet_type[key] + "</td></tr>";
																			index++;
																		}
																		msg += "</table>";
																	}
																}else{
																	var msg="RM <span class='spancls'>"+transfer_amount+"</span> of <span class='spancls'>"+sender_label+"</span> has been successfully transfer to <span class='spancls' style='color:#51d2b7;'>"+reciver_label+"</span> at "+time_label;
																	if(sender_label.length > 1){
																		msg += "<table id='wallet_sent_table'>";
																		let index = 0;
																		for(var key in transfer_wallet_type){
																			msg += "<tr><td>" + sender_label[index] + "</td><td>" + transfer_wallet_type[key] + "</td></tr>";
																			index++;
																		}
																		msg += "</table>";
																	}
																}
															}
															else{
																if(trasfer_lang=="chinese")
															var msg="RM <span class='spancls'>"+transfer_amount+"</span> 的  <span class='spancls'>"+sender_label+"</span> 已经成功装入 <span class='spancls' style='color:#51d2b7;'>"+reciver_label+"</span> 于 "+time_label;	
															else
															var msg="RM <span class='spancls'>"+transfer_amount+"</span> of <span class='spancls'>"+sender_label+"</span> has been successfully transfer to <span class='spancls' style='color:#51d2b7;'>"+reciver_label+"</span> at "+time_label;
															}
															
															
														   
															//return;    
															$('#show_msg_t').html(msg);
															$('#fund_wallet_input_modal').modal('hide'); 
															$('#TModel').modal('show');
															// $('form#form-transfer').submit();   
															
															 
														  
														}
														else
														{
															$('#BeforeTrasfer').modal('hide');
															btn.disabled = false;
															$('#confirm_transfer').removeClass("btn-default").addClass("btn-primary");
															// alert(data.msg);
															$('.error-block-for-wallet-type').html(data.msg);	
														  $('.error-block-for-wallet-type').show();	  
														}   
														}		  
												});
											
										}
									}  
								}
							}
							
						}
						else {
							btn.disabled = false;
						// btn.innerText = 'Posting...';
						$(this).removeClass(" btn-default").addClass("btn-primary");
							$('.error-block-for-mobile').html(objresult.msg);
							$('.error-block-for-mobile').show();
						}
					});
					}
					else
					{
						$('.error-block-for-wallet-type').show();
					}
					
					
				}
				else
				{
					$('span.error-block-for-amount').html('Enter Transfer Amount');
					$('span.error-block-for-amount').show();
				}
				
				
			}
			else
			{
				$('#transfer_to').focus();
				$('.error-block-for-mobile').html('Invalid Mobile Number');
				$('.error-block-for-mobile').show();
			}
		}
		else
		{
		   $('span.error-block-for-amount').html('Min Transfer  Amount is 0.01');
			$('span.error-block-for-amount').show();	
		}
		
	});       
	$(document).on('change','.mutilewallet_input',function( e ) {  
		
		var dInput = parseFloat(this.value);
		var data_max= $(this).attr('data-max');
		var input_id= $(this).attr('id');
		var s_merchant_id= $(this).attr('s_merchant_id');
		// alert('input '+dInput);
		// alert('max value '+data_max);
		if(dInput>data_max){
		
			var span_id="span_"+s_merchant_id;
			var max_str="This number can only accept maximum of RM "+data_max ;
			document.getElementById(span_id).style.fontSize = "16px";
			document.getElementById(span_id).style.fontWeight = "bold";
			document.getElementById(span_id).textContent=max_str;
			document.getElementById(input_id).value = '';
			document.getElementById(input_id).focus();
			// $(span_id).html("You exit more value");
		}
		else{
			
			var span_id="span_"+s_merchant_id;
			document.getElementById(span_id).textContent='';
		
		} 
		var sum = 0;
			$("input[class *= 'mutilewallet_input']").each(function(){
				sum += +$(this).val();
			});
			
			$('#transfer_amount').val(sum); 
	});
	$('#confirm_transfer').click(function(){
		var number=$('#transfer_to').val();
		var both_user_role=$('#both_user_role').val();
		var trasfer_as=$('#trasfer_as').val();
		var transfer_wallet_type=$('#transfer_wallet_type').val();
		var reffer_as=$('#reffer_as').val();
		var wallet_merchant_id=$("#transfer_wallet_type option:selected").attr("s_merchant_id");
		// alert(wallet_merchant_id);
		var transfer_amount=$('#transfer_amount').val();
		var remark=$('#remark').val();
		// alert(reffer_as);   
		// alert(transfer_amount);
		var low_bal_label="<?php echo $language['low_bal'] ?>";
		// alert(low_bal_label);
		var multiwallet = $("#multiple_wallet").is(":checked");
		// alert(multiwallet);
		if(multiwallet){
			var transfer_wallet_type 	= 	{};
			var wallet_merchant_id 		= 	{};
			$("#wallet_amounts input").each(function(){
				var name = $(this).parent().attr("data-name");
				var value = $(this).val();
				if(value != '' && value != 0){
					wallet_merchant_id[name] = $(this).attr("s_merchant_id");
					transfer_wallet_type[name] = $(this).val();
				}
			});

		}else{
			var transfer_wallet_type=$('#transfer_wallet_type').val();
			var wallet_merchant_id=$("#transfer_wallet_type option:selected").attr("s_merchant_id");
		}
		if (parseFloat(transfer_amount) >= 0.01)    
		{
			$('span.error-block-for-amount').hide();	                
				// if(number.length >= 9 && number.length <= 14){                
				if(number.length >= 9 && number.length <= 12 && (number[0] == 1 || number[0]==6 || number[0]==0)){
				$('.error-block-for-mobile').hide();
				// alert(both_user_role);
				// alert(trasfer_as);
				if(transfer_amount)
				{
					if(both_user_role=="y")
					{
						if(trasfer_as=='-1')
						{
							$('#trasfer_as').focus();
							$('.error-block-for-trasfer-as').show();
							return false;
						}
					}
					$('.error-block-for-trasfer-as').hide();
					// alert(transfer_wallet_type);  
					if(transfer_wallet_type)
					{
						
						$('.error-block-for-wallet-type').hide();
						var btn = document.getElementById('confirm_transfer');
						//btn.disabled = true;        
						$(this).removeClass(" btn-primary").addClass("btn-default");
						btn.innerText = 'Posting...';
						$('.error-block-for-mobile').hide();
						$.post('transfer_module.php', {trasfer_as:trasfer_as,both_user_role:both_user_role,reff_as:reffer_as,wallet_merchant_id:wallet_merchant_id,transfer_amount:transfer_amount,wallet_type:transfer_wallet_type,mobile_number:$('#transfer_to').val(), sender_id: "<?php echo $_SESSION['login'];?>",multi_wallet: multiwallet}, function (data){
						 var objresult = JSON.parse(data);
						 $('.error-block-for-wallet').hide();
						if (objresult.status==true) {
							
							var data=objresult.data;
							// var balance_data = JSON.parse(data);
							var balance_data = data;  

							var postdata = {};
							postdata.created = new Date();
							postdata.created = postdata.created.getTime();
							postdata.sender_name = '<?php echo clean($profile_data["name"]) ?>';
							postdata.sender_mobile = '<?php echo $profile_data["mobile_number"] ?>';
							postdata.sender_id = $('#sender_id').val();
							postdata.receiver_id = balance_data['id'];
							postdata.amount = $('#transfer_amount').val();
							postdata.remark = $('#remark').val();   
							postdata.merchant_send =$('#merchant_send').val();
							postdata.new_coin_trasfer =balance_data['new_coin_trasfer']; 
							// postdata.login_token =balance_data['login_token']; 
							var reciver_label=balance_data['reciver_label'];
							var time_label=balance_data['time_label'];
							var sender_label=balance_data['sender_label'];  
							
							postdata.wallet_merchant_id =wallet_merchant_id;
							postdata.wallet_type = $('#transfer_wallet_type').val();
							
							if (postdata.sender_id == postdata.receiver_id) {
							
								$('.error-block-for-mobile').html('Cant able to send amount to self no');
								
									$('.error-block-for-mobile').show();
									btn.disabled = false;
								// btn.innerText = 'Posting...';
								   $('#confirm_transfer').removeClass("btn-default").addClass("btn-primary");
							}
							else {
								$('.error-block-for-mobile').hide();
								if (postdata.amount == '') {
									$('.error-block-for-amount').show();
								}else {
									$('.error-block-for-amount').hide();
									//alert((postdata.wallet_type).isArray);
								
									if (postdata.wallet_type == '' && multiwallet==false) {
										$('.error-block-for-wallet-type').show();
									} else {
										$('span.current-balance>b').html(parseFloat(balance_data[postdata.wallet_type]));
										$('span.current-balance').show();

										$('.error-block-for-wallet-type').hide();
										//alert(parseFloat(postdata.amount));   
										//alert(parseFloat(balance_data[postdata.wallet_type]));
										if (parseFloat(postdata.amount) > parseFloat(balance_data[postdata.transfer_amount])) {
											$('span.error-block-for-amount').html(low_bal_label);
											$('span.error-block-for-amount').show();
												btn.disabled = false;
											$('#confirm_transfer').removeClass("btn-default").addClass("btn-primary");
										}
										else {
										//	alert('else case draw');
											$('span.error-block-for-amount').html('Please type amount to transfer');
											$('span.error-block-for-amount').hide();
											if(remark)
												var remark_label="</br> Remark :"+remark;
													else
															var remark_label='';
												var trasfer_lang="<?php echo $_SESSION['langfile']; ?>";  
											//	alert(multiwallet);
												if(multiwallet){
												//	alert('inside multiple wallet');
													if(trasfer_lang=="chinese"){
																	var msg="RM <span class='spancls'>"+transfer_amount+"</span> 的  <span class='spancls'>"+sender_label+"</span> 已经成功装入 <span class='spancls' style='color:#51d2b7;'>"+reciver_label+"</span> 于 "+time_label;	
																	msg += "<table id='wallet_sent_table'>";
																	if(sender_label.length > 1){
																		let index = 0;
																		for(var key in transfer_wallet_type){
																			msg += "<tr><td>" + sender_label[index] + "</td><td>" + transfer_wallet_type[key] + "</td></tr>";
																			index++;
																		}
																		msg += "</table>";
																	}
																}else{
																	// var msg="RM <span class='spancls'>"+transfer_amount+"</span> of <span class='spancls'>"+sender_label+"</span> has been successfully transfer to <span class='spancls' style='color:#51d2b7;'>"+reciver_label+"</span> at "+time_label;
																	var msg="Are You want to trasfer below coins ??"
																		console.log(transfer_wallet_type);
																		msg += "<table id='wallet_sent_table'>";
																		let index = 0;
																		for(var key in transfer_wallet_type){
																			msg += "<tr><td>" + sender_label[index] + "</td><td>" + transfer_wallet_type[key] + "</td></tr>";
																			index++;
																		}
																		msg += "</table>";
																
																}

												}
												else
												{		
													//alert('otuside multiple wallet');
														if(trasfer_lang=="chinese")
															var msg="你要转吗 RM <span class='spancls'>"+transfer_amount+"</span> 的  <span class='spancls'>"+sender_label+"</span> 已经成功装入 <span class='spancls' style='color:#51d2b7;'>"+reciver_label+"</span> 于 ? ";
															else
															var msg="Are you want to Transfer RM <span class='spancls'>"+transfer_amount+"</span> of <span class='spancls'>"+sender_label+"</span>  to <span class='spancls' style='color:#51d2b7;'>"+reciver_label+"</span> ??";
												}
											//alert(msg);
											var remark_val=$('#remark').val();
											if(remark_val)
											$('#remark_confirm_text').html("Remark :"+$('#remark').val());		
											$('#before_trasfer_text').html(msg);	   	
											$('#BeforeTrasfer').modal('show');
											return;
										/*
										    $.ajax({
													url :'transfer_module.php',
													type:'POST',
													dataType : 'json',
													data:postdata,   
													success:function(response){
														var data = JSON.parse(JSON.stringify(response));
														$('span.current-balance>b').html(parseFloat(balance_data[postdata.wallet_type]+" "+data.coin_name));  
														if(data.status==true)
														{
															
															
															if(remark)
															var remark_label="</br> Remark :"+remark;
															else
															var remark_label='';
															var trasfer_lang="<?php echo $_SESSION['langfile']; ?>";  
															
															if(trasfer_lang=="chinese")
															var msg="RM <span class='spancls'>"+transfer_amount+"</span> 的  <span class='spancls'>"+sender_label+"</span> 已经成功装入 <span class='spancls' style='color:#51d2b7;'>"+reciver_label+"</span> 于 "+time_label+remark_label;	
															else
															var msg="RM <span class='spancls'>"+transfer_amount+"</span> of <span class='spancls'>"+sender_label+"</span> has been successfully transfer to <span class='spancls' style='color:#51d2b7;'>"+reciver_label+"</span> at "+time_label+remark_label;
														
															$('#show_msg_t').html(msg);
															$('#fund_wallet_input_modal').modal('hide'); 
															$('#TModel').modal('show');
															
															
															 
														  
														}
														else
														{
															btn.disabled = false;
															$('#confirm_transfer').removeClass("btn-default").addClass("btn-primary");
															
															$('.error-block-for-wallet-type').html(data.msg);	
														  $('.error-block-for-wallet-type').show();	  
														}   
														}		  
												});
												*/
											
										}
									}
								}
							}
							
						}
						else {
							btn.disabled = false;
						// btn.innerText = 'Posting...';
						$(this).removeClass(" btn-default").addClass("btn-primary");
							$('.error-block-for-amount').html(objresult.msg);
							$('.error-block-for-amount').show();
						}
					});
					}
					else
					{
						$('.error-block-for-wallet-type').show();
					}
					
					
				}
				else
				{
					$('span.error-block-for-amount').html('Enter Transfer Amount');
					$('span.error-block-for-amount').show();
				}
				
				
			}
			else
			{
				$('#transfer_to').focus();
				$('.error-block-for-mobile').html('Invalid Mobile Number');
				$('.error-block-for-mobile').show();
			}
		}
		else
		{
		   $('span.error-block-for-amount').html('Min Transfer  Amount is 0.01');
			$('span.error-block-for-amount').show();	
		}
		
	});
	
    $('#merchant_select_checkbox').change(function() {
		$('.qr_select').css("background-color", "#51D2B7");
		$('.qr_select').css("color", "#555");
		$('.merchant_select').css("background-color", "#51D2B7");
		$('.merchant_select').css("color", "#555");
		$('.user_info').hide();
        if(this.checked) {
			$('#transfer_name').val('');
			$('.merchant_select').css("background-color", "red");
			$('.merchant_select').css("color", "white"); 
        }
		else
		{
			$('.merchant_select').css("background-color", "#51D2B7");
			$('.merchant_select').css("color", "#555"); 
			$('#transfer_name_label').hide();
			$('.user_info').hide();
			$('#transfer_to').val('');
			$("#transfer_to").prop("readonly", false);
		}
        $('#merchant_select_checkbox').val(this.checked);        
    });
	$('.cashout').click(function(e) {
		$('#transfer_name_label').hide();
		$('.qr_select').css("background-color", "#51D2B7");
		$('.qr_select').css("color", "#555");
		$('.merchant_select').css("background-color", "#51D2B7");
		$('.merchant_select').css("color", "#555");
		$(this).css("background-color", "red");
		$(this).css("color", "white");
		 $("#merchant_select_checkbox").prop("checked", false);
		 $("#qr_select_checkbox").prop("checked",false);
		 $("#cashout").prop("checked",true);
		$('#transfer_to').val('137285670');
		// $('#transfer_to').val(user_mobile);
							var number = $('#transfer_to').val();
							// alert(number.length);
							// if(number.length >= 9 && number.length <= 14){
							if(number.length >= 9 && number.length <= 12 && (number[0] == 1 || number[0]==6)){
								$('.error-block-for-mobile').hide();   
								$.ajax({  
											url :'functions.php',
											type:'POST',
											dataType : 'json',
											data:{mobile_number:number,method:"userdetail"},   
											success:function(response){
													var data = JSON.parse(JSON.stringify(response));
													if(data.status==true)
													{
														var both_user=data.both_user;
														$('#both_user_role').val(both_user);
														// alert(both_user);
														if(both_user=="y")
														{
														
														   $('.user_info').hide();
															$('.both_user').show();
														}
														else
														{
															 $('.user_name').show();
															$('.both_user').hide();
															var user_name=data.data.name;
															if(user_name)
															{
																$('.intro_user').hide();
																$('.user_info').show();
																$('.user_mobile').html(user_name);
															}
															else
															{
																$('.intro_user').hide();
																$('.user_info').hide();
															}	
														}
														
													}
													else
													{
														$('.user_info').hide();
														$('.intro_user').show();
													}	
											}		  
										});
							
							}  
	});
	//  search by merchant name 
	$('.merchant_select').click(function(e) {
		$('#transfer_to').val('');
		$('.qr_select').css("background-color", "#51D2B7");
		$('.qr_select').css("color", "#555");
		$('.cashout').css("background-color", "#51D2B7");
		$('.cashout').css("color", "#555");
		<?php 
				$qMerchant = mysqli_query($conn, "SELECT id,name FROM users WHERE name!='' and id not in('1944')  ORDER BY name ASC");
				
				while($row = mysqli_fetch_assoc($qMerchant)){
					// print_R($row);
					// die;
					$m_name = str_replace("'", ' ', $row['name']);
					$m_name=clean($m_name);
					echo "merchant_tags.push('" . $m_name . "');\n";
					// echo "console.log('" . $row['name'] . "');\n";
				}
				
			?>  
			$( "#transfer_name" ).autocomplete({
				source: merchant_tags
			}); 
		$(this).css("background-color", "red");
		$(this).css("color", "white");
		 
		$('.user_info').hide();
		$('#transfer_to').val();
		$('#transfer_name_label').show();
		var checkbox_check=$('#merchant_select_checkbox').is(':checked'); 
		if(checkbox_check)
		{
		 // $("#merchant_select_checkbox"). prop("checked", false);
		}
		else
		{
		 // $("#merchant_select_checkbox"). prop("checked",true);	
		}     
		 $("#qr_select_checkbox"). prop("checked",false);
		 $("#cashout"). prop("checked",false);
	});
	$("#transfer_name").focusout(function(){  
		// alert(3);
		var transfer_name=$('#transfer_name').val();
		if(transfer_name)
		{
		$.ajax({
					 url:"functions.php",
					 type:"post",
					 data:{transfer_name:transfer_name,method:"userdetailbyname"},
					 dataType:'json',
					 success:function(result){
						var data = JSON.parse(JSON.stringify(result));
						if(data.status==true)
						{
						    var user_mobile=data.data.mobile_number;
							// alert(user_mobile);
							$('#transfer_to').val(user_mobile);
							$("#transfer_to").prop("readonly", true);
							var number = $('#transfer_to').val();
							// alert(number.length);
							// if(number.length >= 9 && number.length <= 14){
							if(number.length >= 9 && number.length <= 12 && (number[0] == 1 || number[0]==6 || number[0] == 0)){
								$('.error-block-for-mobile').hide();   
								$.ajax({  
											url :'functions.php',
											type:'POST',
											dataType : 'json',
											data:{mobile_number:number,method:"userdetail"},   
											success:function(response){
													var data = JSON.parse(JSON.stringify(response));
													if(data.status==true)
													{
														var both_user=data.both_user;
														$('#both_user_role').val(both_user);
														// alert(both_user);
														if(both_user=="y")
														{
														
														   $('.user_info').hide();
															$('.both_user').show();
														}
														else
														{
															 $('.user_name').show();
															$('.both_user').hide();
															var user_name=data.data.name;
															if(user_name)
															{
																$('.intro_user').hide();
																$('.user_info').show();
																$('.user_mobile').html(user_name);
															}
															else
															{
																$('.intro_user').hide();
																$('.user_info').hide();
															}	
														}
														
													}
													else
													{
														$('.user_info').hide();
														$('.intro_user').show();
													}	
											}		  
										});
							
							}
							else
							{ 
								$('.error-block-for-mobile').html("Wrong mobile no. entered");
								$('.user_info').hide();
								$('.error-block-for-mobile').show();
							}
						}
						else
						{		 
							alert('Invalid  Merchant Name');
						}
						
					}   
			});  
		}
		else
		{
			$('#transfer_name').focus();
		}
	});
	$('.qr_select').click(function(e) {
		$('#transfer_name_label').hide();
		$('.merchant_select').css("background-color", "#51D2B7");
		$('.merchant_select').css("color", "#555");
		$('.qr_select').css("background-color", "#51D2B7");
		$('.qr_select').css("color", "#555");
		$(this).css("background-color", "red");
		$(this).css("color", "white");
		 $("#merchant_select_checkbox"). prop("checked", false);
		 $("#qr_select_checkbox"). prop("checked",true);
		  $('#qr_scan_model').modal('show');	
		  $('#fund_wallet_input_modal').modal('hide');	
	});
	$('.logout').click(function(e) {
		 e.preventDefault();
		var logout_type = $(this).attr('type');
		// alert(logout_type);   
		var local_id=localStorage.getItem("login_live_id");
		var data = {logout_type:logout_type,user_id:local_id};
		$.ajax({
					 url:"logout.php",
					 type:"post",
					 data:data,
					 dataType:'json',
					 success:function(result){
						var data = JSON.parse(JSON.stringify(result));
						if(data.status==true)
						{
						    localStorage.clear();
							localStorage.removeItem("login_live_id");
							localStorage.removeItem("login_live_role_id");   
							window.location = "login.php"
						}
						else
						{		 alert('Failed to logout');	}
						
					}
			});
	});  
	$('#trasfer_as').on('change', function() {
		// alert( this.value );
		var number = $('#transfer_to').val();
		var user_selected_role=this.value;
		if(user_selected_role && user_selected_role!='-1')
		{ 
			$('.error-block-for-trasfer-as').hide();
			$.ajax({  
						url :'functions.php',
						type:'POST',
						dataType : 'json',
						data:{mobile_number:number,method:"userselectedinfo",user_selected_role:user_selected_role},   
						success:function(response){
								var data = JSON.parse(JSON.stringify(response));
								if(data.status==true)
								{
									
									 $('.user_name').show();
										// $('.both_user').hide();
									  var user_name=data.data.name;
										if(user_name)
										{
											$('.intro_user').hide();
											$('.user_info').show();
											$('.user_mobile').html(user_name);
										}
										else
										{
											$('.intro_user').hide();
											$('.user_info').hide();
										}	
								}
																
						}		  
					});
		}
	});
	$("#transfer_to").focusout(function(){
		$('#transfer_name_label').hide();
		var number = $('#transfer_to').val();
		// alert(number.length);
		// if(number.length >= 9 && number.length <= 14){
		if(number.length >= 9 && number.length <= 12 && (number[0] == 1 || number[0]==6 || number[0]==0)){
			$('.error-block-for-mobile').hide();   
			$.ajax({  
						url :'functions.php',
						type:'POST',
						dataType : 'json',
						data:{mobile_number:number,method:"userdetail"},   
						success:function(response){
								var data = JSON.parse(JSON.stringify(response));
								if(data.status==true)
								{
									var both_user=data.both_user;
									$('#both_user_role').val(both_user);
									// alert(both_user);
									if(both_user=="y")
									{
									
									   $('.user_info').hide();
										$('.both_user').show();
									}
									else
									{
										 $('.user_name').show();
										$('.both_user').hide();
										var user_name=data.data.name;
										if(user_name)
										{
											$('.intro_user').hide();
											$('.user_info').show();
											$('.user_mobile').html(user_name);
										}
										else
										{
											$('.intro_user').hide();
											$('.user_info').hide();
										}	
									}
									
								}
								else
								{
									$('.user_info').hide();
									$('.intro_user').show();
								}	
						}		  
					});
		
		}
		else
		{ 
			$('.error-block-for-mobile').html("Wrong mobile no. entered");
			$('.user_info').hide();
			$('.error-block-for-mobile').show();
		}
    });
	$('.final_done').click(function (){
		location.reload(true);
	});
	$(".Logoutpop").on('click', function(event){
		  // alert(3);
       $('#LoginModel').modal('show');
	});
});
</script>   
 <!--a href="https://api.whatsapp.com/send?phone=60123945670" target="_blank"><img src ="images/iconfinder_support_416400.png" style="width:75px;height:75px;position: fixed;left:15px;bottom: 70px;z-index:999;"></a!-->
 <a href="https://chat.whatsapp.com/FdbA1lt6YQVBNDeXuY7uWd" target="_blank"><img src ="images/iconfinder_support_416400.png" style="width:75px;height:75px;position: fixed;left:15px;bottom: 70px;z-index:999;"></a>
