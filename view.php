<?php
include 'session.php'; // Protect the page with session check
include 'conn.php';  // Database connection

// Query the user data
$sql = "SELECT * FROM user";
$result = $dbhandle->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Users</title>
    <link rel="stylesheet" href="css/view.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Navigation Bar -->

    <div class="absolute top-4 left-4">
        <a href="home.php" class="bg-white hover:bg-[rgba(255,111,97,0.7)] text-gray-800 font-medium py-2 px-4 rounded-full shadow-sm transition-all duration-300 flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>
            Back
        </a>
    </div>

    <div class="container">
        <h2 class="title">User Management</h2>
        <div class="table-container">
            <table class="user-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Lastname</th>
                        <th>Firstname</th>
                        <th>Middlename</th>
                        <th>Gender</th>
                        <th>Birthday</th>
                        <th>Contact Number</th>
                        <th>Username</th>
                        <th>Password</th>
                        <th>Role</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td>" . $row['lastname'] . "</td>";
                            echo "<td>" . $row['firstname'] . "</td>";
                            echo "<td>" . $row['middlename'] . "</td>";
                            echo "<td>" . $row['gender'] . "</td>";
                            echo "<td>" . $row['birthdate'] . "</td>";
                            echo "<td>" . $row['contact_no'] . "</td>";
                            echo "<td>" . $row['username'] . "</td>";
                            echo "<td>*********</td>"; // Hide the password
                            echo "<td>" . $row['role'] . "</td>";
                            echo "<td><a href='edit_user.php?id=" . $row['id'] . "' class='action-btn edit-btn'>Edit</a></td>";
                            echo "<td><a href='delete.php?id=" . $row['id'] . "' class='action-btn delete-btn' onclick='return confirm(\"Are you sure you want to delete this user?\")'>Delete</a></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='11' class='no-data'>No users found</td></tr>";
                    }
                    $dbhandle->close();
                    ?>
                </tbody>
            </table>
        </div>
        <div class="add-user-container">
            <a href="add_user.php" class="action-btn add-btn">Add New User</a>
        </div>
    </div>
</body>
</html>