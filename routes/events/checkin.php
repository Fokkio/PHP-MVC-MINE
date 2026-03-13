<?php
declare(strict_types=1);

$EventId = (int)($context['id'] ?? 0); 
$method = $context['method']; 

if ($method === 'GET') {
    $participant = getEventById($EventId);
    if (!$participant) {
        die("ไม่พบข้อมูลผู้เข้าร่วมในระบบ");
    }
    creatorcheck($participant['creator_id'], '/events');

    renderView('checkin-view', [
        'title'       => 'Check-in OTP',
        'eventId'     => $participant['id']
    ]);
} else {
    notFound();
}