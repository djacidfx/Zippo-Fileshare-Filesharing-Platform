<?php
//start session
session_start();
// Check if the user_id is not in the session
if (!isset($_SESSION['user_id'])) {
    // Redirect to the signup page
    header('Location: signup.php');
    exit(); // Stop further execution
}

$user_id = $_SESSION['user_id'];

// Include the database connection file
require_once 'db.php';
?>