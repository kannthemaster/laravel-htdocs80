<?php
define('SECURE', true);
// เริ่มการเชื่อมต่อกับ WebSocket Server
require_once __DIR__ . '/vendor/autoload.php';

use Workerman\Connection\AsyncTcpConnection;

$websocket_address = 'ws://127.0.0.1:8080'; // ที่อยู่ WebSocket server

// ฟังก์ชันส่งข้อมูลไปยัง WebSocket server
function sendDataToWebSocket($data) {
    global $websocket_address;

    // สร้างการเชื่อมต่อไปยัง WebSocket server
    $connection = new AsyncTcpConnection($websocket_address);
    $connection->send(json_encode($data)); // ส่งข้อมูลในรูปแบบ JSON
    $connection->close();
}
// การเชื่อมต่อฐานข้อมูล
include 'config/db_connect.php';

// รับค่าหมายเลขห้องจาก GET (ถ้าไม่มีห้อง ให้ใช้ห้อง 1 เป็นค่าเริ่มต้น)
$selectedRoom = isset($_GET['room']) ? intval($_GET['room']) : 1;

// สร้างเงื่อนไข SQL สำหรับการกรองห้อง
$sqlCondition = $selectedRoom === 1 ? "" : "AND room_number = $selectedRoom";

// ดึงข้อมูลคิวจากฐานข้อมูล
$sql = "SELECT *, TIMESTAMPDIFF(SECOND, created_at, NOW()) AS waiting_time FROM queues WHERE status <> 'Completed' $sqlCondition ORDER BY id DESC";
$result = $conn->query($sql);

// แปลงผลลัพธ์จาก $result เป็น array
$queueData = [];
while ($row = $result->fetch_assoc()) {
    $queueData[] = $row;
}

// จัดเรียงสถานะ waiting ให้อยู่ด้านบน และเรียง Wait Time จากมากไปน้อยสำหรับ waiting
usort($queueData, function ($a, $b) {
    // จัดลำดับสถานะให้ 'waiting' อยู่ด้านบน
    if ($a['status'] === 'waiting' && $b['status'] !== 'waiting') {
        return -1; // ให้ $a อยู่ก่อน $b
    }
    if ($a['status'] !== 'waiting' && $b['status'] === 'waiting') {
        return 1; // ให้ $b อยู่ก่อน $a
    }

    // ถ้าทั้งคู่เป็น 'waiting' ให้เรียงตาม Wait Time จากมากไปน้อย
    if ($a['status'] === 'waiting' && $b['status'] === 'waiting') {
        $waitTimeA = isset($a['waiting_time']) ? (int)$a['waiting_time'] : 0;
        $waitTimeB = isset($b['waiting_time']) ? (int)$b['waiting_time'] : 0;
        return $waitTimeB - $waitTimeA; // เรียงจากมากไปน้อย
    }

    // ถ้าทั้งคู่ไม่ใช่ 'waiting' ให้ลำดับตามค่าดั้งเดิม
    return 0;
});

// แสดงผลตารางคิว
echo '<table class="table table-bordered queue-table">';
echo '<thead>
        <tr>
            <th class="text-center">Queue Number</th>
            <th class="text-center">Room</th>
            <th class="text-center">Status</th>
            <th class="text-center">Wait Time</th>
            <th>Action</th>
        </tr>
      </thead>';
echo '<tbody>';

foreach ($queueData as $row) {
    $statusClass = getStatusClass($row['status']); // กำหนดคลาสสีตามสถานะ
    $statusColor = getStatusColor($row['status']); // กำหนดสีพื้นหลังตามสถานะ
    $textColor = getStatusTextColor($row['status']); // กำหนดสีข้อความตามสถานะ
    $waitingTime = isset($row['waiting_time']) ? (int)$row['waiting_time'] : 0;

    // ตรวจสอบสถานะว่าเป็น 'waiting' หรือไม่
    if ($row['status'] === 'waiting') {
        // คำนวณเวลาที่รอเป็นนาที:วินาที
        $minutes = floor($waitingTime / 60);
        $seconds = $waitingTime % 60;
        $formattedWaitTime = sprintf("%02d:%02d", $minutes, $seconds);
    } else {
        // ถ้าไม่ใช่ 'waiting' ให้แสดงเป็น - หรือไม่แสดงเวลา
        $formattedWaitTime = '-';
    }

    echo '<tr>';
    echo '<td align="center" style="font-size: 2em; background-color: ' . getRoomColor($row['room_number']) . ';">'
         . $row['queue_number'] . '</td>';
    echo '<td align="center" style="background-color: ' . getRoomColor($row['room_number']) . '; font-size: 1.5em;">'
         . $row['room_number'] . '</td>';
    echo '<td align="center" class="' . $textColor . '" style="background-color: ' . $statusColor . '; color: ' . $textColor . ';">'
         . ucfirst($row['status']) . '</td>';
    echo '<td align="center">' . $formattedWaitTime . '</td>'; // แสดงเวลารอเฉพาะเมื่อสถานะเป็น 'waiting'
    echo '<td>
        <button class="btn btn-success call-btn" data-id="' . $row['id'] . '" data-wait-time="' . $waitingTime . '" disabled>Call/เรียกคิว</button>
        <button class="btn btn-primary transfer-btn" data-id="' . $row['id'] . '">Transfer/ส่งต่อ</button>
        <button class="btn btn-danger complete-btn" data-id="' . $row['id'] . '">จบงาน/ผู้ป่วยกลับบ้าน</button>
        <button class="btn btn-info print-btn" 
            data-id="' . $row['id'] . '" 
            data-number="' . $row['queue_number'] . '" 
            data-room="' . $row['room_number'] . '" 
            data-status="' . $row['status'] . '">Print/พิมพ์คิว</button>
      </td>';
    echo '</tr>';
}
echo '</tbody>';
echo '</table>';




// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();

// กำหนดเขตเวลาให้เป็นของประเทศไทย
date_default_timezone_set('Asia/Bangkok');

// กำหนดรูปแบบวันที่ DD/MM/YYYY
$currentDate = date("d/m/Y");
/**
 * ฟังก์ชันกำหนดสีพื้นหลังของเลขห้อง
 */
function getRoomColor($roomNumber) {
    switch ($roomNumber) {
        case 2:
            return '#ADD8E6'; // สีฟ้าอ่อน
        case 3:
            return '#98FB98'; // สีเขียวอ่อน
        case 4:
            return '#FFFF99'; // สีเหลืองอ่อน
        case 6:
            return '#FFFFFF'; // สีขาว
        case 8:
            return '#F7CCFF'; // สีม่วง
        default:
            return '#FFFFFF'; // สีขาว
    }
}
/**
 * ฟังก์ชันกำหนดสีพื้นหลังสำหรับสถานะ
 */
function getStatusColor($status) {
    switch ($status) {
        case 'called':
            return '#28a745'; // สีเขียว
        case 'waiting':
            return '#ffc107'; // สีส้ม
        case 'completed':
            return '#dc3545'; // สีแดง
        default:
            return '#6c757d'; // สีเทา
    }
}
/**
 * ฟังก์ชันกำหนดสีข้อความสำหรับสถานะ
 */
function getStatusTextColor($status) {
    switch ($status) {
        case 'called':
            return '#ffffff'; // ข้อความสีขาว
        case 'waiting':
            return '#000000'; // ข้อความสีดำ
        case 'completed':
            return '#ffffff'; // ข้อความสีขาว
        default:
            return '#ffffff'; // ข้อความสีขาว
    }
}
/**
 * ฟังก์ชันกำหนดคลาสสีสำหรับสถานะ
 */
function getStatusClass($status) {
    switch ($status) {
        case 'called':
            return 'text-success'; // สถานะ "เรียกแล้ว" สีเขียว
        case 'waiting':
            return 'text-warning'; // สถานะ "กำลังรอ" สีส้ม
        case 'completed':
            return 'text-danger'; // สถานะ "เสร็จแล้ว" สีแดง
        default:
            return 'text-secondary'; // สถานะอื่น สีเทา
    }
}
?>

<script>

function confirmAddQueue() {
    return confirm("ต้องการเพิ่มคิวใช่หรือไม่?");
}
 
// ฟังก์ชันคำนวณเวลารอ
function calculateWaitingTime(createAt) {
    const createdDate = new Date(createAt);
    const now = new Date();
    const diffMs = now - createdDate; // ความแตกต่างเป็นมิลลิวินาที
    const diffMinutes = Math.floor(diffMs / 60000); // แปลงเป็นนาที
    const diffHours = Math.floor(diffMinutes / 60); // แปลงเป็นชั่วโมง
    const minutes = diffMinutes % 60;

    if (diffHours > 0) {
        return `${diffHours} ชั่วโมง ${minutes} นาที`;
    } else {
        return `${minutes} นาที`;
    }
}
document.querySelectorAll('.call-btn').forEach(function (button) {
    const initialWaitTime = parseInt(button.getAttribute('data-wait-time'), 10);

    if (initialWaitTime >= 60) {
        // ถ้าเวลารอเริ่มต้นถึง 30 วิแล้ว เปิดปุ่มทันที
        button.disabled = false;
    } else {
        // คำนวณเวลาที่เหลือจนถึง 30 วินาที
        const timeToEnable = 60 - initialWaitTime;

        // นับถอยหลังเพื่อเปิดใช้งานปุ่ม
        let remainingTime = timeToEnable;

        const timer = setInterval(() => {
            if (remainingTime > 0) {
                remainingTime--;
            } else {
                clearInterval(timer);
                button.disabled = false; // เปิดใช้งานปุ่ม
                button.textContent = 'Call/เรียกคิว'; // เปลี่ยนข้อความบนปุ่มกลับ
            }
        }, 1000);

        // แสดงเวลาที่เหลือบนปุ่มระหว่างนับถอยหลัง
        button.textContent = `Call (${remainingTime}s)`;
    }

    // เพิ่ม event listener เมื่อปุ่มถูกกด
    button.addEventListener('click', function () {
        alert('เรียกคิวสำเร็จ!');
        // เพิ่มโค้ดสำหรับการเรียกใช้งาน เช่น ส่งคำขอไปยังเซิร์ฟเวอร์
    });
});


// อัปเดตเวลารอในทุกแถว
function updateWaitingTimes() {
    document.querySelectorAll('.waiting-time').forEach(cell => {
        const createdAt = cell.getAttribute('data-created-at');
        if (createdAt) {
            cell.textContent = calculateWaitingTime(createdAt);
        }
    });
}

// เรียกฟังก์ชันอัปเดตทุก 1 วินาที
setInterval(updateWaitingTimes, 3000);

// เรียกครั้งแรกเมื่อหน้าโหลด
updateWaitingTimes();
    
document.querySelectorAll('.print-btn').forEach(function (button) {
    button.addEventListener('click', function () {
        const queueNumber = this.getAttribute('data-number');
        const roomNumber = this.getAttribute('data-room');
        const lang = document.getElementById("langSelect").value;

        const translations = {
            th: {
                queue: "คิวหมายเลข",
                room: "ห้องตรวจ",
                wait: "กรุณารอเรียกคิว",
                male: "ห้องชาย",
                female: "ห้องหญิง",
                treatment: "Treatment",
                center: "ศูนย์บริการเวชศาสตร์ป้องกัน",
                date: "วันที่"
            },
            en: {
                queue: "QUEUE",
                room: "Room",
                wait: "Please wait for your queue to be called.",
                male: "Male Room",
                female: "Female Room",
                treatment: "Treatment Room",
                center: "Preventive Medicine Service Center",
                date: "Date"
            },
            my: {
                queue: "စာရင်း",
                room: "ခန်း",
                wait: "ကျေးဇူးပြုပြီး စာရင်းခေါ်ရန်စောင့်ပါ",
                male: "ယောကျ်ားခန်း",
                female: "မိန်းမခန်း",
                treatment: "ကုသခန်း",
                center: "ကာကွယ်ရေးဆေးဝါးဌာန",
                date: "နေ့"
            },
            zh: {
                queue: "号码",
                room: "房间",
                wait: "请等待叫号",
                male: "男病房",
                female: "女病房",
                treatment: "治疗室",
                center: "预防医学服务中心",
                date: "日期"
            }
        };

        const t = translations[lang];
        const today = new Date().toLocaleDateString();

        // ตั้งค่า checked เฉพาะห้องที่กำหนด
        const isMale = roomNumber === "2";
        const isTreatment = roomNumber === "4";
        const isFemale = roomNumber === "8";

        // ส่วน checkbox บน (ให้ห้องตรวจ)
        const testCheckboxes = `
            <div style="font-size: 1.2em; display: inline-block; margin-top: 10px;">
                <label style="display: inline-block; margin-right: 15px;">
                    <input type="checkbox" name="test" value="HBsAg" style="transform: scale(1.5); margin-right: 5px;"> HBsAg
                </label>
                <label style="display: inline-block; margin-right: 15px;">
                    <input type="checkbox" name="test" value="Anti-HCV" style="transform: scale(1.5); margin-right: 5px;"> Anti-HCV
                </label>
                <label style="display: inline-block;">
                    <input type="checkbox" name="test" value="None" style="transform: scale(1.5); margin-right: 5px;"> None
                </label>
            </div>`;

        // ส่วน checkbox ล่าง (ให้คนไข้) + ตั้งค่า checked ตามเงื่อนไข
        const roomCheckboxes = `
            <div style="font-size: 1.2em; display: inline-block; margin-top: 1px;">
                <label style="display: inline-block; margin-right: 15px;">
                    <input type="checkbox" style="transform: scale(1.5); margin-right: 5px;" ${isMale ? "checked" : ""}> ${t.male}
                </label>
                <label style="display: inline-block; margin-right: 15px;">
                    <input type="checkbox" style="transform: scale(1.5); margin-right: 5px;" ${isFemale ? "checked" : ""}> ${t.female}
                </label>
                <label style="display: inline-block;">
                    <input type="checkbox" style="transform: scale(1.5); margin-right: 5px;" ${isTreatment ? "checked" : ""}> ${t.treatment}
                </label>
            </div>`;

        const printContent = `
        <!-- ส่วนบน: ให้ห้องตรวจ -->
        <div style="text-align: center; font-family: Arial, sans-serif; padding: 10px; width: 370px; margin: auto;">
            <h1 style="font-size: 2.5em; margin: 0; font-weight: bold; border: 3px solid black; padding: 10px; display: inline-block;">
                ${t.queue} ${queueNumber}
            </h1>
            <p style="font-size: 1.8em; margin: 10px 0;">${t.room}: ${roomNumber}</p>
            <b><span style="font-size: 1em;">${t.wait}</span></b>
            <p style="font-size: 1.2em; margin: 5px 0;">${t.date}: ${today}</p>
            ${testCheckboxes}
        </div>

        <!-- ปุ่มควบคุม -->
        <div class="print-controls">
            <button class="btn btn-primary" onclick="window.print();">Print</button>
            <button class="btn btn-danger" onclick="window.close();">Close Window</button>
        </div>

        <!-- ส่วนล่าง: ให้คนไข้ -->
        <div style="page-break-before: always;"></div>
        <div style="text-align: center; font-family: Arial, sans-serif; padding: 5px; width: 370px; margin: auto;">
            <p style="font-size: 1.0em; font-weight: bold;">${t.center}</p>
            <h1 style="font-size: 2.5em; border: 3px solid black; padding: 10px; display: inline-block; margin: auto;">
                ${t.queue} ${queueNumber}
            </h1>
            <p style="font-size: 0.8em; margin: 0px 0;">Line: @352bavoo | ${t.date}: ${today}</p>
            <p style="font-size: 1.8em;">${t.room}: ${roomNumber}</p>
            ${roomCheckboxes}
            <br><b><span style="font-size: 1.0em; margin: 1px;">${t.wait}</span></b>
        </div>`;

        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <html>
            <head>
                <title>Print</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                <style>
                    body { margin: 0; padding: 0; text-align: center; font-family: Arial, sans-serif; }
                    h1 { margin: 0; font-weight: bold; }
                    p { margin: 5px 0; font-size: 1.2em; }
                    @page {
                        size: 8in 10in;
                        margin: 10mm;
                    }
                    @media print {
                        .print-controls {
                            display: none;
                        }
                    }
                    .print-controls {
                        margin-top: 20px;
                    }
                    .print-controls button {
                        font-size: 1.2em;
                        padding: 10px 20px;
                        margin: 5px;
                    }
                </style>
            </head>
            <body>
                ${printContent}
            </body>
            </html>
        `);
        printWindow.document.close();
    });
});

</script>
