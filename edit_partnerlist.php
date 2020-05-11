<?php 
session_start();
include("config.php");
$me="partner_list";
if(!isset($_SESSION['login']))
{
	header("location:login.php");
}

$date = date('Y-m-d H:i:s');
if(isset($_POST['edit_coin'])){
		extract($_POST);
		
		$q = mysqli_query($conn,"update unrecoginize_coin set coin_max_limit='$coin_max_limit' where id='$plan_id'");   
			if(!$q)
			die(mysqli_error($conn));
			else
			die(true);     
					
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
		$plan_id=$_GET['plan_id'];
		  $query="select unrecoginize_coin.*,users.name as merchant_name,users.special_coin_name from unrecoginize_coin inner join users on users.id=unrecoginize_coin.merchant_id where unrecoginize_coin.id='$plan_id' and status=1";
		   
		$u_query = mysqli_query($conn,$query);
		$p=mysqli_fetch_assoc($u_query);
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
					<h3>Edit Partner <?php echo $p['merchant_name']; ?></h3>
					<button type="button" class="btn btn-danger" onclick="window.location.href='partner_list.php'">Back</button>

					<form>
						<div class="row">
							
							
							<div class="col-md-3">
								<div class="ui-widget">
									<div class="form-group">
										<label for="tags_merchant">Choose a merchant</label>
										<input type="hidden" id="plan_id" value="<?php echo $p['id']; ?>"/>
										<input class="form-control"  readonly value="<?php echo $p['merchant_name']; ?>" required id="tags_merchant"  placeholder="Introduce merchant's name"/>
									</div>
								</div>
							</div>
							<div class="col-md-3 col-sm-12">
								<div class="form-group">
									<label for="name">Max Limit</label>
									<input type="text"  value="<?php echo $p['coin_max_limit']; ?>" required class="form-control" maxlength="8" id="coin_max_limit" name="coin_max_limit" placeholder="Coin Max Value">
								</div>
							</div>
							<div class="col-md-3 col-sm-12">
								<div class="form-group">
									<label for="name">Limit Class <?php echo $p['coin_class']; ?>
									
									</label>
									<input type="hidden" id="coin_limit" value="<?php echo $p['coin_limit']; ?>"/>
									 <select class="form-control" name="limit_class" id="limit_class">
										<?php  foreach($limit_class as $k=>$v){?>
										<option   <?php if($k==$p['coin_class']){ echo "selected";} ?> value="<?php echo $k?>" coin_limit="<?php echo $v;?>"><?php echo $k."-".$v." Coin" ?></option>
										<?php } ?>
										 
									 </select>    
								</div>
							</div>
							<div class="col-md-3" style="margin-top:2%;">
							 <button type="submit" name="add-agent" class="btn btn-lg btn-outline-primary">EDIT</button>
							</div>

							
						</div>
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
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script>
	   var merchant_tags = [];
	    $(document).ready(function(){
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
				var plan_id = $("#plan_id").val();
				var merch_name = $("#tags_merchant").val();
				var coin_limit = $("#coin_limit").val();
				// alert(coin_limit);
				var limit_class = $("#limit_class").val();
				if(merch_name == '' || coin_max_limit == ''){
					alert("You have to fill all the options.")
				}else{
					// alert(coin_max_limit);
					 $.ajax({
								url :'functions.php',
								type:'POST',
								dataType : 'json',
								data:{coin_max_limit:coin_max_limit,plan_id:plan_id,method:"editpartner",coin_limit:coin_limit,coin_class:limit_class},   
								success:function(response){
										var data = JSON.parse(JSON.stringify(response));
										if(data.status==true)
										{
											$('#show_msg').html("Partner Updated");
											$('#AlerModel').modal('show'); 
											setTimeout(function(){
												$("#AlerModel").modal("hide"); 
												window.location.href = "./partner_list.php";
												},2000); 
										}
										else 
										{
											$('#show_msg').html("Failed to Update Try Again later");
											$('#AlerModel').modal('show'); 
											setTimeout(function(){
												$("#AlerModel").modal("hide"); 
												 location.reload();
												},2000);   
										}   
									}		  
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

</html>
