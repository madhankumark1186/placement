<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "exam";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION["id"]; // User ID from the session
$status = "new"; // Default status

// Ensure the answers are valid for insertion
if (!empty($_SESSION['answers'])) {
    // Loop through each test (e.g., test2, test3)
    foreach ($_SESSION['answers'] as $test_key => $test_answers) {
        // Dynamically set exam_id based on the test key
        if ($test_key === 'test1') {
            $exam_id = 1; // Assign exam_id for test2s
        }elseif ($test_key === 'test2') {
            $exam_id = 2; // Assign exam_id for test2s
        } elseif ($test_key === 'test3') {
            $exam_id = 3; // Assign exam_id for test3
        } else {
            continue; // Skip unknown test keys
        }

        // Loop through each question in the test
        foreach ($test_answers as $question => $answer) {
            // Convert array values to a string if needed
            if (is_array($answer)) {
                $answer = implode(', ', $answer);
            }

            // Prepare the SQL query
            $query = "INSERT INTO exam_answers (axmne_id, exam_id, quest_id, exans_answer, exans_status) 
                      VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);

            if ($stmt) {
                $stmt->bind_param('sssss', $user_id, $exam_id, $question, $answer, $status);
                $stmt->execute();
            } else {
                echo "Error preparing statement: " . $conn->error;
            }

            $stat = "used";

             // Prepare the SQL query
            $query1 = "INSERT INTO exam_attempt (exmne_id, exam_id, examat_status) VALUES (?, ?, ?)";
            $stmt1 = $conn->prepare($query1);

            if ($stmt1) {
                $stmt1->bind_param('sss', $user_id, $exam_id, $stat);
                $stmt1->execute();
            } else {
                echo "Error preparing statement: " . $conn->error;
            }
        }
    }

    echo "<script>
        alert('Answers inserted successfully.');
        window.location.href = 'feedback.html';
      </script>";

} else {
    echo "No answers to insert.";
}

$conn->close();
?>
