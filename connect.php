<?php

	require_once('config.php');	

	function connect() {
		//First of all, we need to connect to the MySql server
			$mysqli = mysqli_init();
		//the connection will then be given to any function which retrieves information from the server
			$connection = $mysqli->real_connect($GLOBALS['host'], $GLOBALS['username'], $GLOBALS['password'], 'webbridge') 
			or die($GLOBALS['connection_error']);

			return $mysqli;
	}

?>