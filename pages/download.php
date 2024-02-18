<?php
    // Include the necessary file for database connection
    require_once 'hfo/db.php';

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['file_id'])) {
        // Get the file ID from the query parameters
        $file_id = $_GET['file_id'];

        // Retrieve the file details from the database
        $fileQuery = "SELECT filename, filepath FROM files WHERE file_id = '$file_id'";
        $fileResult = $conn->query($fileQuery);

        if ($fileResult->num_rows > 0) {
            $fileRow = $fileResult->fetch_assoc();
            $filename = $fileRow['filename'];
            $filepath = $fileRow['filepath'];

            // Check if the file exists
            if (file_exists($filepath)) {
                // Set the appropriate headers for file download
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="' . $filename . '"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($filepath));
                readfile($filepath);
                exit;
            } else {
                echo 'File not found.';
            }
        } else {
            echo 'Invalid file ID.';
        }
    } else {
        echo 'Invalid request.';
    }

    // Close the database connection
    $conn->close();
?>