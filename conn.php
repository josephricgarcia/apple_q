<?php
$database = "aqc";
$Port = 3306;
$Username = "root";
$Password = "";
$hostname = "localhost"; // or IP address
$dbhandle = mysqli_connect($hostname, $Username, $Password, $database, $Port) or die("Unable to connect to MYSQL");

// Select the database
$selected = mysqli_select_db($dbhandle, $database) or die("Could not connect to database");

// Query to fetch the data from the correct table 'apple_data'
$query = "SELECT apple_id, size, weight, sweetness, crunchiness, juiciness, ripeness, acidity FROM apple_data"; // Adjust query without 'id'
$result = mysqli_query($dbhandle, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($dbhandle));  // Show error if the query fails
}
?>
