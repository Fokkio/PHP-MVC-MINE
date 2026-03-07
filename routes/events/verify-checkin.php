<?php
declare(strict_types=1);

$method = $context['method'] ?? 'POST';
$eventId = (int)($context['id'] ?? 0);

if ($method !== 'POST') {
    notFound();
}

// รับค่า OTP จากฟอร์ม
$inputOtp = $_POST['otp'] ?? '';
$getAllparticipant = getApprovedButNotCheckedInJoinEventByEventId($eventId);

if (!$getAllparticipant) {
    die("ไม่พบข้อมูลผู้เข้าร่วม");
}

// 2. คำนวณหาช่วงเวลาหมดอายุ (60 วินาที)
$ttl = 60;
$now = time();

$otpvertify = false;
$foundJoinId = null; // เพิ่มตัวแปรเพื่อเก็บ ID ที่หาเจอ

foreach ($getAllparticipant as $participant){
    $secret = $participant['totp_secret'] ?? '';

    $expectedOtp = getTOTP($secret);

    if ($inputOtp === $expectedOtp) {
        $otpvertify = true;
        $foundJoinId = $participant['join_event_id']; // เก็บค่า ID นี้ไว้ใช้ตอนอัปเดต
        break; // ถ้าเจอแล้วให้หยุดลูปทันที
    }
}

if (!$otpvertify && $foundJoinId === null) {
    echo "<script>
        alert('❌ OTP ไม่ถูกต้องหรือหมดอายุ!');
        window.location.href = '/events/{$eventId}/checkin';
    </script>";
    exit;
}

try {
    // แก้ตรงนี้: ใช้ $foundJoinId ที่เราหาเจอจากในลูป
    updateCheckInEvent($foundJoinId, true); 
    
    echo "<script>
        alert('✅ Check-in สำเร็จ!');
        window.location.href = '/events/{$eventId}/participants';
    </script>";
    exit;
} catch (Exception $e) {
    die("เกิดข้อผิดพลาดในการอัปเดตสถานะ: " . $e->getMessage());
}