<?php
session_start();
include("index.php"); // Database connection

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    die("<p class='error'>You must be logged in to view your records.</p>");
}

$email = $_SESSION['email']; // Logged-in user's email

// Fetch farmer ID and username using email
if (!isset($_SESSION['farmer_id']) || !isset($_SESSION['username'])) {
    $query = "SELECT id, username FROM farmer WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['farmer_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
    } else {
        die("Error: Farmer details not found.");
    }
}

$farmer_id = $_SESSION['farmer_id'];
$username = $_SESSION['username']; // Get username from session

// Fetch milk production data for the logged-in farmer
$sql = "SELECT production_date, milk FROM milkproduction WHERE farmer_id = ? ORDER BY production_date";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $farmer_id);
$stmt->execute();
$result = $stmt->get_result();

$milkData = [];
while ($row = $result->fetch_assoc()) {
    $milkData[] = $row;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Milk Production Report</title>
    <link rel="stylesheet" href="/home.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Chart.js CDN -->
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background: #e3f2fd; /* Blue Theme */
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .chart-container {
            width: 100%;
            height: 400px; /* Ensuring proper height */
            position: relative;
        }
        canvas {
            width: 100% !important;
            height: 100% !important;
        }
        header{
            margin-bottom: 30px;
        }
    </style>
</head>
<body>

<header>
    <nav class="navbar">
        <a href="home.php" id="a" style="font-weight: bold;">DairyTrack</a>
        <ul class="nav-items">
            <li><a href="home.php">Home</a></li>
            <li><a href="#">About Us</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</header>
<h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>

<h2 style="margin-top:20px;margin-bottom:20px;">Milk Production Report</h2>

<div class="container">
    <h3 >Milk Production (Liters)</h3>
    <div class="chart-container">
        <canvas id="milkChart"></canvas>
    </div>
</div>
<footer style="margin-top:30px;">
    <p>&copy; 2024 Milk Dairy Management System. All rights reserved.</p>
</footer>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        let milkData = <?php echo json_encode($milkData); ?>; // Fetching data from PHP

        if (milkData.length === 0) {
            alert("No milk production data available.");
            return;
        }

        // Extracting values for Chart.js
        let dates = milkData.map(entry => entry.production_date);
        let milkValues = milkData.map(entry => parseFloat(entry.milk));

        // Chart Configuration
        let ctx = document.getElementById("milkChart").getContext("2d");
        new Chart(ctx, {
            type: "line",
            data: {
                labels: dates,
                datasets: [{
                    label: "Milk (Liters)",
                    data: milkValues,
                    borderColor: "rgba(0, 123, 255, 1)",
                    backgroundColor: "rgba(0, 123, 255, 0.2)",
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: { title: { display: true, text: "Date" } },
                    y: { title: { display: true, text: "Milk (Liters)" }, beginAtZero: true }
                }
            }
        });
    });
</script>

</body>
</html>
