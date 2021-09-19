<?php 
//include("config.php");
date_default_timezone_set("Asia/Kuala_Lumpur");
session_start();
//ini_set('max_execution_time', '3000'); // for infinite time of execution 
$conn = mysqli_connect("localhost", "koofamilies_user", "k00Family_deMo", "koofamilies_demo");
if($_POST['language_name'] == 1){
	@mysqli_query($conn,"SET CHARACTER SET 'latin1'"); //if langugae chinese
}
if(!$conn)
{
	echo "database error"; die;
}
 
if(!isset($_SESSION['admin']))
{
	header("location:login.php");
}

header('Content-Type: text/html; charset=utf-8');
//header('Content-Type: text/html; charset=latin1');
error_reporting(1);
ini_set('memory_limit', '-1'); // unlimited memory limit
ini_set('max_execution_time', 3000);

$a_m="foodpanda";
include('SimpleXLSX.php');
/*
$name_test = addslashes('Nando"s (AEON Tebrau City)') ;
$test_query = mysqli_query($conn,"INSERT INTO users (`name`,`mobile_number`,`user_roles`,`banner_image`,`address`,`google_map`,`latitude`,`longitude`,`created_at`,`working_text`,`working_text_chiness`,`foodpanda_link`,`csv_import`,`city`,`isLocked`,`show_merchant`)VALUES ('".$name_test."','1850601278','2','Screenshot 2021-06-07 130824','','','','','2021-06-08','','周一 - 周日 上午10:00 - 下午7:114','https://www.foodpanda.my/restaurant/new/m93dx/nandos-aeon-tebrau-city','1','Kulai',0,1)");
$last_id = mysqli_insert_id($conn);

echo $last_id;
exit;
*/


if(isset($_POST['addCat'])){
	extract($_POST);
	$csv_file = '';
	if(isset($_FILES['image'])){
		$errors= array();
		$file_name = $_FILES['image']['name'];
		$file_size = $_FILES['image']['size'];
		$file_tmp = $_FILES['image']['tmp_name'];
		$file_type = $_FILES['image']['type'];
		$file_ext = strtolower(end(explode('.',$_FILES['image']['name'])));
		
		$extensions= array("csv");
		
		if($file_name == ''){
			$errors[]="Please upload csv file";
		}/*else if(in_array($file_ext,$extensions)=== false){
		   $errors[]="Extension not allowed, please choose only .csv file.";
		}*//*else if($file_size > 2097152){
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
				
				$q = "INSERT INTO `tbl_dbcsv` (`d_filename`, `d_savedfilename`,`d_city`,`d_version`, `d_createddate`)VALUES ( '".$file_name."','".$csv_file."','".$_POST['city_name']."','".$_POST['language_name']."','".date('Y-m-d h:i:s')."')";
				
				//echo $q;
				//echo '<br/>';
				$insert = mysqli_query($conn,$q);
				$lastexcel_id = mysqli_insert_id($conn);
				/* start: read csv */
				/*
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
				*/

				//echo '<pre>';
				if ( $xlsx = SimpleXLSX::parse($readcsv) ) {
					$row = 0;
					foreach($xlsx->rows() as $key => $data){
						//print_R($data);
						//echo '<br/>----<br/>';
						$data28 = explode("|",trim($data[28]));  //AC
						$timing_array = explode("|",trim($data[30])); // AE
						//$mm_name = 
						if(!array_key_exists($data[2],$merchantArray)){
							$merchantArray[$data[2]]['name'] = $data[2]." (".$data[29].")";  //C- AD
							$merchantArray[$data[2]]['foodpanda_link'] = $data[3]; //D
							$merchantArray[$data[2]]['Address'] = $data[4]; // E
							$merchantArray[$data[2]]['working_text'] = $data[9]; // J
							$merchantArray[$data[2]]['working_text_chiness'] = $data[10]; //K 	
							$merchantArray[$data[2]]['banner_image'] = $data[13]; // N
							$merchantArray[$data[2]]['postcode'] = $data[18]; //
							$merchantArray[$data[2]]['telephone'] = $data[17]; //
							$merchantArray[$data[2]]['longitude'] = $data[14]; //
							$merchantArray[$data[2]]['latitude'] = $data[15]; // 
							$merchantArray[$data[2]]['m_state'] = $data[31]; // AF
							$merchantArray[$data[2]]['special_coin_name'] = $data[33]; // AH
							$merchantArray[$data[2]]['timing_array'] = array_map('trim',$timing_array);
						}
						$merchantArray[$data[2]]['tags'] = $data[16];
						
						$merchantArray[$data[2]]['category_array'][$data[32]]['main_category'] = $data[32]; //AG
						$merchantArray[$data[2]]['category_array'][$data[32]]['sub_category'][$data[20]] = $data[20]; //subcategory  //U
						
						
						$merchantArray[$data[2]]['products'][$row]['category'] = $data[20]; // U
						$merchantArray[$data[2]]['products'][$row]['name'] = $data[22]; // W
						$merchantArray[$data[2]]['products'][$row]['remark'] = $data[23]; //
						$merchantArray[$data[2]]['products'][$row]['product_price'] = $data[24]; //Y
						$merchantArray[$data[2]]['products'][$row]['product_image'] = $data[26]; // AA
						$merchantArray[$data[2]]['products'][$row]['product_variant'] = array_map('trim',$data28);
						
						
					$row++;	
					}
				}
				/*if(count($merchantArray['Tesco (Kulai)']['category_array']) > 0){
					$keys_category = array_keys($merchantArray['Tesco (Kulai)']['category_array']);
					$master_category = implode(",",$keys_category);
					echo $master_category;
				}
				echo '<pre>';
				print_R($merchantArray);
				exit;*/
				if(count($merchantArray) > 0){
					//echo '<pre>';
					//print_R($merchantArray);
					
					$s = 1;
					
					foreach($merchantArray as $key => $value){
						$m_name = addslashes($value['name']);
						$mobile_number = $value['telephone'];
						if($mobile_number == ''){
							$mobile_number = rand();
						}
						$user_roles = 2;
						$city = $_POST['city_name'];
						
						
						if($_POST['language_name'] == 2){
							//English Version
							if($value['banner_image'] != ''){
								$link_img1 = $value['banner_image'];
								$link_array1 = explode("/",$link_img1);
								$imgArrayCount1 = count($link_array1) - 1;
								$banner_image = $link_array1[$imgArrayCount1];
							}else{
								$banner_image = '';
							}
						}else{
							if($value['banner_image'] != ''){
								$banner_image = $value['banner_image'].".jpg";
							}else{
								$banner_image = '';
							}
						}
						
								
						
						
						$address = $value['Address'];
						$google_map = $value['Address'];
						$latitude = $value['latitude'];
						$longitude = $value['longitude'];
						$m_state = $value['m_state'];
						$special_coin_name = $value['special_coin_name'];
						
						$created_at = date('Y-m-d');
						$working_text = $value['working_text'];
						$working_text_chiness = $value['working_text_chiness'];
						$foodpanda_link	 = $value['foodpanda_link'];

						//insert merchants in users table
						$user_query = "INSERT INTO users (`user_language`,`name`,`mobile_number`,`user_roles`,`image`,`banner_image`,`address`,`google_map`,`latitude`,`longitude`,`created_at`,`working_text`,`working_text_chiness`,`foodpanda_link`,`csv_import`,`city`,`m_state`,`isLocked`,`show_merchant`,`csv_id`,`special_price_value`,`cash_on_delivery`,`table_exit`,`delivery_address_exit`,`special_coin_name`)
						VALUES ('english','".$m_name."','".$mobile_number."','".$user_roles."','".$banner_image."','".$banner_image."','".$address."','".$google_map."','".$latitude."','".$longitude."','".$created_at."','".$working_text."','".$working_text_chiness."','".$foodpanda_link."','1','".$city."','".$m_state."',0,1,'".$lastexcel_id."',0,1,0,1,'".$special_coin_name."')";
						
						//echo $s."===========".$user_query;
						//echo '<br/>';
						$insert_users = mysqli_query($conn,$user_query);	
						$lastuser_id = mysqli_insert_id($conn);
						
						//echo '<pre>';
						//print_R($value['timing_array']);
						
						
						//save data in timings
								if(count($value['timing_array']) > 0){
									foreach($value['timing_array']  as $t_key => $t_value){
										$ts_one = explode(",",$t_value);
										
										//times array		
										$timess_array = $ts_one[1];
										$ts_two = explode("-",$timess_array);
										//$start_time = str_replace("am","",trim($ts_two[0]));
										//$end_time = str_replace("pm","",trim($ts_two[1]));
										$pos_starttime = substr(trim($ts_two[0]), 0, 2); //am or pm
										$pos_endtime = substr(trim($ts_two[1]), 0, 2); //am or pm
										
										$start_time = str_replace($pos_starttime,"",trim($ts_two[0]));
										$end_time = str_replace($pos_endtime,"",trim($ts_two[1]));
										
										if (preg_match("/pm/", $end_time)){
											$pos_endtime = 'pm';
											$end_time = str_replace("pm","",$end_time);
											$end_time = str_replace(" ","",$end_time);
										}
										if (preg_match("/pm/", $start_time)){
											$pos_starttime = 'pm';
											$start_time = str_replace("pm","",$start_time);
											$start_time = str_replace(" ","",$start_time);
										}
										if (preg_match("/am/", $end_time)){
											$end_time = str_replace("am","",$end_time);
											$end_time = str_replace(" ","",$end_time);
										}
										if (preg_match("/am/", $start_time)){
											$start_time = str_replace("am","",$start_time);
											$start_time = str_replace(" ","",$start_time);
										}

										
										if($pos_starttime == 'pm'){
											//$start_time  = $start_time + 12;
											$start_time = trim($start_time);
											$start_time1 = $start_time."pm";//strtotime($start_time) + 60*60*12;
											$start_time = date('H:i', strtotime(($start_time1)));
										}
										if($pos_endtime == 'pm'){
											//$end_time  = $end_time + 12;
											$end_time = trim($end_time);
											$end_time1 = $end_time."pm";//strtotime($end_time) + 60*60*12;
											$end_time = date('H:i', strtotime($end_time1));
											
										}
										
										//echo $start_time1."==========".$end_time1;
										//echo '<br/>';
										//echo $pos_starttime."==========".$pos_endtime;
										////echo '<br/>';
										//echo $start_time."==========".$end_time;
										
										
										//echo '<br/>';
										$days_array = $ts_one[0];
										$ds_two = explode("-",$days_array);
										//echo '<pre>';
										//print_R($ds_two);
										//echo '<br/>';
										if(count($ds_two) > 1){
											$start_day = strtolower(trim($ds_two[0]));
											$end_day = strtolower(trim($ds_two[1]));
											$daykeyArray = array('mon'=>1,'tue'=>2,'wed'=>3,'thu'=>4,'fri'=>5,'sat'=>6,'sun'=>7);
											$dayNames = array(1=>'Monday',2=>'Tuesday', 3=>'Wednesday',4=>'Thursday',5=>'Friday', 6=>'Saturday', 7=>'Sunday',);
											
											$f_start_limit = $daykeyArray[$start_day];
											$f_end_limit = $daykeyArray[$end_day];
											
											for($d = $f_start_limit; $d <= $f_end_limit; $d++ ){
												$db_dayname = $dayNames[$d];
												$t_query = "INSERT INTO `timings` ( `merchant_id`, `day`, `start_time`, `end_time`,`csv_import`,`csv_id`) VALUES ('".$lastuser_id."', '".$db_dayname."', '".trim($start_time)."', '".trim($end_time)."',1,'".$lastexcel_id."')";
												//echo '<br/>';
												//echo '<br/>';
												$tt_data = mysqli_query($conn,$t_query);	
											}
										}
										if(count($ds_two) == 1){
											$start_day = strtolower(trim($ds_two[0]));
										$t_query = "INSERT INTO `timings` ( `merchant_id`, `day`, `start_time`, `end_time`,`csv_import`,`csv_id`) VALUES ('".$lastuser_id."', '".$start_day."', '".trim($start_time)."', '".trim($end_time)."',1,'".$lastexcel_id."')";
										//	echo '<br/>';
											$tt_data = mysqli_query($conn,$t_query);	
										}
										
									}
									
									/*foreach($value['timing_array']  as $t_key => $t_value){
										$ts_one = explode(",",$t_value);
										
										//times array		
										$timess_array = $ts_one[1];
										$ts_two = explode("-",$timess_array);
										$start_time = str_replace("am","",trim($ts_two[0]));
										$end_time = str_replace("pm","",trim($ts_two[1]));
										
										
										//echo '<pre>';
										//print_R($ts_two);
										//daysarray
										$days_array = $ts_one[0];
										$ds_two = explode("-",$days_array);
										$start_day = strtolower(trim($ds_two[0]));
										$end_day = strtolower(trim($ds_two[1]));
										
										$daykeyArray = array('mon'=>1,'tue'=>2,'wed'=>3,'thu'=>4,'fri'=>5,'sat'=>6,'sun'=>7);
										$dayNames = array(1=>'Monday',2=>'Tuesday', 3=>'Wednesday',4=>'Thursday',5=>'Friday', 6=>'Saturday', 7=>'Sunday',);
										
										$f_start_limit = $daykeyArray[$start_day];
										$f_end_limit = $daykeyArray[$end_day];
										
										//print_R($ts_one);
										

										for($d = $f_start_limit; $d <= $f_end_limit; $d++ ){
											$db_dayname = $dayNames[$d];
											$t_query = "INSERT INTO `timings` ( `merchant_id`, `day`, `start_time`, `end_time`,`csv_import`,`csv_id`) VALUES ('".$lastuser_id."', '".$db_dayname."', '".trim($start_time)."', '".trim($end_time)."',1,'".$lastexcel_id."')";
											//echo '<br/>';
											$tt_data = mysqli_query($conn,$t_query);	
										}
										
										
									
										
									}*/
								}
						
						//Add Main category with subcategory 
						if(count($value['category_array']) > 0){
							$keys_category = array_keys($value['category_array']);
							$master_category = implode(",",$keys_category);
							$add_mc_category = "INSERT INTO `cat_mater` (`CatName`,`UserID`,`IsEnable`,`DateAdded`,`csv_import`,`csv_id`) VALUES ('".$master_category."', '".$lastuser_id."', '1', '".date('Y')."',1,'".$lastexcel_id."')";
							$insert_mc_category = mysqli_query($conn,$add_mc_category);	
							$subcateArray = array();
							$i = 1;
							foreach($value['category_array'] as $mc_key => $mc_value){
								$subcateArray = $mc_value['sub_category'];
								if(count($subcateArray) > 0){
									foreach($subcateArray as $ss_key => $ss_value){
										$cat_insert = "INSERT INTO category(`user_id`,`category_name`,`status`,`catparent`,`created_date`,`csv_import`,`csv_id`) VALUES('".$lastuser_id."','".$ss_value."',0,'".$i."','".date('Y-m-d')."',1,'".$lastexcel_id."')";
										$insert_category = mysqli_query($conn,$cat_insert);	
										$lastcategory_id = mysqli_insert_id($conn);
									}
								}$i++;
							}
						}
						//END
						
						if(count($value['products']) > 0){
							foreach($value['products'] as $p_key => $p_value){
								$category = $p_value['category'];
								$cate_query = mysqli_query($conn,"SELECT * FROM `category` where user_id = '".$lastuser_id."' and category_name = '".$category."'");
								$totalcat_rows = mysqli_num_rows($cate_query);
								$cate_data = mysqli_fetch_assoc($cate_query);
								$lastcategory_id =  $cate_data['id'];
								if($totalcat_rows == 0){
									//add category
									$cat_insert = "INSERT INTO category(`user_id`,`category_name`,`status`,`catparent`,`created_date`,`csv_import`,`csv_id`) VALUES('".$lastuser_id."','".$category."',0,1,'".date('Y-m-d')."',1,'".$lastexcel_id."')";
									$insert_category = mysqli_query($conn,$cat_insert);	
									$lastcategory_id = mysqli_insert_id($conn);
								}
								
								$product_name = addslashes($p_value['name']);
								$product_remark = $p_value['remark'];
								$product_price = str_replace("MYR","",$p_value['product_price']);
								$product_price = str_replace("RM","",$product_price);
								$product_price = str_replace("from","",$product_price);
								
								
								if($_POST['language_name'] == 2){
									//English Version
									if($p_value['product_image'] != ''){
										$link_img = $p_value['product_image'];
										$link_array = explode("/",$link_img);
										$imgArrayCount = count($link_array) - 1;
										$product_image = "XLSX/".$link_array[$imgArrayCount];
									}else{
										$product_image = '';
									}
								}else{
									if($p_value['product_image'] != ''){
										$product_image = "XLSX/".$p_value['product_image'].".jpg";
									}else{
										$product_image = '';
									}
								}

								
								
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
								
								 $product_query = "INSERT INTO products (`user_id`,`product_name`,`category`,`product_price`,`image`,`status`,`created_date`,`category_id`,`varient_exit`,`csv_import`,`csv_id`) VALUES ('".$lastuser_id."','".$product_name."','".$category."','".$product_price."','".$product_image."',0,'".date('Y-m-d')."','".$lastcategory_id."','".$varient_exit."',1,'".$lastexcel_id."')";
								//echo '<br/>';
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
											$varient_name = addslashes($ex_varients[0]);
											$varient_price = str_replace("Price:","",$ex_varients[1]);
											
											if($varient_price == '' || $varient_price == 0){
												$only_varients_price = 0;
											//	echo $lastproduct_id."============".$only_varients_price;
											//echo '<br/>';
											}else{
												$only_varients_price = trim($varient_price) - $product_price;
												//echo $lastproduct_id."====#####========".$only_varients_price;
											//echo '<br/>';
											}
											
											//echo $product_price."====".$varient_price."====".$only_varients_price;
											
										 $subproducts_query = "INSERT INTO sub_products (`product_id`,`name`,`product_price`,`status`,`created_date`,`merchant_id`,`csv_import`,`csv_id`) values('".$lastproduct_id."','".$varient_name."','".$only_varients_price."',1,'".date('Y-m-d h:i:s')."','".$lastuser_id."',1,'".$lastexcel_id."')";
//echo '<br/>';										
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
			//exit;
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