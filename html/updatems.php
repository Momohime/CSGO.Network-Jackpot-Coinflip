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

$ms = $_POST["ms"];

if($ms)
{
	mysql_query("UPDATE info SET `value`='$ms' WHERE `name`='maxitems'");
}

$ms2 = $_POST["ms2"];

if($ms2)
{
	mysql_query("UPDATE p2info SET `value`='$ms2' WHERE `name`='maxitems'");
}
	

if($ms)
{
	Header("Location: apot1.php");
}
if($ms2)
{
	Header("Location: apot2.php");
}

exit;
?>