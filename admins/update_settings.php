<?php
// Include necessary files and start session
require_once 'hfo/db.php'; // File containing database connection details
session_start();

// Check if user is logged in, otherwise redirect to login page
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Retrieve form data
$user_id = $_SESSION['admin_id'];
$username = $_POST['username'];
$password = $_POST['password'];
$email = $_POST['email'];

// Validate and sanitize input (you may need to add more validation and sanitization based on your requirements)
$username = filter_var($username, FILTER_SANITIZE_STRING);
$email = filter_var($email, FILTER_SANITIZE_EMAIL);

// Update user's settings in the database
$query = "UPDATE admins SET username = ?, email = ? WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('ssi', $username, $email, $user_id);

if ($stmt->execute()) {
    // Update successful
    echo "Settings updated successfully.";
} else {
    // Error occurred
    echo "Error updating settings.";
}
?>