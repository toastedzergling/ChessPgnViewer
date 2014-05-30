<?php
require('dbconnect.php');
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Chess Game Listing Portal</title>
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.0/themes/base/jquery-ui.css" />
	<style>
		html {
			height: 100%;
			font-family: Tahoma, Geneva, sans-serif;
		}
		body {
			margin: 0;
			padding: 0;
			height: 100%;
		}
		body {
			height:100%;
			background-color: #101010;
		}
		h1 {
			padding: 0;
			margin: .5em 0 .5em 0;
		}
		#container {
			border-left: 1px solid #999999;
			border-right: 1px solid #999999;
			width: 1024px;
			background-color: #f0f0f0;
			min-height: 100%;
			margin: auto;
			padding: 0 20px 0 20px;
			text-align
		}
		table {
			border-collapse: collapse;
			margin-bottom: 2em;
			border: 2px solid black;
		}
		td { 
			border: 1px solid black;
			padding: 1em;
			margin: 0;
			width: 140px;
		}
		.light {
			background: white;
		}
		th {
			border-bottom: 2px solid black;
		}
		.dark {
			background-image: linear-gradient(bottom, rgb(148,145,148) 11%, rgb(201,201,201) 56%);
			background-image: -o-linear-gradient(bottom, rgb(148,145,148) 11%, rgb(201,201,201) 56%);
			background-image: -moz-linear-gradient(bottom, rgb(148,145,148) 11%, rgb(201,201,201) 56%);
			background-image: -webkit-linear-gradient(bottom, rgb(148,145,148) 11%, rgb(201,201,201) 56%);
			background-image: -ms-linear-gradient(bottom, rgb(148,145,148) 11%, rgb(201,201,201) 56%);

			background-image: -webkit-gradient(
				linear,
				left bottom,
				left top,
				color-stop(0.11, rgb(148,145,148)),
				color-stop(0.56, rgb(201,201,201))
			);
		}
		#game_list tr:hover .light, #game_list  tr:hover .dark{
			background-color: #dddddd;
			background-image: none;
		}
		.winner {
			float: right;
			color: green;
		}
	</style>
	<script src="js/jquery.min.js"></script>
	<script src="http://code.jquery.com/ui/1.10.0/jquery-ui.js"></script>
	<script>
		$(document).ready(function(){ 
			update_winners();
			$("#game_date").datepicker();
			$("#game_date").datepicker("option", "dateFormat", "yy-mm-dd");
		}); 
		
		var showedOnce = 0;
		function doDelete(id) {
			var check = confirm("Press OK to DELETE game ID " + id);
			if ( check ) {
				$.ajax({
				  url: "delete_game.php",
				  data: {
					"id": id
				  },
				  dataType: "json"
				}).done(function(result) {	
					if (result.success) {
						$("#row" + result.data.id).fadeOut("slow"); 
					} 
					else if (result.error) {
						alert("Result Error Message\n" + result.error);
					} else {
						alert('unknown save error');
					}
				});
			}
		}
		function update_winners() {
			if ( $('#white_player')[0].options.selectedIndex != -1
				&& $('#black_player')[0].options.selectedIndex != -1
			){
				$('#winning_player')[0].innerHTML = "<option>"+$('#white_player').val()+"</option><option>"+$('#black_player').val()+"</option>";
			} else {
				$('#winning_player')[0].disabled = true;
				$('#save')[0].disabled = true;
			}
		}
		
		function processSave(result) {
			if (result.success) {
				addGameRow(result.data);
			} 
			else if (result.error) {
				alert("Result Error Message\n" + result.error);
			} else {
				alert('unknown save error');
			}
		}
		
		function playerRow(name, color_bit, who_won_bit) {
			rowhtml = name;
			if ( color_bit == who_won_bit ) {
				rowhtml += "<span class='winner' title='Winner'>&#x2713;</span>";
			}
			return rowhtml;
		}
		
		function openGameWindow(id) {
			window.open('game.php?id='+id, "game"+id);
		}
				
		function addGameRow(game) {
			var htmlStr = '' 
				+ '<tr id="row'+ game.id + '">'
				+	'<td>' + game.id + '</td>'
				+	'<td>' + playerRow(game.white_player, 0, game.winner) + '</td>'
				+	'<td>' + playerRow(game.black_player, 1, game.winner) + '</td>'
				+	'<td>' + game.game_date + '</td>'
				+	'<td><a href="' + game.game_url + '">Replay Link</a></td>'
				+	'<td>'
				+		'<input type="button" value="show pgn" onclick="document.getElementById(\'pgn"' + game.id + '\').style.display = document.getElementById(\'pgn"' + game.id + '\').style.display == \'none\' ? \'\' : \'none\';">'
				+		'<textarea style="display:none;" id="pgn' + game.id + '">' + game.pgn + '</textarea>'
				+		'<input type="button" value="launch game" onclick=openGameWindow(' + game.id + ') />'
				+	'</td>'
				+	'<td>'
				+		'<input type="button" value="Delete" onclick="doDelete(' + game.id + ')" />'
				+	'</td>'
				+ '</tr>';
			
			$('#game_list > tbody:last').append(htmlStr).hide().fadeIn();
			addChecker();
		}
		
		function addChecker() {
			$('#game_list tbody td').each(function(idx, node) {
				node.className = (node.cellIndex % 2) ^ (node.parentElement.rowIndex % 2) ? 'light' : 'dark';
			});
		}
		
	</script>
</head>
<body>
<div id="container">
<h1>Games!</h1>
<table id="game_list">
	<thead>
		<tr>
			<th>ID</th>
			<th>White</th>
			<th>Black</th>
			<th>Game Date</th>
			<th>Game URL</th>
			<th>PGN</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
<?php

function playerSelect($htmlname) {
	$html = "<select id='$htmlname' name='$htmlname' onchange='update_winners()'>";
	$players = getPlayers();
	
	for($i=0, $len=sizeof($players); $i<$len; $i++){
		$html .= "<option value=\"" . $players[$i] . "\">" . $players[$i] . "</option>";
	}
	$html .= "</select>";
	return $html;
}

function printCellStyle ($whiteFirstRow, $col) {
	if (
		($whiteFirstRow && $col % 2 == 0)
		|| (!$whiteFirstRow && $col % 2 == 1)
	) {
		return "class='light'";
	}
	return "class='dark'";
}

function playerRow($name, $color_bit, $who_won_bit) {
	$rowhtml = $name;
	if ( $color_bit == $who_won_bit ) {
		$rowhtml .= "<span class='winner' title='Winner' whatever='$color_bit $who_won_bit'>&#x2713;</span>";
	}
	return $rowhtml;
}

$sql = <<<SQL

SELECT 
	id, 
	white_player, 
	black_player, 
	winning_player, 
	winner, 
	game_date, 
	game_url, 
	pgn, 
	submit_date 
FROM
	chess_games
ORDER BY
	id

SQL;

$games = doSelect($sql);

for($i=0, $len = sizeof($games); $i < $len; $i++) {
	$col = 0;
	$whiteFirstRow = $i % 2 == 0;
	$game = $games[$i];
?>
		<tr id="row<?= $game["id"] ?>">
			<td <?= printCellStyle($whiteFirstRow, $col++) ?>><?php print $game["id"] ?></td>
			<td <?= printCellStyle($whiteFirstRow, $col++) ?>><?php print playerRow($game["white_player"], 0, $game['winner']) ?></td>
			<td <?= printCellStyle($whiteFirstRow, $col++) ?>><?php print playerRow($game["black_player"], 1, $game['winner']) ?></td>
			<td <?= printCellStyle($whiteFirstRow, $col++) ?>><?php print $game["game_date"] ?></td>
			<td <?= printCellStyle($whiteFirstRow, $col++) ?>><a href="<?php print $game["game_url"] ?>">Replay Link</a></td>
			<td <?= printCellStyle($whiteFirstRow, $col++) ?>>
				<input type="button" value="show pgn" onclick="document.getElementById('pgn<?php print $game["id"]; ?>').style.display = document.getElementById('pgn<?php print $game["id"] ?>').style.display == 'none' ? '' : 'none';">
				<textarea style="display:none;" id="pgn<?php print $game['id'] ?>"><?= stripslashes($game["pgn"]) ?></textarea>
				<input type="button" value="launch game" onclick=openGameWindow(<?= $game["id"] ?>) />
			</td>
			<td <?= printCellStyle($whiteFirstRow, $col++) ?>>
				<input type="button" value="Delete" onclick="doDelete(<?= $game["id"] ?>)" />
			</td>
		</tr>
<?php 
} // game loop 

?>
	</tbody>
</table>

<h1>Add a game</h1>
<form target="save_game" action="save_game.php" method="post">
<table>
	<tr>
		<td class="light">
			<label for="white_player">White Player:</label>
		</td>
		<td class="dark">
			<?= playerSelect('white_player') ?>
		</td>
	</tr>
	<tr>
		<td class="dark">
			<label for="black_player">Black Player: </label>
		</td>
		<td class="light">
			<?= playerSelect('black_player') ?>
		</td>
	</tr>
	<tr>
		<td class="light">
			<label for="winning_player">Winning Player:  </label>
		</td>
		<td class="dark">
			<select id="winning_player" name="winning_player"></select>
		</td>
	</tr>
	<tr>
		<td class="dark">
			 <label for="game_date">Game Date: </label>
		</td>
		<td class="light">
			<input type="text" id="game_date" name="game_date" value="TBD" />
		</td>
	</tr>
	<tr>
		<td class="light">
			 <label for="game_url">Analysis URL</label>
		</td>
		<td class="dark">
			<input type="text" name="game_url" />
		</td>
	</tr>
	<tr>
		<td colspan="2">
			 <label for="pgn">Analysis Notation</label>
			 <textarea cols="80" rows="10" name="pgn"></textarea>
		</td>
	</tr>
		<td class="dark">
			<input type="submit" value="Save Game" />
			<iframe src="empty.html" id="save_game" name="save_game" style="height:501px; width:501px; display: none;"></iframe>
		</td>
		<td class="light">
		
		</td>
	</tr>
</table>
</form>
<br />

<h1>PGN Parser (coming soon...</h1>
<form target="parse_game" method="POST" action="empty.html">
<a href="http://search.cpan.org/~gmax/Chess-PGN-Parse-0.19/Parse.pm">Perl Module to Investigate</a>
</form>
<br />

<h1>Stats (coming soon...)</h1>
<table>
	<thead>
		<tr>
			<th>Player</th>
			<th>Games Played</th>
			<th>Overall Record</th>
			<th>White Record</th>
			<th>Black Record</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>Toasted</td>
			<td>67</td>
			<td>12-57-2 (16.2%)</td>
			<td>6-28-1 (16.0%)</td>
			<td>6-27-1 (16.4%)</td>
		</tr>
	</tbody>
</table>

<h1>TODO List</h1>
<ul>
	<li>Add White PGN / Black PGN / Analysis PGN fields</li>
	<li>PGN Merge</li>
	<li>Updating</li>
	<li>Make Deletes Safer</li>
	<li>Add Version Control</li>
	<li>Make Stats Work</li>
	<li>Table Sorter</li>
	<li>Table Search / Filter</li>
	<li>Clean up JS / CSS</li>
	<li>Clean up PHP converted to JS</li>
	<li><strike>DatePicker Input</strike></li>
	<li><strike>Saving</strike></li>
	<li><strike>Deleting</strike></li>
	<li><strike>Replacing Chess.com's Game Viewer <a href="http://chesstempo.com/pgn-viewer.html">with this guy's</a></strike></li>
</ul>
<br />

</div>
</body>
</html>