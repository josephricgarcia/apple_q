<?php
include 'conn.php';
include 'session.php';

// Initialize variables
$role = 'user'; // Default value

// Fetch existing user data
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $stmt = $dbhandle->prepare("SELECT * FROM user WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Assign values from database
        $lastname = $user['lastname'];
        $firstname = $user['firstname'];
        $middlename = $user['middlename'];
        $gender = $user['gender'];
        $birthdate = $user['birthdate'];
        $contact_number = $user['contact_no'];
        $username = $user['username'];
        $role = $user['role'] ?? 'user'; // Handle role
    } else {
        die("User not found.");
    }
    $stmt->close();
} else {
    die("User ID not provided.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $lastname = trim($_POST['lastname']);
    $firstname = trim($_POST['firstname']);
    $middlename = trim($_POST['middlename']);
    $gender = $_POST['gender'];
    $birthdate = $_POST['birthdate'];
    $contact_number = trim($_POST['contact_number']);
    $username = trim($_POST['username']);
    $role = $_POST['role'];
    
    // Password handling (optional update)
    $password = trim($_POST['password']);
    $password_update = !empty($password) ? ", password = ?" : "";
    
    // Validations
    if (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format.');</script>";
    } elseif (!preg_match("/^\d{10,15}$/", $contact_number)) {
        echo "<script>alert('Invalid contact number. Use digits only (10-15 characters).');</script>";
    } else {
        // Check username uniqueness excluding current user
        $stmt = $dbhandle->prepare("SELECT id FROM user WHERE username = ? AND id != ?");
        $stmt->bind_param("si", $username, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            echo "<script>alert('Username (email) already exists.');</script>";
        } else {
            // Build dynamic update query
            $sql = "UPDATE user SET 
                    lastname = ?, 
                    firstname = ?, 
                    middlename = ?, 
                    gender = ?, 
                    birthdate = ?, 
                    contact_no = ?, 
                    username = ?, 
                    role = ?
                    $password_update 
                    WHERE id = ?";

            $stmt = $dbhandle->prepare($sql);
            
            // Bind parameters dynamically
            $params = [$lastname, $firstname, $middlename, $gender, $birthdate, 
                      $contact_number, $username, $role];
            
            if (!empty($password)) {
                $hashed_pw = password_hash($password, PASSWORD_DEFAULT);
                $params[] = $hashed_pw;
            }
            
            $params[] = $user_id;
            
            $types = str_repeat("s", count($params) - 1) . "i";
            $stmt->bind_param($types, ...$params);

            if ($stmt->execute()) {
                echo "<script>alert('User updated successfully'); window.location='view.php';</script>";
                exit();
            } else {
                echo "<script>alert('Error updating user.');</script>";
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="css/edit_user.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="edit-popup">
        <h2>Edit User</h2>
        <form method="post">
            <div class="form-group">
                <label for="lastname">Last Name</label>
                <input type="text" id="lastname" name="lastname" value="<?= htmlspecialchars($lastname) ?>" required>
            </div>

            <div class="form-group">
                <label for="firstname">First Name</label>
                <input type="text" id="firstname" name="firstname" value="<?= htmlspecialchars($firstname) ?>" required>
            </div>

            <div class="form-group">
                <label for="middlename">Middle Name</label>
                <input type="text" id="middlename" name="middlename" value="<?= htmlspecialchars($middlename) ?>">
            </div>

            <div class="form-group">
                <label for="gender">Gender</label>
                <select id="gender" name="gender" required>
                    <option value="m" <?= $gender == 'm' ? 'selected' : '' ?>>Male</option>
                    <option value="f" <?= $gender == 'f' ? 'selected' : '' ?>>Female</option>
                    <option value="x" <?= $gender == 'x' ? 'selected' : '' ?>>Prefer not to say</option>
                </select>
            </div>

            <div class="form-group">
                <label for="role">Role</label>
                <select id="role" name="role" required>
                    <option value="user" <?= $role == 'user' ? 'selected' : '' ?>>User</option>
                    <option value="admin" <?= $role == 'admin' ? 'selected' : '' ?>>Admin</option>
                </select>
            </div>

            <div class="form-group">
                <label for="birthdate">Birthdate</label>
                <input type="date" id="birthdate" name="birthdate" value="<?= $birthdate ?>" required>
            </div>

            <div class="form-group">
                <label for="contact_number">Contact Number</label>
                <input type="text" id="contact_number" name="contact_number" value="<?= htmlspecialchars($contact_number) ?>" required>
            </div>

            <div class="form-group">
                <label for="username">Username (Email)</label>
                <input type="email" id="username" name="username" value="<?= htmlspecialchars($username) ?>" required>
            </div>

            <div class="form-group">
                <label for="password">New Password (leave blank to keep current)</label>
                <input type="password" id="password" name="password" placeholder="Enter new password">
            </div>

            <div class="form-buttons">
                <button type="submit" class="btn-update">Update User</button>
                <button type="button" class="btn-cancel" onclick="window.location='view.php'">Cancel</button>
            </div>
        </form>
    </div>
</body>
</html>
<?php
$dbhandle->close();
?>