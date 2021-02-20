<?php include('config.php');
?>
<!DOCTYPE html>
<html lang="en">
	

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
    <style>
        .radiocontainer{
            display : inline-block;
            position : relative;
            cursor: pointer;
            font-size : 16px;
            user-select : none;
            padding-left : 30px;
        }
        .radiocontainer input{
            display: none;
        }
        .radiocontainer .circle{
            display: inline-block;
            width:16px;
            height: 16px;
            border:2px solid aqua;
            position: absolute;
            left: 10px;
            top: 4px;
            border-radius: 50%;
        }
        .radiocontainer input:checked + .circle{
             background-color: #0075ff;
        }
        
</style>
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
				                <li><a href="#">Post Job</a></li>
				                
				            </ul>
		       	 		</div>
		    		</div>
		    		<div class="col-xl-4 col-lg-5 col-md-5">
		    			<div class="search_bar_list">
						<a href="jobs.php"><button class="btn_1 add_bottom_15 pull-right">JOBS</button></a>
						</div>
		    		</div>
		    	</div>
		    	<!-- /row -->		       
		    </div>
		</div>
		<!-- /page_header -->
		<div class="container margin_60_40" style="padding:0px;">
			
				<div class="row justify-content-center">
					<div class="col-lg-5">
						<div class="text-center add_bottom_15">
							<h4>Post Job</h4>
							
						</div>
						<div id="message-register"></div>
							<form method="post" action="postJob.php">
								
								<div class="row">
									<div class="col-lg-12">
										<div class="form-group">
                                            <label for="title">Job Title <span style="color:red;">*</span></label>
                                            <input type="text" name="title" class="form-control" required="" placeholder="Job Title">
										</div>
									</div>
								</div>
								
								<div class="form-group">
                                    <label for="title">Job Category<span style="color:red;">*</span></label>
                                    <select name="category" class="form-control" required>
                                        <option value="">--select job category--</option>
                                        <?php $catQuery = mysqli_query($conn,"select * from job_category where status ='y'");
                                            while($row=mysqli_fetch_assoc($catQuery)){?>
                                                <option value="<?php echo $row['id']?>" > <?php echo $row['category_name'];?> </option>
                                    <?php }?>
                                    </select>
                                </div>
								<!-- /row -->
								
								<div class="row">
									<div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="title">Job Price <span style="color:red;">*</span></label>
                                            <input type="text" required="" name="jPrice" min="1" class="form-control" placeholder="Price">
                                        </div>
									</div>
								</div>
								<!-- /row -->
								<div class="row">
									<div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="comment">Detailed Job Description<span style="color:red;">*</span></label>
											<textarea name="jobDesc" class="form-control" placeholder="Job Description" rows="5" id="comment"></textarea>
											<span style="color:red;font-weight:600;" id="error"></span>
                                        </div>
									</div>  
								</div>
								<!-- /row -->
								<div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="">Salary Type<span style="color:red;">*</span></label>
                                        <div class="row">
                                            <div class="col-6">
                                                <!-- <label class="radio-inline " style="margin-right:3%;">Fixed fee</label><input type="radio" name="salaryType" value="monthly" required=""> -->
                                                <label class="radiocontainer">Fixed fee<input type="radio" name="salaryType" value="monthly" required=""><span class="circle"></span></label>
                                                
                                            </div>
                                            <div class="col-6">
                                                <!-- <label class="radio-inline " style="margin-right:3%;">Per Hours</label><input type="radio" name="salaryType" value="hour" required=""> -->
                                                <label class="radiocontainer">Per Hours<input type="radio" name="salaryType" value="hour" required=""><span class="circle"></span></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </div>

                              

                                
                                <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="">Are you willing to pay milestone in advance?<span style="color:red;">*</span></label>
                                        <div class="row">
                                            <div class="col-6">
                                                <!-- <label class="radio-inline" style="margin-right:3%;">Yes</label><input type="radio" name="advance_salery" value="yes" required=""> -->
                                                <label class="radiocontainer">Yes<input type="radio" name="advance_salery" value="yes" required=""><span class="circle"></span></label>
                                            </div>
                                            <div class="col-6">
                                                <!-- <label class="radio-inline" style="margin-right:3%;">No</label><input type="radio" name="advance_salery" value="no" required=""> -->
                                                <label class="radiocontainer">No<input type="radio" name="advance_salery" value="no" required=""><span class="circle"></span></label>
                                            </div>
                                        </div>
                                        <p>Note: milestone will only be released to freelancer only when employer has 100 % satisfied with the job.</p>
                                    </div>
                                </div>
								</div>
								<!-- /row -->
								
								<div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="">Salary Status</label>
                                        <div class="row">
                                            
                                            <div class="col-6">
												<!-- <input type="radio" name="salaryStatus" value="1" required="">
                                                <label class="radio-inline " style="margin-right:3%">Negotiable</label> -->
                                                <label class="radiocontainer">Negotiable<input type="radio" name="salaryStatus" value="1" required=""><span class="circle"></span></label>
                                            </div>
                                            <div class="col-6">
											<!-- <input type="radio" name="salaryStatus" value="0" required="">
                                                <label class="radio-inline " style="margin-right:3%">Not Negotiable</label> -->
                                                <label class="radiocontainer">Not Negotiable<input type="radio" name="salaryStatus" value="0" required=""><span class="circle"></span></label>
												
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </div>
                                
                                <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="birthday">Expire Date</label>
                                        <input type="date" class="form-control" name="exDate" required="">
                                    </div>
                                </div>
                                </div>

                                <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="title">Your Name <span style="color:red;">*</span></label>
                                        <input type="text" required="" name="jProname" class="form-control" placeholder="Enter Your Name">
                                    </div>
                                </div>
                                </div>
                                <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="title">Your Contact <span style="color:red;">*</span></label>
                                        <input type="tel" required="" name="jProNo" class="form-control" placeholder="Enter Mobile No">
                                    </div>
                                </div>
                                </div>

                                <hr>
								<!-- /row -->
								<div class="form-group text-center"><input type="submit" id="submitForm" name="post_job" class="btn_1" value="Submit"></div>
                            </form>
                            
					</div>
				</div>
			</div>
		
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
<script type="text/javascript">
	 $(document).ready(function(){
		
	 });
 </script> 
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB4BfDrt-mCQCC1pzrGUAjW_2PRrGNKh_U&libraries=places" async defer></script> 

		 <script type="text/javascript" src="extra/js/jquery.lazy.min_74facba505554b93155d59a4d2d7e78b.js" defer></script>
 <a href="https://api.whatsapp.com/send?phone=60123945670" target="_blank"><img src ="images/iconfinder_support_416400.png" style="width:75px;height:75px;position: fixed;left:15px;bottom: 70px;z-index:999;"></a>

</body>
</html>
<?php
function gw_send_sms($user,$pass,$sms_from,$sms_to,$sms_msg){           
    $query_string = "api.aspx?apiusername=".$user."&apipassword=".$pass;
    $query_string .= "&senderid=".rawurlencode($sms_from)."&mobileno=".rawurlencode($sms_to);
    $query_string .= "&message=".rawurlencode(stripslashes($sms_msg)) . "&languagetype=1";        
     $url = "http://gateway.onewaysms.com.au:10001/".$query_string;  
    
	// Initialize a CURL session. 
	$ch = curl_init();  
	  
	// Return Page contents. 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	  
	//grab URL and pass it to the variable. 
	curl_setopt($ch, CURLOPT_URL, $url); 
	  
	$result = curl_exec($ch); 
	 $ok = "success"; 
	      
    return $ok;  
}
		if(isset($_POST['post_job'])){  
            extract($_POST);
            // print_r($_POST);
            // die;
            
            $post_date = strtotime(Date("Y-m-d h:i:s"));
            $expire_date = strtotime($exDate);  
            $insert="INSERT INTO `jobs`(`title`, `job_desc`, `price`, `posted_date_utc`, `expire_date_utc`, `job_category_id`, `job_provider_name`, `job_provide_mobile`, `salaryType`, `salaryStatus`,`advance_salery`,`view`,`postedStatus`) 
            VALUES ('$title','$jobDesc','$jPrice','$post_date','$expire_date','$category','$jProname','$jProNo','$salaryType','$salaryStatus','$advance_salery','0','0')";
			
		   $query = mysqli_query($conn,$insert);
            
            if($query){
				$msg_str='';
				$msg_str.="New Job Posted !"."\r\n";
				$msg_str.="Posted By:".$jProname.",".$jProNo."\r\n";
				// $msg_str.="Job title:".$title."\r\n";
				// $msg_str.="Desc:".$jobDesc."\r\n";
				$whatapp_group_name="Job delivery/live";
				whatappgroupmsg($whatapp_group_name,$msg_str);
				$sms_to="60123945670,60127500913";
				$smsend=gw_send_sms("APIHKXVL33N5E", "APIHKXVL33N5EHKXVL", "9787136232", $sms_to,$msg_str);
				$_SESSION['job_post']="yes";
				$send_url="jobs.php"."?vs=".md5(rand());
                 header("Location:$send_url");
				 die;
            }else{
                // header('location:postJob.php');
            }
        }   
	?>