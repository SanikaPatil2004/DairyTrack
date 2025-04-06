<?php
include("index.php");
$sql = "SELECT loan.id, farmer.username AS farmer_name, loan.reason, loan.amount, loan.request_date 
        FROM loan 
        JOIN farmer ON loan.farmer_id = farmer.id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/home.css">
    <title>Loan Requests</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        button {
            padding: 5px 10px;
            margin: 2px;
            cursor: pointer;
        }
        .approve {
            background-color: green;
            color: white;
            border: none;
        }
        .deny {
            background-color: red;
            color: white;
            border: none;
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
<h2>Loan Requests</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Farmer Name</th>
        <th>Reason</th>
        <th>Amount</th>
        <th>Request Date</th>
        <th>Action</th>
    </tr>

    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['farmer_name']}</td>
                    <td>{$row['reason']}</td>
                    <td>{$row['amount']}</td>
                    <td>{$row['request_date']}</td>
                    <td>
                        <form action='loan_action.php' method='POST' style='display:inline;'>
                            <input type='hidden' name='loan_id' value='{$row['id']}'>
                            <button type='submit' name='action' value='approve' class='approve'>Approve</button>
                        </form>
                        <form action='loan_action.php' method='POST' style='display:inline;'>
                            <input type='hidden' name='loan_id' value='{$row['id']}'>
                            <button type='submit' name='action' value='deny' class='deny'>Deny</button>
                        </form>
                    </td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No loan requests found</td></tr>";
    }
    $conn->close();
    ?>
</table>

</body>
</html>
