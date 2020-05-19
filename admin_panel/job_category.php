<?php 
include("config.php");

if(!isset($_SESSION['admin']))
{
	header("location:login.php");
}

$a_m="jobs_category";
if(isset($_POST['addCat'])){
	extract($_POST);
	if(isset($_FILES['image'])){
		$errors= array();
		$file_name = $_FILES['image']['name'];
		$file_size =$_FILES['image']['size'];
		$file_tmp =$_FILES['image']['tmp_name'];
		$file_type=$_FILES['image']['type'];
		$file_ext=strtolower(end(explode('.',$_FILES['image']['name'])));
		
		$extensions= array("jpeg","jpg","png");
		
		if(in_array($file_ext,$extensions)=== false){
		   $errors[]="extension not allowed, please choose a JPEG or PNG file.";
		}
		
		if($file_size > 2097152){
		   $errors[]='File size must be excately 2 MB';
		}
		
		if(empty($errors)==true){
		   move_uploaded_file($file_tmp,"category/".$file_name);
		//    echo "Success";
		}else{
		   print_r($errors);
		}
	 }
	 
	 $query = mysqli_query($conn, "INSERT INTO `job_category`( `category_name`, `category_icon`, `status`) VALUES ('$name','$file_name','$status')");
	//  if($query){$_SESSION['show_msg']='Successfully Created';}else{$_SESSION['show_msg']='Error in Creating';}
}
if(isset($_POST['editCat'])){
	extract($_POST);
	if(isset($_FILES['image'])){
		$errors= array();
		$file_name = $_FILES['image']['name'];
		$file_size =$_FILES['image']['size'];
		$file_tmp =$_FILES['image']['tmp_name'];
		$file_type=$_FILES['image']['type'];
		$file_ext=strtolower(end(explode('.',$_FILES['image']['name'])));
		
		$extensions= array("jpeg","jpg","png");
		
		if(in_array($file_ext,$extensions)=== false){
		   $errors[]="extension not allowed, please choose a JPEG or PNG file.";
		}
		
		if($file_size > 2097152){
		   $errors[]='File size must be excately 2 MB';
		}
		
		if(empty($errors)==true){
		   move_uploaded_file($file_tmp,"category/".$file_name);
		//    echo "Success";
		}else{
		   print_r($errors);
		}
	 }
	 $file_name;
	 if($file_name==''){$file_name = $exiImg;}
	
	 $query = mysqli_query($conn, "UPDATE `job_category` SET`category_name`='$name',`category_icon`='$file_name' WHERE id='$id' ");
	//  if($query){$_SESSION['show_msg']='Successfully updated';}else{$_SESSION['show_msg']='Error in Updated';}
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
						<div class="card">
							<div class="card-header">
                        		<h3>Add Category</h3>
                    		</div>
							
							<div class="card-body">
							<form action="job_category.php" method="POST" class = "form-inline" role = "form" enctype="multipart/form-data">
								<div class = "form-group">
									<label class = "sr-only" for = "name">Category Name</label>
									<input type = "text" name="name" class = "form-control mr-3"  placeholder = "Enter Category Name">
								</div>
								<div class = "form-group">
									<label class = "sr-only" for = "inputfile">File input</label>
									<input type = "file" name="image" id = "inputfile">
								</div>
								<div class = "form-group">
									<label class = "sr-only"  for = "status">Status</label>
									<select name="status" class="form-control ml-2">
										<option value="y">Active</option>
										<option value="n">Deactive</option>
									</select>
								</div>
								
								<button type = "submit" name="addCat" class = "btn btn-default ml-2">Add</button>
							</form>
							</div>
						</div>
					</div>
					<h2 class="text-center wallet_h">Jobs Category List</h2>
				
					<?php if(isset($_SESSION['show_msg'])){ echo $_SESSION['show_msg']; unset($_SESSION['show_msg']);} ?>
					<table class="table table-striped kType_table" id="kType_table"> 
					    <thead>
					        <tr>
							<th>Particular</th>
							<th>Category</th>
							<th>Icon</th>
							<th>Action</th>
							
							<th>Status </th>
							
							
						  </tr>
					    </thead>
					   <tbody>
                    	<?php
                    	$i=1;
						$query=mysqli_query($conn,"select * from job_category where status='y'");

                    	while($row=mysqli_fetch_assoc($query)){
							 ?>
                        	  <tr>
									<td> <?php echo $i; ?> </td>
									<td class="name" data-id=<?php echo $row['id']; ?> style="cursor:pointer;"><?php echo $row['category_name'];  ?></td>
									<td><img  style="max-width: 200px;max-height: 80px;" src="category/<?php echo $row['category_icon'];?>"> </td>
									<td>
									<a href="delete_jobCat.php?c_id=<?php echo $row['id']; ?>">Delete</a>
									<a href="javascript:void(0)" class="edit-cat" cId="<?php echo $row['id']; ?>" cName="<?php echo $row['category_name'];?>" cImg="<?php echo $row['category_icon']; ?>">Edit </a>
									</td>
									<td>
									<?php if($row['status']=='y'){ ?>
									 <button class="btn btn-xs btn-success "  change_type="n" data_id="<?php echo $row['id']?>">Active</button>
									<?php } else {?>
									 <button class="btn btn-xs btn-danger " form_type="user" change_type="y" data_id="<?php echo $row['id']?>">De-Active</button>
									<?php } ?>
									</td>
							</tr>
                    	<?php
                            $i++;  
                    	}?>
                	</tbody>
					</table>   
					
				</div>
			</main>
        </div>
      
        <!-- /.widget-body badge -->
    </div>
    <!-- /.widget-bg -->
	<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Edit Category</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form action="job_category.php" method="POST" enctype="multipart/form-data">
					<div class="form-group">
						<label for = "name">Category Name</label>
						<input type = "text" name="name" class = "form-control" id="name"   placeholder = "Enter Category Name">
						<input type = "hidden" name="id"  id="id"  >
					</div>
					<div class="form-group">
						<label for = "name">Category Name</label>
						<input type = "file" name="image" class = "form-control">
						<input type = "hidden" name="exiImg"  id="exiImg"  >
					</div>
				
			</div>
			<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="submit" name="editCat" class="btn btn-primary">Save changes</button>
				</form>
			</div>
			</div>
		</div>
		</div>
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
		$('.edit-cat').click(function(){
			var name = $(this).attr('cName');
			var img = $(this).attr('cImg');
			var id = $(this).attr('cId');
			// alert(img);
			$('#name').val(name);
			$('#id').val(id);
			$('#exiImg').val(img);
			$('#editModal').modal('show');
		});
	});
</script>




	
	
</body>

</html>
