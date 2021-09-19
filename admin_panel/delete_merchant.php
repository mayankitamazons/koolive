<?php 
include("config.php");
if(!isset($_SESSION['mdadmin']))
{
	header("location:login2.php?mdadmin=1");
}else{
	unset($_SESSION['mdadmin_page']);
}

if(!isset($_SESSION['admin']))
{
	//header("location:login.php");
}


/*DELETE function*/
if(isset($_GET['data'])&&$_GET['data']=='deleteRecord'){
	$merchant_id = $_GET['merchant_id'];
		$m_query = mysqli_query($conn,"DELETE FROM `users` WHERE `id`='$merchant_id' and user_roles = 2");
	if($m_query){
		$product_query = mysqli_query($conn,"DELETE FROM `products` WHERE `user_id`='$merchant_id'");
		$cat_query = mysqli_query($conn,"DELETE FROM `category` WHERE `user_id` = '$merchant_id'");
	
	}
	
}

/* END DELETE function*/


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
    // $query="select SQL_NO_CACHE users.*,about.image from users left join about on users.id=about.userid Where users.user_roles = 2 order by name desc LIMIT $offset, $rec_limit";
	$query = "select u.id,u.name,u.mobile_number,u.google_map,count(p.user_id) as product from users as u left Join products as p On p.user_id = u.id where u.user_roles = 2 group by u.id ORDER BY `product` ASC LIMIT $offset, $rec_limit";
if($_POST['m_id'])
{
	$m_id=$_POST['m_id'];
	// $query="select SQL_NO_CACHE users.*,about.image from users left join about on users.id=about.userid Where users.user_roles = 2 and users.id='$m_id' order by name desc LIMIT $offset, $rec_limit";
	 
	 $query = "select u.id,u.name,u.mobile_number,u.google_map,count(p.user_id) as product  from users as u left Join products as p On p.user_id = u.id  where u.user_roles = 2 and u.id='$m_id' group by u.id ORDER BY `product` ASC LIMIT $offset, $rec_limit";
	 // $query="select SQL_NO_CACHE * from users Where user_roles = 2 and id='$m_id' order by name desc LIMIT $offset, $rec_limit";
}
$user = mysqli_query($conn,$query);
$total_rows = mysqli_num_rows($user);
$total_page_num = ceil($total_rows / $limit);

$start = ($page - 1) * $limit;
$end = $page * $limit;
$a_m="delete_merchant";


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
					<div class="col-md-12">
					        <form action="" method="post">
								<div class=" col-md-6 form-group">
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
									
									
									<select class="col-md-4 tags_merchant_select" name='m_id'>
									    <option value='-1'>Select Merchant</option>
										<?php 
										  
											while($row = mysqli_fetch_assoc($qMerchant)){ ?>
									       <option  <?php if($_POST['m_id']==$row['id']){ echo "selected";} ?>value="<?php  echo $row['id'];?>"><?php echo $row['id']."- ".$row['name'];?></option>
											<?php } ?>

									</select>
									<input type="submit" class="btn btn-lg btn-outline-primary search" name="search" value="search"/>
									
									<a type="button" class="btn btn-danger" href="delete_merchant.php">Clear Page</a>
									
								</div>  
								
							</form>
							</div> 
							
					
					
				<h3> Total Records <?php  echo $rec_count;?></h3>
				<h4> Total Pages <?php  echo floor($rec_count/$rec_limit);?></h4>
				<h2  style=" background: red;color: white; padding: 12px;">Please Take Database backup, before Delete any Merchant!!!</h2>				
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
							<th>Address Google Map</th>
							
							<th>Action</th>
							</tr>
					    </thead>
					   <tbody>
                    	<?php
                    	$i=1;
                    	while($row=mysqli_fetch_assoc($user)){
							$default_lang=$row['default_lang'];
							$od_query = "select Count(*) as od_count from order_list where merchant_id =".$row['id'];
							$od_data = mysqli_fetch_assoc(mysqli_query($conn,$od_query));
							
							$cat_query = "select Count(*) as cat_count from category where user_id =".$row['id'];
							$cat_data = mysqli_fetch_assoc(mysqli_query($conn,$cat_query));
							
							
							
							 ?>
                        	  <tr>
                        		 <td> <?php echo $i; ?> </td>
								 <td><?php  echo $row['id'];?>
								<p><b>Product:</b><?php echo $row['product'];?></p>
								<p><b>Category :</b><?php echo $cat_data['cat_count'];?></p>
								<p><b>Orders :</b><?php echo $od_data['od_count'];?></p>
								 </td>
                        
								<td style="min-width:200px;">
								<?php if(isset($row['name'])){ echo $row['name']; }?>
								<br/>
								<p><b>Number:</b><?php echo $row['mobile_number'];?>
								</p>
								</td>
					
								<td><?php if(isset($row['google_map'])){ echo $row['google_map']; }?></td>
                                
								
                                <td >
								<a class="deleteRecord" merchant_id="<?php echo $row['id']; ?>" style="color:black"><i style="font-size: 20px;" class="fa fa-trash"></i></a>
								
								</td>
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


	/*user delete function */
	
	$(".deleteRecord").click(function(){

	var merchant_id = $(this).attr('merchant_id');
	var cnfrmDelete = confirm("Have you Take Databse backup ?");
	if(cnfrmDelete==true){
		var cnfrmDelete = confirm("Are you sure want delete this merchant ?");
		if(cnfrmDelete==true){
			//alert('success');
			$.ajax({
				url:'delete_merchant.php',
				method:'GET',
				data:{data:'deleteRecord',merchant_id:merchant_id},
				success:function(res){location.reload(true);}
			});	
		}
		  
	}
  });


				
				
	});
	  
	  
	</script>
	
</body>

</html>
