<?php
	/*
		*	This file handles all the AJAX-requests from Javascript
	*/

	//requires
	require_once('lang.php');
	require_once('classes.php');

	//Now get the data sent from Javascript
	$object = "";
	$method = "";
	$param  = ""; //a playername, a playerIp or a worldname

	//check if a correct object was defined

	if(!isset($_GET['object'])) die($GLOBALS['no_object']);
	$object = $_GET['object'];
	if(empty($object) || (!("server" == $object) && 
		!("world" == $object) && !("player" == $object) && !("jobs" == $object))) 
		die($GLOBALS['incorrect_object']);

	//check if a correct method was defined
	if(!isset($_GET['method'])) die($GLOBALS['no_method']);
	if(empty($_GET['method'])) die($GLOBALS['no_method']);
	$method = $_GET['method'];
		//to send a notification that the method is defined wrongly, simply put a message at the end of the code
			//--> because: when there was a successful execution of a method, the script would have been exited


	//Now, make the different requests based on the method specified

		/* Server-Object methods */

	if("server" == $object) {
		//PARAMS: object=server & method=[method]
		$server = new Server();

		switch($method) {
			case "getMaxPlayerNum": 
				die($server -> getMaxPlayerNum());
			break;

			case "getPlayerOnline":
				die($server -> getPlayerOnline());
			break;

			case "getMotd":
				die($server -> getMotd());
			break;

			case "getName":
				die($server -> getName());
			break;

			case "getIp":
				die($server -> getIp());
			break;

			case "getPlayerList":
				die($server -> getPlayerList());
			break;

			case "getWorldList":
				die($server -> getWorldList());
			break;

			case "getPlayerOnlineList":
				die($server -> getPlayerOnlineList());
			break;
		}

	} 

	if("player" == $object) {
		//PARAMS: object=player & method=[method] & param=[playerName/IP]
		if(!isset($_GET['param']) || empty($_GET['param'])) die($GLOBALS['no_player_ip']);
		//now we are sure, that a (also maybe incorrect) player-ip/name/world-name was specified
		$param  = $_GET['param'];

		$player = new Player($param);

		switch($method) {
			case "getName":
				die($player -> getName());
			break;

			case "getIp":
				die($player -> getIp());
			break;

			case "isOnline":
				die($player -> isOnline()); 
			break;

			case "getTimesJoined":
				die($player -> getTimesJoined());
			break;
			case "getKills":
				die($player -> getKills());
			break;

			case "getDeaths":
				die($player -> getDeaths());
			break;

			case "getHealth":
				die($player -> getHealth());
			break;

			case "getWorld":
				die($player -> getWorld());
			break;

			case "getHelmet":
				die($player -> getHelmet());
			break;

			case "getChestplate": 
				die($player -> getChestplate());
			break;

			case "getLeggins":
				die($player -> getLeggins());
			break;

			case "getBoots":
				die($player -> getBoots());
			break;
		}

	}


	if("world" == $object) {
		//PARAMS: object=world & method=[method] & param=[worldName]
		if(!isset($_GET['param']) || empty($_GET['param'])) die($GLOBALS['no_world_name']);
		//now we are sure, that a (also maybe incorrect) world-name was specified
		$param  = $_GET['param'];
		$world = new World($param);
		switch($method) {
			case "getType":
				die($world -> getType());
			break;

			case "getSeed":
				die($world -> getSeed());
			break;
		}
	}

	if("jobs" == $object) { 
	//PARAMS: object=jobs & method=[method] & pw = [jobSystem-password] & param=[parameter]
		//pw parameter has to be set
		if(!isset($_GET['pw'])) die($GLOBALS['no_job_pw']);
		$pw = $_GET['pw'];
		if(empty($pw) || $pw == NULL) die($GLOBALS['no_job_pw']);
		//Now we are sure that there has been specified a password
		$jobs = new Jobs($pw);

		//methods where no PARAMS are required

		switch($method) {
			case "reloadServer":
				die($jobs -> reloadServer());
			break;

			case "stopServer":
				die($jobs -> stopServer());
			break;

			case "saveall":
				die($jobs -> saveall());
			break;

			case "saveoff":
				die($jobs -> saveoff());
			break;

			case "saveon":
				die($jobs -> saveon());
			break;
		}

		//the following methods need certain data in order to be executed
		if(!isset($_GET['param']) || empty($_GET['param'])) die($GLOBALS['parameter_missing']);
		$param = $_GET['param'];

		switch($method) {
			case "ban":
				die($jobs -> ban($param));
			break;

			case "banip":
				die($jobs -> banip($param));
			break;

			case "clear":
				die($jobs -> clear($param));
			break;

			case "deop":
				die($jobs -> deop($param));
			break;

			case "difficulty":
				die($jobs -> difficulty($param));
			break;

			case "defaultgamemode":
				die($jobs -> defaultgamemode($param));
			break;

			case "kick":
				die($jobs -> kick($param));
			break;

			case "op":
				die($jobs -> op($param));
			break;

			case "pardon":
				die($jobs -> pardon($param));
			break;

			case "pardonip":
				die($jobs -> pardonip($param));
			break;

			case "say":
				die($jobs -> say($param));
			break;

			case "setidletimeout":
				die($jobs -> setidletimeout($param));
			break;

			case "setworldspawn":
				die($jobs -> setworldspawn($param));
			break;

			case "tell":
				die($jobs -> tell($param));
			break;
		}


	}

	//END OF FILE --> No method matched, return error
	echo($GLOBALS['incorrect_method']);

?>