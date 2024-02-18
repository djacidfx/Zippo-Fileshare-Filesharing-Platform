<?php include "hfo/all.php"?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="hfo/all.css">

    <style>
        body {
            background-color: #404040 !important;
            color: #AFAFAF !important;
        }

        .top-row{
            display: flex;
        }
        .profile-info{
            width: 30%;
            margin: 2%;
            background-color: #232323;
            padding:2%;
        }
        .upload{
            width: 70%;
            margin: 2%;
            background-color: #232323;
            padding:2%;
        }
        .upload h4{
            text-align:center;
        }
        .upload form{
            display:flex;
            align-items: center !important;
            justify-items: center !important;
            justify-content: center;
            align-content: center;
        }
        .upload form {
            display: flex;
            flex-direction: row;
            align-items: center;
        }
        .upload form .form-group {
            margin-bottom: 0;
        }
        .upload form .form-group .custom-file {
            margin-bottom: 10px;
        }
        .upload form .form-group .submit-btn {
            margin-top: 18px;
            margin-left: 10%;
        }
        .cn {
            max-width: 100vw !important;
            margin-top: 50px !important;
            min-height: 60vh;
        }
        .file-list {
            margin-top: 20px !important;
        }
        .file-list h4 {
            color: #E8F6EF !important;
            margin-top: 0 !important;
            font-size: 20px !important;
        }
        .file-list p {
            color: #fff !important;
            margin-bottom: 5px !important;
        }
        .file-list p a {
            color: #fff !important;
        }
        .group-list{
            margin-top: 18px;
        }
        .custom-file-label::after {
            content: "Browse" !important;
        }
        .invalid-feedback {
            display: none !important;
            color: red !important;
            margin-top: 5px !important;
        }
        .custom-file-input.is-invalid ~ .invalid-feedback {
            display: block !important;
        }
        .section {
            background-color: #232323 !important;
            padding: 2%;
        }
        .buttons{
            display: flex;
            flex-wrap: nowrap;
        }
        .modal{
            color: #1B9C85 !important;
        }
        #suggestions{
            cursor: pointer;
        }
    </style>
</head>
<body>
    <!-- Loader -->
    <div class="loader"></div>
    
    <?php include "hfo/profile-header.php"?>

    <?php
    if (isset($_SESSION['user_id'])) {
        // Include the database connection file
        require_once 'hfo/db.php';

        // Get the user_id from the session
        $user_id = $_SESSION['user_id'];

        // Retrieve user_type from the users table
        $userQuery = "SELECT * FROM users WHERE user_id = $user_id";
        $userResult = $conn->query($userQuery);

        if ($userResult->num_rows > 0) {
            $userRow = $userResult->fetch_assoc();
            $user_type = $userRow['user_type'];
            $username = $userRow['username'];
            $email = $userRow['email'];

            if ($user_type === 'individual') {
                // Retrieve group list from group_memberships table
                $groupQuery = "SELECT * FROM group_memberships
                               INNER JOIN groups ON group_memberships.group_id = groups.group_id
                               WHERE group_memberships.user_id = $user_id";
                $groupResult = $conn->query($groupQuery);
    ?>
    <section class="top-row">
        <section class="profile-info">
            <h3><?php echo $username;?></h3>
            <h3><?php echo $email;?></h3>
        </section>

        <!-- File upload form -->
        <section class="upload">
            <h4>Upload Files</h4>
            <form action="process_upload.php" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                <div class="form-group">
                    <label for="file">Choose File:</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="file" name="file" required>
                        <label class="custom-file-label" for="file">Select file</label>
                        <div class="invalid-feedback">Please choose a file to upload.</div>
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary submit-btn">Upload</button>
                </div>
            </form>
        </section>
    </section>

                <main class="container cn">
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Display uploaded files -->
                            <section class="file-list section">
                                <h4>Uploaded Files</h4>
                                <?php
                                    // Retrieve user's uploaded files from the database
                                    $fileQuery = "SELECT file_id, filename, filepath FROM files WHERE user_id = '$user_id'";
                                    $fileResult = $conn->query($fileQuery);

                                    if ($fileResult->num_rows > 0) {
                                        while ($fileRow = $fileResult->fetch_assoc()) {
                                            $fileId = $fileRow['file_id'];
                                            $filename = $fileRow['filename'];
                                            $filepath = $fileRow['filepath'];

                                            echo '<div class="buttons">';

                                            // Display the file name and create a link to download the file
                                            echo '<p><a href="' . $filepath . '">' . $filename . '</a>';
                                            echo '<a href="process_delete.php?fileId=' . $fileId . '" class="btn btn-danger btn-sm ml-2">Delete</a>';

                                            // Share button to open modal
                                            echo '<button class="btn btn-primary btn-sm ml-2" data-toggle="modal" data-target="#shareModal" data-fileid="' . $fileId . '">Share</button>';

                                            echo '</div>';
                                        }
                                    } else {
                                        echo '<p>No files uploaded.</p>';
                                    }
                                ?>

                            </section>
                        </div>
                        <div class="col-md-6">
                            <!-- Display group list -->
                            <section class="group-list section">
                                <h4>Group List</h4>
                                <?php
                                if ($groupResult->num_rows > 0) {
                                    while ($groupRow = $groupResult->fetch_assoc()) {
                                        $groupName = $groupRow['name'];
                                        $group_id = $groupRow['group_id'];
                                        echo '<p><a href="group-home.php?group_id=' . $group_id . '">' . $groupName . '</a></p>';
                                    }
                                } else {
                                    echo '<p>No groups joined.</p>';
                                }
                                ?>
                            </section>
                        </div>
                    </div>
                </main>
            <?php
            } else if ($user_type === 'business') {
                ?>
                <main class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <!-- Display uploaded files -->
                            <section class="file-list section">
                                <h4>Uploaded Files</h4>
                                <?php
                                // Retrieve user's uploaded files from the database
                                $fileQuery = "SELECT file_id, filename, filepath FROM files WHERE user_id = '$user_id'";
                                $fileResult = $conn->query($fileQuery);

                                if ($fileResult->num_rows > 0) {
                                    while ($fileRow = $fileResult->fetch_assoc()) {
                                        $fileId = $fileRow['file_id'];
                                        $filename = $fileRow['filename'];
                                        $filepath = $fileRow['filepath'];

                                        // Display the file name and create a link to download the file
                                        echo '<p><a href="' . $filepath . '">' . $filename . '</a>';
                                        echo '<a href="process_delete.php?fileId=' . $fileId . '" class="btn btn-danger btn-sm ml-2">Delete</a></p>';
                                    }
                                } else {
                                    echo '<p>No files uploaded.</p>';
                                }
                                ?>
                            </section>
                        </div>
                    </div>
                </main>
            <?php
            }
        }
    }
    ?>

    <!-- Share Modal -->
    <div class="modal fade" id="shareModal" tabindex="-1" role="dialog" aria-labelledby="shareModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="shareModalLabel">Share File</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="recipients">To:</label>
                        <input type="text" class="form-control" id="recipients" name="recipients" placeholder="Enter recipients" aria-describedby="recipientsHelp">
                        <small id="recipientsHelp" class="form-text text-muted">Separate usernames or emails with commas for multiple recipients.</small>
                        <div id="suggestions"></div> <!-- Container to display suggestions -->
                    </div>
                    <div class="form-group">
                        <label for="message">Message:</label>
                        <textarea class="form-control" name="message" id="message" rows="3"></textarea>
                    </div>
                    <input type="hidden" name="file_id" id="fileId" value="<?php echo $fileId?>">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="shareButton">Share</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer -->
    <?php include "hfo/footer.php"?>
    <!-- Gets script from all.js -->
    <script src="hfo/all.js"></script>
    <script>
        // Attach an event listener to the recipients input field
        const recipientsInput = document.getElementById("recipients");
        recipientsInput.addEventListener("input", getSuggestions);

        // Function to fetch and display suggestions
        function getSuggestions() {
            const input = recipientsInput.value;
            const suggestionsContainer = document.getElementById("suggestions");

            // Clear previous suggestions
            suggestionsContainer.innerHTML = "";

            // Only make a request if there is input
            if (input.length > 0) {
                // Send an Ajax request to fetch the suggestions
                const request = new XMLHttpRequest();
                request.open("GET", `get_suggestions.php?input=${input}`, true);
                request.onreadystatechange = function () {
                    if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                        const suggestions = JSON.parse(this.responseText);

                        // Display the suggestions
                        suggestions.forEach(function (suggestion) {
                            const suggestionElement = document.createElement("p");
                            suggestionElement.textContent = suggestion;
                            suggestionsContainer.appendChild(suggestionElement);
                        });
                    }
                };
                request.send();
            }
        }

        // Attach an event listener to the share button
        const shareButton = document.getElementById("shareButton");
        shareButton.addEventListener("click", shareFile);

        // Function to handle the sharing of the file
        function shareFile() {
            // Retrieve the recipients, message, and other data
            const recipients = recipientsInput.value;
            const message = document.getElementById("message").value;
            const file_id = document.getElementById("fileId").value;

            // Create a URL-encoded string with the data
            const data = `file_id=${encodeURIComponent(file_id)}&recipients=${encodeURIComponent(recipients)}&message=${encodeURIComponent(message)}`;

            // Send an AJAX request to the process_share.php page
            const request = new XMLHttpRequest();
            request.open("POST", "process_share.php", true);
            request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            request.onreadystatechange = function () {
                if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                    // Handle the response from the process_share.php page
                    const response = this.responseText;
                    if (response === "success") {
                        // Display a success message
                        alert("File shared successfully.");
                        // Close the modal
                        const shareModal = document.getElementById("shareModal");
                        $(shareModal).modal("hide");
                    } else {
                        // Display an error message
                        alert("Error sharing file.");
                    }
                }
            };
            request.send(data);
        }

        // Attach an event listener to the suggestions container
        const suggestionsContainer = document.getElementById("suggestions");
        suggestionsContainer.addEventListener("click", selectSuggestion);

        // Function to handle selecting a suggestion
        function selectSuggestion(event) {
            // Check if a suggestion was clicked
            if (event.target.tagName === "P") {
                // Retrieve the selected suggestion
                const selectedSuggestion = event.target.textContent;

                // Auto-fill the recipient field with the selected suggestion
                recipientsInput.value = selectedSuggestion;

                // Clear the suggestions container
                suggestionsContainer.innerHTML = "";
            }
        }

    </script>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>
</html>