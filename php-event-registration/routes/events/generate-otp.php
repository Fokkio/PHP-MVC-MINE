<?php
declare(strict_types=1);

require_once __DIR__ . '/../../includes/helper/helper.php';

// context มาจาก router
$eventId = $context['id'] ?? null;
$method  = $context['method'] ?? 'GET';
$userId = $_SESSION['user_id'] ?? null;

if (!$userId || !$eventId || $method !== 'GET') {
    notFound();
}

// ดึงข้อมูล join_event ของ user กับ event นี้ (model function ที่มีอยู่)
$participant = getByUserAndEvent($userId, (int)$eventId);

if (!$participant) {
    notFound();
}

if (($participant['join_status'] ?? '') !== JoinStatus::APPROVED->value) {
    notFound();
}

if($participant['checkin_status']) {
    notFound();
}

$joinEventId = (int)$participant['join_event_id'];

// ---------- สร้าง secret ถ้ายังไม่มี ----------
if (empty($participant['totp_secret'])) {
    $newSecret = generateSecret(16);

    // อัพเดต DB ตรงนี้ (ใช้ global $connection เหมือน model อื่นของระบบ)
    global $connection;
    $sql = "UPDATE join_event SET totp_secret = ? WHERE join_event_id = ?";
    $stmt = $connection->prepare($sql);
    if (!$stmt) {
        throw new Exception("DB prepare failed: " . $connection->error);
    }
    $stmt->bind_param('si', $newSecret, $joinEventId);
    $stmt->execute();

    // รีโหลด participant ข้อมูลใหม่
    $participant = getJoinEventById($joinEventId);
    if (!$participant) {
        throw new Exception("Failed to reload join_event after saving secret");
    }
}

// ---------- สร้าง OTP (คำนวณจาก secret + เวลา) ----------
$secret = getTotpSecret($joinEventId); 
if (empty($secret)) {
    // กรณีแปลก ๆ
    renderView('otp-viewer', [
        'error' => 'ไม่พบ secret สำหรับผู้ใช้นี้ โปรดลอง Generate อีกครั้ง',
    ]);
    exit;
}

// คำนวณ OTP จาก secret (TOTP)
$otp = getTOTP($secret);

global $OTP_DURATION;

$remainingSeconds = $OTP_DURATION - (time() - floor(time() / $OTP_DURATION) * $OTP_DURATION); // เวลาที่เหลือในรอบ 1 นาที

// เตรียมข้อมูลสำหรับ View
$data = [
    'otp'        => $otp,
    'eventId'   => $eventId,
    'remaining' => $remainingSeconds, 
    'join_event_id' => $participant['join_event_id'],
    'is_owner'   => ($participant['user_id'] === $userId),
    'participant'=> $participant,
];

renderView('otp-viewer', $data);
exit;