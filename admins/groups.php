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
        
        .group {
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
        <h1>Groups</h1>

        <?php
        // Include database connection details and other required files
        require_once 'hfo/db.php'; // Include database connection details

        // Retrieve users from the database
        $query = "SELECT * FROM groups";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        $groups = $result->fetch_all(MYSQLI_ASSOC);

        // Display user profiles
        foreach ($groups as $group) {
            echo '<div class="group">';
            echo '<h3>'.$group['name'].'</button></h3>';
            echo '<p>Email: '.$group['group_key'].'</p>';
            echo '<button class="btn btn-danger" onclick="deletegroup('.$group['group_id'].')">Delete</button>';

            echo '</div>';
        }
        ?>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <!-- Custom JavaScript -->
    <script>
        // Function to delete a group
        function deletegroup(groupId) {
            // Perform AJAX request to delete user
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'delete_group.php');
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    alert('Group deleted successfully');
                    // Refresh the page or update the group list
                    location.reload();
                } else {
                    alert('Failed to delete group');
                }
            };
            xhr.send('groupId=' + groupId);
        }
    </script>
    <script src="hfo/all.js"></script>

</body>
</html>