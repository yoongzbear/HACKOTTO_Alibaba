<?php
$servername = "rm-3nsixe3586321q3dsuo.mysql.rds.aliyuncs.com"; // RDS endpoint
$username   = "woozi";
$password   = "w@ozi123";
$database   = "caratretail";
$port       = 3306;

// Create connection
$conn = new mysqli($servername, $username, $password, $database, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>