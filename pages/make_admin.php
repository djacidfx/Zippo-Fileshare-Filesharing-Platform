<?php
    // Include the database connection file
    require_once 'hfo/db.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the user ID from the AJAX request
        $user_id = $_POST['user_id'];

        // Update the creator to 'Yes' for the specified user ID
        $sql = "UPDATE users SET creator = 'Yes' WHERE user_id = $user_id";
        if ($conn->query($sql) === TRUE) {
            echo 'User has been made admin successfully.';
        } else {
            echo 'Error: ' . $conn->error;
        }

        // Close the database connection
        $conn->close();
    }
?>
