<?php
date_default_timezone_set('Asia/Manila');
session_start();
require 'conn.php';  // Add database connection

// Log logout if user is logged in
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $time_out = date('H:i:s');
    $log_stmt = mysqli_prepare($dbhandle, "UPDATE user_log SET time_out = ? WHERE username = ? AND time_out IS NULL ORDER BY id DESC LIMIT 1");
    mysqli_stmt_bind_param($log_stmt, "ss", $time_out, $username);
    mysqli_stmt_execute($log_stmt);
    mysqli_stmt_close($log_stmt);
}

session_unset();
session_destroy();
header("Location: login.php");
exit();
?>