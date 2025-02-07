<?php
// Database configuration
$host = 'localhost';      // Database server, typically 'localhost'
$dbname = 'exam';         // Database name
$dbUsername = 'root';     // Database username
$dbPassword = '';         // Database password

// Connect to the database
$conn = new mysqli($host, $dbUsername, $dbPassword, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare an SQL statement to select the username and password from the `admin` table
    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ? AND password = ?");

    // Check if the statement was prepared correctly
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }

    // Bind the parameters and execute the statement
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();

    // Store the result to check if any record exists
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // If a record is found, data is verified
        echo "<script>alert('Data verified successfully!'); window.location.href = 'adminpanel/admin/home.php';</script>";
    } else {
        // If no record is found, show an error message
        echo "<script>alert('Invalid username or password. Please try again.'); window.location.href = 'admin_login.php';</script>";
    }

    // Close the prepared statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
