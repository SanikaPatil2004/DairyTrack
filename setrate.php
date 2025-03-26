<?php
// Database connection
include "index.php";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['rate'])) {
    $rates = $_POST['rate'];
    foreach ($rates as $fat => $snf_values) {
        foreach ($snf_values as $snf => $rate) {
            $fat = floatval($fat);
            $snf = floatval($snf);
            $rate = floatval($rate);

            $sql = "INSERT INTO milk_rates (fat_percentage, snf_percentage, rate) 
                    VALUES (?, ?, ?) 
                    ON DUPLICATE KEY UPDATE rate = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ddsd", $fat, $snf, $rate, $rate);
            $stmt->execute();
        }
    }
    echo "<p style='color: green;'>Milk rates updated successfully.</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Milk Rate Setting</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e3f2fd;
            text-align: center;
        }
        .container {
            width: 60%;
            margin: 50px auto;
            padding: 20px;
            background: #bbdefb;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #e1f5fe;
        }
        th, td {
            padding: 10px;
            border: 1px solid #90caf9;
        }
        th {
            background: #64b5f6;
            color: white;
        }
        button {
            margin-top: 20px;
            padding: 10px 20px;
            background: #1976d2;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        button:hover {
            background: #1565c0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Set Milk Rates by Fat and SNF Percentage</h2>
        <form action="" method="POST">
            <table>
                <tr>
                    <th>Fat Percentage</th>
                    <th>SNF Percentage</th>
                    <th>Rate (per liter in INR)</th>
                </tr>
                <?php
                    $fat_values = [4.0,5.0,6.0,7.0,8.0,9.0,10.0];
                    $snf_values = [8.0, 9.0, 10.0];
                    foreach ($fat_values as $fat) {
                        foreach ($snf_values as $snf) {
                            echo "<tr>";
                            echo "<td>$fat%</td>";
                            echo "<td>$snf%</td>";
                            echo "<td><input type='number' name='rate[$fat][$snf]' step='0.1' required></td>";
                            echo "</tr>";
                        }
                    }
                ?>
            </table>
            <button type="submit">Save Rates</button>
        </form>
    </div>
</body>
</html>

<?php $conn->close(); ?>
