<?php
require 'conn.php';
require 'session.php';

// Ensure the user is logged in
if (!isset($_SESSION['id'])) {
    echo "<script>alert('You must be logged in to edit your account.'); window.location.href = 'login.php';</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $lastname = trim(htmlspecialchars($_POST["lastname"]));
    $firstname = trim(htmlspecialchars($_POST["firstname"]));
    $middlename = trim(htmlspecialchars($_POST["middlename"]));
    $gender = $_POST["gender"];
    $birthdate = $_POST["birthdate"];
    $contact_number = trim(htmlspecialchars($_POST["contact_number"]));
    $username = trim(htmlspecialchars($_POST["username"]));
    $id = $_POST["id"]; // Hidden field from form

    // Ensure the user can only edit their own account
    if ($id != $_SESSION['id']) {
        echo "<script>alert('Unauthorized action.'); window.location.href = 'profile.php';</script>";
        exit();
    }

    $error = "";

    // Update query
    $stmt = $dbhandle->prepare("UPDATE user SET lastname = ?, firstname = ?, middlename = ?, gender = ?, birthdate = ?, contact_no = ?, username = ? WHERE id = ?");
    $stmt->bind_param("sssssssi", $lastname, $firstname, $middlename, $gender, $birthdate, $contact_number, $username, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Account updated successfully'); window.location.href = 'settings.php';</script>";
        exit();
    } else {
        $error = "Error: Update failed.";
    }

    $stmt->close();
    $dbhandle->close();
}

// Fetch user data for pre-filling the form
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Ensure the user can only view their own data
    if ($id != $_SESSION['id']) {
        echo "<script>alert('Unauthorized access.'); window.location.href = 'profile.php';</script>";
        exit();
    }

    $stmt = $dbhandle->prepare("SELECT * FROM user WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if (!$user) {
        echo "<script>alert('User not found.'); window.location.href = 'profile.php';</script>";
        exit();
    }
} else {
    // If no ID is provided, redirect or use session user ID
    $id = $_SESSION['id'];
    $stmt = $dbhandle->prepare("SELECT * FROM user WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if (!$user) {
        echo "<script>alert('User not found.'); window.location.href = 'profile.php';</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Account</title>
    <link rel="stylesheet" href="css/edit-account.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500&family=Montserrat:wght@600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <header class="form-header">
            <h2 class="title">Update Your Account</h2>
        </header>

        <?php if (!empty($error)): ?>
            <p class="error" role="alert"><?php echo $error; ?></p>
        <?php endif; ?>

        <form action="edit-account.php" method="post" id="update-form" aria-label="Update Form">
            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">

            <div class="input-group">
                <label for="lastname">Last Name</label>
                <div class="input-wrapper">
                    <input type="text" id="lastname" name="lastname" value="<?php echo htmlspecialchars($user['lastname']); ?>" required aria-required="true">
                </div>
            </div>

            <div class="input-group">
                <label for="firstname">First Name</label>
                <div class="input-wrapper">
                    <input type="text" id="firstname" name="firstname" value="<?php echo htmlspecialchars($user['firstname']); ?>" required aria-required="true">
                </div>
            </div>

            <div class="input-group">
                <label for="middlename">Middle Name</label>
                <div class="input-wrapper">
                    <input type="text" id="middlename" name="middlename" value="<?php echo htmlspecialchars($user['middlename']); ?>" required aria-required="true">
                </div>
            </div>

            <div class="input-group">
                <label for="gender">Gender</label>
                <div class="input-wrapper">
                    <select id="gender" name="gender" required aria-required="true">
                        <option value="m" <?php echo $user['gender'] == 'm' ? 'selected' : ''; ?>>Male</option>
                        <option value="f" <?php echo $user['gender'] == 'f' ? 'selected' : ''; ?>>Female</option>
                        <option value="x" <?php echo $user['gender'] == 'x' ? 'selected' : ''; ?>>Prefer not to say</option>
                    </select>
                </div>
            </div>

            <div class="input-group">
                <label for="birthdate">Birthdate</label>
                <div class="input-wrapper">
                    <input type="date" id="birthdate" name="birthdate" value="<?php echo htmlspecialchars($user['birthdate']); ?>" required aria-required="true">
                </div>
            </div>

            <div class="input-group">
                <label for="contact_number">Contact Number</label>
                <div class="input-wrapper">
                    <input type="text" id="contact_number" name="contact_number" value="<?php echo htmlspecialchars($user['contact_no']); ?>" required aria-required="true">
                </div>
            </div>

            <div class="input-group">
                <label for="username">Username (Email)</label>
                <div class="input-wrapper">
                    <input type="email" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required aria-required="true">
                </div>
            </div>

            <button type="submit" class="register-btn" id="update-btn">Update</button>
            <a href="settings.php" class="cancel-btn" id="cancel-btn">Cancel</a>
        </form>

        <footer class="form-footer">
        </footer>
    </div>
</body>
</html>