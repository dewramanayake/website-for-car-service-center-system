<!DOCTYPE html>
<html lang="en">
<head>
    <title>Contact Us</title>
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        
        .background {
            background-image: url('assets/side.avif'); 
            background-size: cover;
            background-position: center;
            height: 100%;
            width: 100%;
            position: absolute;
            top: 0;
            left: 0;
            z-index: -1;
        }
        
        .content {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: white;
            text-align: center;
            background: rgba(0, 0, 0, 0.5);
        }
        
        h2 {
            font-size: 3em;
            margin-bottom: 0.5em;
        }
        
        p {
            font-size: 1.2em;
            margin-bottom: 1em;
            max-width: 600px;
        }
        
        .form-container {
            background: rgba(0, 0, 0, 0.7);
            padding: 20px;
            border-radius: 15px;
        }
        
        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }
        
        input, textarea {
            width: 80%;
            padding: 10px;
            margin-bottom: 20px;
            border: none;
            border-radius: 5px;
        }
        
        button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background: #ff9900;
            color: white;
            font-size: 1em;
            cursor: pointer;
        }
        
        button:hover {
            background: #e68a00;
        }
    </style>
</head>
<body>
    
<div class="background"></div>
    <div class="content">
        <h2>What do you want to say?</h2>
        <p>We value your feedback and inquiries. Please fill out the form below to get in touch with us. Our team will respond to your message as soon as possible.</p>
        <div class="form-container">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" id="contactForm">
                <input type="hidden" name="access_key" value="aeecd2da-f50a-4451-979c-ab45a92956a3">
                <label for="fullName">Your Name</label>
                <input type="text" id="fullName" name="fullName" required>
                
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required>
                
                <label for="message">Your Message</label>
                <textarea id="message" name="message" rows="5" required></textarea>
                
                <button type="submit">SUBMIT</button>
                <button type="button" onclick="goBack()">BACK</button>
            </form>
        </div>
    </div>
    <script>
        function goBack() {
            document.getElementById('contactForm').reset();
            window.location.href = 'index.php';
        }
    </script>
</body>
</html>


<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection settings
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "car_service";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $fullName = $conn->real_escape_string($_POST['fullName']);
    $email = $conn->real_escape_string($_POST['email']);
    $message = $conn->real_escape_string($_POST['message']);

    $sql = "INSERT INTO appointments (name, email, message) VALUES ('$fullName', '$email', '$message')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Thank you for your message. We will get back to you shortly!');</script>";
    } else {
        echo "<script>alert('There was a problem with your submission. Please try again.');</script>";
    }

    $conn->close();
}
?>
