<?php
include 'conn.php';  // Include the database connection and query logic
include 'session.php';    // Start the session to access session variables
$user_role = $_SESSION['role'] ?? 'user';

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    echo "<script>alert('Please log in first.'); window.location='login.php';</script>";
    exit();
}

$user_role = $_SESSION['role'];  // Get the user's role (user or admin)
$user = $_SESSION['username'];  // Get the username from session
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apple Quality Dataset</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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
    </style>
</head>
<body class="gradient-bg min-h-screen">

<?php include 'header.php' ?>

<?php include 'navigation.php' ?>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Welcome Section -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">Welcome to Apple Quality System</h2>
                    <p class="text-gray-600 text-lg leading-relaxed mb-6">
                        Welcome to the Apple Quality System! This platform allows you to manage and analyze apple quality data. 
                        You can insert new data, predict apple quality, and explore various features to ensure the best quality assessment. 
                        Admins have additional access to user management tools.
                    </p>
                    
                    <?php if ($user_role === 'admin' || $user_role === 'user') { ?>
                        <a href="add_data.php" class="inline-flex items-center bg-apple-red-600 hover:bg-apple-red-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-300 shadow-md">
                            <i class="fas fa-brain mr-3"></i>
                            Try Classifier
                        </a>
                    <?php } ?>
                </div>
            </div>

            <!-- Social Links -->
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h3 class="text-xl font-bold text-gray-800 mb-6">Connect With Me</h3>
                <div class="space-y-4">
                    <a href="https://facebook.com/saihaelmabatid/" target="_blank" class="flex items-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition duration-300">
                        <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center mr-4">
                            <i class="fab fa-facebook-f text-white"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">Saihael Mabatid</p>
                            <p class="text-sm text-gray-600">Facebook</p>
                        </div>
                    </a>
                    
                    <a href="https://www.instagram.com/sayhelpcm/" target="_blank" class="flex items-center p-4 bg-pink-50 hover:bg-pink-100 rounded-lg transition duration-300">
                        <div class="w-10 h-10 bg-pink-600 rounded-full flex items-center justify-center mr-4">
                            <i class="fab fa-instagram text-white"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">Sayhelpcm</p>
                            <p class="text-sm text-gray-600">Instagram</p>
                        </div>
                    </a>
                    
                    <a href="https://github.com/spcmabatid" target="_blank" class="flex items-center p-4 bg-gray-800 hover:bg-gray-900 rounded-lg transition duration-300">
                        <div class="w-10 h-10 bg-gray-700 rounded-full flex items-center justify-center mr-4">
                            <i class="fab fa-github text-white"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-white">Sayhelpcm</p>
                            <p class="text-sm text-gray-300">GitHub</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <?php if ($user_role === 'admin'): ?>
        <!-- Stats Section -->
        <div class="grid grid-cols-1 md:grid-cols-<?php echo $user_role === 'admin' ? '3' : '2'; ?> gap-6 mt-8">
            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <div class="w-12 h-12 bg-apple-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-chart-line text-apple-red-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Quality Predictions</h3>
                <p class="text-2xl font-bold text-apple-red-600">98%</p>
                <p class="text-sm text-gray-600">Accuracy Rate</p>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Total Predictions</h3>
                <p class="text-2xl font-bold text-green-600">1,247</p>
                <p class="text-sm text-gray-600">This Month</p>
            </div>
            

            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Active Users</h3>
                <p class="text-2xl font-bold text-blue-600">42</p>
                <p class="text-sm text-gray-600">System Wide</p>
            </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-12">
        <div class="container mx-auto px-4 py-6">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-600 text-sm">© 2025 Apple Quality System. All rights reserved.</p>
                <div class="flex space-x-4 mt-4 md:mt-0">
                    <a href="#" class="text-gray-400 hover:text-apple-red-600 transition duration-300">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-apple-red-600 transition duration-300">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-apple-red-600 transition duration-300">
                        <i class="fab fa-github"></i>
                    </a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>