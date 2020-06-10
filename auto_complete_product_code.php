<?php 
$searchTerm = $_GET['term'];
	if($searchTerm)
	{
	include("config.php");
     $user_id = $_SESSION['mm_id'];
	 
	function ceiling($number, $significance = 1)
	{
		return ( is_numeric($number) && is_numeric($significance) ) ? (ceil(round($number/$significance))*$significance) : false;
	}
	 $merchant_detail = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SQL_NO_CACHE * FROM users WHERE id='$user_id' and user_roles='2'")); 
		$hike_per=$merchant_detail['price_hike'];  
	// $hike_per = $_SESSION['price_hike'];
	// $hike_rate="y";
	
	$select =mysqli_query($conn,"SELECT * FROM products WHERE user_id = '$user_id' AND product_type LIKE '%".$searchTerm."%' ");
	$data = array();
	while ($row=mysqli_fetch_assoc($select)) {
		$product_price=$row['product_price'];
		if($hike_per)
		{
			$new_price_direct = (($hike_per / 100) * $product_price)+$product_price;
			$new_price=ceiling($new_price_direct,0.05);
			
		}
		else
		{
		  $new_price=$row['product_price'];	
		}
		$new_price=number_format($new_price,2);
		$item = array('product_discount'=>$row['product_discount'],'id' => $row['id'], 'value' => $row['product_type'], 'price' => $new_price, 'name' => $row['product_name'], 'remark' => $row['remark']);
		array_push($data, $item);
	}   
	echo json_encode($data);  
	}
?>