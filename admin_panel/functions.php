<?php 
    include("config.php");

    if(isset($_POST['method']) && ($_POST['method'] == "changeDiscountStatus")){
        $status = $_POST['status'];
        $id = $_POST['id'];
        /*if($_POST['favorite'] == 1){
            mysqli_query($conn, "INSERT INTO favorities VALUES('', ".$_POST['user_id'].", ".$_POST['merchant_id'].")");
        } else {
            mysqli_query($conn, "DELETE FROM favorities WHERE user_id = ".$_POST['user_id']." AND favorite_id = ".$_POST['merchant_id']."");
        }*/
        if($status == 1) $status = 0;
        else $status = 1;
        mysqli_query($conn, "UPDATE k1k2_history SET mark='$status' WHERE id='$id'");
        echo "success";
    }
    
    
?>