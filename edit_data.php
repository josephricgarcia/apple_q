<?php
include 'conn.php';  // Include session.php to handle session protection
include 'session.php';  // Include session.php to handle session protection
// Ensure the user is logged in

if (!isset($_SESSION['id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'user')) {
    echo "<script>alert('Unauthorized access.'); window.location='home.php';</script>";
    exit();
}

// Ensure apple_id is present
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('Invalid Apple ID'); window.location='home.php';</script>";
    exit();
}

$apple_id = intval($_GET['id']); // Sanitize input

// Fetch apple data to check if the current user is the owner or an admin
$stmt = $dbhandle->prepare("SELECT * FROM apple_data WHERE apple_id = ?");
$stmt->bind_param("i", $apple_id);
$stmt->execute();
$result = $stmt->get_result();
$apple = $result->fetch_assoc();
$stmt->close();

if (!$apple) {
    echo "<script>alert('Apple data not found'); window.location='home.php';</script>";
    exit();
}

// Check if the current user is either an admin or the user who created the data
if ($_SESSION['role'] === 'user' && $apple['user_id'] !== $_SESSION['id']) {
    echo "<script>alert('You do not have permission to edit this data.'); window.location='home.php';</script>";
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate input data
    $size = $_POST['size'];
    $weight = $_POST['weight'];
    $sweetness = $_POST['sweetness'];
    $crunchiness = $_POST['crunchiness'];
    $juiciness = $_POST['juiciness'];
    $ripeness = $_POST['ripeness'];
    $acidity = $_POST['acidity'];
    $quality = $_POST['quality'];

    // Validate numerical inputs
    if (!is_numeric($weight) || !is_numeric($sweetness) || !is_numeric($crunchiness) || !is_numeric($juiciness) || !is_numeric($acidity)) {
        echo "<script>alert('Please enter valid numerical values for all fields.');</script>";
    } else {
        // Update apple data in the database
        $stmt = $dbhandle->prepare("UPDATE apple_data SET size=?, weight=?, sweetness=?, crunchiness=?, juiciness=?, ripeness=?, acidity=?, quality=? WHERE apple_id=? AND user_id=?");
        $stmt->bind_param("sddddsdsi", $size, $weight, $sweetness, $crunchiness, $juiciness, $ripeness, $acidity, $quality, $apple_id, $_SESSION['id']);

        if ($stmt->execute()) {
            echo "<script>alert('Apple data updated successfully!'); window.location='home.php';</script>";
            exit();
        } else {
            echo "<script>alert('Error updating apple data.');</script>";
        }

        $stmt->close();
    }
}
$dbhandle->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Apple Data</title>
    <link rel="stylesheet" href="css/edit_user.css">
</head>
<body>
    <div class="edit-popup">
        <h2>Edit Apple Data</h2>
        <form method="POST">
            <label>Size:</label>
            <select name="size" required>
                <option value="small" <?php if($apple['size'] == 'small') echo 'selected'; ?>>Small</option>
                <option value="medium" <?php if($apple['size'] == 'medium') echo 'selected'; ?>>Medium</option>
                <option value="large" <?php if($apple['size'] == 'large') echo 'selected'; ?>>Large</option>
            </select>

            <label>Weight:</label>
            <input type="number" name="weight" step="0.01" value="<?= htmlspecialchars($apple['weight']) ?>" required>

            <label>Sweetness:</label>
            <input type="number" name="sweetness" step="0.01" value="<?= htmlspecialchars($apple['sweetness']) ?>" required>

            <label>Crunchiness:</label>
            <input type="number" name="crunchiness" step="0.01" value="<?= htmlspecialchars($apple['crunchiness']) ?>" required>

            <label>Juiciness:</label>
            <input type="number" name="juiciness" step="0.01" value="<?= htmlspecialchars($apple['juiciness']) ?>" required>

            <label>Ripeness:</label>
            <select name="ripeness" required>
                <option value="unripe" <?php if($apple['ripeness'] == 'unripe') echo 'selected'; ?>>Unripe</option>
                <option value="ripe" <?php if($apple['ripeness'] == 'ripe') echo 'selected'; ?>>Ripe</option>
                <option value="overripe" <?php if($apple['ripeness'] == 'overripe') echo 'selected'; ?>>Overripe</option>
            </select>

            <label>Acidity:</label>
            <input type="number" name="acidity" step="0.01" value="<?= htmlspecialchars($apple['acidity']) ?>" required>


            <button type="submit">Update</button>
            <button type="button" onclick="window.location='home.php'">Cancel</button>
        </form>
    </div>
</body>
</html>
