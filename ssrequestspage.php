<?php
session_start();

// Ensure user is logged in
if (!isset($_SESSION['phone'])) {
    die("You must be logged in to access this page.");
}

// Connect to database
$conn = new mysqli("localhost", "root", "", "home_and_heart");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch profile details
$phone = $_SESSION['phone'];
$sql = "SELECT fname, lname, ss_id, gender, address FROM ssprofile WHERE phone = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $phone);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Assign default values
$fname = $user['fname'] ?? '';
$lname = $user['lname'] ?? '';
$ss_id = $user['ss_id'] ?? '';
$gender = $user['gender'] ?? '';
$address = $user['address'] ?? '';

// Handle form submission
$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $fname = trim($_POST['fname']);
    $lname = trim($_POST['lname']);
    $ss_id = trim($_POST['ss_id']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $gender = $_POST['gender'];
    $category = $_POST['category'];
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];
    $workShiftFrom = $_POST['workShiftFrom'];
    $workShiftTo = $_POST['workShiftTo'];
    $price = $_POST['price'];
    $notes = $_POST['notes'];

    // Insert into ssrequests table
    $insert = $conn->prepare("INSERT INTO ssrequests 
        (fname, lname, ss_id, phone, address, gender, category, startDate, endDate, workShiftFrom, workShiftTo, price, notes) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $insert->bind_param("sssssssssssss", $fname, $lname, $ss_id, $phone, $address, $gender, $category, $startDate, $endDate, $workShiftFrom, $workShiftTo, $price, $notes);

    if ($insert->execute()) {
        $success = true;
    } else {
        die("Error: " . $insert->error);
    }

    $insert->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Service Request</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #00796b;
            color: #3e3e3e;
            text-align: center;
            margin: 0;
            padding: 0;
            height: 100%;
        }

        /* Header */
        header {
            background-color: #00796b;
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

        .form-container {
            max-width: 700px;
            margin: 40px auto;
            background-color: #ffffff;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .form-container h2 {
            color: #00796b;
        }

        label {
            display: block;
            font-weight: bold;
            color: #00796b;
            margin-bottom: 5px;
            text-align: left;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 0.4rem;
            margin-bottom: 1rem;
            font-size: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
        }

        button {
            padding: 0.5rem 1.5rem;
            background-color: #4a7c6a;
            color: white;
            border: none;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            font-size: 14px;
        }

        button:hover {
            background-color: #396555;
        }

        .success {
            background-color: #00796b;
            color: white;
            padding: 1rem;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
            margin-bottom: 1rem;
            font-size: 16px;
        }

        /* Footer */
        footer {
            background-color: #4a4a4a;
            color: white;
            padding: 10px;
            margin-top: 20px;
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
            font-size: clamp(12px, 2vw, 16px);
        }

        footer p {
            color: white;
            text-align: center;
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
                <li><a href="sshomepage.php"><i class="fas fa-home"></i></a></li>
                <li><a href="ssservicespage.php"><i class="fas fa-handshake"></i></a></li>
                <li><a href="ssrequestspage.php"><i class="fas fa-bullhorn"></i></a></li>
                <li><a href="ssnotificationspage.php"><i class="fas fa-bell"></i></a></li>
                <li><a href="sscontactuspage.php"><i class="fas fa-phone"></i></a></li>
                <li><a href="ssprofilepage.php"><i class="fas fa-user"></i></a></li>
            </ul>
        </nav>
    </header>

    <!-- Flashing Text -->
    <div class="running-text-container">
        <div class="running-text">!!! Only Limited To Andheri !!! </div>
    </div>

    <div class="form-container">
        <h2>Post Your Service Request</h2>

        <?php if ($success): ?>
            <div class="success">
                Form Submitted. We'll contact you soon!
            </div>
        <?php endif; ?>

        <form method="POST">
            <label for="fname">First Name</label>
            <input type="text" id="fname" name="fname" value="<?php echo htmlspecialchars($fname); ?>" readonly required>

            <label for="lname">Last Name</label>
            <input type="text" id="lname" name="lname" value="<?php echo htmlspecialchars($lname); ?>" readonly required>

            <label for="ss_id">SS ID</label>
            <input type="text" id="ss_id" name="ss_id" value="<?php echo htmlspecialchars($ss_id); ?>" readonly required>

            <label for="phone">Phone Number</label>
            <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>" readonly required>

            <label for="gender">Gender</label>
            <select id="gender" name="gender" required>
                <option disabled>Select your gender</option>
                <option value="Male" <?php echo ($gender == "Male") ? "selected" : ""; ?>>Male</option>
                <option value="Female" <?php echo ($gender == "Female") ? "selected" : ""; ?>>Female</option>
                <option value="Other" <?php echo ($gender == "Other") ? "selected" : ""; ?>>Other</option>
            </select>

            <label for="address">Address</label>
            <select id="address" name="address" required>
                <option disabled>Select your address</option>
                <?php
                $areas = [
                    "Chakala - 400099",
                    "Marol - 400059",
                    "Sakinaka - 400072",
                    "Mogra - 400058",
                    "Versova - 400061",
                    "Juhu - 400049",
                    "Four Bungalows - 400053",
                    "Lokhandwala Complex - 400053"
                ];
                foreach ($areas as $area) {
                    $selected = ($area == $address) ? "selected" : "";
                    echo "<option value=\"$area\" $selected>$area</option>";
                }
                ?>
            </select>

            <label for="category">Service Category</label>
            <select id="category" name="category" required>
                <option disabled selected>Select category</option>
                <option value="Cooking">Cooking</option>
                <option value="Cleaning">Cleaning</option>
                <option value="Child Care">Child Care</option>
                <option value="Elder Care">Elder Care</option>
            </select>

            <label for="startDate">Start Date</label>
            <input type="date" id="startDate" name="startDate" required>

            <label for="endDate">End Date</label>
            <input type="date" id="endDate" name="endDate" required>

            <label for="workShiftFrom">Work Shift From</label>
            <input type="time" id="workShiftFrom" name="workShiftFrom" required>

            <label for="workShiftTo">Work Shift To</label>
            <input type="time" id="workShiftTo" name="workShiftTo" required>

            <label for="price">Price (in ₹)</label>
            <input type="number" id="price" name="price" step="0.01" required>

            <label for="notes">Additional Notes</label>
            <textarea id="notes" name="notes" rows="4" placeholder="Any additional information..."></textarea>

            <button type="submit">Submit</button>
        </form>
    </div>

    <script>
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('startDate').setAttribute('min', today);
        document.getElementById('endDate').setAttribute('min', today);

        document.getElementById('startDate').addEventListener('change', function() {
            const selectedStart = this.value;
            document.getElementById('endDate').setAttribute('min', selectedStart);
        });
    </script>

    <!-- Footer -->
    <footer>
        <ul>
            <li><a href="sstermsandconditions.php">Terms and Conditions</a></li>
            <li><a href="ssprivacypolicy.php">Privacy Policy</a></li>
            <li><a href="sscancellationandrefund.php">Cancellation and Refund</a></li>
            <li><a href="sscontactuspage.php">Contact Us</a></li>
        </ul>
        <p>© 2025 Home&Heart. All rights reserved.</p>
    </footer>

</body>

</html>