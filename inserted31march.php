<?php

require_once "./config.php";
$posted = file_get_contents("php://input");
$orders = json_decode($posted);

if (!$conn)
{
    echo "database error"; die;
}

$query = mysqli_query($conn, "SELECT * FROM order_list");

if (isset($orders->orders)) {
    if ($orders->orders != null) {
        foreach ($orders->orders as $order) {

            $update = "UPDATE `order_list` SET " .
                "`product_id` = '{$order->product_id}', `user_id` = '{$order->user_id}', `merchant_id` = '{$order->merchant_id}'," .
                "`quantity` = '{$order->quantity}', `amount` = '{$order->amount}', `wallet` = '{$order->wallet}', `created_on` = '{$order->created_on}', " .
                "`location` = '{$order->location}', `table_type` = '{$order->table_type}', `status` = '{$order->status}', `remark` = '{$order->remark}', " .
                "`invoice_no` = '{$order->invoice_no}', `popup` = '{$order->popup}', `product_code` = '{$order->product_code}',`status_change_date` = '{$order->status_change_date}', " .
                "`section_type` = '{$order->section_type}',`varient_type` = '{$order->varient_type}' WHERE `order_list`.`id` = ##"; 
    
            $sql = "INSERT INTO `order_list` (`id`, `product_id`, `user_id`, `merchant_id`, `quantity`, `amount`, `wallet`, `created_on`, `location`, " .
                "`table_type`, `status`, `remark`, `invoice_no`, `popup`, `product_code`, `status_change_date`, `section_type`,`varient_type`) " .
                "VALUES (NULL, '{$order->product_id}', '{$order->user_id}', '{$order->merchant_id}', '{$order->quantity}', '{$order->amount}', " .
                "'{$order->wallet}', '{$order->created_on}', '{$order->location}', '{$order->table_type}', '{$order->status}', " .
                "'{$order->remark}', '{$order->invoice_no}', '{$order->popup}', '{$order->product_code}', '{$order->status_change_date}', '{$order->section_type}','{$order->varient_type}')";

      
            $found = 0;
            while ($row = mysqli_fetch_assoc($query)) {
                if (strval($row['created_on']) == strval($order->created_on) &&
                    strval($row['invoice_no']) == strval($order->invoice_no)
                ) {
                    switch (intval($order->status)) {
                        case 0:
                            // Do not update nor insert
                            $found = 2;
                            break;
                        case 1:
                            if (intval($row['status']) == 0 || intval($row['status']) == 2) {
                                // Update
                                $found = 1;
                            }
                            break;
                        case 2:
                            if (intval($row['status']) == 0) {
                                // Update
                                $found = 1;
                            }
                            break;
                    }
                    $update = str_replace("##", $row["id"], $update);

                    // $found = true;
                    break;
                } else {
                    if (strval($row['merchant_id']) == strval($order->merchant_id) &&
                        strval($row['invoice_no']) == strval($order->invoice_no) &&
                        strval($row['user_id']) == strval($order->user_id) &&
                        strval($row['product_id']) == strval($order->product_id)) {
                        switch (intval($order->status)) {
                            case 0:
                                // Do not update nor insert
                                $found = 2;
                                break;
                            case 1:
                                if (intval($row['status']) == 0 || intval($row['status']) == 2) {
                                    // Update
                                    $found = 1;
                                }
                                break;
                            case 2:
                                if (intval($row['status']) == 0) {
                                    // Update
                                    $found = 1;
                                }
                                break;
                        }
                        $update = str_replace("##", $row["id"], $update);

                        //$found = true;
                        break;
                    }

                }
            }

            if ($found == 1) {
                echo "$update\n";
                mysqli_query($conn, $update);
            } else {
                if ($found == 0) {
                    echo "insert\n";
                    mysqli_query($conn, $sql);

                    /*
                                $inserted_id=mysqli_insert_id($conn);
                                $mrchat_id=$order->merchant_id;
                                $user_id=$order->user_id;
                                // check setting of user is auto print is enabled or not
                                $ref_result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name, id, order_print_setting, sst, gst, register FROM users WHERE id='".$mrchat_id."'"));
                                if( $ref_result['order_print_setting'] === 'on' ) {
                                    $register = $ref_result['register'];
                                    $sst = $ref_result['sst'];
                                    $gst = $ref_result['gst'];
                                    $merchant_name = $ref_result['name'];
                                    $array_product_names;
                                    $product_ids = explode(",", $order->product_id);
                                    $product_qty = explode(",", $order->quantity);
                                    $product_amt = explode(",", $order->amount);
                                    for ($i = 0; $i < count($product_ids); $i++) {
                                        if (is_numeric($product_ids[$i])) {
                                            $product_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT product_name FROM products WHERE id ='" . $product_ids[$i] . "'"))['product_name'];
                                        } else {
                                            $product_name = $product_ids[$i];
                                        }
                                        $array_product_names[$i] = $product_name;
                                    }
                                    $order_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name FROM users where id='$user_id'"))['name'];
                                    // when new value inserted make auto print of that
                                    $item = array('product_ids' => $product_ids, 'register' => $register, 'sst' => $sst, 'gst' => $gst, 'user_id' => $user_id, 'product_code' => $order->product_code, 'table_type' => $order->table_type, 'section_type' => $order->section_type, 'location' => $order->location, 'remark' => $order->remark, 'invoice_no' => $order->invoice_no, 'status' => $order->status, 'id' => $inserted_id, 'username' => $order_name, 'merchantname' => $merchant_name, 'product_name' => $array_product_names, 'product_qty' => $product_qty, 'product_amt' => $product_amt);
                                    array_push($array_detail, $item);
                                    $request = json_encode($array_detail);
                                    $date = date('m/d/Y', time());
                                    $time = date('h:i:s a', time());
                                    $post = [
                                        'order' => $request,
                                        'method' => "pintOrder",
                                        'date' => $date,
                                        'time' => $time,
                                    ];

                                    $ch = curl_init('https://www.koofamilies.com/functions.php');
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

                                    // execute!
                                    $response = curl_exec($ch);

                                    // close the connection, release resources used
                                    curl_close($ch);

                                    // do anything you want with your response
                                    var_dump($response);

                                }
                    */
                } else {
                    // auto print is not enabled do manully print
                }


            }
        }
    }
}

// print_r($orders->users);
foreach ($orders->users as $user)
{
    
    $sql =  "INSERT INTO `users` (`id`, `fbid`, `name`, `image`, `email`, `mobile_number`, `password`, `fund_password`, `security_questions`, " .
            "`security_answer`, `balance_usd`, `balance_inr`, `balance_myr`, `verification_code`, `bank_name`, `bank_branch`, `bank_ifsc`, " .
            
			"`bank_ac_num`, `doc_copy`, `referral_id`, `referred_by`, `isLocked`, `IsVIP`, `joined`, `user_roles`, `real_name`, `mric_no`, " .
            "`address`, `facebook`, `authentication`, `rand_num`, `auth_key`, `company`, `register`, `gst`, `sst`, `facsimile_number`, " .
            "`business1`, `business2`, `bank_deatils`, `name_card`, `expiry_date`, `cvv`, `name_accoundholder`, `charge`, " .
            "`nric_number`, `address_person`, `hand_phone`, `google_map`, `latitude`, `longitude`, `company_doc`, `handphone_number`, " .
            "`login_status`, `number_lock`, `account_type`, `created_at`, `k_date`, `k_lock`, `merchant_code`, `merchant_url`, " .
            "`moengage_added`, `moengage_unique_id`, `session`, `token`, `resetdate`, `order_print_setting`, `print_ip_address`, " .
            "`guest_permission`, `preset_words`, `pending_time`, `menu_type`, `printer_style`) " .
            "VALUES ({$user->id}, '{$user->fbid}', '{$user->name}', '{$user->image}', '{$user->email}', '{$user->mobile_number}', '{$user->password}', '{$user->fund_password}', '{$user->security_questions}', " .
            "'{$user->security_answer}', '0', '0', '0', '{$user->verification_code}', NULL, NULL, NULL, NULL, NULL, '{$user->referral_id}', '{$user->referred_by}', '0', '{$user->IsVIP}', " . 
            "'{$user->joined}', '{$user->user_roles}', '{$user->real_name}', '{$user->mric_no}', '{$user->address}', '{$user->facebook}', '{$user->authentication}', '{$user->rand_num}', '{$user->auth_key}', " .
            "'{$user->company}', '{$user->register}', '{$user->gst}', '{$user->sst}', '{$user->facsimile_number}', '{$user->business1}', '{$user->business2}', '{$user->bank_deatils}', '{$user->name_card}', " .
            "'{$user->expiry_date}', '{$user->cvv}', '{$user->name_accoundholder}', '{$user->charge}', '{$user->nric_number}', '{$user->address_person}', '{$user->hand_phone}', '{$user->google_map}', " .
            "'{$user->latitude}', '{$user->longitude}', '{$user->company_doc}', '{$user->handphone_number}', '0', '0', '{$user->account_type}', '{$user->created_at}', '{$user->k_date}', '{$user->k_lock}', " .
            "'{$user->merchant_code}', '{$user->merchant_url}', 'n', '{$user->moengage_unique_id}', '{$user->session}', '{$user->token}', '{$user->resetdate}', '{$user->order_print_setting}', '{$user->print_ip_address}', '0', " .
            "'{$user->preset_words}', '{$user->pending_time}', '{$user->menu_type}', 'normal')";


    $found = false;
	$query = mysqli_query($conn, "SELECT * FROM users");
    while($row = mysqli_fetch_assoc($query))
    {
        if (strval($row['id']) == strval($user->id))
        {
            $found = true;
            break;
        }
    }

    if (!$found)
    {
        mysqli_query($conn, $sql);
    }
}

?><?php

require_once "./config.php";
$posted = file_get_contents("php://input");
$orders = json_decode($posted);

if (!$conn)
{
    echo "database error"; die;
}

$query = mysqli_query($conn, "SELECT * FROM order_list");

if (isset($orders->orders)) {
    if ($orders->orders != null) {
        foreach ($orders->orders as $order) {

            $update = "UPDATE `order_list` SET " .
                "`product_id` = '{$order->product_id}', `user_id` = '{$order->user_id}', `merchant_id` = '{$order->merchant_id}'," .
                "`quantity` = '{$order->quantity}', `amount` = '{$order->amount}', `wallet` = '{$order->wallet}', `created_on` = '{$order->created_on}', " .
                "`location` = '{$order->location}', `table_type` = '{$order->table_type}', `status` = '{$order->status}', `remark` = '{$order->remark}', " .
                "`invoice_no` = '{$order->invoice_no}', `popup` = '{$order->popup}', `product_code` = '{$order->product_code}', `varient_type` = '{$order->varient_type}',`status_change_date` = '{$order->status_change_date}', " .
                "`section_type` = '{$order->section_type}', " .
                "`printed` = '{$order->printed}' WHERE `order_list`.`id` = ##";

            $sql = "INSERT INTO `order_list` (`id`, `product_id`, `user_id`, `merchant_id`, `quantity`, `amount`, `wallet`, `created_on`, `location`, " .
                "`table_type`, `status`, `remark`, `invoice_no`, `popup`, `product_code`, `status_change_date`, `section_type`, `printed`,`varient_type`) " .
                "VALUES (NULL, '{$order->product_id}', '{$order->user_id}', '{$order->merchant_id}', '{$order->quantity}', '{$order->amount}', " .
                "'{$order->wallet}', '{$order->created_on}', '{$order->location}', '{$order->table_type}', '{$order->status}', " .
                "'{$order->remark}', '{$order->invoice_no}', '{$order->popup}', '{$order->product_code}', '{$order->status_change_date}', '{$order->section_type}', '{$order->printed}', '{$order->varient_type}')";  


            $found = 0;
            while ($row = mysqli_fetch_assoc($query)) {
                if (strval($row['created_on']) == strval($order->created_on) &&
                    strval($row['invoice_no']) == strval($order->invoice_no)
                ) {
                    switch (intval($order->status)) {
                        case 0:
                            // Do not update nor insert
                            $found = 2;
                            break;
                        case 1:
                            if (intval($row['status']) == 0 || intval($row['status']) == 2) {
                                // Update
                                $found = 1;
                            }
                            break;
                        case 2:
                            if (intval($row['status']) == 0) {
                                // Update
                                $found = 1;
                            }
                            break;
                    }
                    $update = str_replace("##", $row["id"], $update);

                    // $found = true;
                    break;
                } else {
                    if (strval($row['merchant_id']) == strval($order->merchant_id) &&
                        strval($row['invoice_no']) == strval($order->invoice_no) &&
                        strval($row['user_id']) == strval($order->user_id) &&
                        strval($row['product_id']) == strval($order->product_id)) {
                        switch (intval($order->status)) {
                            case 0:
                                // Do not update nor insert
                                $found = 2;
                                break;
                            case 1:
                                if (intval($row['status']) == 0 || intval($row['status']) == 2) {
                                    // Update
                                    $found = 1;
                                }
                                break;
                            case 2:
                                if (intval($row['status']) == 0) {
                                    // Update
                                    $found = 1;
                                }
                                break;
                        }
                        $update = str_replace("##", $row["id"], $update);

                        //$found = true;
                        break;
                    }

                }
            }

            if ($found == 1) {
                echo "$update\n";
                mysqli_query($conn, $update);
            } else {
                if ($found == 0) {
                    echo "insert\n";
                    mysqli_query($conn, $sql);

                    /*
                                $inserted_id=mysqli_insert_id($conn);
                                $mrchat_id=$order->merchant_id;
                                $user_id=$order->user_id;
                                // check setting of user is auto print is enabled or not
                                $ref_result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name, id, order_print_setting, sst, gst, register FROM users WHERE id='".$mrchat_id."'"));
                                if( $ref_result['order_print_setting'] === 'on' ) {
                                    $register = $ref_result['register'];
                                    $sst = $ref_result['sst'];
                                    $gst = $ref_result['gst'];
                                    $merchant_name = $ref_result['name'];
                                    $array_product_names;
                                    $product_ids = explode(",", $order->product_id);
                                    $product_qty = explode(",", $order->quantity);
                                    $product_amt = explode(",", $order->amount);
                                    for ($i = 0; $i < count($product_ids); $i++) {
                                        if (is_numeric($product_ids[$i])) {
                                            $product_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT product_name FROM products WHERE id ='" . $product_ids[$i] . "'"))['product_name'];
                                        } else {
                                            $product_name = $product_ids[$i];
                                        }
                                        $array_product_names[$i] = $product_name;
                                    }
                                    $order_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name FROM users where id='$user_id'"))['name'];
                                    // when new value inserted make auto print of that
                                    $item = array('product_ids' => $product_ids, 'register' => $register, 'sst' => $sst, 'gst' => $gst, 'user_id' => $user_id, 'product_code' => $order->product_code, 'table_type' => $order->table_type, 'section_type' => $order->section_type, 'location' => $order->location, 'remark' => $order->remark, 'invoice_no' => $order->invoice_no, 'status' => $order->status, 'id' => $inserted_id, 'username' => $order_name, 'merchantname' => $merchant_name, 'product_name' => $array_product_names, 'product_qty' => $product_qty, 'product_amt' => $product_amt);
                                    array_push($array_detail, $item);
                                    $request = json_encode($array_detail);
                                    $date = date('m/d/Y', time());
                                    $time = date('h:i:s a', time());
                                    $post = [
                                        'order' => $request,
                                        'method' => "pintOrder",
                                        'date' => $date,
                                        'time' => $time,
                                    ];

                                    $ch = curl_init('https://www.koofamilies.com/functions.php');
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

                                    // execute!
                                    $response = curl_exec($ch);

                                    // close the connection, release resources used
                                    curl_close($ch);

                                    // do anything you want with your response
                                    var_dump($response);

                                }
                    */
                } else {
                    // auto print is not enabled do manully print
                }


            }
        }
    }
}

// print_r($orders->users);
foreach ($orders->users as $user)
{
    
    $sql =  "INSERT INTO `users` (`id`, `fbid`, `name`, `image`, `email`, `mobile_number`, `password`, `fund_password`, `security_questions`, " .
            "`security_answer`, `balance_usd`, `balance_inr`, `balance_myr`, `verification_code`, `bank_name`, `bank_branch`, `bank_ifsc`, " .
            
			"`bank_ac_num`, `doc_copy`, `referral_id`, `referred_by`, `isLocked`, `IsVIP`, `joined`, `user_roles`, `real_name`, `mric_no`, " .
            "`address`, `facebook`, `authentication`, `rand_num`, `auth_key`, `company`, `register`, `gst`, `sst`, `facsimile_number`, " .
            "`business1`, `business2`, `bank_deatils`, `name_card`, `expiry_date`, `cvv`, `name_accoundholder`, `charge`, " .
            "`nric_number`, `address_person`, `hand_phone`, `google_map`, `latitude`, `longitude`, `company_doc`, `handphone_number`, " .
            "`login_status`, `number_lock`, `account_type`, `created_at`, `k_date`, `k_lock`, `merchant_code`, `merchant_url`, " .
            "`moengage_added`, `moengage_unique_id`, `session`, `token`, `resetdate`, `order_print_setting`, `print_ip_address`, " .
            "`guest_permission`, `preset_words`, `pending_time`, `menu_type`, `printer_style`) " .
            "VALUES ({$user->id}, '{$user->fbid}', '{$user->name}', '{$user->image}', '{$user->email}', '{$user->mobile_number}', '{$user->password}', '{$user->fund_password}', '{$user->security_questions}', " .
            "'{$user->security_answer}', '0', '0', '0', '{$user->verification_code}', NULL, NULL, NULL, NULL, NULL, '{$user->referral_id}', '{$user->referred_by}', '0', '{$user->IsVIP}', " . 
            "'{$user->joined}', '{$user->user_roles}', '{$user->real_name}', '{$user->mric_no}', '{$user->address}', '{$user->facebook}', '{$user->authentication}', '{$user->rand_num}', '{$user->auth_key}', " .
            "'{$user->company}', '{$user->register}', '{$user->gst}', '{$user->sst}', '{$user->facsimile_number}', '{$user->business1}', '{$user->business2}', '{$user->bank_deatils}', '{$user->name_card}', " .
            "'{$user->expiry_date}', '{$user->cvv}', '{$user->name_accoundholder}', '{$user->charge}', '{$user->nric_number}', '{$user->address_person}', '{$user->hand_phone}', '{$user->google_map}', " .
            "'{$user->latitude}', '{$user->longitude}', '{$user->company_doc}', '{$user->handphone_number}', '0', '0', '{$user->account_type}', '{$user->created_at}', '{$user->k_date}', '{$user->k_lock}', " .
            "'{$user->merchant_code}', '{$user->merchant_url}', 'n', '{$user->moengage_unique_id}', '{$user->session}', '{$user->token}', '{$user->resetdate}', '{$user->order_print_setting}', '{$user->print_ip_address}', '0', " .
            "'{$user->preset_words}', '{$user->pending_time}', '{$user->menu_type}', 'normal')";


    $found = false;
	$query = mysqli_query($conn, "SELECT * FROM users");
    while($row = mysqli_fetch_assoc($query))
    {
        if (strval($row['id']) == strval($user->id))
        {
            $found = true;
            break;
        }
    }

    if (!$found)
    {
        mysqli_query($conn, $sql);
    }
}

?>