<?php
  include ('link.php');
  include ('core.php');
  require_once('steamauth/steamauth.php');
  @include_once('steamauth/userInfo.php');

  $page="rls";

  if(isset($_SESSION["steamid"])) {
    $time = time();

      $conn->query("UPDATE users SET lastseen=".$time." WHERE steamid=".$_SESSION['steamid']."");
      $premium  = fetchinfo("premium","users","steamid",$_SESSION["steamid"]);
      $banned   = fetchinfo("ban","users","steamid",$_SESSION["steamid"]);
      $cbanned  = fetchinfo("cban","users","steamid",$_SESSION["steamid"]);
      $mytrade  = fetchinfo("tlink","users","steamid",$_SESSION["steamid"]);
      $admin    = fetchinfo("admin","users","steamid",$_SESSION["steamid"]);
      $name     = $steamprofile['personaname'];
      $name     = $conn->real_escape_string($name);

      if($name) {
          $conn->query("UPDATE `users` SET `name`='".$name."', `avatar`='".$steamprofile['avatarfull']."' WHERE `steamid`='".$_SESSION["steamid"]."'");
      }

      if($banned==1) {
          $banby    = fetchinfo("banby","users","steamid",$_SESSION["steamid"]);
          $banend   = fetchinfo("banend","users","steamid",$_SESSION["steamid"]);
          $banreason  = fetchinfo("banreason","users","steamid",$_SESSION["steamid"]);

          if($banend!=-1) {
            $banendt  = date('Y-m-d - H:i', $banend);
            $bmsg     = 'You have been banned from this site by '.$banby.'.<br>Your ban ends on '.$banendt.'.<br>Ban reason: '.$banreason.'.';
          } else if($banend==-1) {
            $bmsg     = 'You have been banned from this site by '.$banby.'.<br>Your ban is permanent.<br>Ban reason: '.$banreason.'.';
          }

          if($banend>=$time || $banend==-1) {
            echo $bmsg;
            die();
          } else {
            $conn->query("UPDATE `users` SET `ban`='0' WHERE `steamid`='".$_SESSION["steamid"]."'");
            $conn->query("UPDATE `users` SET `banend`='0' WHERE `steamid`='".$_SESSION["steamid"]."'");
            $conn->query("UPDATE `users` SET `banreason`='' WHERE `steamid`='".$_SESSION["steamid"]."'");
          }
        }

        $cbanstring='';

        if($cbanned==1) {
          $cbanby=fetchinfo("cbanby","users","steamid",$_SESSION["steamid"]);
          $cbanend=fetchinfo("cbanend","users","steamid",$_SESSION["steamid"]);
          $cbanreason=fetchinfo("cbanreason","users","steamid",$_SESSION["steamid"]);

          if($cbanend!=-1) {
            $cbanendt=date('Y-m-d - H:i', $cbanend);
            $cbtt='Chat ban by '.$cbanby.'';
            $cbmsg='Reason: '.$cbanreason.' - Ends on '.$cbanendt.'.';
          } else if($cbanend==-1) {
            $cbtt='You have been banned from the chat by '.$banby.'';
            $cbmsg='Reason: '.$cbanreason.' - The ban is permanent.';
          }

          if($cbanend>=$time || $cbanend==-1) {
            $cbanstring="
                <script>
                  $.Notification.notify('black', 'top center',
                          '".$cbtt."',
                          '".$cbmsg."'
                        );
                </script>
            ";
          } else {
            $conn->query("UPDATE `users` SET `cban`='0' WHERE `steamid`='".$_SESSION["steamid"]."'");
            $conn->query("UPDATE `users` SET `cbanend`='0' WHERE `steamid`='".$_SESSION["steamid"]."'");
            $conn->query("UPDATE `users` SET `cbanreason`='' WHERE `steamid`='".$_SESSION["steamid"]."'");
          }
      }

      if($premium==1) {
      $id   = $_SESSION['steamid'];
      $time   = time();
      $puntil = fetchinfo("puntil","users","steamid","$id");
        if($puntil <= $time) {
            $conn->query("UPDATE users SET `premium`='0' WHERE `steamid`='$id'");
          $conn->query("UPDATE users SET `profile`='1' WHERE `steamid`='$id'");
        }
    }

    $steamid = $_SESSION['steamid'];;
  } else {
      $premium      = 0;
      $banned       = 0;
      $cbanned      = 0;
      $mytrade      = '';
      $admin        = 0;
      $name         = '';
      $cbanstring   = '';
      $steamid      = '';
  }


?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Site description">
        <meta name="author" content="Website.com">

        <link rel="shortcut icon" href="defico.png">

        <title><?php echo $title; ?></title>

        <!--Morris Chart CSS -->
		<link rel="stylesheet" href="assets/plugins/morris/morris.css">
        <script src="assets/js/modernizr.min.js"></script>
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <script src="assets/js/detect.js"></script>
        <script src="assets/js/fastclick.js"></script>

        <script src="assets/js/jquery.slimscroll.js"></script>
        <script src="assets/js/jquery.blockUI.js"></script>
        <script src="assets/js/waves.js"></script>
        <script src="assets/js/wow.min.js"></script>
        <script src="assets/js/jquery.nicescroll.js"></script>
        <script src="assets/js/jquery.scrollTo.min.js"></script>

        <script src="assets/plugins/peity/jquery.peity.min.js"></script>

        <!-- jQuery  -->
        <script src="assets/plugins/waypoints/lib/jquery.waypoints.js"></script>
        <script src="assets/plugins/counterup/jquery.counterup.min.js"></script>



        <script src="assets/plugins/morris/morris.min.js"></script>
        <script src="assets/plugins/raphael/raphael-min.js"></script>

        <script src="assets/plugins/jquery-knob/jquery.knob.js"></script>

        <script src="assets/pages/jquery.dashboard.js"></script>

        <script src="assets/js/jquery.core.js"></script>
        <script src="assets/js/jquery.app.js"></script>
        <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/core.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/components.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/pages.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/responsive.css" rel="stylesheet" type="text/css" />
        <script src="assets/plugins/notifyjs/dist/notify.min.js"></script>
        <script src="assets/plugins/notifications/notify-metro.js"></script>
                <script src="chat/chat.js"></script>
        <link rel="stylesheet" href="chat/chat.css">
        <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

<link href="css/style.css" rel="stylesheet" type="text/css" />

    </head>


    <body class="fixed-left">
	<?php echo $cbanstring; ?>

        <!-- Begin page -->
        <div id="wrapper">

            <!-- Top Bar Start -->
              <?php include 'topmenu.php';?>
            <!-- Top Bar End -->
                          <script>
                $(".right-bar-toggle").click(function(){
                  $(".wrapper").toggleClass("right-bar-enabled");
                  console.log($(".right-bar").css("right") == "0px");
                  if($(".right-bar").css("right") != "0px")
                    $(".right-bar").css("right","0");
                  else
                    $(".right-bar").css("right","-266px");
                });
              </script>


            <!-- ========== Left Sidebar Start ========== -->
            <?php include 'leftmenu.php';?>
            <!-- Left Sidebar End -->



            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="content-page">
                <!-- Start content -->
                <div class="content" style="text-align:center;">
                	<h1>Rules & FAQ</h1>
                	<div class="col-md-12 col-lg-12">
                        <div class="widget-bg-color-icon card-box">
                        	<p style="font-size:20px;">


   <h4>The LOWEST commission jackpot site!</h4>
  <h4>Mechanics are simple: More you contribute into the pool, Higher the chances you win the entire pool!</h4> - Login through Steam and connect your steam URL through Settings<br>- Place your deposit!<br>
- The system calculates value for items that you deposited (valued by csgo.steamanalyst.com), for every dollar you get 100 points.<br> 
- When the pot reaches 50 items or 90 seconds, the raffle begins. The bigger contribution percentage to the pool, the higher chance to win.<br> - 
The jackpot winner will receive the jackpot VIA a steam offer. (MAX 15 minutes after chosen).<br>
<h4>Why use us?</h4> We run the lowest commission (4%) out of all jackpot sites. We consist of active, ready-to-help staff member at all times. We don't run any B.S like "if bot gets glitched, you don't get skins back."<br><br>We try to maintain the most legit and efficient way of CSGO Jackpotting!<br>
<h4>Requirements to join:</h4> -There's a 10 item limit per trade offer. Minimum of $0.5 value. <h4>Note That:</h4>
- Your first deposit gives you a +5% win chance.<br>- By adding "Website.com" to your steam name and re-logging the site, you get +6% extra on your winnings!<br><h4>Current Promotions:</h4>
1. All entries have a chance to win a choice of Karambit Fade/Slaughter FN (ends sep 8)<br>2. Retweet/Share us on Facebook for an extra chance!<br> <h4>Why did I not receive all the skins in the pool?</h4>
- The site takes 10% commission (4% with Website.com) for upkeep, advertising, give-aways and site development.
<h4>How long does it take for my winnings?</h4> - Within a minute. The bot should automatically send the winner their jackpot winnings! <h4>Your Privacy:</h4>
- Logging in with Steam will never provide us with any information. Our Jackpot logs will NEVER be shared or sold.<br>
<br>Questions and Inquires can be sent to: support@Website.com<br><br> Deposit today and try your luck! Evolve Your Skins!<br>
-Website Team <a class="close" data-page="play"></a>                    </p>   </div>
                    </div>
                </div> <!-- container -->

                </div> <!-- content -->
<br><br><br>
                <?php include('footer.php'); ?>

            </div>


            <!-- ============================================================== -->
            <!-- End Right content here -->
            <!-- ============================================================== -->


            <!-- Right Sidebar -->
            <div class="side-bar right-bar nicescroll">
                <script>
      var name = "<?php echo $steamprofile['personaname'] ?>";
      var ava = "<?php echo $steamprofile['avatarfull'] ?>";
      var id = "<?php echo $_SESSION['steamid'] ?>";
      var color = "<?php echo 'FF0000' ?>";
      var admin = "<?php echo $admin ?>";

      // display name on page
      $("#name-area").html("You are: <span>" + name + "</span>");
      // kick off chat
      var chat =  new Chat();
      $(function() {
        chat.getState(); 
        // watch textarea for key presses
          $("#sendie").keydown(function(event) {  
            var key = event.which;  
            //all keys including return.  
            if (key >= 33) {
              var maxLength = 57;  
              var length = this.value.length;  
              // don't allow new content if length is maxed out
              if (length >= maxLength) {  
                event.preventDefault();  
              }  
            }
          });
          // watch textarea for release of key press
          $('#sendie').keyup(function(e) {       
            if (e.keyCode == 13) { 
              var text = $(this).val();
              var maxLength = $(this).attr("maxlength");  
              var length = text.length; 
              // send 
              if (length <= maxLength + 1) { 
                chat.send(text, name, ava,id,admin,color);  
                $(this).val("");
              } else {
                $(this).val(text.substring(0, maxLength));
              } 
            }
          });
          
          // watch textarea for release of key press
          $("#sendchat").click( function() {    
              var text = $('#sendie').val();
              var maxLength = $('#sendie').attr("maxlength");  
              var length = text.length; 
              
              if (length >= maxLength) {  
                event.preventDefault();  
              }  
              // send 
              else if (length <= maxLength + 1) { 
                chat.send(text, name, ava,id,admin,color);  
                $('#sendie').val("");
              } else {
                $('#sendie').val(text.substring(0, maxLength));
              } 
          });
      });
      </script>
                <h4 class="text-center">Chat</h4>                      
                    <div class="row userarea" style="height:78%; padding: 0 5%;">
                          <div id="chat-wrap">
                            <?php include "chat/chat.php";?>
                            </div>
                            <div class="botton">
                            <?php
                if(!isset($_SESSION["steamid"])){
                  echo '
                  <div id="otpsoob"><div style="padding-top: 7px;">
                      <a href="?login" class="btn btn-success">
                        <p style="padding: 0; margin: 20px 0 20px 0; text-transform: uppercase; font-weight: bold;">Login through Steam</p>
                      </a>
                  </div></div>';
                } else {
                  echo '
                   
                    <div id="otpsoob"><form id="send-message-area">
                      <textarea id="sendie" maxlength="125" rows="2" placeholder="Enter your message"></textarea>
                      <button onClick="return false;" id="sendchat" class="btn btn-success">
                        Send
                      </button>
                      </form>
                    </div>
                  ';
                }
              ?>
                            </div>
                        </div>
            </div>
            <!-- /Right-bar -->

        </div>
        <!-- END wrapper -->

<script src="assets/plugins/sweetalert/dist/sweetalert.min.js"></script>
        <script src="assets/pages/jquery.sweet-alert.init.js"></script>

        <script>
            var resizefunc = [];
        </script>

        <!-- jQuery  -->


        <script type="text/javascript">
            jQuery(document).ready(function($) {
                $('.counter').counterUp({
                    delay: 100,
                    time: 1200
                });

                $(".knob").knob();

            });
        </script>




    </body>
</html>