<?php
require('dbconnect.php');

function save_game($white_player, $black_player, $winning_player, $winner, $game_date, $game_url, $pgn) {

	$sql = <<<SQL
		INSERT INTO chess_games
			(white_player, black_player, winning_player, winner, game_date, game_url, pgn)
		VALUES
			(?, ?, ?, ?, ?, ?, ?)
SQL;

	$mysqli = get_connection();
	$stmt = $mysqli->prepare($sql);
	
	if ( !$stmt) {
		return array("success" => 0, "error" => $mysqli->error);
	}

	$stmt->bind_param("sssisss", $white_player, $black_player, $winning_player, $winner, $game_date, $game_url, $pgn);
	$success = $stmt->execute();

	$res;
	if ( $success ) {
		$res = array(
				"success" => 1,
				"data" => array(
					"id"	=> $stmt->insert_id,
					"white_player" => $white_player,
					"black_player" => $black_player,
					"winning_player" => $winning_player, 
					"winner" => $winner,
					"game_date"	=> $game_date,
					"game_url" => $game_url,
					"pgn"	=> $pgn
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

$white_player = $_POST['white_player'] ? $_POST['white_player'] : "";
$black_player = $_POST['black_player'] ? $_POST['black_player'] : "";
$winning_player = $_POST['winning_player'] ? $_POST['winning_player'] : "";
$winner = ($black_player == $winning_player) ? 1 : 0;
$game_date = $_POST['game_date'] ? $_POST['game_date'] : "";
$game_url = $_POST['game_url'] ? $_POST['game_url'] : "";
$pgn = $_POST['pgn'] ? $_POST['pgn'] : "";


$save_response = save_game($white_player, $black_player, $winning_player, $winner, $game_date, $game_url, $pgn);
$json = json_encode($save_response);

echo "<script>parent.processSave($json);</script>";
?>