<?php
require 'conn.php';
include 'session.php';

// Check if ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: history.php");
    exit();
}

$apple_id = (int)$_GET['id'];
$user_id = $_SESSION['id'];

// Fetch existing prediction data
$stmt = $dbhandle->prepare("SELECT size, weight, sweetness, crunchiness, juiciness, ripeness, acidity 
                           FROM apple_data 
                           WHERE apple_id = ? AND user_id = ?");
$stmt->bind_param("ii", $apple_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<script>alert('Prediction not found or you do not have permission to edit it'); window.location.href = 'history.php';</script>";
    exit();
}

$row = $result->fetch_assoc();
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input values
    $size = filter_input(INPUT_POST, 'size', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $weight = filter_input(INPUT_POST, 'weight', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $sweetness = filter_input(INPUT_POST, 'sweetness', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $crunchiness = filter_input(INPUT_POST, 'crunchiness', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $juiciness = filter_input(INPUT_POST, 'juiciness', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $ripeness = filter_input(INPUT_POST, 'ripeness', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $acidity = filter_input(INPUT_POST, 'acidity', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

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
            
            // Update data in database
            $stmt = $dbhandle->prepare("UPDATE apple_data 
                                      SET size = ?, weight = ?, sweetness = ?, crunchiness = ?, 
                                          juiciness = ?, ripeness = ?, acidity = ?, quality = ?, confidence = ?
                                      WHERE apple_id = ? AND user_id = ?");
            $stmt->bind_param("dddddddsdii", $size, $weight, $sweetness, $crunchiness, $juiciness, 
                            $ripeness, $acidity, $quality, $confidence, $apple_id, $user_id);
            
            if ($stmt->execute()) {
                echo "<script>alert('Prediction updated successfully\\nQuality: $quality\\nConfidence: " . 
                     number_format($confidence, 2) . "%'); window.location.href = 'history.php';</script>";
            } else {
                echo "<script>alert('Error updating data: " . $stmt->error . "');</script>";
            }
            
            $stmt->close();
        } else {
            echo "<script>alert('Error in prediction: Invalid response from prediction service');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Apple Prediction</title>
    <link rel="stylesheet" href="css/predict.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
    <div class="container">
        <h2 class="title">Update Apple Prediction</h2>

        <form action="update.php?id=<?php echo htmlspecialchars($apple_id); ?>" method="post" class="form">
            <div class="input-group">
                <label for="size">Size (-7.15 to 6)</label>
                <input type="number" name="size" id="size" step="0.01" min="-7.15" max="6" 
                       value="<?php echo htmlspecialchars($row['size']); ?>" required>
            </div>

            <div class="input-group">
                <label for="weight">Weight (-7.15 to 6)</label>
                <input type="number" name="weight" id="weight" step="0.01" min="-7.15" max="6" 
                       value="<?php echo htmlspecialchars($row['weight']); ?>" required>
            </div>

            <div class="input-group">
                <label for="sweetness">Sweetness (-6.89 to 6)</label>
                <input type="number" name="sweetness" id="sweetness" step="0.01" min="-6.89" max="6" 
                       value="<?php echo htmlspecialchars($row['sweetness']); ?>" required>
            </div>

            <div class="input-group">
                <label for="crunchiness">Crunchiness (-6.06 to 8)</label>
                <input type="number" name="crunchiness" id="crunchiness" step="0.01" min="-6.06" max="8" 
                       value="<?php echo htmlspecialchars($row['crunchiness']); ?>" required>
            </div>

            <div class="input-group">
                <label for="juiciness">Juiciness (-5.96 to 7)</label>
                <input type="number" name="juiciness" id="juiciness" step="0.01" min="-5.96" max="7" 
                       value="<?php echo htmlspecialchars($row['juiciness']); ?>" required>
            </div>

            <div class="input-group">
                <label for="ripeness">Ripeness (-5.86 to 7)</label>
                <input type="number" name="ripeness" id="ripeness" step="0.01" min="-5.86" max="7" 
                       value="<?php echo htmlspecialchars($row['ripeness']); ?>" required>
            </div>

            <div class="input-group">
                <label for="acidity">Acidity (-7.01 to 7)</label>
                <input type="number" name="acidity" id="acidity" step="0.01" min="-7.01" max="7" 
                       value="<?php echo htmlspecialchars($row['acidity']); ?>" required>
            </div>

            <div class="button-group">
                <button type="submit" class="register-btn"><i class="fas fa-check"></i> Update</button>
                <button type="reset" class="clear-btn"><i class="fas fa-eraser"></i> Reset</button>
                <a href="history.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back</a>
            </div>
        </form>
    </div>

    <?php
    $dbhandle->close();
    ?>
</body>
</html>