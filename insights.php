<?php
include 'session.php'; // Protect the page with session check
include 'con.php';  // Database connection

// Query for gender distribution
$gender_sql = "SELECT gender, COUNT(*) as count FROM user GROUP BY gender";
$gender_result = $dbhandle->query($gender_sql);
$gender_data = ['Male' => 0, 'Female' => 0];
if ($gender_result) {
    while ($row = $gender_result->fetch_assoc()) {
        $gender = strtolower($row['gender']); // Convert to lowercase for case-insensitive comparison
        if ($gender === 'male' || $gender === 'm') {
            $gender_data['Male'] = $row['count'];
        } elseif ($gender === 'female' || $gender === 'f') {
            $gender_data['Female'] = $row['count'];
        }
    }
}

// Query for apple quality distribution
$user_id = $_SESSION['id'];
$quality_sql = "SELECT quality, COUNT(*) as count FROM apple_data GROUP BY quality";
$quality_result = $dbhandle->query($quality_sql);
$quality_data = ['Good' => 0, 'Bad' => 0];
if ($quality_result) {
    while ($row = $quality_result->fetch_assoc()) {
        if (strtolower($row['quality']) === 'good') {
            $quality_data['Good'] = $row['count'];
        } elseif (strtolower($row['quality']) === 'bad') {
            $quality_data['Bad'] = $row['count'];
        }
    }
}


$dbhandle->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apple Quality Classifier | Insights</title>
    <link rel="stylesheet" href="css/insights.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <style>
        .gradient-bg {
            background: linear-gradient(to right, #FFD1DC, #FF9F80);
        }
        .chart-container {
            max-width: 400px;
            margin: 0 auto;
            position: relative;
            height: 300px;
        }
    </style>
</head>
<body class="gradient-bg min-h-screen font-sans">
    <!-- Back Button -->
    <div class="absolute top-4 left-4">
        <a href="home.php" class="bg-white hover:bg-[rgba(255,111,97,0.7)] text-gray-800 font-medium py-2 px-4 rounded-full shadow-sm transition-all duration-300 flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>
            Back
        </a>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-16 max-w-4xl">
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="inline-block bg-white/80 p-3 rounded-full shadow-md mb-4">
                <i class="fas fa-apple-alt text-red-500 text-3xl"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-800 mb-2">Apple Quality Insights</h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Visualizing user and apple quality data
            </p>
        </div>

        <!-- Pie Charts -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
            <!-- Gender Distribution Chart -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-md p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">User Gender Distribution</h2>
                <div class="chart-container">
                    <canvas id="genderChart"></canvas>
                </div>
            </div>

            <!-- Apple Quality Distribution Chart -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-md p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Apple Quality Distribution</h2>
                <div class="chart-container">
                    <canvas id="qualityChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Simple Footer -->
    <footer class="bg-white/80 backdrop-blur-sm py-6 px-4">
        <div class="max-w-4xl mx-auto text-center text-gray-600 text-sm">
            <p>© 2023 Apple Quality Classifier. Developed by Saihael Mabatid.</p>
        </div>
    </footer>

    <!-- Chart.js Script -->
    <script>
        // Ensure DOM is fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Gender Chart
            const genderCtx = document.getElementById('genderChart').getContext('2d');
            new Chart(genderCtx, {
                type: 'pie',
                data: {
                    labels: ['Male', 'Female'],
                    datasets: [{
                        data: [<?php echo $gender_data['Male'] ?: 0; ?>, <?php echo $gender_data['Female'] ?: 0; ?>],
                        backgroundColor: ['#60A5FA', '#F472B6'],
                        borderColor: ['#FFFFFF', '#FFFFFF'],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                font: {
                                    size: 14
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    let value = context.raw || 0;
                                    let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    let percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });

            // Quality Chart
            const qualityCtx = document.getElementById('qualityChart').getContext('2d');
            new Chart(qualityCtx, {
                type: 'pie',
                data: {
                    labels: ['Good', 'Bad'],
                    datasets: [{
                        data: [<?php echo $quality_data['Good'] ?: 0; ?>, <?php echo $quality_data['Bad'] ?: 0; ?>],
                        backgroundColor: ['#34D399', '#EF4444'],
                        borderColor: ['#FFFFFF', '#FFFFFF'],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                font: {
                                    size: 14
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    let value = context.raw || 0;
                                    let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    let percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>