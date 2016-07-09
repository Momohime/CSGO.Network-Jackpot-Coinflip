<?php
@include_once('link.php');
@include_once('steamauth/steamauth.php');
$cg = fetchinfo("value","info","name","current_game");
$timeleft = fetchinfo("starttime","games","id",$cg);

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
			url: "winner1.php",
			success: function(msg)
			{
				$("span.wnr").html(msg);
			}
		});
	},2000)
	
	</script>';
}

?>