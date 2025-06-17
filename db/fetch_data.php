<?php

$conn = new mysqli("localhost", "root", "", "webtech");

if($conn) {
} else {
    echo "failed";
    exit();
}

$sql = "SELECT id, username, points, rank FROM player ORDER BY points DESC";
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
