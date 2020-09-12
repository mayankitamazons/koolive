<?php 
include("config.php");
$a_m="coupon";
if(!isset($_SESSION['admin']))
{
	header("location:login.php");
}
$coupon = $_REQUEST['coupon_id'];
$subscription_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id,title,coupon_code FROM coupon WHERE  id = '$coupon'"));
$merchant_id=$_SESSION['login'];
$subscription_name = $subscription_data['title'];
	$sql = "SELECT order_list.*, coupon.*, users.* FROM `coupon` INNER JOIN order_list ON coupon.coupon_code = order_list.coupon_code INNER JOIN users ON order_list.user_id = users.id WHERE coupon.coupon_code ='".$subscription_data['coupon_code']."'";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
	    // output data of each row
	    while($row = $result->fetch_assoc()) {
	        $list_users[] = $row;
	    }
	}
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
					
					<div class="well" style="width:100%">
						<h3>List Users for <?php echo $subscription_name ?> </h3>
						
						<?php if (empty($list_users)): ?>
							<h4>No Data Found!</h4>
						<?php endif ?>
						<?php if (!empty($list_users)): ?>
								
							
						<table class="table table-striped">
							<tr>
								<th>User Name</th>
								<th>Mobile</th>
								<th>Invoice No</th>
								<th>Total Order Amount</th>
								<th>Coupon Discount</th>
							</tr>					
							<?php foreach ($list_users as $key):
							$user_id=$key['user_id'];
							
							?>
								<tr>
									<td><?php echo $key['name']; ?></td>
									<td><?php echo $key['mobile_number']; ?></td>	
									<td><?php echo $key['invoice_no']; ?></td>	
									
									<td><?php echo $key['total_cart_amount']; ?></td>
									<td><?php echo $key['coupon_discount']; ?></td>
								</tr>
							<?php endforeach ?>								
						</table>
						<?php endif ?>	
						
					</div>
					
				</div>
			</main>
        </div>
        <!-- /.widget-body badge -->
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
	function transfer(user_id,mobile_number) {
		$('#fund_user_id').val(user_id);
		$('#reciver_mobile_number').val(mobile_number);
		$('#transfer_to').val(mobile_number);
		$('#fund_wallet_model').modal('show');
	}
	$('#confirm_fund').click(function () {
		fund_engine();
	});
	$('#fund_pass').keyup(function (){
		// fund_engine();
	});
	$('#cancel_transfer').click(function (){
		$('#fund_wallet_input_modal').modal('hide');
	});
	function fund_engine(){
		if ($('#fund_pass').val() == '<?php echo $profile_data["fund_password"];?>') {
			$('.error-block-fund-pass').hide();
			// alert('success');
			$('#fund_wallet_model').modal('hide');
			$('#fund_wallet_input_modal').modal('show');
			// $('#fund_pass').val('');

		}else {
			$('.error-block-fund-pass').show();
		}
	}
	$('#confirm_transfer').click(function(){
		var transfer_to=$('#transfer_to').val();
		// alert(transfer_to);
		if(transfer_to=='')
		{
			$('.error-block-for-mobile').html('Mobile No is Required');
			$('.error-block-for-mobile').show();
			return false;
		}
		else
		{
			// $('.error-block-for-mobile').html('');
			$('.error-block-for-mobile').hide();
		}
		$.post('transfer_module.php', {mobile_number:$('#transfer_to').val(), sender_id: "<?php echo $_SESSION['login'];?>"}, function (data){
			if (data != -1) { 
				$('.error-block-for-mobile').hide();
				var balance_data = JSON.parse(data);
				// alert(balance_data);
				var postdata = {};
				postdata.created = new Date();
				postdata.created = postdata.created.getTime();
				postdata.sender_name = '<?php echo $profile_data["name"] ?>';
				postdata.sender_id ="<?php echo $_SESSION['login'];?>",
				postdata.receiver_id = balance_data['id'];
				postdata.amount = $('#transfer_amount').val();
				postdata.wallet_type =balance_data['special_coin_name'];
			var wallet_bal =balance_data['CF'];
				postdata.special_wallet ="y";       
				if (postdata.sender_id == postdata.receiver_id) {
					$('.error-block-for-mobile').html('This phone number is not valid');
					$('.error-block-for-mobile').show();
				}
				else {
					$('.error-block-for-mobile').hide();
					if (postdata.amount == '') {
						$('.error-block-for-amount').show();
					}else {
						$('.error-block-for-amount').hide();
						if (postdata.wallet_type == '') {
							$('.error-block-for-wallet-type').show();
						} else {
							$('span.current-balance>b').html(parseFloat(balance_data[postdata.wallet_type]));
							$('span.current-balance').show();

							$('.error-block-for-wallet-type').hide();
							if (parseFloat(postdata.amount) > parseFloat(wallet_bal)) {
								$('span.error-block-for-amount').html('Your balance is not enough to transfer');
								$('span.error-block-for-amount').show();
							}
							else {
								$('span.error-block-for-amount').html('Please type amount to transfer');
								$('span.error-block-for-amount').hide();
								// alert('success');
							
								$.post('transfer_module.php', postdata, function(result){
									// location.href = 'dashboard.php';
									$('form#form-transfer').submit();  
								});
							}
						}
					}
				}
			}
			else {
				$('.error-block-for-mobile').show();
			}
		});
	});
</script>
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
