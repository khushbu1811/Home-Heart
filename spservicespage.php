<?php
session_start();

// DB setup
$conn = new mysqli("localhost", "root", "", "home_and_heart");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Check login
if (!isset($_SESSION['phone'])) {
    echo "कृपया पहले लॉगिन करें।";
    exit;
}

$phone = $_SESSION['phone'];
$success_msg = "";

// Get sp_id
$stmt = $conn->prepare("SELECT sp_id FROM spprofile WHERE phone = ?");
$stmt->bind_param('s', $phone);
$stmt->execute();
$sp_result = $stmt->get_result();

if ($sp_result->num_rows > 0) {
    $sp_id = $sp_result->fetch_assoc()['sp_id'];
} else {
    echo "SP आईडी नहीं मिली।";
    exit;
}

// Handle edit request
if (isset($_POST['edit'])) {
    $_SESSION['edit_mode'] = true;
}

// Overlap checker (updated)
function checkOverlap($slots) {
    $times = array_filter($slots); // Remove empty slots
    $formatted = [];

    foreach ($times as $time) {
        $dt = DateTime::createFromFormat('H:i', $time);
        if ($dt !== false) {
            $formatted[] = $dt->format('H:i');
        }
    }

    return count($formatted) !== count(array_unique($formatted));
}

// Handle form submit
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['edit'])) {
    $category = $_POST['category'];
    $time_slot_1 = $_POST['time_slot_1'];
    $time_slot_2 = $_POST['time_slot_2'];
    $time_slot_3 = $_POST['time_slot_3'];
    $price = $_POST['price'];
    $notes = $_POST['notes'];

    $slots = [$time_slot_1, $time_slot_2, $time_slot_3];
    if (checkOverlap($slots)) {
        echo "<div style='color:red;'>Error: समय स्लॉट्स को एकदम मेल नहीं खाना चाहिए।</div>";
        exit;
    }

    // Check if service exists
    $check = $conn->prepare("SELECT * FROM spservices WHERE sp_id = ?");
    $check->bind_param('s', $sp_id);
    $check->execute();
    $check_result = $check->get_result();

    if ($check_result->num_rows > 0) {
        // Update
        $update = $conn->prepare("UPDATE spservices SET category=?, time_slot_1=?, time_slot_2=?, time_slot_3=?, price=?, notes=? WHERE sp_id=?");
        $update->bind_param('sssssss', $category, $time_slot_1, $time_slot_2, $time_slot_3, $price, $notes, $sp_id);
        $update->execute();
        $success_msg = "सेवा सफलतापूर्वक अपडेट कर दी गई!";
    } else {
        // Insert
        $insert = $conn->prepare("INSERT INTO spservices (sp_id, category, time_slot_1, time_slot_2, time_slot_3, price, notes) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $insert->bind_param('sssssss', $sp_id, $category, $time_slot_1, $time_slot_2, $time_slot_3, $price, $notes);
        $insert->execute();
        $success_msg = "सेवा सफलतापूर्वक जोड़ी गई!";
    }

    $_SESSION['edit_mode'] = false;
}

// Fetch latest service data
$fetch = $conn->prepare("SELECT * FROM spservices WHERE sp_id = ?");
$fetch->bind_param('s', $sp_id);
$fetch->execute();
$data_result = $fetch->get_result();
$service_data = $data_result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home&Heart - Service Provider Services</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #795548;
            color: white;
            text-align: center;
            margin: 0;
            padding: 0;
        }

        /* Header */
        header {
            background-color: #795548;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 5px 15px;
            height: 45px;
            flex-wrap: wrap;
            border-bottom: 1px solid white;
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

        .container {
            max-width: 550px;
            padding: 40px;
            background-color: #ffffff;
            color: black;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            border-radius: 16px;
            text-align: center;
            margin: 30px auto;
        }

        .container strong {
            color: #795548;
        }

        h2 {
            text-align: center;
            color: #795548;
            font-size: 24px;
            margin-top: 10px;
        }

        h3 {
            color: #795548;
            margin-bottom: 20px;
            font-size: 20px;
        }

        p {
            font-size: 18px;
            line-height: 1.7;
            color: #3e3e3e;
            margin-bottom: 20px;
            text-align: center;
        }

        table,
        th,
        td {
            width: 50%;
            border: 1px solid #000;
            border-collapse: collapse;
            padding: 8px;
            margin: 20px auto;
        }

        table td:first-child,
        table th:first-child {
            color: #795548;
        }

        .success {
            background-color: #795548;
            padding: 10px;
            border: 1px solid #c3e6cb;
            color: #ffffff;
            margin-bottom: 20px;
            font-size: 18px;
        }

        .form-row {
            display: flex;
            align-items: flex-start;
            margin-bottom: 15px;
            padding-left: 113px;
        }

        label {
            display: inline-block;
            width: 150px;
            color: #795548;
            font-weight: bold;
        }

        input[type=time],
        input[type=number],
        select,
        textarea {
            width: 175px;
            padding: 8px 12px;
            /* Extra spacing */
            font-size: 14px;
            /* Bigger font */
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
            position: relative;
        }

        .sub-btn {
            background-color: #8C6A5C;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .sub-btn:hover {
            background-color: #705248;
        }

        .edit-button {
            background-color: #8C6A5C;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .edit-button:hover {
            background-color: #705248;
        }

        /* Footer */
        footer {
            background-color: #4e342e;
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
                <li><a href="sphomepage.php"><i class="fas fa-home"></i></a></li>
                <li><a href="spservicespage.php"><i class="fas fa-handshake"></i></a></li>
                <li><a href="spnotificationspage.php"><i class="fas fa-bell"></i></a></li>
                <li><a href="spcontactuspage.php"><i class="fas fa-phone"></i></a></li>
                <li><a href="spprofilepage.php"><i class="fas fa-user"></i></a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <h2>सेवा प्रदाता पोर्टल</h2>
        <p><strong>आपकी SP ID :</strong> <?= htmlspecialchars($sp_id); ?></p>

        <?php if (!empty($success_msg)) { ?>
            <div class="success"><?= $success_msg; ?></div>
        <?php } ?>

        <?php if (!isset($service_data) || (isset($_SESSION['edit_mode']) && $_SESSION['edit_mode'])) { ?>
            <form method="POST">
                <label>श्रेणी :</label>
                <select name="category" required>
                    <option value="">चुनें</option>
                    <option value="Cooking" <?= ($service_data['category'] ?? '') == 'Cooking' ? 'selected' : ''; ?>>खाना बनाना</option>
                    <option value="Cleaning" <?= ($service_data['category'] ?? '') == 'Cleaning' ? 'selected' : ''; ?>>सफाई</option>
                    <option value="Child Care" <?= ($service_data['category'] ?? '') == 'Child Care' ? 'selected' : ''; ?>>बच्चे की देखभाल</option>
                    <option value="Elder Care" <?= ($service_data['category'] ?? '') == 'Elder Care' ? 'selected' : ''; ?>>बुजुर्ग की देखभाल</option>
                </select><br><br>

                <label>समय स्लॉट 1 ( आवश्यक ) :</label>
                <input type="time" name="time_slot_1" min="06:00" max="23:00" required value="<?= $service_data['time_slot_1'] ?? '' ?>"><br><br>

                <label>समय स्लॉट 2 :</label>
                <input type="time" name="time_slot_2" min="06:00" max="23:00" value="<?= $service_data['time_slot_2'] ?? '' ?>"><br><br>

                <label>समय स्लॉट 3 :</label>
                <input type="time" name="time_slot_3" min="06:00" max="23:00" value="<?= $service_data['time_slot_3'] ?? '' ?>"><br><br>

                <label>कीमत :</label>
                <input type="number" name="price" required value="<?= $service_data['price'] ?? '' ?>"><br><br>

                <div class="form-row">
                    <label for="notes">टिप्पणियाँ :</label>
                    <textarea name="notes" rows="4"><?= $service_data['notes'] ?? '' ?></textarea><br><br>
                </div>

                <button class="sub-btn" type="submit">जमा करें</button>
            </form>
        <?php } else { ?>
            <h3>आपकी सबमिट की गई सेवा की जानकारी</h3>
            <table>
                <tr>
                    <th>श्रेणी</th>
                    <td><?= htmlspecialchars($service_data['category']) ?></td>
                </tr>
                <tr>
                    <th>समय स्लॉट 1</th>
                    <td><?= htmlspecialchars($service_data['time_slot_1']) ?></td>
                </tr>
                <tr>
                    <th>समय स्लॉट 2</th>
                    <td><?= $service_data['time_slot_2'] ?: 'N/A' ?></td>
                </tr>
                <tr>
                    <th>समय स्लॉट 3</th>
                    <td><?= $service_data['time_slot_3'] ?: 'N/A' ?></td>
                </tr>
                <tr>
                    <th>कीमत</th>
                    <td><?= htmlspecialchars($service_data['price']) ?></td>
                </tr>
                <tr>
                    <th>टिप्पणियाँ</th>
                    <td><?= nl2br(htmlspecialchars($service_data['notes'])) ?></td>
                </tr>
            </table>
            <form method="POST">
                <button class="edit-button" name="edit" value="1">श्रेणी बदलें</button>
            </form>
        <?php } ?>
    </div>

    <!-- Footer -->
    <footer>
        <ul>
            <li><a href="sptermsandconditions.php">नियम और शर्तें</a></li>
            <li><a href="spprivacypolicy.php">गोपनीयता नीति</a></li>
            <li><a href="spcancellationandrefund.php">रद्दीकरण और धनवापसी</a></li>
            <li><a href="spcontactuspage.php">संपर्क करें</a></li>
        </ul>
        <p>© 2025 Home&Heart. सर्वाधिकार सुरक्षित।</p>
    </footer>
</body>
</html>
