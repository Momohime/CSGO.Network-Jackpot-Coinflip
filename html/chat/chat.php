<?php
	$file = "chat/chat.txt";
	$f = file($file);
	$lines = count(file($file));
	
        include_once(dirname(__FILE__) . '/../link.php');
	$admin = fetchinfo("admin","users","steamid",$_SESSION["steamid"]);
	for($i=$lines-25; $i!=$lines; $i++){
		if($i>=0){
			echo "<div style='border-bottom: dotted 1px #DCDCDC; display:flex'>".$f[$i]."</div>";
		}
	}
?>