<?php
########################################
#									   #
#	LEAKED BY OVERWATCHGG @ LEAKFORUMS #
#									   #
########################################
error_reporting(0);	
include ('link.php');
include ('core.php');
require_once('steamauth/steamauth.php');
@include_once('steamauth/userInfo.php');
$page="cf";

if(!isset($_SESSION["steamid"]))
{
	Header("Location: coinflip.php");
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

$return=$_SERVER['HTTP_REFERER'];

		echo "
		<a href='coinflip.php'><font size='1' color='grey'>Go back to the lobbies</font></a>
		<br>
		<font color='red'>If you don't see your skins make sure your inventory is public and refresh this page.</font>
		<br>
				<center style='border: 3px solid black; padding: 1%;'>

		<img src='images/ct-coin-bw.png' class='coin ct bw' width='100px'>
		<img src='images/t-coin.png' class='coin t bw' width='100px'>
		<br>
		<br>
		";
		echo'	
				<h3><font color="#42575E">GAP</font></h3>
                <input type="number" value="0.05" step="0.01" min="0.05" max="0.05" name="value" id="Text2">&emsp;<label class="maxgap" for="Text2"><font color="#42575E">Max GAP: $<span>0.05<span></label></font>
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
		
		
		<script>

			$('img.t').attr('src','images/t-coin-bw.png');
			$('img.ct').attr('src','images/ct-coin.png');
			$(\".coinflside\").attr('value','CT');

		
			$('.coin').click(function(){
				if($(this).hasClass('ct')){
					if($(\".side\").val() == 'T'){
						$(this).removeClass('bw');
						$(\".coinflside\").attr('value','CT');
						$(this).attr('src','images/ct-coin.png');
						$('img.t').attr('src','images/t-coin-bw.png');
					}
				}
				else{
					if($(\".side\").val() == 'CT'){
						$(this).removeClass('bw');
						$(\".coinflside\").attr('value','T');
						$(this).attr('src','images/t-coin.png');
						$('img.ct').attr('src','images/ct-coin-bw.png');
					}
				}
			});
		</script>
		<input type='hidden' name='side' class='side coinflside' value='CT'>
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
		foreach($parsedInv->rgInventory as $k => $v){
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
                           <img class="item-card__image item-card__image--zoom" src="http://steamcommunity-a.akamaihd.net/economy/image/'.$img.'" alt="'.$name.'">
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
		eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('F k=0;I U(){9(i.d==v){n.s("i").d=r;9(k!=0){$("#q").Q(\'o\')}}A{n.s("i").d=v;$("#q").E("o",r)}}$("5.g").O(I(){F b=$(8).4(\'5\');9($(8).4(\'5\').N("j-h-G")){n.s("i").d=v;$("#q").E("o",r);$(8).4(\'5\').H("j-h-G");$(8).4(\'5\').H("d");$(8).4(\'5\').m("j-h-K");k--;T=($("#7").6()*0.10);$("#7").6(f(f($("#7").6())-f($(8).4("5").4(\'5.g-B\').4(\'C\').4(\'c\').6())).e(2));7=($("#7").6()*0.10);9(7<0.t){7=0.t}J=$("#l").u();$(\'.L c\').6(7.e(2));$(\'#l\').x(\'w\',7.e(2));D=$(\'#l\').x(\'w\');9(J>D){$("#l").u(D)}$(\'#y 5\').P(I(){F a=$(8).R();S.11(a+" - "+b.4("5").4(\'5.g-M\').4(\'.z\').6()+" - "+b.4("c.u").6());9(a==b.4(".z").6()+" - "+b.4("c.u").6()){$(8).V("");$(8).W().4("X").Y()}})}A{9(k+1<=10){$(8).4(\'5\').m("j-h-G");$(8).4(\'5\').H("j-h-K");n.s("i").d=v;$("#q").E("o",r);k++;$(8).4(\'5\').m("d");$(8).4("p").m("Z");$("#7").6(f((f($("#7").6())+f($(8).4("5").4(\'5.g-B\').4(\'C\').4(\'c\').6())).e(3)));7=($("#7").6()*0.10);9(7<0.t){7=0.t}$(\'.L c\').6(7.e(2));$(\'#l\').x(\'w\',7.e(2));$("#y").6($("#y").6()+"<5>"+$(8).4("5").4(\'5.g-M\').4(\'.z\').6()+" - "+$(8).4("5").4(\'5.g-B\').4(\'C\').4(\'c\').6()+"</5>")}A{}}});',62,64,'||||children|div|html|value|this|if|||span|checked|toFixed|parseFloat|item|quality|myCheck|steam|selected|Text2|addClass|document|disabled||submit|true|getElementById|05|val|false|max|attr|deposit|name|else|card__footer|small|mv|prop|var|covert|removeClass|function|omv|baseGrade|maxgap|card__header|hasClass|click|each|removeAttr|text|console|ovalue|check|replaceWith|parent|br|remove|chk||log'.split('|'),0,{}))
		</script>

<script>

	eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('$(".J").X(f(){g a=0;g b=0;b=a+"="+$(\'.I\').r();a++;b=b+"&"+a+"="+$(\'#A\').r();a++;g c=0;$("2.K .9").F(f(){3($(7).1(\'2\').Z("13")){3(a==0){b=a+"="+$(7).1("2").1(\'2.9-o\').1(\'.p\').e();c=c+h($(7).1("2").1(\'2.9-i\').1(\'j\').1(\'k\').e());a++}1h{b=b+"&"+a+"="+$(7).1("2").1(\'2.9-o\').1(\'.p\').e();c=c+h($(7).1("2").1(\'2.9-i\').1(\'j\').1(\'k\').e());a++}}});c=h(c);b=b+"&"+"1o="+c;3(a!=0){$.1C({1D:"1I",y:\'z.l\',B:b,C:D,E:f(a){a=a.m(\'\\n\',\'\');a=a.m(\'    \',\'\');3(a==\'G\'||a==\'H\'){6.4(\'8!\',\'<b>L M N O P Q, R S a U V</b>.\')}3(a==\'W\'){6.4(\'8!\',\'<b>q Y d 10 11 12 s 14 (15: $0.16 | 17: 18 19)</b>.\')}3(a==\'1a\'){6.4(\'8!\',\'<b>1b 1c: $0.1d</b>.\')}3(a==\'1e\'){6.4(\'8!\',\'<b>q 1f d 1g t T 1i</b>.\')}3(a==\'1j\'){6.4(\'8!\',\'<b>1k 1l 1m 1n s v <a 1p="1q.l">1r 1s</a></b>.\')}3(a.1t>5){6.4(\'1u!\',\'1v v 1w 1x 1y 1z 1A d 1B: <b>\'+a+\'</b><w><w><u>1E 1F 1G a 1H x 1J x d 1K t 1L 1M</u>.\');1N.1O(\'1P\',a)}}})}});',62,114,'|children|div|if|alert||alertify|this|Error|item||||the|html|function|var|parseFloat|card__footer|small|span|php|replace||card__header|name|Please|val|your|or||Trade|br|to|url|processhost|Text2|data|processData|false|success|each|err1|err4|side|show|container1|Our|Pricing|API|is|having|issues|try|again||bit|later|err2|click|fix|hasClass|Gap|value|of|checked|lobby|Min|05|Max|displayed|above|err2b|Minimum|Deposit|20|err3|choose|CT|else|Coin|err5|Make|sure|you|set|sum|href|usersettings|URL|HERE|length|Success|Your|Offer|has|been|sent|with|hash|ajax|type|You|have|about|minute|POST|respond|offer|it|expires|socket|emit|sendoffer'.split('|'),0,{}))

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