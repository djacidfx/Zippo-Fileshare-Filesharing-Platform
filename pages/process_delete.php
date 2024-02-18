<?php
session_start();

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    // Check if the fileId is provided in the query parameter
    if (isset($_GET['fileId'])) {
        $fileId = $_GET['fileId'];

        // Include the database connection file
        require_once 'hfo/db.php';

        // Retrieve the file information from the database
        $sql = "SELECT filename, filepath FROM files WHERE file_id = '$fileId'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $filename = $row['filename'];
            $filepath = $row['filepath'];

            // Delete the file from the file system
            if (unlink($filepath)) {
                // Delete the file record from the database
                $deleteSql = "DELETE FROM files WHERE file_id = '$fileId'";
                if ($conn->query($deleteSql) === TRUE) {
                    // Redirect back to the profile page with a success message
                    header('Location: profile.php?message=success');
                    exit();
                } else {
                    // Redirect back to the profile page with an error message
                    header('Location: profile.php?message=error');
                    exit();
                }
            } else {
                // Redirect back to the profile page with an error message
                header('Location: profile.php?message=error');
                exit();
            }
        } else {
            // Redirect back to the profile page with an error message
            header('Location: profile.php?message=error');
            exit();
        }
    } else {
        // Redirect back to the profile page if fileId is not provided
        header('Location: profile.php');
        exit();
    }
} else {
    // Redirect to the login page if the user is not logged in
    header('Location: login.php');
    exit();
}
?>