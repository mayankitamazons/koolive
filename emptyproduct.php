<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
   include("config.php");
   if(isset($_POST['submit']))
   {
   
    if(!empty($_POST['main_merchant']))
    {
		
       extract($_POST);
	   $query = mysqli_query($conn,"select * from users  where id='$main_merchant'");
	   $rowcount=mysqli_num_rows($query);
	   if($rowcount>0){
		if($cat_mater=="cat_mater")
		{
			$query = mysqli_query($conn,"DELETE FROM cat_mater WHERE UserID=$main_merchant");
		}
		if($category=="category")
		{
			$query = mysqli_query($conn,"DELETE FROM category WHERE user_id=$main_merchant");
		}
		if($arrange_system_category=="arrange_system_category")
		{
			$query = mysqli_query($conn,"DELETE FROM arrange_system WHERE user_id=$main_merchant and page_type='c'");
		}
		if($products=="products")
		{
			$query = mysqli_query($conn,"DELETE FROM products WHERE user_id=$main_merchant");
		}
		if($arrange_system_product=="arrange_system_product")
		{
			$query = mysqli_query($conn,"DELETE FROM arrange_system WHERE user_id=$main_merchant and page_type='p'");
		}
		echo "Selected Record Deleted";
	   }
	   else{
		   echo "Wrong Merchant id";
	   }
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
      <div class="container" style="width:50%;text-align:center;">
         <h2>Permanent Delete Data from Merchant </h2>
         <form method="post" action=''>
            <div class="form-group">
               <label for="email">Main Merchant:</label>
               <input type="text" class="form-control" required name="main_merchant" placeholder="Enter Merchant User id">
            </div>
            <div class="checkbox">
               <label><input type="checkbox" checked  name="cat_mater" value="cat_mater">Master Category</label>
            </div>
			<div class="checkbox">
               <label><input type="checkbox" checked  name="category" value="category">Category</label>
            </div>
			<div class="checkbox">
               <label><input type="checkbox" checked  name="arrange_system_category" value="arrange_system_category">Arrange System for Category</label>
            </div>
			<div class="checkbox">
               <label><input type="checkbox" checked  name="products" value="products">Product Master</label>
            </div>
			<div class="checkbox">
               <label><input type="checkbox" checked  name="arrange_system_product" value="arrange_system_product">Arrange System for Product</label>
            </div>
            <small><b>Note : this tool delete all selected data permantly </b></small>
            <input type="submit" class="btn btn-block btn-primary" name="submit" value="delete">
         </form>
      </div>
   </body>
</html>