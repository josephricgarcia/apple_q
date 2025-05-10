<?php
include 'con.php';  // Include the database connection and query logic
include 'session.php';    // Start the session to access session variables

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    echo "<script>alert('Please log in first.'); window.location='login.php';</script>";
    exit();
}

$user_role = $_SESSION['role'];  // Get the user's role (user or admin)
$user = $_SESSION['username'];  // Get the username from session
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apple Quality Dataset</title>
    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <header>
        <img src="images/apples.png" alt="Apple Quality Banner">
        <h1>APPLE QUALITY</h1>
    </header>

    <nav>
        <ul>
            <li><a href="home.php"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="predict.php"><i class="fas fa-chart-line"></i> Predict Quality</a></li>
            <li><a href="history.php"><i class="fas fa-history"></i> History</a></li>
            <li><a href="settings.php"><i class="fas fa-cog"></i> Settings</a></li>
            <li><a href="about.php"><i class="fas fa-info-circle"></i> About</a></li>
            <?php if ($user_role === 'admin') { ?>
                <li><a href="view.php"><i class="fas fa-users-cog"></i> User Management</a></li>
                <li><a href="insights.php"><i class="fas fa-chart-bar"></i> Insights</a></li>
            <?php } ?>
        </ul>
    </nav>

    <main>
    <section class="section1">
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['firstname'] ?? 'Guest'); ?>!</h2>
        <p>Welcome to the Apple Quality System! This platform allows you to manage and analyze apple quality data. You can insert new data, predict apple quality, and explore various features to ensure the best quality assessment. Admins have additional access to user management tools.</p>
        
        <!-- Insert Button that links to the insert page, visible to both users and admins -->
        <?php if ($user_role === 'admin' || $user_role === 'user') { ?>
            <a href="add_data.php" class="view-btn"><button><i class="fas fa-brain"></i> Try Classifier</button></a>
        <?php } ?>
    </section>

        <aside>
            <p>
                Check out my socials: <br>
                <a href="https://facebook.com/saihaelmabatid/" target="_blank" class="social-btn"><i class="fab fa-facebook-f"></i></a> Saihael Mabatid<br>
                <a href="https://www.instagram.com/sayhelpcm/" target="_blank" class="social-btn"><i class="fab fa-instagram"></i></a> Sayhelpcm<br>
                <a href="https://github.com/spcmabatid" target="_blank" class="social-btn"><i class="fab fa-github"></i></a> sayhelpcm
            </p>
        </aside>
    </main>

    <details>
        <summary>More Info</summary>
        <p>I also enjoy creating new systems and exploring new gadgets.</p>
    </details>

    <footer>
        <p>© 2025 SPC. All rights reserved.</p>
    </footer>
</body>
</html>