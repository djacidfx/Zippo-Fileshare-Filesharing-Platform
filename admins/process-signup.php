<?php
// Include the db.php file to establish the database connection
require_once 'hfo/db.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];

    // Validate form data
    if ($password !== $confirmPassword) {
        // Passwords do not match
        echo "Error: Passwords do not match.";
        exit;
    }

    // Check if the username or email already exists in the database
    $checkQuery = "SELECT * FROM admins WHERE username = '$username' OR email = '$email'";
    $checkResult = $conn->query($checkQuery);
    if ($checkResult->num_rows > 0) {
        // Username or email already exists
        echo "Error: Username or email already exists.";
        exit;
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert the new user into the database
    $insertQuery = "INSERT INTO admins (username, email, password) VALUES ('$username', '$email', '$hashedPassword')";
    if ($conn->query($insertQuery) === TRUE) {
        // User successfully registered
        echo "Success: User registered successfully.";
    } else {
        // Error occurred while inserting the user
        echo "Error: " . $conn->error;
    }
} else {
    // Invalid request method
    echo "Error: Invalid request method.";
}
