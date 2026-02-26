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
        // --- 1. จัดการลบรูปภาพที่เกี่ยวข้องทั้งหมด ---
        // ใช้ฟังก์ชันที่เราสร้างไว้เพื่อดึงทั้ง path และ delete_hash
        $images = getFullImagesByEventId((int)$id);

        

        // --- 2. ลบข้อมูลรูปภาพออกจากตาราง image_storage ใน Database ---
        deleteImagesByEventId((int)$id);

        foreach ($images as $image) {
            if (!empty($image['delete_hash'])) {
                // เรียกฟังก์ชันที่คุณเขียนไว้ โดยส่ง public_id (delete_hash) เข้าไป
                deleteFromCloudinary($image['delete_hash']);
            }
        }
        // --- 3. ลบตัวกิจกรรมออกจากตาราง events ---
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