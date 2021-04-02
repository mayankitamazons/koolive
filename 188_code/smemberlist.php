<?php 
session_start();
include("config.php");
$memebership_tab="y";
$me="subscription_plan";
if(!isset($_SESSION['login']))
{
	header("location:login.php");
}

$date = date('Y-m-d H:i:s');

// $profile_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$_SESSION['login']."'"));
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
   <?php
        $merchant_id=$_SESSION['login'];
		$sql="select users.name,users.mobile_number,membership_plan.plan_name,user_membership_plan.* from user_membership_plan inner join membership_plan on membership_plan.id=user_membership_plan.plan_id 
		inner join users on users.id=user_membership_plan.user_id where user_membership_plan.merchant_id='$merchant_id' and user_membership_plan.plan_active='y'";
		$result = $conn->query($sql);
		$rec_count =$result->num_rows;
		$rec_limit = 30;
		if( isset($_GET{'page'} ) ) {
            $page = $_GET{'page'} + 1;
            $offset = $rec_limit * $page;
         }else {
            $page = 0;
            $offset = 0;
         }
		$left_rec = $rec_count - ($page * $rec_limit);
		$sql2="select users.name,users.mobile_number,membership_plan.plan_name,user_membership_plan.* from user_membership_plan inner join membership_plan on membership_plan.id=user_membership_plan.plan_id 
		inner join users on users.id=user_membership_plan.user_id where user_membership_plan.merchant_id='$merchant_id' and user_membership_plan.plan_active='y' LIMIT $offset, $rec_limit";
		$result2 = $conn->query($sql2);
		if ($result2->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
			$list_users[] = $row;
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
           <form>

            <main class="main-wrapper clearfix" style="min-height: 522px;">
                <div class="container-fluid" id="main-content" style="padding-top:25px">
					<h3><?php echo $language['list_user']; ?></h3>
					<button type="button" class="btn btn-danger" onclick="window.location.href='smemberlist.php'">Clear Page</button>
					</br>
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
					          <th>Sr No</th>
								<th>User Name</th>

								<th>Mobile</th>

								<th>Plan Name</th>

								<th>Trial Purchase</th>
								<th>Date of Purchase</th>

								<th>Plan Upgraded</th>

								
								<th>Total Amount After Trial </th>
								<th>Local Order Point After Trial </th>
								<th>Final Amount After Trial </th>
								<th>Action </th>
					        </tr>
					    </thead>
					    <tbody>
					       <?php $i=1;foreach ($list_users as $key):

							$user_id=$key['user_id'];
							$mobile_number=$key['mobile_number'];
							// echo "SELECT sum(local_coin) as local_coin FROM local_coin_sync WHERE user_id='$user_id' and merchant_id='$merchant_id'";
							
							$defalut_plan="select count(plan.id) as total_count,u.created from membership_plan as plan inner join user_membership_plan as u on u.plan_id=plan.id where plan.user_id='$merchant_id' and plan.default_plan='y'
							and u.user_id='$user_id'";
							$defalutarray = mysqli_fetch_assoc(mysqli_query($conn,$defalut_plan));
							$defalutplan=$defalutarray['total_count'];
							if($defalutplan>0)
							{
							$created_date=$defalutarray['created'];
							// echo "SELECT sum(total_cart_amount) as total_amount FROM order_list WHERE user_id='$user_id' and merchant_id='$merchant_id' annd created_on>='$created_date'";
							// die;
							$local_coin=mysqli_fetch_assoc(mysqli_query($conn, "SELECT sum(local_coin) as local_coin FROM local_coin_sync WHERE user_mobile='$mobile_number' and merchant_id='$merchant_id' and order_date>='$created_date'"))['local_coin'];

							$total_amount=mysqli_fetch_assoc(mysqli_query($conn, "SELECT sum(total_cart_amount) as total_amount FROM order_list WHERE user_id='$user_id' and merchant_id='$merchant_id' and created_on>='$created_date'"))['total_amount'];

							?>

								<tr>

									<td><?php echo $i; ?></td>
									<td><?php echo $key['name']; ?></td>

									<td><?php echo $key['mobile_number']; ?></td>

									<td><?php echo $key['plan_name']; ?></td>  

									<td><?php echo date('F d, Y h:i:A', strtotime($created_date)); ?></td>	
									<td><?php echo date('F d, Y h:i:A', strtotime($key['paid_date'])); ?></td>	

									<td><?php if($key['is_upgrade']=="y"){echo "YES";} else { echo "NO";} ?></td>  

									<td><a class="mr-4" href="memberorder.php?user_id=<?php echo $user_id;?>&promo_id=<?php echo $key['plan_id'] ?>">

									<i class="fa fa-list" aria-hidden="true"></i>

									<?php echo number_format($total_amount,2); ?>

									</a></td>
									<td><?php echo number_format($local_coin,2); ?></td>
									<td><p style="font-weight:bold;"><?php echo number_format($local_coin+$total_amount,2); ?></p></td>
									<td><a href="addlocalcoin.php?plan_id=<?php echo $key['plan_id']?>&mobile=<?php echo $key['mobile_number'];?>">Add Local Point</a></td>  

								</tr>  

							<?php $i++;} endforeach ?>	
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
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
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
				"pageLength":50,
				dom: 'Bfrtip',
				 buttons: [
					'copy', 'csv', 'excel', 'pdf', 'print'
				]
				});
				
				
		});
	  
	  
	</script>
	
	
</body>

</html>

<?php 
session_start();
include("config.php");
if(!isset($_SESSION['login']))
{
	header("location:login.php");
}

$date = date('Y-m-d H:i:s');

// $profile_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$_SESSION['login']."'"));
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
   <?php
        $merchant_id=$_SESSION['login'];
		$sql="select users.name,users.mobile_number,membership_plan.plan_name,user_membership_plan.* from user_membership_plan inner join membership_plan on membership_plan.id=user_membership_plan.plan_id 
		inner join users on users.id=user_membership_plan.user_id where user_membership_plan.merchant_id='$merchant_id' and user_membership_plan.plan_active='y'";
		$result = $conn->query($sql);
		$rec_count =$result->num_rows;
		$rec_limit = 30;
		if( isset($_GET{'page'} ) ) {
            $page = $_GET{'page'} + 1;
            $offset = $rec_limit * $page;
         }else {
            $page = 0;
            $offset = 0;
         }
		$left_rec = $rec_count - ($page * $rec_limit);
		$sql2="select users.name,users.mobile_number,membership_plan.plan_name,user_membership_plan.* from user_membership_plan inner join membership_plan on membership_plan.id=user_membership_plan.plan_id 
		inner join users on users.id=user_membership_plan.user_id where user_membership_plan.merchant_id='$merchant_id' and user_membership_plan.plan_active='y' LIMIT $offset, $rec_limit";
		$result2 = $conn->query($sql2);
		if ($result2->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
			$list_users[] = $row;
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
           <form>

            <main class="main-wrapper clearfix" style="min-height: 522px;">
                <div class="container-fluid" id="main-content" style="padding-top:25px">
					<h3><?php echo $language['list_user']; ?></h3>
					<button type="button" class="btn btn-danger" onclick="window.location.href='smemberlist.php'">Clear Page</button>
					</br>
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
					          <th>Sr No</th>
								<th>User Name</th>

								<th>Mobile</th>

								<th>Plan Name</th>

								<th>Trial Purchase</th>
								<th>Date of Purchase</th>

								<th>Plan Upgraded</th>

								
								<th>Total Amount After Trial </th>
								<th>Local Order Point After Trial </th>
								<th>Final Amount After Trial </th>
								<th>Action </th>
					        </tr>
					    </thead>
					    <tbody>
					       <?php $i=1;foreach ($list_users as $key):

							$user_id=$key['user_id'];
							$mobile_number=$key['mobile_number'];
							// echo "SELECT sum(local_coin) as local_coin FROM local_coin_sync WHERE user_id='$user_id' and merchant_id='$merchant_id'";
							
							$defalut_plan="select count(plan.id) as total_count,u.created from membership_plan as plan inner join user_membership_plan as u on u.plan_id=plan.id where plan.user_id='$merchant_id' and plan.default_plan='y'
							and u.user_id='$user_id'";
							$defalutarray = mysqli_fetch_assoc(mysqli_query($conn,$defalut_plan));
							$defalutplan=$defalutarray['total_count'];
							if($defalutplan>0)
							{
							$created_date=$defalutarray['created'];
							// echo "SELECT sum(total_cart_amount) as total_amount FROM order_list WHERE user_id='$user_id' and merchant_id='$merchant_id' annd created_on>='$created_date'";
							// die;
							$local_coin=mysqli_fetch_assoc(mysqli_query($conn, "SELECT sum(local_coin) as local_coin FROM local_coin_sync WHERE user_mobile='$mobile_number' and merchant_id='$merchant_id' and order_date>='$created_date'"))['local_coin'];

							$total_amount=mysqli_fetch_assoc(mysqli_query($conn, "SELECT sum(total_cart_amount) as total_amount FROM order_list WHERE user_id='$user_id' and merchant_id='$merchant_id' and created_on>='$created_date'"))['total_amount'];

							?>

								<tr>

									<td><?php echo $i; ?></td>
									<td><?php echo $key['name']; ?></td>

									<td><?php echo $key['mobile_number']; ?></td>

									<td><?php echo $key['plan_name']; ?></td>  

									<td><?php echo date('F d, Y h:i:A', strtotime($created_date)); ?></td>	
									<td><?php echo date('F d, Y h:i:A', strtotime($key['paid_date'])); ?></td>	

									<td><?php if($key['is_upgrade']=="y"){echo "YES";} else { echo "NO";} ?></td>  

									<td><a class="mr-4" href="memberorder.php?user_id=<?php echo $user_id;?>&promo_id=<?php echo $key['plan_id'] ?>">

									<i class="fa fa-list" aria-hidden="true"></i>

									<?php echo number_format($total_amount,2); ?>

									</a></td>
									<td><?php echo number_format($local_coin,2); ?></td>
									<td><p style="font-weight:bold;"><?php echo number_format($local_coin+$total_amount,2); ?></p></td>
									<td><a href="addlocalcoin.php?plan_id=<?php echo $key['plan_id']?>&mobile=<?php echo $key['mobile_number'];?>">Add Local Point</a></td>  

								</tr>  

							<?php $i++;} endforeach ?>	
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
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
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
				"pageLength":50,
				dom: 'Bfrtip',
				 buttons: [
					'copy', 'csv', 'excel', 'pdf', 'print'
				]
				});
				
				
		});
	  
	  
	</script>
	
	
</body>

</html>
