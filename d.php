<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: h1.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>
    <p>Your email: <?php echo $_SESSION['email']; ?></p>
    <a href="logout.php">Logout</a>
</body>
</html>
