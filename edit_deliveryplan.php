<?php 

include("config.php");

if(!isset($_SESSION['login']))

{

	header("location:login.php");

}

$site_url="https://www.koofamilies.com/";



// print_R($bank_data);

// die;

if(isset($_GET['plan_id']))

{

	$u_query =mysqli_query($conn,"SELECT * FROM delivery_plan WHERE id='".$_GET['plan_id']."'");  

	$p_data=mysqli_fetch_assoc($u_query);

	

}

if(isset($_POST['submit']))

{	

	extract($_POST);

	$plan_id=$_POST['plan_id'];

	// echo "update delivery_plan set min_distance='$min_distance',max_distance='$max_distance',charge='$charge' where id='$plan_id'";

	// die;

			mysqli_query($conn, "update delivery_plan set min_distance='$min_distance',max_distance='$max_distance',charge='$charge' where id='$plan_id'");

			header('Location: deliveryplan.php');

}



?>

<!DOCTYPE html>

<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">



<head>

    <?php include("includes1/head.php"); ?>

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

	.account_kType{

	    margin-bottom: 10px;

	}

	/* Jupiter 24.02.19*/

	.payment_tick{

		width: 20px;

		height: 20px;

		margin-right: 15px;

	}

	.payment_label{

		margin-top: -27px;

    	margin-left: 30px;

	}

	.payment_btn{

		margin-left: 125px;

	    display: block;

	    margin-bottom: 15px;

	    margin-top: -45px;

	    line-height: 0.57143;

	}

	.custom_message_val{

		width: 100%;

		height: 200px;

		padding: 5px;

		box-sizing: border-box;

		border-radius: 5px;

		border: 1px solid #e4e9f0;

		resize: none;

	}

	</style>  

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

					<div class="container">

					<?php

						if(isset($error))

						{

							echo "<div class='alert alert-info'>".$error."</div>";

						}

					?>

					</div>  

					<div class="container" > 

					    <div class="row">

					        <div class="well col-md-12">

					        

								<form method="post" enctype="multipart/form-data" id="profile_account" action="">

									<div class="panel price panel-red">

										<h2>Edit Delivery Plan</h2>

									</div>  

									

									<div class="form-group">

										<div class="row">

											<input type="hidden" name="plan_id" value="<?php echo $p_data['id']; ?>" />

											<div class="col-md-4">

												<label><?php echo $language['min_distance']; ?></label>

												<input type="text" value="<?php echo $p_data['min_distance']; ?>" required oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" maxlength="4" name="min_distance" class="form-control" placeholder="<?php echo $language['min_distance']; ?>">

											</div>

											<div class="col-md-4">

												<label><?php echo $language['max_distance']; ?></label>

												<input type="text" value="<?php echo $p_data['max_distance']; ?>"  required oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" maxlength="4" name="max_distance" class="form-control" placeholder="<?php echo $language['max_distance']; ?>">

										</div>

										<div class="col-md-4">

												<label><?php echo $language['charge']; ?></label>

												<input type="text" value="<?php echo $p_data['charge']; ?>"  required oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" maxlength="4" name="charge" class="form-control" placeholder="Order <?php echo $language['charge']; ?>">

										</div>

										</div>																		

									</div>

									

								

									

									

									<button type="submit" value="Edit" name="submit" id="formSubmit" class="btn btn-primary">Submit</button>

								</form>

					        	

							</div>

						</div>

					</div>

				</div>				

			</main>

        </div>

        

    </div>

    <!-- /.widget-bg -->



    <!-- /.content-wrapper -->

    <?php include("includes1/footer.php"); ?>

</body>



</html>

<style>

select {

    height: 30px;

}

</style>



    </script>   

<style>

  .tele_num{

	font-weight: 400;

    display: block;

    width: 345%;

    padding: 0.5625rem 1.2em;

    font-size: 0.875rem;

    line-height: 1.57143;

    color: #74708d;

    background-color: #fff;

    background-image: none;

    background-clip: padding-box;

    border: 1px solid #e4e9f0;

    border-radius: 0.25rem;

    -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);

    box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);

    -webkit-transition: border-color ease-in-out 0.15s, -webkit-box-shadow ease-in-out 0.15s;

    transition: border-color ease-in-out 0.15s, -webkit-box-shadow ease-in-out 0.15s;

    transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;

    transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s, -webkit-box-shadow ease-in-out 0.15s;

}

h3.text_qrcode {

    width: 100%;

}

.credit_card{

 display:none;

}

.branch_details{

display:none;

}

div#multiSelectCombo {

    width: 450px!important;

}

</style>

<script

  src="https://code.jquery.com/jquery-3.4.1.min.js"

  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="

  crossorigin="anonymous"></script>

<script src="jquery-1.9.1.min.js"></script>













    <script src="http://ajax.aspnetcdn.com/ajax/modernizr/modernizr-2.8.3.js"></script>

    <script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>

    <script src="http://code.jquery.com/ui/1.11.1/jquery-ui.min.js"></script>



   

    