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

    // ตรวจสอบว่าเป็นเจ้าของกิจกรรมจริงไหม
    creatorcheck($event['creator_id'], '/events');

    try {
        // --- 1. ดึงข้อมูลรูปภาพทั้งหมดมาเตรียมไว้ ---
        $images = getFullImagesByEventId((int)$id);

        // --- 2. วนลบรูปภาพบน Cloudinary ก่อน ---
        if (!empty($images)) {
            foreach ($images as $image) {
                if (!empty($image['delete_hash'])) {
                    deleteFromCloudinary($image['delete_hash']);
                }
            }
        }

        // --- 3. ลบข้อมูลรูปภาพออกจากตาราง image_storage ใน Database ---
        deleteImagesByEventId((int)$id);

        // --- 4. ลบตัวกิจกรรมออกจากตาราง events ---
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