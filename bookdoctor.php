<?php
session_start();
if (!isset($_SESSION["email"])) {
    header("Location: home.php");
    exit();
}

// Include database connection
include "index.php"; // Ensure this file correctly connects to the database

$successMessage = "";
$farmer_email = $_SESSION['email']; // Get logged-in farmer's email

// Retrieve farmer ID using email
$sql = "SELECT id FROM farmer WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $farmer_email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Error: No farmer found with this email.");
}

$farmer = $result->fetch_assoc();
$farmer_id = $farmer['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $disease = $_POST["disease"];
    $symptoms = $_POST["symptoms"];

    $query = "INSERT INTO appointments (farmer_id, disease, symptoms) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iss", $farmer_id, $disease, $symptoms);

    if ($stmt->execute()) {
        $successMessage = "Appointment booked successfully!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Appointment</title>
    <link rel="stylesheet" href="home.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            text-align: center;
        }
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            background: linear-gradient(135deg, #3ad5e5, #b367ed);
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            height: 70px;
        }

        .navbar a {
            text-decoration: none;
            color: white;
            font-size: 18px;
            font-weight: bold;
        }

        .nav-items {
            list-style: none;
            display: flex;
            gap: 20px;
        }

        .nav-items li {
            display: inline;
        }

        .nav-items a {
            text-decoration: none;
            color: white;
            font-size: 16px;
            transition: color 0.3s;
        }

        .nav-items a:hover {
            color: #ddd;
        }
        .container {
            width: 50%;
            margin: 50px auto;
            margin-top: 100px;
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        h2, h3 {
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        input, textarea {
            width: 80%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="submit"] {
            background: linear-gradient(135deg, #3ad5e5, #b367ed);
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background:#0056b3;
        }
        
        /* Popup styling */
        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            text-align: center;
            z-index: 1000;
            width: 300px;
            height: 80px;
            align-items: center;
        }
        .popup .close-btn {
            position: absolute;
            top: 5px;
            right: 10px;
            font-size: 20px;
            cursor: pointer;
            color: #555;
        }
        .popup .close-btn:hover {
            color: #000;
        }
        .popup p {
            margin: 0;
            font-size: 16px;
            color: #333;
        }
    </style>
</head>
<body>
<header>
    <nav class="navbar">
        <a href="home.php">DairyTrack</a>
        <ul class="nav-items">
            <li><a href="home.php">Home</a></li>
            <li><a href="#">About Us</a></li>
            <li><a href="logout.php" class="logout-btn">Logout</a></li>
        </ul>
    </nav>
</header>

<div class="container">
    <h2>Welcome, <?php echo $_SESSION["username"]; ?>!</h2>
    <h3>Book a Doctor Appointment</h3>
    <form method="POST" action="bookdoctor.php">
        <input type="text" name="disease" placeholder="Enter Disease" required><br>
        <textarea name="symptoms" placeholder="Describe Symptoms" required></textarea><br>
        <input type="submit" value="Book Appointment">
    </form>
</div>

<!-- Popup Message -->
<div class="popup" id="popupMessage">
    <span class="close-btn" onclick="closePopup()">&times;</span>
    <p id="popupText"></p>
</div>

<script>
    function showPopup(message) {
        document.getElementById("popupText").innerText = message;
        document.getElementById("popupMessage").style.display = "block";
    }

    function closePopup() {
        document.getElementById("popupMessage").style.display = "none";
    }

    // Show the popup if a success message is set in PHP
    <?php if (!empty($successMessage)) { ?>
        window.onload = function() {
            showPopup("<?php echo $successMessage; ?>");
        };
    <?php } ?>
</script>

</body>
</html>
