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
$prk = $_POST["mri"];

if($prk)
{
	mysql_query("UPDATE info SET `value`='$prk' WHERE `name`='maxritem'");
}

$prk2 = $_POST["mri2"];

if($prk2)
{
	mysql_query("UPDATE p2info SET `value`='$prk2' WHERE `name`='maxritem'");
}
	

if($prk)
{
	Header("Location: apot1.php");
}
if($prk2)
{
	Header("Location: apot2.php");
}

exit;
?>