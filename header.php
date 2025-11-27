<?php

?>

<header class="bg-white shadow-lg">
    <div class="container mx-auto px-4 py-4 flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-apple-red-500 rounded-full flex items-center justify-center">
                <i class="fas fa-apple-alt text-white text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">APPLE QUALITY</h1>
                <p class="text-sm text-gray-600">Quality Assessment System</p>
            </div>
        </div>
        <div class="flex items-center space-x-4">
            <span class="text-sm text-gray-600">Welcome, <?php echo htmlspecialchars($_SESSION['firstname'] ?? 'Guest'); ?></span>
            <div class="w-8 h-8 bg-apple-red-500 rounded-full flex items-center justify-center">
                <span class="text-white text-sm font-bold"><?php echo strtoupper(substr($_SESSION['firstname'] ?? 'G', 0, 1)); ?></span>
            </div>
        </div>
    </div>
</header>