<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">

    <title>Header</title>
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
    </style>
</head>
<body>
    <header class="header">
        <div class="header-container">
            <nav class="navbar navbar-expand-lg navbar-light">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="http://localhost/zippo/admins/dashboard.php">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="http://localhost/zippo/admins/users.php">Users</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="http://localhost/zippo/admins/groups.php">Groups</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="http://localhost/zippo/admins/settings.php">Settings</a>
                        </li>
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
