<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start(); // Start the session

$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "home_and_heart";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $phone = $_POST['phone'];
    $password = $_POST['password'];

    // Prepare to fetch hashed password from DB
    $stmt = $conn->prepare("SELECT password FROM spsignup WHERE phone = ?");
    $stmt->bind_param("s", $phone);
    $stmt->execute();
    $stmt->store_result();

    // Check if phone exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

        // Check if password matches
        if (password_verify($password, $hashed_password)) {
            // ✅ Set session with user's phone number
            // ✅ Set session with user's phone number
$_SESSION['phone'] = $phone;

// 🔄 Insert into splogin table if not already there
$checkLogin = $conn->prepare("SELECT * FROM splogin WHERE phone = ?");
$checkLogin->bind_param("s", $phone);
$checkLogin->execute();
$checkLogin->store_result();

if ($checkLogin->num_rows == 0) {
    $insertLogin = $conn->prepare("INSERT INTO splogin (phone, password) VALUES (?, ?)");
    $insertLogin->bind_param("ss", $phone, $hashed_password); // Store hashed password
    $insertLogin->execute();
    $insertLogin->close();
}
$checkLogin->close();

// Redirect to home page
header("Location: sphomepage.php");
exit();

        } else {
            // Password incorrect
            echo "<script>alert('पासवर्ड गलत दर्ज किया गया है।'); window.location.href='splogin.php';</script>";
        }
    } else {
        // Phone not registered
        echo "<script>alert('पंजीकृत फ़ोन नंबर गलत है।'); window.location.href='splogin.php';</script>";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home&Heart - Service Provider Login Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #faf8f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            width: 90%;
            max-width: 500px;
            background-color: white;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            border: 2px solid #afafaf;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #795548;
        }

        input[type="text"],
        input[type="tel"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #afafaf;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .password-container {
            position: relative;
            display: flex;
            align-items: center;
        }

        .password-container input {
            flex: 1;
            padding-right: 35px;
        }

        .toggle-password {
            position: absolute;
            right: 10px;
            cursor: pointer;
            font-size: 18px;
            color: #555;
        }

        .toggle-password:hover {
            color: #795548;
            /* Hover effect */
        }

        .error {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }

        .btn {
            width: 100%;
            padding: 12px;
            font-size: 18px;
            background-color: #8C6A5C;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #705248;
        }

        .switch {
            text-align: center;
            margin-top: 15px;
        }

        .switch a {
            color: #795548;
            text-decoration: none;
            font-weight: bold;
        }

        .switch a:hover {
            text-decoration: underline;
        }

        h2 {
            text-align: center;
            color: #795548;
        }

        /* Media Query for Smaller Screens */
        @media (max-width: 600px) {
            body {
                padding: 10px;
            }

            .container {
                padding: 20px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            .btn {
                font-size: 16px;
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <!-- Login Form -->
    <div id="login-container" class="container">
        <h2>सेवा खोजकर्ता लॉग इन</h2>
        <form id="login-form" action="splogin.php" method="POST">
            <div class="form-group">
                <label for="phone">फ़ोन नंबर</label>
                <input type="tel" id="phone" name="phone" placeholder="आपका फ़ोन नंबर भरें" required>
                <div class="error" id="phone-error"></div>
            </div>
            <div class="form-group">
                <label for="password">पासवर्ड</label>
                <div class="password-container">
                    <input type="password" id="password" name="password" placeholder="आपका पासवर्ड भरें" required>
                    <span class="toggle-password" onclick="togglePassword('password', 'toggle-icon')">
                        <i id="toggle-icon" class="fa fa-eye"></i>
                    </span>
                </div>
                <div class="error" id="password-error"></div>
            </div>
            <button type="submit" class="btn">लॉग इन</button>
        </form>
        <div class="switch">
            <p><a href="spforgotpassword.php">पासवर्ड भूल गए ? </a></p>
            <p>खाता नहीं है ? <a href="spsignup.php">साइन अप</a></p>
        </div>
    </div>

    <script>
        function togglePassword(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                passwordInput.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }

        document.getElementById("login-form").addEventListener("submit", function (event) {
            let isValid = true;

            const phone = document.getElementById("phone").value.trim();
            const password = document.getElementById("password").value;

            document.getElementById("phone-error").textContent = "";
            document.getElementById("password-error").textContent = "";

            if (!/^\d{10}$/.test(phone)) {
                document.getElementById("phone-error").textContent = "फ़ोन नंबर 10 अंकों का होना चाहिए।";
                isValid = false;
            }

            // Password validation
    if (password.length < 8) {
        document.getElementById("password-error").textContent = "पासवर्ड कम से कम 8 अक्षरों का होना चाहिए।";
        isValid = false;
    } else if (!/[A-Z]/.test(password)) {
        document.getElementById("password-error").textContent = "पासवर्ड में कम से कम एक बड़ा अक्षर होना चाहिए।";
        isValid = false;
    } else if (!/\d/.test(password)) {
        document.getElementById("password-error").textContent = "पासवर्ड में कम से कम एक संख्या होनी चाहिए।";
        isValid = false;
    } else if (!/[\W_]/.test(password)) {
        document.getElementById("password-error").textContent = "पासवर्ड में कम से कम एक विशेष अक्षर होना चाहिए (जैसे, !@#$%^&*_)।";
        isValid = false;
    }

            if (!isValid) {
                event.preventDefault();
            }
        });
    </script>
</body>
</html>