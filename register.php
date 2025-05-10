<?php
require 'conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
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

    // Check if username already exists
    $stmt = $dbhandle->prepare("SELECT id FROM user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $error = "Username has already been taken.";
    } elseif ($password !== $confirmpassword) {
        $error = "Passwords do not match.";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert query
        $stmt = $dbhandle->prepare("INSERT INTO user (lastname, firstname, middlename, gender, birthdate, contact_no, username, password) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $lastname, $firstname, $middlename, $gender, $birthdate, $contact_number, $username, $hashedPassword);

        if ($stmt->execute()) {
            echo "<script>alert('Registered Successfully'); window.location.href = 'login.php';</script>";
            exit();
        } else {
            $error = "Error: Registration failed.";
        }
    }

    $stmt->close();
    $dbhandle->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Create Your Account</title>
    <link rel="stylesheet" href="css/register.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500&family=Montserrat:wght@600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <header class="form-header">
            <h2 class="title">Create Your Account</h2>
        </header>

        <?php if (!empty($error)): ?>
            <p class="error" role="alert"><?php echo $error; ?></p>
        <?php endif; ?>

        <form action="register.php" method="post" id="register-form" aria-label="Registration Form">
            <div class="input-group">
                <label for="lastname">Last Name</label>
                <div class="input-wrapper">
                    <input type="text" id="lastname" name="lastname" placeholder="Enter last name" required aria-required="true">
                    <span class="input-icon" aria-hidden="true"></span>
                </div>
            </div>

            <div class="input-group">
                <label for="firstname">First Name</label>
                <div class="input-wrapper">
                    <input type="text" id="firstname" name="firstname" placeholder="Enter first name" required aria-required="true">
                    <span class="input-icon" aria-hidden="true"></span>
                </div>
            </div>

            <div class="input-group">
                <label for="middlename">Middle Name</label>
                <div class="input-wrapper">
                    <input type="text" id="middlename" name="middlename" placeholder="Enter middle name" required aria-required="true">
                    <span class="input-icon" aria-hidden="true"></span>
                </div>
            </div>

            <div class="input-group">
                <label for="gender">Gender</label>
                <div class="input-wrapper">
                    <select id="gender" name="gender" required aria-required="true">
                        <option value="">Select Gender</option>
                        <option value="m">Male</option>
                        <option value="f">Female</option>
                        <option value="x">Prefer not to say</option>
                    </select>
                    <span class="input-icon" aria-hidden="true"></span>
                </div>
            </div>

            <div class="input-group">
                <label for="birthdate">Birthdate</label>
                <div class="input-wrapper">
                    <input type="date" id="birthdate" name="birthdate" required aria-required="true">
                    <span class="input-icon" aria-hidden="true"></span>
                </div>
            </div>

            <div class="input-group">
                <label for="contact_number">Contact Number</label>
                <div class="input-wrapper">
                    <input type="text" id="contact_number" name="contact_number" placeholder="Enter contact number" required aria-required="true">
                    <span class="input-icon" aria-hidden="true"></span>
                </div>
            </div>

            <div class="input-group">
                <label for="username">Username (Email)</label>
                <div class="input-wrapper">
                    <input type="email" id="username" name="username" placeholder="Enter email" required aria-required="true">
                    <span class="input-icon" aria-hidden="true"></span>
                </div>
            </div>

            <div class="input-group">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <input type="password" id="password" name="password" placeholder="Enter password" required aria-required="true">
                    <span class="input-icon" aria-hidden="true"></span>
                </div>
            </div>

            <div class="input-group">
                <label for="confirmpassword">Confirm Password</label>
                <div class="input-wrapper">
                    <input type="password" id="confirmpassword" name="confirmpassword" placeholder="Confirm password" required aria-required="true">
                    <span class="input-icon" aria-hidden="true"></span>
                </div>
            </div>

            <button type="submit" class="register-btn" id="register-btn">Register</button>

            <p class="login-link">Already have an account? <a href="login.php" aria-label="Go to login page">Sign In</a></p>
        </form>

        <footer class="form-footer">
        </footer>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('register-form');
            const submitBtn = document.getElementById('register-btn');
            
            // Real-time input validation
            const inputs = form.querySelectorAll('input, select');
            inputs.forEach(input => {
                input.addEventListener('input', () => {
                    const wrapper = input.closest('.input-wrapper');
                    if (input.validity.valid) {
                        wrapper.classList.add('input-valid');
                        wrapper.classList.remove('input-invalid');
                    } else {
                        wrapper.classList.add('input-invalid');
                        wrapper.classList.remove('input-valid');
                    }
                });
            });

            // Simulate loading state on submit
            form.addEventListener('submit', (e) => {
                submitBtn.classList.add('loading');
                submitBtn.textContent = 'Registering...';
                setTimeout(() => {
                    submitBtn.classList.remove('loading');
                    submitBtn.textContent = 'Register Now';
                }, 2000); // Reset after 2s for demo; actual form submission handled by PHP
            });
        });
    </script>
</body>
</html>