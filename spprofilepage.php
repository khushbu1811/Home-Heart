<?php
session_start();

// DEBUG: Show current session phone
if (!isset($_SESSION['phone'])) {
    echo "कोई उपयोगकर्ता लॉग इन नहीं है। कृपया पहले लॉग इन करें।";
    exit();
}

$phone = $_SESSION['phone'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "home_and_heart";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user profile based on session phone
$stmt = $conn->prepare("SELECT * FROM spprofile WHERE phone = ?");
$stmt->bind_param("s", $phone);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $profile = $result->fetch_assoc();
} else {
    echo "इस उपयोगकर्ता के लिए कोई डेटा नहीं मिला।";
    exit();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home&Heart - Service Provider Profile Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #795548;
            color: #3e3e3e;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #795548;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 5px 15px;
            height: 45px;
            flex-wrap: wrap;
        }

        .logo-container {
            display: flex;
            align-items: center;
        }

        .logo img {
            height: 40px;
        }

        .site-name {
            color: white;
            font-size: 23px;
            font-weight: bold;
            margin-left: 20px;
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
            justify-content: center;
            padding: 0;
        }

        nav ul li a {
            color: white;
            font-size: 20px;
            text-decoration: none;
        }

        /* Flash */
        .running-text-container {
            overflow: hidden;
            white-space: nowrap;
            background-color: #ffffff;
            padding: 10px 0;
            font-weight: bold;
            text-align: center;
        }

        .running-text {
            display: inline-block;
            color: red;
            font-size: 18px;
            animation: marquee 10s linear infinite;
        }

        @keyframes marquee {
            from {
                transform: translateX(100%);
            }

            to {
                transform: translateX(-100%);
            }
        }

        main {
            display: flex;
            justify-content: center;
            /* centers horizontally */
            align-items: center;
            /* centers vertically (optional) */
            flex-direction: column;
            /* keeps things stacked vertically */
            min-height: 80vh;
            /* adjusts height of main section */
        }


        .form-container {
            width: 90%;
            max-width: 500px;
            background-color: white;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            border: 2px solid #afafaf;
            margin: 30px 0;
            /* 20px space at top and bottom */
        }

        .container {
            display: flex;
            align-items: center;
            /* Centers vertically */
            padding-left: 20%;
        }

        h2 {
            text-align: center;
            color: #795548;
        }

        .form-heading {
            text-align: center;
            color: #795548;
            font-size: 16px;
            margin-bottom: 20px;
        }

        .profile-image-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .profile-image {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #795548;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .profile-photo {
            width: 180px;
            height: 180px;
            object-fit: cover;
            object-position: top center;
            border-radius: 50%;
            display: block;
            margin: 0 auto 20px auto;
            border: 3px solid #795548;
            background-color: #f0f0f0;
        }

        button {
            background-color: #8C6A5C;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 18px;
        }

        button:hover {
            background-color: #705248;
        }

        /* Footer */
        footer {
            background-color: #4e342e;
            color: white;
            padding: 10px;
            margin-top: 20px;
            text-align: center;
            font-size: clamp(14px, 2.5vw, 20px);
        }

        footer ul {
            list-style: none;
            padding: 0;
        }

        footer ul li {
            display: inline;
            margin-right: 15px;
            font-size: clamp(14px, 2.2vw, 18px);
        }

        footer ul li a {
            color: white;
            text-decoration: none;
        }

        footer p {
            font-size: clamp(12px, 2vw, 16px);
        }
    </style>
</head>
<body>
<header>
        <div class="logo-container">
            <div class="logo"><img src="home&heartlogo.jpg" alt="Home&Heart Logo"></div>
            <div class="site-name">Home&Heart</div>
        </div>
        <nav>
            <ul>
                <li><a href="sphomepage.php"><i class="fas fa-home"></i></a></li>
                <li><a href="spservicespage.php"><i class="fas fa-handshake"></i></a></li>
                <li><a href="spnotificationspage.php"><i class="fas fa-bell"></i></a></li>
                <li><a href="spcontactuspage.php"><i class="fas fa-phone"></i></a></li>
                <li><a href="spprofilepage.php"><i class="fas fa-user"></i></a></li>
            </ul>
        </nav>
    </header>

    <!-- Flashing Text -->
    <div class="running-text-container">
        <div class="running-text">!!! Only Limited To Andheri !!! </div>
    </div>

    <main>
        <div class="form-container">
            <div class="form-heading">
                <h2>सेवा प्रदाता प्रोफ़ाइल</h2>
            </div>

        <div class="profile-image-wrapper">
            <img src="<?php echo $profile['profile_photo']; ?>" alt="Profile Picture" class="profile-photo"><br><br>
        </div><br><br>
        <p><strong>SP ID :</strong> <?php echo $profile['sp_id']; ?></p>
        <p><strong>पहला नाम :</strong> <?php echo $profile['fname']; ?></p>
        <p><strong>आखिरी नाम :</strong> <?php echo $profile['lname']; ?></p>
        <p><strong>फ़ोन नंबर :</strong> <?php echo $profile['phone']; ?></p>
        <p><strong>लिंग :</strong> <?php echo $profile['gender']; ?></p>
        <p><strong>उम्र :</strong> <?php echo $profile['age']; ?></p>
        <p><strong>पता :</strong> <?php echo $profile['address']; ?></p>
        <!-- <p><strong>पहचान प्रमाण :</strong></p>
        <img src="<?php echo $profile['identity_proof']; ?>" alt="Identity Proof" width="200px"><br> -->
        <a href="spprofile.php"><button>प्रोफ़ाइल संपादित करें</button></a>
    </div>
    </main>

    <!-- Footer -->
    <footer>
        <ul>
            <li><a href="sptermsandconditions.php">नियम और शर्तें</a></li>
            <li><a href="spprivacypolicy.php">गोपनीयता नीति</a></li>
            <li><a href="spcancellationandrefund.php">रद्दीकरण और वापसी</a></li>
            <li><a href="spcontactuspage.php">संपर्क करें</a></li>
        </ul>
        <p>© 2025 Home&Heart. सभी अधिकार सुरक्षित।</p>
    </footer>
</body>
</html>
