<?php
include 'conn.php';  
include 'session.php';  

$user_role = $_SESSION['role'] ?? 'user';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - Apple Quality Classifier</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>
</head>
<body class="gradient-bg min-h-screen">

<?php include 'header.php' ?>

<?php include 'navigation.php' ?>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-apple-red-600 rounded-full mb-6">
                <i class="fas fa-apple-alt text-white text-3xl"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-800 mb-4">Apple Quality Classifier</h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                AI-powered quality assessment for apples using machine learning
            </p>
        </div>

        <!-- About Section -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-6">About the Project</h2>
            <div class="space-y-6 text-gray-700 text-lg leading-relaxed">
                <p>
                    Our advanced AI system provides instant, accurate classification of apples based on 
                    multiple quality parameters, helping maintain the highest quality standards in agriculture 
                    and food production.
                </p>
                <p>
                    Using machine learning models trained on comprehensive apple quality datasets, we can 
                    predict apple quality with high accuracy based on characteristics like size, weight, 
                    sweetness, crunchiness, juiciness, ripeness, and acidity.
                </p>
                
                <!-- Stats Section -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-8">
                    <div class="text-center p-4 bg-apple-red-50 rounded-lg">
                        <div class="text-2xl font-bold text-apple-red-600 mb-2">98%</div>
                        <div class="text-sm text-gray-600">Accuracy</div>
                    </div>
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <div class="text-2xl font-bold text-green-600 mb-2">7</div>
                        <div class="text-sm text-gray-600">Parameters</div>
                    </div>
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <div class="text-2xl font-bold text-blue-600 mb-2">Real-time</div>
                        <div class="text-sm text-gray-600">Analysis</div>
                    </div>
                    <div class="text-center p-4 bg-purple-50 rounded-lg">
                        <div class="text-2xl font-bold text-purple-600 mb-2">ML</div>
                        <div class="text-sm text-gray-600">Powered</div>
                    </div>
                </div>

                <div class="mt-8">
                    <a href="predict.php" class="inline-flex items-center bg-apple-red-600 hover:bg-apple-red-700 text-white font-semibold py-3 px-8 rounded-lg transition duration-300 shadow-md">
                        <i class="fas fa-brain mr-3"></i>
                        Try Classifier
                    </a>
                </div>
            </div>
        </div>

        <!-- Key Features -->
        <div class="mb-12">
            <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">Key Features</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white rounded-xl p-6 feature-card transition duration-300 shadow-lg border border-gray-100">
                    <div class="w-12 h-12 bg-apple-red-100 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-brain text-apple-red-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Advanced ML</h3>
                    <p class="text-gray-600">
                        Machine learning models trained on comprehensive apple quality datasets.
                    </p>
                </div>
                
                <div class="bg-white rounded-xl p-6 feature-card transition duration-300 shadow-lg border border-gray-100">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-bolt text-green-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Real-time Analysis</h3>
                    <p class="text-gray-600">
                        Instant quality classification with detailed confidence scores.
                    </p>
                </div>
                
                <div class="bg-white rounded-xl p-6 feature-card transition duration-300 shadow-lg border border-gray-100">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-mobile-alt text-blue-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Mobile Ready</h3>
                    <p class="text-gray-600">
                        Fully responsive design that works perfectly on all devices.
                    </p>
                </div>
                
                <div class="bg-white rounded-xl p-6 feature-card transition duration-300 shadow-lg border border-gray-100">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-chart-line text-purple-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Data Insights</h3>
                    <p class="text-gray-600">
                        Track prediction history and analyze quality trends over time.
                    </p>
                </div>
            </div>
        </div>

        <!-- Technology Stack -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-6">Technology Stack</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="text-center p-4">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fab fa-php text-red-600 text-2xl"></i>
                    </div>
                    <h4 class="font-semibold text-gray-800">PHP</h4>
                    <p class="text-sm text-gray-600">Backend</p>
                </div>
                <div class="text-center p-4">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fab fa-python text-blue-600 text-2xl"></i>
                    </div>
                    <h4 class="font-semibold text-gray-800">Python</h4>
                    <p class="text-sm text-gray-600">ML Models</p>
                </div>
                <div class="text-center p-4">
                    <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fab fa-js-square text-yellow-600 text-2xl"></i>
                    </div>
                    <h4 class="font-semibold text-gray-800">JavaScript</h4>
                    <p class="text-sm text-gray-600">Frontend</p>
                </div>
                <div class="text-center p-4">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-database text-green-600 text-2xl"></i>
                    </div>
                    <h4 class="font-semibold text-gray-800">MySQL</h4>
                    <p class="text-sm text-gray-600">Database</p>
                </div>
            </div>
        </div>

        <!-- Developer Info -->
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <div class="flex flex-col md:flex-row items-center">
                <div class="md:w-1/3 mb-6 md:mb-0 flex justify-center">
                    <div class="w-40 h-40 bg-gradient-to-br from-apple-red-100 to-apple-red-200 rounded-full flex items-center justify-center shadow-lg">
                        <i class="fas fa-user-circle text-apple-red-400 text-6xl"></i>
                    </div>
                </div>
                <div class="md:w-2/3 md:pl-12">
                    <h2 class="text-3xl font-bold text-gray-800 mb-4">Developer</h2>
                    <p class="text-apple-red-600 font-semibold text-xl mb-2">Saihael Mabatid</p>
                    <p class="text-gray-600 text-lg mb-6 leading-relaxed">
                        AI developer specializing in machine learning applications for agriculture and 
                        computer vision systems. Passionate about creating practical solutions that 
                        bridge technology and real-world problems.
                    </p>
                    <div class="flex space-x-4">
                        <a href="https://github.com/spcmabatid" target="_blank" class="w-10 h-10 bg-gray-800 hover:bg-gray-900 rounded-full flex items-center justify-center transition duration-300">
                            <i class="fab fa-github text-white"></i>
                        </a>
                        <a href="https://facebook.com/saihaelmabatid/" target="_blank" class="w-10 h-10 bg-blue-600 hover:bg-blue-700 rounded-full flex items-center justify-center transition duration-300">
                            <i class="fab fa-facebook-f text-white"></i>
                        </a>
                        <a href="https://www.instagram.com/sayhelpcm/" target="_blank" class="w-10 h-10 bg-pink-600 hover:bg-pink-700 rounded-full flex items-center justify-center transition duration-300">
                            <i class="fab fa-instagram text-white"></i>
                        </a>
                    </div>
                </div>
            </div>
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

    <style>
        .nav-item {
            @apply px-4 py-2 rounded-lg transition duration-300 flex items-center space-x-2 text-sm font-medium;
        }
    </style>
</body>
</html>