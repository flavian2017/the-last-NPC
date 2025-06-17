<?php

$gameId = uniqid();
$playerId = $_POST["id"];
$mapId = $_POST["mapId"];
$mapTime = $_POST["time"];
$pts = $_POST["pts"];
$startTime = date("H:i:s");
$currentDate = date('Y-m-d');


$conn = new mysqli("localhost", "root", "", "webtech");

if($conn) {
} else {
    echo "failed";
    exit();
}

$sql = "INSERT INTO game (gameId, points, startTime, date, mapId, playerId, mapTime) values ('$gameId', $pts, '$startTime', '$currentDate', $mapId, '$playerId', $mapTime)";
$result = $conn->query($sql);

if($result){
    echo "<br>data inserted successfully";
}
else{
    echo "data insertion failed";
}

if($pts >=320){
    $rank = 'Grandmaster';
}
else if($pts >=190){
    $rank = 'elite';
}
else if($pts >=100){
    $rank = 'brown belt';
}

$sql1 = "UPDATE player SET points = ?, rank = ? WHERE id = ?";
$stmt = $conn->prepare($sql1);
$stmt->bind_param("isi", $pts, $rank, $playerId);

if ($stmt->execute()) {
    echo "Points updated successfully.";
} else {
    echo "Error updating points: " . $stmt->error;
}

$conn->close();
?>
