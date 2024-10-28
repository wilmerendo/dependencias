<?php
$servername = "localhost";
$username = "Wilmer";
$password = "123456";
$dbname = "dependencia";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}