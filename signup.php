<?php

include('config.php');

$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$regno = $_POST['Confirmpassword'];



$sql = "INSERT INTO examinee_tbl (exmne_fullname, exmne_email, exmne_password, exmne_rnumber) VALUES('$username', '$email', '$password', '$regno')";

if ($conn->query($sql) === TRUE) {
    echo "<script type='text/javascript'>alert('Signup Successfully');window.location.href='login.html';</script>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();

?>
