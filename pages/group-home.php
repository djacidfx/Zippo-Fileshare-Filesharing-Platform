<?php include "hfo/all.php"?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Group Home</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="hfo/all.css">
    <link rel="stylesheet" href="hfo/text.css">
    <style>
        body {
            background-color: #404040 !important;
            color: #AFAFAF !important;
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

        .btn-primary {
            background-color: #404040 !important;
            border-color: #404040 !important;
            margin: 2%;
        }

        .btn-danger {
            margin: 2%;
        }

        .btn-primary:hover {
            background-color: #232323 !important;
            border-color: #232323 !important;
        }
    </style>
</head>
<body>
    <!-- Loader -->
    <div class="loader"></div>
    <!-- Header -->
    <?php include "hfo/profile-header.php"?>

    <div class="container cn">
        <!-- User profiles -->
         <?php
            // Include the database connection file
            require_once 'hfo/db.php';

            //Get group_id from url
            $group_id = $_GET['group_id'];

            //Get user_id from session starte in the header
            $user_id = $_SESSION['user_id'];

            // Select information from the users table
            $query = "SELECT * FROM users WHERE user_id = $user_id";
            $result = $conn->query($query);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $user_type = $row['user_type'];
            }
            //If the account is individual, create a h4 element that displays "Group Profiles"
            if ($user_type === 'individual') {
                echo '<h4>Group Profiles</h4>';
            }
        ?>
        <!-- Display a list of groups -->
        <ul class="user-list">
            <?php
            if ($user_type === 'individual') {
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

                                echo '</li>';
                            }
                        }
                    } else {
                        echo '<li>No group members found.</li>';
                    }
            }
            ?>
        </ul>
    </div>

    <?php include "hfo/footer.php"?>
    <script src="hfo/all.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>