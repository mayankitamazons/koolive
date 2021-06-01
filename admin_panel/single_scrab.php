<?php 
include("config.php");

if(!isset($_SESSION['admin']))
{
	header("location:login.php");
}
$rec_limit = 20;

$row = mysqli_fetch_assoc(mysqli_query($conn,$sql));
$rec_limit = 20;
 if(isset($_POST['search']))
 {
	 $restro_link=$_POST['restro_link'];
	echo $l="python3 foodpanda/enter_rest_link_get_everything.py ".$restro_link;
	$command = escapeshellcmd($l);
	$c_restro = shell_exec($command);
	$c_restro = json_decode($c_restro, TRUE);
?>
<pre>
    <?php
        print_r($c_restro);
	
	
    ?>
</pre>
<?php 
	if($c_restro)
	{
		extract($_POST);
		if($save_data="all" || $save_data=="info")
		{
			
			
		}
		// save only menu 
		if($save_data="all" || $save_data=="menu")
		{
			
		}	
	}	
 }
	

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
					<h2 class="text-center wallet_h">Single Restro All Detail </h2>
					<div class="col-md-3">
					        <form action="#" method="post">
								<div class="form-group">
									<?php 
									 $q="SELECT id,name FROM users WHERE user_roles = '2'  ORDER BY name ASC";
									 
									 	$qMerchant = mysqli_query($conn,$q);
									 
									 if($_POST['choose_agent'])
									 {
										 $q2="select id,name from users as u where u.user_roles='2' and u.name!=''  order by u.name asc";
									    $qMerchant1=mysqli_query($conn,$q2);
									 }
									
									?>
										<label for="tags_merchant">Choose a merchant</label>
									
									
									<select class="tags_merchant_select" name='m_id' id="merchant_id">
									    <option value='-1'>Select Merchant</option>
										<?php 
										  
											while($row = mysqli_fetch_assoc($qMerchant)){ ?>
									       <option  <?php if($_POST['m_id']==$row['id']){ echo "selected";} ?>value="<?php  echo $row['id'];?>"><?php echo $row['id']."- ".$row['name'];?></option>
											<?php } ?>

									</select>
								</div>   
								<div class="form-group">
								
										
									   <div class="col-md-10" >
								<input type="text" class="form form-control" name="restro_link" id="restro_link" placeholder="Enter FoodPanda Link"/>
							 
								
							</div>
							  <div class="col-md-2" >
							  <label></label>
								<input type="radio" name="save_data" value="all" checked="checked"/>All 
								<input type="radio" name="save_data" value="menu"/>Menu 
								<input type="radio" name="save_data" value="info"/>Info </br>
							    
								
							</div>
								</div>  
								<div class="col-md-6" >
								<input type="submit" class="btn btn-lg btn-outline-primary search" name="search" value="search"/>
							 
								
							</div>
							</form>
					</div> 
							
					<button type="button" class="btn btn-danger" onclick="window.location.href='./single_scrab.php'">Clear Page</button>
					
				
					
					
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
	


<script src="https://scripts.sirv.com/sirv.js" defer></script>

<script type="text/javascript">

	  $(document).ready(function() {
    $(".tags_merchant_select").select2();
});
</script>

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
				"pageLength":1000,
				dom: 'Bfrtip',
				 buttons: [
					'copy', 'csv', 'excel', 'pdf', 'print'
				]
				});
	 $(".image").on('change', function() {
			 ///// Your code
	});
	$("#merchant_id").change(function(){  
		var m_id = $(this).val();
		// alert(m_id);
		var id = $(this).data("id");
		//alert(id);  
		$.ajax({

           url : '../functions.php',

            type: "post",

           data :{m_id:m_id,method:"userdetailbyid"},

            dataType: 'json',

            success: function(response) {

                var data = JSON.parse(JSON.stringify(response));

                if (data.status == true)

                {

                   $('#restro_link').val(data.foodpanda_link);

                } else

                {

                    alert(data.msg);

                }

            }

        });
		
		  
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
		     location.reload();
			}  
		});
		
	});
	$(".city").change(function(){
		var cityval = $(this).val();
		//alert(cityval);
		var id = $(this).data("id");
		//alert(id);
		$.ajax({
			url : 'updatecity.php',
			type: 'POST',
			data :{updatedid:id,cityval:cityval},
			success:function(data){
				
		     location.reload();
			}  
		});
		
	});
	$(".default_lang").change(function(){
		var langval = $(this).val();
		//alert(cityval);
		var id = $(this).data("id");
		//alert(id);
		$.ajax({
			url : 'updatecity.php',
			type: 'POST',
			data :{updatedid:id,langval:langval},
			success:function(data){
		     location.reload();
			}  
		});
		  
	});
	$(".show_business").change(function(){
		var status = $(this).val();
		//~ alert(status);
		var id = $(this).data("id");
		//~ alert(id);
		$.ajax({
			url : 'updateuser.php',
			type: 'POST',
			data :{m_id:id,upadtedstatus:status},
			success:function(data){  
				location.reload();
			}
		});
		
	});
    		$(".grup_save").focusout(function(e){
		var selected_user_id= $(this).attr('data_id');
		var whatapp_group_name=this.value;
		if(whatapp_group_name!='' && selected_user_id)
		{  
		  $.ajax({
						url :'updatenatureofbusiness.php',
						 type:"post",
						 data:{updatedid:selected_user_id,whatapp_group_name:whatapp_group_name},     
						 dataType:'json',
						 success:function(result){  
							var data = JSON.parse(JSON.stringify(result));   
							alert(data.msg);
						 }
				});      
		} 
	});
	$(".service").change(function(){
		var service_id = $(this).val();
		 //alert(service_id);
		 var id = $(this).find(':selected').attr('data-id');
		 //alert(id);
		$.ajax({
			url : 'updatenatureofbusiness.php',
			type: 'POST',
			data :{updatedid:id,service_id:service_id},
			success:function(data){
		
			}
		});
		
	});
		$(".service").change(function(){
		var service_id = $(this).val();
		 //alert(service_id);
		 var id = $(this).find(':selected').attr('data-id');
		 //alert(id);
		$.ajax({
			url : 'updatenatureofbusiness.php',
			type: 'POST',
			data :{updatedid:id,service_id:service_id},
			success:function(data){
		
			}
		});
		
	});
	 $(".real_name").focusout(function(e){
		var selected_user_id= $(this).attr('selected_user_id');
		// var selected_user_id= $(this).attr('selected_user_id');
		var name=this.value; 
		// alert(name);
		if(name!='' && selected_user_id)
		{  
		  $.ajax({
						url :'../functions.php',
						 type:"post",
						 data:{name:name,method:"adminprofilesave",selected_user_id:selected_user_id},     
						 dataType:'json',
						 success:function(result){  
							var data = JSON.parse(JSON.stringify(result));   
							if(data.status==true)
							{  
							   // location.reload(true);
								
							}
							else
							{alert('Failed to update');	}
							
							}
				});      
		}
		});
		$(".foodpanda_link").focusout(function(e){
		var selected_user_id= $(this).attr('selected_user_id');
		// var selected_user_id= $(this).attr('selected_user_id');
		var foodpanda_link=this.value; 
		// alert(name);
		if(foodpanda_link!='' && selected_user_id)
		{  
		  $.ajax({
						url :'../functions.php',
						 type:"post",
						 data:{foodpanda_link:foodpanda_link,method:"adminprofilesave",selected_user_id:selected_user_id},     
						 dataType:'json',
						 success:function(result){  
							var data = JSON.parse(JSON.stringify(result));   
							if(data.status==true)
							{  
							   // location.reload(true);
								
							}
							else
							{alert('Failed to update');	}
							
							}
				});      
		}
		});
		 $(".mapSearch").focusout(function(e){
		var selected_user_id= $(this).attr('selected_user_id');
		// var mapSearch= $(".mapSearch").val();  
		var mapSearch=this.value; 		
		// alert(name);   
		if(mapSearch!='' && selected_user_id)
		{  
		  $.ajax({
						url :'../functions.php',
						 type:"post",
						 data:{google_map:mapSearch,method:"adminprofilesave",selected_user_id:selected_user_id},     
						 dataType:'json',
						 success:function(result){  
							var data = JSON.parse(JSON.stringify(result));   
							if(data.status==true)
							{
							  // location.reload(true);
								
							}
							else
							{alert('Failed to update');	}
							
							}
				});      
		}
		});
    $(".sst_rate").focusout(function(e){
		var selected_user_id= $(this).attr('selected_user_id');
		var sst_rate=this.value;
		if(sst_rate!='' && selected_user_id)
		{  
		  $.ajax({
						url :'../functions.php',
						 type:"post",
						 data:{sst_rate:sst_rate,method:"adminprofilesave",selected_user_id:selected_user_id},     
						 dataType:'json',
						 success:function(result){  
							var data = JSON.parse(JSON.stringify(result));   
							if(data.status==true)
							{
							  // location.reload(true);
								
							}
							else
							{alert('Failed to update');	}
							
							}
				});      
		} 
	});
	$(".delivery_rate").focusout(function(e){
		var selected_user_id= $(this).attr('selected_user_id');
		var delivery_rate=this.value;
		if(delivery_rate!='' && selected_user_id)
		{  
		  $.ajax({
						url :'../functions.php',
						 type:"post",
						 data:{delivery_rate:delivery_rate,method:"adminprofilesave",selected_user_id:selected_user_id},     
						 dataType:'json',
						 success:function(result){  
							var data = JSON.parse(JSON.stringify(result));   
							if(data.status==true)
							{
							  // location.reload(true);
								
							}
							else
							{alert('Failed to update');	}
							
							}
				});      
		} 
	});
		$(".vendor_comission").focusout(function(e){
		var selected_user_id= $(this).attr('selected_user_id');
		var vendor_comission=this.value;
		if(vendor_comission!='' && selected_user_id)
		{  
		  $.ajax({
						url :'../functions.php',
						 type:"post",
						 data:{vendor_comission:vendor_comission,method:"adminprofilesave",selected_user_id:selected_user_id},     
						 dataType:'json',
						 success:function(result){  
							var data = JSON.parse(JSON.stringify(result));   
							if(data.status==true)
							{
							  // location.reload(true);
							alert('Updated');
							}
							else
							{alert('Failed to update');	}
							
							}
				});      
		} 
	});
	$(".special_price_value").focusout(function(e){
		var selected_user_id= $(this).attr('selected_user_id');
		var special_price_value=this.value;
		if(special_price_value!='' && selected_user_id)
		{  
		  $.ajax({
						url :'../functions.php',
						 type:"post",
						 data:{special_price_value:special_price_value,method:"adminprofilesave",selected_user_id:selected_user_id},     
						 dataType:'json',
						 success:function(result){  
							var data = JSON.parse(JSON.stringify(result));   
							if(data.status==true)
							{
							  // location.reload(true);
								alert('Updated');
							}
							else
							{alert('Failed to update');	}
							
							}
				});      
		} 
	});
		$(".order_extra_charge").focusout(function(e){
		var selected_user_id= $(this).attr('selected_user_id');
		var order_extra_charge=this.value;
		if(order_extra_charge!='' && selected_user_id)
		{  
		  $.ajax({
						url :'../functions.php',
						 type:"post",
						 data:{order_extra_charge:order_extra_charge,method:"adminprofilesave",selected_user_id:selected_user_id},     
						 dataType:'json',
						 success:function(result){  
							var data = JSON.parse(JSON.stringify(result));   
							if(data.status==true)
							{
							  // location.reload(true);
								alert('Updated');
							}
							else
							{alert('Failed to update');	}
							
							}
				});      
		} 
	});
	$(".price_hike").focusout(function(e){
		var selected_user_id= $(this).attr('selected_user_id');
		var price_hike=this.value;
		if(price_hike!='' && selected_user_id)
		{  
		  $.ajax({
						url :'../functions.php',
						 type:"post",
						 data:{price_hike:price_hike,method:"adminprofilesave",selected_user_id:selected_user_id},     
						 dataType:'json',
						 success:function(result){  
							var data = JSON.parse(JSON.stringify(result));   
							if(data.status==true)
							{
							  // location.reload(true);
								alert('Price hike updated');
							}
							else
							{alert('Failed to update');	}
							
							}
				});      
		} 
	});
	$(".popular_restro").focusout(function(e){
		var selected_user_id= $(this).attr('selected_user_id');
		var popular_restro=this.value;
		if(popular_restro!='' && selected_user_id)
		{  
		  $.ajax({
						url :'../functions.php',
						 type:"post",
						 data:{popular_restro:popular_restro,method:"adminprofilesave",selected_user_id:selected_user_id},     
						 dataType:'json',
						 success:function(result){  
							var data = JSON.parse(JSON.stringify(result));   
							if(data.status==true)
							{
							  // location.reload(true);
								alert('Status updated for popular merchant');
							}
							else
							{alert('Failed to update');	}
							
							}
				});      
		} 
	});
	$(".show_merchant").focusout(function(e){
		var selected_user_id= $(this).attr('selected_user_id');
		var show_merchant=this.value;
		if(show_merchant!='' && selected_user_id)
		{  
		  $.ajax({
						url :'../functions.php',
						 type:"post",
						 data:{show_merchant:show_merchant,method:"adminprofilesave",selected_user_id:selected_user_id},     
						 dataType:'json',
						 success:function(result){  
							var data = JSON.parse(JSON.stringify(result));   
							if(data.status==true)
							{
							  // location.reload(true);
								alert('Status updated for show merchant');
							}
							else
							{alert('Failed to update');	}
							
							}
				});      
		} 
	});  
	
	$('.delivery_select').change(function() {
		var selected_type= $(this).attr('selected_type');
		alert(selected_type);
        if($(this).is(":checked")) {
            
        }
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
	
</body>

</html>
