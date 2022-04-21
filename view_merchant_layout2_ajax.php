<?php 
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
//include("config.php");

function ceiling($number, $significance = 1)
{
	return ( is_numeric($number) && is_numeric($significance) ) ? (ceil(round($number/$significance))*$significance) : false;
}
function toSeconds($date){
    $a = explode(":", $date); 
    // var_dump($a);
    return intval($a[0]) * 3600 + intval($a[1]) * 60; 
  }
  function isActive($date){
      if($date == 1){
          return true;
      }
      $date = json_decode($date);
      for($i = 0; $i < sizeof($date); $i++) {
          $inDate = false;
          $startHours = '';
          $endHours = '';
          foreach ($date[$i] as $key => $value) {
              if($key == "days" && in_array(date("N"),explode("-", $value))){
                  $inDate = true;
              }
              if($inDate && $key == "start"){
                  $startHours = $value;
              }else if($inDate && $key == "end"){
                  if(toSeconds($startHours) < toSeconds(date("G:i")) && toSeconds(date("G:i")) < toSeconds($value)){
                      return true;
                  }
              }
          }
      }
      return false;
  }
if(isset($_GET['cats'])){
    include("config.php");
	$id = $_GET['id'];
	$product['pro_ct'] = $_GET['prduct_qty'];
    $hike_per = $_GET['hike_per'];
}
$hike_per = $_GET['hike_per'];
$index = 1;
$sub_catArray = array(); //ajaxcall
$categories_q = mysqli_query($conn, "SELECT   * FROM cat_mater WHERE UserID ='".$id."' and IsEnable=1 ");
$category_a = mysqli_fetch_assoc($categories_q);
$categories = explode(",",$category_a['CatName']);
$query="select  id from arrange_system WHERE user_id ='".$id."' and page_type='c' and status='active' group  by entity_id";
$orderquery=mysqli_query($conn,$query);
$ordercount=mysqli_num_rows($orderquery);

			
foreach ($categories as $category)
 {
	if($ordercount>0)
	{
        $q="SELECT  category.*,arrange_system.shift_pos FROM category inner join arrange_system on  category.id=arrange_system.entity_id and arrange_system.user_id='".$id."'
        WHERE category.user_id ='".$id."' and category.catparent='".$index."' and category.status=0  and arrange_system.page_type='c' group by arrange_system.entity_id order by arrange_system.shift_pos asc";
        $sub_categories_q = mysqli_query($conn,$q);
	}else{
		$sub_categories_q = mysqli_query($conn, "SELECT * FROM category WHERE user_id ='".$id."' and catparent='".$index."' and status=0 ");
	}
    while ($row = mysqli_fetch_assoc($sub_categories_q))
    {
		 $sub_catArray[$index][$row['id']] = $row['id'];
    }
	 $index++;						
}

?>

                <div class="grid">
                    <div class="show_ajax_loader" style="display:none;padding: 13px;text-align: center;">
                        <img src="<?php echo $site_url; ?>/ajax-loader-view.gif" alt="Loading.." />
                    </div>
                    <?php
					$products_id_global = [];
                    $subproducts_global = [];
					$search_bar_query = '';
					if($_GET['search_bar'] != ''){
						$searchProduct = addslashes($_GET['search_bar']);
						$search_bar_query = " prd.product_name LIKE '%$searchProduct%' and ";
					}

						$loadCategory = '';
                        $catIndex = $_GET['catindex'];
                        $categoryFirst = implode(",",$sub_catArray[$catIndex]);
                        $loadCategory = " and prd.category_id IN(".$categoryFirst.")";
                   
                    
					if($ordercount>0)
					{
                       
						$q= " select GROUP_CONCAT(sub.id SEPARATOR '^') as subproduct_id,GROUP_CONCAT(sub.name SEPARATOR '^') as subproduct,GROUP_CONCAT(sub.product_price SEPARATOR '^') as subprice, prd.*,arrange_system.shift_pos from products as prd left join sub_products as sub ON sub.product_id = prd.id inner join arrange_system on prd.id=arrange_system.entity_id and arrange_system.user_id = '".$id."' 
						where $search_bar_query prd.user_id='".$id."' AND prd.status=0  and arrange_system.page_type='p' $loadCategory group by arrange_system.entity_id order by arrange_system.shift_pos asc ";
						if($_GET['product_id']){
							#echo '2';exit;
							$q = "	(select GROUP_CONCAT(sub.id SEPARATOR '^') as subproduct_id,GROUP_CONCAT(sub.name SEPARATOR '^') as subproduct,GROUP_CONCAT(sub.product_price SEPARATOR '^') as subprice, prd.*,arrange_system.shift_pos from products as prd left join sub_products as sub ON sub.product_id = prd.id inner join arrange_system on prd.id=arrange_system.entity_id and arrange_system.user_id = '".$id."' 
							where $search_bar_query prd.user_id='".$id."' AND prd.status=0  and arrange_system.page_type='p' $loadCategory group by arrange_system.entity_id order by arrange_system.shift_pos asc) UNION (select GROUP_CONCAT(sub.id SEPARATOR '^') as subproduct_id,GROUP_CONCAT(sub.name SEPARATOR '^') as subproduct,GROUP_CONCAT(sub.product_price SEPARATOR '^') as subprice, prd.*,arrange_system.shift_pos from products as prd left join sub_products as sub ON sub.product_id = prd.id inner join arrange_system on prd.id=arrange_system.entity_id and arrange_system.user_id = '".$id."' 
							where $search_bar_query prd.user_id='".$id."' AND prd.status=0  and arrange_system.page_type='p' and prd.id ='".$_GET['product_id']."' group by arrange_system.entity_id order by arrange_system.shift_pos asc)	";
						}	
					}
					else
					{
						$q="select GROUP_CONCAT(sub.id SEPARATOR '^') as subproduct_id,GROUP_CONCAT(sub.name SEPARATOR '^') as subproduct,GROUP_CONCAT(sub.product_price SEPARATOR '^') as subprice, prd.* from products as prd left join sub_products as sub ON sub.product_id = prd.id where $search_bar_query prd.user_id='$id'  AND prd.status=0 $loadCategory  group by prd.id";
						if($_GET['product_id']){
							$q="(select GROUP_CONCAT(sub.id SEPARATOR '^') as subproduct_id,GROUP_CONCAT(sub.name SEPARATOR '^') as subproduct,GROUP_CONCAT(sub.product_price SEPARATOR '^') as subprice, prd.* from products as prd left join sub_products as sub ON sub.product_id = prd.id where $search_bar_query prd.user_id='$id'  AND prd.status=0 $loadCategory  group by prd.id) UNION (select GROUP_CONCAT(sub.id SEPARATOR '^') as subproduct_id,GROUP_CONCAT(sub.name SEPARATOR '^') as subproduct,GROUP_CONCAT(sub.product_price SEPARATOR '^') as subprice, prd.* from products as prd left join sub_products as sub ON sub.product_id = prd.id where $search_bar_query prd.user_id='$id'  AND prd.status=0 and prd.id ='".$_GET['product_id']."'  group by prd.id)";
						}
					}
					/*
					if($_GET['search_bar'] != ''){
						echo $catIndex;
						echo '<br/>';
						echo '<pre>';
						print_R($sub_catArray[$catIndex]);
						echo $q;exit;
						
					}*/
					
					$total_rows = mysqli_query($conn,$q);  
					
					$rowcountProducts = mysqli_num_rows($total_rows);

                    $subproducts_global = array();
					?>
					
					<?php if($_GET['search_bar'] != ''){?>
					<div class="col-12 col-sm-12 pl-2">
						<div class="mb-2" >
						<?php echo $language['showing_text'];?> 
						<b><?php echo $rowcountProducts;?></b>
						<?php echo $language['result_for'];?>
						"<b><?php echo $searchProduct;?></b>"
						
						<a href="<?php echo urldecode($_GET['redirect']);?>" style="text-decoration:underline;margin-left:10px;"><?php echo $language['show_menu1'];?></a>
						</div>
						
					</div>
					<?php }?>
			
					<?php
                    while ($row=mysqli_fetch_assoc($total_rows)){
						$total_sale = 0;
						array_push($products_id_global, $row['id']);     
						  $result = array();
						 
						 if($row['varient_exit']=="y" && $row['subproduct_id'])
						 {
							$sub_product_arr=explode('^',$row['subproduct']);
							$sub_product_ids=explode('^',$row['subproduct_id']);
							$sub_price_arr=explode('^',$row['subprice']);
							$k=0;
							foreach ($sub_product_ids as $key => $sub_id) {
								if($hike_per!=0)
								{
									$new_sub_price_direct =(($hike_per / 100) * $sub_price_arr[$k])+ $sub_price_arr[$k];
									$new_sub_price=ceiling($new_sub_price_direct,0.05);
								}
								else
								{
									$new_sub_price=$sub_price_arr[$k];
								} 
								$new_sub_price=number_format($new_sub_price, 2);
								 
									if($sub_product_arr[$k] != ''){
										 $item = array( "id" => $sub_id, "name" =>$sub_product_arr[$k], "product_id" => $row['id'],'product_price' => $new_sub_price);
									}
								 	array_push($result, $item);
								$k++;	
							 }
								
							 $res['data'] = $result;
							 array_push($subproducts_global, $res['data']);
						 }
						 else
						 {
							 $res['data'] = [];
							array_push($subproducts_global, $res['data']);
						 }
                        ?>
                        
																

                            <div class="element-item grid-item all-grids productmaindiv_<?php echo $row['id'] ?> prdd_div_<?php echo $row['category_id'];?>  <?php echo $row['category_id'];?>" >
                                <form action="#" method="post" class="set_calss" data-id="<?php echo $row['id'] ?>" data-code="<?php echo $row['product_type'] ?>"  data-pr="<?php echo $row['product_price'] ?>">
                                    <div class="row no-gutters">
                                    <?php 
									$variableA2=$row['product_price'];
									if($hike_per != 0)
									{
										$new_price_direct = (($hike_per / 100) * $variableA2)+$variableA2;
										$new_price=ceiling($new_price_direct,0.05);
									}
									else
									{
										$new_price=$variableA2;
									}
									
									if(!empty($row['image'])) { ?>
                                        <div class="col-md-5 col-sm-5">
                                            <?php if(isActive($row['active_time'])){
                                                if($row['on_stock']){ ?>
                                                    <div class="container_test gallery">
                                                <?php }else{ ?>
                                                    <div class='container_test out_of_stock'>      
                                            <?php } }else{ ?>
                                                <div class='container_test not_available'>      
                                            <?php } ?>
											<?php if($product_zoom){ ?>
											    <?php
											  		$variableA1=str_replace('"','',$row['product_name']);
											        $variableA2=$row['product_price'];
											    ?>
											    <a class="adminDemoVideo" href="https://koofamilies.sirv.com/product/<?php echo $row['image']; ?>?w=650" data-fancybox data-caption="<div class='card' style='text-align:center;'>
                                                    <button data-fancybox-close='' style='float:right;' class='fancybox-button fancybox-button--close' title='Close'><svg xmlns='https://www.w3.org/2000/svg' viewBox='0 0 24 24'><path d='M12 10.6L6.6 5.2 5.2 6.6l5.4 5.4-5.4 5.4 1.4 1.4 5.4-5.4 5.4 5.4 1.4-1.4-5.4-5.4 5.4-5.4-1.4-1.4-5.4 5.4z'></path></svg></button>
                                                            <div class='card-body' style='padding:0.50rem;'> 
                                                                <h5 style='font-weight:bold'><?php echo $variableA1; ?></h5>
                                                        	    <p style='color:black;'><?php echo $language['price'];?>: Rm <?php echo number_format($new_price,2); ?></p>
																<?php if($total_sale > 0){ ?> <p style='color:black;'><?php echo "Total Sale: ".$total_sale; ?></p> <?php } ?>  
																<p class='fancybox_place_order btn btn-danger'>Add to cart</p>
                                                            </div>
                                                        </div>">
                                                    <img src="https://koofamilies.sirv.com/product/<?php echo $row['image']; ?>?w=350" data-src="https://koofamilies.sirv.com/product/<?php echo $row['image']; ?>?w=350"  class="img-fluid lazy" />
                                                </a>
											<?php } else { ?>
											    <img  data-src="https://koofamilies.sirv.com/product/<?php echo $row['image']; ?>?w=350" src="https://koofamilies.sirv.com/product/<?php echo $row['image']; ?>?w=350" class="img-fluid lazy" >
											<?php } ?>
                                                </div>
                                        </div>
                                        <div class="col-md-7 col-sm-7 pl-2">
                                    <?php } else { ?>
                                         <div class="col-md-12 col-sm-12">
                                    <?php } ?>
                                            <input type="hidden" id="id" name="m_id" value="<?php echo $id;?>">
                                            <input type="hidden" id="id" name="p_id" value="<?php echo $row['id'];?>">
                                            <?php if(isActive($row['active_time']) && $row['on_stock']){ ?>     
                                            <a  style="display:none;" role="button" data-rid="<?php echo $row['id']; ?>"  class="pro_status introduce-remarks introduce-remarks_l2 " data-toggle="modal" data-target="#remarks_area" disabled="disabled"></a>
                                          
										   <input type="hidden" name="single_ingredients" value=""/>
                                            <input type="hidden" name="extra" value=""/>   
                                            <?php } ?>
                                            <!--<b style="color:Red"><?php echo $row['category_id'];?></b>-->
                                            <p class="mBt10 product_name_field"><strong><?php echo $row['product_name']; ?></strong>
                                            
											<?php if($product_google_image==1){
														$s_url=urlencode($row['product_name'].",". $row['remark']);
														$s_url='https://www.google.com/search?q='.$s_url.'&tbm=isch';
														
														?>
													&nbsp;&nbsp;<a href="<?php echo $s_url; ?>" target="_blank">
													<i  class="fa fa-camera"></i>   
													</a>
													<?php } ?>
											</p>
                                             <div style="float: left;width:100%">
                                                 
                                                 <p class="mBt10"><?php echo $row['remark']; ?></p>
                                                 <p class="mBt10"><?php echo $language['price'].': Rm'.number_format($new_price,2); ?></p>
                                                 <?php if($total_sale > 0){ ?> <p class="mBt10"><?php echo "Total Sale: ".$total_sale; ?></p> <?php } ?>
                                             </div>  

<?php
$show_pop_cart = 'show';
$only_one_qty1 = 'no'; 
if($_GET['one_product_offer'] == 1 && $row['one_qty_prd_offer'] == 1){
	$show_pop_cart = 'show';
	$only_one_qty1 = 'yes';
	//print_r($_GET);
	foreach($_SESSION['AjaxCartResponse'][$_GET['cart_merchant_number']] as $cart_key1 => $cart_val1){
		if($cart_val1['s_id'] == $row['id']){
			$show_pop_cart = 'hide';
		}
	}
}
?>		
<?php if($only_one_qty1 == 'yes'){?>									 
	<p style="color:red"><?php echo $language['only_one_qty_per_order'];?></p>
<?php }?>
                                            <div class="common_quant ">
											<?php if($row['varient_exit']=="y") { $cart_class="with_varient";} else { $cart_class="without_varient";} ?>
                                            <?php 
											
                                            if(!empty($row['image'])){
                                                if(isActive($row['active_time']) && $row['on_stock']){ ?>
                                                    <p id="product_child_<?php echo $row['id']?>" product_name="<?php echo $row['product_name'];?>" product_remark="<?php echo $row['remark']; ?>" show_pop_cart="<?php echo $show_pop_cart;?>"  prd_one_offer="<?php echo $row['one_qty_prd_offer']; ?>" product_price='<?php echo number_format($new_price,2);?>' style="<?php if($row['add_to_cart_button']=='0'){echo "display:none;";} if($show_pop_cart == 'hide'){echo "display:none;";} ?>" data-rebate='<?php echo $row['product_discount'];?>' class="pro_status text_add_cart <?php echo $cart_class ?>"  data_varient_must='<?php echo $row['varient_must']; ?>' data-id = "<?php echo $row['id'] ?>" data-code = "<?php echo $row['product_type'] ?>"  data-pr = "<?php echo $new_price; ?>" data-name = "<?php echo $row['product_name'] ?>">
												    
													<i  id="child_<?php echo $row['id']?>" class="fa fa-plus"></i></p>
													
                                                    <p class="quantity">
                                                        <input type="hidden" value="1" class="quatity" name="quatity">
                                                    </p>
                                                <?php } }else{
                                                    if(isActive($row['active_time'])){
                                                        if($row['on_stock']){
                                                ?>
                                                    <p  id="product_child_<?php echo $row['id']?>" product_name="<?php echo $row['product_name'];?>" product_remark="<?php echo $row['remark']; ?>" show_pop_cart="<?php echo $show_pop_cart;?>" prd_one_offer="<?php echo $row['one_qty_prd_offer']; ?>" product_price='<?php echo number_format($new_price,2);?>' style="<?php if($row['add_to_cart_button']=='0'){echo "display:none;";}if($show_pop_cart == 'hide'){echo "display:none;";} ?>" class="pro_status text_add_cart <?php echo $cart_class ?>" data-rebate='<?php echo $row['product_discount'];?>' data_varient_must='<?php echo $row['varient_must']; ?>'  data-id = "<?php echo $row['id'] ?>" data-code = "<?php echo $row['product_type'] ?>"  data-pr = "<?php echo $new_price; ?>" data-name = "<?php echo $row['product_name'] ?>">
												    <i  id="child_<?php echo $row['id']?>" class="fa fa-plus"></i></p>
													
                                                    <p class="quantity">
                                                        <input type="hidden" value="1" class="quatity" name="quatity">
                                                    </p>
                                                <?php }else{ ?>
                                                    <div class="out_of_stock">Out of stock</div>
                                                <?php } }else{ ?>
                                                    <div class="out_of_stock">Not available</div>
                                                <?php } } ?>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        <?php  						 
                    }  
                    $subproducts_global = json_encode($subproducts_global);
                    ?>
                    <script type="text/javascript">
                        products_id_global.push(<?php echo implode(",",$products_id_global); ?>);
                        subproducts_global = <?php echo $subproducts_global; ?>;
                    </script>
                    
                </div>   
            

