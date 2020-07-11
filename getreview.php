<?php
include("config.php");
$order_id = $_POST['s_id'];
$login_as = $_POST['login_as'];
//$order_id =21458;
$sql = "select * from feedback where order_id='$order_id'";
if($parent_data = mysqli_fetch_assoc(mysqli_query($conn, "select * from feedback where order_id='$order_id'")))
{

    //echo $parent_data['q1'].",".$parent_data['q2'].",".$parent_data['q3'].",".$parent_data['q4'].",".$parent_data['q5'].",".$parent_data['q6'].",".$parent_data['q7'].",".$parent_data['q8'].",".$parent_data['q9'].",".$parent_data['q10'];
?>      
         <!--center>
           <div class="row" >
              <div class="col-md-5"><button id="marchant_review_button" type="button" style="font-size: 12px;cursor: pointer;" name="marchant_review_button"  class="btn btn-primary">Review  for Marchant</button></div>
              <div class="col-md-1"></div>
			  <?php ?>
              <div class="col-md-5"><button id="deliveryman_review_button" type="button" style="font-size: 12px;cursor: pointer;" name="deliveryman_review_button" class="btn btn-secondary">Review for Deliveryman</button>
           </div>
          </center!-->
        <div id="merchant_review" >
		<?php if($login_as==1){ ?>
          <br><center><b>Feedback for service</b></center><hr>
		<?php } ?>
              Q 1. Are you satisfied with the food quality?<br>
            <div class="row">
            <?php if ($parent_data['q1']==1){ ?>
              <div class="col-3 col-md-2"><img id="review_q1_a1" src="assets\img\smile\laughing_green.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>

          		<?php }
          		else{
              ?>
               <div class="col-3 col-md-2"><img id="review_q1_a1" src="assets\img\smile\laughing_grey.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
          <?php } ?>

<?php if ($parent_data['q1']==2){ ?>
              <div class="col-3 col-md-2"><img id="review_q1_a2" src="assets\img\smile\happy_green.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
        <?php }
          		else{
              ?>
              <div class="col-3 col-md-2"><img id="review_q1_a2" src="assets\img\smile\happy_grey.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
        <?php } ?>

<?php if ($parent_data['q1']==3){ ?>
              <div class="col-3 col-md-2"><img  src="assets\img\smile\surprised_green.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>

  <?php }
          		else{
              ?>    
              <div class="col-3 col-md-2"><img  src="assets\img\smile\surprised_grey.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
           <?php } ?>  
           <?php if ($parent_data['q1']==4){ ?> 
              <div class="col-3 col-md-2"><img  src="assets\img\smile\sad_green.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
              <?php }
          		else{
              ?>  
              <div class="col-3 col-md-2"><img  src="assets\img\smile\sad_grey.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
              <?php }?>
            <?php if ($parent_data['q1']==5){ ?>    
              <div class="col-3 col-md-2"><img  src="assets\img\smile\verysad_green.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
               <?php }
          		else{
              ?> 
              <div class="col-3 col-md-2"><img  src="assets\img\smile\verysad_grey.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
              <?php }?>
              <div class="col-3 col-md-2"></div>
              
            </div>
            <hr>      
                   Q 2. Are you happy with deliveryman service?<br> 
            <div class="row">
            <?php if ($parent_data['q2']==1){ ?>
              <div class="col-3 col-md-2"><img id="review_q1_a1" src="assets\img\smile\laughing_green.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>

          		<?php }
          		else{
              ?>
               <div class="col-3 col-md-2"><img id="review_q1_a1" src="assets\img\smile\laughing_grey.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
          <?php } ?>

<?php if ($parent_data['q2']==2){ ?>
              <div class="col-3 col-md-2"><img id="review_q1_a2" src="assets\img\smile\happy_green.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
        <?php }
          		else{
              ?>
              <div class="col-3 col-md-2"><img id="review_q1_a2" src="assets\img\smile\happy_grey.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
        <?php } ?>

<?php if ($parent_data['q2']==3){ ?>
              <div class="col-3 col-md-2"><img  src="assets\img\smile\surprised_green.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>

  <?php }
          		else{
              ?>    
              <div class="col-3 col-md-2"><img  src="assets\img\smile\surprised_grey.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
           <?php } ?>  
           <?php if ($parent_data['q2']==4){ ?> 
              <div class="col-3 col-md-2"><img  src="assets\img\smile\sad_green.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
              <?php }
          		else{
              ?>  
              <div class="col-3 col-md-2"><img  src="assets\img\smile\sad_grey.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
              <?php }?>
            <?php if ($parent_data['q2']==5){ ?>    
              <div class="col-3 col-md-2"><img  src="assets\img\smile\verysad_green.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
               <?php }
          		else{
              ?> 
              <div class="col-3 col-md-2"><img  src="assets\img\smile\verysad_grey.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
              <?php }?>
              <div class="col-3 col-md-2"></div>
              
            </div>
            <hr> 
			 Q 3. Any additional comments? ?<br>
      <textarea class="form-control rounded-0" id="addiComments" rows="1" readonly><?php echo  $parent_data['remark'];?></textarea>
		</br>
		</br>
        </div>
		<?php if($login_as==1){ ?>
        <div id="deliveryman_review" style="font-size: 11px;display: none;">
		
                 <br> <center style="font-size: 12px;margin-top: -5px;"><b>Feedback for deliveryman</b></center><hr>

                  Q 1. Did the deliveryman deliver the foods/goods in time ?<br>
                       <div class="row">
            <?php if ($parent_data['q5']==1){ ?>
              <div class="col-3 col-md-2"><img id="review_q1_a1" src="assets\img\smile\laughing_green.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>

          		<?php }
          		else{
              ?>
               <div class="col-3 col-md-2"><img id="review_q1_a1" src="assets\img\smile\laughing_grey.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
          <?php } ?>

<?php if ($parent_data['q5']==2){ ?>
              <div class="col-3 col-md-2"><img id="review_q1_a2" src="assets\img\smile\happy_green.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
        <?php }
          		else{
              ?>
              <div class="col-3 col-md-2"><img id="review_q1_a2" src="assets\img\smile\happy_grey.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
        <?php } ?>

<?php if ($parent_data['q5']==3){ ?>
              <div class="col-3 col-md-2"><img  src="assets\img\smile\surprised_green.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>

  <?php }
          		else{
              ?>    
              <div class="col-3 col-md-2"><img  src="assets\img\smile\surprised_grey.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
           <?php } ?>  
           <?php if ($parent_data['q5']==4){ ?> 
              <div class="col-3 col-md-2"><img  src="assets\img\smile\sad_grey.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
              <?php }
          		else{
              ?>  
              <div class="col-3 col-md-2"><img  src="assets\img\smile\sad_grey.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
              <?php }?>
            <?php if ($parent_data['q5']==5){ ?>    
              <div class="col-3 col-md-2"><img  src="assets\img\smile\verysad_green.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
               <?php }
          		else{
              ?> 
              <div class="col-3 col-md-2"><img  src="assets\img\smile\verysad_grey.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
              <?php }?>
              <div class="col-3 col-md-2"></div>
              
            </div>
                  <hr>

                  Q 2. Did our deliveryman wearing facemask all the times ?<br>
                        <div class="row">
            <?php if ($parent_data['q6']==1){ ?>
              <div class="col-3 col-md-2"><img id="review_q1_a1" src="assets\img\smile\laughing_green.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>

          		<?php }
          		else{
              ?>
               <div class="col-3 col-md-2"><img id="review_q1_a1" src="assets\img\smile\laughing_grey.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
          <?php } ?>

<?php if ($parent_data['q6']==2){ ?>
              <div class="col-3 col-md-2"><img id="review_q1_a2" src="assets\img\smile\happy_green.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
        <?php }
          		else{
              ?>
              <div class="col-3 col-md-2"><img id="review_q1_a2" src="assets\img\smile\happy_grey.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
        <?php } ?>

<?php if ($parent_data['q6']==3){ ?>
              <div class="col-3 col-md-2"><img  src="assets\img\smile\surprised_green.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>

  <?php }
          		else{
              ?>    
              <div class="col-3 col-md-2"><img  src="assets\img\smile\surprised_grey.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
           <?php } ?>  
           <?php if ($parent_data['q6']==4){ ?> 
              <div class="col-3 col-md-2"><img  src="assets\img\smile\sad_green.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
              <?php }
          		else{
              ?>  
              <div class="col-3 col-md-2"><img  src="assets\img\smile\sad_grey.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
              <?php }?>
            <?php if ($parent_data['q6']==5){ ?>    
              <div class="col-3 col-md-2"><img  src="assets\img\smile\verysad_green.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
               <?php }
          		else{
              ?> 
              <div class="col-3 col-md-2"><img  src="assets\img\smile\verysad_grey.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
              <?php }?>
              <div class="col-3 col-md-2"></div>
              
            </div>
                  <hr>

                  Q 3. Did our deliveryman use plastic bag if you are using cash to pay ?<br>
                        <div class="row">
            <?php if ($parent_data['q7']==1){ ?>
              <div class="col-3 col-md-2"><img id="review_q1_a1" src="assets\img\smile\laughing_green.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>

          		<?php }
          		else{
              ?>
               <div class="col-3 col-md-2"><img id="review_q1_a1" src="assets\img\smile\laughing_grey.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
          <?php } ?>

<?php if ($parent_data['q7']==2){ ?>
              <div class="col-3 col-md-2"><img id="review_q1_a2" src="assets\img\smile\happy_green.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
        <?php }
          		else{
              ?>
              <div class="col-3 col-md-2"><img id="review_q1_a2" src="assets\img\smile\happy_grey.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
        <?php } ?>

<?php if ($parent_data['q7']==3){ ?>
              <div class="col-3 col-md-2"><img  src="assets\img\smile\surprised_green.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>

  <?php }
          		else{
              ?>    
              <div class="col-3 col-md-2"><img  src="assets\img\smile\surprised_grey.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
           <?php } ?>  
           <?php if ($parent_data['q7']==4){ ?> 
              <div class="col-3 col-md-2"><img  src="assets\img\smile\sad_green.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
              <?php }
          		else{
              ?>  
              <div class="col-3 col-md-2"><img  src="assets\img\smile\sad_grey.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
              <?php }?>
            <?php if ($parent_data['q7']==5){ ?>    
              <div class="col-3 col-md-2"><img  src="assets\img\smile\verysad_green.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
               <?php }
          		else{
              ?> 
              <div class="col-3 col-md-2"><img  src="assets\img\smile\verysad_grey.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
              <?php }?>
              <div class="col-3 col-md-2"></div>
              
            </div>
                  <hr>

                  Q 4. Did our deliveryman behave professional during the whole process? ?<br>
                        <div class="row">
            <?php if ($parent_data['q8']==1){ ?>
              <div class="col-3 col-md-2"><img id="review_q1_a1" src="assets\img\smile\laughing_green.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>

          		<?php }
          		else{
              ?>
               <div class="col-3 col-md-2"><img id="review_q1_a1" src="assets\img\smile\laughing_grey.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
          <?php } ?>

<?php if ($parent_data['q8']==2){ ?>
              <div class="col-3 col-md-2"><img id="review_q1_a2" src="assets\img\smile\happy_green.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
        <?php }
          		else{
              ?>
              <div class="col-3 col-md-2"><img id="review_q1_a2" src="assets\img\smile\happy_grey.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
        <?php } ?>

<?php if ($parent_data['q8']==3){ ?>
              <div class="col-3 col-md-2"><img  src="assets\img\smile\surprised_green.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>

  <?php }
          		else{
              ?>    
              <div class="col-3 col-md-2"><img  src="assets\img\smile\surprised_grey.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
           <?php } ?>  
           <?php if ($parent_data['q8']==4){ ?> 
              <div class="col-3 col-md-2"><img  src="assets\img\smile\sad_green.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
              <?php }
          		else{
              ?>  
              <div class="col-3 col-md-2"><img  src="assets\img\smile\sad_grey.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
              <?php }?>
            <?php if ($parent_data['q8']==5){ ?>    
              <div class="col-3 col-md-2"><img  src="assets\img\smile\verysad_green.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
               <?php }
          		else{
              ?> 
              <div class="col-3 col-md-2"><img  src="assets\img\smile\verysad_grey.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
              <?php }?>
              <div class="col-3 col-md-2"></div>
              
            </div>
                  <hr>

                  Q 5. Any additional comments? ?<br>

                
                     <textarea class="form-control rounded-0" id="addiComments"  rows="1" disabled=""><?php echo $parent_data['q10']; ?></textarea>
                  <hr>
                  Q 6. Do you allow us to contact you for further clarification ?<br>
                  <input type="radio" name="clarification" value="No" <?php if ($parent_data['q9']=='No'){ ?>checked <?php } ?> disabled="">No</input>&nbsp;&nbsp;&nbsp;
                  <input type="radio" name="clarification" value="Yes" <?php if ($parent_data['q9']=='Yes'){ ?>checked <?php } ?> disabled="">Yes</input>
                  <br>
                 
        </div>
		<?php } ?>		
      </div>
     <script src="js/jquery.form.js"></script> 
      <script type="text/javascript">
      	    $("#marchant_review_button").click(function(){
              $("#merchant_review").css("display", "block");
              $("#deliveryman_review").css("display", "none");

               if ( $("#merchant_review_button").hasClass('btn-secondary') )  
                $("#merchant_review_button").addClass('btn-primary').removeClass('btn-secondary');

               if ( $("#deliveryman_review_button").hasClass('btn-primary') )  
                $("#deliveryman_review_button").addClass('btn-secondary').removeClass('btn-primary');

              

          });

          $("#deliveryman_review_button").click(function(){
              $("#merchant_review").css("display", "none");
              $("#deliveryman_review").css("display", "block");
               $("#your_feedback").css("display", "none");

               if ( $("#merchant_review_button").hasClass('btn-primary') )  
                $("#merchant_review_button").addClass('btn-secondary').removeClass('btn-primary');

               if ( $("#deliveryman_review_button").hasClass('btn-secondary') )  
                $("#deliveryman_review_button").addClass('btn-primary').removeClass('btn-secondary');
              
          });
          
      </script>
      <script src="js/jquery.form.js"></script> 
<?php
} 
else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}
?>