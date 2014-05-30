<?php 
require('dbconnect.php'); 

$id = $_GET['id'];
// TODO this is begging for SQL injection; change to prepared statements
$res = doSelect("SELECT pgn FROM chess_games WHERE ID = $id");
$pgn = $res[0] or die('couldn\'t find the game');
echo trim(stripslashes($pgn["pgn"]));
?>