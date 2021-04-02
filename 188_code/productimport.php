<?php
include('config.php');
$row = 1;
$m_id=11416;
if (($handle = fopen("g3.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {   
       $p_no=$data[0];
             $p_name=$data[1];
			
			if($p_no && $p_name && $p_name!='Product Name')
			{
				$p_name=$data[1];
				$p_remark=$data[2];
				$unit_price=$data[3];
				$mas_cat=$data[5];
				$sub_cat=$data[4];  
				$sub_cat_name=$data[6];  
				echo $query="INSERT INTO `products` (`user_id`, `product_name`, `category`, `product_type`, `product_price`,`remark`,`category_id`) 
				VALUES ('$m_id', '$p_name', '$sub_cat_name', '$p_no', '$unit_price', '$p_remark', '$sub_cat')";
				$insert=mysqli_query($conn,$query);
				if($insert)
				$row++;
				echo "</br>";
			}
			
			
           
        }  
		
		
        
    fclose($handle);
	echo "Total Record inserted. ".$row;
	die;
}    
?>