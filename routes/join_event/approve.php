<?php
declare(strict_types=1);

$joinEventId = (int)$context['id']; 
$eventId = (int)($_GET['event_id'] ?? 0);
$maxParticipants = getMaxparticipants($eventId);
$currentParticipants = getAmountOfApprovedParticipantsByEventId($eventId);
if (empty($_SESSION['user_id'])) {
    header('Location: /login');
    exit;
}

try {
    if ($currentParticipants >= $maxParticipants) {
        echo '<script>alert("ไม่สามารถอนุมัติได้ เนื่องจากจำนวนผู้เข้าร่วมเต็มแล้ว"); window.location.href = "/events/' . $eventId . '/participants";</script>';
        exit;
    }
    $success = updateJoinStatus($joinEventId, JoinStatus::APPROVED->value);

    if ($success) {
        header("Location: /events/$eventId/participants");
        exit;
    } 
} catch (Exception $e) {
    die("เกิดข้อผิดพลาด: " . $e->getMessage());
}