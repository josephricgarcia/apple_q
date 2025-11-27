<DOCUMENT filename="register.php">
<?php
require 'conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $lastname = trim(htmlspecialchars($_POST["lastname"]));
    $firstname = trim(htmlspecialchars($_POST["firstname"]));
    $middlename = trim(htmlspecialchars($_POST["middlename"]));
    $gender = $_POST["gender"];
    $birthdate = $_POST["birthdate"];
    $contact_number = trim(htmlspecialchars($_POST["contact_number"]));
    $username = trim(htmlspecialchars($_POST["username"]));
    $password = $_POST["password"];
    $confirmpassword = $_POST["confirmpassword"];

    $error = "";

    // Validate password strength
    if (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long.";
    } elseif (!preg_match('/[A-Z]/', $password)) {
        $error = "Password must contain at least one uppercase letter.";
    } elseif (!preg_match('/[a-z]/', $password)) {
        $error = "Password must contain at least one lowercase letter.";
    } elseif (!preg_match('/[0-9]/', $password)) {
        $error = "Password must contain at least one number.";
    } elseif (!preg_match('/[^A-Za-z0-9]/', $password)) {
        $error = "Password must contain at least one special character.";
    } elseif ($password !== $confirmpassword) {
        $error = "Passwords do not match.";
    } else {
        $stmt = $dbhandle->prepare("SELECT id FROM user WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Username has already been taken.";
            $stmt->close();
        } else {
            $stmt->close();
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $dbhandle->prepare("INSERT INTO user (lastname, firstname, middlename, gender, birthdate, contact_no, username, password) 
                                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssss", $lastname, $firstname, $middlename, $gender, $birthdate, $contact_number, $username, $passwordHash);

            if ($stmt->execute()) {
                $stmt->close();
                echo "<script>alert('Registered Successfully'); window.location.href = 'login.php';</script>";
                exit();
            } else {
                $error = "Error: Registration failed.";
                $stmt->close();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Apple Quality Application</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
                    },
                    fontFamily: {
                        'sans': ['Inter', '-apple-system', 'BlinkMacSystemFont', 'Helvetica Neue', 'Arial', 'sans-serif'],
                    },
                    spacing: {
                        '18': '4.5rem',
                        '88': '22rem',
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        .gradient-bg {
            background: linear-gradient(135deg, #ffcccc 0%, #ff9999 100%);
        }
        
        .card-shadow {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
            border-color: #f87171;
        }
        
        .password-toggle {
            transition: all 0.2s ease;
        }
        
        .password-toggle:hover {
            color: #ef4444;
        }
        
        /* Compact form styling */
        .compact-input {
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
        }
        
        .compact-label {
            margin-bottom: 0.25rem;
            font-size: 0.875rem;
        }
        
        /* Ensure no scroll on desktop */
        @media (min-height: 768px) {
            .no-scroll-container {
                max-height: 100vh;
                overflow: hidden;
            }
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center p-4 no-scroll-container">
    <div class="w-full max-w-2xl bg-white rounded-2xl card-shadow overflow-hidden">
        <!-- Header with Title Only (removed 3D animation to save space) -->
        <div class="relative bg-gradient-to-r from-apple-red-500 to-apple-red-600 py-4">
            <div class="text-center text-white">
                <h1 class="text-2xl font-bold">APPLE QUALITY</h1>
                <p class="text-apple-red-100 mt-1">Application</p>
            </div>
        </div>
        
        <!-- Registration Form - Compact Design -->
        <div class="p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 text-center">Create your account</h2>
            
            <form action="" method="POST" class="space-y-4">
                <!-- Name Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="lastname" class="block text-sm font-medium text-gray-700 compact-label">Last Name</label>
                        <input type="text" name="lastname" id="lastname" placeholder="Last name" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg input-focus focus:outline-none transition-colors compact-input">
                    </div>
                    <div>
                        <label for="firstname" class="block text-sm font-medium text-gray-700 compact-label">First Name</label>
                        <input type="text" name="firstname" id="firstname" placeholder="First name" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg input-focus focus:outline-none transition-colors compact-input">
                    </div>
                </div>
                
                <!-- Middle Name -->
                <div>
                    <label for="middlename" class="block text-sm font-medium text-gray-700 compact-label">Middle Name</label>
                    <input type="text" name="middlename" id="middlename" placeholder="Middle name" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg input-focus focus:outline-none transition-colors compact-input">
                </div>
                
                <!-- Gender & Birthdate -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700 compact-label">Gender</label>
                        <select name="gender" id="gender" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg input-focus focus:outline-none transition-colors compact-input">
                            <option value="">Select</option>
                            <option value="m">Male</option>
                            <option value="f">Female</option>
                            <option value="x">Prefer not to say</option>
                        </select>
                    </div>
                    <div>
                        <label for="birthdate" class="block text-sm font-medium text-gray-700 compact-label">Birthdate</label>
                        <input type="date" name="birthdate" id="birthdate" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg input-focus focus:outline-none transition-colors compact-input">
                    </div>
                </div>
                
                <!-- Contact Number -->
                <div>
                    <label for="contact_number" class="block text-sm font-medium text-gray-700 compact-label">Contact Number</label>
                    <input type="tel" name="contact_number" id="contact_number" placeholder="Contact number" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg input-focus focus:outline-none transition-colors compact-input">
                </div>
                
                <!-- Email -->
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 compact-label">Email</label>
                    <input type="email" name="username" id="username" placeholder="Enter your email" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg input-focus focus:outline-none transition-colors compact-input">
                </div>
                
                <!-- Password -->
                <div>
                    <label for="reg-password" class="block text-sm font-medium text-gray-700 compact-label">Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="reg-password" placeholder="Create password" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg input-focus focus:outline-none transition-colors compact-input pr-10">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <button type="button" onclick="toggleRegPasswordVisibility()" class="password-toggle">
                                <i class="fas fa-eye text-gray-400 text-sm" id="reg-toggleIcon"></i>
                            </button>
                        </div>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Password must be at least 8 characters with uppercase, lowercase, number, and special character.</p>
                </div>
                
                <!-- Confirm Password -->
                <div>
                    <label for="confirmpassword" class="block text-sm font-medium text-gray-700 compact-label">Confirm Password</label>
                    <div class="relative">
                        <input type="password" name="confirmpassword" id="confirmpassword" placeholder="Confirm password" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg input-focus focus:outline-none transition-colors compact-input pr-10">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <button type="button" onclick="toggleConfirmPasswordVisibility()" class="password-toggle">
                                <i class="fas fa-eye text-gray-400 text-sm" id="confirm-toggleIcon"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit" class="w-full bg-apple-red-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-apple-red-700 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-apple-red-500">
                        Create Account
                    </button>
                </div>
                
                <!-- Login Link -->
                <div class="text-center pt-2">
                    <p class="text-sm text-gray-600">
                        Already have an account?
                        <a href="login.php" class="font-medium text-apple-red-600 hover:text-apple-red-500 transition-colors">Sign in here</a>
                    </p>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Password Toggle Functions
        function toggleRegPasswordVisibility() {
            const passwordInput = document.getElementById("reg-password");
            const toggleIcon = document.getElementById("reg-toggleIcon");
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                toggleIcon.classList.remove("fa-eye");
                toggleIcon.classList.add("fa-eye-slash");
            } else {
                passwordInput.type = "password";
                toggleIcon.classList.remove("fa-eye-slash");
                toggleIcon.classList.add("fa-eye");
            }
        }

        function toggleConfirmPasswordVisibility() {
            const passwordInput = document.getElementById("confirmpassword");
            const toggleIcon = document.getElementById("confirm-toggleIcon");
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                toggleIcon.classList.remove("fa-eye");
                toggleIcon.classList.add("fa-eye-slash");
            } else {
                passwordInput.type = "password";
                toggleIcon.classList.remove("fa-eye-slash");
                toggleIcon.classList.add("fa-eye");
            }
        }
    </script>
</body>
</html>