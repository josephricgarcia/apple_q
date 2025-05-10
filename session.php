<?php
session_start();

// Log session state for debugging
error_log("Session ID: " . session_id());
error_log("Session Data: " . print_r($_SESSION, true));

// Get the current file name
$currentPage = basename($_SERVER['PHP_SELF']);

// Define public pages accessible without login
$publicPages = ['login.php', 'register.php'];

// Redirect to login.php for non-public pages if not logged in
if (!in_array($currentPage, $publicPages) && !isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// If logged in, check role-based restrictions
if (isset($_SESSION['id']) && isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'admin') {
        // Admin can access all pages
    } elseif ($_SESSION['role'] === 'user') {
        // Restrict user access to admin-only pages
        $restrictedPages = ['admin_dashboard.php', 'admin_page.php'];
        if (in_array($currentPage, $restrictedPages)) {
            header("Location: home.php");
            exit();
        }
    }
}
?>