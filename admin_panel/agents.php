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
if(isset($_GET['q']) && $_GET['q'] == "getInfoAgent"){
	
	$id = $_GET['id'];
	
	$res = mysqli_fetch_assoc(mysqli_query($conn, "SELECT agents.* FROM agents INNER JOIN users WHERE agents.id = '$id' AND users.id=agents.user_id"));
	$code = mysqli_fetch_assoc(mysqli_query($conn, "SELECT code FROM agent_code WHERE agent_id = '$id' ORDER BY date DESC LIMIT 1"))['code'];
	array_push($res, ['code' => $code]);
	echo json_encode($res);
	die();
}



if(isset($_POST['q']) && $_POST['q'] == "updateData"){

	$id = $_POST['id'];
	$name = $_POST['name'];
	$code = $_POST['code'];
	$type = $_POST['agent_type'];
	$agent_status = $_POST['agent_status'];
	$merchant_id = $_POST['merchant_id'];

	$res = mysqli_query($conn, "UPDATE agents SET name='$name', type='$type', active='$agent_status', merchant_id='$merchant_id' WHERE id='$id'");
	$res2 = mysqli_query($conn, "INSERT INTO agent_code(id, agent_id, code, date) VALUES (null,'$id','$code',CURRENT_TIMESTAMP)");

	die(($res == true && $res2 == true) ? "true" : mysqli_error($conn));

}

if(isset($_POST['q']) && $_POST['q'] == "removeAgent"){

	$id = $_POST['id'];
	
	$res = mysqli_query($conn, "DELETE FROM agents WHERE id='$id'");

	die(($res == true) ? "true" : mysqli_error($conn));

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
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css"/>
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
                    <h2>
                        Agents
                    </h2>
                    <div class="row">
                        <div class="col-md-12">
                            <a class="btn btn-primary" href="./agents-add.php">Add Agent</a>
                        </div>
                    </div>

                    <table class="table table-striped" style="margin-top: 20px;">
                        <thead>
                            <tr>
                                <th><a href="./agents.php?filter=name&<?=($_GET['filter'] == "name" && $_GET['order'] == "ASC") ? "order=DESC" : "order=ASC"; ?>">Name</a></th>
                                <th>Agent Code</th>
                                <th>Phone number</th>
                                <th><a href="./agents.php?filter=type&<?=($_GET['filter'] == "type" && $_GET['order'] == "ASC") ? "order=DESC" : "order=ASC"; ?>">Type</a></th>
                                <th><a href="./agents.php?filter=active&<?=($_GET['filter'] == "active" && $_GET['order'] == "ASC") ? "order=DESC" : "order=ASC"; ?>">Active</a></th>
                                <th>Merchant</th>
                                <th>User</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        
                        <tbody>

						<?php
							$filter = $_GET['filter'];
							$order = $_GET['order'];
							$acceptFilter = $filter == "name" || $filter == "type" || $filter == "active";
							$acceptOrder = $order == "ASC" || $order == "DESC";
							if(isset($_GET['filter']) && $acceptFilter && $acceptOrder){
								$q = mysqli_query($conn,"SELECT * FROM agents ORDER BY $filter $order");
							}else{
								$q = mysqli_query($conn,"SELECT * FROM agents WHERE 1");
							}
							while($row = mysqli_fetch_assoc($q)){
								$merchant = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name FROM users WHERE id='$row[merchant_id]'"))['name'];
								$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name,mobile_number FROM users WHERE users.id = $row[user_id]"));
								$user_name = $user['name'];
								$code = mysqli_fetch_assoc(mysqli_query($conn, "SELECT code FROM agent_code WHERE agent_id = '$row[id]' ORDER BY date DESC LIMIT 1"))['code'];
								?>
								<tr data-id="<?=$row['id']; ?>">
									<td><?=$row['name']; ?></td>
									<td><?=$code; ?></td>
									<td><?=($user['mobile_number'] == null) ? "-" : $user['mobile_number']; ?></td>
									<td><?=($row['type'] == 0) ? "A" : "B"; ?></td>
									<td><?=($row['active']) ? "Yes" : "No"; ?></td>
									<td><?=$merchant; ?></td>
									<td><?=($user_name == null) ? "-" : $user_name; ?></td>
									<td>
										<button class="btn btn-primary change_status" data-id="<?=$row['id']; ?>"><i class="fas fa-edit"></i></button>
										<button class="btn btn-danger remove_agent" data-id="<?=$row['id']; ?>"><i class="fas fa-user-minus"></i></button>
									</td>
								</tr>
								<?php
							}
						?>
						
                        </tbody>
                        
                    </table>

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
	

	<div class="modal fade" id="edit_info" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Modify agent</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="name">Agent name</label>
					<input type="text" class="form-control" id="name">
				</div>
				<div class="form-group">
					<label for="code">Agent code</label>
					<input type="text" class="form-control" id="code">
				</div>
				<div class="form-group">
					<label for="agent_type">Agent type</label>
					<select class="form-control" id="agent_type">
						<option>Choose a type...</option>
						<option value="0">Type A</option>
						<option value="1" selected>Type B</option>
					</select>
				</div>
				<div class="form-group">
					<label for="agent_status">Status</label>
					<select class="form-control" id="agent_status">
						<option value="1">Active</option>
						<option value="0" selected>Not active</option>
					</select>
				</div>
				<div class="form-group">
					<label for="merchant_name">Merchant</label>
					<select class="form-control" id="merchant_name">
							<option value="null">Select a merchant...</option>
							<?php 
								$qUsers = mysqli_query($conn, "SELECT id,name FROM users WHERE user_roles = '2' ORDER BY name ASC");
								while($row = mysqli_fetch_assoc($qUsers)){
									echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
								}
							?>
							
					</select>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary save_close">Save changes</button>
			</div>
			</div>
		</div>
	</div>


	<script type="text/javascript">

	$(".save_close").on("click", function(e){
		e.preventDefault();

		var id = $(".save_close").data("id");
		var name = $("#name").val();
		console.log(name);
		var code = $("#code").val();
		var agent_type = $("#agent_type option:selected").val();
		var agent_status = $("#agent_status option:selected").val();
		var merchant_id = $("#merchant_name option:selected").val();

		$.post("./agents.php", {
			q: "updateData",
			id: id,
			name: name,
			code: code,
			agent_type: agent_type,
			agent_status: agent_status,
			merchant_id: merchant_id
		}, function(data, res){
			if(data == 'true'){
				
				window.location.href = "./agents.php";

			}else{
				alert("Unable to update.");
				console.log(data);
			}
		});


	});

	$(".remove_agent").on("click", function(e){
		e.preventDefault();

		var id = $(this).data("id");

		$.post("./agents.php", {
			q: "removeAgent",
			id: id
		}, function(data, res){
			console.log(data);
			if(data == 'true'){
				location.reload();
			}
		});

	});

	$(".change_status").on("click", function(e){
		e.preventDefault();

		var id = $(this).data("id");

		$.get("./agents.php", {
			q: "getInfoAgent",
			id: id
		}, function(data){
			data = JSON.parse(data);
			console.log(data);

			$("#name").val(data['name']);
			$("#code").val(data[0]['code']);

			$("#agent_type option, #merchant_name option, #agent_status option").prop("selected", false);

			$(".save_close").attr("data-id", id);

			$("#agent_type option[value='" + data['type'] + "']").prop("selected", true);
			$("#agent_status option[value='" + data['active'] + "']").prop("selected", true);
			$("#merchant_name option[value='" + data['merchant_id'] + "']").prop("selected", true);

			$("#edit_info").modal("show");

		});


	});

	</script>


</body>

</html>

