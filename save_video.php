<?php
include('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $exam_id = $_POST['exam_id'];

    if (isset($_FILES['video']) && $_FILES['video']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = "uploads/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_name = uniqid() . ".webm";
        $file_path = $upload_dir . $file_name;

        if (move_uploaded_file($_FILES['video']['tmp_name'], $file_path)) {
            $stmt = $conn->prepare("INSERT INTO video_records (user_id, exam_id, file_path) VALUES (?, ?, ?)");
            $stmt->bind_param("iis", $user_id, $exam_id, $file_path);

            if ($stmt->execute()) {
                echo "Video saved successfully.";
            } else {
                echo "Error saving video: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Error moving uploaded file.";
        }
    } else {
        echo "Error uploading video.";
    }
}
?>
