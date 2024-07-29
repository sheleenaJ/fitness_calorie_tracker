<?php
include 'db_config.php';

// Function to send image to Flask API and get calories
function getCaloriesFromFlaskAPI($imagePath) {
    $url = 'http://35.238.14.42:5000/upload'; // Update with your Flask API URL

    // Initialize curl
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, ['file' => new CURLFile($imagePath)]);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: multipart/form-data']);

    // Execute the request
    $response = curl_exec($ch);
    curl_close($ch);

    // Check for curl errors
    if ($response === false) {
        return ['error' => 'Request failed'];
    }

    // Print the raw response for debugging
    echo "<pre>Raw response: " . htmlspecialchars($response) . "</pre>";

    // Decode the response
    $responseData = json_decode($response, true);

    // Check if the response contains the expected data
    if (!isset($responseData['food_item']) || !isset($responseData['calories_per_100g'])) {
        return ['error' => 'Invalid response data'];
    }

    // Extract numeric calorie value from the string
    preg_match('/\d+/', $responseData['calories_per_100g'], $matches);
    if (empty($matches)) {
        return ['error' => 'Invalid calorie data format'];
    }

    return [
        'food_item' => $responseData['food_item'],
        'calories_per_100g' => (int)$matches[0]
    ];
}

function saveCalorieData($conn, $userId, $calories, $date) {
    $stmt = $conn->prepare("INSERT INTO daily_calorie_intake (user_id, date, calories) VALUES (?, ?, ?)");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("isd", $userId, $date, $calories);
    $stmt->execute();
    $stmt->close();
}

session_start();
if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

$userId = $_SESSION['user_id'];
$date = date('Y-m-d');
$caloriesFromImage = 0;
$calorieError = '';
$foodItem = '';
$caloriesPer100g = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['fetchCalories'])) {
        if (isset($_FILES['foodImage']) && $_FILES['foodImage']['error'] === UPLOAD_ERR_OK) {
            $imagePath = $_FILES['foodImage']['tmp_name'];
            $caloriesFromImage = getCaloriesFromFlaskAPI($imagePath);

            if (isset($caloriesFromImage['error'])) {
                $calorieError = $caloriesFromImage['error'];
            } else {
                $foodItem = $caloriesFromImage['food_item'];
                $caloriesPer100g = $caloriesFromImage['calories_per_100g'];
            }
        }
    } elseif (isset($_POST['addCalories'])) {
        $grams = $_POST['grams'] ?? 0;
        $userEnterdCalories = $_POST['calories'] ?? 0;
        if($grams == 0 || !is_numeric($grams) || $userEnterdCalories == 0){
            echo "<script>
            alert('Error in data!');
            window.location.href = 'track_calories.php';
          </script>";
        }else{
            $totalCalories = ($userEnterdCalories / 100) * $grams;

            saveCalorieData($conn, $userId, $totalCalories, $date);
            echo "<script>
                    alert('Calorie data saved successfully.');
                    window.location.href = 'track_calories.php';
                  </script>";
        }
       
    }
}

// Calculate total calories consumed today
$totalCaloriesToday = 0;
$sql = "SELECT SUM(calories) AS total FROM daily_calorie_intake WHERE user_id = ? AND date = ?";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("is", $userId, $date);
$stmt->execute();
$stmt->bind_result($totalCaloriesToday);
$stmt->fetch();
$stmt->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Calories - Bon Appetit</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        .result-container {
            margin-top: 20px;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .result-container p {
            margin: 0;
        }
        .result-container .food-item {
            font-weight: bold;
            color: #007bff;
        }
        .result-container .calories {
            font-weight: bold;
            color: #28a745;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #a6ff97;">
        <a class="navbar-brand" href="index.html">Bon Appetit</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <!-- <li class="nav-item active">
                    <a class="nav-link" href="track_calories.php">Track Calories</a>
                </li> -->
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
            <div class="col-md-6 offset-md-3">
                <h1 class="text-center">Track Your Calorie Intake</h1>
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="foodImage">Upload Food Image</label>
                        <input type="file" class="form-control-file" id="foodImage" name="foodImage" accept="image/*" required>
                    </div>
                    <?php if ($calorieError): ?>
                        <p class="text-danger"><?php echo $calorieError; ?></p>
                    <?php elseif ($foodItem && $caloriesPer100g): ?>
                        <div class="result-container">
                            <p>Food Item: <span class="food-item"><?php echo htmlspecialchars($foodItem); ?></span></p>
                            <p>Calories per 100g: <span class="calories"><?php echo htmlspecialchars($caloriesPer100g); ?> calories</span></p>
                        </div>
                    <?php endif; ?>
                    <button type="submit" name="fetchCalories" class="btn mt-2" style="background-color: #a6ff97; padding-left: 2%; padding-right:2%;">Fetch Calories</button>
                </form>
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="grams">Grams</label>
                        <input type="number" class="form-control" id="grams" name="grams" placeholder="Enter grams" required>
                    </div>
                    <div class="form-group">
                        <label for="calories">Calories per 100g</label>
                        <input type="number" class="form-control" id="calories" name="calories" placeholder="Enter calories per 100g: " required>
                    </div>
                    <!-- <div class="form-group">
                        <label for="foodImage">Upload Food Image</label>
                        <input type="file" class="form-control-file" id="foodImage" name="foodImage" accept="image/*" required>
                    </div> -->
                    <button type="submit" name="addCalories" class=" mt-2" style="background-color: #a6ff97; padding-left: 2%; padding-right:2%;">Add to Tracker</button>
                </form>
                <h3 class="text-center mt-4">Total Calories Consumed Today: <span id="totalCalories"><?php echo $totalCaloriesToday; ?></span></h3>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
