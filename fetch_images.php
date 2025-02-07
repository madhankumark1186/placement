<?php
// Database connection
$servername = "localhost";
$username = "root"; // Replace with your MySQL username
$password = ""; // Replace with your MySQL password
$dbname = "image_store"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all images from the database
$sql = "SELECT image_name, image_data, upload_time FROM images";
$result = $conn->query($sql);

$images = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $images[] = [
            'name' => $row['image_name'],
            'data' => base64_encode($row['image_data']),
            'time' => $row['upload_time']
        ];
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>All Images</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f9f9f9;
    }
    h1 {
      text-align: center;
      margin: 20px 0;
      color: #333;
    }
    .container {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 15px;
      padding: 20px;
    }
    .image-card {
      border: 1px solid #ccc;
      border-radius: 8px;
      background-color: #fff;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      text-align: center;
      padding: 10px;
      max-width: 200px;
    }
    .image-card img {
      width: 100%;
      height: auto;
      border-radius: 8px;
    }
    .image-card p {
      margin: 5px 0;
      font-size: 14px;
      color: #555;
    }
  </style>
</head>
<body>
  <h1>All Images</h1>
  <div class="container">
    <?php if (!empty($images)): ?>
      <?php foreach ($images as $image): ?>
        <div class="image-card">
          <img src="data:image/png;base64,<?= $image['data'] ?>" alt="<?= htmlspecialchars($image['name']) ?>">
          <p><strong><?= htmlspecialchars($image['name']) ?></strong></p>
          <p>Uploaded: <?= htmlspecialchars($image['time']) ?></p>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p style="text-align: center; font-size: 18px;">No images found in the database.</p>
    <?php endif; ?>
  </div>
</body>
</html>
