<?php

session_start();
include("config.php");
if($_SESSION['login']=='')
{
    header('Location: '. $site_url .'/login.php');
    die;
}

$statementid = $_POST['statementid'];
 $Merchantid = $_SESSION['login'];
 $paid = $_POST['paid'];
 $change = $_POST['change'];
 $chan = number_format($change,2);
 $tol_qty = $_POST['tol_qty'];
 $tol_mnt = $_POST['tol_mnt'];

$product_name = $_POST['product_name'];
$product_code = $_POST['product_code'];
$remarks = $_POST['remark'];
$user = $_POST['user'];
$tablety = $_POST['tablety'];
$invo = $_POST['invo'];
$orderid = $_POST['orderid'];
 $count = count($orderid);
$section = $_POST['section'];
$qtyno = $_POST['qtyno'];
$total = $_POST['total']; 


$qry = "insert into statement_transection (statement_id,merchant_id,staff_id,tol_qty,subtotal,paid,balance) values('$statementid','$Merchantid','0','$tol_qty','$tol_mnt','$paid','$chan')";
$rel = mysqli_query($conn,$qry);
$last_id = mysqli_insert_id($conn);

for($i=0;$i<$count ;$i++){
   
    $_user  = $user[$i];
    $_tablety  = $tablety[$i];
    $_invo  = $invo[$i];
    $_qtyno  = $qtyno[$i];
    $_total  = $total[$i];
    $_section  = $section[$i];
   
 
    $query = "INSERT INTO statement_data(data_id,username,table_num,invoice_num,qty_num,amount,section_type) values ('$last_id','$_user','$_tablety','$_invo','$_qtyno','$_total','$_section')";
    $result = mysqli_query($conn, $query); 
}

if($result)
    {
    	$orderid = $_POST['orderid'];
    	//print_r($orderid);
    	 // $or_cont = implode(",",$orderid);

    	 $co = count($orderid);

	    	for($i=0;$i<$co;$i++){    
				   $sql = "update order_list set status ='1' where id = '".$orderid[$i]."'";
				 $rel = mysqli_query($conn, $sql);
				}

        	echo"Data inserted successfully !";
    }
    else
    {
    	echo "not done";
    }




?>