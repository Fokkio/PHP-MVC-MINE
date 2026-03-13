<?php
declare(strict_types=1);
$method = $context['method'];
$id     = $context['id'];
if (empty($_SESSION['user_id'])) {
    header('Location: /login');
    exit;
}
if ($method === 'GET') {
    $event = getEventById((int)$id);

    if (!$event) {
        notFound();
    }
    creatorcheck($event['creator_id'], '/events');
    try {
        $images = getFullImagesByEventId((int)$id);
        if (!empty($images)) {
            foreach ($images as $image) {
                if (!empty($image['delete_hash'])) {
                    deleteFromCloudinary($image['delete_hash']);
                }
            }
        }
        deleteImagesByEventId((int)$id);
        $success = deleteEvent((int)$id);
        if ($success) {
            header('Location: /events/my-event');
        } else {
            die("ไม่สามารถลบกิจกรรมได้ กรุณาลองใหม่");
        }
    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }
    exit;

} else {
    notFound();
}