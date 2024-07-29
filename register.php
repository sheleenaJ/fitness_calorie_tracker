<?php
// Include the database configuration file
require_once 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $weight = $_POST['weight'];
    $height = $_POST['height'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $re_password = $_POST['re_password'];

    // Validate the input
    if (!empty($full_name) && !empty($age) && !empty($gender) && !empty($weight) && !empty($height) && !empty($username) && !empty($password) && !empty($re_password)) {
        if ($password === $re_password) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Query to insert user details
            $query = "INSERT INTO users (full_name, age, gender, weight, height, username, password) VALUES (?, ?, ?, ?, ?, ?, ?)";
            if ($stmt = $conn->prepare($query)) {
                $stmt->bind_param('sisssss', $full_name, $age, $gender, $weight, $height, $username, $hashed_password);
                if ($stmt->execute()) {
                    header('Location: login.php');
                    exit();
                } else {
                    $error = "Error registering user.";
                }
                $stmt->close();
            } else {
                $error = "Failed to prepare statement.";
            }
        } else {
            $error = "Passwords do not match.";
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
    <title>Register</title>
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
    height: 650px;
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
    padding: 10px;
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
            <h2>REGISTER</h2>
            <?php if (isset($error)): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            <form method="POST" action="">
                <label for="full_name">Full Name</label>
                <input type="text" id="full_name" name="full_name" placeholder="Enter Full Name here" required>
                
                <div style="display: flex; justify-content: space-between;">
                    <div style="flex: 1; margin-right: 10px;">
                        <label for="age">Age</label>
                        <input type="number" id="age" name="age" placeholder="Age" required>
                    </div>
                    <div style="flex: 1;">
                        <label for="gender">Gender</label>
                        <div>
                            <input type="radio" id="male" name="gender" value="Male" required> <label for="male">Male</label>
                            <input type="radio" id="female" name="gender" value="Female" required style="margin-left: 10px;"> <label for="female">Female</label>
                        </div>
                    </div>
                </div>
                
                <div style="display: flex; justify-content: space-between;">
                    <div style="flex: 1; margin-right: 10px;">
                        <label for="weight">Weight</label>
                        <input type="number" id="weight" name="weight" placeholder="Weight (kg)" step="0.01" required>
                    </div>
                    <div style="flex: 1;">
                        <label for="height">Height</label>
                        <input type="number" id="height" name="height" placeholder="Height (cm)" step="0.01" required>
                    </div>
                </div>
                
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Enter a username" required>
                
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter a password" required>
                
                <label for="re_password">Re-enter password</label>
                <input type="password" id="re_password" name="re_password" placeholder="Re-enter the password" required>
                
                <button type="submit">SIGN UP</button>
            </form>
            <a href="login.php">Login</a>
        </div>
    </div>
</body>
</html>
