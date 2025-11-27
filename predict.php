<?php
require 'conn.php';
include 'session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input values
    $size = filter_input(INPUT_POST, 'size', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $weight = filter_input(INPUT_POST, 'weight', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $sweetness = filter_input(INPUT_POST, 'sweetness', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $crunchiness = filter_input(INPUT_POST, 'crunchiness', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $juiciness = filter_input(INPUT_POST, 'juiciness', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $ripeness = filter_input(INPUT_POST, 'ripeness', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $acidity = filter_input(INPUT_POST, 'acidity', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $user_id = $_SESSION['id'];


// Define min and max ranges
$min_max = [
    'size' => [-7.15, 6],
    'weight' => [-7.15, 6],
    'sweetness' => [-6.89, 6],
    'crunchiness' => [-6.06, 8],
    'juiciness' => [-5.96, 7],
    'ripeness' => [-5.86, 7],
    'acidity' => [-7.01, 7]
];

// Function to clip values within min-max range
function clip($value, $min, $max) {
    return max($min, min($max, $value));
}

// Apply min-max clipping
$size = clip($size, $min_max['size'][0], $min_max['size'][1]);
$weight = clip($weight, $min_max['weight'][0], $min_max['weight'][1]);
$sweetness = clip($sweetness, $min_max['sweetness'][0], $min_max['sweetness'][1]);
$crunchiness = clip($crunchiness, $min_max['crunchiness'][0], $min_max['crunchiness'][1]);
$juiciness = clip($juiciness, $min_max['juiciness'][0], $min_max['juiciness'][1]);
$ripeness = clip($ripeness, $min_max['ripeness'][0], $min_max['ripeness'][1]);
$acidity = clip($acidity, $min_max['acidity'][0], $min_max['acidity'][1]);


    // Prepare data for API request
    $data = [
        'size' => (float)$size,
        'weight' => (float)$weight,
        'sweetness' => (float)$sweetness,
        'crunchiness' => (float)$crunchiness,
        'juiciness' => (float)$juiciness,
        'ripeness' => (float)$ripeness,
        'acidity' => (float)$acidity
    ];

    // Make HTTP request to FastAPI endpoint
    $url = 'http://127.0.0.1:8000/predict';
    $options = [
        'http' => [
            'method' => 'POST',
            'header' => 'Content-Type: application/json',
            'content' => json_encode($data)
        ]
    ];
    $context = stream_context_create($options);
    $response = @file_get_contents($url, false, $context);

    if ($response === FALSE) {
        echo "<script>alert('Error connecting to prediction service');</script>";
    } else {
        $result = json_decode($response, true);
        
        if ($result && isset($result['prediction']) && isset($result['confidence'])) {
            $quality = $result['prediction'] === 1 ? 'good' : 'bad';
            $confidence = $result['confidence'];
            
            // Insert data into database
            $stmt = $dbhandle->prepare("INSERT INTO apple_data (size, weight, sweetness, crunchiness, juiciness, ripeness, acidity, quality, confidence, user_id) 
                                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("dddddddsdi", $size, $weight, $sweetness, $crunchiness, $juiciness, $ripeness, $acidity, $quality, $confidence, $user_id);
            
            if ($stmt->execute()) {
                echo "<script>alert('Prediction: Quality is $quality\\nConfidence: " . number_format($confidence, 2) . "%'); window.location.href = 'home.php';</script>";
            } else {
                echo "<script>alert('Error saving data: " . $stmt->error . "');</script>";
            }
            
            $stmt->close();
        } else {
            echo "<script>alert('Error in prediction: Invalid response from prediction service');</script>";
        }
    }
    
    $dbhandle->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Predict Apple Quality</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'apple-red': {
                            50: '#fef2f2',
                            100: '#fee2e2',
                            200: '#fecaca',
                            300: '#fca5a5',
                            400: '#f87171',
                            500: '#ef4444',
                            600: '#dc2626',
                            700: '#b91c1c',
                            800: '#991b1b',
                            900: '#7f1d1d',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #ffcccc 0%, #ff9999 100%);
        }
        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
            border-color: #f87171;
        }
    </style>
</head>
<body class="gradient-bg min-h-screen">

<?php include 'header.php' ?>

<?php include 'navigation.php' ?>
    <div class="container mx-auto px-4 max-w-4xl">
        <!-- Form -->
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <form action="predict.php" method="post" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Size -->
                    <div class="space-y-2">
                        <label for="size" class="block text-sm font-medium text-gray-700">
                            Size <span class="text-xs text-gray-500">(-7.15 to 6)</span>
                        </label>
                        <div class="relative">
                            <input type="number" name="size" id="size" step="0.01" min="-7.15" max="6" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus focus:outline-none transition-colors">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <i class="fas fa-ruler text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Weight -->
                    <div class="space-y-2">
                        <label for="weight" class="block text-sm font-medium text-gray-700">
                            Weight <span class="text-xs text-gray-500">(-7.15 to 6)</span>
                        </label>
                        <div class="relative">
                            <input type="number" name="weight" id="weight" step="0.01" min="-7.15" max="6" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus focus:outline-none transition-colors">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <i class="fas fa-weight text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Sweetness -->
                    <div class="space-y-2">
                        <label for="sweetness" class="block text-sm font-medium text-gray-700">
                            Sweetness <span class="text-xs text-gray-500">(-6.89 to 6)</span>
                        </label>
                        <div class="relative">
                            <input type="number" name="sweetness" id="sweetness" step="0.01" min="-6.89" max="6" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus focus:outline-none transition-colors">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <i class="fas fa-candy-cane text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Crunchiness -->
                    <div class="space-y-2">
                        <label for="crunchiness" class="block text-sm font-medium text-gray-700">
                            Crunchiness <span class="text-xs text-gray-500">(-6.06 to 8)</span>
                        </label>
                        <div class="relative">
                            <input type="number" name="crunchiness" id="crunchiness" step="0.01" min="-6.06" max="8" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus focus:outline-none transition-colors">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <i class="fas fa-cookie text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Juiciness -->
                    <div class="space-y-2">
                        <label for="juiciness" class="block text-sm font-medium text-gray-700">
                            Juiciness <span class="text-xs text-gray-500">(-5.96 to 7)</span>
                        </label>
                        <div class="relative">
                            <input type="number" name="juiciness" id="juiciness" step="0.01" min="-5.96" max="7" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus focus:outline-none transition-colors">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <i class="fas fa-tint text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Ripeness -->
                    <div class="space-y-2">
                        <label for="ripeness" class="block text-sm font-medium text-gray-700">
                            Ripeness <span class="text-xs text-gray-500">(-5.86 to 7)</span>
                        </label>
                        <div class="relative">
                            <input type="number" name="ripeness" id="ripeness" step="0.01" min="-5.86" max="7" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus focus:outline-none transition-colors">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <i class="fas fa-apple-alt text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Acidity -->
                    <div class="space-y-2">
                        <label for="acidity" class="block text-sm font-medium text-gray-700">
                            Acidity <span class="text-xs text-gray-500">(-7.01 to 7)</span>
                        </label>
                        <div class="relative">
                            <input type="number" name="acidity" id="acidity" step="0.01" min="-7.01" max="7" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus focus:outline-none transition-colors">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <i class="fas fa-flask text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 pt-6">
                    <button type="submit" class="flex-1 bg-apple-red-600 hover:bg-apple-red-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-300 flex items-center justify-center">
                        <i class="fas fa-check mr-2"></i>
                        Predict Quality
                    </button>
                    <button type="reset" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-semibold py-3 px-6 rounded-lg transition duration-300 flex items-center justify-center">
                        <i class="fas fa-eraser mr-2"></i>
                        Clear Form
                    </button>
                    <a href="home.php" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-3 px-6 rounded-lg transition duration-300 flex items-center justify-center">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Home
                    </a>
                </div>
            </form>
        </div>

        <!-- Info Section -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mt-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">How it works</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="text-center p-4">
                    <div class="w-12 h-12 bg-apple-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-sliders-h text-apple-red-600"></i>
                    </div>
                    <h4 class="font-medium text-gray-800 mb-2">Enter Parameters</h4>
                    <p class="text-sm text-gray-600">Fill in the apple characteristics using the form above</p>
                </div>
                <div class="text-center p-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-brain text-blue-600"></i>
                    </div>
                    <h4 class="font-medium text-gray-800 mb-2">AI Analysis</h4>
                    <p class="text-sm text-gray-600">Our machine learning model analyzes the input data</p>
                </div>
                <div class="text-center p-4">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-chart-pie text-green-600"></i>
                    </div>
                    <h4 class="font-medium text-gray-800 mb-2">Get Results</h4>
                    <p class="text-sm text-gray-600">Receive quality prediction with confidence score</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>