<?php
require 'con.php';
include 'session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and assign input values
    $size = $_POST["size"];
    $weight = $_POST["weight"];
    $sweetness = $_POST["sweetness"];
    $crunchiness = $_POST["crunchiness"];
    $juiciness = $_POST["juiciness"];
    $ripeness = $_POST["ripeness"];
    $acidity = $_POST["acidity"];
    $quality = $_POST["quality"];
    $user_id = $_SESSION['id'];  // Store the user ID from the session

    // Prepare and execute insert statement (including user_id)
    $stmt = $dbhandle->prepare("INSERT INTO apple_data (size, weight, sweetness, crunchiness, juiciness, ripeness, acidity, quality, user_id) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sddddsdsi", $size, $weight, $sweetness, $crunchiness, $juiciness, $ripeness, $acidity, $quality, $user_id);

    if ($stmt->execute()) {
        echo "<script>alert('Apple data inserted successfully!'); window.location.href = 'home.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error inserting data: " . $stmt->error . "');</script>";
    }

    $stmt->close();
    $dbhandle->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Insert Apple Data</title>
    <link rel="stylesheet" href="css/add_data.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
    <div class="container">
        <h2 class="title">Insert Apple Data</h2>

        <form action="add_data.php" method="post" class="form">
            <div class="input-group">
                <label for="size">Size</label>
                <select name="size" id="size" required>
                    <option value="" disabled selected>Select size</option>
                    <option value="small">Small</option>
                    <option value="medium">Medium</option>
                    <option value="large">Large</option>
                </select>
            </div>

            <div class="input-group">
                <label for="weight">Weight</label>
                <input type="number" name="weight" id="weight" step="0.01" required>
            </div>

            <div class="input-group">
                <label for="sweetness">Sweetness</label>
                <input type="number" name="sweetness" id="sweetness" step="0.01" required>
            </div>

            <div class="input-group">
                <label for="crunchiness">Crunchiness</label>
                <input type="number" name="crunchiness" id="crunchiness" step="0.01" required>
            </div>

            <div class="input-group">
                <label for="juiciness">Juiciness</label>
                <input type="number" name="juiciness" id="juiciness" step="0.01" required>
            </div>

            <div class="input-group">
                <label for="ripeness">Ripeness</label>
                <select name="ripeness" id="ripeness" required>
                    <option value="" disabled selected>Select ripeness</option>
                    <option value="unripe">Unripe</option>
                    <option value="ripe">Ripe</option>
                    <option value="overripe">Overripe</option>
                </select>
            </div>

            <div class="input-group">
                <label for="acidity">Acidity</label>
                <input type="number" name="acidity" id="acidity" step="0.01" required>
            </div>

            <div class="input-group">
                <label for="quality">Quality</label>
                <input type="text" name="quality" id="quality" required>
            </div>

            <div class="button-group">
                <button type="submit" class="register-btn"><i class="fas fa-check"></i> Submit</button>
                <button type="reset" class="clear-btn"><i class="fas fa-eraser"></i> Clear</button>
                <a href="home.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back</a>
            </div>
        </form>
    </div>
</body>
</html>