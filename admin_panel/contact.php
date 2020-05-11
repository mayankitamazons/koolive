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

$query = "SELECT contacts.*, users.name , users.email FROM contacts LEFT JOIN users ON contacts.user_id =  users.id";

if(isset($_GET['status']))
{
	$status = $_GET['status'];
	if($status == 0)
	{
		$query = $query . " WHERE contacts.action=''";
	}
	else
	{
	    $query = $query . " WHERE contacts.action='$status'";
	}
	
}
else
{
	$status = "";
}

$total_rows = mysqli_num_rows(mysqli_query($conn, $query));
$total_page_num = ceil($total_rows / $limit);

$start = ($page - 1) * $limit;
$end = $page * $limit;

$contact = mysqli_query($conn, $query . " ORDER BY id DESC LIMIT $start,$end");
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
							window.location = "contact.php";
						}
						else
						{
							window.location = "contact.php?status="+elemVal;
						}
					}
					</script>
				</div>
			<table class="table table-striped">
	<thead>
      <tr>
        <th>User Name</th>
		<th>User Email</th>
        <th>Subject</th>
        <th>Message</th>
		<th>Query Type</th>
		<th>Query Date</th>
		<th>Action</th>
		<th>Action Date</th>
      </tr>
    </thead>
	<?php
	while ($row=mysqli_fetch_assoc($contact)){
	?>
	<tbody>
	<tr>
	
	<td><?php echo $row['name'];?></td>
	<td><?php echo $row['email'];?></td>
	<td><?php echo $row['subject'];?></td>
	<td><?php echo $row['message'];?></td>
	<td><?php if($row['query_type']=='1')
	{
		echo "Feedback";
	}
	
	else{
		echo "Complaint";
	}?></td>
	<td><?php $querydate=$row['created_on'];
	          $querydate2=date("Y-m-d h:i:sa",$querydate);
			  echo $querydate2;
	?></td>
	<td><select  class="action" data-id="<?php echo $row['id']; ?>">
	<option> Select Action</option>
	<option value='0' <?php echo $row['action']=='0' ?  'selected' : " " ?>>Process</option>
	<option value='1' <?php echo $row['action']=='1' ?  'selected' : " " ?>>Complete</option>
	<option value='2' <?php echo $row['action']=='2' ?  'selected' : " " ?> >Invalid</option>
	</td>
	<td><?php $date=$row['action_date'];
	          $date2=date("Y-m-d h:i:sa");
			  echo $date2;
	?>
	</td>
	</tr>
    </tbody>
	<?php
	}
	?>
	</table>
	<div style="margin:0px auto;">
 <ul class="pagination">
 <?php
   for($i = 1; $i <= $total_page_num; $i++)
   {
    if($i == $page)
    {
     $active = "class='active'";
    }
    else
    {
     $active = "";
    }
    if($status != "")
	{
		echo "<li $active><a href='?page=$i&status=$status'>$i</a></li>";
	}
	else
	{
		echo "<li $active><a href='?page=$i'>$i</a></li>";
	}
   }
 ?>
 </ul>
</div>
			</main>
        </div>
        <!-- /.widget-body badge -->
    </div>
    <!-- /.widget-bg -->

    <!-- /.content-wrapper -->
    <?php include("includes1/footer.php"); ?>
	
<script>
$(".action").change(function(){
	var action = $(this).val();
	var id = $(this).data("id");
	
  $.ajax({
	 url : 'updatecontact.php',  
	 type: 'POST',
	 data:{recordid:id,updated_stauts:action},
	 success: function(data){
		 alert();
	 }
  });	
});
 
</script>	
</body>

</html>