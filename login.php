<?php
require 'db.php'; // Include database connection
session_start(); // Start a session

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Fetch user from database
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    
    // Check if user exists
    if ($stmt->num_rows == 1) {
        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();
        
        // Verify password
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;
            header("Location: chat.php"); // Redirect to chat page
            exit;
        } else {
            echo "Invalid username or password.";
        }
    } else {
        echo "Invalid username or password.";
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" type="image/x-icon" href="main.jpg">
    <!-- <link rel="stylesheet" href="login.css"> -->
</head>
<style>
    /* Reset Default Styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Inter', sans-serif;
}

body {
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #f4f4f4;
    height: 100vh;
}

/* Login Container */
.login-container {
    background: linear-gradient(to right, #007BFF, #0056b3); /* Soft blue gradient */
    width: 70vw;
    max-width: 400px;
    padding: 30px;
    border-radius: 20px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
    backdrop-filter: blur(12px);
    text-align: center;
    color: white;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

/* Login Title */
.login-container h2 {
    margin-bottom: 20px;
    font-size: 26px;
}

/* Form Styling */
form {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

input {
    width: 100%;
    padding: 12px;
    border-radius: 10px;
    border: none;
    outline: none;
    background: rgba(255, 255, 255, 0.2);
    color: black;
    backdrop-filter: blur(8px);
    font-size: 16px;
}

/* Button Styling */
button {
    padding: 12px;
    font-size: 16px;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    background: #191919;
    color: white;
    font-weight: bold;
    transition: 0.3s;
}

/* button:hover {
    background: rgba(255, 255, 255, 0.5);
} */
/* Register Link */
.register-link {
    margin-top: 15px;
    font-size: 14px;
}

.register-link a {
    color: #ffcc00; /* Gold color */
    text-decoration: none;
    font-weight: bold;
    transition: 0.3s;
}

.register-link a:hover {
    color: #ffd700; /* Slightly brighter on hover */
}

</style>
<body>
<div class="login-container">
        <h2>Login</h2>
        <form method="POST">
            <label>Username:</label>
            <input type="text" name="username" required>
            <label>Password:</label>
            <input type="password" name="password" required>
            <button type="submit">Login</button>
        </form>
        <p class="register-link">Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</body>
</html>
