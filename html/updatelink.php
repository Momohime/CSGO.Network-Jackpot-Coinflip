<?php
	@include_once('link.php');
	@include_once('steamauth/steamauth.php');

	if(!isset($_SESSION["steamid"])) {
		Header("Location: index.php");
		exit;
	}

	$mytrade 	= fetchinfo("tlink","users","steamid",$_SESSION["steamid"]);
	$link 		= $_POST["link"];
	$link 		= $conn->real_escape_string($link);
	$steam 		= $_SESSION["steamid"];

	if(strlen($link)>20) {
		$conn->query("UPDATE users SET `tlink`='$link' WHERE `steamid`='$steam'");

	if($mytrade)
	{
		$conn->query("INSERT INTO `messages` (`type`,`app`,`userid`,`title`,`msg`,`time`,`active`) VALUES ('success','0','$steam','Success!','You have successfully changed your Trade URL!','10',1)");
	}
	if(!$mytrade)
	{
		$conn->query("INSERT INTO `messages` (`type`,`app`,`userid`,`title`,`msg`,`time`,`active`) VALUES ('success','0','$steam','Success!','You have successfully changed set Trade URL!','10',1)");
	}
}
else
{	
	$conn->query("INSERT INTO `messages` (`type`,`app`,`userid`,`title`,`msg`,`time`,`active`) VALUES ('error','0','$steam','Error!','Invalid Trade URL!','10',1)");
}

Header("Location: usersettings.php");

exit;
?>