<?php
include 'con.php';  // Include session.php to handle session protection
include 'session.php';  // Include session.php to handle session protection

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apple Quality Classifier | About</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .gradient-bg {
            background: linear-gradient(to right, #FFD1DC, #FF9F80);
        }
        .apple-shape {
            clip-path: circle(50% at 50% 50%);
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="gradient-bg min-h-screen font-sans">
    <!-- Back Button -->
    <div class="absolute top-4 left-4">
        <a href="home.php" class="bg-white hover:bg-[rgba(255,111,97,0.7)] text-gray-800 font-medium py-2 px-4 rounded-full shadow-sm transition-all duration-300 flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>
            Back
        </a>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-16 max-w-4xl">
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="inline-block bg-white/80 p-3 rounded-full shadow-md mb-4">
                <i class="fas fa-apple-alt text-red-500 text-3xl"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-800 mb-2">Apple Quality Classifier</h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                AI-powered quality assessment for apples
            </p>
        </div>

        <!-- About Section -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-md p-8 mb-12">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">About the Project</h2>
            <div class="space-y-4 text-gray-700">
                <p>
                    Our advanced AI system provides instant, accurate classification of apples based on visual characteristics, helping maintain the highest quality standards in agriculture.
                </p>
                <p>
                    Using deep learning models trained on thousands of apple images, we can detect quality factors like color consistency, bruising, and size with 98% accuracy.
                </p>
                <div class="mt-6">
                    <a href="#" class="inline-block bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-6 rounded-full transition duration-300">
                        Try Classifier
                    </a>
                </div>
            </div>
        </div>

        <!-- Key Features -->
        <div class="mb-12">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Key Features</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white/80 backdrop-blur-sm rounded-xl p-6 feature-card transition duration-300 shadow-sm">
                    <div class="text-red-500 mb-3">
                        <i class="fas fa-brain text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Advanced AI</h3>
                    <p class="text-gray-600 text-sm">
                        Deep learning models trained on thousands of apple images.
                    </p>
                </div>
                
                <div class="bg-white/80 backdrop-blur-sm rounded-xl p-6 feature-card transition duration-300 shadow-sm">
                    <div class="text-red-500 mb-3">
                        <i class="fas fa-bolt text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Real-time</h3>
                    <p class="text-gray-600 text-sm">
                        Instant quality classification results in milliseconds.
                    </p>
                </div>
                
                <div class="bg-white/80 backdrop-blur-sm rounded-xl p-6 feature-card transition duration-300 shadow-sm">
                    <div class="text-red-500 mb-3">
                        <i class="fas fa-mobile-alt text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Mobile Ready</h3>
                    <p class="text-gray-600 text-sm">
                        Works perfectly on any device, ideal for field use.
                    </p>
                </div>
                
                <div class="bg-white/80 backdrop-blur-sm rounded-xl p-6 feature-card transition duration-300 shadow-sm">
                    <div class="text-red-500 mb-3">
                        <i class="fas fa-leaf text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Variety ID</h3>
                    <p class="text-gray-600 text-sm">
                        Recognizes different apple varieties automatically.
                    </p>
                </div>
            </div>
        </div>

        <!-- Developer Info -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-md p-8">
            <div class="flex flex-col md:flex-row items-center">
                <div class="md:w-1/3 mb-6 md:mb-0 flex justify-center">
                    <div class="w-32 h-32 bg-gradient-to-r from-red-100 to-red-200 rounded-full flex items-center justify-center">
                        <i class="fas fa-user-circle text-red-400 text-5xl"></i>
                    </div>
                </div>
                <div class="md:w-2/3 md:pl-8">
                    <h2 class="text-xl font-bold text-gray-800 mb-2">Developer</h2>
                    <p class="text-red-500 font-medium mb-4">Saihael Mabatid</p>
                    <p class="text-gray-600 text-sm mb-4">
                        AI developer specializing in computer vision applications for agriculture.
                    </p>
                    <div class="flex space-x-3">
                        <a href="#" class="text-gray-500 hover:text-red-500 transition duration-300">
                            <i class="fab fa-github"></i>
                        </a>
                        <a href="#" class="text-gray-500 hover:text-red-500 transition duration-300">
                            <i class="fab fa-linkedin"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Simple Footer -->
    <footer class="bg-white/80 backdrop-blur-sm py-6 px-4">
        <div class="max-w-4xl mx-auto text-center text-gray-600 text-sm">
            <p>&copy; 2023 Apple Quality Classifier. Developed by Saihael Mabatid.</p>
        </div>
    </footer>
</body>
</html>