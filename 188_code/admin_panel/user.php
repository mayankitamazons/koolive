<?php 
include("config.php");
if(!isset($_SESSION['madmin']))
{
	header("location:login2.php");
}
$rec_limit = 50;

/* end  for limit  */

 $sql = "select count(id) as total_count from users Where user_roles = 1";

$row = mysqli_fetch_assoc(mysqli_query($conn,$sql));
$rec_limit = 50;
  $rec_count = $row['total_count'];

if( isset($_GET{'page'} ) ) {
            $page = $_GET{'page'} + 1;
            $offset = $rec_limit * $page ;
         }else {
            $page = 0;
            $offset = 0;
         }
           
$left_rec = $rec_count - ($page * $rec_limit);
    $query="select * from users Where user_roles = 1 order by id desc LIMIT $offset, $rec_limit";
if($_POST['m_id'])
{
	$m_id=$_POST['m_id'];
	 $query="select * from users Where user_roles = 1 and id='$m_id'";
	 // $query="select SQL_NO_CACHE users.*,about.image from users left join about on users.id=about.userid Where users.user_roles = 2 and users.id='$m_id' order by name desc LIMIT $offset, $rec_limit";
	 // $query="select SQL_NO_CACHE * from users Where user_roles = 2 and id='$m_id' order by name desc LIMIT $offset, $rec_limit";
}
$user = mysqli_query($conn,$query);
$total_rows = mysqli_num_rows($user);
$total_page_num = ceil($total_rows / $limit);

$start = ($page - 1) * $limit;
$end = $page * $limit;
$a_m="member";
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
					<h2 class="text-center wallet_h">User List</h2>
					<div class="col-md-3">
					        <form action="" method="post">
								<div class="form-group">
									<?php 
									 $q="SELECT id,name,mobile_number FROM users WHERE user_roles = '1'  ORDER BY name ASC";
									 
									 	$qMerchant = mysqli_query($conn,$q);
									 
									 
									
									?>
										<label for="tags_merchant">Choose a User</label>
									
									
									<select class="tags_merchant_select" name='m_id'>
									    <option value='-1'>Select User</option>
										<?php 
										  
											while($row = mysqli_fetch_assoc($qMerchant)){ 
											 if($row['name']){?>
									       <option  <?php if($_POST['m_id']==$row['id']){ echo "selected";} ?>value="<?php  echo $row['id'];?>"><?php echo $row['name']."- ".$row['mobile_number']."-".$row['id'];?></option>   
											 <?php }else { ?>
											 <option  <?php if($_POST['m_id']==$row['id']){ echo "selected";} ?>value="<?php  echo $row['id'];?>"><?php echo $row['mobile_number']."-".$row['id'];?></option>  	 
											<?php }}  ?>

									</select>
								</div>  
								<div class="col-md-6" >
								<input type="submit" class="btn btn-lg btn-outline-primary search" name="search" value="search"/>
							 
								
							</div>
							</form>
							</div> 
							
				
					<button type="button" class="btn btn-danger" onclick="window.location.href='./user.php'">Clear Page</button>
				<h3> Total Records <?php  echo $rec_count;?></h3>
				<h4> Total Pages <?php  echo floor($rec_count/$rec_limit);?></h4>
				<?php if($rec_count>25){ ?>        
					<p style="float:right;" class="pagecount">   
					 <?php
					      
								if( $page > 0 ) {
									$last = $page - 2;
  									echo "<a href = \"$_PHP_SELF?"."page=$last\">Last $rec_limit Records</a> |";
									echo "<a href = \"$_PHP_SELF?"."page=$page\">Next $rec_limit Records</a> |";
  									
								 }else if( $page == 0 ) {
									 echo "<a href = \"$_PHP_SELF?"."page=1\">Next $rec_limit Records</a> |";
								
								 }else if( $left_rec < $rec_limit ) {
									$last = $page - 2;
									 echo "<a href = \"$_PHP_SELF?"."page=$last\">Last $rec_limit Records</a> |";
									
								 }
							?>
					</p>
					<?php } ?>
					<table class="table table-striped kType_table" id="kType_table">
					    <thead>
					        <tr>
							<th>Particular</th>
							<th>Name</th>
							<th>User id</th>
							<th>Mobile Nmber</th>
							<th>K Type</th>
							<th>Koo CashBack</th>
							<th>MYR Wallet</th>
							<th>CF</th>
							<th>Joining Date</th>
							<th>Status</th>
							<th>View</th>
							<th>Delete</th>
						  </tr>
					    </thead>
					   <tbody>
                    	<?php
                    	$i=1;
                    	while($row=mysqli_fetch_assoc($user)){
							 ?>
                        	  <tr>
                        		 <td> <?php echo $i; ?> </td>
								  <td class="name" data-id=<?php echo $row['id']; ?> style="cursor:pointer;"><?php echo $row['name'];  ?></td>
                        		 <td> <?php echo $row['id']; ?> </td>
                               
                                <td><?php echo $row['mobile_number'];  ?></td>
                                <td><?php echo $row['account_type'];?></td>
								<td>
								<input type="text" selected_user_id="<?php echo $row['id']; ?>"  name="balance_inr" placeholder="%" class="form-control balance_inr" value="<?php echo $row['balance_inr'];?>">
								</td> 
                        		<td><?php echo $row['balance_usd'];  ?></td>
                        		<td><?php echo $row['balance_myr'];  ?></td>
                        		 
								
                        		<td><?php  $date=$row['joined'];
                        	        echo $joinigdate=date("Y-m-d h:i:sa",$date);  ?>
                        	    </td>
                        	    <td>
                        	        <select class='status'   data-id="<?php echo $row['id']; ?>" >
                                	    <option>Select Status</option>
                                	    <option value='1' <?php echo $row['isLocked']=='1' ? 'selected' : ''?>>Blocked</option>
                                	    <option value='0' <?php echo $row['isLocked']=='0' ? 'selected' : ''?>>Unblocked</option>
                        	        </select>
                        	    </td>
                        	    <td><a href="user_edit.php?id=<?php echo $row['id'];?>"><i style="font-size: 20px;" class="fa fa-eye"></i></a></td>
                        	    <td><a target="_blank" href="../orderlist.php?did=<?php echo $row['id'];?>"><i style="font-size: 20px;" class="fa fa-detail"></i></a></td>          
                                <td class="del" data-toggle="modal" data-target="#delModal" data-del="<?php echo $row['id']; ?>"><i style="font-size: 20px;" class="fa fa-trash"></i></td>
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
  									echo "<a href = \"$_PHP_SELF?"."page=$last\">Last $rec_limit Records</a> |";
									echo "<a href = \"$_PHP_SELF?"."page=$page\">Next $rec_limit Records</a> |";
  									
								 }else if( $page == 0 ) {
									 echo "<a href = \"$_PHP_SELF?"."page=1\">Next $rec_limit Records</a> |";
								
								 }else if( $left_rec < $rec_limit ) {
									$last = $page - 2;
									 echo "<a href = \"$_PHP_SELF?"."page=$last\">Last $rec_limit Records</a> |";
									
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
  $(".balance_inr").focusout(function(e){
		var selected_user_id= $(this).attr('selected_user_id');
		var balance_inr=this.value;
		if(balance_inr!='' && selected_user_id)
		{  
		  $.ajax({
						url :'../functions.php',
						 type:"post",
						 data:{balance_inr:balance_inr,method:"adminprofilesave",selected_user_id:selected_user_id},     
						 dataType:'json',
						 success:function(result){  
							var data = JSON.parse(JSON.stringify(result));   
							if(data.status==true)
							{
							  // location.reload(true);
							alert('Koo CashBack Credited');
							}
							else
							{alert('Failed to update');	}
							
							}
				});      
		} 
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
