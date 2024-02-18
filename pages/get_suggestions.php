<?php
// Include the database connection file
require_once 'hfo/db.php';

// Retrieve the input from the GET request
$input = $_GET['input'];

// Prepare the SQL query to retrieve matching users
$query = "SELECT username, email FROM users WHERE username LIKE '%$input%' OR email LIKE '%$input%'";
$result = $conn->query($query);

// Create an array to store the suggestions
$suggestions = [];

// Check if there are any matching users
if ($result->num_rows > 0) {
    // Fetch the results and add them to the suggestions array
    while ($row = $result->fetch_assoc()) {
        $suggestions[] = $row['username'];
        $suggestions[] = $row['email'];
    }
}

// Return the suggestions as JSON
echo json_encode($suggestions);

// Close the database connection
$conn->close();
?>
