<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "home_and_heart";

// Connect to DB
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get filters
$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';
$gender = $_GET['gender'] ?? '';
$sort = $_GET['sort'] ?? '';

// SQL query
$sql = "SELECT spp.sp_id, fname, lname, age, spp.address, gender, 
               ss.category, ss.time_slot_1, ss.time_slot_2, ss.time_slot_3, ss.price, ss.notes
        FROM spprofile spp
        LEFT JOIN spservices ss ON spp.sp_id = ss.sp_id
        WHERE 1";

if (!empty($search)) {
    $sql .= " AND (fname LIKE '%$search%' OR lname LIKE '%$search%' OR spp.address LIKE '%$search%' OR spp.sp_id LIKE '%$search%')";
}
if (!empty($category)) {
    $sql .= " AND ss.category = '$category'";
}
if (!empty($gender)) {
    $sql .= " AND gender = '$gender'";
}
if ($sort == "age_asc") {
    $sql .= " ORDER BY age ASC";
} elseif ($sort == "age_desc") {
    $sql .= " ORDER BY age DESC";
} elseif ($sort == "price_asc") {
    $sql .= " ORDER BY ss.price ASC";
} elseif ($sort == "price_desc") {
    $sql .= " ORDER BY ss.price DESC";
} elseif ($sort == "time_slot_asc") {
    $sql .= " ORDER BY ss.time_slot_1 ASC";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Browse Service Providers</title>
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
      text-align: center;
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

    main {
      display: flex;
      justify-content: center;
      /* centers horizontally */
      align-items: center;
      /* centers vertically (optional) */
      flex-direction: column;
      /* keeps things stacked vertically */
      min-height: 80vh;
      /* adjusts height of main section */
    }

    .form-container {
      width: 90%;
      max-width: 700px;
      background-color: white;
      padding: 30px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      border-radius: 8px;
      border: 2px solid #afafaf;
      margin: 30px 0;
      /* 20px space at top and bottom */
    }

    .container {
      display: flex;
      align-items: center;
      /* Centers vertically */
      padding-left: 20%;
    }

    h2 {
      text-align: center;
      color: #00796b;
    }

    .form-heading {
      text-align: center;
      color: #00796b;
      font-size: 16px;
      margin-bottom: 20px;
    }

    form {
      display: flex;
      flex-wrap: wrap;
      gap: 15px;
      justify-content: left;
      align-items: left;
      margin-top: 15px;
    }

    form input[type="text"],
    form select {
      padding: 10px;
      font-size: 14px;
      border: 1.5px solid #ccc;
      border-radius: 5px;
      width: 125px;
      background-color: #f9f9f9;
      transition: border-color 0.3s;
    }

    form input[type="text"]:focus,
    form select:focus {
      border-color: #00796b;
      outline: none;
    }

    form button[type="submit"] {
      background-color: #4a7c6a;
      color: white;
      padding: 10px 18px;
      border: none;
      border-radius: 5px;
      font-size: 14px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    form button[type="submit"]:hover {
      background-color: #396555;
    }

    .card {
      background-color: white;
      border: 1px solid #ccc;
      border-radius: 8px;
      padding: 20px;
      width: 93%;
      max-width: 650px;
      margin-top: 20px;
      margin-bottom: 20px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      position: relative;
    }

    .card strong {
      color: #00796b;
    }

    .card a {
      display: inline-block;
      margin-top: 10px;
      color: #00796b;
      text-decoration: none;
      font-weight: bold;
    }

    .card a:hover {
      text-decoration: underline;
    }

    .view-button {
      position: absolute;
      top: 50%;
      right: 20px;
      transform: translateY(-50%);
    }

    .view-profile-btn {
      display: inline-block;
      background-color: #4a7c6a;
      color: white !important;
      padding: 10px 20px;
      border: none;
      border-radius: 6px;
      text-decoration: none;
      font-size: 14px;
      font-weight: bold;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .view-profile-btn:hover {
      background-color: #396555;
      text-decoration: none;
    }

    #backToTop {
      position: fixed;
      bottom: 20px;
      right: 20px;
      background-color: #4a7c6a;
      color: white;
      border: none;
      padding: 10px 15px;
      cursor: pointer;
      border-radius: 5px;
      transform: scale(0.95);
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    /* Footer */
    footer {
      background-color: #4a4a4a;
      color: white;
      padding: 10px;
      margin-top: 20px;
      text-align: center;
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

  <main>
    <div class="form-container">
      <div class="form-heading">
        <h2>Search & Filter Service Providers</h2>
      </div>

      <form method="GET">
        <input type="text" name="search" placeholder="Search by Name, Address or SPID" value="<?= htmlspecialchars($search) ?>">
        <select name="category">
          <option value="">All Categories</option>
          <!-- Populate categories dynamically from spservices table -->
          <?php
          $catQuery = "SELECT DISTINCT category FROM spservices WHERE category IS NOT NULL AND category != ''";
          $catResult = $conn->query($catQuery);
          while ($catRow = $catResult->fetch_assoc()) {
            $cat = htmlspecialchars($catRow['category']);
            $selected = ($category == $cat) ? 'selected' : '';
            echo "<option value=\"$cat\" $selected>$cat</option>";
          }
          ?>
        </select>
        <select name="gender">
          <option value="">All Genders</option>
          <option value="Female" <?= $gender == 'Female' ? 'selected' : '' ?>>Female</option>
          <option value="Male" <?= $gender == 'Male' ? 'selected' : '' ?>>Male</option>
          <option value="Other" <?= $gender == 'Other' ? 'selected' : '' ?>>Other</option>
        </select>
        <select name="sort">
          <option value="">Sort By</option>
          <option value="age_asc" <?= $sort == 'age_asc' ? 'selected' : '' ?>>Age: Low to High</option>
          <option value="age_desc" <?= $sort == 'age_desc' ? 'selected' : '' ?>>Age: High to Low</option>
        </select>
        <button type="submit">Apply Filters</button>
      </form>

      <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <div class="card">
            <strong>SP ID :</strong> <?= htmlspecialchars($row['sp_id']) ?><br>
            <strong>First Name :</strong> <?= htmlspecialchars($row['fname']) ?><br>
            <strong>Last Name :</strong> <?= htmlspecialchars($row['lname']) ?><br>
            <strong>Category :</strong> <?= htmlspecialchars($row['category']) ?><br>
            <strong>Age :</strong> <?= $row['age'] ?><br>
            <strong>Address :</strong> <?= htmlspecialchars($row['address']) ?><br>
            <strong>Gender :</strong> <?= $row['gender'] ?><br>
            <div class="view-button">
              <a href="spbooking.php?sp_id=<?= $row['sp_id'] ?>&category=<?= urlencode($row['category']) ?>" class="view-profile-btn">View Profile</a>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p>No service providers found.</p>
      <?php endif; ?>

      <?php $conn->close(); ?>
  </main>

  <!-- Back to Top -->
  <button id="backToTop"> Back To Top </button>

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
    // Back to Top Button
    document.getElementById("backToTop").addEventListener("click", function() {
      window.scrollTo({
        top: 0,
        behavior: "smooth"
      });
    });

    document.querySelectorAll('.question').forEach(question => {
      question.addEventListener('click', () => {
        const answer = question.nextElementSibling;
        answer.style.display = answer.style.display === 'none' || answer.style.display === '' ? 'block' : 'none';
      });
    });
  </script>

</body>
</html>
