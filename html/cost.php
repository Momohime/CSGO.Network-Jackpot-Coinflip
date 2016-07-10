<?php
	include ('link.php');

	$item 		= $_GET['item'];
	if(isset($_GET['db'])) {
		$debugmode 	= $_GET['db'];
	} else {
		$debugmode = 0;
	}

	$hitem 		= str_replace("\"", "", $item);
	$hitem 		= str_replace("\'", "", $hitem);
	$hitem 		= str_replace(" ", "%20", $hitem);
	$hitem 		= str_replace("\\", "", $hitem);
	$mhitem 	= $conn->real_escape_string($hitem);

	$query = $conn->query("SELECT * FROM `items` WHERE `name`='$mhitem'");
	$updateinterval = 604800; // 7 days in seconds
	$time 			= time();
	$fetch 			= 0;
	$insert 		= 0;

	if($query->num_rows == 0) {
		if($debugmode==1) {
			echo '<br> [DEBUG] Skin not found in the database';
		}

		$fetch=1;
		$insert=1;
	} else {
		$row  		= $query->fetch_array();	
		$price 		= $row['cost'];
		$lastupdate = $row['lastupdate'];
		$updatedate = $lastupdate+$updateinterval;

		if($time > $updatedate) {
			if($debugmode==1) {
				echo'<br> [DEBUG] The skin cost needs to be updated, performing price check & update';
			}

			$fetch=1;
		} else {
			if($debugmode == 1) {
				echo'<br> [DEBUG] Fetching price from the Database: ';
			}

			if($price) {
				echo $price;
				$fetch = 0;
			} else {
				$fetch = 1;
			}
		}
	}

	if($fetch==1) {
		if($debugmode==1) {
			echo '<br> [DEBUG] Fetching price from Steam: ';
		}

		$link 	= "https://api.csgofast.com/price/all";
		$string = file_get_contents($link);
		$obj 	= json_decode($string);

		$hitem = str_replace("%20", " ", $hitem);

		if(property_exists($obj, $hitem)) {
			$lowest_price = $obj->$hitem;
		} else {
			$lowest_price = 0;
		}
		
		$hitem = str_replace(" ", "%20", $hitem);

		echo $lowest_price = str_replace("$", "", $lowest_price);

		if($insert==1) {
			if($debugmode==1) {
				echo '<br> [DEBUG] Inserting new item into database';
			}

			$conn->query("INSERT INTO `items` (`name`,`cost`,`lastupdate`) VALUES ('$mhitem','$lowest_price','$time')");	
		} else {
			if($price!=$lowest_price && $lowest_price && $lowest_price!=0) {
				if($debugmode==1) {
					echo '<br> [DEBUG] Updating database: cost, lastupdate';
				}

				$conn->query("UPDATE items SET `cost`='$lowest_price' WHERE `name`='$mhitem'");
				$conn->query("UPDATE items SET `lastupdate`='$time' WHERE `name`='$mhitem'");
			} else {
				if($lowest_price && $lowest_price!=0) {
					if($debugmode==1) {
						echo '<br> [DEBUG] The price has not changed, updating `lastupdate` only';
					}

					$conn->query("UPDATE items SET `lastupdate`='$time' WHERE `name`='$mhitem'");
				}
			}
		}
	}
?>