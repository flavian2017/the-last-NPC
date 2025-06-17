<?php

    $username = $_POST["username"];    
    $password = $_POST["password"];    
    $email = $_POST["email"]; 
    $gender = isset($_POST['gender']) ? $_POST['gender'] : null;
    $dob = isset($_POST['birthdate']) ? $_POST['birthdate'] : null;
    $theme = isset($_POST['theme']) ? $_POST['theme'] : '#000000';
    $uniqueID = uniqid();
    $currentDate = date('Y-m-d');

    $conn = mysqli_connect("localhost", "root", "", "webtech");
    
    if($conn) {
        echo "<br>Connection is successful";
    } else {
        echo "Connection failed";
        exit();
    }
    
    $q1= "INSERT INTO player (id, username, email, password, gender, dob, doj, theme, rank, points) values ('$uniqueID', '$username', '$email', '$password', '$gender', '$dob','$currentDate', '$theme', 'noob', 0)";
    $r1= mysqli_query($conn, $q1);
    
    if($r1){
        echo "<br>data inserted successfully";
    }
    else{
        echo "data insertion failed";
    }

     $selectQuery = "SELECT * FROM player";
     $result = mysqli_query($conn, $selectQuery);
 
     if (mysqli_num_rows($result) > 0) {
         echo "<h3>Players List:</h3>";
         echo "<table border='1'><tr><th>Username</th><th>Email</th><th>Gender</th><th>DOB</th><th>Theme</th><th>Phone No</th></tr>";
         while ($row = mysqli_fetch_assoc($result)) {
             echo "<tr><td>{$row['username']}</td><td>{$row['email']}</td><td>{$row['gender']}</td><td>{$row['dob']}</td><td>{$row['doj']}</td><td>{$row['theme']}</td></tr>";
         }
         echo "</table>";
     } else {
         echo "No data found.";
     }
    
    
    mysqli_close($conn);

?>