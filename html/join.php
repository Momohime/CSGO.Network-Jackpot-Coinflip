  <?php
  error_reporting(0);
include ('link.php');
include ('core.php');
require_once('steamauth/steamauth.php');
@include_once('steamauth/userInfo.php');
$page="cf";
$id=$_GET['id'];
$id=mysql_escape_string($id);
$gap=fetchinfo("gap","cflobbies","ID",$id);
$flip=fetchinfo("flip","cflobbies","ID",$id);
$value=fetchinfo("value","cflobbies","ID",$id);
$pid=fetchinfo("pid","cflobbies","ID",$id);
$valuemin=$value-$gap;
$valuemax=$value+$gap;

$return=$_SERVER['HTTP_REFERER'];

if(!isset($_SESSION['steamid']) || !$id || !is_numeric($id) || $pid)
{
	Header("Location: coinflip.php");
	echo"
	<script>
	location.href='coinflip.php';
	</script>
	";
	die();
}
if(isset($_SESSION["steamid"]))
{
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
 

		<script src="//cdn.jsdelivr.net/alertifyjs/1.4.1/alertify.min.js"></script>
		<link rel="stylesheet" href="//cdn.jsdelivr.net/alertifyjs/1.4.1/css/alertify.min.css"/>
		<link rel="stylesheet" href="//cdn.jsdelivr.net/alertifyjs/1.4.1/css/themes/semantic.rtl.min.css"/>

	   
        <script src="assets/plugins/notifications/notify-metro.js"></script>
                <script src="chat/chat.js"></script>
				<script src="js/cfscript.js"></script>
                 <link rel="stylesheet" href="css/bundle.css"/>
                 <link href="assets/plugins/ion-rangeslider/ion.rangeSlider.css" rel="stylesheet" type="text/css"/>
        <link href="css/ion-rangeslider/ion.rangeSlider.skinFlat.css" rel="stylesheet" type="text/css"/>
         <script src="css/ion-rangeslider/ion.rangeSlider.min.js"></script>
								<style>
			.accept {
  background: #4cc700;
  background-image: -webkit-linear-gradient(top, #4cc700, #028502);
  background-image: -moz-linear-gradient(top, #4cc700, #028502);
  background-image: -ms-linear-gradient(top, #4cc700, #028502);
  background-image: -o-linear-gradient(top, #4cc700, #028502);
  background-image: linear-gradient(to bottom, #4cc700, #028502);
  -webkit-border-radius: 4;
  -moz-border-radius: 4;
  border-radius: 4px;
  font-family: Arial;
  color: #ffffff;
  font-size: 29px;
  padding: 10px 20px 10px 20px;
  text-decoration: none;
}

.accept:disabled {
  background: #757075;
  background-image: -webkit-linear-gradient(top, #757075, #4d4d4d);
  background-image: -moz-linear-gradient(top, #757075, #4d4d4d);
  background-image: -ms-linear-gradient(top, #757075, #4d4d4d);
  background-image: -o-linear-gradient(top, #757075, #4d4d4d);
  background-image: linear-gradient(to bottom, #757075, #4d4d4d);
  -webkit-border-radius: 4;
  -moz-border-radius: 4;
  border-radius: 4px;
  font-family: Arial;
  color: #adadad;
  font-size: 29px;
  padding: 10px 20px 10px 20px;
  text-decoration: none;
}

.confirm {
  background: #6e95bc;
  background-image: -webkit-linear-gradient(top, #6e95bc, #324c65);
  background-image: -moz-linear-gradient(top, #6e95bc, #324c65);
  background-image: -ms-linear-gradient(top, #6e95bc, #324c65);
  background-image: -o-linear-gradient(top, #6e95bc, #324c65);
  background-image: linear-gradient(to bottom, #6e95bc, #324c65);
  -webkit-border-radius: 4;
  -moz-border-radius: 4;
  border-radius: 4px;
  font-family: Arial;
  color: #f0f0f0;
  font-size: 20px;
  padding: 10px 20px 10px 20px;
  text-decoration: none;
}
input[type=number]
			{
			    
			    border:solid 1px #BFBDBD;
			    color: #979797;
			    height: 28px;
			    padding-left:10px;
			    width: 191px;
			    box-shadow: 2px 2px 0 #828181 inset;
			}
			input[type=button]
			{
			    background-color: #1E1E1E;
			    color: #979797;
			    height: 24px;
			    width: 103px;
			    color: #bbbbbb;
			    text-transform:uppercase;
			    box-shadow:-1px 2px #6E6B6A inset;
			}

			input[type=button], input[type=number]
			{
			    border: 0;
			    border-radius:5px;
			    font-family: Sansation,Arial;
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
                        	
		<?php

?>
<?php

$return=$_SERVER['HTTP_REFERER'];

		echo "<input type='hidden' name='lobbyid' class='lobbyid' value='$id'>
		<a href='coinflip.php'><font size='1' color='grey'>Go back to the lobbies</font></a>
		<br>
		<font color='red'>If you don't see your skins make sure your inventory is public and refresh this page.</font>
		<br>
				<center style='border: 3px solid black; padding: 1%;'>";

		if($flip==2)
		{
			echo"<img src='images/ct-coin.png' class='coin' width='100px'>";
		}
		else
		{
			echo"<img src='images/t-coin.png' class='coin' width='100px'>";
		}
		
		echo"
		<br>
		<br>
		";
		echo'	
			<font color="#42575E" size="5">GAP</font><br>
			<font color="black" size="1">(Skin Value must be between these numbers)</font><br>
               <font color="#42575E">$'.$valuemin.' - $'.$valuemax.'</font>
            </center><br>
		';
		echo"
		<div style='border: 3px solid black; padding: 1%;'>		
		<font style='float:left' color='#42575E' size='5' class='lato'>Current Value: &ensp;<font color='green'>$</font></font>
		<font style='float:left' color='green' size='5'><div id='value'>0</div></font>
		
		<font color='#42575E' size='5' class='lato'>Min value:</font>
		<font color='green' size='5'>&ensp;$0.20</font>

		<font style='float:right' color='green' size='5'>&ensp;10</font>
		<font style='float:right' color='#42575E' size='5' class='lato'>Max Skins:</font>
		

		</div>
		<center>
		
		<br>
		<br>
		";
		$steamid=$_SESSION['steamid'];
		echo '<div class="container1">';
		//$prices1 = file_get_contents('prices.txt');
        $prices = file_get_contents('https://api.csgofast.com/price/all');
        $parsedPrices = json_decode($prices);
        $get_content = file_get_contents("http://steamcommunity.com/profiles/$steamid/inventory/json/730/2/");
        $parsedInv = json_decode($get_content);
        $parsedInvT = json_decode($get_content);
        $counter = 0;
        $mindeposit = 0.2;
        echo '<span id="noitems"></span>';
		foreach($parsedInv->rgInventory as $k => $v)
		{
			$id = $v->id;
            $iid = $v->instanceid;
            $cid = $v->classid;
            $key = $cid."_".$iid;
            $t = $parsedInvT->rgDescriptions->$key->tradable;
			if($t==1)
			{				
				$name = $parsedInvT->rgDescriptions->$key->market_hash_name;
            $price = $parsedPrices->$name;
            $img = $parsedInvT->rgDescriptions->$key->icon_url;
			if($t && $price && $price >= $mindeposit)
			{
					echo'<div class="item-card__wrapper item '.$id.'">
                
                    <div class=" item-card steam-quality-baseGrade steam-appid-730">
                        <div class="item-card__header">
                            <h4 class="item-card__title_main steam-category-normal name">'.$name.'</h4>
        
                            <small class="item-card__title_opt">
                                 
                            </small>
                        </div>
                        <div class="item-card__image-wrapper item-card__image-wrapper--alfa">
                           <img class="item-card__image item-card__image--zoom" src="http://steamcommunity-a.akamaihd.net/economy/image//'.$img.'" alt="'.$name.'">
                        </div>
                        <div class="item-card__footer">
                            <small>$<span class="val">'.$price.'</span></small>
                        </div>
                    </div>
            </div>';
				}
				
			}
		}
		echo"</div>";
		echo"<br>
		<button class=\"confirm\" onclick=\"check()\"><input onclick=\"check()\" type=\"checkbox\" name=\"accepted\" id=\"myCheck\" value=\"checkbox\"> Confirm items & proceed</button>
		<br>
		<br>	
		<button class=\"accept show\" id=\"submit\" type=\"submit\" disabled>Deposit</button>
		
		</center>

		";
		?>

<script>
		eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp(''+e(c)+'','g'),k[c]);return p}('B h=0;y I(){d(i.8==k){j.n("i").8=l;d(h!=0){$("#q").H(\'m\')}}s{j.n("i").8=k;$("#q").u("m",l)}}$("5.9").N(y(){B b=$(6).4(\'5\');d($(6).4(\'5\').M("f-g-w")){j.n("i").8=k;$("#q").u("m",l);$(6).4(\'5\').x("f-g-w");$(6).4(\'5\').x("8");$(6).4(\'5\').r("f-g-D");h--;$("#o").7(c(c($("#o").7())-c($(6).4("5").4(\'5.9-z\').4(\'t\').4(\'e\').7())).F(2));$(\'#A 5\').O(y(){B a=$(6).J();K.L(a+" - "+b.4("5").4(\'5.9-C\').4(\'.v\').7()+" - "+b.4("e.E").7());d(a==b.4(".v").7()+" - "+b.4("e.E").7()){$(6).G("");$(6).P().4("Q").R()}})}s{d(h+1<=S){$(6).4(\'5\').r("f-g-w");$(6).4(\'5\').x("f-g-D");j.n("i").8=k;$("#q").u("m",l);h++;$(6).4(\'5\').r("8");$(6).4("p").r("T");$("#o").7(c((c($("#o").7())+c($(6).4("5").4(\'5.9-z\').4(\'t\').4(\'e\').7())).F(3)));$("#A").7($("#A").7()+"<5>"+$(6).4("5").4(\'5.9-C\').4(\'.v\').7()+" - "+$(6).4("5").4(\'5.9-z\').4(\'t\').4(\'e\').7()+"</5>")}s{}}});',56,56,'||||children|div|this|html|checked|item|||parseFloat|if|span|steam|quality|selected|myCheck|document|false|true|disabled|getElementById|value||submit|addClass|else|small|prop|name|covert|removeClass|function|card__footer|deposit|var|card__header|baseGrade|val|toFixed|replaceWith|removeAttr|check|text|console|log|hasClass|click|each|parent|br|remove|10|chk'.split('|'),0,{}))
		</script>
<script>

eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp(''+e(c)+'','g'),k[c]);return p}('k=$(\'.k\').A();$(".D").1u(t(){j a=0;j c=0;c=a+"="+$(\'.k\').A();a++;j d=0;$("3.1n .g").1B(t(){2($(e).1(\'3\').1x("1A")){2(a==0){c=a+"="+$(e).1("3").1(\'3.g-J\').1(\'.I\').m();d=d+q($(e).1("3").1(\'3.g-E\').1(\'x\').1(\'F\').m());a++}1G{c=c+"&"+a+"="+$(e).1("3").1(\'3.g-J\').1(\'.I\').m();d=d+q($(e).1("3").1(\'3.g-E\').1(\'x\').1(\'F\').m());a++}}});d=q(d);c=c+"&"+"1U="+d;2(a!=0){$.P({R:"11",16:\'18.p\',1b:c,1g:1l,1m:t(a){a=a.w(\'
\',\'\');a=a.w(\'    \',\'\');2(a==\'1p\'){4.6(\'7!\',\'<b>G 1T O o Q 8</b>.\')}2(a==\'S\'||a==\'T\'){4.6(\'7!\',\'<b>U V W X Y Z, 10 y a 12 13</b>.\')}2(a==\'14\'){4.6(\'7!\',\'<b>15 8 z 17 29, B 1a\'s C 1c 1d. 1e y 1f a v 2 1h 1i 1j C</b>.\')}2(a==\'1k\'){4.6(\'7!\',\'<b>i h r H 1o 9 O a 8 1q 1r</b>.\')}2(a==\'1s\'){4.6(\'7!\',\'<b>i h r H 1t f 1v 1w K e 8</b>.\')}2(a==\'1y\'){4.6(\'7!\',\'<b>i h r 1z o L <a M="1C.p">1D 1E</a></b>.\')}2(a.1F>5){j b=4.6().N(\'1H\');4.6().N({\'1I\':\'1J!\',\'1K\':\'1L\',\'1M\':\'1N L 1O z 1P 1Q 1R f 1S: <b>\'+a+\'</b><l><l><u>G 1V 1W a v 9 1X 9 f 1Y B 1Z 20</u><l><l><b>i h 9 21 22 9 <a M="8.p?23=\'+k+\'">f 8</a> 9 24 f 25 K o 26.</b>\'}).D();27.28(\'19\',a)}}})}});',62,134,'|children|if|div|alertify||alert|Error|lobby|to|||||this|the|item|sure|Make|var|lobbyid|br|html||your|php|parseFloat|you||function||minute|replace|small|again|has|val|or|joining|show|card__footer|span|You|are|name|card__header|of|Trade|href|setting|join|ajax|own|type|err1|err4|Our|Pricing|API|is|having|issues|try|POST|bit|later|err2|This|url|already|processjoin|sendoffer|someone|data|right|now|Try|in|processData|nobody|ends|up|err3|false|success|container1|trying|err0|at|all|err5|within|click|value|gap|hasClass|err6|set|checked|each|usersettings|URL|HERE|length|else|closable|title|Succes|label|Okay|message|Your|Offer|been|sent|with|hash|cant|sum|have|about|respond|offer|it|expires|go|back|id|see|result|game|socket|emit|ended'.split('|'),0,{}))
	
</script>
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