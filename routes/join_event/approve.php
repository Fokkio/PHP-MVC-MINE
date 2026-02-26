<?php
declare(strict_types=1);

$joinEventId = (int)$context['id']; 
$eventId = (int)($_GET['event_id'] ?? 0);
if (empty($_SESSION['user_id'])) {
    header('Location: /login');
    exit;
}

try {
    $success = updateJoinStatus($joinEventId, JoinStatus::APPROVED->value);

    if ($success) {
        header("Location: /events/$eventId/participants");
        exit;
    } 
} catch (Exception $e) {
    die("เกิดข้อผิดพลาด: " . $e->getMessage());
}