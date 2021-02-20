<?php 
include("config.php");

if(!isset($_SESSION['admin']))
{
	header("location:login.php");
}
$rec_limit = 100;

/* end  for limit  */

 $sql = "select count(feedback_id) as total_count from feedback";

$row = mysqli_fetch_assoc(mysqli_query($conn,$sql));
$rec_limit = 100;
   $rec_count = $row['total_count'];

if( isset($_GET{'page'} ) ) {
            $page = $_GET{'page'};
            $offset = $rec_limit * $page ;
         }else {
            $page = 0;
            $offset = 0;
         }
         
$left_rec = $rec_count - ($page * $rec_limit);
      $query="select SQL_NO_CACHE  f.*,m.name as merchant_name,m.mobile_number as merchant_mobile,o.id as order_id,o.invoice_no,o.user_name,o.user_mobile,o.amount,o.quantity  
	 from feedback as f inner join order_list as o on f.order_id=o.id inner join users as m on m.id=o.merchant_id   order by f.feedback_id desc LIMIT $offset, $rec_limit";

$user = mysqli_query($conn,$query);
$total_rows = mysqli_num_rows($user);
$total_page_num = ceil($total_rows / $limit);

$start = ($page - 1) * $limit;
$end = $page * $limit;
$a_m="merchant";
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
					<h2 class="text-center wallet_h">Feedback List</h2>
					<button type="button" class="btn btn-danger" onclick="window.location.href='./feedback.php'">Clear Page</button>
					
				<h3> Total Records <?php  echo $rec_count;?></h3>
				<h4> Total Pages <?php  echo floor($rec_count/100);?></h4>
				<p>Note : Question 1: Are you satisfied with the food quality?</br>
				Question 2: Are you happy with deliveryman service?</p>
				<?php if($rec_count>25){ ?>        
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
					<table class="table table-striped kType_table" id="kType_table">
					    <thead>
					        <tr>
							<th>Sr No</th>
							<th>Invoice no</th>
							<th>User detail</th>
							<th>Merchant detail</th>
							<th>Total amount</th>
							<th>Order status</th>
							<th>Question 1</th>
							<th>Question 2</th>
							<th>Comment</th>
							<th>Remark </th>
							
							
						  </tr>
					    </thead>
					   <tbody>
                    	<?php
                    	$i=1;
                    	while($row=mysqli_fetch_assoc($user)){
							 $amount_val = explode(",",$row['amount']);
							    $quantity_ids = explode(",",$row['quantity']);
							 $total = 0;
                        foreach ($amount_val as $key => $value){
                            if( $quantity_ids[$key] && $value ) {
                                $total =  $total + ($quantity_ids[$key] *$value );
                            } 
                           }
							$n_status='';  
                                if($row['status'] == 0)
								{
									$sta ="Pending";
									$s_color="red";
									$n_status=1;
								}
                                else if($row['status'] == 1) 
								{
									
									$sta ="Done,in delivery";
									$s_color="green";
									$n_status=4;
								}
								else if($row['status'] == 4 || $row['status']==5) 
								{
									$sta ="Deliverted";
									$s_color="green";
								}
                                else 
								{
									$n_status=1;
									$sta ="Accepted";
									// $sta = "Accepted";
									$s_color="";
								}
							 ?>
                        	  <tr>
                        		 <td> <?php echo $i; ?> </td>
                                <td class="name"  style="cursor:pointer;"><?php echo $row['invoice_no'];  ?></td>
                                <td><?php echo $row['user_name']."/".$row['user_mobile'];  ?></td>
                                <td><?php echo $row['merchant_name']."/".$row['merchant_mobile'];  ?></td>
                                <td><?php echo $total;?></td>
                        		<td><input type="button" next_status="<?php echo $n_status; ?>" style="background-color:<?php echo $s_color;?>" class= "status btn btn-primary" value="<?php  echo $sta;?>" status="<?php echo $row['status'];?>" data-invoce='<?php echo $row['invoice_no'];?>' data-id="<?php echo $row['id']; ?>"/>
</td>
                        		<td><?php echo $row['q1'];  ?></td>
                        		<td><?php echo $row['q2'];  ?></td>
                        		<td><?php echo $row['q10'];  ?></td>
								<td><textarea class="remark_done" data_id="<?php echo $row['feedback_id']; ?>"><?php echo $row['remark']; ?></textarea></td>
								
                              </tr>
                    	<?php
                            $i++;  
                    	}?>
                	</tbody>
					</table>
					<?php if($rec_count>25){ ?>    
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
				
	
    	

	$(".remark_done").focusout(function(e){
		var selected_id= $(this).attr('data_id');
		var remark=this.value;
		// alert(remark);
		if(remark!='' && selected_id)
		{  
		  $.ajax({
						url :'../functions.php',
						 type:"post",
						 data:{remark:remark,method:"adminfeedbacksave",selected_id:selected_id},     
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
	
	
	
	

	
				
				
	});
	  
	  
	</script>
	
</body>

</html>
