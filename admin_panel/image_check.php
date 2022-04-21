<?php 
include("config.php");
$sql = "select GROUP_CONCAT(id) as user_id FROM `users` WHERE user_roles = 2 and banner_image != ''";
$row = mysqli_fetch_assoc(mysqli_query($conn,$sql));
$user_id = $row['user_id'];
$userArray = explode(",",$user_id );

$sql_about = "SELECT GROUP_CONCAT(id) as about_user_id  FROM `about` WHERE `userid` IN (".$user_id.")";
$row1 = mysqli_fetch_assoc(mysqli_query($conn,$sql_about));
$about_id = $row1['about_user_id'];
$userAboutArray = explode(",",$about_id );

$sql11 = "select * FROM `users` WHERE user_roles = 2 and banner_image != ''";
$row111 =mysqli_query($conn,$sql11);

//foreach($userArray as $ukey =>$u_value){
$ukey = 0;
while($row_wh=mysqli_fetch_assoc($row111)){
	$u_value = $row_wh['id'];
	$sql_about1 = "SELECT *  FROM `about` WHERE `userid` =".$u_value;
	$row11 = mysqli_fetch_assoc(mysqli_query($conn,$sql_about1));
	if($row11['userid']){
		$inAboutArray[$ukey] = $u_value;
	}else{
		$NOtinAboutArray[$ukey] = $u_value;
		$about_user ="INSERT INTO `about` (`id`, `userid`, `description`, `link`, `welcome_note`, `image`, `video_upload`, `xlsx_upload`) VALUES (NULL, '".$u_value."', ' ', '', ' ', '".$row_wh['banner_image']."', '', '1');";
		echo $about_user;
		echo '<br/>';
		//$abouts_users = mysqli_query($conn,$about_user);	
	}
	$ukey++;
}

echo 'inAboutArray'.count($inAboutArray);
echo '<br/>';
echo 'NOtinAboutArray'.count($NOtinAboutArray);
/*
echo '<pre>';
print_r($inAboutArray);
print_r($NOtinAboutArray);
print_R($userArray);
*/

?>