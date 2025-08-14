<?php
if (!defined('SECURE')) {
    die("Direct access not allowed");
}
$servername = "localhost";
$username = "root";
$password = "BqfF4gdef";
$database = "queue_system";
$port = 3307;

// สร้างการเชื่อมต่อ MySQL
$conn = new mysqli($servername, $username, $password, $database, $port);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
