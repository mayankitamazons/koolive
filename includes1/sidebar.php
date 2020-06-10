<style>
.media-body .user-type{
    color:#fff;
}
 a:not([href]):not([tabindex]):hover {
    color: #fff;
    text-decoration: none;
}
.sidebar-dark .side-menu li a {
    color: #ffffff;
    font-size: 17px;
}
.color-color-scheme, .text-color-scheme {
    color: #ffffff !important;
}
.sidebar-dark .side-menu :not([class*="color-"]) > .list-icon, .sidebar-dark .side-menu .menu-item-has-children > a::before {
    color: #ffffff;
}
.side-menu .menu-item-has-children > a::before {
    font-family: FontAwesome;
    content: "\f0d7";
    position: absolute;
    right: 0.83333em;
    top: 0;
    font-size: 1.2em;
    color: #ccc;
}
.investor_relation i{
    color: yellow;
    font-size: 10px;
    position: absolute;
    top: 12px;
}
.active
{
	color:#51d2b7 !important; 
}
</style>
<?php
$profile_data = isset($_SESSION['login']) ? mysqli_fetch_assoc(mysqli_query($conn, "SELECT SQL_NO_CACHE * FROM users WHERE id='".$_SESSION['login']."'")) : '';

$membership_plan_set=$profile_data['membership_plan'];
$coupon_offer=$profile_data['coupon_offer'];
if($user_roles=="5")
{
	if(isset($profile_data['permission_set']))
	{
		$getdetail=unserialize($profile_data['permission_set']);
		if(in_array(1,$getdetail))
		{
			$orderlistperm=1;
		}
		 if(in_array(2,$getdetail))
		{
			$comminutyper=1;
		}
		 if(in_array(3,$getdetail))
		{
			$referlistper=1;
		}
		 if(in_array(4,$getdetail))
		{
			$ktypeper=1;
		}
		 if(in_array(5,$getdetail))
		{
			$profileper=1;
		}
		 if(in_array(6,$getdetail))
		{
			$inverstorper=1;
		}
		 if(in_array(7,$getdetail))
		{
			$contactusper=1;
		}
		 if(in_array(8,$getdetail))
		{
			$dashboardwe=1;
		}
		 if(in_array(9,$getdetail))
		{
			$addproductper=1;
		}
		 if(in_array(10,$getdetail))
		{
			$viewproductper=1;
		}
		 if(in_array(11,$getdetail))
		{
			$addcategpryper=1;
		}
		 if(in_array(12,$getdetail))
		{
			$addmasterper=1;
		}
		 if(in_array(13,$getdetail))
		{
			$viewcategoryper=1;
		}
		 if(in_array(14,$getdetail))
		{
			$subscriptionper=1;
		}
		 if(in_array(15,$getdetail))
		{
			$aboutusper=1;
		}
		
		 if(in_array(16,$getdetail))
		{
			$report=1;
		}
	}
}
?>
<aside class="site-sidebar scrollbar-enabled clearfix ps " data-ps-id="d59dfc42-2cc7-c0b1-cf68-87ad01c4613d">
	<!-- User Details -->
	<div class="side-user">
		<a class="col-sm-12 media clearfix">
		<div class="media-body hide-menu"> 
		<?php if(isset($_SESSION['login'])) {?>
			<h4 class="media-heading mr-b-5 text-uppercase"><?php echo $profile_data['name']; ?></h4><span class="user-type fs-12"><?php echo $profile_data['email']; ?></span>
			<h4 class="media-heading mr-b-5 text-uppercase"><?php echo $language['Referral_id'];?>:</h4><span class="user-type fs-12"><?php echo $profile_data['referral_id']; ?></span>
		<?php }?>
		</div>
		</a>
		<div class="clearfix"></div>
	</div>
	<nav class="sidebar-nav">
		<ul class="nav in side-menu">
			<?php if(isset($_SESSION['login'])){ ?>
			  <li> <a href="dashboard.php?vs=<?=md5(rand()) ?>" class="<?php if($me=="dashboard"){ echo "active";} ?>"> <?php echo $language["dashboard"] ?> </a> </li> 
			
				<?php if($user_roles=="2" || $user_roles=="5"){ ?>
					<li class="menu-item-has-children">
					<a href='#' class="<?php if ($merchant_tab=="y"){ echo "active";}?>"><?php echo $language['merchant'];?></a>
					<ul class="list-unstyled sub-menu collapse" aria-expanded="true">
					    <!-- setup of order list  !-->
						<?php if($user_roles=="2"){ ?>
					  	<li><a href="orderview.php?vs=<?=md5(rand()) ?>" class="<?php if ($me=="order_list"){ echo "active";}?>"><?php echo $language["order_list"];?></a></li> 
						<?php if($profile_data['voice_recognition']){ ?>
						<li><a href="voiceorderview.php?vs=<?=md5(rand()) ?>" class="<?php if ($me=="voice_order_view"){ echo "active";}?>"><?php echo $language["voice_order_list"];?></a></li> 
						<?php } } else if($profile_data['user_roles'] ==  '5' && $orderlistperm==1){ ?>
							<li><a href="orderview-staff.php?vs=<?=md5(rand()) ?>"" class="<?php if ($me=="order_list_staff"){ echo "active";}?>"><?php echo $language['order_list'];?></a></li> 
						<?php } ?>
						<!-- end of order list  !-->
						<!-- setup of report  !-->
						<?php if($user_roles=="2"){ ?>
						<li><a href="report.php?vs=<?=md5(rand()) ?>" class="<?php if ($me=="report"){ echo "active";}?>"><?php echo $language["report"];?></a></li> 
						<?php } else if($profile_data['user_roles'] ==  '5' && $report==1){ ?>
							<li><a href="report.php" class="<?php if ($me=="report"){ echo "active";}?>"><?php echo $language['report'];?></a></li> 
						<?php } ?>
						<!-- end of report  !-->
						<?php if($user_roles=="2"){ ?>
						 <li><a href="add_mater_category.php?vs=<?=md5(rand()) ?>" class="<?php if ($me=="add_master"){ echo "active";}?>"><?php echo $language['add_master']; ?></a></li>
						<?php } else if($profile_data['user_roles'] ==  '5' && $addmasterper==1){ ?>
							<li><a href="add_mater_category.php" class="<?php if ($me=="add_master"){ echo "active";}?>"><?php echo $language['add_master']; ?></a></li>
						<?php } ?>
						
						<?php if($user_roles=="2"){ ?>   
						  <li class="menu-item-has-children">
						    <a href="#" class="<?php if ($category_tab=="y"){ echo "active";}?>"><?php echo $language['category']; ?></a>
							<ul class="list-unstyled sub-menu collapse">
								<li><a href="add_category.php?vs=<?=md5(rand()) ?>" class="<?php if ($me=="add_category_button"){ echo "active";}?>"><?php echo $language['add_button']; ?></a></li>
								<li><a href="view_category.php?vs=<?=md5(rand()) ?>" class="<?php if ($me=="edit_category_button"){ echo "active";}?>"><?php echo $language['edit_button']; ?></a></li>
								<li><a href="category_order.php?vs=<?=md5(rand()) ?>" class="<?php if ($me=="position_category_button"){ echo "active";}?>"><?php echo $language['position_button']; ?></a></li>
							</ul>  
						  </li>
						  <li class="menu-item-has-children">
						    <a href="#" class="<?php if ($product_tab=="y"){ echo "active";}?>"><?php echo $language['product']; ?></a>
							<ul class="list-unstyled sub-menu collapse" aria-expanded="true">
								<li><a href="merchant_product.php?vs=<?=md5(rand()) ?>" class="<?php if ($me=="add_product_button"){ echo "active";}?>"><?php echo $language['add_button']; ?></a></li>
								<li><a href="view_product.php?vs=<?=md5(rand()) ?>" class="<?php if ($me=="edit_product_button"){ echo "active";}?>"><?php echo $language['edit_button']; ?></a></li>
								<li><a href="product_order.php?vs=<?=md5(rand()) ?>" class="<?php if ($me=="position_product_button"){ echo "active";}?>"><?php echo $language['position_button']; ?></a></li>
							</ul>
						  </li>
						   <li><a href="remark.php" class="<?php if ($me=="remark"){ echo "active";}?>"><span class="hide-menu"><?php echo $language['remarks']; ?></span></a></li>
						   <li><a href="set_workinghours.php?vs=<?=md5(rand()) ?>" class="<?php if ($me=="workinghrs"){ echo "active";}?>"><?php echo $language["timing_working"];?></a></li> 
						   <li><a href="newtimings.php?vs=<?=md5(rand()) ?>" class="<?php if ($me=="workinghrs"){ echo "active";}?>"><?php echo $language["new_timing_working"];?></a></li> 
						   <li><a href="offers.php?vs=<?=md5(rand()) ?>" class="<?php if ($me=="offers"){ echo "active";}?>"><?php echo $language['offers'];?></a></li>   
						   <li><a href="sections.php?vs=<?=md5(rand()) ?>" class="<?php if ($me=="sections"){ echo "active";}?>"><?php echo $language['sections'];?></a></li>
						<?php } else if($profile_data['user_roles'] ==  '5'){ 
							 if($addmasterper==1 || $viewcategoryper==1){?>
							<li class="menu-item-has-children">
								  <a href="#" class="<?php if ($category_tab=="y"){ echo "active";}?>"><?php echo $language['category']; ?></a>
								<ul class="list-unstyled sub-menu collapse" aria-expanded="true">
								  <?php if($addmasterper==1){ ?>
								 <li><a href="add_category.php" class="<?php if ($me=="add_category_button"){ echo "active";}?>"><?php echo $language['add_button']; ?></a></li> <?php } if($viewcategoryper==1){ ?>
								 <li><a href="view_category.php" class="<?php if ($me=="edit_category_button"){ echo "active";}?>"><?php echo $language['edit_button']; ?></a></li><?php } ?>
									
								</ul>
							</li>
							<?php }  if($addproductper==1 || $viewproductper==1){  ?>
							<li class="menu-item-has-children">
								 <a href="#" class="<?php if ($product_tab=="y"){ echo "active";}?>"><?php echo $language['product']; ?></a>
								<ul class="list-unstyled sub-menu collapse" aria-expanded="true">
								  <?php if($addproductper==1){ ?>
								 <li><a href="merchant_product.php" class="<?php if ($me=="add_product_button"){ echo "active";}?>"><?php echo $language['add_button']; ?></a></li> <?php } if($viewproductper==1){ ?>
								 <li><a href="view_product.php" class="<?php if ($me=="edit_product_button"){ echo "active";}?>"><?php echo $language['edit_button']; ?></a></li><?php } ?>
									
								</ul>
							</li>
							<?php } } ?>
						
					</ul>
					</li>
					  <?php if($profile_data['unrecognize_coin']=='1'){ ?>
			  <li><a href="partner_list.php" class="<?php if($me=="partner_list"){ echo "active";} ?>"><?php echo $language["partner_list"];?></a></li> <?php } ?>
				<?php if($profile_data['special_coin_name']){ ?>
				 <li><a href="coinuserlist.php" class="<?php if($me=="special_coin_name"){ echo "active";} ?>"><?php echo $profile_data['special_coin_name'];?></a>  </li>
				<?php } } ?>
				<li class="menu-item-has-children">
					<a href='#' class="<?php if($find_merchant_tab=="y") echo "active"; ?>"><?php echo $language["find_merchant"];?></a>
					<ul class="list-unstyled sub-menu collapse" aria-expanded="true">
						<li><a href="merchant_find.php" class="<?php if ($me=="merchant_find"){ echo "active";}?>"><?php echo $language["find_merchant"];?></a></li>
						<li><a href="favorite.php" class="<?php if ($me=="favorite"){ echo "active";}?>"><?php echo $language['fav_merchant'];?></a></li>
					</ul>
				</li>
				<?php  if($_SESSION['login']){?>
					<li><a href="orderlist.php?vs=<?=md5(rand()) ?>" class="<?php if ($me=="orderlist"){ echo "active";}?>"><?php echo $language['list_order_payment'];?></a></li>
					<li><a href="voiceorderlist.php?vs=<?=md5(rand()) ?>" class="<?php if ($me=="voice_order_list"){ echo "active";}?>"><?php echo "Voice Order History";?></a></li>  
				<?php } ?>   
				<?php if($user_roles=="2" || $user_roles=="5"){ ?>
			
					<?php if($user_roles=="2"){ ?>
						<li class="menu-item-has-children">
							<a href="#" class="<?php if ($memebership_tab=="y"){ echo "active";}?>"><?php echo $language['membership']; ?></a>
							<ul class="list-unstyled sub-menu collapse" aria-expanded="true">
								<li><a href="memberlist.php"  class="<?php if ($me=="refferal_member_list"){ echo "active";}?>"><?php echo $language["refferal_member_list"];?></a></li> 
								<?php if($membership_plan_set==1){ ?>
								<li><a href="subscription.php"  class="<?php if ($me=="subscription_plan"){ echo "active";}?>"><span class="hide-menu"><?php echo $language["subscription_plan"];?></span></a></li>
								<?php } ?>
								<!--li><a href='#'>Member Application</a> </li!-->
							</ul>
						</li>
						<?php if($coupon_offer==1){ ?>
						<li><a href="coupon_list.php?vs=<?=md5(rand()) ?>" class="<?php if ($me=="coupon_code_list"){ echo "active";}?>"><span class="hide-menu"><?php echo $language["coupon_code_list"];?></span></a></li> <?php } ?>
						<li><a href="deliveryplan.php?vs=<?=md5(rand()) ?>" class="<?php if ($me=="delivery_plan"){ echo "active";}?>"><span class="hide-menu"><?php echo $language["delivery_plan"];?></span></a></li>
						<li><a href="referral_list.php?vs=<?=md5(rand()) ?>" class="<?php if ($me=="referral_list"){ echo "active";}?>"><span class="hide-menu"><?php echo $language['referral_list'];?></span></a> </li>
						<li class="menu-item-has-children">
						  <a href="#" class="<?php if ($profile_tab=="y"){ echo "active";}?>"><?php echo $language['profile']; ?></a>
							<ul class="list-unstyled sub-menu collapse" aria-expanded="true">
								<li><a href="profile_merchant.php" ><span class="hide-menu <?php if ($me=="profile_merchant"){ echo "active";}?>"><?php echo $language['profile']; ?></span></a></li>
								<li><a href="about_us.php"><span class="hide-menu <?php if ($me=="about_us"){ echo "active";}?>"><?php echo $language['about_us']; ?></span></a></li>
								<li><a href="staff.php"><span class="hide-menu <?php if ($me=="staff"){ echo "active";}?>"><?php echo $language["staff"];?></span></a></li>
								<!--li><a href="#">Subscription</a></li!-->
							</ul>
						</li> 
						<li> 
							<?php   $setup_shop=$balance['setup_shop'];
							   if(($setup_shop=="y") && ($user_roles=='2')){ ?>   
							
							<a class="Logoutpop"><?php echo $language['log_out'];?> </a>
							<?php } else { ?>
							  <a href="#"  type="normal_logout" class="logout"><?php echo $language['log_out'];?>  </a>    
							<?php } ?>
						</li> 
					<?php } ?>
				<?php } ?>
			<?php } if($user_roles=="1"){  ?>
				 <li><a href="profile.php?vs=<?=md5(rand()) ?>"><span class="hide-menu <?php if($me=="profile"){echo "active";} ?>"><?php echo $language['profile'];?></span></a></li>
				 <li><a href="referral_list.php?vs=<?=md5(rand()) ?>"><span class="hide-menu <?php if($me=="referral_list"){echo "active";} ?>"><?php echo $language['referral_list'];?></span></a> </li>
				   <li><a href="investor_relations.php" class="investor_relation"><span class="hide-menu <?php if($me=="investor_relation"){echo "active";} ?>"><?php echo $language['InvestorRelations']; ?></span><i class='fa fa-star' style="color:yellow;"></i></a></li>
				   <li><a href="contact.php"><span class="hide-menu <?php if($me=="contact"){echo "active";} ?>"><?php echo $language['contact'];?></span></a></li>  
				   <li><a href="#"  type="normal_logout" class="logout"><?php echo $language['log_out']; ?>  </a></li>
				<?php }else if($user_roles==''){ ?>
				<li><a href="merchant_find.php"><span class="hide-menu <?php if($me=="merchant_find"){ echo "active";} ?>"><?php echo $language["find_merchant"];?></span></a></li>
				<li><a href="investor_relations.php" class="investor_relation"><span class="hide-menu <?php if($me=="investor_relation"){ echo "active";} ?>"><?php echo $language['InvestorRelations']; ?></span><i class="fa fa-star" style="color:yellow;"></i></a></li>
				<li><a href="contact.php"><span class="hide-menu <?php if($me=="contact"){ echo "active";} ?>"><?php echo $language['contact'];?></span></a></li>
				<li><a href="login.php"><span class="hide-menu <?php if($me=="login"){ echo "active";} ?>"><?php echo $language['login'];?></span></a>	</li>
			<?php } ?>  
			<?php if($user_roles==5){ ?>
			<li> <a href="#"  type="normal_logout" class="logout"><?php echo $language['log_out'];?>  </a> </li>
			<?php } ?>
		</ul>
	</nav>
	
</aside>
  

