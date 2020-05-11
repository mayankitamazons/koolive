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

$query = "SELECT users.name, users.mobile_number, ex_requests.*  FROM users RIGHT JOIN ex_requests ON users.id = ex_requests.user_id";

if(isset($_GET['status']))
{
	$status = $_GET['status'];
	$query = $query . " WHERE ex_requests.status='$status'";
}
else
{
	$status = "";
}

$total_rows = mysqli_num_rows(mysqli_query($conn, $query));
$total_page_num = ceil($total_rows / $limit);

$start = ($page - 1) * $limit;
$end = $page * $limit;

$exchange = mysqli_query($conn, $query . " ORDER BY id DESC LIMIT $start,$end");
?>
<!DOCTYPE html>
<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">

<head>
<style>'
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
				<div class="row" style="padding:15px;">
					<select class="form-control" onchange="filter(this.value)">
						<option value="">Show All</option>
						<option value='0' <?php echo ($status=='0') ? 'selected' : '';?>>New Request</option>
						<option value='1' <?php echo ($status=='1') ? 'selected' : '';?>>Progress</option>
						<option value='2' <?php echo ($status=='2') ? 'selected' : '';?>>Complete</option>
						<option value='3' <?php echo ($status=='3') ? 'selected' : '';?>>Invalid</option>
					</select>
					<script type="text/javascript">
					function filter(elemVal)
					{
						if(elemVal == "")
						{
							window.location = "exchange_requests.php";
						}
						else
						{
							window.location = "exchange_requests.php?status="+elemVal;
						}
					}
					</script>
				</div>
				<table class="table table-striped" id="example">
	<thead>
      <tr>
        <th>User Name</th>
        <th>Mobile Number</th>
        <th>Rate</th>
		<th>From</th>
		<th>To</th>
		<th>User Note</th>
		<th>Created Date</th>
		<th>Status</th>
		<th>Status Date</th>
		<th>Status Note</th>
      </tr>
    </thead>
	<tbody>
	<?php
	while($row=mysqli_fetch_assoc($exchange)){		
	?>
	
	<tr>
		<?php 
									if($row['wallet_to'] == "INR")
							{
								$wato = "CNY";
							}
							else
							{
								$wato = $row['wallet_to'];
							}
							
							?>
							<?php 
									if($row['wallet_from'] == "INR")
							{
								$watf = "CNY";
							}
							else
							{
								$watf = $row['wallet_from'];;
							}
							
							?>
	<td class="name" data-id=<?php echo $row['id']; ?> style="cursor:pointer;"><?php echo $row['name'];  ?></td>
	<td><?php echo $row['mobile_number'];?></td>
	<td><?php echo $row['rate'];?></td>
	<td><?php echo $row['amount_from']." ".$watf;?></td>
	<td><?php echo $row['amount_to']." ".$wato;?></td>
	<td><?php echo $row['user_note'];?></td>
	<td><?php $date=$row['created_on'];
	          echo  $updatedate=date("Y-m-d h:i:sa",$date);?></td>
	<td><select id='status' name='status' onchange="update_ex_request('<?php echo $row['id'];?>','<?php echo $row['status_note'];?>')">
	<option value='0' <?php echo ($row['status']=='0') ? 'selected' : '';?>>New Request</option>
	<option value='1' <?php echo ($row['status']=='1') ? 'selected' : '';?>>Progress</option>
	<option value='2' <?php echo ($row['status']=='2') ? 'selected' : '';?>>Complete</option>
	<option value='3' <?php echo ($row['status']=='3') ? 'selected' : '';?>>Invalid</option>
	</select></td>
	<td><?php $date2=$row['created_on'];
	    echo  $updatedate2=date("Y-m-d h:i:sa",$date2);?></td>
	<td><?php echo $row['status_note'];?></td>	
	</tr>
	
	<?php
	}
	?>
	</table>
	
<!--
	<div style="margin:0px auto;">
 <ul class="pagination">
 <?php
   //~ for($i = 1; $i <= $total_page_num; $i++)
   //~ {
    //~ if($i == $page)
    //~ {
     //~ $active = "class='active'";
    //~ }
    //~ else
    //~ {
     //~ $active = "";
    //~ }
	
	//~ if($status != "")
	//~ {
		//~ echo "<li $active><a href='?page=$i&status=$status'>$i</a></li>";
	//~ }
	//~ else
	//~ {
		//~ echo "<li $active><a href='?page=$i'>$i</a></li>";
	//~ }
   //~ }
 ?>
 </ul>
</div>
-->
	<div>
	 <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Status Note</h4>
        </div>
        <div class="modal-body" style="padding-bottom:0px;">
         <input type="hidden" id='newid' name="id" value=""> 
		 <div class="form-group">
		 <label style="margin-left: 4%;">Status Note</label>
		 <div>
		 <div class="col-sm-10">
		 <textarea id='updated_status' class="form-control" value=""> </textarea>
        </div>
		</div>
        <div class="modal-footer" style="padding-bottom:2px;">
          <button type="submit" class="btn btn-default" data-dismiss="modal" onclick="submitmodal()">submit</button>
        </div>
      </div>
      
    </div>
  </div>
	
	</div>
	</div>
	
	
	<div class="modal fade" id="myModal2" role="dialog" >
    <div class="modal-dialog modal-lg">
      <div class="modal-content" id="modalcontent">
      <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  
			</main>
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
function update_ex_request(id,statusnote){
	var myid=id;
	 var status=$("#status").val();
	
	$.ajax({
		 url : 'update_ex_request.php',
		 type: 'POST',
		 data: {recordid:myid,updatedstatus:status},
		 success:function(data){
			 $("#myModal").modal("show");
			 $("#newid").val(id);
			 $("#updated_status").val(statusnote);	 
		 }
	 });
}

function submitmodal(){
	var myid=$("#newid").val();
	var mystatus=$("#updated_status").val();	
 $.ajax({
	 url : 'update_ex_request2.php',
	 type: 'POST',
	 data: {mid:myid,mstatus:mystatus},
	 success:function(data){
		 setTimeout(function(){location.reload();},2000);
	 }
	 
 })	
}	


$(".name").click(function(){
	  $("#myModal2").modal("show");
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
