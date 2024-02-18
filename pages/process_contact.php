<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Admin email address
    $adminEmail = 'admin@example.com';

    // Email subject
    $subject = 'Contact Form Submission';

    // Email body
    $body = "Name: $name\n";
    $body .= "Email: $email\n";
    $body .= "Message: $message\n";

    // Send email
    $success = mail($adminEmail, $subject, $body);

    if ($success) {
        echo 'Email sent successfully.';
    } else {
        echo 'Failed to send email.';
    }
} else {
    echo 'Invalid request.';
}
?>
