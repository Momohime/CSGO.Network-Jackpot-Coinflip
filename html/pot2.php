
<?php
@include_once('link.php');
@include_once('steamauth/steamauth.php');
$cg = fetchinfo("value","p2info","name","current_game");
$cb = fetchinfo("cost","p2games","id",$cg);
$cb=round($cb,2);
echo '$'.$cb;

?>