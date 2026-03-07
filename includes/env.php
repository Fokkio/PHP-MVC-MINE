<?php
    // 1. ฟังก์ชันสำหรับโหลดไฟล์ .env
function loadEnv($path) {
    if (!file_exists($path)) return;
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line) || strpos($line, '#') === 0) continue;
        if (strpos($line, '=') !== false) {
            list($name, $value) = explode('=', $line, 2);
            $_ENV[trim($name)] = trim($value);
        }
    }
}

// 2. เรียกใช้งานโหลดไฟล์ .env
// ถ้า .env อยู่ข้างนอกโฟลเดอร์ includes ให้ถอยหลังกลับไป 1 Step
$envPath = __DIR__ . '/../.env'; // ปรับ Path ตามที่ไฟล์อยู่จริง
if (!file_exists($envPath)) {
    die("<h1>หาไฟล์ .env ไม่เจอที่: $envPath</h1>");
}

loadEnv($envPath);
