<?php
session_start();
// Check if the user_id is not in the session
if (!isset($_SESSION['user_id'])) {
    // Redirect to the signup page
    header('Location: signup.php');
    exit(); // Stop further execution
}

// Include the database connection file
require_once 'hfo/db.php';

$user_id = $_SESSION['user_id'];

$query = "SELECT * FROM users WHERE user_id = $user_id";
$result = $conn->query($query);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $user_type = $row['user_type'];
    $creator = $row['creator'];
}

if ($user_type === 'individual') {
    // Check if the group_id is provided in the request
    if (isset($_POST['group_id'])) {
        $group_id = $_POST['group_id'];

        // Retrieve the group creator's email from the users table
        $creatorQuery = "SELECT email FROM users WHERE group_id = $group_id AND creator = 'Yes'";
        $creatorResult = $conn->query($creatorQuery);
        
        if ($creatorResult->num_rows > 0) {
            $creatorData = $creatorResult->fetch_assoc();
            $creatorEmail = $creatorData['email'];

            // Retrieve the user's email from the session data
            $userEmail = $row['email'];

            // Compose the email message
            $subject = 'Request to Join Group';
            $message = "Hello,\n\nYou have received a request to join your group. The user's email is: $userEmail\n\nPlease take appropriate action.\n\nBest regards,\nZippo Fileshare";
            
            if (mail($creatorEmail, $subject, $message)) {
                // Email sent successfully
                echo 'Email sent successfully.';
            } else {
                // Error sending email
                echo 'Failed to send the email.';
            }
        } else {
            // No group creator found
            echo 'No group creator found.';
        }
    } else {
        // Invalid request, group_id not provided
        echo 'Invalid request.';
    }
} else {
    // Invalid user type
    echo 'Invalid user type.';
}

// Close the database connection
$conn->close();
?>