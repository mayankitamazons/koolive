<?php
      include("config.php");
   if(isset($_POST['submit']))
   {
	  
	   if($_POST['main_merchant'] && $_POST['child_merchant'])
	   {
		    // print_R($_POST);
			// die;
		   // catgry copy 
			$m_id=$_POST['main_merchant'];
			$c_id=$_POST['child_merchant'];
			//  cat master copy
			$query = mysqli_query($conn,"select SQL_NO_CACHE * from cat_mater  where UserID='$m_id'");
			$query2 = mysqli_query($conn,"select SQL_NO_CACHE * from cat_mater  where UserID='$c_id'");
			$master_cat_master=mysqli_fetch_array($query);
				// print_R($master_cat_master);
				// die;
			$chid_cat_master=mysqli_fetch_array($query2);
			$cat_str=$chid_cat_master['CatName'];
			$CMasterID=$chid_cat_master['CMasterID'];
			 $update_cat_str=$cat_str.",".$master_cat_master['CatName'];
			    $u="update cat_mater set CatName='$update_cat_str' where CMasterID='$CMasterID'";
			// die;   
				$update_cat_str=mysqli_query($conn,$u);
			$query = mysqli_query($conn,"select  SQL_NO_CACHE* from category  where user_id='$m_id' and catparent='1' and status='0' and id 
			in(select entity_id from arrange_system where user_id='$m_id' and page_type='c')");
			while ($row=mysqli_fetch_assoc($query)){   
				$cat_id=$row['id'];
				
				$category_name=$row['category_name'];
				$catparent=$row['catparent'];
				$catparent_name=$row['catparent_name'];
				$status=$row['status'];
				// $created_date=$row['created_date'];
				$created_date=date('Y-m-d h:i:s');
				mysqli_query($conn, "INSERT INTO  category SET common_category='y',category_name='$category_name', catparent='4', user_id='$c_id',status= '0',created_date='$created_date'");
	
			}
			//product copy 
			$query = mysqli_query($conn,"select  SQL_NO_CACHE* from products  where user_id='$m_id' and status='0' and id 
			in(select entity_id from arrange_system where user_id='$m_id' and page_type='p')");   
			while ($row=mysqli_fetch_assoc($query)){   
				$cat_id=$row['id'];
				// $user_id=$row['user_id'];
				$productname=$row['product_name'];
				$varient_exit=$row['varient_exit'];
				$varient_must=$row['varient_must'];
				$product_id=$row['id'];
				$category=$row['category'];
				if($category)
					 {
						// echo "SELECT id FROM category WHERE user_id ='".$loginidset."' and category_name ='".$category."'";
						// die;
						$categories = mysqli_query($conn, "SELECT SQL_NO_CACHE id FROM category WHERE status='0' and user_id ='".$c_id."' and category_name ='".$category."'");
						$categoryrow=mysqli_fetch_assoc($categories);
						$category_id=$categoryrow['id'];
					 }
				$product_type=$row['product_type'];
				$product_price=$row['product_price'];
				$remark=$row['remark'];
				$image=$row['image'];
				$current_date=$row['current_date'];
				$print_ip_address=$row['print_ip_address'];
				$printer_ip_2=$row['printer_ip_2'];
				$printer_profile=$row['printer_profile'];
				$usb_name=$row['usb_name'];
				  $in="INSERT INTO products SET varient_must='$varient_must',varient_exit='$varient_exit',product_name='$productname',user_id='$c_id', category='$category',category_id='$category_id',product_type='$product_type',product_price='$product_price', remark = '$remark', image='$image', code='$code',created_date='$current_date',print_ip_address='$print_ip_address',printer_ip_2='$printer_ip_2',printer_profile='$printer_profile',usb_name='$usb_name'";
				
				mysqli_query($conn,$in);
				// die;
				if($varient_exit=="y")
				{
					
					$new_product_id = mysqli_insert_id($conn);
        
				   //sub product copy
					$query2 = mysqli_query($conn,"select SQL_NO_CACHE* from sub_products  where product_id='$product_id'");
					while ($row2=mysqli_fetch_assoc($query2)){
						
						$p_name=$row2['name'];
						$product_type=$row2['product_type'];
						$product_price=$row2['product_price'];
						$status=$row2['status'];
						// $merchant_id=$row2['merchant_id'];
						mysqli_query($conn, "INSERT INTO sub_products SET product_id='$new_product_id',name='$p_name', product_type='$product_type',product_price='$product_price',product_type='$product_type',status='$status', merchant_id = '$c_id'");

					}
				
				
			}
			}   
			// auto fill arrange system too 
			$total_rows = mysqli_query($conn, "SELECT SQL_NO_CACHE * FROM category WHERE user_id ='$c_id' and common_category='y' and status=0");
			$total_num_rows = mysqli_num_rows($total_rows);
			if($total_num_rows>0)
			{
				// $category_id=$catdata['id'];    
				
				$shift_pos=1;
				while ($row1=mysqli_fetch_assoc($total_rows)){   
					// print_R($row);
					// die;
					$entity_id=$row1['id'];
					$category_id=$row1['id'];
					$cat_name=$row1['category_name'];
					$query="delete from arrange_system where entity_id='$entity_id' and page_type='c' and user_id='$c_id'";
					// die;
					$listq = mysqli_query($conn,$query);
					// copy category postion from past merchant 
					$match_cat="select SQL_NO_CACHE a.shift_pos from category inner join arrange_system as a on a.entity_id=category.id 
					where category.category_name='$cat_name' and category.user_id='$m_id'";
					$match_q=mysqli_query($conn,$match_cat);
					$past_pos_data=mysqli_fetch_assoc($match_q);
					$past_pos=$past_pos_data['shift_pos'];
					$q="INSERT INTO arrange_system(id,entity_id,user_id,shift_pos,page_type,status,category_id) VALUES (NULL, '$entity_id', '$c_id', '$past_pos', 'c', 'active','$category_id')";
					mysqli_query($conn,$q);
					$shift_pos++;
					$total_rows_pro = mysqli_query($conn, "SELECT SQL_NO_CACHE* FROM products WHERE user_id ='".$user_id."' and category_id='$category_id' and status=0 order by product_name asc");
					$total_num_rows_pro = mysqli_num_rows($total_rows_pro);
					if($total_num_rows_pro>0)
					{
						$listq = mysqli_query($conn,"delete from arrange_system where category_id='$category_id' and page_type='p' and user_id='".$c_id."'");
						$shift_pos_p=1;
						
						while ($row2=mysqli_fetch_assoc($total_rows)){
							$entity_id=$row2['id'];
							$pro_name=$row2['product_name'];
							$match_pro="select SQL_NO_CACHE a.shift_pos from products inner join arrange_system as a on a.entity_id=products.id 
							where products.product_name='$product_name' and products.user_id='$m_id'";
							$match_q=mysqli_query($conn,$match_pro);
							$past_pos_data=mysqli_fetch_assoc($match_q);
							$past_pos=$past_pos_data['shift_pos'];
							$q="INSERT INTO arrange_system(id,entity_id,user_id,shift_pos,page_type,status,category_id) VALUES (NULL, '$entity_id', '$c_id', '$past_pos', 'p', 'active','$category_id')";
							mysqli_query($conn,$q);
							$shift_pos_p++;
						}
						echo "product updated ";   
					}  
				}
				
				echo "Category updated ";
				
			}
			
			echo "Data Copy Successfully";  
			
	   
   }
   }
?>   
<!DOCTYPE html>    
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
  <h2>Copy Single Master From One Merchant to other</h2>
  <form method="post" action=''>
   
    <div class="form-group">
      <label for="pwd">From Merchant:</label>
     <input type="text" class="form-control" name="main_merchant" placeholder="Enter Merchant User id">
    </div>
	 <div class="form-group">
      <label for="email">To Merchant:</label>
      <input type="text" class="form-control"  name="child_merchant" placeholder="Enter Merchant User id">
    </div>
    <small><b>Note : That tool copy Category , Product , Sub Product </b></small>
    <input type="submit" class="btn btn-block btn-primary" name="submit" value="Update Details">
  </form>
</div>

</body> 
</html>