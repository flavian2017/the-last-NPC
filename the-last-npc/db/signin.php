<?php
session_start();

$username = $_POST["username"];
$password = $_POST["password"];

$conn = mysqli_connect('localhost', 'root', '', 'webtech');
if (!$conn) {
    die("Error in connection: " . mysqli_connect_error());
}

$q1 = "SELECT id, username, points, rank, password FROM player WHERE username = '$username'";
$result = mysqli_query($conn, $q1);


if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    if ($password === $row['password']) {
        $_SESSION['id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['rank'] = $row['rank'];
        $_SESSION['points'] = $row['points'];
        header("Location:../views/game.php"); 
        exit;
    } else {
        echo "Invalid password.";
    }
} else {
    echo "No user found with this email.";
}

mysqli_close($conn);
?>