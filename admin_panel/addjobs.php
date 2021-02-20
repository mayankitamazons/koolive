<?php 
include("config.php");

if(!isset($_SESSION['admin']))
{
	header("location:login.php");
}

$a_m="jobs_category";
if(isset($_POST['add-job'])){
    extract($_POST);
    // print_r($_POST);
    if($wGno==''){
        $wn = 60123945670;
    }else{
        $wn = $wGNo;
    }

    
    $post_date = strtotime(Date("Y-m-d"));
    $expire_date = strtotime($exDate);
    

    $query = mysqli_query($conn, "INSERT INTO `jobs`(`title`, `job_desc`, `price`, `posted_date_utc`, `expire_date_utc`, `job_category_id`, `job_provider_name`, `job_provide_mobile`, `job_provider_desc`,`whatsAppGroup`, `salaryType`, `salaryStatus`,`advance_salery`) 
	VALUES ('$title','$jobDesc','$jPrice','$post_date','$expire_date','$category','$jProname','$jProNo','$jobProDesc','$wn','$salaryType','$salaryStatus','$advance_salery')");
    
    if($query){
        header('Location: jobs_list.php');
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

        <div class="container">
            <h3 class="mb-3">Add Job</h3>
            <form action="addjobs.php" method="POST">
                <div class="form-group">
                    <label for="title">Job Title <span style="color:red;">*</span></label>
                    <input type="text" name="title" class="form-control" required  placeholder="Job Title">
                </div>
                <div class="form-group">
                    <label for="title">Job Category<span style="color:red;">*</span></label>
                    <select name="category" class="form-control" required>
                        <option value="">--select job category--</option>
                        <?php $catQuery = mysqli_query($conn,"select * from job_category where status ='y'");
                            while($row=mysqli_fetch_assoc($catQuery)){?>
                                <option value="<?php echo $row['id']?>" > <?php echo $row['category_name'];?> </option>
                       <?php }?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="title">Job Price <span style="color:red;">*</span></label>
                    <input type="number" required name="jPrice" min=1 class="form-control"  placeholder="Price">
                </div>
                <div class="form-group">
                    <label for="comment">Job Description</label>
                    <textarea name="jobDesc" class="form-control" minlength=150 placeholder="Job Description" rows="5" id="comment"></textarea>
                    <span style="color:red;font-weight:600;" id="error"></span>
                </div>
                
                <div class="form-group">
                    <label for="">Salary Type</label>
                    <div class="row">
                        
                        <div class="col-2">
                            <label class="radio-inline mr-2">Fixed fee</label><input type="radio" name="salaryType" value="monthly" required>
                        </div>
                        <div class="col-2">
                            <label class="radio-inline mr-2">Per Hours</label><input type="radio" name="salaryType" value="hour" required>
                        </div>
                    </div>
                </div>
				<div class="form-group">
                    <label for="">Are you willing to pay milestone in advance?</label>
                    <div class="row">
                        
                        <div class="col-2">
                            <label class="radio-inline mr-2">Yes</label><input type="radio" name="advance_salery" value="yes" required>
                        </div>
                        <div class="col-2">
                            <label class="radio-inline mr-2">No</label><input type="radio" name="advance_salery" value="no" required>
                        </div>
                        <p>Note: milestone will only be released to freelancer only when employer has 100 % satisfied with the job.</p>
                    </div>
                </div>
                
                <div class="form-group">
                <label for="">Salary Status</label>
                <div class="row">
                    
                    <div class="col-2">
                        <label class="radio-inline mr-2">Negotiable</label><input type="radio" name="salaryStatus" value="1" required>
                    </div>
                    <div class="col-2">
                        <label class="radio-inline mr-2">Not Negotiable</label><input type="radio" name="salaryStatus" value="0" required>
                    </div>
                </div>
                </div>

                <div class="form-group">
                    <label for="title">WhatsApp Group<span style="color:red;">*</span></label>
                    <input type="text"    name="wGNo" class="form-control"  placeholder="Whatapp link">
                </div>
                    
                
                <div class="form-group">
                    <label for="birthday">Expire Date</label>
                    <input type="date" class="form-control" name="exDate" required>
                </div>
                <div class="form-group">
                    <label for="title">Job Provider Name <span style="color:red;">*</span></label>
                    <input type="text" required name="jProname" class="form-control"  placeholder="Job Provide Name">
                </div>
                <div class="form-group">
                    <label for="title">Job Provider Contact <span style="color:red;">*</span></label>
                    <input type="tel" required   required name="jProNo" class="form-control"  placeholder="Job Provide Mobile">
                </div>
                <div class="form-group">
                    <label for="comment">Job Provider Description</label>
                    <textarea name="jobProDesc" class="form-control" placeholder="Job Provide Description" rows="5" id="comment"></textarea>
                </div>
                
                <button type="submit" name="add-job" class="btn btn-lg btn-outline-primary" id="submitForm">Submit</button>
            </form>
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
	 $(document).ready(function(){
		$("#comment").blur(function(){
				
			var data = $(this).val().length;
			// alert(data);
			if(data>150){
				$('#submitForm').prop('disabled',false);
				$('#error').text("");
				
			}else{
				$('#submitForm').prop('disabled',true);
				// alert('Job description more than 150 charactors');
				$('#error').text("Job description more than 150 characters");
			}	
		 });	
	 });
 </script> 
	    
</body>

</html>
               