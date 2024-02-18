<?php
require_once 'hfo/db.php'; // Include database connection details

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the email address from the form submission
    $email = $_POST['email'];

    // Validate the email address (you can add more validation if needed)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email address.";
        exit;
    }

    // Check if the email exists in the database
    $query = "SELECT * FROM admins WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        echo "Email address not found.";
        exit;
    }

    // Generate a unique token for password reset
    $token = bin2hex(random_bytes(32));

    // Store the token and associated email address in a password_reset table
    $query = "INSERT INTO password_reset (email, token) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $email, $token);
    if (!$stmt->execute()) {
        echo "Error storing password reset token.";
        exit;
    }

    // Compose the email message
    $resetLink = "http://localhost/zippo/pages/reset_password.php?token=$token"; // Replace with your actual domain and reset password page
    $subject = "Password Reset";
    $message = "Click the link below to reset your password:\n\n$resetLink";

    // Send the email
    $headers = "From: Your Name <yourname@example.com>"; // Replace with your name and email address
    if (mail($email, $subject, $message, $headers)) {
        echo "Success: Password reset instructions have been sent to your email address.";
    } else {
        echo "Error sending password reset instructions.";
    }
}
?>
