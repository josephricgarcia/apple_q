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
    <title>Settings - Apple Quality Classifier</title>
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
        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
            border-color: #f87171;
        }
    </style>
</head>
<body class="gradient-bg min-h-screen">

<?php include 'header.php' ?>

<?php include 'navigation.php' ?>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-apple-red-600 rounded-full mb-4">
                <i class="fas fa-cog text-white text-2xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">User Settings</h1>
            <p class="text-gray-600">View and manage your account information</p>
        </div>

        <!-- User Info Section -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Account Details</h2>
                    <p class="text-gray-600 mt-1">Your personal information and account settings</p>
                </div>
                <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3 mt-4 md:mt-0">
                    <a href="edit-account.php" class="inline-flex items-center bg-apple-red-600 hover:bg-apple-red-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-300">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Account
                    </a>
                    <a href="logout.php" onclick="return confirm('Are you sure you want to logout?');" class="inline-flex items-center bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-300">
                        <i class="fas fa-sign-out-alt mr-2"></i>
                        Logout
                    </a>
                </div>
            </div>

            <?php
                if (!isset($_SESSION['id'])) {
                    echo "<script>alert('Please log in first.'); window.location='login.php';</script>";
                    exit();
                }

                require 'con.php';
                $user_id = $_SESSION['id'];
                $stmt = mysqli_prepare($dbhandle, "SELECT lastname, firstname, middlename, gender, birthdate, contact_no, username, role FROM user WHERE id = ?");
                mysqli_stmt_bind_param($stmt, "i", $user_id);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if ($result && mysqli_num_rows($result) > 0) {
                    $user = mysqli_fetch_assoc($result);
                    // Map gender values to readable format
                    $gender_map = [
                        'm' => 'Male',
                        'f' => 'Female',
                        'x' => 'Prefer not to say'
                    ];
                    $user['gender'] = isset($gender_map[$user['gender']]) ? $gender_map[$user['gender']] : 'Not specified';
            ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Last Name -->
                <div class="space-y-2">
                    <label for="lastname" class="block text-sm font-medium text-gray-700">
                        <i class="fas fa-user mr-2 text-apple-red-500"></i>Last Name
                    </label>
                    <input type="text" id="lastname" value="<?php echo htmlspecialchars($user['lastname']); ?>" readonly 
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-700">
                </div>

                <!-- First Name -->
                <div class="space-y-2">
                    <label for="firstname" class="block text-sm font-medium text-gray-700">
                        <i class="fas fa-user mr-2 text-apple-red-500"></i>First Name
                    </label>
                    <input type="text" id="firstname" value="<?php echo htmlspecialchars($user['firstname']); ?>" readonly 
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-700">
                </div>

                <!-- Middle Name -->
                <div class="space-y-2">
                    <label for="middlename" class="block text-sm font-medium text-gray-700">
                        <i class="fas fa-user mr-2 text-apple-red-500"></i>Middle Name
                    </label>
                    <input type="text" id="middlename" value="<?php echo htmlspecialchars($user['middlename']); ?>" readonly 
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-700">
                </div>

                <!-- Gender -->
                <div class="space-y-2">
                    <label for="gender" class="block text-sm font-medium text-gray-700">
                        <i class="fas fa-venus-mars mr-2 text-apple-red-500"></i>Gender
                    </label>
                    <input type="text" id="gender" value="<?php echo htmlspecialchars($user['gender']); ?>" readonly 
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-700">
                </div>

                <!-- Birthdate -->
                <div class="space-y-2">
                    <label for="birthdate" class="block text-sm font-medium text-gray-700">
                        <i class="fas fa-calendar-alt mr-2 text-apple-red-500"></i>Birthdate
                    </label>
                    <input type="text" id="birthdate" value="<?php echo htmlspecialchars($user['birthdate']); ?>" readonly 
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-700">
                </div>

                <!-- Contact Number -->
                <div class="space-y-2">
                    <label for="contact_number" class="block text-sm font-medium text-gray-700">
                        <i class="fas fa-phone mr-2 text-apple-red-500"></i>Contact Number
                    </label>
                    <input type="text" id="contact_number" value="<?php echo htmlspecialchars($user['contact_no']); ?>" readonly 
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-700">
                </div>

                <!-- Username -->
                <div class="space-y-2 md:col-span-2">
                    <label for="username" class="block text-sm font-medium text-gray-700">
                        <i class="fas fa-envelope mr-2 text-apple-red-500"></i>Username (Email)
                    </label>
                    <input type="text" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" readonly 
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-700">
                </div>

                <!-- Role -->
                <div class="space-y-2 md:col-span-2">
                    <label for="role" class="block text-sm font-medium text-gray-700">
                        <i class="fas fa-user-shield mr-2 text-apple-red-500"></i>Role
                    </label>
                    <div class="flex items-center">
                        <input type="text" id="role" value="<?php echo htmlspecialchars(ucfirst($user['role'])); ?>" readonly 
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-700">
                        <span class="ml-4 px-3 py-1 bg-apple-red-100 text-apple-red-800 rounded-full text-sm font-medium">
                            <?php echo $user['role'] === 'admin' ? 'Administrator' : 'User'; ?>
                        </span>
                    </div>
                </div>
            </div>
            <?php
                } else {
                    echo '<div class="bg-red-50 border border-red-200 rounded-lg p-4 text-center">
                            <i class="fas fa-exclamation-circle text-red-500 text-2xl mb-2"></i>
                            <p class="text-red-700">User data not found.</p>
                          </div>';
                }
                mysqli_stmt_close($stmt);
                mysqli_close($dbhandle);
            ?>
        </div>

        <!-- Account Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <div class="w-12 h-12 bg-apple-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-chart-line text-apple-red-600"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Total Predictions</h3>
                <p class="text-2xl font-bold text-apple-red-600">24</p>
                <p class="text-sm text-gray-600">This month</p>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Good Quality</h3>
                <p class="text-2xl font-bold text-green-600">18</p>
                <p class="text-sm text-gray-600">75% success rate</p>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-user-clock text-blue-600"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Member Since</h3>
                <p class="text-2xl font-bold text-blue-600">2024</p>
                <p class="text-sm text-gray-600">Active account</p>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Quick Actions</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="predict.php" class="p-4 bg-apple-red-50 hover:bg-apple-red-100 rounded-lg transition duration-300 border border-apple-red-200 text-center">
                    <div class="w-10 h-10 bg-apple-red-500 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-plus text-white"></i>
                    </div>
                    <h3 class="font-semibold text-gray-800">New Prediction</h3>
                    <p class="text-sm text-gray-600 mt-1">Create prediction</p>
                </a>
                
                <a href="history.php" class="p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition duration-300 border border-blue-200 text-center">
                    <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-history text-white"></i>
                    </div>
                    <h3 class="font-semibold text-gray-800">View History</h3>
                    <p class="text-sm text-gray-600 mt-1">Past predictions</p>
                </a>
                
                <a href="edit-account.php" class="p-4 bg-green-50 hover:bg-green-100 rounded-lg transition duration-300 border border-green-200 text-center">
                    <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-user-edit text-white"></i>
                    </div>
                    <h3 class="font-semibold text-gray-800">Edit Profile</h3>
                    <p class="text-sm text-gray-600 mt-1">Update information</p>
                </a>
                
                <a href="about.php" class="p-4 bg-purple-50 hover:bg-purple-100 rounded-lg transition duration-300 border border-purple-200 text-center">
                    <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-info-circle text-white"></i>
                    </div>
                    <h3 class="font-semibold text-gray-800">About</h3>
                    <p class="text-sm text-gray-600 mt-1">Learn more</p>
                </a>
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