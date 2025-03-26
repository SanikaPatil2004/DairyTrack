<?php
session_start(); 
include("index.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Milk Dairy Management</title>
    <link rel="stylesheet" href="home.css">
    <style>
        #admin{
            color: white;
            margin-left: 150px;
        }
    </style>
</head>
<body>
<header>
    <nav class="navbar">
        <a href="#" id="a" style="font-weight: bold;">DairyTrack</a>
        <p id="admin">Admin Panel</p>
        <ul class="nav-items">
            <li><a href="#">Home</a></li>
            <li><a id="loginBtn" href="#">Login</a></li>
            <!-- <li>
                    <select id="languageSelect">
                        <option value="English">English</option>
                        <option value="Hindi">Hindi</option>
                        <option value="Marathi">Marathi</option>
                    </select>
            </li> -->
        </ul>
    </nav>
</header>

<section class="hero">
    <img src="cow.jpeg" alt="Milk Production" class="hero-image">
    <h1>Get All Information About Your Milk Production</h1>
</section>
    <div class="container">
    <div class="chatbox">
        <div class="chatbox__support">
            <div class="chatbox__header">
                <div class="chatbox__image--header">
                    <img src="chatbox-icon.png" alt="image">
                </div>
                <div class="chatbox__content--header">
                    <h4 class="chatbox__heading--header">Chat support</h4>
                    <p class="chatbox__description--header">How can I help you?</p>
                </div>
            </div>
            <div class="chatbox__messages">
                <div></div>
            </div>
            <div class="chatbox__footer">
                <input type="text" placeholder="Write a message...">
                <button class="chatbox__send--footer send__button">Send</button>
            </div>
        </div>
        <div class="chatbox__button">
            <button><img src="chatbox-icon.svg" /></button>
        </div>
    </div>
</div>

<div id="popupForm" class="popup">
    <div class="popup-content">
        <span class="close-btn" id="closePopup">&times;</span>
        <div>
            <button id="loginTab" class="tab active">Login</button>
            <button id="registerTab" class="tab">Register</button>
        </div>
        
        <form method="POST" id="loginForm" class="form" action="#">
            <h3>Login</h3>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input class="button" type="submit" value="Login" name="login">                   
        </form>
        
        <form method="POST" id="registerForm" class="form hidden" action="#">
            <h3>Register</h3>
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm" placeholder="Confirm Password" required>
            <input type="number" name="mobno" placeholder="Mobile Number" required>
            <input class="button" type="submit" value="Register" name="register">
        </form>
    </div>
</div>

<div id="popupMessage" class="popup-message hidden">
    <div class="popup-message-content">
        <span class="close-btn" id="closePopupMessage">&times;</span>
        <p id="popupMessageText"></p>
    </div>
</div>

<footer>
    <p>&copy; 2024 Milk Dairy Management System. All rights reserved.</p>
</footer>
<script src="./app.js"></script>
<script src="home.js"></script>
</body>
</html>
<?php
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
        echo "<script>window.location.href='admindash.php';</script>";
        exit;
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
