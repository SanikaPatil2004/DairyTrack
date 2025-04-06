<?php
session_start(); // Start session to get logged-in user info

// Include the database connection
include("index.php");

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    die("<p class='error'>You must be logged in to view your records.</p>");
}

$email = $_SESSION['email']; // Access email directly from the session

// Fetch farmer ID using email (if not already set in session)
if (!isset($_SESSION['farmer_id'])) {
    $query = "SELECT id FROM farmer WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['farmer_id'] = $row['id'];
    } else {
        die("Error: Farmer ID not found.");
    }
}

$farmer_id = $_SESSION['farmer_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cow Species Form</title>
    <link rel="stylesheet" href="/home.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background: #e3f2fd;
            text-align: center;
        }
        .container {
            margin: 20px auto;
            max-width: 500px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .species-group {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
        }
        .btn {
            background: linear-gradient(135deg, #3ad5e5, #b367ed);
            color: white;
            padding: 8px 12px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .btn-remove {
            background: red;
        }
        .success {
            color: green;
            font-weight: bold;
            margin-top: 20px;
        }
        .error {
            color: red;
            font-weight: bold;
            margin-top: 20px;
        }
        .species-list {
            margin-top: 20px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 5px;
            text-align: left;
            display: inline-block;
        }
        #h2{
            margin-top: 20px;
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

<!-- Welcome Message -->
<h2 id="h2">Welcome, <?php echo $_SESSION['username']; ?>!</h2>

<div class="container">
    <h2 style="margin-bottom:20px;">Enter Cow Details</h2>
    <form action="" method="POST"> 
        <input type="hidden" name="farmer_id" value="<?php echo $farmer_id; ?>"> 
        
        <div id="species-container">
            <div class="species-group">
                <input type="text" name="species[]" placeholder="Species Name" required></br>
                <input type="number" name="count[]" placeholder="Count" required>
                <button type="button" class="btn btn-add">+</button>
            </div>
        </div>

        <button type="submit" name="submit" class="btn">Submit</button>
    </form>
</div>
<footer style="margin-top:30px;">
    <p>&copy; 2024 Milk Dairy Management System. All rights reserved.</p>
</footer>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const container = document.getElementById("species-container");

        document.addEventListener("click", function (e) {
            if (e.target.classList.contains("btn-add")) {
                let newField = document.createElement("div");
                newField.classList.add("species-group");
                newField.innerHTML = `
                    <input type="text" name="species[]" placeholder="Species Name" required>
                    <input type="number" name="count[]" placeholder="Count" required>
                    <button type="button" class="btn btn-remove">-</button>
                `;
                container.appendChild(newField);
            }

            if (e.target.classList.contains("btn-remove")) {
                e.target.parentElement.remove();
            }
        });
    });
</script>

<?php
if (isset($_POST['submit'])) {
    // Validate form submission
    if (!isset($_POST['species']) || !isset($_POST['count'])) {
        echo "<p class='error'>Error: Form fields missing!</p>";
        exit;
    }

    $speciesArray = $_POST['species'];
    $countArray = $_POST['count'];

    if (empty($speciesArray) || empty($countArray)) {
        echo "<p class='error'>Error: Please fill out all fields.</p>";
        exit;
    }

    $errors = [];
    $insertedSpecies = [];

    for ($i = 0; $i < count($speciesArray); $i++) {
        $species = $conn->real_escape_string($speciesArray[$i]);
        $count = (int) $countArray[$i];

        if ($count <= 0) {
            $errors[] = "Count must be greater than 0 for species: $species.";
            continue;
        }

        // Use prepared statements for security
        $sql = "INSERT INTO cow_species (farmer_id, species, count) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isi", $farmer_id, $species, $count);
        
        if ($stmt->execute()) {
            $insertedSpecies[] = "$species - $count";
        } else {
            $errors[] = "Error inserting $species: " . $conn->error;
        }
    }

    if (empty($errors)) {
        echo "<p class='success'>Data successfully inserted!</p>";

        // Display entered species list
        if (!empty($insertedSpecies)) {
            echo "<div class='species-list'><strong>Entered Species:</strong><br>" . implode("<br>", $insertedSpecies) . "</div>";
        }
    } else {
        echo "<p class='error'>" . implode("<br>", $errors) . "</p>";
    }
}
?>

</body>
</html>
