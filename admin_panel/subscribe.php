<?php 
include("config.php");

$year = date("Y");
$month = date("m");
$start_dt = $year."-".$month."-01"." 00-00-00";
$end_dt = $year."-".$month."-31"." 23-59-59";

$subscribe_history = mysqli_query($conn, "SELECT * FROM ( SELECT * FROM subscribes WHERE created_dt > '".$start_dt."' AND created_dt < '".$end_dt."' ORDER BY created_dt DESC ) a group by a.referral_id");

if($_POST['filter']){
    $year = $_POST['year'];
    $month = $_POST['month'];
    $start_dt = $year."-".$month."-01"." 00-00-00";
    $end_dt = $year."-".$month."-31"." 23-59-59";
    
    $subscribe_history = mysqli_query($conn, "SELECT * FROM ( SELECT * FROM subscribes WHERE created_dt > '".$start_dt."' AND created_dt < '".$end_dt."' ORDER BY created_dt DESC ) a group by a.referral_id");
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
					<h2 class="text-center wallet_h">Subscribes</h2>
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
					            <th>Name</th>
					            <th>Referral ID</th>
					            <th>Mobile Number</th>
					        </tr>
					    </thead>
					    <tbody>
					        <?php $sum = 0; ?>
					        <?php while ($row=mysqli_fetch_assoc($subscribe_history)){
					        ?>
					            <tr>
					                <td><?php echo $row['created_dt'];?></td>
					                <td><?php echo $row['name'];?></td>
					                <td><?php echo $row['referral_id'];?></td>
					                <td><?php echo $row['mobile_number'];?></td>
					                
					            </tr>
					            
					        <?php }?>
					    </tbody>
					</table>
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
