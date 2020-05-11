<?php
include("config.php");
require 'cloudinary/src/Cloudinary.php';
require 'cloudinary/src/Uploader.php';
require 'cloudinary/src/Api.php';

// \Cloudinary::config(array( 
// 	"cloud_name" => "tophat123", 
// 	"api_key" => "883511372359857", 
// 	"api_secret" => "8fLfITH3Rpkyhr9qp12N22dqxKQ", 
// 	"secure" => true
// ));

\Cloudinary::config(array( 
	"cloud_name" => "koofamilies", 
	"api_key" => "828961446391997", 
	"api_secret" => "GF2D4rh_faUqzSffM4emUsgMKfY", 
	"secure" => true
));
if(isset($_POST)){

if(isset($_FILES['soundBlob']['name']) && !empty($_POST['mobile_number']) && $_FILES['soundBlob']['error'] == 0){

		

		$res = array();
		extract($_POST);
		$merchant_id=$_POST['m_id'];
		$merchant_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$merchant_id."'"));
		$guest_permission = $merchant_data['guest_permission'];
		$referral_by	  = $merchant_data['referral_id']; 
		$show_alert="y";
		$password_created="n";
		if($mobile_number)
		{
			if($guest_permission==1)
			{
				$f_letter=$mobile_number[0];
				if (($f_letter=="1") ) {
					$show_alert="y";
					$password_created="n";
				}
				else
				{
					$show_alert="n";
					$password_created="y";
				}
			}
			else
			{
				$show_alert="y";
			}
			$mobile_check="60".$mobile_number;
			if($_SESSION['login']=='')
			{
				$loginmatch = mysqli_query($conn, "SELECT * FROM users WHERE  mobile_number ='".$mobile_check."'");	
			}
			else
			{
				$loginmatch = mysqli_query($conn, "SELECT * FROM users WHERE  id ='".$_SESSION['login']."'");	
			}
			$logincount=mysqli_num_rows($loginmatch);
		}
		else
		{
			$show_alert="n";
			$logincount=0;
		}
		$mobile_block = array("60123456789", "601234567890", "Glenn", "Cleveland");
		if(in_array($mobile_check,$mobile_block))
		{
			$res['error']	= false;
			$res['message'] = "Your number has been barred from placing order, please contact 012-3115670 for clarification..";
			die(json_encode($res));	
		}
		if($logincount>0)
		{
			$userdata = mysqli_fetch_assoc($loginmatch);
			$user_id  = $userdata['id'];
			$user_mobile=$userdata['mobile_number'];
			if($mobile_check==$user_mobile)
			{
			}
			else
			{
				$user_mobile=$mobile_check;
			}
			$user_name=$userdata['name'];
			$q=mysqli_query($conn,"select count(id) as total_order from order_voice_list where user_id='$user_id'");
			$totalcount=mysqli_fetch_assoc($q);
			$totalcount=$totalcount['total_order'];
			if($totalcount>10)
			{   
				$res['error']	 = false;
				$res['message']  = "Unable to complete order now, you can only place 10 order maximum!";
				$res['Location'] = $site_url."/view_merchant.php";
				die(json_encode($res));	
			}

			$date=date('Y-m-d');     
			$_SESSION['user_id']=$user_id;
		}
		else
		{
			if($mobile_number)
			{
				// create new user account with respect to merchant 
				$code = uniqid();
				$ref = $mobile_number." ".$code;
				$mobile_check ="60".$mobile_check;
				$user_role=1;
				$reocrd=mysqli_query($conn, "INSERT INTO users SET referral_id='$ref',referred_by='$referral_by',name='$name',user_roles='$user_role',mobile_number='$mobile_check',guest_user='y',login_status='1',password_created='$password_created'");
	            $user_id=mysqli_insert_id($conn);
				$user_mobile="60".$mobile_number;   
				$newuser="y";
			}
			else
			{
				if($guest_permission == "1"){
					$user_id=3366;
					$user_mobile='';
					$$neuser="n";
					$guest_user_order="y";
				}
				else
				{
				
				}
			}
		}

		if($_SESSION['login']=='')
		{
			$_SESSION['tmp_login']=$user_id;
		}

		$login_user_role = isset($_POST["login_user_role"])?$_POST["login_user_role"]:"";
		$table_type 	= isset($_POST["table_type"])?$_POST["table_type"]:"";
		$mobile_number 	= isset($_POST["mobile_number"])?$_POST["mobile_number"]:"";
		$location 		= isset($_POST["location"])?$_POST["location"]:"";
		$section_type 	= isset($_POST["section_type"])?$_POST["section_type"]:"";
		$stl_key 		= isset($_POST['stl_key']) ? $_POST['stl_key'] : '';
		$status 		= 0;
		$created_on  	= date('Y-m-d H:i:s');
		$extension 		= pathinfo($_FILES['soundBlob']['name'], PATHINFO_EXTENSION);
		$filename 		= time()."_".$_FILES["soundBlob"]["name"];

		//$moved 			= move_uploaded_file($_FILES["soundBlob"]["tmp_name"],"uploads/audios/".$filename);

		$moved = \Cloudinary\Uploader::upload($_FILES["soundBlob"]['tmp_name'], 
			array(
				"folder" => "koofamilies_audio",
				"public_id" => $filename,
				"overwrite" => TRUE, 
				"resource_type" => "video"
			)
		);
		$mobile_number ="60".$mobile_number;
		if($moved){
			if(!empty($moved['secure_url'])){
				$file_path = $moved['secure_url'];
			}else{
				$file_path = $moved['url'];
			}

			$sql ="INSERT INTO `order_voice_list`(`user_id`,`merchant_id`, `table_type`, `user_mobile`, `location`, `section_type`, `audio_file`, `file_path`,`status`, `created_on`) VALUES ('$user_id','$merchant_id','$table_type','$mobile_number','$location','$section_type','$filename','$file_path','$status','$created_on')";
			$result 	= mysqli_query($conn, $sql);
			$order_id   = mysqli_insert_id($conn); 
			$res['error']	= true;
			$res['message'] = "Your order has been placed Successfully.";
			$res['data'] 	= $order_id;
			$res['user_type'] = $login_user_role;
		}else{
			$res['error']	= false;
			$res['message'] = "Order not placed.";
		}
	}
	else{
		$res['error']	= false;
		$res['message'] = "There was an error uploading the file, please try again!";
	}
	die(json_encode($res));		  
}
?>