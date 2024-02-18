<?php
// Establish a database connection
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'zippo';
$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>