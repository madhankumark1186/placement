<?php
// Database configuration
$host = 'localhost';  // Change if your database server is different
$dbname = 'exam';
$username = 'root';   // Replace with your database username
$password = '';       // Replace with your database password

// Connect to the database
$conn = new mysqli($host, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['RegisterEmail'];
    $newPassword = $_POST['NewPassword'];
    $confirmPassword = $_POST['ConfirmNewPassword'];

    // Check if the new password and confirm password match
    if ($newPassword !== $confirmPassword) {
        echo "<script>alert('Passwords do not match. Please try again.');</script>";
    } else {
        // Check if the email exists in the database
        $stmt = $conn->prepare("SELECT * FROM signup WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Update the password in plain text
            $updateStmt = $conn->prepare("UPDATE signup SET password = ? WHERE email = ?");
            $updateStmt->bind_param("ss", $newPassword, $email);
            
            if ($updateStmt->execute()) {
                echo "<script>alert('Password changed successfully!'); window.location.href = 'login.php';</script>";
            } else {
                echo "<script>alert('Error updating password. Please try again.');</script>";
            }

            $updateStmt->close();
        } else {
            echo "<script>alert('Email not found. Please enter a registered email.');</script>";
        }

        $stmt->close();
    }
}

$conn->close();
?>
