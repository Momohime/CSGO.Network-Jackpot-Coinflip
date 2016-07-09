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
		for ($i=2; $i < sizeof($_POST); $i++)
		{ 
			$array[$i] = $_POST[$i];
		}
		$items = join('/',$array);
		
		for ($i=2; $i < sizeof($array); $i++) { 
			$array2[$i-2] = $array[$i];
		}
		$side = $_POST[0];
		$val = $_POST[1];
		if($side=="CT")
		{
			$side=1;
		}
		else if($side=="T")
		{
			$side=2;
		}
		$sum=0;
		$askins=0;
		foreach($array2 as $key => $value)
		{
			$askins++;
			$price = $parsedPrices->$value;
			$sum+=$price;
			if(!$price || $price==0)
			{
				$error=1;
			}
		}

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
		$maxgap=0.05;
		if($sum>1)
		{
			$maxgap=$sum*0.10;
			$maxgap=round($maxgap,2);
		}
		if($sum!=0)
		{
			if($side==1 || $side==2)
			{
				if($val>=0.05 && $val<=$maxgap)
				{
					if($error==0)
					{
						if($sum>=0.20)
						{
							$items=mysql_escape_string($items);
							$sum=mysql_escape_string($sum);
							$token=mysql_escape_string($token);
							$val=mysql_escape_string($val);
							$side=mysql_escape_string($side);
							
							mysql_query("INSERT INTO `cfqueue` (`userid`,`value`,`hash`,`token`,`skins`,`status`,`flip`,`gap`,`type`,`askins`) VALUES ('$sid','$sum','$key','$token','$items','active','$side','$val','host','$askins')") or die(mysql_error());
							$lastid=mysql_insert_id();
							$newkey=$lastid.$key;
							mysql_query("UPDATE `cfqueue` SET `hash`='$newkey' WHERE `id`='$lastid'");
							echo $newkey;
						}
						else
						{
							echo 'err2b';	
						}
					}
					else
					{
						echo 'err1';
					}
				}
				else
				{
					echo 'err2';
				}
			}
			else
			{
				echo 'err3';
			}
		}
		else
		{
			echo 'err4';
		}
	}
	else
	{
		echo 'err5';
	}
}
else
{
	
}
?>