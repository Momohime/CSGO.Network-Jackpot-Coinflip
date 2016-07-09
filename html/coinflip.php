<?php
include ('link.php');
include ('core.php');
require_once('steamauth/steamauth.php');
@include_once('steamauth/userInfo.php');
$page="cf";

if(isset($_SESSION["steamid"]))
{
	$steamid=$_SESSION['steamid'];
	$time = time();
    mysql_query("UPDATE users SET lastseen=".$time." WHERE steamid=".$_SESSION['steamid']."");
	$premium=fetchinfo("premium","users","steamid",$_SESSION["steamid"]);
	$banned=fetchinfo("ban","users","steamid",$_SESSION["steamid"]);
	$cbanned=fetchinfo("cban","users","steamid",$_SESSION["steamid"]);
	$mytrade = fetchinfo("tlink","users","steamid",$_SESSION["steamid"]);
	$admin = fetchinfo("admin","users","steamid",$_SESSION["steamid"]);
	$name=$steamprofile['personaname'];
	$name = mysql_real_escape_string($name);
	if($name)
	{
		mysql_query("UPDATE `users` SET `name`='".$name."', `avatar`='".$steamprofile['avatarfull']."' WHERE `steamid`='".$_SESSION["steamid"]."'");
	}
	if($banned==1)
	{
		$banby=fetchinfo("banby","users","steamid",$_SESSION["steamid"]);
		$banend=fetchinfo("banend","users","steamid",$_SESSION["steamid"]);
		$banreason=fetchinfo("banreason","users","steamid",$_SESSION["steamid"]);
		if($banend!=-1)
		{
			$banendt=date('Y-m-d - H:i', $banend);
			$bmsg='You have been banned from this site by '.$banby.'.<br>Your ban ends on '.$banendt.'.<br>Ban reason: '.$banreason.'.';
		}
		else if($banend==-1)
		{
			$bmsg='You have been banned from this site by '.$banby.'.<br>Your ban is permanent.<br>Ban reason: '.$banreason.'.';
		}

		

		
		if($banend>=$time || $banend==-1)
		{
			echo $bmsg;
			die();
		}
		else
		{
			mysql_query("UPDATE `users` SET `ban`='0' WHERE `steamid`='".$_SESSION["steamid"]."'");
			mysql_query("UPDATE `users` SET `banend`='0' WHERE `steamid`='".$_SESSION["steamid"]."'");
			mysql_query("UPDATE `users` SET `banreason`='' WHERE `steamid`='".$_SESSION["steamid"]."'");
		}
	}
	$cbanstring='';
	if($cbanned==1)
	{
		$cbanby=fetchinfo("cbanby","users","steamid",$_SESSION["steamid"]);
		$cbanend=fetchinfo("cbanend","users","steamid",$_SESSION["steamid"]);
		$cbanreason=fetchinfo("cbanreason","users","steamid",$_SESSION["steamid"]);
		if($cbanend!=-1)
		{
			$cbanendt=date('Y-m-d - H:i', $cbanend);
			$cbtt='Chat ban by '.$cbanby.'';
			$cbmsg='Reason: '.$cbanreason.' - Ends on '.$cbanendt.'.';
	
		}
		else if($cbanend==-1)
		{
			$cbtt='You have been banned from the chat by '.$banby.'';
			$cbmsg='Reason: '.$cbanreason.' - The ban is permanent.';
		}
		if($cbanend>=$time || $cbanend==-1)
		{
					$cbanstring="
					<script>
					$.Notification.notify('black', 'top center',
                     '".$cbtt."',
                     '".$cbmsg."');
					 </script>
					 ";
		}
		else
		{
			mysql_query("UPDATE `users` SET `cban`='0' WHERE `steamid`='".$_SESSION["steamid"]."'");
			mysql_query("UPDATE `users` SET `cbanend`='0' WHERE `steamid`='".$_SESSION["steamid"]."'");
			mysql_query("UPDATE `users` SET `cbanreason`='' WHERE `steamid`='".$_SESSION["steamid"]."'");
		}
	}
}

if($premium==1)
{
	$id=$_SESSION['steamid'];
	$time=time();
	$puntil = fetchinfo("puntil","users","steamid","$id");
	if($puntil<=$time)
	{
		
		mysql_query("UPDATE users SET `premium`='0' WHERE `steamid`='$id'");
		mysql_query("UPDATE users SET `profile`='1' WHERE `steamid`='$id'");
		
	}
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
		
		<script src="https://cdn.socket.io/socket.io-1.4.5.js"></script>
		<script src="https://code.jquery.com/jquery-2.2.2.min.js"></script>
<script src="js/cfscript.js"></script>
<script src="js/script.js"></script>
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
						<style>
		
		tr:hover {background-color: #f5f5f5}
		tr.cent {
		  text-align: center;
		  vertical-align: middle;
		}

		</style>
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
<span class="msg"></span>
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
                	<div class="col-md-12 col-lg-12">
                        <div class="widget-bg-color-icon card-box">
                        	<p style="font-size:20px;">

							<center>
							<?php
							
							if(isset($_SESSION['steamid']))
							{
								echo'
									<a href="host.php" target="_BLANK">
									<button type="button" class="btn btn-lg btn-inverse btn-custom waves-effect waves-light">Host a lobby</button>
									</a>	
								';
							}
							else
							{
								echo'
									<a href="?login">
									<button type="button" class="btn btn-lg btn-inverse btn-custom waves-effect waves-light">Log In before Hosting</button>
									</a>	
								';
							}
							
							?>
							
							<br>
							<br>
							<table class="table" style="width:50%;">
                                                <thead>
                                                    <tr>
                                                        <th><center>Host</center></th>
                                                        <th><center>Name</center></th>
                                                        <th><center>Wager</center></th>
														<th><center>Value Gap</center></th>
														<th><center>Skins</center></th>
														<th><center>Player</center></th>
														<th> </th>
                                                    </tr>
                                                </thead>
                                                <tbody id="cflobbies">

												
												<?php
												$rs0 = mysql_query("SELECT COUNT(ID) FROM `cflobbies` WHERE `pid`='' ORDER BY ID DESC");						
												$row = mysql_fetch_row($rs0);
												$nr=round($row[0]);
												$pages=round($nr/30);
												
												$page=$page-1;
												$offset=$page*30;
								
												$rs = mysql_query("SELECT * FROM `cflobbies` WHERE `pid`='' ORDER BY `id` DESC");
												while($row = mysql_fetch_array($rs))
												{
													$id=$row['id'];
													
													$pid=$row['cid'];
													$pav=fetchinfo("avatar","users","steamid",$pid);
													$pname=fetchinfo("name","users","steamid",$pid);
													$pname=mysql_escape_string($pname);
													$pname=htmlentities(strip_tags($pname));
													$wager=$row['value'];
													$gap=$row['gap'];
													$fc=fetchinfo("flip","cflobbies","id",$id);
													$askins=fetchinfo("askins","cflobbies","id",$id);

													if($fc==1)
													{
														$hs='<img src="images/ct-coin.png" width="25">';
														$ms='<img src="images/t-coin.png" width="25">';
													}
													if($fc==2)
													{
														$hs='<img src="images/t-coin.png" width="25">';
														$ms='<img src="images/ct-coin.png" width="25">';
													}
													echo '
													<tr class="cent" id="cf'.$id.'">
													<td>'.$hs.'</td>
													<td>
													<a href="profile.php?action=view&id='.$pid.'" target="_BLANK">
													
													<img src="'.$pav.'" class="img-circle" width="30">

													</a>
													&ensp; 
													<a href="profile.php?action=view&id='.$pid.'" target="_BLANK">
													<b>'.$pname.'</b>
													</a>
													</td>
													<td>$'.$wager.'</td>
													<td>+- $'.$gap.'</td>
													<td>'.$askins.'</td>
													<td class="cent">'.$ms.'</td>
													<td><a href="lobby.php?id='.$id.'">
													<button type="button" class="btn btn-primary waves-effect waves-light">View Lobby</button>
													</a></td>
													</tr>
														';
												}
												?>

                                                </tbody>
                                            </table>
							</p>
							<br>
							
							<?php
							if(isset($_GET['resend']))
							{
								$rid=$_GET['resend'];
								$rid=mysql_escape_string($rid);
								if(is_numeric($rid))
								{
									echo"
									<script>
									socket.emit('resendoffer',$rid);
									$.Notification.notify('success', 'top center',
									 'It\'s on the way!',
									 'Trade Offer for Game #$rid will be sent to you shortly');
									</script>
									";
								}
							}
							if(isset($_SESSION['steamid']))
							{
								$rs = mysql_query("SELECT * FROM `cfwithdrawqueue` WHERE `userid`='$steamid' AND `status`='expired' OR `userid`='$steamid' AND `status`='active' ORDER BY `id` DESC");
								if(mysql_num_rows($rs) != 0)
								{
									echo'
									<br>
									<hr>
									<br>
									<div class="panel panel-default panel-border">
										<div class="panel-heading">
											<h3 class="panel-title">Unsent offers</h3>
										</div>
										<div class="panel-body">
											<p>
												Every trade offer expires within a minute, so if you\'re not quick enough you can resend it here.
											</p>
										</div>
									</div>
									<table class="table" style="width:50%;">
										<thead>
											<tr>
												<th><center>Game ID</th>
												<th><center>Value</center></th>
												<th><center>Status</center></th>
												<th><center>Action</center></th>
											</tr>
										</thead>
										<tbody>
									';
									$rs = mysql_query("SELECT * FROM `cfwithdrawqueue` WHERE `userid`='$steamid' AND `status`='expired' OR `userid`='$steamid' AND `status`='active' ORDER BY `id` DESC");
									while($row = mysql_fetch_array($rs))
									{
										$wid=$row['id'];
										$gid=$row['gameid'];
										$value=$row['value'];
										$status=$row['status'];
										if($status=='expired')
										{
											$status='Expired';
										}
										if($status=='cancelled')
										{
											$status='Cancelled';
										}
										echo'
										<tr  class="cent">
											<td>'.$gid.'</td>
											<td>$'.$value.'</td>
											<td>'.$status.'</td>
											<td><a href="coinflip.php?resend='.$wid.'"><button type="button" class="btn btn-success waves-effect waves-light">Resend</button></a></td>
										</tr>
										';
									}
									echo'
									</tbody>
									</table>
									';
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