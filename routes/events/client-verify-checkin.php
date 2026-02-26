<?php
declare(strict_types=1);

$method = $context['method'] ?? 'GET';

if ($method !== 'GET' || !isset($_GET['join_event_id'])) {
    header('Location: /events');
    exit;
}

$response = getCheckInStatusByJoinEventId($_GET['join_event_id']);

header('Content-Type: application/json');
if ($response['checkin_status']) {
    echo json_encode([
        "status" => "FOUND",
        "message" => "Check-in สำเร็จแล้ว"
    ]);
    return;
}

echo json_encode([
    "status" => "WAITING"
]);