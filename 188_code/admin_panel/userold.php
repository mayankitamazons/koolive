<?php 
include("config.php");

if(!isset($_SESSION['admin']))
{
	header("location:login.php");
}
if(isset($_GET['page']))
{
 $page = $_GET['page'];
}
else
{
 $page = 1;
}

$limit = 25; 

$total_rows = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users Where user_roles = 1"));
$total_page_num = ceil($total_rows / $limit);

$start = ($page - 1) * $limit;
$end = $page * $limit;

$user = mysqli_query($conn, "SELECT * FROM users Where `user_roles` = 1 and `isLocked` = 0 ORDER BY id DESC ");

?>
<!DOCTYPE html>
<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">

<head>

<style>
.pagination {
  display: inline-block;
  padding-left: 0;
  margin: 20px 0;
  border-radius: 4px;
 }
 td.del {
    cursor: pointer;
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
 
    <?php include("includes1/head.php"); ?>

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
				
				<table class="table table-striped" id="example">
                    <thead>
                      <tr>
                		<th>Particular</th>
                        <th>Name</th>
                        <th>Mobile Nmber</th>
                        <th>K Type</th>
                		<th>Koo Coin</th>
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
                                <td><?php echo $row['mobile_number'];  ?></td>
                                <td><?php echo $row['account_type'];?></td>
                        		<td><?php echo $row['balance_usd'];  ?></td>
                        		<td><?php echo $row['balance_myr'];  ?></td>
                        		<td><?php echo $row['balance_inr'];  ?></td>
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
                                <td class="del" data-toggle="modal" data-target="#delModal" data-del="<?php echo $row['id']; ?>"><i style="font-size: 20px;" class="fa fa-trash"></i></td>
                              </tr>
                    	<?php
                            $i++;  
                    	}?>
                	</tbody>
	
	            </table>

	             <div>
                    
                    <div class="modal fade" id="myModal" role="dialog" >
                        <div class="modal-dialog modal-sm">
                          <div class="modal-content" id="modalcontent">
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                          </div>
                        </div>
                      </div>
                  <div class="modal fade" id="delModal" role="dialog" >
                    <div class="modal-dialog modal-sm">
                      <div class="modal-content" id="modalcontent">
                          <div class="modal-body">
                              <h3>Are you sure?</h3>
                          </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default confirm-btn" user-id="">Delete</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                      </div>
                    </div>
                  </div>
 
	
	
	
	
				</div>
			</main>
        </div>
        <!-- /.widget-body badge -->
    </div>
    <!-- /.widget-bg -->

    <!-- /.content-wrapper -->
    <?php include("includes1/footer.php"); ?>
<script>
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
	</script>

</body>

</html>
<script>
$(document).ready(function() {
 $('#example').DataTable();
});
</script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">

	<script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>

