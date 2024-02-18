<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_group'])) {
        // Delete the group and the user account from the database
        require_once "hfo/db.php";

        $group_id = $_POST['group_id'];
        $user_id = $_POST['user_id'];

        // Delete the group
        $groupQuery = "DELETE FROM groups WHERE group_id = ?";
        $stmt = $conn->prepare($groupQuery);
        $stmt->bind_param("i", $group_id);
        $stmt->execute();

        // Delete the user account
        $userQuery = "DELETE FROM users WHERE user_id = ?";
        $stmt = $conn->prepare($userQuery);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        // Check if the deletion was successful
        if ($stmt->affected_rows > 0) {
            // Group and user account deleted successfully, redirect to a signup page
            header("Location: signup.php");
            exit;
        } else {
            // Failed to delete the group or user account
            echo "Failed to delete the group or user account.";
        }
    }
}
?>
