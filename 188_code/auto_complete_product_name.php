<?php 
	include("config.php");
	function ceiling($number, $significance = 1)
	{
		return ( is_numeric($number) && is_numeric($significance) ) ? (ceil(round($number/$significance))*$significance) : false;
	}
	 // $hike_per=5; 
	// $hike_rate="y";
    $user_id = $_SESSION['mm_id'];
    $hike_per = $_SESSION['price_hike'];
	$searchTerm = $_GET['term'];
	// echo "SELECT * FROM products WHERE user_id = '$user_id' AND product_name LIKE '%".$searchTerm."%' ";
	// die;
	$select =mysqli_query($conn,"SELECT * FROM products WHERE user_id = '$user_id' AND product_name LIKE '%".$searchTerm."%' ");
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
		$item = array('product_discount'=>$row['product_discount'],'id' => $row['id'], 'value' => $row['product_name'], 'price' => $new_price, 'code' => $row['product_type'], 'remark' => $row['remark']);
		array_push($data, $item);       
	}    
	echo json_encode($data);   
?>