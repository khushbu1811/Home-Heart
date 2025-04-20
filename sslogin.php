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
    $stmt = $conn->prepare("SELECT password FROM sssignup WHERE phone = ?");
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
            $_SESSION['phone'] = $phone;

            // 🔄 Insert into sslogin table if not already there
            $checkLogin = $conn->prepare("SELECT * FROM sslogin WHERE phone = ?");
            $checkLogin->bind_param("s", $phone);
            $checkLogin->execute();
            $checkLogin->store_result();

            if ($checkLogin->num_rows == 0) {
                $insertLogin = $conn->prepare("INSERT INTO sslogin (phone, password) VALUES (?, ?)");
                $insertLogin->bind_param("ss", $phone, $hashed_password); // Store hashed password
                $insertLogin->execute();
                $insertLogin->close();
            }
            $checkLogin->close();

            // Redirect to home page
            header("Location: sshomepage.php");
            exit();
        } else {
            // Password incorrect
            echo "<script>alert('Password entered is incorrect'); window.location.href='sslogin.php';</script>";
        }
    } else {
        // Phone not registered
        echo "<script>alert('Registered Phone Number is incorrect'); window.location.href='sslogin.php';</script>";
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
    <title>Home&Heart - Service Seeker Login Page</title>
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
            color: #00796b;
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
            color: #00796b;
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
            background-color: #00796b;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #009688;
        }

        .switch {
            text-align: center;
            margin-top: 15px;
        }

        .switch a {
            color: #00796b;
            text-decoration: none;
            font-weight: bold;
        }

        .switch a:hover {
            text-decoration: underline;
        }

        h2 {
            text-align: center;
            color: #00796b;
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
        <h2>Service Seeker's Log In</h2>
        <form id="login-form" action="sslogin.php" method="POST">
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" required>
                <div class="error" id="phone-error"></div>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <div class="password-container">
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    <span class="toggle-password" onclick="togglePassword('password', 'toggle-icon')">
                        <i id="toggle-icon" class="fa fa-eye"></i>
                    </span>
                </div>
                <div class="error" id="password-error"></div>
            </div>
            <button type="submit" class="btn">Log In</button>
        </form>
        <div class="switch">
            <p><a href="ssforgotpassword.php">Forgot Password ?</a></p>
            <p>Don't have an account ? <a href="sssignup.php">Sign Up</a></p>
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
                document.getElementById("phone-error").textContent = "Phone number must be a 10-digit number.";
                isValid = false;
            }

            // Password validation
    if (password.length < 8) {
        document.getElementById("password-error").textContent = "Password must be at least 8 characters long.";
        isValid = false;
    } else if (!/[A-Z]/.test(password)) {
        document.getElementById("password-error").textContent = "Password must contain at least one uppercase letter.";
        isValid = false;
    } else if (!/\d/.test(password)) {
        document.getElementById("password-error").textContent = "Password must contain at least one number.";
        isValid = false;
    } else if (!/[\W_]/.test(password)) {
        document.getElementById("password-error").textContent = "Password must contain at least one special character (e.g., !@#$%^&*_).";
        isValid = false;
    }

            if (!isValid) {
                event.preventDefault();
            }
        });
    </script>
</body>
</html>