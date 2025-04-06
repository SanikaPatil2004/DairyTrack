<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="/home.css">
    <script defer src="dashboard.js"></script>
    
    <?php
    session_start();
    if (!isset($_SESSION['username'])) {
        header("Location: home.php");
        exit;
    }
    ?>
    <style>
       /* General Styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}


.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #007bff;
    padding: 15px;
    position: fixed; 
    top: 0;
    left: 0;
    width: 100%;
    z-index: 1000; 
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
    background: linear-gradient(135deg, #17adde, #912ae0);
}

/* Add some margin to the main content so it doesn't overlap the fixed navbar */
.dashboard-container {
    display: flex;
    width: 100%;
    margin-top: 60px; /* Adjust based on navbar height */
}



/* Main Content */
.content {
    flex: 1;
    padding: 30px;
    background: #e3f2fd;
}
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            height: 100vh;
            background: #e3f2fd;
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            background: linear-gradient( #3ad5e5, #b367ed);
            color: white;
            padding: 20px;
            display: flex;
            flex-direction: column;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.2);
        }
        
        .sidebar h2 {
            text-align: center;
            font-size: 22px;
            margin-bottom: 20px;
            margin-top: 20px;
        }
        
        .sidebar ul {
            list-style: none;
            padding: 0;
        }
        
        .sidebar ul li {
            padding: 12px;
            margin: 5px 0;
            border-radius: 5px;
            transition: background 0.3s ease;
            cursor: pointer;
        }
        
        .sidebar ul li a {
            text-decoration: none;
            color: white;
            font-size: 16px;
            display: block;
        }
        
        .sidebar ul li:hover {
            background: linear-gradient(135deg, #17adde, #912ae0);
        }
        
        /* Main Content */
        .dashboard-container {
            display: flex;
            width: 100%;
            height: 100%;
        }
        
        .content {
            flex: 1;
            padding: 30px;
            background: #e3f2fd;
        }
        
        .content h1 {
            color: #0d47a1;
            font-size: 26px;
            margin-bottom: 20px;
            margin-left: 400px;
        }
        
        #contentDisplay {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
            height: 200px;
            /*width: 1000px; */
            background: url('header.jpg') no-repeat center center/cover;
            margin-left: 100px;
        }
        .profile-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

/* Profile section: align image and username in a row */
.profile {
    display: flex;
    align-items: center;
    gap: 10px;
    color: white;
    font-weight: bold;
}

/* Profile image styling */
.profile-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid white;
}

/* Username styling */
.username {
    font-size: 16px;
}

.profile-details {
    display: flex;
    flex-direction: column;
    align-items: center;
}


/* Styled Edit Button */

/* Modal Styling */
.modal {
    display: none; /* Hidden initially */
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-content {
    background: white;
    padding: 25px;
    border-radius: 10px;
    width: 350px;
    box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
    text-align: center;
    position: relative;
    animation: fadeIn 0.3s ease-in-out;
}

/* Close button */
.close {
    position: absolute;
    top: 10px;
    right: 15px;
    font-size: 24px;
    cursor: pointer;
    color: #555;
}

.close:hover {
    color: black;
}

/* Input fields */
input[type="text"], input[type="file"] {
    width: 100%;
    padding: 8px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
}

/* Save button */
.save-btn {
    background: linear-gradient(135deg, #3ad5e5, #b367ed);
    color: white;
    border: none;
    padding: 8px 15px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    transition: 0.3s;
}

.save-btn:hover {
    background: linear-gradient(135deg, #17adde, #912ae0);
}
/* Profile container to keep everything together */
.profile-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

/* Profile section: align image and username in a row */
.profile {
    display: flex;
    align-items: center;
    gap: 10px;
    color: white;
    font-weight: bold;
}

/* Profile image styling */
.profile-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid white;
}

/* Username styling */
.username {
    font-size: 16px;
}


/* Smooth fade-in animation */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: scale(0.9);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}
@media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: 250px;
                position: fixed;
            }

            .content {
                margin-left: 0;
                padding: 20px;
            }

            .sidebar.active {
                transform: translateX(0);
            }
        }

        /* Toggle Button */
        .menu-btn {
            display: none;
            font-size: 24px;
            background: none;
            border: none;
            color: white;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .menu-btn {
                display: block;
                position: absolute;
                left: 15px;
                top: 15px;
            }
        }
        .info-boxes{
            margin-left: 100px;
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

<div id="editProfileModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeEditProfile()">&times;</span>
        <h2>Edit Profile</h2>
        <form action="#" method="POST" enctype="multipart/form-data">
            <label>Username:</label>
            <input type="text" name="username" value="<?php echo $_SESSION['username']; ?>" required>

            <label>Upload Profile Picture:</label>
            <input type="file" name="profile_image" accept="image/*">
            
            <button type="submit" class="save-btn">Save Changes</button>
        </form>
    </div>
</div>
</div>
    <div class="dashboard-container">
        <aside class="sidebar">
        <div class="profile-container">
    <div class="profile">
        <img src="<?php echo $_SESSION['profile_image'] ?? 'default.png'; ?>" alt="Profile" class="profile-icon">
        <span class="username"><?php echo $_SESSION['username']; ?></span>
    </div>
    <!-- <button class="edit-btn" onclick="openEditProfile()">✎ Edit Profile</button> -->
</div>
            <!-- <img src="profile.png" alt="Profile" class="profile-icon"> -->
            
            <h2>Dashboard</h2>
            <ul>
                <li><a href="slip.php" id="milkTab">MilkDetails</a></li>
                <li><a href="buffalo.php" id="milkTab">BuffaloDeatil</a></li>
                <li><a href="cow.php" id="milkTab">CowDetails</a></li>
                <li><a href="request.php" id="loanTab">Request Loan</a></li>
                <li><a href="bookdoctor.php" id="doctorTab">Book Doctor</a></li>
                <li><a href="progress.php" id="doctorTab">Progress</a></li>
                <li  class="edit-btn" onclick="openEditProfile()">Edit Profile</li>
            </ul>
        </aside>
        
        <main class="content">
            <h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1>
            <div id="contentDisplay"></div>
            <section class="info-boxes">
        <div class="box">
            <h2>How to increase fat?</h2>
            <p> Fiber helps to improve the digestion and absorption of nutrients in cows, which can result in higher fat production in milk. Good quality roughage (such as hay or silage) is crucial.
            Add energy-rich feeds like corn silage, oats, barley, or wheat can boost milk fat content. Carbohydrates such as sugars and starches play a key role in fat production.
            </p>

        </div>
        <div class="box">
            <h2>How to keep animals healthy?</h2>
            <p> Feed your animals a balanced diet that meets their specific nutritional needs. This includes adequate amounts of carbohydrates, proteins, fats, vitamins, and minerals. Consult a nutritionist or veterinarian to develop a tailored feeding program.
                Ensure that animals have access to fresh, clean water at all times. Dehydration can cause serious health problems.
                Use high-quality feed, including roughage (hay, silage) and grains, that is free from mold, toxins, and contaminants.</p>
        </div>
        <div class="box">
            <h2>How to increase milk production?</h2>
            <p> Milk production is highly dependent on water intake. Ensure that animals have access to fresh, clean water at all times. Dehydration can lead to reduced milk yield and lower overall health.
                Cows, for example, may drink up to 30-50 liters of water per day, especially when producing large quantities of milk. Make sure they are drinking enough water to support milk production.</p>
        </div>
    </section>
        </main>
    </div>
    
    <script>
    
    function openEditProfile() {
        document.getElementById("editProfileModal").style.display = "flex";
    }

    function closeEditProfile() {
        document.getElementById("editProfileModal").style.display = "none";
    }
    // Close the modal when clicking outside the modal-content
    window.onclick = function(event) {
    let modal = document.getElementById("editProfileModal");
    if (event.target === modal) {
        modal.style.display = "none";
    }
    };
    // Ensure modal is closed when page loads
    document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("editProfileModal").style.display = "none";
    });
</script>

</body>
</html>
<?php
include "index.php"; // Ensure you have a database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $profileImage = $_FILES['profile_image'];

    // File upload handling
    if ($profileImage['name']) {
        $targetDir = "uploads/";
        $fileName = basename($profileImage["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        // Allow only image formats
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array(strtolower($fileType), $allowedTypes)) {
            if (move_uploaded_file($profileImage["tmp_name"], $targetFilePath)) {
                // Update database with new profile image
                $sql = "UPDATE users SET username = ?, profile_image = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssi", $username, $targetFilePath, $_SESSION['user_id']);
                $stmt->execute();

                $_SESSION['username'] = $username;
                $_SESSION['profile_image'] = $targetFilePath;

                header("Location: dashboard.php");
                exit;
            } else {
                echo "Error uploading file.";
            }
        } else {
            echo "Invalid file type.";
        }
    } else {
        // Update only username if no file is uploaded
        $sql = "UPDATE users SET username = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $username, $_SESSION['user_id']);
        $stmt->execute();

        $_SESSION['username'] = $username;
        header("Location: dashboard.php");
        exit;
    }
}
?>

