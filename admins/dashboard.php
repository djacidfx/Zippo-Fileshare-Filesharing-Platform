<?php
session_start();
// Check if the user_id is not in the session
if (!isset($_SESSION['admin_id'])) {
    // Redirect to the signup page
    header('Location: login.php');
    exit(); // Stop further execution
}
// Include database connection details and other required files
require_once 'hfo/db.php'; // Include database connection details

// Retrieve user count
$query = "SELECT COUNT(*) AS user_count FROM users";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
$userCount = $result->fetch_assoc()['user_count'];

// Retrieve file count for each user
$query = "SELECT users.username, COUNT(files.file_id) AS file_count FROM users LEFT JOIN files ON users.user_id = files.user_id GROUP BY users.user_id";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
$fileCounts = $result->fetch_all(MYSQLI_ASSOC);

// Calculate overall file count
$overallFileCount = 0;
foreach ($fileCounts as $row) {
    $overallFileCount += $row['file_count'];
}

// Prepare data for chart
$userLabels = [];
$fileData = [];
foreach ($fileCounts as $row) {
    $userLabels[] = $row['username'];
    $fileData[] = $row['file_count'];
}

// Convert data to JSON for JavaScript chart library (e.g., Chart.js)
$userLabelsJson = json_encode($userLabels);
$fileDataJson = json_encode($fileData);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="hfo/all.css">

    <style>
        body {
            background-color: #AFAFAF;
        }
        
        .container {
            margin-top: 50px;
            background-color: #FFFFFF;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        h1 {
            color: #232323;
            margin-bottom: 20px;
        }
        
        h3 {
            color: #232323;
            font-weight: bold;
            margin-bottom: 20px;
        }
        
        canvas {
            background-color: #FFFFFF;
        }
    </style>
</head>
<body>
    <div class="loader"></div>
    <?php include "hfo/admin_header.php"?>

    <div class="container">
        <h1>Dashboard</h1>
        <div class="row">
            <div class="col-md-6">
                <h3>User Count: <?php echo $userCount; ?></h3>
                <h3>Overall File Count: <?php echo $overallFileCount; ?></h3>
            </div>
            <div class="col-md-6">
                <canvas id="fileChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Retrieve data from PHP and parse JSON
        var userLabels = <?php echo $userLabelsJson; ?>;
        var fileData = <?php echo $fileDataJson; ?>;

        // Create chart
        var ctx = document.getElementById('fileChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: userLabels,
                datasets: [{
                    label: 'File Count',
                    data: fileData,
                    backgroundColor: '#232323',
                    borderColor: '#232323',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: false,
                        ticks: {
                            stepSize: 1,
                            suggestedMin: 1,
                            precision: 0,
                            fontColor: '#4C4C6D'
                        },
                        grid: {
                            color: '#E8F6EF'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            fontColor: '#4C4C6D'
                        }
                    }
                }
            }
        });

    </script>
    <script src="hfo/all.js"></script>
</body>
</html>

