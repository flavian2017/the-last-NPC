<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<title>The last NPC</title>
	<link href="../styles/styles.css" rel="stylesheet"/>

	<script>
		window.onload = function() {	
			document.getElementById('username').innerHTML = "username : <?php echo $_SESSION['username']; ?>";
			document.getElementById('id').innerHTML = "<?php echo $_SESSION['id']; ?>";
			document.getElementById('rank').innerHTML = "rank : <?php echo $_SESSION['rank']; ?>";
			document.getElementById('score').innerHTML = "<?php echo $_SESSION['points']; ?>";

			fetch('../db/fetch_data.php')
				.then(response => response.json())
				.then(data => {
					displayData(data);
				})
				.catch(error => console.error('Error:', error));

			function displayData(data) {
				const tableBody = document.getElementById('table-body');
				data.forEach(item => {
					const row = document.createElement('tr');
					row.innerHTML = `
						<td>${item.id}</td>
						<td>${item.username}</td>
						<td>${item.points}</td>
						<td>${item.rank}</td>
					`;
					tableBody.appendChild(row);
				});
			}

			var formData = new FormData();
			formData.append('level', 1);
			var url = '../db/fetch_map_data.php';
			fetch(url, { method: 'POST', body: formData })    
			.then(response => response.json())
			.then(data => {
				displayMapTableData(data);
			})
			.catch(error => console.error('Error:', error));

			function displayMapTableData(data) {
			const tableBody = document.getElementById('table1-body');
			tableBody.innerHTML = '';
			data.forEach(item => {
				const row = document.createElement('tr');
				row.innerHTML = `
					<td>${item.playerId}</td>
					<td>${item.username}</td>
					<td>${item.lowest_time}</td>
					<td>${item.rank}</td>
				`;
				tableBody.appendChild(row);
			});
			}
		};
	  </script>
</head>
<body>

	<div id="profile">
		<div id="profile-card" class="side-container">
			<span>id : </span><span id="id"></span>
			<p id="username"></p>
			<p id="rank"></p>
			<span>points : </span><span id="score"></span>
			<br>
			<br>
			<br>
			<p>
				*please do not refresh the page after starting the game
			</p>
		</div>

		<div id="profile-card"  class="side-container game-card">
			<div class="select-char-container">
				<img  class="select-char" src="characters/character-profile/npc_char.png"/>
				<img  class="select-char" src="characters/character-profile/simplePC_char.png"/>
				<img  class="select-char" src="characters/character-profile/hawks_eye_char.png"/>
				<img  class="select-char" src="characters/character-profile/armorPC_char.png"/>
				<img  class="select-char" src="characters/character-profile/snapy.png"/>
			</div>
			<span>selected character : </span><span id='charName'>creeper</span><p id='charDesc'>this bot escaped a world where there were only cubes.</p>
			<span>
				<p id= "levelTag">LEVEL 1</p>
				<div id="game-bar">
					<div id="timer">
						<p id= "second">00 </p>
						<p> : </p>
						<p id= "millisecond">00 </p>
					</div>
				</div>
			</span>
			<button id="button-start">START</button>
		</div>
	</div>

	<div id="map">
		<canvas id="gameCanvas"></canvas>
	</div>

	<div id="leaderboard" class="side-container">


		
		<div class="Table">
			<table border="1">
				<tr>
					<th colspan="4">OVERALL LEADERBOARD</th>
				</tr>
				<tr>
					<th>id</th>					
					<th>username</th>
					<th>Score</th>
					<th>rank</th>
				</tr>
				<tbody id="table-body">
					
				</tbody>
			</table>
	</div>
	<br/>
	<br/>

	<div class="Table">
			<table border="1">
				<tr>
					<th colspan="4">MAP LEADERBOARD</th>
				</tr>
				<tr>
					<th>id</th>					
					<th>username</th>
					<th>time(s)</th>
					<th>rank</th>
				</tr>
				<tbody id="table1-body">
					
				</tbody>
			</table>
	</div>

	<br><br><br>
	<a href='../index.html'>go home</a>


    <script src="../js/index.js"></script>
</body>

</html>