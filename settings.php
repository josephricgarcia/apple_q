<?php
include 'conn.php'; // Include your database connection file
include 'session.php'; // Include your session management file
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apple Quality Classifier | Settings</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/settings.css">
</head>
<body class="gradient-bg min-h-screen font-sans flex flex-col">
    <!-- Back Button -->
    <div class="absolute top-4 left-4">
        <a href="home.php" class="bg-white hover:bg-[rgba(255,111,97,0.7)] text-gray-800 font-medium py-2 px-4 rounded-full shadow-sm transition-all duration-300 flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>
            Back
        </a>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-16 max-w-4xl flex-grow">
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="inline-block bg-white/80 p-3 rounded-full shadow-md mb-4">
                <i class="fas fa-cog text-red-500 text-3xl"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-800 mb-2">User Settings</h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                View and manage your account information
            </p>
        </div>

        <!-- User Info Section -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-md p-8 mb-12">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Account Details</h2>

                <div class="flex space-x-3">
                    <a href="edit-account.php" class="bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded-full shadow-sm transition-all duration-300 flex items-center">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Account
                    </a>
                    <a href="logout.php" onclick="return confirm('Are you sure you want to logout?');" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-full shadow-sm transition-all duration-300 flex items-center">
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
            <form class="form">
                <!-- Last Name -->
                <div class="input-group">
                    <label for="lastname" class="text-sm text-gray-600">Last Name</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user input-icon"></i>
                        <input type="text" id="lastname" value="<?php echo htmlspecialchars($user['lastname']); ?>" readonly>
                    </div>
                </div>

                <!-- First Name -->
                <div class="input-group">
                    <label for="firstname" class="text-sm text-gray-600">First Name</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user input-icon"></i>
                        <input type="text" id="firstname" value="<?php echo htmlspecialchars($user['firstname']); ?>" readonly>
                    </div>
                </div>

                <!-- Middle Name -->
                <div class="input-group">
                    <label for="middlename" class="text-sm text-gray-600">Middle Name</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user input-icon"></i>
                        <input type="text" id="middlename" value="<?php echo htmlspecialchars($user['middlename']); ?>" readonly>
                    </div>
                </div>

                <!-- Gender -->
                <div class="input-group">
                    <label for="gender" class="text-sm text-gray-600">Gender</label>
                    <div class="input-wrapper">
                        <i class="fas fa-venus-mars input-icon"></i>
                        <input type="text" id="gender" value="<?php echo htmlspecialchars($user['gender']); ?>" readonly>
                    </div>
                </div>

                <!-- Birthdate -->
                <div class="input-group">
                    <label for="birthdate" class="text-sm text-gray-600">Birthdate</label>
                    <div class="input-wrapper">
                        <i class="fas fa-calendar-alt input-icon"></i>
                        <input type="text" id="birthdate" value="<?php echo htmlspecialchars($user['birthdate']); ?>" readonly>
                    </div>
                </div>

                <!-- Contact Number -->
                <div class="input-group">
                    <label for="contact_number" class="text-sm text-gray-600">Contact Number</label>
                    <div class="input-wrapper">
                        <i class="fas fa-phone input-icon"></i>
                        <input type="text" id="contact_number" value="<?php echo htmlspecialchars($user['contact_no']); ?>" readonly>
                    </div>
                </div>

                <!-- Username -->
                <div class="input-group">
                    <label for="username" class="text-sm text-gray-600">Username (Email)</label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="text" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" readonly>
                    </div>
                </div>

                <!-- Role -->
                <div class="input-group">
                    <label for="role" class="text-sm text-gray-600">Role</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user-shield input-icon"></i>
                        <input type="text" id="role" value="<?php echo htmlspecialchars($user['role']); ?>" readonly>
                    </div>
                </div>
            </form>
            <?php
                } else {
                    echo '<p class="error">User data not found.</p>';
                }
                mysqli_stmt_close($stmt);
                mysqli_close($dbhandle);
            ?>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <p>&copy; 2023 Apple Quality Classifier. Developed by Saihael Mabatid.</p>
        </div>
    </footer>
</body>
</html>