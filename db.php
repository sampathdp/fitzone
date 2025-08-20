<?php
$host = 'localhost'; 
$dbname = 'fitzone'; 
$username = 'root';  
$password = ''; 


$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    header("Location: signup.php");
    exit;
}
?>
