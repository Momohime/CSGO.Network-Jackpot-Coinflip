<?php
@include_once('link.php');
$cg = fetchinfo("value","p2info","name","current_game");
$hash=md5($cg);

echo '<table class="table">
<tr class="danger">
<td>
<b>Game #'.$cg.' hash:</b> '.$hash.'
</td>
</tr>
</table>';

?>