<?php $this->load->view('header');
//echo "<pre>"; print_r($deals); die;

$getuserdetails = $this->admin_model->getuserdetails($_SESSION['front_user_id']);
 ?>
        
        <div class="body_sectn noTopBottomMargin">
        <!-- <div class="headerUser" style="background-image: url(<?php echo base_url(); ?>upload/user_profile_img/thumb/<?php echo @$getuserdetails->image;?> );"> --->          
            <div class="headingBar">
                <div class="container">
                    <h3 class="pageTitle">Overview</h3>
                </div>
            </div>
            <div class="container">
                <div class="user-settings">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist" style="margin:0;">
						<h3 class="userName" style="font-size: 20px;text-align: center;font-weight: bold;"><?php echo @$getuserdetails->username;?><!-- <span class="badge">Editorial</span> --></h3>
                         <h5 class="joiningDate" style="text-align: center;">Joined <?php echo date("M d, Y ",strtotime($getuserdetails->date));?></h5>
                         <?php if(@$getuserdetails->description!=''){ ?>  <div class="userProfile-quote"><span class="arrow"></span><?php echo @$getuserdetails->description;?></div><?php } ?>                         
                          <div class="userImage" style=" margin-bottom: 5px;">
                            <img <?php if(@$getuserdetails->image!=''){ ?>
                               src="<?php echo base_url(); ?>upload/user_profile_img/thumb/<?php echo @$getuserdetails->image;?>"
                           <?php }else{ ?>
                              src="<?php echo base_url(); ?>assets/front/images/login_pic.png"
                           <?php } ?>      
                             alt="" class="img-responsive img-circle img-user" style="border-radius: 50%!important;border: 1px solid rgba(0, 0, 0, 0.1);height: 100px;width:100px;margin:0 auto">
                           </div>   
                         <li role="presentation" ><a href="<?php echo base_url();?>index.php/index/overview" >My Deals</a></li>
                        <li role="presentation"><a href="<?php echo base_url();?>index.php/index/user_voucher" >My Vouchers</a></li>
                        <li role="presentation"><a href="<?php echo base_url();?>index.php/index/user_commented_deal" >My Commented Deals</a></li>
                        <li role="presentation"><a href="<?php echo base_url();?>index.php/index/user_commented_voucher" >My Commented Vouchers</a></li>
                        <li role="presentation" class="active"><a href="<?php echo base_url();?>index.php/index/badgesdetails" >My Badges
                         <?php /* ?>    <?php
                             $this->load->model('front/admin_model');
                           $dealdetails=  $this->admin_model->countbadgesfordeal();

                                                    $likeImageName = '';
                                                    if(@$dealdetails->hot_count <=100){

                                                      $image = $this->admin_model->getBadgeByBadgeTypeId(1);
                                                    }
                                                    else{

                                                      $image = $this->admin_model->getBadgeByBadgeTypeId(2);
                                                    }
                                                    if(!empty($image)){

                                                      $likeImageName = $image[0]->image_name;
                                                    }

                                                    $commentImageName = $commentImage = '';
                                                    if(@$dealdetails->total_comment > 50){

                                                      $commentImage = $this->admin_model->getBadgeByBadgeTypeId(3);
                                                      if(!empty($commentImage)){

                                                        $commentImageName = $commentImage[0]->image_name;
                                                      }
                                                    }
                                                  ?>
                                                   



                                                <?php if($likeImageName != '' || $commentImageName != ''){ ?>
<!--                                  <div style="top:85px !important;" class="batch_icnleft"><?php echo $c;?></div>-->
                                                    <div style="" class="">
                                                      <img src="http://spicydealsindia.com/upload/badge/thumb/<?php echo $likeImageName!='' ? $likeImageName : $commentImageName; ?>">
                                                    </div>
                                                    <?php if($likeImageName != '' && $commentImageName != ''){ ?>
                                                 
                                                        <img src="http://spicydealsindia.com/upload/badge/thumb/<?php echo $commentImageName; ?>">
                                                       
                                               
                                                    <?php } ?>
                                               
                                                <?php } ?> <?php */?>
                                                   
                                                   <!-- End -->
                            
                        
                        </a></li>
<!--                        <li role="presentation"><a href="" >My Voucher Badges <br>
                             <?php
                             $this->load->model('front/admin_model');
                           $dealdetailsvoucher=  $this->admin_model->countbadgesforvoucher();

                                                    $likeImageName = '';
                                                    if(@$dealdetailsvoucher->hot_count <=100){

                                                      $image = $this->admin_model->getBadgeByBadgeTypeId(1);
                                                    }
                                                    else{

                                                      $image = $this->admin_model->getBadgeByBadgeTypeId(2);
                                                    }
                                                    if(!empty($image)){

                                                      $likeImageName = $image[0]->image_name;
                                                    }

                                                    $commentImageName = $commentImage = '';
                                                    if(@$dealdetailsvoucher->total_comment > 50){

                                                      $commentImage = $this->admin_model->getBadgeByBadgeTypeId(3);
                                                      if(!empty($commentImage)){

                                                        $commentImageName = $commentImage[0]->image_name;
                                                      }
                                                    }
                                                  ?>
                                                   



                                                <?php if($likeImageName != '' || $commentImageName != ''){ ?>
                                  <div style="top:85px !important;" class="batch_icnleft"><?php echo $c;?></div>
                                                    <div style="" class="">
                                                      <img src="http://spicydealsindia.com/upload/badge/thumb/<?php echo $likeImageName!='' ? $likeImageName : $commentImageName; ?>">
                                                    </div>
                                                    <?php if($likeImageName != '' && $commentImageName != ''){ ?>
                                                 
                                                        <img src="http://spicydealsindia.com/upload/badge/thumb/<?php echo $commentImageName; ?>">
                                                       
                                               
                                                    <?php } ?>
                                               
                                                <?php } ?> */?>
                                                   
                                                    End 
                            
                        
                        </a></li>-->
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content whiteBox">
                        
                     

<?php
/*if(!empty($badges))
{
    foreach ($badges as $value) {
        
       
    
?>
                        <div class="col-md-12 aa">
                            <div class="col-md-2">
                               <img style="height:25px; width:25px;"   src="<?php echo base_url(); ?>upload/badge/thumb/<?php echo @$value->image; ?>" />
                            </div>   
                            <div class="col-md-10">
                                <p><?php echo @$value->name; ?></p>
                            </div> 
                             </div>
                            <?php
}
}*/
?>
								<div class="col-md-12 aa">	
									<?php 
									 $sql = "select badge_notification from user_notification where badge_notification='1' and user_id='".$_SESSION['front_user_id']."'";
									$query = $this->db->query($sql);
									$checkedrow = $query->num_rows();
									/*print_r($checkedrow);
									if (!empty($checkedrow)) {
										echo 'hi';
									}
									
									else
									{
										echo 'no';
									}
									die();*/
									
									// echo $this->db->last_query();exit;
									if(!empty($checkedrow)) {

											$qryBadName = "SELECT * from badge_types where id=16 and deleted=0";
											 $qryBadImg = "SELECT * from badges where badge_type_id=16 and status=0 and deleted=0";
											
											$query = $this->db->query($qryBadName);
											$query1 = $this->db->query($qryBadImg);
											$BadgeUserName =  $query->row();
											$BadgeUserImg =  $query1->row();
											
											
											/* notification */
											$this->db->where('user_id',$_SESSION['front_user_id']);
											 $ans = $this->db->get('user_notification');
											 $NotifiRow = $ans->row();
											 $commeNot = $NotifiRow->badge_notification;
											 
											 
											 $this->db->where('user_id',$_SESSION['front_user_id']);
											 $this->db->where('badge_id',16);
											 $NotiResult = $this->db->get('notification');
											 $CheckNotifi = $NotiResult->result_array();
											//~ echo '<pre>';
											//~ print_r($BadgeUserImg); 
											//~ print_r($CheckNotifi['0']['badge_image']); 
											//~ echo '</pre>';
											if($commeNot == 1)
											{	
												if($CheckNotifi == null)
												{
													$data = array(
														'user_id' => $_SESSION['front_user_id'],
														'notification' => @$BadgeUserName->name ,
														'notification_type' => 'Badges',
														'badge_id' => 16,
														'badge_image' =>  @$BadgeUserImg->image,
														'date' => date("Y-m-d h:i:sa"),
														'status' => '0',
														
													);  
													$this->db->insert('notification', $data);	
												}	
																					
											}
											/* notification */
											 
											 
											
									?> 
									<?php $CheckUserDet1 = $this->admin_model->check_userprofile_complete(); ?>
									<div class="total col-md-12" style="clear: both;">
									<div class="col-md-2">
										<?php if(@$BadgeUserImg->image!=''){ ?>										
										
										<img style="height:100px; width:100px;border-radius: 50%;"   src="<?php echo base_url(); ?>upload/badge/thumb/<?php echo @$BadgeUserImg->image; ?>" />
									<?php }
									else { ?>
										<img style="height:100px; width:100px;border-radius: 50%;"   src="<?php echo base_url(); ?>assets/front/images/no_photo.jpg" />
									<?php } ?>
									</div>   
									
									<div class="col-md-10">
										<p style="font-size:13px;"><?php echo @$BadgeUserName->name; ?></p>
										<p class="date_timess"><i class="fa fa-comment" aria-hidden="true"></i>  <?php echo date("M d Y ",strtotime($CheckUserDet1->date));?></p>
									</div> 
									</div>
									<?php 								
										$CheckUserDet = $this->admin_model->check_userprofile_complete();
										//~ echo '<pre>';
										//~ print_r($CheckUserDet);
										//~ echo '</pre>';
										if($CheckUserDet ->image == '' || $CheckUserDet ->username == '' || $CheckUserDet ->location == '' || $CheckUserDet ->date_of_birth == '' || $CheckUserDet ->description == '' || $CheckUserDet ->email == '' || $CheckUserDet ->password == '')
										{
											
											
											 $qryBadName = "SELECT * from badge_types where id=21 and deleted=0";
											 $qryBadImg = "SELECT * from badges where badge_type_id=21 and status=0 and deleted=0";
											
											$query = $this->db->query($qryBadName);
											$query1 = $this->db->query($qryBadImg);
											$BadgeUserName =  $query->row();
											$BadgeUserImg =  $query1->row();
											
											/* notification */
											$this->db->where('user_id',$_SESSION['front_user_id']);
											 $ans = $this->db->get('user_notification');
											 $NotifiRow = $ans->row();
											 $commeNot = $NotifiRow->badge_notification;
											 
											 
											 $this->db->where('user_id',$_SESSION['front_user_id']);
											 $this->db->where('badge_id',21);
											 $NotiResult = $this->db->get('notification');
											 $CheckNotifi = $NotiResult->result_array();
											
											if($commeNot == 1)
											{	
												if($CheckNotifi == null)
												{
													$data = array(
														'user_id' => $_SESSION['front_user_id'],
														'notification' => @$BadgeUserName->name ,
														'notification_type' => 'Badges',
														'badge_id' => 21,
														'badge_image' =>  @$BadgeUserImg->image,
														'date' => date("Y-m-d h:i:sa"),
														'status' => '0',
														
													);  
													$this->db->insert('notification', $data);	
												}	
																					
											}
											/* notification */
											
											
											?>
											<div class="total col-md-12" style="clear:both;">
												<div class="col-md-2">
													<?php if(@$BadgeUserImg->image!=''){ ?>	
													<img style="height:100px; width:100px;border-radius: 50%;"   src="<?php echo base_url(); ?>upload/badge/thumb/<?php echo @$BadgeUserImg->image; ?>" />
													<?php }  else { ?>
														<img style="height:100px; width:100px;border-radius: 50%;"  src="<?php echo base_url(); ?>assets/front/images/no_photo.jpg" />
														<?php } ?>
												</div>   
												<div class="col-md-10">
													<p style="font-size:13px;"><?php echo @$BadgeUserName->name; ?></p>
													<p class="date_timess"><i class="fa fa-comment" aria-hidden="true"></i>  <?php echo date("M d Y ",strtotime($CheckUserDet->date));?></p>
												</div> 
												</div>
											<?php
										}
										
										
										$getDealPost = $this->admin_model->get_post_from_deal();
										$getVoucherlPost = $this->admin_model->get_post_from_voucher();
										$getDiscussionPost = $this->admin_model->get_post_from_discussion();
										$TotalPost = $getDealPost + $getVoucherlPost +$getDiscussionPost;
									
										if($TotalPost > 50)
										{
											 $qryBadName = "SELECT * from badge_types where id=10 and deleted=0";
											 $qryBadImg = "SELECT * from badges where badge_type_id=10 and status=0 and deleted=0";
											
											$query = $this->db->query($qryBadName);
											$query1 = $this->db->query($qryBadImg);
											$BadgeUserName =  $query->row();
											$BadgeUserImg =  $query1->row();
											
											
											/* notification */
											$this->db->where('user_id',$_SESSION['front_user_id']);
											 $ans = $this->db->get('user_notification');
											 $NotifiRow = $ans->row();
											 $commeNot = $NotifiRow->badge_notification;
											 
											 
											 $this->db->where('user_id',$_SESSION['front_user_id']);
											 $this->db->where('badge_id',10);
											 $NotiResult = $this->db->get('notification');
											 $CheckNotifi = $NotiResult->result_array();
											
											if($commeNot == 1)
											{	
												if($CheckNotifi == null)
												{
													$data = array(
														'user_id' => $_SESSION['front_user_id'],
														'notification' => @$BadgeUserName->name ,
														'notification_type' => 'Badges',
														'badge_id' => 10,
														'badge_image' =>  @$BadgeUserImg->image,
														'date' => date("Y-m-d h:i:sa"),
														'status' => '0',
														
													);  
													$this->db->insert('notification', $data);	
												}	
																					
											}
											/* notification */
											?>
												<div class="col-md-2">
													<img style="height:25px; width:25px;"   src="<?php echo base_url(); ?>upload/badge/thumb/<?php echo @$BadgeUserImg->image; ?>" />
												</div>   
												<div class="col-md-10">
													<p><?php echo @$BadgeUserName->name; ?></p>
												</div> 
											<?php
										}
										if($TotalPost > 100)
										{
											$qryBadName = "SELECT * from badge_types where id=9 and deleted=0";
											 $qryBadImg = "SELECT * from badges where badge_type_id=9 and status=0 and deleted=0";
											
											$query = $this->db->query($qryBadName);
											$query1 = $this->db->query($qryBadImg);
											$BadgeUserName =  $query->row();
											$BadgeUserImg =  $query1->row();
											
											/* notification */
											$this->db->where('user_id',$_SESSION['front_user_id']);
											 $ans = $this->db->get('user_notification');
											 $NotifiRow = $ans->row();
											 $commeNot = $NotifiRow->badge_notification;
											 
											 
											 $this->db->where('user_id',$_SESSION['front_user_id']);
											 $this->db->where('badge_id',9);
											 $NotiResult = $this->db->get('notification');
											 $CheckNotifi = $NotiResult->result_array();
											
											if($commeNot == 1)
											{	
												if($CheckNotifi == null)
												{
													$data = array(
														'user_id' => $_SESSION['front_user_id'],
														'notification' => @$BadgeUserName->name ,
														'notification_type' => 'Badges',
														'badge_id' => 9,
														'badge_image' =>  @$BadgeUserImg->image,
														'date' => date("Y-m-d h:i:sa"),
														'status' => '0',
														
													);  
													$this->db->insert('notification', $data);	
												}	
																					
											}
											/* notification */
											?>
												<div class="col-md-2">
													<img style="height:25px; width:25px;"   src="<?php echo base_url(); ?>upload/badge/thumb/<?php echo @$BadgeUserImg->image; ?>" />
												</div>   
												<div class="col-md-10">
													<p><?php echo @$BadgeUserName->name; ?></p>
												</div> 
												
											<?php
										}
										if($TotalPost > 200)
										{
											$qryBadName = "SELECT * from badge_types where id=11 and deleted=0";
											 $qryBadImg = "SELECT * from badges where badge_type_id=11 and status=0 and deleted=0";
											
											$query = $this->db->query($qryBadName);
											$query1 = $this->db->query($qryBadImg);
											$BadgeUserName =  $query->row();
											$BadgeUserImg =  $query1->row();
											
											/* notification */
											$this->db->where('user_id',$_SESSION['front_user_id']);
											 $ans = $this->db->get('user_notification');
											 $NotifiRow = $ans->row();
											 $commeNot = $NotifiRow->badge_notification;
											 
											 
											 $this->db->where('user_id',$_SESSION['front_user_id']);
											 $this->db->where('badge_id',11);
											 $NotiResult = $this->db->get('notification');
											 $CheckNotifi = $NotiResult->result_array();
											
											if($commeNot == 1)
											{	
												if($CheckNotifi == null)
												{
													$data = array(
														'user_id' => $_SESSION['front_user_id'],
														'notification' => @$BadgeUserName->name ,
														'notification_type' => 'Badges',
														'badge_id' => 11,
														'badge_image' =>  @$BadgeUserImg->image,
														'date' => date("Y-m-d h:i:sa"),
														'status' => '0',
														
													);  
													$this->db->insert('notification', $data);	
												}	
																					
											}
											/* notification */
											?>
												<div class="col-md-2">
														<img style="height:25px; width:25px;"   src="<?php echo base_url(); ?>upload/badge/thumb/<?php echo @$BadgeUserImg->image; ?>" />
													</div>   
													<div class="col-md-10">
														<p><?php echo @$BadgeUserName->name; ?></p>
													</div> 
												
											<?php
										}
										
										$CheckUserDet = $this->admin_model->check_userprofile_complete();
										$userOverSixMonth = $this->admin_model->get_user_over_six_month();
									
										if($userOverSixMonth > 6)
										{
											$qryBadName = "SELECT * from badge_types where id=17 and deleted=0";
											 $qryBadImg = "SELECT * from badges where badge_type_id=17 and status=0 and deleted=0";
											
											$query = $this->db->query($qryBadName);
											$query1 = $this->db->query($qryBadImg);
											$BadgeUserName =  $query->row();
											$BadgeUserImg =  $query1->row();
											
											/* notification */
											$this->db->where('user_id',$_SESSION['front_user_id']);
											 $ans = $this->db->get('user_notification');
											 $NotifiRow = $ans->row();
											 $commeNot = $NotifiRow->badge_notification;
											 
											 
											 $this->db->where('user_id',$_SESSION['front_user_id']);
											 $this->db->where('badge_id',17);
											 $NotiResult = $this->db->get('notification');
											 $CheckNotifi = $NotiResult->result_array();
											
											if($commeNot == 1)
											{	
												if($CheckNotifi == null)
												{
													$data = array(
														'user_id' => $_SESSION['front_user_id'],
														'notification' => @$BadgeUserName->name ,
														'notification_type' => 'Badges',
														'badge_id' => 17,
														'badge_image' =>  @$BadgeUserImg->image,
														'date' => date("Y-m-d h:i:sa"),
														'status' => '0',
														
													);  
													$this->db->insert('notification', $data);	
												}	
																					
											}
											/* notification */
											?>
												<div class="col-md-2">
														<img style="height:25px; width:25px;"   src="<?php echo base_url(); ?>upload/badge/thumb/<?php echo @$BadgeUserImg->image; ?>" />
													</div>   
													<div class="col-md-10">
														<p><?php echo @$BadgeUserName->name; ?></p>
													</div> 
											<?php
										}
										if($userOverSixMonth > 12)
										{
											$qryBadName = "SELECT * from badge_types where id=18 and deleted=0";
											 $qryBadImg = "SELECT * from badges where badge_type_id=18 and status=0 and deleted=0";
											
											$query = $this->db->query($qryBadName);
											$query1 = $this->db->query($qryBadImg);
											$BadgeUserName =  $query->row();
											$BadgeUserImg =  $query1->row();
											
											/* notification */
											$this->db->where('user_id',$_SESSION['front_user_id']);
											 $ans = $this->db->get('user_notification');
											 $NotifiRow = $ans->row();
											 $commeNot = $NotifiRow->badge_notification;
											 
											 
											 $this->db->where('user_id',$_SESSION['front_user_id']);
											 $this->db->where('badge_id',12);
											 $NotiResult = $this->db->get('notification');
											 $CheckNotifi = $NotiResult->result_array();
											
											if($commeNot == 1)
											{	
												if($CheckNotifi == null)
												{
													$data = array(
														'user_id' => $_SESSION['front_user_id'],
														'notification' => @$BadgeUserName->name ,
														'notification_type' => 'Badges',
														'badge_id' => 12,
														'badge_image' =>  @$BadgeUserImg->image,
														'date' => date("Y-m-d h:i:sa"),
														'status' => '0',
														
													);  
													$this->db->insert('notification', $data);	
												}	
																					
											}
											/* notification */
											?>
												<div class="col-md-2">
														<img style="height:25px; width:25px;"   src="<?php echo base_url(); ?>upload/badge/thumb/<?php echo @$BadgeUserImg->image; ?>" />
													</div>   
													<div class="col-md-10">
														<p><?php echo @$BadgeUserName->name; ?></p>
													</div> 
											<?php
										}
										
										$CountCommDeal = $this->admin_model->get_badged_base_Comment();
										$CountCommVoucher = $this->admin_model->get_badged_base_voucher();
										$TotalComment = $CountCommDeal + $CountCommVoucher;
										
										if($TotalComment >= 50)
										{
											$qryBadName = "SELECT * from badge_types where id=8 and deleted=0";
											 $qryBadImg = "SELECT * from badges where badge_type_id=8 and status=0 and deleted=0";
											
											$query = $this->db->query($qryBadName);
											$query1 = $this->db->query($qryBadImg);
											$BadgeUserName =  $query->row();
											$BadgeUserImg =  $query1->row();
											/* notification */
											$this->db->where('user_id',$_SESSION['front_user_id']);
											 $ans = $this->db->get('user_notification');
											 $NotifiRow = $ans->row();
											 $commeNot = $NotifiRow->badge_notification;
											 
											 
											 $this->db->where('user_id',$_SESSION['front_user_id']);
											 $this->db->where('badge_id',8);
											 $NotiResult = $this->db->get('notification');
											 $CheckNotifi = $NotiResult->result_array();
											
											if($commeNot == 1)
											{	
												if($CheckNotifi == null)
												{
													$data = array(
														'user_id' => $_SESSION['front_user_id'],
														'notification' => @$BadgeUserName->name ,
														'notification_type' => 'Badges',
														'badge_id' => 8,
														'badge_image' =>  @$BadgeUserImg->image,
														'date' => date("Y-m-d h:i:sa"),
														'status' => '0',
														
													);  
													$this->db->insert('notification', $data);	
												}	
																					
											}
											/* notification */
											?>
											<div class="col-md-2">
														<img style="height:25px; width:25px;"   src="<?php echo base_url(); ?>upload/badge/thumb/<?php echo @$BadgeUserImg->image; ?>" />
													</div>   
													<div class="col-md-10">
														<p><?php echo @$BadgeUserName->name; ?></p>
													</div> 
											<?php
											
										}
										if($TotalComment >= 100)
										{
											$qryBadName = "SELECT * from badge_types where id=7 and deleted=0";
											 $qryBadImg = "SELECT * from badges where badge_type_id=7 and status=0 and deleted=0";
											
											$query = $this->db->query($qryBadName);
											$query1 = $this->db->query($qryBadImg);
											$BadgeUserName =  $query->row();
											$BadgeUserImg =  $query1->row();
											
											/* notification */
											$this->db->where('user_id',$_SESSION['front_user_id']);
											 $ans = $this->db->get('user_notification');
											 $NotifiRow = $ans->row();
											 $commeNot = $NotifiRow->badge_notification;
											 
											 
											 $this->db->where('user_id',$_SESSION['front_user_id']);
											 $this->db->where('badge_id',7);
											 $NotiResult = $this->db->get('notification');
											 $CheckNotifi = $NotiResult->result_array();
											
											if($commeNot == 1)
											{	
												if($CheckNotifi == null)
												{
													$data = array(
														'user_id' => $_SESSION['front_user_id'],
														'notification' => @$BadgeUserName->name ,
														'notification_type' => 'Badges',
														'badge_id' => 7,
														'badge_image' =>  @$BadgeUserImg->image,
														'date' => date("Y-m-d h:i:sa"),
														'status' => '0',
														
													);  
													$this->db->insert('notification', $data);	
												}	
																					
											}
											/* notification */
											?>
											<div class="col-md-2">
														<img style="height:25px; width:25px;"   src="<?php echo base_url(); ?>upload/badge/thumb/<?php echo @$BadgeUserImg->image; ?>" />
													</div>   
													<div class="col-md-10">
														<p><?php echo @$BadgeUserName->name; ?></p>
													</div> 
											<?php
											
										}
										if($TotalComment >= 200)
										{
											$qryBadName = "SELECT * from badge_types where id=6 and deleted=0";
											 $qryBadImg = "SELECT * from badges where badge_type_id=6 and status=0 and deleted=0";
											
											$query = $this->db->query($qryBadName);
											$query1 = $this->db->query($qryBadImg);
											$BadgeUserName =  $query->row();
											$BadgeUserImg =  $query1->row();
											
											/* notification */
											$this->db->where('user_id',$_SESSION['front_user_id']);
											 $ans = $this->db->get('user_notification');
											 $NotifiRow = $ans->row();
											 $commeNot = $NotifiRow->badge_notification;
											 
											 
											 $this->db->where('user_id',$_SESSION['front_user_id']);
											 $this->db->where('badge_id',6);
											 $NotiResult = $this->db->get('notification');
											 $CheckNotifi = $NotiResult->result_array();
											
											if($commeNot == 1)
											{	
												if($CheckNotifi == null)
												{
													$data = array(
														'user_id' => $_SESSION['front_user_id'],
														'notification' => @$BadgeUserName->name ,
														'notification_type' => 'Badges',
														'badge_id' => 6,
														'badge_image' =>  @$BadgeUserImg->image,
														'date' => date("Y-m-d h:i:sa"),
														'status' => '0',
														
													);  
													$this->db->insert('notification', $data);	
												}	
																					
											}
											/* notification */
											?>
											<div class="col-md-2">
														<img style="height:25px; width:25px;"   src="<?php echo base_url(); ?>upload/badge/thumb/<?php echo @$BadgeUserImg->image; ?>" />
													</div>   
													<div class="col-md-10">
														<p><?php echo @$BadgeUserName->name; ?></p>
													</div> 
											<?php
											
										}
										
										$CountlikeDeal = $this->admin_model->get_badged_base_like_deal();
										$Countlikevoucher = $this->admin_model->get_badged_base_like_comment();
										$totalLike = $CountlikeDeal + $Countlikevoucher;
										if($totalLike >= 50)
										{
											$qryBadName = "SELECT * from badge_types where id=12 and deleted=0";
											 $qryBadImg = "SELECT * from badges where badge_type_id=12 and status=0 and deleted=0";
											
											$query = $this->db->query($qryBadName);
											$query1 = $this->db->query($qryBadImg);
											$BadgeUserName =  $query->row();
											$BadgeUserImg =  $query1->row();
											
											/* notification */
											$this->db->where('user_id',$_SESSION['front_user_id']);
											 $ans = $this->db->get('user_notification');
											 $NotifiRow = $ans->row();
											 $commeNot = $NotifiRow->badge_notification;
											 
											 
											 $this->db->where('user_id',$_SESSION['front_user_id']);
											 $this->db->where('badge_id',12);
											 $NotiResult = $this->db->get('notification');
											 $CheckNotifi = $NotiResult->result_array();
											
											if($commeNot == 1)
											{	
												if($CheckNotifi == null)
												{
													$data = array(
														'user_id' => $_SESSION['front_user_id'],
														'notification' => @$BadgeUserName->name ,
														'notification_type' => 'Badges',
														'badge_id' => 12,
														'badge_image' =>  @$BadgeUserImg->image,
														'date' => date("Y-m-d h:i:sa"),
														'status' => '0',
														
													);  
													$this->db->insert('notification', $data);	
												}	
																					
											}
											/* notification */
											?>
												<div class="col-md-2">
														<img style="height:25px; width:25px;"   src="<?php echo base_url(); ?>upload/badge/thumb/<?php echo @$BadgeUserImg->image; ?>" />
													</div>   
													<div class="col-md-10">
														<p><?php echo @$BadgeUserName->name; ?></p>
													</div> 
											<?php
											
										}
										if($totalLike >= 100)
										{
											$qryBadName = "SELECT * from badge_types where id=13 and deleted=0";
											 $qryBadImg = "SELECT * from badges where badge_type_id=13 and status=0 and deleted=0";
											
											$query = $this->db->query($qryBadName);
											$query1 = $this->db->query($qryBadImg);
											$BadgeUserName =  $query->row();
											$BadgeUserImg =  $query1->row();
											
											/* notification */
											$this->db->where('user_id',$_SESSION['front_user_id']);
											 $ans = $this->db->get('user_notification');
											 $NotifiRow = $ans->row();
											 $commeNot = $NotifiRow->badge_notification;
											 
											 
											 $this->db->where('user_id',$_SESSION['front_user_id']);
											 $this->db->where('badge_id',13);
											 $NotiResult = $this->db->get('notification');
											 $CheckNotifi = $NotiResult->result_array();
											
											if($commeNot == 1)
											{	
												if($CheckNotifi == null)
												{
													$data = array(
														'user_id' => $_SESSION['front_user_id'],
														'notification' => @$BadgeUserName->name ,
														'notification_type' => 'Badges',
														'badge_id' => 13,
														'badge_image' =>  @$BadgeUserImg->image,
														'date' => date("Y-m-d h:i:sa"),
														'status' => '0',
														
													);  
													$this->db->insert('notification', $data);	
												}	
																					
											}
											/* notification */
											?>
											<div class="col-md-2">
														<img style="height:25px; width:25px;"   src="<?php echo base_url(); ?>upload/badge/thumb/<?php echo @$BadgeUserImg->image; ?>" />
													</div>   
													<div class="col-md-10">
														<p><?php echo @$BadgeUserName->name; ?></p>
													</div> 
											<?php
											
										}
										if($totalLike >= 200)
										{
											$qryBadName = "SELECT * from badge_types where id=14 and deleted=0";
											 $qryBadImg = "SELECT * from badges where badge_type_id=14 and status=0 and deleted=0";
											
											$query = $this->db->query($qryBadName);
											$query1 = $this->db->query($qryBadImg);
											$BadgeUserName =  $query->row();
											$BadgeUserImg =  $query1->row();
											
											/* notification */
											$this->db->where('user_id',$_SESSION['front_user_id']);
											 $ans = $this->db->get('user_notification');
											 $NotifiRow = $ans->row();
											 $commeNot = $NotifiRow->badge_notification;
											 
											 
											 $this->db->where('user_id',$_SESSION['front_user_id']);
											 $this->db->where('badge_id',14);
											 $NotiResult = $this->db->get('notification');
											 $CheckNotifi = $NotiResult->result_array();
											
											if($commeNot == 1)
											{	
												if($CheckNotifi == null)
												{
													$data = array(
														'user_id' => $_SESSION['front_user_id'],
														'notification' => @$BadgeUserName->name ,
														'notification_type' => 'Badges',
														'badge_id' => 14,
														'badge_image' =>  @$BadgeUserImg->image,
														'date' => date("Y-m-d h:i:sa"),
														'status' => '0',
														
													);  
													$this->db->insert('notification', $data);	
												}	
																					
											}
											/* notification */
											?>
											<div class="col-md-2">
														<img style="height:25px; width:25px;"   src="<?php echo base_url(); ?>upload/badge/thumb/<?php echo @$BadgeUserImg->image; ?>" />
													</div>   
													<div class="col-md-10">
														<p><?php echo @$BadgeUserName->name; ?></p>
													</div> 
											<?php
											
										}
										
										$CheckUserBirthdat = $this->admin_model->check_userbirthday();
										if($CheckUserBirthdat == 'yes')
										{
											$qryBadName = "SELECT * from badge_types where id=22 and deleted=0";
											 $qryBadImg = "SELECT * from badges where badge_type_id=22 and status=0 and deleted=0";
											
											$query = $this->db->query($qryBadName);
											$query1 = $this->db->query($qryBadImg);
											$BadgeUserName =  $query->row();
											$BadgeUserImg =  $query1->row();
											/* notification */
											$this->db->where('user_id',$_SESSION['front_user_id']);
											 $ans = $this->db->get('user_notification');
											 $NotifiRow = $ans->row();
											 $commeNot = $NotifiRow->badge_notification;
											 
											 
											 $this->db->where('user_id',$_SESSION['front_user_id']);
											 $this->db->where('badge_id',22);
											 $NotiResult = $this->db->get('notification');
											 $CheckNotifi = $NotiResult->result_array();
											
											if($commeNot == 1)
											{	
												if($CheckNotifi == null)
												{
													$data = array(
														'user_id' => $_SESSION['front_user_id'],
														'notification' => @$BadgeUserName->name ,
														'notification_type' => 'Badges',
														'badge_id' => 22,
														'badge_image' =>  @$BadgeUserImg->image,
														'date' => date("Y-m-d h:i:sa"),
														'status' => '0',
														
													);  
													$this->db->insert('notification', $data);	
												}	
																					
											}
											/* notification */
											
											?>
											<div class="col-md-2">
														<img style="height:25px; width:25px;"   src="<?php echo base_url(); ?>upload/badge/thumb/<?php echo @$BadgeUserImg->image; ?>" />
													</div>   
													<div class="col-md-10">
														<p><?php echo @$BadgeUserName->name; ?></p>
													</div> 
											<?php
											
										}
										
										$CheckUserfirstDeal = $this->admin_model->first_deal_post();
										$CheckUserfirstVoucher = $this->admin_model->first_voucher_post();
										
											
										if($CheckUserfirstDeal == 1 and $CheckUserfirstVoucher ==1)
										{
											$qryBadName = "SELECT * from badge_types where id=1 and deleted=0";
											 $qryBadImg = "SELECT * from badges where badge_type_id=1 and status=0 and deleted=0";
											
											$query = $this->db->query($qryBadName);
											$query1 = $this->db->query($qryBadImg);
											$BadgeUserName =  $query->row();
											$BadgeUserImg =  $query1->row();
											
											/* notification */
											$this->db->where('user_id',$_SESSION['front_user_id']);
											 $ans = $this->db->get('user_notification');
											 $NotifiRow = $ans->row();
											 $commeNot = $NotifiRow->badge_notification;
											 
											 
											 $this->db->where('user_id',$_SESSION['front_user_id']);
											 $this->db->where('badge_id',1);
											 $NotiResult = $this->db->get('notification');
											 $CheckNotifi = $NotiResult->result_array();
											
											if($commeNot == 1)
											{	
												if($CheckNotifi == null)
												{
													$data = array(
														'user_id' => $_SESSION['front_user_id'],
														'notification' => @$BadgeUserName->name ,
														'notification_type' => 'Badges',
														'badge_id' => 1,
														'badge_image' =>  @$BadgeUserImg->image,
														'date' => date("Y-m-d h:i:sa"),
														'status' => '0',
														
													);  
													$this->db->insert('notification', $data);	
												}	
																					
											}
											/* notification */
											
											?>
											<div class="col-md-2">
														<img style="height:25px; width:25px;"   src="<?php echo base_url(); ?>upload/badge/thumb/<?php echo @$BadgeUserImg->image; ?>" />
													</div>   
													<div class="col-md-10">
														<p><?php echo @$BadgeUserName->name; ?></p>
													</div> 
											<?php
										}
										
										$DealOverHund = $this->admin_model->get_over_hundred_degree_post();
										if($CheckUserfirstDeal == 10)
										{
											foreach($DealOverHund as $de)
											{
												if($de['hot_count'] >=100)
												{
													$qryBadName = "SELECT * from badge_types where id=2 and deleted=0";
													 $qryBadImg = "SELECT * from badges where badge_type_id=2 and status=0 and deleted=0";
													
													$query = $this->db->query($qryBadName);
													$query1 = $this->db->query($qryBadImg);
													$BadgeUserName =  $query->row();
													$BadgeUserImg =  $query1->row();
													
													/* notification */
													$this->db->where('user_id',$_SESSION['front_user_id']);
													 $ans = $this->db->get('user_notification');
													 $NotifiRow = $ans->row();
													 $commeNot = $NotifiRow->badge_notification;
													 
													 
													 $this->db->where('user_id',$_SESSION['front_user_id']);
													 $this->db->where('badge_id',2);
													 $NotiResult = $this->db->get('notification');
													 $CheckNotifi = $NotiResult->result_array();
													
													if($commeNot == 1)
													{	
														if($CheckNotifi == null)
														{
															$data = array(
																'user_id' => $_SESSION['front_user_id'],
																'notification' => @$BadgeUserName->name ,
																'notification_type' => 'Badges',
																'badge_id' => 2,
																'badge_image' =>  @$BadgeUserImg->image,
																'date' => date("Y-m-d h:i:sa"),
																'status' => '0',
																
															);  
															$this->db->insert('notification', $data);	
														}	
																							
													}
											/* notification */
													
													?>
												<div class="col-md-2">
														<img style="height:25px; width:25px;"   src="<?php echo base_url(); ?>upload/badge/thumb/<?php echo @$BadgeUserImg->image; ?>" />
													</div>   
													<div class="col-md-10">
														<p><?php echo @$BadgeUserName->name; ?></p>
													</div> 
											<?php
												}
												
												
											}
										}
									
									$AskDiscussion = $this->admin_model->first_discution_ask_post();	
									//print_r($AskDiscussion);
									if($AskDiscussion == 1)
									{
												$qryBadName = "SELECT * from badge_types where id=20 and deleted=0";
												 $qryBadImg = "SELECT * from badges where badge_type_id=20 and status=0 and deleted=0";
												
												$query = $this->db->query($qryBadName);
												$query1 = $this->db->query($qryBadImg);
												$BadgeUserName =  $query->row();
												$BadgeUserImg =  $query1->row();
												
												/* notification */
												$this->db->where('user_id',$_SESSION['front_user_id']);
												 $ans = $this->db->get('user_notification');
												 $NotifiRow = $ans->row();
												 $commeNot = $NotifiRow->badge_notification;
												 
												 
												 $this->db->where('user_id',$_SESSION['front_user_id']);
												 $this->db->where('badge_id',20);
												 $NotiResult = $this->db->get('notification');
												 $CheckNotifi = $NotiResult->result_array();
												
												if($commeNot == 1)
												{	
													if($CheckNotifi == null)
													{
														$data = array(
															'user_id' => $_SESSION['front_user_id'],
															'notification' => @$BadgeUserName->name ,
															'notification_type' => 'Badges',
															'badge_id' => 20,
															'badge_image' =>  @$BadgeUserImg->image,
															'date' => date("Y-m-d h:i:sa"),
															'status' => '0',
															
														);  
														$this->db->insert('notification', $data);	
													}	
																						
												}
										/* notification */
												
												?>
											<div class="col-md-2">
													<img style="height:25px; width:25px;"   src="<?php echo base_url(); ?>upload/badge/thumb/<?php echo @$BadgeUserImg->image; ?>" />
												</div>   
												<div class="col-md-10">
													<p><?php echo @$BadgeUserName->name; ?></p>
												</div> 
										<?php
										
									}




										
									}// end empty row
									else
									{
										echo 'There is no Badges in your Profile';
									}
									
										
									
									?>
								</div>
									
								


                    </div>
                </div>
            </div>
        </div>
        
        <?php $this->load->view('footer'); ?>
        
       
    </body>
</html>
   
<style>
@media (min-width: 0px) and (max-width: 950px) {  
.header_open_btn {
        display: none;
}
}
</style>
