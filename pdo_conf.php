<?php
	$db_host = 'localhost';
	$db_user = 'root';
	$db_pass = '';
	$db_name = 'imobis';
	$sql_conn = "mysql:host=$db_host;dbname=$db_name;";
	$dsn_Options = [];
	
	try { 
	  $pdo = new PDO($sql_conn, $db_user, $db_pass, $dsn_Options);
	  // echo "Connected successfully";
	} catch (PDOException $error) {
	  echo 'Connection error: ' . $error->getMessage();
	}
?>