<?php
session_start();
$conn = new mysqli("localhost", "root", "", "home_and_heart");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Make sure the seeker is logged in
if (!isset($_SESSION['phone'])) {
    echo "You are not logged in.";
    exit;
}

$ss_phone = $_SESSION['phone'];

// Store dismissed confirmations
if (!isset($_SESSION['dismissed_confirmations'])) {
    $_SESSION['dismissed_confirmations'] = [];
}

// ✅ Store dismissed cancellations
if (!isset($_SESSION['dismissed_cancellations'])) {
    $_SESSION['dismissed_cancellations'] = [];
}

// Handle "Okay" button click
foreach ($_POST as $key => $value) {
    if (strpos($key, 'okay_') === 0) {
        $dismissed_id = str_replace('okay_', '', $key);
        $_SESSION['dismissed_confirmations'][] = $dismissed_id;
    }
    // ✅ Handle "Cancel" button click (do NOT redirect)
    if (strpos($key, 'cancel_') === 0) {
        $canceled_id = str_replace('cancel_', '', $key);
        $_SESSION['dismissed_cancellations'][] = $canceled_id;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SS Notifications</title>
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

        .wrapper {
            min-height: calc(90.2vh - 60px);
            /* Adjust based on footer height */
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

        h2 {
            text-align: center;
            margin-top: 30px;
            color: white;
        }

        .popup {
            border: 1px solid #00796b;
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
            color: #00796b;
        }

        .popup strong {
            color: #00796b;
        }

        .popup p {
            color: black;
            text-align: left;
            padding-left: 35%;
        }

        .btn {
            padding: 10px 20px;
            margin: 10px 5px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            color: white;
        }

        .proceed {
            background-color: #007BFF;
        }

        .proceed:hover {
            background-color: #0056b3;
        }

        .cancel {
            background-color: #d32f2f;
        }

        .cancel:hover {
            background-color: #c62828;
        }

        button[type="submit"] {
            padding: 10px 20px;
            margin: 10px 5px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            color: white;
            background-color: #4a7c6a;
            /* Nice blue color */
            transition: background-color 0.3s ease;
            font-size: 16px;
        }

        button[type="submit"]:hover {
            background-color: #396555;
            /* Darker blue on hover */
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

    <div class="wrapper">
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

        <h2>Booking Notifications</h2>

        <?php
        // Fetch notifications for the logged-in seeker from spnotifications table
        $notification_stmt = $conn->prepare("SELECT * FROM spnotifications WHERE ss_id IN (SELECT ss_id FROM ssbookings WHERE phone = ?) AND type = 'new_booking_req'");
        $notification_stmt->bind_param("s", $ss_phone);
        $notification_stmt->execute();
        $notification_result = $notification_stmt->get_result();

        if ($notification_result && $notification_result->num_rows > 0) {
            while ($notif_row = $notification_result->fetch_assoc()) {
                $booking_id = $notif_row['booking_id'];

                // Skip if confirmed popup was dismissed
                if (in_array($booking_id, $_SESSION['dismissed_confirmations'])) {
                    continue;
                }

                // 🔽 Check if the booking is confirmed via sspayment table
                $payment_check = $conn->prepare("SELECT * FROM sspayment WHERE booking_id = ? AND type = 'booking_confirmed'");
                $payment_check->bind_param("s", $booking_id);
                $payment_check->execute();
                $payment_result = $payment_check->get_result();

                if ($payment_result && $payment_result->num_rows > 0) {
                    // Fetch booking details to show in confirmation
                    $booking_info_stmt = $conn->prepare("SELECT booking_id, sp_fname, sp_lname, sp_phone, timing, final_price FROM ssbookings WHERE booking_id = ?");
                    $booking_info_stmt->bind_param("s", $booking_id);
                    $booking_info_stmt->execute();
                    $booking_info_result = $booking_info_stmt->get_result();

                    if ($booking_info_result && $booking_info_result->num_rows > 0) {
                        $booking_info = $booking_info_result->fetch_assoc();
                        $display_price = round($booking_info['final_price'] * 0.9, 2);
        ?>
                        <div class="popup">
                            <h3>Your booking is confirmed!</h3>
                            <p><strong>Booking ID:</strong> <?= $booking_info['booking_id'] ?></p>
                            <p><strong>Service Provider:</strong> <?= $booking_info['sp_fname'] . ' ' . $booking_info['sp_lname'] ?></p>
                            <p><strong>Phone Number :</strong> <?= $booking_info['sp_phone'] ?></p>
                            <p><strong>Timing:</strong> <?= $booking_info['timing'] ?></p>
                            <p><strong>Confirmed Price (90%):</strong> $<?= $display_price ?></p>
                            <form method="post">
                                <button type="submit" name="okay_<?= $booking_info['booking_id'] ?>">Okay</button>
                            </form>
                        </div>
                    <?php
                    } else {
                        error_log("Booking ID $booking_id confirmed in sspayment but not found in ssbookings.");
                    }
                    continue;
                }

                // ✅ Skip if canceled popup was dismissed
                if (in_array($booking_id, $_SESSION['dismissed_cancellations'])) {
                    continue;
                }

                // Match the booking_id from spnotifications with the ssbookings table
                $booking_stmt = $conn->prepare("SELECT booking_id, sp_id, sp_fname, sp_lname, sp_phone, sp_address, category, timing, people_or_rooms, final_price FROM ssbookings WHERE booking_id = ?");
                $booking_stmt->bind_param("s", $booking_id);
                $booking_stmt->execute();
                $booking_result = $booking_stmt->get_result();

                if ($booking_result && $booking_result->num_rows > 0) {
                    $booking_data = $booking_result->fetch_assoc();
                    ?>
                    <div class="popup">
                        <h3>Your booking request has been accepted!</h3>
                        <p><strong>Booking ID:</strong> <?= $booking_data['booking_id'] ?></p>
                        <p><strong>Provider:</strong> <?= $booking_data['sp_fname'] . ' ' . $booking_data['sp_lname'] ?></p>
                        <p><strong>Phone:</strong> <?= $booking_data['sp_phone'] ?></p>
                        <p><strong>Address:</strong> <?= $booking_data['sp_address'] ?></p>
                        <p><strong>Category:</strong> <?= $booking_data['category'] ?></p>
                        <p><strong>Timing:</strong> <?= $booking_data['timing'] ?></p>
                        <p><strong>People/Rooms:</strong> <?= $booking_data['people_or_rooms'] ?></p>
                        <p><strong>Final Price:</strong> $<?= $booking_data['final_price'] ?></p>

                        <form method="POST" action="sspayment.php">
                            <input type="hidden" name="booking_id" value="<?= $booking_data['booking_id'] ?>">
                            <button type="submit" class="btn proceed" name="action" value="proceed">Proceed for Payment</button>
                            <button type="button" class="btn cancel" onclick="dismissPopup('<?= $booking_data['booking_id'] ?>')">Cancel</button>
                        </form>
                    </div>
        <?php
                }
            }
        } else {
            echo "<p style='text-align:center;'>No new booking requests yet.</p>";
        }
        ?>

        <script>
            function dismissPopup(bookingId) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.style.display = 'none';

                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'cancel_' + bookingId;
                input.value = 'yes';

                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            }
        </script>
    </div>

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