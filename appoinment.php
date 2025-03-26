<?php
session_start();
include("index.php"); // Database connection

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    die("<p class='error'>You must be logged in to view your appointments.</p>");
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

$username = $_SESSION['username']; // Get username from session
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- jQuery for AJAX -->
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background: #e3f2fd;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background: #007BFF;
            color: white;
        }
        tr:nth-child(even) {
            background: #f2f2f2;
        }
        header {
            background: #007BFF;
            padding: 15px;
            color: white;
            text-align: center;
            font-size: 20px;
        }
        nav {
            text-align: center;
            margin-top: 10px;
        }
        nav a {
            text-decoration: none;
            color: white;
            font-weight: bold;
            margin: 0 15px;
        }
        nav ul {
            list-style-type: none;
            padding: 0;
        }
        nav ul li {
            display: inline;
            margin: 0 15px;
        }
    </style>
</head>
<body>

<header>
    <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
    <nav>
        <a href="home.php">DairyTrack</a>
        <ul>
            <li><a href="home.php">Home</a></li>
            <li><a href="#">About Us</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</header>

<h2>My Appointments</h2>

<div class="container">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Disease</th>
                <th>Symptoms</th>
                <th>Appointment Date</th>
            </tr>
        </thead>
        <tbody id="appointmentsTable">
            <!-- Data will be loaded here using AJAX -->
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function () {
        function loadAppointments() {
            $.ajax({
                url: "fetch_appointments.php",
                method: "GET",
                success: function (data) {
                    $("#appointmentsTable").html(data);
                },
                error: function () {
                    alert("Error loading appointments.");
                }
            });
        }

        loadAppointments(); // Load appointments on page load
    });
</script>

</body>
</html>
<?php
session_start();
include("index.php"); // Database connection

// Check if user is logged in
if (!isset($_SESSION['farmer_id'])) {
    die("Unauthorized access");
}

$farmer_id = $_SESSION['farmer_id'];

// Fetch appointment data for the logged-in farmer
$sql = "SELECT id, disease, symptoms, appointment_date FROM appointments WHERE farmer_id = ? ORDER BY appointment_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $farmer_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['disease']}</td>
                <td>{$row['symptoms']}</td>
                <td>{$row['appointment_date']}</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='4'>No appointments found.</td></tr>";
}

$conn->close();
?>
