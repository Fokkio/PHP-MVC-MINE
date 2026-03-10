<?php
declare(strict_types=1);

$method = $context['method'];
$joinEventId = (int)$context['id']; 

if (empty($_SESSION['user_id'])) {
    header('Location: /users/login');
    exit;
}

if ($method === 'GET' || $method === 'POST') {
    try {
        $success = leaveEvent($joinEventId);

        if ($success) {
            header('Location: /join_event/my-registers');
            exit;
        } else {
            die("ไม่สามารถยกเลิกการเข้าร่วมกิจกรรมได้");
        }
    } catch (Exception $e) {
        die("เกิดข้อผิดพลาด: " . $e->getMessage());
    }
} else {
    notFound();
}