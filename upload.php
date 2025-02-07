<?php
// Database connection
$servername = "localhost";
$username = "root"; // Replace with your MySQL username
$password = ""; // Replace with your MySQL password
$dbname = "image_store";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if the file is uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $imageName = $_FILES['image']['name'];
        $imageTmpName = $_FILES['image']['tmp_name'];

        // Read the image content
        $imageData = file_get_contents($imageTmpName);

        // Insert image into the database
        $stmt = $conn->prepare("INSERT INTO images (image_name, image_data) VALUES (?, ?)");
        $stmt->bind_param("sb", $imageName, $imageData);
        $stmt->send_long_data(1, $imageData);

        if ($stmt->execute()) {
            echo json_encode(["message" => "Image uploaded and saved to database!", "id" => $stmt->insert_id]);
        } else {
            echo json_encode(["error" => "Failed to save image to database!"]);
        }

        $stmt->close();
    } else {
        echo json_encode(["error" => "No file uploaded or upload error occurred!"]);
    }
} else {
    echo json_encode(["error" => "Invalid request method!"]);
}

$conn->close();
?>
