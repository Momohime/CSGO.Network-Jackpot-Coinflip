<?php
$rsecret='E'; // Also change this on the Bot, both should be the same secret password.
$gsecret = $_GET['secret'];
if($rsecret!=$gsecret)
{
	echo'Invalid Secret Code!';
	die();
}

include_once("link.php");
if(!isset($_GET) or !isset($_GET["id"]) or !is_numeric($_GET["id"]))
{
	die();
}
$id=$_GET['id'];
$id=mysql_escape_string($id);
$time=time();
$creatorid=fetchinfo("cid","cflobbies","id",$id);
$creatorname=fetchinfo("name","users","steamid",$creatorid);
$creatoravatar=fetchinfo("avatar","users","steamid",$creatorid);
$wager=fetchinfo("value","cflobbies","id",$id);
$participantid=fetchinfo("pid","cflobbies","id",$id);
$participantname=fetchinfo("name","users","steamid",$participantid);
$participantavatar=fetchinfo("avatar","users","steamid",$participantid);
$winner=fetchinfo("wid","cflobbies","id",$id);
$flipchoice=fetchinfo("flip","cflobbies","id",$id);
$winningflip=fetchinfo("wflip","cflobbies","id",$id);
$win=($wager*2);
$profit=$win-$wager;
$cvalue=fetchinfo("value","cflobbies","id",$id);
$pvalue=fetchinfo("pvalue","cflobbies","id",$id);
$ovalue=$cvalue+$pvalue;

$cofferid=fetchinfo("offerid","cflobbies","id",$id);
$pofferid=fetchinfo("pofferid","cflobbies","id",$id);

if(!$creatorid || !$participantid) // No Lobby
{
	die();
}

$otime=time();
$time=$otime+8;
if($winningflip==1 && $flipchoice==1 || $winningflip==2 && $flipchoice==2) // Host wins
{
	if(!$winner)
	{
		$tradelink=fetchinfo("tlink","users","steamid",$creatorid);
		$token = substr(strstr($tradelink, 'token='),6);
		$conn->query("INSERT INTO `cfwithdrawqueue` (`userid`,`token`,`cofferid`,`pofferid`,`status`,`value`,`winner`,`gameid`) VALUES ('$creatorid','$token','$cofferid','$pofferid','active',$ovalue,'host','$id')");
		
		$conn->query("INSERT INTO `messages` (`type`,`app`,`userid`,`title`,`msg`,`time`,`active`,`delay`) VALUES ('success','0','$creatorid','Congratulations!','You won $$wager on the Coinflip!','10',1,$time)");
		$conn->query("INSERT INTO `messages` (`type`,`app`,`userid`,`title`,`msg`,`time`,`active`,`delay`) VALUES ('error','0','$participantid','Better Luck next time!','You lost $$wager on the Coinflip.','10',1,$time)");
		$conn->query("UPDATE `cflobbies` SET `wid`='$creatorid' WHERE `id`='$id'");
		$conn->query("UPDATE `cflobbies` SET `ended`='$otime' WHERE `id`='$id'");
	}
}
else // Participant wins
{
	if(!$winner)
	{

		$tradelink=fetchinfo("tlink","users","steamid",$participantid);
		$token = substr(strstr($tradelink, 'token='),6);
		$conn->query("INSERT INTO `cfwithdrawqueue` (`userid`,`token`,`cofferid`,`pofferid`,`status`,`value`,`winner`,`gameid`) VALUES ('$participantid','$token','$cofferid','$pofferid','active',$ovalue,'part','$id')");
		
		$conn->query("INSERT INTO `messages` (`type`,`app`,`userid`,`title`,`msg`,`time`,`active`,`delay`) VALUES ('success','0','$participantid','Congratulations!','You won $$wager on the Coinflip!','10',1,$time)");
		$conn->query("INSERT INTO `messages` (`type`,`app`,`userid`,`title`,`msg`,`time`,`active`,`delay`) VALUES ('error','0','$creatorid','Better Luck next time!','You lost $$wager on the Coinflip.','10',1,$time)");
		$conn->query("UPDATE `cflobbies` SET `wid`='$participantid' WHERE `id`='$id'");
		$conn->query("UPDATE `cflobbies` SET `ended`='$otime' WHERE `id`='$id'");
	}
}
?>
