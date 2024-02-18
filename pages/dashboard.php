<?php include "hfo/all.php"?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Dashboard</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="hfo/all.css">
    <style>
        body {
            background-color: #404040 !important;
            color:#AFAFAF !important;
        }
        .center-align{
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .content {
            margin-top:50px;
            background-color: #232323;
            min-height:100vh;
            width:80%;
            padding: 5%;
            text-align: left;
        }

        .cn {
            max-width: 600px !important;
            margin-top: 50px !important;
        }

        .search-form {
            margin-bottom: 20px !important;
        }

        .user-list {
            list-style: none !important;
            padding: 0 !important;
        }

        .user-list li {
            margin-bottom: 10px !important;
        }

        .user-list strong {
            color: #F4E0B9 !important;
        }

        .user-list a {
            color: #AFAFAF !important;
        }

        .user-list a:hover {
            color: #AFAFAF !important;
        }

        .form-control {
            background-color: #E8F6EF !important;
            color: #4C4C6D !important;
        }

        .btn-primary {
            background-color: #1B9C85 !important;
            border-color: #1B9C85 !important;
            margin: 2%;
        }

        .btn-danger {
            margin: 2%;
        }

        .btn-primary:hover {
            background-color: #166A57 !important;
            border-color: #166A57 !important;
        }
    </style>
    <link rel="stylesheet" href="hfo/text.css">
</head>
<body>
    <!-- Loader-->
    <div class="loader"></div>
    <!-- Header -->
    <?php include "hfo/profile-header.php"?>

    <div class="center-align">
        <main class="content">
            <div class="container cn">
                <!-- User profiles -->
                <?php

                    // Include the database connection file
                    require_once 'hfo/db.php';

                    //Get user_id from session started in header
                    $user_id = $_SESSION['user_id'];

                    // Select information from the users table
                    $query = "SELECT * FROM users WHERE user_id = $user_id";
                    $result = $conn->query($query);
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $user_type = $row['user_type'];
                        $creator = $row['creator'];
                    }

                    // If the account is business, display "Group Profiles". If not, display "Users You Have Shared With"
                    if ($user_type === 'business') {
                        echo '<h4>Group Profiles</h4>';
                    } else {
                        echo '<h4>Users You Have Shared With</h4>';
                    }
                ?>
                <!-- User-list -->
                <ul class="user-list">
                    <?php
                    if ($user_type === 'business') {
                        // Retrieve the group_id from the group_memberships table
                        $groupQuery = "SELECT group_id FROM group_memberships WHERE user_id = $user_id";
                        $groupResult = $conn->query($groupQuery);
                        if ($groupResult->num_rows > 0) {
                            $group = $groupResult->fetch_assoc();
                            $group_id = $group['group_id'];

                            // Retrieve the user IDs of group members from the group_memberships table
                            $groupMembersQuery = "SELECT user_id FROM group_memberships WHERE group_id = $group_id";
                            $groupMembersResult = $conn->query($groupMembersQuery);

                            if ($groupMembersResult->num_rows > 0) {
                                while ($groupMember = $groupMembersResult->fetch_assoc()) {
                                    $memberUserId = $groupMember['user_id'];

                                    // Retrieve the username and email of the group member from the users table
                                    $groupMemberQuery = "SELECT username, email FROM users WHERE user_id = $memberUserId";
                                    $groupMemberResult = $conn->query($groupMemberQuery);

                                    if ($groupMemberResult->num_rows > 0) {
                                        $groupMemberData = $groupMemberResult->fetch_assoc();
                                        $groupMemberUsername = $groupMemberData['username'];
                                        $groupMemberEmail = $groupMemberData['email'];

                                        // Display group member profiles
                                        echo '<li>';
                                        echo '<strong>Email:</strong> <a href="public_profile.php?email=' . $groupMemberEmail . '">' . $groupMemberEmail . '</a>';

                                        if ($creator === 'Yes') {
                                            echo '<button class="btn btn-danger" onclick="banUser(' . $memberUserId . ',' . $group_id . ')">Ban</button>';
                                            echo '<button class="btn btn-primary" onclick="makeAdmin(' . $memberUserId . ')">Make Admin</button>';
                                        }

                                        echo '</li>';
                                    }
                                }
                            } else {
                                echo '<li>No group members found.</li>';
                            }
                        } else {
                            // Handle the case when the user is a business but not a member of any group
                            echo '<h4>No Group Memberships</h4>';
                            echo '<p>You are a business user but not a member of any group.</p>';
                        }
                    } else {
                        // Fetch user profiles from the database
                        if ($user_type === 'individual') {
                            // Retrieve user profiles instead of group names
                            $recipientQuery = "SELECT DISTINCT users.user_id, users.username, users.email 
                                            FROM file_share_recipients 
                                            INNER JOIN users ON file_share_recipients.recipient_id = users.user_id 
                                            WHERE file_share_recipients.share_id IN 
                                                (SELECT share_id FROM file_shares WHERE sender_id = '$user_id')";
                            $recipientResult = $conn->query($recipientQuery);
                        
                            if ($recipientResult->num_rows > 0) {
                                while ($recipientRow = $recipientResult->fetch_assoc()) {
                                    $username = $recipientRow['username'];
                                    $email = $recipientRow['email'];
                        
                                    // Display user profiles
                                    echo '<li>';
                                    echo '<strong>Username:</strong> <a href="public_profile.php?email=' . $email . '">' . $username . '</a><br>';
                                    echo '<strong>Email:</strong>  <a href="public_profile.php?email=' . $email . '">' . $email . '</a>';
                                    echo '</li>';
                                }
                            } else {
                                echo '<li>No shared users found.</li>';
                            }
                        }                
                    }
                    ?>
                </ul>
            </div>
        </main>
    </div>

    <!-- Footer -->
    <?php include "hfo/footer.php"?>
    <!-- Gets script from all.js -->
    <script src="hfo/all.js"></script>
    <script>
        function banUser(userId) {
            // Send an AJAX request to update the user_type to 'individual' for the given user ID
            $.ajax({
                type: 'POST',
                url: 'ban_user.php',
                data: { user_id: userId, group_id: groupId },
                success: function(response) {
                    // Reload the page or perform any other desired actions
                    location.reload();
                },
                error: function(xhr, status, error) {
                    // Handle the error case
                    console.log(xhr.responseText);
                }
            });
        }

        function makeAdmin(userId) {
            // Send an AJAX request to update the creator to 'Yes' for the given user ID
            $.ajax({
                type: 'POST',
                url: 'make_admin.php',
                data: { user_id: userId },
                success: function(response) {
                    // Reload the page or perform any other desired actions
                    location.reload();
                },
                error: function(xhr, status, error) {
                    // Handle the error case
                    console.log(xhr.responseText);
                }
            });
        }
        function requestToJoin(groupId) {
            // Send an AJAX request to handle the request to join the group
            $.ajax({
                type: 'POST',
                url: 'request_to_join.php',
                data: { group_id: groupId },
                success: function(response) {
                // Handle the success case
                console.log(response);
                // Display a success message
                alert('The Group Admins Will Get Back to You');
                // You can perform any other desired actions after a successful request
                },
                error: function(xhr, status, error) {
                // Handle the error case
                console.log(xhr.responseText);
                // Display an error message
                alert('Error: Failed to send the request to join the group.');
                // You can perform any other desired actions after an error
                }
            });
        }
    </script>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>