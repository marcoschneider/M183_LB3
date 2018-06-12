<?php
	$host = "localhost";
	$user = "root";
	$pass = "toor";
	$dbname = "k72021_sco_uebungen";
	
	$conn = mysqli_connect($host, $user, $pass, $dbname);
	
	if (!$conn) {
		mysqli_error($conn);
	}
	
	mysqli_set_charset($conn, "utf8");
?>