<?php 
include("config.php");

if(!isset($_SESSION['admin']))
{
	header("location:login.php");
}

if(isset($_POST['start_dt'])){
	$start_dt = $_POST['start_dt'];
	$end_dt = $_POST['end_dt'];
	$merchant = $_POST['merchant_id'];
	if($start_dt == "") $start_dt = "0000-00-00";
	if($end_dt == "") $end_dt = "9999-99-99";

	$sql = "SELECT date, a.name user_name, a.merchant_id, a.user_id, invoice_no, users.merchant_code, users.name merchant_name, a.quantity, a.amount, a.mobile_number user_phone, users.mobile_number merchant_phone, users.business1, users.business2
			FROM (
			SELECT created_on DATE, users.name, merchant_id, quantity, amount, mobile_number, invoice_no, user_id
			FROM order_list INNER JOIN users ON users.id = order_list.user_id 
			WHERE created_on > '".$start_dt."' AND created_on < '".$end_dt."' AND merchant_id LIKE '%".$merchant."%') a INNER JOIN users ON a.merchant_id = users.id
			ORDER BY date desc";
			
	$transaction_data = mysqli_query($conn, $sql);
} else {
	$sql = "SELECT date, a.name user_name, a.merchant_id, a.user_id, invoice_no, users.merchant_code, users.name merchant_name, a.quantity, a.amount, a.mobile_number user_phone, users.mobile_number merchant_phone, users.business1, users.business2
			FROM (
			SELECT created_on DATE, users.name, merchant_id, quantity, amount, mobile_number, invoice_no, user_id
			FROM order_list INNER JOIN users ON users.id = order_list.user_id 
			ORDER BY created_on desc ) a INNER JOIN users ON a.merchant_id = users.id 
			ORDER BY date desc";
	$transaction_data = mysqli_query($conn, $sql);

} 

$sql_merchant = "SELECT id, NAME
				FROM users
				WHERE users.user_roles = '2' AND isLocked = '0'";
$merchant_rows = mysqli_query($conn, $sql_merchant);

$nature_array = array(
	"Foods and Beverage, such as restaurants, healthy foods, franchise, etc",
	"Motor Vehicle, such as car wash, repair, towing, etc",
	"Hardware, such as household, building, renovation to end users",
	"Grocery Shop such as bread, fish, etc retails shops",
	"Clothes such as T-shirt, Pants, Bra, socks,etc",
	"Business to Business (B2B) including all kinds of businesses"
);
$nature_image = array(
	"foods.jpg",
	"car.jpg",
	"household.jpg",
	"grocery.jpg",
	"clothes.jpg",
	"b2b.jpg"
);
$a_m="Transaction";
?>
<!DOCTYPE html>
<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">

<head>
    <?php include("includes1/head.php"); ?>
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
	/* 10-17 customize */
	.search-item{
		display:inline-block;
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
				
					<form action="transaction.php" method="post" style="width:100%;">
						<div class="col-sm-12">
							<div class="col-sm-3 search-item">
								<div class="form-group">
									<label>Starting Date: </label>
									<input type="date" name="start_dt" class="form-control" value="<?php if(isset($_POST['start_dt'])) echo $_POST['start_dt'];?>"/>
								</div>
							</div>
							<div class="col-sm-3 search-item">
								<div class="form-group">
									<label>End Date: </label>
									<input type="date" name="end_dt" class="form-control" value="<?php if(isset($_POST['end_dt'])) echo $_POST['end_dt'];?>"/>
								</div>
							</div>
							<div class="col-sm-3 search-item">
								<div class="form-group">
									<label>Merchant: </label>
									<select class="form-control select2" name="merchant_id">
										<option ></option>
										<?php while($result = mysqli_fetch_assoc($merchant_rows)){ ?>
										<option value="<?php echo $result['id'];?>"><?php echo $result['NAME'];?></option>
										<?php }?>
									</select>
								</div>
							</div>
							<div class="col-sm-2 search-item" >
								<input type="submit" value="Search" class="btn btn-default form-control" >
							</div>
						</div>
					</form>
					<table class="table table-striped display">
						<thead>
						<tr>
							<th>No</th>
							<th>Print</th>
							<th>Invoice No</th>
							<th>Date</th>
							<th>Username</th>
							<th>Telephone</th>
							<th>Amount</th>
							<th>Merchant</th>
							<th>Nature</th>
						</tr>
						</thead>
						
						<tbody>
							<?php
							$index = 0;
							while($transaction = mysqli_fetch_assoc($transaction_data)){
								$index++;
							?>
							<?php 
							    $year = substr($transaction['date'], 0, 4);
                        		$month = substr($transaction['date'], 5, 2);
                        		$day = substr($transaction['date'], 8, 2);
                        	?>
							<tr>
								<td><?php echo $index; ?></td>
								<td><a target="_blank" href="print.php?id=<?php echo $transaction['user_id'];?>&merchant=<?php echo $transaction['merchant_id'];?>">Print</a></td>
								<td><?php echo $transaction['merchant_code']. '-'.$day.$month.$year.'-'.str_pad($transaction['invoice_no'], 4, '0', STR_PAD_LEFT) ;?></td>
								<td><?php echo substr($transaction['date'], 0, 10); ?></td>
								<td><?php echo $transaction['user_name']; ?></td>
								<td><?php echo $transaction['user_phone']; ?></td>
								<?php 
								    $qty_array = explode(",", $transaction['quantity']);
								    $price_array = explode(",", $transaction['amount']);
								    $total_amount = 0;
								    for($i = 0; $i < count($price_array); $i++){
								        $total_amount += $qty_array[$i] * $price_array[$i];
								    }
								?>
								<td><?php echo $total_amount; ?></td>
								<td><?php echo $transaction['merchant_name']; ?></td>
								<?php 
									$business1 = "";
									$business2 = "";
									for($i = 0; $i < count($nature_array); $i++){
										if($transaction['business1'] == $nature_array[$i])
											$business1 = $nature_image[$i];
										if($transaction['business2'] == $nature_array[$i])
											$business2 = $nature_image[$i];
									}
								?>
								<td>
								    <?php if($business1 != "") {?>
									    <img src="/img/<?php echo $business1;?>" style="width: 30px; height: 30px;"/>
									<?php } ?>
									<?php if($business2 != "") {?>
									    <img src="/img/<?php echo $business2;?>" style="width: 30px; height: 30px;"/>
									<?php }?>
								</td>
							</tr>
							<?php
							}
							?>
						</tbody>
					</table>
					
				</div>
			</main>
        </div>
    </div>
    
    <!-- /.widget-bg -->

    <!-- /.content-wrapper -->
    <?php include("includes1/footer.php"); ?>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">

	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
</body>

</html>
<script>
$(document).ready(function() {
 $('.display').DataTable();
});
</script>

<style>
.dataTables_wrapper {
    width: 100%;
}
</style>
