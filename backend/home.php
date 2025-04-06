<?php
// ✅ Keep all PHP logic at the bottom
session_start();
include("index.php");

// Handle Registration
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];
    $mobno = $_POST['mobno'];

    if ($password !== $confirm) {
        echo "<script>showPopupMessage('Passwords do not match');</script>";
    } else {
        $checkUser = "SELECT * FROM farmer WHERE email='$email'";
        $result = $conn->query($checkUser);

        if ($result->num_rows > 0) {
            echo "<script>showPopupMessage('User already exists');</script>";
        } else {
            $sql = "INSERT INTO farmer (username, email, pass, confpass, mobno) VALUES ('$username', '$email', '$password', '$confirm', '$mobno')";
            if ($conn->query($sql) === TRUE) {
                echo "<script>showPopupMessage('Registration successful! Please login.');</script>";
                // $_SESSION['username'] = $row['username'];
                // $_SESSION['email'] = $row['email'];
                // header("Location: dashboard.php");
                // exit;
            } else {
                echo "<script>showPopupMessage('Error: " . $conn->error . "');</script>";
            }
        }
    }
}

// Handle Login
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM farmer WHERE email='$email' AND pass='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['username'] = $row['username'];
        $_SESSION['email'] = $row['email'];
        echo "<script>window.location.href='dashboard.php';</script>";
        // header("Location: http://localhost/MilkDairyMgmt/frontend/dashboard.php");
        exit();
        
        


        exit;
        // header("Location: dashboard.php");
        // exit;
    } else {
        echo "<script>showPopupMessage('Invalid username or password');</script>";
    }
}

// Handle Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}
?>
