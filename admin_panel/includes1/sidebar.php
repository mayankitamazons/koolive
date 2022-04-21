<style>
 a:not([href]):not([tabindex]):hover {
    text-decoration: none;
    color: #fff;
}
.hide-menu .user-type {
    text-decoration: none;
    color: #fff;
}
.sidebar-dark .side-menu li a {
    color: #fff;
    font-size: 17px;
}
.active
{
	color:#51d2b7 !important;
}
</style>



<?php
$profile_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM admins WHERE id='".$_SESSION['admin']."'"));
?>
<aside class="site-sidebar scrollbar-enabled clearfix ps ps--theme_default ps--active-y" data-ps-id="d59dfc42-2cc7-c0b1-cf68-87ad01c4613d">
<!-- User Details -->
<div class="side-user">
<a class="col-sm-12 media clearfix">
<div class="media-body hide-menu">
<h4 class="media-heading mr-b-5 text-uppercase"><?php echo $profile_data['name']; ?></h4><span class="user-type fs-12"><?php echo $profile_data['email']; ?></span>
</div>
</a>
<div class="clearfix"></div>
</div>
<!-- /.side-user -->
<!-- Sidebar Menu -->
<nav class="sidebar-nav">
    <ul class="nav in side-menu">

		 <li><a href="merchant.php" class="<?php if($a_m=="merchant"){ echo "active";} ?>">Merchant</a></li>
		  <li><a href="delete_merchant.php" class="<?php if($a_m=="delete_merchant"){ echo "active";} ?>">Delete Merchant</a></li>
		    <li><a href="producturl.php" class="<?php if($a_m=="producturl"){ echo "active";} ?>">Product Link</a></li>
			
		   <li class="">
			<a href="import_csv.php" class="<?php if($a_m=="foodpanda"){ echo "active";} ?>">Food Panda</a>
        </li>	
		<li><a href="donation.php" class="<?php if($a_m=="donation"){ echo "active";} ?>">Donation</a></li>
		  
		
 <li><a href="territory.php" class="<?php if($a_m=="territory"){ echo "active";} ?>">Territory</a></li>
		 
				 <li><a href="user.php" class="<?php if($a_m=="member"){ echo "active";} ?>">Member</a></li>
				 <li><a href="sms_setting.php" class="<?php if($a_m=="sms_setting"){ echo "active";} ?>">Sms Setting</a></li>
				 <li><a href="coupon.php" class="<?php if($a_m=="coupon"){ echo "active";} ?>">Coupon code</a></li>
		  <li><a href="classficationmerchant.php"><span class="hide-menu <?php if($a_m=="classfication_merchant"){ echo "active";} ?>">Classfication Merchant</span></a></li>  
 <li><a href="refferalrebate.php"><span class="hide-menu <?php if($a_m=="refferal_rebate"){ echo "active";} ?>">Reffereal Bonun System</span></a></li> 		  		  
		  <li><a href="smspush.php"><span class="hide-menu <?php if($a_m=="send_push"){ echo "active";} ?>">Send push msg</span></a></li>      
		  <li><a href="feedback.php"><span class="hide-menu <?php if($a_m=="feedback"){ echo "active";} ?>">Feedback</span></a></li>     
		   <li><a href="riders.php" class="<?php if($a_m=="riders"){ echo "active";} ?>">Riders</a></li>
        <li class="menu-item-has-children">
			<a href="javascript:void(0);" class="ripple"><span class="color-color-scheme"><span class="hide-menu">Jobs</span></span></a>
			<ul class="list-unstyled sub-menu collapse" aria-expanded="true">
				 <li><a href="job_category.php">Jobs Category</a></li>
				 <li><a href="jobs_list.php">Job list</a></li>
				 <li><a href="postedJob.php">Job Request</a></li>
			</ul>
        </li>		  
		  <li><a href="apartnerlist.php"><span class="hide-menu <?php if($a_m=="a_c_l"){ echo "active";} ?>">Accepting coin list</span></a></li>   
		  <li><a href="partnerlist.php"><span class="hide-menu <?php if($a_m=="a_p_l"){ echo "active";} ?>">Associated  Partner List</span></a></li>   
        <li><a href="agents.php"><span class="hide-menu <?php if($a_m=="agents"){ echo "active";} ?>">Agents</span></a></li>   
      
        <li><a href="rebate.php"><span class="hide-menu <?php if($a_m=="rebate"){ echo "active";} ?>">Rebate</span></a></li>   
        <li><a href="merchants.php"><span class="hide-menu <?php if($a_m=="Subscription"){ echo "active";} ?>">Subscription</span></a></li>
        <li><a href="wallet.php"><span class="hide-menu <?php if($a_m=="wallet"){ echo "active";} ?>">Wallet</span></a></li>
		<li><a href="transaction.php"><span class="hide-menu <?php if($a_m=="Transaction"){ echo "active";} ?>">Transaction</span></a></li>
		<li><a href="recharge.php"><span class="hide-menu <?php if($a_m=="Recharge"){ echo "active";} ?>">Recharge</span></a></li>
		<li><a href="request.php"><span class="hide-menu <?php if($a_m=="Request"){ echo "active";} ?>">Request</span></a></li>
		<li><a href="favorite.php"><span class="hide-menu <?php if($a_m=="Favorite"){ echo "active";} ?>">Favorite</span></a></li>
		<li class="menu-item-has-children">
			<a href="javascript:void(0);" class="ripple"><span class="<?php if($a_m=="p_a_s" || $a_m=="p_v_s"){ echo "active";} ?>"><span class="hide-menu">Product Subscription</span></span></a>
			<ul class="list-unstyled sub-menu collapse" aria-expanded="true">
				 <li><a href="add_sub.php" class="<?php if($a_m=="p_a_s"){ echo "active";} ?>">Add Subscription</a></li>
				 <li><a href="view_sub.php" class="<?php if($a_m=="p_v_s"){ echo "active";} ?>">View Subscription</a></li>
			</ul>
        </li>

        <li><a href="referral_merchant.php"><span class="hide-menu">Referral List</span></a></li>
        <li><a href="community_merchant.php"><span class="hide-menu">Community Fund</span></a></li>
        <!--<li class="menu-item-has-children">
			<a href="javascript:void(0);" class="ripple"><span class="color-color-scheme"><span class="hide-menu">Community Fund</span></span></a>
			<ul class="list-unstyled sub-menu collapse" aria-expanded="true">
				 <li><a href="community_merchant.php">Merchant</a></li>
				 <li><a href="community_member.php">Member</a></li>
			</ul>
        </li>-->
        <li><a href="subscribe.php"><span class="hide-menu">Subscribe</span></a></li>
        <li><a href="kType.php"><span class="hide-menu">K Type</span></a></li>
        <li><a href="total_commission.php"><span class="hide-menu">Commission</span></a></li>
		<li><a href="exchange_requests.php"><span class="hide-menu">Exchange Request</span></a></li>
		<li><a href="charges.php"><span class="hide-menu">Withdraw Charges</span></a></li>
		<li><a href="margin.php"><span class="hide-menu">Exchange Margin</span></a></li>
		<li><a href="contact.php"><span class="hide-menu">Contact</span></a></li>
		<li><a href="city.php"><span class="hide-menu">City</span></a></li>
        <li><a href="logout.php"><span class="hide-menu">Log Out</span></a></li>    
    </ul>
    <!-- /.side-menu -->
</nav>
<!-- /.sidebar-nav -->
<div class="ps__scrollbar-x-rail" style="left: 0px; bottom: 0px;"><div class="ps__scrollbar-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__scrollbar-y-rail" style="top: 0px; height: 523px; right: 0px;"><div class="ps__scrollbar-y" tabindex="0" style="top: 0px; height: 271px;"></div></div></aside>
