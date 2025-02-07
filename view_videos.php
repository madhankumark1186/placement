<?php
include('config.php');

// Fetch videos
$sql = "SELECT * FROM video_records";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Recorded Videos</title>
</head>
<body>
    <h1>Recorded Videos</h1>
    <div>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div>
                    <p>Video ID: <?php echo htmlspecialchars($row['id']); ?></p>
                    <p>User ID: <?php echo htmlspecialchars($row['user_id']); ?></p>
                    <p>Exam ID: <?php echo htmlspecialchars($row['exam_id']); ?></p>
                    <video controls style="width: 300px;">
                        <source src="<?php echo htmlspecialchars($row['file_path']); ?>" type="video/webm">
                    </video>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No videos found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
