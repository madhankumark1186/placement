<?php
// Database connection settings
$servername = "localhost"; // Change if your database server is different
$username = "root"; // Change if your username is different
$password = ""; // Change if your password is different
$dbname = "exam";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Collect form data
$product_satisfaction = $_POST['product_satisfaction'] ?? null;
$service_satisfaction = $_POST['service_satisfaction'] ?? null;
$environment_satisfaction = $_POST['environment_satisfaction'] ?? null;
$comments = $_POST['comments'] ?? "";

// Insert data into database
$sql = "INSERT INTO feedback (product_satisfaction, service_satisfaction, environment_satisfaction, comments)
        VALUES (?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $product_satisfaction, $service_satisfaction, $environment_satisfaction, $comments);

if ($stmt->execute()) {
    echo "<script>
        alert('Thank You for your FeedBack.');
        window.location.href = 'index.html';
      </script>";
} else {
    echo "Error: " . $stmt->error;
}

// Close connections
$stmt->close();
$conn->close();
?>
