<?php
	header("content-type: text/xml");

	require 'dbconfig.php';
	
	$con = mysql_connect(DB_HOST, DB_USER, DB_PASS);
	if (!$con) {
		echo "error: connection";
		return;
	}
	
	mysql_select_db(DB_NAME, $con);
	
	$result = mysql_query("SELECT * FROM paintings ORDER BY id;");
	if (!$result) {
		echo "error: select";
		return;
	}

	$numResults = mysql_num_rows($result);
	
	echo "<xml>";
	
	// return the first image
	echo "<paint><stroke>".
		 "<point x='100' y='100' frame='10'></point>".
		 "<point x='300' y='100' frame='10'></point>".
		 "<point x='300' y='300' frame='10'></point>".
		 "<point x='100' y='300' frame='10'></point>".
		 "<point x='100' y='100' frame='10'></point>".
		 "</stroke></paint>";

	while ($row = mysql_fetch_array($result))
	{
		echo $row[data];
	}

	echo "</xml>";

	mysql_close($con);		
?>
