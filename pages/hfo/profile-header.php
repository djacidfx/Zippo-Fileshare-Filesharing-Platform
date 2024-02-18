<?php
$query = "SELECT * FROM users WHERE user_id = $user_id";
$result = $conn->query($query);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $user_type = $row['user_type'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">

    <title>Header</title>
    <!-- Custom Css -->
    <style>
        /* Header Styles */
        .header {
            background-color: #A8A196;
            padding: 20px;
        }

        .header .navbar-brand {
            color: #232323;
        }

        .header .navbar-nav .nav-link {
            color: #232323;
            text-decoration: none;
        }

        .header .navbar-nav .nav-link:hover {
            text-decoration: underline;
        }

        .header .dropdown-toggle::after {
            display: none;
        }

        .header .navbar-toggler-icon {
            color: #fff;
        }

        .header .search-form {
            margin-right: 10px;
        }

        .header .search-form input[type="text"] {
            border-radius: 20px;
        }

        .header .search-form button[type="submit"] {
            border-radius: 20px;
        }
        
        .btn-primary {
            background-color: #A8A196 !important;
            border-color: #fff !important;
        }

        .btn-primary:hover {
            background-color: #404040 !important;
            border-color: #404040 !important;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <form class="form-inline search-form" method="GET" action="search.php">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="search" placeholder="Search Profiles...">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit">Search</button>
                                    </div>
                                </div>
                            </form>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="http://localhost/zippo/pages/dashboard.php">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="http://localhost/zippo/pages/profile.php">Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="http://localhost/zippo/pages/settings.php">Settings</a>
                        </li>
                        <?php if ($user_type === 'individual') : ?>
                            <li class="nav-item">
                                <a class="nav-link" href="http://localhost/zippo/pages/groups.php">Groups</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </nav>
        </div>
    </header>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>
</html>