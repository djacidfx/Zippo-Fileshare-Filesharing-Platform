<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <!-- Custom CSS -->
        
    <link rel="stylesheet" href="hfo/all.css">
    <link rel="stylesheet" href="hfo/text.css">

    <style>
        body {
            background-color: #404040;
            color: #AFAFAF;
        }

        .container {
            max-width: 400px;
            margin-top: 50px;
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
    </style>
</head>
<body>
    <!-- Loader -->
    <div class="loader"></div>

    <div class="container">
        <h3>Forgot Password</h3>
        <!-- Forgot Password Form -->
        <form id="forgot-password-form">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
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
            $("#forgot-password-form").submit(function(event) {
                event.preventDefault(); // Prevent default form submission
                var form = $(this);
                var url = form.attr('action') || 'process_forgot_password.php';
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
                            // Display a success message to the user
                            alert(response);
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
</html