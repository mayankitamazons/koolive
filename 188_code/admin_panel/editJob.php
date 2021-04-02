<?php 
include("config.php");

if(!isset($_SESSION['admin']))
{
	header("location:login.php");
}
if(isset($_GET['id'])){
    $id = $_GET['id'];
    $query = mysqli_query($conn, "select * from jobs where id ='$id'");
    $data = mysqli_fetch_assoc($query);
    $catId = $data['job_category_id'];
}
$a_m="jobs_category";
if(isset($_POST['edit-job'])){
    extract($_POST);
    // print_r($_POST);
    
    
    if($exDate==""){
        $expire_date= $dateExp ;     //dateExp
    }else{
        $expire_date = strtotime($exDate);
    }
    
    $query = mysqli_query($conn, "UPDATE `jobs` SET `title`='$title',`job_desc`='$jobDesc',`price`='$jPrice',`expire_date_utc`='$expire_date',`job_category_id`='$category',`job_provider_name`='$jProname',`job_provide_mobile`='$jProNo',`job_provider_desc`='$jobProDesc',`whatsAppGroup`='$wGNo',`salaryType`='$salaryType',`advance_salery`='$advance_salery',`salaryStatus`='$salaryStatus' WHERE id ='$uid'");
    
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
            <h3 class="mb-3">Edit Job</h3>
            <form action="editJob.php" method="POST">
                <div class="form-group">
                    <label for="title">Job Title <span style="color:red;">*</span></label>
                    <input type="text" name="title" class="form-control" required  placeholder="Job Title" value="<?php echo isset($data['title'])?$data['title']:'';?>">
                    <input type="hidden" name="uid"  value= "<?php echo $data['id']?>">
                </div>
                <div class="form-group">
                    <label for="title">Job Category<span style="color:red;">*</span></label>
                    <select name="category" class="form-control" required>
                        <option value="">--select job category--</option>
                        <?php $catQuery = mysqli_query($conn,"select * from job_category where status ='y'");
                            while($row=mysqli_fetch_assoc($catQuery)){?>
                                <option value="<?php echo $row['id']?>" <?php if(isset($catId)){if($row['id']==$catId){echo "selected";}}?> > <?php echo $row['category_name'];?> </option>
                       <?php }?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="title">Job Price <span style="color:red;">*</span></label>
                    <input type="number" required name="jPrice" class="form-control"  placeholder="Price" value="<?php echo isset($data['price'])?$data['price']:'';?>">
                </div>
                <div class="form-group">
                    <label for="comment">Job Description</label>
                    <textarea name="jobDesc" class="form-control" placeholder="Job Description" rows="5" id="comment"><?php echo isset($data['job_desc'])?$data['job_desc']:'';?></textarea>
                </div>
                
                <div class="form-group">
                <label for="">Salary Type</label>
                <div class="row">
                    
                    <div class="col-2">
                        <label class="radio-inline mr-2">Fixed fee</label><input type="radio" name="salaryType" <?php if($data['salaryType']=='monthly'){echo "checked";}?> value="monthly" required>
                    </div>
                    <div class="col-2">
                        <label class="radio-inline mr-2">Per Hours</label><input type="radio" name="salaryType" <?php if($data['salaryType']=='hour'){echo "checked";}?> value="hour" required>
                    </div>
                </div>
                </div>
				  <div class="form-group">
                <label for="">Are you willing to pay milestone in advance?</label>
                <div class="row">
                    
                    <div class="col-2">
                        <label class="radio-inline mr-2">Yes</label><input type="radio" name="advance_salery" value="yes" required <?php if($data['advance_salery']=='yes'){echo "checked";}?>>
                    </div>
                    <div class="col-2">
                        <label class="radio-inline mr-2">No</label><input type="radio" name="advance_salery" value="no" required <?php if($data['advance_salery']=='no'){echo "checked";}?>>
                    </div>
					<p>Note: milestone will only be released to freelancer only when employer has 100 % satisfied with the job.</p>
                </div>
                </div>
                
                <div class="form-group">
                <label for="">Salary Status</label>
                <div class="row">
                    
                    <div class="col-2">
                        <label class="radio-inline mr-2">Negotiable</label><input type="radio" name="salaryStatus" value="1" required <?php if($data['salaryStatus']=='1'){echo "checked";}?>>
                    </div>
                    <div class="col-2">
                        <label class="radio-inline mr-2">Not Negotiable</label><input type="radio" name="salaryStatus" value="0" required <?php if($data['salaryStatus']=='0'){echo "checked";}?>>
                    </div>
                </div>
                </div>

                <div class="form-group">
                    <label for="title">WhatsApp Group<span style="color:red;">*</span></label>
                    <input type="text"   name="wGNo" class="form-control"  placeholder="Whatapp Group name" value="<?php echo isset($data['whatsAppGroup'])?$data['whatsAppGroup']:'';?>">
                </div>
                    
                
                <div class="form-group">
                    <label for="birthday" >Expire Date</label> <small class="text-muted ml-4"><?php echo date("d-M-Y",$data["expire_date_utc"])?></small>
                    <input type="date" class="form-control" name="exDate" >
                    <input type="hidden" name="dateExp" value="<?php echo $data["expire_date_utc"]?>">
                </div>
                <div class="form-group">
                    <label for="title">Job Provider Name <span style="color:red;">*</span></label>
                    <input type="text" name="jProname" class="form-control"  placeholder="Job Provide Name" value="<?php echo isset($data['job_provider_name'])?$data['job_provider_name']:'';?>">
                </div>
                <div class="form-group">
                    <label for="title">Job Provider Contact <span style="color:red;">*</span></label>
                    <input type="tel"   required name="jProNo" class="form-control"  placeholder="Job Provide Mobile" value="<?php echo isset($data['job_provide_mobile'])?$data['job_provide_mobile']:'';?>">
                </div>
                <div class="form-group">
                    <label for="comment">Job Provider Description</label>
                    <textarea name="jobProDesc" class="form-control" placeholder="Job Provide Description" rows="5" id="comment"><?php echo isset($data['job_provider_desc'])?$data['job_provider_desc']:'';?></textarea>
                </div>
                
                <button type="submit" name="edit-job" class="btn btn-lg btn-outline-primary">Update</button>
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
	
	    
</body>

</html>
               