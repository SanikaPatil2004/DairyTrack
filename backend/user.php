<?php
include("index.php");

// SQL query to fetch data
$sql = "SELECT id, username, email, mobno, profile_photo, address FROM farmer";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/home.css">
    <title>Farmer Data</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            
            color: #fff;
            text-align: center;
            margin: 0;
            padding: 0 ;
        }
        h2 {
            margin-bottom: 20px;
            color:#3ad5e5;
            font-size: 30px;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
        }
        table {
            width: 80%;
            margin: auto;
            border-collapse: collapse;
            background: rgba(255, 255, 255, 0.2);
            background: linear-gradient(135deg, #3ad5e5, #b367ed);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        th, td {
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 10px;
            text-align: center;
        }
        th {
            background: rgba(255, 255, 255, 0.3);
        }
        tr:hover {
            background: rgba(255, 255, 255, 0.4);
        }
        img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: 2px solid white;
        }
    </style>
</head>
<body>
<header>
    <nav class="navbar">
        <a href="#" id="a" style="font-weight: bold;">DairyTrack</a>
        <ul class="nav-items">
            <li><a href="#">Home</a></li>
            <li><a href="#">About Us</a></li>
            <li><a id="loginBtn" href="#">Login</a></li>
        </ul>
    </nav>
</header>
<h2>Farmer Data</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Email</th>
        <th>Mobile No</th>
        <th>Profile Photo</th>
        <th>Address</th>
    </tr>
    <?php
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td><img src='{$row['profile_photo']}' alt='Profile Photo'></td>
                    <td>{$row['username']}</td>
                    <td>{$row['email']}</td>
                    <td>{$row['mobno']}</td>
                    <td>{$row['address']}</td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No data found</td></tr>";
    }
    ?>
</table>

</body>
</html>

<?php
$conn->close();
?>
