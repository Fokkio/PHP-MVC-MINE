<?php
declare(strict_types=1);

$method = $context['method'];
$userId = (int)$_SESSION['user_id'];

if ($method === 'GET') {
    try {
        $joinevents = getJoinedEventsByUserId($userId); 
        renderView('my-register', [
            'title' => 'My Registered Events',
            'joinEvents' => $joinevents
        ]);

    } catch (Exception $e) {
        die("เกิดข้อผิดพลาด: " . $e->getMessage());
    }
} else {
    notFound();
}