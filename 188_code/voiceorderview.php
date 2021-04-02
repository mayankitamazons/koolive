<?php  
include("config.php"); 
$merchant_tab="y";
$me="voice_order_view";
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");   

$current_time = date('Y-m-d H:i:s');
if(isset($_GET['did']))
{
	 $did=$_GET['did'];   
	 
	include_once('dlogin.php');
	// print_R($_SESSION);
	// die;
	
}
  if(!empty($_SESSION['merchant_id'])){
      $id =  $_SESSION['merchant_id'];
  }else{
      $id = $_SESSION['login'];
  }
$profile_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$id."'"));
// print_r($profile_data);
// die;

if($profile_data)
{
	 if($profile_data['user_roles']==5){
   $loginidset=$profile_data['parentid'];
  }else if($_SESSION['login']){
   $loginidset = $_SESSION['login'];
  }else if($_SESSION['merchant_id']){
    $loginidset = $_SESSION['merchant_id'];
  }
  $sql = "SELECT count(id) as total_count FROM order_voice_list WHERE merchant_id ='".$loginidset."'";
$row = mysqli_fetch_assoc(mysqli_query($conn,$sql));
  $rec_limit = 25;
 $rec_count = $row['total_count'];

if( isset($_GET{'page'} ) ) {
            $page = $_GET{'page'} + 1;
            $offset = $rec_limit * $page ;
         }else {
            $page = 0;
            $offset = 0;
         }
         
$left_rec = $rec_count - ($page * $rec_limit);
 $query="SELECT users.name as user_name,order_voice_list.*, sections.name as section_name FROM order_voice_list 
left join sections on order_voice_list.section_type = sections.id left join users on users.id=order_voice_list.user_id WHERE order_voice_list.merchant_id ='".$loginidset."' ORDER BY order_voice_list.created_on DESC LIMIT $offset, $rec_limit";
  $total_rows = mysqli_query($conn,$query);
  $sql1 ="SELECT * FROM `order_voice_list` WHERE `show_alert` = 'yes' ORDER BY `created_on` DESC LIMIT 0,1;";
  $result1 = mysqli_fetch_assoc(mysqli_query($conn,$sql1));
  if(count($result1) > 0  && !empty($result1)) {
    $checkModel = 1;
  }else{
    $checkModel =0;
  }
	?>
<!DOCTYPE html>
  <html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">
  <head>
    <style>
      .no-close .ui-dialog-titlebar-close {
        display: none;
      }
      .test_product{
        padding-right: 125px!important;
      }
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
        color: orange !important;
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
      blink {
        -webkit-animation-name: blink; 
        -webkit-animation-iteration-count: infinite; 
        -webkit-animation-timing-function: cubic-bezier(1.0,0,0,1.0);
        -webkit-animation-duration: 1s;
      }
      blink {
        animation: blinker 1s linear infinite;
      }

      @keyframes blinker {
        50% {
          opacity: 0;
        }
      }
      .new_tablee {
        width: 200px!important;
        display: block;
        word-break: break-word;
      }
      td.test_productss {
        white-space: nowrap;
        /*width: 200px!important;*/
        display: block;
      }
      th.product_name.test_product {
        width: 200px!important;
      }
      @media only screen and (max-width: 600px) and (min-width: 300px){
        table.table.table-striped {
          white-space: unset!important;
        }
        #mep_0
        {

          display:none !important;
        }
        div.fixed {
          position: fixed;
          bottom: 0;
          right: 0;
          width: 100%;
          text-align:right;
          border: 3px solid #73AD21;
        }
      </style>
      <style type="text/css">


        /* Gradient text only on Webkit */
        .warning {
          background: -webkit-linear-gradient(45deg,  #c97874 10%, #463042 90%);
          -webkit-background-clip: text;
          -webkit-text-fill-color: transparent;
          color: #8c5059;
          font-weight: 400;
          margin: 0 auto 6em;
          max-width: 9em;
        }

        .calculator {
          font-size: 18px;
          margin: 0 auto;
          width: 10em;
          &::before,
          &::after {
            content: " ";
            display: table;
          }

          &::after {
            clear: both;
          }
        }

        /* Calculator after dividing by zero */
        .broken {
          animation: broken 2s;
          transform: translate3d(0,-2000px,0);
          opacity: 0;
        }

        .viewer {
          color: #c97874;
          float: left;
          line-height: 3em;
          text-align: right;
          text-overflow: ellipsis;
          overflow: hidden;
          width: 7.5em;
          height: 3em;
        }

        .button {
          border: 0;
          background: #99e1dc;
          color: #000;
          cursor: pointer;
          float: left;
          font: inherit;
          margin: 0.20em;
          width: 2em;
          height: 2em;
          transition: all 0.5s;

          &:hover {
            background: #201e40;
          }

          &:focus {
            outline: 0; // Better check accessibility

            /* The value fade-ins that appear */
            &::after {
              animation: zoom 1s;
              animation-iteration-count: 1;
              animation-fill-mode: both; // Fix Firefox from firing animations only once
              content: attr(data-num);
              cursor: default;
              font-size: 100px;
              position: absolute;
              top: 1.5em;
              left: 50%;
              text-align: center;
              margin-left: -24px;
              opacity: 0;
              width: 48px;    
            }
          }
        }

        /* Same as above, modified for operators */
        .ops:focus::after {
          content: attr(data-ops);
          margin-left: -210px;
          width: 420px;
        }

        /* Same as above, modified for result */
        .equals:focus::after {
          content: attr(data-result);
          margin-left: -300px;
          width: 600px;
        }

        /* Reset button */

        .reset {
          background: rgba(201,120,116,.28);
          color:#c97874;
          font-weight: 400;
          margin-left: -77px;
          padding: 0.5em 1em;
          position: absolute;
          top: -20em;
          left: 50%;
          width: auto;
          height: auto;

          &:hover {
            background: #c97874;
            color: #100a1c;    
          }

          /* When button is revealed */
          &.show {
            top: 20em;
            animation: fadein 4s;
          }
        }

        /* Animations */

        /* Values that appear onclick */
        @keyframes zoom {
          0% { 
            transform: scale(.2); 
            opacity: 1;
          }

          70% { 
            transform: scale(1); 
          }

          100% { 
            opacity: 0;
          }
        }

        /* Division by zero animation */
        @keyframes broken {
          0% {
            transform: translate3d(0,0,0);
            opacity: 1;
          }

          5% {
            transform: rotate(5deg);
          }

          15% {
            transform: rotate(-5deg);
          }

          20% {
            transform: rotate(5deg);
          }

          25% {
            transform: rotate(-5deg);
          }

          50% {
            transform: rotate(45deg);
          }

          70% {
            transform: translate3d(0,2000px,0);
            opacity: 1;
          }

          75% {
            opacity: 0;
          }

          100% {
            transform: translate3d(0,-2000px,0);
          }
        }

        /* Reset button fadein */
        @keyframes fadein {
          0% {
            top: 20em;
            opacity: 0;
          }

          50% {
            opacity: 0;
          }

          100% {
            opacity: 1;
          }
        }

        @media (min-width: 420px) {
          .calculator {
            width: 12em;
          }
          .viewer {
            width: 8.5em;
          }
          .button {
            margin: 0.5em;
          }
        }

        @media (max-width: @screen-xs-min) {
          .modal-xs { width: @modal-sm; }
        }

        .modal-lg {
          max-width: 900px;}
          @media (min-width: 768px) {
            .modal-lg {
              width: 100%;
            } 
          }
          @media (min-width: 992px) {
            .modal-lg {
              width: 900px;
            }
          }

          .blinking{
            animation:blinkingText 1.5s infinite;
            color:red;

          }
          @keyframes blinkingText{
            0%{     color: red;    }
            49%{    color: transparent; }
            50%{    color: transparent; }
            99%{    color:transparent;  }
            100%{   color:red;    }
          }
          .mejs__container{ display: none;}
          .label-danger{ background-color: #d9534f !important;}
          .animatedicon{font-size: 40px !important;color:red !important;}
        </style>
        <?php include("includes1/head.php"); ?>
        <?php 
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: PUT, GET, POST");
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        ?>
      <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
      <link rel="stylesheet" type="text/css" href="css/font-awesome-animation.min.css">
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
                <div class="well col-lg-12">
                  <div class="row">
                   <div class="col-md-4 text-left">
                     <h4>Voice Order list</h4>
                   </div>
                   <div class="col-md-4">
                      <div class="alertMessage text-center" style="display: none;"></div>
                   </div> 
                   <div class="col-md-4 text-right">
                      <h4>New Order 
                        <span class="label label-danger" id="totalNewOrder">0</span>
                      </h4>
                   </div> 
                   <div class="col-md-12">
                      <div style="display:none;" class="audioShow">
                        <!-- <audio controls="controls" onloadeddata="var audioPlayer = this; setTimeout(function() { audioPlayer.play(); }, 5000)">
                          <source src='notification.wav' type='audio/wav'>
                        </audio> -->
                        <audio id='beep' controls="controls">
                          <source src='notification.wav' type='audio/wav'>
                        </audio>
                      </div>
                   </div>
                  </div>
                  <div class="table-responsive">      
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th><?php echo $language["items"];?></th>
                          <th><?php echo $language["audio_message"];?></th>      
                          <th><?php echo $language["status"];?></th>
                          <th><?php echo $language["move_to_pos"];?></th>
                          <th><?php echo $language['date_of_order']; ?></th>
                        
                          <th><?php echo $language["table_number"];?></th>
                          <th><?php echo $language["username"];?></th>
                          <th><?php echo $language["mobile_number"];?></th>
                          <th><?php echo $language['section_number'];?></th>
                          <th><?php echo $language["location"];?></th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php 
                      $i =1;
                      while ($row=mysqli_fetch_assoc($total_rows)){
                         $user_name  = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM users WHERE id ='".$row['merchant_id']."'"));
                      ?>
                      <?php
                          if($row['status'] == 0){
                              $callss = "red";
                          }else if($row['status'] == 1){
                              $callss = "gr";
                          }else{
                              $callss = "or";
                          }  
                        ?>
                        <tr data-id="<?php echo $row['id']; ?>" class="<?php echo $todayorder; ?> fdfd <?php echo $callss; ?>">
                          <td style="text-align: center;"><?php echo $i; ?></td>
                           <td style="text-align: center;">
                            <audio id="player_<?php echo $i; ?>" style="display: none;" src="<?php echo $row['file_path']; ?>"></audio>
                            <button type="button" id="button" data-id="<?php echo $i; ?>"  class="btn btn-info row_class"><i class="material-icons">play_circle_outline</i></button>
                          </td>
                          <td>
                            <?php
                            if($row['status'] == 0)
                            {
                              $sta = "Pending";
                              $s_color="red";
                            }
                            else if($row['status'] == 1) 
                            {
                              $sta = "Done";
                              $s_color="green";
                            }
                            else 
                            {
                              $sta = "Accepted";
                              $s_color="";
                            }

                            ?>
                            <input type="button" style="background-color:<?php echo $s_color;?>" class= "status btn btn-primary" value="<?php  echo $sta;?>" status="<?php echo $row['status'];?>" data-invoce='<?php echo $row['invoice_no'];?>' data-id="<?php echo $row['id']; ?>"/>
                            <!--label class= "status btn btn-primary" status="<?php echo $row['status'];?>" data-invoce='<?php echo $row['invoice_no'];?>' data-id="<?php echo $row['id']; ?>"> <?php echo $sta; ?></label!-->

                          </td>
                           <td>
                            <a href="pos.php?check=audio&id=<?php echo $row['id']; ?>" type="text">
                              <?php echo $language['move_to_pos']; ?>
                            </a>
                          </td>
                          <td><?php $show_date=$row['created_on']; echo $newDate = date("d-m-Y h:i A", strtotime($show_date));   ?></td>
                        
                          <td><?php echo $row['table_type']; ?></td>
                          <td><?php echo $row['user_name']; ?></td>
                          <td><?php echo $row['user_mobile']; ?></td>
                          <td><?php echo $row['section_name']; ?></td>
                          <td><?php echo $row['location']; ?></td>
                      </tr>

                       <?php $i++; } ?>
                      <tbody>
                    </table>
                  </div>
                </div>
              </div>
            </main>
          </div>
        </div>
      <?php include("includes1/footer.php"); ?>
      <div class="modal" id="orderdetailmodel" role="dialog">            
        <div class="modal-dialog">
          <!-- Modal content-->   
          <div class="modal-content" id="orderdata"> 
                    
          </div>            
        </div>            
    </div>
    <script type="text/javascript">
      function generatetokenno(length) {
		   var result           = '';
		   var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
		   var charactersLength = characters.length;
		   for ( var i = 0; i < length; i++ ) {
			  result += characters.charAt(Math.floor(Math.random() * charactersLength));
		   }
		   return result;
		}
		  setInterval(function(){ 
			var s_token=generatetokenno(16);
						var r_url="https://www.koofamilies.com/voiceorderview.php?vs="+s_token;
					 window.location.replace(r_url);
    }, 
    30000);  
      $(document).ready(function() {
        
        var ckecker = "<?php echo $checkModel; ?>";
        if(ckecker == 1){
            setTimeout((function () {
                
                $.ajax({
                  type: "GET",
                  url: "checkAudioOrder.php",
                  dataType:'json',
                  success: function(data) {
                    
                    if(data.error == true){
                      $("#beep").get(0).play();
                      $("#orderdetailmodel").modal("show");
                      $("#orderdata").html(data.data);
                      statusSet(data.id);
                    }
                    else{
                      $("#orderdetailmodel").modal('hide');
                    }
                  }
                });
            }), 1500);
          }
          
        //audio play and stop
        var playing = false; 
        $(document).on('click','.row_class',function(){
          var row_id  = $(this).data('id');
          $(this).toggleClass("down");
            if (playing == false) {
            document.getElementById('player_'+row_id).play();
            playing = true;
            $(this).html('<i class="material-icons">pause_circle_outline</i>');
          } else {
            document.getElementById('player_'+row_id).pause();
            playing = false;
            $(this).html('<i class="material-icons">play_circle_outline</i>');
          }
        });

        /*==============*/
        var playing1 = false;
        $(document).on('click','button#button1',function() {
          $(this).toggleClass("down");
          if (playing1 == false) {
            document.getElementById('player1').play();
            playing1 = true;
            //$(this).html('<i class="material-icons">play_circle_outline</i>');
            $(this).html('<i class="material-icons">pause_circle_outline</i>');
          } else {
            document.getElementById('player1').pause();
            playing1 = false;
            $(this).html('<i class="material-icons">play_circle_outline</i>');
            //$(this).html('<i class="material-icons">pause_circle_outline</i>');
          }
        });

      });

      function statusSet(id){
        var status = 1;
        var type = 1;
        $.ajax({
          type: "POST",
          url: "checkAudioOrder.php",
          data: {id:id,status:status,type:type},
          success: function(data) {
            console.log(data);
          },
          error: function(result) {
            alert('error');
          }
        });
      }

     setInterval( function() {
        var countStatus = "countStatus";
        $.ajax ({
          type: "GET",
          url: "get_voice_order_merchant.php",
          data: {countStatus:countStatus},
          dataType:'json',
          success: function(data) {
            if(data.status == true){
              $("#totalNewOrder").html(data.total_count_new)
            }
          }
        });
      }, 1000);
    </script>
    <script> 
      var old_count = 0;
      var i = 0;
      setInterval(function(){    
      $.ajax({
      type : "POST",
      url : "checkneworder.php",
      success : function(data){
        if (data > old_count) 
          { 
            if (i == 0){
              old_count = data;
            } 
            else{
             $(".alertMessage").show();
             $(".alertMessage").html("<a href='voiceorderview.php'><i class='fa fa-bell faa-ring animated animatedicon'></i></a>");
              old_count = data;
          }
        } 
        i=1;
      }
      });
      },1000);

    // var x = document.getElementById("ad");
    // function enableLoop() { 
    //   x.loop = true;
    //   x.load();
    // }  
    </script>
      </body>
      </html>
<?php } else {header("location:mlogin.php"); }?>
