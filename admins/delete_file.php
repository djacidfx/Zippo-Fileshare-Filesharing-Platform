<?php
// Include database connection details and other required files
require_once 'hfo/db.php'; // Include database connection details

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the file ID from the POST data
    $fileId = $_POST['fileId'];

    // Retrieve the file information from the database
    $query = "SELECT * FROM files WHERE file_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $fileId);
    $stmt->execute();
    $result = $stmt->get_result();
    $file = $result->fetch_assoc();

    if ($file) {
        // Delete the file from the file system
        $filePath = 'http://locahost/zippo/pages/uploads/' . $file['file_name'];
        if (unlink($filePath)) {
            // File deleted successfully from the file system, now delete from the database
            $deleteQuery = "DELETE FROM files WHERE file_id = ?";
            $deleteStmt = $conn->prepare($deleteQuery);
            $deleteStmt->bind_param('i', $fileId);
            if ($deleteStmt->execute()) {
                // File deleted successfully from the database
                echo "File deleted successfully.";
            } else {
                // Failed to delete the file from the database
                echo "Failed to delete the file from the database.";
            }
        } else {
            // Failed to delete the file from the file system
            echo "Failed to delete the file from the file system.";
        }
    } else {
        // File not found in the database
        echo "File not found.";
    }
}
?>
