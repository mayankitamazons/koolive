<?php 
if(isset($_POST['type'])){
	ini_set('error_reporting', E_ALL);
    function checktimestatus($time_detail)
	{
		extract($time_detail);
		switch ($starday) {
			case "Monday":
				$s_day=1;
				break;
			case "Tuesday":
				$s_day=2;
				break;
			case "Wednesday":
				$s_day=3;
				break;
			case "Thursday":
				$s_day=4;
				break;
			case "Friday":
				$s_day=5;
				break;
			case "Saturday":
				$s_day=6;
				break;
			default:
				$s_day=7;
		}
		switch ($endday) {
			case "Monday":
				$e_day=1;
				break;
			case "Tuesday":
				$e_day=2;
				break;
			case "Wednesday":
				$e_day=3;
				break;
			case "Thursday":
				$e_day=4;
				break;
			case "Friday":
				$e_day=5;
				break;
			case "Saturday":
				$e_day=6;
				break;
			default:
				$e_day=7;
		}  
	 	$currenttime=date("H:i");
		$n=date("N");
		    if(($currenttime >$starttime && $currenttime < $endttime) && ($s_day<=$n && $e_day>=$n)){
			  $shop_close_status="y";
		  }
		  else
		  {
			  $shop_close_status="n";
		  }
		return $shop_close_status;
	  }	

include('config.php');
$view_btn = '';
$LOCSQL = 'and users.city="'.$_SESSION['locationsort'].'"';
if($_POST['type'] == 'popular'){
	$main_title = 'Popular Restaurants';
	$view_btn = '<div style="margin:5px 0 0 0;float: left;" class="col-md-2"><a href="merchant_find.php">View All</a></div>';
	$sql="select SQL_NO_CACHE set_working_hr.*,users.mobile_number,users.banner_image,users.name,users.address,users.login_status,users.id,about.image,users.shop_open,cs.shift_pos from classification_arrange_system as cs inner join users on users.id=cs.merchant_id LEFT JOIN about on  users.id=about.userid LEFT JOIN set_working_hr on users.id=set_working_hr.merchant_id where cs.classfication_id='3' and users.user_roles = 2  and users.shop_open=1 $LOCSQL group by users.id ORDER BY cs.shift_pos  ASC  limit 20";
	
	#echo $sql;
}else{
	if($_POST['type'] == 'seafood'){
		$c_id = 7; //sea food
		$main_title = 'Sea-food Shop';
	}
	if($_POST['type'] == 'milktea'){
		$c_id = 10; //milktea food
		$main_title = 'Milk Teas Shop';
	}
	if($_POST['type'] == 'steambot'){
		$c_id = 16; //steamboat
		$main_title = 'Steamboat Shop';
	}
	if($_POST['type'] == 'veg'){
		$c_id = 15; //Vegetarian food
		$main_title = 'Vegetarian Shop';
	}
	if($_POST['type'] == 'fastfood'){
		$c_id = 5; //fast food
		$main_title = 'Fast-food Shop';
	}
	
	$sql="select SQL_NO_CACHE users.banner_image,users.name, users.address,service.short_name,about.image,users.mobile_number,set_working_hr.*,
	users.order_extra_charge,users.delivery_plan,users.not_working_text,users.not_working_text_chiness 
	from classification_arrange_system as cs   inner join users on users.id=cs.merchant_id left JOIN service on users.service_id = service.id LEFT JOIN about on users.id=about.userid LEFT JOIN set_working_hr on users.id=set_working_hr.merchant_id where cs.classfication_id='$c_id' and users.user_roles = 2 and users.shop_open=1 $LOCSQL group by users.id ORDER BY cs.shift_pos ASC limit 20";
}
$result = mysqli_query($conn,$sql);                  
$totalpo=mysqli_num_rows($result);
//echo $totalpo;
$response ='';
if($totalpo>0){ 
$response .= '<h2 style="text-align: center;color: black;">'.$main_title.'</h2><br/><div class="main_title" style="margin:0px;"><span><em></em></span><div class="row"><div class="col-md-8"></div>'.$view_btn.'</div></div><div class="owl-carousel owl-theme carousel_4">'; 
	if(mysqli_num_rows($result)>0){
        while($rd=mysqli_fetch_assoc($result)){
			$working="y";
			if($rd['start_day']){
				 $time_detail['starday']=$rd['start_day'];
				 $time_detail['endday']=$rd['end_day'];
				 $time_detail['starttime']=$rd['start_time'];
				 $time_detail['endttime']=$rd['end_time'];
				 $working=checktimestatus($time_detail);
			}   
			if($working=="y")
			{
				$response .= '<div class="item"><div class="strip showLoader6"><figure>';
                if($rd['banner_image']){  
					$response .='<img ref="banner_image" data-src="'.$image_cdn.'banner_image/'.$rd['banner_image'].'?w=400" class="owl-lazy lazy2 Sirv" alt="">';
				} else {
				if($rd['image']==""){ 
					$response .='<img src="images/logo_new.jpg" data-src="images/logo_new.jpg" class="owl-lazy" alt="">'; 
				}else{
					$response .='<img  data-src="'.$image_cdn.'about_images/'.$rd['image'].'?w=200" class="owl-lazy lazy2 Sirv" alt="">'; 
				} 
				}
				$response .='<a href="view_merchant.php?vs='.md5(rand()).'&sid='.$rd['mobile_number'].'" class="strip_info"><div class="item_title index_hotel"><h3>'.$rd['name'].'</h3><small>';
				if($work_str==""){
					$response .="<br>";
				}else{ 
					$response .=$work_str;
				}
					$response .='</small>';
				if($rd[12] || $rd[13]){ if($rd[13]){ $d_str="Flexible Delivery";} else { $d_str="MYR ".number_format($rd[12],2);} } else { $d_str="Free Delivery";}
			        $response .='</div></a></figure></div></div>';
			}
        }
            }
			$response .='</div>';
			}
			
			echo $response ;
	}
	
?>	