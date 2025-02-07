<?php
include("config.php");

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST["email"];
    $password = $_POST["password"];
    
    $sql = "SELECT * FROM examinee_tbl WHERE exmne_email = '$email' AND exmne_password = '$password'";
    
    // Execute the query
    $result = $conn->query($sql);

    // Check if a user with the given credentials exists
    if ($result->num_rows == 1) {
       
        $_SESSION["logged_in"] = true;
        $userInfo = $result->fetch_assoc();

        $_SESSION["id"] = $userInfo["exmne_rnumber"]; 
        
        $_SESSION["username"] = $userInfo["exmne_fullname"];
        
       

       
            // Append email as a query parameter to the Exam.html link
            header("Location: Exam.html?email=" . urlencode($email));
            exit();
        
    } else {
        // Invalid credentials, show an error message
        echo "<script type='text/javascript'>alert('Invalid Username and Password');window.location.href='login.html';</script>";
    }

    // Close the database connection
    $conn->close();
}
?>
