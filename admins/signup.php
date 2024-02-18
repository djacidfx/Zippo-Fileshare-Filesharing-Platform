<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <!-- Custom CSS -->

    <link rel="stylesheet" href="hfo/all.css">

    <style>
        body {
            background-color: #1B9C85;
        }

        .container {
            max-width: 400px;
            margin-top: 50px;
            background-color: #E8F6EF;
            padding: 20px;
            border-radius: 5px;
        }

        .container h2 {
            color: #1B9C85;
            text-align: center;
        }

        .form-group label {
            color: #1B9C85;
        }

        .form-control {
            border-color: #1B9C85;
        }

        .btn-primary {
            background-color: #1B9C85;
            border-color: #1B9C85;
        }

        .btn-primary:hover {
            background-color: #166A57;
            border-color: #166A57;
        }

        .mt-3 {
            color: #1B9C85;
            text-align: center;
        }

        .mt-3 a {
            color: #1B9C85;
            text-decoration: underline;
        }

        .mt-3 a:hover {
            color: #166A57;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <!-- Loader -->
    <div class="loader"></div>

    <div class="container">
        <h2>Admin Sign Up</h2>
        <form id="signup-form">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm-password">Confirm Password:</label>
                <input type="password" class="form-control" id="confirm-password" name="confirm-password" required>
            </div>
            <button type="submit" class="btn btn-primary">Sign Up</button>
        </form>
        
        <div class="mt-3">
            Already have an account? <a href="login.php">Log in</a>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script src="hfo/all.js"></script>
    <script>
        $(document).ready(function() {
            // Handle form submission
            $("#signup-form").submit(function(event) {
                event.preventDefault(); // Prevent default form submission
                var form = $(this);
                var url = form.attr('action') || 'process-signup.php';
                var formData = form.serialize();

                // Perform AJAX request
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: formData,
                    success: function(response) {
                        // Process the response from the server
                        console.log(response);
                        // Check if the response indicates success
                        if (response.startsWith('Success:')) {
                            // Redirect to the login page
                            window.location.href = 'login.php';
                        } else {
                            // Display the response message to the user
                            alert(response);
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle AJAX errors
                        console.log(xhr.responseText);
                    }
                });
            });
        });
    </script>
</body>
</html>