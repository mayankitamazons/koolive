<?php $order_id=$_POST['s_id']; ?>
      <p style="font-size: 14px;" id="your_feedback">Your feedback is extermely important to us in order to provide best service to you</p>
      <!--center>
         <div class="row" >
            <div class="col-md-5"><button id="marchant_review_button" type="button" style="font-size: 12px;cursor: pointer;" name="marchant_review_button"  class="btn btn-primary">Review to Marchant</button></div>
            <div class="col-md-1"></div>
            <div class="col-md-5"><button id="deliveryman_review_button" type="button" style="font-size: 12px;cursor: pointer;" name="deliveryman_review_button" class="btn btn-secondary">Review to Deliveryman</button>
            </div>
      </center!-->
     
      <div id="merchant_review" style="font-size:14px;">
      <br> <center style="font-size: 14px;margin-top: -5px;text-align: center;font-weight: bold;"><b>Give feedback for service</b></center><hr>
        Q 1. Are you satisfied with the food quality?<br>
      <div class="row">
	  
      <div class="col-2 col-md-2 col-xs-2"><img id="review_q1_a1" src="assets\img\smile\laughing_green.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
      <div class="col-2 col-md-2 col-xs-2"><img id="review_q1_a2" src="assets\img\smile\happy_grey.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
      <div class="col-2 col-md-2 col-xs-2"><img id="review_q1_a3" src="assets\img\smile\surprised_grey.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
      <div class="col-2 col-md-2 col-xs-2"><img id="review_q1_a4" src="assets\img\smile\sad_grey.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
      <div class="col-2 col-md-2 col-xs-2"><img id="review_q1_a5" src="assets\img\smile\verysad_grey.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
      <div class="col-2 col-md-2 col-xs-2"></div>
      </div>
      <hr>
       Q 2. Are you happy with deliveryman service?<br> 
      <div class="row">
      <div class="col-2 col-md-2 col-xs-2"><img id="review_q2_a1" src="assets\img\smile\laughing_green.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
      <div class="col-2 col-md-2 col-xs-2"><img id="review_q2_a2" src="assets\img\smile\happy_grey.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
      <div class="col-2 col-md-2 col-xs-2"><img id="review_q2_a3" src="assets\img\smile\surprised_grey.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
      <div class="col-2 col-md-2 col-xs-2"><img id="review_q2_a4" src="assets\img\smile\sad_grey.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
      <div class="col-2 col-md-2 col-xs-2"><img id="review_q2_a5" src="assets\img\smile\verysad_grey.png" style="height: 30px;width: 30px;cursor: pointer;" /></div>
      <div class="col-2 col-md-2 col-xs-2"></div>
      </div>
		<hr>
      Q 3. Any additional comments? ?<br>
      <textarea class="form-control rounded-0" id="addiComments" rows="1"></textarea>
      <!--hr>
      Q 6. Do you allow us to contact you for further clarification ?<br>
      <input type="radio" name="clarification" value="No" checked>No</input>&nbsp;&nbsp;&nbsp;
      <input type="radio" name="clarification" value="Yes">Yes</input>
      <br!-->
      <p id="review_error" style="color: red;"></p>
      <br>
      </div> 
      <center><button id="review_check" type="button" name="review_check" onclick="checkNext()" style="color:black;"   class="btn btn-primary" order_id="<?php echo $order_id; ?>">Feedback now</button>
      <button id="review_skip" type="button" onclick="skip_review()"   class="btn btn-primary" style="color:black;"    order_id="<?php echo $order_id; ?>">Feedback later</button></center>
<br/>
<script type="text/javascript">
  
  
              //For Review Script
             var review_q1 = 0;
             var review_q2 = 0;
             var review_q3 = 0;
             var review_q4 = 0;
             var review_q5 = 0;
             var review_q6 = 0;
             var review_q7 = 0;
             var review_q8 = 0;
             var review_q9 = 0;
             var review_q10 = 0;

             var q1=1;
             var q2=1;
             var q3=1;
             var q4=1;
             var q5=1;
             var q6=1;
             var q7=1;
             var q8=1;
             
            function review_q1_clear() {
                $("#review_q1_a1").attr("src", "assets/img/smile/laughing_grey.png");
                $("#review_q1_a2").attr("src", "assets/img/smile/happy_grey.png");
                $("#review_q1_a3").attr("src", "assets/img/smile/surprised_grey.png");
                $("#review_q1_a4").attr("src", "assets/img/smile/sad_grey.png");
                $("#review_q1_a5").attr("src", "assets/img/smile/verysad_grey.png");
              }
                  function review_q2_clear() {
                $("#review_q2_a1").attr("src", "assets/img/smile/laughing_grey.png");
                $("#review_q2_a2").attr("src", "assets/img/smile/happy_grey.png");
                $("#review_q2_a3").attr("src", "assets/img/smile/surprised_grey.png");
                $("#review_q2_a4").attr("src", "assets/img/smile/sad_grey.png");
                $("#review_q2_a5").attr("src", "assets/img/smile/verysad_grey.png");
              }


              $(document).ready(function(){
                
              //update image on click
              //q1
              $("#review_q1_a1").click(function(){
                  review_q1_clear();
                  review_q1=1;
                  q1=1;
                  $("#review_q1_a1").attr("src", "assets/img/smile/laughing_green.png");
              });
              $("#review_q1_a2").click(function(){
                  review_q1_clear();
                  review_q1=1;
                  q1=2;
                  $("#review_q1_a2").attr("src", "assets/img/smile/happy_green.png");
              });
               $("#review_q1_a3").click(function(){
                  review_q1_clear();
                  review_q1=1;
                  q1=3;
                  $("#review_q1_a3").attr("src", "assets/img/smile/surprised_green.png");
              }); 
               $("#review_q1_a4").click(function(){
                  review_q1_clear();
                  review_q1=1;
                  q1=4;
                  $("#review_q1_a4").attr("src", "assets/img/smile/sad_green.png");
              }); 
               $("#review_q1_a5").click(function(){
                  review_q1_clear();
                  review_q1=1;
                  q1=5;
                  $("#review_q1_a5").attr("src", "assets/img/smile/verysad_green.png");
              });
               //q2
                $("#review_q2_a1").click(function(){
                  review_q2_clear();
                  review_q2=1;
                  q2=1;
                  $("#review_q2_a1").attr("src", "assets/img/smile/laughing_green.png");
              });
              $("#review_q2_a2").click(function(){
                  review_q2_clear();
                  review_q2=1;
                  q2=2;
                  $("#review_q2_a2").attr("src", "assets/img/smile/happy_green.png");
              });
               $("#review_q2_a3").click(function(){
                  review_q2_clear();
                  review_q2=1;
                  q2=3;
                  $("#review_q2_a3").attr("src", "assets/img/smile/surprised_green.png");
              }); 
               $("#review_q2_a4").click(function(){
                  review_q2_clear();
                  review_q2=1;
                  q2=4;
                  $("#review_q2_a4").attr("src", "assets/img/smile/sad_green.png");
              }); 
               $("#review_q2_a5").click(function(){
                  review_q2_clear();
                  review_q2=1;
                  q2=5;
                  $("#review_q2_a5").attr("src", "assets/img/smile/verysad_green.png");
              });


               



            });
          
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
         function skip_review(){
           var s_id = $("#review_check").attr('order_id');
           $.ajax({
                      
                      url :'skiped_review.php',
                      type:'POST',
                      data:{
                              order_id : s_id,
                            },
                      success:function(response){
                        $('#reviewdetailmodel').modal('hide');
                        }     
                    });
          
         }
       //nikhil-->
          function checkNext(){
              var s_id = $("#review_check").attr('order_id');
              $("#review_error").html("");
			  $('#review_check').hide();
			  $('#review_skip').hide();
              
            var ele = document.getElementsByName('clarification'); 
            var q9;
            for(i = 0; i < ele.length; i++) { 
                if(ele[i].checked) 
               q9=ele[i].value; 
          }
            var q10 = $.trim($("#addiComments").val());
            
              $.ajax({
                      
                      url :'review_insert.php',
                      type:'POST',
                      data:{
                              order_id : s_id,
                              q1 : q1,
                              q2 : q2,
                              q3 : q2,
                              q4 : q4,
                              q5 : q5,
                              q6 : q6,
                              q7 : q7,
                              q8 : q8,
                              q9 : q9,
                              remark : q10,

                            },  
                      success:function(response){
                        $("#review_model").html("<center><p style='color:green;'>Thanks for Review to improve our System. We appreciate your feedback.</p></center>"); 
                        $("#review_check").css("display", "none");
                        
                        setTimeout(function(){
							var s_token=generatetokenno(16);
						var r_url="https://www.koofamilies.com/orderlist.php?vs="+s_token;
						window.location.replace(r_url);
                        }, 5000);

                        }     
                    });   
          }
</script>
         
<!--nikhil--->