<?php
session_start();

// Connect to the database (add your database connection code here)

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Use a unique key for each test
    $test_id = 'test1';
    $question_id = $_POST['question_id'];
    $answer = isset($_POST['answer']) ? $_POST['answer'] : null;
    $action = $_POST['action'];

    // Initialize the test key in the session if not already set
    if (!isset($_SESSION['answers'][$test_id])) {
        $_SESSION['answers'][$test_id] = [];
    }

    // Store the question and answer in the session for the specific test
    if ($answer !== null) {
        $_SESSION['answers'][$test_id][$question_id] = $answer;
    }

    print_r($_SESSION['answers']); 
    // exit;

    // Redirect to the next or previous question
    if ($action === 'next') {
        header("Location: test1.php?id=" . ($question_id + 1));
        exit();
    } elseif ($action === 'prev') {
        header("Location: test1.php?id=" . ($question_id - 1));
        exit();
    }
}
?>