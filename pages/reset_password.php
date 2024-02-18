<?php
require_once 'hfo/db.php'; // Include database connection details

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if the token exists in the password_reset table
    $query = "SELECT * FROM password_reset WHERE token = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "Invalid or expired token.";
        exit;
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Reset Password</title>
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
        <!-- Custom CSS -->
        <style>
            body {
                background-color: #404040;
                color: #AFAFAF;
            }

            .container {
                max-width: 600px;
                padding-top: 50px;
                text-align: center;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
            }

            h1 {
                text-align: center;
                margin-bottom: 30px;
                font-size: 36px;
            }

            form input[type="password"] {
                width: 100%;
                padding: 10px;
                margin-bottom: 20px;
                border: none;
                border-radius: 5px;
            }

            form input[type="submit"] {
                background-color: #AFAFAF;
                border-color: #AFAFAF;
                color: #404040;
                padding: 10px 20px;
                font-size: 16px;
                border-radius: 5px;
            }

            form input[type="submit"]:hover {
                background-color: #232323;
                border-color: #232323;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>Reset Password</h1>

            <!-- Reset Password Form -->
            <form action="update_password.php" method="POST">
                <input type="hidden" name="token" value="<?php echo $token; ?>">

                <div class="form-group">
                    <label for="password">New Password:</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Enter your new password" required>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Confirm your new password" required>
                </div>

                <button type="submit" class="btn btn-primary">Reset Password</button>
            </form>
        </div>

        <!-- Bootstrap JS -->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    </body>
    </html>
    <?php
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate the password
    if ($password !== $confirm_password) {
        echo "Passwords do not match.";
        exit;
    }

    // Check if the token exists in the password_reset table
    $query = "SELECT * FROM password_reset WHERE token = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "Invalid or expired token.";
        exit;
    }

    // Update the user's password in the database
    $row = $result->fetch_assoc();
    $email = $row['email'];

    // Hash the new password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $query = "UPDATE users SET password = ? WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $hashedPassword, $email);
    if ($stmt->execute()) {
        // Delete the token from the password_reset table
        $query = "DELETE FROM password_reset WHERE token = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $token);
        $stmt->execute();

        echo "Password reset successful.";
    } else {
        echo "Error updating password.";
    }
} else {
    echo "Invalid request.";
}
?>