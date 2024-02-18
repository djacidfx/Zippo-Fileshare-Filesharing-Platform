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
        .form-group.company-fields {
            display: none;
        }
    </style>
</head>
<body>
    <!-- Loader -->
    <div class="loader"></div>

    <div class="container">
        <h2>Create Business Group</h2>

        <!-- Create Group Form -->
        <form id="create-group-form">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <?php
            if (isset($_GET['email'])) {
                $email = $_GET['email'];
                echo '<input type="hidden" name="email" value="' . htmlspecialchars($email) . '">';
            }
            ?>
            <button type="submit" class="btn btn-primary">Create Group</button>
        </form>
        
        <!-- If the user wants to join a group instead, take them to the group-login.php page -->
        <div class="mt-3">
            <a href="group-login.php?email=<?php echo htmlspecialchars($_GET['email']); ?>">Join Group Instead</a>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <!-- Gets script from all.js -->
    <script src="hfo/all.js"></script>
    <!-- Custom Script -->
    <script>
        $(document).ready(function() {
            // Handle form submission
            $("#create-group-form").submit(function(event) {
                event.preventDefault(); // Prevent default form submission
                var form = $(this);
                var url = form.attr('action') || 'process_group-creation.php';
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

            // Handle user type switch
            $("#user-type").change(function() {
                var userType = $(this).val();
                if (userType === "business") {
                    $(".form-group.company-fields").show();
                    $("#username").attr("placeholder", "Company Name").val("");
                    $("#email").attr("placeholder", "Work Email").val("");
                } else {
                    $(".form-group.company-fields").hide();
                    $("#username").attr("placeholder", "Username").val("");
                    $("#email").attr("placeholder", "Email").val("");
                }
            });
        });
    </script>
</body>
</html>