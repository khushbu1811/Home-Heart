<?php
session_start();
$conn = new mysqli("localhost", "root", "", "home_and_heart");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ✅ 1. Handle AJAX PayPal response
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'store_payment') {
    $booking_id = $_POST['booking_id'];
    $ss_id = $_POST['ss_id'];
    $sp_id = $_POST['sp_id'];
    $amount_paid = $_POST['amount_paid'];
    $payment_mode = $_POST['payment_mode'];

    // Insert payment into sspayment table
    $stmt = $conn->prepare("INSERT INTO sspayment (booking_id, ss_id, sp_id, amount_paid, payment_mode) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssds", $booking_id, $ss_id, $sp_id, $amount_paid, $payment_mode);
    $stmt->execute();

    // Insert notification into ssnotifications table
    $type = "payment_received";
    $created_at = date('Y-m-d H:i:s');
    $updated_at = $created_at;

    echo "success";
    exit;
}

// ✅ 2. Check session
if (!isset($_SESSION['phone'])) {
    echo "You are not logged in.";
    exit;
}

// ✅ 3. Get booking_id from either POST or GET
$booking_id = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'])) {
    $booking_id = $_POST['booking_id'];
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['booking_id'])) {
    $booking_id = $_GET['booking_id'];
} else {
    echo "<script>alert('Invalid access.'); window.location.href='sshomepage.php';</script>";
    exit;
}

// ✅ 4. Fetch booking details
$stmt = $conn->prepare("SELECT ss_id, sp_id, final_price FROM ssbookings WHERE booking_id = ?");
$stmt->bind_param("s", $booking_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $ss_id = $row['ss_id'];
    $sp_id = $row['sp_id'];
    $final_price = $row['final_price'];
    $advance = round($final_price * 0.10, 2);
} else {
    echo "<script>alert('Booking not found.'); window.location.href='sshomepage.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PayPal Payment</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://www.sandbox.paypal.com/sdk/js?client-id=AW4eCjYYZSIjTqFCRG74y5dSwfCpB_fcgRu6cng20hX1b7W7OjdqJ77ZvucOZHrPaEg7AC-IIwy-8_86&currency=USD"></script>
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

        .container {
            background-color: white;
            margin: 40px auto;
            padding: 30px;
            border: 1px solid #ddd;
            border-radius: 10px;
            max-width: 500px;
            box-shadow: 2px 2px 10px #ccc;
            font-family: Arial, sans-serif;
            text-align: center;
        }

        h2 {
            color: #333;
        }

        p{
            font-size: 18px;
        }

        .warning {
            color: red;
            font-size: 20px;
            font-weight: bold;
            margin-top: 10px;
        }

        .amount {
            font-size: 24px;
            margin: 20px 0;
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

    <div class="container">
        <h2>Proceed with Payment</h2>
        <p>You need to pay <strong>10%</strong> now and the remaining <strong>90%</strong> to the Service Provider.</p>
        <p class="warning">No Cancellation or Refund after Payment.</p>
        <div class="amount"><strong>Amount to Pay Now: $<?= number_format($advance, 2) ?></strong></div>

        <div id="paypal-button-container"></div>
    </div>

    <script>
        paypal.Buttons({
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: '<?= number_format($advance, 2) ?>'
                        }
                    }]
                });
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    fetch('sspayment.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: new URLSearchParams({
                                action: 'store_payment',
                                booking_id: '<?= $booking_id ?>',
                                ss_id: '<?= $ss_id ?>',
                                sp_id: '<?= $sp_id ?>',
                                amount_paid: '<?= $advance ?>',
                                payment_mode: 'PayPal'
                            })
                        })
                        .then(response => response.text())
                        .then(result => {
                            if (result.trim() === "success") {
                                alert("Booking Confirmed! Payment Successful.");
                                window.location.href = "ssnotificationspage.php?booking_id=<?= $booking_id ?>";
                            } else {
                                alert("Something went wrong: " + result);
                            }
                        });
                });
            }
        }).render('#paypal-button-container');
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