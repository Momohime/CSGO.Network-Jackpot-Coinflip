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
$srk = $_POST["nm"];


mysql_query("UPDATE info SET `value`='$srk' WHERE `name`='nmsg'");
	

Header("Location: achat.php");

exit;
?>