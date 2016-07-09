<?php
session_start();
include_once("link.php");

if(!isset($_GET) or !isset($_GET["id"]) or !is_numeric($_GET["id"]))
{
	die();
}
$id=$_GET['id'];
$creatorid=fetchinfo("cid","cflobbies","ID",$id);
$creatorname=fetchinfo("name","users","steamid",$creatorid);
$creatoravatar=fetchinfo("avatar","users","steamid",$creatorid);
$wager=fetchinfo("value","cflobbies","ID",$id);
$participantid=fetchinfo("pid","cflobbies","ID",$id);
$participantname=fetchinfo("name","users","steamid",$participantid);
$participantavatar=fetchinfo("avatar","users","steamid",$participantid);
$winner=fetchinfo("wid","cflobbies","ID",$id);
$flipchoice=fetchinfo("flip","cflobbies","ID",$id);
$winningflip=fetchinfo("wflip","cflobbies","ID",$id);
$rakepercent=10;
$rake=($wager*2)/$rakepercent;
$rake=floor($rake);
$win=($wager*2)-$rake;

$participantname=mysql_escape_string($participantname);
$participantname=htmlentities(strip_tags($participantname));

$creatorname=mysql_escape_string($creatorname);
$creatorname=htmlentities(strip_tags($creatorname));

if(!$creatorid || !$participantid) // no lobby
{
	die();
}


if($flipchoice==1)
{
	$hs='<img src="images/ct-coin.png" width="35">';
	$ms='<img src="images/t-coin.png" width="35">';
}
if($flipchoice==2)
{
	$hs='<img src="images/t-coin.png" width="35">';
	$ms='<img src="images/ct-coin.png" width="35">';
}
?>

<?php
echo '
'.$hs.'
&emsp; <a href="profile.php?action=view&id='.$creatorid.'" target="_BLANK">
<span data-toggle="tooltip" data-placement="top" title="" data-tooltip="'.$creatorname.'"><img  class="img-circle" src="'.$creatoravatar.'" width="80"></a></span>
&emsp;&emsp;
<font size="5">VS</font>
&emsp;&emsp;';
if($participantid)
{
	echo'<a href="profile.php?action=view&id='.$participantid.'" target="_BLANK">';
}
echo'
<span data-toggle="tooltip" data-placement="top" title="" data-tooltip="'.$participantname.'"><img class="img-circle" src="'.$participantavatar.'" width="80"></a></span> &emsp;
'.$ms.'
<br>
<br>
<br>
<br>

<center>
<b>The Coin will be flipped in 5 seconds</b>
</center>
<br>


							</div>';
?>
<br>
<br>

<div id="coin-flip-cont">
<div id="coin">
<div class="front"></div>
<div class="back"></div>
</div>
</div>
<script src="js/flip.js"></script>

<script>
$(document).ready(function(e)
{	

$('#coin').removeClass();

setTimeout(function() {
  $('#coin').addClass(getSpin(<?php echo $winningflip; ?>));
}, 5000);

setTimeout(function()
{
	$(".alert").remove();
	$(".wager").show();
},4800);

});
</script>
