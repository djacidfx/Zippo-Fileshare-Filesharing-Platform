<?php include "hfo/all.php"?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <!-- Custom css -->
    <link rel="stylesheet" href="hfo/all.css">
    <style>
        body {
            background-color: #404040 !important;
            color: #AFAFAF !important;
        }
        .profile-link {
            color: #F4E0B9;
            text-decoration: none;
        }
        .profile-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <!-- Loader -->
    <div class="loader"></div>

    <!-- Header -->
    <?php include 'hfo/Profile-header.php'; ?>

    <div class="container mt-4">
        <?php

        //database connection
        include 'hfo/db.php';

        //Get user_id fro session started in header
        $user_id = $_SESSION['user_id'];

        // Select user information from users table
        $query = "SELECT * FROM users WHERE user_id = $user_id";
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $user_type = $row['user_type'];
            $group_id = $row['group_id'];
            $creator = $row['creator'];
        }

        // Check if the search query is submitted
        if (isset($_GET['search'])) {
            // Sanitize the search query to prevent SQL injection
            $searchQuery = $conn->real_escape_string($_GET['search']);

            // Construct the SQL query to search for user profiles
            $sql = "SELECT * FROM users WHERE 1=1";

            // Add the search query condition
            $sql .= " AND (username LIKE '%$searchQuery%' OR email LIKE '%$searchQuery%')";

            // Execute the query
            $result = $conn->query($sql);

            // Check if any profiles match the search query
            if ($result->num_rows > 0) {
                // Display the search results
                while ($row = $result->fetch_assoc()) {
                    // Output the profile information
                    echo "<div>";
                    if ($user_type === 'business') {
                        echo "Email: <a class='profile-link' href='public_profile.php?email=" . urlencode($row['email']) . "'> " . $row['email']  . "</a> ";
                        echo "</div><hr>";
                    } else {
                        echo '<strong>Username: </strong>' . $row['username'];
                        echo " | Email: " . $row['email'];
                        echo "</div><hr>";
                    }
                }
            } else {
                // No profiles match the search query
                echo "No results found.";
            }

            // Close the database connection
            $conn->close();
        }
        ?>
    </div>

    <!-- Footer -->
    <?php include 'hfo/footer.php'; ?>

    <!-- Gets script from all.js -->
    <script src="hfo/all.js"></script>

    <!-- jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>