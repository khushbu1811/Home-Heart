<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "home_and_heart";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

session_start();

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = trim($_POST['fname'] ?? '');
    $lname = trim($_POST['lname'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Regex patterns
    $nameRegex = "/^[a-zA-Z\s]+$/";
    $phoneRegex = "/^\d{10}$/";

    // Validate inputs
    if (!preg_match($nameRegex, $fname)) {
        die("त्रुटि : पहला नाम केवल अक्षरों में हो सकता है।");
    }

    if (!preg_match($nameRegex, $lname)) {
        die("त्रुटि : आखिरी नाम केवल अक्षरों में हो सकता है।");
    }

    if (!preg_match($phoneRegex, $phone)) {
        die("त्रुटि : कृपया एक मान्य 10-अंकीय फ़ोन नंबर दर्ज करें।");
    }

    if (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/\d/', $password) || !preg_match('/[\W_]/', $password)) {
        die("त्रुटि : पासवर्ड कम से कम 8 अक्षरों का होना चाहिए, जिसमें एक बड़ा अक्षर, एक संख्या और एक विशेष अक्षर शामिल हो।");
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert into database
    $sql = "INSERT INTO spsignup (fname, lname, phone, password) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $fname, $lname, $phone, $hashed_password);
    
    try {
        $stmt->execute();
        
        // Store data in session
        $_SESSION['fname'] = $fname;
        $_SESSION['lname'] = $lname;
        $_SESSION['phone'] = $phone;

        header("Location: spprofile.php");
        exit();
    } catch (mysqli_sql_exception $e) {
        if (str_contains($e->getMessage(), 'नकली प्रविष्टि')) {
            echo "<script>alert('त्रुटि : फ़ोन नंबर पहले से मौजूद है। कृपया लॉग इन करें'); window.history.back();</script>";
        } else {
            echo "<script>alert('त्रुटि : कुछ गलत हो गया। कृपया बाद में पुनः प्रयास करें।'); window.history.back();</script>";
        }
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
    <title>Home&Heart - Service Provider Sign Up</title>
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
    <!-- Sign-Up Form -->
    <div id="signup-container" class="container">
        <h2>सेवा खोजकर्ता साइन अप</h2>
        <form id="signup-form" action="spsignup.php" method="POST">
            <div class="form-group">
                <div class="form-group">
                    <label for="fname">पहला नाम</label>
                    <input type="text" id="fname" name="fname" placeholder="आपका पहला नाम भरें"
                        required>
                    <div class="error" id="fname-error"></div>
                </div>
                <div class="form-group">
                    <label for="lname">आखिरी नाम</label>
                    <input type="text" id="lname" name="lname" placeholder="आपका आखिरी नाम भरें"
                        required>
                    <div class="error" id="lname-error"></div>
                </div>
                <div class="form-group">
                    <label for="phone">फ़ोन नंबर</label>
                    <input type="tel" id="phone" name="phone"
                        placeholder="आपका फ़ोन नंबर भरें" required>
                    <div class="error" id="phone-error"></div>
                </div>
                <div class="form-group">
                    <label for="password">पासवर्ड</label>
                    <div class="password-container">
                        <input type="password" id="password" name="password"
                            placeholder="आपका पासवर्ड भरें" required>
                        <span class="toggle-password" onclick="togglePassword('password', 'toggle-icon1')">
                            <i id="toggle-icon1" class="fa fa-eye"></i>
                        </span>
                    </div>
                    <div class="error" id="password-error"></div>
                </div>
                <div class="form-group">
                    <label for="confirm-password">पासवर्ड पुष्टि</label>
                    <div class="password-container">
                        <input type="password" id="confirm-password" name="confirm-password"
                            placeholder="आपके पासवर्ड पुष्टि करें" required>
                        <span class="toggle-password" onclick="togglePassword('confirm-password', 'toggle-icon2')">
                            <i id="toggle-icon2" class="fa fa-eye"></i>
                        </span>
                    </div>
                    <div class="error" id="confirm-password-error"></div>
                </div>
                <button type="submit" class="btn">साइन अप</button>
        </form>
        <div class="switch">
            <p>क्या आपके पास खाता है ? <a href="splogin.php">लॉग इन</a></p>
        </div>
    </div>

    <script>
        function togglePassword(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (passwordInput.type === "password") {
                passwordInput.type = "text"; // Show password
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash"); // Change icon to "eye-slash"
            } else {
                passwordInput.type = "password"; // Hide password
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye"); // Change back to "eye"
            }
        }

        document.getElementById("signup-form").addEventListener("submit", function (event) {
    let isValid = true;

    // Get values
    const fname = document.getElementById("fname").value.trim();
    const lname = document.getElementById("lname").value.trim();
    const phone = document.getElementById("phone").value.trim();
    const password = document.getElementById("password").value;
    const confirmPassword = document.getElementById("confirm-password").value;

    // Clear error messages
    document.getElementById("fname-error").textContent = "";
    document.getElementById("lname-error").textContent = "";
    document.getElementById("phone-error").textContent = "";
    document.getElementById("password-error").textContent = "";
    document.getElementById("confirm-password-error").textContent = "";

    // Name validation
    if (!/^[a-zA-Z\s]+$/.test(fname)) {
        document.getElementById("fname-error").textContent = "पहला नाम केवल अक्षरों में होना चाहिए।";
        isValid = false;
    }

    if (!/^[a-zA-Z\s]+$/.test(lname)) {
        document.getElementById("lname-error").textContent = "आखिरी नाम केवल अक्षरों में होना चाहिए।";
        isValid = false;
    }

    // Phone number validation
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

    // Confirm password validation
    if (password !== confirmPassword) {
        document.getElementById("confirm-password-error").textContent = "पासवर्ड मेल नहीं खा रहे हैं।";
        isValid = false;
    }

    if (!isValid) {
        event.preventDefault(); // Stop form submission if validation fails
    }
});

    </script>

</body>

</html>