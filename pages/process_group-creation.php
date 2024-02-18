<?php
// Include the db.php file to establish the database connection
require_once 'hfo/db.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $name = $_POST['name'];
    $email = $_POST['email'];

    // Generate a unique group key
    $groupKey = generateGroupKey();

    // Prepare and execute the query to insert the group into the groups table
    $groupQuery = "INSERT INTO groups (name, group_key) VALUES (?, ?)";
    $stmt = $conn->prepare($groupQuery);
    $stmt->bind_param("ss", $name, $groupKey);
    $stmt->execute();

    // Check if the query was successful
    if ($stmt->affected_rows > 0) {
        // Group inserted successfully

        // Get the group_id of the inserted group
        $groupId = $stmt->insert_id;

        // Prepare and execute the query to update the "creator" field in the users table
        $userQuery = "UPDATE users SET creator = 'Yes', group_id = ? WHERE email = ?";
        $stmt = $conn->prepare($userQuery);
        $stmt->bind_param("is", $groupId, $email);
        $stmt->execute();

        // Check if the query was successful
        if ($stmt->affected_rows > 0) {
            // Creator field updated successfully
            echo "Success: Group Created Successfully";
        } else {
            // Update failed
            echo "Error: Failed to update the 'creator' field.";
        }
    } else {
        // Insert failed
        echo "Error: Failed to insert the group.";
    }
} else {
    // Invalid request method
    echo "Error: Invalid request method.";
}

// Function to generate a unique group key
function generateGroupKey($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}
?>