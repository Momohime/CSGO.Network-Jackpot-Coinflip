<?php
@include_once('link.php');
@include_once('steamauth/steamauth.php');
$cg = fetchinfo("value","p2info","name","current_game");
$timeleft = fetchinfo("starttime","p2games","id",$cg);

if($timeleft == 2147483647)
{
	$timeleft = 120;
}
else
{
	$timeleft += 120-time();
}
if($timeleft<0)
{
	$timeleft='Ending round...';
	
}
echo $timeleft;

if($timeleft === 1)
{
	echo '
	<script>
	
	setTimeout(function()
	{
		$("span.wnr").removeClass("hidden");
		$.ajax({
			type: "GET",
			url: "winner2.php",
			success: function(msg)
			{
				$("span.wnr").html(msg);
			}
		});
	},2000)
	
	</script>';
}

?>