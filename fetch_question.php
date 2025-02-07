<?php
// Database connection
$host = "localhost";
$username = "root";
$password = "";
$dbname = "exam";

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]));
}

// Check if `id` is provided in the request
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    // Prepare the SQL query to fetch the question and options
    $sql = "SELECT exam_question, exam_ch1, exam_ch2, exam_ch3, exam_ch4 FROM logicalquestion WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    // Execute the query
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the question exists
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode($row); // Return the question and options as JSON
    } else {
        echo json_encode(['error' => 'Question not found']);
    }

    // Close the statement and connection
    $stmt->close();
} else {
    echo json_encode(['error' => 'Invalid question ID']);
}

$conn->close();
?>
