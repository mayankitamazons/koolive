<?php 
include("config.php");

	$total_rows = mysqli_query($conn, "SELECT * FROM subscription ");
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
 td.sub_pop_up {
    cursor: pointer;
}
td.del {
    cursor: pointer;
}

<!--new style--->

.table > tbody > tr:first-child > td {
    border-top: none;
    border-right: 2px solid #efefef;
}
tr {
    border-bottom: 2px solid #efefef;
}
td {
    border-top: 1px solid #efefef;
    border-right: 2px solid #efefef;
}
th {
    border-right: 1px solid #efefef;
}
thead {
    border: 2px solid #efefef;
}

</style>
 
    <?php include("includes1/head.php"); ?>
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
                <div class="row" id="main-content" style="padding-top:25px">
	<table class="table table-striped">
    <thead>
      <tr>
		  <th>S.NO</th>
			<th>Package Name</th>
			<th>Subscritpion Period</th>
			<th>Package Fee</th>
			<th>Categories Created</th>
			<th>Product Listing</th>
			<th>company website</th>
			<th>company video</th>
			<th>Advertisement sms</th>
			<th>About Us </th>
			<th>Referral commission </th>
			<th>Merchant welcome note</th>
			<th>Search nearby restaurant</th>
			<th>Renewal Fee</th>
<!--
			<th>Commission </th>
-->
			<th>Buy To </th>
			<th>Action</th>
      </tr>
    </thead>
	
	<?php 
  $i=1;
	while ($row=mysqli_fetch_assoc($total_rows)){
	?>
    <tbody>
      <tr>
		<td><?php echo $i; ?></td>
        <td><?php echo $row['subscription_name'];  ?></td>
        <td><?php echo $row['subscription_period'];  ?></td>
		   <td><?php echo $row['subscription_rate'];  ?></td>
		   <td><?php echo $row['categories'];  ?></td>
		  <td><?php echo $row['subscription_qyt'];  ?></td>
		  <td><?php echo $row['company_website'];  ?></td>
		  <td><?php echo $row['company_video'];  ?></td>
		  <td><?php echo $row['advertisement_sms'];  ?></td>
		  <td><?php echo $row['about'];  ?></td>
		  <td><?php echo $row['referral_commision'];  ?></td>
		  <td><?php echo $row['welcome_note'];  ?></td>
		  <td><?php echo $row['search_resataraunt'];  ?></td>
		  <td><?php echo $row['renewal_fee'];  ?></td>
<!--
		  <td><?php// echo $row['amount_com'].' %';  ?></td>
-->
		  <td>
			  <a href="http://www.koohealthywater.com" target="_blank">koohealthywater</a>
		</td>
      <td class="sub_pop_up" data-id="<?php echo $row['id']; ?>">Edit</td>  
        <td class="del" data-del="<?php echo $row['id']; ?>">Delete</td>
      </tr>
	  
      <?php
	$i++; }
	  ?>
	  
    </tbody>
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
    echo "<li $active><a href='?page=$i'>$i</a></li>";
   }
 ?>
 </ul>
</div>
<div>
	 <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Edit Product Subscription</h4>
        </div>
        <div class="modal-body" style="padding-bottom:0px;">
        
		 <div class="col-sm-10">

		    <!--<textarea id='updated_status' class="form-control" value=""> </textarea> -->

      <form id ="data">
      <div class="form-group">
      <div class="form-group">

                    <input type="hidden" id="id" name="id" value=""> 

<div class="form-group">
										<label>Package Name</label>
										<input type="text" name="productname" id="product_name" class="form-control" value="" required>
									</div>
								<div class="form-group">
										<label>Subscritpion Period</label>
										<input type="text" name="period" id="period" class="form-control" value="" required>
									</div>
										<div class="form-group">
										<label>Package Fee</label>
										<input type="text" name="package_fee" id="package_fee" class="form-control" value="" required>
									</div>
								<div class="form-group">
										<label>Categories Created</label>
										<select class="form-control" name="catgeories" id="catgeories">
											<option value="">Select Options</option>
											<option value='Yes' >Yes</option>
											<option value='No' >No</option>
										</select>
									</div>
									
									<div class="form-group">
										<label>Product Listing</label>
										<input type="text" name="product_type" id="product_type" class="form-control" value="" required>
									</div>
								
								<div class="form-group">
										<label>Display of company website</label>
										<select class="form-control" name="website" id="website">
											<option value="">Select Options</option>
											<option value='Yes' >Yes</option>
											<option value='No' >No</option>
										</select>
								<div class="form-group">
										<label>Upload of company video</label>
										<select class="form-control" name="video" id="video">
											<option value="">Select Options</option>
											<option value='Yes' >Yes</option>
											<option value='No' >No</option>
										</select>
									</div>
								<div class="form-group">
										<label>Advertisement sms </label>
										<select class="form-control" name="sms" id="sms">
											<option value="">Select Options</option>
											<option value='Yes' >Yes</option>
											<option value='No' >No</option>
										</select>
									</div>
								<div class="form-group">
										<label>About Us </label>
										<input type="text" name="about" class="form-control" id="about" value="" required>
									</div>
								<div class="form-group">
										<label> Referral commission</label>
										<select class="form-control" name="referral" id="referral">
											<option value="">Select Options</option>
											<option value='Yes' >Yes</option>
											<option value='No' >No</option>
										</select>
									</div>
								<div class="form-group">
										<label>  Merchant welcome note</label>
										<select class="form-control" name="welcome" id="welcome">
											<option value="">Select Options</option>
											<option value='Yes' >Yes</option>
											<option value='No' >No</option>
										</select>
									</div>
								<div class="form-group">
										<label>Search nearby restaurant</label>
										<select class="form-control" name="restaurant" id="restaurant">
											<option value="">Select Options</option>
											<option value='Yes' >Yes</option>
											<option value='No' >No</option>
										</select>
									</div>
									<div class="form-group">
										<label>Renewal Fee</label>
										<input type="text" name="renewal" class="form-control" id="renewal" value="" >
									</div>
									<br>
<!--
									<input type="submit" class="btn btn-block btn-primary" name="submit" value="Submit">
-->
								</div>
							

		</div>

        </div>
		</div>
        <div class="modal-footer" style="padding-bottom:2px;">
			<button class="update">Submit</button>
        </div>
      </form>
      </div>
  
 <div class="modal fade" id="myModal" role="dialog" >
    <div class="modal-dialog modal-lg">
      <div class="modal-content" id="modalcontent">
       <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
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
  $(".sub_pop_up").click(function(){
	  $("#myModal").modal("show");
	  var userid = $(this).data("id");
    //alert(userid);
    $.ajax({
		  
		  url :'sub_edit.php',
		  type:'POST',
      dataType : 'json',
      data:{showid:userid},
		  success:function(response){
		//~ alert(response);
      console.log(response);
      $("#id").val(response.id);
      $("#product_name").val(response.subscription_name);
      $("#period").val(response.subscription_period);
      $("#package_fee").val(response.subscription_rate);
      $("#catgeories").val(response.categories);
      $("#product_type").val(response.subscription_qyt);
      $("#video").val(response.company_video);
      $("#sms").val(response.advertisement_sms);
      $("#about").val(response.about);
      $("#referral").val(response.referral_commision);
      $("#welcome").val(response.welcome_note);
      $("#restaurant").val(response.search_resataraunt);
      $("#renewal").val(response.renewal_fee);
          
		  }		  
	  }); 
  });

$('.update').on('click', function() {
     form = jQuery("#data").serialize();
           $.ajax({
               url: 'sub_update.php',
               type: 'POST',
               data: form,

        success: function(data) {
        console.log(data);
         //alert(data);
         //location.reload();
          }
           });
       });
       
         $('.del').click(function(){
    var id=$(this).data("del");
    //~ alert(id);
   $.ajax({
            url:'sub_del.php',
           type:'POST',
            data:{id:id},
            success: function(data) {
				//~ alert(data);
         location.reload();
           

         }
        
        });
    });
	</script>
</body>

</html>
