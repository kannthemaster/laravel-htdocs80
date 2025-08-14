<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terminal Display</title>
    <!-- Bootstrap CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <!-- <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@100..900&family=Sriracha&display=swap" rel="stylesheet"> -->
     <!-- jQuery -->
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
    
    <style>
       
       @font-face {
    font-family: 'Noto Sans Thai';
    src: url('assets/fonts/NotoSansThai-Regular.woff2') format('woff2'),
         url('assets/fonts/NotoSansThai-Regular.woff') format('woff');
  }
  @font-face {
    font-family: 'Sriracha';
    src: url('assets/fonts/Sriracha-Regular.woff2') format('woff2'),
         url('assets/fonts/Sriracha-Regular.woff') format('woff');
  }
        body {
        
            font-family: "Noto Sans Thai", sans-serif;
            font-optical-sizing: auto;
            font-weight: <weight>;
            font-style: normal;
            font-variation-settings:
            "wdth" 100;

        }
        h1 {
            font-size: 3em; /* เพิ่มขนาดให้ใหญ่ขึ้น */
            font-weight: 700; /* ทำให้หนาขึ้น */
            margin-bottom: 20px;
        }
        h3 {
            font-size: 2em; /* เพิ่มขนาดให้ใหญ่ขึ้น */
            font-weight: 400;
        }

        body {
            background: linear-gradient(135deg, #1e3c72,rgb(2, 27, 71));
            background-color: #323232;
            color: #fff;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            flex-direction: row; /* จัดให้ห้องอยู่ในแนวนอน */
            justify-content: flex-start;
            align-items: flex-start;
            flex-wrap: nowrap; /* ป้องกันการย้ายห้อง */
            overflow-x: auto; /* ให้เลื่อนซ้ายขวาได้ถ้าห้องมีมาก */
        }
        #clock-container {
            font-size: 4rem;
            font-weight: bold;
            letter-spacing: 0.1em;
            text-shadow: 0 4px 8px rgba(0, 0, 0, 0.2), 0 0 15px rgba(255, 255, 255, 0.4);
            border: 4px solid rgba(255, 255, 255, 0.5);
            border-radius: 15px;
            padding: 20px 40px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            position: relative;
            overflow: hidden;
            animation: glow 2.5s infinite alternate;
        }
        #date-container {
            font-size: 2rem;
            font-weight: bold;
            letter-spacing: 0.1em;
            text-shadow: 0 4px 8px rgba(0, 0, 0, 0.2), 0 0 15px rgba(255, 255, 255, 0.4);
            border-radius: 15px;
            padding: 20px 40px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            
            position: relative;
            
        }
        .top-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            gap: 20px;
        }
        .qr-code {
            width: 400px;
            height: 150px;
            
            border-radius: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            
        }
        .qr-code img {
            width: 100%;
            height: 100%;
            border-radius: 10px;
        }
        /* แอนิเมชันแสงเรือง */
        @keyframes glow {
            from {
                box-shadow: 0 8px 10px rgba(0, 0, 0, 0.3), 0 0 12px rgba(255, 255, 255, 0.5);
            }
            to {
                box-shadow: 0 8px 10px rgba(0, 0, 0, 0.3), 0 0 22px rgba(255, 255, 255, 0.8);
            }
        }
        /* แอนิเมชันเปลี่ยนตัวเลข */
        .digit {
            display: inline-block;
            transform: scale(1);
            transition: transform 0.5s ease, color 0.5s ease;
        }
        .digit.update {
            transform: scale(1.0);
            color: #ffcc00;
        }
        .room {
            width: 300px; /* ขนาดห้องคงที่ */
            margin: 10px;
            padding: 20px;
            border: 2px solid #fff;
            border-radius: 10px;
            position: relative;
            color: #fff;
            box-sizing: border-box;
            height: auto; /* ขนาดห้องแนวตั้ง */
        }

        .room h2 {
            text-align: center;
        }

        .queue {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
            padding: 10px;
            border-radius: 5px;
            background-color: #191919;
            color: #fff;
        }

        .queue.waiting {
    background-color:rgb(194, 75, 75); /* สีแดง */
    color: #fff;
    animation: blink-red 1s infinite; /* กระพริบสีแดง */
}

.queue.called {
    background-color:rgb(131, 131, 131); /* สีเขียว */
    color: #000; /* ตัวหนังสือสีดำ */
    animation: blink-green 1s infinite; /* กระพริบสีเขียว */
}

/* การกระพริบสีแดง */
@keyframes blink-red {
    50% {
        background-color:rgb(195, 76, 76);
    }
}

/* การกระพริบสีเขียว */
@keyframes blink-green {
    50% {
        background-color:rgb(121, 121, 121);
    }
}


        .queue.empty {
            background-color: #191919;
            text-align: center;
            width: 100%;
            padding: 10px;
            font-style: italic;
            font-size: 14px;
        }

        @keyframes blink {
            50% {
                background-color: #ff8888;
            }
        }

        /* เพิ่มสไตล์การเลื่อนห้อง */
        @media (max-width: 767px) {
            .room {
                width: 100%;
            }
        }

/* สำหรับแสดงห้องในแนวนอน */
#room-container {
    background-color: #191919;
    display: flex;
    flex-direction: row;
    flex-wrap: wrap; /* เปลี่ยนจาก nowrap เป็น wrap เพื่อให้ขึ้นบรรทัดใหม่ได้ */
    justify-content: center; /* 🔸 จัดตำแหน่งลูกตรงกลางแนวนอน */
    border-radius: 30px;
    overflow-x: auto;
    padding: 20px;
    width: 100%;
    gap: 20px; /* 🔸 ระยะห่างระหว่างกล่องห้อง */
}
.room {
    
    border-radius: 12px;
    padding: 16px;
    min-width: 280px;
    max-width: 320px;
    text-align: center;
    box-shadow: 0 0 10px rgba(255, 255, 255, 0.05);
}

/* สำหรับกล่องห้อง */
#room-box {
    display: flex;
    flex-direction: column;
    align-items: center; /* ✅ จัดทุกอย่างในห้องไว้ตรงกลางแนวนอน */
    margin: 0 10px;
    min-width: 220px; /* ป้องกันห้องหด */
    background: #2a2a2a;
    border-radius: 20px;
    padding: 16px;
    color: white;
        justify-content: flex-start; /* หรือใช้ space-around ถ้าอยากให้เว้นระยะเท่าๆ กัน */
}
/* ซ่อน Scroll Bar */
.queue-list {
    max-height: calc(4 * 80px); /* จำกัดความสูงสำหรับ 4 คิว */
    overflow-y: scroll; /* เปิดเลื่อนแนวตั้ง */
    display: flex;
    flex-direction: column;
    gap: 0px; /* ระยะห่างระหว่างคิว */
    scrollbar-width: none; /* ซ่อน Scroll Bar สำหรับ Firefox */
}

.queue-list::-webkit-scrollbar {
    display: none; /* ซ่อน Scroll Bar สำหรับ Chrome, Safari และ Edge */
}

/* คิวแต่ละรายการ */
.queue-item {
    background-color: #ff4d4d; /* สีพื้นหลัง */
    color: #fff; /* สีข้อความ */
    border-radius: 5px;
    text-align: center;
    line-height: 80px; /* จัดข้อความให้อยู่ตรงกลางแนวตั้ง */
    height: 80px; /* ความสูงของแต่ละคิว */
    font-size: 16px; /* ขนาดตัวอักษร */
}

        .scrolling-text {
            width: 100%;
            white-space: nowrap;
            overflow: hidden;
            box-sizing: border-box;
            
            color: #fff;
            font-size: 3rem;
            font-weight: bold;
            padding: 10px 0;
            position: relative;
        }
        .scrolling-text span {
            display: inline-block;
            padding-left: 100%; /* เริ่มต้นนอกหน้าจอ */
            animation: scroll 80s linear infinite; /* ทำแอนิเมชันเลื่อน */
        }
        @keyframes scroll {
            from {
                transform: translateX(0); /* เริ่มต้นที่ตำแหน่งปัจจุบัน */
            }
            to {
                transform: translateX(-100%); /* เลื่อนไปทางซ้ายจนพ้นจอ */
            }
        }
        .header-container {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 20px; /* ระยะห่างระหว่างโลโก้กับข้อความ */
}

.logo-container img {
    height: 95px; /* ปรับขนาดโลโก้ */
    filter: drop-shadow(0px 0px 0px rgba(255, 255, 255, 0.8)); /* แสงสีเหลือง */
}

.text-container {
    text-align: left;
}

    </style>
    
</head>
<body>
<div class="container-fluid text-center mt-4">
<div class="header-container">
        <div class="logo-container">
            <img src="https://192.168.1.250/public/queue/assets/logo-กรม-2566.png" alt="Logo 1">
            <img src="https://192.168.1.250/public/queue/assets/50 ปี-01.png" alt="Logo 2">
        </div>
        <div class="text-container">
            <h3>ศูนย์บริการเวชศาสตร์ป้องกัน</h3>
            <h3>Preventive Medicine Service Center</h3>
            <h4>สำนักงานป้องกันควบคุมโรคที่ 1 เชียงใหม่ | Office of Disease Prevention and Control 1 Chiang Mai</h4>
        </div>
    </div><!-- <div class="text-center mb-3">
    <button class="btn btn-secondary" id="fullscreen-btn">Toggle Fullscreen</button>
</div> -->
    <div class="container-fluid text-center mt-4" id="room-container">
        <!-- จะมีการอัปเดตข้อมูลคิวในนี้ -->
    </div>
    <br>
       
        <!-- ส่วนแสดงนาฬิกาและ QR Code -->
        <div class="top-section">
            <div id="clock-container">00:00:00</div>
            <div id="date-container">January 1, 2024</div>
            <div class="qr-code">
                <img src="https://192.168.1.250/public/queue/assets/@352bavoo.png" alt="QR Code">
            </div>
        </div>
        
        <div class="scrolling-text">
        <span>
        ศูนย์บริการเวชศาสตร์ป้องกัน | Preventive Medicine Service Center | สำนักงานป้องกันควบคุมโรคที่ 1 เชียงใหม่ | Office of Disease Prevention and Control 1 Chiang Mai | โทรศัพท์/tel: 053-140774 ถึง/to 6 | Line: @352bavoo
        </span>
    </div>
    </div>
    <div id="queue-modal" class="modal-overlay" style="display: none;">
    <div class="modal-content"> 
        <div class="logo-container">
            <img src="https://192.168.1.250/public/queue/assets/logo-กรม-2566.png" alt="Logo 1">
            <img src="https://192.168.1.250/public/queue/assets/50 ปี-01.png" alt="Logo 2"> 
</div>
    <h1 id="modal-queue-number" style="color: green;">Queue 1</h1>
        <h2 id="modal-room-number">Room 1</h2>
    </div>
</div>
<style>
/* สไตล์พื้นหลังจาง */
.modal-overlay {
    display: none; /* ซ่อนโดยเริ่มต้น */
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    z-index: 1000;
    display: flex;
    justify-content: center;
    align-items: center;
    animation: fadeIn 1.5s ease-in-out;
}



/* สไตล์เนื้อหา */
.modal-content {
    background: white;
    border-radius: 15px;
    padding: 100px 50px;
    text-align: center;
    color: #000;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.5);
}

.modal-content h1 {
    font-size: 10rem;
    font-weight: bold;
    margin-bottom: 20px;
}

.modal-content h2 {
    font-size: 6rem;
    font-weight: normal;
}

/* แอนิเมชัน */
@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}
@keyframes fadeOut {
    from {
        opacity: 1;
    }
    to {
        opacity: 0;
    }
}
@keyframes blink {
    0% { color: green; } /* เขียวอ่อน */
    25% { color: green; } /* เขียวอ่อน */
    50% { color: rgb(92, 245, 21); } /* เขียวเข้ม */
    75% { color: green; } 
    100% { color: green; } /* เขียวอ่อน */
}
#modal-room-number {
    color: red;
}
#modal-queue-number {
    animation: blink 1.0s infinite; /* กระพริบทุก 1 วินาที */
}
#queue-modal {
    display: none; /* ซ่อนเริ่มต้น */
    opacity: 0;
    transition: opacity 1s ease-out;
}

</style>

<script>
    // // เชื่อมต่อกับ WebSocket Server
    // const socket = new WebSocket('ws://localhost:8080');

    // // เมื่อเชื่อมต่อสำเร็จ
    // socket.onopen = function(event) {
    //     console.log("WebSocket connection established");
    // };

    // // เมื่อรับข้อความจาก WebSocket
    // socket.onmessage = function(event) {
    //     const data = JSON.parse(event.data);
    //     console.log("Received data from WebSocket:", data);
        
    //     // อัปเดตข้อมูลห้องและคิวจากข้อมูลที่ได้รับ
    //     updateRooms(data);
        
    //     // หากมีการเรียกคิวใหม่, เล่นเสียงแจ้งเตือน
    //     data.forEach(room => {
    //         room.forEach(queue => {
    //             if (queue.status === "called" && queue.call_count > 0) {
    //                 playSound(queue.queue_number, queue.room_number);
    //             }
    //         });
    //     });
    // };

    // // เมื่อเกิดข้อผิดพลาดในการเชื่อมต่อ
    // socket.onerror = function(error) {
    //     console.error("WebSocket Error:", error);
    // };

    // // เมื่อการเชื่อมต่อถูกปิด
    // socket.onclose = function(event) {
    //     console.log("WebSocket connection closed", event);
    // };

    function showQueueModal(queueNumber, roomNumber) {
        const modal = document.getElementById("queue-modal");
        const queueDisplay = document.getElementById("modal-queue-number");
        const roomDisplay = document.getElementById("modal-room-number");

        queueDisplay.textContent = `Queue ${queueNumber}`;
        roomDisplay.textContent = `Room/ห้อง ${roomNumber}`;

        // แสดง modal และทำให้ opacity เป็น 1
        modal.style.display = "flex";
    }

    let soundQueue = []; // คิวสำหรับเก็บคำสั่งเสียง
    let lastCallCounts = {}; // เก็บ call_count ล่าสุดสำหรับแต่ละคิว
    let isSoundPlaying = false; // ตรวจสอบว่าเสียงกำลังเล่นอยู่หรือไม่
    let voices = [];

    speechSynthesis.onvoiceschanged = function() {
        voices = speechSynthesis.getVoices();
    };

    // ฟังก์ชันเล่นเสียง
    function playSound(queueNumber, roomNumber) {
        soundQueue.push({ queueNumber, roomNumber });
        processSoundQueue();
    }

    function processSoundQueue() {
        if (isSoundPlaying || soundQueue.length === 0) return; // หยุดถ้าเสียงกำลังเล่นอยู่
        isSoundPlaying = true; // ตั้งค่าสถานะว่าเสียงกำลังเล่น

        const { queueNumber, roomNumber } = soundQueue.shift(); // ดึงคิวออกจากคิวลำดับแรก

        const textThai = `ผู้รับบริการหมายเลข, ${queueNumber}, หมายเลข, ${queueNumber}, เข้ารับบริการที่ห้องตรวจหมายเลข, ${roomNumber}`;
        // const textThai = `ผู้ฮับบริก่ารหม่ายเลข, ${queueNumber}, หม่ายเลข, ${queueNumber}, เค่าฮับบริก่ารตี้ห้องตรวจหม่ายเลข, ${roomNumber}`;
        const textEnglish = `Queue number, ${queueNumber}, Queue number, ${queueNumber}, please proceed to room number, ${roomNumber}.`;

        // ฟังก์ชันพูดข้อความ พร้อมกำหนดเสียง
        function speak(text, lang, rate, pitch, voiceName) {
            const msg = new SpeechSynthesisUtterance(text);
            const voices = speechSynthesis.getVoices();
            const selectedVoice = voices.find(voice => voice.name.includes(voiceName));
            
            if (selectedVoice) {
                msg.voice = selectedVoice; // กำหนดเสียงที่เลือก
            } else {
                console.warn(`ไม่พบเสียง: ${voiceName}`);
            }

            msg.lang = lang;
            msg.rate = rate;
            msg.pitch = pitch;

            return msg;
        }

        // เล่นเสียงแจ้งเตือน
        const notificationSound = new Audio('notification-sound.mp3');
        notificationSound.play().catch((error) => {
            console.log("ไม่สามารถเล่นเสียงแจ้งเตือน: ", error);
        });

        // แสดง Modal เมื่อเริ่มเล่นเสียงแจ้งเตือน
        showQueueModal(queueNumber, roomNumber);

        // notificationSound.onended = function () {
        //     // พูดข้อความภาษาไทย
        //     const thaiMsg = speak(textThai, "th-TH", 1.0, 1.1, "Microsoft Pattara - Thai (Thailand))");
        //     thaiMsg.onend = function () {
        //         // พูดข้อความภาษาอังกฤษหลังจากข้อความภาษาไทยจบ
        //         const englishMsg = speak(textEnglish, "en-US", 0.9, 1.2, "Microsoft Zira - English (United States)");
        //         englishMsg.onend = function () {
        //             isSoundPlaying = false; // ปลดสถานะเสียง
        //             processSoundQueue(); // ดำเนินการเล่นเสียงในคิวถัดไป
        //         };
        //         speechSynthesis.speak(englishMsg);
        //     };
        //     speechSynthesis.speak(thaiMsg);
        // };
        notificationSound.onended = function () {
            // พูดข้อความภาษาไทย
            const thaiMsg = speak(textThai, "th-TH", 1.0, 1.1, "Microsoft Premwadee Online (Natural) - Thai (Thailand)");
            thaiMsg.onend = function () {
                // พูดข้อความภาษาอังกฤษหลังจากข้อความภาษาไทยจบ
                const englishMsg = speak(textEnglish, "en-US", 0.9, 1.2, "Microsoft Aria Online (Natural) - English (United States)");
                englishMsg.onend = function () {
                    isSoundPlaying = false; // ปลดสถานะเสียง
                    processSoundQueue(); // ดำเนินการเล่นเสียงในคิวถัดไป
                };
                speechSynthesis.speak(englishMsg);
            };
            speechSynthesis.speak(thaiMsg);
        };
    }

    function hideQueueModal() {
        const modal = document.getElementById("queue-modal");

        // เช็กก่อนว่า modal ยังอยู่ไหม
        if (!modal || modal.style.display === "none") return;

        // เริ่ม fade out
        modal.style.transition = "opacity 1s ease-out";
        modal.style.opacity = "0";

        // รอให้ fade out เสร็จ ค่อยซ่อน modal
        setTimeout(() => {
            modal.style.display = "none";
        }, 1000);
    }

    function showQueueModal(queueNumber, roomNumber) {
        const modal = document.getElementById("queue-modal");
        const queueDisplay = document.getElementById("modal-queue-number");
        const roomDisplay = document.getElementById("modal-room-number");

        queueDisplay.textContent = `Queue ${queueNumber}`;
        roomDisplay.textContent = `Room/ห้อง ${roomNumber}`;
        // แสดง modal และทำให้ opacity เป็น 1
        modal.style.display = "flex";
        modal.style.opacity = "1";
        modal.style.transition = "opacity 1.5s ease-in";

        // ตั้งเวลาให้ modal fade out หลัง 15 วินาที
        setTimeout(hideQueueModal, 15500);
    }

    speechSynthesis.onvoiceschanged = function() {
        console.log(speechSynthesis.getVoices());
    };

    let isFirstLoad = true; // ตรวจสอบว่าหน้าโหลดครั้งแรกหรือไม่
    let lastPlayedQueue = {}; // เก็บ queue_number ที่เล่นเสียงล่าสุด
    let manualCallQueues = {}; // เก็บคิวที่ถูกเรียกด้วยปุ่ม
    let initialCallCounts = {}; // เพิ่มตัวแปรใหม่เพื่อติดตาม call_count เริ่มต้น

if (!document.getElementById("blinking-style")) {
    $("head").append(`<style id="blinking-style">
    .blinking-calling {
    font-size: 2.5rem;        /* ตัวอักษรใหญ่ขึ้น */
    padding: 1rem 2rem;       /* ขยายพื้นที่ภายใน */
    border-radius: 12px;      /* มุมโค้งมากขึ้น */
    border-width: 5px;        /* ขอบหนาขึ้น */
    box-shadow: 0 0 20px rgba(0, 255, 0, 0.8); /* เงาใหญ่ขึ้น */
    min-width: 200px;         /* กำหนดความกว้างขั้นต่ำ */
    min-height: 150px;         /* ความสูงขั้นต่ำ */
    animation: blinkFlash 1.5s infinite;
    color: #fff;
    background-color: #00c853;
    font-weight: bold;
    border-radius: 12px;
    text-align: center;
    margin-bottom: 8px;
    border: 5px solid #00c853;
    display: flex;
flex-direction: column;
justify-content: center;
align-items: center;
    }

    @keyframes blinkFlash {
        0% {
            background-color: #00c853;
            border-color: #00c853;
            opacity: 1;
        }
        50% {
            background-color: #ffffff;
            color:rgb(255, 0, 0);
            border-color:rgb(255, 0, 0);
            box-shadow: 0 0 20px rgba(255, 0, 0, 0.9);
            opacity: 1;
        }
        100% {
            background-color: #00c853;
            border-color: #00c853;
            opacity: 1;
        }
    }
    </style>`);
}



    // ฟังก์ชันอัปเดตห้องตรวจ
    function updateRooms(data) {
    const roomContainer = $("#room-container");
    const isScrolledToBottom = roomContainer.scrollTop() + roomContainer.innerHeight() >= roomContainer[0]?.scrollHeight;

    roomContainer.empty();
    const roomsToShow = [2, 3, 4, 6, 8];

    roomsToShow.forEach(roomNumber => {
        const roomDiv = $(`
            <div class="room" data-room-number="${roomNumber}">
                <h2>Room ${roomNumber} <br> ห้องตรวจ ${roomNumber}</h2>
                <div class="queue-list"></div>
            </div>
        `);

        const queueList = roomDiv.find(".queue-list");

        if (data[roomNumber] && data[roomNumber].length > 0) {
            // 🔽 แยกคิว is_calling ออกมาก่อน แล้วค่อยตามด้วยคิวอื่น
            const callingQueues = data[roomNumber].filter(q => q.is_calling == 1);
            const otherQueues = data[roomNumber].filter(q => q.is_calling != 1);

            const allQueues = [...callingQueues, ...otherQueues];

            allQueues.forEach(queue => {
                let queueClass = "queue";
                let statusText = "";

                if (queue.is_calling == 1) {
                    queueClass += " blinking-calling";
                    statusText = "Calling";
                } else if (queue.status === "called") {
                    queueClass += " called";
                    statusText = "Called";
                } else if (queue.status === "waiting") {
                    queueClass += " waiting";
                    statusText = "Waiting";
                }

                const queueDiv = $(`
                    <div class="${queueClass}" 
                        data-queue-number="${queue.queue_number}" 
                        data-call-count="${queue.call_count}">
                        <span>Queue ${queue.queue_number}</span>
                        <span>${statusText}</span>
                    </div>
                `);

                queueList.append(queueDiv);

                // ✅ บันทึกค่า call_count เริ่มต้นเมื่อโหลดหน้าครั้งแรก
                if (isFirstLoad) {
                    if (!initialCallCounts[roomNumber]) {
                        initialCallCounts[roomNumber] = {};
                    }
                    initialCallCounts[roomNumber][queue.queue_number] = queue.call_count;
                }

                // ✅ เล่นเสียงเฉพาะคิวที่ถูกเรียกใหม่ (ไม่นับ call_count ก่อนหน้า)
                if (!isFirstLoad && queue.status === "called" && queue.call_count > 0) {
                    if (lastCallCounts[roomNumber]?.[queue.queue_number] !== queue.call_count) {
                        if (queue.call_count > (initialCallCounts[roomNumber]?.[queue.queue_number] || 0)) {
                            playSound(queue.queue_number, roomNumber);
                        }

                        lastCallCounts[roomNumber] = lastCallCounts[roomNumber] || {};
                        lastCallCounts[roomNumber][queue.queue_number] = queue.call_count;
                    }
                }
            });
        } else {
            queueList.append('<div class="queue empty" style="display: flex; align-items: center; justify-content: center; height: 100%;">- คิวว่าง -</div>');
        }

        roomContainer.append(roomDiv);
    });

    isFirstLoad = false;
}


    // ฟังก์ชันโหลดข้อมูลคิว
    function checkUpdates() {
        $.get("fetch_queues_terminal.php", function (response) {
            const data = JSON.parse(response);
            updateRooms(data);

            // ตรวจสอบให้แน่ใจว่ามีการเรียก modal เฉพาะคิวที่ต้องการ
        });
    }

    // เรียกใช้งานครั้งแรกและทุกๆ 3 วินาที
    checkUpdates();
    setInterval(checkUpdates, 3000);

    document.addEventListener('click', function () {
        const elem = document.documentElement; // เลือกองค์ประกอบ <html> ทั้งหน้า

        if (!document.fullscreenElement) {
            // เปิดโหมด Fullscreen
            elem.requestFullscreen().catch(err => {
                alert(`Error attempting to enable full-screen mode: ${err.message} (${err.name})`);
            });
        }
    });

    // ฟังก์ชันแสดงเวลา
    function updateClock() {
        const clockContainer = document.getElementById("clock-container");
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        const timeString = `${hours}:${minutes}:${seconds}`;

        // อัปเดตข้อความพร้อมแอนิเมชัน
        const digits = clockContainer.querySelectorAll(".digit");
        if (digits.length) {
            const newDigits = timeString.split("");
            digits.forEach((digit, index) => {
                if (digit.textContent !== newDigits[index]) {
                    digit.textContent = newDigits[index];
                    digit.classList.add("update");
                    setTimeout(() => digit.classList.remove("update"), 300); // ลบคลาสหลังแอนิเมชัน
                }
            });
        } else {
            // สร้างตัวเลขพร้อมแท็ก .digit
            clockContainer.innerHTML = timeString
                .split("")
                .map(char => `<span class="digit">${char}</span>`)
                .join("");
        }
    }

    // อัปเดตเวลาทุก 1 วินาที
    setInterval(updateClock, 1000);

    function updateDate() {
    const dateContainer = document.getElementById("date-container");
    const now = new Date();

    // รับปี ค.ศ. และแปลงเดือนเป็นชื่อเดือนในภาษาอังกฤษ
    const year = now.getFullYear();
    const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    const month = monthNames[now.getMonth()]; // รับชื่อเดือนเป็นภาษาอังกฤษ
    const date = String(now.getDate()).padStart(2, '0'); // รับวันที่และทำให้มี 2 หลัก

    const dateString = `${date} ${month} ${year}`; // รูปแบบวันที่: "March 31, 2025"

    dateContainer.textContent = dateString;  // อัปเดตวันที่ใน container
}

// เรียกอัปเดตวันที่ทันทีเมื่อหน้าโหลด
updateDate();

// อัปเดตวันที่ทุกวัน
setInterval(updateDate, 86400000);

</script>

</body>
</html>
