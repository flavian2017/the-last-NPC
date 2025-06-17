<?php

$level = $_POST["level"];

$conn = new mysqli("localhost", "root", "", "webtech");

if($conn) {
} else {
    echo "failed";
    exit();
}

$sql = "SELECT g.playerId, p.rank, p.username, MIN(g.mapTime)/100 AS lowest_time
        FROM player p
        JOIN game g ON p.id = g.playerId
        WHERE g.mapId = $level
        GROUP BY g.playerId, p.rank
        ORDER BY lowest_time ASC";

$result = $conn->query($sql);

$data = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

$conn->close();

echo json_encode($data);
?>
