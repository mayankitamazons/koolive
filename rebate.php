<?php 
 include("config.php");
if(!isset($_SESSION['login']))
{
	header("location:login.php");
}
if(isset($_GET['page']))
{
 $page = $_GET['page'];
}
else
{
 $page = 1;
}

$limit = 25; 
 if($_SESSION['login'])
   {
	   $user_id=$_SESSION['login'];
   }
    $query="SELECT m.name as merchant_name,o.*,u.name,u.id as u_id,u.balance_usd,u.balance_inr,u.balance_myr from order_list
 as o inner join users as u on u.id=o.user_id inner join users as m on m.id=o.merchant_id Where o.user_id='$user_id'  and u.user_roles = 1 and u.isLocked=0 and total_rebate_amount>0 ORDER BY o.id DESC";
  
   $userrecord = mysqli_query($conn,$query);

 $total_rows = mysqli_num_rows($userrecord);

$total_page_num = ceil($total_rows / $limit);

$start = ($page - 1) * $limit;
$end = $page * $limit;

// $user = mysqli_query($conn, "SELECT * FROM users Where `user_roles` = 1 and `isLocked` = 0 ORDER BY id DESC ");

?>
<!DOCTYPE html>
<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">

<head>

<style>
.pagination {
  display: inline-block;
  padding-left: 0;
  margin: 20px 0;
  border-radius: 4px;
 }
 td.del {
    cursor: pointer;
}
 .pagination>li {
  display: inline;
 }
 .pagination>li:first-child>a, .pagination>li:first-child>span {
  margin-left: 0;
  border-top-left-radius: 4px;
  border-bottom-left-radius: 4px;
 }
 .pagination>li:last-child>a, .pagination>li:last-child>span {
  border-top-right-radius: 4px;
  border-bottom-right-radius: 4px;
 }
 .pagination>li>a, .pagination>li>span {
  position: relative;
  float: left;
  padding: 6px 12px;
  margin-left: -1px;
  line-height: 1.42857143;
  color: #337ab7;
  text-decoration: none;
  background-color: #fff;
  border: 1px solid #ddd;
 }
 .pagination a {
  text-decoration: none !important;
 }
 .pagination>.active>a, .pagination>.active>a:focus, .pagination>.active>a:hover, .pagination>.active>span, .pagination>.active>span:focus, .pagination>.active>span:hover {
  z-index: 3;
  color: #fff;
  cursor: default;
  background-color: #337ab7;
  border-color: #337ab7;
 }
</style>
 
    <?php include("includes1/head.php"); ?>

</head>

<body class="header-light sidebar-dark sidebar-expand pace-done">

    <div class="pace  pace-inactive">
        <div class="pace-progress" data-progress-text="100%" data-progress="99" style="transform: translate3d(100%, 0px, 0px);">
            <div class="pace-progress-inner"></div>
        </div>
        <div class="pace-activity"></div>
    </div>

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
				<input type="hidden" id="credit_amount_input"/>
				<input type="hidden" id="user_id"/>
				<input type="hidden" id="w_type_input"/>
				<input type="hidden" id="order_id"/>
				<table class="table table-striped" id="example">
                    <thead>
                      <tr>
                		<th>Particular</th>
                      
                     
                        <th>Id /Invoice No</th>
                        <th>Merchant Name</th>
                        <th>Order Amount</th>
                        <th>Rebate Amount</th>
                		<th>Paid By wallet</th>
                		
                		
                		<th>Date</th>
						<th>Credited Date</th>
                		<th>Rebate Status</th>
                		
                		
                      </tr>
                    </thead>
                	
                	<tbody>
                    	<?php
                    	$i=1;
                    	while($row=mysqli_fetch_assoc($userrecord)){
							// print_R($row);
						
							$r_status=$row['rebate_credited'];
							   if($r_status=="y")
								$r_label="Credited";
								 if($r_status=="rejected")
								$r_label="Rejected";
							   if($r_status=="n")
								$r_label="Requested";
							$order_status=$row['status'];
								if($order_status)
									$s_label="DONE";
								else 
									$s_label="PENDING";
							 ?>
                        	  <tr style="font-weight: bold;">
                        		 <td> <?php echo $i; ?> </td>
                              
                                <td><?php echo $row['invoice_no'];?></td>
                                <td><?php echo number_format($row['merchant_name'],2);?></td>
								<td><?php echo number_format($row['total_cart_amount'],2);  ?></td>
                        		<td><?php echo number_format($row['total_rebate_amount'],2);  ?></td>
                        		<td><?php echo number_format($row['wallet_paid_amount'],2);  ?></td>
                        		
                        		
                        		
                        		
                        		
                        		<td><?php  $date=$row['created_on'];  
                        	        echo $joinigdate=date("Y-m-d h:i:sa",strtotime($date));  ?>
                        	    </td>
								
								<td><?php  $rebate_credited_date=$row['rebate_credited_date'];
										 if($r_status=="y")
										{
											echo $joinigdate=date("Y-m-d h:i:sa",strtotime($rebate_credited_date));
										}									
										else echo "--"; 
										?>
                        	    </td>
								<td><?php echo $r_label; ?></td>
								 
                        	    
							  </tr>
                    	<?php
                            $i++;  
                    	}?>
                	</tbody>
	
	            </table>

	             <div>
                    
                   
                  <div class="modal fade" id="ProcessModel" role="dialog" >
                    <div class="modal-dialog modal-sm">
                      <div class="modal-content" id="modalcontent">
                          <div class="modal-body">
                              <h3>Are you sure you want to credit Rm. <span id="credit_amount_label"></span> to <span id="credit_mobile_label"></span>?</h3>
                          </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default credit_confirm_amount">Yes</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                      </div>
                    </div>
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

</html>
<script>
$(document).ready(function() {
 $('#example').DataTable();
});
</script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">

	<script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>

