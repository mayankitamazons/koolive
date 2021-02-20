<?php 
include("config.php");

$users = mysqli_query($conn, "SELECT * FROM users WHERE user_roles = '1' order by id desc");
$merchants = mysqli_query($conn, "SELECT * FROM users where user_roles='2'");

$merchant = "";
$user = "";
if(!isset($_SESSION['admin']))
{
	header("location:login.php");
}


if(isset($_POST['add_agent'])){

	$name = $_POST['name'];
	$code = $_POST['a_code'];
	$type = $_POST['a_type'];
	$merchant_name = $_POST['m_name'];
	$u_no = $_POST['u_no'];

	//echo $name . " " . $code . " " . $type . " " . $merchant;

	$m_id = mysqli_fetch_assoc(mysqli_query($conn,"SELECT id FROM users WHERE name='$merchant_name' LIMIT 1"))['id'];
	// $u_id = mysqli_fetch_assoc(mysqli_query($conn,"SELECT id FROM users WHERE mobile_number='$u_no' LIMIT 1"))['id'];

	$q = mysqli_query($conn,"INSERT INTO agents(id, name, type, active, merchant_id, user_id) VALUES ('null', '$name', '$type', '0', '$m_id', '$u_no')");
	$id = mysqli_insert_id($conn);
	$q2 = mysqli_query($conn, "INSERT INTO agent_code(id, agent_id, code, date) VALUES (null,'$id','$code',CURRENT_TIMESTAMP)");
	if(!$q && !$q2)
		die(mysqli_error($conn));
	else
		die(true);
}
	
$a_m="agents";
?>
<!DOCTYPE html>
<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">

<head>
    <?php include("includes1/head.php"); ?>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="/css/dropzone.css" type="text/css" /> 
    <link rel="stylesheet" href="./css/chosen.min.css" type="text/css" /> 
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
	.select2-container--bootstrap{
	    width: 175px;
	    display: inline-block !important;
	    margin-bottom: 10px;
	}
	.ui-menu{
		background: white;
		width: fit-content;
		max-width: 90%;
		padding-left: 0;
		border: 2px solid gray;
		max-height: 200px;
		overflow-y: auto;
		border-radius: 0 0 5px 5px;
		border-top: unset;
	}
	.ui-menu > li{
		list-style: none;
		padding: 5px;
		box-sizing: border-box;
	}
	.ui-menu > li:nth-child(even){
		background: #f1f1f1;
	}
	.ui-menu > li:hover{
		background: #ccc;
		cursor: pointer;
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

            <div class="row">
                <div class="col-md-12">
                    <h3>
                        Add a new agent
                    </h3>
					<form>
						<div class="row">
							<div class="col-md-3 col-sm-12">
								<div class="form-group">
									<label for="name">Agent name</label>
									<input type="text" class="form-control" id="name" placeholder="Agent name">
								</div>
							</div>
							<div class="col-md-3 col-sm-8">
								<label for="generate-button">Agent's code</label>
								<input type="text" class="form-control" name="agent-code" id="agent-code"/>
							</div>

							<div class="col-md-2 col-sm-4">
								<label class="" for="inlineFormCustomSelect">Agent type</label>
								<div>
									<select class="custom-select col-md-12" id="agent-type">
										<option selected value="null">Choose type...</option>
										<option value="0">Type A</option>
										<option value="1">Type B</option>
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="ui-widget">
									<div class="form-group">
										<label for="tags_merchant">Choose a merchant</label>
										<input class="form-control" id="tags_merchant" placeholder="Introduce merchant's name"/>
									</div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="ui-widget">
									<div class="form-group">
										<label for="tags_user">Choose a user</label>
										<input class="form-control" id="tags_user" placeholder="Introduce user's ID"/>
									</div>
								</div>
							</div>

							<div class="col-md-12" style="margin-top: 20px;">
								<button type="submit" name="add-agent" class="btn btn-lg btn-outline-primary">Submit</button>
							</div>
						</div>
					</form>
                </div>
            </div>
			</main>
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
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


	<script type="text/javascript">
		var merchant_tags = [];
		var user_ids = [];
		$(document).ready(function(){
			<?php 
				$qMerchant = mysqli_query($conn, "SELECT id,name FROM users WHERE user_roles = '2' ORDER BY name ASC");
				while($row = mysqli_fetch_assoc($qMerchant)){
					echo "merchant_tags.push('" . $row['name'] . "');\n";
					echo "console.log('" . $row['name'] . "');\n";
				}
				$qUsers = mysqli_query($conn, "SELECT id FROM users WHERE user_roles = '1' ORDER BY id ASC");
				while($row = mysqli_fetch_assoc($qUsers)){
					if($row['id'] != '')
						echo "user_ids.push('" . addslashes($row['id']) . "');\n";
				}
			?>
			$( "#tags_merchant" ).autocomplete({
				source: merchant_tags
			});
			$( "#tags_user" ).autocomplete({
				source: user_ids
			});
			var randHex = len => {
				var maxlen = 8,
					min = Math.pow(16,Math.min(len,maxlen)-1) 
					max = Math.pow(16,Math.min(len,maxlen)) - 1,
					n   = Math.floor( Math.random() * (max-min+1) ) + min,
					r   = n.toString(16);
				while ( r.length < len ) {
					r = r + randHex( len - maxlen );
				}
				return r;
			};
			$("#generate-button").click(function(e){
				e.preventDefault();
				$("#agent-code").val(randHex(12).toUpperCase());
			});
			$("form").on("submit", function(e){
				e.preventDefault();

				var name = $("#name").val();
				var agent_code = $("#agent-code").val();
				var agent_type = $("#agent-type").val();
				var merch_name = $("#tags_merchant").val();
				var u_no = $("#tags_user").val();

				console.log(name);
				console.log(agent_code);
				console.log(agent_type);
				console.log(merch_name);
				console.log(u_no);
				if(name == '' || agent_code == '' || agent_type == 'null' || merch_name == 'null' || u_no == 'null'){
					alert("You have to fill all the options.")
				}else{
					$.post("./agents-add.php", {
						name: name,
						a_code: agent_code,
						a_type: agent_type,
						m_name: merch_name,
						u_no: u_no,
						add_agent: true
					}, function(data, res){
						console.log(data);
						if(data == true)
							window.location.href = "./agents.php";
						else
							console.log(data);
					});
				}

			});
		});
	</script>


</body>

</html>

