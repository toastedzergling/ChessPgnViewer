<?php 
session_start();

// TODO make configurable
if($_POST['pass'] && md5($_POST['pass']) == '6524fcc18d678fc6dedae2faa92a6581'){
	$_SESSION['auth'] = true;
}

if ( $_SESSION['auth'] != true ) {

echo <<<FFF

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Chess Game Listing Portal</title>
</head>
<body>
<form method="post">
Secret Password: <input name="pass" type="password" /><input type="submit" value="I think I remember what it is...">
</form>
</body>
</html>

FFF;

	  exit;
}

if ($_SESSION['auth'] == true) {

	function get_connection() {

		// TODO change these params pull from a config file;
		$db_server = '';
		$db_user = '';
		$db_pass = '';
		$db_name = '';
		$mysqli = new mysqli($db_server, $db_user, $db_pass, $db_name);
	
		if ($mysqli->connect_errno) {
			echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
			exit;
		}
	
		return $mysqli;
	}

	
	function doSelect($sql){
		
		$mysqli = get_connection();
		
		$results = $mysqli->query($sql) or die($mysqli->error);
		while( $row = $results->fetch_assoc() ){
			$result[] = $row;
		}
		return $result;
	}
	
	
	// TODO return dynamically once there actually are more players
	function getPlayers() {
		return array(
			'ToastedZergy',
			'Sevets',
			'Toxicology',
			'Computer',
			'Doug',
			'Tessa',
			'George',
			'HTML-Inject this entry',
		);
	}

}
?>