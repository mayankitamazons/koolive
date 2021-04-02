
   <div class="modal fade" id="ProductModel" role="dialog">

        <div class="modal-dialog modal-dialog-centered">

            <!-- Modal content-->

            <div class="modal-content">

                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                    <h4 class="modal-title">Product Varieties For <br/> <span id="varient_name" style="font-weight:bold;"></span></h4>

                    

                    <p id="varient" style="display:none;"></p>

                    <p id="varient_type" style="display:none;"></p>

                                    </div>

                                    <form id ="data">

                                        <div class="modal-body product_data" style="padding-bottom:0px;max-height:50vh;overflow-x: auto;">

                     <p id="varient_error" style="color:red;display:none;">Please select at least one choice. Thank</p>

                      

                       <div id="product_main" class="ingredients_container">

                         

                       </div>

                      

                      <div class="product_extra">

                      <input id="p_pop_price"  type="hidden"/>

                      <table border="1px solid" style="width:80%;color:black;">

                      <tr><td> Product Name </td><td> Rm </td></tr>

                       <tbody id="product_table">

                          

                       </tbody>

                        

                        <tr><td> <b> Total : </b></td><td id="pr_total"></td></tr>

                        <tbody><tr><td>Remarks</td><td id="remark_td"></td></tr></tbody>

                      </table>

                       <br/>

                      

                        <!--p id="pr_total"></p!-->

                        

                      </div>

                      

                    

                                        </div>

                                        <div style="margin: 10px 0 10px 34%;"  class="modal-footer product_button pop_model">

                        

                                         

                                        </div>

                    <br/>

                                    </form>

                                </div>

                            </div>

                        </div>
	<div class="modal fade" id="free_trial_model" role="dialog">

					<div class="modal-dialog">

					 



						<!-- Modal content-->

						<div class="modal-content">

						 

							<div class="modal-header">

								<button type="button" class="close" data-dismiss="modal">&times;</button>

								<h4 class="modal-title"></h4>

							</div>

							 

								<div class="modal-body" style="padding-bottom:0px;">

									<div class="col-md-12" style="text-align: center;">

									  <h5><?php echo $language['free_trial']; ?></h5>

									   <button type="button"  class="btn btn-primary" id="verifybutton" onclick="verifiedmobile()">Verify Now</button>

									   <span class='alert' id="resend_link_label" style="display:none;">Resend Link Shared to mobile Number</span>

									 <input type="hidden" id="verifiedmobile"/>

									</div>

								</div>

								<div class="modal-footer" style="padding-bottom:2px;">

								

								</div>

						   

						</div>

					</div>

			</div>
	<div class="modal fade" id="show_new_label" role="dialog">

					<div class="modal-dialog">

					 



						<!-- Modal content-->

						<div class="modal-content">

						 

							<div class="modal-header">

								<button type="button" class="close" data-dismiss="modal">&times;</button>

								<h4 class="modal-title"></h4>

							</div>

							 

								<div class="modal-body" style="padding-bottom:0px;">

									<div class="col-md-12" style="text-align: center;">

									  <h5>Same Order within 5 min is not allowed </h5>

									 

									</div>

								</div>

								<div class="modal-footer" style="padding-bottom:2px;">

								

								</div>

						   

						</div>

					</div>

			</div>

			

			 

			

            <div class="modal fade" id="location_model" role="dialog">

        <div class="modal-dialog">

         



            <!-- Modal content-->

            <div class="modal-content">

             

                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                    <h4 class="modal-title"></h4>

                </div>

                 

                    <div class="modal-body" style="padding-bottom:0px;">

                        <div class="col-md-12" style="text-align: center;">

                          <h5>This merchant require your permission for location in order to place order, </h5>

                         <button type="button" class="btn btn-primary" onclick="clearhistory()">How to clear Cache</button>

                

                        </div>

                    </div>

                    <div class="modal-footer" style="padding-bottom:2px;">

                    

                    </div>

               

            </div>

        </div>

 </div>

 <div class="modal fade" id="clear_history_model" role="dialog">

        <div class="modal-dialog">

         



            <!-- Modal content-->

            <div class="modal-content">

             

                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                    <h4 class="modal-title"></h4>

                </div>

                 <div class="modal-body" style="padding-bottom:0px;padding: 0px;">

                        <div class="col-md-12" style="text-align: center;">

                          

									<h4 class="">

										How to clear your app cache data?</h4>

									    <div style="text-align:left;">

										<p>Please follow these steps below:-</p>



										<ul style="margin-left: 0px;padding: 0px;">

											<li><strong>Mobile Browser</strong>:-



											<ul style="list-style-type: lower-alpha;">

												<li>Click on the left hand side Lock icon in address bar.</li>

												<li>Select Site Settings</li>

												

												<li>Go to Permission and Select Location Permission </li>

												<li>Allow that and press back setting Button</li>

												

											</ul>  

											<li><strong>Web Desktop</strong>:-



											<ul style="list-style-type: lower-alpha;">

												<li>Click on the left end hand Lock icon in address bar.</li>

												<li>Select Site Settings or Clear Cookies and Site Data.. </li>

												

												<li>Remove Cahce and Cookies for KooFamiles</li>

												<li>Click ok, after that it will again permission allow now</li>

											</ul>

											

											<li><strong>Android App</strong>:-



											<ul style="list-style-type: lower-alpha;">

												<li>Go to ‘Settings’ and tap on ‘Apps’.</li>

												<li>Select KooFamiles</li>

												<li>On the ‘App Info’ interface, tap on ‘Storage’</li>

												<li>Clear your cache by tapping on the ‘Clear Cache’ button</li>

											</ul>

											</li>

											<!--li><strong>iOS</strong>:-<br>
											There is no manual way to clear app cache data for iOS. The only solution to clear it is to delete the KooFamiles app and reinstall it again.</li>
											!-->

										</ul>

										</div>

						</div>

                    </div>

               

            </div>

        </div>

 </div>

   <div class="modal fade" id="paypal_model" role="dialog">

        <div class="modal-dialog">

         



            <!-- Modal content-->

            <div class="modal-content">

             

                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                    <h4 class="modal-title"></h4>

                </div>

                 

                    <div class="modal-body" style="padding-bottom:0px;">

                        <div class="col-md-12" style="text-align: center;">

                          <h5>

								Something Went Wrong to Complete Payment Try Again or Use cash method to complete it

							</h5>

                          

                        </div>

                    </div>

                    <div class="modal-footer" style="padding-bottom:2px;">

                       <button id="paypal_cash" type="button" class="btn btn-primary" data-dismiss="modal" style="width:50%;margin-bottom: 3%;text-align: center;">Pay Cash</button>

                       <button id="paypal_close" type="button" class="btn btn-primary" data-dismiss="modal" style="width:50%;margin-bottom: 3%;text-align: center;">Close</button>

                    </div>

               

            </div>

        </div>

 </div>

    <div class="modal fade" id="shop_model" role="dialog">

        <div class="modal-dialog">

         



            <!-- Modal content-->

            <div class="modal-content">

             

                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                    <h4 class="modal-title"></h4>

                </div>

                 

                    <div class="modal-body" style="padding-bottom:0px;">

                        <div class="col-md-12" style="text-align: center;">

                          <h5 id="shop_model_text">

						  <?php if($merchant_detail['mobile_number']!="60172669613"){ ?>

              Sorry, we are currently experiencing some internet connection issue. If you want to place any order, please contact our waiter for placing order.

						  <?php } else { ?>

						   Our online order is from 8:00pm to 11.00pm only. please contact our waiter for your order. 

						  <?php } ?>

              </h5>
			      <!--h4> Do you want to visit other shops?</h4!-->

                          

                        </div>

						

                    </div>

                    <div class="modal-footer" style="padding-bottom:2px;">

						<div class="row">

						  <!--div class="col-md-4">
						     <button type="button" class="btn btn-primary redirect_fav" data-dismiss="modal" style="width:50%;margin-bottom: 3%;text-align: center;">Yes</button> 
						  </div!-->

						  <div class="col-md-12">

						  <button  type="button" class="btn btn-primary" data-dismiss="modal" style="margin-left:19%;width:50%;margin-bottom: 3%;text-align: center;">OK</button>

						  </div>

						</div>

                    </div>

               

            </div>

        </div>

 </div>

 

 <div class="modal fade" id="work_model" role="dialog">

        <div class="modal-dialog">

         



            <!-- Modal content-->

            <div class="modal-content">

             

                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                    <h4 class="modal-title"></h4>

                </div>

                 

                    <div class="modal-body" style="padding-bottom:0px;">

                        <div class="col-md-12" style="text-align: center;">

                          <h5>

						  Sorry,Shop is Close Now

						  </h5>

                          

                        </div>

                    </div>

                    <div class="modal-footer" style="padding-bottom:2px;">

                       <button id="close_shop" type="button" class="btn btn-primary" data-dismiss="modal" style="width:50%;margin-bottom: 3%;text-align: center;">Close</button>

                    </div>

               

            </div>

        </div>

 </div>

  <div class=" modal fade" id="ProductAdded" role="dialog">

        <div class="element-item modal-dialog modal-dialog-centered" style="position: absolute;top: 0;bottom: 0;left: 0;right: 0;display: grid;align-content: center;">

            <!-- Modal content-->

            <div class="element-item modal-content">

                <div class="element-item modal-header">

                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                            

                    

                              </div>   

                                    <p><?php echo $language['the_product_added']; ?></p>

                   <div id="without_varient_footer" class="modal-footer model_pop" style="padding-bottom:2px;">

                   <input type="hidden" id="pop_ok" name="pop_ok">

                    <button role="button" style="min-height:40px;position:static !important;" class="introduce-remarks btn btn-large btn-primary" data-toggle="modal" data-target="#remarks_area" disabled=""><?php echo $language['remarks']; ?></button>

                    <button role="button" class="close_pop btn btn-large btn-primary" style="background:#50D2B7;border:none;"><?php echo $language['ok']; ?></button>

                  </div>    

                                </div>

                            </div>

    </div>

	  <div class=" modal fade" id="AlerModel" role="dialog" style="width:80%;min-height: 200px;text-align: center;margin:8%;">

        <div class="element-item modal-dialog modal-dialog-centered" style="position: absolute;top: 0;bottom: 0;left: 0;right: 0;display: grid;align-content: center;">

            <!-- Modal content-->

            <div class="element-item modal-content">

                <div class="element-item modal-header">

                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                            

                    

                              </div>   

                                    <p id="show_msg" style="font-size:22px;font-weight:bold;"><?php echo $language['the_product_added']; ?></p>

                    

                                </div>

                            </div>

    </div>
	<div class=" modal fade" id="ProductshowModel" role="dialog" style="width:80%;min-height: 200px;text-align: center;margin:8%;">

        <div class="element-item modal-dialog modal-dialog-centered" style="position: absolute;top: 0;bottom: 0;left: 0;right: 0;display: grid;align-content: center;">

            <!-- Modal content-->

            <div class="element-item modal-content">
 

                                    <p id="show_msg_product" style="font-size:22px;font-weight:bold;"><?php echo $language['the_product_added']; ?></p>

                    

                                </div>

                            </div>

    </div>

 	<div class="modal fade" id="map_model" role="dialog">

        <div class="modal-dialog">

         



            <!-- Modal content-->

            <div class="modal-content">

             

                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                    <h4 class="modal-title"></h4>

                </div>

                 

                    <div class="modal-body" style="padding-bottom:0px;">

                        <div class="col-md-12" style="text-align: center;">

                          <h5>Sorry, To Place order You has to be in <span id='map_range'> </span> km range of Merchant </h5>

                          

                        </div>

                    </div>

                    <div class="modal-footer" style="padding-bottom:2px;">

                       

                    </div>

               

            </div>

        </div>

 </div>

 



  <div class="modal fade" id="map_model" role="dialog">

        <div class="modal-dialog">

         



            <!-- Modal content-->

            <div class="modal-content">

             

                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                    <h4 class="modal-title"></h4>

                </div>

                 

                    <div class="modal-body" style="padding-bottom:0px;">

                        <div class="col-md-12" style="text-align: center;">

                          <h5>Sorry, To Place order You has to be in <span id='map_range'> </span> km range of Merchant </h5>

                          

                        </div>

                    </div>

                    <div class="modal-footer" style="padding-bottom:2px;">

                       

                    </div>

               

            </div>

        </div>

 </div>

  <div class="modal fade" id="our_stall" role="dialog">

        <div class="modal-dialog">

            <!-- Modal content-->

            <div class="modal-content">

                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                    <h4 class="modal-title">Sub Merchant List of <?php echo $merchant_detail['name'];?> </h4>

                </div>

                    <div class="modal-body" style="padding-bottom:0px;">

                        <div class="col-sm-10">

                            <div class="form-group">

                       



                              <div class="container">

                              

                                <table class="table table-striped submer">

                                  <thead>

                                    <tr>

                                      <th>Submerchant Name</th>

                                      <th>Favorite</th>

                                    </tr>

                                  </thead>

                                 

                                  <tbody> 

                                    <form id="sub_mer_form" action="structure_merchant.php" method="post">



                                      <?php 

                                            $sql = mysqli_query($conn, "SELECT * FROM users WHERE mian_merchant='".$merchant_detail['name']."' ");

                                            

                                            while($data = mysqli_fetch_array($sql))

                                             {

                                            

                                             $fav=mysqli_query($conn, "SELECT count(*) as number FROM `favorities` WHERE `favorite_id`='".$data['id']."'");

                                             $cu=mysqli_fetch_array($fav);

                                             $sql2 = mysqli_query($conn, "SELECT * FROM users WHERE name='".$data['name']."' ");

                                             $m =mysqli_fetch_array($sql2);

                                            echo'<tr value='.$m['id'].' onclick="getId(this)"><td>'.$data['name'].' </td><td>( '.$cu['number'].' ) <i style="font-size: 17px;" class="heart fa fa-heart"></i> </td><input type="hidden" name="merchant_id" id="merchant_id" value=""><input type="hidden" name="sub_mer_id" id="sub_mer_id" value="sub_mer_id"></tr>';

                                            

                                              }

                                            ?>

                                           

                                     </form> 

                                  

                                  </tbody>

                                 

                                </table>

                              </div>







                            </div>

                        </div>

                    </div>

                    <div class="modal-footer" style="padding-bottom:2px;">

                        <!--<button type="button" class="btn btn-primary" data-dismiss="modal">No</button> <a href="view_merchant.php"><input type="button" class="btn btn-primary" value="Yes"></a>-->

                    </div>

                </form>

            </div>

        </div>

 </div>

<div class="modal fade" id="login_passwd_modal" tabindex="-1" role="dialog" aria-labelledby="login_passwd_modal_title" aria-hidden="true">

  <div class="modal-dialog" role="document">

    <div class="form-group">

      <div class="modal-content">

        <div class="modal-header">

          <h5 class="modal-title">Enter your phone number</h5>

          <button type="button" class="close" data-dismiss="modal" aria-label="Close">

            <span aria-hidden="true">&times;</span>

          </button>

        </div>

        <form action="" method="post" id="cred_ajax_popup">

          <div class="modal-body">

            <div class="form-group">

              <label for="check_phone">Phone number</label>

              <input type="text" id="check_phone" class="form-control" name="check_phone"/>

              <div class="passwd_field">

                <label for="login_password">Password</label>

                <input type="password" id="login_password" class="form-control" name="login_password" required/>

                <small class="wrong_login" style="display: none;color:#e6614f;">

                  Something went wrong! Try again

                </small>

                <small class="acc_blocked" style="display: none;color:#e6614f;">

                  This account is blocked, please contact support.

                </small>

                <small class="reg_pending" style="display: none;color:#e6614f;">

                  This account is waiting for activation.

                </small>

                <small class="logged-in" style="display: none;color:#e6614f;">

                  You are already logged into this account

                </small>

                <small class="success_login" style="display: none;color:#28a745;">

                  Successfully logged in!

                </small>

              </div>

            </div>

          </div>

          <div class="modal-footer login_footer">

            <div class="row" style="margin: 0;">

              <div class="col" style="padding: 0;margin: 5px;">

                <input type="submit" class="btn btn-primary" name="login_ajax" value="<?php echo $language['login']; ?>" style="width:100%;"/>

              </div>

              <div class="col" style="padding:0; margin: 5px;">

                <div class="btn facebook-login" style="width:100%;height:100%;"></div>

              </div>

              <div class="col-sm-4" style="padding: 0;margin: 5px;">

                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="width:100%;">Exit</button>

              </div>

            </div>

          </div>

          <div class="modal-footer register_footer" style="display: none;">

            <div class="row" style="margin: 0;">

              <div class="col" style="padding: 0;margin: 5px;">

                <input type="submit" class="btn btn-primary" name="register_ajax" value="Register" style="width:100%;"/>

              </div>

               <div class="col" style="padding:0; margin: 5px;">

                <div class="btn facebook-login" style="width:100%;height:100%;position:relative;"></div>

              </div>

                            <div class="col-sm-4" style="padding: 0;margin: 5px;">

                <button id="continue_guest" type="button" class="btn btn-secondary" data-dismiss="modal" style="width:100%;">Continue as Guest</button>

              </div>

                        </div>

          </div>

        </form>

      </div>

    </div>

  </div>

</div>

<!-- login popup for rebeat process!-->

	<div class="modal fade" id="LesaaAmountModel" role="dialog" style="z-index:999999;margin-top:3%;">

                    <div class="modal-dialog">

                      <div class="modal-content" id="modalcontent">

					   <div class="modal-header">

        

          <button type="button" class="close" data-dismiss="modal" aria-label="Close">

            <span aria-hidden="true">&times;</span>

          </button>

        </div>

		

     

                          <div class="modal-body">

                               <p style="font-size:18px;">Insufficient balance to pay. Please select other wallet or   <a href="https://api.whatsapp.com/send?phone=<?php  echo $merchant_detail['mobile_number']?>" target="_blank">contact us at whatapp 

					<img src="images/whatapp.png" style="max-width:40px;"/></a> to top up </p>

							   <button class="btn btn-large btn-primary insufficient_close">ok</button>

                          </div>

                      

                      </div>

                    </div>

    </div>

	<div class="modal fade" id="WalletModel" role="dialog" style="z-index:999999;margin-top:3%;">

                    <div class="modal-dialog">

                      <div class="modal-content" id="modalcontent">

					   <div class="modal-header">

        

          <button type="button" class="close" data-dismiss="modal" aria-label="Close">

            <span aria-hidden="true">&times;</span>

          </button>

        </div>

     

                          <div class="modal-body">

                              <p style="font-size:18px;">Wallet Feature is Only Applicale for Register Member,We are Processing as Cash Wallet </p>

							   <button class="btn btn-large btn-primary"  data-dismiss="modal">ok</button>

                          </div>

                      

                      </div>

                    </div>

    </div>

	<div class="modal fade" id="ProccedAmount" role="dialog" style="z-index:999999;margin-top:3%;">

                    <div class="modal-dialog">

                      <div class="modal-content" id="modalcontent">

					   <div class="modal-header">

        

          <button type="button" class="close" data-dismiss="modal" aria-label="Close">

            <span aria-hidden="true">&times;</span>

          </button>

        </div>

     

                          <div class="modal-body">

                              <p style="font-size:18px;" id="amount_label"> </p>

                            <button class="btn btn-large btn-danger make_payment">Yes</button>

							<button class="btn btn-large btn-primary extracss" data-dismiss="modal">Cancel</button>

						 </div>

                      

                      </div>

                    </div>

    </div>
<div class="modal fade" id="InternetModel" tabindex="-1" role="dialog" aria-hidden="true">
 <div class="modal-dialog" role="document">
     <div class="form-group">
	    <div class="modal-content">
		   <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			 <div class="modal-body">
					

                    <div class="" style="">

                        <h4>Pay with Internet Banking</h4>

                        <div class="row">
							 <div clas="form-group" style="margin-left:3%;font-size:15px;">
								

                                <div style="clear:both;"></div>
								<p> Please pay to: </br>
								<?php if($merchant_detail['id']=='5062'){ ?>
									Name: Lim Foot Ping</br>
									Bank name: Maybank </br>
									Bank account : 511234010582 </br>
									
									
									<span style="color: red;" class="final_amount_label">

									Payable  amount: Rm <span style="font-weight:bold;" class="final_amount_value"></span>

                                </span>
								</br>
									 Enquiry:  <a href="https://api.whatsapp.com/send?phone=601159223660" target="_blank"> 601159223660 <img src="images/whatapp.png" style="max-width:40px;"/> </a>
									 </br>
								<?php } else { ?>
										Name: Chong Woi Joon  </br>
									Bank name: Hong Leong Bank </br>
									Bank account : 22850076859 </br>
									<b style="font-size:18px;">Boostpay Number 6012-3115670</b>  
									</br>
									
									<span style="color: red;" class="final_amount_label">

									Payable  amount: Rm <span style="font-weight:bold;" class="final_amount_value"></span>

                                </span>
								</br>
									 Enquiry:  <a href="https://api.whatsapp.com/send?phone=60123945670" target="_blank"> 60123945670 <img src="images/whatapp.png" style="max-width:40px;"/> </a>
									 </br>
								<?php } ?>
								</p>  

                                <span class="btn btn-large btn-danger trasfer_complete">Transfer Complete<span>   

				 </span></span>
                            </div>

                            <div class="form-group no-wallet">

                                <p style="text-align: center;">OR</p>

                                <span class="btn btn-block btn-primary cash_pay" name="cashpayment">Change to Cash Payment 

								</span>

                            </div>
                      

                    </div>


                </div>

            </div>
		</div>  
	 </div>
 </div>
</div>
<div class="modal fade" id="newuser_model" tabindex="-1" role="dialog" aria-labelledby="login_passwd_modal_title" aria-hidden="true">

  <div class="modal-dialog" role="document">

    <div class="form-group">

      <div class="modal-content">

	    

        <div class="modal-header">

            

          <button type="button" class="close" data-dismiss="modal" aria-label="Close">

            <span aria-hidden="true">&times;</span>

          </button>

        </div>

     

          <div class="modal-body online_login_model">

		  <div class="form-group" style="margin-bottom: 0em;">

				

				<p id="with_wallet" style="font-size:16px;color:red;display:none;">

				<span style='font-size:20px;'>&#128512;</span> 

				<!--Congratulations, your order has just earned Rm <span class="rebate_amount_label"></span> into your Koo Coin wallet. You can use it after 24 hours !-->

					<?php if($special_coin_name){  echo "Congratulation,Please login to use $special_coin_name wallet.";?>

					

					<?php } else {?>

					Congratulation, your order is completed. <span id="with_wallet_span"> Please login to claim for your rebate of RM <span class="rebate_amount_label">15.20 </span> into your KOO Coin wallet.</span>  

					

					<?php } ?>

				 </p>  

				 <p id="without_wallet" style="font-size: 16px; color: red;display:none;">

				<span style="font-size:20px;">😀</span> 

				<!--Congratulations, your order has just earned Rm <span class="rebate_amount_label"></span> into your Koo Coin wallet. You can use it after 24 hours !-->

					<?php if($special_coin_name){ echo "Congratulation,Please login to use $special_coin_name wallet.";} else { echo "Congratulation, your order is completed. Please login to use KOO Coin wallet.";} ?>

					

					

				

				 </p>

				 

				

			</div>

			<div class="wallet_mode" style="display:none;">  

				<h4>Please choose your wallet to pay</h4>

				 <div class="row">

					<!--div style="margin: 2%;max-height: 80px;text-align: left;padding: 0%;" class="col-md-3 card bg-primary text-white">
						<div class="card-body wallet_select" wallet_name="MYR" type="myr_bal">MYR <br> <span  id="myr_bal"><?php if(isset($urecord['balance_myr'])){ echo $urecord['balance_myr'];} ?></span></div>
					</div>
					<div style="margin: 2%;max-height: 80px;text-align: left;padding: 0%;" class="col-md-3 card bg-info text-white">
						<div class="card-body wallet_select" wallet_name="CF"  type="usd_bal">CF <br> <span id="usd_bal"><?php if(isset($urecord['balance_usd'])){ echo $urecord['balance_usd'];} ?></span></div>
					</div!-->

					<?php if($special_coin_name){ ?>

					 <div style="margin: 2%;max-height: 80px;text-align: left;padding: 0%;font-size: 13px;" class="col-md-7 card bg-info text-white">

						<div class="card-body wallet_select" wallet_name="<?php echo $special_coin_name; ?>"  type="special_bal"  style="color:black;padding:1.00rem !important;font-size: 18px;">

						 <?php echo $special_coin_name; ?>  RM <span id="special_bal" style="color:red;font-weight:bold;"><?php if(isset($special_bal)){ echo number_format($special_bal,2);} ?></span>

						 </div>

						

				   </div>

				    <small style="margin-left: 3%;color:red;">(Min RM <?php echo $merchant_detail['special_coin_min'];?> to use, and Max RM <?php echo $merchant_detail['special_coin_max'];?> per transaction)</small>

					<?php } else { ?>

				   <div style="margin: 2%;max-height: 80px;text-align: left;padding: 0%;font-size: 13px;" class="col-md-7 card bg-info text-white">

						<div class="card-body wallet_select" wallet_name="KOO COIN"  type="inr_bal"  style="color:black;padding:1.00rem !important;font-size: 18px;">

						 KOO COIN  RM <span id="inr_bal" style="color:red;font-weight:bold;"><?php if(isset($balance_inr)){ echo number_format($balance_inr,2);} ?></span>

						 </div>

						

					</div> 

					 <small style="margin-left: 3%;color:red;">(Min RM 0.50 to use, and Max RM 10.00 per transaction)</small>

					<?php } ?>

				   

				 <div clas="form-group" style="margin-left:3%;font-size:15px;">

				 <?php if($merchant_detail['order_extra_charge']>0){ $s_label="Product";} else { echo "Total";}?>

					

					 <?php echo $s_label;?> Amount: Rm 

					 <span id="total_cart_amount_label" style="font-weight:bold;color: black;"></span>    

				 <p class="select_label" style="display:none;">Selected Wallet : <span id='wallet_name'></span></br>

				    

					  <div style="clear:both;"></div>

					  <span style="display:none;" class="delivery_extra" style="margin-bottom:1%;">

					     <?php  echo ucfirst(strtolower("Delivery Charges")); ?>: Rm <span class="delivery_extra_value"></span>

			

					 </span>

					 <div style="clear:both;"></div>

					 <span style="display:none;" class="membership_discount_label" style="margin-bottom:1%;">

					     <?php  echo ucfirst(strtolower("Membership Discount")); ?>: Rm <span class="membership_discount_value"></span>

			

					 </span>

					 <div style="clear:both;"></div>

					 <span style="display:none;" class="coupon_discount_amount_label" style="margin-bottom:1%;">

					     <?php  echo ucfirst(strtolower("Coupon Discount")); ?>: Rm <span class="coupon_discount_amount_value"></span>

			

					 </span>

					

					

					  <div style="clear:both;"></div>

					  <div  style="display:none;" class="sst_amount_label">  

						<div style="grid-template-columns:.2fr 2fr;grid-column-gap: 10px;vertical-align: middle;align-content:center;font-weight: bold;font-size: 15px;color:black;">

							

						   

							<?php  echo "Service fee "?><?php echo $merchant_detail['sst_rate']." % "; ?>: Rm <span class="sst_amount_value"></span>

							</div>

						</div>  

					  <div style="clear:both;"></div>

					    <div  style="display:none;" class="delivery_tax_amount_label">  

						<div style="grid-template-columns:.2fr 2fr;grid-column-gap: 10px;vertical-align: middle;align-content:center;font-weight: bold;font-size: 15px;color:black;">

							

						   

							<?php  echo "Delivery Service tax "?><?php echo $merchant_detail['delivery_rate']." % "; ?>: Rm <span class="delivery_tax_amount_value"></span>

							</div>

						</div> 

						<div style="clear:both;"></div>

							<span style="display:none;color:Red;" class="final_amount_label" style="margin-bottom:1%;">

					     <?php  echo ucfirst(strtolower("Payable  Amount")); ?>: Rm <span style="font-weight:bold;" class="final_amount_value"></span>

			

							</span>

					  <div style="clear:both;"></div>

					      

					 

					  <span id="wallet_payment_label" style="display:none;"></span>

					  <span id="bal_to_paid_label" style="display:none;color: green;"></span>

				 

					 </p> 

						 

					 <span class="btn btn-large btn-danger wallet_final_payment" style="display:none;">Pay Now<span>   

				 </div>

					 <?php  if($_SESSION['block_pay']!=="y"){?>

					<div class="form-group no-wallet">

					<p style="text-align: center;">OR</p>

				   <span class="btn btn-block btn-primary cash_pay" name="cashpayment">Change to Cash Payment 

				  </span> 

					

					 </div> <?php } ?>

				</div>

				

			</div>

			<div id="login_process">

             

			

				

			  <div class="login_passwd_field" style="display:none;">

                <label for="login_password"><?php echo $language['password_login']; ?></label>  

                <input  type="password" id="login_ajax_password" class="form-control" name="login_password" required/>

				

       <i  onclick="myFunction()" id="eye_slash" class="fa fa-eye-slash" aria-hidden="true"></i>

	  <span onclick="myFunction()" id="eye_pass"> <?php echo $language['show_password']; ?> </span>   

            <div style="clear:both"></div>

				<span class="forgot_pass" style="color:#28a745;/*! float:right; */font-size:16px;text-align: center;text-decoration: underline;/*! width: 100% !important; */display: inline-block;">

							 <?php echo $language['reset_password']; ?> 

							</span>

              

              </div>

			    <div class="forgot-form" style="display:none;">

				  <label for="login_password"><?php echo $language['reset_password']; ?> </label>

				  <div class="input-group mb-2">

					<div class="input-group-prepend">

					  <div class="input-group-text" style="background-color:#51D2B7;border-radius: 5px 0 0 5px;height: 100%;padding: 0 10px;display: grid;align-content: center;">+60</div>

					</div>

					

					<input  type="number" autocomplete="tel" maxlength='10' id="user_mobile" class="mobile_number form-control" <?php if($check_number){ echo "readonly";} ?> value="<?php if($check_number){ echo $check_number;}  ?>" placeholder="Phone number" name="mobile_number" required="" />

				   

				  </div>

				  <small class="forgot_error" style="display: none;color:#e6614f;">

					 Please Key in valid number

					</small>

				  <img id="loader-credentials" src="<?php echo $site_url;?>/img/loader.gif" style="display:none;width:40px;height:40px;grid-column-start: 2;grid-column-end: 3;"/>

				</div>

			  

        

          <div class="modal-footer login_footer" style="padding:0px;">

            <div class="row" style="margin: 0;">

			 

             

              <div class="col otp_fields join_now" style="padding: 0;margin: 5px;display:none;">

                

                <input type="submit" class="btn btn-primary login_ajax"  name="login_ajax" value="<?php echo $language['login']; ?>" style="float: right;display:none;"/>

				 	 <small id="login_error" style="display: none;color:#e6614f;">

                 

                </small>

				

              </div>

			  <div class="col otp_fields forgot_now" style="padding: 0;margin: 5px;display:none;">

                <input type="submit" class="btn btn-primary forgot_reset"   value="Reset" style="width:50%;float: right;display:none;"/>

              </div>         

           

            </div>

			

          </div>

		  </div>

			

		  

      </div>

    </div>

  </div>

</div>

</div>

<!-- end login popup for rebeat process!-->

<div class="modal fade" id="newmodel_check" tabindex="-1" role="dialog" aria-labelledby="login_passwd_modal_title" aria-hidden="true">

   <div class="modal-dialog" role="document">

		<div class="form-group">

			<div class="modal-content">

				<div class="modal-header">

				   

				  <button type="button" class="close" data-dismiss="modal" aria-label="Close">

					<span aria-hidden="true">&times;</span>

				  </button>

				</div>

				<div class="modal-body">

				    <p id="show_msg_new" style="font-size: 16px;color: red;"><span style='font-size:20px;'>&#128512;</span> 

					    Verfiy otp get membership discount

					</p>  

					<div class="form-group otp_form" style="display:none;">

					<div id="divOuter">

						<div id="divInner">

						Otp code

							<input id="partitioned" type="Number" maxlength="4" />

							   <!--small style="float:right;color:#28a745;display:none;" class="resend send_otp">Resend</small!-->

							 <small class="otp_error" style="display: none;color:#e6614f;">

								Invalid Otp code

							</small>

						</div>

					</div>

					</div>

					<div class="login_passwd_field" style="display:none;">

							<label for="login_password">Password to login</label>

							<input  type="password" id="login_ajax_password_new" class="form-control" name="login_password" required/>

							

				   <i  onclick="myFunctionnew()" id="eye_slash_new" class="fa fa-eye-slash" aria-hidden="true"></i>

				  <span onclick="myFunctionnew()" id="eye_pass_new"> <?php echo $language['show_password']; ?>  </span>

			 

						   <div style="clear:both"></div>	

							<span class="forgot_pass" style="color:#28a745;/*! float:right; */font-size:16px;text-align: center;text-decoration: underline;/*! width: 100% !important; */display: inline-block;">

							 <?php echo $language['reset_password']; ?> 

							</span>

              

              </div>

			    <div class="forgot-form" style="display:none;">

						  <label for="login_password">Reset/Create  Password</label>

						  <div class="input-group mb-2">

							<div class="input-group-prepend">

							  <div class="input-group-text" style="background-color:#51D2B7;border-radius: 5px 0 0 5px;height: 100%;padding: 0 10px;display: grid;align-content: center;">+60</div>

							</div>

							

							<input  type="number" autocomplete="tel" maxlength='10'  class="mobile_number form-control" <?php if($check_number){ echo "readonly";} ?> value="<?php if($check_number){ echo $check_number;}  ?>" placeholder="Phone number" name="mobile_number" required="" />

						   

						  </div>

						  <small class="forgot_error" style="display: none;color:#e6614f;">

							 Please Key in valid number

							</small>

						  <img id="loader-credentials" src="<?php echo $site_url;?>/img/loader.gif" style="display:none;width:40px;height:40px;grid-column-start: 2;grid-column-end: 3;"/>

				</div>

				</div>

				<div class="modal-footer login_footer" style="display:none;">

						<div class="row" style="margin: 0;">

						<div class="col otp_fields join_now" style="padding: 0;margin: 5px;display:none;">

							<input type="submit" class="btn btn-primary login_ajax_new"  name="login_ajax" value="LOGIN" style="float: right;display:none;"/>

							<small id="login_error_new" style="display: none;color:#e6614f;"></small>

						</div>

						  <div class="col otp_fields forgot_now" style="padding: 0;margin: 5px;display:none;">

							<input type="submit" class="btn btn-primary forgot_reset"   value="Reset" style="width:50%;float: right;display:none;"/>

						  </div>         

				   

						</div>

					<small  class="reg_pending skip" id='skip' style="color:#e6614f;font-size:14px;min-width:50px;">

					  <u>Got it !</u>

					</small>

					 <small  class="reg_pending register_skip skip" style="color:#e6614f;font-size:14px;display:none;min-width:50px">

					  <u>Got it !</u>

					</small>

			  </div>

			</div>

		</div>

	</div>

</div>

<div class="modal fade" id="PasswordModel" role="dialog" style="">  

   <div class="modal-dialog">

           <?php 



          



            ?>



            <!-- Modal content-->

            <div class="modal-content">

              

                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal">&times;</button>

					

                </div>

                 

                    <div class="modal-body" style="padding-bottom:0px;">

					    <div class="form-group register_password" style="<?php  if($password_created=="y"){ echo "display:none;"; }?>">

             

							  <div class="passwd_field">

								<label for="login_password">Please create your password</label>

								<input type="password" id="register_password" class="form-control" name="login_password"/>

											

					   <i  onclick="myFunction2()" id="eye_slash_2" class="fa fa-eye-slash" aria-hidden="true"></i>

					  <span id="eye_pass_2" onclick="myFunction2()" > <?php echo $language['show_password']; ?>  </span>

					   <small id="register_error" style="display: none;color:#e6614f;">

                 

                </small>

										

							  </div>

				</div>

						

                    </div>

                    <div class="modal-footer" style="padding-bottom:2px;">

                        <div class="row" style="margin: 0;">

			 

             

						  <div class="col" style="padding: 0;margin: 5px;">

							

							<input type="submit" class="btn btn-primary register_ajax"  name="register_ajax" value="Confirm" style="float: right;"/>

							 <small id="register_error" style="display: none;color:#e6614f;">

							 

							</small>

							

						  </div>

						         

					   

						</div>

						  <small  class="finalskip"  style="color:#e6614f;font-size:14px;min-width:50px;">

						  <u class="finalskip">Skip</u>

						</small>    

						

                    </div>

                  

            </div>

        </div>

  </div>  