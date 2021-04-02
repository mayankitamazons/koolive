<?php
include("config.php");
if( isset($_POST['coupon'])) {
    $coupon = $_POST['coupon'];
    // $coupon = $_POST['coupon'];
    $userID = $_POST['user'];
    $amount = $_POST['amount'];
    $merchant_id = $_POST['merchant_id'];
    $user_mobile = $_POST['user_mobile'];
	$mobile_check="60".$user_mobile;
    $coupon_detail = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `coupon` WHERE `coupon_code` = '$coupon' and status = 1"));
    if(empty($coupon_detail)){
        $res=array('status'=>false,'data'=>'Not Valid Coupon');
    }else{
		// first check is coupon is merchant speicific or not 
		if($coupon_detail['coupon_type']=="merchant_specific" && $coupon_detail['user_id']!=$merchant_id)
		{
		  $res=array('status'=>false,'data'=>'Not Valid Coupon 2');	
		}
		else
		{	
			if($coupon_detail['remain_user'] > 0){
				$coupon_id=$coupon_detail['id'];
				$user_detail = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `order_list` WHERE `user_mobile` = '$mobile_check' and `coupon_id` = '$coupon_id'"));
				$total_user_array = mysqli_fetch_assoc(mysqli_query($conn, "SELECT count(id) as total_record FROM `order_list` WHERE `user_mobile` = '$mobile_check' and `coupon_id` = '$coupon_id'"));
				
				 $total_use=$total_user_array['total_record'];
				
				 $per_user_count=$coupon_detail['per_user_count'];
			if(($per_user_count>$total_use) || empty($user_detail)){
					$coupon_query = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM coupon WHERE id='$coupon_id' and status = 1 and $amount BETWEEN total_min_price AND total_max_price;"));
					$total_min_price=$coupon_detail['total_min_price'];
					$total_max_price=$coupon_detail['total_max_price'];
					if($total_min_price==0 && $total_max_price>0)
					$text="Coupon is Valid up to RM ".$total_max_price;   
					if($total_min_price>0 && $total_max_price>0)
					$text="Coupon is Valid Only Order Between RM ".$total_min_price." to RM ".$total_max_price;
					if(!empty($coupon_query)){
						$date_from = date('d m Y', strtotime($coupon_query['valid_from']));
						$date_to = date('d m Y', strtotime($coupon_query['valid_to']));
						$current_utc=strtotime(date('Y-m-d H:i:s',time()));
						$date_from_utc=strtotime($coupon_query['valid_from']);
						$date_to_utc=strtotime($coupon_query['valid_to']);
						if($coupon_query['valid_to'] == '0000-00-00 00:00:00' && $coupon_query['valid_from'] == '0000-00-00 00:00:00'){
							$coupon_discount = $coupon_query['discount'];
							$coupon_rate=$coupon_query['discount'];
							$coupon_type = $coupon_query['type'];
							if($coupon_type == 'per'){
								$coupon_discount = ($coupon_discount / 100) * $amount;
							
							   
							}
								$coupon_discount=number_format($coupon_discount,2);
							$msg='Coupon Applied ,'.$text;
							$res=array('status'=>true,'coupon_rate'=>$coupon_rate,'data'=>$msg,'id'=>$coupon_query['id'],'price' => $coupon_discount, 'type' => $coupon_type, 'min' => $coupon_query['total_min_price'],'max' => $coupon_query['total_max_price']);
						}else if($date_from_utc <= $current_utc && $date_to_utc >=$date_from_utc){   
							$coupon_discount = $coupon_query['discount'];
							$coupon_type = $coupon_query['type'];
							if($coupon_type == 'per'){
							   $coupon_discount = ($coupon_discount / 100) * $amount;
							}
							$msg='Coupon Applied,'.$text;       
							$coupon_discount=number_format($coupon_discount,2);
							$res=array('status'=>true,'data'=>$msg,'id'=>$coupon_query['id'],'coupon_rate'=>$coupon_rate,'price' => $coupon_discount, 'type' => $coupon_type, 'min' => $coupon_query['total_min_price'],'max' => $coupon_query['total_max_price']);
						}else{  
							$res=array('status'=>false,'data'=>'Coupon Expire');
						}
						
					}else{    
						
						if($text=='')
							$text="Price not in Range";
						$res=array('status'=>false,'data'=>$text);
					}
					
				}else{
					$res=array('status'=>false,'data'=>'Already in use');
				}
			}else{
				$res=array('status'=>false,'data'=>'Coupon is out of Stock,Try Different Coupon');
			}
		}
    }
    
}
echo json_encode($res);
die;
?>  