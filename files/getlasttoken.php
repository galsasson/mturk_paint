<?php
	header("content-type: text/xml");

	require 'dbconfig.php';
	
	$con = mysql_connect(DB_HOST, DB_USER, DB_PASS);
	if (!$con) {
		echo "error: connection";
		return;
	}
	
	mysql_select_db(DB_NAME, $con);
	
	$result = mysql_query("SELECT * FROM tokens ORDER BY id DESC LIMIT 1;");
	if (!$result) {
		echo "error: select";
		return;
	}

	$numResults = mysql_num_rows($result);
	if ($numResults == 0) {
		echo "error";
		mysql_close($con);
		return;
	}

	$row = mysql_fetch_array($result);
	echo "<xml><token>".$row[token]."</token></xml>";
	
	mysql_close($con);
?>
