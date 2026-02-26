<?php
declare(strict_types=1);

// รับ join_event_id จาก URL 
// ในไฟล์ checkin.php และ verify-checkin.php
$joinEventId = (int)($context['id'] ?? 0); // ดึง id จาก router มาใช้เป็น join_event_id
$method = $context['method']; // 'GET' หรือ 'POST'

creatorcheck(getJoinEventById($joinEventId)['event_creator_id'], '/events');

if ($method === 'GET') {
    // สมมติว่ามีฟังก์ชัน getJoinEventById สำหรับดึงข้อมูลการเข้าร่วม
    $participant = getJoinEventById($joinEventId); 

    if (!$participant) {
        die("ไม่พบข้อมูลผู้เข้าร่วมในระบบ");
    }

    // เรียกหน้า View และส่งข้อมูลไปให้
    renderView('checkin-view', [
        'title'       => 'Check-in OTP',
        'joinEventId' => $joinEventId,
        'eventId'     => $participant['event_id'], // เอาไว้ทำปุ่มกดย้อนกลับ
        'participant' => $participant
    ]);
} else {
    notFound();
}