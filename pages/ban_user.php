<?php
// Include the database connection file
require_once 'hfo/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the user ID from the AJAX request
    $user_id = $_POST['user_id'];
    $group_id = $_POST['group_id'];

    // Update the user_type to 'individual' and group_id to 0 for the specified user ID
    $updateUserQuery = "UPDATE users SET user_type = 'individual', group_id = 0 WHERE user_id = $user_id";
    if ($conn->query($updateUserQuery) === TRUE) {
        // Delete the group membership from the group_memberships table
        $deleteMembershipQuery = "DELETE FROM group_memberships WHERE user_id = $user_id AND group_id = $group_id";
        if ($conn->query($deleteMembershipQuery) === TRUE) {
            echo 'User has been banned successfully.';
        } else {
            echo 'Error: ' . $conn->error;
        }
    } else {
        echo 'Error: ' . $conn->error;
    }

    // Close the database connection
    $conn->close();
}
?>
