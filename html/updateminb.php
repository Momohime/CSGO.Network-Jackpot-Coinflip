<?php
@include_once('link.php');
@include_once('steamauth/steamauth.php');
if(!isset($_SESSION["steamid"])) {
	Header("Location: index.php");
	exit;
}
$admin = fetchinfo("admin","users","steamid",$_SESSION["steamid"]);

if($admin==0)
{
	die();
}
$mb = $_POST["mb"];

if($mb)
{
	mysql_query("UPDATE info SET `value`='$mb' WHERE `name`='minbet'");
}

$mb2 = $_POST["mb2"];

if($mb2)
{
	mysql_query("UPDATE p2info SET `value`='$mb2' WHERE `name`='minbet'");
}
	

if($mb)
{
	Header("Location: apot1.php");
}
if($mb2)
{
	Header("Location: apot2.php");
}


exit;
?>