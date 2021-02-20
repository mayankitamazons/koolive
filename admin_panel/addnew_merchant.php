<?php 
include("config.php");

if(!isset($_SESSION['admin']))
{
	header("location:login.php");
}
$c_id=$_GET['c_id'];
$a_m="classfication_merchant"; 
?>
<!DOCTYPE html>
<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">

<head>
    <?php include("includes1/head.php"); ?>
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
	.select2-dropdown {
	  z-index: 90019;
	}
	@media  (max-width: 750px) and (min-width: 300px)  {
	    .select2-container--bootstrap{
	        width: 300px;
	    }
	}
	.card :hover {
		background: gray;
	}
	.activecard
	{
		background: gray;
	}
	</style>
</head>

<body class="header-light sidebar-dark sidebar-expand pace-done">

    <div class="pace  pace-inactive">
        <div class="pace-progress" data-progress-text="100%" data-progress="99" style="transform: translate3d(100%, 0px, 0px);">
            <div class="pace-progress-inner"></div>
        </div>
        <div class="pace-activity"></div>
    </div>
     <?php
       if(isset($_POST['update']))
	   {
		   $classfication_id=$_GET['c_id'];
		   $shift_pos=$_POST['shift_pos'];
		   $merchant_id=$_POST['merchant_id'];
		   $precheck=mysqli_query($conn,"select * from classification_arrange_system where classfication_id='$c_id' and merchant_id='$merchant_id'");
		   $predata=mysqli_fetch_array($precheck);
		   if($predata)
		   {
			   $pre_id=$predata['id'];
			   $q="update classification_arrange_system set shift_pos='$shift_pos'.merchant_id='$merchant_id' where id='$pre_id'";
			   // old entry mark update
				$query=mysqli_query($conn,$q);
				$_SESSION['show_msg']="Position updated successfully";
		   }
		   else
		   {
			   // echo "dd";
			   // die;
			   // check same shift position 
			   $precheck=mysqli_query($conn,"select * from classification_arrange_system where classfication_id='$c_id' and shift_pos='$shift_pos'");
				$predata=mysqli_fetch_array($precheck);
				// print_R($predata);
				// die;
				if($predata)
				{
					$pre_id=$predata['id'];
				   // old entry mark update
				    $q="update classification_arrange_system set shift_pos='$shift_pos',merchant_id='$merchant_id' where id='$pre_id'";
				  
					$query=mysqli_query($conn,$q);
					$_SESSION['show_msg']="Position updated successfully";
				}
				else
				{
				   $q="INSERT INTO `classification_arrange_system` (`classfication_id`, `merchant_id`, `shift_pos`) VALUES ('$classfication_id', '$merchant_id', '$shift_pos')";
				   // echo  $q="insert into classification_arrange_system ('classfication_id','merchant_id','shift_pos') values ('$classfication_id','$merchant_id','$shift_pos')";
					// die;
					$query=mysqli_query($conn,$q);
					$_SESSION['show_msg']="Position updated successfully";
				}
		   }
	   }		   
	 ?>
    <div id="wrapper" class="wrapper">
        <!-- HEADER & TOP NAVIGATION -->
        <?php include("includes1/navbar.php"); ?>
        <!-- /.navbar -->
        <div class="content-wrapper">
            <!-- SIDEBAR -->
            <?php include("includes1/sidebar.php"); ?>
            <!-- /.site-sidebar -->


            <main class="main-wrapper clearfix" style="min-height: 522px;">
                <div class="row" id="main-content" style="padding-top:25px">
					<div class="container">
					<?php
					  
	
					?>
					</div>
					<?php
					
					 	$q="select cs.*,c.classification_name,users.name as merchant_name from classification_arrange_system as cs inner join users on cs.merchant_id=users.id 
						right join  classfication_service as c on cs.classfication_id=c.id where c.id='$c_id'";
						$query=mysqli_query($conn,$q);
						$result = mysqli_fetch_all ($query, MYSQLI_ASSOC);
						$class_name=$result[0]['classification_name'];
					?>
					<div class="container" >
					   
					        <div class="well">
							
									<?php  echo "<h2>Classfication Arrange system for $class_name</h2>"; ?>
									<div class="row">
									<?php if(isset($result)){ 
									  
                                   
									 foreach ($result as $key => $item) {
									
									   $shift_pos=$item['shift_pos'];
									   $arr[$shift_pos]=$item;
									   
									   }
									   	 // print_R($arr);
										 // die;
									?>
									
									  <?php for($p=1;$p<=100;$p++){ 
									    
									    $list=$arr[$p];
										
										  $shift_pos=$list['shift_pos'];
										  if($shift_pos==$p)
										  {
										  ?>
										   <div class="card col-3">
												<div class="card-body" style="text-align:center;">
												 <label style="text-align:left;">Position <?php echo $p; ?></label>
												<img src="<?php echo $image;?>" style="max-width: 80px;">
												  <p><?php echo $list['merchant_name']; ?></p>
												
												  
												</div>
												<i  class="add_product"   pos="<?php echo $p; ?>" style="text-align:right">Edit</i>
											</div>
										  <?php }
											  else
											  {
											?>
											 <div class="card col-3 add_product"  data-toggle="modal" data-target="#myModal"  pos="<?php echo $p; ?>">
												<div class="card-body" style="text-align:center;">
												<p> Add </p>
												</div>
											</div>
										 <?php  }
									$i++;} } ?>
										 	
									</div>
								
							
						</div>
						
					
				</div>
				 
  <div id="responsive-catelog-model" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                    <div class="modal-dialog" style="max-width:1000px !important;">
									
									<form method="post">
									
									  <input  type="hidden" id="data_shift_pos" name="shift_pos"/>
									  <input  type="hidden" id="classifciation_id" name="classifciation_id" value="<?php  echo $c_id;?>"/>
                                        <div class="modal-content catelog_plan_body">
                                          <?php
										   $q="select id,name from users where user_roles='2' and id not in (select merchant_id from classification_arrange_system where  classfication_id='$c_id')";
											$query2=mysqli_query($conn,$q);
										  ?>
										      <div class="modal-body">
                                                <div class="row">
												
                        <div class="col-12 table-responsive catelog_body">  
						            <label>Select Merchant For Position <span id="list_shift_pos"></span></label>
									<select class="form-control tags_merchant_select" style="width:250px !important;" name="merchant_id">
									 <option>Select Merchant</option>
									 <?php while($r=mysqli_fetch_array($query2)){ ?>
									 <option value="<?php echo $r['id']; ?>"><?php echo $r['name']; ?></option>
									 <?php } ?>
									</select>
                                </div>

                       
                    
							</div>
							 
														</div>
														<div class="modal-footer">
															<button type="button" class="btn btn-default waves-effect close_model" data-dismiss="modal">Close</button>
															<input type="submit" name="update" class="btn btn-danger waves-effect waves-light" value="Save Changes"/>
															 
														</div>
										  
                                        </div>
									</form>
                                    </div>
          </div>
			</main>
        </div>
        <!-- /.widget-body badge -->
    </div>
    <!-- /.widget-bg -->

    <!-- /.content-wrapper -->
    <?php include("includes1/footer.php"); ?>
	
</body>
 <script>
    jQuery(document).ready(function() {
		// $(".tags_merchant_select").select2();    
		  $(".tags_merchant_select").select2({
    dropdownParent: $("#responsive-catelog-model")
  });
		   $('.add_product').click(function() {
			var pos = $(this).attr("pos");
			 $(".card").removeClass("activecard");
			// alert(pos);
			$("#list_shift_pos").html(pos);
			$("#data_shift_pos").val(pos);
			 $(this).addClass("activecard");
			$('#responsive-catelog-model').modal('show');
			
		});
		$('.close_model').click(function() {
			 $(".card").removeClass("activecard");
		});
		
	});
 </script>
</html>
<style>
select {
    height: 30px;
}
</style>
