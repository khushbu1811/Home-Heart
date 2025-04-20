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
            background-color: #795548;
            color: white;
            align-items: center;
            justify-content: center;
        }

        /* Header */
        header {
            background-color: #795548;
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
            color: #795548;
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
            color: #795548;
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
            background-color: #8C6A5C;
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
            background-color: #705248;
        }

        .social-icons {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            margin-top: 10px;
            padding-left: 150px;
        }

        .social-icons a {
            text-decoration: none;
            color: #795548;
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
            background-color: #795548;
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
            color: #795548;
            font-size: 22px;
            text-align: center;
            margin-top: 10px;
        }

        .support-details p {
            margin: 6px 0;
            color: black;
        }

        .support-details strong {
            color: #795548;
        }

        /* Footer */
        footer {
            background-color: #4e342e;
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

        <div class="section">
            <h2>हमसे संपर्क करें</h2>
            <p>यदि आपकी कोई भी समस्या, सुझाव, या सेवा से संबंधित प्रश्न हैं, तो कृपया नीचे दिया गया फ़ॉर्म भरें। हम 24 घंटों के भीतर उत्तर देंगे।</p>
            <form action="/submit-contact" method="POST">
                <label for="name">नाम</label>
                <input type="text" id="name" name="name" required placeholder="अपना नाम दर्ज करें">

                <label for="phone">फ़ोन नंबर</label>
                <input type="tel" id="phone" name="phone" required placeholder="10 अंकों का मोबाइल नंबर दर्ज करें">

                <label for="subject">विषय</label>
                <input type="text" id="subject" name="subject" required placeholder="जैसे: बुकिंग समस्या, भुगतान सहायता, आदि">

                <label for="message">संदेश</label>
                <textarea id="message" name="message" required placeholder="अपनी समस्या या प्रश्न विस्तार से बताएं..."></textarea><br>

                <button type="submit">जमा करें</button>
            </form>
        </div>

        <div class="section">
            <h2>सोशल मीडिया पर हमसे जुड़ें</h2>
            <div class="social-icons">
                <a href="https://facebook.com/homeandheart" target="_blank">
                    <img src="https://cdn-icons-png.flaticon.com/512/733/733547.png" alt="Facebook"> फेसबुक
                </a>
                <a href="https://instagram.com/homeandheart" target="_blank">
                    <img src="https://cdn-icons-png.flaticon.com/512/733/733558.png" alt="Instagram"> इंस्टाग्राम
                </a>
                <a href="https://twitter.com/homeandheart" target="_blank">
                    <img src="https://cdn-icons-png.flaticon.com/512/733/733579.png" alt="Twitter"> ट्विटर
                </a>
                <a href="https://linkedin.com/company/homeandheart" target="_blank">
                    <img src="https://cdn-icons-png.flaticon.com/512/733/733561.png" alt="LinkedIn"> लिंक्डइन
                </a>
                <a href="mailto:support@homeandheart.com">
                    <img src="https://cdn-icons-png.flaticon.com/512/732/732200.png" alt="Email"> ईमेल
                </a>
            </div>
        </div>

        <div class="section">
            <h2>संस्थापक से मिलें</h2>
            <div class="founders">
                <div class="founder">
                    <img src="RG.jpg" alt="Founder 1">
                    <h3>रूपा गुप्ता</h3>
                    <p>सह-संस्थापक और क्रिएटिव डायरेक्टर। रूपा वेब विकास और उपयोगकर्ता अनुभव में माहिर हैं। वह यह सुनिश्चित करती हैं कि प्लेटफ़ॉर्म सभी उपयोगकर्ताओं के लिए सरल और स्वागतपूर्ण हो।</p>
                </div>
                <div class="founder">
                    <img src="KP.jpg" alt="Founder 2">
                    <h3>खुशबू पाटेकर</h3>
                    <p>सह-संस्थापक और संचालन प्रमुख। खुशबू सेवा आधारित प्लेटफ़ॉर्म का संचालन अनुभव रखती हैं। वह ऑनबोर्डिंग, समय-सारणी प्रणाली, और सेवा प्रदाताओं के नेटवर्क का प्रबंधन करती हैं।</p>
                </div>
            </div>
        </div>

        <div class="support-details">
            <h3>Home&Heart सहायता विवरण</h3>
            <p><strong>ईमेल:</strong> support@homeandheart.in</p>
            <p><strong>फ़ोन सहायता:</strong> +91 98765 43210</p>
            <p><strong>कार्यालय समय:</strong> सोमवार – शनिवार | सुबह 9:00 बजे से शाम 6:00 बजे तक</p>
            <p><strong>कार्यालय पता:</strong> तीसरी मंज़िल, ब्लिस कॉम्प्लेक्स, अंधेरी वेस्ट, मुंबई – 400058, भारत</p>
            <p><strong>रिफंड से जुड़ी जानकारी:</strong> यदि बुकिंग के 10 मिनट के भीतर रद्द किया गया तो रिफंड 24 घंटों के भीतर कर दिया जाएगा।</p>
            <p><strong>बुकिंग सहायता:</strong> समय स्लॉट, भुगतान स्थिति या सेवा प्रदाता की अनुपलब्धता से जुड़ी समस्या? हमें बताएं।</p>
        </div>
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

    <script>
        const form = document.querySelector("form");

        form.addEventListener("submit", function(event) {
            event.preventDefault(); // Prevent default form submission

            const phoneInput = document.getElementById("phone");
            const phoneNumber = phoneInput.value.trim();

            // Phone number validation: must be 10 digits only
            const phonePattern = /^[0-9]{10}$/;

            if (!phonePattern.test(phoneNumber)) {
                alert("कृपया 10 अंकों का सही फ़ोन नंबर दर्ज करें।");
                phoneInput.focus();
                return;
            }

            alert("आपका फ़ॉर्म सफलतापूर्वक जमा हो गया है!");
            form.reset(); // Clear form after success
        });
    </script>

</body>

</html>