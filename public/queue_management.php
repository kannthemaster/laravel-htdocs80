<?php
require 'vendor/autoload.php'; // รวม Composer autoload

use Google\Cloud\TextToSpeech\V1\TextToSpeechClient;

$host = 'localhost';
$db = 'clinic_queue';
$user = 'root';
$pass = 'BqfF4gdef';
$port = '3307';

// สร้างการเชื่อมต่อไปยังฐานข้อมูล
$conn = new mysqli($host, $user, $pass, $db, $port);

// เช็คการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ฟังก์ชันเพื่อเพิ่มคิวใหม่
function addQueue($roomId) {
    global $conn;
    $result = $conn->query("SELECT COUNT(*) as count FROM queue WHERE room_id = $roomId");
    $count = $result->fetch_assoc()['count'];
    $queueNumber = $count + 1;

    $conn->query("INSERT INTO queue (room_id, queue_number) VALUES ($roomId, $queueNumber)");

    return $queueNumber;
}

// ฟังก์ชันเพื่อเรียกคิว
function callQueue($roomId) {
    global $conn;
    $conn->query("UPDATE queue SET status = 'in_progress' WHERE room_id = $roomId AND status = 'waiting' LIMIT 1");
    // เรียกใช้ TTS
    textToSpeech("เรียกคิวห้องตรวจที่ " . $roomId);
}

// ฟังก์ชัน TTS
function textToSpeech($text) {
    $client = new TextToSpeechClient();
    $response = $client->synthesizeSpeech([
        'input' => ['text' => $text],
        'voice' => ['languageCode' => 'th-TH', 'name' => 'th-TH-Wavenet-A'],
        'audioConfig' => ['audioEncoding' => 'MP3']
    ]);

    file_put_contents('output.mp3', $response->getAudioContent());
    $client->close();
}

// ฟังก์ชันเพื่อแสดงคิวเฉพาะห้อง
function showQueueForRoom($roomId) {
    global $conn;
    $result = $conn->query("SELECT * FROM queue WHERE room_id = $roomId");

    while ($row = $result->fetch_assoc()) {
        echo "Queue Number: " . $row['queue_number'] . " - Status: " . $row['status'] . "<br>";
    }
}

// ตรวจสอบการเรียกใช้ฟังก์ชัน
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_queue'])) {
        $roomId = $_POST['room_id'];
        $queueNumber = addQueue($roomId);
        echo "Queue added for Room ID: $roomId. Queue Number: $queueNumber<br>";
    }

    if (isset($_POST['call_queue'])) {
        $roomId = $_POST['room_id'];
        callQueue($roomId);
        echo "Queue called for Room ID: $roomId.<br>";
    }

    // แสดงคิวเฉพาะห้องที่เลือก
    if (isset($_POST['room_id'])) {
        $roomId = $_POST['room_id'];
        showQueueForRoom($roomId);
    }
}

// ฟังก์ชันเพื่อแสดงห้องทั้งหมด
function showRooms() {
    global $conn;
    $result = $conn->query("SELECT * FROM rooms");
    return $result;
}

$rooms = showRooms();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Queue Management</title>
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">ระบบจัดการคิวห้องตรวจ</h1>

    <form method="POST" class="mb-4">
        <div class="form-group">
            <label for="room_id">เลือกห้องตรวจ:</label>
            <select name="room_id" id="room_id" class="form-control">
                <?php while ($room = $rooms->fetch_assoc()): ?>
                    <option value="<?php echo $room['id']; ?>"><?php echo $room['room_name']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <button type="submit" name="add_queue" class="btn btn-primary">เพิ่มคิว</button>
        <button type="submit" name="call_queue" class="btn btn-success">เรียกคิว</button>
    </form>
</div>
</body>
</html>