<?php
include 'conn.php';
include 'session.php';

// Ensure ID is present
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('Invalid User ID'); window.location='view.php';</script>";
    exit();
}

$id = intval($_GET['id']); // Convert to integer for security

// Delete user
$stmt = $dbhandle->prepare("DELETE FROM user WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "<script>alert('User deleted successfully'); window.location='view.php';</script>";
} else {
    echo "<script>alert('Error deleting user.'); window.location='view.php';</script>";
}

$stmt->close();
$dbhandle->close();
?>