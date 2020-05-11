<?php 
include("config.php");
if(!isset($_SESSION['login']))
{
	header("location:login.php");
}
$site_url="https://www.koofamilies.com/";

// print_R($bank_data);
// die;
$plan_id = $_GET['plan_id'];
$exists = 0;
$gotString = str_replace(' ', '', $_GET['string']);
$gotString = strtolower($gotString);
$query = "SELECT coupon_code FROM coupon WHERE id != $plan_id";
$names = mysqli_query($conn, $query);
while ($row = $names->fetch_assoc()) {
	$name  = str_replace(' ', '', $row['coupon_code']);
	$name = strtolower($name);
	if ($name == $gotString) {
		$exists = 1;
	}
}
?>
<?php if (!$exists): ?>
	<span style="color: green">
		<i class="fa fa-check mr-2" aria-hidden="true"></i> Coupon Code is okay!
	</span>
	<script>
$('document').ready(function() {
	if ($('#formSubmit').hasClass("disabled") == 1) {
   		$('#formSubmit').removeClass("disabled");
	}
});
</script>
<?php endif ?>
<?php if ($exists): ?>
	<span style="color: red">
		<i class="fa fa-times mr-2" aria-hidden="true"></i> Coupon Code Already Exists!
	</span>
		<script>
$('document').ready(function() {
	if ($('#formSubmit').hasClass("disabled") == 0) {
   		$('#formSubmit').addClass("disabled");
	}
});
</script>
<?php endif ?>