<?php 
include("config.php");

if(!isset($_SESSION['admin']))
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

$total_rows = mysqli_num_rows(mysqli_query($conn, "SELECT  recharges.currency,recharges.amount,recharges.paypal_txn_id,recharges.created_on, users.name, users.email FROM  users RIGHT JOIN recharges ON users.id  =  recharges.user_id"));
$total_page_num = ceil($total_rows / $limit);

$start = ($page - 1) * $limit;
$end = $page * $limit;
$a_m="Recharge";
$recharge=mysqli_query($conn,"SELECT  recharges.currency,recharges.amount,recharges.paypal_txn_id,recharges.created_on, users.name, users.mobile_number FROM  users RIGHT JOIN recharges ON users.id  =  recharges.user_id ORDER BY users.id DESC LIMIT $start,$end");
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
	<table class="table table-striped">
	<thead>
      <tr>
        <th>User Name</th>
        <th>Mobile Number</th>
        <th>Currency</th>
		<th>Amount</th>
		<th>Paypal Transaction Id</th>
		<th>Date</th>
      </tr>
    </thead>
	<?php	
	while ($row=mysqli_fetch_assoc($recharge)){
	?>
	<tbody>
	<tr>
	<td><?php echo $row['name'];?></td>
	<td><?php echo $row['mobile_number'];?></td>
	<td><?php echo $row['currency'];?></td>
	<td><?php echo $row['amount'];?></td>
	<td><?php echo $row['paypal_txn_id'];?></td>
	<td><?php $date=$row['created_on'];
	         echo  $rechargedate=date("Y-m-d h:i:sa",$date);?></td>
	<td></td>
	
	
	</tr>
    </tbody>
	<?php
	}
	?>
	</table>
	<div style="margin:0px auto;">
 <ul class="pagination">
 <?php
   for($i = 1; $i <= $total_page_num; $i++)
   {
    if($i == $page)
    {
     $active = "class='active'";
    }
    else
    {
     $active = "";
    }
    echo "<li $active><a href='?page=$i'>$i</a></li>";
   }
 ?>
 </ul>
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
