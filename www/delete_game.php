<?php
require('dbconnect.php');

function delete_game($game_id) {

	$sql = <<<SQL
		DELETE FROM chess_games WHERE ID = ?
SQL;

	$mysqli = get_connection();
	$stmt = $mysqli->prepare($sql);
	if ( !$stmt) {
		return array("success" => 0, "error" => "prepare failed");
	}
	
	
	$stmt->bind_param("i", $game_id);
	$success = $stmt->execute();

	$res;
	if ( $success ) {
		$res = array(
				"success" => 1,
				"data" => array(
					"id"	=> $game_id,
				),
			);
	} else {
		$res = array(
			"success" => 0,
			"error" => "Something went wrong with the save",
		);
	}
	return $res;
}

$game_id = $_GET['id'] ? $_GET['id'] : -1;

echo json_encode(delete_game($game_id));

?>