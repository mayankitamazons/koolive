<?php
include('config.php');
// session_start();
if(isset($_GET['language'])){
	$_SESSION["langfile"] = $_GET['language'];
} 
if (empty($_SESSION["langfile"])) { $_SESSION["langfile"] = "english"; }
    require_once ("languages/".$_SESSION["langfile"].".php");
extract($_POST);
// print_r($_SESSSION);
// DIE;
$distance="y";

if($stype=="fav")

{

  if($latitude && $longitude)
	{
		$q="SELECT SQL_NO_CACHE users.id as user_id,users.mobile_number,users.name, favorite_id, users.latitude, users.longitude, users.account_type,
      (6371 * ACOS ( COS ( RADIANS (".$_POST['latitude'].")) * COS ( RADIANS(users.latitude)) * COS(RADIANS(users.longitude) - RADIANS(".$_POST['longitude']."))
       + SIN(RADIANS(".$_POST['latitude'].")) * SIN(RADIANS(users.latitude)))) AS distance
    FROM favorities
    inner  join users on favorities.favorite_id=users.id
    where  user_id='$user_id' AND (users.service_id = '$type') AND users.latitude!='' and users.longitude!='' order by distance asc limit 0,5";
	}
	else
	{
		$q="SELECT SQL_NO_CACHE users.id as user_id,users.mobile_number,users.name, favorite_id, users.latitude, users.longitude, users.account_type
                             FROM favorities INNER JOIN users ON favorities.favorite_id = users.id
                             WHERE user_id='$user_id' AND (users.service_id = '$type') order by users.name asc limit 0,5";
		$distance="n";
		
	}
	// echo $q;
	// die;
}else if($stype=="nearby")
{
	if($latitude && $longitude)
	{
		$q="select SQL_NO_CACHE users.name,users.mobile_number,users.id as user_id,(6371 * ACOS ( COS ( RADIANS (".$_POST['latitude'].")) * COS ( RADIANS(users.latitude)) * COS(RADIANS(users.longitude) - RADIANS(".$_POST['longitude']."))
       + SIN(RADIANS(".$_POST['latitude'].")) * SIN(RADIANS(users.latitude)))) AS distance from users 
	   where service_id='$type' and user_roles='2' and isLocked='0' AND users.latitude!='' and users.longitude!='' order by distance asc limit 0,20";
	}  
	else
	{
		$q="select users.name,users.mobile_number,users.id as user_id from users where service_id='$type' and user_roles='2' and isLocked='0' order by name asc limit 0,20";
		$distance="n";
	}
}
	
	// die;
	$query=mysqli_query($conn,$q);
	$total_row=mysqli_num_rows($query);
	if($total_row>0)
	{	
 ?>	
 <tr>
 <th><?php echo $language['no']; ?></th>
					<th><?php echo $language['name']; ?></th>
					<!--th><?php echo $language['mobile_number']; ?></th!-->
<?php if($distance=="y"){ ?>
<th><?php echo $language['distance']; ?></th>
<?php } ?>
 </tr>
   <?php $j=1;while($fd = mysqli_fetch_assoc($query)){
     $m_url="structure_merchant.php?merchant_id=".$fd['user_id'];
	   ?>
   <tr>>
    <td><?php echo $j; ?></td>
    <td><a style="color:#51d2b7;" href="<?php echo $m_url; ?>"><?php echo $fd['name']; ?></a></td>
    <!--td><?php echo $fd['mobile_number']; ?></td!-->
	<?php if($distance=="y"){ ?>
    <td><?php echo number_format($fd['distance'],2)." KM"; ?></td>
	<?php } ?>
   </tr>
<?php $j++;} } else { echo "No Merchant Found";}  ?>