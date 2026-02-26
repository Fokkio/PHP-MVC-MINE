<?php
declare(strict_types=1);

$method = $context['method'];
$eventId = (int)$context['id']; 

if (empty($_SESSION['user_id'])) {
    header('Location: /login');
    exit;
}

$userId = (int)$_SESSION['user_id'];

if ($method === 'GET' || $method === 'POST') {
    $event = getEventById($eventId);
    if (!$event) {
        notFound();
    }

    try {
        $success = joinEvent($userId, $eventId);

        if ($success) {
            header('Location: /events/' . $eventId . '/detail');
        } else {
            die("เกิดข้อผิดพลาดในการเข้าร่วมกิจกรรม");
        }   
    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }
    exit;
} else {
    notFound();
}