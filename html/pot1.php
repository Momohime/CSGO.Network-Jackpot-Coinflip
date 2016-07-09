
<?php
@include_once('link.php');
@include_once('steamauth/steamauth.php');
$cg = fetchinfo("value","info","name","current_game");
$cb = fetchinfo("cost","games","id",$cg);
$cb=round($cb,2);
echo '$'.$cb;

?>