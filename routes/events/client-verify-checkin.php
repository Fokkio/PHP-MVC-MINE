<?php
declare(strict_types=1);

$method = $context['method'] ?? 'GET';

if ($method !== 'GET' || !isset($_GET['join_event_id'])) {
    header('Content-Type: application/json');
    http_response_code(400);
    echo json_encode(["status" => "ERROR"]);
    exit;
}

$response = getCheckInStatusByJoinEventId($_GET['join_event_id']);

header('Content-Type: application/json');

if (!$response || !$response['success']) {
    echo json_encode(["status" => "ERROR"]);
    exit;
}

if ($response['checkin_status']) {
    echo json_encode([
        "status" => "FOUND"
    ]);
    exit;
}

echo json_encode([
    "status" => "WAITING"
]);