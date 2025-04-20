<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "home_and_heart";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Ensure phone number is in session (user is logged in)
if (!isset($_SESSION['phone'])) {
  die("You must be logged in to book a service.");
}

$phone = $_SESSION['phone'];

// Fetch ss_id and other details from ssprofile using phone
$ss_query = "SELECT * FROM ssprofile WHERE phone = '$phone'";
$ss_result = $conn->query($ss_query);
if (!$ss_result || $ss_result->num_rows == 0) {
  die("No profile found for logged-in user.");
}
$ss = $ss_result->fetch_assoc();
$ss_id = $ss['ss_id']; // we now have ss_id

// Get sp_id from URL
$sp_id = $_GET['sp_id'] ?? '';
if (!$sp_id) {
  die("Service provider not specified.");
}

// Fetch service info
$service_result = $conn->query("SELECT * FROM spservices WHERE sp_id = '$sp_id'");
$service = $service_result->fetch_assoc();

// Fetch service provider profile
$sp_result = $conn->query("SELECT sp_id, fname AS sp_fname, lname AS sp_lname, phone AS sp_phone, address AS sp_address FROM spprofile WHERE sp_id = '$sp_id'");
$sp = $sp_result->fetch_assoc();

// Handle booking
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $slot = $_POST['slot'] ?? '';
  $people = (int)($_POST['people'] ?? 1);
  $final_price = (float)($_POST['final_price'] ?? 0);
  $booking_id = uniqid("BK_");
  $timing = $service[$slot] ?? '';

  // Insert booking data into ssbookings table
  $insert_sql = "INSERT INTO ssbookings 
        (booking_id, ss_id, ss_fname, ss_lname, phone, address, sp_id, sp_fname, sp_lname, sp_phone, sp_address, category, timing, people_or_rooms, final_price)
        VALUES 
        ('$booking_id', '{$ss['ss_id']}', '{$ss['fname']}', '{$ss['lname']}', '{$ss['phone']}', '{$ss['address']}', 
         '{$sp['sp_id']}', '{$sp['sp_fname']}', '{$sp['sp_lname']}', '{$sp['sp_phone']}', '{$sp['sp_address']}', 
         '{$service['category']}', '$timing', '$people', '$final_price')";

  if ($conn->query($insert_sql) === TRUE) {
    $message = "Booking successful! Booking ID: $booking_id";
  } else {
    $message = "Error: " . $conn->error;
  }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home&Heart - Service Seeker - Service Booking</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #00796b;
      color: #3e3e3e;
      margin: 0;
      padding: 0;
    }

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
      background: white;
      padding: 20px;
      border-radius: 10px;
      max-width: 500px;
      margin: auto;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      margin-top: 45px;
    }

    .container strong {
      color: #00796b;
    }

    h2 {
      text-align: center;
      color: #00796b;
      font-size: 24px;
      margin-top: 10px;
    }

    .popup {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      align-items: center;
      justify-content: center;
    }

    .popup-content {
      background: white;
      padding: 20px;
      border-radius: 10px;
      width: 300px;
      position: relative;
    }

    .popup-content strong {
      color: #00796b;
    }

    h3 {
      text-align: center;
      color: #00796b;
      font-size: 20px;
      margin-top: 10px;
    }

    .slot-buttons {
      text-align: center;
      margin-top: 10px;
    }

    .btn {
      padding: 8px 20px;
      margin: 5px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 14px;
    }

    .primary {
      background: #4a7c6a;
      color: white;
    }

    .primary:hover {
      background-color: #396555;
    }

    .secondary {
      background: #4a7c6a;
      color: white;
    }

    .secondary:hover {
      background-color: #396555;
    }

    .counter {
      display: flex;
      align-items: center;
      gap: 10px;
      margin: 10px 0;
    }

    .counter button {
      width: 30px;
      height: 30px;
      font-size: 18px;
      color: black;
    }

    #qtyLabel {
      color: #00796b;
      font-weight: bold;
    }

    .message {
      color: black;
      text-align: center;
      margin: 10px 0;
      font-size: 17px;
    }

    /* Footer */
    footer {
      background-color: #4a4a4a;
      color: white;
      padding: 10px;
      margin-top: 20px;
      text-align: center;
      font-size: clamp(14px, 2.5vw, 20px);
      position: fixed;
      bottom: 0;
      width: 100%;
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
        <li><a href="sshomepage.php"><i class="fas fa-home"></i></a></li>
        <li><a href="ssservicespage.php"><i class="fas fa-handshake"></i></a></li>
        <li><a href="ssofferspage.php"><i class="fas fa-bullhorn"></i></a></li>
        <li><a href="ssnotificationspage.php"><i class="fas fa-bell"></i></a></li>
        <li><a href="sscontactuspage.php"><i class="fas fa-phone"></i></a></li>
        <li><a href="ssprofilepage.php"><i class="fas fa-user"></i></a></li>
      </ul>
    </nav>
  </header>

  <div class="container">
    <h2>Service Booking Details</h2>
    <?php if ($message): ?>
      <p class="message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>
    <p><strong>Category :</strong> <?= htmlspecialchars($service['category']) ?></p>
    <p><strong>Time Slot 1 :</strong> <?= htmlspecialchars($service['time_slot_1']) ?></p>
    <p><strong>Time Slot 2 :</strong> <?= htmlspecialchars($service['time_slot_2']) ?></p>
    <p><strong>Time Slot 3 :</strong> <?= htmlspecialchars($service['time_slot_3']) ?></p>
    <p><strong>Price :</strong> ₹<?= htmlspecialchars($service['price']) ?></p>
    <p><strong>Notes :</strong> <?= nl2br(htmlspecialchars($service['notes'])) ?></p>

    <div class="slot-buttons">
      <button class="btn primary" onclick="openPopup('time_slot_1')">Slot 1</button>
      <button class="btn primary" onclick="openPopup('time_slot_2')">Slot 2</button>
      <button class="btn primary" onclick="openPopup('time_slot_3')">Slot 3</button>
    </div>
  </div>

  <!-- Popup -->
  <div class="popup" id="bookingPopup">
    <div class="popup-content">
      <form method="POST">
        <h3 id="popupTitle">Booking</h3>
        <p><strong>Time :</strong> <span id="popupTime"></span></p>
        <p><strong>Price :</strong> ₹<span id="popupPrice"></span></p>

        <div class="counter" id="quantitySection">
          <label id="qtyLabel">People / Rooms :</label>
          <button type="button" onclick="changeQty(-1)">-</button>
          <span id="quantity">1</span>
          <button type="button" onclick="changeQty(1)">+</button>
        </div>

        <p><strong>Total :</strong> ₹<span id="totalPrice"></span></p>

        <input type="hidden" name="slot" id="hiddenSlot">
        <input type="hidden" name="people" id="hiddenPeople">
        <input type="hidden" name="final_price" id="hiddenPrice">

        <div class="slot-buttons">
          <button type="submit" class="btn primary">Book Now</button>
          <button type="button" class="btn secondary" onclick="closePopup()">Cancel</button>
        </div>
      </form>
    </div>
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

  <script>
    const category = "<?= $service['category'] ?>";
    const slots = {
      time_slot_1: "<?= $service['time_slot_1'] ?>",
      time_slot_2: "<?= $service['time_slot_2'] ?>",
      time_slot_3: "<?= $service['time_slot_3'] ?>",
    };
    const basePrice = parseFloat("<?= $service['price'] ?>");

    let currentSlot = "";
    let quantity = 1;

    function openPopup(slot) {
      document.getElementById("bookingPopup").style.display = "flex";
      quantity = 1;
      currentSlot = slot;

      document.getElementById("popupTitle").innerText = category + " Booking";
      document.getElementById("popupTime").innerText = slots[slot];
      document.getElementById("popupPrice").innerText = basePrice.toFixed(2);
      document.getElementById("quantity").innerText = quantity;

      if (category === "Cooking") {
        document.getElementById("qtyLabel").innerText = "People:";
        document.getElementById("quantitySection").style.display = "flex";
      } else if (category === "Cleaning") {
        document.getElementById("qtyLabel").innerText = "Rooms:";
        document.getElementById("quantitySection").style.display = "flex";
      } else {
        document.getElementById("quantitySection").style.display = "none";
      }

      document.getElementById("hiddenSlot").value = slot;
      updateTotal();
    }

    function closePopup() {
      document.getElementById("bookingPopup").style.display = "none";
    }

    function changeQty(delta) {
      quantity = Math.max(1, quantity + delta);
      document.getElementById("quantity").innerText = quantity;
      updateTotal();
    }

    function updateTotal() {
      let total = (category === "Cooking" || category === "Cleaning") ? basePrice * quantity : basePrice;
      document.getElementById("totalPrice").innerText = total.toFixed(2);
      document.getElementById("hiddenPeople").value = quantity;
      document.getElementById("hiddenPrice").value = total.toFixed(2);
    }
  </script>

</body>

</html>