<?php
$adminid='76561198165809540'; // The Steam ID of the Admin - This is needed so the Rake Items are sent to the correct person
$admintradelink='https://steamcommunity.com/tradeoffer/new/?partner=205543812&token=bUIFOWjn'; // FULL TRADE LINK of the Admin - This is also needed for the Rake System
$rsecret='E'; // Also change this on the Bot, both should be the same secret.

$gsecret = $_GET['secret'];
$p2 = $_GET['p2'];

if($p2==true)
{
	$p2t='p2';
}
else
{
	$p2t='';
}

if($rsecret!=$gsecret)
{
	echo'Invalid Secret Code!';
	die();
}

$mov = "0.".mt_rand(100000000,999999999);
@include_once('link.php');
@include_once('steamauth/steamauth.php');
$cg = fetchinfo("value","".$p2t."info","name","current_game");

$in = fetchinfo("itemsnum","".$p2t."games","id",$cg);

if($in==0)
{
	echo 'There are no skins in the pot ( <b>`itemsnum` for table `'.$p2t.'games` WHERE `id`='.$cg.'</b> = <u>'.$in.'</u> ) <br>The round will not be ended.';
	die();
}
mysql_query("UPDATE ".$p2t."games SET `module`='$mov' WHERE `id`='$cg'");

$from=0;
$to=0;

$rs = mysql_query("SELECT * FROM ".$p2t."games WHERE `id`='$cg'");
$row = mysql_fetch_array($rs);
$jackpotcost = $row["cost"];
$wincost = $jackpotcost*$mov;
$jackpot1 = round($jackpotcost,2);
echo 'Jackpot Cost: '.$jackpotcost.'<br>Mov: '.$mov.'<br>Winning Ticket: '.$wincost.'<br>';

echo'<br>';

$rs = mysql_query("SELECT * FROM ".$p2t."game".$cg."");
while($grow = mysql_fetch_array($rs))
{	
	$from=$to;
	$to+=$grow['value'];
	echo 'Pool increased from $'.$from.' to $'.$to.' by '.$grow['username'].'\'s '.$grow['item'].' &ensp; $'.$grow['value'].'';
	if($wincost>=$from && $wincost<=$to)
	{
		echo'&emsp; Winner!';
		$winnerid=$grow['userid'];
		$winnername = mysql_real_escape_string($grow['username']);
		
		$rs = mysql_query("SELECT SUM(value) AS ValueSum FROM `".$p2t."game$cg` WHERE `userid`='$winnerid'") or die(logsqlerror(mysql_error()));
		$row = mysql_fetch_array($rs);
		$wonpercent = 100*$row["ValueSum"]/$jackpotcost;
		$wonpercent=round($wonpercent,2);
		
		mysql_query("UPDATE ".$p2t."games SET `percent`='$wonpercent', `winner`='$winnername', `userid`='$winnerid' WHERE `id`='$cg'") or die(mysql_error());
		
	}
		
	echo'<br>';
}

$rs = mysql_query("SELECT * FROM ".$p2t."game".$cg." WHERE `userid`=".$winnerid."");
$winnercost=0;
while($wrow = mysql_fetch_array($rs))
{
	$winnercost+=$wrow['value'];
	
}
$profit=$jackpotcost-$winnercost;

echo '<br>'.$winnername.' won $'.$jackpotcost.' with a '.$wonpercent.'% chance. Profit: $'.$profit.'<br>';

$rs = mysql_query("SELECT userid FROM `".$p2t."game$cg` GROUP BY userid") or die(mysql_error());
$currenttime=time();

while($row = mysql_fetch_array($rs))
{
	if($row["userid"] == $winnerid)
	{
		$time=time();
		$time=$time+10;
		mysql_query("UPDATE users SET `profit`=profit+$profit, `won`=won+$jackpotcost, `gameswon`=gameswon+1, `games`=games+1 WHERE `steamid`='$winnerid'") or die(mysql_error());
		mysql_query("INSERT INTO `messages` (`type`,`app`,`userid`,`title`,`msg`,`time`,`active`,`delay`) VALUES ('success','0','$winnerid','Congratulations!','You won $$jackpotcost in Game #$cg with a $wonpercent% chance!','10',1,$time)");
	}
	else
	{
		$loserid = $row["userid"];
		$rs = mysql_query("SELECT * FROM ".$p2t."game".$cg." WHERE `userid`=".$loserid."");
		$losercost=0;
		while($lrow = mysql_fetch_array($rs))
		{
			$losercost+=$lrow['value'];
			
		}
		$time=time();
		$time=$time+10;
		mysql_query("UPDATE users SET `profit`=profit-$losercost, `games`=games+1 WHERE `steamid`='$loserid'") or die(mysql_error());
		mysql_query("INSERT INTO `messages` (`type`,`app`,`userid`,`title`,`msg`,`time`,`active`,`delay`) VALUES ('error','0','$loserid','GL Next Game!','$winnername won $$jackpotcost in Game #$cg with a $wonpercent% chance!','10',1,$time)");

	}
}

$rs = mysql_query("SELECT item,value,userid,assetid,id FROM `".$p2t."game$cg`") or die(mysql_error());
$ila = 0;
while($row = mysql_fetch_array($rs))
{
	$itemsar[$ila] = mysql_escape_string($row["item"]);
	$valuear[$ila] = $row["value"];
	$userar[$ila] = $row["userid"];
	$assetid[$ila] = $row["assetid"];
	$idar[$ila] = $row["id"];
	$ila++;
}

for ($j = 0; $j < $ila-1; $j++)
{
	for ($i = 0; $i < $ila-$j-1; $i++) {
		if ($valuear[$i] > $valuear[$i+1]) {
			
			$b = $valuear[$i];
            $valuear[$i] = $valuear[$i+1];
            $valuear[$i+1] = $b;
			
			$cc = $itemsar[$i];
            $itemsar[$i] = $itemsar[$i+1];
            $itemsar[$i+1] = $cc;
						
			$d = $userar[$i];
            $userar[$i] = $userar[$i+1];
            $userar[$i+1] = $d;
			
			$e = $assetid[$i];
            $assetid[$i] = $assetid[$i+1];
            $assetid[$i+1] = $e;
			
			$f = $idar[$i];
            $idar[$i] = $idar[$i+1];
            $idar[$i+1] = $f;
			
        }
    }
}

$rakeitems='';
$rakevalue=0;
$firstitem=true;
if($jackpotcost>0)
{
	
	$rake = fetchinfo("value","".$p2t."info","name","rake");

	if(stristr(strtolower($winnername),"website.com") != NULL)
	{
		$rake = fetchinfo("value","".$p2t."info","name","srake");
	}
	$premium=fetchinfo("premium","users","steamid",$winnerid);
	if($premium==1)
	{
		$rake = fetchinfo("value","".$p2t."info","name","prake");
	}
	$rake /= 100;
	$rake *= $jackpotcost;
	for($i = $ila-1; $i >= 0; $i--)
	{
		if($valuear[$i] <= $rake && $userar[$i]!=$winnerid)
		{
			if($firstitem==true)
			{
				$rakeitems=$itemsar[$i];
				$rakeasset=$assetid[$i];

			}
			else
			{
				$rakeitems.="/".$itemsar[$i];
				$rakeasset.="/".$assetid[$i];
			}
			$thisid=$idar[$i];
			mysql_query("UPDATE `".$p2t."game$cg` SET `rake`='1' WHERE `id`='$thisid'");
			$rakevalue+=$valuear[$i];
			$itemsar[$i] = "";
			$assetid[$i] = "";
			$idar[$i] = "";
			$rake -= $valuear[$i];
			$firstitem=false;
		}
	}
}

$rs = mysql_query("SELECT * FROM users WHERE `steamid`='$winnerid'");
$row = mysql_fetch_array($rs);
$tradelink = $row["tlink"];
$token = substr(strstr($tradelink, 'token='),6);

$admintoken = substr(strstr($admintradelink, 'token='),6);

if($rakeitems!='')
{
	echo '<br>Rake for the house: $'.$rakevalue.'<br>';
	mysql_query("INSERT INTO `".$p2t."rakeitems` (`id`,`userid`,`status`,`token`,`items`,`assetid`,`value`) VALUES ('$cg','$adminid','active','$admintoken','$rakeitems','$rakeasset','$rakevalue')") or die(mysql_error());	
}

$boolv = false;
for($i=0; $i < $ila; $i++)
{
	if($itemsar[$i] == "") continue;
	if($boolv == false)
	{
		$itemstring = $itemsar[$i];
		$assetstring = $assetid[$i];
	}
	else
	{
		$itemstring .= "/".$itemsar[$i];
		$assetstring .= "/".$assetid[$i];
	}
	$boolv = true;
}

mysql_query("INSERT INTO `".$p2t."queue` (`id`,`userid`,`status`,`token`,`items`,`assetid`) VALUES ('$cg','$winnerid','active','$token','$itemstring','$assetstring')") or die(mysql_error());	

$cg++;
$hash=md5($cg);
mysql_query("INSERT INTO `".$p2t."games` (`id`,`starttime`,`cost`,`winner`,`userid`,`percent`,`itemsnum`,`module`,`hash`) VALUES ('$cg','2147485547','0','','',NULL,'0','','$hash')");
mysql_query("CREATE TABLE `".$p2t."game$cg` (
  `id` int(11) NOT NULL auto_increment,
  `userid` varchar(70) NOT NULL,
  `username` varchar(70) NOT NULL,
  `item` text,
  `assetid` varchar(70) NOT NULL,
  `offerid` varchar(70) NOT NULL,
  `color` text,
  `value` float NOT NULL,
  `avatar` varchar(512) NOT NULL,
  `image` text NOT NULL,
  `rake` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;");
mysql_query("TRUNCATE TABLE `".$p2t."game$cg`");
mysql_query("UPDATE ".$p2t."info SET `value`='$cg' WHERE `name`='current_game'");
mysql_query("UPDATE ".$p2t."info SET `value`='waiting' WHERE `name`='state'");

?>