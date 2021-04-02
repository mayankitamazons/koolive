<?php
include('config.php');
if($_POST)
{
	extract($_POST);
	$merchant_id=$_SESSION['login'];
	$order_by="ORDER BY Distance asc";
	$cond='';
  if($sort_by=="name")
	  $order_by="ORDER BY name asc";
  if($tags_merchant)
  {
	  $cond=" and name='$tags_merchant' ";
  }
  if($latitude=='')
  {
	 $order_by="ORDER BY name asc";  
  }
 
  if($latitude && $longitude)
  {
	$puery=" SELECT users.*,about.image,users.id, 
      (6371 * ACOS ( COS ( RADIANS (".$_POST['latitude'].")) * COS ( RADIANS(users.latitude)) * COS(RADIANS(users.longitude) - RADIANS(".$_POST['longitude']."))
       + SIN(RADIANS(".$_POST['latitude'].")) * SIN(RADIANS(users.latitude)))) AS distance,unrecoginize_coin.user_id  as coin_user_id
    FROM users
    left join about on about.userid=users.id  left join unrecoginize_coin on users.id=unrecoginize_coin.merchant_id and unrecoginize_coin.user_id='$merchant_id' 
    where  users.user_roles=2 and users.service_id!=0 and users.isLocked=0 and users.name!='' and users.show_business='1' and users.latitude!='' and users.longitude!='' $cond group by users.id $order_by limit 0,20";  
  }  
  else
  {
	 $puery="SELECT users.*,about.image,users.id,unrecoginize_coin.user_id as coin_user_id  FROM users left join about on about.userid=users.id  left join unrecoginize_coin on users.id=unrecoginize_coin.merchant_id and unrecoginize_coin.user_id='$merchant_id'  where users.user_roles=2 
    and users.service_id!=0 and users.isLocked=0 and users.name!='' and users.show_business='1'" . $cond ." group by users.id $order_by";    
  }
    // echo $puery;
    $u_query = mysqli_query($conn,$puery);

   $ppartner=mysqli_num_rows($u_query);	
	include('business_sub.php');
}


?>
<script>

 

// init Isotope

var $grid_sub = $('.sub_category_grid').isotope({

    // options

    layoutMode: 'fitRows'

});

var $grid = $('.grid').isotope({

  // options

});



// filter items on button click

$('.master_category_filter').on( 'click', function(e) {

    e.preventDefault();

	$('.master_category_filter').removeClass("active_menu");

    $(this).addClass("active_menu");

    var filterValue = $(this).attr('data-filter');

    $grid_sub.on( 'arrangeComplete', function ( event, filteredItems) {

        console.log(event, filteredItems);

        $(filteredItems[0].element).find('button').trigger('click');

        console.log('am called');

    });

    $grid_sub.isotope({ filter: filterValue });

 

        var filterValue = $(this).attr('data-filter');

        var position_value = $(this).attr('data-position');

    

  

});

$('.sub_category_grid .category_filter button').on( 'click',function() {

      var filterValue = $(this).attr('data-filter');

      var subcateg_show = $(this).data("subcategory");

	   $('.sub_category_grid .category_filter button').removeClass("active_menu");

		$(this).addClass("active_menu");

      console.log(filterValue);

      console.log(subcateg_show);

      $("#remarks_area .modal-body .btn-group").each(function(){

        if($(this).data("subcategory") == subcateg_show || $(this).data("subcategory") == "all"){

          $(this).show();

        }else{

          $(this).hide();

        }

      });

      $grid.isotope({ filter: filterValue });

});

</script>