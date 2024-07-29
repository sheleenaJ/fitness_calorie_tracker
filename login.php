<?php
// Include the database configuration file
require_once 'db_config.php';

// Start the session
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate the input
    if (!empty($username) && !empty($password)) {
        // Query to fetch user details
        $query = "SELECT * FROM users WHERE username = ?";
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                header('Location: index.php');
                exit();
            } else {
                $error = "Invalid username or password.";
            }
        }
    } else {
        $error = "Please fill all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* style.css */
body {
    margin: 0;
    font-family: Arial, sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background-color: #f0f0f0;
}

.container {
    display: flex;
    width: 800px;
    height: 500px;
    background-color: #e8ffe8;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.left {
    width: 50%;
    padding: 100px;
    background-color: #a6ff97;
}

.left h1 {
    margin-top: 0;
}

.right {
    width: 50%;
    padding: 40px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    background-color: #f0f0f0;
}

.right h2 {
    margin: 0;
    margin-bottom: 20px;
}

form {
    width: 100%;
    display: flex;
    flex-direction: column;
}

form label {
    margin-bottom: 5px;
}

form input {
    margin-bottom: 20px;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

form button {
    padding: 10px;
    background-color: #a6ff97;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

form button:hover {
    background-color: #8fe682;
}

a {
    margin-top: 20px;
    text-align: center;
    color: #333;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

.error {
    color: red;
    margin-bottom: 20px;
}

    </style>
</head>
<body>
    <div class="container">
        <div class="left">
            <h1>Bon Appetit</h1>
            <p>Take control of your health and wellness with Bon Appetit, your ultimate companion for managing daily calorie intake and making informed food choices. Whether you're aiming to lose weight, maintain a healthy lifestyle, or simply understand your eating habits better, our app provides the tools and insights you need.</p>
        </div>
        <div class="right">
            <h2>LOGIN</h2>
            <?php if (isset($error)): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            <form method="POST" action="">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Enter the username" required>
                
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter the password" required>
                
                <button type="submit">LOGIN</button>
            </form>
            <a href="register.php">Register</a>
        </div>
    </div>
</body>
</html>
