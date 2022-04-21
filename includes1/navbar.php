<?php
if(isset($_GET['language'])){
	$_SESSION["langfile"] = $_GET['language'];
} 
if (empty($_SESSION["langfile"])) { $_SESSION["langfile"] = "english"; }
    require_once ("languages/".$_SESSION["langfile"].".php");
/*else {
    $st_phone = substr($_SESSION['mobile'], 0, 2);
    if($st_phone == "60"){
        $_SESSION["langfile"] = "malaysian";
    } else if($st_phone == "86"){
         $_SESSION["langfile"] = "chinese";
    } else {
        $_SESSION["langfile"] = "english";
    }
    require_once ("languages/".$_SESSION["langfile"].".php");
}*/


$profile_data = isset($_SESSION['login']) ? mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$_SESSION['login']."'")) : '';
// print_R($profile_data);
$last_login=$profile_data['last_login'];
$u_role_id=$profile_data['user_roles'];
$date = date('Y-m-d H:i:s');
if($last_login)
$start_dt=date("Y-m-d H:i:s", $last_login);
else
$start_dt = $date;	
$end_dt = $date;
if($u_role_id!='1')
{
  $sql1 = "
	SELECT *
    FROM order_list
    left join users on order_list.user_id = users.id
    WHERE created_on >= '$start_dt' AND created_on <= '$end_dt' AND merchant_id = '$merchant' and status in(0,2)";

$result1 = mysqli_query($conn, $sql1);
$pending_count=mysqli_num_rows($result1);
}
else
{
	$pending_count=0;
}
?>

<?php
if($_GET['force_refresh'] == 'yes'){
	header('Clear-Site-Data: "cache", "cookies", "storage", "executionContexts"');
	//$url="https://www.koofamilies.com/";
	//header("Location:$url");
}
?>


<style>
	/*loader Css */
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
		background-image: url("https://www.koofamilies.com/loader.gif");
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
<?php 
$page_script = $_SERVER['SCRIPT_URL'];
$page_SCRIPT_NAME = $_SERVER['SCRIPT_NAME'];

//if($page_script != '/orderlist.php'){?>
<div class="page_loader" style="display:none!important;"><span id="load"></span></div>
<?php// }?>
<nav class="navbar">
    <!--<audio id="myAudio" style="display:none;">
      <source src="/notification.mp3" type="audio/mpeg">
    </audio>-->
    <input type="hidden" class="user_id" value="<?php echo $_SESSION['login'];?>" >
<!-- Logo Area -->
<div class="navbar-header">
<a href="<?php echo $site_url; ?>/index.php?vs=<?=md5(rand()) ?>" class="navbar-brand">
    <p class="logo-expand">Koo Families</p>
    <p class="logo-collapse">Koo</p>
<!-- <p>OSCAR</p> -->
</a>
</div>
<!-- /.navbar-header -->
<!-- Left Menu & Sidebar Toggle -->
<ul class="nav navbar-nav">
<li class="sidebar-toggle"><a href="javascript:void(0)" class="ripple"><i class="fa fa-bars" aria-hidden="true"></i></a>
</li>
</ul>
<!-- /.navbar-left -->
<div class="spacer"></div>



<!-- User Image with Dropdown -->
<ul class="nav navbar-nav">
<?php if(isset($profile_data['user_roles']) && $profile_data['user_roles'] ==  '2'){?>

         <!--li class="home_screen">
       <a href="pos.php" style="cursor:pointer; font-size: 18px;height: 74px;margin-top: 7%;color: white;margin-left: -14%;background:#4387fd;border: none;" class="btn btn-primary"><?php echo $language['order']; ?></a>
		</li!-->
    <?php }?>
	<?php if(isset($profile_data['user_roles']) && $profile_data['user_roles'] ==  '5'){?>

         <!--li class="home_screen">
       <a href="pos.php" style="cursor:pointer; font-size: 18px;height: 74px;margin-top: 7%;color: white;margin-left: -14%;background:#4387fd;border: none;" class="btn btn-primary"><?php echo $language['order']; ?></a>
		</li!-->
    <?php }?>
	<!--new-->
<?php 
//get current page URL
$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";  
$CurPageURL = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
if($_SERVER['QUERY_STRING']!= ''){
	$frce_text = "&force_refresh=yes";
}else{
	$frce_text = "?force_refresh=yes";
}
 ?>
 
	<?php  if( isset($profile_data['user_roles']) && $profile_data['user_roles'] !=  '') { ?>
	<?php if($_SESSION['login']){ ?>
	<!--li class="btn btn-primary" style="background:#51d2b7;border:none;height: 38%;margin-top:30px;margin-right: 18px;">	 
    	<div class="home_screen">
          
    		<span  data-toggle="modal" data-target="#qrmodel">
				<i  style="font-size:20px;" class="fa fa-qrcode" aria-hidden="true"></i>
    		</span>
    
    	</div>
    </li!-->
	
	<?php  }?>
<li style="color: white;font-size: 24px;border-radius: 14px;">
			<a href="<?php //echo $CurPageURL.$frce_text; ?>"  title="Refresh" class="myrefresh"><i class="fa fa-refresh" style="padding-right:8px"></i></a>
		</li>

<li class="home_screen">	 
    	<div class="home_screen">
            <input type="hidden" class="sender_id" value="<?php echo $_SESSION['login'];?>">
    		<a href="https://koofamilies.com" class="fa-stack fa-1x unread" style="cursor:pointer; margin-top: 30px; margin-right:5px;">
				<i class="fa fa-home" aria-hidden="true"></i>
    		</a>
    
    	</div>
    </li>
    <?php } else { ?>
	


		<li style="color: white;font-size: 24px;border-radius: 14px;">
			<a href="<?php //echo $CurPageURL.$frce_text; ?>"  title="Refresh" class="myrefresh"><i class="fa fa-refresh" style="padding-right:8px"></i></a>
		</li>
		
		<li class="home_screen">	 
    	<div class="home_screen">
    
    		<a href="https://koofamilies.com/login.php?redirect=<?php echo urlencode($CurPageURL);?>" class="fa-stack fa-1x unread" style="cursor:pointer; margin-top: 30px; margin-right:5px;">
				<i class="fa fa-sign-in"></i>
    		</a>
    
    	</div>
    </li>
    <?php } ?>
    <!--end new--->
	
    <!--li class="dropdown">	
    	<div class="stacked-icons">
    		<a class="fa-stack fa-1x unread" style="cursor:pointer; margin-top: 30px; margin-right:5px;">
    			<i class="fa fa-comment fa-stack-2x"></i>
    			<strong class="fa-stack-1x fa-stack-text fa-inverse unread_num" style="color: red;"></strong>
    		</a>
    
    	</div>
    </li!-->
<li class="dropdown" style="width: 47px;">
  <a href="#" class="dropdown-toggle" style="cursor:pointer; font-size: 24px; padding:0" data-toggle="dropdown" >
  <i class="fa fa-language" style="font-size:38px;" aria-hidden="true"></i></a>
  <?php 
  $g_url='';
  if(isset($_GET)){
       // print_R($_GET);
	   foreach($_GET as $key => $value){
		   if($key!="language")
		   $g_url.="&".$key."=".$value;
	   }
  }
   if($_GET['shopname']){
	  $shop_link = $_GET['shopname']."/".$_GET['ms'];
  }
  
	  ?>
  <?php ?>
  <?php if($_GET['shopname']){?>
  <ul class="dropdown-menu" style="padding:10px 15px;">
	<a href="<?php echo $site_url; ?>/merchant/english/<?php echo $shop_link; ?>" style="display:block; color:#000; font-size: 18px; margin-bottom: 10px;">English</a>
	<a href="<?php echo $site_url; ?>/merchant/chinese/<?php echo $shop_link; ?>" style="display:block; color:#000; font-size: 18px; margin-bottom: 10px;">Chinese</a>
	<a href="<?php echo $site_url; ?>/merchant/malaysian/<?php echo $shop_link; ?>" style="display:block; color:#000; font-size: 18px; margin-bottom: 10px;">Malay</a>
  </ul>
  <?php }else if($_GET['sid']){?>
  <ul class="dropdown-menu" style="padding:10px 15px;">
	<a href="<?php echo $site_url; ?>/view_merchant.php?language=english<?php echo $g_url; ?>" style="display:block; color:#000; font-size: 18px; margin-bottom: 10px;">English</a>
	<a href="<?php echo $site_url; ?>/view_merchant.php?language=chinese<?php echo $g_url; ?>" style="display:block; color:#000; font-size: 18px; margin-bottom: 10px;">Chinese</a>
	<a href="<?php echo $site_url; ?>/view_merchant.php?language=malaysian<?php echo $g_url; ?>" style="display:block; color:#000; font-size: 18px; margin-bottom: 10px;">Malay</a>
  </ul>
  <?php }else{?>
  <ul class="dropdown-menu" style="padding:10px 15px;">
	<a href="<?php echo $site_url; ?>/?language=english<?php echo $g_url; ?>" style="display:block; color:#000; font-size: 18px; margin-bottom: 10px;">English</a>
	<a href="<?php echo $site_url; ?>/?language=chinese<?php echo $g_url; ?>" style="display:block; color:#000; font-size: 18px; margin-bottom: 10px;">Chinese</a>
	<a href="<?php echo $site_url; ?>/?language=malaysian<?php echo $g_url; ?>" style="display:block; color:#000; font-size: 18px; margin-bottom: 10px;">Malay</a>
  </ul>
  <?php } ?>
</li>
<li class="dropdown">
  <a class="dropdown-toggle" style="cursor:pointer" data-toggle="dropdown"><img src="<?php echo $site_url; ?>/images/wallet.png" style="width:40px"></a>
  <?php
  // $balance = isset($_SESSION['login']) ? mysqli_fetch_assoc(mysqli_query($conn, "SELECT special_coin_name,mobile_number,id,user_roles,setup_shop,balance_usd,balance_inr,balance_myr FROM users WHERE id='".$_SESSION['login']."'")) : '';
  $balance =$profile_data;
	$a_user_id=$balance['id'];
 ?>
  <ul class="dropdown-menu" style="padding:10px 10px;margin-left: -95px;">
	<table class="table table-striped">
	  <?php if( $balance['balance_myr']) {?>
	    <tr><th>MYR</th>
		<td><a href="transaction_history.php?coin_type=MYR">
		<?php if( $balance['balance_myr']) { echo number_format($balance['balance_myr'],2);} else{ echo "0.00";} ?></a></td>
		</tr>
		<?php } if( $balance['balance_usd']) { ?>
		<tr><th><?php echo $balance['special_coin_name']; ?></th>
		<td><a href="transaction_history.php?coin_type=<?php if($_SESSION['login_user_role']==2){ echo $balance['special_coin_name'];}else { echo "CF";} ?>">
		<?php if($balance['balance_usd']) { echo number_format($balance['balance_usd'],2);}else{ echo "0.00";} ?></a></td>
		   
		</tr>
		<?php  } if( $balance['balance_inr']) {?>
		<tr><th>Koo Coin</th>
		<td><a href="transaction_history.php?coin_type=INR">
		<?php if( $balance['balance_inr'] ) { echo number_format($balance['balance_inr'],2);}else{ echo "0.00";} ?></a></td>
		
		</tr>
		<?php  }
						    // $sq="select special_coin_wallet.*,m.special_coin_name from special_coin_wallet  inner join users as m on m.id=special_coin_wallet.merchant_id where user_id='$a_user_id'";
						$sq="select special_coin_wallet.*,m.special_coin_name from special_coin_wallet  inner join users as m on

							  m.id=special_coin_wallet.merchant_id where user_id='$a_user_id' and special_coin_wallet.coin_balance>0 
							  and special_coin_wallet.coin_active='y'";
						$sub_rows = mysqli_query($conn,$sq);
						 if(mysqli_num_rows($sub_rows)>0){
							while ($swallet=mysqli_fetch_assoc($sub_rows)){
					?>
					
					<tr><th><?php echo $swallet['special_coin_name'];?></th>
					<td><a href="<?php echo $site_url; ?>/structure_merchant.php?merchant_id=<?php echo $swallet['merchant_id'];?>">
					<?php if($swallet['coin_balance']){echo number_format($swallet['coin_balance'],2);}else{ echo "0.00";} ?></a></td></tr>
						
							<?php } } ?>
	</table>
  </ul>
</li>
<li class="dropdown"><a href="javascript:void(0);" class="dropdown-toggle ripple" data-toggle="dropdown">
<span class="thumb-sm"><img src="<?php echo $site_url; ?>/Dashboard_files/user-image.png" class="rounded-circle" alt="" style="width: 40px;"> </span></a>
<div class="dropdown-menu dropdown-left dropdown-card-dark text-inverse" style="padding:8px">
	<!-- logout menu -->
	<?php   
	$setup_shop=$balance['setup_shop'];
	$user_roles=$balance['user_roles'];
	  if(($setup_shop=="y") && ($user_roles=="2")){ ?>   
	<a class="Logoutpop"><?php echo $language['log_out']; ?></a>
	<?php } else { ?>
	  	<a href="#"  type="normal_logout" class="logout"><?php echo $language['log_out']; ?>  </a>   
	<?php } ?>
	<!-- // logout menu -->
</div>
</li>
</ul>
<!-- /.navbar-right -->
</nav>
<?php $a_mboile_no=$balance['mobile_number']; ?>
<div id="qrmodel" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="text_qrcode"><?php echo $language['my_qr_code']; ?></h3>
				<button type="button" class="close"data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body" style="text-align: left;">
				<div class="credentials-container">
					<img src="<?php echo $site_url; ?>/qrcode/qrcode.php?text=<?php echo $a_mboile_no; ?>" style="width:100%" class="text_qrcode">
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="LoginModel" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><?php echo $language['log_out']; ?></h4>
										
										
                                    </div>
                                    <form id ="closeshop">
                                        <div class="modal-body closeshop" style="padding-bottom:0px;max-height:50vh;overflow-x: auto;">
										  
												<span href="<?php echo $site_url; ?>/logout.php?s=shop_close" type="shop_close" class="btn btn-primary logout">Shop Close with Logout  </span>  	 
												&nbsp;&nbsp;&nbsp;&nbsp;
												<?php
												  if($pending_count>0)
													{
														$url="#";
														
													}
													else
													{
														$url="logout.php?s=shift_close";
													}   
																?>
												<!--a  href="<?php echo $url; ?>" class="btn btn-primary  <?php if($pending_count>0){ echo "print_shift_stop";} ?>" ">Logout & Shift Close </a>  
												<br/>
												<br/!-->
												<?php if($_SESSION['login']!=670){ ?>
													<!--a href="logout.php" class="btn btn-primary"><?php echo $language['log_out']; ?> </a!-->   
													<span   type="normal_logout" class="btn btn-primary logout"><?php echo $language['log_out']; ?>  </span>   													
												<?php } ?>
											
										
                                        </div>    
                                        <div style="margin: 10px 0 10px 34%;"  class="modal-footer product_button pop_model">
										    
                                         
                                        </div>
										<br/>
                                    </form>
                                </div>
                            </div>
                        </div>
<style>

i.fa.fa-home {
    font-size: 30px;
    margin-left: -15px;
    color:#09caab;
}
i.fa.fa-sign-in {
    font-size: 26px;
    margin-left: -10px;;
    color:#09caab;
    margin-top: 5px;
}
@media only screen and (max-width:400px) {
	
.navbar-nav > li
{
	<?php if($user_roles=='2'){ ?>
	margin:-5px;
	<?php } else { ?>
	margin:2px;
	<?php } ?>  
}
}
@media only screen and (max-width: 450px) and (min-width: 300px)  {
li.home_screen {
	<?php if($user_roles=='2'){ ?>
	margin-right:-2px;
	<?php } else { ?>
	margin-right: -15px;
	<?php } ?>
    
}
i.fa.fa-bars {
    font-size: 19px;
}

}

</style>

 <!--- Force Refresh -->
<?php //if($_GET['mode'] =='dev'){
if($page_SCRIPT_NAME != '/dashboard.php'){?>
<script>
    $(document).ready(function(){
		$(".myrefresh").click(function(){
			$("#newuser_model").modal('hide');
			$("#myModal_forcerefresh").modal({
			show: false,
			backdrop: 'static'
			});
			$("#myModal_forcerefresh").modal('show');
			$("#myModal_language").modal('hide'); 
			$("#myModal").modal('hide');
			return false;
		});
		
    });
</script>
<?php }
//}?>
<div id="myModal_forcerefresh" class="modal fade" style="top:90px">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cache/Cookies </h5>
                <!--<button type="button" class="close" data-dismiss="modal">&times;</button>-->
            </div>
            <div class="modal-body">
				
				<h3>
				<?php if($_SESSION["langfile"] == 'chinese'){?>
				由于系统升级，我们这里将协助你把旧记录清楚。如果页面没有更新, <a href="<?php echo $CurPageURL;?>" target="_blank" class="" style="color:red;text-decoration:underline">请按这里</a>.
<?php }else{ ?>
				Due to upgrade of system, we need you to clear page history to avoid order error. if this page has no respond, please click <a href="<?php echo $CurPageURL;?>" target="_blank" class="" style="color:red;text-decoration:underline">HERE</a>
				<?php }?>
				</h3>
			 </div>
			<div class="modal-footer">
				<a href="#" class=" force_no btn btn-primary btn_language" data-dismiss="modal" >NO</a>
				<a href="<?php echo $CurPageURL.$frce_text; ?>" class="force_yes btn btn-success btn_language" >YES</a>
           
			</div>
        </div>
    </div>
</div>

<!-- END --->