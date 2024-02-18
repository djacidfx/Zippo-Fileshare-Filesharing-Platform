<?php include "hfo/all.php"?>
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
            background-color: #404040 !important;
            color: #AFAFAF !important;
        }

        .cn {
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
    <!-- Loader -->
    <div class="loader"></div>

    <!-- Header -->
    <?php include "hfo/profile-header.php"?>

    <!-- Php code for deleting an account -->
    <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['delete_account'])) {
                // Delete the user account from the database
                require_once "hfo/db.php";

                $user_id = $_SESSION['user_id'];
                $query = "DELETE FROM users WHERE user_id = $user_id";
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

    <div class="container cn">
        <h1>Settings</h1>

        <!-- The actual settings -->

        <?php
        require_once "hfo/db.php";

        // Fetch user's information from the database
        $user_id = $_SESSION['user_id'];
        $query = "SELECT * FROM users WHERE user_id = $user_id";
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $username = $row['username'];
            $password = $row['password'];
            $email = $row['email'];
            $unhashedPassword = password_hash($password, PASSWORD_DEFAULT); // Unhash the password
            $user_type = $row['user_type'];
            $group_id = $row['group_id'];
            $creator = $row['creator'];
        }
        ?>
        <!-- Group section -->
        <h2>Groups</h2>

        <!-- If account is indiviual then display a Group key form, which will mae the user a group member -->
        <?php if ($user_type === 'individual'): ?>
        <form id="group-form">
            <div class="form-group">
                <label for="group-key">Group Key: </label>
                <input type="text" class="form-control" id="group-key" name="group-key" required>
            </div>
            <?php
                echo '<input type="hidden" name="email" value="' . $email . '">';
            ?>
            <button type="submit" class="btn btn-primary">Join Group</button>
        </form>
        <?php endif; ?>
        <br>

        <!-- The Code to recieve the group key-->
        <?php if ($user_type === 'business'): ?>
        <?php
            $query = "SELECT DISTINCT groups.group_key, groups.name 
            FROM group_memberships 
            INNER JOIN groups ON group_memberships.group_id = groups.group_id 
            WHERE group_memberships.user_id = $user_id";
            $result = $conn->query($query);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $group_key = $row['group_key'];
                $group_name = $row['name'];
                echo '        
                <p>
                    Group Key: '. $group_name .'
                    <span class="group-key" onclick="copyGroupKey()">' . $group_key . ' </span>
                </p>';
            }
        ?>

        <!-- The code to copy the group key to the clipboard -->
        <script>
            function copyGroupKey() {
                var groupKey = document.querySelector(".group-key");
                var range = document.createRange();
                range.selectNode(groupKey);
                window.getSelection().removeAllRanges();
                window.getSelection().addRange(range);
                document.execCommand("copy");
                window.getSelection().removeAllRanges();
                alert("Group key copied to clipboard!");
            }
        </script>
        <?php endif; ?>

        <!-- Same as above -->
        <?php if ($user_type === 'individual'): ?>
        <?php
            $query = "SELECT DISTINCT groups.group_key, groups.name 
                      FROM group_memberships 
                      INNER JOIN groups ON group_memberships.group_id = groups.group_id 
                      WHERE group_memberships.user_id = $user_id";
            $result = $conn->query($query);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $group_key = $row['group_key'];
                    $group_name = $row['name'];

                    // Display group keys
                    echo '<p>';
                    echo 'Group Key: '. $group_name .' <span class="group-key" onclick="copyGroupKey()">' . $group_key . '</span>';
                    echo '</p>';
                }
            } else {
                echo '<p>No groups joined.</p>';
            }
        ?>
        <script>
            function copyGroupKey() {
                var groupKey = event.target.innerText;
                var range = document.createRange();
                range.selectNode(event.target);
                window.getSelection().removeAllRanges();
                window.getSelection().addRange(range);
                document.execCommand("copy");
                window.getSelection().removeAllRanges();
                alert("Group key copied to clipboard!");
            }
        </script>
        <?php endif; ?>

        <!-- Form to update email and username -->
        <form action="update_settings.php" method="POST">
            <div class="form-group">
                <label for="username">Company Name/Username:</label>
                <input type="text" id="username" name="username" class="form-control" placeholder="Enter your username" value="<?php echo $username; ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" value="<?php echo $email; ?>" required>
            </div>

            <button type="submit" class="btn btn-primary submit-btn">Update</button>
        </form>

        <br>

        <!-- Danger Section -->
        <div class="danger-section">
            <h2>Danger Zone</h2>
            <?php if ($user_type === 'business' && $creator === 'Yes'): ?>
                <p>Delete your group:</p>
                <form action="delete_group.php" method="POST" onsubmit="return confirm('Are you sure you want to delete your group? This action cannot be undone.');">
                    <input type="hidden" name="group_id" value="<?php echo $group_id; ?>">
                    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                    <button type="submit" name="delete_group" class="btn btn-danger">Delete Group</button>
                </form>
            <?php endif; ?>
            <br>
            <p>Delete your account:</p>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">
                <button type="submit" name="delete_account" class="btn btn-danger">Delete Account</button>
            </form>
        </div>
    </div>

    <br>
    
    <!-- Footer -->
    <?php include "hfo/footer.php"?>

    <!-- Gets script from all.js -->
    <script src="hfo/all.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            // Handle form submission
            $("#group-form").submit(function(event) {
                event.preventDefault(); // Prevent default form submission
                var form = $(this);
                var url = form.attr('action') || 'process_group-login.php';
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
                            // Redirect to the settings page
                            window.location.href = 'settings.php';
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