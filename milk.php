<?php
// Database connection
include("index.php");

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch milk rates from the database
$milkRates = [];
$sql = "SELECT * FROM milk"; // Assuming your 'milk' table contains rate info
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $milkRates[] = $row;
    }
}

// Handle form submission to save milk production
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST as $key => $value) {
        if (preg_match('/milk_(\d+)/', $key, $matches)) {
            $farmer_id = $matches[1];
            $milk = $_POST["milk_$farmer_id"];
            $fat = $_POST["fat_$farmer_id"];
            $snf = $_POST["snf_$farmer_id"];
            $total_rs = $_POST["total_rs_$farmer_id"];
            
            // Determine the rate based on the fat percentage
            $rate = 0;
            foreach ($milkRates as $rateInfo) {
                if ($fat > $rateInfo['min_fat'] && $fat <= $rateInfo['max_fat']) {
                    $rate = $rateInfo['rate'];
                    break;
                }
            }

            $date = date("Y-m-d");

            // Insert milk production data along with the rate
            $sql = "INSERT INTO milkproduction (farmer_id, milk, fat, snf, production_date, rate, total_rs) VALUES 
                    ($farmer_id, $milk, $fat, $snf, '$date', $rate, $total_rs)";

            $conn->query($sql);
        }
    }
    echo "<script>
            alert('Data saved successfully!');
            localStorage.setItem('lastSavedDate', '" . date('Y-m-d') . "');
            </script>";
}

// Fetch farmers
$farmers = [];
$sql = "SELECT id, username FROM farmer";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $farmers[] = $row;
    }
}

// Fetch existing milk production records for the current day
$currentDate = date("Y-m-d");
$milkProduction = [];
$sql = "SELECT * FROM milkproduction WHERE production_date = '$currentDate'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $milkProduction[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Milk Production</title>
    <style>
body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f4f7fc;
        color: #333;
        height: 100%;
        overflow: auto; /* Allow scrolling when content overflows */
        }

.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #007bff;
    padding: 15px;
    
}


.nav-items {
    list-style: none;
    display: flex;
    gap: 15px;
}

.nav-items li a {
    color: white;
    text-decoration: none;
    padding: 10px 15px;
    border-radius: 5px;
}

.nav-items li a:hover {
    background-color: #0056b3;
}

#languageSelect {
    padding: 5px;
    border-radius: 10px;
}
#a{
    list-style: none;
    font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
    font-weight: 500;
    display: flex;
}
#a {
    color: white;
    text-decoration: none;
    padding: 10px 15px;
    border-radius: 5px;
}

#a:hover {
    background-color: #0056b3;
}
h1{
    align-items: center;
    align-content: center;
    margin-left: 600px;
}



        main {
            padding-bottom: 100px; /* Ensure space for footer */
        }

        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            border: 1px solid #ccc;
            background-color: white;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        input[type="number"], input[type="submit"] {
            padding: 8px 12px;
            margin: 5px 0;
            border-radius: 4px;
            border: 1px solid #ddd;
            width: 100%;
        }

        input[type="number"]:disabled {
            background-color: #e0e0e0;
            cursor: not-allowed;
        }

        button.saveButton {
            padding: 8px 16px;
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }

        button.saveButton:disabled {
            background-color: #ccc;
        }

        footer {
            background-color: #007bff;
            color: white;
            padding: 10px 0;
            text-align: center;
            position: fixed;
            width: 100%;
            bottom: 0;
        }

        footer p {
            margin: 0;
        }
    </style>
</head>
<body>

<header>
        <nav class="navbar">
            <a href="admin.php" id="a"style="font-weight: bold;">MilkDairyManagement</a>
            <ul class="nav-items">
                <li><a href="home.php">Home</a></li>
                <li><a href="setrate.php">SetRate</a></li>
                <li><a href="#">About Us</a></li>
                </li>
                <li>
                    <select id="languageSelect">
                        <option value="English">English</option>
                        <option value="Hindi">Hindi</option>
                        <option value="Marathi">Marathi</option>
                    </select>
                </li>
            </ul>
        </nav>
    </header>

<main>
    <h1>Milk Production Entry</h1>
    <form id="milkProductionForm" method="POST">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Farmer Name</th>
                    <th>Milk (litre)</th>
                    <th>Fat (%)</th>
                    <th>SNF (%)</th>
                    <th>Total Rs</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($farmers as $farmer): ?>
                    <?php
                    // Check if data for the current farmer exists for today
                    $farmerHasDataToday = false;
                    foreach ($milkProduction as $production) {
                        if ($production['farmer_id'] == $farmer['id']) {
                            $farmerHasDataToday = true;
                            break;
                        }
                    }
                    ?>
                    <tr data-farmer-id="<?= $farmer['id'] ?>" class="farmer-row" <?php if ($farmerHasDataToday) echo 'style="background-color: #d6eaf8;"' ?>>
                        <td><?= $farmer['id'] ?></td>
                        <td><?= htmlspecialchars($farmer['username']) ?></td>
                        <?php if ($farmerHasDataToday): ?>
                            <td colspan="4">Data already entered for today</td>
                            <td><button type="button" class="saveButton" disabled>Saved</button></td>
                        <?php else: ?>
                            <td><input type="number" name="milk_<?= $farmer['id'] ?>" min="0" step="0.1"  class="milkInput"></td>
                            <td><input type="number" name="fat_<?= $farmer['id'] ?>" min="0" step="0.1"  class="fatInput"></td>
                            <td><input type="number" name="snf_<?= $farmer['id'] ?>" min="0" step="0.1" ></td>
                            <td>
                                <input type="number" name="total_rs_<?= $farmer['id'] ?>" readonly class="totalRs" style="border: none; background: transparent;">
                            </td>
                            <td>
                                <button type="submit" class="saveButton" id="saveButton_<?= $farmer['id'] ?>">Save</button>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </form>
</main>

<footer>
    <p>&copy; 2024 Milk Dairy Management System. All rights reserved.</p>
</footer>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const milkRates = <?php echo json_encode($milkRates); ?>;
        const rows = document.querySelectorAll("tbody tr");

        rows.forEach(row => {
            const milkInput = row.querySelector(".milkInput");
            const fatInput = row.querySelector(".fatInput");
            const totalRsInput = row.querySelector(".totalRs");
            const saveButton = row.querySelector(".saveButton");
            const farmerId = row.dataset.farmerId;

            if (!milkInput || !fatInput || !totalRsInput) return;

            const calculateTotal = () => {
                const milk = parseFloat(milkInput.value) || 0;
                const fat = parseFloat(fatInput.value) || 0;

                let rate = 0;
                milkRates.forEach(rateInfo => {
                    if (fat > rateInfo.min_fat && fat <= rateInfo.max_fat) {
                        rate = rateInfo.rate;
                    }
                });

                const total = milk * rate;
                totalRsInput.value = total.toFixed(2);
            };

            milkInput.addEventListener("input", calculateTotal);
            fatInput.addEventListener("input", calculateTotal);
        });
    });
</script>

</body>
</html>     