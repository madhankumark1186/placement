<?php include('config.php'); ?>


<?php

// Get the current exam_id from the URL or default to 1
$exam_id = isset($_GET['exam_id']) ? intval($_GET['exam_id']) : 2;

// Query to fetch the specific question and exam details
$query = "
    SELECT 
        verbalquestion.eqt_id, 
        verbalquestion.exam_question, 
        exam_tbl.ex_title, 
        exam_tbl.ex_time_limit, 
        exam_tbl.ex_questlimit_display 
    FROM 
        verbalquestion
    JOIN 
        exam_tbl 
    ON 
        verbalquestion.exam_id = exam_tbl.ex_id
    WHERE 
        verbalquestion.exam_id = $exam_id
";

$result = $conn->query($query);

// Initialize variables
$exam_title = "Default Title";
$exam_time = "00:00 mins";
$exam_question_limit = "0";

if ($result && $result->num_rows > 0) {
    // Fetch the exam details from the first row
    $row = $result->fetch_assoc();
    $exam_title = $row['ex_title'];
    $exam_time = $row['ex_time_limit'];
    $exam_question_limit = $row['ex_questlimit_display'];
}
// Convert exam_time from "MM:SS" to total seconds


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

// exit;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Online Placement Examination</title>
  <style>
      a {
      text-decoration: none !important;
    }
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Times New Roman', Times, serif, sans-serif;
    }

    body {
      background-color: #f8f8f8;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 20px;
    }

    .container {
      width: 500%;
      max-width: 1500px;
      background-color: #fff;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      height: 90vh;
      margin-top: 0px;
    }

    .header {
      background-color: #000;
      padding: 15px;
      text-align: center;
      color: #fff;
      margin-top: 0px;
    }

    .header h1 {
      font-size: 28px;
      color: #0000ff;
    }

    .exam-info {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px;
      background-color: #f0f0f0;
      font-size: 18px;
      font-weight: bold;
    }

    .exam-info .section-name {
      color: #ff0040;
    }

    .exam-info .timer {
      color: red;
    }

    .exam-info .btn-group {
      display: flex;
      gap: 10px;
    }

    .btn-finish,
    .btn-next-section {
      padding: 10px 20px;
      font-size: 16px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    .btn-finish {
      background-color: #33cc33;
      color: #fff;
    }

    .btn-next-section {
      background-color: #3CF60E;
      color: #fff;
    }

    .question-navigation {
      display: flex;
      justify-content: center;
      gap: 20px;
      padding: 10px;
      border-bottom: 2px solid #f0f0f0;
    }

    .question-navigation button {
      border: none;
      background: none;
      font-size: 18px;
      color: #333;
      cursor: pointer;
    }

    .question-navigation button.active {
      color: #0000ff;
      font-weight: bold;
      border-bottom: 3px solid #0000ff;
    }

    .content {
      display: flex;
      padding: 20px;
      gap: 20px;
    }

    .instructions {
      flex: 1;
      background-color: #e0ffe0;
      border-radius: 8px;
      padding: 20px;
      font-size: 15px;
      color: #333;
      height: 500px;
    }

    .instructions h3 {
      background-color: #0EF6CC;
      padding: 10px;
      border-radius: 8px;
      font-size: 20px;
      text-align: center;
      color: black;
      margin-bottom: 20px;
    }

    .instructions p {
      font-size: 20px;
    }

    .question-section {
      flex: 1;
      background-color: #ccffcc;
      border-radius: 8px;
      padding: 20px;
      font-size: 18px;
      color: #333;
      height: 500px;
      left: 50px;
    }

    .question-section .question-text {
      margin-bottom: 30px;
      font-size: 30px;
      font-weight: bold;
      left: 50px;
    }

    .options {
      display: flex;
      flex-direction: column;
      gap: 20px;
      margin-top: 30px;
    }

    .options label {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 18px;
      cursor: pointer;
    }

    .options input[type="radio"] {
      margin: 0;
    }

    .nav-buttons {
      display: flex;
      justify-content: space-between;
      margin-top: 30px;
    }

    .nav-buttons .btn-prev,
    .nav-buttons .btn-next {
      padding: 10px 20px;
      font-size: 18px;
      border-radius: 5px;
      border: none;
      cursor: pointer;
      color: #fff;
      margin-top: 100px;
    }

    .btn-prev {
      background-color: #ff0040;
      margin-left: 300px;
      margin-bottom: 100px;
      text-decoration:none;
    }

    .btn-next {
      background-color: #33cc33;
      margin-right: 150px;
      margin-bottom: 100px;
      text-decoration:none;
    }
    .back-button {
            position: absolute;
            top: 20px; /* Positioned at the top-left of the container */
            left: 20px;
            width: 50px; /* Increased size */
            height: 50px; /* Increased size */
            cursor: pointer;
            position:fixed;
        }

        .back-button img {
            width: 70%;
            height: 70%;
            border-radius: 50%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            margin-top:15px;
            margin-left:20px;
        }
        .back-button:hover::after {
        content: "Go Back";
        position: absolute;
        top: 50px;
        left: 50%;
        transform: translateX(-50%);
        background-color: #000;
        color: #fff;
        padding: 5px 10px;
        font-size: 12px;
        border-radius: 5px;
        white-space: nowrap;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        z-index: 1000;
    }
    .logout-icon {
            margin-right: 30px;
        }

        .logout-icon img {
            width: 100px;
            height: 100px;
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
        }
        .logout-icon {
          position: absolute; /* Change from relative to absolute or fixed */
          top: 25px; /* Adjust as needed to align vertically within the header */
          right: 10px; /* Align the logout icon to the right edge */
     }

        .logout-icon img {
         width: 50px; /* Adjust size for better appearance */
        height: 50px;
        cursor: pointer;
    }

  </style>
</head>
<body>
     <!-- <div class="back-button" onclick="goBack()">
        <img src="backimages.png" alt="Back">
    </div> -->
  <div class="container">
    <!-- Header Section -->
    <div class="header">
      <h1>Online Placement Examination</h1>
      <div class="logout-icon">
            <a href="index.html"><img src="Logout1.png" alt="Logout"></a>
        </div>
    </div>
    
    <!-- Exam Information Section -->
    <div class="exam-info">
      <span class="section-name">Verbal Reasoning</span>
      <div class="question-navigation">
        <?php
        // Get the current exam_id from the URL or default to 1
            $exam_id = isset($_GET['exam_id']) ? intval($_GET['exam_id']) : 2;

            // Query to fetch the specific question
            $query = "SELECT * FROM verbalquestion WHERE exam_id = $exam_id";
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $id = $row['eqt_id'];
                    $exam_question = $row['exam_question'];
                  
                    echo '
                         <button><a href="test2.php?id='.$id.'">'.$id.' </a></button>
                    ';
                }
            } else {
                echo ' <button>No Question found</button>';
            }
          
            ?>
      </div>
      <div class="exam-info">
            <!-- <span>Section: <?php echo htmlspecialchars($exam_title); ?></span><br> -->
            <span>Time: <?php echo htmlspecialchars($exam_time); ?></span><br>
            <span class="timer" id="timer"></span>
            <!-- <span>Total Questions: <?php echo htmlspecialchars($exam_question_limit); ?></span> -->
        </div>
       
      <div class="btn-group">
        
        <button onclick="window.location.href='test3.php'" class="btn-next-section">Next Section</button>
      </div>
    </div>

    <!-- Main Content Section -->
    <div class="content">
      <!-- Instructions Panel -->
      <div class="instructions">
        <h3>Read the passage and answer the associated questions</h3>
        <?php
        // Get the current exam_id from the URL or default to 1
            $id = isset($_GET['id']) ? intval($_GET['id']) : 1;

            // Query to fetch the specific question
            $query = "SELECT * FROM verbalquestion WHERE eqt_id = $id";
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $exam_id = $row['eqt_id'];
                    $topic = $row['topic'];
                    echo '
                        <p>'.$topic.'</p>
                    ';
                }
            } else {
                echo "<script>
            window.location.href = 'test3.php';
          </script>";
            }
        
            ?>
      </div>
      
      <!-- Question Panel -->
      <div class="question-section">
        <?php
        // Get the current exam_id from the URL or default to 1
            $id = isset($_GET['id']) ? intval($_GET['id']) : 1;

            // Query to fetch the specific question
            $query = "SELECT * FROM verbalquestion WHERE eqt_id = $id";
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
                    <form method="POST" action="test2bk.php">
                        <p class="question-text">
                            Question '.$exam_id.'
                        </p>
                        <p class="question-text">'.$exam_question.'</p>
                        <div class="options">
                            <label><input type="radio" name="answer" value="'.$exam_ch1.'">'.$exam_ch1.'</label>
                            <label><input type="radio" name="answer" value="'.$exam_ch2.'">'.$exam_ch2.'</label>
                            <label><input type="radio" name="answer" value="'.$exam_ch3.'">'.$exam_ch3.'</label>
                            <label><input type="radio" name="answer" value="'.$exam_ch4.'">'.$exam_ch4.'</label>
                        </div>
                    ';
                }
                
            }
             else {
              echo '
              <div style="justify-content:center; text-align:center; margin-top:10px;">
              <p >Completed! For go next exam >>></p>
              <br><br>
              <button onclick="window.location.href="test2.php"" class="finish-test" style="width:300px;"><a href="test2.php">Next Section</a></button>
              </div>';
            }
        
            ?>

            <div class="nav-buttons"> 
              <button class="btn-prev" type="submit" name="action" value="prev">Previous</button>
              <button class="btn-next" type="submit" name="action" value="next">Next</button>
          </div>  
          <input type="hidden" name="question_id" value="<?php echo $exam_id; ?>">
          </form>
      </div>
    </div>
  </div>
  <script>
    function goBack() {
        window.history.back();
    }

    var totalSeconds = <?php echo $total_seconds; ?>;
    var timerElement = document.getElementById('timer');
    var interval = setInterval(function() {
        var minutes = Math.floor(totalSeconds / 60);
        var seconds = totalSeconds % 60;
        timerElement.textContent = 'Time Remaining: ' + minutes + 'm ' + seconds + 's';
        totalSeconds--;

        if (totalSeconds < 0) {
            clearInterval(interval);
            window.location.href = 'test2.php';  // Redirect to next section after time ends
        }
    }, 1000);
  </script>
</body>
</html>
