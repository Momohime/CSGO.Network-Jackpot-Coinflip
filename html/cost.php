<?php
include ('link.php');
$item = $_GET['item'];
$debugmode = $_GET['db'];
$hitem = str_replace("\"", "", $item);
$hitem = str_replace("\'", "", $hitem);
$hitem = str_replace(" ", "%20", $hitem);
$hitem = str_replace("\\", "", $hitem);
$mhitem=mysql_real_escape_string($hitem);
$query = mysql_query("SELECT * FROM `items` WHERE `name`='$mhitem'");
$updateinterval=604800; // 7 days in seconds
$time=time();
$fetch=0;
$insert=0;
if(mysql_num_rows($query) == 0) 
{
	if($debugmode==1)
	{
		echo '<br> [DEBUG] Skin not found in the database';
	}
	$fetch=1;
	$insert=1;
}
else
{
	$row = mysql_fetch_array($query);
	$price=$row['cost'];
	$lastupdate=$row['lastupdate'];
	$updatedate=$lastupdate+$updateinterval;
	if($time>$updatedate)
	{
		if($debugmode==1)
		{
			echo'<br> [DEBUG] The skin cost needs to be updated, performing price check & update';
		}
		$fetch=1;
	}
	else
	{
		if($debugmode==1)
		{
			echo'<br> [DEBUG] Fetching price from the Database: ';
		}
		if($price)
		{
			echo $price;
			$fetch=0;
		}
		else
		{
			$fetch=1;
		}
	}
}

if($fetch==1)
{
	if($debugmode==1)
	{
		echo '<br> [DEBUG] Fetching price from Steam: ';
	}
	$link = "http://steamcommunity.com/market/priceoverview/?currency=1&appid=730&market_hash_name=".$hitem;
	$string = file_get_contents($link);
	$obj = json_decode($string);
	if($obj->{'success'} == "0") die("notfound");
	$lowest_price = $obj->{'median_price'};
	echo $lowest_price = str_replace("$", "", $lowest_price);
	if($insert==1)
	{
		if($debugmode==1)
		{
			echo '<br> [DEBUG] Inserting new item into database';
		}
		mysql_query("INSERT INTO `items` (`name`,`cost`,`lastupdate`) VALUES ('$mhitem','$lowest_price','$time')");	
	}
	else
	{
		if($price!=$lowest_price && $lowest_price && $lowest_price!=0)
		{
			if($debugmode==1)
			{
				echo '<br> [DEBUG] Updating database: cost, lastupdate';
			}
			mysql_query("UPDATE items SET `cost`='$lowest_price' WHERE `name`='$mhitem'");
			mysql_query("UPDATE items SET `lastupdate`='$time' WHERE `name`='$mhitem'");
		}
		else
		{
			if($lowest_price && $lowest_price!=0)
			{
				if($debugmode==1)
				{
					echo '<br> [DEBUG] The price has not changed, updating `lastupdate` only';
				}
				mysql_query("UPDATE items SET `lastupdate`='$time' WHERE `name`='$mhitem'");
			}
		}
	}
}
?>