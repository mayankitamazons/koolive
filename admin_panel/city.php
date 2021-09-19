<?php 
include("config.php");
session_start();
//$conn = mysqli_connect("localhost", "koofamil_B277", "rSFihHas];1P", "koofamil_B277");
//$conn = mysqli_connect("localhost","root","","adminpanel");

#winapps code-----------------------------------------

$query2 = "SELECT * FROM `city`";
    $query_run = mysqli_query($conn,$query2);

if(isset($_GET['data'])&&$_GET['data']=='deleteRecord'){
	$id = $_GET['id'];
	//$query = mysqli_query($conn,"UPDATE `city` SET `status`=0 WHERE CityID='$id'");
	$query = mysqli_query($conn,"DELETE FROM `city` WHERE CityID='$id'");
	
	if($query){echo true;}else{die();}
}


if(isset($_GET['data'])&&$_GET['data']=='offer_one'){
	$id = $_GET['id'];
	$offer_one = $_GET['offer_one'];
	$query1 = mysqli_query($conn,"UPDATE `city` SET `offer_one`= $offer_one WHERE CityID='$id'");
	
	if($query1){echo true;}else{die();}
}

if(isset($_GET['data'])&&$_GET['data']=='offer_two'){
	$id = $_GET['id'];
	$offer_two = $_GET['offer_two'];
	$query2 = mysqli_query($conn,"UPDATE `city` SET `offer_two`= $offer_two WHERE CityID='$id'");
	
	if($query2){echo true;}else{die();}
}



if(!isset($_SESSION['admin']))
{
	header("location:login.php");
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
					<h2 class="text-center wallet_h">City List</h2>
					<!-- <button type="button" class="btn btn-danger" onclick="window.location.href='./user.php'">Clear Page</button> -->
					<button type="button" class="btn btn-danger pull-right" onclick="window.location.href='./addcity.php'">Add City</button>
				
				
					<table class="table table-striped kType_table" id="kType_table">
					    <thead>
					       <tr>
                                <th>SR</th>
								<th>State Name</th>
                                <th>City Name</th>
                                <th>15-Minute Offer</th>
                                <th>48-Hour Offer</th>
                                <th>status</th>
							    <th>Action</th>
						  </tr>
					    </thead>
					   <tbody>
                    	<?php
                        $i=1;
						$jobs = mysqli_query($conn, "SELECT * from city order by CityID desc");
					
                    	while($row=mysqli_fetch_assoc($jobs)){
							 ?>
                        	  <tr>
                                <td><?php echo $i;?></td>
                                <td><?php echo isset($row['StateName'])?$row['StateName']:'';?></td>

                                <td><?php echo isset($row['CityName'])?$row['CityName']:'';?></td>
								<td>
									<select name="offer_one" style="width:100px" class="form-control offer_one" jId="<?php echo $row['CityID']?>">
										<option value="0">Select</option>
										<option <?php if($row['offer_one'] == 1){ echo 'selected';}?> value="1">YES</option>
										<option <?php if($row['offer_one'] == 2){ echo 'selected';}?> value="2">NO</option>
									</select>
								</td>
								<td>
									<select name="offer_two" style="width:100px"  class="form-control offer_two" jId="<?php echo $row['CityID']?>">
										<option value="0">Select</option>
										<option <?php if($row['offer_two'] == 1){ echo 'selected';}?> value="1">YES</option>
										<option <?php if($row['offer_two'] == 2){ echo 'selected';}?> value="2">NO</option>
									</select>
								</td>
								<td><span class="btn btn-primary"><?php if($row['status']){echo "Active";} else { echo "Inactive";} ?></span></td>
								
                        		 <td>
                                     <a href="javascript:void(0)" class="deleteRecord" jId="<?php echo $row['CityID']?>">Delete</a>
                                 </td>
                              </tr>
                    	<?php
                            $i++;  
                    	}?>
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
				
	
		
	
//   $(".editJob").click(function(){
// 	  var id = $(this).attr('jId');
// 	  $.get("./jobs_list.php", {
// 		jobData: "data",
// 			id: id
// 		}, function(data){
// 			data = JSON.parse(data);
// 			console.log(data);
// 			$('#id').val(data['id']);
// 			$("#title").val(data['title']);
// 			$("#jPrice").val(data['price']);
// 			$("#jobDesc").val(data['job_desc']);
// 			$("#wGNo").val(data['whatsAppGroup']);
// 			$('#postDate').val(data['posted_date_utc']);
// 			$("#exExDate").val(data['expire_date_utc']);
// 			$("#jProname").val(data['job_provider_name']);
// 			$("#jProNo").val(data['job_provide_mobile']);
// 			$("#jobProDesc").val(data['job_provider_desc']);
			
// 			$("#category").prop("selected", false);
// 			$("#category option[value='" + data['job_category_id'] + "']").prop("selected", true);
// 			$("#edit_info").modal("show");
			

			

// 		});
		
	 
//   });
  $(".deleteRecord").click(function(){

	var id = $(this).attr('jId');
	var cnfrmDelete = confirm("Are You Sure Delete This city ?");
	if(cnfrmDelete==true){
		  $.ajax({
				url:'city.php',
				method:'GET',
				data:{data:'deleteRecord',id:id},
				success:function(res){location.reload(true);}
		  });	
	}
  });
	
	
	$(".offer_one").click(function(){

		var id = $(this).attr('jId');
		var offer_one = $(this).val();
		$.ajax({
			url:'city.php',
			method:'GET',
			data:{data:'offer_one',offer_one:offer_one,id:id},
			success:function(res){}
		});	
	});
	
	$(".offer_two").click(function(){

		var id = $(this).attr('jId');
		var offer_two = $(this).val();
		$.ajax({
			url:'city.php',
			method:'GET',
			data:{data:'offer_two',offer_two:offer_two,id:id},
			success:function(res){}
		});	
	});
	
	
  
  
	
				
				
	});
	  
	  
	</script>
	

	



</body>

</html>
