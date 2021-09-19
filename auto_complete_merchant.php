<?php

include("config.php");
/*$searchTerm = $_['keyword'];
$select =mysqli_query($conn,"SELECT * FROM users WHERE name LIKE '%".$searchTerm."%' and user_roles=2");
while ($row=mysqli_fetch_assoc($select)) 
{
 $data[] = $row['name'];
}

echo json_encode($data);*/

$searchTerm = $_GET['term'];
$city = $_GET['city_name'];
$c_state = $_GET['c_state'];
$where_city = "";
$where_m_state = "";
if($city != ''){
	$where_city = " and city = '".$city."' ";
} 
if($c_state != ''){
	$where_m_state = " and m_state = '".$c_state."' ";
} 
$select =mysqli_query($conn,"SELECT * FROM users WHERE name LIKE '%".$searchTerm."%' and user_roles=2 ".$where_city." ".$where_m_state." ");

//echo "SELECT * FROM users WHERE name LIKE '%".$searchTerm."%' and user_roles=2   ".$where_city."  ".$where_m_state."";

//echo "SELECT * FROM users WHERE name LIKE '%".$searchTerm."%' and user_roles=2 and city = '".$city."' ";
$num_rows = mysqli_num_rows($select);
if($num_rows > 0){
?>
<ul id="country-list">
<?php while ($row=mysqli_fetch_assoc($select)) 
{
	$data[] = $row['name'];
?>
	<li onClick="selectCountry('<?php echo $row["name"]; ?>');"><?php echo $row["name"]; ?></li>
<?php
}
?>
</ul>
<?php 
}else{
}
//echo json_encode($data);
//exit;
?>





