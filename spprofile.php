<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "home_and_heart";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$phone = $_SESSION['phone'] ?? '';

if (!empty($phone)) {
    $signup_sql = "SELECT fname, lname FROM spsignup WHERE phone = ?";
    $stmt = $conn->prepare($signup_sql);
    $stmt->bind_param("s", $phone);
    $stmt->execute();
    $signup_result = $stmt->get_result();
    
    if ($signup_result->num_rows > 0) {
        $signup_row = $signup_result->fetch_assoc();
        $fname = $signup_row['fname'];
        $lname = $signup_row['lname'];
    } else {
        $fname = '';
        $lname = '';
    }
} else {
    $fname = '';
    $lname = '';
}


$gender = $age = $address = "";
$profile_photo = $identity_proof = "";
$sp_id = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $gender = $_POST['gender'];
    $age = $_POST['age'];
    $address = $_POST['address'];

    if (empty($gender) || empty($age) || empty($address)) {
        die("सभी फ़ील्ड भरना अनिवार्य है।");
    }

    if (!empty($_FILES['profile_photo']['name'])) {
        $profile_photo = 'uploads/' . uniqid() . "_" . basename($_FILES['profile_photo']['name']);
        move_uploaded_file($_FILES['profile_photo']['tmp_name'], $profile_photo);
    }

    if (!empty($_FILES['identity_proof']['name'])) {
        $identity_proof = 'uploads/' . uniqid() . "_" . basename($_FILES['identity_proof']['name']);
        move_uploaded_file($_FILES['identity_proof']['tmp_name'], $identity_proof);
    }

    $sql = "SELECT * FROM spprofile WHERE phone = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $phone);
    $stmt->execute();
    $result = $stmt->get_result();
    $userExists = $result->num_rows > 0;

    if ($userExists) {
        $row = $result->fetch_assoc();
        $sp_id = $row['sp_id'];

        $profile_photo = empty($profile_photo) ? $row['profile_photo'] : $profile_photo;
        $identity_proof = empty($identity_proof) ? $row['identity_proof'] : $identity_proof;

        $update_sql = "UPDATE spprofile SET gender=?, age=?, address=?, profile_photo=?, identity_proof=? WHERE phone=?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("sissss", $gender, $age, $address, $profile_photo, $identity_proof, $phone);
        $stmt->execute();
        echo "<script>alert('प्रोफ़ाइल सफलतापूर्वक अपडेट हो गई है!'); window.location.href='sphomepage.php';</script>";
    } else {
        do {
            $sp_id = "SP" . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            $check_sql = "SELECT sp_id FROM spprofile WHERE sp_id = ?";
            $stmt = $conn->prepare($check_sql);
            $stmt->bind_param("s", $sp_id);
            $stmt->execute();
            $check_result = $stmt->get_result();
        } while ($check_result->num_rows > 0);

        $insert_sql = "INSERT INTO spprofile (sp_id, phone, fname, lname, gender, age, address, profile_photo, identity_proof) 
                       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("sssssisss", $sp_id, $phone, $fname, $lname, $gender, $age, $address, $profile_photo, $identity_proof);
        $stmt->execute();
        echo "<script>alert('होम एंड हार्ट में आपका स्वागत है! आपका SP ID है : $sp_id'); window.location.href='sphomepage.php';</script>";
    }
}

if (isset($stmt) && $stmt instanceof mysqli_stmt) {
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home&Heart - Service Provider Profile Creation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #795548;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            /* Ensures items are stacked vertically */
            justify-content: center;
            align-items: center;
        }

        /* Flash */
        .running-text-container {
            width: 100vw;
            /* Full screen width */
            overflow: hidden;
            white-space: nowrap;
            background-color: #ffffff;
            padding: 10px 0;
            font-weight: bold;
            text-align: center;
            margin-bottom: 10px;
            /* Space between flash text and container */
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

        .form-container {
            width: 90%;
            max-width: 500px;
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
            color: #795548;
        }

        .form-heading {
            text-align: center;
            color: #795548;
            font-size: 16px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            color: #795548;
            margin-bottom: 5px;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #afafaf;
            background-color: #ffffff;
            border-radius: 4px;
            box-sizing: border-box;
            box-shadow: none;
            outline: none;
            margin-bottom: 10px;
            /* Adds space between textboxes */
        }

        button {
            background-color: #8C6A5C;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 18px;
        }

        button:hover {
            background-color: #705248;
        }

        .image-preview {
            width: 150px;
            height: 150px;
            border: 1px solid #8C6A5C;
            border-radius: 50%;
            /* Makes it circular */
            display: block;
            margin: 0 17%;
            justify-content: center;
            /* Horizontal centering */
            align-items: center;
            /* Vertical centering */
            text-align: center;
            background-color: #f0f0f0;
            color: #3e3e3e;
            position: relative;
            /* Required for pseudo-element */
        }

        .image-preview:empty::before {
            content: "Profile Photo Preview";
            /* Text appears when image is empty */
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            /* Perfectly centers text */
            color: #3e3e3e;
            font-size: 16px;
            /* Adjust size as needed */
        }

        #identityProofPreview {
            background-color: #dfeeea;
            color: #795548;
            padding: 5px 10px;
            border-radius: 6px;
            display: inline-block;
            width: 100%;
            /* Same width as the text box */
            word-wrap: break-word;
            /* Breaks long text to the next line */
            overflow-wrap: break-word;
            /* Ensures proper wrapping */
            box-sizing: border-box;
            /* Ensures padding doesn’t exceed width */
        }

        input[type="file"] {
            border: none;
            background-color: #e8f0fe;
            cursor: pointer;
        }

        input[type="file"]:hover {
            background-color: #d0e3f0;
        }
    </style>
</head>

<body>
    <!-- Flashing Text -->
    <div class="running-text-container">
        <div class="running-text">!!! केवल अंधेरी तक ही सीमित !!!</div>
    </div>
    <div class="form-container">
        <div class="form-heading">
            <h2>सेवा प्रदाता प्रोफ़ाइल</h2>
        </div>
        <form method="post" enctype="multipart/form-data" onsubmit="return validateForm()" novalidate>
            <div class="form-group">
                <label>प्रोफ़ाइल फ़ोटो</label>
                <input type="file" id="profile_photo" name="profile_photo" accept="image/*"
                    onchange="previewImage('profile_photo', 'profilePhotoPreview')">
                <br>
                <div class="container">
                    <img id="profilePhotoPreview" class="image-preview"
                        src="<?= !empty($profile_photo) ? htmlspecialchars($profile_photo) : '' ?>" />
                </div>
                <br>
                <?php if (!empty($profile_photo)) echo "<img src='$profile_photo' width='100' height='100'><br>"; ?>
            </div>
            <div class="form-group">
                <label>पहला नाम</label>
                <input type="text" name="fname" value="<?= htmlspecialchars($fname) ?>" readonly><br>
            </div>
            <div class="form-group">
                <label>अंतिम नाम</label>
                <input type="text" name="lname" value="<?= htmlspecialchars($lname) ?>" readonly><br>
            </div>
            <div class="form-group">
                <label>फ़ोन नंबर</label>
                <input type="text" name="phone" value="<?= htmlspecialchars($phone) ?>" readonly><br>
            </div>
            <div class="form-group">
                <label>लिंग</label>
                <select name="gender" required>
                    <option value="Male" <?= ($gender == 'Male') ? 'selected' : '' ?>>पुरुष</option>
                    <option value="Female" <?= ($gender == 'Female') ? 'selected' : '' ?>>महिला</option>
                    <option value="Other" <?= ($gender == 'Other') ? 'selected' : '' ?>>अन्य</option>
                </select><br>
            </div>
            <div class="form-group">
                <label>उम्र</label>
                <input type="number" name="age" value="<?= htmlspecialchars($age) ?>" required><br>
            </div>
            <div class="form-group">
                <label>पता</label>
                <select name="address" required>
                    <option value="Chakala" <?= ($address == 'Chakala') ? 'selected' : '' ?>>चकला - 400099</option>
                    <option value="Marol" <?= ($address == 'Marol') ? 'selected' : '' ?>>मरोल - 400059</option>
                    <option value="Sakinaka" <?= ($address == 'Sakinaka') ? 'selected' : '' ?>>साकीनाका - 400072</option>
                    <option value="Mogra" <?= ($address == 'Mogra') ? 'selected' : '' ?>>मोघरा - 400058</option>
                    <option value="Versova" <?= ($address == 'Versova') ? 'selected' : '' ?>>वर्सोवा - 400061</option>
                    <option value="Juhu" <?= ($address == 'Juhu') ? 'selected' : '' ?>>जुहू - 400049</option>
                    <option value="Four Bungalows" <?= ($address == 'Four Bungalows') ? 'selected' : '' ?>>फोर बंगालोज - 400053</option>
                    <option value="Lokhandwala Complex" <?= ($address == 'Lokhandwala Complex') ? 'selected' : '' ?>>लोखंडवाला कॉम्प्लेक्स - 400053</option>
                </select><br>
            </div>
            <div class="form-group">
                <label>पहचान प्रमाण (PDF)</label>
                <input type="file" id="identity_proof" name="identity_proof" accept="application/pdf"
                    onchange="previewPDF('identity_proof', 'identityProofPreview')">
                <br>
                <span id="identityProofPreview" style="display:none;">पीडीएफ फ़ाइल चुनी गई :<span id="pdfFileName"></span></span>
                <br>
                <?php if (!empty($identity_proof)) echo "<a href='$identity_proof' target='_blank'>View Identity Proof</a><br>"; ?>
            </div>
            <div class="form-group">
                <button type="submit">प्रोफ़ाइल बनाएँ</button>
            </div>
        </form>
    </div>
    <script>
        function previewPDF(inputId, previewId) {
            var input = document.getElementById(inputId);
            var preview = document.getElementById(previewId);
            var fileNameSpan = document.getElementById("pdfFileName");

            if (input.files.length > 0) {
                fileNameSpan.innerText = input.files[0].name;
                preview.style.display = "block";
            } else {
                preview.style.display = "none";
            }
        }

        function previewImage(inputId, imgId) {
            var input = document.getElementById(inputId);
            var img = document.getElementById(imgId);

            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    img.src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function validateForm() {
            var age = document.querySelector('input[name="age"]').value;
            if (age < 18) {
                alert("उम्र कम से कम 18 वर्ष होनी चाहिए।");
                return false;
            }
            return true;
        }
    </script>
</body>

</html>