<?php
require 'conn.php';
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
        .quality-good {
            @apply bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium;
        }
        .quality-bad {
            @apply bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs font-medium;
        }
    </style>
</head>
<body class="gradient-bg min-h-screen">

<?php include 'header.php' ?>

<?php include 'navigation.php' ?>

        <?php if ($result->num_rows > 0): ?>
            <!-- Desktop Table -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-6 hidden md:block">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Size</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Weight</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sweetness</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Crunchiness</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Juiciness</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ripeness</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acidity</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quality</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Confidence</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr class="hover:bg-gray-50 transition duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo htmlspecialchars($row['apple_id']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars(number_format($row['size'], 2)); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars(number_format($row['weight'], 2)); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars(number_format($row['sweetness'], 2)); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars(number_format($row['crunchiness'], 2)); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars(number_format($row['juiciness'], 2)); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars(number_format($row['ripeness'], 2)); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars(number_format($row['acidity'], 2)); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="<?php echo $row['quality'] === 'good' ? 'quality-good' : 'quality-bad'; ?>">
                                            <?php echo htmlspecialchars(ucfirst($row['quality'])); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div class="flex items-center">
                                            <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                                <div class="bg-apple-red-600 h-2 rounded-full" style="width: <?php echo $row['confidence']; ?>%"></div>
                                            </div>
                                            <?php echo htmlspecialchars(number_format($row['confidence'], 1)); ?>%
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="update.php?id=<?php echo htmlspecialchars($row['apple_id']); ?>" class="text-blue-600 hover:text-blue-900 transition duration-150">
                                                <i class="fas fa-edit mr-1"></i> Edit
                                            </a>
                                            <a href="delete-history.php?id=<?php echo htmlspecialchars($row['apple_id']); ?>" class="text-red-600 hover:text-red-900 transition duration-150" onclick="return confirm('Are you sure you want to delete this prediction?');">
                                                <i class="fas fa-trash mr-1"></i> Delete
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Mobile Cards -->
            <div class="space-y-4 md:hidden">
                <?php
                $result->data_seek(0);
                while ($row = $result->fetch_assoc()): ?>
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800">Prediction #<?php echo htmlspecialchars($row['apple_id']); ?></h3>
                                <span class="<?php echo $row['quality'] === 'good' ? 'quality-good' : 'quality-bad'; ?>">
                                    <?php echo htmlspecialchars(ucfirst($row['quality'])); ?>
                                </span>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars(number_format($row['confidence'], 1)); ?>%</div>
                                <div class="text-xs text-gray-500">Confidence</div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 text-sm mb-4">
                            <div><span class="text-gray-500">Size:</span> <?php echo htmlspecialchars(number_format($row['size'], 2)); ?></div>
                            <div><span class="text-gray-500">Weight:</span> <?php echo htmlspecialchars(number_format($row['weight'], 2)); ?></div>
                            <div><span class="text-gray-500">Sweetness:</span> <?php echo htmlspecialchars(number_format($row['sweetness'], 2)); ?></div>
                            <div><span class="text-gray-500">Crunchiness:</span> <?php echo htmlspecialchars(number_format($row['crunchiness'], 2)); ?></div>
                            <div><span class="text-gray-500">Juiciness:</span> <?php echo htmlspecialchars(number_format($row['juiciness'], 2)); ?></div>
                            <div><span class="text-gray-500">Ripeness:</span> <?php echo htmlspecialchars(number_format($row['ripeness'], 2)); ?></div>
                            <div><span class="text-gray-500">Acidity:</span> <?php echo htmlspecialchars(number_format($row['acidity'], 2)); ?></div>
                        </div>
                        
                        <div class="flex space-x-3 pt-4 border-t border-gray-200">
                            <a href="update.php?id=<?php echo htmlspecialchars($row['apple_id']); ?>" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded-lg transition duration-300">
                                <i class="fas fa-edit mr-2"></i> Edit
                            </a>
                            <a href="delete-history.php?id=<?php echo htmlspecialchars($row['apple_id']); ?>" class="flex-1 bg-red-600 hover:bg-red-700 text-white text-center py-2 px-4 rounded-lg transition duration-300" onclick="return confirm('Are you sure you want to delete this prediction?');">
                                <i class="fas fa-trash mr-2"></i> Delete
                            </a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <!-- Empty State -->
            <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-history text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">No predictions yet</h3>
                <p class="text-gray-600 mb-6">Start by creating your first apple quality prediction</p>
                <a href="predict.php" class="inline-flex items-center bg-apple-red-600 hover:bg-apple-red-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-300">
                    <i class="fas fa-plus mr-2"></i>
                    Create New Prediction
                </a>
            </div>
        <?php endif; ?>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 mt-8">
            <a href="predict.php" class="flex-1 bg-apple-red-600 hover:bg-apple-red-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-300 flex items-center justify-center">
                <i class="fas fa-plus mr-2"></i>
                Create New Prediction
            </a>
            <a href="home.php" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-semibold py-3 px-6 rounded-lg transition duration-300 flex items-center justify-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Home
            </a>
        </div>
    </div>

    <?php
    $stmt->close();
    $dbhandle->close();
    ?>
</body>
</html>