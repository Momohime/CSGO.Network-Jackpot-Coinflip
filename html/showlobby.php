<?php
session_start();
include_once("link.php");
$id=$_GET['id'];
$id = preg_replace('~[\r\n]+~', '', $id);
$id=mysql_real_escape_string($id);
$id=trim($id);

if(!$id)
{
	die();
}

$pid=fetchinfo("cid","cflobbies","ID",$id);
$pav=fetchinfo("avatar","users","steamid",$pid);
$pname=fetchinfo("name","users","steamid",$pid);
$pname=mysql_escape_string($pname);
$pname=htmlentities(strip_tags($pname));
$wager=fetchinfo("value","cflobbies","ID",$id);
$gap=fetchinfo("gap","cflobbies","ID",$id);
$steamid=$_SESSION['steamid'];
$fc=fetchinfo("flip","cflobbies","ID",$id);
$askins=fetchinfo("askins","cflobbies","id",$id);

if($fc==1)
{
	$hs='<img src="images/ct-coin.png" width="25">';
	$ms='<img src="images/t-coin.png" width="25">';
}
if($fc==2)
{
	$hs='<img src="images/t-coin.png" width="25">';
	$ms='<img src="images/ct-coin.png" width="25">';
}
echo '
<tr class="cent" id="cf'.$id.'">
<td>'.$hs.'</td>
<td>
<a href="profile.php?action=view&id='.$pid.'" target="_BLANK">

<img src="'.$pav.'" class="img-circle" width="30">

</a>
&ensp; 
<a href="profile.php?action=view&id='.$pid.'" target="_BLANK">
<b>'.$pname.'</b>
</a>
</td>
<td>$'.$wager.'</td>
<td>+- $'.$gap.'</td>
<td>'.$askins.'</td>
<td class="cent">'.$ms.'</td>
<td><a href="lobby.php?id='.$id.'">
<button type="button" class="btn btn-primary waves-effect waves-light">View Lobby</button>
</a></td>
</tr>
';