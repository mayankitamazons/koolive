<?php
include("config.php");
// error_reporting(E_ALL);
require __DIR__ . '/vendor/autoload.php';
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;


if(isset($_POST['update_ingredients'])){
    $words = $_POST['update_ingredients'];
    $id = $_SESSION['login'];
    if(!mysqli_query($conn,"UPDATE users SET preset_words='$words' WHERE id='$id'")){
        die(false);
    }
    die(true);
}

if(isset($_POST['method']) && ($_POST['method'] == "favorite")){
    if($_POST['favorite'] == 1){
        mysqli_query($conn, "INSERT INTO favorities VALUES('', ".$_POST['user_id'].", ".$_POST['merchant_id'].")");
    } else {
        mysqli_query($conn, "DELETE FROM favorities WHERE user_id = ".$_POST['user_id']." AND favorite_id = ".$_POST['merchant_id']);
    }
}
if(isset($_POST['method']) && ($_POST['method'] == "getNoneImageProduct")){
    if($_POST['type'])
	{
		// $catparent=$_POST['mainclick'];
		
		$resultcat = mysqli_query($conn, "SELECT * FROM category WHERE user_id ='".$_POST['id']."' and catparent = '".$_POST['category']."' order by id limit 0,1");
		$resultdata=mysqli_fetch_array($resultcat,MYSQLI_ASSOC);
		$category_name=$resultdata['category_name'];
		$category_name = mysqli_real_escape_string($conn, $category_name);
		 /* $category_name = str_replace(' ', '-', $category_name); */
		$result = mysqli_query($conn, "SELECT * FROM products WHERE user_id ='".$_POST['id']."' and category = '".$category_name."' and image='' and status=0");
		$resultdata=mysqli_fetch_array($resultcat,MYSQLI_ASSOC);
		
	}
	else
	{
		// echo "SELECT * FROM products WHERE user_id ='".$_POST['id']."' and category = '".$_POST['category']."' and image!='' and status=0";
		// die;
	   $result = mysqli_query($conn, "SELECT * FROM products WHERE user_id ='".$_POST['id']."' and category = '".$_POST['category']."' and image='' and status=0");
	
	}
	$array_product = array();
    while ($row=mysqli_fetch_row($result)){
		
        $item = array("image"=>$row[6],"product_name"=> $row[2], "category"=> $row[3], 'type'=> $row[4], 'price'=> $row[5], 'remark'=>$row[8], 'id' => $row[0]);
        array_push($array_product, $item);
    }

    echo json_encode($array_product);
}
if(isset($_POST['method']) && ($_POST['method'] == "getImageProduct")){
	
	if($_POST['type'])
	{
		// $catparent=$_POST['mainclick'];
		
		$resultcat = mysqli_query($conn, "SELECT * FROM category WHERE user_id ='".$_POST['id']."' and catparent = '".$_POST['category']."' and status='0' order by id limit 0,1");
		$resultdata=mysqli_fetch_array($resultcat,MYSQLI_ASSOC);
		   $category_name=$resultdata['category_name'];
		// die;
		// $category_name="Diam-Sum-点心";
		 $category_name = mysqli_real_escape_string($conn, $category_name);
		 // $category_name = str_replace(' ', '-', $category_name);
	
		$result = mysqli_query($conn, "SELECT * FROM products WHERE user_id ='".$_POST['id']."' and category = '".$category_name."' and image!='' and status=0");
		$resultdata=mysqli_fetch_array($resultcat,MYSQLI_ASSOC);
		
	}
	else
	{
		// echo "SELECT * FROM products WHERE user_id ='".$_POST['id']."' and category = '".$_POST['category']."' and image!='' and status=0";
		// die;
	   $result = mysqli_query($conn, "SELECT * FROM products WHERE user_id ='".$_POST['id']."' and category = '".$_POST['category']."' and image!='' and status=0");
	
	}
    
    $array_product = array();
    while ($row=mysqli_fetch_row($result)){
		
        $item = array("image"=>$row[6],"product_name"=> $row[2], "category"=> $row[3], 'type'=> $row[4], 'price'=> $row[5], 'remark'=>$row[8], 'id' => $row[0]);
        array_push($array_product, $item);
    }

    echo json_encode($array_product);
}
if(isset($_POST['method']) && ($_POST['method'] == "getFavoriteByBusiness")){
    $id = $_POST['user_id'];
    $type = $_POST['type'];
    $result = mysqli_query($conn, "SELECT users.name, user_id, favorite_id, users.latitude, users.longitude, users.account_type
                             FROM favorities INNER JOIN users ON favorities.favorite_id = users.id
                             WHERE user_id='$id' AND (users.business1 = '$type' OR users.business2 = '$type')");

    $array_favorite = array();
    while ($row=mysqli_fetch_row($result)){

        $sql_transaction = "SELECT COUNT(id) ordered_num
							FROM order_list
							WHERE user_id = '".$id."' AND merchant_id = '". $row[2]."' AND STATUS='1'";
        $result_transaction = mysqli_fetch_assoc(mysqli_query($conn,$sql_transaction));
        $sql_favorite = "SELECT COUNT(id) favorite_num
    						FROM favorities
    						WHERE favorite_id = '".$row[2]."'";
        $result_favorite = mysqli_fetch_assoc(mysqli_query($conn,$sql_favorite));
        $account_type = $row['5'];
        if($row['5'] != "") $account_type = $row['5'].", ";

        $item = array("name"=> $row[0], "id"=> $row[1], 'favorite_id'=> $row[2], 'latitude'=>$row[3], 'longitude'=>$row[4], 'account_type'=>$account_type, 'order_num'=>$result_transaction['ordered_num'], 'favorite_num'=>$result_favorite['favorite_num']);
        array_push($array_favorite, $item);
    }

    echo json_encode($array_favorite);
}

if(isset($_POST['method']) && ($_POST['method'] == "getNearbyRestaurants")){
    $id = $_POST['user_id'];
    $type = $_POST['type'];
    $result = mysqli_query($conn, "SELECT users.name, users.id user_id, users.latitude, users.longitude, users.account_type
                             FROM users 
                             WHERE user_roles='2' AND (users.business1 = '$type' OR users.business2 = '$type')");

    $array_nearby = array();
    while ($row=mysqli_fetch_assoc($result)){
        $sql_transaction = "SELECT COUNT(id) ordered_num
							FROM order_list
							WHERE user_id = '".$id."' AND merchant_id = '". $row['user_id']."' AND STATUS='1'";
        $result_transaction = mysqli_fetch_assoc(mysqli_query($conn,$sql_transaction));
        $sql_favorite = "SELECT COUNT(id) favorite_num
    						FROM favorities
    						WHERE favorite_id = '".$row['user_id']."'";
        $result_favorite = mysqli_fetch_assoc(mysqli_query($conn,$sql_favorite));

        if($id == "") $transaction_num = 0;
        else $transaction_num = $result_transaction['ordered_num'];

        $account_type = $row['account_type'];
        if($row['account_type'] != "") $account_type = $row['account_type'].", ";

        $item = array("name"=> $row['name'], "id"=> $row['user_id'], 'account_type'=>$account_type, 'latitude'=>$row['latitude'], 'longitude'=>$row['longitude'], 'order_num'=>$transaction_num, 'favorite_num'=>$result_favorite['favorite_num']);
        array_push($array_nearby, $item);
    }

    echo json_encode($array_nearby);
}

if(isset($_POST['method']) && ($_POST['method'] == "k_type")){
    $id = $_POST['id'];
    $comment = $_POST['complain'];
    $image = $_POST['image'];
    $role = $_POST['role'];
    if($role == '1'){
        mysqli_query($conn, "UPDATE k1k2_history SET user_comment='$comment', user_complain='$image' where id='$id'");
    } else if($role == '2'){
        mysqli_query($conn, "UPDATE k1k2_history SET merchant_comment='$comment', merchant_complain='$image' where id='$id'");
    }
}

if(isset($_POST['method']) && ($_POST['method'] == "getUnreadMsg")){
    $id = $_POST['id'];

    $data = mysqli_query($conn, "SELECT sender, COUNT(id) num FROM chat_history WHERE STATUS = 'unread' and receiver='$id' GROUP BY sender");
    $array_unread = array();
    while ($row=mysqli_fetch_assoc($data)){
        $item = array("num"=> $row['num'], "sender"=>$row['sender']);
        array_push($array_unread, $item);
    }
    echo json_encode($array_unread);
}


if(isset($_POST['method']) && ($_POST['method'] == "getOrderDetail")){
    $id = $_POST['id'];

    $data = mysqli_query($conn, "SELECT * FROM order_list WHERE id='$id'");
    $array_detail = array();
    while ($row=mysqli_fetch_assoc($data)){
        $user_id = $row['user_id'];
        $merchant_id = $row['merchant_id'];
        $product_ids = explode(",",$row['product_id']);
        $product_qty = explode(",", $row['quantity']);
        $product_amt = explode(",", $row['amount']);
        $product_code = explode(",", $row['product_code']);
        $remark_ids = explode("|",$row['remark']);
        $location = isset($row['location']) ? $row['location'] : '';
        $section_type = isset($row['section_type']) ? $row['section_type'] : '';
        $table_type = isset($row['table_type']) ? $row['table_type'] : '';
        $user_id = isset($row['user_id']) ? $row['user_id'] : '';
        $array_product_names;
        for($i = 0;  $i < count($product_ids); $i++){
            if(is_numeric($product_ids[$i])){
                $product_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT product_name FROM products WHERE id ='".$product_ids[$i]."'"))['product_name'];
            } else {
                $product_name = $product_ids[$i];
            }
            $array_product_names[$i] = $product_name;
        }
        $order_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name FROM users where id='$user_id'"))['name'];
        $ref_result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name, gst, sst, register FROM users where id='$merchant_id'"));
        $register = $ref_result['register'];
        $merchant_name = $ref_result['name'];
        $sst = $ref_result['sst'];
        $gst = $ref_result['gst'];  
        $item = array('product_ids'=>$product_ids,'register' => $register, 'sst' => $sst, 'gst' => $gst, 'user_id' => $user_id, 'product_code' => $product_code, 'table_type' => $table_type,'section_type'=>$section_type,'location' => $location, 'remark' => $remark_ids, 'invoice_no' => $row['invoice_no'] , 'status' => $row['status'] , 'id' => $row['id'] , 'username' =>$order_name, 'merchantname' => $merchant_name, 'product_name' => $array_product_names, 'product_qty' => $product_qty, 'product_amt' => $product_amt);
        array_push($array_detail, $item);     
    }  
    echo json_encode($array_detail);
}

if(isset($_POST['method']) && ($_POST['method'] == "getOrderDetailByIdAndInvoice")){
    $invoice_id = $_POST['invoice_no'];
    $id = $_POST['id'];
    $data = mysqli_query($conn, "SELECT * FROM order_list WHERE invoice_no='$invoice_id' AND id='$id'");
    $array_detail = array();
    while ($row=mysqli_fetch_assoc($data)){
        $user_id = $row['user_id'];
        $merchant_id = $row['merchant_id'];
        $product_ids = explode(",",$row['product_id']);
        $product_qty = explode(",", $row['quantity']);
        $product_amt = explode(",", $row['amount']);
        $remark_ids = explode("|",$row['remark']);
        $product_code = explode(",", $row['product_code']);
        $location = isset($row['location']) ? $row['location'] : '';
        $section_type = isset($row['section_type']) ? $row['section_type'] : '';
        $table_type = isset($row['table_type']) ? $row['table_type'] : '';
        $user_id = isset($row['user_id']) ? $row['user_id'] : '';
        for($i = 0;  $i < count($product_ids); $i++){
            if(is_numeric($product_ids[$i])){
                $product_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT product_name FROM products WHERE id ='".$product_ids[$i]."'"))['product_name'];
            } else {
                $product_name = $product_ids[$i];
            } 
            $array_product_names[$i] = $product_name;
        }
        $order_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name FROM users where id='$user_id'"))['name'];
        $ref_result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name, gst, sst, register FROM users where id='$merchant_id'"));
        $merchant_name = $ref_result['name'];
        $sst = $ref_result['sst'];
        $gst = $ref_result['gst'];
        $register = $ref_result['register'];
        $item = array('register' => $register, 'sst' => $sst, 'gst' => $gst, 'user_id' => $user_id, 'product_code' => $product_code, 'table_type' => $table_type,'section_type'=>$section_type, 'location' => $location, 'remark' => $remark_ids, 'invoice_no' => $row['invoice_no'] , 'status' => $row['status'] , 'id' => $row['id'] , 'username' =>$order_name, 'merchantname' => $merchant_name, 'product_name' => $array_product_names, 'product_qty' => $product_qty, 'product_amt' => $product_amt);
        array_push($array_detail, $item);
    }
    echo json_encode($array_detail);
}

if( isset( $_POST['method']) && ( $_POST['method'] == "getUnPrintedOrder" )  ) {
    $id = $_POST['id'];

    $ref_result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name, id, order_print_setting, sst, gst, register FROM users WHERE id='".$id."'"));
    if( $ref_result['order_print_setting'] === 'on' ) {
        $sst = $ref_result['sst'];
        $gst = $ref_result['gst'];
        $register = $ref_result['register'];
        $merchant_name = $ref_result['name'];
        $data = mysqli_query($conn, "SELECT * FROM order_list WHERE status=0 AND merchant_id='$id' AND printed!='1' ORDER BY ID DESC LIMIT 10");
        $array_detail = array();
        while ( $row=mysqli_fetch_assoc($data) ) {
            $user_id = $row['user_id'];
            $merchant_id = $row['merchant_id'];
            $wallet = isset($row['wallet']) ? $row['wallet'] : '';
            $product_ids = explode(",",$row['product_id']);
            $product_qty = explode(",", $row['quantity']);
            $product_amt = explode(",", $row['amount']);
            $product_code = explode(",", $row['product_code']);
            $remark_ids = explode("|",$row['remark']);
            $location = isset($row['location']) ? $row['location'] : '';
            $section_type = isset($row['section_type']) ? $row['section_type'] : '';
            $table_type = isset($row['table_type']) ? $row['table_type'] : '';
            $user_id = isset($row['user_id']) ? $row['user_id'] : '';
            $array_product_names = [];
            for($i = 0;  $i < count($product_ids); $i++){
                if(is_numeric($product_ids[$i])){
                    $product_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT product_name FROM products WHERE id ='".$product_ids[$i]."'"))['product_name'];
                } else {
                    $product_name = $product_ids[$i];
                }
                $array_product_names[$i] = $product_name;
            }
            $order_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name FROM users where id='$user_id'"))['name'];
            $item = array('product_ids'=>$product_ids,'register' => $register, 'sst' => $sst, 'gst' => $gst, 'user_id' => $user_id, 'product_code' => $product_code, 'table_type' => $table_type,'section_type'=>$section_type,'location' => $location, "wallet" => $wallet, 'remark' => $remark_ids, 'invoice_no' => $row['invoice_no'] , 'status' => $row['status'] , 'id' => $row['id'] , 'username' =>$order_name, 'merchantname' => $merchant_name, 'product_name' => $array_product_names, 'product_qty' => $product_qty, 'product_amt' => $product_amt);
            array_push($array_detail, $item);
        }
        echo json_encode($array_detail);
    } else {
        $array_detail = array();
        echo json_encode($array_detail);
    }
}

if( isset( $_POST['method']) && ( $_POST['method'] == "pintOrder" ) ) {
	$print='n';
	
	$order = $_POST['order'];
	
	$product_ids=$order['product_ids'];
	$mer_id=670;
    $date = $_POST['date'];
    $time = $_POST['time'];
    $ref_result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id, print_ip_address,printer_style FROM users WHERE id='".$_SESSION['login']."'"));
    // $ref_result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id, print_ip_address,printer_style FROM users WHERE id='".$mer_id."'"));
    // print_R($ref_result);
	// die;
	 $printer_style=$ref_result['printer_style'];
	
    if($printer_style=="product")
	{
		
		$prdouct_str=implode(",",$product_ids);
		
		$product_result = mysqli_query($conn, "SELECT id, print_ip_address FROM products WHERE id in($prdouct_str)");
	  // $prow=mysqli_fetch_array($product_result);
	  // print_R($prow);
	  // die;
		while ($prow=mysqli_fetch_array($product_result)){
			// print_R($prow);
			  $ip_address = $prow['print_ip_address'];
			    
			 if($ip_address)
			 {
				 OrderCustomprint($ip_address,$order,$date,$time,$conn);
			 }
			 else
			 {
				// do normal print 
				$ip_address = $ref_result['print_ip_address'];
				OrderCustomprint($ip_address,$order,$date,$time,$conn);
				 
			 }
		}
        
	}
	else if($printer_style=="section")
	{
		// section based printing 
		$section_type=$order['section_type'];
		$section_result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id, printer_ip FROM sections WHERE id='$section_type'"));
		 $ip_address = $section_result['printer_ip'];
		
		OrderCustomprint($ip_address,$order,$date,$time,$conn);
	}
	else
	{
		// if( ! isset( $ref_result['print_ip_address'] ) ) {
			// echo('print_setting_error');
			// die();
		// }
		$ip_address = $ref_result['print_ip_address'];
		OrderCustomprint($ip_address,$order,$date,$time,$conn);
	}
    // echo $ip_address;
	// die;
    //OrderCustomprint($ip_address,$order,$date,$time,$conn);

	
}
function OrderCustomprint($ip_address,$order,$date,$time,$conn)
	{    
	   
	   	if( $order == null ) {
			echo( "empty" );
			} else {
			try {
				//$connector = new WindowsPrintConnector("smb://DESKTOP-19L9BOQ/POS-80C");
				$connector = new NetworkPrintConnector($ip_address, 9100);          
			} catch( Exception $e ) {
				//echo('print_setting_error');
				echo(print_r($e, true));
				// die();
			}
			echo(print_r($connector, true));
			$ref_result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT mobile_number FROM users WHERE id='".$order['user_id']."'"));
			if( ! isset( $ref_result['mobile_number'] ) ) {
				echo('User is not exist.');
				// die();
			}
			
			// 3 + 16 + 8 + 5 + 7 + 7
			$mobile = $ref_result['mobile_number'];
			$section_id=$order['section_type'];
			$section_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name FROM sections WHERE id='".$order['section_type']."'"));
			$section_name=$section_data['name'];
			$printer = new Printer($connector);
			try {
				$printer -> text("\n");
				$printer -> setJustification(Printer::JUSTIFY_CENTER);
				$printer -> text("\n");
				$printer -> setEmphasis(true);
				$printer -> selectPrintMode(Printer::MODE_FONT_B | Printer::MODE_DOUBLE_HEIGHT | Printer::MODE_DOUBLE_WIDTH);
				$printer -> text( $order['merchantname'] . "\n" );
				// $printer -> text("\n");
				$printer -> selectPrintMode(Printer::MODE_FONT_A);
				$printer -> setEmphasis(false);
				$printer -> text( '( ' . $order['register'] . ' )' . "\n" );
				// $printer -> text("\n");
				$printer -> setEmphasis(true);
				$printer -> selectPrintMode(Printer::MODE_FONT_B | Printer::MODE_DOUBLE_HEIGHT | Printer::MODE_DOUBLE_WIDTH);
				$printer -> text( "Customer : " . $order['username'] . "\n" );
				// $printer -> text("\n");
				// $printer -> selectPrintMode(Printer::MODE_FONT_B | Printer::MODE_DOUBLE_HEIGHT | Printer::MODE_DOUBLE_WIDTH);
				 // $printer -> selectPrintMode(Printer::MODE_FONT_A);
				$printer -> setEmphasis(false);
				$printer -> text( 'Phone : ' . $mobile . "\n" );
				// $printer -> text("\n");
				$printer -> selectPrintMode(Printer::MODE_FONT_A);
				$printer -> setEmphasis(false);
				// $printer -> text("\n");
				$location = $order['location'];
				$words = explode(" ", $location);
				$rows_locations = [];
				$rows_location = '';
				for( $i = 0 ; $i < sizeof( $words ) ; $i ++ ) {
					$word = $words[$i];
					$word .= ' ';
					if( strlen( $rows_location ) + strlen( $word ) < 30 ) {
						$rows_location .= $word;
						if( $i == sizeof( $words ) - 1 ) {
							array_push( $rows_locations, $rows_location);
						}
					} else {
						array_push( $rows_locations, $rows_location);
						$rows_location = $word;
						if( $i == sizeof( $words ) - 1 ) {
							array_push( $rows_locations, $rows_location);
						}
					}
				}
				foreach( $rows_locations as $item ) {
					$printer -> text( ' ' . $item . "\n" );
					// $printer -> text("\n");
				}  
				$printer -> text( 'GST ID : ' . $order['gst'] . "\n" );
				// $printer -> text("\n");
				$printer -> text( 'SST NO : ' . $order['sst'] . "\n" );
				// $printer -> text("\n");
				// $printer -> text("\n");
				$printer -> barcode( $order['id'] . '-' . $order['invoice_no'], Printer::BARCODE_CODE39 );
				// $printer -> text("\n");
				$printer -> selectPrintMode(Printer::MODE_FONT_B | Printer::MODE_DOUBLE_HEIGHT | Printer::MODE_DOUBLE_WIDTH);
				$printer -> text(  $order['id'] . '-' . $order['invoice_no'] . "\n" );
				$printer -> selectPrintMode(Printer::MODE_FONT_A);
				// $printer -> text("\n");
				$printer -> setJustification(Printer::JUSTIFY_LEFT);
				$printer -> text("\n");
				$printer -> setEmphasis(true);
				$printer -> selectPrintMode(Printer::MODE_FONT_B | Printer::MODE_DOUBLE_HEIGHT | Printer::MODE_DOUBLE_WIDTH);
				$printer -> text("  Table : " . $order['table_type'] . "  "."Section : " .$section_name. "\n");
				 // $printer -> selectPrintMode(Printer::MODE_FONT_B | Printer::MODE_DOUBLE_HEIGHT | Printer::MODE_DOUBLE_WIDTH);
				// $printer -> text("  Section : " . $order['section_type'] . "\n");   
				$printer -> selectPrintMode(Printer::MODE_FONT_A);
				$printer -> text("\n"); 
				$printer -> setEmphasis(false);
				$printer -> text( '  ' . $date . ' ' . $time . "\n");
				$printer -> text("\n");
				$printer -> selectPrintMode(Printer::MODE_FONT_A);
				$printer -> text( "  No  Name( Code )    Qty  Remark  Price  Amount " . "\n");
				$printer -> text("\n");
				$total = 0;
				$qty_total = 0;
				for( $i = 0 ; $i < sizeof( $order['product_name'] ) ; $i ++ ) {
					if( $order['product_qty'][$i] && $order['product_amt'][$i] ) {
						$amount = $order['product_qty'][$i] * $order['product_amt'][$i];
					} else {
						$amount = 0;
					}
					$qty_total += $order['product_qty'][$i];
					$total += $amount;
					$total=number_format($total,2);
					$remark = isset($order['remark'][$i]) ? $order['remark'][$i] : '';
					$product_code = isset($order['product_code'][$i]) ? $order['product_code'][$i] : '';
					$product_code  = '(' . $product_code . ')';
					$name = $order['product_name'][$i];
					$qty = $order['product_qty'][$i];
					$price = $order['product_amt'][$i];
					$name .= $product_code;
					$number = $i + 1;
					$size_number = 3;
					$size_name = 15;
					$size_remark = 7;
					$size_qty = 4;
					$size_price = 6;
					$size_amount = 6;
					$lines = max(intval( strlen($number) / $size_number) , intval( strlen($name) / $size_name) , intval( strlen($remark) / $size_remark) , intval( strlen($qty) / $size_qty) , intval( strlen($price) / $size_price) , intval( strlen($amount) / $size_amount) );
					$lines ++;
					for( $j = 0 ; $j < $lines ; $j++) {
						$number_print = '';
						if( strlen($number) > ($j) * $size_number ) {
							$number_print = substr($number, $j * $size_number, min($size_number, strlen($number) - ($j) * $size_number));
						}
						$number_print = str_pad($number_print,  $size_number, "   ");
						$name_print = '';
						if( strlen($name) > ($j) * $size_name ) {
							$name_print = substr($name, $j * $size_name, min($size_name, strlen($name) - ($j) * $size_name));
						}
						$name_print = str_pad($name_print,  $size_name, "   ");
						$remark_print = '';
						if( strlen($remark) > ($j) * $size_remark ) {
							$remark_print = substr($remark, $j * $size_remark, min($size_remark, strlen($remark) - ($j) * $size_remark));
						}
						$remark_print = str_pad($remark_print,  $size_remark, "   ");
						$qty_print = '';
						if( strlen($qty) > ($j) * $size_qty ) {
							$qty_print = substr($qty, $j * $size_qty, min($size_qty, strlen($qty) - ($j) * $size_qty));
						}
						$qty_print = str_pad($qty_print,  $size_qty, "   ");
						$price_print = '';
						if( strlen($price) > ($j) * $size_price ) {
							$price_print = substr($price, $j * $size_price, min($size_price, strlen($price) - ($j) * $size_price));
						}
						$price_print = str_pad($price_print,  $size_price, "   ");
						$amount_print = '';
						if( strlen($amount) > ($j) * $size_amount ) {
							$amount_print = substr($amount, $j * $size_amount, min($size_amount, strlen($amount) - ($j) * $size_amount));
						}
						$amount_print = str_pad($amount_print,  $size_amount, "   ");
						$printer -> text( '  ' . $number_print . ' ' .  $name_print . ' ' . $qty_print. ' ' . $remark_print . ' ' . $price_print . ' ' . $amount_print . "\n");
					}
					$printer -> text("\n");
				}
				$printer->setJustification(Printer::JUSTIFY_RIGHT);
				$printer -> selectPrintMode(Printer::MODE_FONT_B | Printer::MODE_DOUBLE_HEIGHT | Printer::MODE_DOUBLE_WIDTH);
				$printer -> text( "------------ -------------" . "\n");
				$printer -> text( "Qty: " . $qty_total . "      " . "Total: RM $total" . "   " . "\n");
				$printer -> text( "============ =============" . "\n");
				$printer -> text("\n");
				$printer -> text("\n");
				$printer -> cut( Printer::CUT_FULL, 3 );
				$printer -> close();
				echo('success');
			} finally {
				$printer -> close();
			}
		}	
	}
		if(isset($_POST['method']) && ($_POST['method'] == "subproduct")){
	$product_id = $_POST['product_id'];
	// $product_id =433;
	$result = array();
	$total_rows = mysqli_query($conn, "SELECT * FROM sub_products WHERE product_id='".$product_id."'");
	 $ref_result=mysqli_affected_rows($conn);

	if($ref_result)
	{
		$res['status']=200;
		while ($row=mysqli_fetch_assoc($total_rows)){
			$item = array("id" => $row['id'], "name" => $row['name'], 
        				'product_price' => $row['product_price']);
        
			array_push($result, $item);
		}
		 
		$res['data']=$result;
	}
	else
	{
		$res['status']=404;
		// $res['data']=ref_result;
	}
	echo json_encode($res);
	die;
}
