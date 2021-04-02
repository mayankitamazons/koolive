<?php 
include("config.php");
if(!isset($_SESSION['admin']))
{
	header("location:login.php");
}
if(isset($_POST['msg_type']))
{
	extract($_POST);
	// print_R($_POST);
	// die;
	$rand= substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1,4);
	if($redirect_url=="")
	$redirect_url=$site_url."/index.php?vs=".$rand;
	if($send_msg)
	{
		
		$result=exec("/usr/bin/python myscript.py");
		$resultarray=explode(",",$result);	
		if (count($resultarray)>0) {
						 // code...
				$data['camp_name']=$camp_name=$resultarray[0];
				$data['sign']=$sign=$resultarray[1];
				$data['push_email']=$push_id;
				$data['title']='Place Order';
				$data['message']=$send_msg;
				$data['redirectURL']=$redirect_url;
				include 'push.php';
				$user = new Push();
				$resultpush = $user->send_push($data);
						// print_R($resultpush);
				$_SESSION['msg']=$resultpush;
						// die;     
					 }
		
	}		
}
  
$a_m="send_push";   
?>
<!DOCTYPE html>
<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">

<head>   
    <?php include("includes1/head.php"); ?>
	
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css">
	
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
	#kType_table_paginate
	{
		display:none !important;
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
	.select2-container--bootstrap{
	    width: 175px;
	    display: inline-block !important;
	    margin-bottom: 10px;
	}
	.select2-dropdown {
	  z-index: 90019;
	}
	@media  (max-width: 750px) and (min-width: 300px)  {
	    .select2-container--bootstrap{
	        width: 300px;
	    }
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
					<h2 class="text-center wallet_h">Send Push message</h2>
					<h2 style="color:red;"><?php  if(isset($_SESSION['s'])){ echo $_SESSION['s']; unset($_SESSION['s']);}?></h2>
					 <form class="form-horizontal" id="send_push_form" method="post" style="width:80%;">
					   <?php if(isset($_SESSION['msg'])){ if($_SESSION['msg']['status']){ ?>
					   <p style="color:black"><?php  ?>Push msg send succeesfully </p>
					   <?php } else { ?>
					    <p style="color:red"><?php print_r($_SESSION['msg']) ?></p>
					   <?php } ?>
						
					   <?php unset($_SESSION['msg']);   }  ?>
						<div class="form-group">
						  <label class="control-label col-sm-2" for="email">Template:</label>
						  <div class="col-sm-10">
							 <select class="form-control template_type" id="template_type" name="msg_type">
								  <option value="">Select msg template</option>
								  <option value="1">Morning msg</option>
								  <option value="2">Lunch msg</option>
								  <option value="3">Dinner msg</option>
								  <option value="4">Custom Msg</option>
							 </select>
							 <small id="template_error" style="color:red;display:none;">Select Template type</small>
						  </div>
						</div>
						<div class="form-group">
						  <label class="control-label col-sm-2" for="pwd">Message:</label>
						  <div class="col-sm-10">          
							<textarea rows="5" class="form-control" cols="15" id="send_msg" name="send_msg"></textarea>
						  </div>
						</div>
						<div class="form-group url_form" style="display:none;">
						  <label class="control-label col-sm-2" for="pwd">Redirect url:</label>
						  <div class="col-sm-10">          
							<input type="text" class="form-control" id="redirect_url" name="redirect_url"/>
						  </div>
						</div>
						
						<div class="form-group">        
						  <div class="col-sm-offset-2 col-sm-10">
						  <button type="submit" class="form-control btn btn-primary" id="send_button">Send</button>

						  </div>
						</div>
					</form>
					
				</div>
			</main>
        </div>
      
        <!-- /.widget-body badge -->
    </div>
    <!-- /.widget-bg -->

    <!-- /.content-wrapper -->
	<?php include("includes1/footer.php"); ?>
	<script type="text/javascript" src="/js/dropzone.js"></script>
	<script>
	 	$(document).ready(function() {
			$( "#send_button").click(function() {
				var template_type=$('#template_type').val();
				if(template_type=='')
				{
					$('#template_error').show();
				}
				else
				{
					var checkstr =  confirm('are you sure you want to send sms?');
						if(checkstr == true){
						  // do your code
						  $('#send_push_form').submit();
						}else{
						return false;
						}
				}
			});
			$( ".template_type" ).change(function() {
				var selected_val=$( this ).val();
				$('#template_error').hide();
				if(selected_val==1)
				{
				   var msg="Hey Good Morning!,Do you want to book your Lunch with us?";	
				} else if(selected_val==2)
				{
					var msg="Hey Good Afternoon!,Do you want to book your Lunch with us?";	
				} else if(selected_val==3)
				{
					var msg="Hey Good Evening!,Do you want to book your Lunch with us?";	
					var msg="Good evening!";
				} else if(selected_val==4)
				{
				  var msg="";
                  $('.url_form').show();				  
				}
				$('#send_msg').val(msg);
				   
			});
		});
	</script>
</body>

</html>
