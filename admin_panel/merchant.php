<?php 
include("config.php");

if(!isset($_SESSION['admin']))
{
	header("location:login.php");
}

if($_POST['functiontype'] == 'update_territory')
{
	$id = $_POST['updatedid'];
	$territory = $_POST['territoryval'];
	$update = mysqli_query($conn,"UPDATE users SET `m_territory_id`='$territory' WHERE id='$id' ");
}

$rec_limit = 20;

/* end  for limit  */

 $sql = "select count(id) as total_count from users Where user_roles = 2";

$row = mysqli_fetch_assoc(mysqli_query($conn,$sql));
$rec_limit = 20;

  $rec_count = $row['total_count'];
if( isset($_GET{'page'} ) ) {
            $page = $_GET{'page'} + 1;
            $offset = $rec_limit * $page ;
         }else {
            $page = 0;
            $offset = 0;
         }
         
$left_rec = $rec_count - ($page * $rec_limit);
     $query="select SQL_NO_CACHE users.*,about.image from users left join about on users.id=about.userid Where users.user_roles = 2 order by name desc LIMIT $offset, $rec_limit";
if($_POST['m_id'])
{
	$m_id=$_POST['m_id'];
	 $query="select SQL_NO_CACHE users.*,about.image from users left join about on users.id=about.userid Where users.user_roles = 2 and users.id='$m_id' order by name desc LIMIT $offset, $rec_limit";
	 // $query="select SQL_NO_CACHE * from users Where user_roles = 2 and id='$m_id' order by name desc LIMIT $offset, $rec_limit";
}
$user = mysqli_query($conn,$query);
$total_rows = mysqli_num_rows($user);
$total_page_num = ceil($total_rows / $limit);

$start = ($page - 1) * $limit;
$end = $page * $limit;
$a_m="merchant";

	  if(isset($_FILES["banner_image"]) && $_FILES["banner_image"]["error"] == 0){
		  extract($_POST);
        $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
        $filename = $_FILES["banner_image"]["name"];
        $filetype = $_FILES["banner_image"]["type"];
        $filesize = $_FILES["banner_image"]["size"];
 
        // Verify file extension
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if(!array_key_exists($ext, $allowed)) die("Error: Please select a valid file format.");    
        
        // Verify MYME type of the file
        if(in_array($filetype, $allowed)){
			// Check whether file exists before uploading it
					$uniquesavename=time().uniqid(rand(1000,9999)).".png";
					
					$destFile = "banner_image/".$uniquesavename;
					require_once('../sirv.api.class.php');
					$sirvClient = new SirvClient(
					  // S3 bucket
					  'koofamilies',
					  // S3 Access Key
					  'click4mayank@gmail.com',
					  // S3 Secret Key
					  'iFOyO1LVMp7EOYIW3IP9VOn76UBFFWdxGaDzuJGj2tHlHMP0'
					);
					if ($sirvClient->testConnection()) {  
					  // Connection SUCCEEDED
					  // echo "connected";
						$res = $sirvClient->uploadFile(
						  // File path on Sirv
						  $destFile,
						  // Local file name
						 $_FILES["banner_image"]["tmp_name"] 
						);
						if($old_image)
							{
								$old_image_path="banner_image/".$old_image;  
								$sirvClient->deleteFile($old_image_path);  
							}
						if($res['full_url']=='')
						{
							$destFile = "/home/koofamilies/public_html/images/banner_image/".$uniquesavename;
							move_uploaded_file($_FILES["banner_image"]["tmp_name"],$destFile); 
						}
						else
						{
							$banner_cdn_url=$uniquesavename;
						}

					} else {
					  // Connection FAILED
						$destFile = "/home/koofamilies/public_html/images/banner_image/".$uniquesavename;
						move_uploaded_file($_FILES["banner_image"]["tmp_name"],$destFile); 
					}
        } else{
            // echo "Error: There was a problem uploading your file. Please try again."; 
        }
		$update="update users set banner_image='$banner_cdn_url' where id='$selected_user_id'";
		mysqli_query($conn,$update);
		header("Location:merchant.php");
exit();
    } 
	
	 if(isset($_FILES["image"]) && $_FILES["image"]["error"] == 0){
		 // print_R($_POST);
		 // die;
		 extract($_POST);
        $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
        $filename = $_FILES["image"]["name"];
        $filetype = $_FILES["image"]["type"];
        $filesize = $_FILES["image"]["size"];
 
        // Verify file extension
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if(!array_key_exists($ext, $allowed)) die("Error: Please select a valid file format.");    
        
        // Verify MYME type of the file
        if(in_array($filetype, $allowed)){
			// Check whether file exists before uploading it
					$uniquesavename=time().uniqid(rand(1000,9999)).".png";
					
					$destFile = "about_images/".$uniquesavename;
					require_once('../sirv.api.class.php');
					$sirvClient = new SirvClient(
					  // S3 bucket
					  'koofamilies',
					  // S3 Access Key
					  'click4mayank@gmail.com',
					  // S3 Secret Key
					  'iFOyO1LVMp7EOYIW3IP9VOn76UBFFWdxGaDzuJGj2tHlHMP0'
					);
					if ($sirvClient->testConnection()) {  
					  // Connection SUCCEEDED
					  // echo "connected";
						$res = $sirvClient->uploadFile(
						  // File path on Sirv
						  $destFile,
						  // Local file name
						 $_FILES["image"]["tmp_name"] 
						);
						if($old_image)
							{
								$old_image_path="image/".$old_image;  
								$sirvClient->deleteFile($old_image_path);  
							}
						if($res['full_url']=='')
						{
							$destFile = "/home/koofamilies/public_html/about_images/".$uniquesavename;
							move_uploaded_file($_FILES["image"]["tmp_name"],$destFile); 
						}
						else
						{
							$image_cdn_url=$uniquesavename;
						}

					} else {
					  // Connection FAILED
						$destFile = "/home/koofamilies/public_html/about_images/".$uniquesavename;
						move_uploaded_file($_FILES["image"]["tmp_name"],$destFile); 
					}
        } else{
            echo "Error: There was a problem uploading your file. Please try again."; 
        }
		$row_count=mysqli_num_rows(mysqli_query($conn,"select * from about where userid='$selected_user_id'"));
		if($row_count>0)
		 $update="update about set image='$image_cdn_url' where userid='$selected_user_id'";
		else
		$insert="INSERT INTO `about` (`userid`,`image`) VALUES ('$selected_user_id','$image_cdn_url');";
		mysqli_query($conn,$update);
		header("Location:merchant.php");
exit();
    }   
	

?>
<!DOCTYPE html>
<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">

<head>   
    <?php include("includes1/head.php"); ?>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="/css/dropzone.css" type="text/css" /> 
	<style>
	.well
	{
		min-height: 20px;
		padding: 19px;
		margin-bottom: 20px;
		background-color: #fff;
		border: 1px solid #e3e3e3;
		border-radius: 4px;
		-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
		box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
	}
	#kType_table_paginate
	{
		display:none !important;
	}
	
	.wallet_h{
	    font-size: 30px;
        color: #213669;

	}
	.kType_table{
	    border: 1px #aeaeae solid !important;
	}
	.kType_table th, .kType_table td{
	    border: 1px #aeaeae solid !important;
	}
	.kType_table thead th{
	    border-bottom: 1px  #aeaeae solid !important;
	} 
	.kType_table tbody .complain{
	    color: red;
	    text-decoration: underline;
	}
	.sort{
	    margin-bottom: 10px;
	}
	/*kType_table tbody tr.k_normal{
	    background: #ececec;
	}*/
	#kType_table tbody tr.k_user{
	    background: #bcbcbc;
	}
	#kType_table tbody tr.k_merchant{
	    background: #dcdcdc;
	}
	.select2-container--bootstrap{
	    width: 175px;
	    display: inline-block !important;
	    margin-bottom: 10px;
	}
	@media  (max-width: 750px) and (min-width: 300px)  {
	    .select2-container--bootstrap{
	        width: 300px;
	    }
	}
	</style>
</head>

<body class="header-light sidebar-dark sidebar-expand pace-done">

    <div id="wrapper" class="wrapper">
        <!-- HEADER & TOP NAVIGATION -->
        <?php include("includes1/navbar.php"); ?>
        <!-- /.navbar -->
        <div class="content-wrapper">
            <!-- SIDEBAR -->
            <?php include("includes1/sidebar.php"); ?>
            <!-- /.site-sidebar -->


            <main class="main-wrapper clearfix" style="min-height: 522px;">
                <div class="container-fluid" id="main-content" style="padding-top:25px">
					<h2 class="text-center wallet_h">Merchant List</h2>
					<div class="col-md-3">
					        <form action="" method="post">
								<div class="form-group">
									<?php 
									 $q="SELECT id,name FROM users WHERE user_roles = '2'  ORDER BY name ASC";
									 
									 	$qMerchant = mysqli_query($conn,$q);
									 
									 if($_POST['choose_agent'])
									 {
										 $q2="select id,name from users as u where u.user_roles='2' and u.name!=''  order by u.name asc";
									    $qMerchant1=mysqli_query($conn,$q2);
									 }
									
									?>
										<label for="tags_merchant">Choose a merchant</label>
									
									
									<select class="tags_merchant_select" name='m_id'>
									    <option value='-1'>Select Merchant</option>
										<?php 
										  
											while($row = mysqli_fetch_assoc($qMerchant)){ ?>
									       <option  <?php if($_POST['m_id']==$row['id']){ echo "selected";} ?>value="<?php  echo $row['id'];?>"><?php echo $row['id']."- ".$row['name'];?></option>
											<?php } ?>

									</select>
								</div>  
								<div class="col-md-6" >
								<input type="submit" class="btn btn-lg btn-outline-primary search" name="search" value="search"/>
							 
								
							</div>
							</form>
							</div> 
							
					<button type="button" class="btn btn-danger" onclick="window.location.href='./merchant.php'">Clear Page</button>
					
				<h3> Total Records <?php  echo $rec_count;?></h3>
				<h4> Total Pages <?php  echo floor($rec_count/$rec_limit);?></h4>
				<?php if($rec_count>25){ ?>        
					<p style="float:right;" class="pagecount">   
					 <?php
					      
								if( $page > 0 ) {
									$last = $page - 2;
  									echo "<a href = \"$_PHP_SELF?"."page=$last\">Last $rec_limit Records</a> |";
									echo "<a href = \"$_PHP_SELF?"."page=$page\">Next $rec_limit Records</a> |";
  									
								 }else if( $page == 0 ) {
									 echo "<a href = \"$_PHP_SELF?"."page=1\">Next $rec_limit Records</a> |";
								
								 }else if( $left_rec < $rec_limit ) {
									$last = $page - 2;
									 echo "<a href = \"$_PHP_SELF?"."page=$last\">Last $rec_limit Records</a> |";
									
								 }
							?>
					</p>
					<?php } ?>
					<table class="table table-striped kType_table" id="kType_table">
					    <thead>
					        <tr>
							<th>Particular</th>
							<th>Merchant id</th>
							<th style="min-width:200px;">Name</th>
							<th style="min-width:200px;">Merchant Remark</th>
							<th> </th>
							<th style="min-width:200px;">Food Panda Link</th>
							<th style="min-width:200px;">Whats app Link</th>
							
							<th>Normal Image</th>
							<th>Banner Image <small> (800*400)</small></th>
							<th>State/City</th>
							<th>Territory</th>

							<th>Defalut Lanaguage</th>
							<th>Address Google Map</th>
							<th>Mobile Nmber</th>
							<th>K Type</th>
							<th>Wallet Coin</th>
							<th>MYR Wallet</th>
							<th>CF</th>
							<th>Joining Date</th>
							<th>Nature of Business</th>
							<th>Whatapp Group Name</th>
							<th>Status</th>
							<th>Merchant Status</th>
							<th>Service tax (%) </th>
							<th>Set Delivery Rate (%) </th>
							<th>Vendor Comission (%) </th>
							<th>Chiness Delivery </th>
							<th>Fix Delivery Charges </th>
							<th>Price Hike </th>
							<th> Time of the pop-up </th>
							<th>Popular Merchant </th>
							<th>Show Merchant </th>
							
							<!--th>Delivery Rate use </th!-->
							<th>View</th>
							<th>Delete</th>
						  </tr>
					    </thead>
					   <tbody>
                    	<?php
                    	$i=1;
                    	while($row=mysqli_fetch_assoc($user)){
							// print_R($row);
							// die;
							 $default_lang=$row['default_lang'];
							 ?>
                        	  <tr>
                        		 <td> <?php echo $i; ?> </td>
								 <td><?php  echo $row['id'];?>
								 <br/>
								 <p style="width:150px">
								 <b>Free Delivery:</b> <input type="Checkbox" selected_user_id="<?php echo $row['id']; ?>" value="1" class="free_delivery_check" name="free_delivery_check" id="free_delivery_check" <?php if($row['free_delivery_check'] == 1){ echo 'checked';}?> />
								 </p>
								 
								 <p style="width:150px">
								 <b>Cash Delivery:</b> <input type="Checkbox" selected_user_id="<?php echo $row['id']; ?>" value="1" class="cash_on_delivery" name="cash_on_delivery" id="cash_on_delivery" <?php if($row['cash_on_delivery'] == 1){ echo 'checked';}?> />
								 </p>
								 
								 
								 <p style="width:150px">
								 <b>No Product options:</b> <input type="Checkbox" selected_user_id="<?php echo $row['id']; ?>" value="1" class="no_product_options" name="no_product_options" id="no_product_options" <?php if($row['no_product_options'] == 1){ echo 'checked';}?> />
								 </p>
								 
								 
								 </td>
                        
								<td style="min-width:200px;">
								<textarea  style="min-width: 200px;max-width: 200px;" selected_user_id="<?php echo $row['id']; ?>" class="form-control real_name" rows="5" name="name"><?php if(isset($row['name'])){ echo $row['name']; }?></textarea>
								
								<p>
								<b>COIN Name:</b>
								<textarea  style="max-width: 200px;" selected_user_id="<?php echo $row['id']; ?>" class="form-control special_coin_name" rows="1" name="special_coin_name"><?php if(isset($row['special_coin_name'])){ echo $row['special_coin_name']; }?></textarea>
								</p>
								</td>
								<td style="min-width:200px;">
								<textarea  style="min-width: 200px;" selected_user_id="<?php echo $row['id']; ?>" class="form-control merchant_remark" rows="5" name="merchant_remark"><?php if(isset($row['merchant_remark'])){ echo $row['merchant_remark']; }?></textarea></td>
								<td><a target="_blank" href="../orderview.php?did=<?php echo $row['id'];?>"><i style="font-size: 60px;" class="fa fa-info"></i></a>
								</td>
                                
								<td style="min-width:200px;">
								<textarea  style="min-width: 200px;" selected_user_id="<?php echo $row['id']; ?>" class="form-control foodpanda_link" rows="5" name="foodpanda_link"><?php if(isset($row['foodpanda_link'])){ echo $row['foodpanda_link']; }?></textarea>
								</td>  
								<td style="min-width:200px;">
								<textarea  style="min-width: 200px;" selected_user_id="<?php echo $row['id']; ?>" class="form-control whatsapp_link" rows="5" name="whatsapp_link"><?php if(isset($row['whatsapp_link'])){ echo $row['whatsapp_link']; }?></textarea>
								</td> 
                                
								<td><form action="" method="post" enctype="multipart/form-data">
								  <input name="image" type="file">
								   <input name="selected_user_id" type="hidden" value="<?php echo $row['id']; ?>">
								     <input type="submit" value="Upload" />
								   <?php if($row['image']==""){ ?>
							<img src="images/logo_new.jpg" data-src="images/logo_new.jpg"  alt=""> <?php
							}else{ ?> <img src="<?php echo $image_cdn; ?>about_images/<?php echo $row['image']?>" data-src="<?php echo $image_cdn; ?>about_images/<?php echo $row['image']?>" alt=""> <?php }?>
			                
								
								</form></td>
								<td><form  action="" method="post" enctype="multipart/form-data">
								  <input name="banner_image" type="file">
								  <input name="selected_user_id" type="hidden" value="<?php echo $row['id']; ?>">
								  <input type="submit" value="Upload" />
								  <?php if ($row['banner_image']) {  ?>

													<img ref="banner_image" data-src="<?php echo $image_cdn; ?>banner_image/<?php echo $row['banner_image'] ?>?w=400" class="owl-lazy lazy2 Sirv" alt="">

													<?php } else {
													if ($row['image'] == "") { ?>

														<img src="images/logo_new.jpg" data-src="images/logo_new.jpg" class="owl-lazy" alt=""> <?php

																																			} else { ?> <img data-src="<?php echo $image_cdn; ?>about_images/<?php echo $row['image'] ?>?w=200" class="owl-lazy lazy2 Sirv" alt=""> <?php }
																																						} ?>
								  
								</form></td>
								
									<td>
									<b>State</b>
									<select class='m_state form-control ' name="m_state" style="height: auto;width: 125px;" data-id="<?php echo $row['id']; ?>">
									<option>Select State</option>
									<?php
									$sql1 = mysqli_query($conn, "SELECT StateName,CityName FROM city GROUP BY StateName order by StateName asc");
									$selected1 = '';
									while($data1 = mysqli_fetch_array($sql1))
									{
									if($row['m_state'] == $data1['StateName']){
									$selected1= 'selected';
									}else{
									$selected1 = '';
									}
									echo'<option data-id="'.$row['id'].'" city_name="'.$data1['CityName'].'" value="'.$data1['StateName'].'" '.$selected1.'>'.$data1['StateName'].'</option>';
									}
									?>
									</select>
									
									<br/>
									<b>CITY</b>
									<select class='city form-control' name="city" style="height: auto;width: 125px;" data-id="<?php echo $row['id']; ?>">
									<option>Select city</option>
									<?php
									$sql = mysqli_query($conn, "SELECT CityName  FROM city WHERE 0=0 and StateName = '".$row['m_state']."' GROUP BY CityName");
									$selected = '';
									while($data = mysqli_fetch_array($sql))
									{
									if($row['city'] == $data['CityName']){
									$selected= 'selected';
									}else{
									$selected = '';
									}
									echo'<option data-id="'.$row['id'].'" value="'.$data['CityName'].'" '.$selected.'>'.$data['CityName'].'</option>';
									}
									?>
									</select>
								</td>
								<td>
	<select class='territory' name="territory" style="" data-id="<?php echo $row['id']; ?>">
	<option>Select Territory</option>
	<?php
	$sql_territory = mysqli_query($conn, "SELECT t_label,t_id FROM `territory` group by t_label");
	$selected = '';
	while($data_territory = mysqli_fetch_array($sql_territory))
	{
		if($row['m_territory_id'] == $data_territory['t_id']){
		$selected= 'selected';
		}else{
		$selected = '';
		}
		echo'<option data-id="'.$row['id'].'" value="'.$data_territory['t_id'].'" '.$selected.'>'.$data_territory['t_label'].'</option>';
	}

	?>
	</select>
	&nbsp;
  <img src="ajax-loader.gif" class="ajx_resp_<?php echo $row['id'];?>" style="display:none"/>

</td>
								  <td>
                        	    	<select class='default_lang' name="default_lang" style="" data-id="<?php echo $row['id']; ?>">
											<option value='1' <?php if($default_lang==1)echo "selected"; ?>>English</option>
											<option value='2' <?php if($default_lang==2)echo "selected"; ?>>Chiness</option>
											<option value='3' <?php if($default_lang==3)echo "selected"; ?>>Malaysian</option>

                                    </select>

                        	    </td>
								<td>
									<textarea  style="min-width: 200px;" selected_user_id="<?php echo $row['id']; ?>" class="form-control mapSearch" rows="5" name="google_map"><?php if(isset($row['google_map'])){ echo $row['google_map']; }?></textarea></td>
                                
								<td><?php echo $row['mobile_number'];  ?></td>
                                <td><?php echo $row['account_type'];?></td>
                        		<td><?php echo $row['balance_usd'];  ?></td>
                        		<td><?php echo $row['balance_myr'];  ?></td>
                        		<td><?php echo $row['balance_inr'];  ?></td>
                        		<td><?php  $date=$row['joined'];
                        	        echo $joinigdate=date("Y-m-d h:i:sa",$date);  ?>
                        	    </td>
                        	    <td>
                        	    	<select class='service' name="service" style="">
											<option>Select Nature of Business</option>
											<?php
												$sql = mysqli_query($conn, "SELECT * FROM service WHERE status=1");
	                                           	$selected = '';
	                                           	while($data = mysqli_fetch_array($sql))
	                                           	{
	                                           		if($data['id'] == $row['service_id']){
	                                           			$selected= 'selected';
	                                           		}else{
	                                           			$selected = '';
	                                           		}
	                                           	 	echo'<option data-id="'.$row['id'].'" value="'.$data['id'].'" '.$selected.'>'.$data['short_name'].'</option>';
	                                           	}

											?>
                                        </select>

                        	    </td>
								<td><input type="text" class="grup_save" data_id="<?php echo $row['id']; ?>" value="<?php echo $row['whatapp_group_name'];?>" class="form-control"/></td>
								
                        	    <td>
                        	        <select class='status' data-id="<?php echo $row['id']; ?>" >
                                	    <option>Select Status</option>
                                	    <option value='1' <?php echo $row['isLocked']=='1' ? 'selected' : ''?>>Blocked</option>
                                	    <option value='0' <?php echo $row['isLocked']=='0' ? 'selected' : ''?>>Unblocked</option>
                        	        </select>
                        	    </td>
								  <td>
                        	        <select class='show_business' data-id="<?php echo $row['id']; ?>" >
                                	    <option>Merchant Status</option>
                                	    <option value='1' <?php echo $row['show_business']=='1' ? 'selected' : ''?>>Show</option>
                                	    <option value='0' <?php echo $row['show_business']=='0' ? 'selected' : ''?>>Hide</option>
                        	        </select>
                        	    </td>   
								<td><input type="text" selected_user_id="<?php echo $row['id']; ?>"  name="sst_rate" placeholder="%" class="form-control sst_rate" value="<?php echo $row['sst_rate'];?>"></td>   
								<td><input type="text" selected_user_id="<?php echo $row['id']; ?>"  name="delivery_rate" placeholder="%" class="form-control delivery_rate" value="<?php echo $row['delivery_rate'];?>"></td>   
								<td><input type="text" selected_user_id="<?php echo $row['id']; ?>"  name="vendor_comission" placeholder="%" class="form-control vendor_comission" value="<?php echo $row['vendor_comission'];?>"></td>   
								<td><input type="text" selected_user_id="<?php echo $row['id']; ?>"  name="special_price_value" placeholder="" class="form-control special_price_value" value="<?php echo $row['special_price_value'];?>"></td>   
								<td><input type="text" selected_user_id="<?php echo $row['id']; ?>"  name="order_extra_charge" placeholder="" class="form-control order_extra_charge" value="<?php echo $row['order_extra_charge'];?>"></td>   
								<td><input type="text" selected_user_id="<?php echo $row['id']; ?>"  name="price_hike" placeholder="" class="form-control price_hike" value="<?php echo $row['price_hike'];?>"></td>   
								<td><input type="text" selected_user_id="<?php echo $row['id']; ?>"  name="custom_msg_time" placeholder="" class="form-control custom_msg_time" value="<?php echo $row['custom_msg_time'];?>"></td>   
								<td><input type="text" selected_user_id="<?php echo $row['id']; ?>"  name="popular_restro" placeholder="" class="form-control popular_restro" value="<?php echo $row['popular_restro'];?>"></td>   
								<td><input type="text" selected_user_id="<?php echo $row['id']; ?>"  name="show_merchant" placeholder="" class="form-control show_merchant" value="<?php echo $row['show_merchant'];?>"></td>   
								<!--td>
									<div class="form-group checkbox-checked">
											
											<input type="checkbox" class="delivery_select" selected_user_id="<?php echo $row['id']; ?>" selected_type="delivery_take_up" name="delivery_take_up" <?php if($row['delivery_take_up'] == '1') echo "checked='checked'";?>> Take away<br>
											<input type="checkbox" class="delivery_select" selected_user_id="<?php echo $row['id']; ?>" selected_type="delivery_dive_in" name="delivery_dive_in" <?php if($row['delivery_take_up'] == '1') echo "checked='checked'";?>> Dine in<br>
   
									</div>
								</td!-->
                        	    <td><a href="user_edit.php?id=<?php echo $row['id'];?>"><i style="font-size: 20px;" class="fa fa-eye"></i></a></td>
                                <td class="del" data-toggle="modal" data-target="#delModal" data-del="<?php echo $row['id']; ?>"><i style="font-size: 20px;" class="fa fa-trash"></i></td>
                              </tr>
                    	<?php
                            $i++;  
                    	}?>
                	</tbody>
					</table>
					<?php if($rec_count>25){ ?>    
					<p style="float:right;">
					 <?php
								if( $page > 0 ) {
									$last = $page - 2;
  									echo "<a href = \"$_PHP_SELF?"."page=$last\">Last $rec_limit Records</a> |";
									echo "<a href = \"$_PHP_SELF?"."page=$page\">Next $rec_limit Records</a> |";
  									
								 }else if( $page == 0 ) {
									 echo "<a href = \"$_PHP_SELF?"."page=1\">Next $rec_limit Records</a> |";
								
								 }else if( $left_rec < $rec_limit ) {
									$last = $page - 2;
									 echo "<a href = \"$_PHP_SELF?"."page=$last\">Last $rec_limit Records</a> |";
									
								 }
							?>
					</p>
					<?php } ?>
				</div>
			</main>
        </div>
      
        <!-- /.widget-body badge -->
    </div>
    <!-- /.widget-bg -->

    <!-- /.content-wrapper -->
	<?php include("includes1/footer.php"); ?>
	<script type="text/javascript" src="/js/dropzone.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
	


<script src="https://scripts.sirv.com/sirv.js" defer></script>

<script type="text/javascript">

	  $(document).ready(function() {
    $(".tags_merchant_select").select2();
});
</script>

	<script>
	    $(document).ready(function(){
	        jQuery(".dropzone").dropzone({
                sending : function(file, xhr, formData){
                },
                success : function(file, response) {
                    $(".complain_image").val(file.name);
                    
                }
            });
            $('#kType_table').DataTable({
				"bSort": false,
				"pageLength":1000,
				dom: 'Bfrtip',
				 buttons: [
					'copy', 'csv', 'excel', 'pdf', 'print'
				]
				});
	 $(".image").on('change', function() {
			 ///// Your code
	});
	$(".status").change(function(){
		var status = $(this).val();
		//~ alert(status);
		var id = $(this).data("id");
		//~ alert(id);
		$.ajax({
			url : 'updateuser.php',
			type: 'POST',
			data :{updatedid:id,upadtedstatus:status},
			success:function(data){
		     location.reload();
			}  
		});
		
	});
	/*$(".city").change(function(){
		var cityval = $(this).val();
		//alert(cityval);
		var id = $(this).data("id");
		//alert(id);
		$.ajax({
			url : 'updatecity.php',
			type: 'POST',
			data :{updatedid:id,cityval:cityval},
			success:function(data){
		     location.reload();
			}  
		});
		
	});
	*/
	
		$(".city").change(function(){
		var cityval = $(this).val();
		//alert(cityval);
		var id = $(this).data("id");
		//alert(id);
		$.ajax({
			url : 'updatecity.php',
			type: 'POST',
			data :{updatedid:id,cityval:cityval},
			success:function(data){
		     location.reload();
			}  
		});
	});
	
	$(".m_state").change(function(){
		var m_state = $(this).val();
		//alert(cityval);
		var id = $(this).data("id");
		var city_name = $(this).attr('city_name');
		//alert(id);
		$.ajax({
			url : 'updatecity.php',
			type: 'POST',
			data :{updatedid:id,m_state:m_state,city_name:city_name},
			success:function(data){
		     location.reload();
			}  
		});
	});
	
	$(".default_lang").change(function(){
		var langval = $(this).val();
		//alert(cityval);
		var id = $(this).data("id");
		//alert(id);
		$.ajax({
			url : 'updatecity.php',
			type: 'POST',
			data :{updatedid:id,langval:langval},
			success:function(data){
		     location.reload();
			}  
		});
		  
	});
	$(".show_business").change(function(){
		var status = $(this).val();
		//~ alert(status);
		var id = $(this).data("id");
		//~ alert(id);
		$.ajax({
			url : 'updateuser.php',
			type: 'POST',
			data :{m_id:id,upadtedstatus:status},
			success:function(data){  
				location.reload();
			}
		});
		
	});
    		$(".grup_save").focusout(function(e){
		var selected_user_id= $(this).attr('data_id');
		var whatapp_group_name=this.value;
		if(whatapp_group_name!='' && selected_user_id)
		{  
		  $.ajax({
						url :'updatenatureofbusiness.php',
						 type:"post",
						 data:{updatedid:selected_user_id,whatapp_group_name:whatapp_group_name},     
						 dataType:'json',
						 success:function(result){  
							var data = JSON.parse(JSON.stringify(result));   
							alert(data.msg);
						 }
				});      
		} 
	});
	$(".service").change(function(){
		var service_id = $(this).val();
		 //alert(service_id);
		 var id = $(this).find(':selected').attr('data-id');
		 //alert(id);
		$.ajax({
			url : 'updatenatureofbusiness.php',
			type: 'POST',
			data :{updatedid:id,service_id:service_id},
			success:function(data){
		
			}
		});
		
	});
		$(".service").change(function(){
		var service_id = $(this).val();
		 //alert(service_id);
		 var id = $(this).find(':selected').attr('data-id');
		 //alert(id);
		$.ajax({
			url : 'updatenatureofbusiness.php',
			type: 'POST',
			data :{updatedid:id,service_id:service_id},
			success:function(data){
		
			}
		});
		
	});
	
	
	 $(".special_coin_name").focusout(function(e){
		var selected_user_id= $(this).attr('selected_user_id');
		var special_coin_name=this.value; 
		if(selected_user_id)
		{  
			$.ajax({
				url :'../functions.php',
				 type:"post",
				 data:{special_coin_name:special_coin_name,method:"merchantspecialcoin",selected_user_id:selected_user_id},     
				 dataType:'json',
				 success:function(result){  
					var data = JSON.parse(JSON.stringify(result));   
					if(data.status==true)
					{  
					   // location.reload(true);
					}
					else
					{alert('Failed to update');	}
					
				}
			});      
		}
		});
		
		
		
	 $(".real_name").focusout(function(e){
		var selected_user_id= $(this).attr('selected_user_id');
		// var selected_user_id= $(this).attr('selected_user_id');
		var name=this.value; 
		// alert(name);
		if(name!='' && selected_user_id)
		{  
		  $.ajax({
						url :'../functions.php',
						 type:"post",
						 data:{name:name,method:"adminprofilesave",selected_user_id:selected_user_id},     
						 dataType:'json',
						 success:function(result){  
							var data = JSON.parse(JSON.stringify(result));   
							if(data.status==true)
							{  
							   // location.reload(true);
								
							}
							else
							{alert('Failed to update');	}
							
							}
				});      
		}
		});
		
		
		$(".free_delivery_check").click(function(e){
			var selected_user_id= $(this).attr('selected_user_id');
			var free_delivery_check = this.value; 
			if (this.checked) {
				var free_delivery_check = 1;
			}else{
				var free_delivery_check = 0;
			}
			
			if(selected_user_id)
			{  
			  $.ajax({
							url :'../functions.php',
							 type:"post",
							 data:{free_delivery_check:free_delivery_check,method:"free_delivery_check",selected_user_id:selected_user_id},     
							 dataType:'json',
							 success:function(result){  
								var data = JSON.parse(JSON.stringify(result));   
								if(data.status==true)
								{  
								   // location.reload(true);
									
								}
								else
								{alert('Failed to update');	}
								
								}
					});      
			}
		});
		
		
		
		$(".no_product_options").click(function(e){
			var selected_user_id= $(this).attr('selected_user_id');
			var no_product_options = this.value; 
			if (this.checked) {
				var no_product_options = 1;
			}else{
				var no_product_options = 0;
			}
			if( selected_user_id)
			{  
			  $.ajax({
					url :'../functions.php',
					type:"post",
					data:{no_product_options:no_product_options,method:"no_product_options",selected_user_id:selected_user_id},     
					dataType:'json',
					success:function(result){  
						var data = JSON.parse(JSON.stringify(result));   
						if(data.status==true){}
						else{alert('Failed to update');	}
						}
					});      
			}
		});
		
		
		
		
		$(".cash_on_delivery").click(function(e){
			var selected_user_id= $(this).attr('selected_user_id');
			var cash_on_delivery = this.value; 
			if (this.checked) {
				var cash_on_delivery = 1;
			}else{
				var cash_on_delivery = 0;
			}
			if( selected_user_id)
			{  
			  $.ajax({
							url :'../functions.php',
							 type:"post",
							 data:{cash_on_delivery:cash_on_delivery,method:"cash_on_delivery",selected_user_id:selected_user_id},     
							 dataType:'json',
							 success:function(result){  
								var data = JSON.parse(JSON.stringify(result));   
								if(data.status==true)
								{  
								   // location.reload(true);
									
								}
								else
								{alert('Failed to update');	}
								
								}
					});      
			}
		});
		
		
		
		
		
		
		
		 $(".merchant_remark").focusout(function(e){
		var selected_user_id= $(this).attr('selected_user_id');
		// var selected_user_id= $(this).attr('selected_user_id');
		var merchant_remark=this.value; 
		// alert(name);
		if(merchant_remark!='' && selected_user_id)
		{  
		  $.ajax({
						url :'../functions.php',
						 type:"post",
						 data:{merchant_remark:merchant_remark,method:"adminprofilesave",selected_user_id:selected_user_id},     
						 dataType:'json',
						 success:function(result){  
							var data = JSON.parse(JSON.stringify(result));   
							if(data.status==true)
							{  
							   // location.reload(true);
								
							}
							else
							{alert('Failed to update');	}
							
							}
				});      
		}
		});
		$(".foodpanda_link").focusout(function(e){
		var selected_user_id= $(this).attr('selected_user_id');
		// var selected_user_id= $(this).attr('selected_user_id');
		var foodpanda_link=this.value; 
		// alert(name);
		if(foodpanda_link!='' && selected_user_id)
		{  
		  $.ajax({
						url :'../functions.php',
						 type:"post",
						 data:{foodpanda_link:foodpanda_link,method:"adminprofilesave",selected_user_id:selected_user_id},     
						 dataType:'json',
						 success:function(result){  
							var data = JSON.parse(JSON.stringify(result));   
							if(data.status==true)
							{  
							   // location.reload(true);
								
							}
							else
							{alert('Failed to update');	}
							
							}
				});      
		}
		});
		$(".whatsapp_link").focusout(function(e){
		var selected_user_id= $(this).attr('selected_user_id');
		// var selected_user_id= $(this).attr('selected_user_id');
		var whatsapp_link=this.value; 
		// alert(name);  
		if(whatsapp_link!='' && selected_user_id)    
		{    
		  $.ajax({
						url :'../functions.php',
						 type:"post",
						 data:{whatsapp_link:whatsapp_link,method:"adminprofilesave",selected_user_id:selected_user_id},     
						 dataType:'json',
						 success:function(result){  
							var data = JSON.parse(JSON.stringify(result));   
							if(data.status==true)
							{  
							   // location.reload(true);
								
							}
							else
							{alert('Failed to update');	}
							
							}
				});      
		}
		});
		 $(".mapSearch").focusout(function(e){
		var selected_user_id= $(this).attr('selected_user_id');
		// var mapSearch= $(".mapSearch").val();  
		var mapSearch=this.value; 		
		// alert(name);   
		if(mapSearch!='' && selected_user_id)
		{  
		  $.ajax({
						url :'../functions.php',
						 type:"post",
						 data:{google_map:mapSearch,method:"adminprofilesave",selected_user_id:selected_user_id},     
						 dataType:'json',
						 success:function(result){  
							var data = JSON.parse(JSON.stringify(result));   
							if(data.status==true)
							{
							  // location.reload(true);
								
							}
							else
							{alert('Failed to update');	}
							
							}
				});      
		}
		});
    $(".sst_rate").focusout(function(e){
		var selected_user_id= $(this).attr('selected_user_id');
		var sst_rate=this.value;
		if(sst_rate!='' && selected_user_id)
		{  
		  $.ajax({
						url :'../functions.php',
						 type:"post",
						 data:{sst_rate:sst_rate,method:"adminprofilesave",selected_user_id:selected_user_id},     
						 dataType:'json',
						 success:function(result){  
							var data = JSON.parse(JSON.stringify(result));   
							if(data.status==true)
							{
							  // location.reload(true);
								
							}
							else
							{alert('Failed to update');	}
							
							}
				});      
		} 
	});
	$(".delivery_rate").focusout(function(e){
		var selected_user_id= $(this).attr('selected_user_id');
		var delivery_rate=this.value;
		if(delivery_rate!='' && selected_user_id)
		{  
		  $.ajax({
						url :'../functions.php',
						 type:"post",
						 data:{delivery_rate:delivery_rate,method:"adminprofilesave",selected_user_id:selected_user_id},     
						 dataType:'json',
						 success:function(result){  
							var data = JSON.parse(JSON.stringify(result));   
							if(data.status==true)
							{
							  // location.reload(true);
								
							}
							else
							{alert('Failed to update');	}
							
							}
				});      
		} 
	});
		$(".vendor_comission").focusout(function(e){
		var selected_user_id= $(this).attr('selected_user_id');
		var vendor_comission=this.value;
		if(vendor_comission!='' && selected_user_id)
		{  
		  $.ajax({
						url :'../functions.php',
						 type:"post",
						 data:{vendor_comission:vendor_comission,method:"adminprofilesave",selected_user_id:selected_user_id},     
						 dataType:'json',
						 success:function(result){  
							var data = JSON.parse(JSON.stringify(result));   
							if(data.status==true)
							{
							  // location.reload(true);
							alert('Updated');
							}
							else
							{alert('Failed to update');	}
							
							}
				});      
		} 
	});
	$(".special_price_value").focusout(function(e){
		var selected_user_id= $(this).attr('selected_user_id');
		var special_price_value=this.value;
		if(special_price_value!='' && selected_user_id)
		{  
		  $.ajax({
						url :'../functions.php',
						 type:"post",
						 data:{special_price_value:special_price_value,method:"adminprofilesave",selected_user_id:selected_user_id},     
						 dataType:'json',
						 success:function(result){  
							var data = JSON.parse(JSON.stringify(result));   
							if(data.status==true)
							{
							  // location.reload(true);
								alert('Updated');
							}
							else
							{alert('Failed to update');	}
							
							}
				});      
		} 
	});
		$(".order_extra_charge").focusout(function(e){
		var selected_user_id= $(this).attr('selected_user_id');
		var order_extra_charge=this.value;
		if(order_extra_charge!='' && selected_user_id)
		{  
		  $.ajax({
						url :'../functions.php',
						 type:"post",
						 data:{order_extra_charge:order_extra_charge,method:"adminprofilesave",selected_user_id:selected_user_id},     
						 dataType:'json',
						 success:function(result){  
							var data = JSON.parse(JSON.stringify(result));   
							if(data.status==true)
							{
							  // location.reload(true);
								alert('Updated');
							}
							else
							{alert('Failed to update');	}
							
							}
				});      
		} 
	});
	$(".price_hike").focusout(function(e){
		var selected_user_id= $(this).attr('selected_user_id');
		var price_hike=this.value;
		if(price_hike!='' && selected_user_id)
		{  
		  $.ajax({
						url :'../functions.php',
						 type:"post",
						 data:{price_hike:price_hike,method:"adminprofilesave",selected_user_id:selected_user_id},     
						 dataType:'json',
						 success:function(result){  
							var data = JSON.parse(JSON.stringify(result));   
							if(data.status==true)
							{
							  // location.reload(true);
								alert('Price hike updated');
							}
							else
							{alert('Failed to update');	}
							
							}
				});      
		} 
	});
	$(".popular_restro").focusout(function(e){
		var selected_user_id= $(this).attr('selected_user_id');
		var popular_restro=this.value;
		if(popular_restro!='' && selected_user_id)
		{  
		  $.ajax({
						url :'../functions.php',
						 type:"post",
						 data:{popular_restro:popular_restro,method:"adminprofilesave",selected_user_id:selected_user_id},     
						 dataType:'json',
						 success:function(result){  
							var data = JSON.parse(JSON.stringify(result));   
							if(data.status==true)
							{
							  // location.reload(true);
								alert('Status updated for popular merchant');
							}
							else
							{alert('Failed to update');	}
							
							}
				});      
		} 
	});
	$(".show_merchant").focusout(function(e){
		var selected_user_id= $(this).attr('selected_user_id');
		var show_merchant=this.value;
		if(show_merchant!='' && selected_user_id)
		{  
		  $.ajax({
						url :'../functions.php',
						 type:"post",
						 data:{show_merchant:show_merchant,method:"adminprofilesave",selected_user_id:selected_user_id},     
						 dataType:'json',
						 success:function(result){  
							var data = JSON.parse(JSON.stringify(result));   
							if(data.status==true)
							{
							  // location.reload(true);
								alert('Status updated for show merchant');
							}
							else
							{alert('Failed to update');	}
							
							}
				});      
		} 
	});  
	
	$('.delivery_select').change(function() {
		var selected_type= $(this).attr('selected_type');
		alert(selected_type);
        if($(this).is(":checked")) {
            
        }
	});
  $(".name").click(function(){
	  $("#myModal").modal("show");
	  var userid=$(this).data("id");
	 
	  $.ajax({
		  
		  url :'bankdatalil.php',
		  type:'POST',
		  data:{showid:userid},
		  success:function(table){
			 $("#modalcontent").html(table);
		  }		  
	  });
	 
  });
	
	/*user delete function */
	
	$('.del').click(function(){
        var id=$(this).data("del");
        
        $(".confirm-btn").attr({'user-id': id});
    });
    $('.confirm-btn').click(function(){
        var id = $(this).attr('user-id');
        $.ajax({
            url:'user_delete.php',
            type:'POST',
            data:{id:id},
            success: function(data) {
                location.reload();
            }
        });
    });
			


$(".territory").change(function(){
		var territoryval = $(this).val();
		//alert(cityval);
		var id = $(this).data("id");
		//alert(id);
		$(".ajx_resp_"+id).show();
		$.ajax({
			url : 'merchant.php',
			type: 'POST',
			data :{
				functiontype: 'update_territory',
				updatedid:id,
				territoryval:territoryval
				},
			success:function(data){
			$(".ajx_resp_"+id).hide();
			
		     //location.reload();
			}  
		});
		
	});
				
				
	});
	  
	  
	</script>
	
</body>

</html>
