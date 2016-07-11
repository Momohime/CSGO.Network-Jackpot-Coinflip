<?php
  include ('link.php');
  include ('core.php');
  require_once('steamauth/steamauth.php');
  @include_once('steamauth/userInfo.php');

  $page="tos";

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
                	<h1>Terms of Service</h1>
                	<div class="col-md-12 col-lg-12">
                        <div class="widget-bg-color-icon card-box">
                        	<p style="font-size:20px;">

<h4>License of usage:</h4> 
By using our service or being on our website, you acknowledge and accept our terms and conditions in full and without reservation. 
You are bound to our terms as long as you are affiliating yourself with any aspects of Website. 
Any conflicts with our terms and conditions and parts of it is NOT allowed to affiliate themselves any further without discussion. 
Those who are under affiliation with Valve corporation, Steam or external Jackpot Sites are prohibited to affiliate themselves with this website/service and/or their owners.
<br><br> You must the age of 18+ (21+ in some places) or older to use our website. By using this website you agree to these terms and conditions, you warrant that you are at least 18/21 years of age. Website.com has the right to request identification of age to prevent under-aged gambling while temporarily disabiling your account. 
<h4>Disclaimer:</h4>
The use of our service is provided "AS IS" or "AS AVAILABLE". No guarantees are provided for ANY activity done on this site.<br>
We reserve the rights to have anything on Website or affiliated changed, whenever, without consent.<br>
<h4>License of service usage:</h4> 
Website and/or its owners own the intellectual property rights and originality that is published on Website (in any form). Subject to the license below, all these intellectual property rights are reserved. <h4>
Accounts/Privacy:</h4> The account you authorize with us through Steam (https://steamcommunity.com) must be your own. Your privacy is kept 100% secure and will never be distributed externally. 
<h4>Fair Play</h4>
We respect fair play. A MAXIMUM of 4% will be taken from jackpot winners with "Website.com" and up to 10% without for further website development, give-aways and upkeep. All users on this website are expected to be "legit" users of the community as we hold the right to freeze your account without warning if suspicion is raised.
<h4>Missing Items</h4> 
If you are missing any items deposited or returned from Website.com, you have 30 days to claim your items through a support ticket. You will be notified after 30 days. Items will not be returned if put into the jackpot and lost. All jackpot betting are FINAL.
<h4>Valuation:</h4> Website items are valued from SteamAnalyst's database backed up with the Steam Market. Prices are subjected to change any time without notice. <br> All deposits are final after the user confirms them on the website, there will be no refunds, since the user agrees to enter the jackpot. Agrees he is liable to losing
his skins to another user and agrees Website is not liable for losses of any kind. 
<h4>Responsible Gambling:</h4> 
We do not hold responsibility for any losses. Bet responsibly, know your limits. All bets are final as you have agreed to the ToS by being on this site.
<h4>Skin Deposits:</h4> Website.com only allows items from game “Counter Strike: Global Offensive” (non-affiliated). These skins MUST be your own.Items from different games will be declined. 
<h4>Variation:</h4>
Website.com may revise these terms and conditions at all time. Revised terms and conditions will apply to the use of this website from the date of the publication of the revised terms and conditions on this website. Please check this page regularly to ensure you are familiar with the current version. 
<h4>Termination of Account:</h4>
Any violation of service will have your account terminated/frozen with any virtual assets on it.
<h4>Third-Party Links:</h4>
The third-party links that are shown outside Website.com is not our responsibility. We are unaffiliated unless mentioned. That includes the content, privacy, or practices of external parties.
<h4>Indemnification:</h4>
By BEING on the site, you agree to defend Website and any staff affiliated against all and any claims, losses, obligations, costs and expenses arisen from breaking any section of our terms or any claim that you have broken any section of our terms.
<h4>Affiliation:</h4>
We are NOT in any way, shape or form affiliated with Valve corporation, Steam, Any Counter-Strike franchise, other Jackpot sites or any other trademark of the Valve/Steam corporation.
<h4>Law and jurisdiction:</h4> 
Our terms of service will be governed by and construed in accordance with the laws of Portugal, and any disputes relating to these terms and conditions will be subject to the exclusive jurisdiction of the courts of Portugal.
</p>
                        </div>
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
           	<?php if(isset($_SESSION["steamid"])) { ?>
              	var name 	= "<?php echo $steamprofile['personaname'] ?>";
			    var ava 	= "<?php echo $steamprofile['avatarfull'] ?>";
			    var id 		= "<?php echo $_SESSION['steamid'] ?>";
			    var color 	= "<?php echo 'FF0000' ?>";
			    var admin 	= "<?php echo $admin ?>";
			<?php } else { ?>
              	var name 	= "";
			    var ava 	= "";
			    var id 		= "";
			    var color 	= "<?php echo 'FF0000' ?>";
			    var admin 	= "0";
			<?php } ?>

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