<?php
@include_once('link.php');
@include_once('steamauth/steamauth.php');
if(isset($_SESSION["steamid"]))
{
	$prices = file_get_contents('https://api.csgofast.com/price/all');
    $parsedPrices = json_decode($prices);
	
	$sid=$_SESSION["steamid"];
	$tradelink = fetchinfo("tlink","users","steamid",$sid);
	$token = substr(strstr($tradelink, 'token='),6);
	if($tradelink)
	{
		$array[0] = "";
		$array2[0] = "";
		for ($i=1; $i < sizeof($_POST)-1; $i++)
		{ 
			$array[$i] = $_POST[$i];
		}
		$items = join('/',$array);
		
		
		for ($i=1; $i < sizeof($array); $i++) { 
			$array2[$i-1] = $array[$i];
		}

		
		$lobbyid = $_POST[0];
		$lobbyid=mysql_escape_string($lobbyid);
		$gap=fetchinfo("gap","cflobbies","ID",$lobbyid);
		$value=fetchinfo("value","cflobbies","ID",$lobbyid);
		$valuemin=$value-$gap;
		$valuemax=$value+$gap;
		
		

		$sum=0;
		foreach($array2 as $key => $value)
		{
			$price = $parsedPrices->$value;
			$sum+=$price;
			if(!$price || $price==0)
			{
				$error=1;
			}
		}
		if($sum<=$valuemax && $sum>=$valuemin )
		{
			function generateRandomString($length = 10)
			{
				$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
				$charactersLength = strlen($characters);
				$randomString = '';
				for ($i = 0; $i < $length; $i++) {
					$randomString .= $characters[rand(0, $charactersLength - 1)];
				}
				return $randomString;
			}
			$key=generateRandomString(6);
			if($sum!=0)
			{
				if($lobbyid)
				{
					$pid=fetchinfo("pid","cflobbies","ID",$lobbyid);
					$cid=fetchinfo("cid","cflobbies","ID",$lobbyid);
					$poid=fetchinfo("pofferid","cflobbies","ID",$lobbyid);
					if(!$pid && $poid==0)
					{
						if($error==0)
						{	
							if($cid!=$sid)
							{
								$items=mysql_escape_string($items);
								$sum=mysql_escape_string($sum);
								$token=mysql_escape_string($token);
								mysql_query("INSERT INTO `cfqueue` (`userid`,`value`,`hash`,`token`,`skins`,`status`,`type`) VALUES ('$sid','$sum','$key','$token','$items','active','$lobbyid')") or die(mysql_error());
								$lastid=mysql_insert_id();
								$newkey='l'.$lobbyid.'g'.$lastid.'h'.$key;
								mysql_query("UPDATE `cfqueue` SET `hash`='$newkey' WHERE `id`='$lastid'");
								echo $newkey;
							}
							else
							{
								echo 'err0';
							}
						}
						else
						{
							echo'err1';
						}
					}
					else
					{
						echo'err2';
					}
				}
				else
				{
					echo'err3';
				}
			}
			else
			{
				echo'err4';
			}
		}
		else
		{
			echo'err5';
		}
		
	}
	else
	{
		echo'err6';
	}
}
?>