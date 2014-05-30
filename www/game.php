<?php require('dbconnect.php'); 

$id = $_GET["id"] or die('no id');

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Chess Game Player</title>
	<script type="text/javascript" src="http://chesstempo.com/js/pgnyui.js"></script>   
	<script type="text/javascript" src="http://chesstempo.com/js/pgnviewer.js"></script>  
	<link type="text/css" rel="stylesheet" href="http://chesstempo.com/css/board-min.css"></link>
	<style>
		
		#faux-move-border {
			border: 5px solid #262b3c;
			display: block;
			height: 293px;
			left: 601px;
			position: absolute;
			top: 285px;
			padding-left: 2px;
			width: 372px;
			z-index: 10;
		}
	
		.ct-board-move-comment {
			border: 5px solid #262b3c;
			display: block;
			overflow: auto;
			white-space: pre-wrap;
			height: 293px;
			left: 601px;
			position: absolute;
			top: 285px;
			padding-left: 2px;
			width: 372px;
			z-index: 15;
		}
		

		#toasty-container {
			float: left;
		}
		
		#toasty-moves {
			border: 5px solid #262b3c;
			height: 247px;
			overflow: auto;
			padding-left: 2px;
			width: 372px;
			margin-top: 5px;
		}
		
		#hideFirst {
			left: 225px;
			position: absolute;
			top: 597px;
		}
		
		#toasty-container .ct-black-square  
		{   
			background-color: #a49294;  
		}  
		
		#toasty-container .ct-white-square  
		{   
			background-image: linear-gradient(bottom, rgb(243,211,214) 17%, rgb(255,255,255) 80%);
			background-image: -o-linear-gradient(bottom, rgb(243,211,214) 17%, rgb(255,255,255) 80%);
			background-image: -moz-linear-gradient(bottom, rgb(243,211,214) 17%, rgb(255,255,255) 80%);
			background-image: -webkit-linear-gradient(bottom, rgb(243,211,214) 17%, rgb(255,255,255) 80%);
			background-image: -ms-linear-gradient(bottom, rgb(243,211,214) 17%, rgb(255,255,255) 80%);

			background-image: -webkit-gradient(
				linear,
				left bottom,
				left top,
				color-stop(0.17, rgb(243,211,214)),
				color-stop(0.8, rgb(255,255,255))
			);  
		}
		
		body {
			background-color: #bdbec2;
			font-family: Trebuchet MS, sans serif;
			padding: 0;
			margin: 0;
		}
		
		#banner {
			top: 0px;
			position: absolute;
			width: 100%;
			height: 30px;
			opacity: .15;
			background-color: #262b3c;
			z-index: 3;
			
		}
		
		#logo {
			top: 0px;
			position: absolute;
			width: 100%;
			height: 30px;
			color: white;
			font-size: 26px;
			top: -66px;
			z-index:9999;
		}
		
		#content {
			background-color: #FFFFFF;
			border-top: 66px solid #262b3c;
			border-left: 4px solid;
			border-right: 4px solid;
			border-bottom: 15px solid #262b3c;
			margin: 0 auto 30px auto;
			height: 610px;
			padding: 20px 1em 1em 1em;
			position: relative;
			width: 980px;
		}
		
		#additional-controls {
			position: absolute;
			bottom: 14px;
			left: 240px;
		}
		
		#toasty-boardBorder {
			border: 0; !important
		}
		
		#faux-border {
			position: absolute;
			top: 23px;
			left: 34px;
			height: 560px;
			width: 560px;
			border: 2px solid #363A3D;
		}
		
		#toasty-flipper {
			position: absolute;
			bottom: 18px;
			left: 224px;
		}
		
		#gamedataheader {
			position: absolute;
			color: white;
			font-weight: bold;
			left: 515px;
			top: -62px;
			z-index: 4;
		}
		
	</style>
	
	<script src="js/jquery.min.js"></script>
	<script>  

	var gameViewer = new PgnViewer({ 
		showCoordinates: true,
		boardName: "toasty",  
		pgnFile: 'pgn.id<?= $id ?>.php',  
		pieceSet: 'merida', // 'merida' (the default), 'leipzig', 'maya', 'condal', 'case' and 'kingdom'
		pieceSize: 70,
		boardImagePath: 'http://www.ghettofix.com/chess'
	});

	function showMoveComment() {
		var initialID = gameViewer.board.currentMove.obj_id;
		var lastMoveNum;
	
		setInterval( function(){
			var moveNum = gameViewer.board.currentMove.obj_id;
			if (lastMoveNum == moveNum) {
				return;
			}
			lastMoveNum = moveNum;
			$(".ct-board-move-comment").hide();
			
			if (moveNum == initialID){
				$(".ct-board-move-comment:first-child").show();
			} else {
				var id = parseInt(/([0-9]+)/.exec($('.ct-board-move-current')[0].id)[0]);
				$("#toasty-m" + id).nextUntil("#toasty-m" + (id + 1)).show();
			}
			
		}, 150);
	}
	
	gameViewer.finishedCallback = showMoveComment;
	
	function showhidegrid(chkbox) {
		if(chkbox.checked) {
			$('#toasty-fileLabels').children('span').each(function (i, file) {
				if (i) {
					file.style.visibility = 'visible';
				}
			});
			$('#toasty-rankLabels').css('visibility', 'visible');
		} else {
			$('#toasty-fileLabels').children('span').each(function (i, file) {
				if (i) {
					file.style.visibility = 'hidden';
				}
			});
			$('#toasty-rankLabels').css('visibility', 'hidden');
		}
	}
	
	
	function trySetConfig() {
		if (gameViewer.chessapp.pgn.pgnGames[0]) {
			setConfig();
			
			$('.ct-board-move-comment').each( function(i, elem){
				elem.innerHTML = $.trim(elem.innerHTML);
			});
			
			return;
		}
		setTimeout(trySetConfig, 200);
	}
	
	function setConfig(){
		var game = gameViewer.chessapp.pgn.pgnGames[0];
		$("#blackPlayer")[0].innerHTML = game.blackPlayer;
		$("#whitePlayer")[0].innerHTML = game.whitePlayer;
		$("#gameDate")[0].innerHTML = game.date;
		$("#gameResult")[0].innerHTML = game.pgn_result;
	}

	$(function() {
		trySetConfig();
	});
	
	</script>
</head>
<body>
<div id="content">
	<div id="logo"><img src="images/ghettobase.png" /></div>
	<div id="gamedataheader">
		(White) <span id="whitePlayer"></span> vs (Black) <span id="blackPlayer"></span><br />
		Date: <span id="gameDate"></span><br />
		Result: <span id="gameResult"></span><br />
	</div>
	<div id="faux-border"></div>
	<div id="faux-move-border"></div>
	<div id="toasty-container"></div> 
	<div id="toasty-moves"></div>
	<div id="additional-controls">
		<label for="toasty-flipper">Flip Board</label>
		<input onclick="showhidegrid(this)" type="checkbox" name="showgrid" id="showgrid" checked /><label for="showgrid">Show Grid<label>
	</div>
</div>
<div id="banner"></div>
</body>
</html>