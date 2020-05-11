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

$merchant_referral = "";
if(isset($_GET['merchant_referral'])){
    $merchant_referral = $_GET['merchant_referral'];
}
$sql = "
    SELECT SUM(rate )rate, SUBSTRING(DATE, 1, 7) date
    	FROM (
        	SELECT a.referral_id first_ref, '' second_ref, '' third_ref, a.referred_by, subscription.subscription_rate * 0.05 rate, b.subscription_date DATE
        	FROM (
        	SELECT users.name, users.id, users.referral_id, users.referred_by, users.mobile_number
        	FROM users 
        	WHERE users.referred_by LIKE '%".$merchant_referral."%' ) a LEFT JOIN (SELECT users.referral_id, TYPE, subscription_date FROM merchant_subscription ms INNER JOIN users ON users.id = ms.user_id)b 
        	ON a.referral_id = b.referral_id LEFT JOIN subscription ON subscription.id = b.type
        	WHERE b.subscription_date != ''
		
        UNION ALL
        
            SELECT a.first_ref, users.referral_id second_ref,  '' third_ref, a.referred_by referred_by, subscription.subscription_rate * 0.02 rate, b.subscription_date DATE
        	FROM (
        		SELECT users.name, users.id, users.referral_id first_ref, users.referred_by, users.mobile_number
        		FROM users
        		WHERE users.referred_by LIKE '%".$merchant_referral."%'
        		) a INNER JOIN users ON a.first_ref = users.referred_by LEFT JOIN (SELECT users.referral_id, TYPE, subscription_date FROM merchant_subscription ms INNER JOIN users ON users.id = ms.user_id)b 
        		ON users.referral_id = b.referral_id LEFT JOIN subscription ON subscription.id = b.type
			WHERE b.subscription_date != ''
        UNION ALL
        
            SELECT a.first_ref, a.second_ref, users.referral_id third_ref,  a.referred_by referred_by, subscription.subscription_rate * 0.01 rate, b.subscription_date DATE
        	FROM (
        		SELECT users.name, users.id, a.first_ref, users.referral_id second_ref, users.mobile_number, a.referred_by
        		FROM (
        			SELECT users.name, users.id, users.referral_id first_ref, users.referred_by, users.mobile_number
        			FROM users
        			WHERE users.referred_by LIKE '%".$merchant_referral."%' ) a 
        		INNER JOIN users ON a.first_ref = users.referred_by ) a
        	INNER JOIN users ON a.second_ref = users.referred_by LEFT JOIN (SELECT users.referral_id, TYPE, subscription_date FROM merchant_subscription ms INNER JOIN users ON users.id = ms.user_id)b
        	ON users.referral_id = b.referral_id LEFT JOIN subscription ON subscription.id = b.type
        	WHERE b.subscription_date != ''
    	) a
    	GROUP BY SUBSTRING(DATE, 1, 7)
";
$user = mysqli_query($conn, $sql);

$sql = "SELECT * FROM users WHERE user_roles='2' and isLocked='0'";
$merchant =  mysqli_query($conn, $sql);
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
 #example_filter{
     display: none;
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
                <div class="row" id="main-content" style="padding-left: 25px; padding-top:25px">
				   <div class="col-md-4">
				       <select class="form-control merchant_list">
    				        <option value="">*</option>
    				        <?php while($row=mysqli_fetch_assoc($merchant)){ ?>
    				            <option <?php if($row['referral_id'] == $merchant_referral) echo "selected";?> value="<?php echo $row['referral_id'];?>"><?php echo $row['name']?></option>
    				        <?php }?>
    				    </select>
				   </div>
			        
				    <div class="col-md-9">
				        <table class="table table-striped" id="example">
                            <thead>
                              <tr>
                        		<th>id</th>
                                <th>Month</th>
                                <th>Rate</th>
                              </tr>
                            </thead>
                        	
                        	<tbody>
                            	<?php
                            	$i=1;
                            	while($row=mysqli_fetch_assoc($user)){ ?>
                                	  <tr>
                            		     <td> <?php echo $i; ?> </td>
                                         <td> <?php echo $row['rate']?></td>
                                         <td><?php echo $row['date']?></td>
                                      </tr>
                            	<?php
                                    $i++;  
                            	}?>
                        	</tbody>
        	
        	            </table>
				    </div>
    				

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
	$(".merchant_list").change(function(){
	   window.location.href = "total_commission.php?merchant_referral="+$(this).val();
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

