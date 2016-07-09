<?php
	$conn = new mysqli("localhost", "root", ""); // MySQL Host , Username and password

	/* check connection */
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}

	/* change db */
	$conn->select_db("github-csgonetwork"); 	// Database Name

	/* change character set to utf8 */
	if (!$conn->set_charset("utf8")) {
		printf("Error loading character set utf8: %s\n", $mysqli->error);
		exit();
	}
?>

<?php
	function fetchinfo($rowname,$tablename,$finder,$findervalue) {
		global $conn;

		if($finder == "1") {
			$result = $conn->query("SELECT $rowname FROM $tablename");
		} else {
			$result = $conn->query("SELECT $rowname FROM $tablename WHERE `$finder`='$findervalue'");
		}
		$row = $result->fetch_assoc();
		return $row[$rowname];
	}

	function secureoutput($string) {
		global $conn;

		$string = $conn->real_escape_string($string);
		$string = htmlentities(strip_tags($string));
		$string = str_replace('>', '', $string);
		$string = str_replace('<', '', $string);
		$string = htmlspecialchars($string);
		return $string;
	}
?>