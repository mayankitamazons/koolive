<?php
include("config.php"); 
$pr_code = $_POST['pr_code'];
$p_name = $_POST['p_name'];
function ceiling($number, $significance = 1)
	{
		return ( is_numeric($number) && is_numeric($significance) ) ? (ceil(round($number/$significance))*$significance) : false;
	}
if($pr_code)
{
	$search=$pr_code;
}
if($p_name)
{
	$search=$p_name;
}
if($search && $search!='+')
{
 $user_id = $_SESSION['mm_id'];
$product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE user_id='$user_id'  and product_type ='".$search."'"));	
$merchant_detail = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SQL_NO_CACHE * FROM users WHERE id='$user_id' and user_roles='2'")); 

$hike_per=$merchant_detail['price_hike'];  

$product_price=$product['product_price'];
		if($hike_per)
		{
			$new_price_direct = (($hike_per / 100) * $product_price)+$product_price;
			$new_price=ceiling($new_price_direct,0.05);
			
		}
		else
		{
		  $new_price=$product['product_price'];	
		}
		$new_price=number_format($new_price,2);   
$product['product_price']=$new_price;   
echo json_encode($product);
}
?>