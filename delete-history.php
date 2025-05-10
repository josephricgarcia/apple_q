<?php
require 'con.php'; // Database connection
include 'session.php'; // Session management

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Check if apple_id is provided and valid
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: history.php");
    exit();
}

$apple_id = (int)$_GET['id'];
$user_id = $_SESSION['id'];

// Prepare and execute the delete query, ensuring the record belongs to the user
$stmt = $dbhandle->prepare("DELETE FROM apple_data WHERE apple_id = ? AND user_id = ?");
$stmt->bind_param("ii", $apple_id, $user_id);

if ($stmt->execute()) {
    // Check if any rows were affected
    if ($stmt->affected_rows > 0) {
        $_SESSION['message'] = "Prediction deleted successfully!";
        $_SESSION['message_class'] = "success";
    } else {
        $_SESSION['message'] = "No prediction found or you don't have permission to delete it.";
        $_SESSION['message_class'] = "error";
    }
} else {
    $_SESSION['message'] = "Error deleting prediction. Please try again.";
    $_SESSION['message_class'] = "error";
}

// Clean up
$stmt->close();
$dbhandle->close();

// Redirect to history.php
header("Location: history.php");
exit();
?>