<?php
include 'conn.php';
include 'session.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $lastname = trim($_POST['lastname']);
    $firstname = trim($_POST['firstname']);
    $middlename = trim($_POST['middlename']);
    $gender = $_POST['gender'];
    $birthdate = $_POST['birthdate'];
    $contact_number = trim($_POST['contact_number']);
    $username = trim($_POST['username']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT); // Hash the password
    $role = $_POST['role'];

    // Validate email format
    if (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format.');</script>";
    } elseif (!preg_match("/^\d{10,15}$/", $contact_number)) {
        echo "<script>alert('Invalid contact number. Use digits only (10-15 characters).');</script>";
    } elseif (strlen(trim($_POST['password'])) < 8) {
        echo "<script>alert('Password must be at least 8 characters long.');</script>";
    } else {
        // Check if username already exists
        $stmt = $dbhandle->prepare("SELECT id FROM user WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            echo "<script>alert('Username (email) already exists.');</script>";
        } else {
            // Insert new user
            $stmt = $dbhandle->prepare("INSERT INTO user (lastname, firstname, middlename, gender, birthdate, contact_no, username, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssss", $lastname, $firstname, $middlename, $gender, $birthdate, $contact_number, $username, $password);

            if ($stmt->execute()) {
                echo "<script>alert('User added successfully'); window.location='view.php';</script>";
                exit();
            } else {
                echo "<script>alert('Error adding user.');</script>";
            }
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New User</title>
    <link rel="stylesheet" href="css/add_user.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="add-popup">
        <h2>Add New User</h2>
        <form method="post">
            <div class="form-group">
                <label for="lastname">Last Name</label>
                <input type="text" id="lastname" name="lastname" placeholder="Enter last name" required>
            </div>

            <div class="form-group">
                <label for="firstname">First Name</label>
                <input type="text" id="firstname" name="firstname" placeholder="Enter first name" required>
            </div>

            <div class="form-group">
                <label for="middlename">Middle Name</label>
                <input type="text" id="middlename" name="middlename" placeholder="Enter middle name (optional)">
            </div>

            <div class="form-group">
                <label for="gender">Gender</label>
                <select id="gender" name="gender" required>
                    <option value="" disabled selected>Select gender</option>
                    <option value="m">Male</option>
                    <option value="f">Female</option>
                    <option value="x">Prefer not to say</option>
                </select>
            </div>

            <div class="form-group">
                <label for="birthdate">Birthdate</label>
                <input type="date" id="birthdate" name="birthdate" required>
            </div>

            <div class="form-group">
                <label for="contact_number">Contact Number</label>
                <input type="text" id="contact_number" name="contact_number" placeholder="Enter contact number" required>
            </div>

            <div class="form-group">
                <label for="username">Username (Email)</label>
                <input type="email" id="username" name="username" placeholder="Enter email" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter password" required>
            </div>

            <div class="form-group">
                <label for="role">Role</label>
                    <select id="role" name="role" required>
                        <option value="" disabled selected>Select role</option>
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
            </div>

            <div class="form-buttons">
                <button type="submit" class="btn-add">Add User</button>
                <button type="button" class="btn-cancel" onclick="window.location='view.php'">Cancel</button>
            </div>
        </form>
    </div>
</body>
</html>