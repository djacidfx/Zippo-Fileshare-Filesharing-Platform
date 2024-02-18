<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_account'])) {
        // Delete the user account from the database
        require_once "hfo/db.php";

        $user_id = $_SESSION['user_id'];
        $query = "DELETE FROM admins WHERE admin_id = $admin_id";
        if ($conn->query($query)) {
            // User account deleted successfully, redirect to a login page or any other appropriate page
            header("Location: login.php");
            exit;
        } else {
            // Failed to delete the user account
            echo "Failed to delete the user account.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <!-- Custom CSS -->
    
    <link rel="stylesheet" href="hfo/all.css">

    <style>
        body {
            background-color: #AFAFAF !important;
            color: #232323 !important;
        }

        .container {
            max-width: 600px !important;
            padding-top: 50px !important;
        }

        h1 {
            text-align: center !important;
            margin-bottom: 30px !important;
            font-size: 36px !important;
        }

        form label {
            font-size: 18px !important;
        }

        form input[type="text"],
        form input[type="email"] {
            width: 100% !important;
            padding: 10px !important;
            margin-bottom: 20px !important;
            border: none !important;
            border-radius: 5px !important;
        }

        form input[type="submit"] {
            background-color: #404040 !important;
            border-color: #AFAFAF !important;
            color: #AFAFAF !important;
            padding: 10px 20px !important;
            font-size: 16px !important;
            border-radius: 5px !important;
        }

        .submit-btn {
            background-color: #404040 !important;
            border-color: #AFAFAF !important;
            color: #fff !important;
            padding: 10px 20px !important;
            font-size: 16px !important;
            border-radius: 5px !important;
        }

        form input[type="submit"]:hover {
            background-color: #0B665B !important;
            border-color: #0B665B !important;
        }
    </style>
</head>
<body>
    <div class="loader"></div>
    <?php include "hfo/admin_header.php"?>

    <div class="container">
        <h1>Admin Settings</h1>

        <?php
        require_once "hfo/db.php";

        // Fetch user's information from the database
        $admin_id = $_SESSION['admin_id'];
        $query = "SELECT * FROM admins WHERE admin_id = $admin_id";
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $username = $row['username'];
            $password = $row['password'];
            $email = $row['email'];
            $unhashedPassword = password_hash($password, PASSWORD_DEFAULT); // Unhash the password
        }
        ?>

        <form action="update_settings.php" method="POST">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" class="form-control" placeholder="Enter your username" value="<?php echo $username; ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" value="<?php echo $email; ?>" required>
            </div>

            <button type="submit" class="btn btn-primary submit-btn">Update</button>
        </form>
        <br>
        <div class="danger-section">
            <h2>Danger Zone</h2>
            <p>Delete admin account:</p>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">
                <button type="submit" name="delete_account" class="btn btn-danger">Delete Account</button>
            </form>
        </div>
    </div>
    <script src="hfo/all.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>
</html>