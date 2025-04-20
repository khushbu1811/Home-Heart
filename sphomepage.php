<!DOCTYPE html>
<html lang="hi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home&Heart - Service Provider Home Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* General Styles */
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

        /* Hero Section */
        .hero {
            text-align: center;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 50px 20px;
            border-radius: 10px;
            margin: 20px;
            background-image: url('https://www.transparenttextures.com/patterns/white-diamond-pattern.png');
            background-repeat: repeat;
        }

        .hero h1 {
            font-size: 3.5em;
            color: #333333;
            animation: slideIn 1s ease-out forwards;
        }

        .hero p {
            font-size: 1.5em;
            color: white;
            margin-top: 15px;
            animation: fadeIn 1.5s ease-out forwards;
        }

        .cta-btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: #8C6A5C;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .cta-btn:hover {
            background-color: #705248;
        }

        .cta-btn:active {
            transform: scale(0.95);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        @keyframes slideIn {
            from {
                transform: translateX(-50%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
            }
        }

        .image-container {
            text-align: center;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 25px 10px;
            border-radius: 10px;
            margin: 80px;
            background-image: url('https://www.transparenttextures.com/patterns/white-diamond-pattern.png');
            background-repeat: repeat;
        }

        .image-container img {
            align-content: center;
            width: 90%;
            max-width: 800px;
            display: block;
            margin: 0 auto;
            /* Centers the image horizontally */
        }

        .info {
            text-align: center;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 25px 10px;
            border-radius: 10px;
            margin: 80px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            background-image: url('https://www.transparenttextures.com/patterns/white-diamond-pattern.png');
            background-repeat: repeat;
        }


        .info img {
            width: 90%;
            max-width: 800px;
            margin: 20px auto;
            display: block;
        }

        .info img {
            width: 90%;
            max-width: 800px;
            margin: 20px auto;
            display: block;
        }

        .faq {
            color: black;
            margin-bottom: 10px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
        }

        .question {
            color: black;
            cursor: pointer;
            font-weight: bold;
        }

        .answer {
            color: black;
            display: none;
            margin-top: 5px;
        }

        .faq .question {
            color: black;
            font-size: clamp(10px, 3.5vw, 16px);
            font-weight: bold;
        }

        .faq .answer {
            color: black;
            font-size: clamp(8px, 3vw, 14px);
        }

        /* Back to Top */
        #backToTop {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #8C6A5C;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 5px;
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
            font-size: clamp(12px, 2vw, 16px);
        }

        /* For smaller screens (max-width 768px) */
        @media (max-width: 768px) {

            /* Header */
            header {
                display: flex;
                justify-content: space-between;
                /* Space between logo & icons */
                align-items: center;
                padding: 10px 15px;
            }

            /* Logo container */
            .logo-container {
                display: flex;
                align-items: center;
                justify-content: flex-start;
                gap: 5px;
                /* Small gap between logo and text */
            }

            /* Logo */
            .logo img {
                height: 35px;
                /* Adjust logo size for smaller screens */
            }

            /* Site name */
            .site-name {
                font-size: 16px;
                /* Smaller text for site name */
                margin-left: 0;
                /* Remove any extra margin */
            }

            /* Navigation Menu */
            nav ul {
                list-style: none;
                display: flex;
                gap: 15px;
                /* Equal gap between icons */
                padding: 0;
                margin: 0;
            }

            nav ul li {
                display: inline-block;
            }

            nav ul li a {
                color: #3e3e3e;
                font-size: 18px;
                /* Adjust font size for smaller screens */
                text-decoration: none;
            }

            /* Flash (Running Text) */
            .running-text-container {
                font-size: 16px;
                /* Smaller font for running text */
            }

            .running-text {
                font-size: 14px;
                /* Smaller text size for scrolling text */
            }
        }


        /* For extra small screens (max-width 480px) */
        @media (max-width: 480px) {

            /* Header */
            header {
                padding: 5px 10px;
            }

            /* Logo */
            .logo img {
                height: 30px;
                /* Further reduce logo size */
            }

            /* Site name */
            .site-name {
                font-size: 14px;
                /* Even smaller text for site name */
                margin-left: 0;
                /* Ensure no gap between logo and text */
            }

            /* Navigation Menu */
            nav ul {
                display: flex;
                gap: 10px;
                /* Equal gap between icons */
                padding: 0;
                margin: 0;
            }

            nav ul li a {
                font-size: 16px;
                /* Reduce text size for menu items */
            }

            /* Flash (Running Text) */
            .running-text-container {
                font-size: 14px;
                /* Smaller font for running text */
            }

            .running-text {
                font-size: 12px;
                /* Reduce scrolling text size */
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

    <!-- Flashing Text -->
    <div class="running-text-container">
        <div class="running-text">!!! केवल अंधेरी तक सीमित !!! </div>
    </div>

    <!-- Main Section -->
    <section class="hero">
        <h1 style="color: black ;font-size: 52px; font-weight: bold; text-align: center;">Home&Heart में आपका स्वागत है!</h1>
        <p style="color: black ;font-size: 22px; font-weight: bold; text-align: center;">सेवाओं को आसानी से प्रबंधित करें</p>
        <a href="spservicespage.php" class="cta-btn">अपनी सेवा चुनें</a>
    </section>

    <div class="image-container">
        <img src="spservice.png" alt="सेवा की छवि" style="color: black; font-weight: bold;">
    </div>

    <!-- How It Works -->
    <section class="info">
        <h2 style="color: black; font-weight: bold;">यह कैसे काम करता है ?</h2>
        <img src="sphowitworks.jpg" alt="यह कैसे काम करता है ? की छवि" style="color: black; font-weight: bold;">
    </section>

    <!-- What We Offer -->
    <section class="info">
        <h2 style="color: black; font-weight: bold;">हम क्या प्रदान करते हैं ?</h2>
        <img src="spwhatweoffer.jpg" alt="हम क्या प्रदान करते हैं ? की छवि" style="color: black; font-weight: bold;">
    </section>

    <!-- Why Choose Us -->
    <section class="info">
        <h2 style="color: black; font-weight: bold;">हमें क्यों चुनें ?</h2>
        <img src="spwhychooseus.jpg" alt="हमें क्यों चुनें ? की छवि" style="color: black; font-weight: bold;">
    </section>

    <!-- FAQs -->
    <section class="info">
        <h2 style="color: black ;font-size: 22px; font-weight: bold; text-align: center;">अक्सर पूछे जाने वाले प्रश्न</h2>
        <div class="faq">
            <div class="question">1. मैं कितनी सेवाएँ दे सकता/सकती हूँ ?</div>
            <div class="answer">केवल एक सेवा श्रेणी चुन सकते हैं, जिसमें 3 समय स्लॉट जोड़ सकते हैं।</div>
        </div>
        <div class="faq">
            <div class="question">2. मुझे ग्राहकों से सेवा अनुरोध कैसे मिलेगा ?</div>
            <div class="answer">ग्राहक आपकी प्रोफ़ाइल से सेवा चुनकर आपको अनुरोध भेजते हैं। आप उस अनुरोध को स्वीकार या
                अस्वीकार कर सकते हैं।</div>
        </div>
        <div class="faq">
            <div class="question">3. भुगतान प्रणाली कैसे काम करती है ?</div>
            <div class="answer">बुकिंग की पुष्टि के लिए सेवा लेने वाले को कुल राशि का 10% Home&Heart को ऑनलाइन भुगतान करना होता है।
                शेष 90% राशि सेवा पूरी होने के बाद आपको नकद में मिलती है।</div>
        </div>
        <div class="faq">
            <div class="question">4. मुझे सूचना कैसे मिलेगी ?</div>
            <div class="answer">सभी बुकिंग और अनुरोध की जानकारी नोटिफिकेशन से मिलेगी।</div>
        </div>
        <div class="faq">
            <div class="question">5. क्या सेवा श्रेणी बदली जा सकती है ?</div>
            <div class="answer">एक बार चयन के बाद सेवा श्रेणी बदली जा सकती है।</div>
        </div>
    </section>

    <!-- Back to Top -->
    <button id="backToTop"> शीर्ष पर जाएं </button>

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