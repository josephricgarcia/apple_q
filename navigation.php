<?php
// navigation.php - Reusable navigation component
$user_role = $_SESSION['role'] ?? 'user';
$current_page = basename($_SERVER['PHP_SELF']);
?>

<nav class="bg-apple-red-600 text-white shadow-md">
    <div class="container mx-auto px-4">
        <div class="flex flex-wrap justify-center md:justify-start space-x-1 md:space-x-4 py-3">
            <a href="home.php" class="nav-item <?php echo $current_page == 'home.php' ? 'bg-apple-red-700' : 'hover:bg-apple-red-700'; ?>">
                <i class="fas fa-home"></i> Home
            </a>
            <a href="predict.php" class="nav-item <?php echo $current_page == 'predict.php' ? 'bg-apple-red-700' : 'hover:bg-apple-red-700'; ?>">
                <i class="fas fa-chart-line"></i> Predict Quality
            </a>
            <a href="history.php" class="nav-item <?php echo $current_page == 'history.php' ? 'bg-apple-red-700' : 'hover:bg-apple-red-700'; ?>">
                <i class="fas fa-history"></i> History
            </a>
            <a href="settings.php" class="nav-item <?php echo $current_page == 'settings.php' ? 'bg-apple-red-700' : 'hover:bg-apple-red-700'; ?>">
                <i class="fas fa-cog"></i> Settings
            </a>
            <a href="about.php" class="nav-item <?php echo $current_page == 'about.php' ? 'bg-apple-red-700' : 'hover:bg-apple-red-700'; ?>">
                <i class="fas fa-info-circle"></i> About
            </a>
            <?php if ($user_role === 'admin') { ?>
                <a href="view.php" class="nav-item <?php echo $current_page == 'view.php' ? 'bg-apple-red-700' : 'hover:bg-apple-red-700'; ?>">
                    <i class="fas fa-users-cog"></i> User Management
                </a>
                <a href="insights.php" class="nav-item <?php echo $current_page == 'insights.php' ? 'bg-apple-red-700' : 'hover:bg-apple-red-700'; ?>">
                    <i class="fas fa-chart-bar"></i> Insights
                </a>
            <?php } ?>
        </div>
    </div>
</nav>

<style>
    .nav-item {
        @apply px-4 py-2 rounded-lg transition duration-300 flex items-center space-x-2 text-sm font-medium;
    }
</style>