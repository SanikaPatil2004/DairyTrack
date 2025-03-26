<?php
session_start(); // Start session to get logged-in user info

// Include the database connection
include("index.php");

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    die("<p class='error'>You must be logged in to view your records.</p>");
}

$email = $_SESSION['email']; // Get the email of the logged-in user

// Fetch farmer details using email


$farmerSql = "SELECT id, username FROM farmer WHERE email = ?";
$stmt = $conn->prepare($farmerSql);
$stmt->bind_param('s', $email);
$stmt->execute();
$farmerResult = $stmt->get_result();

if ($farmerResult->num_rows > 0) {
    $farmerData = $farmerResult->fetch_assoc();
    $farmer_id = $farmerData['id'];

    // Check if we are fetching today's record or all records
    if (isset($_POST['fetch_today'])) {
        $today = date("Y-m-d");
        $productionSql = "SELECT production_date, milk, fat, snf, rate, total_rs FROM milkproduction WHERE farmer_id = ? AND production_date = ?";
        $stmt = $conn->prepare($productionSql);
        $stmt->bind_param('is', $farmer_id, $today);
    } else {
        $productionSql = "SELECT production_date, milk, fat, snf, rate, total_rs FROM milkproduction WHERE farmer_id = ?";
        $stmt = $conn->prepare($productionSql);
        $stmt->bind_param('i', $farmer_id);
    }
    
    $stmt->execute();
    $productionResult = $stmt->get_result();
    
    if ($productionResult->num_rows > 0) {
        while ($row = $productionResult->fetch_assoc()) {
            $productionData[] = $row;
        }
    } else {
        $productionData = [];
    }
} else {
    die("<p class='error'>No farmer data found for the logged-in user.</p>");
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="home.css">
    <title>Milk Production Records</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fc;
            text-align: center;
        }
        
        .container {
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            width: 80%;
            max-width: 600px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        button {
            background: linear-gradient(135deg,rgb(94, 197, 232),rgb(169, 81, 236));
            color: white;
            cursor: pointer;
            width: 150px;
            height: 40px;
            padding: 10px;
            margin: 5px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        button:hover {
            background: linear-gradient(135deg,rgb(5, 142, 188),rgb(149, 29, 240));
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        h1{
            margin-top: 20px;
        }
    </style>
</head>
<body>
<header>
        <nav class="navbar">
            <a href="home.php" id="a"style="font-weight: bold;">DairyTrack</a>
            <ul class="nav-items">
                <li><a href="#">Home</a></li>
                <li><a href="#">About Us</a></li>
                <li><a href="logout.php">Logout</a></li>
                <!-- <li>
                    <select id="languageSelect">
                        <option value="English">English</option>
                        <option value="Hindi">Hindi</option>
                        <option value="Marathi">Marathi</option>
                    </select>
                </li> -->
            </ul>
        </nav>
    </header>
    <h1>Milk Production Records</h1>
    <div class="container">
        <h3>Welcome, <?= htmlspecialchars($farmerData['username']) ?>!</h3>
        <form method="POST">
            <button type="submit" name="fetch_today">Fetch Today's Record</button>
            <button type="submit" name="see_all_details">See All Records</button>
        </form>
        
        <?php if (!empty($productionData)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Milk (L)</th>
                        <th>Fat (%)</th>
                        <th>SNF (%)</th>
                        <th>Rate</th>
                        <th>Total Rs</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productionData as $data): ?>
                        <tr>
                            <td><?= $data['production_date'] ?></td>
                            <td><?= $data['milk'] ?></td>
                            <td><?= $data['fat'] ?></td>
                            <td><?= $data['snf'] ?></td>
                            <td><?= $data['rate'] ?></td>
                            <td><?= $data['total_rs'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No milk production records found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
