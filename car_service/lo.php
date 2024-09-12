<?php
session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $role = 'client'; // Set role to client by default
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    // Client registration
    if (isset($_POST['register'])) {
        // Check if email already exists
        $stmt = $conn->prepare('SELECT id FROM clients WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "An account with this email already exists.";
        } else {
            $stmt->close();
            // Proceed with registration
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare('INSERT INTO clients (username, email, password) VALUES (?, ?, ?)');
            $stmt->bind_param('sss', $username, $email, $hashed_password);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $message = "Registration successful. Please login.";
            } else {
                $error = "Registration failed: " . $stmt->error;
            }
        }
        $stmt->close();
    } else {
        // Client login
        $stmt = $conn->prepare('SELECT id, username, email, password FROM clients WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $username, $email, $hashed_password);
            $stmt->fetch();
            if (password_verify($password, $hashed_password)) {
                $_SESSION['clients'] = true;
                header('Location: index.php');
                exit();
            } else {
                $error = "Invalid client credentials!";
            }
        } else {
            $error = "Invalid client credentials!";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url("assets/2.jpg");
            background-size: cover;
            font-family: Arial, sans-serif;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .login-container h2 {
            margin-bottom: 20px;
            font-size: 24px;
        }

        .login-container label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        .login-container input, .login-container select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .login-container button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        .login-container button:hover {
            background-color: #45a049;
        }

        .login-container p {
            color: black;
            margin-top: 10px;
        }

        .login-container .switch {
            color: #007BFF;
            cursor: pointer;
        }
        .login-container .switch1 {
            color: red;
            cursor: pointer;
        }
    </style>
    <script>
        function showLogin() {
            document.getElementById('login-form').style.display = 'block';
            document.getElementById('register-form').style.display = 'none';
            document.getElementById('form-title').innerText = 'Client Login';
        }

        function showRegister() {
            document.getElementById('login-form').style.display = 'none';
            document.getElementById('register-form').style.display = 'block';
            document.getElementById('form-title').innerText = 'Client Registration';
        }

        window.onload = function() {
            showRegister(); // Call this function to set the default view
        }
    </script>
</head>
<body>
    <div class="login-container">
        <h2 id="form-title">Client Registration</h2>

        <form id="register-form" method="post">
            <label for="username">Username</label>
            <input type="text" name="username" required>
            <label for="email">Email</label>
            <input type="email" name="email" required>
            <label for="password">Password</label>
            <input type="password" name="password" required>
            <button type="submit" name="register">Register</button>
            <?php if (isset($error)) echo "<p>$error</p>"; ?>
            <?php if (isset($message)) echo "<p style='color:green;'>$message</p>"; ?>
            <p id="login-link" class="switch" onclick="showLogin()">Already have an account? Login here.</p>
        </form>

        <form id="login-form" method="post" style="display:none;">
            <label for="username">Username</label>
            <input type="text" name="username" required>
            <label for="email">Email</label>
            <input type="email" name="email" required>
            <label for="password">Password</label>
            <input type="password" name="password" required>
            <button type="submit">Login</button>
            <?php if (isset($error)) echo "<p>$error</p>"; ?>
            <p id="register-link" class="switch" onclick="showRegister()">Don't have an account? Register here.</p>
        </form>
        <br>
        <p id="register-link" class="switch1" onclick="window.location.href='login.php'">ADMIN</p>
    </div>
</body>
</html>
