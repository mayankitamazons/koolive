<?php
include('config.php');
$id=$_POST['showid'];

$details=mysqli_query($conn,"SELECT * FROM users WHERE id='$id'");
$row=mysqli_fetch_assoc($details);
?>
 <h3 style="text-align:center;">Bank Details</h3>
<table class="table">
<tr> <th>Bank NAme</th> <td><?php echo $row['bank_name'];?></td></tr>
<tr><th>Branch NAme</th><td><?php echo $row['bank_branch'];?></td></tr>
<tr><th>IFSC Code</th><td><?php echo $row['bank_ifsc'];?></td></tr>
<tr><th>Bank Account No.</th><td><?php echo $row['bank_ac_num'];?></td></tr>
<tr><th>Documents</th><td><a href="<?php echo "../documents/".$row['doc_copy'];?>" target="_blank"><?php echo $row['doc_copy'];?></a></td></tr>
</table>