<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "home_and_heart";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $phone = $_POST['phone'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        echo "<script>alert('Passwords do not match.'); window.location.href='forgotpassword.php';</script>";
        exit();
    }

    // Check if the phone number exists
    $stmt = $conn->prepare("SELECT phone FROM sssignup WHERE phone = ?");
    $stmt->bind_param("s", $phone);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        
        // Update password
        $update_stmt = $conn->prepare("UPDATE sssignup SET password = ? WHERE phone = ?");
        $update_stmt->bind_param("ss", $hashed_password, $phone);
        $update_stmt->execute();
        $update_stmt->close();

        echo "<script>alert('Password reset successfully! Please log in.'); window.location.href='sslogin.php';</script>";
    } else {
        echo "<script>alert('Phone number not found.'); window.location.href='forgotpassword.php';</script>";
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
    <title>Forgot Password</title>
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
    </style>
</head>
<body>
    <div id="forgot-password-container" class="container">
        <h2>Reset Password</h2>
        <form id="forgot-password-form" action="forgotpassword.php" method="POST">
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" placeholder="Enter your registered phone number" required>
                <div class="error" id="phone-error"></div>
            </div>
            <div class="form-group">
                <label for="new_password">New Password</label>
                <div class="password-container">
                    <input type="password" id="new_password" name="new_password" placeholder="Enter new password" required>
                    <span class="toggle-password" onclick="togglePassword('new_password', 'toggle-icon1')">
                        <i id="toggle-icon1" class="fa fa-eye"></i>
                    </span>
                </div>
                <div class="error" id="password-error"></div>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <div class="password-container">
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required>
                    <span class="toggle-password" onclick="togglePassword('confirm_password', 'toggle-icon2')">
                        <i id="toggle-icon2" class="fa fa-eye"></i>
                    </span>
                </div>
                <div class="error" id="confirm-password-error"></div>
            </div>
            <button type="submit" class="btn">Reset Password</button>
        </form>
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

        document.getElementById("forgot-password-form").addEventListener("submit", function (event) {
            let isValid = true;

            const phone = document.getElementById("phone").value.trim();
            const password = document.getElementById("new_password").value;
            const confirmPassword = document.getElementById("confirm_password").value;

            document.getElementById("phone-error").textContent = "";
            document.getElementById("password-error").textContent = "";
            document.getElementById("confirm-password-error").textContent = "";

            // Phone number validation
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

            if (password !== confirmPassword) {
                document.getElementById("confirm-password-error").textContent = "Passwords do not match.";
                isValid = false;
            }

            if (!isValid) {
                event.preventDefault();
            }
        });
    </script>
</body>
</html>
