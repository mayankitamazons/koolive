<?php 
session_start();
include("config.php");
$me="partner_list";
if(!isset($_SESSION['login']))
{
	header("location:login.php");
}
$date = date('Y-m-d H:i:s');
if(isset($_POST['add_coin'])){
		extract($_POST);
		$user_id=$_SESSION['login'];
		$m_id = mysqli_fetch_assoc(mysqli_query($conn,"SELECT id FROM users WHERE name='$merchant_name' and user_roles='2' LIMIT 1"))['id'];  
		$pastentry = mysqli_fetch_assoc(mysqli_query($conn,"SELECT id FROM unrecoginize_coin WHERE merchant_id='$m_id' and user_id='$user_id' and status=1 LIMIT 1"))['id'];  
	    if(!$pastentry)
		{
			if($coin_class=="A")
				$coin_limit=200;
			else if($coin_class=="B")
				$coin_limit=500;
			else if($coin_class=="C")
				$coin_limit=5000;
			else if($coin_class=="D")
				$coin_limit=10000;
			$q = mysqli_query($conn,"INSERT INTO unrecoginize_coin(user_id,merchant_id,coin_max_limit,status,coin_class,coin_limit) VALUES ('$user_id', '$m_id', '$coin_max_limit', '1','$coin_class','$coin_limit')");
			
			if(!$q)
			die(mysqli_error($conn));
			else
			die(true);  
		}   
		else
		{
			echo "Merchant Already Added";
			die;
		}			
	 }
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
		$sql = "select count(id) as total_count from unrecoginize_coin Where status=1 and user_id='$merchant_id'";
		$row = mysqli_fetch_assoc(mysqli_query($conn,$sql));
		$rec_count = $row['total_count'];
		$rec_limit = 100;
		if( isset($_GET['page'] ) ) {
            $page = $_GET['page'] + 1;
            $offset = $rec_limit * $page;
         }else {
            $page = 0;
            $offset = 0;
         }
		$left_rec = $rec_count - ($page * $rec_limit);
		 $query="select unrecoginize_coin.*,users.name as merchant_name,users.special_coin_name from unrecoginize_coin inner join users on users.id=unrecoginize_coin.merchant_id where unrecoginize_coin.user_id='$merchant_id' and status=1 order by unrecoginize_coin.id desc LIMIT $offset, $rec_limit";
		
		$u_query = mysqli_query($conn,$query);
		
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
					<h3><?php echo $language['partner_list']; ?></h3>
					
					<button type="button" class="btn btn-danger" onclick="window.location.href='business.php'"><?php echo $language['merchant_list']; ?></button>

					<form>
						<div class="row">
							
							
							<div class="col-md-3">
								<div class="ui-widget">
									<div class="form-group">
										<label for="tags_merchant"><?php echo $language['choose_merchant']; ?></label>
										<!-- <input class="form-control" required id="tags_merchant" placeholder="<?php echo $language['enter_merchant_name']; ?>"/> -->
										<select class="select2 form-control" id="tags_merchant" name="tags_merchant">
										<?php  	
										$qMerchant = mysqli_query($conn, "SELECT id,name FROM users WHERE user_roles = '2' and special_coin_name!='' and id!='$merchant_id' ORDER BY name ASC");
											while($row = mysqli_fetch_assoc($qMerchant)){  ?>
											<option value="<?php echo $row['name']; ?>"><?php echo $row['name']; ?></option>
										<?php } ?>
											
									</select>
									</div>
								
								</div>
							</div>
							<div class="col-md-3 col-sm-12">
								<div class="form-group">
									<label for="name"><?php echo $language['max_limit']; ?></label>
									<input type="text" required class="form-control" maxlength="8" id="coin_max_limit" name="coin_max_limit" placeholder="Coin Max Value">
								</div>
							</div>
							<div class="col-md-3 col-sm-12">
								<div class="form-group">
									<label for="name"><?php echo $language['limit_class']; ?>
									
									</label>
									<input type="hidden" id="coin_limit"/>
									 <select class="form-control" name="limit_class" id="limit_class">
										<?php  foreach($limit_class as $k=>$v){?>
										<option value="<?php echo $k?>" coin_limit="<?php echo $v;?>"><?php echo $k."-".$v." Coin" ?></option>
										<?php } ?>
										 
									 </select> 
									
								</div>
							</div>
							<div class="col-md-3" style="margin-top:2%;">
							 <button type="submit" name="add-agent" class="btn btn-lg btn-outline-primary"><?php echo $language['add']; ?></button>
							</div>

							
						</div>    
					</form>
					<span id="market_hold" style="font-weight:20px;color:red;font-weight:bold;display:none;"></span>   
					<?php if($rec_count>$rec_limit){ ?>        
					<p style="float:right;" class="pagecount">   
					 <?php
					      
								if( $page > 0 ) {
									$last = $page - 2;
  									echo "<a href = \"$_PHP_SELF?"."page=$last\">Last 100 Records</a> |";
								
									echo "<a href = \"$_PHP_SELF?"."page=$page\">Next 100 Records</a> |";
  									
								 }else if( $page == 0 ) {
									 echo "<a href = \"$_PHP_SELF?"."page=1\">Next 100 Records</a> |";
								
								 }else if( $left_rec < $rec_limit ) {
									$last = $page - 2;
									 echo "<a href = \"$_PHP_SELF?"."page=$last\">Last 100 Records</a> |";
									
								 }
							?>
					</p>
					<?php } ?>
					<h3>Total Record <?php echo $rec_count; ?></h3>
					<table class="table table-striped kType_table" id="kType_table" style="width:100% !important;">
					    <thead>
					        <tr>
					        <th>S.No</th>
							<th><?php echo $language['merchant_name']; ?></th>
							<th>Special Coin Name</th>
							<th>Coin Value</th>
							<th>Coin Used</th>
							<th>Pending Limit</th>
							<th>Coin in Merchant Hand</th>
							<th><?php echo $language['limit_class']; ?></th>
							<th>Limit Class Pending Point</th>
							
							<th>Created</th>
							
							<th>Action</th>
                		
					        </tr>
					    </thead>
					    <tbody>
					        <?php
							function partnerbal($coin_merchant_id,$conn)
							{
								// $q="SELECT sum(coin_balance) as total_amount FROM `special_coin_wallet` WHERE `merchant_id` ='$coin_merchant_id'";
								 	$q="SELECT sum(coin_balance) as total_amount FROM `special_coin_wallet` inner join users on users.id=special_coin_wallet.user_id and users.user_roles='2' WHERE `merchant_id` ='$coin_merchant_id'";
	
								$parq=mysqli_query($conn,$q);  
								$p_total=mysqli_fetch_assoc($parq);
								return $p_total['total_amount'];
							}   
							function partnerbalold($coin_merchant_id,$conn)
							{  
								$q="select sum(amount) as total_amount from tranfer as t where receiver_id in(select user_id from unrecoginize_coin where merchant_id='$coin_merchant_id' and status='1') and coin_merchant_id='$coin_merchant_id'";
								$parq=mysqli_query($conn,$q);
								$p_total=mysqli_fetch_assoc($parq);
								return $p_total['total_amount'];
							}
                    	$i=1;
                    		while($row = mysqli_fetch_assoc($u_query))
							{
								$partner_merchant_id=$row['merchant_id'];
								$totalcoin="SELECT sum(amount) as totalamount FROM tranfer WHERE MONTH(created_date) = MONTH(CURRENT_DATE()) AND YEAR(created_date) = YEAR(CURRENT_DATE()) and receiver_id='$merchant_id' and coin_merchant_id='$partner_merchant_id'";
								$acceptedcoin = mysqli_fetch_assoc(mysqli_query($conn,$totalcoin));
								$totalamount=$acceptedcoin['totalamount'];
								$coin_max_limit=$row['coin_max_limit'];
								$pending_limit=$row['coin_max_limit']-$acceptedcoin['totalamount'];
								 $partner_bal=partnerbal($partner_merchant_id,$conn);
								 
								 $pen=$row['coin_limit']-$partner_bal;
								if($pen<0)
									$pen=0;
							?>
                        	 <tr>
								<td><?php echo $i; ?></td>	
								<td><?php echo $row['merchant_name']; ?></td>	
								<td><?php echo $row['special_coin_name']; ?></td>	
								<td><?php echo number_format($row['coin_max_limit'],2); ?></td>	
								<td><?php echo number_format($totalamount,2); ?></td>	
								<td><?php echo number_format($pending_limit,2); ?></td>	
								<td><?php echo number_format($partner_bal,2); ?></td>	
								
								<td><?php echo "Class ".$row['coin_class']."-".$row['coin_limit'] ?></td>
								<td><?php echo number_format($pen,2); ?></td>	
								<td><?php echo date('d M Y h:i A', strtotime($row['created'])); ?></td>
								 <td>
									
									<a class="mr-4" href="edit_partnerlist.php?plan_id=<?php echo $row['id'] ?>">
									<i class="fa fa-pencil" aria-hidden="true"></i>
									</a>
									<a class="mr-4" href="delete_partnerlist.php?plan_id=<?php echo $row['id'] ?>">
									<i class="fa fa-trash" aria-hidden="true"></i>
									</a>
									
								</td>
							</tr>
                    	<?php
                            $i++;  
                    	}?>
					    </tbody>
					</table>
					<?php if($rec_count>$rec_limit){ ?>          
					<p style="float:right;">
					 <?php
								if( $page > 0 ) {
									$last = $page - 2;
  									echo "<a href = \"$_PHP_SELF?"."page=$last\">Last 100 Records</a> |";
									echo "<a href = \"$_PHP_SELF?"."page=$page\">Next 100 Records</a> |";
  									
								 }else if( $page == 0 ) {
									 echo "<a href = \"$_PHP_SELF?"."page=1\">Next 100 Records</a> |";
								
								 }else if( $left_rec < $rec_limit ) {
									$last = $page - 2;
									 echo "<a href = \"$_PHP_SELF?"."page=$last\">Last 100 Records</a> |";
									
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
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
	<script>
	   var merchant_tags = [];
	    $(document).ready(function(){
			$('#tags_merchant').select2();
			// <?php 
			// 	$qMerchant = mysqli_query($conn, "SELECT id,name FROM users WHERE user_roles = '2' and special_coin_name!='' and id!='$merchant_id' ORDER BY name ASC");
			// 	while($row = mysqli_fetch_assoc($qMerchant)){
			// 		// print_R($row);
			// 		// die;
			// 		$m_name = str_replace("'", ' ', $row['name']);
			// 		$m_name = str_replace("(", ' ', $m_name);
			// 		$m_name = str_replace(")", ' ', $m_name);
					
			// 		echo "merchant_tags.push('" . $m_name . "');\n";   
				
				
			// 	}
				
			// ?>  
			// $( "#tags_merchant" ).autocomplete({
			// 	source: merchant_tags
			// });
			
			$("#tags_merchant").change(function(){  
				var tags_merchant=$('#tags_merchant').val();
				// alert(tags_merchant);
				$.ajax({
							url :'functions.php',
							type:'POST',
							dataType : 'json',
							data:{tags_merchant:tags_merchant,method:"marketbal"},   
								success:function(response){
										// alert(response);
										if(response>0)
										{
											$('#market_hold').show();
											$('#market_hold').html("Coin hold in market: "+response);
										}
										else
										{
											$('#market_hold').hide();
										}
										   
									}		  
							});
			});
			$("#limit_class").change(function(){
				var selected_class=$("#limit_class").val();
				var selected_coin=$('option:selected',this).attr('coin_limit');
				$('#coin_limit').val(selected_coin);
				if(selected_class!="A")
				{
					var msg="By changing class "+selected_class+" you will be self responsible for any kind of loss";  
					$('#show_msg').html(msg);
					$('#AlerModel').modal('show'); 
					setTimeout(function(){ $("#AlerModel").modal("hide"); },5000);
				}   
			}); 
			$("form").on("submit", function(e){
				e.preventDefault();
				var coin_max_limit = $("#coin_max_limit").val();
				var merch_name = $("#tags_merchant").val();
				var coin_limit = $("#coin_limit").val();
				// alert(coin_limit);
				var limit_class = $("#limit_class").val();
				if(merch_name == '' || coin_max_limit == ''){
					alert("You have to fill all the options.")
				}else{
					$.post("./partner_list.php", {
						coin_max_limit: coin_max_limit,
						merchant_name: merch_name,
						coin_limit: coin_limit,
						coin_class: limit_class,
						add_coin:true  
					}, function(data, res){
						console.log(data);
						if(data==1)
						{   
							$('#show_msg').html("Merchant Added");
							
						}
						else
						{
							$('#show_msg').html(data);
						}
						$('#AlerModel').modal('show'); 
						setTimeout(function(){
							$("#AlerModel").modal("hide"); 
							window.location.href = "./partner_list.php";
							},2000); 
						
							// console.log(data);
					});
				}
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
				"pageLength":50,
				dom: 'Bfrtip',
				 buttons: [
					'copy', 'csv', 'excel', 'pdf', 'print'
				]
				});
				
				
		});
	  
	  
	</script>
	 <div class=" modal fade" id="AlerModel" role="dialog" style="width:80%;min-height: 200px;text-align: center;margin:8%;">
        <div class="element-item modal-dialog modal-dialog-centered" style="position: absolute;top: 0;bottom: 0;left: 0;right: 0;display: grid;align-content: center;">
            <!-- Modal content-->
            <div class="element-item modal-content">
                <div class="element-item modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                            
                    
                              </div>   
                                    <p id="show_msg" style="font-size:22px;font-weight:bold;"><?php echo $language['the_product_added']; ?></p>
                    
                                </div>
                            </div>
    </div>
	
</body>
