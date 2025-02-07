<?php include('config.php'); ?>

<?php
// Fetch exam details
$sql = "SELECT ex_title, ex_time_limit, ex_questlimit_display FROM exam_tbl LIMIT 1"; // Adjust query as needed
$result = $conn->query($sql);

$exam_title = "Default Title";
$exam_time = "00:00 mins";
$exam_question_limit = "0";

if ($result && $result->num_rows > 0) {
    // Fetch the first row
    $row = $result->fetch_assoc();
    $exam_title = $row['ex_title'];
    $exam_time = $row['ex_time_limit'];
    $exam_question_limit = $row['ex_questlimit_display'];
} else {
    echo "No exam data found.";
}

try {
  $time_parts = explode(" ", $exam_time);

  if (count($time_parts) !== 2) {
      throw new Exception("Invalid input format for exam time.");
  }

  list($minutes, $seconds) = $time_parts;
  $total_seconds = ($minutes * 60) + $seconds;

} catch (Exception $e) {
  echo "";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Online Placement Examination</title>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
    }

    h1, h2 {
      text-align: center;
    }

    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px;
      background-color: #f4f4f4;
      
    }

    .logout-icon img {
      width: 30px;
      cursor: pointer;
    }

    .exam-container {
      padding: 20px;
    }

    .exam-info-box span {
      display: block;
      margin: 5px 0;
    }

    .question-container {
      display: flex;
    }

    .question-box {
      flex: 3;
      margin-right: 20px;
    }

    .question-panel {
      flex: 1;
    }

    .question-panel button {
      margin: 5px;
    }

    video {
    position: fixed;
    bottom: 10px;
    left: 10px; /* Align to the left */
    width: 150px;
    height: 150px;
    border: 2px solid #000;
    border-radius: 50%; /* Makes it circular */
    object-fit: cover; /* Ensures the video fits well within the circular shape */
  }

    .timer {
      font-weight: bold;
      color: red;
    }
    a {
      text-decoration: none !important;
    }
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: Arial, sans-serif;
      background-color: #f8f8f8;
      display: flex;
      flex-direction: column;
      align-items: center;
      color: #333;
    }

    .header {
      text-align: center;
      margin: 0;
      background-color: #000;
      color: #fff;
      padding: 10px;
      width: 100%;
    }

    .header h1 {
      font-size: 20px;
      color: blue;
    }

    .header h2 {
      font-size: 28px;
      color: #00f;
      margin-left:500px;
      
    }

    .exam-container {
      background-color: #fff;
      border-radius: 8px;
      width: 100%;
      max-width: 1400px;
      padding: 25px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      margin-top: 20px;
    }

    .exam-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
      background-color: white;
      padding: 10px;
      border-radius: 5px;
    }

    .exam-info-box {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background-color: #e0e0e0;
      padding: 10px 20px;
      border-radius: 8px;
      width: 73%;
      color: red;
      font-weight: bold;
    }

    .exam-info-box span {
      font-size: 18px;
      margin: 0 10px;
    }

    .finish-test {
      background-color: #3CF60E;
      color: #fff;
      border: none;
      padding: 10px 20px;
      border-radius: 5px;
      font-size: 18px;
      cursor: pointer;
    }

    .finish-test {
      margin-left: 50px;
    }

    .question-container {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
    }

    .question-box {
      background-color: #ccffcc;
      border-radius: 10px;
      flex: 3;
      padding: 20px;
    }

    .question-text {
      font-size: 20px;
      font-weight: bold;
      margin-bottom: 30px;
      color: #333;
    }

    .question-text p {
      font-weight: normal;
      color: #008000;
    }

    .options label {
      display: flex;
      align-items: center;
      font-size: 18px;
      margin-bottom: 20px;
      color: #333;
    }

    .options input[type="radio"] {
      margin-right: 10px;
    }

    .nav-buttons {
      display: flex;
      justify-content: flex-end;
      gap: 20px;
      margin: 20px 0;
      margin-left: 300px;
      margin-right: 300px;
    }

    .btn-prev, .btn-next {
      background-color: #EC0D27;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
      transition: background-color 0.3s ease;
    }

    .btn-prev {
      margin-right: 0px;
      margin-top: 50px;
    }

    .btn-next {
      margin-right: 200px;
      margin-top: 50px;
    }

    .btn-prev:hover, .btn-next:hover {
      background-color: #0056b3;
    }

    .question-panel {
      background-color: #ccffcc;
      border-radius: 8px;
      flex: 1;
      padding: 20px;
      text-align: center;
    }

    .panel-title {
      font-size: 18px;
      font-weight: bold;
      margin-bottom: 15px;
    }

    .question-numbers {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 20px;
    }

    .question-numbers button {
      background-color: #fff;
      border: 1px solid #ccc;
      padding: 10px;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
      font-weight: bold;
    }

    .question-numbers button.current {
      background-color: #33cc33;
      color: #fff;
    }

    .back-button {
      position: absolute;
      top: 20px;
      left: 20px;
      width: 50px;
      height: 50px;
      cursor: pointer;
      position: fixed;
    }

    .back-button img {
      width: 70%;
      height: 70%;
      border-radius: 50%;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      margin-top: -10px;
    }

    .timer {
      font-size: 20px;
      color: red;
      font-weight: bold;
      text-align: center;
      margin-top: 20px;
    }

    .logout-icon {
      position: absolute;
      top: 0px;
      right: 10px;
    }

    .logout-icon img {
      width: 50px;
      height: 50px;
      cursor: pointer;
    }

    .logout-icon:hover::after {
      content: "Logout";
      position: absolute;
      top: 50px;
      right: 0px;
      background-color: #000;
      color: #fff;
      padding: 5px 10px;
      font-size: 12px;
      border-radius: 5px;
      white-space: nowrap;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      z-index: 1000;
    }    body {
      font-family: Arial, sans-serif;
      background-color: #f8f8f8;
      display: flex;
      flex-direction: column;
      align-items: center;
      color: #333;
      overflow: hidden; /* Prevent scrolling */
    }
    /* .back-button {
            width: 50px; /* Increased size */
            height: 50px; /* Increased size */
            cursor: pointer;
            
        /* } */

        /* .back-button img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            margin-right: 70%;
        } */ 
  </style>
</head>
<body>
  <div class="header">
  <!-- <div class="back-button">
            <img src="logo-photoaidcom-cropped.png" alt="Back">
        </div> -->
    <h2>Online Placement Examination</h2>
    <div class="logout-icon">
        <a href="index.html"><img src="Logout1.png" alt="Logout"></a>
    </div>
  </div>
  
  <div class="exam-container">
    <div class="exam-header">
      <div class="exam-info-box">
        <span>Title: <?php echo htmlspecialchars($exam_title); ?></span>
        <span>Time: <?php echo htmlspecialchars($exam_time); ?></span>
        <span class="timer" id="timer"></span>
        <span>Total Questions: <?php echo htmlspecialchars($exam_question_limit); ?></span>
      </div>
      <button onclick="window.location.href='test2.php'" class="finish-test">Next Section</button>
    </div>
    <div class="question-container">
      <div class="question-box">
        <?php
          $id = isset($_GET['id']) ? intval($_GET['id']) : 1;
          $query = "SELECT * FROM exam_question_tbl WHERE eqt_id = $id";
          $result = $conn->query($query);
          if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                  $exam_id = $row['eqt_id'];
                  $exam_question = $row['exam_question'];
                  $exam_ch1 = $row['exam_ch1'];
                  $exam_ch2 = $row['exam_ch2'];
                  $exam_ch3 = $row['exam_ch3'];
                  $exam_ch4 = $row['exam_ch4'];
                  echo '
                  <form method="POST" action="test1bk.php">
                      <p class="question-text">Question '.$exam_id.'</p>
                      <p class="question-text">'.$exam_question.'</p>
                      <div class="options">
                        <label><input type="radio" name="answer" value="'.$exam_ch1.'">'.$exam_ch1.'</label>
                        <label><input type="radio" name="answer" value="'.$exam_ch2.'">'.$exam_ch2.'</label>
                        <label><input type="radio" name="answer" value="'.$exam_ch3.'">'.$exam_ch3.'</label>
                        <label><input type="radio" name="answer" value="'.$exam_ch4.'">'.$exam_ch4.'</label>
                      </div>
                      <div class="nav-buttons">';
                  if ($id > 1) {
                      echo '<button class="btn-prev" type="submit" name="action" value="prev">Previous</button>';
                  }
                  echo '
                        <button class="btn-next" type="submit" name="action" value="next">Next</button>
                      </div>
                      <input type="hidden" name="question_id" value="'.$exam_id.'">
                  </form>';
              }
          } else {
              echo '<p>No question available.</p>';
          }
        ?>
      </div>
      <div class="question-panel">
        <p class="panel-title">Question</p>
        <div class="question-numbers">
          <?php
            $query = "SELECT * FROM exam_question_tbl ORDER BY eqt_id ASC";
            $result = $conn->query($query);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $id = $row['eqt_id'];
                    echo '<button><a href="test1.php?id='.$id.'">'.$id.'</a></button>';
                }
            } else {
                echo '<button>No Questions Found</button>';
            }
          ?>
        </div>
      </div>
    </div>
  </div>

  <video id="video" autoplay></video>
  <canvas id="canvas" width="640" height="480" style="display:none;"></canvas>
  
  <script>
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const context = canvas.getContext('2d');
    let cameraStream = null;

    // Start the camera
    async function startCamera() {
      try {
        cameraStream = await navigator.mediaDevices.getUserMedia({ video: true });
        video.srcObject = cameraStream;
        console.log('Camera started');
      } catch (error) {
        console.error('Error accessing the camera:', error);
      }
    }

    // Stop the camera
    function stopCamera() {
      if (cameraStream) {
        cameraStream.getTracks().forEach(track => track.stop());
        console.log('Camera stopped');
      }
    }

    // Capture an image and send it to the server
    function captureImage() {
      context.drawImage(video, 0, 0, canvas.width, canvas.height);
      canvas.toBlob(blob => {
        const formData = new FormData();
        formData.append('image', blob, 'capture.png');

        fetch('upload.php', {
          method: 'POST',
          body: formData
        })
          .then(response => response.json())
          .then(data => {
            console.log(data.message || data.error);
          })
          .catch(error => {
            console.error('Error uploading the image:', error);
          });
      });
    }

    // Start the camera and schedule image capture
    startCamera();
    const captureInterval = setInterval(captureImage, 5000); // Capture every 5 seconds

    // Stop the camera when the page is closed or refreshed
    window.addEventListener('beforeunload', () => {
      stopCamera();
      clearInterval(captureInterval);
    });

    // Timer functionality
    var totalSeconds = <?php echo $total_seconds; ?>;
    var timerElement = document.getElementById('timer');
    var interval = setInterval(function() {
        var minutes = Math.floor((totalSeconds / 60)-27);
        var seconds = totalSeconds % 60;
        timerElement.textContent = 'Time Remaining: ' + minutes + 'm ' + seconds + 's';
        totalSeconds--;

        if (totalSeconds < 0) {
            clearInterval(interval);
            window.location.href = 'test2.php';  // Redirect to next section after time ends
        }
    }, 1000);

    // Monitor tab visibility
    let warnings = 0;
    document.addEventListener('visibilitychange', () => {
      if (document.hidden) {
        warnings++;
        if (warnings === 1) {
          alert("Warning: Switching tabs is not allowed!");
        } else if (warnings >= 2) {
          alert("Exam terminated.");
          window.location.href = 'index.html';
        }
      }
    });
    
  </script>
</body>
</html>