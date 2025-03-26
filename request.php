<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: home.php"); // Redirect if not logged in
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="home.css">
    <title>Loan Request Form</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Poppins', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

        /* Navbar Styles */
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            background-color: #007bff;
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

        /* Main Container */
        .container {
            width: 40%;
            margin: auto;
            background: white;
            padding: 30px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            margin-top: 100px; 
            text-align: center;
        }

        h2 {
            color: #007bff;
        }

        /* Form Styles */
        input, textarea {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        textarea {
            resize: vertical;
            height: 100px;
        }

        button {
            background: linear-gradient(135deg, #3ad5e5, #b367ed);
            color: white;
            padding: 12px;
            border: none;
            cursor: pointer;
            width: 100%;
            font-size: 18px;
            border-radius: 5px;
            transition: background 0.3s ease-in-out;
        }

        button:hover {
            background: linear-gradient(135deg, #17adde, #912ae0);
        }
        p{
            margin-top: 20px;
        }

        /* Responsive Design */
        @media screen and (max-width: 768px) {
            .container {
                width: 90%;
            }

            .navbar {
                flex-direction: column;
                height: 110px;
                text-align: center;
                padding: 10px;
            }

            .nav-items {
                flex-direction: column;
                gap: 10px;
            }
            .container{
                margin-top: 200px;
            }
        }
    </style>
    <script>
        function validateForm() {
            let reason = document.getElementById("reason").value;
            let amount = document.getElementById("amount").value;

            if (reason.trim() === "" || amount.trim() === "") {
                alert("All fields are required!");
                return false;
            }
            if (isNaN(amount) || parseFloat(amount) <= 0) {
                alert("Please enter a valid amount!");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>

<!-- Navbar -->
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

<!-- Form Container -->
<div class="container">
    <h2>Loan Request Form</h2>
    <p>Welcome, <b><?php echo $_SESSION['username']; ?></b></p>
    <form action="#" method="POST" onsubmit="return validateForm();">
        <label for="reason">Reason for Loan:</label>
        <textarea id="reason" name="reason" required></textarea>

        <label for="amount">Amount Needed:</label>
        <input type="text" id="amount" name="amount" required>

        <button type="submit">Submit Loan Request</button>
    </form>
</div>

</body>
</html>

<?php
// session_start();
if (!isset($_SESSION['email'])) {
    die("Unauthorized access.");
}

include "index.php";

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
$farmer_id = $farmer['id']; // Get the farmer ID

// Process loan request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reason = $conn->real_escape_string($_POST['reason']);
    $amount = floatval($_POST['amount']);

    $sql = "INSERT INTO loan (farmer_id, reason, amount) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isd", $farmer_id, $reason, $amount);

    if ($stmt->execute()) {
        echo "Loan request submitted successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}

$stmt->close();
$conn->close();
?>
