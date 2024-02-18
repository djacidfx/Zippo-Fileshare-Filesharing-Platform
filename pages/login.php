<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <!-- Custom CSS -->
    
    <link rel="stylesheet" href="hfo/all.css">
    
    <style>
        body {
            background-color: #404040;
            color: #AFAFAF;
        }

        .container {
            max-width: 400px;
            margin-top: 50px;
            margin-bottom: 50px;
            background-color: #232323;
            padding: 20px;
            border-radius: 5px;
        }

        .container h3 {
            text-align: center;
        }

        .form-group label {
            color: #F4E0B9;
        }

        .form-control {
            border-color: #F4E0B9;
        }

        .btn-primary {
            background-color: #404040;
            border-color: #404040;
        }

        .btn-primary:hover {
            background-color: #232323;
            border-color: #232323;
        }

        .mt-3 {
            text-align: center;
        }

        .mt-3 a {
            color: #F4E0B9;
            text-decoration: underline;
        }

        .mt-3 a:hover {
            color: #F4E0B9;
            text-decoration: underline;
        }
    </style>
    <link rel="stylesheet" href="hfo/text.css">
</head>
<body>

    <!-- Loader -->
    <div class="loader"></div>

    <div class="container">
        <h3>Login</h3>
        <!-- Login Form -->
        <form id="login-form">
            <div class="form-group">
                <label for="username-email">Username or Email:</label>
                <input type="text" class="form-control" id="username-email" name="username-email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>

        <div class="mt-3">
            <a href="forgot_password.php">Forgot Password?</a>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <!-- Gets script from all.js -->
    <script src="hfo/all.js"></script>
    <script>
        $(document).ready(function() {
            // Handle form submission
            $("#login-form").submit(function(event) {
                event.preventDefault(); // Prevent default form submission
                var form = $(this);
                var url = form.attr('action') || 'process_login.php';
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
                            // Redirect to the dashboard page
                            window.location.href = 'dashboard.php';
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