<?php
session_start();
// Check if the user_id is not in the session
if (!isset($_SESSION['admin_id'])) {
    // Redirect to the signup page
    header('Location: login.php');
    exit(); // Stop further execution
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="hfo/all.css">
    <style>
        body {
            background-color: #AFAFAF;
        }
        
        .container {
            margin-top: 50px;
            background-color: #FFFFFF;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        h1 {
            color: #232323;
            margin-bottom: 20px;
        }
        
        .user-profile {
            margin-bottom: 20px;
            padding: 20px;
            border: 1px solid #232323;
            border-radius: 5px;
        }
        
        .btn {
            margin-right: 10px;
        }
        
        .email-all-btn {
            margin-top: 10px;
        }
        
        .file-list {
            margin-top: 10px;
        }
        
        .file-item {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
        }
        
        .file-name {
            flex-grow: 1;
        }
        
        .delete-file-btn {
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <div class="loader"></div>
    <?php include "hfo/admin_header.php"?>

    <div class="container">
        <h1>Users</h1>

        <?php
        // Include database connection details and other required files
        require_once 'hfo/db.php'; // Include database connection details

        // Retrieve users from the database
        $query = "SELECT * FROM users";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        $users = $result->fetch_all(MYSQLI_ASSOC);

        // Display user profiles
        foreach ($users as $user) {
            echo '<div class="user-profile">';
            echo '<h3><button class="btn btn-link" data-toggle="modal" data-target="#fileModal'.$user['user_id'].'">'.$user['username'].'</button></h3>';
            echo '<p>Email: '.$user['email'].'</p>';
            echo '<button class="btn btn-danger" onclick="deleteUser('.$user['user_id'].')">Delete</button>';
            echo '<button class="btn btn-primary" onclick="emailUser('.$user['user_id'].')">Email</button>';

            // Retrieve files uploaded by the user
            $query = "SELECT * FROM files WHERE user_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('i', $user['user_id']);
            $stmt->execute();
            $result = $stmt->get_result();
            $files = $result->fetch_all(MYSQLI_ASSOC);

            if (!empty($files)) {
                echo '<div class="modal fade" id="fileModal'.$user['user_id'].'" tabindex="-1" role="dialog" aria-labelledby="fileModalLabel'.$user['user_id'].'" aria-hidden="true">';
                echo '<div class="modal-dialog" role="document">';
                echo '<div class="modal-content">';
                echo '<div class="modal-header">';
                echo '<h5 class="modal-title" id="fileModalLabel'.$user['user_id'].'">Files: '.$user['username'].'</h5>';
                echo '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
                echo '<span aria-hidden="true">&times;</span>';
                echo '</button>';
                echo '</div>';
                echo '<div class="modal-body">';
                echo '<ul>';
                foreach ($files as $file) {
                    echo '<li>'.$file['filename'].'<button class="btn btn-danger delete-file-btn" onclick="deleteFile('.$file['file_id'].')">Delete</button></li>';
                }
                echo '</ul>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }

            echo '</div>';
        }
        ?>

        <button class="btn btn-success email-all-btn" onclick="emailAllUsers()">Email All</button>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="emailModal" tabindex="-1" role="dialog" aria-labelledby="emailModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="emailModalLabel">Send Email</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <textarea id="emailMessage" class="form-control" rows="5" placeholder="Enter your message"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="sendEmail()">Send</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <!-- Custom JavaScript -->
    <script>
        // Function to delete a user
        function deleteUser(userId) {
            // Perform AJAX request to delete user
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'delete_user.php');
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    console.log('User deleted successfully');
                    // Refresh the page or update the user list
                } else {
                    console.log('Failed to delete user');
                }
            };
            xhr.send('userId=' + userId);
        }

        // Function to delete a file
        function deleteFile(fileId) {
            // Perform AJAX request to delete file
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'delete_file.php');
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    console.log('File deleted successfully');
                    // Refresh the page or update the file list
                } else {
                    console.log('Failed to delete file');
                }
            };
            xhr.send('fileId=' + fileId);
        }

        // Function to email a user
        function emailUser(userId) {
            // Open the email modal
            $('#emailModal').modal('show');

            // Store the user ID in a hidden input field inside the modal
            $('#userId').val(userId);
        }

        // Function to email all users
        function emailAllUsers() {
            // Open the email modal
            $('#emailModal').modal('show');

            // Clear the user ID in the hidden input field inside the modal
            $('#userId').val('');
        }

        // Function to send the email
        function sendEmail() {
            // Retrieve the user ID and message from the modal
            var userId = $('#userId').val();
            var message = $('#emailMessage').val();

            // Perform AJAX request to send the email
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'send_email.php');
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    console.log('Email sent successfully');
                } else {
                    console.log('Failed to send email');
                }
            };
            xhr.send('userId=' + userId + '&message=' + encodeURIComponent(message));

            // Close the email modal
            $('#emailModal').modal('hide');
        }

    </script>
    <script src="hfo/all.js"></script>

</body>
</html>