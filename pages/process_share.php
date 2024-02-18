<?php
    session_start();
    // Include the necessary file for database connection
    require_once 'hfo/db.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the form data
        $file_id = $_POST['file_id'];
        $sender_id = $_SESSION['user_id'];
        $recipients = $_POST['recipients'];
        $message = $_POST['message'];

        // Insert file sharing details into file_shares table
        $shareQuery = "INSERT INTO file_shares (file_id, sender_id) VALUES ('$file_id', '$sender_id')";
        if ($conn->query($shareQuery) === TRUE) {
            // Get the share_id of the inserted record
            $share_id = $conn->insert_id;

            // Insert recipients into file_share_recipients table
            $recipientArray = explode(',', $recipients);
            foreach ($recipientArray as $recipient) {
                // Trim whitespace from recipient's username or email
                $recipient = trim($recipient);

                // Retrieve recipient_id from users table based on username or email
                $recipientQuery = "SELECT * FROM users WHERE username = '$recipient' OR email = '$recipient'";
                $recipientResult = $conn->query($recipientQuery);

                if ($recipientResult->num_rows > 0) {
                    $recipientRow = $recipientResult->fetch_assoc();
                    $recipient_id = $recipientRow['user_id'];
                    $recipient_email = $recipientRow['email'];

                    // Insert recipient details into file_share_recipients table
                    $recipientInsertQuery = "INSERT INTO file_share_recipients (share_id, recipient_id) VALUES ('$share_id', '$recipient_id')";
                    $conn->query($recipientInsertQuery);

                    // Send email to recipient
                    $recipientEmail = $recipient_email; // Change this to the recipient's email column in the users table
                    $fileLink = "http://localhost/zippo/pages/download.php?file_id=" . $file_id; // Update the download link accordingly
                    $emailSubject = "File Sharing Notification";
                    $emailBody = "Dear $recipient,\n\nYou have received a shared file from the user.\n\nMessage: $message\n\nYou can download the file using the link below:\n$fileLink\n\nThank you,\nThe Sender";
                    mail($recipientEmail, $emailSubject, $emailBody);
                }
            }

            // Redirect to a success page or display a success message
            echo "success";
        } else {
            echo "Error: " . $conn->error;
        }

        // Close the database connection
        $conn->close();
    }
?>