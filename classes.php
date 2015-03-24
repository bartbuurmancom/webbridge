<?php
	/*
		*	In this file, all the objects are defined.
		*	They later on return information to Javascript through Ajax
		*	You could see this as the core of WebBridge
	*/

	require_once('lang.php');
	require_once('connect.php');
	require_once('config.php'); //Needed for the jobPassword

	/*
		*	SPECIAL FUNCTIONS ;)

	*/

	function selectQuery($query, $mysqli) {
		$rows = array();
		$result = $mysqli -> query($query);
		if(!$result) return false;

		while($row = $result -> fetch_assoc()) $rows[] = $row;
		return $rows;
	}


	/*
		*	[== The Server Object ==]
	*/


	class Server {
		private $mysqli;

		function __construct() {
			$this->mysqli = connect();
		}

		public function getPlayerOnline() {
			$result = selectQuery('SELECT playerOnline FROM serverInfo;', $this->mysqli);
			if(!$result) return $GLOBALS['query_error'];
			if(!isset($result) || empty($result)) return $GLOBALS['getting_data_error'];
			return $result[0]['playerOnline'];
		}

		public function getIp() {
			$result = selectQuery('SELECT ip FROM serverInfo;', $this->mysqli);
			if(!$result) return $GLOBALS['query_error'];
			if(!isset($result) || empty($result)) return $GLOBALS['getting_data_error'];
			return $result[0]['ip'];
		}

		public function getName() {
			$result = selectQuery('SELECT name FROM serverInfo;', $this->mysqli);
			if(!$result) return $GLOBALS['query_error'];
			if(!isset($result) || empty($result)) return $GLOBALS['getting_data_error'];
			return $result[0]['name'];
		}

		public function getMaxPlayerNum() {
			$result = selectQuery('SELECT maxPlayer FROM serverinfo;', $this->mysqli);
			if(!$result) return $GLOBALS['query_error'];
			if(!isset($result[0]['maxPlayer']) || empty($result[0]['maxPlayer'])) return $GLOBALS['getting_data_error'];
			return $result[0]['maxPlayer'];
		}

		public function getPlayerList() {
			$result = selectQuery('SELECT ip FROM playerInfo;', $this->mysqli);
			$list = "";
			if(!$result) return $GLOBALS['query_error'];
			foreach($result as $currPlayer) {
				$list = $list . $currPlayer['ip'] . '|';
			}
			if(empty($list)) return $GLOBALS['getting_data_error'];
			return $list;
		}

		public function getWorldList() {
			$result = selectQuery('SELECT name FROM worlds;', $this->mysqli);
			$list = "";
			if(!$result) return $GLOBALS['query_error'];
			foreach($result as $currWorld) {
				$list = $list . $currWorld['name'] . '|';
			}
			if(empty($list)) return $GLOBALS['getting_data_error'];
			return $list;
		}

		public function getPlayerOnlineList() {
			$result = selectQuery('SELECT ip FROM playerInfo WHERE isOnline = true;', $this->mysqli);
			$list = "";
			if(!$result) return "";
			foreach($result as $currPlayer) {
				$list = $list . $currPlayer['ip'] . '|';
			}
			if(empty($list)) return $GLOBALS['getting_data_error'];
			return $list;
		}

	}

	/*
		*	[== The Player Object ==]
	*/


	class Player {
		private $mysqli;
		private $identifier; //can be name or IP

		function __construct($identifier) {
			$this->mysqli     = connect();
			$this->identifier = $this->mysqli->real_escape_string($identifier);
		}

		//returning "-1" if the player specified does not exist

		//ATTENTION: All methods except from getIp() use the IP-adress as the identifier

		public function getName() {
			$result = selectQuery('SELECT name FROM playerInfo WHERE ip = "' . $this->identifier . '";', $this->mysqli);
			if(!$result) return "-1";
			return $result[0]['name'];
		}

		public function getIp() {
			$result = selectQuery('SELECT ip FROM playerInfo WHERE name = "' . $this->identifier . '";', $this->mysqli);
			if(!$result) return "-1";
			return $result[0]['ip'];
		}

		public function isOnline() {
			$result = selectQuery('SELECT isOnline FROM playerInfo WHERE ip = "' . $this->identifier . '";', $this->mysqli);
			if(!$result) return "-1";
			return $result[0]['isOnline'];
		}

		public function getTimesJoined() {
			$result = selectQuery('SELECT timesJoined FROM playerInfo WHERE ip = "' . $this->identifier . '";', $this->mysqli);
			if(!$result) return "-1";
			return $result[0]['timesJoined'];
		}

		public function getKills() {
			$result = selectQuery('SELECT kills FROM playerInfo WHERE ip = "' . $this->identifier . '";', $this->mysqli);
			if(!$result) return "-1";
			return $result[0]['kills'];
		}

		public function getDeaths() {
			$result = selectQuery('SELECT deaths FROM playerInfo WHERE ip = "' . $this->identifier . '";', $this->mysqli);
			if(!$result) return "-1";
			return $result[0]['deaths'];
		}

		public function getHealth() {
			$result = selectQuery('SELECT health FROM playerInfo WHERE ip = "' . $this->identifier . '";', $this->mysqli);
			if(!$result) return "-1";
			return $result[0]['health'];
		}

		public function getWorld() {
			$result = selectQuery('SELECT world_id FROM playerInfo WHERE ip = "' . $this->identifier . '";', $this->mysqli);
			if(!$result) return "-1";
			$worldName = selectQuery('
					SELECT
						w.name
					FROM
						playerInfo p
					INNER JOIN
						worlds w ON (p.world_id = w.id);
				', $this->mysqli);

			return $worldName[0]['name'];
		}

		public function getHelmet() {
			$result = selectQuery('SELECT helmet FROM playerInfo WHERE ip = "' . $this->identifier . '";', $this->mysqli);
			if(!$result) return "-1";
			return $result[0]['helmet'];
		}

		public function getChestplate() {
			$result = selectQuery('SELECT chestplate FROM playerInfo WHERE ip = "' . $this->identifier . '";', $this->mysqli);
			if(!$result) return "-1";
			return $result[0]['chestplate'];
		}

		public function getLeggins() {
			$result = selectQuery('SELECT leggins FROM playerInfo WHERE ip = "' . $this->identifier . '";', $this->mysqli);
			if(!$result) return "-1";
			return $result[0]['leggins'];
		}

		public function getBoots() {
			$result = selectQuery('SELECT boots FROM playerInfo WHERE ip = "' . $this->identifier . '";', $this->mysqli);
			if(!$result) return "-1";
			return $result[0]['boots'];
		}

	}

	/*
		*	[== The World Object ==]
	*/


	class World {
		private $mysqli;
		private $name;

		function __construct($name) {
			$this->mysqli = connect();
			$this->name  = $this->mysqli->real_escape_string($name); 
		}

		function getType() {
			$result = selectQuery('SELECT type FROM worlds WHERE name = "' . $this->name . '";', $this->mysqli);
			if(!$result) return "-1";
			return $result[0]['type'];
		}

		function getSeed() {
			$result = selectQuery('SELECT seed FROM worlds WHERE name = "' . $this->name . '";', $this->mysqli);
			if(!$result) return "-1";
			return $result[0]['seed'];
		}

	}

	/*
		*	[== The Jobs Object ==]
	*/

	class Jobs { 
		private $mysqli;
		private $password;

		function __construct($pw = NULL) {
			$this->mysqli = connect();
			$this->password = $pw;
		}

		//In the contruct method, everything was set properly. Now we have the password!

		function checkPassword() {
			//When the pw is NULL, someone directly created a Jobs-Object in PHP, which is legitim

			if($this->password == NULL) $this->password = $GLOBALS['jobPassword'];
			else {
				if($this->password != $GLOBALS['jobPassword']) return "-1";
			}

			//Now check if the password given is equal to the one in the db
			$result = selectQuery('SELECT password FROM serverInfo;', $this->mysqli);
			if(!$result) return "-1";
			if($result[0]['password'] != $this->password) return "-1";
		}

		function reloadServer() {
			if($this->checkPassword() == "-1") return "-1";
			$this->mysqli->query("INSERT INTO jobs (job) VALUES ('reload');");
			return "1"; //1 == Job was sent
		}

		function stopServer() {
			if($this->checkPassword() == "-1") return "-1";
			$this->mysqli->query("INSERT INTO jobs (job) VALUES ('stop');");
			return "1"; //1 == Job was sent
		}

		function ban($pName) {
			if($this->checkPassword() == "-1") return "-1";
			$this->mysqli->query('INSERT INTO jobs (job) VALUES ("ban '. $pName .'");');
			return "1"; //1 == Job was sent
		}

		function banip($pName) {
			if($this->checkPassword() == "-1") return "-1";
			$this->mysqli->query('INSERT INTO jobs (job) VALUES ("ban-ip '. $pName .'");');
			return "1"; //1 == Job was sent
		}

		function clear($pName) {
			if($this->checkPassword() == "-1") return "-1";
			$this->mysqli->query('INSERT INTO jobs (job) VALUES ("clear '. $pName .'");');
			return "1"; //1 == Job was sent
		}

		function deop($pName) {
			if($this->checkPassword() == "-1") return "-1";
			$this->mysqli->query('INSERT INTO jobs (job) VALUES ("deop '. $pName .'");');
			return "1"; //1 == Job was sent
		}

		function difficulty($diff) {
			if($this->checkPassword() == "-1") return "-1";
			$this->mysqli->query('INSERT INTO jobs (job) VALUES ("difficulty '. $diff .'");');
			return "1"; //1 == Job was sent
		}

		function defaultgamemode($gm) {
			if($this->checkPassword() == "-1") return "-1";
			$this->mysqli->query('INSERT INTO jobs (job) VALUES ("defaultgamemode '. $gm .'");');
			return "1"; //1 == Job was sent
		}

		function kick($pName) {
			if($this->checkPassword() == "-1") return "-1";
			$this->mysqli->query('INSERT INTO jobs (job) VALUES ("kick '. $pName .'");');
			return "1"; //1 == Job was sent
		}

		function op($pName) {
			if($this->checkPassword() == "-1") return "-1";
			$this->mysqli->query('INSERT INTO jobs (job) VALUES ("op '. $pName .'");');
			return "1"; //1 == Job was sent
		}


		function pardon($pName) {
			if($this->checkPassword() == "-1") return "-1";
			$this->mysqli->query('INSERT INTO jobs (job) VALUES ("pardon '. $pName .'");');
			return "1"; //1 == Job was sent
		}

		function pardonip($adress) {
			if($this->checkPassword() == "-1") return "-1";
			$this->mysqli->query('INSERT INTO jobs (job) VALUES ("pardon-ip '. $adress .'");');
			return "1"; //1 == Job was sent
		}

		function saveall() {
			if($this->checkPassword() == "-1") return "-1";
			$this->mysqli->query("INSERT INTO jobs (job) VALUES ('save-all');");
			return "1"; //1 == Job was sent
		}

		function saveoff() {
			if($this->checkPassword() == "-1") return "-1";
			$this->mysqli->query("INSERT INTO jobs (job) VALUES ('save-off');");
			return "1"; //1 == Job was sent
		}

		function saveon() {
			if($this->checkPassword() == "-1") return "-1";
			$this->mysqli->query("INSERT INTO jobs (job) VALUES ('save-on');");
			return "1"; //1 == Job was sent
		}

		function say($msg) {
			if($this->checkPassword() == "-1") return "-1";
			$this->mysqli->query('INSERT INTO jobs (job) VALUES ("say '. $msg .'");');
			return "1"; //1 == Job was sent
		}

		function setidletimeout($time) {
			if($this->checkPassword() == "-1") return "-1";
			$this->mysqli->query('INSERT INTO jobs (job) VALUES ("setidletimeout '. $time .'");');
			return "1"; //1 == Job was sent
		}

		function setworldspawn($coords) {
			if($this->checkPassword() == "-1") return "-1";
			$coords = explode("|", $coords);
			if(isset($coords[0]) && isset($coords[1]) && isset($coords[2])) //ALL coords set?
				$this->mysqli->query('INSERT INTO jobs (job) 
					VALUES ("setworldspawn '. $coords[0] . ' ' . $coords[1] . ' ' . $coords[2] .'");');
			else
				return "-1";
			return "1"; //1 == Job was sent
		}

		function tell($msg) {
			if($this->checkPassword() == "-1") return "-1";
			$splitten = explode("|", $msg);
			if(isset($splitten[0]) && isset($splitten[1]))
				$this->mysqli->query('INSERT INTO jobs (job) 
					VALUES ("tell '. $splitten[0] . ' ' . $splitten[1] .'");');
			else
				return "-1";
			return "1"; //1 == Job was sent
		}

	}

?>