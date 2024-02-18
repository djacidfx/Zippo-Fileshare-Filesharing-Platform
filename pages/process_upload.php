<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page or display an error message
    header('Location: login.php');
    exit();
}

// Include the database connection file
require_once 'hfo/db.php';

// Get the user_id from the session
$user_id = $_SESSION['user_id'];

// Check if a file was uploaded successfully
if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['file'];

    // Retrieve file information
    $filename = $file['name'];
    $tmpFilePath = $file['tmp_name'];

    // Generate a unique filename to avoid conflicts
    $uniqueFilename = uniqid() . '_' . $filename;

    // Specify the directory to which the file will be uploaded
    $uploadDirectory = 'uploads/';

    // Create the full filepath
    $filePath = $uploadDirectory . $uniqueFilename;

    // Move the uploaded file to the destination directory
    if (move_uploaded_file($tmpFilePath, $filePath)) {
        // Store the file information in the database
        $sql = "INSERT INTO files (user_id, filename, filepath) VALUES ('$user_id', '$filename', '$filePath')";
        if ($conn->query($sql) === TRUE) {
            // File upload and database insertion successful
            header('Location: profile.php');
            exit();
        } else {
            // Error inserting file information into the database
            echo 'Error: ' . $sql . '<br>' . $conn->error;
        }
    } else {
        // Error moving the uploaded file to the destination directory
        echo 'Error uploading the file.';
    }
} else {
    // No file uploaded or an error occurred during upload
    echo 'Error uploading the file.';
}

// Close the database connection
$conn->close();
?>
