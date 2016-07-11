<?php
	@include_once('link.php');
	@include_once('steamauth/steamauth.php');

	$cg = fetchinfo("value","p2info","name","current_game");
	$cb = fetchinfo("cost","p2games","id",$cg);

	$ms = fetchinfo("value","p2info","name","maxritem");
	$cs = fetchinfo("itemsnum","p2games","id",$cg);

	$percent = $cs / $ms *100;

	$rs = $conn->query("SELECT * FROM `p2game".$cg."` GROUP BY `userid` ORDER BY `id` DESC");
	$crs = "";

	if($rs->num_rows == 0)  {
		/*$lg=$cg-1;
		$lw = fetchinfo("winner","games","id",$lg);
		$ld = fetchinfo("userid","games","id",$lg);
		$lp = fetchinfo("percent","games","id",$lg);
		$li = fetchinfo("itemsnum","games","id",$lg);
		$lc = fetchinfo("cost","games","id",$lg);
		$la = fetchinfo("avatar","users","steamid",$ld);*/

	} else {
		$crs.=' <table class="table winnertable" style="width:100%; margin: 0 auto;">
					<tbody class="row lato">';

		$usern=0;
		while($row = $rs->fetch_array()) {
			$usern++;
			$ls++;
			$avatar 	= $row["avatar"];
			$userid 	= $row["userid"];
			$username 	= fetchinfo("name","users","steamid",$userid);
			$username 	= secureoutput($username);

			$rs2 = $conn->query("SELECT SUM(value) AS value FROM `p2game".$cg."` WHERE `userid`='$userid'");						
			$row = $rs2->fetch_assoc();
			$sumvalue = $row["value"];
			$sumvalue = round($sumvalue,2);
			
			$rs3 	= $conn->query("SELECT COUNT(value) AS items FROM `p2game".$cg."` WHERE `userid`='$userid'");						
			$rf 	= $rs3->fetch_assoc();
			$amount = $rf["items"];
			
			$chance = round(100*$sumvalue/$cb,1);
			
			$crs .= '
			<tr class="" style="text-align: left; vertical-align: middle;">
			<td><a href="http://steamcommunity.com/profiles/'.$userid.'"><img src="'.$avatar.'" width="30"></a>&emsp; <a href=""><font color="black"><a href="http://steamcommunity.com/profiles/'.$userid.'"><b>'.$username.'</b></a></font></a>&ensp; deposited <font color="#7A7A2A"><span class="label label-pill label-warning"><b>'.$amount.' skin(s)</b></span></font> valued <font color="#3D732A"><span class="label label-pill label-success"><b>$'.$sumvalue.'</b></font></span> Chances to win: <font color="#42879E"><span class="label label-pill label-info"><b>'.$chance.'%</b></span></font>
			&emsp;
		';
		
		$rs4 = $conn->query("SELECT * FROM `p2game".$cg."` WHERE `userid`='$userid' ORDER BY `value` DESC");
			while($row33 = $rs4->fetch_arary()) {
				$szinkod='#'.$row33["color"];
				$itemname=$row33["item"];
				$value=$row33['value'];
				$crs .='
						<span data-toggle="tooltip" data-placement="top" title="" data-tooltip="'.$itemname.' ($'.$value.')">
						<a href="https://steamcommunity.com/market/listings/730/'.$itemname.'" target="_BLANK"><img src="https://steamcommunity-a.akamaihd.net/economy/image/'.$row33["image"].'" width="35"></a>
						</span>
				
				';
			}
			$crs.='</td></tr>';
		}
		$crs.='	</tbody>
				</table>';
	}
	echo $crs;
?>