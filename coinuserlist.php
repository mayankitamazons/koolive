<?php 
session_start();
include("config.php");
$me="special_coin_name";
// if(!isset($_SESSION['login']))
// {
	// header("location:login.php");
// }

if(isset($_GET['page']))
{
	$page = $_GET['page'];
}
else
{
	$page = 1;
}

$limit = 50;
$date = date('Y-m-d H:i:s');
$end_dt = $date;
$filter="";
// $m_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$_SESSION['login']."'"));
// print_R($m_data);
// die;
$loginset=$_SESSION['login'];
 $query="select scoin.*,users.user_roles,users.name,users.mobile_number from special_coin_wallet as scoin inner join users on users.id=scoin.user_id where scoin.coin_balance>0 and scoin.merchant_id='".$_SESSION['login']."'";
$qdata=mysqli_query($conn,$query);
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

    <div id="wrapper" class="wrapper">
        <!-- HEADER & TOP NAVIGATION -->
        <?php include("includes1/navbar.php"); ?>
        <!-- /.navbar -->
        <div class="content-wrapper">
            <!-- SIDEBAR -->
            <?php include("includes1/sidebar.php"); ?>
            <!-- /.site-sidebar -->
			<?php
			   $m_data=$profile_data;
			   $a_user_id=$profile_data['id'];
  			   $totalcoin = mysqli_fetch_assoc(mysqli_query($conn,"SELECT sum(amount) as total_bal FROM `tranfer` WHERE `type_method` = 'topup' and receiver_id='".$_SESSION['login']."'"))['total_bal'];
  			   $walletcoin = mysqli_fetch_assoc(mysqli_query($conn, "SELECT sum(coin_balance) as  total_bal FROM special_coin_wallet inner join users on users.id=special_coin_wallet.user_id WHERE merchant_id='".$_SESSION['login']."'"))['total_bal'];
  			   $coininmerchant = mysqli_fetch_assoc(mysqli_query($conn, "SELECT sum(coin_balance) as  total_bal FROM special_coin_wallet inner join users on users.id=special_coin_wallet.user_id and users.user_roles='2' WHERE merchant_id='".$_SESSION['login']."'"))['total_bal'];
			
			  
			?>

            <main class="main-wrapper clearfix" style="min-height: 522px;">
                <div class="container-fluid" id="main-content" style="padding-top:25px">
					<h3><?php echo $m_data['special_coin_name']; ?> <?php echo $language['user_list']; ?></h3>
						<div class="row" style="font-weight:bold;">
						  <div class="col-md-4"><?php echo $language['total_coin_created']; ?>: <?php echo number_format($totalcoin,2);?></div>
						  <div class="col-md-4"><?php echo $language['coin_in_market']; ?>: <?php echo number_format($totalcoin-$m_data['balance_usd'],2);?></div>
						  <div class="col-md-4"><?php echo "Coin in merchant hand"; ?>: <?php echo number_format($coininmerchant,2);?></div>
						 <div class="col-md-4"><?php echo $language['coin_in_wallet']; ?>: <?php echo number_format($m_data['balance_usd'],2);?></div>
						 
						</div>
						<button  style="margin-bottom:2%;" class="btn btn-primary" onclick='transfer("<?php echo $_SESSION['login'];?>","")'>Transfer</button>
								
					<table class="table table-striped kType_table" id="kType_table" style="width:100% !important;">
					    <thead>
					        <tr>
					        <th>User ID</th>
								<th>Name</th>
								<th>Mobile</th>
								<th>User Type</th>
								
								<th>Cur. Balance</th>
								<th>Last Trascation</th>
								<th>Total Order Amount</th>
								<th>Action</th>
                		
					        </tr>
					    </thead>
					    <tbody>
					       <?php
					
							while($row = mysqli_fetch_assoc($qdata))
							{
								$l_user_id=$row['user_id'];
								$user_roles=$row['user_roles'];
								if($user_roles==2)
								$user_t="Merchant";
								else
								$user_t="Member";
								$merchant_id=$row['merchant_id'];
								$last=mysqli_fetch_assoc(mysqli_query($conn, "SELECT created_on FROM order_list WHERE user_id='$l_user_id' and merchant_id='$merchant_id' order by id desc limit 0,1"));
								$total_amount=mysqli_fetch_assoc(mysqli_query($conn, "SELECT sum(total_cart_amount) as total_amount FROM order_list WHERE user_id='$l_user_id' and merchant_id='$merchant_id'"))['total_amount'];
								if($last)
								{
									// print_R($last);
									$last_tras=$last['created_on'];
								}
								else
								{
									$last="--";
								}
								?>
								<tr>
									<td><?php echo $row['user_id']; ?></td>
									<td><?php echo $row['name']; ?></td>
									<td><?php echo $row['mobile_number']; ?></td>
									<td><?php echo $user_t; ?></td>
								
									<td><?php echo number_format($row['coin_balance'],2); ?></td>
									<td><?php if($last){echo $last_tras;}else { echo $last;} ?></td>
									<td><?php echo number_format($total_amount,2);?></td>
									<td>
									<button class="btn btn-primary" onclick='transfer("<?php echo $_SESSION['login'];?>","<?php echo $row['mobile_number'];?>","<?php echo $row['name']; ?>")'>Transfer</button>
									
									</td>
									
								</tr>
								<?php
								// die;
							}
							?>
					    </tbody>
					</table>
					
					
	

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
	



   <?php $user_name=$profile_data['name']; ?>   
  
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
