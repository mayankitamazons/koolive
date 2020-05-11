<?php 
include("config.php");

$year = date("Y");
$month = date("m");
$start_dt = $year."-".$month."-01"." 00-00-00";
$end_dt = $year."-".$month."-31"." 23-59-59";

$k_history = mysqli_query($conn, "SELECT a.*, users.name merchant_name, users.mobile_number merchantphone FROM ( SELECT k1k2_history.*, users.name user_name, users.mobile_number userphone, order_list.created_on, order_list.quantity, order_list.amount FROM k1k2_history inner join users on users.id = k1k2_history.user_id inner join order_list on order_list.id = k1k2_history.order_id WHERE order_list.created_on > '".$start_dt."' AND order_list.created_on < '".$end_dt."' ) a INNER JOIN users ON a.merchant_id = users.id ORDER BY a.created_on desc");

if($_POST['filter']){
    $year = $_POST['year'];
    $month = $_POST['month'];
    $start_dt = $year."-".$month."-01"." 00-00-00";
    $end_dt = $year."-".$month."-31"." 23-59-59";
    
    $k_history = mysqli_query($conn, "SELECT a.*, users.name merchant_name FROM (SELECT k1k2_history.*, users.name user_name, order_list.created_on, order_list.quantity, order_list.amount FROM k1k2_history inner join users on users.id = k1k2_history.user_id inner join order_list on order_list.id = k1k2_history.order_id WHERE order_list.created_on > '".$start_dt."' AND order_list.created_on < '".$end_dt."' ) a INNER JOIN users ON a.merchant_id = users.id ORDER BY a.created_on desc");
}


?>
<!DOCTYPE html>
<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">

<head>
    <?php include("includes1/head.php"); ?>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
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
					<h2 class="text-center wallet_h">K1 & K2 History</h2>
					<form class="sort row" method="post" action="">
					    <input type="hidden" name="filter" value="filter">
					    <div class="col-sm-3"></div>
					    <div class="col-sm-6">
					        <label class="control-label col-sm-2">Year: </label>
					        <select class="form-control col-sm-3" name="year" style="display: inline-block;">
				                <?php for($i = 2018; $i < 2023; $i++){?>
				                    <option <?php if($year == $i) echo "selected";?>><?php echo $i;?></option>
				                <?php }?>
				            </select>
				            <label class="control-label col-sm-2">Month: </label>
				            <select class="form-control col-sm-2" name="month" style="display: inline-block;">
				                <?php for($i = 1; $i < 13; $i++){
				                    $mt = sprintf("%02d", $i);?>
				                    <option <?php if($month == $mt) echo "selected";?> value="<?php echo $mt;?>"><?php echo $i;?></option>
				                <?php }?>
				                
				            </select>
				            <button class="btn btn-default col-sm-2" type="submit">Filter</button>
					    </div>
				        
					        
					</form>
					<table class="table table-striped kType_table" id="kType_table">
					    <thead>
					        <tr>
					            <th>Date</th>
					            <th>Member</th>
					            <th>Mobile</th>
					            <th>Merchant</th>
					            <th>Mobile</th>
					            <th>Member Type</th>
					            <th>Merchant Type</th>
					            <th>Total(RM)</th>
					            <th>Discount(RM)</th>
					            <th>Mark</th>
					            <th>Complain</th>
					        </tr>
					    </thead>
					    <tbody>
					        <?php $sum = 0; ?>
					        <?php while ($row=mysqli_fetch_assoc($k_history)){
					            $bg_kType = "k_normal";
					            if($row['user_comment'] != ""){
					                $bg_kType = "k_user";
					            } else if($row['merchant_comment'] != ""){
					                $bg_kType = "k_merchant";
					            } 
					        ?>
					            <tr class="<?php echo $bg_kType;?>">
					                <td><?php echo substr($row['created_on'], 0, 10)?></td>
					                <td><?php echo $row['user_name'];?></td>
					                <td><?php echo $row['userphone'];?></td>
					                <td><?php echo $row['merchant_name'];?></td>
					                <td><?php echo $row['merchantphone'];?></td>
					                <td><?php echo $row['k_user'];?></td>
					                <td><?php echo $row['k_merchant'];?></td>
					                <?php 
					                    $amount_array = explode(",", $row['amount']);
					                    $qty_array = explode(",", $row['quantity']);
					                    $total = 0;
					                    for($i = 0; $i < count($amount_array); $i++){
					                        $total += $amount_array[$i] * $qty_array[$i];
					                    }
					                    $discount_rate = substr($row['discount'], 0, 1);
					                ?>
					                <td><?php echo $total;?></td>
					                <td><?php echo ($total * $discount_rate / 100); $sum += $total * $discount_rate / 100; ?></td>
					                <td>
					                    <?php if($row['mark'] == 0){?>
					                        <a href="#" class="discount_mark" id="<?php echo $row['id'];?>" status="<?php echo $row['mark'];?>">Not Paid</a>
					                    <?php } else {?>
					                        <a href="#" class="discount_mark" id="<?php echo $row['id'];?>" status="<?php echo $row['mark'];?>">Discount Paid</a>
					                    <?php }?>
					                </td>
					                <td><p class="complain"  user-comment="<?php echo $row['user_comment']?>" merchant-comment="<?php echo $row['merchant_comment']?>" merchant-img-url="<?php echo $row["merchant_complain"]?>" user-img-url="<?php echo $row["user_complain"]?>" data-target="complainModal_<?php echo $row['id'];?>" data-id="<?php echo $row['id'];?>" style="cursor:pointer;">Complain</p></td>
					            </tr>
					            
					        <?php }?>
					    </tbody>
					</table>
					<div class="total_discount" style="float:right;">
					    <h5>Total: <?php echo $sum;?> (RM)</h5>
					</div>
				</div>
			</main>
        </div>
        <div class="modal fade" id="complainModal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Complain</h4>
                    </div>
                    <form id ="data">
                        <div class="modal-body" style="padding-bottom:0px;">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Comment</label>
                            		<input type="text" name="complain" id = "complain" class="form-control comment" value="<?php echo $row['user_comment];'];?>" required>
                                </div>
                                <div class="form-group">
                                    <img src="" class="complain_image">
                                </div>
                                
                            </div>
                        </div>
                        <div class="modal-footer" style="padding-bottom:2px;">
                			<button class="btn btn-default" data-dismiss="modal" type="button">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /.widget-body badge -->
    </div>
    <!-- /.widget-bg -->

    <!-- /.content-wrapper -->
	<?php include("includes1/footer.php"); ?>
	<script type="text/javascript" src="/js/dropzone.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
	<script>
	    $(document).ready(function(){
	        jQuery(".dropzone").dropzone({
                sending : function(file, xhr, formData){
                },
                success : function(file, response) {
                    $(".complain_image").val(file.name);
                    
                }
            });
            $('#kType_table').DataTable({"bSort": false});
	        
	        $(".discount_mark").click(function(){
	            var status = $(this).attr("status");
	            var id = $(this).attr("id");
	            var data = {"method":"changeDiscountStatus", "status": status, "id": id};
	            $.ajax({
                  url:"functions.php",
                  type:"post",
                  data:data,    
                  success:function(data){
                    window.location.href="/admin_panel/kType.php";
                  }
                });
	        });
	    });
	    
	    $(".complain").click(function(e){
	        var id = $(this).attr('data-id');
	        var user_img_url = $(this).attr('user-img-url');
	        var merchant_img_url = $(this).attr('merchant-img-url');
	        var user_comment = $(this).attr('user-comment');
	        var merchant_comment = $(this).attr('merchant-comment');
	        $("#complainModal").modal();
	        $(".k_id").val(id);
	        if(user_img_url != ""){
	            $(".complain_image").attr({"src": "/uploads/"+user_img_url});
	        } else if (merchant_img_url != ""){
	            $(".complain_image").attr({"src": "/uploads/"+merchant_img_url});
	        } else {
	            $(".complain_image").attr({"src": ""});
	        }
	        if(user_comment != ""){
	            $(".comment").val(user_comment);
	        } else if (merchant_comment != ""){
	            $(".comment").val(merchant_comment);
	        } else {
	            $(".comment").val("");
	        }
	    });
	    
	    
        /*$(".complain_btn").click(function(){
            
            var data = {"method": "k_type", id: $(".k_id").val(), role: $(".role").val(), "complain": $(".comment").val(), "image": $(".complain_image").val()};
            $.ajax({
                  url:"/functions.php",
                  type:"post",
                  data:data,    
                  success:function(data){
                      $("#complainModal").modal('hide');
                        //$(".home-listing .grid-group-style").html(data);
                  }
            });
        });*/
	</script>
	
</body>

</html>
