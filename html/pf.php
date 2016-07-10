<?php
	include ('link.php');
	include ('core.php');
	require_once('steamauth/steamauth.php');
	@include_once('steamauth/userInfo.php');

	$page="pf";

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
        		$banendt 	= date('Y-m-d - H:i', $banend);
        		$bmsg 		= 'You have been banned from this site by '.$banby.'.<br>Your ban ends on '.$banendt.'.<br>Ban reason: '.$banreason.'.';
      		} else if($banend==-1) {
        		$bmsg 		= 'You have been banned from this site by '.$banby.'.<br>Your ban is permanent.<br>Ban reason: '.$banreason.'.';
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
	 		$id 	= $_SESSION['steamid'];
	 		$time 	= time();
	 		$puntil = fetchinfo("puntil","users","steamid","$id");
	    	if($puntil <= $time) {
	      		$conn->query("UPDATE users SET `premium`='0' WHERE `steamid`='$id'");
	     		$conn->query("UPDATE users SET `profile`='1' WHERE `steamid`='$id'");
	    	}
	 	}

    $steamid = $_SESSION['steamid'];;
 	} else {
	    $premium  		= 0;
	    $banned   		= 0;
	    $cbanned  		= 0;
	    $mytrade  		= '';
	    $admin    		= 0;
	    $name    	 	  = '';
	    $cbanstring 	= '';
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
                	<h1>Provably Fair</h1>
                	<div class="col-md-12 col-lg-12">
                        <div class="widget-bg-color-icon card-box">
                        	<p style="font-size:20px;">
<h4>To prove our system is legit we have made a Provably Fair system</h4>
This system is simple and you've probably seen something like it before! We don't use all kind of weird hash system, you simply need to enter the round hash to see all avaliable information on the round.
<br>
Every skin you deposit gives you 1 ticket per every $0.01 the skin is worth. (Example: A skin worth $1.4 gives you 140 tickets)
<br>
The winner is randomly selected after every round based on the winning module generated on every round's end.
<br>
The winning module is based on a simple algorithm: We generate a random value between 0.100000000 and 0.999999999.
<br>
The winning percentage/ticket is then generated by multiplying said round's pot with the module. (Example: 23*0.213123123 = 4.90[1831829])
<br>
The generated percentage/ticket will point to someone's deposit in our database and that user is the winner.
<br>
<br>

<h4>Basic example</h4>
<br>
Pot: $23
<br>
Gergo4961 deposited $13 - > This means he has 1300 tickets, from 1-1300
<br>
Player 2 deposited $10 - > This means he now owns 1000 tickets, ranging from 1301-2301
<br>
The winning module is generated, let's say its 0.123456789
<br>
The winning percentage/ticket is generated, it's 23*0.123456789 which equals to 2.83[9506147])
<br>
This means whoever owns ticket #283 wins the round.
<br>
Since Gergo4961 owns tickets 1-1300 this winning ticket is in his threshold he wins the round.
<br>
<br>

<h4>Still don't believe us?</h4>
You can purchase this script on <a href="http://csgo.network">csgo.network</a> and check the winner generating script yourself!

<br>
<br>
<br>
<br>
<h4>Verify a round</h4>
<br>
<?php
	$rh = '';
	if(isset($_GET["action"]))	
	if($_GET["action"] == "verify") {
		$rh = $_GET["id"];
		$rh=mysql_real_escape_string($rh);
	}
		echo '<form class="form-inline" method="post" action="pf.php">
			<div class="form-group">
				<input type="text" class="form-control" id="hash" name="hash" placeholder="Round Hash" value="'.$rh.'">
			</div>
			<br><br>
			<button value="submit" name="submit" id="submit" type="submit" class="btn btn-success waves-effect waves-light m-l-10 btn-md">Verify (Pot 1)!</button>
			<button value="submit2" name="submit2" id="submit2" type="submit" class="btn btn-success waves-effect waves-light m-l-10 btn-md">Verify (Pot 2)!</button>
		</form>';
		
		if(isset($_POST["submit"]))
		if ($_POST['submit'])
		{
			$hash = $_POST['hash'];
			$hash = $conn->real_escape_string($hash);
			if(!$hash)
			{
				echo'<br><font color="red">Please enter a round	hash!</font>';
			}
			else
			{
				$hid=fetchinfo("id","games","hash",$hash);
				$hcost=fetchinfo("cost","games","hash",$hash);
				$hmod=fetchinfo("module","games","hash",$hash);
				$hwid=fetchinfo("userid","games","hash",$hash);
				if($hwid)
				{
					$hwn=fetchinfo("winner","games","hash",$hash);
					$hwn=secureoutput($hwn);
					$hti=$hcost*$hmod;
					$from=0;
					$to=0;
					
					$rs = $conn->query("SELECT * FROM game".$hid	."");
					while($grow = $rs->fetch_array())
					{	
						$from=$to;
						$to+=$grow['value'];
						if($hti>=$from && $hti<=$to)
						{
							$hwit=$grow['item'];
							$hwc=$grow['value'];
							$rs = $conn->query("SELECT SUM(value) AS ValueSum FROM `game$hid` WHERE `userid`='$hwid'") or die(logsqlerror(mysql_error()));
							$row = $rs->fetch_array();
							$wp = 100*$row["ValueSum"]/$hcost;
							$wp=round($wp);
						}
					}
					
					echo '
					<br>
					<br>
					<hr>
					<h3>
					Round <b>#'.$hid.'</b> information:
					</h3>
					<br>
					<u>Winner:</u> &ensp; <a href="profile.php?action=view&id='.$hwid.'" target="_BLANK"">'.$hwn.'</a>
					<br>
					<u>Jackpot cost:</u> &ensp; $'.$hcost.'
					<br>
					<u>Win chance:</u> '.$wp.'%
					<br>
					<u>Winning module:</u> &ensp; '.$hmod.'
					<br>
					<u>Winning ticket ('.$hcost.'*'.$hmod.'):</u> &ensp; '.$hti.'
					<br>
					<br>
					The winning ticket was given to the winner by his/her <b>'.$hwit.'</b> valued <b>$'.$hwc.'</b>
					<br>
					This skin held tickets &ensp; <b>'.$from.'&ensp;-&ensp;'.$to.'</b>
					
					<hr>
					';
				
				}
				else
				{
					echo '<br><br><font color="red">This round haven\'t ended yet!</font>';
				}
			}
		}
		
		if(isset($_POST["submit2"]))
		if ($_POST['submit2'])
		{
			$hash = $_POST['hash'];
			$hash = $conn->real_escape_string($hash);
			if(!$hash)
			{
				echo'<br><font color="red">Please enter a round	hash!</font>';
			}
			else
			{
				$hid=fetchinfo("id","p2games","hash",$hash);
				$hcost=fetchinfo("cost","p2games","hash",$hash);
				$hmod=fetchinfo("module","p2games","hash",$hash);
				$hwid=fetchinfo("userid","p2games","hash",$hash);
				if($hwid)
				{
					$hwn=fetchinfo("winner","p2games","hash",$hash);
					$hwn=secureoutput($hwn);
					$hti=$hcost*$hmod;
					$from=0;
					$to=0;
					
					$rs = $conn->query("SELECT * FROM p2game".$hid	."");
					while($grow = $rs->fetch_array())
					{	
						$from=$to;
						$to+=$grow['value'];
						if($hti>=$from && $hti<=$to)
						{
							$hwit=$grow['item'];
							$hwc=$grow['value'];
							$rs = $conn->query("SELECT SUM(value) AS ValueSum FROM `p2game$hid` WHERE `userid`='$hwid'") or die(logsqlerror(mysql_error()));
							$row = $rs->fetch_array();
							$wp = 100*$row["ValueSum"]/$hcost;
							$wp=round($wp);
						}
					}
					
					echo '
					<br>
					<br>
					<hr>
					<h3>
					Round <b>#'.$hid.'</b> information:
					</h3>
					<br>
					<u>Winner:</u> &ensp; <a href="profile.php?action=view&id='.$hwid.'" target="_BLANK"">'.$hwn.'</a>
					<br>
					<u>Jackpot cost:</u> &ensp; $'.$hcost.'
					<br>
					<u>Win chance:</u> '.$wp.'%
					<br>
					<u>Winning module:</u> &ensp; '.$hmod.'
					<br>
					<u>Winning ticket ('.$hcost.'*'.$hmod.'):</u> &ensp; '.$hti.'
					<br>
					<br>
					The winning ticket was given to the winner by his/her <b>'.$hwit.'</b> valued <b>$'.$hwc.'</b>
					<br>
					This skin held tickets &ensp; <b>'.$from.'&ensp;-&ensp;'.$to.'</b>
					
					<hr>
					';
				
				}
				else
				{
					echo '<br><br><font color="red">This round haven\'t ended yet!</font>';
				}
			}
		}

?>
 
 
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