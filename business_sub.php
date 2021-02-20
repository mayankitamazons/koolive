<?php
$self_id=$_SESSION['login'];
if (empty($_SESSION["langfile"])) { $_SESSION["langfile"] = "english"; }
   require_once ("languages/".$_SESSION["langfile"].".php"); 
?>
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



<div class="col-md-12 merchant-layout-2">   

   <?php if($ppartner>0){ ?>

   <div class="row no-gutters">

      <?php if($_POST['tags_merchant']=='') { ?>

       <div class="col-4 col-sm-3">
         <div class="sub_category_grid">
            <div class="all category_filter">
                   <button class="btn btn-primary" type="button" data-filter=".all" data-subcategory='all'>All</button>
            </div>
            <?php
                $query = "select * from service where status=1";
                $exe = mysqli_query($conn , $query);
                if(mysqli_num_rows($exe)>0)
                {   $value='';
                    while($row=mysqli_fetch_assoc($exe)) {
                ?>
                 <div class="<?php echo $row['id']; ?> category_filter">
                   <button class="btn btn-primary" type="button" data-filter=".<?php echo $row['id'];?>" data-subcategory='<?php echo $row['id']; ?>'>
                    <?php echo $row['short_name'];?></button>
                </div>       
                <?php }} ?>
			</div>
		</div> 

	  <?php } ?>

      <div class="col-8 col-sm-8 pl-2">

	     

         <div class="grid">

            <!-- <div class="element-item grid-item  <?php echo 0;?>" > -->

               <?php 

                  $totalp=0;

                  while($prow = mysqli_fetch_assoc($u_query))

                  {

					  $b_s=$prow['business1'];

                    ?>

                     <div class=" all element-item grid-item  <?php echo $prow['service_id'];?>" >

                <?php    $totalp++;

                  ?>

                       <div class="bg-gray restaurant-entry">

                          <div class="row">

                             <div class="col-sm-12 col-md-12 col-lg-8 text-xs-center text-sm-left">

                                <?php if(!empty($prow['image'])){ ?>

                                <div class="entry-logo">

                                   <a class="img-fluid" href="#">

                                   <img alt="Food logo" style="max-width: 100%;display: block;" src="<?php echo $site_url; ?>/images/about_images/<?php echo $prow['image'];  ?>"></a>

                                </div>

                                <?php } ?>

                                <!-- end:Logo -->

                                <div class="entry-dscr" style="<?php if($prow['image']==''){ echo "padding-left:0px;";} ?>">

                                   <h5 ><a style="font-weight:bold;" href="structure_merchant.php?merchant_id=<?php echo $prow['id'];?>">

                                      <?php echo $prow['name']; ?></a>

									   <a href="https://api.whatsapp.com/send?phone=<?php  echo $prow['mobile_number']?>" target="_blank">

                                         <img src="images/whatapp.png" style="max-width:40px;"/>

                                         </a>

                                   </h5>

                                   <!--span>Burgers, American, Sandwiches, Fast Food, BBQ,urgers, American, Sandwiches

                                      </span!-->

                                    <ul class="list-inline">
                                       <?php if($prow['business_nature']){ ?>
                                      <li class="list-inline-item">
                                        <?php echo $prow['business_nature']; ?>
                                      </li>
									 <?php } ?>
                                      </br>
                                     <?php if($latitude && $longitude){ ?>
                                     
									 <li class="list-inline-item"> <?php echo number_format($prow['distance'],2)." KM"; ?></li> <?php } ?>
                                   </ul>

                                </div>

                                <!-- end:Entry description -->

                             </div>

                             <div class="col-sm-12 col-md-12 col-lg-4 text-xs-center">

                                <div class="right-content bg-white">

                                   <div class="right-review" style="padding: 15px 10px;min-height: 145px;text-align:center;">

                                      <!--div class="rating-block" style="color: #f30;background: transparent;border: none;padding: 5px 15px 5px;"> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star-o"></i> </div>

                                         <p> 245 Reviews</p!--> 
                                         
                                          <?php if($prow['coin_user_id']==NULL && $self_id!=$prow['id']) {?>
                                      <span  class="btn theme-btn-dash set_limit" merchant_id="<?php echo $prow['id']; ?>" style=""><?php echo $language['set_limit'];?></span> 
									  </br>




									  </br>
                                          <?php }?>

                                      <!--a href="structure_merchant.php?merchant_id=<?php echo $prow['id'];?>" class="btn theme-btn-dash" style=""><?php echo $language['information'];?></a!--> 

                                   </div>

                                </div>

                                <!-- end:right info -->

                             </div>

                          </div>

                          <!--end:row -->

                       </div>

               <?php echo '</div>'; } ?>

               <?php if($totalp==0){ echo "<p>No Merchant Found</p>";}?>

            

            

            <!-- -->

            </div>    

         </div>

      </div>

   <?php } else { echo "<p>No Merchant Found</p>";} ?>

   </div>

</div>
<script>
$(".save_limit").click(function(e) { 
   var user_id=$('#user_id').val();
   var merchant_id=$('#merchant_id').val();
   var coin_max_limit=$('#coin_max_limit').val();
   var limit_class=$('#limit_class').val();
   if(merchant_id && user_id && coin_max_limit)
   {
	   	$.ajax({
             url:"functions.php",
             type:"post",
             data:{method:"savelimit",user_id:user_id,merchant_id:merchant_id,coin_max_limit:coin_max_limit,limit_class:limit_class},
             dataType:'json',
             success:function(response){
				 var data = JSON.parse(JSON.stringify(response));
					if(data.status==true)
					{
						
					  $('#LimitModel').modal('hide'); 
					  var msg="Merchant Updated";
					  $('#show_msg').html(msg);
					  $('#AlerModel').modal('show'); 
					  setTimeout(function(){ 
						//$("#AlerModel").modal("hide"); 
						location.reload();
					  },3000); 
					}
					else
					{
						// alert(data.msg);  
						 $('#show_msg').html(data.msg);
					  $('#AlerModel').modal('show');
						setTimeout(function(){ 
						$("#AlerModel").modal("hide"); 
					  },3000); 					  
					}
			 }
		});
   }
   else
   {
	   var msg="Required Field Missed";
		$('#show_msg').html(msg);
		 $('#AlerModel').modal('show'); 
   }
}); 
$(".set_limit").click(function() {  
		var user_id= $(this).attr("merchant_id");
		$('#merchant_id').val(user_id);
		$('#LimitModel').modal('show'); 
	});
</script>

