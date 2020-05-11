<?php

   session_start();
  if($_SESSION['login']=='')
  {
     header('Location: https://koofamilies.com/login.php');
     die;
  }
   include("config.php");

    $total_rows = mysqli_query($conn, "SELECT order_list.*, users.mobile_number FROM order_list inner join users on order_list.user_id = users.id WHERE merchant_id ='".$_SESSION['login']."' ORDER BY `created_on` DESC");

    require_once ("languages/".$_SESSION["langfile"].".php");
?>
<!DOCTYPE html>
<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">

   <head>
      <style>
		    /*.test_product{
		padding-right: 125px!important;
		}*/
		td.products_namess {
    text-transform: lowercase;
}
		  tr {
            border-bottom: 2px solid #efefef;
            }
         .well {
             min-height: 20px;
             padding: 19px;
             margin-bottom: 20px;
             background-color: #fff;
             border: 1px solid #e3e3e3;
             border-radius: 4px;
             -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
             box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
         }
         td {
            border-right: 1px solid #efefef;
		    }
		th {
            border-right: 1px solid #efefef;
        }
        tr.fdfd {
            border-bottom: 3px double #000;
        }
         .pagination {
             display: inline-block;
             padding-left: 0;
             margin: 20px 0;
             border-radius: 4px;
         }
         .pagination>li {
            display: inline;
         }
         .pagination>li:first-child>a, .pagination>li:first-child>span {
             margin-left: 0;
             border-top-left-radius: 4px;
             border-bottom-left-radius: 4px;
         }
         .pagination>li:last-child>a, .pagination>li:last-child>span {
             border-top-right-radius: 4px;
             border-bottom-right-radius: 4px;
         }
         .pagination>li>a, .pagination>li>span {
             position: relative;
             float: left;
             padding: 6px 12px;
             margin-left: -1px;
             line-height: 1.42857143;
             color: #337ab7;
             text-decoration: none;
             background-color: #fff;
             border: 1px solid #ddd;
         }
         .pagination a {
            text-decoration: none !important;
         }
         .pagination>.active>a, .pagination>.active>a:focus, .pagination>.active>a:hover, .pagination>.active>span, .pagination>.active>span:focus, .pagination>.active>span:hover {
             z-index: 3;
             color: #fff;
             cursor: default;
             background-color: #337ab7;
             border-color: #337ab7;
         }
         tr.red {
            color: red;
         }
         label.status {
            cursor: pointer;
         }
         td {
            border-right: 2px solid #efefef;
		    }
		 th {
            border-right: 2px solid #efefef;
        }
         .gr{
            color:green;
         }
         .or{
             color: orange;
         }
         .red.gr{
            color:green;
         }
         .product_name{
		     width: 100%;
		 }
		 .total_order{
		    font-weight:bold;
		 }
		 p.pop_upss {
    display: inline-block;
}
.location_head{
width:200px;
}
.new_tablee {
    width: 200px!important;
    display: block;
    word-break: break-word;
}
td.test_productss {
    width: 200px!important;
    display: block;
}
th.product_name.test_product {
    width: 200px!important;
}

@media only screen and (max-width: 600px) and (min-width: 300px){
table.table.table-striped {
    white-space: unset!important;
}
}


      </style>
  
      <?php include("includes1/head.php"); ?>
   </head>
   <body class="header-light sidebar-dark sidebar-expand pace-done">
      <div id="wrapper" class="wrapper">
      <!-- HEADER & TOP NAVIGATION -->
      <?php include("includes1/navbar.php"); ?>
      <!-- /.navbar -->
      <div class="content-wrapper">
      <!-- SIDEBAR -->
      <?php include("includes1/sidebar.php"); ?>
      <!-- /.site-sidebar -->
      <main class="main-wrapper clearfix" style="min-height: 522px;">
         <div class="row" id="main-content" style="padding-top:25px">
         <div class="well">
            <h3><?php echo $language['order_list'];?></h3>
            <?php
               $dt = new DateTime();
               $today =  $dt->format('Y-m-d');
            ?>
            <table class="table table-striped">
               <thead>
                  <tr>
                      <th><?php echo $language["items"];?></th>
                     <th><?php echo $language["date_of_order"];?></th>
                     <th>Username</th>
					 <th><?php echo $language["status"];?></th>
					 <th>Kitchen</th>
					  <th><?php echo $language["chat"];?></th>
					   <th><?php echo $language["print"];?></th>
					     <th><?php echo $language["table_number"];?></th>
						   <th><?php echo $language["product_code"];?></th>
						     <th class="product_name test_product"><?php echo $language["product_name"];?></th>
						  <th class="product_name test_product"><?php echo $language["remark"];?></th>
						   <th><?php echo $language["quantity"];?></th>
						      <th><?php echo $language["amount"];?></th>
							   <th><?php echo $language["total"];?></th>
							     <th><?php echo $language["mode_of_payment"];?></th>
								  <th class="location_head"><?php echo $language["location"];?></th>
								    <th>Delivery <br> Service</th>
                     <th>Phone</th>
                     <th>K1/K2</th>     
                   
                  </tr>
               </thead>
               <?php
                  $i =1;

                  while ($row=mysqli_fetch_assoc($total_rows)){
                      $product_ids = explode(",",$row['product_id']);
                      $quantity_ids = explode(",",$row['quantity']);
                      $amount_val = explode(",",$row['amount']);
                      $product_code = explode(",",$row['product_code']);
                      $amount_data = array_combine($product_ids, $amount_val);
					  $total_data = array_combine($quantity_ids, $amount_val);
                        

                      $created =$row['created_on'];
                      $remark_ids = explode("|",$row['remark']);
                      $new_time = explode(" ",$created);
                      //$c = array_combine($product_ids, $quantity_ids);
                      $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id ='".$row['product_id']."'"));

                      $user_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id ='".$row['user_id']."'"));

                      $id_row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id ='".$row['id']."'"));

                      $merchant_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id ='".$row['merchant_id']."'"));
                      $date=date_create($created);
                  ?>
               <tbody>
                  <?php
                     if($row['status'] == 1) $callss = "gr";
                     else if($row['status'] == 2) $callss = "or";
                     else $callss = " ";
                     $todayorder = $today == $new_time[0] ? "red" : "";

                     $i1 =1;  ?>
                  <tr class="<?php echo $todayorder; ?> fdfd <?php echo $callss; ?>" data-id="<?php echo $row['id']; ?>">
                     <input type="hidden" class="merchant_<?php echo $row['id'];?>" value="<?php echo $merchant_name['name'];?>">
                     <input type="hidden" class="userphone_<?php echo $row['id'];?>" value="<?php echo $user_name['mobile_number'];?>" >
                     <input type="hidden" class="merchantphone_<?php echo $row['id'];?>" value="<?php echo $merchant_name['mobile_number'];?>" >
                     <input type="hidden" class="merchantaddress_<?php echo $row['id'];?>" value="<?php echo $merchant_name['google_map'];?>" >
                     <td><?php echo  $i ?></td>
                     <td><?php echo date_format($date,"Y/m/d");  ?>
                      <?php echo '<br>'; echo $new_time[1] ?>
                     </td>
                     <td class="username_<?php echo $row['id'];?>"><?php echo $user_name['name']; ?></td>
					 
                      <td>
                          <?php 
                            if($row['status'] == 0) $sta = "Pending";
                            else if($row['status'] == 1) $sta = "Done";
                            else $sta = "Accepted";
                          ?>
                        <label class= "status" status="<?php echo $row['status'];?>" data-id="<?php echo $row['id']; ?>"> <?php echo $sta; ?></label>
                     </td>
					  <td></td>
                     <td><a target="_blank" href="http://koofamilies.com/chat/chat.php?sender=<?php echo $_SESSION['login']?>&receiver=<?php echo $row['user_id'];?>"><i class="fa fa-comments-o" style="font-size:25px;"></i></a></td>
                        <td>  
                         <?php if($row['status'] == 2){?>
                       <a target="_blank" href="print_kitchen.php?id=<?php echo $row['id'];?>&merchant=<?php echo $_SESSION['login']?>">Print</a><
                         <?php }?>
						</td>
                     
					  
					  <td class="table_number_<?php echo $row['id']?>"><?php echo $row['table_type'];?></td>
					    <td>
                        <?php
                           foreach ($product_code as $key)
                           {
                           //$product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id ='".$key."'"));
                           echo $key.'<br>'; }
                           ?>
                     </td>
						<td class="products_namess product_name_<?php echo $row['id'];?> test_productss" ><?php foreach ($product_ids as $key )
                        {
							if(is_numeric($key))
							{
                         $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id ='".$key."'"));

                         echo $product['product_name'].'<br>';
					 }
					 else
					 {
						 echo $key.'<br>';
					 }
                         }
                         ?>
                    </td>
					  <td><?php   foreach ($remark_ids as $vall)
                        {

                        		echo $vall.'<br>';

                        } ?></td>
					 <td class="quantity_<?php echo $row['id'];?>"><?php
                        foreach ($quantity_ids as $key)
                        {
                        echo $key;
                        echo '<br>';
                        }
                          ?></td>
					 <td class="amount_<?php echo $row['id'];?>">
	                    <?php
                            $q_id = 0;
                            foreach ($amount_data as $key => $value){
								 $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id ='".$key."'"));
								 if($value == '0') { ?>
							        <p class="pop_upss" data-id="<?php echo $row['id']; ?>"  data-prodid="<?php echo $key; ?>"> <i class="fa fa-pencil-square-o" aria-hidden="true"></i></p>
                        	    <?php  }

                                echo $quantity_ids[$q_id] * $value.'<br>';
                                $q_id++;
                           } ?>
                     </td>
					 
					   <td class="total_order total_<?php echo $row['id']?>">
                        <?php
                            $total = 0;

                            foreach ($total_data as $key => $valus){
                                $total =  $total + ($key *$valus );
                            }
                            echo  $total;
                           ?>
                     </td> 
					 <td><?php echo $row['wallet'];  ?></td>
					 	<td class="location_<?php echo $row['id']; ?> new_tablee"><?php echo $row['location'];?></td>
							<td><a onclick="copy_orderDetail(<?php echo $row['id']?>)" href="#" class="delivery" id="<?php echo $row['id'];?>"><i class="fa fa-truck" style="font-size:25px;"></i></button></td>
                 
						
						 <?php if($user_name['number_lock'] == 0){?>
                        <td><?php echo $user_name['mobile_number']; ?></td>
                     <?php }else {?>
                        <td></td>
                     <?php }?>
					 <td><?php echo $user_name['account_type']; ?></td>
                    
                  </tr>
                  <?php   $i++; }
                      ?>
               </tbody>
            </table>

            <div style="margin:0px auto;">
               <ul class="pagination">
                  <?php
                     for($i = 1; $i <= $total_page_num; $i++)
                     {
                      if($i == $page)
                      {
                       $active = "class='active'";
                      }
                      else
                      {
                       $active = "";
                      }
                      echo "<li $active><a href='?page=$i'>$i</a></li>";
                     }
                     ?>
               </ul>
            </div>
            <div>
                <div class="modal fade" id="myModal" role="dialog">
                    <div class="modal-dialog">
                      <!-- Modal content-->
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                          <h4 class="modal-title">Edit Amount</h4>
                        </div>
                        <div class="modal-body" style="padding-bottom:0px;">

                		 <div class="col-sm-10">
                      <form id ="data">
                      <div class="form-group">
                      <label>Amount</label>
                		 	<input type="text" name="amount" id = "amount" class="form-control" value="" required>
                       <input type="hidden" id="id" name="id" value="">
                       <input type="hidden" id="p_id" name="p_id" value="">
                      </div>
                        </div>
                		</div>
                        <div class="modal-footer" style="padding-bottom:2px;">
                			<button>Submit</button>
                        </div>
                      </form>
                      </div>
                 <div class="modal fade" id="myModal" role="dialog" >
                    <div class="modal-dialog modal-lg">
                      <div class="modal-content" id="modalcontent">
                       <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
      </main>
      </div>
      <!-- /.widget-body badge -->
      </div>




      <!-- /.widget-bg -->
      <!-- /.content-wrapper -->
      <?php include("includes1/footer.php"); ?>
   </body>
</html>
<script type="text/javascript">
function copy_orderDetail(id){
    var detailContent = "";
    var username = $("username");
    var dummy = document.createElement("input");
    document.body.appendChild(dummy);
    var product_name = $(".product_name_" + id).html().split("<br>");
    var product_qty = $(".quantity_"+id).html().split("<br>");
    var product_amount = $(".amount_"+id).html().split("<br>");
    dummy.setAttribute("id", "dummy_id");
    var detail = "User Name: " + $(".username_" + id).html() +" ";
    detail += "User Phone: " + $(".userphone_" + id).val() + " ";
    detail += "Merchant Name: " + $(".merchant_" + id).val() + " ";
    detail += "Merchant Phone: " + $(".merchantphone_" + id).val() + " ";
    detail += "Merchant Address: " + $(".merchantaddress_" + id).val() + " ";
    
    for(var i = 0; i < product_name.length - 1; i++){
        detail += "Product Name: " + product_name[i] + " ";
        detail += "Quantity: " + product_qty[i] + " ";
        var amount = product_amount[i].trim();
        if(product_amount[i].indexOf("class") > -1){
            amount = 0;
        }
        detail += "Amount: " + amount + " ";
    }
    detail += "Total: " + $(".total_" + id).html().trim() + "   ";
    detail += "Table Number: " + $(".table_number_" + id).html().trim() + "   ";
    detail += "Location: " + $(".location_" + id).html().trim() + "   ";
    document.getElementById("dummy_id").value= detail;

    dummy.select();

    document.execCommand("copy");
            
    alert("Send Delivery Service to Admin!");
}

   $(document).ready(function(){

   	$(".status").click(function(){
       	var data_id = $(this).attr("data-id");
       	var status = $(this).attr("status");
       	if(status == 0){
           	$.ajax({
           		url : 'update_status.php',
           		type: 'POST',
           		data: {id:data_id, status: 2},
           		success:function(data){
           		    //~ alert(1);
           		   location.reload();
           		 }
            });
       	}
       	if(status == 2){
           	$.ajax({
           		url : 'update_status.php',
           		type: 'POST',
           		data: {id:data_id, status: 1},
           		success:function(data){
           		    //~ alert(1);
           		   location.reload();
           		 }
            });
       	}
     });   
     /*adding new update */
    $(".pop_upss").click(function(){
	    $("#myModal").modal("show");
	    var dataid=$(this).data("id");
	    var prodid=$(this).data("prodid");
        $("#id").val(dataid);
        $("#p_id").val(prodid);
	});

    $("form#data").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: 'update_amount.php',
            type: 'POST',
            data: formData,
            success: function (data) {
    			//alert(data);
            location.reload();
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });

});
</script>
<script> window.setInterval('refresh()', 60000); 
 function refresh() {
	  window.location.reload();
	  } 
 </script> 