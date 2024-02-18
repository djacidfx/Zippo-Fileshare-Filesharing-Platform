<?php
// Include the db.php file to establish the database connection
require_once 'hfo/db.php';

// Start a session
session_start();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $usernameEmail = $_POST['username-email'];
    $password = $_POST['password'];

    // Check if the username or email exists in the database
    $query = "SELECT * FROM admins WHERE username = '$usernameEmail' OR email = '$usernameEmail'";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        // User found, verify the password
        $row = $result->fetch_assoc();
        $hashedPassword = $row['password'];
        if (password_verify($password, $hashedPassword)) {
            // Password is correct, set up session variables
            $_SESSION['admin_id'] = $row['admin_id'];
            $_SESSION['username'] = $row['username'];

            // Send a success message to the client
            echo "Success: Logged in successfully.";
        } else {
            // Incorrect password
            echo "Error: Invalid password.";
        }
    } else {
        // User not found
        echo "Error: User not found.";
    }
} else {
    // Invalid request method
    echo "Error: Invalid request method.";
}
