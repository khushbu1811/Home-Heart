<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli("localhost", "root", "", "home_and_heart");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if provider is logged in
if (!isset($_SESSION['phone'])) {
    echo "आप लॉगिन नहीं हैं।";
    exit;
}

$sp_phone = $_SESSION['phone'];

// Handle AJAX Accept/Decline Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'], $_POST['action'])) {
    header('Content-Type: application/json');
    $booking_id = $_POST['booking_id'];
    $action = $_POST['action'];

    if ($action === 'accept') {
        $stmt = $conn->prepare("SELECT ss_id, sp_id FROM ssbookings WHERE booking_id = ?");
        $stmt->bind_param("s", $booking_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $ss_id = $row['ss_id'];
            $sp_id = $row['sp_id'];

            if (!empty($ss_id) && !empty($sp_id)) {
                // Insert into spnotifications
                $type_sp = "new_booking_req";
                $insert_sp = $conn->prepare("INSERT INTO spnotifications (booking_id, ss_id, sp_id, type) VALUES (?, ?, ?, ?)");
                $insert_sp->bind_param("ssss", $booking_id, $ss_id, $sp_id, $type_sp);
                $insert_sp->execute();

                // Insert into ssnotifications
                $type_ss = "booking_req_accepted";
                $insert_ss = $conn->prepare("INSERT INTO ssnotifications (booking_id, ss_id, sp_id, type) VALUES (?, ?, ?, ?)");
                $insert_ss->bind_param("ssss", $booking_id, $ss_id, $sp_id, $type_ss);
                $insert_ss->execute();

                echo json_encode(["success" => true, "message" => "पुष्टि का इंतजार कर रहे हैं!"]);
                exit;
            } else {
                echo json_encode(["success" => false, "message" => "SS ID या SP ID गायब है।"]);
                exit;
            }
        } else {
            echo json_encode(["success" => false, "message" => "बुकिंग नहीं मिली।"]);
            exit;
        }
    } elseif ($action === 'decline') {
        $delete = $conn->prepare("DELETE FROM ssbookings WHERE booking_id = ?");
        $delete->bind_param("s", $booking_id);
        $delete->execute();

        echo json_encode(["success" => true, "message" => "बुकिंग अस्वीकृत और हटा दी गई।"]);
        exit;
    }
}

// Handle "Okay" acknowledgement to permanently remove
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acknowledge_booking_id'])) {
    header('Content-Type: application/json');
    $booking_id = $_POST['acknowledge_booking_id'];

    $delete = $conn->prepare("DELETE FROM ssbookings WHERE booking_id = ? AND sp_phone = ?");
    $delete->bind_param("ss", $booking_id, $sp_phone);
    if ($delete->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "डेटाबेस त्रुटि।"]);
    }
    exit;
}

// Fetch bookings to show (excluding already accepted)
$query = "SELECT b.booking_id, b.ss_id, b.ss_fname, b.ss_lname, b.phone, b.address, b.category, b.timing, b.people_or_rooms, b.final_price 
          FROM ssbookings b
          WHERE b.sp_phone = ? 
          AND b.booking_id NOT IN (
              SELECT booking_id FROM spnotifications WHERE type = 'new_booking_req'
          )";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $sp_phone);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SP Notifications</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #795548;
            color: white;
            text-align: center;
            margin: 0;
            padding: 0;
            height: 100%;
        }

        .wrapper {
            min-height: calc(88.5vh - 60px);
            /* Adjust based on footer height */
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

        .popup {
            border: 1px solid #795548;
            background: white;
            padding: 20px;
            margin: 20px auto;
            width: 50%;
            border-radius: 10px;
            box-shadow: 2px 2px 10px #aaa;
            position: relative;
            font-size: 18px;
        }

        .popup h3 {
            color: #795548;
        }

        .popup strong {
            color: #795548;
        }

        .popup p {
            color: black;
            text-align: left;
            padding-left: 34%;
        }

        .btn {
            padding: 10px 20px;
            margin: 10px 5px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            color: white;
        }

        .accept {
            background-color: #45a049;
        }

        .accept:hover {
            background-color: #388e3c;
        }

        .decline {
            background-color: #d32f2f;
        }

        .decline:hover {
            background-color: #c62828;
        }

        .message {
            margin: 20px auto;
            padding: 15px;
            background-color: white;
            color: #795548;
            width: 80%;
            border-radius: 8px;
            text-align: center;
            font-size: 16px;
            font-weight: bold;
        }

        .okay {
            background-color: #8C6A5C;
        }

        .okay:hover {
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

    <div class="wrapper">
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

        <h2>बुकिंग सूचनाएँ</h2>

        <div id="message-area"></div>

        <?php
        // "You have been booked" section
        $bookedQuery = "
    SELECT b.booking_id, b.ss_fname, b.ss_lname, b.phone, b.address, b.timing, b.final_price
    FROM ssbookings b
    INNER JOIN sspayment p ON b.booking_id = p.booking_id
    WHERE b.sp_phone = ?
";
        $bookedStmt = $conn->prepare($bookedQuery);
        $bookedStmt->bind_param("s", $sp_phone);
        $bookedStmt->execute();
        $bookedResult = $bookedStmt->get_result();

        while ($booked = $bookedResult->fetch_assoc()) {
            $ninetyPercent = round($booked['final_price'] * 0.9, 2);
        ?>
            <div class="popup">
                <h3>आपकी बुकिंग हो गई है!</h3>
                <p><strong>बुकिंग आईडी :</strong> <?= $booked['booking_id'] ?></p>
                <p><strong>सेवा प्राप्तकर्ता का नाम :</strong> <?= $booked['ss_fname'] . ' ' . $booked['ss_lname'] ?></p>
                <p><strong>फोन :</strong> <?= $booked['phone'] ?></p>
                <p><strong>पता :</strong> <?= $booked['address'] ?></p>
                <p><strong>समय :</strong> <?= $booked['timing'] ?></p>
                <p><strong>कीमत जो आपको मिलेगी :</strong> $<?= $ninetyPercent ?></p>
                <button class="btn okay" onclick="acknowledgeBooking('<?= $booked['booking_id'] ?>', this)">ठीक है!</button>
            </div>
        <?php } ?>

        <?php while ($row = $result->fetch_assoc()) { ?>
            <div class="popup" id="popup-<?= $row['booking_id'] ?>">
                <h3>आपके पास एक नई बुकिंग अनुरोध है!</h3>
                <p><strong>बुकिंग आईडी :</strong> <?= $row['booking_id'] ?></p>
                <p><strong>सेवा प्राप्तकर्ता का नाम :</strong> <?= $row['ss_fname'] . ' ' . $row['ss_lname'] ?></p>
                <p><strong>फोन :</strong> <?= $row['phone'] ?></p>
                <p><strong>पता :</strong> <?= $row['address'] ?></p>
                <p><strong>Category:</strong> <?= $row['category'] ?></p>
                <p><strong>श्रेणी :</strong> <?= $row['timing'] ?></p>
                <p><strong>लोग / कमरे :</strong> <?= $row['people_or_rooms'] ?></p>
                <p><strong>अंतिम मूल्य :</strong> $<?= $row['final_price'] ?></p>

                <form class="action-form" data-booking="<?= $row['booking_id'] ?>">
                    <button type="submit" name="action" value="accept" class="btn accept">स्वीकार करें</button>
                    <button type="submit" name="action" value="decline" class="btn decline">अस्वीकृत करें</button>
                </form>
            </div>
        <?php } ?>

        <script>
            document.querySelectorAll('.action-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const bookingId = this.dataset.booking;
                    const action = e.submitter.value;
                    const formData = new FormData();
                    formData.append('booking_id', bookingId);
                    formData.append('action', action);

                    fetch("", {
                            method: 'POST',
                            body: formData
                        })
                        .then(res => {
                            if (!res.ok) throw new Error("Server returned " + res.status);
                            return res.json();
                        })
                        .then(data => {
                            if (data.success) {
                                document.getElementById('popup-' + bookingId).style.display = 'none';
                                document.getElementById('message-area').innerHTML = `<div class="message">${data.message}</div>`;
                            } else {
                                alert(data.message || 'Something went wrong.');
                            }
                        })
                        .catch(err => {
                            alert('Server error: ' + err.message);
                            console.error(err);
                        });
                });
            });

            function acknowledgeBooking(bookingId, button) {
                fetch("", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `acknowledge_booking_id=${encodeURIComponent(bookingId)}`
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            button.closest('.popup').remove();
                        } else {
                            alert(data.message || 'Failed to acknowledge.');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert("Error acknowledging booking.");
                    });
            }
        </script>
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