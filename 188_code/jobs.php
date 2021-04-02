<?php include('config.php');
if(isset($_GET['language'])){
	$_SESSION["langfile"] = $_GET['language'];
} 
if (empty($_SESSION["langfile"])) { $_SESSION["langfile"] = "english"; }
    require_once ("languages/".$_SESSION["langfile"].".php");
?>
<!DOCTYPE html>
<html lang="en">
<?php
		// Find out how many items are in the table
		$totalQuery = mysqli_query($conn,"select count(id) as totalRecord from jobs");
		$totalFetch = mysqli_fetch_assoc($totalQuery);
		$numrows = $totalFetch['totalRecord'];

		// number of rows to show per page
		$rowsperpage = 10;
		// find out total pages
		$totalpages = ceil($numrows / $rowsperpage);
		// get the current page or set a default
		if (isset($_GET['currentpage']) && is_numeric($_GET['currentpage'])) {
			// cast var as int
			$currentpage = (int) $_GET['currentpage'];
		} else {
			// default page num
			$currentpage = 1;
		} // end if
		// if current page is greater than total pages...
		if ($currentpage > $totalpages) {
			// set current page to last page
			$currentpage = $totalpages;
		} // end if
		// if current page is less than first page...
		if ($currentpage < 1) {
			// set current page to first page
			$currentpage = 1;
		} // end if
		// find out total pages
		$totalpages = ceil($numrows / $rowsperpage);
		// the offset of the list, based on current page 
		$offset = ($currentpage - 1) * $rowsperpage;
		// get the info from the db 
		// $sql = "SELECT id, number FROM numbers LIMIT $offset, $rowsperpage";
        
	?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="KooFamilies - Discover & Book the best restaurants at the best price">
    <meta name="author" content="Ansonika">
    <title>KooFamilies - One stop centre for your everything</title>

    <!-- Favicons-->
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" type="image/x-icon" href="img/apple-touch-icon-57x57-precomposed.png">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="72x72" href="img/apple-touch-icon-72x72-precomposed.png">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="114x114" href="img/apple-touch-icon-114x114-precomposed.png">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="144x144" href="img/apple-touch-icon-144x144-precomposed.png">

    <!-- GOOGLE WEB FONT -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- BASE CSS -->
    <link href="extra/css/bootstrap_customized.min.css" rel="stylesheet">
    <link href="extra/css/style.css" rel="stylesheet">

    <!-- SPECIFIC CSS -->
    <link href="extra/css/blog.css" rel="stylesheet">

    <!-- YOUR CUSTOM CSS -->
    <link href="extra/css/custom.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="css/sweetalert.css">
   <link rel="manifest" id="my-manifest-placeholder">
    <meta name="theme-color" content="#317EFB"/>
	<script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>

</head>

<body>
	
	<header class="header_in clearfix">
		<div class="container">
		<div id="logo">
			<a href="index.php?vs=<?php echo md5(rand()); ?>">
				<!-- <img src="img/logo_sticky.svg" width="140" height="35" alt=""> -->
				<img src="svgLog_second.svg" width="140" height="35" alt="" class="logo_normal">
                <img src="svgLog_first.svg" width="140" height="35" alt="" class="logo_sticky">
			</a>
		</div>
		<ul id="top_menu">
			
			<?php if(isset($_SESSION['login'])){	?>
			<li><a href="favorite.php" class="wishlist_bt_top" title="Your favorite">Your wishlist</a></li>
			<?php } ?>
		</ul>
		<!-- /top_menu -->   
		<a href="#0" class="open_close">
			<i class="icon_menu"></i><span>Menu</span>
		</a>
		<nav class="main-menu">
			<div id="header_menu">
				<a href="#0" class="open_close">
					<i class="icon_close"></i><span>Menu</span>
				</a>
				<a href="index.php?vs=<?php echo md5(rand()); ?>">KooFamilies</a>
			</div>
			 <ul>
				
				<?php if(!isset($_SESSION['login'])){	?>
					<li class="submenu"><a href="login.php" class="show-submenu" style="font-size:16px"><?php echo $language['login']; ?></a></li>
				<?php } else {?>
				<li class="submenu"><a href="dashboard.php" class="show-submenu" style="font-size:16px"><?php echo $language['dashboard']; ?></a></li>
				<li class="submenu"><a href="favorite.php" class="show-submenu" style="font-size:16px"><?php echo $language['my_fav']; ?></a></li>
				<?php } ?>
				
				<li class="submenu">
					<a href="#0" class="show-submenu"> <i class="fa fa-language" style="font-size:18px;" aria-hidden="true"></i> Language</a>
					<ul class="">
						
						<li><a href="index.php?vs=<?php echo md5(rand()); ?>&language=english">English</a></li>
						<li><a href="index.php?vs=<?php echo md5(rand()); ?>&language=chinese">Chinese</a></li>
						<li><a href="index.php?vs=<?php echo md5(rand()); ?>&language=malaysian">Malay</a></li>  
						
						
					</ul>
				</li>   
			</ul>
		</nav>
	</div>
	</header>
	<!-- /header -->
	
	<main class="mt-5">
		<div class="page_header element_to_stick">
		    <div class="container">
		    	<div class="row">
		    		<div class="col-xl-8 col-lg-7 col-md-7 d-none d-md-block">
		    			<div class="breadcrumbs blog">
				            <ul>
				                <li><a href="index.php?vs=<?php echo md5(rand()); ?>">Home</a></li>
				                <li><a href="jobs.php">Jobs</a></li>
				                
				            </ul>
		       	 		</div>
		    		</div>
		    		<div class="col-xl-4 col-lg-5 col-md-5">
		    			<div class="search_bar_list">
						<a href="postJob.php"><button class="btn_1 add_bottom_15 pull-right">POST JOB</button></a>
						</div>
		    		</div>
		    	</div>
		    	<!-- /row -->		       
		    </div>
		</div>
		<!-- /page_header -->
		<?php if($_GET['catid']){ 
		$catid=$_GET['catid'];
		$catName = mysqli_query($conn, "select * from job_category where id='$catid'");
		$catdetail=mysqli_fetch_array($catName);
  		// print_R($catdetail);
		
		?>
		<h2 style="margin-left:3%;"><?php echo "Job for ".$catdetail['category_name']; ?></h2>
		<div></div>
		<?php }?>
		<?php if($_SESSION['job_post']){ ?>
		<p style="color:red;text-align:center;"><?php echo "Your job has been submitted. We will review and come back to you as soon as possible"; $_SESSION['job_post']=''; ?></h2>
		<?php } ?>
		<div class="container margin_30_40" style="min-height:500px;">	
           	
			<div class="row">
				<div class="col-lg-9">
					<div class="row">
					    <a class="btn btn-primary" style="color:black;"  href="index.php?vs=<?php echo rand(); ?>"><button class="btn_1 add_bottom_15"><?php echo $language['more_shops'];?></button>

						 <img class="Sirv" data-src="https://koofamilies.sirv.com/shop.png" alt="" />
						 <img class="Sirv" data-src="https://koofamilies.sirv.com/shop.png" alt="" />
						 <img class="Sirv" data-src="https://koofamilies.sirv.com/shop.png" alt="" />
					 </a>   
						<?php 
						if($_GET['catid']){
							$cId = $_GET['catid'];
							// echo $cId;
							$sql = "SELECT jobs.*, job_category.category_name FROM `jobs` INNER JOIN job_category on jobs.job_category_id = job_category.id where jobs.view = '1' and jobs.job_category_id = '$cId' order by jobs.id desc ";
						}else{
							$sql = "SELECT jobs.*, job_category.category_name FROM `jobs` INNER JOIN job_category on jobs.job_category_id = job_category.id where jobs.view = '1' order by jobs.id desc";
						}
							$jobs = mysqli_query($conn, "$sql");
							while($row=mysqli_fetch_assoc($jobs)){
						?>
						<div class="col-md-12">
							<article class="blog">
								<div class="post_info row" style="border:1px solid;">
									<div class="col-md-8">
									
										<h2><a href="job_desc.php?id=<?php echo $row['id']?>"><?php echo $row['title']?></a></h2>
										<small><?php echo $row['category_name']?></small>
									<p><?php echo substr($row['job_desc'],0,50)?>
									
									</br>Expired date: <?php echo Date("d m Y", $row['expire_date_utc'])?>
									</br> <?php $salaryType=$row['salaryType'];
										if($salaryType=="monthly")
										{
											echo "Salery :"." Rm ".number_format($row['price'],2)." (fixed)";
										}
										else if($salaryType=="hour")
										{
											echo "Salery :"." Rm ".number_format($row['price'],2)."/hour";
										}
									?>
									</p>
									</div>
									<div class="col-md-4">
									<!-- <a href="https://api.whatsapp.com/send?phone=60123945670" target="_blank">	 -->
									<a href="job_desc.php?id=<?php echo $row['id']?>">
									<button type="button"  class="btn_1 add_bottom_15">  Details</button></a>
					
									</div>
								</div>
							</article>
							<!-- /article -->
						</div>
						<!-- /col -->
						<?php }?>
						
					</div>
					<!-- /row -->
					<?php
					// if not on page 1, don't show back links
					if ($currentpage > 1) {
						// show << link to go back to page 1
						echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=1'><<</a> ";
						// get previous page num
						$prevpage = $currentpage - 1;
						// show < link to go back to 1 page
						echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$prevpage'><</a> ";
						} // end if
						// loop to show links to range of pages around current page
						for ($x = ($currentpage - $range); $x < (($currentpage + $range) + 1); $x++) {
							// if it's a valid page number...
							if (($x > 0) && ($x <= $totalpages)) {
							// if we're on current page...
							if ($x == $currentpage) {
								// 'highlight' it but don't make a link
								echo " [<b>$x</b>] ";
							// if not current page...
							} else {
								// make it a link
								echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$x'>$x</a> ";
							} // end else
							} // end if 
						} // end for
						
						// if not on last page, show forward and last page links        
						if ($currentpage != $totalpages) {
							// get next page
							$nextpage = $currentpage + 1;
							// echo forward link for next page 
							echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$nextpage'>></a> ";
							// echo forward link for lastpage
							echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$totalpages'>>></a> ";
						} // end if
						/****** end build pagination links ******/
 ?>
					

					<!-- <div class="pagination_fg">
					  <a href="#">&laquo;</a>
					  <a href="#" class="active">1</a>
					  <a href="#">2</a>
					  <a href="#">3</a>
					  <a href="#">4</a>
					  <a href="#">5</a>
					  <a href="#">&raquo;</a>
					</div> -->

				</div>
				<!-- /col -->

				<aside class="col-lg-3">
				
					<div class="widget">
						<div class="widget-title">
							<h4>Categories</h4>
						</div>
						<ul class="cats">
						   <li><a href="jobs.php?vs=<?php echo rand(); ?>">View all</a></li>
							<?php $catName = mysqli_query($conn, "select * from job_category");
								while($catRow = mysqli_fetch_assoc($catName)){?>
										<li><a href="jobs.php?catid=<?php echo $catRow['id']; ?>&vs=<?php echo rand(); ?>"><?php echo $catRow['category_name']?> <span>(<?php $catID = $catRow['id'];$count = mysqli_query($conn,"select * from jobs where job_category_id ='$catID' and jobs.view = '1'"); echo mysqli_num_rows($count); ?>)</span></a></li>
										<!-- jobs.php?catid =<?php echo $catRow['id'];?> -->
							<?php	}
							?>
						</ul>
					</div>
					
				</aside>
				<!-- /aside -->
			</div>
			<!-- /row -->	
		</div>
		<!-- /container -->
		
	</main>

<!-- main end -->








	<footer>
		<div class="container">
			<div class="row">
				<div class="col-lg-6 col-md-6">
					<h3 data-target="#collapse_1">Quick Links</h3>
					<div class="collapse dont-collapse-sm links" id="collapse_1">
						<ul>
                            
							<li><a href="about.php">About us</a></li>
							<li><a href="login.php">Add your restaurant</a></li>
							<li><a href="favorite.php">Favorite Merchant</a></li>
							<li><a href="login.php">My account</a></li>
							
							<li><a href="about.php">Contacts</a></li>
						</ul>
					</div>
				</div>   
				<!--div class="col-lg-3 col-md-6">
					<h3 data-target="#collapse_2">Categories</h3>
					<div class="collapse dont-collapse-sm links" id="collapse_2">
						<ul>
							<li><a href="#">Top Categories</a></li>
							<li><a href="#">Best Rated</a></li>
							<li><a href="#">Best Price</a></li>
							<li><a href="#">Latest Submissions</a></li>
						</ul>
					</div>
				</div!-->
				<div class="col-lg-3 col-md-6">
						<h3 data-target="#collapse_3">Contacts</h3>
					<div class="collapse dont-collapse-sm contacts" id="collapse_3">
						<ul>
							<li><i class="icon_house_alt"></i>Kemajuaan ladang Cermerlang Sdn. Bhd. 1400, Jalan Lagenda 50, </br>Taman Lagenda Putra Kulai, Johor, 81000, Malaysia</li>
							<li><i class="icon_mobile"></i>+60 123-11-5670</li>
							<li><i class="icon_mail_alt"></i><a href="#0">info@koopay.com</a></li>
						</ul>
					</div>
				</div>
				<div class="col-lg-3 col-md-6">
						<h3 data-target="#collapse_4">Keep in touch</h3>
					<div class="collapse dont-collapse-sm" id="collapse_4">
						<div id="newsletter">
							<div id="message-newsletter"></div>
							<form method="post" action="assets/newsletter.php" name="newsletter_form" id="newsletter_form">
								<div class="form-group">
									<input type="email" name="email_newsletter" id="email_newsletter" class="form-control" placeholder="Your email">
									<button type="submit" id="submit-newsletter"><i class="arrow_carrot-right"></i></button>
								</div>
							</form>
						</div>
						<div class="follow_us">
							<h5>Follow Us</h5>
							<ul>
								<!--li><a href="#0"><img src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==" data-src="img/twitter_icon.svg" alt="" class="lazy"></a></li!-->
								<li><a href="https://www.facebook.com/koofamilies/" target="_blank"><img src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==" data-src="img/facebook_icon.svg" alt="" class="lazy"></a></li>
								<li><a href="#0"><img src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==" data-src="img/instagram_icon.svg" alt="" class="lazy"></a></li>
								<li><a href="#0"><img src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==" data-src="img/youtube_icon.svg" alt="" class="lazy"></a></li>
							</ul>
						</div>  
					</div>
				</div>
			</div>
			<!-- /row-->
			<hr>
			<div class="row add_bottom_25">
				<!--div class="col-lg-6">
					<ul class="footer-selector clearfix">
						<li>
							<div class="styled-select lang-selector">
								<select>
									<option value="English" selected>English</option>
									<option value="French">French</option>
									<option value="Spanish">Spanish</option>
									<option value="Russian">Russian</option>
								</select>
							</div>
						</li>
						<li>
							<div class="styled-select currency-selector">
								<select>
									<option value="US Dollars" selected>US Dollars</option>
									<option value="Euro">Euro</option>
								</select>
							</div>
						</li>
						<li><img src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==" data-src="img/cards_all.svg" alt="" width="198" height="30" class="lazy"></li>
					</ul>
				</div!-->
				<div class="col-lg-6">
					<ul class="additional_links">
						<li><a href="privacy.php">Terms and conditions</a></li>
						<li><a href="privacy.php">Privacy</a></li>
						<li><span>© 2020 KooFamilies</span></li>
					</ul>
				</div>
			</div>
		</div>
	</footer>
	<!--/footer-->

	<div id="toTop"></div><!-- Back to top button -->
	
	<div class="layer"></div><!-- Opacity Mask Menu Mobile -->
	
	<!-- Sign In Modal -->
	<div id="sign-in-dialog" class="zoom-anim-dialog mfp-hide">
		<div class="modal_header">
			<h3>Sign In</h3>
		</div>
		<form>
			<div class="sign-in-wrapper">
				<a href="#0" class="social_bt facebook">Login with Facebook</a>
				<a href="#0" class="social_bt google">Login with Google</a>
				<div class="divider"><span>Or</span></div>
				<div class="form-group">
					<label>Email</label>
					<input type="email" class="form-control" name="email" id="email">
					<i class="icon_mail_alt"></i>
				</div>
				<div class="form-group">
					<label>Password</label>
					<input type="password" class="form-control" name="password" id="password" value="">
					<i class="icon_lock_alt"></i>
				</div>
				<div class="clearfix add_bottom_15">
					<div class="checkboxes float-left">
						<label class="container_check">Remember me
						  <input type="checkbox">
						  <span class="checkmark"></span>
						</label>
					</div>
					<div class="float-right mt-1"><a id="forgot" href="javascript:void(0);">Forgot Password?</a></div>
				</div>
				<div class="text-center">
					<input type="submit" value="Log In" class="btn_1 full-width mb_5">
					Don’t have an account? <a href="login.php">Sign up</a>
				</div>
				<div id="forgot_pw">
					<div class="form-group">
						<label>Please confirm login email below</label>
						<input type="email" class="form-control" name="email_forgot" id="email_forgot">
						<i class="icon_mail_alt"></i>
					</div>
					<p>You will receive an email containing a link allowing you to reset your password to a new preferred one.</p>
					<div class="text-center"><input type="submit" value="Reset Password" class="btn_1"></div>
				</div>
			</div>
		</form>
		<!--form -->
	</div>
	<!-- /Sign In Modal -->
		  <div class="modal fade" id="location_model" role="dialog">

        <div class="modal-dialog">

         



            <!-- Modal content-->

            <div class="modal-content">

             

                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                    <h4 class="modal-title"></h4>

                </div>

                 

                    <div class="modal-body" style="padding-bottom:0px;">

                        <div class="col-md-12" style="text-align: center;">

                          <h5>To Sort By Distance Location permission is required </h5>

                         <button type="button" class="btn btn-primary" onclick="clearhistory()">How to clear Cache</button>

                

                        </div>

                    </div>

                    <div class="modal-footer" style="padding-bottom:2px;">

                    

                    </div>

               

            </div>

        </div>

 </div>
 <link href="https://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" rel="Stylesheet"></link>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script type="text/javascript" src="js/sweetalert.min.js" defer></script>
  <script src="extra/js/common_scripts.min.js" defer></script>
  <script src="extra/js/common_func.js" defer></script>
  
  <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<script src="extra/js/select2.min_da99e0cfb43d832f77954298a0557ca5.js" defer></script>
  <!-- SPECIFIC SCRIPTS -->
  <script src="extra/js/modernizr.min.js" defer></script>
  
	   <script type="text/javascript">
var map;
function initMap() {
var mapCenter = new google.maps.LatLng(47.6145, -122.3418); //Google map Coordinates
map = new google.maps.Map($("#map")[0], {
	  center: mapCenter,
	  zoom: 8
	});
}

</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB4BfDrt-mCQCC1pzrGUAjW_2PRrGNKh_U&libraries=places" async defer></script> 

		 <script type="text/javascript" src="extra/js/jquery.lazy.min_74facba505554b93155d59a4d2d7e78b.js" defer></script>
 <a href="https://api.whatsapp.com/send?phone=60123945670" target="_blank"><img src ="images/iconfinder_support_416400.png" style="width:75px;height:75px;position: fixed;left:15px;bottom: 70px;z-index:999;"></a>

</body>
</html>