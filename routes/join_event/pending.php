<?php
declare(strict_types=1);

$joinEventId = (int)$context['id'];
$eventId = (int)($_GET['event_id'] ?? 0);

if (empty($_SESSION['user_id'])) {
    header('Location: /login');
    exit;
}

try {
    $checkin = getCheckInStatusByJoinEventId($joinEventId);
    $success = updateJoinStatus($joinEventId, JoinStatus::PENDING->value);

    if ($success && $checkin['checkin_status']) {
        header("Location: /events/$eventId/participants");
        exit;
    } else {
        die("ไม่สามารถให้รอการลงทะเบียนได้");
    }
} catch (Exception $e) {
    die("เกิดข้อผิดพลาด: " . $e->getMessage());
}