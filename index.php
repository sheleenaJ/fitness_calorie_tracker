<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bon Appetit - Calorie Management System</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            background-color: #f0f0f0;
            font-family: Arial, sans-serif;
        }
        .navbar {
            background-color: #ffffff;
            border-bottom: 1px solid #e0e0e0;
        }
        .container {
            margin-top: 50px;
        }
        .card {
            background-color: #d4f1c5;
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .card h5 {
            font-size: 1.25rem;
            font-weight: bold;
        }
        .card p {
            font-size: 0.9rem;
        }
        .btn {
            background-color: #ffffff;
            color: #000000;
            border: 2px solid #000000;
            border-radius: 25px;
            font-weight: bold;
            padding: 10px 20px;
        }
        .btn:hover {
            background-color: #e0e0e0;
        }
        h1, p {
            text-align: center;
        }
        .navbar-nav {
            margin-left: auto;
        }
        .navbar-nav a {
            color: #000000 !important;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #a6ff97;">
        <a class="navbar-brand" href="#">Bon Appetit</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="register.php">Register</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <div class="row mt-5">
            <div class="col-md-12">
                <h1>Welcome to Bon Appetit</h1>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-4 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Calorie Intake</h5>
                        <p class="card-text">Track your daily calorie intake</p>
                        <a href="track_calories.php" class="btn">Track</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">BMI</h5>
                        <p class="card-text">Calculate your BMI</p>
                        <a href="bmi.php" class="btn">Calculate</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Lifestyle Modification</h5>
                        <p class="card-text">View recommendations based on your BMI</p>
                        <a href="#" class="btn">View</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-4 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Healthy Recipes</h5>
                        <p class="card-text">View healthy Sri Lankan recipes</p>
                        <a href="#" class="btn">View</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Physical Activity</h5>
                        <p class="card-text">Track your physical activity</p>
                        <a href="#" class="btn">Track</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Contact a Dietary Specialist</h5>
                        <p class="card-text">Contact a dietary specialist today for free!</p>
                        <a href="#" class="btn">Contact</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-4 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Manage Your Calorie Log</h5>
                        <p class="card-text">View, update and delete your log</p>
                        <a href="#" class="btn">Manage</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Manage Physical Activity</h5>
                        <p class="card-text">View, update and delete your physical activity log</p>
                        <a href="#" class="btn">Manage</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
