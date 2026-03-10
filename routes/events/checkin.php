<?php
declare(strict_types=1);
//ส่วนนี้แก้ให้ วนหา user ที่ approved แต่ยังไม่ check-in ถ้า otp ที่กรอกมา ตรงกับ secert ที่คำนวณมาเช็ค ของเขาก็ถือว่าผ่าน 
// รับ join_event_id จาก URL 
// ในไฟล์ checkin.php และ verify-checkin.php
$EventId = (int)($context['id'] ?? 0); // ดึง id จาก router มาใช้เป็น join_event_id
$method = $context['method']; // 'GET' หรือ 'POST'



if ($method === 'GET') {
    // สมมติว่ามีฟังก์ชัน getJoinEventById สำหรับดึงข้อมูลการเข้าร่วม
    echo "<script>alert('GET: eventId = $EventId')</script>";
    $participant = getEventById($EventId);
    if (!$participant) {
        die("ไม่พบข้อมูลผู้เข้าร่วมในระบบ");
    }
    creatorcheck($participant['creator_id'], '/events');

    // เรียกหน้า View และส่งข้อมูลไปให้
    renderView('checkin-view', [
        'title'       => 'Check-in OTP',
        'eventId'     => $participant['id']// เอาไว้ทำปุ่มกดย้อนกลับ
    ]);
} else {
    notFound();
}