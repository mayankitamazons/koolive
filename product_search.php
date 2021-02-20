<?php
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Proxies.
include_once("./config.php");

if (isset($_GET['language'])) {
    $_SESSION["langfile"] = $_GET['language'];
}
if (empty($_SESSION["langfile"])) {
    $_SESSION["langfile"] = "english";
}
require_once("languages/" . $_SESSION["langfile"] . ".php");

$issetProduct = true;
if (!isset($_GET['p']) || $_GET['p'] == '')
    $issetProduct = false;

if (!isset($_GET['rdm'])) {
    header("location: ./product_search.php?" . $_SERVER['QUERY_STRING'] . '&rdm=' . md5(rand(0, 1000)));
}


if ($issetProduct) {
    $searchProduct = addslashes($_GET['p']);

    $longitude = isset($_GET['lng']) ? floatval($_GET['lng']) : null;
    $latitude = isset($_GET['lat']) ? floatval($_GET['lat']) : null;

    if (isset($_GET['orderby']) && $_GET['orderby'] !== '' && in_array($_GET['orderby'], ['name', 'price', 'distance', 'rating'])) {
        $translations = [
            'price' => 'product_price',
            'name'  => 'name',
            'distance' => 'distance',
            'rating' => 'avg_rating'
        ];

        $directions = [
            'price' => 'ASC',
            'name' => 'ASC',
            'distance' => 'ASC',
            'rating' => 'DESC'
        ];

        if ($_GET['orderby'] == 'distance') {

            if (!isset($latitude) || !isset($longitude)) {
                $_GET['orderby'] = 'price';
            }
        }
        $orderby = $translations[$_GET['orderby']];
        $orderbyQueryDirection = (in_array($_GET['orderby'], ['distance', 'price'])) ? $directions[$_GET['orderby']] : $_GET['direction'];
        $orderbyDirection = ($orderbyQueryDirection) ? strtoupper($orderbyQueryDirection) : $directions[$_GET['orderby']];
    } else {
        $orderby = "product_price";
        $orderbyDirection = 'ASC';
    }
    $offset = (isset($_GET['pg'])) ? ((intval($_GET['pg']) - 1) * 12) : 0;

    if ($latitude && $longitude)
        $addDistance = ", (6371 * ACOS ( COS ( RADIANS ('$latitude')) * COS ( RADIANS(users.latitude)) * COS(RADIANS(users.longitude) - RADIANS('$longitude'))+ SIN(RADIANS('$latitude')) * SIN(RADIANS(users.latitude)))) AS distance";
    else
        $addDistance = '';

    // $sql = "SELECT user_id AS u_id, products.id AS product_id, product_name, product_type, products.on_stock, about.image, users.name, users.mobile_number AS merchant_number, products.image AS p_image, products.status, user_feedback.avg_rating, user_feedback.ratings, IF( users.price_hike > 0, MIN( product_price *((users.price_hike / 100) + 1) ), MIN(product_price) ) AS product_price_hike $addDistance FROM products INNER JOIN( SELECT products.id, products.user_id AS u_id FROM products INNER JOIN users ON users.id = user_id INNER JOIN arrange_system ON products.id = arrange_system.entity_id WHERE product_name LIKE '%$searchProduct%' AND products.category_id IS NOT NULL AND products.status = 0 AND products.on_stock = 1 AND arrange_system.page_type = 'p' AND products.add_to_cart_button = 1 GROUP BY u_id ) AS products2 ON products.id = products2.id AND products.user_id = products2.u_id LEFT JOIN( SELECT AVG(q1) AS avg_rating, order_list.merchant_id, COUNT(feedback_id) AS ratings FROM `feedback` INNER JOIN order_list ON feedback.order_id = order_list.id INNER JOIN users ON order_list.merchant_id = users.id GROUP BY users.id ) AS user_feedback ON user_feedback.merchant_id = products.user_id INNER JOIN users ON products.user_id = users.id AND users.show_merchant = 1 INNER JOIN about ON products.user_id = about.userid GROUP BY products.user_id ORDER BY {$orderby} {$orderbyDirection} limit 12 offset $offset";

    $sql = "SELECT user_id AS u_id, products.id AS product_id, product_name, product_type, products.on_stock, about.image, users.name, users.mobile_number AS merchant_number, products.image AS p_image, products.status, user_feedback.avg_rating, user_feedback.ratings, IF( users.price_hike > 0, MIN( product_price *((users.price_hike / 100) + 1) ), MIN(product_price) ) AS product_price_hike $addDistance FROM products INNER JOIN( SELECT products.id, products.user_id AS u_id FROM products INNER JOIN users ON users.id = user_id INNER JOIN arrange_system ON products.id = arrange_system.entity_id WHERE product_name LIKE '%$searchProduct%' AND products.category_id IS NOT NULL AND products.status = 0 AND products.on_stock = 1 AND arrange_system.page_type = 'p' AND products.add_to_cart_button = 1 GROUP BY u_id ) AS products2 ON products.id = products2.id AND products.user_id = products2.u_id LEFT JOIN( SELECT AVG(q1) AS avg_rating, order_list.merchant_id, COUNT(feedback_id) AS ratings FROM `feedback` INNER JOIN order_list ON feedback.order_id = order_list.id INNER JOIN users ON order_list.merchant_id = users.id GROUP BY users.id ) AS user_feedback ON user_feedback.merchant_id = products.user_id INNER JOIN users ON products.user_id = users.id AND users.show_merchant = 1 INNER JOIN about ON products.user_id = about.userid GROUP BY products.user_id ORDER BY {$orderby} {$orderbyDirection} limit 12 offset $offset";

    // This SQL is used to have the number of restaurants
    $sql2 = "SELECT user_id AS u_id, products.id AS product_id, product_name, product_type, products.on_stock, about.image, users.name, users.mobile_number AS merchant_number, products.image AS p_image, products.status, user_feedback.avg_rating, user_feedback.ratings, IF( users.price_hike > 0, MIN( product_price *((users.price_hike / 100) + 1) ), MIN(product_price) ) AS product_price_hike $addDistance FROM products INNER JOIN( SELECT products.id, products.user_id AS u_id FROM products INNER JOIN users ON users.id = user_id INNER JOIN arrange_system ON products.id = arrange_system.entity_id WHERE product_name LIKE '%$searchProduct%' AND products.category_id IS NOT NULL AND products.status = 0 AND products.on_stock = 1 AND arrange_system.page_type = 'p' AND products.add_to_cart_button = 1 GROUP BY u_id ) AS products2 ON products.id = products2.id AND products.user_id = products2.u_id LEFT JOIN( SELECT AVG(q1) AS avg_rating, order_list.merchant_id, COUNT(feedback_id) AS ratings FROM `feedback` INNER JOIN order_list ON feedback.order_id = order_list.id INNER JOIN users ON order_list.merchant_id = users.id GROUP BY users.id ) AS user_feedback ON user_feedback.merchant_id = products.user_id INNER JOIN users ON products.user_id = users.id AND users.show_merchant = 1 INNER JOIN about ON products.user_id = about.userid GROUP BY products.user_id";

    $q = mysqli_query($conn, $sql);
    $q2 = mysqli_query($conn, $sql2);
    
    $totalResults = mysqli_num_rows($q2);

    $productResult = [
        'results' => $q->num_rows,
        'order_by' => 'price',
        'data' => []
    ];

    while ($row = mysqli_fetch_assoc($q)) {
        $productResult['data'][] = $row;
    }
} else {
    $productResult = [
        'results' => 0,
        'order_by' => 'none',
        'data' => []
    ];
}

function ceiling($number, $significance = 1)
{
    return (is_numeric($number) && is_numeric($significance)) ? (ceil(round($number / $significance)) * $significance) : false;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("includes1/head.php"); ?>
    <link rel="stylesheet" href="extra/css/style.css" />
    <link rel="stylesheet" href="extra/css/listing.css" />
    <style>
        #sort_fields:after {
            content: '';
        }

        #sort_fields .icon {
            position: absolute;
            right: 5px;
            top: 0;
            bottom: 0;
            margin: auto;
            text-align: center;
            width: 25px;
            height: 25px;
        }

        #sort_fields .icon:after {
            content: <?= ($orderbyDirection === 'ASC') ? '"\21"' : '"\22"' ?>;
            font-family: ElegantIcons;
            font-size: 1rem;
        }
    </style>
</head>

<body>
    <header class="header_in clearfix element_to_stick">
        <div class="container">
            <div id="logo">
                <a href="/">
                    <img src="svgLog_first.svg" width="140" height="35" alt="" class="logo_sticky">
                </a>
            </div>
            <ul id="top_menu">

                <?php if (isset($_SESSION['login'])) {    ?>
                    <li><a href="favorite.php" class="wishlist_bt_top" title="Your favorite">Your wishlist</a></li>
                <?php } ?>
            </ul>
            <!-- /top_menu -->
            <a href="#0" class="open_close">
                <i class="icon_menu"></i><span>Menu</span>
            </a>
            <nav class="main-menu">
                <div id="header_menu">
                    <a href="#0" class="open_close">
                        <i class="icon_close"></i><span>Menu</span>
                    </a>
                    <a href="index.php?vs=<?php echo md5(rand()); ?>">KooFamilies</a>

                </div>
                <ul>

                    <li><a href="." style="font-size:16px">Homepage</a></li>
                    <?php if (!isset($_SESSION['login'])) {    ?>
                        <li><a href="login.php" style="font-size:16px"><?php echo $language['login']; ?></a></li>
                    <?php } else { ?>
                        <li class="submenu"><a href="dashboard.php" class="show-submenu" style="font-size:16px"><?php echo $language['dashboard']; ?></a></li>
                        <li class="submenu"><a href="favorite.php" class="show-submenu" style="font-size:16px"><?php echo $language['my_fav']; ?></a></li>
                    <?php } ?>

                    <li class="submenu">
                        <a href="#0" class="show-submenu"> <i class="fa fa-language" style="font-size:18px;" aria-hidden="true"></i> Language</a>
                        <ul class="">

                            <li><a href="index.php?vs=<?php echo md5(rand()); ?>&language=english">English</a></li>
                            <li><a href="index.php?vs=<?php echo md5(rand()); ?>&language=chinese">Chinese</a></li>
                            <li><a href="index.php?vs=<?php echo md5(rand()); ?>&language=malaysian">Malay</a></li>


                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
    </header>
    <main style="min-height: 90vh">
        <div class="page_header pb-0">
            <div class="container">
                <div class="row">
                    <div class="col-xl-8 col-lg-7 col-md-7 d-none d-md-block">
                        <div class="breadcrumbs">
                            <ul>
                                <li><a href="/">Home</a></li>
                                <li><a href="/">Search</a></li>
                                <li><?= $searchProduct ?></li>
                            </ul>
                        </div>
                        <?php
                        if ($totalResults !== 0) {
                        ?>
                            <h1><?= $totalResults ?> restaurants have <?= $searchProduct ?></h1>
                        <?php
                        } else {
                        ?>
                            <h1>No restaurants were found</h1>
                        <?php
                        }
                        ?>
                    </div>
                    <div class="col-xl-4 col-lg-5 col-md-5">
                        <div class="search_bar_list">
                            <form>
                                <input type="text" class="form-control" name="p" value="<?= (isset($_GET['p'])) ? $_GET['p'] : '' ?>" placeholder="Search again...">
                                <input type="submit" value="<?=$language['search'] ?>">
                            </form>
                        </div>
                    </div>
                </div>
                <!-- /row -->
            </div>

            <div class="filters_full clearfix add_bottom_15">
                <div class="container">
                    <div class="clearfix">
                        <div id="sort_fields" class="sort_select bg-white">
                            <select name="sort" id="sort">
                                <option value="price" <?= (isset($_GET['orderby']) && $_GET['orderby'] == 'price') ? 'selected="selected"' : '' ?>><?=$language['sort_price_low_high'] ?></option>
                                <option value="distance" <?= (isset($_GET['orderby']) && $_GET['orderby'] == 'distance') ? 'selected="selected"' : '' ?>>Sort by Distance</option>
                                <option value="rating" <?= (isset($_GET['orderby']) && $_GET['orderby'] == 'rating') ? 'selected="selected"' : '' ?>>Sort by Rating</option>
                                <option value="name" <?= (isset($_GET['orderby']) && $_GET['orderby'] == 'name') ? 'selected="selected"' : '' ?>>Sort by Name: A - Z</option>
                            </select>
                            <div class="icon"></div>
                        </div>
                    </div>
                    <a href="./index.php?vs=<?=md5(rand()) ?>" class="btn btn-success" style="background-color: #589442;color:#fff;"><?=$language["search_by_company"] ?></a>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row mt-5">
                <?php
                if ($totalResults === 0) {
                ?>
                    <span>No results were found</span>
                    <?php
                } else {
                    foreach ($productResult['data'] as $product) {
                        if ($longitude && $latitude)
                            $product['distance'] = number_format(floatval($product['distance']), 2);
                        $user_id = $product['u_id'];
                        $productsAvailable = mysqli_fetch_assoc(mysqli_query($conn, "SELECT count(id) AS productsAvailable FROM products WHERE products.user_id = $user_id"))['productsAvailable'];
                        $floatDistance = $product['distance'] ? str_replace(',', '', $product['distance']) : 0;
                        $chargeSql = "SELECT charge FROM delivery_plan WHERE merchant_id='{$user_id}' AND max_distance >= {$floatDistance} ORDER BY max_distance ASC LIMIT 1";
                        $product['charge'] = mysqli_fetch_assoc(mysqli_query($conn, $chargeSql))['charge'];
                        $avgRating = $product['avg_rating'];
                        $number_ratings = $product['ratings'];
                        

                        $get_workingHr = mysqli_fetch_assoc(mysqli_query($conn, "SELECT start_time , end_time , merchant_id FROM `timings` WHERE   DAY = DAYNAME( NOW() )AND  merchant_id = '$user_id' "));
						//echo $get_workingHr['start_time'] ;	
						//echo "SELECT start_time , end_time , merchant_id FROM `timings` WHERE   DAY = DAYNAME( NOW() )AND  merchant_id = '$user_id' " ;
                        $inWorkingHours = false;
                        if (!empty($get_workingHr['start_time']) && !empty($get_workingHr['end_time'])) {
							 $currentTime    = strtotime(date("H:i"));
							 
							  if (strtotime($get_workingHr['start_time']) < $currentTime && $currentTime < strtotime($get_workingHr['end_time'])) {
                                    $inWorkingHours = true;
									
                                 }
								
                            /**
							$daysArr = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                            $working_hours = (object) [
                                'start_hour'    => $get_workingHr['start_time'],
                                'end_hour'      => $get_workingHr['end_time'],
                                'start_day'     => $get_workingHr['start_day'],
                                'end_day'       => $get_workingHr['end_day']
                            ];
                            $start_time = date("g:i a", strtotime($working_hours->start_hour));
                            $end_time = date("g:i a", strtotime($working_hours->end_hour));

                            $startDay   = intval(array_search(strtolower($working_hours->start_day), $daysArr)) + 1;  // To make it 1-7 and not 0-6
                            $endDay     = intval(array_search(strtolower($working_hours->end_day), $daysArr)) + 1;      // To make it 1-7 and not 0-6
                            
                            $currentDay = intval(date("N"));
                            $currentTime    = strtotime(date("H:i"));

                            if ($startDay < $currentDay && $currentDay < $endDay) {

                                if (strtotime($start_time) < $currentTime && $currentTime < strtotime($end_time)) {
                                    $inWorkingHours = true;
                                }
                            }
							**/
                        }
                    ?>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                            <div class="card strip showLoader6">
                                <figure class="showLoader6" style="border-radius: 5px 5px 0 0">
                                    <?php
                                    if ($product['p_image']) {
                                    ?>
                                        <img src="<?= "{$image_cdn}product/{$product['p_image']}" ?>" data-src="<?= "{$image_cdn}about_images/{$product['image']}" ?>" class="img-fluid lazy loaded" alt="" data-was-processed="true">
                                    <?php } else {
                                    ?>
                                        <img src="<?= "{$image_cdn}about_images/{$product['image']}" ?>" data-src="<?= "{$image_cdn}about_images/{$product['image']}" ?>" class="img-fluid lazy loaded" alt="" data-was-processed="true">
                                    <?php
                                    } ?>
                                    <a href="<?= $site_url . "/view_merchant.php?sid={$product['merchant_number']}&pd={$product['product_id']}" ?>" class="strip_info">
                                    </a>
                                </figure>
                                <div class="card-body pt-0 pl-2 pr-2 pb-2">
                                    <div class="row mr-0 ml-0">
                                        <?php
                                        if ($avgRating) {
                                        ?>
                                            <div class="col-md-12 p-0">
                                                <div class="score float-right">
                                                    <span class="text-secondary">
                                                        Merchant<br />rating
                                                    </span>
                                                    <strong>
                                                        <?= ($avgRating) ? number_format($avgRating, 1) + 0 : '-' ?> / 5
                                                    </strong>
                                                </div>
                                                <?= $product['name'] ?>
                                            </div>
                                            <div class="col-md-12 p-0">
                                                <div class="float-right">
                                                    <small>Has <?= $number_ratings ?> reviews</small>
                                                </div>
                                            </div>
                                        <?php } else { ?>
                                            <div class="col-md-12 p-0 mt-0">
                                                <div class="score float-right">
                                                    <span class="text-secondary">
                                                        No reviews
                                                    </span>
                                                </div>
                                                <?= $product['name'] ?>
                                            </div>
                                        <?php } ?>
                                        <div class="col-md-12 p-0">
                                            <hr class="mt-2 mb-2">
                                        </div>
                                        <?php
                                         if(!$inWorkingHours){
                                        ?>
                                        <div class="col-md-12 p-0 text-danger"><?=$language['shop_closed_order_later'] ?></div>
                                        <?php
                                        }
                                        ?>
                                        <div class="col-md-12 p-0">
                                            <b><?= $product['product_name'] ?> price:</b> <span class="text-info">Rm <?= ceiling($product['product_price_hike'], 0.05); ?></span>
                                        </div>

                                        <div class="col-md-12 p-0">
                                            <b>Delivery fee: </b> <span class="text-info"><?= ($product['charge']) ? "Rm {$product['charge']}" : 'Not available' ?></span>
                                        </div>
                                        <?php
                                        if ($addDistance !== '') {
                                        ?>
                                            <!--div class="col-md-12 p-0">
                                                <b>Distance:</b> <span class="text-info">~ <?= $product['distance'] ?> Km</span>
                                            </div!-->
                                        <?php
                                        }
                                        
                                       // if ($inWorkingHours) {
                                            $start_day_diminutive = date('D',strtotime($working_hours->start_day));
                                            $end_day_diminutive = date('D',strtotime($working_hours->end_day));
                                        ?>
                                            <div class="col-md-12 p-0">
                                                <!--<b>Opening days:</b> <span class="text-info"><?="{$start_day_diminutive} - {$end_day_diminutive}" ?></span>!-->
                                             
                                                <b>Delivery hours:</b> <span class="text-info"><?= $get_workingHr['start_time'] ?> - <?= $get_workingHr['end_time'] ?>
												</span>
                                            </div>
                                        <?php
                                       // }
                                        ?>
                                        <div class="col-md-12 p-0">
                                            <b>Products available:</b> <span class="text-info"><?= $productsAvailable ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php
                    }
                }
                ?>
            </div>
            <?php 
            $ignoredKeys = ['pg']; // Ignored keys for the formation of new URL
            $pages = ceil(floatval($totalResults) / 12);
            $currentPage = isset($_GET['pg']) ? $_GET['pg'] : 1;
            $urlQueries = '?';
            foreach($_GET as $key => $value) {
                if(in_array($key, $ignoredKeys)) continue;
                $urlQueries .= "{$key}={$value}&";
            }
            // $urlQueries = mb_substr($urlQueries, 0, -1);
            ?>
            <div class="pagination_fg m-2">
                <a id="pag_prev" href="./product_search.php<?=$urlQueries ?>pg=<?= ($currentPage - 1 < 1) ? 1 : $currentPage - 1 ?>" class="<?= ($currentPage - 1 < 1) ? 'disabled' : '' ?>">«</a>
                <?php

                for ($i = 0; $i < $pages; $i++) {
                    $page = $i + 1;
                ?>
                    <a href="./product_search.php<?=$urlQueries ?>pg=<?= $page ?>" class="<?= ($currentPage == $page) ? 'active' : '' ?>"><?= $page ?></a>
                <?php
                } ?>
                <a id="pag_next" href="./product_search.php<?=$urlQueries ?>pg=<?= ($currentPage + 1 > $pages) ? $pages : $currentPage + 1 ?>" class="<?= ($currentPage + 1 > $pages) ? 'disabled' : '' ?>">»</a>
            </div>
        </div>
    </main>


    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <h3 data-target="#collapse_1">Quick Links</h3>
                    <div class="collapse dont-collapse-sm links" id="collapse_1">
                        <ul>

                            <li><a href="about.php">About us</a></li>
                            <li><a href="login.php">Add your restaurant</a></li>
                            <li><a href="favorite.php">Favorite Merchant</a></li>
                            <li><a href="login.php">My account</a></li>

                            <li><a href="about.php">Contacts</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h3 data-target="#collapse_3">Contacts</h3>
                    <div class="collapse dont-collapse-sm contacts" id="collapse_3">
                        <ul>
                            <li><i class="icon_house_alt"></i>Kemajuaan ladang Cermerlang Sdn. Bhd. 1400, Jalan Lagenda 50, </br>Taman Lagenda Putra Kulai, Johor, 81000, Malaysia</li>
                            <li><i class="icon_mobile"></i>+60 123-11-5670</li>
                            <li><i class="icon_mail_alt"></i><a href="#0">info@koopay.com</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h3 data-target="#collapse_4">Keep in touch</h3>
                    <div class="collapse dont-collapse-sm" id="collapse_4">
                        <div id="newsletter">
                            <div id="message-newsletter"></div>
                            <form method="post" action="assets/newsletter.php" name="newsletter_form" id="newsletter_form">
                                <div class="form-group">
                                    <input type="email" name="email_newsletter" id="email_newsletter" class="form-control" placeholder="Your email">
                                    <button type="submit" id="submit-newsletter"><i class="arrow_carrot-right"></i></button>
                                </div>
                            </form>
                        </div>
                        <div class="follow_us">
                            <h5>Follow Us</h5>
                            <ul>
                                <!--li><a href="#0"><img src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==" data-src="img/twitter_icon.svg" alt="" class="lazy"></a></li!-->
                                <li><a href="https://www.facebook.com/koofamilies/" target="_blank"><img src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==" data-src="img/facebook_icon.svg" alt="" class="lazy"></a></li>
                                <li><a href="#0"><img src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==" data-src="img/instagram_icon.svg" alt="" class="lazy"></a></li>
                                <li><a href="#0"><img src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==" data-src="img/youtube_icon.svg" alt="" class="lazy"></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /row-->
            <hr>
            <div class="row add_bottom_25">
                <div class="col-lg-6">
                    <ul class="additional_links">
                        <li><a href="privacy.php">Terms and conditions</a></li>
                        <li><a href="privacy.php">Privacy</a></li>
                        <li><span>© <?= date('Y'); ?> KooFamilies</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
    <?php include("includes1/footer.php"); ?>
    <script src="extra/js/common_scripts.min.js" defer></script>
    <script src="extra/js/common_func.js" defer></script>
    <script>
        $(document).ready(function() {
			jQuery(document).on("click", '.showLoader6', function() {
				if(window.location.href.includes('orderview.php')) return false;
				$('.page_loader').removeAttr('style');
				$("#load").removeAttr('style');;
				$('.page_loader').show();
				$("#load").show();
				setTimeout(function () {
					$('.page_loader').hide();
					$("#load").hide();
				}, 6000);
			});
            navigator.geolocation.getCurrentPosition((position) => {
                let url = new URLSearchParams(location.search);
                var latitude = position.coords.latitude;
                var longitude = position.coords.longitude;
                if (latitude && longitude) {
                    let needsReload = false;
                    if (!(url.has('lat') === true && url.has('lng') === true))
                        window.location.href = `./product_search.php?p=<?= $searchProduct ?>&orderby=${$("#sort_fields").find("option:selected").val()}&direction=<?= ($orderbyDirection === 'ASC') ? 'desc' : 'asc' ?>&lat=${latitude}&lng=${longitude}&pg=<?=$_GET['pg']; ?>`;
                }
            });

            $("#sort_fields .icon").on("click", function() {
                window.location.href = `./product_search.php?p=<?= $searchProduct ?>&orderby=${$("#sort_fields").find("option:selected").val()}&direction=<?= ($orderbyDirection === 'ASC') ? 'desc' : 'asc' ?><?= (isset($_GET['lat']) ? '&lat=' . $_GET['lat'] : '') ?><?= (isset($_GET['lng']) ? '&lng=' . $_GET['lng'] : '') ?>`;
            });

            $("#sort_fields").on("change", function() {
                window.location.href = `./product_search.php?p=<?= $searchProduct ?>&orderby=${$(this).find("option:selected").val()}&direction=<?= ($orderbyDirection === 'ASC') ? 'desc' : 'asc' ?><?= (isset($_GET['lat']) ? '&lat=' . $_GET['lat'] : '') ?><?= (isset($_GET['lng']) ? '&lng=' . $_GET['lng'] : '') ?>`;
                // window.location.href = `./product_search.php?p=<?= $searchProduct ?>&orderby=${$(this).find("option:selected").val()}`;
            });
        });
    </script>

</body>

</html>