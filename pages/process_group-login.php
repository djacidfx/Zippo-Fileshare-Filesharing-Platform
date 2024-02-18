<?php
// Include the db.php file to establish the database connection
require_once 'hfo/db.php';

// Start a session
session_start();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $groupKey = $_POST['group-key'];
    $email = $_POST['email'];

    // Prepare and execute the query to get the group_id from the groups table based on the group_key
    $groupQuery = "SELECT group_id FROM groups WHERE group_key = ?";
    $stmt = $conn->prepare($groupQuery);
    $stmt->bind_param("s", $groupKey);
    $stmt->execute();
    $groupResult = $stmt->get_result();

    if ($groupResult->num_rows > 0) {
        // Group with the specified group_key exists

        // Retrieve the group_id from the result
        $group = $groupResult->fetch_assoc();
        $groupId = $group['group_id'];

        // Prepare and execute the query to get the user_id from the users table based on the email
        $userQuery = "SELECT user_id FROM users WHERE email = ?";
        $stmt = $conn->prepare($userQuery);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $userResult = $stmt->get_result();

        if ($userResult->num_rows > 0) {
            // User with the specified email exists

            // Retrieve the user_id from the result
            $user = $userResult->fetch_assoc();
            $userId = $user['user_id'];

            // Prepare and execute the query to insert the group_id and user_id into the group_memberships table
            $membershipQuery = "INSERT INTO group_memberships (group_id, user_id) VALUES (?, ?)";
            $stmt = $conn->prepare($membershipQuery);
            $stmt->bind_param("ii", $groupId, $userId);
            $stmt->execute();

            // Check if the query was successful
            if ($stmt->affected_rows > 0) {
                // Insert successful
                // Store the user_id and username in the session
                $_SESSION['user_id'] = $userId;

                // Redirect to the dashboard page
                echo "Success: Added Successfully!";
            } else {
                // Insert failed
                echo "Error: Failed to insert group membership.";
            }
        } else {
            // User not found
            echo "Error: User not found.";
        }
    } else {
        // Group not found
        echo "Error: Group not found.";
    }
} else {
    // Invalid request method
    echo "Error: Invalid request method.";
}
?>