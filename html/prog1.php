
<?php
@include_once('link.php');
@include_once('steamauth/steamauth.php');

$cg = fetchinfo("value","info","name","current_game");
$ms = fetchinfo("value","info","name","maxritem");
$cs = fetchinfo("itemsnum","games","id",$cg);

$percent = $cs / $ms *100;
echo'<div class="progress progress-lg m-b-5" style="background: #d2d2d2!important; height: 40px!important; width: 100%; margin: 0 auto">
	  <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="0" style="width: '.$percent.'%; padding: 10px!important;  font-size: 25px; ">
	  <span class="">'.$cs.'/'.$ms.'</span>
	  </div>
      </div>';

?>