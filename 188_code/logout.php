<?php
include('config.php');
extract($_POST);
// print_R($_POST);
if($_SESSION['login'])
{   

  // echo $logout_type;
if($logout_type && isset($_SESSION['login']))
{
	 $id=$_SESSION['login'];
	// q
	if($id=='')
		$id=$_POST['user_id'];
if($logout_type=="shop_close" || $logout_type=="button")
	{
		// $id=$_SESSION['login'];
	     $sql = "UPDATE users SET shop_open = '0',active_login='n' WHERE id = '$id'";	
	   // die;   
	   mysqli_query($conn,$sql);
	  
	}
	if($logout_type=="shift_close")
	{
		// $id=$_SESSION['login'];
	   $sql2 = "UPDATE user_login SET logout_time = '$dateutc',is_active='n' WHERE user_id = '$id' and is_active='y'";	
	   $sql = "UPDATE users SET last_login = '$dateutc',active_login='n' WHERE id = '$id'";	   
	   $query="UPDATE cash_system SET is_active= 'n',logout_time='$dateutc' WHERE user_id = '$id' and is_active='y'";  
	   mysqli_query($conn,$query);
	   mysqli_query($conn,$sql);
	   mysqli_query($conn,$sql2);   
	  
	}
}    
      
// $login_user_role=$_SESSION['login_user_role'];      

}
else
{
	header("Location: ".$site_url."/login.php");
	die;   
}
session_destroy();         
 $res = array('status'=>true);
 echo json_encode($res);
  // header("Location: ".$site_url."/login.php");
	die; 	
?>
