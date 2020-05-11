<?php 
include("config.php");
if(!isset($_SESSION['admin']))
{
	header("location:login.php");
}
if(isset($_POST['choose_agent']))
{
	extract($_POST);
	// $m_id = mysqli_fetch_assoc(mysqli_query($conn,"SELECT id FROM users WHERE name='$tags_merchant' and user_roles='2' LIMIT 1"))['id'];  
	//$query="select unrecoginize_coin.*,users.name as merchant_name,users.special_coin_name from unrecoginize_coin inner join users on users.id=unrecoginize_coin.user_id where unrecoginize_coin.merchant_id='$m_id' and status=1 order by unrecoginize_coin.id desc";
	$query="select unrecoginize_coin.*,users.name as merchant_name,users.special_coin_name from unrecoginize_coin inner join users on users.id=unrecoginize_coin.merchant_id where unrecoginize_coin.user_id='$m_id' and status=1 order by unrecoginize_coin.id desc";
		  
	 $coin_in_market=partnerbal($m_id,$conn);
	$u_query = mysqli_query($conn,$query);   
}
function partnerbal($coin_merchant_id,$conn)
{
	// $q="SELECT sum(coin_balance) as total_amount FROM `special_coin_wallet` WHERE `merchant_id` ='$coin_merchant_id'";
	$q="SELECT sum(coin_balance) as total_amount FROM `special_coin_wallet` inner join users on users.id=special_coin_wallet.user_id and users.user_roles='2' WHERE `merchant_id` ='$coin_merchant_id'";
	$parq=mysqli_query($conn,$q);  
	$p_total=mysqli_fetch_assoc($parq);
	return $p_total['total_amount'];
} 
$a_m="a_p_l";
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
	.select2-dropdown {
	  z-index: 90019;
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
					<h2 class="text-center wallet_h">Associated Partner List</h2>
					<h2 style="color:red;"><?php  if(isset($_SESSION['s'])){ echo $_SESSION['s']; unset($_SESSION['s']);}?></h2>
					<form method="post">
						<div class="row">
							
							
							<div class="col-md-3">
								<div class="form-group">
									<?php 
									 $q="SELECT id,name FROM users WHERE user_roles = '2' and name!='' ORDER BY name ASC";
									 
									 	$qMerchant = mysqli_query($conn,$q);
									 
									 if($_POST['choose_agent'])
									 {
										 $q2="select u.id,u.name,u.special_coin_name,c.id as u_coin_id,c.coin_max_limit,c.coin_class,c.coin_limit from users as u left join unrecoginize_coin as c
										on u.id=c.merchant_id and c.user_id='$m_id' and c.status='1' where u.user_roles='2' and u.name!='' and u.special_coin_name!=''  order by u.name asc";
									    $qMerchant1=mysqli_query($conn,$q2);
									 }
									
									?>
										<label for="tags_merchant">Choose a merchant</label>
									
									
									<select class="tags_merchant_select" name='m_id'>
									    <option value='-1'>Select Merchant</option>
										<?php 
										  
											while($row = mysqli_fetch_assoc($qMerchant)){ ?>
									       <option <?php if($m_id==$row['id']){ echo "selected";} ?> value="<?php  echo $row['id'];?>"><?php echo $row['name'];?></option>
											<?php } ?>

									</select>
								</div>  
								
							</div> 
							
							<div class="col-md-6" >
								<input type="submit" class="btn btn-lg btn-outline-primary" name="choose_agent" value="Choose"/>
							 
								<button type="button" class="btn btn-danger" onclick="window.location.href='./partnerlist.php'">Clear Page</button>
				
							</div>

							
						</div>    
					</form>   
					<?php if($coin_in_market){ 
					$show_coin=number_format($coin_in_market,2);
					echo "<p style='color:red;font-size:18px;'>Total coin hold by other merchants: $show_coin</p>";} ?>
					<form method="post" id="form1" action="update_partner.php">
					<input type="hidden" id='all_p_list' name="all_p_list" /> 
					<input type="hidden" name="merchant_id" value="<?php echo $m_id;?>"/>
					<?php
					    if($_POST['choose_agent'])
						{
							
							 $total_count=mysqli_num_rows($u_query); ?>
							 <input type="submit" name="submit" class="btn btn-lg btn-outline-primary" value="Add"/>
							<table class="table table-striped kType_table" id="kType_table">
								<thead>
									<tr>
									<td><input id="checkAll" type="button" value="Check All"><td>		
									<th>S.No</th>
									<th><?php echo "MERCHANT NAME"; ?></th>
									<th>Special Coin Name</th>
									<th>Coin Value</th>
									<th>Coin Used</th>
									<th>Pending Limit</th>
									<th>Coin in Merchant Hand</th>
									<th><?php echo "Limit Class"; ?></th>
									<th>Limit Class Pending Point</th>
									
									<th>Created</th>
									
									
									</tr>
								</thead>
								<?php
								  
								$i=0; while($row = mysqli_fetch_assoc($qMerchant1)){
									// print_r($row);
									if($row['u_coin_id']){ 
										$s='y';
										$partner_merchant_id=$row['id']; 
										$u_coin_id=$row['u_coin_id']; 
										 $totalcoin="SELECT sum(amount) as totalamount FROM tranfer WHERE MONTH(created_date) = MONTH(CURRENT_DATE()) AND YEAR(created_date) = YEAR(CURRENT_DATE()) and receiver_id='$m_id' and coin_merchant_id='$partner_merchant_id'";
										$acceptedcoin = mysqli_fetch_assoc(mysqli_query($conn,$totalcoin));
										$totalamount=$acceptedcoin['totalamount'];
										$coin_max_limit=$row['coin_max_limit'];
										 $pending_limit=$row['coin_max_limit']-$acceptedcoin['totalamount'];
										
										 $partner_bal=partnerbal($partner_merchant_id,$conn);
										 
										 $pen=$row['coin_limit']-$partner_bal;
										if($pen<0)
											$pen=0;
										} else {  $s='n'; $partner_merchant_id=''; 
										$u_coin_id='';}
									?>
								<tr style="<?php if($s=="y"){ echo "color:#51d2b7";} ?>">
									<td>
									  
									<input type="hidden" name="p_list[]" value="<?php echo $u_coin_id; ?>"/>
									<input type="hidden" name="m_list[]" value="<?php echo $row['id']; ?>"/>
									<input type="checkbox"  <?php if($s=="y"){echo "checked";}   ?> name="tick_checkbox" class="tick_checkbox" value="<?php echo $i;?>" m_id="<?php echo $row['id']; ?>"/><td>	
									<td><?php echo $i+1; ?></td>	
									<td><?php echo $row['name']; ?></td>	
									<td><?php echo $row['special_coin_name']; ?></td>	
									<td><input type="text" name="coin_max_limit[]" style="max-width:40px;" class="form-control" value="<?php if($row['coin_max_limit']){echo $row['coin_max_limit'];} else {echo 100;} ?>"></td>	
									<td><?php if($s=="y"){ echo number_format($totalamount,2); } else { echo "--";}?></td>	
									<td><?php if($s=="y"){ echo number_format($pending_limit,2);} else { echo "--";} ?></td>	
									<td><?php  echo number_format($partner_bal,2);?></td>	
									
									<td>
									<select class="form-control" name="limit_class[]" id="limit_class">
									   
										<?php  foreach($limit_class as $k=>$v){?>
										<option  <?php if($row['coin_class']==$k){echo "selected";} ?> value="<?php echo $k?>" coin_limit="<?php echo $v;?>"><?php echo $k."-".$v." Coin" ?></option>
										<?php } ?>
										 
									 </select> 
									</td>
									<td><?php if($s=="y"){ echo number_format($pen,2);} else { echo "--";} ?></td>	
									<td><?php echo date('d M Y h:i A', strtotime($row['created'])); ?></td>
									
								</tr>  
								<?php $i++; } ?>
								<tbody>
								</tbody>
							</table>
							
						<?php } ?>
					
						
						
						
					
						
					</form>
					
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
	






	<script>
	     var merchant_tags = [];
	    $(document).ready(function(){
			$(document).on('click', '#checkAll', function() {
				if ($(this).val() == 'Check All') {
				  $('.tick_checkbox').prop('checked', true);
				  $(this).val('Uncheck All');
				} else {
				  $('.tick_checkbox').prop('checked', false);
				  $(this).val('Check All');
				}  
			  });
			
			$('#form1').submit(function () {
            var favorite = [];
            $.each($("input[name='tick_checkbox']:checked"), function(){
                favorite.push($(this).val());
            });
			var all_list=favorite.join(",");
			// $('#all_p_list').val(all_list);
			// alert(all_list);
			// return  false;
			$('#all_p_list').val(all_list);
            // alert("My favourite sports are: " + all_list);
			// return false;
        });
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
		
			}
		});
		
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
				
				
	});
	  
	  
	</script>
	<script type="text/javascript">

	  $(document).ready(function() {
    $(".tags_merchant_select").select2();
});
</script>



	
</body>

</html>
