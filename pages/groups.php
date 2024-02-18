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
            color: #AFAFAF !important;
        }

        .cn {
            max-width: 600px !important;
            margin-top: 50px !important;
            min-height: 60vh;
        }

        .search-form {
            margin-bottom: 20px !important;
        }

        .group-list {
            list-style: none !important;
            padding: 0 !important;
        }

        .group-list li {
            margin-bottom: 10px !important;
        }

        .group-list strong {
            color: #F4E0B9 !important;
        }

        .group-list a {
            color: #1B9C85 !important;
        }

        .group-list a:hover {
            color: #E8F6EF !important;
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
</head>
<body>
    <!-- Loader -->
    <div class="loader"></div>
    <!-- Header -->
    <?php include "hfo/profile-header.php"?>

    <div class="container cn">
        <!-- User profiles -->
        <h4>Groups</h4>
        <ul class="group-list">
            <?php
                // Fetch user profiles from the database
                if ($user_type === 'individual') {
                    // Retrieve group names instead of user profiles
                    $sql = "SELECT * FROM groups";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $groupName = $row['name'];
                            $groupId = $row['group_id'];

                            // Display group names with "Request to Join" button
                            echo '<li>';
                            echo '<strong>Group Name:</strong> ' . $groupName;
                            echo '<button class="btn btn-primary" onclick="requestToJoin(' . $groupId . ')">Request to Join</button>';
                            echo '</li>';
                        }
                    } else {
                        echo '<li>No groups found.</li>';
                    }
                }
            ?>
        </ul>
    </div>

    <!-- Footer -->
    <?php include "hfo/footer.php"?>
    <!-- Gets script from all.js -->
    <script src="hfo/all.js"></script>
    <script>
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

    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>