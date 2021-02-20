<?php 
include("config.php");
$merchant_tab="y";
$me="report";
$date = date('Y-m-d H:i:s');
$start_dt = $date;
$end_dt = $date;
if(!isset($_SESSION['login']) || empty($_SESSION['login'])){
	header("location:logout.php");
}
$merchant = $_SESSION['login'];
if(isset($_POST['search'])){
	$start_dt = $_POST['start_dt'];
	$end_dt = $_POST['end_dt'];
}
$sql = "
	SELECT *
    FROM order_list
    inner join users on order_list.user_id = users.id
    WHERE created_on >= '$start_dt' AND created_on <= '$end_dt' AND merchant_id = '$merchant'";
$result = mysqli_query($conn, $sql);
$reports = array();
while($row = mysqli_fetch_assoc($result)){
	$products = explode(",", $row['product_id']);
	$qtys = explode(",", $row['quantity']);
	$amounts = explode(",", $row['amount']);
	for($i = 0; $i < count($products); $i++){
		$product_id = $products[$i];
		$sql = "SELECT *
                FROM products
                WHERE id = '$product_id'";
        $product = mysqli_fetch_assoc(mysqli_query($conn, $sql));
        $item = array(
        	'name' => $product['product_name'],
        	'category' => $product['category'],
        	'qty' => $qtys[$i],
        	'amounts' => $amounts[$i],
        	'date' => substr($row['created_on'], 0, 10)
        );
        array_push($reports, $item);
	}
}
function cmp($a, $b){
    return strcmp($a['category'], $b['category']);
}
usort($reports, "cmp");
?>
<!DOCTYPE html>
<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">

<head>
    <?php include("includes1/head.php"); ?>
    <link href="js/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
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
				
					<form action="" method="post" style="width:100%;">
						<input type="hidden" value="<?= $_SESSION['login'];?>" name="user_id">
						<div class="col-sm-12">
							<div class="col-sm-3 search-item">
								<div class="form-group">
									<label>Starting Date: </label>
									<div class="input-group date form_datetime form_datetime bs-datetime">
                                        <input type="text" size="16" class="form-control" name="start_dt" value="<?= $start_dt;?>">
                                        <span class="input-group-addon" style="padding: 0.3rem;">
                                            <button class="btn default date-set" type="button" style="padding: 0.3rem;">
                                                <i class="fa fa-calendar"></i>
                                            </button>
                                        </span>
                                    </div>
									<!-- <input type="date" name="start_dt" class="form-control form_datetime" value="<?= $start_dt;?>"/> -->
								</div>
							</div>
							<div class="col-sm-3 search-item">
								<label>End Date: </label>
									<div class="input-group date form_datetime form_datetime bs-datetime">
                                        <input type="text" size="16" class="form-control" name="end_dt" value="<?= $end_dt;?>">
                                        <span class="input-group-addon" style="padding: 0.3rem;">
                                            <button class="btn default date-set" type="button" style="padding: 0.3rem;">
                                                <i class="fa fa-calendar"></i>
                                            </button>
                                        </span>
                                    </div>
								<!-- <div class="form-group">
									<label>End Date: </label>
									<input type="date" name="end_dt" class="form-control" value="<?= $end_dt;?>"/>
								</div> -->
							</div>
							
							<div class="col-sm-2 search-item" >
								<input type="submit" value="Search" class="btn btn-default form-control" name="search">
							</div>
							<div class="col-sm-2 search-item" >
								<?php if(count($reports) > 0){?>
									<a href="print_report.php?start_dt=<?= $start_dt;?>&end_dt=<?= $end_dt;?>&user=<?= $_SESSION['login'];?>" class="btn btn-default form-control" >Report</a>
								<?php }?>
								
							</div>
						</div>
					</form>
					<table class="table table-striped display" style="width:100% !important;">
						<thead>
						<tr>
							<th>No</th>
							<th>Date</th>
							<th>Category</th>
							<th>Product</th>
							<th>Qty</th>
							<th>Amounts</th>
						</tr>
						</thead>
						<tbody>
							<?php for($i = 0; $i < count($reports); $i++){?>
								<tr>
									<td><?= $i + 1;?></td>
									<td><?= $reports[$i]['date'];?></td>
									<td><?= $reports[$i]['category'];?></td>
									<td><?= $reports[$i]['name'];?></td>
									<td><?= $reports[$i]['qty'];?></td>
									<td><?= $reports[$i]['amounts'];?></td>
								</tr>
							<?php }?>
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
	<script src="js/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
	<!-- <script src="js/components-date-time-pickers.min.js" type="text/javascript"></script> -->
</body>

</html>
<script>
$(document).ready(function() {
 //$('.display').DataTable();
 $(".form_datetime").datetimepicker({
    autoclose: true,
    format: "yyyy-mm-dd  hh:ii:ss",
    fontAwesome: true
});
});
</script>

<style>
.dataTables_wrapper {
    width: 100%;
}
</style>
