<?php

	header("content-type: text/xml");

	$user_id = $_POST["user_id"];
	$task_id = $_POST["task_id"];
	$username = $_POST["username"];
	$data = $_POST["data"];
	$saveToken = $_POST["token"];

	require 'dbconfig.php';

	$con = mysql_connect(DB_HOST, DB_USER, DB_PASS);
	if (!$con) {
		echo "error: connection";
		return;
	}

	mysql_select_db(DB_NAME, $con);

	// generate next verification string
	$next_verify = substr(md5(rand()), 0, 7);

	$result = mysql_query(
		"INSERT INTO paintings (user_id,task_id,username,data,next_verification,token) VALUES ('".
			mysql_real_escape_string($user_id).
			"','".
			mysql_real_escape_string($task_id).
			"','".
			mysql_real_escape_string($username).
			"','".
			mysql_real_escape_string($data).
			"','".
			mysql_real_escape_string($next_verify).
			"','".
			mysql_real_escape_string($saveToken).
			"');");
	if (!$result) {
		echo "error: insert";
		mysql_close($con);
		return;
	}

	// now we need to create a new HIT
	//
	// create a new token and write it to the database
	$token = md5($data);
	$result = mysql_query("INSERT INTO tokens (token) VALUES ('".$token."');");
	if (!$result) {
		echo "error: insert token";
		mysql_close($con);
		return;
	}

	// allow up to 100 tasks
	$result = mysql_query("SELECT COUNT(*) FROM tokens;");
	if (!$result) {
		echo "error: count tokens";
		mysql_close($con);		
		return;
	}
	$numTokens = mysql_result($result, 0);

	// allow up to 100 tasks
	if ($numTokens < 100) {
		// execute make new task script
		exec("/var/www/maketask/makenew.sh ".$token." &");
	}
	
	echo "ok";

	mysql_close($con);		
?>

