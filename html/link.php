<?php

//session_start();
$link = mysql_connect("", "", ""); // MySQL Host , Username and password
$db_selected = mysql_select_db('', $link); // MySQL Database
mysql_query("SET NAMES utf8");

function fetchinfo($rowname,$tablename,$finder,$findervalue)
{
	if($finder == "1") $result = mysql_query("SELECT $rowname FROM $tablename");
	else $result = mysql_query("SELECT $rowname FROM $tablename WHERE `$finder`='$findervalue'");
	$row = mysql_fetch_assoc($result);
	return $row[$rowname];
}

function secureoutput($string)
{
	$string = mysql_real_escape_string($string);
	$string=htmlentities(strip_tags($string));
	$string=str_replace('>', '', $string);
	$string=str_replace('<', '', $string);
	$string=htmlspecialchars($string);
	return $string;
}
?>