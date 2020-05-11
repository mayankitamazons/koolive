<style type="text/css">
    .parent-category-menu {
        background-color: #fff;
        padding-top: 6px;
        padding-bottom: 6px;
        -webkit-box-shadow: 0px 3px 8px 0px rgba(82, 63, 105, 0.15);
        box-shadow: 0px 3px 8px 0px rgba(82, 63, 105, 0.15);
        position: relative;
    }
    .parent-category-menu a {
        padding: 8px 18px 8px 18px;
        display: inline-block;
        vertical-align: top;
        line-height: normal;
        font-size: 14px;
        color: #4a5368;
        font-weight: 600;
        background-color: transparent;
        border: 0px;
        box-shadow: none;
    }
    .merchant-layout-2 .sub_category_grid{
        background: #e9ebf1;
        margin-top: 0;
    }
    .merchant-layout-2 .sub_category_grid .category_filter{
        margin-right: 0px;
        width: 100%;
        border-bottom: 1px solid rgba(84, 92, 115, 0.14);
    }
    .merchant-layout-2 .sub_category_grid button{
        width: 100%;
        display: block;
        background-color: transparent;
        border: 0;
        color: #4a5368;
        border-radius: 0px;
        box-shadow: none;
        white-space: normal;
        text-align: left;
    }
    .merchant-layout-2 .text_add_cart, .modal-footer .text_add_cart{
        background-color:red;
        width: 30px;
        height: 30px;
        font-size: 16px;
        border-radius: 100%;
        text-align: center;
        line-height: normal;
        padding: 6px 0 0 0;
        margin: 0;
        display: inline-block;
        vertical-align: top;
    }
    .merchant-layout-2 .common_quant{
        display: block;
        text-align: right;
    }
    .merchant-layout-2 .grid .grid-item{
        background-color: #ffffff;
        padding: 15px;
        -webkit-box-shadow: 0px 0px 13px 0px rgba(82, 63, 105, 0.05);
        box-shadow: 0px 0px 13px 0px rgba(82, 63, 105, 0.05);
        margin-bottom: 15px;
        width: 100%;
    }
    .element-item .introduce-remarks{
        font-size: .8em;
        position: absolute;
        z-index: 10;
        bottom: 0;
        top: 33px;
        right: 6vw;
        width: 5em;
        height: 30px;
        border-radius: 10px;
        box-sizing: border-box;
        padding: 0;
        display: grid;
        align-content: center;
    }
    @media (max-width: 767px) {
        .product_name_field{
            min-height: 3em;
        }
        .parent-category-menu a{
            padding: 8px 12px 8px 12px;
            width: 24%;
        }
        .main-wrapper {
            padding: 0 0 0 15px;
        }
        .merchant-layout-2 .sub_category_grid button {
            font-size: 12px;
        }
        .merchant-layout-2 .sub_category_grid .category_filter {
            padding: 6px 4px;
        }
        .merchant-layout-2 .grid .grid-item{
            padding: 8px;
        }
        .element-item .introduce-remarks{
            position: absolute;
            font-size: .8em;
            bottom: 5px;
            right: 35px;
            margin: 0;
            top: auto;
        }
        .element-item .row .col-12:nth-child(1) .introduce-remarks{
            right: 35px;
            bottom: 5px;
            left: auto;
        }
    }
    @media (max-width: 480px) and (min-width: 315px){
        .wrapper{
            width: 100%;
        }
    }
	@media (min-width: 320px) and (max-width: 991px) {
		.restaurant-entry .entry-logo {
			float: none;
			text-align: center;
			margin: 10px auto;
		}
		.restaurant-entry .entry-dscr {
			padding: 0 15px;
			text-align: center;
		}
		.restaurant-entry .right-review {
			padding: 15px 10px;
			border-left: transparent;
			
		}
	}

</style>
<?php
  $puery="select about.image,unrecoginize_coin.*,users.name as merchant_name,users.mobile_number,users.special_coin_name from 

 unrecoginize_coin inner join users on users.id=unrecoginize_coin.user_id left join about on unrecoginize_coin.user_id=about.userid where unrecoginize_coin.merchant_id='$merchant_id' and status=1 group by users.id order by users.name asc ";

	$u_query = mysqli_query($conn,$puery);
	$ppartner=mysqli_num_rows($u_query);

?>
<div class="col-md-12 merchant-layout-2">
        <div class="filter-button-group parent-category-menu">
		 </div>
		<div class="row no-gutters">
            <!--div class="col-4 col-sm-3">
                <div class="sub_category_grid">
					<?php
					// $categories=array('All','Foods and Beverage','Motor Vehicle','Hardware','Grocery Shop','Clothes','Business to Business');
					$categories=array();
                     $index = 0;
					foreach ($categories as $category)
                    {
						?>
				   <div class="<?php echo $index; ?> category_filter">
                            <button class="btn btn-primary" type="button" data-filter=".<?php echo $index;?>" data-subcategory='<?php echo $index; ?>'><?php echo str_replace("-", " ", $category);?></button>
                            </div>
					<?php $index++; } ?>
				</div>
			</div!-->
			<div class="col-12 col-sm-12 pl-2">
                <div class="grid">
					 <div class="element-item grid-item  <?php echo 0;?>" >
					    <?php 
								$totalp=0;
								while($prow = mysqli_fetch_assoc($u_query))
								{
									$partner_merchant_id=$prow['merchant_id'];
									$u_merchant_id=$prow['user_id'];
									 $totalcoin="SELECT sum(amount) as totalamount FROM tranfer WHERE MONTH(created_date) = MONTH(CURRENT_DATE()) AND YEAR(created_date) = YEAR(CURRENT_DATE()) and receiver_id='$u_merchant_id' and coin_merchant_id='$partner_merchant_id'";
									
									$acceptedcoin = mysqli_fetch_assoc(mysqli_query($conn,$totalcoin));
									$totalamount=$acceptedcoin['totalamount'];
									$coin_max_limit=$prow['coin_max_limit'];
									$pending_limit=$prow['coin_max_limit']-$acceptedcoin['totalamount'];
									if($pending_limit>0)
									{
										$totalp++;
								?>
					  <div class="bg-gray restaurant-entry">
                                <div class="row">
                                    <div class="col-sm-12 col-md-12 col-lg-8 text-xs-center text-sm-left">
                                         <?php if(!empty($prow['image'])){ ?>
										<div class="entry-logo">
                                            <a class="img-fluid" href="structure_merchant.php?merchant_id=<?php echo $prow['user_id'];?>">
											 
											<img alt="Food logo" style="max-width: 100%;display: block;" src="<?php echo $site_url; ?>/images/about_images/<?php echo $prow['image'];  ?>"></a>
                                        </div>
										 <?php } ?>
                                        <!-- end:Logo -->
                                        <div class="entry-dscr" style="<?php if($prow['image']==''){ echo "padding-left:0px;";} ?>">
                                            <h5 ><a style="font-weight:bold;" href="structure_merchant.php?merchant_id=<?php echo $prow['user_id'];?>">
											<?php echo $prow['merchant_name']; ?></a></h5>
											<!--span>Burgers, American, Sandwiches, Fast Food, BBQ,urgers, American, Sandwiches
											</span!-->
                                            <ul class="list-inline">
                                                <!--li class="list-inline-item">
												   <a href="https://api.whatsapp.com/send?phone=<?php  echo $prow['mobile_number']?>" target="_blank">Chat with <?php echo $prow['merchant_name'] ?>
								 <img src="images/whatapp.png" style="max-width:40px;"/>
								 </a>
												</li!-->
											
												<?php if($pending_limit>0){ ?>
                                                <li class="list-inline-item"><i class="fa fa-check"></i>Limit left: <span style="color:red;"><?php echo $pending_limit; ?></span></li>
												<?php } ?>
													<!--li class="list-inline-item"><i class="fa fa-motorcycle"></i> 30 min</li!-->
                                            </ul>
                                        </div>
                                        <!-- end:Entry description -->
                                    </div>
                                    <!--div class="col-sm-12 col-md-12 col-lg-4 text-xs-center">
                                        <div class="right-content bg-white">
                                            <div class="right-review" style="padding: 15px 10px;min-height: 145px;text-align:center;">
                                                <!--div class="rating-block" style="color: #f30;background: transparent;border: none;padding: 5px 15px 5px;"> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star-o"></i> </div>
                                                
												<?php if($pending_limit>0){ ?>
												<span class="btn theme-btn-dash" onclick='transfer("<?php echo $_SESSION['login'];?>","<?php echo $prow['mobile_number']?>","<?php echo $prow['merchant_name']?>")' style="">Transfer Now</span> <?php } ?> 
												<br/>
												<br/>
												<a href="structure_merchant.php?merchant_id=<?php echo $prow['user_id'];?>" class="btn theme-btn-dash" style="">About us</a> 
												</div>
                                        </div>
                                       
                                    </div!-->
                                </div>
                                <!--end:row -->
                        </div>
									<?php }} ?>
						
						 <?php if($totalp==0){ echo "<p>No Partner Found,Try another merchant</p>";}?>
						  
					 </div>
					  
				
				</div>
			</div>
		</div>
</div>  