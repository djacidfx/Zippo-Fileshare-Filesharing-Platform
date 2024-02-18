<?php include "hfo/all.php"?>
<?php
        $email = $_GET['email'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $email; ?> - Profile</title>
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
        }

        .profile-header {
            text-align: center !important;
            margin-bottom: 30px !important;
        }

        .profile-header h2 {
            color: #AFAFAF !important;
        }

        .user-info-card {
            background-color: #232323 !important;
            padding: 20px !important;
            margin-bottom: 30px !important;
        }

        .user-info-card h4 {
            margin-top: 0 !important;
            font-weight: bold !important;
        }

        .file-section {
            background-color: #232323 !important;
            padding: 20px !important;
        }

        .file-section h4 {
            margin-top: 0 !important;
            font-weight: bold !important;
            margin-bottom: 20px !important;
        }

        .file-list {
            list-style: none !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        .file-list li {
            margin-bottom: 10px !important;
        }

        .file-list a {
            color: #AFAFAF !important;
        }

        .file-list a:hover {
            color: #AFAFAF !important;
        }
    </style>
</head>
<body>
    <!-- Loader -->
    <div class="loader"></div>
    <!-- Header -->
    <?php include "hfo/profile-header.php"?>

    <div class="container cn">
        <!-- Display the User's username -->
        <div class="profile-header">
            <h2><?php echo $email; ?> - Profile</h2>
        </div>

        <?php
        // Include the database connection file
        require_once 'hfo/db.php';

        // Check if the username is provided as a query parameter
        if (isset($_GET['email'])) {
            $email = $_GET['email'];

            $query = "SELECT * FROM users WHERE user_id = $user_id";
            if ($conn->query($query)) {
                $user_type = $row['user_id'];
            }
            if($user_type === 'business'){
                // Retrieve user information from the database
                $sql = "SELECT username, email FROM users WHERE email = '$email'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $username = $row['username'];
                    $email = $row['email'];

                    // Display the user information
                    echo '<div class="user-info-card">';
                    echo '<h4>Username: ' . $username . '</h4>';
                    echo '<h4>Email: ' . $email . '</h4>';
                    echo '</div>';

                    // Retrieve user's files from the database
                    $sql_files = "SELECT filename, filepath FROM files WHERE user_id = (SELECT user_id FROM users WHERE email = '$email')";
                    $result_files = $conn->query($sql_files);

                    if ($result_files->num_rows > 0) {
                        echo '<div class="file-section">';
                        echo '<h4>Files:</h4>';
                        echo '<ul class="file-list">';
                        while ($file_row = $result_files->fetch_assoc()) {
                            $filename = $file_row['filename'];
                            $filepath = $file_row['filepath'];

                            // Display the file name and create a link to download the file
                            echo '<li><a href="' . $filepath . '">' . $filename . '</a></li>';
                        }
                        echo '</ul>';
                        echo '</div>';
                    } else {
                        echo '<p>No files available.</p>';
                    }
                } else {
                    echo '<p>User not found.</p>';
                }
            } else {
                // Retrieve user information from the database
                $sql = "SELECT username, email FROM users WHERE email = '$email'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $username = $row['username'];
                    $email = $row['email'];

                    // Display user information
                    echo '<div class="user-info-card">';
                    echo '<h4>Username: ' . $username . '</h4>';
                    echo '<h4>Email: ' . $email . '</h4>';
                    echo '</div>';

                    // Retrieve files shared by the user with the specified email
                    $fileQuery = "SELECT f.filename, f.filepath
                                FROM file_shares fs
                                INNER JOIN files f ON fs.file_id = f.file_id
                                INNER JOIN users u ON fs.sender_id = u.user_id
                                WHERE u.email = '$email'";
                    $fileResult = $conn->query($fileQuery);

                    if ($fileResult->num_rows > 0) {
                        echo '<div class="file-section">';
                        echo '<h4>Files:</h4>';
                        echo '<ul class="file-list">';
                        while ($fileRow = $fileResult->fetch_assoc()) {
                            $filename = $fileRow['filename'];
                            $filepath = $fileRow['filepath'];

                            // Display the file name and create a link to download the file
                            echo '<li><a href="' . $filepath . '">' . $filename . '</a></li>';
                        }
                        echo '</ul>';
                        echo '</div>';
                    } else {
                        echo '<p>No files shared.</p>';
                    }
                } else {
                    echo '<p>User not found.</p>';
                }
            }
        } else {
            echo '<p>Invalid request.</p>';
        }
        ?>

    </div>

    <!-- Footer -->
    <?php include "hfo/footer.php"?>
    
    <!-- Gets script from all.js -->
    <script src="hfo/all.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>
</html>