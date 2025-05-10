<?php
require 'con.php';
include 'session.php';

// Fetch apple quality prediction history for the logged-in user
$user_id = $_SESSION['id'];
$stmt = $dbhandle->prepare("SELECT apple_id, size, weight, sweetness, crunchiness, juiciness, ripeness, acidity, quality, confidence 
                           FROM apple_data 
                           WHERE user_id = ? 
                           ORDER BY apple_id DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apple Quality Prediction History</title>
    <link rel="stylesheet" href="css/history.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  
</head>
<body>
    <div class="container">
        <h2 class="title">Prediction History</h2>

        <?php if ($result->num_rows > 0): ?>
            <!-- Table for desktop -->
            <div class="history-table" role="region" aria-label="Prediction history table">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Size (cm)</th>
                            <th>Weight (g)</th>
                            <th>Sweetness</th>
                            <th>Crunchiness</th>
                            <th>Juiciness</th>
                            <th>Ripeness</th>
                            <th>Acidity</th>
                            <th>Quality</th>
                            <th>Confidence (%)</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['apple_id']); ?></td>
                                <td><?php echo htmlspecialchars(number_format($row['size'], 2)); ?></td>
                                <td><?php echo htmlspecialchars(number_format($row['weight'], 2)); ?></td>
                                <td><?php echo htmlspecialchars(number_format($row['sweetness'], 2)); ?></td>
                                <td><?php echo htmlspecialchars(number_format($row['crunchiness'], 2)); ?></td>
                                <td><?php echo htmlspecialchars(number_format($row['juiciness'], 2)); ?></td>
                                <td><?php echo htmlspecialchars(number_format($row['ripeness'], 2)); ?></td>
                                <td><?php echo htmlspecialchars(number_format($row['acidity'], 2)); ?></td>
                                <td class="<?php echo $row['quality'] === 'good' ? 'quality-good' : 'quality-bad'; ?>">
                                    <?php echo htmlspecialchars(ucfirst($row['quality'])); ?>
                                </td>
                                <td><?php echo htmlspecialchars(number_format($row['confidence'], 2)); ?></td>
                                <td>
                                    <a href="update.php?id=<?php echo htmlspecialchars($row['apple_id']); ?>" class="update-btn">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="delete-history.php?id=<?php echo htmlspecialchars($row['apple_id']); ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this prediction?');">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <!-- Card layout for mobile -->
            <div class="history-cards" role="region" aria-label="Prediction history cards">
                <?php
                $result->data_seek(0); // Reset result pointer for card layout
                while ($row = $result->fetch_assoc()): ?>
                    <div class="history-card">
                        <div><span>ID:</span> <span><?php echo htmlspecialchars($row['apple_id']); ?></span></div>
                        <div><span>Size (cm):</span> <span><?php echo htmlspecialchars(number_format($row['size'], 2)); ?></span></div>
                        <div><span>Weight (g):</span> <span><?php echo htmlspecialchars(number_format($row['weight'], 2)); ?></span></div>
                        <div><span>Sweetness:</span> <span><?php echo htmlspecialchars(number_format($row['sweetness'], 2)); ?></span></div>
                        <div><span>Crunchiness:</span> <span><?php echo htmlspecialchars(number_format($row['crunchiness'], 2)); ?></span></div>
                        <div><span>Juiciness:</span> <span><?php echo htmlspecialchars(number_format($row['juiciness'], 2)); ?></span></div>
                        <div><span>Ripeness:</span> <span><?php echo htmlspecialchars(number_format($row['ripeness'], 2)); ?></span></div>
                        <div><span>Acidity:</span> <span><?php echo htmlspecialchars(number_format($row['acidity'], 2)); ?></span></div>
                        <div><span>Quality:</span> <span class="<?php echo $row['quality'] === 'good' ? 'quality-good' : 'quality-bad'; ?>">
                            <?php echo htmlspecialchars(ucfirst($row['quality'])); ?>
                        </span></div>
                        <div><span>Confidence:</span> <span><?php echo htmlspecialchars(number_format($row['confidence'], 2)); ?>%</span></div>
                        <div>
                            <span>Actions:</span>
                            <span>
                                <a href="update.php?id=<?php echo htmlspecialchars($row['apple_id']); ?>" class="update-btn">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="delete-history.php?id=<?php echo htmlspecialchars($row['apple_id']); ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this prediction?');">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </span>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p class="error">No predictions found. Start by creating a new prediction!</p>
        <?php endif; ?>

        <div class="button-group">
            <a href="predict.php" class="register-btn"><i class="fas fa-plus"></i> Create New Prediction</a>
            <a href="home.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Home</a>
        </div>
    </div>

    <?php
    $stmt->close();
    $dbhandle->close();
    ?>
</body>
</html>