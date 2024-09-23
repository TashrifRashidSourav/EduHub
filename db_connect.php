<?php
$servername = "localhost";
$username = "root";  // Change this if you have a different username
$password = "";      // Change this if your MySQL server has a password
$dbname = "EduHub";  // Name of the database

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
