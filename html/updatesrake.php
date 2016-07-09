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
$srk = $_POST["srk"];

if($srk)
{
	mysql_query("UPDATE info SET `value`='$srk' WHERE `name`='srake'");
}

$srk2 = $_POST["srk2"];

if($srk2)
{
	mysql_query("UPDATE p2info SET `value`='$srk2' WHERE `name`='srake'");
}
	
if($srk)
{
	Header("Location: apot1.php");
}
if($srk2)
{
	Header("Location: apot2.php");
}

exit;
?>