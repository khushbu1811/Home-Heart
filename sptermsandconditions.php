<!DOCTYPE html>
<html lang="hi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>सेवा प्रदाता की नियम और शर्तें - Home&Heart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
            padding: 40px;
            background-color: #ffffff;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            border-radius: 16px;
            text-align: center;
            margin: 30px auto;
        }

        .container h2 {
            text-align: left;
        }


        h1 {
            color: #795548;
            margin-bottom: 20px;
            font-size: 28px;
        }

        h2 {
            color: #795548;
            margin-top: 30px;
            font-size: 20px;
        }

        p {
            font-size: 16px;
            line-height: 1.7;
            color: #3e3e3e;
            margin-bottom: 20px;
            text-align: justify;
        }

        ul {
            padding-left: 20px;
        }

        li {
            margin-bottom: 10px;
            color: #3e3e3e;

        }

        .left-list {
            text-align: left;
            margin-left: 0;
        }

        .left-list ul {
            padding-left: 20px;
            list-style-type: disc;
        }

        @media (max-width: 600px) {
            .container {
                padding: 20px;
            }

            h1 {
                font-size: 1.5rem;
            }

            h2 {
                font-size: 1.2rem;
            }

            p {
                font-size: 0.95rem;
            }
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
        <h1>सेवा प्रदाताओं के लिए नियम और शर्तें</h1>

        <p>Home&Heart प्लेटफॉर्म पर सेवा प्रदाता के रूप में पंजीकरण और सेवाएं प्रदान करने से पहले, कृपया निम्नलिखित नियमों और शर्तों को ध्यानपूर्वक पढ़ें। यह नियम और शर्तें आपके और Home&Heart के बीच एक कानूनी अनुबंध है।</p>

        <h2>1. खाता निर्माण और प्रोफ़ाइल जानकारी</h2>
        <div class="left-list">
        <ul>
            <li>सेवा प्रदाता को खाता बनाने के लिए वैध मोबाइल नंबर, नाम, पता, और सेवा की जानकारी प्रदान करनी होगी।</li>
            <li>प्रोफ़ाइल सत्यापन के बाद ही सेवाएं प्रदान करने की अनुमति दी जाएगी।</li>
            <li>प्रत्येक सेवा प्रदाता केवल एक सेवा श्रेणी (जैसे सफाई, खाना बनाना आदि) चुन सकता है और 3 समय स्लॉट निर्धारित कर सकता है।</li>
        </ul>
        </div>

        <h2>2. सेवा और मूल्य निर्धारण</h2>
        <div class="left-list">
        <ul>
            <li>सेवा प्रदाता केवल वही सेवाएं प्रदान कर सकता है जो उसने अपने प्रोफ़ाइल में सूचीबद्ध की हों।</li>
            <li>सेवा की कीमत और समय स्लॉट सेवा प्रदाता द्वारा तय किए जाएंगे, जिन्हें बाद में संपादित किया जा सकता है।</li>
        </ul>
        </div>

        <h2>3. बुकिंग प्रक्रिया</h2>
        <div class="left-list">
        <ul>
            <li>सेवा खोजने वाले उपयोगकर्ता बुकिंग अनुरोध भेज सकते हैं, जिसे सेवा प्रदाता स्वीकार, अस्वीकार या लंबित रख सकता है।</li>
            <li>बुकिंग स्वीकार करने के बाद, उपयोगकर्ता 10% अग्रिम भुगतान "Home&Heart" के बैंक खाते में करेगा।</li>
            <li>शेष 90% राशि सेवा पूरी होने के बाद नकद में दी जाएगी।</li>
        </ul>
        </div>

        <h2>4. अनुरोध स्वीकार करना या अस्वीकार करना</h2>
        <p>सेवा प्रदाता सेवा अनुरोध को स्वीकार, अस्वीकार या पेंडिंग रख सकते हैं। एक बार जब सेवा अनुरोध स्वीकार कर लिया जाता है और उपयोगकर्ता द्वारा 10% भुगतान हो जाता है, तब सेवा प्रदाता उस अनुरोध को रद्द नहीं कर सकता।</p>

        <h2>5. भुगतान और प्रतिबद्धता</h2>
        <p>सेवा की पुष्टि पर उपयोगकर्ता द्वारा 10% अग्रिम भुगतान Home&Heart को किया जाएगा, और शेष 90% नकद में सेवा के पूर्ण होने के बाद सेवा प्रदाता को मिलेगा। सेवा प्रदाता को समय पर सेवा प्रदान करना अनिवार्य है।</p>

        <h2>6. सेवा नियमों का उल्लंघन</h2>
        <p>किसी भी प्रकार का धोखा, सेवा में लापरवाही, अनुचित व्यवहार, या समय पर सेवा न देने पर सेवा प्रदाता का खाता निलंबित या प्रतिबंधित किया जा सकता है। Home&Heart इस निर्णय के लिए उत्तरदायी नहीं होगा।</p>

        <h2>7. गोपनीयता और सुरक्षा</h2>
        <p>सेवा प्रदाता द्वारा दी गई सारी जानकारी सुरक्षित रखी जाएगी और गोपनीयता नीति के तहत संरक्षित रहेगी। किसी तीसरे पक्ष के साथ जानकारी साझा नहीं की जाएगी जब तक कि कानूनी आवश्यकता न हो।</p>

        <h2>8. नियमों में परिवर्तन</h2>
        <p>Home&Heart समय-समय पर इन नियमों में परिवर्तन कर सकता है। किसी भी प्रकार का बदलाव वेबसाइट पर अपडेट किया जाएगा। सेवा प्रदाता को सलाह दी जाती है कि वे समय-समय पर इन नियमों की समीक्षा करते रहें।</p>

        <h2>9. सहमति</h2>
        <p>सेवा प्रदाता के रूप में पंजीकरण करने के बाद, आप इन सभी नियमों और शर्तों से सहमत होते हैं और उनका पालन करने के लिए बाध्य होते हैं।</p>
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