<?php 
include("config.php");  

    $product = "";
	$password_Created='';
	if(!empty($_GET['l'])){
		$login_token=$_GET['l'];
		$r_merchant_id=$_GET['merchant_id'];
		$user_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE login_token='".$login_token."'"));
		$m_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id,special_coin_name FROM users WHERE id='".$r_merchant_id."'"));
		// print_R($user_data);
		// die;
		if($user_data)
		{
			$user_id=$user_data['id'];
			$_SESSION['login'] = $user_id;
   			$_SESSION['user_id'] = $user_id;
   			$_SESSION['setup_shop'] = $user_data['setup_shop'];
   			$_SESSION['referral_id'] = $user_data['referral_id'];
   			$_SESSION['name'] = $user_data['name'];
			$_SESSION['login_user_role'] = $user_data['user_role'];
   			$_SESSION['mobile'] = $user_data['mobile_number'];
			mysqli_query($conn, "UPDATE users SET otp_verified='y',isLocked='0' WHERE id='$user_id'");
			if($user_data['password']=='')
			$password_Created="n";
		}
		 $t=$_GET['t'];
		 $s_coin_ref=$m_data['special_coin_name'];
	}
	if(!empty($_POST['countrycode']))  
	{
        $mobil_num =  $_POST['countrycode'].''. $_POST['merchant'];
        $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE mobile_number='$mobil_num' and user_roles='2'"));	
        $_SESSION['merchant_id'] = $product['id'];
        
        $merchant_name = $product['name'];
        $_SESSION['invitation_id'] = $product['referral_id']; 
        $_SESSION['address_person'] = $product['address'] ;
        $_SESSION['latitude'] = $product['latitude'] ; 
        $_SESSION['longitude'] = $product['longitude'] ; 
        $_SESSION['IsVIP'] = $product['IsVIP'] ;
    }
     else if(!empty($_POST['merchant_id']))
    {
		$m_u_id = $_POST['merchant_id'];
		
		
		$product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE name='$m_u_id' and user_roles='2'"));	
		$merchant_name = $product['name'];
		$merchant_mobile_number = $product['mobile_number'];  
		// if($merchant_mobile_number=="60127771833")
		// $_SESSION["langfile"] = "chinese";
        $_SESSION['merchant_id'] =  $product['id'];
		$_SESSION['invitation_id'] = $product['referral_id'];
		$_SESSION['address_person'] = $product['address_person'] ;
		$_SESSION['latitude'] = $product['latitude'] ; 
        $_SESSION['longitude'] = $product['longitude'] ;
        $_SESSION['IsVIP'] = $product['IsVIP'] ;
	} 
	else if(!empty($_POST['merchant_address'])){
	    $m_address = $_POST['merchant_address'];
	    $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE merchant_url='$m_address' and user_roles='2'"));
	    $merchant_name = $product['name'];
	    $_SESSION['invitation_id'] = $product['referral_id'];
		$_SESSION['address_person'] = $product['address_person'] ;
		$_SESSION['latitude'] = $product['latitude'] ; 
        $_SESSION['longitude'] = $product['longitude'] ;
         $_SESSION['IsVIP'] = $product['IsVIP'] ;
	}
	else if(!empty($_GET['favorite_id'])){
        $id = $_GET['favorite_id'];
        $_SESSION['merchant_id'] = $id;
        $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='$id'"));
        $merchant_name = $product['name'];
        $_SESSION['invitation_id'] = $product['referral_id'];
        $_SESSION['address_person'] = $product['address_person'] ;
        $_SESSION['latitude'] = $product['latitude'] ; 
        $_SESSION['longitude'] = $product['longitude'] ;
         $_SESSION['IsVIP'] = $product['IsVIP'] ;
         
    } else if(!empty($_GET['merchant_id'])){
        $id = $_GET['merchant_id'];
        $_SESSION['merchant_id'] = $id;
        $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='$id'"));
        $merchant_name = $product['name'];
        $_SESSION['invitation_id'] = $product['referral_id'];
        $_SESSION['address_person'] = $product['address_person'] ;
        $_SESSION['latitude'] = $product['latitude'] ; 
        $_SESSION['longitude'] = $product['longitude'] ;
         $_SESSION['IsVIP'] = $product['IsVIP'] ;
    }
    else if(!empty($_GET['sid'])){
        $sid = $_GET['sid'];
        $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE mobile_number='$sid' and user_roles='2'"));
        $merchant_name = $product['name'];
        $_SESSION['invitation_id'] = $product['referral_id'];
        $_SESSION['merchant_id'] = $product['id'];
        $_SESSION['address_person'] = $product['address_person'] ;
        $_SESSION['latitude'] = $product['latitude'] ; 
        $_SESSION['longitude'] = $product['longitude'] ;
        $_SESSION['IsVIP'] = $product['IsVIP'] ;
         
    } else if(isset($_SESSION['merchant_id'])){
        $m_u_id = $_SESSION['merchant_id'];
        $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='$m_u_id' and user_roles='2'"));	
		$merchant_name = $product['name'];
		$_SESSION['invitation_id'] = $product['referral_id'];
        $_SESSION['address_person'] = $product['address_person'] ;
        $_SESSION['latitude'] = $product['latitude'] ; 
        $_SESSION['longitude'] = $product['longitude'] ;
        $_SESSION['IsVIP'] = $product['IsVIP'] ;
    }
    //~ if(!isset($_SESSION['merchant_id'])){
		//~ header("location:merchant_find.php");
	//~ }    
    /*if($product == NULL){
        header("location:merchant_find.php?error_type=1"); 
    }*/
     else {
		header("location:merchant_find.php");
	}   
    if(isset($_SESSION['login']))
	{
		 $wallet_name=$product['special_coin_name'];
		 $merchant_id=$product['id'];
		 $user_id=$_SESSION['login'];
		 $walletcheck = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM special_coin_wallet WHERE  coin_active='y' and coin_balance>=0 and user_id='$user_id' and merchant_id ='".$merchant_id."'"));
		 $u_bal=$walletcheck['coin_balance'];
	}		
    if($product == NULL){
        header("location:merchant_find.php?error_type=1"); 
    } 
    $nature_array = array(
        "Foods and Beverage, such as restaurants, healthy foods, franchise, etc",
        "Motor Vehicle, such as car wash, repair, towing, etc",
        "Hardware, such as household, building, renovation to end users",
        "Grocery Shop such as bread, fish, etc retails shops",
        "Clothes such as T-shirt, Pants, Bra, socks,etc",
        "Business to Business (B2B) including all kinds of businesses"
    );
    $nature_image = array(
        "foods.jpg",
        "car.jpg",
        "household.jpg",
        "grocery.jpg",
        "clothes.jpg",
        "b2b.jpg"
    );
	$default_lang = $product['default_lang'];  
	if($default_lang==1)
	{
		$langfile="english";
	} else if($default_lang==2)
	{
		$langfile="chinese";
	} else if($default_lang==3)
	{
		$langfile="malaysian";
	}
	$_SESSION["langfile"] =$langfile;

?>
            

<!DOCTYPE html>
<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">

<head>
    <?php include("includes1/v_header.php"); ?>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://scripts.sirv.com/sirv.js" defer></script> 
	 <meta name="theme-color" content="#317EFB"/>
	</head>
	<body class="header-light sidebar-dark sidebar-expand pace-done">

    <div class="pace  pace-inactive">
        <div class="pace-progress" data-progress-text="100%" data-progress="99" style="transform: translate3d(100%, 0px, 0px);">
            <div class="pace-progress-inner"></div>
        </div>
        <div class="pace-activity"></div>
    </div>

    <div id="wrapper" class="wrapper">
        <!-- HEADER & TOP NAVIGATION -->
        <?php include("includes1/navbar.php"); ?>
        <!-- /.navbar -->
        <div class="content-wrapper">
            <!-- SIDEBAR -->
            <?php include("includes1/sidebar.php"); ?>
            <!-- /.site-sidebar -->
            <main class="main-wrapper clearfix" style="min-height: 522px;">
                <div class="row" id="main-content" style="padding-top:25px">
					
				
            <?php
			$merchant_id=$product['id'];
			$query="select unrecoginize_coin.*,users.name as merchant_name,users.mobile_number,users.special_coin_name from unrecoginize_coin inner join users on users.id=unrecoginize_coin.merchant_id where unrecoginize_coin.user_id='$merchant_id' and status=1  order by unrecoginize_coin.id desc limit 0,50";
	        $u_query = mysqli_query($conn,$query);
			$totalpartner=mysqli_num_rows($u_query);
			// who accept the c&m coin_active
			if($product['special_coin_name'])
			{
				$puery="select unrecoginize_coin.*,users.name as merchant_name,users.special_coin_name from unrecoginize_coin inner join users on users.id=unrecoginize_coin.merchant_id where unrecoginize_coin.merchant_id='$merchant_id' and status=1 order by unrecoginize_coin.id desc";
				$p_query = mysqli_query($conn,$puery);
				$ppartner=mysqli_num_rows($p_query);
			}
			
            if($_SESSION['IsVIP'] ==1){
             $mar_id = $product['id'];
            $about = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM about WHERE `userid` = $mar_id"));   
            ?>
            
            <div class="box-right">
			
			<?php 
			$merchant_detail = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$mar_id."'"));
			$sql_transaction = "SELECT COUNT(id) ordered_num 
			FROM order_list
			WHERE user_id='".$_SESSION['login']."' and merchant_id = '".$mar_id."' AND STATUS='1'";
			$result_transaction = mysqli_fetch_assoc(mysqli_query($conn,$sql_transaction));
			$sql_favorite = "SELECT COUNT(id) favorite_num
			FROM favorities
			WHERE favorite_id = '".$mar_id."'";
			$result_favorite = mysqli_fetch_assoc(mysqli_query($conn,$sql_favorite));
				
			$business1 = "";
			$business2 = "";
			for($i = 0; $i < count($nature_array); $i++){
			if($merchant_detail['business1'] == $nature_array[$i])
			$business1 = $nature_image[$i];
			if($merchant_detail['business2'] == $nature_array[$i])
			$business2 = $nature_image[$i];
			}
			// $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT count(id) as pro_ct FROM products WHERE user_id ='".$mar_id."' and status=0"));	
			//$total_rows = mysqli_query($conn, "SELECT * FROM products WHERE user_id ='".$mar_id."' and status=0");
			//$categories = mysqli_query($conn, "SELECT DISTINCT(products.category) FROM products WHERE user_id ='".$mar_id."' and status=0");
			?>
			<?php if(isset($_SESSION['invitation_id']) && (!isset($_SESSION['login']))){?>
			<a class="col-md-2" href="signup.php?invitation_id=<?php echo $_SESSION['invitation_id'];?>">

			<img src="img/join-us.jpg" style="width: 100px;">
			</a>
			
			<?php }?>
				<a style="text-align:center;width:100%;margin-top:2%;" href="https://play.google.com/store/apps/details?id=com.app.koofamily" target="blank">
					<img style="max-width:140px;" src="google.png" alt=""></a>
				<a style="text-align:center;width:100%;margin-top:2%;" href="https://apps.apple.com/us/app/id1491595615?mt=8" target="blank">
                                <img style="max-width:140px;max-height:40px;" src="appstore.png" alt="">
                </a>
            <div class="title">
            <div class="title-left"> <img src="new/images/mail.png"> <div class="title-h">  <a href="#"> Merchant Name:<span>  <?php echo $merchant_name ?></span> </a> </div>  </div> 
            <div class="title-right"> 
			
			<div class="favorite_icon">
                                <?php if($count > 0) {?>
                                <i class="heart fa fa-heart"></i>
                                <?php } else {?>
                                    <i class="heart fa fa-heart-o"></i>
                                <?php }?>
                                <h4 class="starting-bracket white" style="display: inline-block;">(</h4>
                        	<?php if($business1 != ""){ ?>
                        	    <img style="margin-top:0px;" class="nature_image" src="/img/<?php echo $business1;?>">
                        	<?php }?>
                        	<?php if($business2 != ""){ ?>
                        	    <img style="margin-top:0px;" class="nature_image" src="/img/<?php echo $business2;?>">
                        	<?php }?>
                        	 
                        	<?php if($merchant_detail['account_type'] != ''){?>
							    <h4 class="transaction_num white kType"> <?php echo $merchant_detail['account_type'];?>, </h4>
							<?php }?>
                    	   <h4 class="transaction_num white" ><?php echo $result_transaction['ordered_num'];?>, </h4>
                    	    <h4 class="favorite_num white" ><?php echo $result_favorite['favorite_num'];?>)</h4>
                            </div>
			</div> 
            </div> 
            <div class="cont-area"> 
            <div class="head-title">Mykluang Coffee </div> 
            <div class="tabs">
            <div class="tab"> <a href="#" onclick="window.location.href = 'view_merchant.php';" > <img src="new/images/tab-01.png"> </a> </div>
            <div class="tab"> <a href="#" onclick="window.location.href = 'payment_menu.php';"> <img src="new/images/tab-02.png"> </a> </div>
            </div>
            <div class="tabs">
            <div class="tab"> <a href="#" onclick="window.location.href = 'rating_list.php';"> <img src="new/images/tab-03.png"> </a> </div>
            <div class="tab"> <a href="#" onclick="window.location.href = 'about_menu.php';"> <img src="new/images/tab-04.png"> </a> </div>
            </div>	
            <div class="tabs-02">
            <div class="tab-main">
			<!--a href="" onclick="window.location.href = 'location.php?address=<?php echo  $_SESSION['address_person'] ?>';"> <img src="new/images/tab-05.png"> </a!--> 
			<a href="<?php echo "http://maps.googleapis.com/maps/api/geocode/json?latlng=".$merchant_detail['latitude'].",".$merchant_detail['longitude']."&sensor=false"; ?>"> <img src="new/images/tab-05.png"> </a> 
		
		</div>   
            </div>
            </div>
            </div> 
			
            <?php
            }
            else{
            ?>
           	<div class="col-md-12 test_wel_not" id="test_wel_not">
					
    					<?php  $mar_id = $product['id'];
                                $about = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM about WHERE `userid` = $mar_id"));
								$merchant_detail = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$mar_id."'"));
								?>
<!--
                           <div class="heading">WELCOME NOTE</div>
-->
                           <div class="welccc_nottt"><?php echo $about['welcome_note']; ?></div>
<!--
                           <div class="logo_head">LOGO</div>
-->
                        <?php if(!empty($about['image'])){ ?>
                            <div class=""> <img data-src="<?php echo $image_cdn; ?>about_images/<?php echo $about['image'];?>?w=150" alt="<?php echo $merchant_detail['name'];?>" class="Sirv" ></div>
                        <?php }  else { ?>
    	                    <img src="https://upload.wikimedia.org/wikipedia/commons/a/ac/No_image_available.svg" class="img-responsive" style="max-height:150px;">
    	                <?php } ?>
							
					</div>
					
				   
					<div class="col-md-12" style="margin-bottom:10px; padding-right: 0px;">
						<?php 
						    // $merchant_detail = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$mar_id."'"));
							// print_R($merchant_detail);
							// die;
						    if( isset( $_SESSION['login'] ) ) {
                                $sql_transaction = "SELECT COUNT(id) ordered_num 
                							FROM order_list
                							WHERE user_id='".$_SESSION['login']."' and merchant_id = '".$mar_id."' AND STATUS='1'";						        
                				$result_transaction = mysqli_fetch_assoc(mysqli_query($conn,$sql_transaction));
						    } else {
						        $sql_transaction = '';
						        $result_transaction = '';
						    }
                    		
                    		$sql_favorite = "SELECT COUNT(id) favorite_num
                    						FROM favorities
                    						WHERE favorite_id = '".$mar_id."'";
                    		$result_favorite = mysqli_fetch_assoc(mysqli_query($conn,$sql_favorite));
                    												
                    	    $business1 = "";
                    	    $business2 = "";
                    	    for($i = 0; $i < count($nature_array); $i++){
                    	        if($merchant_detail['business1'] == $nature_array[$i])
                    	            $business1 = $nature_image[$i];
                    	        if($merchant_detail['business2'] == $nature_array[$i])
                    	            $business2 = $nature_image[$i];
                    	    }
                           // $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT count(id) as pro_ct FROM products WHERE user_id ='".$mar_id."' and status=0"));	
                          	//$total_rows = mysqli_query($conn, "SELECT * FROM products WHERE user_id ='".$mar_id."' and status=0");
                            //$categories = mysqli_query($conn, "SELECT DISTINCT(products.category) FROM products WHERE user_id ='".$mar_id."' and status=0");
						?>
						<?php if(isset($_SESSION['invitation_id']) && (!isset($_SESSION['login']))){?>
							<a class="col-md-2" href="signup.php?invitation_id=<?php echo $_SESSION['invitation_id'];?>">
							   
							    <img src="img/join-us.jpg" style="width: 100px;">
							</a>
							
                        <?php }?>
						<a style="text-align:center;width:100%;margin-top:2%;" href="https://play.google.com/store/apps/details?id=com.app.koofamily" target="blank">
					<img style="max-width:140px;" src="google.png" alt=""></a>
					<a style="text-align:center;width:100%;margin-top:2%;" href="https://apps.apple.com/us/app/id1491595615?mt=8" target="blank">
                                <img style="max-width:140px;max-height:40px;" src="appstore.png" alt="">
                            </a>
                        <div class="col-md-12 favorite" style="padding: 0px !important;">
                            <?php 
                            if (!empty($_SESSION['login'])){
                                $favorite = mysqli_query($conn, "SELECT * FROM favorities WHERE user_id = ".$_SESSION['login']." AND favorite_id = ".$mar_id."");
                                $count = mysqli_num_rows($favorite);
                                
                                 $about = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM favorities WHERE user_id = ".$_SESSION['login']." AND favorite_id = ".$mar_id.""));
                                 } ?>

                            <h4 class="favorite_name" style="display: inline-blick;">Name: <?php echo $merchant_name;?></h4>
                            <div style="clear:both;">
                             <h4 class="favorite_name" style="display: inline-blick;">
                                 <!--a href="javascript:jqcc.cometchat.launch({uid:'<?php echo $mar_id;?>'});">
								 Chat with <?php echo $merchant_name ?></a!-->
								 <?php if($merchant_detail['id']==6958 || $merchant_detail['id']==6956 || $merchant_detail['id']==7634) {  ?>
                      <a href="https://api.whatsapp.com/send?phone=60123115670" target="_blank">Chat with <?php echo $merchant_detail['name']; ?>
					   <?php } else { ?>
					    <a href="https://api.whatsapp.com/send?phone=<?php  echo $merchant_detail['mobile_number']?>" target="_blank">Chat with <?php echo $merchant_detail['name']; ?>
					   <?php } ?>  
								 <img src="images/whatapp.png" style="max-width:40px;"/>
								 </a>
								 </h4>
                            </div>
                            <?php  //print_r($_SESSION); ?>
                            <script type="text/javascript">
                            var chat_appid = '52013';
                            var chat_id = '<?php echo $_SESSION['login'] ;?>';
                            var chat_name = '<?php echo $_SESSION['name'];?>';
                            var chat_position = 'left';
                            
                            (function() {
                            var chat_css = document.createElement('link'); chat_css.rel = 'stylesheet'; chat_css.type = 'text/css'; chat_css.href = 'https://fast.cometondemand.net/'+chat_appid+'x_xchat.css';
                            document.getElementsByTagName("head")[0].appendChild(chat_css);
                            var chat_js = document.createElement('script'); chat_js.type = 'text/javascript'; chat_js.src = 'https://fast.cometondemand.net/'+chat_appid+'x_xchat.js'; var chat_script = document.getElementsByTagName('script')[0]; chat_script.parentNode.insertBefore(chat_js, chat_script);
                            })();
                            </script>

                            
                            <!--div class="hint" style="display:inline-block;">
                                <span style="display:inline-block;">
                                    <div class="tri_div test_fav1" style="left:22px;"></div>
                                    <div class="test_fav_1">Click here to add me as your "Favorite"</div>
                                </span>
                                <span style="display:inline-block;">
                                    <div class="tri_div trail2 test_mobile" style="left:121px;"></div>
                                   <div class="test_fav_2"> Number of transaction that <br>you have ordered with this merchant</div>
                                </span>
                                <span style="display:inline-block;">
                                    <div class="tri_div trail2 trail_test" style="left:15px;"></div>
                                    <div class="test_fav_3">Number of members who have added as  <br> Favorite
									</div>
                                </span>
                            </div!-->
                            <div class="favorite_icon">
                                <?php if(isset($count) && $count > 0) {?>
                                <i class="heart fa fa-heart" data-toggle="tooltip"></i>
                                <?php } else {?>
                                    <i class="heart fa fa-heart-o" data-toggle="tooltip"></i>
                                <?php }?>
                                <h4 class="starting-bracket white" style="display: inline-block;">(</h4>
                            	<?php if($business1 != ""){ ?>
                            	    <img style="margin-top:0px;" class="nature_image" src="/img/<?php echo $business1;?>">
                            	<?php }?>
                            	<?php if($business2 != ""){ ?>
                            	    <img style="margin-top:0px;" class="nature_image" src="/img/<?php echo $business2;?>">
                            	<?php }?>
                         
                            	<?php if($merchant_detail['account_type'] != ''){?>
    							    <h4 class="transaction_num white kType"><?php echo $merchant_detail['account_type'];?>,</h4>
    							<?php }?>
                        	      	 <h4 class="transaction_num white" data-toggle="tooltip"><?php if(isset($result_transaction['ordered_num'])) {echo $result_transaction['ordered_num'];}?>, </h4>
                        	    <h4 class="favorite_num white" data-toggle="tooltip"><?php echo $result_favorite['favorite_num'];?>)</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <?php 
                            if($product['id'] !=""){
								$_SESSION['mm_id'] = $product['id'];
							}
							else if($_SESSION['mm_id'] != "")
							{
								$_SESSION['mm_id'];
							}
							else
							{
							header("location:merchant_find.php");
							}
						?>
						<input type="hidden" id="login_user_id" value="<?php echo $user_data['id']; ?>"/>
						 <input  style="color:black;font-weight:bold;margin-top:0.5em;"  onclick="window.location.href = 'view_merchant.php?sid=<?php echo $merchant_detail['mobile_number'];?>&vs=<?=md5(rand()) ?>';" type="button" class="btn btn-block btn-primary col-md-5" name="Menu" value="<?php echo $language["menu"];?>" style="margin-top: 0.5em;"> 
						 <!--input onclick="window.location.href = 'payment_menu.php';" type="button" class="btn btn-block btn-primary col-md-5" name="Payment" value="<?php echo $language["payment"];?>"!--> 
						 <!--input onclick="window.location.href = 'rating_list.php';" type="button" class="btn btn-block btn-primary col-md-5" name="Rating" value="<?php echo $language["rating"] ?>"!-->
						 
						 <a style="color:black;font-weight:bold;"  class="btn btn-block btn-primary col-md-5"  href="dashboard.php"> <?php if($_SESSION['login'] && $u_bal>0){ echo strtoupper($wallet_name)." <span style='color:red;'>".number_format($u_bal,2)."</span>";} else{echo $language['wallet'];} ?></a> 
						
						 <input style="color:black;font-weight:bold;" onclick='transfer("<?php echo $_SESSION['login'];?>","<?php echo $merchant_detail['mobile_number']?>","<?php echo $merchant_detail['name']?>")' type="button" class="btn btn-block btn-primary col-md-5 trasfer" name="Wallet" value="<?php echo $language['tranfer']." ".$merchant_detail['mobile_number']; ?>">
						    
						<?php if($totalpartner>0){ ?>
						  <input  style="color:black;font-weight:bold;"  type="button" class="btn btn-block btn-primary col-md-5 partner"  value="<?php echo  $language['ASSOCIATED_PARTNER']; ?>">
						 <?php } ?>
						  <?php if($ppartner>0){ ?>
						  <input style="color:black;font-weight:bold;"  onclick="window.location.href = 'coinpartner.php?m_id=<?php echo $product['id'];?>';" type="button" class="btn btn-block btn-primary col-md-5"  value="<?php  echo $language['ACCEPT']." ".strtoupper($product['special_coin_name']); ?>">
						 <?php } ?>
                         <!--input onclick="window.location.href ='http://maps.google.com/maps?q=<?php echo  $merchant_detail["address_person"] ?>' type="button" class="btn btn-block btn-primary col-md-5" name="location" value="<?php echo "LOCATION";?>"!-->
						<?php if($merchant_detail['google_map']){ ?>
						<a  style="color:black;font-weight:bold;"  class="btn btn-block btn-primary col-md-5" target="_blank" href="http://maps.google.com/maps?q=<?php echo  $merchant_detail['google_map']; ?>"> <?php echo $language['location']; ?></a> 
						
						<?php  } else { ?>
						<a style="color:black;font-weight:bold;"  class="btn btn-block btn-primary col-md-5"  href="#"> <?php echo $language['location']; ?></a> 
						
						<?php }  ?>
						<input style="color:black;font-weight:bold;"  onclick="window.location.href = 'about_menu.php';" type="button" class="btn btn-block btn-primary col-md-5" name="About us" value="<?php echo $language["about_us"];?>">
						<input style="color:black;font-weight:bold;"  onclick="window.location.href = 'signup_referral.php?m_id=<?php echo $merchant_detail['id']; ?>';" type="button" class="btn btn-block btn-primary col-md-5" name="About us" value="<?php echo "JOIN MEMBER";?>">
					
					</div>
                <?php } ?>
                	<input type="hidden" class="merchant_id" value="<?php echo $mar_id;?>">
                    <input type="hidden" class="user_id" value="<?php echo $_SESSION['login'] ?>">
</div><!--content wrapper--->
 <div class="modal fade" id="PartnerModel" role="dialog">						
							<div class="modal-dialog">
							<!-- Modal content-->		
							<div class="modal-content" style="padding-bottom: 3%;">	   
							<div class="modal-header">	
							<button type="button" class="close" data-dismiss="modal">&times;</button>						
							<h4 class="modal-title">Associated  Partner</h4>	
							</div>					
								
							<div class="modal-body" style="padding-bottom:0px;padding-top:0px;">
								 <ul class="list-group">
								   <?php 
								   $wallet_merchant_id=$_SESSION['mm_id'];
								   $totalp=0;
								      while($prow = mysqli_fetch_assoc($u_query))
										{
											$partner_merchant_id=$prow['merchant_id'];
											   $totalcoin="SELECT sum(amount) as totalamount FROM tranfer WHERE MONTH(created_date) = MONTH(CURRENT_DATE()) AND YEAR(created_date) = YEAR(CURRENT_DATE()) and receiver_id='$wallet_merchant_id' and coin_merchant_id='$partner_merchant_id'";
										      
											$acceptedcoin = mysqli_fetch_assoc(mysqli_query($conn,$totalcoin));
											$totalamount=$acceptedcoin['totalamount'];
											
												$coin_max_limit=$prow['coin_max_limit'];
												$pending_limit=$prow['coin_max_limit']-$acceptedcoin['totalamount'];
												if($pending_limit>0)
												{
													$totalp++;
											?>
											  <li class="list-group-item" style="border-bottom: 1px solid #eaebeb;">
												<div class="row">
												<div class="col-md-8">
													<h5 style="font-weight:bold;"><?php echo $prow['merchant_name']; ?></h5>
													<p>Limit Left: <span style="color:red;"><?php echo $pending_limit; ?></span></p>
												</div>
												<div class="col-md-4">
												    <a class="btn btn-primary" style="color:white;max-height: 40px;" href="<?php echo $site_url."/structure_merchant.php?merchant_id=".$prow['merchant_id'];?>">About us</a>
												   </br>
												   <a class="btn btn-primary trasfer" onclick='transfer("<?php echo $_SESSION['login'];?>","<?php echo $prow['mobile_number']?>","<?php echo $prow['merchant_name']?>")' m_name="<?php echo $prow['merchant_name']; ?>" m_mobile="<?php echo $prow['mobile_number']; ?>"   style="margin-top:5%;color:white;">Transfer  Now</a>
												</div>
											   
												</div>
												  
											   
											  </li>
											<?php } } ?>
								
									</ul> 
									<?php if($totalp==0){ echo "<p>No Partner Now,Check Later</p>";} ?>
												
												
							</div>						
							</div>						
							</div>
						</div>
<div class=" modal fade" id="AlerModel" role="dialog" style="width:80%;min-height: 200px;text-align: center;margin:8%;">
        <div class="element-item modal-dialog modal-dialog-centered" style="position: absolute;top: 0;bottom: 0;left: 0;right: 0;display: grid;align-content: center;">
            <!-- Modal content-->
            <div class="element-item modal-content">
                <div class="element-item modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                            
                    
                              </div>   
                                    <p id="show_msg" style="font-size:22px;font-weight:bold;"><?php echo $language['cancel']; ?></p>
                    
                                </div>
                            </div>
    </div>   
	<div class="modal fade" id="PasswordModel" role="dialog" style="">  

   <div class="modal-dialog">

           <?php 



          



            ?>



            <!-- Modal content-->

            <div class="modal-content">

              

                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal">&times;</button>

					

                </div>

                 

                    <div class="modal-body" style="padding-bottom:0px;">

					    <div class="form-group register_password" style="<?php  if($password_created=="y"){ echo "display:none;"; }?>">

             

							  <div class="passwd_field">

								<label for="login_password">Please create your password</label>

								<input type="password" id="register_password" class="form-control" name="login_password"/>

											

					   <i  onclick="myFunction2()" id="eye_slash_2" class="fa fa-eye-slash" aria-hidden="true"></i>

					  <span id="eye_pass_2" onclick="myFunction2()" > <?php echo $language['show_password']; ?>  </span>

					   <small id="register_error" style="display: none;color:#e6614f;">

                 

                </small>

										

							  </div>

				</div>

						

                    </div>

                    <div class="modal-footer" style="padding-bottom:2px;">

                        <div class="row" style="margin: 0;">

			 

             

						  <div class="col" style="padding: 0;margin: 5px;">

							

							<input type="submit" class="btn btn-primary register_ajax"  name="register_ajax" value="Confirm" style="float: right;"/>

							 <small id="register_error" style="display: none;color:#e6614f;">

							 

							</small>

							

						  </div>

						         

					   

						</div>

						 
						

                    </div>

                  

            </div>

        </div>

  </div>
<!-- fund pass models !-->

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

<!-- end fund pass models !-->
<?php include("includes1/footer.php"); ?>
</div><!--wrapper--->

</body>

</html>
<style>
.col-md-5 {
    float: left;
    margin-right: 15px;
    margin-left: 20px;
}
.heading {
    font-size: 18px;
    font-weight: 600;
}
.logo_head {
    font-size: 18px;
    font-weight: 600;
}
main.main-wrapper.clearfix{    
    background: url(../images/background/menss.jpg); 
    background-size: cover; 
    background-repeat: no-repeat; 
}
h4.favorite_name {    color: white;    background: #0000003b;}
.hint span{
    background: #fff;
    color: black;
    font-size: 10px;
    position:relative;
    border-radius:0.8em;
    padding: 6px;
    
}
.hint span .tri_div{
    content: '';
    position:absolute;
    width: 0;
    height: 0;
    border-left: 8px solid transparent;
    border-right: 8px solid transparent;
    border-top: 24px solid #fff;
    top: 55px;
}
.hint span .tri_div.trail2{
    top: 55px;
    display:none;
}

@media only screen and (max-width: 400px) and (min-width: 300px){
	.test_fav_3 {
    width: 90px!important;
    margin-left: 0px!important; 
}

i.heart.fa.fa-heart {
	padding-right: 0px!important; 
}
.test_fav_2 {
    width: 85px!important;
     margin-left: 0px!important; 
}
.test_fav_1 {
    width: 73px!important;
    margin-left: 0px!important; 
}
.trail_test {
        top: 70px!important;
    left: 45px!important;
}
.tri_div.trail2.test_mobile {
    left: 80px!important;
    top: 71px!important;
    transform: rotate(-30deg);
    -ms-transform: rotate(-30deg);
    -webkit-transform: rotate(-50deg);
    border-top: 56px solid #fff!important;
}
.tri_div.trail2.trail_test {
    transform: rotate(35deg);
    -ms-transform: rotate(35deg);
    -webkit-transform: rotate(35deg);
    top: 64px!important;
    border-left: 8px solid transparent;
    border-right: 8px solid transparent;
    border-top: 35px solid #fff;
    left: 40px!important;
}
.tri_div.test_fav1 {
    left: 32px !important;
    border-top: 20px solid #fff!important;
}
}
@media only screen and (max-width: 650px) and (min-width: 600px){
.test_mobile {
         border-top: 42px solid #fff!important;
    left: 57px!important;
    top: 48px!important;
    transform: rotate(-30deg);
    -ms-transform: rotate(-30deg);
    -webkit-transform: rotate(-30deg);
}
.tri_div.trail2.trail_test {
    transform: rotate(35deg);
    -ms-transform: rotate(35deg);
    -webkit-transform: rotate(35deg);
    top: 64px!important;
    border-left: 8px solid transparent;
    border-right: 8px solid transparent;
	border-top: 38px solid #fff!important;
    left: 10px!important;
}
}
@media only screen and (max-width: 600px) and (min-width: 400px){
	.test_fav_3 {
    width: 90px!important;
    margin-left: 0px!important; 
}
.favorite .favorite_icon i {
    padding-top: 15px;
    font-size: 30px;
    margin-right: 10px;
}
i.heart.fa.fa-heart {
    padding-right: 17px;
}
h4.starting-bracket.white {
    margin-left: -16px;
}
.test_fav_2 {
    width: 85px!important;
     margin-left: 0px!important; 
}
.test_fav_1 {
    width: 73px!important;
    margin-left: 0px!important; 
}
.trail_test {
        top: 70px!important;
    left: 45px!important;
}
.tri_div.trail2.test_mobile {
    left: 58px!important;
    top: 71px!important;
    transform: rotate(-30deg);
    -ms-transform: rotate(-30deg);
    -webkit-transform: rotate(-50deg);
    border-top: 56px solid #fff!important;
}
.tri_div.trail2.trail_test {
    transform: rotate(35deg);
    -ms-transform: rotate(35deg);
    -webkit-transform: rotate(35deg);
    top: 64px!important;
    border-left: 8px solid transparent;
    border-right: 8px solid transparent;
    border-top: 35px solid #fff;
    left: 40px!important;
}
.tri_div.test_fav1 {
    left: 32px !important;
    border-top: 20px solid #fff!important;
}

}

@media only screen and (max-width: 900px) and (min-width: 600px){
.test_fav_1 {
    width: 80px!important;
    margin-left: 0px!important;
}
i.heart.fa.fa-heart {
    padding-right: 0px!important;
}
.test_fav_2 {
    width: 105px!important;
    margin-left: 0px!important;
}
.test_fav_3 {
    width: 96px!important;
    margin-left: 0!important;
}
.test_mobile {
       border-top: 40px solid #fff!important;
    left: 84px!important;
    top: 66px!important;
    transform: rotate(-30deg);
    -ms-transform: rotate(-30deg);
    -webkit-transform: rotate(-30deg);
}
.tri_div.trail2.trail_test {
    transform: rotate(35deg);
    -ms-transform: rotate(35deg);
    -webkit-transform: rotate(35deg);
    top: 64px!important;
    border-left: 8px solid transparent;
    border-right: 8px solid transparent;
	border-top: 38px solid #fff!important;
    left: 10px!important;
}
.tri_div.test_fav1 {
    left: 31px!important;
    border-top: 20px solid #fff!important;
}
}
@media only screen and (max-width: 900px) and (min-width: 360px){
    .favorite .favorite_icon i {
        padding-top: 15px;
        font-size: 30px;
    }
    .starting-bracket{
        font-size: 30px;
    }
    .nature_image{
        width: 30px;
        height: 30px;
        margin-top: -10px !important;
    }
    .transaction_num{
        font-size: 18px;
    }
    .favorite_num{
        font-size: 18px;
    }
    .main-wrapper{
        padding: 0 1rem 2.5rem;
    }
    
    
    /*.kType{
        font-size: 12px;
    }*/
}

.welccc_nottt {
    color: #fff;
    background: #00000012;
    margin-bottom: 12px;
    width: 200px;
}
.test_fav_1 {
    width: 100px;
    margin-left: 8px;
}
.test_fav_2 {
    width: 150px;
    margin-left: 8px;
}
.test_fav_3 {
    width: 150px;
    margin-left: 8px;
}
i.heart.fa.fa-heart {
    padding-right: 20px;
}
.tri_div.trail2.trail_test {
    
    transform: rotate(35deg);
    -ms-transform: rotate(35deg);
    -webkit-transform: rotate(35deg);
    top: 50px;
    border-left: 8px solid transparent;
    border-right: 8px solid transparent;
    border-top: 35px solid #fff;
 } 
@media only screen and (max-width: 1400px) and (min-width: 1200px){
.tri_div.trail2.test_mobile {
    left: 144px!important;
}
.tri_div.trail2.trail_test {
    transform: none;
    -ms-transform: none;
    -webkit-transform: none!important;
    top: 50px;
    border-left: 8px solid transparent;
    border-right: 8px solid transparent;
    border-top: 29px solid #fff;
    left: 15px!important;
}
}
</style>
 

<?php
if($merchant_detail['shortcut_icon'])
  $shortcut_icon=$site_url."/images/shortcut_icon/".$merchant_detail['shortcut_icon'];
  if($shortcut_icon=='')
	$shortcut_icon='https://koofamilies.com/img/logo_512x512.png';
$start_url=$site_url."/view_merchant.php?sid=".$merchant_detail['mobile_number']; 
 ?>
<script>
    $(function(){
    
      // setTimeout(function(){  
          // $("#test_wel_not").hide();
      // }, 5000);
    
    });
	function myFunction2() {

  var x = document.getElementById("register_password");

  if (x.type === "password") {

    x.type = "text";

	    $("#eye_pass_2").html('Hide Password');

			 $('#eye_slash_2').removeClass( "fa-eye-slash" );

            $('#eye_slash_2').addClass( "fa-eye" );

			

  } else {

    x.type = "password";   

	 $("#eye_pass_2").html('Show Password');

	  $('#eye_slash_2').addClass( "fa-eye-slash" );

            $('#eye_slash_2').removeClass( "fa-eye" );

  }

}
    $(document).ready(function(){
       /* $(".transaction_num").hover(function(e){
            $(".hint").css("display", "block");
            //$('[data-toggle="tooltip"]').tooltip(); 
        }, function(e){
            $(".hint").css("display", "none");
        });*/
        //
		var password_Created="<?php echo $password_Created; ?>";   
		
		var t="<?php echo $t; ?>";   
		var s_coin_ref="<?php echo $s_coin_ref; ?>";   
		
		// alert(t);
		if(t)
		{
			var msg="Congratulations,Your wallet is Credited with "+t+" "+s_coin_ref;  
			$('#show_msg').html(msg);
			$('#AlerModel').modal('show'); 
			// setTimeout(function(){},5000);
			setTimeout(function(){ $("#AlerModel").modal("hide"); },5000);
		}
		if(password_Created=="n")
		{
			$('#PasswordModel').modal('show'); 
		}
		var myDynamicManifest = {
			"gcm_sender_id": "540868316921",
	"icons": [
	
    {
      "src": "<?php echo $shortcut_icon; ?>",
      "type": "image/png",
      "sizes": "512x512"
    }
	],
	"short_name":'<?php echo $merchant_detail['name']; ?>',
	"name": "One stop centre for your everything",
	"background_color": "#4A90E2",
	"theme_color": "#4A90E2",
	"orientation":"any",
	"display": "standalone",
	"start_url":"<?php echo $start_url; ?>"
}
const stringManifest = JSON.stringify(myDynamicManifest);
const blob = new Blob([stringManifest], {type: 'application/json'});
const manifestURL = URL.createObjectURL(blob);
document.querySelector('#my-manifest-placeholder').setAttribute('href', manifestURL);
 $(".partner").click(function(){
			  $('#PartnerModel').modal('show');
		 });
    });
	
 $(".register_ajax").click(function(){    

			// return false;

			var password_created='<?php  echo $password_created;?>';

			// alert(password_created);

			

				   $("#register_error").hide();

					$(this).removeClass(" btn-primary").addClass("btn-default");

				 setTimeout(function() {  

					

				 $(this).removeClass("btn-default").addClass("btn-primary");

					}.bind(this), 5000);

					// var user_id=('#login_user_id').val();

					

					var user_id=$("#login_user_id").val();

					var order_id=$("#order_id").val();

					

					var login_password=$("#register_password").val();  

					var pass_length=login_password.length;  

					if((pass_length==0) || (pass_length>5))

					{    

					var data = {user_id:user_id,register_password:login_password,save_password:"y"};

				   

					$.ajax({

					  

					  url :'passwordsave.php',

					  type:'POST',

					  dataType : 'json',

					  data:data,

					  success:function(response){

						  if(response==1)

						  {

							  // location.reload();

							  $('#PasswordModel').modal('hide'); 

						  }

						  else

						  {

							 

							  // $("#login_error").show();

						  }

						  

						}		  

				  });

					}

					else

					{

						$("#register_error").html('The password must be six digit and above');

						$("#register_error").show();

						// $("#login_error").show();

					}

			

			 

			return false;

	   });

if ('serviceWorker' in navigator) {
  navigator.serviceWorker.register('/sw_new.js')
  .then(function(registration) {
    console.log('Registration successful, scope is:', registration.scope);
  })
  .catch(function(error) {
    console.log('Service worker registration failed, error:', error);
  });
}
/*
window.addEventListener("beforeinstallprompt", ev => { 
  // Stop Chrome from asking _now_
  ev.preventDefault();

  // Create your custom "add to home screen" button here if needed.
  // Keep in mind that this event may be called multiple times, 
  // so avoid creating multiple buttons!
  myCustomButton.onclick = () => ev.prompt();
});*/
</script>



<!-- USER MANAGEMENT CODE -->

<!-- Refer to User Management guide for this code -->

<!-- LAUNCH COMETCHAT CODE -->

