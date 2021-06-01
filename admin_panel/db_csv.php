<?php 
include("config.php");
 
if(!isset($_SESSION['admin']))
{
	header("location:login.php");
}

header('Content-Type: text/html; charset=UTF-8');
error_reporting(0);
ini_set('memory_limit', '-1'); // unlimited memory limit
ini_set('max_execution_time', 3000);

$a_m="foodpanda";

if(isset($_POST['addCat'])){
	extract($_POST);
	$csv_file = '';
	if(isset($_FILES['image'])){
		$errors= array();
		$file_name = $_FILES['image']['name'];
		$file_size =$_FILES['image']['size'];
		$file_tmp =$_FILES['image']['tmp_name'];
		$file_type=$_FILES['image']['type'];
		$file_ext=strtolower(end(explode('.',$_FILES['image']['name'])));
		
		$extensions= array("csv");
		if($file_name == ''){
			$errors[]="Please upload csv file";
		}else if(in_array($file_ext,$extensions)=== false){
		   $errors[]="Extension not allowed, please choose only .csv file.";
		}/*else if($file_size > 2097152){
		   $errors[]='File size must be excately 2 MB';
		}*/
		//echo $file_size;
		//echo '<br/>';
		if(empty($errors)==true){
			$path   =   $_SERVER['DOCUMENT_ROOT'].'/admin_panel/fpcsv/';
			$name_file = date('Ymdhis');
			$ext = end(explode(".", $_FILES["image"]["name"]));
			$csv_file = $name_file.".".$ext;
			
			$readcsv = $path.$csv_file;
			
			if (move_uploaded_file($file_tmp, $path.$csv_file)) {
				$row = 1;
				$merchantArray = array();
				
				$q="INSERT INTO `tbl_dbcsv` (`d_filename`, `d_savedfilename`, `d_createddate`)VALUES ( '".$file_name."','".$csv_file."','".date('Y-m-d h:i:s')."')";
				$insert=mysqli_query($conn,$q);
				/* start: read csv */
				
				if (($handle = fopen($readcsv, "r")) !== FALSE) {
					while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
						$num = count($data);
						if($data[0] != ''){
							$data28 = explode("|",trim($data[28]));
							$merchantArray[$data[2]]['name'] = $data[2];
							$merchantArray[$data[2]]['foodpanda_link'] = $data[3];
							$merchantArray[$data[2]]['Address'] = $data[4];
							$merchantArray[$data[2]]['postcode'] = $data[18];
							$merchantArray[$data[2]]['telephone'] = $data[17];
							$merchantArray[$data[2]]['working_text'] = $data[9];
							$merchantArray[$data[2]]['working_text_chiness'] = $data[10];
							$merchantArray[$data[2]]['banner_image'] = $data[13];
							$merchantArray[$data[2]]['longitude'] = $data[14];
							$merchantArray[$data[2]]['latitude'] = $data[15];
							$merchantArray[$data[2]]['tags'] = $data[16];
							$merchantArray[$data[2]]['products'][$row]['category'] = $data[20];
							$merchantArray[$data[2]]['products'][$row]['name'] = $data[22];
							$merchantArray[$data[2]]['products'][$row]['remark'] = $data[23];
							$merchantArray[$data[2]]['products'][$row]['product_price'] = $data[24];
							$merchantArray[$data[2]]['products'][$row]['product_image'] = $data[26];
							$merchantArray[$data[2]]['products'][$row]['product_variant'] = array_map('trim',$data28);
						}
					$row++;
					}
				  fclose($handle);
				}
				echo '<pre>';
				print_R($merchantArray);
				exit;
				if(count($merchantArray) > 0){
					
					//echo '<pre>';
					//print_R($merchantArray);
					$s = 1;
					foreach($merchantArray as $key => $value){
						$m_name = $value['name'];
						$mobile_number = $value['telephone'];
						if($mobile_number == ''){
							$mobile_number = rand();
						}
						$user_roles = 2;
						$city = $_POST['city_name'];
						$banner_image = $value['banner_image'];
						$address = $value['Address'];
						$google_map = $value['Address'];
						$latitude = $value['latitude'];
						$longitude = $value['longitude'];
						$created_at = date('Y-m-d');
						$working_text = $value['working_text'];
						$working_text_chiness = $value['working_text_chiness'];
						$foodpanda_link	 = $value['foodpanda_link'];

						//insert merchants in users table
						$user_query = "INSERT INTO users (`name`,`mobile_number`,`user_roles`,`banner_image`,`address`,`google_map`,`latitude`,`longitude`,`created_at`,`working_text`,`working_text_chiness`,`foodpanda_link`,`csv_import`,`city`,`isLocked`,`show_merchant`)
						VALUES ('".$m_name."','".$mobile_number."','".$user_roles."','".$banner_image."','".$address."','".$google_map."','".$latitude."','".$longitude."','".$created_at."','".$working_text."','".$working_text_chiness."','".$foodpanda_link."','1','".$city."',0,1)";
						$insert_users = mysqli_query($conn,$user_query);	
						$lastuser_id = mysqli_insert_id($conn);
						
						//echo '<pre>';
						//print_R($value['products']);
						
						if(count($value['products']) > 0){
							foreach($value['products'] as $p_key => $p_value){
								$category = $p_value['category'];
								$cate_query = mysqli_query($conn,"SELECT * FROM `category` where user_id = '".$lastuser_id."' and category_name = '".$category."'");
								$totalcat_rows = mysqli_num_rows($cate_query);
								$cate_data = mysqli_fetch_assoc($cate_query);
								$lastcategory_id =  $cate_data['id'];
								if($totalcat_rows == 0){
									//add category
									$cat_insert = "INSERT INTO category(`user_id`,`category_name`,`status`,`catparent`,`created_date`) VALUES('".$lastuser_id."','".$category."',0,1,'".date('Y-m-d')."')";
									$insert_category = mysqli_query($conn,$cat_insert);	
									$lastcategory_id = mysqli_insert_id($conn);
								}
								
								$product_name = $p_value['name'];
								$product_remark = $p_value['remark'];
								$product_price = str_replace("MYR","",$p_value['product_price']);
								$product_price = str_replace("from","",$product_price);
								$product_image = $p_value['product_image'];
								//check varients exists or not
								if(count($p_value['product_variant']) > 1){
									$varient_exit = 'y';
								}else{
									$varient_exit = 'n';
									$ex_varients1 = explode(",", $p_value['product_variant']);
									if($ex_varients1[0] == ''){
										$varient_exit = 'n';
									}else{
										$varient_exit = 'y';
									}
										
								}
								
								$product_query = "INSERT INTO products (`user_id`,`product_name`,`category`,`product_price`,`image`,`status`,`created_date`,`category_id`,`varient_exit`) VALUES ('".$lastuser_id."','".$product_name."','".$category."','".$product_price."','".$product_image."',0,'".date('Y-m-d')."','".$lastcategory_id."','".$varient_exit."')";
								$insert_product = mysqli_query($conn,$product_query);	
								$lastproduct_id = mysqli_insert_id($conn);
								
								
								//save data in sub_products (varients)
								if(count($p_value['product_variant']) >0 ){
									foreach($p_value['product_variant']  as $v_key => $v_value){
										$ex_varients = explode(",", $v_value);
										if($ex_varients[0] == ''){
											$varient_exit = 'n';
										}else{
											$varient_exit = 'y';
											$varient_name = $ex_varients[0];
											$varient_price = str_replace("Price:","",$ex_varients[1]);
											$only_varients_price = trim($varient_price) - $product_price;
											//echo $product_price."====".$varient_price."====".$only_varients_price;
											
											$subproducts_query = "INSERT INTO sub_products (`product_id`,`name`,`product_price`,`status`,`created_date`,`merchant_id`) values('".$lastproduct_id."','".$varient_name."','".$only_varients_price."',1,'".date('Y-m-d h:i:s')."','".$lastuser_id."')";
											$insert_subproduct = mysqli_query($conn,$subproducts_query);	
										}

										//echo '<pre>';
										///print_R($p_value['product_variant']);
										//print_r($ex_varients);
								
									}
									//exit;
								}
								
								
								
								
								//echo '<br/>';
							}
						}
						
						
					$s++;}
				}

				/* end: read csv*/	
			
			
			
			} 
			  
			unset($_SESSION['errors_fp']); 
			$_SESSION['success_fp'] = count($merchantArray).' Merchants added successfully!!';  
			header("location:import_csv.php?mode=post");
		   
		   
		}else{
		   print_r($errors);
		   unset($_SESSION['success_fp']); 
		   $_SESSION['errors_fp'] = $errors;
		   header("location:import_csv.php?mode=post");
		}
	 }
	
}
?>