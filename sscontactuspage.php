<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us - Home&Heart</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <!--<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto&display=swap">-->
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
      background-color: #00796b;
      color: #3e3e3e;
      align-items: center;
      justify-content: center;
    }

    /* Header */
    header {
      background-color: #00796b;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 5px 15px;
      padding-bottom: 10px;
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
      max-width: 900px;
      margin: 40px auto;
      padding: 0 20px;
    }

    .section {
      background: white;
      border-radius: 12px;
      padding: 30px;
      margin-bottom: 30px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.08);
    }

    .section h2 {
      color: #00796b;
      margin-top: 2px;
      margin-bottom: 20px;
      text-align: center;
    }

    .section p {
      color: black;
    }

    form label {
      display: block;
      margin: 12px 0 5px;
      color: #00796b;
      font-weight: bold;
    }

    form input,
    form textarea {
      width: 97%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 8px;
      margin-bottom: 10px;
      font-size: 14px;
    }

    form textarea {
      resize: vertical;
      height: 120px;
    }

    form button {
      background-color: #4a7c6a;
      color: white;
      padding: 10px 25px;
      margin-left: 43%;
      font-size: 16px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      margin-top: 10px;
    }

    form button:hover {
      background-color: #396555;
    }

    .social-icons {
      display: flex;
      gap: 20px;
      flex-wrap: wrap;
      margin-top: 10px;
      padding-left: 110px;
    }

    .social-icons a {
      text-decoration: none;
      color: #00796b;
      font-size: 18px;
      display: flex;
      align-items: center;
    }

    .social-icons img {
      width: 30px;
      margin-right: 8px;
    }

    .founders {
      display: flex;
      flex-wrap: wrap;
      gap: 30px;
      margin-top: 20px;
    }

    .founder {
      flex: 1 1 250px;
      text-align: center;
      padding: 20px;
      border-radius: 10px;
      background-color: #00796b;
    }

    .founder img {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      margin-bottom: 10px;
      object-fit: cover;
      border: 3px solid white;
    }

    .founder h3 {
      margin: 10px 0 5px;
      color: white;
    }

    .founder p {
      font-size: 15px;
      color: white;
    }

    .support-details {
      background-color: white;
      padding: 20px;
      border-radius: 10px;
      margin-top: 20px;
    }

    .support-details h3 {
      margin-bottom: 10px;
      color: #00796b;
      font-size: 22px;
      text-align: center;
      margin-top: 10px;
    }

    .support-details p {
      margin: 6px 0;
    }

    .support-details strong {
      color: #00796b;
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
      color: white;
      text-align: center;
      font-size: clamp(12px, 2vw, 16px);
    }

    @media (max-width: 768px) {
      .founders {
        flex-direction: column;
        align-items: center;
      }
    }
  </style>
</head>

<body>

  <header>
    <div class="logo-container">
      <div class="logo"><img src="home&heartlogo.jpg" alt="HomelyHeart Logo"></div>
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

    <div class="section">
      <h2>We’d love to hear from you!</h2>
      <p>If you have any questions, suggestions, or concerns regarding our services or platform, please fill out the form below. We’re here to help and will get back to you within 24 hours.</p>
      <form action="/submit-contact" method="POST">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" required placeholder="Enter the Name">

        <label for="phone">Phone Number</label>
        <input type="tel" id="phone" name="phone" required placeholder="Enter the Phone No.">

        <label for="subject">Subject</label>
        <input type="text" id="subject" name="subject" required placeholder="Booking issue, payment support, etc.">

        <label for="message">Message</label>
        <textarea id="message" name="message" required placeholder="Describe your Query or Issue..."></textarea><br>

        <button type="submit">Submit</button>
      </form>
    </div>

    <div class="section">
      <h2>Connect with us on Social Media</h2>
      <div class="social-icons">
        <a href="https://facebook.com/homeandheart" target="_blank">
          <img src="https://cdn-icons-png.flaticon.com/512/733/733547.png" alt="Facebook"> Facebook
        </a>
        <a href="https://instagram.com/homeandheart" target="_blank">
          <img src="https://cdn-icons-png.flaticon.com/512/733/733558.png" alt="Instagram"> Instagram
        </a>
        <a href="https://twitter.com/homeandheart" target="_blank">
          <img src="https://cdn-icons-png.flaticon.com/512/733/733579.png" alt="Twitter"> Twitter
        </a>
        <a href="https://linkedin.com/company/homeandheart" target="_blank">
          <img src="https://cdn-icons-png.flaticon.com/512/733/733561.png" alt="LinkedIn"> LinkedIn
        </a>
        <a href="mailto:support@homeandheart.com">
          <img src="https://cdn-icons-png.flaticon.com/512/732/732200.png" alt="Email"> Email
        </a>
      </div>
    </div>

    <div class="section">
      <h2>Meet the Founders</h2>
      <div class="founders">
        <div class="founder">
          <img src="RG.jpg" alt="Founder 1">
          <h3>Rupa Gupta</h3>
          <p>Co-Founder & Creative Director of Home&Heart. Rupa specializes in web development and user experience. She ensures the platform is functional, inclusive, and welcoming to all users.</p>
        </div>
        <div class="founder">
          <img src="KP.jpg" alt="Founder 2">
          <h3>Khushbu Patekar</h3>
          <p>Co-Founder & Operations Head. Khushbu brings years of experience in managing service-based platforms. He manages onboarding, scheduling systems, and the SP network.</p>
        </div>
      </div>
    </div>

    <div class="support-details">
      <h3>Home&Heart Support Details</h3>
      <p><strong>Email:</strong> support@homeandheart.in</p>
      <p><strong>Phone Support:</strong> +91 98765 43210</p>
      <p><strong>Office Hours:</strong> Mon – Sat | 9:00 AM to 6:00 PM</p>
      <p><strong>Office Address:</strong> 3rd Floor, Bliss Complex, Andheri West, Mumbai – 400058, India</p>
      <p><strong>Refund Queries:</strong> Refunds are processed within 24 hours if cancellation is made within 10 minutes of booking.</p>
      <p><strong>Booking Support:</strong> Issues with time slots, payment status, or service provider unavailability? Let us know.</p>
    </div>
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
    const form = document.querySelector("form");

    form.addEventListener("submit", function(event) {
      event.preventDefault(); // Prevent default form submission

      const phoneInput = document.getElementById("phone");
      const phoneNumber = phoneInput.value.trim();

      // Phone number validation: must be 10 digits only
      const phonePattern = /^[0-9]{10}$/;

      if (!phonePattern.test(phoneNumber)) {
        alert("Phone number must be exactly 10 digits.");
        phoneInput.focus();
        return;
      }

      alert("Your form has been submitted successfully!");
      form.reset(); // Clear form after success
    });
  </script>

</body>

</html>