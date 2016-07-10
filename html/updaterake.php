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
$rk = $_POST["rk"];

if($rk)
{
	$conn->query("UPDATE info SET `value`='$rk' WHERE `name`='rake'");
}

$prk2 = $_POST["rk2"];

if($prk2)
{
	$conn->query("UPDATE p2info SET `value`='$prk2' WHERE `name`='rake'");
}
	

if($rk)
{
	Header("Location: apot1.php");
}
if($prk2)
{
	Header("Location: apot2.php");
}

exit;
?>